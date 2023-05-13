# Solution of the "Secret Server Coding Task"

## Installation
1. Clone this project via: `git clone https://github.com/csaba-nagy/secret-server.git`
2. Create the *.env* file via `cp .env.example .env` command and set the environment variables
3. [ OPTIONAL ] Open the project in Dev Container with VsCode.
4. import the files from the workspace/src/Database/exported folder to the database.
  (You can use phpmyadmin to do that, it's included)
5. Run `composer install` command to install the dependencies.

## Usage
To start the application, use the `composer dev` command.

Once the application is running you can use the following routes:
```php
  GET => [
    'http://host:port/v1/secret/{hash}' => Receive the secret by the given hash
  ]

  POST => [
    'http://host:port/v1/secret/'       => Creates a secret
  ]
```

> The API is sending responses in JSON format and this is the only available option for now.

### Required payload parameters for POST requests
```json
{
  // your secret text
  "secret":  "top_secret",

  // expiration times in minutes
  "expiresAfter" : 5,

  // expires after the given number of views
  "expiresAfterViews": 1
}
```
The secrets cannot be accessable and will be deleted from the database when they expired.

### Response format
```json
[
	{
		"hash": "99dd9222-8",
		"secretText": "secret server",
		"createdAt": "2023-05-13 14:52:04",
		"expiresAt": "2023-05-13 14:57:04",
		"remainingViews": 0
	}
]
```
