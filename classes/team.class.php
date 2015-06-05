<?php

class Team extends Snake {

  public $members = array();

  public $strength;
  public $toxicity;
  public $speed;
  public $energy;
  public $tools = array();

  public function __construct($human, $bot) {
    $this->members[] = $human;
    $this->members[] = $bot;

    //strengths
    $this->strength = $human->strength + $bot->strength;
    $this->toxicity = $human->toxicity + $bot->toxicity;
    $this->speed = $human->speed + $bot->speed;
    $this->energy = $human->energy + $bot->energy;

    //tools
    for ($i=0; $i < count($this->members); $i++) { 
      for ($j=0; $j < count($this->members[$i]->tools); $j++) { 
        $this->tools[] = $this->members[$i]->tools[$j];
      }
    }

    //call the parent class (Snake) __construct to set name of team
    parent::__construct($name);
  }
}