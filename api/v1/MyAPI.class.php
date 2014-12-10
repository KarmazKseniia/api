<?php
require_once 'config.php';
require_once 'API.class.php';

class MyAPI extends API {
    protected $User;
	protected $pdo;

    public function __construct($request, $origin) {
        parent::__construct($request);

		$this.$pdo = connect();
		
        // Abstracted out for example
        //$APIKey = new Models\APIKey();
        //$User = new Models\User();

        /*
		if (!array_key_exists('apiKey', $this->request)) {
            throw new Exception('No API Key provided');
        } else if (!$APIKey->verifyKey($this->request['apiKey'], $origin)) {
            throw new Exception('Invalid API Key');
        } else if (array_key_exists('token', $this->request) &&
             !$User->get('token', $this->request['token'])) {

            throw new Exception('Invalid User Token');
        }

        $this->User = $User; 
		*/
    }

    /**
     * /api/v1/exercise
     */
     protected function exercise() {
        if ($this->method == 'GET') {
			$params = array(':category' => 'test');
 
			$this.$pdo->prepare('
			   SELECT * FROM exercise
			   WHERE category = :category');
			 
			return $this.$pdo->execute($params);
        } else {
            return "Only accepts GET requests";
        }
     }
 }
?>