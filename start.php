<?php

include_once("nodebite-swiss-army-oop.php");

$dbos = new DBObjectSaver(array(
    "host" => "127.0.0.1",
    "dbname" => "wu14oop2",
    "username" => "root",
    "password" => "mysql",
    "prefix" => "snake_wars",
));

$number_of_bots = 2;

/* Create three players */
if (isset($_REQUEST["player_name"]) && isset($_REQUEST["player_class"])) {
    unset($dbos->human);
    unset($dbos->bots);
    unset($dbos->tools);
    unset($dbos->current_challenge);
    unset($dbos->challenges);
    unset($dbos->team);

    $player_name = $_REQUEST["player_name"];
    $player_class = $_REQUEST["player_class"];
    
    //Human player
    if (count($dbos->human[0]) === 0) {
        $dbos->human[] = new $player_class($player_name);
        $human = &$dbos->human[0];
        $human_data = array(
            "Name" => $human->name,
            "Type" => $human->class,
            "Strength" => $human->strength, 
            "Toxicity" => $human->toxicity, 
            "Speed" => $human->speed,
            "Energy" => $human->energy,
            "Success" => $human->success,
            "Tools" => $human->tools
        );
        echo json_encode($human_data);
    }
}

/* If no bots create two computer players (bots) */
if (count($dbos->bots) === 0) {
    $human = &$dbos->human[0];
    $available_classes = array("BallPython", "BlackMamba", "CornSnake", "GreenAnaconda");
    $random_class = get_class($human);

    for ($i = 0; $i < $number_of_bots; $i++) {
        while ($random_class === get_class($human) || (count($dbos->bots) > 0 && $random_class === get_class($dbos->bots[$i-1]))) {
            $random_class = $available_classes[array_rand($available_classes, 1)];
        }
        $dbos->bots[] = new $random_class("Bot".($i+1));
    }
}

/* Create ten challenges */
if (count($dbos->challenges) === 0) {
    for ($i=0; $i < 10; $i++) {
        $challenge_json_data = file_get_contents("data/challenge".$i.".json");
        $challenge_data = json_decode($challenge_json_data, true);
        $dbos->challenges[] = New Challenge($challenge_data["name"], $challenge_data);
    }
}

/* If no tools in db create nine tools */
if (count($dbos->tools) === 0) {
    $available_strengths = array("strength", "toxicity", "speed", "energy");
    $random_strength = $available_strengths[array_rand($available_strengths, 1)];
    $tools = array(
        array(
            "description" => "Suprise Potion",
            "skills" => array(
                $random_strength => rand(-100,100),
            ),
        ),
        array(
            "description" => "Poison Potion",
            "skills" => array(
                "toxicity" => 20,
            ),
        ),
        array(
            "description" => "Poison Removal",
            "skills" => array(
                "toxicity" => -100,
            ),
        ),
        array(
            "description" => "Red Bull",
            "skills" => array(
                "energy" => 40
            ),
        ),
        array(
            "description" => "Magic Power",
            "skills" => array(
                "strength" => 100,
                "toxicity" => 100,
                "speed" => 100,
                "energy" => 100
            ),
        ),
        array(
            "description" => "Death Potion",
            "skills" => array(
                "strength" => -100,
                "toxicity" => -100,
                "speed" => -100,
                "energy" => -100
            ),
        ),
        array(
            "description" => "Sleeping Pills",
            "skills" => array(
                "energy" => -50
            ),
        ),
        array(
            "description" => "Super Strength",
            "skills" => array(
                "strength" => 100
            ),
        ),
        array(
            "description" => "Speed Potion",
            "skills" => array(
                "speed" => 50
            ),
        ),
    );

    //Create tools
    for ($i=0; $i < count($tools); $i++) { 
        $dbos->tools[] = New Tool($tools[$i]);
    }
}