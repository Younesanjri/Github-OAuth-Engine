<?php

  require_once 'php/appConfig.php';
  require_once 'php/userEntity.php';

  require_once 'php/embedCodeReader.php';
  require_once 'php/constantListener.php';

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

          // PHP and HTML for the sign in and profile button

          $menuAuthenticateItem = '
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

          ';
          
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
      $menuAuthenticateItem = '
      <a class="nav-link" href="'.htmlspecialchars($loginURL).'" role="button">
        <!-- Sign out icon here? -->
        <span class="nav-link-inner--text">Sign in</span>
      </a>';

      $welcomeName = 'Welcome to Octus.';

  }

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="GitHub OAuth Engine Template, open-source and 100% free.">
  <meta name="author" content="Creative Tim">
  <title>GitHub OAuth Engine for developers</title>
  <link href="./assets/img/brand/##.png" rel="icon" type="image/png">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
  <link href="./assets/vendor/nucleo/css/nucleo.css" rel="stylesheet">
  <link href="./assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link type="text/css" href="./assets/css/argon.css?v=1.0.1" rel="stylesheet">
  <link type="text/css" href="./assets/css/docs.min.css" rel="stylesheet">
  <link type="text/css" href="./assets/css/prism.css" rel="stylesheet">
</head>

<body>
  <header class="header-global">
    <nav id="navbar-main" class="navbar navbar-main navbar-expand-lg navbar-transparent navbar-light headroom">
      <div class="container">
        <!-- Brand logo with the default dimensions --> 
        <a class="navbar-brand mr-lg-5" href="./index.html">
          <img src="./assets/img/brand/white.png">
        </a>
        <!-- Responsive menu toggle icon --> 
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse" id="navbar_global">
          <div class="navbar-collapse-header">
            <div class="row">
              <!-- Brand logo in the responsive menu -->
              <div class="col-6 collapse-brand">
                <a href="./index.html">
                  <img src="./assets/img/brand/blue.png">
                </a>
              </div>
              <!-- Responsive menu close icon --> 
              <div class="col-6 collapse-close">
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false" aria-label="Toggle navigation">
                  <span></span>
                  <span></span>
                </button>
              </div>
            </div>
          </div>
          <!-- Navigation bar -->
          <ul class="navbar-nav navbar-nav-hover align-items-lg-center">
            <!-- First menu link -->
            <li class="nav-item dropdown">
              <a href="#" class="nav-link" data-toggle="dropdown" href="#" role="button">
                <i class="ni ni-ui-04 d-lg-none"></i>
                <span class="nav-link-inner--text">Components</span>
              </a>
              <div class="dropdown-menu dropdown-menu-xl">
                <div class="dropdown-menu-inner">
                  <!-- First item in the dropdown menu of 'Components' -->
                  <a href="#getting-started" class="media d-flex align-items-center">
                    <div class="icon icon-shape bg-gradient-darker rounded-circle text-white">
                      <i class="fa fa-cogs"></i>
                    </div>
                    <div class="media-body ml-3">
                      <h6 class="heading text-darker mb-md-1">Getting started</h6>
                      <p class="description d-none d-md-inline-block mb-0">Learn how to setup and use GOE within 5 minutes.</p>
                    </div>
                  </a>
                  <!-- Second item in the dropdown menu of 'Components' -->
                  <a href="https://demos.creative-tim.com/argon-design-system/docs/foundation/colors.html" class="media d-flex align-items-center">
                  <div class="icon icon-shape bg-gradient-darker rounded-circle text-white">
                      <i class="ni ni-palette"></i>
                    </div>
                    <div class="media-body ml-3">
                      <h6 class="heading text-darker mb-md-1">Styling your </h6>
                      <p class="description d-none d-md-inline-block mb-0">Learn more about colors, typography, icons and the grid system we used for Argon.</p>
                    </div>
                  </a>
                  <!-- Third item in the dropdown menu of 'Components' -->
                  <a href="https://demos.creative-tim.com/argon-design-system/docs/components/alerts.html" class="media d-flex align-items-center">
                    <div class="icon icon-shape bg-gradient-darker rounded-circle text-white">
                      <i class="ni ni-ui-04"></i>
                    </div>
                    <div class="media-body ml-3">
                      <h5 class="heading text-darker mb-md-1">Components</h5>
                      <p class="description d-none d-md-inline-block mb-0">Browse our 50 beautiful handcrafted components offered in the Free version.</p>
                    </div>
                  </a>
                </div>
              </div>
            </li>
            <!-- Second menu link, but this one does not contain any icons before the item -->
            <li class="nav-item dropdown">
              <a href="#" class="nav-link" data-toggle="dropdown" href="#" role="button">
                <i class="ni ni-collection d-lg-none"></i>
                <span class="nav-link-inner--text">Examples</span>
              </a>
              <div class="dropdown-menu">
                <!-- First item in the dropdown menu of 'Examples' -->
                <a href="./examples/landing.html" class="dropdown-item">Landing</a>
                <!-- Second item in the dropdown menu of 'Examples' -->
                <a href="./examples/profile.html" class="dropdown-item">Profile</a>
                <!-- Third item in the dropdown menu of 'Examples' -->
                <a href="./examples/login.html" class="dropdown-item">Login</a>
                <!-- Fourth item in the dropdown menu of 'Examples' -->
                <a href="./examples/register.html" class="dropdown-item">Register</a>
              </div>
            </li>
          </ul>
          <!-- Social media icons, not required and can be replaced with other widgets -->
          <ul class="navbar-nav align-items-lg-center ml-lg-auto">
            <li class="nav-item">
              <!-- Facebook icon link -->
              <a class="nav-link nav-link-icon" href="#" target="_blank">
                <i class="fa fa-facebook-square"></i>
                <span class="nav-link-inner--text d-lg-none">Facebook</span>
              </a>
            </li>
            <li class="nav-item">
              <!-- Instagram icon link -->
              <a class="nav-link nav-link-icon" href="#" target="_blank">
                <i class="fa fa-instagram"></i>
                <span class="nav-link-inner--text d-lg-none">Instagram</span>
              </a>
            </li>
            <li class="nav-item">
              <!-- Twitter icon link -->
              <a class="nav-link nav-link-icon" href="#" target="_blank">
                <i class="fa fa-twitter-square"></i>
                <span class="nav-link-inner--text d-lg-none">Twitter</span>
              </a>
            </li>
            <li class="nav-item">
              <!-- GitHub icon link -->
              <a class="nav-link nav-link-icon" href="#" target="_blank">
                <i class="fa fa-github"></i>
                <span class="nav-link-inner--text d-lg-none">Github</span>
              </a>
            </li>
            <!-- Sign in button -->
            <li class="nav-item d-none d-lg-block ml-lg-4">

              <?php echo $menuAuthenticateItem; ?>

            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>
  <main>
    <div class="position-relative">
      <section class="section section-lg section-shaped pb-100">
        <div class="shape shape-style-1 shape-default">
        </div>
          <div class="container py-lg-md d-flex">
            <div class="col px-0">
              <div class="row">
                <div class="row justify-content-between align-items-center">
                  <div class="col-lg-5 mb-5 mb-lg-0">
                    <h1 class="display-3  text-white">GitHub OAuth Engine
                      <span>made for developers</span>
                      </h1>
                    <p class="lead text-white">A polished and easy GitHub authentication engine, created for developers using just 5 PHP files.</p>
                    <div class="btn-wrapper">
                      <a href="https://www.creative-tim.com/product/argon-design-system" class="btn btn-success btn-icon mb-3 mb-sm-0">
                        <span class="btn-inner--icon"><i class="ni ni-cloud-download-95"></i></span>
                        <span class="no-uppercase btn-inner--text">Download now</span>
                      </a>
                    </div>
                  </div>
                  <div class="col-lg-5 mb-lg-auto">
                    <div class="overflow-hidden">
                        <img class="img-fluid github-brand-image" src="./assets/img/brand/Octicons-mark-github.png" alt="GitHub Mark">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    <section class="section section-components">
      <div class="container" id="getting-started" >
        <h1 class="mt-lg mb-5 text-bolder">
          <span>Getting started</span>
        </h1>
        <!-- Getting started STEP 1 --> 
        <div class="row py-3 align-items-center">
          <div class="col-sm-12">
            <h3 class="h4 font-weight-bold mb-4">Step 1: Preparing your database</h3>
            <p>Since we will be storing the user data into a SQL database, let's start off with executing the query found in <span class="consolas">database-query.sql</span> in your database. After this is completed, all tables will be ready to be filled up.</p>
          </div>
        </div>
        <!-- Getting started STEP 2 --> 
        <div class="row py-3 align-items-center">
          <div class="col-sm-9">
            <h3 class="h4 font-weight-bold mb-4">Step 2: Configure your database settings</h3>
            <p>Once you have executed the query, open <span class="consolas">userEntity.php</span> and make sure the following variables are correctly
              set:</p>
            <div class="code-block"> <!-- We directly insert the embed code from an external text file -->
              <pre><code class="language-php"><?php generateEmbedCode('./embed-snippets/DB_SETTINGS.txt');?></code></pre>
            </div>
          </div>
        </div>
        <!-- Getting started STEP 3 --> 
        <div class="row py-3 align-items-center">
          <div class="col-sm-9">
            <h3 class="h4 font-weight-bold mb-4">Step 3: Configure your settings</h3>
            <p>Next, let's set the configuration settings to fit your application. Inside of <span class="consolas">appConfig.php</span>, replace the values of the 
              following variables with the ones of your application:</p>
            <div class="code-block"> <!-- We directly insert the embed code from an external text file -->
              <pre><code class="language-php"><?php generateEmbedCode('./embed-snippets/APP_SETTINGS.txt');?></code></pre>
            </div>
          </div>
        </div>
        <!-- Getting started STEP 4 --> 
        <div class="row py-3 align-items-center">
          <div class="col-sm-9">
            <h3 class="h4 font-weight-bold mb-4">Step 4: You are ready</h3>
            <p>Now that you have configured all required settings, you are good to go! You can test it out by going to your OAuth website and signing in to your application.</p>
          </div>
        </div>
      </div>
    </section>
    <section class="section section-lg">
      <div class="container">
        <div class="row row-grid justify-content-center">
          <div class="col-lg-8 text-center">
            <h2 class="display-3">Do you find this useful?
              <span class="text-success">Please, let me know.</span>
            </h2>
            <p class="lead">Because if you do, developers can easily create beautiful and effective websites for their products.</p>
            <div class="btn-wrapper">
              <a href="https://www.creative-tim.com/product/argon-design-system" class="no-uppercase btn btn-success mb-3 mb-sm-0">
                <i class="fa fa-github"></i>
                Visit the GitHub
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <footer class="footer">
    <div class="container">
      <hr>
      <div class="row align-items-center justify-content-md-between">
        <div class="col-md-6">
          <div class="copyright">
            &copy; 2019
            <a href="https://www.creative-tim.com" target="_blank">GOE</a>
          </div>
        </div>
        <div class="col-md-6">
          <ul class="nav nav-footer justify-content-end">
            <li class="nav-item">
              <a href="" class="nav-link" target="_blank">Grydlab</a>
            </li>
            <li class="nav-item">
              <a href="" class="nav-link" target="_blank">My GitHub</a>
            </li>
            <li class="nav-item">
              <a href="" class="nav-link" target="_blank">Contact</a>
            </li>
            <li class="nav-item">
              <a href="" class="nav-link" target="_blank">MIT License</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </footer>

  <!-- We load all scripts at the end of the file to improve the loading speed of the page -->
  <script src="./plugins/prism.js"></script>
  <script src="./assets/vendor/jquery/jquery.min.js"></script>
  <script src="./assets/vendor/popper/popper.min.js"></script>
  <script src="./assets/vendor/bootstrap/bootstrap.min.js"></script>
  <script src="./assets/vendor/headroom/headroom.min.js"></script>
  <script src="./assets/vendor/onscreen/onscreen.min.js"></script>
  <script src="./assets/vendor/nouislider/js/nouislider.min.js"></script>
  <script src="./assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
  <script src="./assets/js/argon.js?v=1.0.1"></script>

</body>

</html>