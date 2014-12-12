<?php
class Workout {
	public $id;			// unsigned long
	public $exercises; 	// [Exercise] // при добавлении другого Workout, копируются айдишники всех упражнений
	public $title; 		// text
	public $goal; 		// text
	public $cover; 		// url string
	// $sportTypeIds и $muscleGroupIds формируются на основе входящих в нее упражнений / тренировок.
}
?>
