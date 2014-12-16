<?php
class Recipe
{
    public $id; // unsigned bigint
    public $title; // varchar 255
    public $description; // text
    public $steps; // text
    public $productList; // [Product]


    // GET: '/api/v1/recipe/{{id}}'
    public static function get($pdo, $id)
    {
        $params = array(':id' => $id);

        $stmtRecipe = $pdo->prepare('
		   SELECT * FROM recipe
		   WHERE id = :id');

        $stmtRecipe->execute($params);
        $recipe = $stmtRecipe->fetchAll(PDO::FETCH_CLASS, 'Recipe');

        $stmtProductList = $pdo->prepare('
		   SELECT * FROM recipeproductlist
		   WHERE recipeId = :id');

        $stmtProductList->execute($params);
        $productList = $stmtProductList->fetchAll();

        $recipe->productList = $productList;

        return $recipe;
    }

    // GET: '/api/v1/recipe/list'
    public static function getList($pdo)
    {
        $stmt = $pdo->prepare('
		   SELECT * FROM recipe');

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_CLASS, 'Recipe');

        return $result;
    }

    // POST: '/api/v1/recipe'
    public static function add($pdo)
    {
        /*$paramsRecipe = array(
            ':title' => $this->args[0],
            ':description' => $this->args[1],
            ':steps' => $this->args[2]
        );
        $stmtRecipe = $pdo->prepare('
               INSERT INTO recipe (title, description, steps)
               VALUES (:title, :description, :steps)');

        $stmtProductList = $pdo->prepare('
               INSERT INTO recipeproductlist (recipeId, productId, amount)
               VALUES (:recipeId, :productId, :amount)'); // TODO: should be in foreach

        try {
            $pdo->beginTransaction();
            $stmtRecipe->execute($paramsRecipe);
            $recipeId = $pdo->lastInsertId();

            $stmtProductList->execute($paramsProductList);

            $pdo->commit();

            return array( 'recipeId' => $recipeId);
        } catch(PDOExecption $e) {
            $pdo->rollback();
            print "Error!: " . $e->getMessage() . "</br>";
        } */
    }
}

?>
