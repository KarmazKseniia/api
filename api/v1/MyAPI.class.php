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
	 * /api/v1/workout/list 		GET - список всех тренировок.
	 * /api/v1/workout/list/me 		GET - список моих тренировок.
	 * /api/v1/workout/{{id}} 		GET - взять конкретную тренировку.
	 * /api/v1/workout/ 			POST - создать тренировку.
	 * /api/v1/workout/{{id}} 		POST - добавить тренировку в аккаунт.
	 * /api/v1/workout/{{id}} 		PUT - изменить тренировку.
	 * /api/v1/workout/{{id}} 		DELETE - удалить тренировку из аккаунта.
	 * /api/v1/workout/{{id}}/{{workoutId}} POST - добавить упражнения одной тренировки в другую.
	 */
	 protected function workout() {
        
     }
	 
    /**
	 * /api/v1/exercise/list		GET - взять все упражнения.
	 * /api/v1/exercise/list/me		GET - взять все упражнения из аккаунта.
	 * /api/v1/exercise/{{id}} 		GET - взять конкретное упражнение.
	 * /api/v1/exercise/{{id}}/{{workoutId}} POST - добавить упражнение в тренировку.
	 * /api/v1/exercise/{{id}}/{{workoutId}} DELETE - удалить упражнение из тренировки.
     */
     protected function exercise() {
        if ($this->method == 'GET') {
			$params = array(':category' => 'test');
 
			$this.$pdo->prepare('
			   SELECT * FROM exercise
			   WHERE category = :category');
			 
			$result = $this.$pdo->execute($params);
			$result->setFetchMode(PDO::FETCH_CLASS, 'Exercise');
			 
			while ($exercise = $result->fetch()) {
			   return $exercise->info();
			}
			return '';
        } else {
            return "Only accepts GET requests";
        }
     }
	 		 
	/**
	 * sport_type=1&muscule_group=2
	 */
	 private function filter() {
	 
	 }
 }
?>