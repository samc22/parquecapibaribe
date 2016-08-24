<?php 
class CapibaribeTweets(){
    private $TWITTER_API_KEY = 'tY3ByDU54sbHN0IjrDEPa7uz4';
    private $TWITTER_API_SECRET = 'NqaYE77exGz2H0eQxHSB9sPkMfVUUf3e98yuJgcxZe75wFphoK';
    private $TWITTER_TOKEN_URL = 'https://api.twitter.com/oauth2/token';
    private $TWITTER_SEARCH_URL = 'https://api.twitter.com/1.1/search/tweets.json';

    private function get_bearer_token(){
        $request = curl_init();
        $url_encoded_api_key = rawurlencode($this->$TWITTER_API_KEY);
        $url_encoded_api_secret = rawurlencode($this->$TWITTER_API_SECRET);
        $bearer_token_credentials = $url_encoded_api_key.':'.$url_encoded_api_secret;
        $b64_bearer_token_credentials = base64_encode($bearer_token_credentials);

        curl_setopt($request, CURLOPT_HTTPHEADER, array(
            'Authorization: Basic '.$b64_bearer_token_credentials,
            'Content-Type: application/x-www-form-urlencoded;charset=UTF-8.'
        ));
        curl_setopt($request, CURLOPT_POST, true);
        curl_setopt($request, CURLOPT_POSTFIELDS, array(
            'grant_type' => 'client_credentials'
        ));

        curl_setopt($request, CURLOPT_URL, $this->$TWITTER_TOKEN_URL);

        curl_setopt($request, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($request);

        return json_decode($response, true)['access_token'];
    }

    private function make_query_string($search_params){
        $search_params_str = '';
        $i=0;
        foreach($search_params as $key => $value){
            $search_params_str .= $key.'='.$value;
            if($i < count($search_params) - 1){
                $search_params_str .= '&';
            }
            $i++;
        }

        return $search_params_str;
    }

    public function get_tweets(){
        $request = curl_init();
        $token = $this->get_bearer_token();
        $params = $this->make_query_string(array("q" => "rio capibaribe"));
        
        $search_url = sprintf("%s?%s", $this->$TWITTER_SEARCH_URL, $search_params_str);

        curl_setopt($request, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer '.$token
        ));

        curl_setopt($request, CURLOPT_URL, $search_url);
        
        curl_setopt($request, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($request);

        return $response;
    }
}