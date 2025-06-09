<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class TemplateController{



	 public function index(){

		include "views/template.php";
	 }

	/*=============================================
	Ruta Principal o Dominio del sitio
	=============================================*/

	static public function path(){

		if(!empty($_SERVER["HTTPS"]) && ("on" == $_SERVER["HTTPS"])){

			return "https://".$_SERVER["SERVER_NAME"]."/";

		}else{

			return "http://".$_SERVER["SERVER_NAME"]."/";
		}

	}


	/*=============================================
	fucni para el envio de correos
	=============================================*/
	static public function sendEmail($subject, $email, $title, $message, $link){
		date_default_timezone_set("America/Bogota");
		$mail = new PHPMailer;
		$mail->CharSet = 'utf-8';
		//$mail->Encoding = 'base64'; 
		$mail->isMail();
		$mail->UseSendmailOptions = 0;
		$mail->setFrom("james@james.com","james");
		$mail->Subject = $subject;
		$mail->addAddress($email);
		$mail->msgHTML('

	<div style="width:100%; background:#444242; position:relative; font-family:sans-serif; padding-top:40px; padding-bottom: 40px;">
		
		<div style="position:relative; margin:auto; width:600px; background:rgb(109, 30, 30); padding:20px">
			
			<center>
				

				<img src="'.TemplateController::path().'views/assets/img/template/1/logo.png" style="padding:20px; width:30%">

				<h3 style="font-weight:100; color:#999">'.$title.'</h3>

				<hr style="border:1px solid #ccc; width:80%">

				'.$message.'
				<a href="'.$link.'" target="_blank" style="text-decoration: none;">
					
					<div style="line-height:25px; background:#000; width:60%; padding:10px; color:white;">Haz clic aquí</div>
				</a>

				<br>

				<hr style="border:1px solid #ccc; width:80%">

				<h5 style="font-weight:100; color:#999">Si no solicitó el envío de este correo, comuniquese con nosotros de inmediato.</h5>

			</center>

		</div>

	</div>');
		$send = $mail->Send();
		
		if(!$send){

			return $mail->ErrorInfo;	
		
		}else{

			return "ok";

		}

	}


	/*=============================================
	Función Limpiar HTML
	=============================================*/	

	static public function htmlClean($code){

		$search = array('/\>[^\S ]+/s','/[^\S ]+\</s','/(\s)+/s');
		$replace = array('>','<','\\1');
		$code = preg_replace($search, $replace, $code);
		$code = str_replace("> <", "><", $code);
		return $code;
	}
	/*=============================================
	Función para mayúscula inicial
	=============================================*/

	static public function capitalize($value){

		$value = mb_convert_case($value, MB_CASE_TITLE, "UTF-8");
	    return $value;

	}


	/*=============================================
	Función Reducir texto
	=============================================*/

	static public function reduceText($value, $limit){

		if(strlen($value) > $limit){

			$value = substr($value, 0, $limit)."...";

		}

		return $value;

	}


	/*=============================================
	Función para almacenar imágenes
	=============================================*/

	static public function saveImage($image,$folder,$name,$width,$height){

		if(isset($image["tmp_name"]) && !empty($image["tmp_name"])){ 

			/*=============================================
			Configuramos la ruta del directorio donde se guardará la imagen
			=============================================*/

			$directory = strtolower("views/".$folder);

			/*=============================================
			Preguntamos primero si no existe el directorio, para crearlo
			=============================================*/

			if(!file_exists($directory)){

				mkdir($directory, 0755);

			}

			/*=============================================
			Capturar ancho y alto original de la imagen
			=============================================*/

			list($lastWidth, $lastHeight) = getimagesize($image["tmp_name"]);


			if($lastWidth < $width || $lastHeight < $height){

				$lastWidth = $width;
				$lastHeight = $height;

			}

			/*=============================================
			De acuerdo al tipo de imagen aplicamos las funciones por defecto
			=============================================*/

			if($image["type"] == "image/jpeg"){

				//definimos nombre del archivo
				$newName = $name.'.jpg';

				//definimos el destino donde queremos guardar el archivo
				$folderPath = $directory.'/'.$newName;

				if(isset($image["mode"]) && $image["mode"] == "base64"){

					file_put_contents($folderPath, file_get_contents($image["tmp_name"]));

				}else{

					//Crear una copia de la imagen
					$start = imagecreatefromjpeg($image["tmp_name"]);

					//Instrucciones para aplicar a la imagen definitiva
					$end = imagecreatetruecolor($width, $height);

					imagecopyresized($end,  $start,  0, 0,  0, 0,$width, $height, $lastWidth, $lastHeight);

					imagejpeg($end, $folderPath);

				}


			}

			if($image["type"] == "image/png"){

				//definimos nombre del archivo
				$newName  = $name.'.png';

				//definimos el destino donde queremos guardar el archivo
				$folderPath = $directory.'/'.$newName;

				if(isset($image["mode"]) && $image["mode"] == "base64"){

					file_put_contents($folderPath, file_get_contents($image["tmp_name"]));

				}else{

					//Crear una copia de la imagen
					$start = imagecreatefrompng($image["tmp_name"]);

					//Instrucciones para aplicar a la imagen definitiva
					$end = imagecreatetruecolor($width, $height);

					imagealphablending($end, FALSE);
					
					imagesavealpha($end, TRUE);	

					imagecopyresampled($end, $start, 0, 0, 0, 0, $width, $height, $lastWidth, $lastHeight);

					imagepng($end, $folderPath);

				}


			}

			if($image["type"] == "image/gif"){

				$newName = $name.'.gif';

				$folderPath = $directory.'/'.$newName;	

				if(isset($image["mode"]) && $image["mode"] == "base64"){

					file_put_contents($folderPath, file_get_contents($image["tmp_name"]));

				}else{
					
					move_uploaded_file($image["tmp_name"], $folderPath);

				}	

			}

			return $newName;

		}else{

			return "error";

		}

	}

}

