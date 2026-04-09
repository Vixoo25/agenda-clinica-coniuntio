<?php
include('conn.php');
use PHPMailer\PHPMailer\PHPMailer;
require 'PHPMailer/src/PHPMailer.php'; // Asegúrate de tener la carpeta PHPMailer aquí
require 'PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $accion = $_POST['accion'];
    
    $consulta = mysqli_query($conn, "SELECT * FROM solicitudes_agenda WHERE id = '$id'");
    $paciente = mysqli_fetch_assoc($consulta);

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'mail.tudominio.cl'; // Tu SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'contacto@clinicaconiuntio.cl';
        $mail->Password = 'TuClaveAqui';
        $mail->Port = 587;
        $mail->setFrom('contacto@clinicaconiuntio.cl', 'Clínica Coniuntio');
        $mail->addAddress($paciente['correo']);
        $mail->isHTML(true);

        if ($accion == 'aceptar') {
            $fecha = $_POST['fecha']; $hora = $_POST['hora'];
            mysqli_query($conn, "UPDATE solicitudes_agenda SET estado='aceptado', fecha_cita='$fecha', hora_cita='$hora' WHERE id='$id'");
            $mail->Subject = 'Cita Aprobada';
            $mail->Body = "Buenos dias Sr/a {$paciente['nombre_completo']}. Hemos aceptado su solicitud de {$paciente['servicio_solicitado']} para el {$fecha} a las {$hora} hrs.";
        } else {
            mysqli_query($conn, "UPDATE solicitudes_agenda SET estado='declinado' WHERE id='$id'");
            $mail->Subject = 'Solicitud Declinada';
            $mail->Body = "Buenos dias Sr/a {$paciente['nombre_completo']}. Lamentablemente no tenemos disponibilidad...";
        }

        $mail->send();
        header("Location: admin_panel.php?success=1");
    } catch (Exception $e) { echo "Error: " . $mail->ErrorInfo; }
}