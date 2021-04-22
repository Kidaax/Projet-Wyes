<?php

class Model
{


    /**
     * Attribut contenant l'instance PDO
     */
    private $bd;


    /**
     * Attribut statique qui contiendra l'unique instance de Model
     */
    private static $instance = null;


    /**
     * Constructeur : effectue la connexion à la base de données.
     */
    private function __construct()
    {

        try {
            include 'Utils/credentials.php';
            $this->bd = new PDO("$driver:host=$host;dbname=$dbname",$user,$login);
            $this->bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->bd->query("SET nameS 'utf8'");
        } catch (PDOException $e) {
            die('Echec connexion, erreur n°' . $e->getCode() . ':' . $e->getMessage());
        }
    }


    /**
     * Méthode permettant de récupérer un modèle car le constructeur est privé (Implémentation du Design Pattern Singleton)
     */
    public static function getModel()
    {

        if (is_null(self::$instance)) {
            self::$instance = new Model();
        }
        return self::$instance;
    }

    public function add_info(){
      try{
        $req = $this->bd->prepare('INSERT INTO users(mail,role,mdp) VALUES (:mail,:role,:mdp)');
        $reqId = $this->bd->prepare('SELECT id from users where mail=:mail ');

        if($_POST['role'] === "aidant"){
          $requete = $this->bd->prepare('INSERT INTO aidant(nom,prenom,genre,id) VALUES (:nom,:prenom,:genre,:id)');
        }
        elseif($_POST['role'] === "medecin"){
          $requete = $this->bd->prepare('INSERT INTO medecin(nom,prenom,genre,id) VALUES (:nom,:prenom,:genre,:id)');
        }

        else{
          $requete = $this->bd->prepare('INSERT INTO patiant(nom,prenom,genre,id) VALUES (:nom,:prenom,:genre,:id)');
        }

        $mdp = password_hash($_POST['mdp'],PASSWORD_DEFAULT);

        $req->execute(array(
            "mail" => $_POST["mail"],
            "role"=> $_POST["role"],
            "mdp" => $mdp));

        $reqId->execute(array("mail" => $_POST["mail"]));

        $id = $reqId->fetch();


        //Exécution de la requête
        $requete->execute(array(
            "nom" => $_POST["nom"],
            "prenom" => $_POST["prenom"],
            "genre"=> $_POST["genre"],
            "id" => $id['id']));
          }
      catch (PDOException $e) {
         die('Echec add_info, erreur n°' . $e->getCode() . ':' . $e->getMessage());
     }
   }



/**
* Retourne null si mdp ou mail incorrecte sinon on retourne les informations de la personne
*/
    public function verifco($mail,$mdp){
      try{

        $req = $this->bd->prepare('SELECT * from users where mail = :mail');
        $req->execute(array('mail' => $mail));

        $info = $req->fetch();
        if($info === false){
          return null;
        }
        else{
          $mdpBDD = $info['mdp'];
          $mdpEgaux =  password_verify($mdp, $mdpBDD);
          if($mdpEgaux === true){
            if($info['role'] === 'medecin'){
              $requete = $this->bd->prepare('SELECT nom,prenom from medecin where id = :id');
              $requete->execute(array('id' => $info['id']));
              $info2 = $requete->fetch();
            }

            if($info['role'] === 'patiant'){
              $requete = $this->bd->prepare('SELECT nom,prenom from patiant where id = :id');
              $requete->execute(array('id' => $info['id']));
              $info2 = $requete->fetch();
            }

            if($info['role'] === 'aidant'){
              $requete = $this->bd->prepare('SELECT nom,prenom from aidant where id = :id');
              $requete->execute(array('id' => $info['id']));
              $info2 = $requete->fetch();
            }

            return $infoPersonne = array_merge($info,$info2);
          }

          else{
            return null;
          }
        }

      }

      catch (PDOException $e) {
         die('Echec verifco, erreur n°' . $e->getCode() . ':' . $e->getMessage());
     }
    }

    public function send($destinataire,$message){

        try{
          $id_destinataire = $this->bd->prepare('SELECT id FROM users WHERE mail = ?');
          $id_destinataire->execute(array($destinataire));
          $dest_exist = $id_destinataire->rowCount();
          if($dest_exist == 1) {
             $id_destinataire = $id_destinataire->fetch();
             $id_destinataire = $id_destinataire['id'];
             $ins = $this->bd->prepare('INSERT INTO messages(id_expediteur,id_destinataire,message) VALUES (?,?,?)');
             $ins->execute(array($_SESSION['id'],$id_destinataire,$message));
             $error = "Votre message a bien été envoyé !";
          } else {
             $error = "Cet utilisateur n'existe pas...";
          }

           $destinataires = $this->bd->query('SELECT mail FROM users ORDER BY mail');
           return $error;
        }

        catch (PDOException $e) {
           die('Echec send, erreur n°' . $e->getCode() . ':' . $e->getMessage());
       }
    }

    public function reception(){
      try{
        //on recup les msg du mec connecter, donc en vrai cest le destinataire
        $msg = $this->bd->prepare('SELECT mail,id_destinataire,message FROM messages inner join users ON messages.id_expediteur = users.id where id_destinataire = ?');
        $msg->execute(array($_SESSION['id']));
        //on recup le nombre de msg quil a recu
        $msg_nbr = $msg->rowCount();
        $info_messages = $msg->fetchall();

        $messages = [];

        $ligne_message = [];

        foreach($info_messages as $key => $vals){
          $ligne_message['text'] = $vals['message'];
          $ligne_message['expediteur'] = $vals['mail'];
          array_push($messages, $ligne_message);

        }

        $tab = ['messages' => $messages];
        return $tab;
      }
    catch (PDOException $e) {
       die('Echec reception, erreur n°' . $e->getCode() . ':' . $e->getMessage());
   }
}
}

?>
