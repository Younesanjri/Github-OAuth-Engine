<?php

class User {

    private $databaseHost = "localhost";
    private $databaseUsername = "root";
    private $databasePassword = "";
    private $databaseName = "github";
    private $userTable = 'users';
    
    function __construct() {

        if(!isset($this->database)) {

            $connection = new mysqli($this->databaseHost, $this->databaseUsername, $this->databasePassword, $this->databaseName);

            if($connection->connect_error) {
                die("Failed: connection with MySQL: " . $connection->connect_error);
            } else {
                $this->database = $connection;
            }

        }
    }
    
    function checkUser($userData = array()) {

        if(!empty($userData)) {

            // Check whether user data already exists in database
            $prevQuery = "SELECT * FROM ".$this->userTable." WHERE OAUTH_PROVIDER = '".$userData['OAUTH_PROVIDER']."' AND OAUTH_UID = '".$userData['OAUTH_UID']."'";
            $prevResult = $this->database->query($prevQuery);

            if($prevResult->num_rows > 0) {
                // Update user data if already exists
                $query = "UPDATE ".$this->userTable." SET FULL_NAME = '".$userData['FULL_NAME']."', USERNAME = '".$userData['USERNAME']."', EMAIL = '".$userData['EMAIL']."', LOCATION = '".$userData['LOCATION']."', IMAGE = '".$userData['IMAGE']."', LINK = '".$userData['LINK']."', modified = NOW() WHERE OAUTH_PROVIDER = '".$userData['OAUTH_PROVIDER']."' AND OAUTH_UID = '".$userData['OAUTH_UID']."'";
                $update = $this->database->query($query);
            } else {
                // Insert user data
                $query = "INSERT INTO ".$this->userTable." SET OAUTH_PROVIDER = '".$userData['OAUTH_PROVIDER']."', OAUTH_UID = '".$userData['OAUTH_UID']."', FULL_NAME = '".$userData['FULL_NAME']."', USERNAME = '".$userData['USERNAME']."', EMAIL = '".$userData['EMAIL']."', LOCATION = '".$userData['LOCATION']."', IMAGE = '".$userData['IMAGE']."', LINK = '".$userData['LINK']."', created = NOW(), modified = NOW()";
                $insert = $this->database->query($query);
            }
            
            // Get the user data from the database
            $result = $this->database->query($prevQuery);
            $userData = $result->fetch_assoc();

        }
        
        // Return the user data
        return $userData;
    }
}