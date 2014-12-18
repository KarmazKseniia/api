<?php
require_once 'config.php';
require_once 'DB.class.php';
require_once 'API.class.php';

require_once 'Recipe.class.php';
require_once 'Product.class.php';
require_once 'Exercise.class.php';
require_once 'ImageExercise.class.php';
require_once 'VideoExercise.class.php';
require_once 'Workout.class.php';

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
     * 1) /api/v1/recipe/list          GET - список всех рецептов.
     * 2) /api/v1/recipe/{{id}}        GET - взять конкретный рецепт.
     * 3) /api/v1/recipe               POST - создать рецепт.
     * 4) /api/v1/recipe/{{id}}        PUT - изменить рецепт.
     * 5) /api/v1/recipe/{{id}}        DELETE - удалить рецепт.
     */
    protected function recipe() {
		$id = $this->args[0];
		
        if ($this->method == 'GET') {
            if ($this->verb == 'list') { // (1)
                return Recipe::getList();
            }

            if ($id) { // (2)
                return Recipe::get($id);
            }
			
        } else if ($this->method == 'POST') { // (3)
            return Recipe::add($this->request);
			
        } else if ($this->method == 'PUT') { // (4)
			if ($id) {
				return Recipe::update($id, $this->request);
			}
			
        } else if ($this->method == 'DELETE') { // (5)
            if ($id) {
				return Recipe::delete($id);
			}
        }

        error("404.2");
    }

    /**
     * 1) /api/v1/product/list          GET - список всех продуктов.
     * 2) /api/v1/product/{{id}}        GET - взять конкретный продукт.
     */
    protected function product() {
        if ($this->method == 'GET') {
            if 	($this->verb == 'list') { // (1)
                return Product::getList();
            }

            if ($this->args[0]) { // (2)
                return Product::get($this->args[0]);
            }

        }

        error("404.2");
    }

    /**
     * /api/v1/workout/list             GET - список всех тренировок.
     * /api/v1/workout/list/me          GET - список моих тренировок.
     * /api/v1/workout/{{id}}           GET - взять конкретную тренировку.
     * /api/v1/workout/                 POST - создать тренировку.
     * /api/v1/workout/{{id}}           POST - добавить тренировку в аккаунт.
     * /api/v1/workout/{{id}}           PUT - изменить тренировку.
     * /api/v1/workout/{{id}}           DELETE - удалить тренировку из аккаунта.
     * /api/v1/workout/{{id}}/{{workoutId}} POST - добавить упражнения одной тренировки в другую.
     */
    protected function workout() {
        if ($this->method == 'GET') {
            if ($this->verb == 'list') {

                //  /api/v1/workout/list/me
                if ($this->args[0] == "me") {
                    return "me";
                }

                //  /api/v1/workout/list
                $stmt = DB::getInstance()->prepare('
				   SELECT * FROM workout');

                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_CLASS, 'Workout');

                return $result;
            }

            //  /api/v1/workout/{{id}}
            if ($this->args[0]) {
                $params = array(':id' => $this->args[0]);

                $stmt = DB::getInstance()->prepare('
				   SELECT * FROM workout
				   WHERE id = :id');

                $stmt->execute($params);
                $result = $stmt->fetchAll(PDO::FETCH_CLASS, 'Workout');

                return $result;
            }

        } else if ($this->method == 'POST') {
            if ($this->args[0]) {

                //   /api/v1/workout/{{id}}/{{workoutId}}
                if ($this->args[1]) {
                    return 'copy to workout';
                }

                //   /api/v1/workout/{{id}}
                return 'add workout to account';
            }
            //   /api/v1/workout/
            return 'create workout';

        } else if ($this->method == 'PUT') {
            if ($this->args[0]) {

                //   /api/v1/workout/{{id}}
            }

        } else if ($this->method == 'DELETE') {
            if ($this->args[0]) {
                //   /api/v1/workout/{{id}}
            }
        }

        error("404.2");
    }

    /**
     * /api/v1/exercise/list                GET - взять все упражнения.
     * /api/v1/exercise/list/me             GET - взять все упражнения из аккаунта.
     * /api/v1/exercise/{{id}}              GET - взять конкретное упражнение.
     * /api/v1/exercise/{{id}}/{{workoutId}} POST - добавить упражнение в тренировку.
     * /api/v1/exercise/{{id}}/{{workoutId}} DELETE - удалить упражнение из тренировки.
     */
    protected function exercise() {
        if ($this->method == 'GET') {
            if ($this->verb == 'list') {

                //  /api/v1/exercise/list/me
                if ($this->args[0] == "me") {
                    return "me";
                }

                //  /api/v1/exercise/list
                $stmt = DB::getInstance()->prepare('
				   SELECT * FROM exercise');

                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_CLASS, 'Exercise');

                return $result;
            }

            //  /api/v1/exercise/{{id}}
            if ($this->args[0]) {
                $params = array(':id' => $this->args[0]);

                $stmt = DB::getInstance()->prepare('
				   SELECT * FROM exercise
				   WHERE id = :id');

                $stmt->execute($params);
                $result = $stmt->fetchAll(PDO::FETCH_CLASS, 'Exercise');

                return $result;
            }

        } else if ($this->method == 'POST') {
            //   /api/v1/exercise/{{id}}/{{workoutId}}

        } else if ($this->method == 'DELETE') {
            //   /api/v1/exercise/{{id}}/{{workoutId}}

        }

        error("404.2");
    }

    /**
     * sport_type=1&muscule_group=2
     */
    private function filter() {

    }
}

?>