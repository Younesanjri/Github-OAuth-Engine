<?php

  require_once 'appConfig.php';
  require_once 'userEntity.php';

  $user = new User();

  if(isset($accessToken)) {

      $gitUser = $gitClient->apiRequest($accessToken);
      
      if(!empty($gitUser)) {

          $gitUserData = array();
          $gitUserData['OAUTH_PROVIDER'] = 'github';
          $gitUserData['OAUTH_UID'] = !empty($gitUser->id)?$gitUser->id:'';
          $gitUserData['FULL_NAME'] = !empty($gitUser->name)?$gitUser->name:'';
          $gitUserData['USERNAME'] = !empty($gitUser->login)?$gitUser->login:'';
          $gitUserData['EMAIL'] = !empty($gitUser->email)?$gitUser->email:'';
          $gitUserData['LOCATION'] = !empty($gitUser->location)?$gitUser->location:'';
          $gitUserData['IMAGE'] = !empty($gitUser->avatar_url)?$gitUser->avatar_url:'';
          $gitUserData['LINK'] = !empty($gitUser->html_url)?$gitUser->html_url:'';
          
          // Insert or update user data to the database
          $userData = $user->checkUser($gitUserData);
          
          // Put user data into the session
          $_SESSION['userData'] = $userData;

          // The welcome title for signed in users
          $welcomeName = 'Welcome to octus, '.$userData['name'].'';

          // PHP and HTML for the sign in and profile button
          $profileNavItem = '
          <div class="dropdown">
            <a class="nav-link dropdown-toggle" href="" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img style="vertical-align:middle;width:22px;border-radius:50%;margin-right:7px;" src="'.$userData['IMAGE'].'" />
                <p>'.$userData['FULL_NAME'].'</p>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href="signOut.php">Sign out</a>
            </div>
          </div>';
          
      } else {

          // When there is data that cannot be fetched, display an error
          $output = '<h3 style="color:red">Some problem occurred, please try again.</h3>';

      }
      
  } elseif(isset($_GET['code'])) {

      // Verify the state matches the stored state:
      if(!$_GET['state'] || $_SESSION['state'] != $_GET['state']) {
          header("Location: ".$_SERVER['PHP_SELF']);
      }
      
      // Exchange the auth code for a token:
      $accessToken = $gitClient->getAccessToken($_GET['state'], $_GET['code']);
    
      $_SESSION['access_token'] = $accessToken;
    
      header('Location: ./');

  } else {

      // Generate a random hash and store in the session for security
      $_SESSION['state'] = hash('sha256', microtime(TRUE) . rand() . $_SERVER['REMOTE_ADDR']);

      // Remove access token from the session
      unset($_SESSION['access_token']);

      // Get the URL to authorize
      $loginURL = $gitClient->getAuthorizeURL($_SESSION['state']);
      
      // PHP and HTML for when the user is not signed in
      $profileNavItem = '
      <a class="nav-link btn btn-neutral" href="'.htmlspecialchars($loginURL).'">
          <i class="fab fa-github"></i>
          <p>Login with GitHub</p>
      </a>';

      $welcomeName = 'Welcome to Octus.';

  }

?>