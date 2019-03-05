# Github OAuth Engine [![License: IPL 1.0] [![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

A simple website template with a built-in GitHub authentication log-in system.

## Setting up

### Step 1
**Preparing your database** - Prepare your database for the storage of the user data by executing the following SQL:

```sql
CREATE TABLE `users` (
 `USER_ID` int(11) NOT NULL AUTO_INCREMENT,
 `OAUTH_PROVIDER` enum('','github','facebook','google','twitter') COLLATE utf8_unicode_ci NOT NULL,
 `OAUTH_UID` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
 `FULL_NAME` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
 `USERNAME` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
 `EMAIL` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
 `LOCATION` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
 `IMAGE` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
 `LINK` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
 `CREATED` datetime NOT NULL,
 `MODIFIED` datetime NOT NULL,
 PRIMARY KEY (`USER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
```

### Step 2
**Database configuration** - Update the database variables listed in *userEntity.php*:

```php
private $databaseHost = "localhost";
private $databaseUsername = "root";
private $databasePassword = "";
private $databaseName = "github";
private $userTable = 'users';
```

### Step 3
**Client configuration** - Update the OAuth variables listed in *appConfig.php*:

```php
$clientID  = 'FILL IN YOUR CLIENT ID HERE';
$clientSecret = 'FILL IN YOUR CLIENT SECRET HERE';
$redirectURL = 'http://localhost/YOUR_APP_NAME/'; // Redirects to index.php
```

### Step 4
**You are ready** - Now that you have configured all required settings, you are good to go! You can test it out by going to your OAuth website and signing in to your application.

## Author(s)

Lars Wolters - *Initial work*
