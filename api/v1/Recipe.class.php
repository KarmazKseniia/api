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

        $stmtGetRecipe = $db->prepare('
		   SELECT * FROM recipe
		   WHERE id = :id');

        $stmtGetRecipe->execute($params);
        $recipe = $stmtGetRecipe->fetchAll(PDO::FETCH_CLASS, 'Recipe');

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
        $stmtGetRecipeList = $db->prepare('
		   SELECT * FROM recipe');

        $stmtGetRecipeList->execute();
        $result = $stmtGetRecipeList->fetchAll(PDO::FETCH_CLASS, 'Recipe');

        return $result;
    }

    // POST: '/api/v1/recipe'
    // BODY: array(title, description, steps, 
	// 			ingredients[productId, amount])
    public static function add($params) {
        $db = DB::getInstance();
        $stmtAddRecipe = $db->prepare('
               INSERT INTO recipe (title, description, steps)
               VALUES (:title, :description, :steps)');

        $stmtAddIngredient = $db->prepare('
               INSERT INTO recipeproductlist (recipeId, productId, amount)
               VALUES (:recipeId, :productId, :amount)');

        
        $stmtAddRecipe->execute(array(
            ':title' => $params["title"],
            ':description' => $params["description"],
            ':steps' => $params["steps"]
        ));
        $recipeId = $db->lastInsertId();
		
		try {
            $db->beginTransaction();

            foreach ($params["ingredients"] as $ingredient) {
                $result = $stmtAddIngredient->execute(array(
                    ':recipeId' => $recipeId,
                    ':productId' => $ingredient["id"],
                    ':amount' => $ingredient["amount"]
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

	// PUT: '/api/v1/recipe/{{id}}'
    // BODY: array(title, description, steps, 
	// 			ingredientsToDelete[productId], 
	// 			ingredientsToAdd[productId, amount])
	// 			ingredientsToUpdate[productId, amount])
	public static function update($id, $params) {
		$db = DB::getInstance();
        $stmtUpdateRecipe = $db->prepare('
               UPDATE recipe
			   SET title = :title, description = :description, steps = :steps
			   WHERE id=:id');

		$stmtDeleteIngredient = $db->prepare('
               DELETE FROM recipeproductlist
               WHERE recipeId = :recipeId and productId = :productId');

		$stmtAddIngredient = $db->prepare('
               INSERT INTO recipeproductlist (recipeId, productId, amount)
               VALUES (:recipeId, :productId, :amount)');
			   
        $stmtUpdateIngredient = $db->prepare('
               UPDATE recipeproductlist
			   SET amount = :amount
			   WHERE recipeId = :recipeId && productId = :productId');
			
        
        $stmtUpdateRecipe->execute(array(
			':id' => $id,
            ':title' => $params["title"],
            ':description' => $params["description"],
            ':steps' => $params["steps"]
        ));
		
		foreach ($params["ingredientsToDelete"] as $ingredient) {
			$stmtDeleteIngredient->execute(array(
				':recipeId' => $id,
				':productId' => $ingredient["id"]
			));
		}
		
        foreach ($params["ingredientsToAdd"] as $ingredient) {
            $stmtAddIngredient->execute(array(
                ':recipeId' => $id,
                ':productId' => $ingredient["id"],
                ':amount' => $ingredient["amount"]
            ));
        }

		foreach ($params["ingredientsToUpdate"] as $ingredient) {
            $stmtUpdateRecipe->execute(array(
                ':recipeId' => $id,
                ':productId' => $ingredient["id"],
                ':amount' => $ingredient["amount"]
            ));
        }
	}
	
	// DELETE: '/api/v1/recipe/{{id}}'
    public static function delete($id) {
		$db = DB::getInstance();
		$params = array(':id' => $id);
		
        $stmtDeleteRecipe = $db->prepare('
		   DELETE FROM recipe
		   WHERE id = :id');

        return $stmtDeleteRecipe->execute($params);
	}
}
?>
