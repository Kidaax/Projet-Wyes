<?php
session_start();
  class Controller_send extends Controller{
    public function action_default(){
      $this->action_send();
    }

    public function action_send(){
      $m = Model::getModel();
      $error ="";
      if(isset($_SESSION['id']) AND !empty($_SESSION['id'])) {

          if(isset($_POST['envoi_message'])) {

            if(isset($_POST['destinataire'],$_POST['message'])
              AND !empty($_POST['destinataire'])
              AND !empty($_POST['message'])) {

      $destinataire = htmlspecialchars($_POST['destinataire']);
      $message = htmlspecialchars($_POST['message']);
      $error = $m -> send($destinataire,$message);

      }
      else {
        $error = "Veuillez compléter tous les champs";
      }

    }
    $tab = ['error'=>$error];
    $this->render("send",$tab);
  }
}

}
?>
