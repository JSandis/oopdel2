<?php

include_once("nodebite-swiss-army-oop.php");

$dbos = new DBObjectSaver(array(
	"host" => "127.0.0.1",
	"dbname" => "wu14oop2",
	"username" => "root",
	"password" => "mysql",
	"prefix" => "snake_wars"
));

if (isset($_REQUEST["restart"])) {
	unset($dbos->human);
	unset($dbos->bots);
	unset($dbos->tools);
	unset($dbos->current_challenge);
	unset($dbos->challenges);
	unset($dbos->team);
	
	echo json_encode(true);
}