<?php

/*
 * The engine for authenticating using the GitHub REST API. Does not need to be changed and is functional as it is.
 * Config values used here are defined within the 'appConfig.php' file.
 */

class appClient {

    public $authorizeURL = "https://github.com/login/oauth/authorize";
    public $tokenURL = "https://github.com/login/oauth/access_token";
    public $apiURLBase = "https://api.github.com/";
    public $clientID;
    public $clientSecret;
    public $redirectURI;
    
    public function __construct(array $config = []) {

        $this->clientID = isset($config['client_id']) ? $config['client_id'] : '';

        if(!$this->clientID) {
            // When the client ID key is not found or defined within the config file, alert it.
            die('Failed: the required CLIENT ID is not defined in the config file (appConfig.php)');
        }
        
        $this->clientSecret = isset($config['client_secret']) ? $config['client_secret'] : '';

        if(!$this->clientSecret) {
            // When the client secret key is not found or defined within the config file, alert it.
            die('Failed: the required CLIENT SECRET is not defined in the config file (appConfig.php)');
        }
        
        $this->redirectURI = isset($config['redirect_uri']) ? $config['redirect_uri'] : '';
    }
    
    /**
     * Construct the authorize URL by a HTTP query
     * @return a string containing the generated URL
     */
    public function getAuthorizeURL($state) {

        return $this->authorizeURL . '?' . http_build_query([
            'client_id' => $this->clientID,
            'redirect_uri' => $this->redirectURI,
            'state' => $state,
            'scope' => 'user:email'
        ]);

    }
    
    /**
     * Exchange token and code for an access token
     * @return the access token
     */
    public function getAccessToken($state, $oauth_code) {

        $token = self::apiRequest($this->tokenURL . '?' . http_build_query([
            'client_id' => $this->clientID,
            'client_secret' => $this->clientSecret,
            'state' => $state,
            'code' => $oauth_code
        ]));

        return $token->access_token;

    }
    
    /**
     * Make an API request
     * @return API results
     */
    public function apiRequest($access_token_url) {

        $apiURL = filter_var($access_token_url, FILTER_VALIDATE_URL)?$access_token_url:$this->apiURLBase.'user?access_token='.$access_token_url;

        $context  = stream_context_create([
          'http' => [
            'user_agent' => 'Octus GitHub Login Engine',
            'header' => 'Accept: application/json'
          ]
        ]);

        $response = @file_get_contents($apiURL, false, $context);
        return $response ? json_decode($response) : $response;
        
    }

}