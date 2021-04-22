<?php
  session_start();

  class Controller_reception extends Controller{
    public function action_default(){
        $this->action_reception();
    }

    public function action_reception(){
      if(isset($_SESSION['id']) AND !empty($_SESSION['id'])) {
        $m = Model::getModel();
        $tab = $m->reception();
        $this->render("reception",$tab);
      }

    }

  }
?>
