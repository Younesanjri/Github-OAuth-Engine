# Github OAuth Engine

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

## Author(s)

Lars Wolters - *Initial work*
