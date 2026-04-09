<?php
include('conn.php');
// Importar PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $accion = $_POST['accion'];

    // 1. Buscamos los datos del cliente en la DB
    $res = mysqli_query($conn, "SELECT * FROM solicitudes_agenda WHERE id = '$id'");
    $user = mysqli_fetch_assoc($res);

    $mail = new PHPMailer(true);
    try {
        // Configuración SMTP (Usa los de tu correo corporativo)
        $mail->isSMTP();
        $mail->Host = 'smtp.tu-hosting.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'contacto@clinicaconiuntio.cl';
        $mail->Password = 'clave_aqui';
        $mail->Port = 587;

        $mail->setFrom('contacto@clinicaconiuntio.cl', 'Clínica Coniuntio');
        $mail->addAddress($user['correo'], $user['nombre_completo']);
        $mail->isHTML(true);

        if ($accion == 'aceptar') {
            $f = $_POST['fecha']; $h = $_POST['hora'];
            $mail->Subject = 'Cita Aprobada';
            $mail->Body = "Buenos dias {$user['nombre_completo']}. Hemos aceptado su solicitud de {$user['servicio_solicitado']} para el {$f} a las {$h} hrs.";
            mysqli_query($conn, "UPDATE solicitudes_agenda SET estado='aceptado', fecha_cita='$f', hora_cita='$h' WHERE id='$id'");
        } else {
            $mail->Subject = 'Solicitud Declinada';
            $mail->Body = "Buenos dias {$user['nombre_completo']}. Lamentablemente no tenemos disponibilidad para {$user['servicio_solicitado']}...";
            mysqli_query($conn, "UPDATE solicitudes_agenda SET estado='declinado' WHERE id='$id'");
        }

        $mail->send();
        header("Location: admin_panel.php?msg=ok");
    } catch (Exception $e) {
        echo "Error: " . $mail->ErrorInfo;
    }
}