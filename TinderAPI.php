<?php
/**
 * Class makes requests to Tinder API
 *
 * @author Alexey Suvorov
 */
class TinderAPI 
{
    
    const URL = 'https://api.gotinder.com/';
   
    protected static $routes = [
        'auth' => 'v2/auth/login/facebook',
        'getProfiles' => 'v2/recs/core',
        'like' => 'like/'
    ];
    
    /**
     * HTTP headers
     * @var array 
     */
    protected static $headers = [
        'User-Agent' => 'Tinder Android Version 8.9.0',
        'Content-Type' => 'application/json',
        'os_version' => '22',
        'platform' => 'android',
        'app_version' => '2577'
    ];
    
    /**
     * HTTP headers in line
     * @var string 
     */
    protected $header;
    
    /**
     * Auth in constructor
     * @param string $id facebook id
     * @param string $token facebook token
     */
    public function __construct($id, $token) 
    {
        $this->header = $this->makeHeader(); 
        $authToken = $this->FBAuth($id, $token);
        $this->setAuthtoken($authToken);
    }

    /**
     * Auth in Tinder via Facebook
     * @param string $id 
     * @param string $token
     * @return string api token
     * @throws Exception
     */
    protected function FBAuth($id, $token)
    {
        $response = $this->call(self::$routes['auth'], [
			'id' => $id, 
			'token' => $token
		]);
        if(!$response) {
            throw new Exception('Can\'t get the auth token');
        }
        
        return $response['data']['api_token'];
    }
    
    /**
     * Get profiles
     * @return array
     * @throws Exception
     */
    public function getProfiles()
    {
        $response = $this->call(self::$routes['getProfiles']);
        if(!$response) {
            throw new Exception('Can\'t get the profiles');
        }
        
        return $response['data']['results'];
    }    
    
    /**
     * Like user
     * @param string $user_id
     * @return bool match?
     */
    public function like($user_id)
    {
        $response = $this->call(self::$routes['like'] . $user_id);
        
        return $response['match'];
    }
    
    /**
     * Generate HTTP headers string from self::$headers
     * @return string
     */
    protected function makeHeader()
    {
        $header = '';
        foreach(self::$headers as $k => $v) {
            $header .= $k . ": " . $v . "\r\n";
        }

        return $header;
    }

    /**
     * Add auth token line into headers
     * @param string $authToken
     */
    protected function setAuthtoken($authToken)
    {
        $this->header .= "X-Auth-Token: " . $authToken . "\r\n";
    }
    
    /**
     * Make request to API
     * @param string $path
     * @param string $data to POST
     * @return string result
     */
    protected function call($path, $data = [])
    {
        $context = [
			'http' => [
				'method' => (sizeof($data) === 0) ? 'GET' : 'POST',
				'header' => $this->header
			]
		];

        if(sizeof($data) > 0) {
            $context['http']['content'] = json_encode($data);
        }
        
        $url = self::URL . $path;
        $json = file_get_contents($url, false, stream_context_create($context));

        $response = json_decode($json, true);

        return $response;
    }
}
