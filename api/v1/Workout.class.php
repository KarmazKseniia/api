<?php
class Workout {
	public $id;			// unsigned bigint
	public $title; 		// varchar 255
	//public $exercises; 	// [Exercise] // при добавлении другого Workout, копируются айдишники всех упражнений
	public $goal; 		// text
	public $cover; 		// varchar 255
	// $sportTypeIds и $muscleGroupIds формируются на основе входящих в нее упражнений / тренировок.
}
?>
