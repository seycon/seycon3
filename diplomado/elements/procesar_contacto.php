<?php

function mostrarAlert($mensaje) {
    echo '<script type="text/javascript">';
    echo "alert('" . $mensaje . "');";
    echo '</script>';
}

include_once 'lib/database.php';
include_once 'lib/class.phpmailer.php';
include_once 'lib/class.smtp.php';
$db = new DataBase();
if (isset($_POST['subscribe'])) {
    echo '<div>Se subscribio correctamente</div>';
}
if (isset($_POST['suggest'])) {
    $db->setQuery("INSERT INTO recommendeds(name,created) values('" . $_POST['suggest_name'] . "','" . date('Y-m-d') . "')");
    $db->execute();
    $mail = new PHPMailer();
    $body = '
            <h2>Sugiero la siguiente película : </h2>
            <p>'.$_POST['suggest_name'].'</p>
            ';
    $mail->IsSMTP();
    $mail->Host = "mail.autocinemacoyote.com";
    $mail->SMTPAuth = true;
    $mail->Host = "mail.autocinemacoyote.com";
    $mail->Port = 25;
    $mail->Username = "sender@autocinemacoyote.com";
    $mail->Password = "18denoviembre";
    $mail->SetFrom('peliculas@autocinemacoyote.com');
    $mail->Subject = "Nueva sugerencia en Autocinema Coyote";
    $mail->MsgHTML($body);
    //$mail->AddAddress("ronaldsalazar23@gmail.com", "Ronald Salazar");
    //peliculas@autocinemacoyote.com    
    $mail->AddAddress("peliculas@autocinemacoyote.com", "Autocinema Coyote");
    $mail->Send();
    mostrarAlert('Su sugerencia se envió correctamente.');
}
if (isset($_POST['message'])) {
     $db->setQuery("INSERT INTO messages(name,created,email,phone,message,edad) 
         values('" . $_POST['message_name'] . "','" . date('Y-m-d') . "','" . $_POST['message_email'] . "',
             '" . $_POST['message_phone'] . "','" . $_POST['message_message'] . "','" . $_POST['message_edad'] . "')");
    $db->execute();
    $mail = new PHPMailer();
    $body = '
            <p>Nombre : '.$_POST['message_name'].' </p>
            <p>Correo '.$_POST['message_email'].'</p>
            <p>Edad '.$_POST['message_edad'].'</p>
            <p>Teléfono '.$_POST['message_phone'].'</p>
            <p>Mensaje '.$_POST['message_message'].'</p>
            ';
    $mail->IsSMTP();
    $mail->Host = "mail.autocinemacoyote.com";
    $mail->SMTPAuth = true;
    $mail->Host = "mail.autocinemacoyote.com";
    $mail->Port = 25;
    $mail->Username = "sender@autocinemacoyote.com";
    $mail->Password = "18denoviembre";
    $mail->SetFrom('sender@autocinemacoyote.com');
    $mail->Subject = "Nuevo Comentario en Autocinema Coyote";
    $mail->MsgHTML($body);
    //$mail->AddAddress("ronaldsalazar23@gmail.com", "Ronald Salazar"); 
    $mail->AddReplyTo($_POST['message_email'], $_POST['message_name']);
    $mail->AddAddress("info@autocinemacoyote.com", "Autocinema Coyote");
    $mail->Send();
    mostrarAlert('Su Mensaje se envió correctamente.');
}
?>
