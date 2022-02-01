<?php

namespace App\Utility;

use Google_Client;
use Google_Service_Drive;

use Google\Service\Drive\DriveFile;

class GoogleDrive
{
	public $client;

	public function __construct()
	{
		/*$this->client = new Client();
		$this->client->setAuthConfig(config('spacex.google_oauth_file'));
		$this->client->setRedirectUri('/google-drive/set-token');
		$this->client->addScope("https://www.googleapis.com/auth/drive");
		$this->service = new Drive($this->client);*/

		// dd($this->service);
		
		$this->client = $this->getClient();
	}

	/**
	 * Returns an authorized API client.
	 * @return Google_Client the authorized client object
	 */
	function getClient()
	{
	    $client = new Google_Client();
	    $client->setAuthConfig(config('spacex.google_oauth_file'));
	    $client->setScopes(Google_Service_Drive::DRIVE);

	    // $client->setApplicationName('Google Drive API PHP Quickstart');
	    // $client->setScopes(Google_Service_Drive::DRIVE_METADATA_READONLY);
	    // $client->setAuthConfig(config('spacex.google_oauth_file'));
	    // $client->setAccessType('offline');
	    // $client->setPrompt('select_account consent');

	    // Load previously authorized token from a file, if it exists.
	    // The file token.json stores the user's access and refresh tokens, and is
	    // created automatically when the authorization flow completes for the first
	    // time.
	    $tokenPath = storage_path('app/google/token.json');

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

	    return $client;
	}

	public function setToken()
	{
		/************************************************
		 * If we have a code back from the OAuth 2.0 flow,
		 * we need to exchange that with the
		 * Google\Client::fetchAccessTokenWithAuthCode()
		 * function. We store the resultant access token
		 * bundle in the session, and redirect to ourself.
		 ************************************************/
		if (request()->has('code') && !empty(request()->code)) {
			$token = $this->client->fetchAccessTokenWithAuthCode(request()->code);
			$this->client->setAccessToken($token);

			session()->put('upload_token', $token);

			return redirect()->to('google-drive/upload-token');
		} else {
			echo 'You need to authorize here: ' . $this->client->createAuthUrl();
		}
	}

	public function uploadToken()
	{
		if (!empty(session()->get('upload_token'))) {
		  	$this->client->setAccessToken(session()->get('upload_token'));
		  	
		  	if ($this->client->isAccessTokenExpired()) {
		    	session()->forget('upload_token');
		  	} else {
		  		\Log::info('Access token: ' . $this->client->getAccessToken());
		  		echo 'Access token: ' . $this->client->getAccessToken();
		  	}
	  	} else {
	  		return redirect()->to('google-drive/upload-token');
	  	}
	}

	public function unsetToken()
	{
		session()->forget('upload_token');
	}

	public function uploadFile($fileData = [])
	{
		\Log::info(storage_path('app/backup/backup-20220123181006.sql'));

		$fileData = [
	        'data' => file_get_contents(storage_path('app/backup/backup-20220123181006.sql')),
	        'mimeType' => 'application/octet-stream',
	        'uploadType' => 'media'
  		];

  		\Log::info($this->files);

  		try {
  			$service = new Google_Service_Drive($this->client);

			// This is uploading a file directly, with no metadata associated.
	  		$file = new DriveFile();
	  		
	  		$result = $service->files->create($file, $fileData);

	  		dd($result);
  		} catch (Exception $ex) {
  			\Log::error($ex->getMessage());

  			if ($ex->error->code == 401 || $ex->error->message == 'Login Required') {
  				echo 'You need to first authorize your app here: ' . url('google-drive/set-token');
  			}
  		}
	}
}