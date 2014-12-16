<?php
class Product {
	public $id;				// unsigned bigint
	public $title; 			// varchar 255
	public $proteins; 		// float
	public $fats; 			// float
	public $carbohydrates; 	// float
	public $kcal; 			// float
	public $icon; 			// varchar 255
	
	//public $mesurement;
	//public $categotyId;
	
	// GET: '/api/v1/product/{{id}}'
	public static get($id) {
		$params = array(':id' => $id);
		
		$stmt = $this->pdo->prepare('
		   SELECT * FROM product
		   WHERE id = :id');
		 
		$stmt->execute($params);
		$result = $stmt->fetchAll(PDO::FETCH_CLASS, 'Product');
		
		return $result;
	}
	
	// GET: '/api/v1/product/list'
	public static getList() {
		$stmt = $this->pdo->prepare('
		   SELECT * FROM product');
		 
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_CLASS, 'Product');
		
		return $result;
	}
	
	// POST: '/api/v1/product'
	public static add() {
		$params = array(
			':title' => $this->args[0],
			':proteins' => $this->args[1],
			':fats' => $this->args[2],
			':carbohydrates' => $this->args[3],
			':kcal' => $this->args[4]
		);
		
		$stmt = $this->pdo->prepare('
			   INSERT INTO product (title, proteins, fats, carbohydrates, kcal)
			   VALUES (:title, :proteins, :fats, :carbohydrates, :kcal)');
		 
		$stmt->execute($params);
		$productId = $this->pdo->lastInsertId(); 
		
		return array( 'productId' => $productId);
	}
}
?>
