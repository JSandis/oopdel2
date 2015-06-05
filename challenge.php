<?php

include_once("nodebite-swiss-army-oop.php");

$dbos = new DBObjectSaver(array(
    "host" => "127.0.0.1",
    "dbname" => "wu14oop2",
    "username" => "root",
    "password" => "mysql",
    "prefix" => "snake_wars",
));

if (isset($_REQUEST["offer_challenge"])) {
	$challenges = $dbos->challenges;
	$random_index = rand(0, count($challenges)-1);

	$challenge_data = array(
		"name" => $challenges[$random_index]->name,
		"description" => $challenges[$random_index]->description
	);

	$dbos->current_challenge[0] = $challenges[$random_index];

	echo json_encode($challenge_data);
}

if (isset($_REQUEST["change_challenge"])) {
	$dbos->human[0]->success -=5;

	$challenges = $dbos->challenges;
	$random_index = rand(0, count($challenges)-1);

	$challenge_data = array(
		"name" => $challenges[$random_index]->name,
		"description" => $challenges[$random_index]->description
	);

	$dbos->current_challenge[0] = $challenges[$random_index];

	echo json_encode($challenge_data);
}

if (isset($_REQUEST['do_challenge_alone'])) {
	$human = $dbos->human[0];
	$bots = $dbos->bots;
	$current_challenge = $dbos->current_challenge[0];
	$human_won = false;

	$human_score = $current_challenge->howGoodAMatch($human);

	$bots_score = array();
	for ($i = 0; $i < count($bots); $i++) {
		if ($bots[$i]->success > 0) {
			$bots_score[$i] = $current_challenge->howGoodAMatch($bots[$i]);
		} else {
			$bots_score[$i] = 0;
		}
		if (isset($winning_bot_index)) {
			if ($human_score < $bots_score[$i] && $bots_score[$winning_bot_index] < $bots_score[$i]) {
				$winning_bot_index = $i;
			} elseif ($human_score > $bots_score[$i] && $human_score > $bots_score[$winning_bot_index]) {
				$human_won = true;
			} 
		} else {
			if ($human_score > $bots_score[$i]) {
				$human_won = true;
			} else {
				$winning_bot_index = $i;
				$human_won = false;
			}
		}
	}

	$return_string = "";
	if($human_won) {
		for ($i = 0; $i < count($bots); $i++) {
			//if (not second place) { WHEN MORE THAN 2 BOTS
				//$bots[$i]->success -= 5;
			//}

			if(count($bots[$i]->tools) > 0) {
				//remove a tool
				unset($bots[$i]->tools[0]); 
			}
		}

		//temporary fix
		if($bots_score[0] > $bots_score[1]) {
			$bots[1]->success -= 5;
		} elseif($bots_score[0] < $bots_score[1]) {
			$bots[0]->success -= 5;
		} //otherwise nothing will happen

		$human->success += 15;
		$return_string = "You won the challenge! The bot that came second lost a tool, and the other looser lost a tool and 5 success points";
	} else {
		$looser_bot_index = 0; //temporary fix
		for ($i = 0; $i < count($bots); $i++) {
			if($i === $winning_bot_index) {
				$bots[$i]->success += 15;
			} else {
				//if (not second place) { WHEN MORE THAN 2 BOTS
				if($bots_score[$i] < $human_score) { //temporary fix
					$bots[$i]->success -= 5;
				}
				if(count($bots[$i]->tools) > 0) {
					//remove a tool
					unset($bots[$i]->tools[0]); 
				}

				$looser_bot_index = $i; //temporary fix
			}
		}
		//if (not second place) { WHEN MORE THAN 2 BOTS
		if($bots_score[$looser_bot_index] < $human_score) { //temporary fix
			$human->success -= 5;
		}

		if(count($human->tools) > 0) {
			//remove a tool
			unset($human->tools[0]);
		}

		$return_string = $bots[$winning_bot_index]->name." won this challenge and received 15 success points. You came second and lost a tool, and the other looser lost a tool and 5 success points.";
	}
	echo json_encode($return_string);
}

if(isset($_REQUEST['team_up'])) {
	$human = $dbos->human[0];
	$bots = $dbos->bots;
	$current_challenge = $dbos->current_challenge[0];
	$team_won = false;

	//Teaming up costs 5 success points
	$human->success -=5;

	//Team up with random opponent
	$random_index = rand(0, count($bots)-1);
	$dbos->team[] = New Team($human, $bots[$random_index]);
	$team = $dbos->team[0];

	$team_score = $current_challenge->howGoodAMatch($team);

	$bots_left = array();
	for ($i = 0; $i < count($bots); $i++) {
		if($i !== $random_index) {
			$bots_left[] = $bots[$i];
		}
	}

	for ($i = 0; $i < count($bots_left); $i++) {
		if ($bots_left[$i]->success > 0) {
			$bots_score[$i] = $current_challenge->howGoodAMatch($bots_left[$i]);
		} else {
			$bots_score[$i] = 0;
		}
		if (isset($winning_bot_index)) {
			if ($team_score < $bots_score[$i] && $bots_score[$winning_bot_index] < $bots_score[$i]) {
				$winning_bot_index = $i;
			} elseif ($team_score > $bots_score[$i] && $team_score > $bots_score[$winning_bot_index]) {
				$team_won = true;
			} 
		} else {
			if ($team_score > $bots_score[$i]) {
				$team_won = true;
			} else {
				$winning_bot_index = $i;
				$team_won = false;
			}
		}
	}

	if($team_won) {
		for ($i = 0; $i < count($bots); $i++) {
			if($i !== $random_index) {
				//if (not second place) { WHEN MORE THAN 2 BOTS
					$bots[$i]->success -= 5;
				//}
				if(count($bots[$i]->tools) > 0) {
					//remove a tool
					unset($bots[$i]->tools[0]);
				}

			}
		}
		$human->success += 9;
		$bots[$random_index]->success += 9;
		
		$return_string = "The team won the challenge! Both of you received 9 points each, the player on second place lost a tool and the loosers lost a tool and 5 success points each.";
	} else {
		for ($i = 0; $i < count($bots); $i++) {
			if($i === $winning_bot_index) {
				$bots[$i]->success += 15;
			} else {
				//if (not second place) { WHEN MORE THAN 2 BOTS
					//$bots[$i]->success -= 5;
				//}
				if(count($bots[$i]->tools) > 0) {
					//remove a tool
					unset($bots[$i]->tools[0]); 
				}
			}
		}
		//if (not second place) { WHEN MORE THAN 2 BOTS
			//$human->success -= 5;
		//}
		if(count($human->tools) > 0) {
			//remove a tool
			unset($human->tools[0]);
		}

		$return_string = $bots[$winning_bot_index]->name." won this challenge and received 15 success points. You and the other looser in the team lost a tool each.";
	}
	echo json_encode($return_string);
	unset($dbos->team);
}

/*function addChallenge() {
	$challenges = $dbos->challenges;
	$random_index = rand(0, count($challenges)-1);

	$challenge_data = array(
		"name" => $challenges[$random_index]->name,
		"description" => $challenges[$random_index]->description
	);

	$dbos->current_challenge[0] = $challenges[$random_index];
	return $challenge_data;
}*/