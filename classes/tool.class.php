 <?php

class Tool extends Base {

  protected $description;
  protected $skills;
  
  public function __construct($tools){
    $this->description = $tools["description"];
    $this->skills = $tools["skills"];
  }

  public function get_description() {
  return $this->description;
  }

	public function get_skills() {
  	return $this->skills;
	}

}
