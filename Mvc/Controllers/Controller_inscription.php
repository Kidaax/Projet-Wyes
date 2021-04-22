<?php
  class Controller_inscription extends Controller{
    public function action_default(){
      $this->action_inscription();

    }

    public function action_inscription(){
      $data = [];
      $this-> render("choixInscription", $data);
    }


    public function action_vaSurInscription(){
      $data = [];
      $role = $_POST["role"];
      if($role === "medecin"){
        $this->render("inscription_medecin");
      }
      elseif ($role === "aidant"){
        $this->render("inscription_aidant");
      }
      else{
        $this->render("inscription_patiant");
      }

    }

    public function action_info(){
      $m = Model::getModel();
      $data = [];
      if((isset($_POST["nom"]) and !preg_match("#^\s*$#",$_POST["nom"]))
          and (isset($_POST["prenom"]) and !preg_match("#^\s*$#",$_POST["prenom"]))
          and (isset($_POST["mail"])) and (isset($_POST["mdp"])))
          {


          $m->add_info();
          $this-> render("accueil",$data);
          }
      else{
        $this->render("inscription_".$_POST['role'],$data);
      }
    }
  }
?>
