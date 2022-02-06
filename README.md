## Project Overview
We will implement the functionality to back up the project database and upload the backup file to Google drive. We will also integrate [SpaceX Launches API](https://docs.spacexdata.com/#bc65ba60-decf-4289-bb04-4ca9df01b9c1) to show a list of launches as well as individual launch details.

## Installation
1. Open your terminal, create a directory in your project folder and get inside that directory.
`mkdir spacex`
`cd spacex/`

2. Clone the repo and complete composer installation process
`git clone https://github.com/chittaranjanptnk/unicoc-spacex.git .`
`composer install`

3. Generate the .env file and set it up
`cp .env.example .env`
`php artisan key:generate`
Set the `APP_URL` as per your set up. Assume `http://localhost` in this case

4. Create the database and set the credentials in `.env` and run the migration to create the tables
`php artisan migrate`

5. Create a project through Google console and select/enable Google Drive API. Also need to create the credentials to access your enabled APIs. You need to copy the **API Key**, **OAuth Client ID** and **Secret**. Make sure you configure it properly so that it recongnizes your web app. You also need to download the json having above credentials.
![Google app screenshot](https://drive.google.com/file/d/1RIukJEeh2Wvj8eHpu5HG_XeBrSqAwunI/view)

6. In the terminal, create a directory inside `storage` -> `app` -> `google` and make sure that's writable. You need to copy the above downloaded json file to this `google` directory and rename that to `auth.json`. One thing to note is whenever you make some setting related changes in your Google app, you need to download a fresh json file and overwrite the `auth.json` in your project directory.

7. Set the **API Key**, **OAuth Client ID** and **Secret** against **GOOGLE_DRIVE_API_KEY**, **GOOGLE_DRIVE_CLIENT_ID** and **GOOGLE_DRIVE_CLIENT_SECRET** in the `.env` file respectively.

8. Now run the project with following command and you should be able to access this when you run `http://localhost:8000` in the browser. This should match with the allowed domains/redirect uris list in the google app settings.
`php artisan serve`
![Authorized Redirect URIs](https://drive.google.com/file/d/13dC985rDyJq2j1Aj-NV5SjyBm8HYVLO4/view)

9. Open a new tab in the terminal and using `php artisan tinker`, try to create an object of `GoogleDrive` class like `new App\Utility\GoogleDrive()`. It will show a link to generate the verification code. Copy, paste that url in the browser and you need to go through the authorize process. Then copy the code from url and paste that in the console. It stores the token inside `storage\app\google\token.json` file. You can use this [url decode function](https://www.functions-online.com/urldecode.html) to decode that url which may be needed in some cases.
![Verification Link Generator](https://drive.google.com/file/d/1wtrFNou92mj78BFAaILbPCg75V2UQJfn/view)

10. Come out of the tinker mode and create a directory inside `storage` -> `app` -> `backup` and make sure that's writable. Now we will run the database backup script `php artisan db:backup`. It should create an sql backup file and store that in the above directory. At the same time, it will upload that to the Google drive which you can look for.
![Google Drive Backup File](https://drive.google.com/file/d/1HYOVWzcQugY0MM4oor_ffZ3Awzu8xgk9/view)

11. As far as the SpaceX launches API is concerned, you can access the same when you run `http://localhost:8000` in the browser. You can click on each item to show the details for that launch.