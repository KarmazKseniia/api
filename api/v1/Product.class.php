<?php
class Product
{
    public $id;             // unsigned bigint
    public $title;          // varchar 255
    public $proteins;       // float
    public $fats;           // float
    public $carbohydrates;  // float
    public $kcal;           // float
    public $icon;           // varchar 255

    public $mesurement;     // enum('кг', 'л', 'ст.л.', 'ч.л.', 'стак.')
    //public $categotyId;

    // GET: '/api/v1/product/{{id}}'
    public static function get($id) {
        $db = DB::getInstance();

        $params = array(':id' => $id);

        $stmtGetProduct = $db->prepare('
		   SELECT * FROM product
		   WHERE id = :id');

        $stmtGetProduct->execute($params);
        $result = $stmtGetProduct->fetchAll(PDO::FETCH_CLASS, 'Product');

        return $result;
    }

    // GET: '/api/v1/product/list'
    public static function getList() {
        $db = DB::getInstance();

        $stmtGetProductList = $db->prepare('
		   SELECT * FROM product');

        $stmtGetProductList->execute();
        $result = $stmtGetProductList->fetchAll(PDO::FETCH_CLASS, 'Product');

        return array('products' => $result);
    }

    // POST: '/api/v1/product'
	// BODY: title, proteins, fats, carbohydrates, kcal
    public static function add($params) {
        $db = DB::getInstance();
	
        $params = array(
			':title' => $params->title,
			':proteins' => $params->proteins,
			':fats' => $params->fats,
			':carbohydrates' => $params->carbohydrates,
			':kcal' => $params->kcal
        );

        $stmtAddProduct = $db->prepare('
			   INSERT INTO product (title, proteins, fats, carbohydrates, kcal)
			   VALUES (:title, :proteins, :fats, :carbohydrates, :kcal)');

        $stmtAddProduct->execute($params);
        $productId = $db->lastInsertId();

        return array('productId' => $productId);
    }
	
	// PUT: '/api/v1/product/{{id}}'
    // BODY: title, proteins, fats, carbohydrates, kcal
	public static function update($id, $params) {
	$db = DB::getInstance();

        $params = array(
			':id' => $id,
			':title' => $params->title,
			':proteins' => $params->proteins,
			':fats' => $params->fats,
			':carbohydrates' => $params->carbohydrates,
			':kcal' => $params->kcal
        );

        $stmtUpdateProduct = $db->prepare('
			   UPDATE product 
			   SET title = :title, proteins = :proteins, fats = :fats, carbohydrates = :carbohydrates, kcal = :kcal
			   WHERE id = :id');

        return $stmtUpdateProduct->execute($params);
	}
	
	// DELETE: '/api/v1/product/{{id}}'
    public static function delete($id) {
		$db = DB::getInstance();
		$params = array(':id' => $id);
		
        $stmtDeleteProduct = $db->prepare('
		   DELETE FROM product
		   WHERE id = :id');

        return $stmtDeleteProduct->execute($params);
	}
}

?>
