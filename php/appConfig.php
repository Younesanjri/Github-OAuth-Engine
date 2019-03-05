<?php

if(!session_id()){
    session_start();
}

require_once 'appClient.php';

/**
 * IMPORTANT: The following variables need to be changed to your application details.
 * 
 * $clientID: GitHub -> Settings -> Developer settings -> OAuth Apps -> Select your app -> Client ID (above Client Secret)
 * $clientSecret: GitHub -> Settings -> Developer settings -> OAuth Apps -> Select your app -> Client Secret (below Client ID)
 * $redirectURL: The location of your website folder, redirects to index.php automatically.
 * 
 * ALSO: For the database setup, execute the SQL found in the 'getting-started' folder.
 * 
 */

$clientID  = '563535367511af1c110a';
$clientSecret = 'b2a459f2895bbfee926db540ca72229fe0ebeb6a';
$redirectURL = 'http://localhost/argon-lars/';

$gitClient = new appClient(array(
    'client_id' => $clientID,
    'client_secret' => $clientSecret,
    'redirect_uri' => $redirectURL,
));

if(isset($_SESSION['access_token'])){

    $accessToken = $_SESSION['access_token'];
    
}