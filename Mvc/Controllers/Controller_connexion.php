<?php
session_start();
  class Controller_connexion extends Controller{
    public function action_default(){
      $this->action_connexion();

    }

    public function action_connexion(){
      $data = [];
      $this-> render("connexion", $data);
    }

    public function action_verification(){
      $m=Model::getModel();
      //on recup les infos de lutilisateur, son mdp et son mail
      $mail = $_POST["mail"];
      $mdp = $_POST["mdp"];

      $info = $m->verifco($mail,$mdp);

      if($info == null){
        $this->render("connexion",$data);
      }
      else{
        $_SESSION['id'] = $info['id'];
        $_SESSION['mail'] = $info['mail'];
        $_SESSION['mdp'] = $info['mdp'];



        $this->render("accueil_test",$info);
      }


      //mtn on va verifier des choses samire

      //regardeons dabbord si le mail existe

      //si il existe pas
      //on appel un truc une vue ou jsp qui va dire que ya une erreur

/*
      //si il existe
      else{
        //si le mdp est le bon
        if($mdp_la_meme){
          //bah on lui ouvre un session tavu
          session_start();
          $_SESSION['mail'] = $mail;
          //$_SESSION['mdp'] = $info['mdp'];

          //test
          echo('al hemdoulilah');
          $this->render("accueil",$info);
        }

        //sinon on lui dis rentre chez toi
        else{
          //renvois une erreur jsp encore comment

          //test
          echo('erreur 2 uesh');
          $this->render("connexion",$info);
        }
      }
      //test
      echo($_SESSION['mail']);

    }
*/

  }
}
?>
