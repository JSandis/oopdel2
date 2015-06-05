<?php

include_once("nodebite-swiss-army-oop.php");

$dbos = new DBObjectSaver(array(
	"host" => "127.0.0.1",
	"dbname" => "wu14oop2",
	"username" => "root",
	"password" => "mysql",
	"prefix" => "snake_wars"
));

if (isset($_REQUEST["win_tool"])) {
	$human = &$dbos->human[0];
	
	if (count($human->tools) < 3) {
    	$random_number = rand(0,count($dbos->tools)-1);

    	$random_tool = $dbos->tools[$random_number];

    	$human->tools[] = $random_tool->description;

    	$tool_strength = $random_tool->skills["strength"];
    	$tool_toxicity = $random_tool->skills["toxicity"];
    	$tool_speed = $random_tool->skills["speed"];
    	$tool_energy = $random_tool->skills["energy"];
    	$tool_name = $random_tool->description;

    	$human_strength = $human->strength += $tool_strength;
    	if ($human_strength < 0) {
    		$human_strength = 0;
    	} elseif ($human_strength > 100) {
    		$human_strength = 100;
    	}
    	$human_toxicity = $human->toxicity += $tool_toxicity;
    	if ($human_toxicity < 0) {
    		$human_toxicity = 0;
    	} elseif ($human_toxicity > 100) {
    		$human_toxicity = 100;
    	}
    	$human_speed = $human->speed += $tool_speed;
    	if ($human_speed < 0) {
    		$human_speed = 0;
    	} elseif ($human_speed > 100) {
    		$human_speed = 100;
    	}
    	$human_energy = $human->energy += $tool_energy;
    	if ($human_energy < 0) {
    		$human_energy = 0;
    	} elseif ($human_energy > 100) {
    		$human_energy = 100;
    	}
		$human_data = array(
			"Tool name" => $tool_name,
			"Name" => $human->name,
			"Type" => $human->class,
			"Strength" => $human_strength, 
			"Toxicity" => $human_toxicity, 
			"Speed" => $human_speed,
			"Energy" => $human_energy,
			"Success" => $human->success,
			"Tools" => $human->tools
		);
		echo json_encode($human_data);
    } else {
    	echo json_encode("You will not receive more tools since you already have three.");
    }
} 