<?php

include_once("nodebite-swiss-army-oop.php");

$dbos = new DBObjectSaver(array(
    "host" => "127.0.0.1",
    "dbname" => "wu14oop2",
    "username" => "root",
    "password" => "mysql",
    "prefix" => "snake_wars",
));

//Get opponents (bots) data
if (isset($_REQUEST["get_opponents"])) {
    
    $bots_data = array();
    $bots = $dbos->bots;
    for ($i = 0; $i < count($bots); $i++) {
        $bots_data[] = array(
            "name" => $bots[$i]->name,
            "info" => array(
                "Type" => $bots[$i]->class,
                "Strength" => $bots[$i]->strength,
                "Toxicity" => $bots[$i]->toxicity, 
                "Speed" => $bots[$i]->speed,
                "Energy" => $bots[$i]->energy,
                "Success" => $bots[$i]->success,
                "Tools" => $bots[$i]->tools
            )
        );
    }
    echo json_encode($bots_data);
}

if(isset($_REQUEST["current_standings"])) {
    $human = &$dbos->human[0];
    $bots = &$dbos->bots;
    $data = array();
    $winner = '';
    $bot_died = true;

    $data[] = array(
        "name" => $human->name,
        "info" => array(
            "Type" => $human->class,
            "Strength" => $human->strength,
            "Toxicity" => $human->toxicity, 
            "Speed" => $human->speed,
            "Energy" => $human->energy,
            "Success" => $human->success,
            "Tools" => $human->tools
        )
    );
    if($human->success === 100) {
        $winner = $human->name;
    }

    for ($i = 0; $i < count($bots); $i++) {
        $data[] = array(
            "name" => $bots[$i]->name,
            "info" => array(
                "Type" => $bots[$i]->class,
                "Strength" => $bots[$i]->strength,
                "Toxicity" => $bots[$i]->toxicity, 
                "Speed" => $bots[$i]->speed,
                "Energy" => $bots[$i]->energy,
                "Success" => $bots[$i]->success,
                "Tools" => $bots[$i]->tools
            )
        );
        if ($bots[$i]->success === 100) {
            $winner = $bots[$i]->name;
        } elseif ($bots[$i]->success === 0 && $bot_died) {
            $bot_died = true;
        } else {
            $bot_died = false;
        }
    }

    if ($winner !== '') {
        $data[] = array(
            "name" => "Winner",
            "info" => $winner
        );
    } elseif ($human->success === 0 || $human->energy === 0) {
        $data[] = array(
            "name" => "GAME OVER",
            "info" => "Human player dead."
        );
    } elseif ($bot_died) {
        $data[] = array(
            "name" => "One of your opponents died!",
            "info" => "Carry on."
        );
    } else {
        $data[] = array(
            "name" => "One of your opponents won",
            "info" => "Go on to the next challenge."
        );
    }
    echo json_encode($data);
}