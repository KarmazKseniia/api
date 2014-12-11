<?php
abstract class Exercise {
	public $id;				// unsigned long
	public $title; 			// string
	public $sportTypeIds; 	// [SPORT_TYPE]
	public $muscleGroupIds; // [MUSCULE_GROUP]
	public $goal; 			// text
 
	public function info() {
		return '#'.$this->id.': '.$this->title;
	}
}
?>
