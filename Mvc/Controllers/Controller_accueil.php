<?php
class Controller_accueil extends Controller{
  public function action_default(){
      $this->action_accueil();
  }

  public function action_accueil(){
    //$m = Model::getModel();
    $data = [];
    $this-> render("accueil",$data);
  }
}
?>
