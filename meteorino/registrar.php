<?php
require("conexion.php");
require 'phpmailer/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
session_start();

$nivel = "3";
$email = $_POST["email"];
$usuario = $_POST["usuario"];
$clave = $_POST["clave"];
      
$resultado = mysqli_query($con, "SELECT * FROM login WHERE email = '$email'");
$contador = mysqli_num_rows($resultado);
$clave_encrip = password_hash($clave, PASSWORD_DEFAULT);

if($contador == 1){
		echo "<script type='text/javascript'>alert('El correo ya ha sido ingresado anteriormente.');history.go(-1);</script>";
	}else{
		$query = "INSERT INTO login(nivel,usuario,email,clave) VALUES('$nivel','$usuario','$email','$clave_encrip')";
		$subir = mysqli_query($con, $query);
		$datos = mysqli_query($con, "SELECT * FROM login WHERE email = '$email' ");			
		
		////////// ENVÍO DEL CORREO CON DATOS ///////////
		$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
				$mail->isSMTP();                                 
				$mail->Host = 'smtp.gmail.com'; 
				$mail->SMTPAuth = true;                            
				$mail->Username = 'meteorinopi@gmail.com';        
				$mail->Password = 'meteorinopi2018';                   
				$mail->SMTPSecure = 'tls';                       
				$mail->Port = 587;  
				$mail->CharSet = 'utf-8';				

			    $mail->setFrom('meteorinopi@gmail.com', 'MeteorinoPi');
			    $mail->addAddress($email);
			    $mail->Subject = 'Te damos la bienvenida a MeteorinoPi!';
			    $mail->Body    = '¡Hola '.$usuario.'!, gracias por registrarte en MeteorinoPi, ahora puedes acceder a los datos históricos de la estación. Recuerda que tu clave es '.$clave.', te recomendamos guardar este correo. Disfruta de MeteorinoPi!.';
			    $mail->send();
		/////////////// FIN CORREO //////////////////////
	
	if($sql = mysqli_fetch_array($datos)){
		$mi_usuario = $sql['usuario'];
		$mi_email = $sql['email'];
		$mi_clave = "'".$sql['clave']."'";
		$nivel_sql = $sql['nivel'];
		$_SESSION['mi_usuario'] = $mi_usuario;
		$_SESSION['mi_nivel'] = $nivel_sql;
	}	

	if ($nivel_sql == 3) { 
		header("Location: index.php"); 
	}else{
		header("Location: index.php");
	}
}

mysqli_close($con);

?>