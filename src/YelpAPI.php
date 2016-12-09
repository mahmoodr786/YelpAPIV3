<?php
namespace mahmoodr786;

require_once 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use Exception;

/**
 * YelpV3
 */
class YelpAPI
{
    /**
     * @var String Token URL
     */
    public $tokenURL = "https://api.yelp.com/oauth2/token";
    /**
     * @var String APIURL
     */
    public $APIURL = "https://api.yelp.com/v3/";
    /**
     * @var String Token
     */
    public $token = "";
    /**
     * @var String clientID
     */
    public $clientID = "";
    /**
     * @var String secret
     */
    public $secret = "";

    /**
     * Construct method
     * @param String $clientID
     * @param String|null $secret
     * @param String|null $url
     * @return VOID
     */
    public function __construct($clientID = nul, $secret = null, $url = null)
    {
        // set the api url if we have one
        if (!is_null($url)) {
            $this->APIURL = $url;
        }

        // check key
        if (is_null($clientID)) {
            throw new Exception("Error No API Key", 1);
        }
        // check $secret
        if (is_null($secret)) {
            throw new Exception("Error No API Secret", 1);
        }
        $this->clientID = $clientID;
        $this->secret = $secret;
        $this->setToken();
    }
    /**
     * setToken method
     * @return VOID
     */
    private function setToken()
    {

        $token = @json_decode(file_get_contents('token.json'));

        if (isset($token->access_token) && !empty($token->access_token)) {
            if ($token->expires_in > time()) {
                $this->token = $token->access_token;
            }
        } else {
            print_r($token);
            $params = [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'client_id' => $this->clientID,
                    'client_secret' => $this->secret,
                    'grant_type' => 'client_credentials',
                ],
            ];

            $body = \Unirest\Request\Body::Form($params['form_params']);
            $tokenResponse = \Unirest\Request::post($this->tokenURL, $params['headers'], $body);

            if (isset($tokenResponse->body->access_token) && !empty($tokenResponse->body->access_token)) {
                $this->token = $tokenResponse->body->access_token;
                $tokenResponse->body->expires_in = time() + $tokenResponse->body->expires_in;
                $writeToken = file_put_contents('token.json', json_encode($tokenResponse->body));
                if (!$writeToken) {
                    throw new Exception("Unable to write token to token.json", 1);
                }
            }
        }
    }
    /**
     * getBusinesses method
     * @param Array|array $params
     * @param String| string $url
     * @return JSONobj
     */
    public function getData($url = "", $params = [])
    {

        $headers = [
            'Authorization' => 'Bearer ' . $this->token,
        ];

        $tokenResponse = \Unirest\Request::get($this->APIURL . $url, $headers, $params);

        return $tokenResponse->body;
    }

}
