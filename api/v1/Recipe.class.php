<?php
class Recipe {
    public $id;          // unsigned bigint
    public $title;       // varchar 255
    public $description; // text
    public $steps;       // text
    public $productList; // [Product]


    // GET: '/api/v1/recipe/{{id}}'
    public static function get($id) {
        $db = DB::getInstance();
        $params = array(':id' => $id);

        $stmtRecipe = $db->prepare('
		   SELECT * FROM recipe
		   WHERE id = :id');

        $stmtRecipe->execute($params);
        $recipe = $stmtRecipe->fetchAll(PDO::FETCH_CLASS, 'Recipe');

        $stmtProductList = $db->prepare('
		   SELECT * FROM recipeproductlist
		   WHERE recipeId = :id');

        $stmtProductList->execute($params);
        $productList = $stmtProductList->fetchAll();

        $recipe->productList = $productList;

        return $recipe;
    }

    // GET: '/api/v1/recipe/list'
    public static function getList() {
        $db = DB::getInstance();
        $stmt = $db->prepare('
		   SELECT * FROM recipe');

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_CLASS, 'Recipe');

        return $result;
    }

    // POST: '/api/v1/recipe'
    // BODY: array(title, description, steps, productId[], productAmount[])
    public static function add($params) {
        $db = DB::getInstance();
        $stmtRecipe = $db->prepare('
               INSERT INTO recipe (title, description, steps)
               VALUES (:title, :description, :steps)');

        $stmtProductList = $db->prepare('
               INSERT INTO recipeproductlist (recipeId, productId, amount)
               VALUES (:recipeId, :productId, :amount)');

        try {
            $db->beginTransaction();

            $stmtRecipe->execute(array(
                ':title' => $params["title"],
                ':description' => $params["description"],
                ':steps' => $params["steps"]
            ));

            $recipeId = $db->lastInsertId();

            $db->commit();

            $db->beginTransaction();

            foreach ($params["productId"] as $i => $productId) {
                $result = $stmtProductList->execute(array(
                    ':recipeId' => $recipeId,
                    ':productId' => $productId,
                    ':amount' => $params["productAmount"][$i]
                ));
                if (!$result) new PDOException();
            }

            $db->commit();

            return array( 'recipeId' => $recipeId );

        } catch(PDOException $e) {
            $db->rollback();
            error("404.2");
        }
    }
}

?>
