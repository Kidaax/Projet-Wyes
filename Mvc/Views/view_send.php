<!DOCTYPE html>
  <html>
  <head>
     <title>Envoi de message</title>
     <meta charset="utf-8" />
  </head>
  <body>
     <form action="?controller=send&action=send" method="POST">
        <label>Destinataire:</label>

        <input type="text" name="destinataire" />
        <br /><br />
        <textarea placeholder="Votre message" name="message"></textarea>
        <br /><br />
        <input type="submit" value="Envoyer" name="envoi_message" />
        <br /><br />
        <?php if(isset($error)) { echo '<span style="color:red">'.$error.'</span>'; } ?>
     </form>
     <br />
     <a href="?controller=reception&action=reception">Boîte de réception</a>
  </body>
  </html>
