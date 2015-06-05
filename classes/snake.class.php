<?php

class Snake extends Base {

    protected $strength = 0;
    protected $toxicity = 0;
    protected $speed = 0;
    protected $energy = 0;

    protected $name;
    protected $success = 50;
    protected $tools = array();

    public function __construct($name) {
        $this->name = $name;
    }

    public function get_strength() {
        return $this->strength;
    }

    public function set_strength($value) {
        if($value > 100) {
            $this->strength = 100;
        } elseif ($value < 0) {
            $this->strength = 0;
        } else {
            $this->strength = $value;
        }
    }

    public function get_toxicity() {
        return $this->toxicity;
    }

    public function set_toxicity($value) {
        if($value > 100) {
            $this->toxicity = 100;
        } elseif ($value < 0) {
            $this->toxicity = 0;
        } else {
            $this->toxicity = $value;
        }
    }

    public function get_speed() {
        return $this->speed;
    }

    public function set_speed($value) {
        if($value > 100) {
            $this->speed = 100;
        } elseif ($value < 0) {
            $this->speed = 0;
        } else {
            $this->speed = $value;
        }
    }

    public function get_energy() {
        return $this->energy;
    }

    public function set_energy($value) {
        if($value > 100) {
            $this->energy = 100;
        } elseif ($value < 0) {
            $this->energy = 0;
        } else {
            $this->energy = $value;
        }
    }

    public function get_name() {
        return $this->name;
    }

    public function get_success() {
        return $this->success;
    }

    public function set_success($val) {
      $this->success = $val;
    }

    public function get_tools() {
        return $this->tools;
    }

    public function set_tools() {
        return $this->tools;
    }

    public function get_class() {
        return get_class($this);
    }

    public function winTool() {

    }

    public function looseTool() {
        
    }

    public function acceptChallenge() {
        
    }

    public function changeChallenge() {

    }

    public function carryOutChallengeWithCompanion() {
        
    }
}