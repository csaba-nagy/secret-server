# Solution of the Secret Server Coding Task


## Usage
The API is available at [here](http://134.122.88.24/v1).

You can use the following routes to test this:

| Request Method | Route                                  | Action                                                     |
| ---------------| ---------------------------------------|------------------------------------------------------------|
| GET            | http://134.122.88.24/v1/secret/{hash}  | Returns the secret message which belongs to the given hash |
| POST           | http://134.122.88.24/v1/secret         | Creates a secret message                                   |

> To test these functionalities standalone, you can use Postman or Insomnia for example.

**Required payload parameters to creating secrets:**
```js
{
  "secret": "top_secret",
  "expiresAfter": 5,
  "expiresAfterViews": 1
}
```
> secret: Your secret text

> expiresAfter: The value refers to the secret lifetime in minutes.

> expireFterViews: It refers to the number of the available views. If it decreased to 0, the secret is no longer available.

> The secrets will be deleted from the database when they expired.

**Response format**
> The API is sending responses in JSON format and this is the only available option for now.

```js
{
  "hash": "99dd9222-8",
  "secretText": "top_secret",
  "createdAt": "2023-05-13 14:52:04",
  "expiresAt": "2023-05-13 14:57:04",
  "remainingViews": 0
}
```

## Local Usage

**Installation**
1. Clone this project via:
```
git clone https://github.com/csaba-nagy/secret-server.git
```
2. Create the *.env* file with the following command and set the environment variables:
```
cp .env.example .env
```
3. [ OPTIONAL ] Open the project in Dev Container with Docker and VsCode.
4. Import the files from the workspace/src/Database/exported folder to the database, you can use phpmyadmin to do that. [^1]
5. Run the following command to install dependencies:
```
composer install
```

**Start**

To start the application, use:
```
composer dev
```
The default path is http://localhost:8080. If you want to change it, you can edit it in *composer.json*.

[^1]: If you are using docker and devcontainer, it's included
