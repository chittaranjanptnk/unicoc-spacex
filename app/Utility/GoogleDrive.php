<?php

namespace App\Utility;

use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;

class GoogleDrive
{
	protected $client;

	public function __construct()
	{
		$this->client = $this->getClient();
	}

	/**
	 * Returns an authorized API client.
	 * @return Google_Client the authorized client object
	 */
	public function getClient()
	{
		$client = null;

		try {
		    $client = new Google_Client();
		    $client->setAuthConfig(config('spacex.google_oauth_file'));
		    $client->setScopes(Google_Service_Drive::DRIVE);

		    $tokenPath = config('spacex.google_token_file');

		    if (file_exists($tokenPath)) {
		        $accessToken = json_decode(file_get_contents($tokenPath), true);
		        $client->setAccessToken($accessToken);
		    }

		    // If there is no previous token or it's expired.
		    if ($client->isAccessTokenExpired()) {
		        // Refresh the token if possible, else fetch a new one.
		        if ($client->getRefreshToken()) {
		            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
		        } else {
		            // Request authorization from the user.
		            $authUrl = $client->createAuthUrl();
		            printf("Open the following link in your browser:\n%s\n", $authUrl);
		            print 'Enter verification code: ';
		            $authCode = trim(fgets(STDIN));

		            // Exchange authorization code for an access token.
		            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
		            $client->setAccessToken($accessToken);

		            // Check to see if there was an error.
		            if (array_key_exists('error', $accessToken)) {
		                throw new Exception(join(', ', $accessToken));
		            }
		        }
		    
		        // Save the token to a file.
		        if (!file_exists(dirname($tokenPath))) {
		            mkdir(dirname($tokenPath), 0700, true);
		        }
		    
		        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
		    }
		} catch (Exception $ex) {
  			\Log::error($ex->getMessage());
		}

	    return $client;
	}

	/**
	 * Upload provided file
	 * @param  array $uploadFileData
	 * @return integer
	 */
	public function uploadFile($uploadFileData)
	{
  		try {
  			$service = new Google_Service_Drive($this->client);

			// This is uploading a file directly, with no metadata associated.
	  		$file = new Google_Service_Drive_DriveFile();
	  		$file->setName($uploadFileData['name']);

			$fileData = [
		        'data' => file_get_contents($uploadFileData['path']),
		        'mimeType' => 'application/octet-stream',
		        'uploadType' => 'media'
	  		];
	  		
	  		$result = $service->files->create($file, $fileData);

	  		$isSuccess = 0;
    
		    if (isset($result['name']) && !empty($result['name'])) {
		        $isSuccess = 1;
		    }

		    return $isSuccess;
  		} catch (Exception $ex) {
  			\Log::error($ex->getMessage());

  			return -1;
  		}
	}
}