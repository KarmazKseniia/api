<?php

require_once 'API.class.php';

class MyAPI extends API {
    protected $User;

    public function __construct($request, $origin) {
        parent::__construct($request);

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
     * /api/v1/example
     */
     protected function example() {
        if ($this->method == 'GET') {
            return array("exercises" => array("a" => "orange", "b" => "banana", "c" => "apple"));
        } else {
            return "Only accepts GET requests";
        }
     }
	 
	 /*  require_once '../includes/config.php'; // The mysql database connection script
  $status = '%';
  if (isset($_GET['status'])) {
    $status = $_GET['status'];
  }
  $query = "select ID, TASK, STATUS from tasks where status like '$status' order by status,id desc";
  $result = $mysqli->query($query) or die($mysqli->error.__LINE__);

  $arr = array();
  if($result->num_rows > 0) {
   while($row = $result->fetch_assoc()) {
   $arr[] = $row;
   }
  }
*/
 }
?>