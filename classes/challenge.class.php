<?php

class Challenge extends Base {

    protected $skills;
    protected $description;
    protected $name;
    
    public function __construct($name, $challenge_data) {
        $this->name = $name;
        $this->skills = $challenge_data['skills'];
        $this->description = $challenge_data['description'];
    }

    public function get_skills() {
        return $this->skills;
    }

    public function get_description() {
        return $this->description;
    }

    public function get_name() {
        return $this->name;
    }

    public function howGoodAMatch($player) {
        //total points a player has
        $player_total_points = 0;
        //max points possible for this challenge
        $max_points = 0;

        foreach($this->skills as $skill => $points){
            //how many skill points that are needed
            $needed_points = $points;
            //how many points a player has in each skill
            $player_skill_points = $player->{$skill};

            if (count($player->tools) > 0) {
                for ($i = 0; $i < count($player->tools); $i++) {
                    foreach ($player->tools[$i]->skills as $tool_skill => $value) {
                        if ($tool_skill === $skill) {
                            $player_skill_points += $value;
                        }
                    }
                } 
            }

            $player_total_points += $player_skill_points > $needed_points ? $needed_points : $player_skill_points;
            $max_points += $needed_points;
        }
        $percentage_of_points = $max_points > 0 ? round($player_total_points/$max_points, 4) * 100 : 0;
        //return the percentage of skill points they have
        return $percentage_of_points;
    }
}