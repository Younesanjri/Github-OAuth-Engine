# Github OAuth Engine [![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
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

## Usage

Let's say you want to display the username of the user that is signed in with GitHub, inside of a paragraph:
```php
<p><?php $userData['FULL_NAME']?>
```

Or you want to create a navigation bar item, containing the profile picture and name and a dropdown menu (with, let's say, a Sign out option):

```php
<li class="nav-item dropdown">
  <a href="" class="nav-link" data-toggle="dropdown" role="button">
    <img class="user-profile-picture" src="'.$userData['IMAGE'].'">
    <span class="nav-link-inner--text">'.$userData['FULL_NAME'].'</span>
  </a>
  <div class="dropdown-menu profile-menu">
    <a href="" class="dropdown-item">Profile</a>
    <a href="" class="dropdown-item">Downloads</a>
    <a href="" class="dropdown-item">Settings</a>
    <a href="php/'.$SIGN_OUT_URL.'" class="dropdown-item">Sign Out</a>
  </div>
</li>
```


## Author(s)

Lars Wolters - *Initial work*

## Thanks to

**Argon Design** by [Creative Tim](https://www.creative-tim.com/product/argon-design-system)
