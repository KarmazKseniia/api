<?php
class Recipe {
    public $id;                 // unsigned bigint
    public $title;              // varchar 255
    public $description;        // text
    public $steps;              // text
    public $productList;        // [Product]
    public $complexity;         // unsigned tinyint
    public $preparationTime;    // time
    public $cookingTime;        // time

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

        $recipe["productList"] = $productList;

        return array('recipe' => $recipe);
    }

    // GET: '/api/v1/recipe/list'
    public static function getList() {
        $db = DB::getInstance();
        $stmtGetRecipeList = $db->prepare('
		   SELECT * FROM recipe');

        $stmtGetRecipeList->execute();
        $result = $stmtGetRecipeList->fetchAll(PDO::FETCH_CLASS, 'Recipe');

        return array('recipes' => $result);
    }

    // POST: '/api/v1/recipe'
    // BODY: array(title, description, steps, 
	// 			ingredients[productId, amount])
    public static function add($params) {
        $db = DB::getInstance();
        $stmtAddRecipe = $db->prepare('
               INSERT INTO recipe (title, description, steps, complexity, preparationTime, cookingTime)
               VALUES (:title, :description, :steps, :complexity, :preparationTime, :cookingTime)');

        $stmtAddIngredient = $db->prepare('
               INSERT INTO recipeproductlist (recipeId, productId, amount)
               VALUES (:recipeId, :productId, :amount)');

        
        $stmtAddRecipe->execute(array(
            ':title' => $params->title,
            ':description' => $params->description,
            ':steps' => $params->steps,
            ':complexity' => $params->complexity,
            ':preparationTime' => $params->preparationTime,
            ':cookingTime' => $params->cookingTime
        ));
        $recipeId = $db->lastInsertId();
		
		try {
            $db->beginTransaction();

            foreach ($params->ingredients as $ingredient) {
                $result = $stmtAddIngredient->execute(array(
                    ':recipeId' => $recipeId,
                    ':productId' => $ingredient->id,
                    ':amount' => $ingredient->amount
                ));
                if (!$result) new PDOException();
            }

            $db->commit();
        } catch(PDOException $e) {
            $db->rollback();
            error("404.2");
        }
		
		return array( 'recipeId' => $recipeId );
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
			   SET title = :title, description = :description, steps = :steps, complexity = :complexity,
			          preparationTime = :preparationTime, cookingTime = :cookingTime
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
			
        
        $result = $stmtUpdateRecipe->execute(array(
			':id' => $id,
            ':title' => $params->title,
            ':description' => $params->description,
            ':steps' => $params->steps,
            ':complexity' => $params->complexity,
            ':preparationTime' => $params->preparationTime,
            ':cookingTime' => $params->cookingTime
        ));
		
		if (!$result) return array( "result" => false );
		
		try { 
		  $db->beginTransaction();
		  
		  foreach ($params->ingredientsToDelete as $ingredient) {
		  	$stmtDeleteIngredient->execute(array(
		  		':recipeId' => $id,
		  		':productId' => $ingredient->id
		  	));
		  }
		  
          foreach ($params->ingredientsToAdd as $ingredient) {
              $stmtAddIngredient->execute(array(
                  ':recipeId' => $id,
                  ':productId' => $ingredient->id,
                  ':amount' => $ingredient->amount
              ));
          }
          
		  foreach ($params->ingredientsToUpdate as $ingredient) {
              $stmtUpdateRecipe->execute(array(
                  ':recipeId' => $id,
                  ':productId' => $ingredient->id,
                  ':amount' => $ingredient->amount
              ));
          }
		  
		  $db->commit();
		  return array( "result" => true );
		  
		} catch(PDOExecption $e) { 
			$db->rollback(); 
			return array( "result" => false ); 
		}
	}
	
	// DELETE: '/api/v1/recipe/{{id}}'
    public static function delete($id) {
		$db = DB::getInstance();
		$params = array(':id' => $id);
		
        $stmtDeleteRecipe = $db->prepare('
		   DELETE FROM recipe
		   WHERE id = :id');

        return array( "result" => $stmtDeleteRecipe->execute($params) );
	}
}
?>
