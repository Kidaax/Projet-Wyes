<?php
  class Controller_boutique extends Controller{
    public function action_default(){
      $this->action_boutique();
    }

    public function action_default(){
      $data = [];
      $this->render("boutique",$data);
    }
  }
?>
