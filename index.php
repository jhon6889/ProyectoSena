<?php 



 /*=============================================
prueba
 =============================================*/

//echo "<h1>hola</h1>";


 /*=============================================
  require para el funcionamiento de la vista principal
 =============================================*/

 require_once "controllers/template.controller.php";
 require_once "controllers/curl.controller.php";
 require_once 'extensions/vendor/autoload.php';




 /*=============================================
  funcion principal  para traer la plantila
 =============================================*/

 $index = new TemplateController();
 $index->index();


