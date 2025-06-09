<?php

/* variables de inicio de session*/
ob_start();
session_start();

$path = TemplateController::path();

// CAPTURAS DE LA URL

	/*=============================================
	 explode lo utilizo para convertir en array lo que este
  separado por "/" en la url
	=============================================*/

  $routesArray = explode("/",$_SERVER["REQUEST_URI"]);

  /*aqui la funcion array_shift quita ese primer indice del arreglo, 
    osea el dominio lte.com 
  */ 

  array_shift($routesArray);  

  /* el foreach lo voy a utlizar para esos parametros externos
    como face ?fb = aikdsjdhaksjd, lo 
    que este desues de ? no lo tomara 
  */ 


foreach ($routesArray as $key => $value){

  $routesArray[$key] = explode("?",$value)[0];

}

  //echo '<pre>'; print_r($routesArray ); echo '</pre>';

// AQUI LAS VARIABLES CONEXINO DE API CON LA TABLA TEMPLATE DE LA BASE DE DATOS 
// ADEMAS DE DE VALIDACIONES CON POSTMAN 

$url = "templates?linkTo=active_template&equalTo=ok";
$method = "GET";
$fields = array();

$template = CurlController::request($url, $method, $fields);  
//echo '<pre>'; print_r($template); echo '</pre>';


if($template->status == 200){
  
  

  $template = $template->results[0];
  
  }else{

    echo '
    <!DOCTYPE html>
    <html lang="en">

    <head>
      <link rel="stylesheet" href"'.$path.'views/assets/css/plugins/adminlte/adminlte.min.css">
    </head>

    <body class="hold-transition sidebar-collapse layout-top-nav">
      <div class="wrapper">';

      include"pages/500/500.php";

     echo' </div>
    </body>

    </html>';
    return;  

  }
//echo '<pre>'; print_r(json_decode($template->keywords_template, true)); echo '</pre>';


// AQUI LAS VARIABLES PARA CAMBIO DE PALABRAS CLAVES EN EL TEMPLATE.PHP

$keywords = null;

foreach (json_decode($template->keywords_template, true) as $key => $value) {
 
 
    $keywords .= $value.", ";

}

$keywords = substr($keywords, 0, -2);
//echo '<pre>'; print_r($keywords); echo '</pre>';


// AQUI LAS VARIABLES CON LOS FONTS DE LA BASE DE DATOS

if (!empty($template->fonts_template)) {
  $fonts = json_decode($template->fonts_template);

  // Verifica si las propiedades existen antes de usarlas
  $fontFamily = isset($fonts->fontFamily) ? $fonts->fontFamily : null;
  $fontBody = isset($fonts->fontBody) ? $fonts->fontBody : null;
  $fontSlide = isset($fonts->fontSlide) ? $fonts->fontSlide : null;
} else {
  $fontFamily = $fontBody = $fontSlide = null; // Valores predeterminados en caso de error
}



// AQUI LAS VARIABLES PARA CAMbIO DE COLOR traidas desde base de datos


// echo '<pre>'; print_r(json_decode($template->colors_templates)[0]->top->background); echo '</pre>';
// echo '<pre>'; print_r(json_decode($template->colors_template)); echo '</pre>';
$colors = json_decode($template->colors_template);

// 
$topColor = isset($colors[0]->top) ? $colors[0]->top : null;
$templateColor = isset($colors[1]->template) ? $colors[1]->template : null;

// 
$topBackground = isset($topColor->background) ? $topColor->background : 'transparent';
$topTextColor = isset($topColor->color) ? $topColor->color : '#000';
$templateBackground = isset($templateColor->background) ? $templateColor->background : 'transparent';
$templateTextColor = isset($templateColor->color) ? $templateColor->color : '#000';

?>

<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $template->title_template?></title>
  <meta name="description" content="<?php echo $template->description_template?>">
  <meta name="keywords" content="<?php echo $keywords?>">
  <link rel="icon" href="<?php echo $path ?>views/assets/img/template/<?php echo $template->id_template ?>/<?php echo $template->icon_template ?>">

  <!-- Google Font: Source Sans Pro -->
  <?php echo urldecode($fontFamily)?>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="<?php echo $path ?>views/assets/css/plugins/fontawesome-free/css/all.min.css">


  <!-- Latest compiled and minified CSS BOSSTRAP 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">


  <!-- JDSlider -->
  <link rel="stylesheet" href="<?php echo $path ?>views/assets/css/plugins/jdSlider/jdSlider.css">
  
  <!-- notie alerts -->
  <link rel="stylesheet" href="<?php echo $path ?>views/assets/css/plugins/notie/notie.min.css">

  <!-- toast alerts -->
  <link rel="stylesheet" href="<?php echo $path ?>views/assets/css/plugins/toastr/toastr.min.css">

  <!-- preloader alerts -->
  <link rel="stylesheet" href="<?php echo $path ?>views/assets/css/plugins/material-preloader/material-preloader.css">
  
  <!-- plugin tags input -->
  <link rel="stylesheet" href="<?php echo $path ?>views/assets/css/plugins/tags-input/tags-input.css">



  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo $path ?>views/assets/css/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo $path ?>views/assets/css/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo $path ?>views/assets/css/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $path ?>views/assets/css/plugins/adminlte/adminlte.min.css">
  <link rel="stylesheet" href="<?php echo $path ?>views/assets/css/template/template.css">
  <link rel="stylesheet" href="<?php echo $path ?>views/assets/css/products/products.css">
<style>

body{
    font-family: '<?php echo $fontBody?>';
}

.slideOpt h1, .slideOpt h2, .slideOpt h3 {   
    font-family: '<?php echo $fontSlide ?>', sans-serif;
}

.topColor {
  background: <?php echo $topBackground; ?>;
  color: <?php echo $topTextColor; ?>;
}

.templateColor, .templateColor:hover, a.templateColor {
  background: <?php echo $templateBackground; ?> !important;
  color: <?php echo $templateTextColor; ?> !important;
}

</style>

  <!-- JS -->


  <!-- jQuery -->
  <script src="<?php echo $path ?>views/assets/js/plugins/jquery/jquery.min.js"></script>

  <!-- Latest compiled JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- JDSlider 
  https://www.jqueryscript.net/slider/Carousel-Slideshow-jdSlider.html -->
  <script src="<?php echo $path ?>views/assets/js/plugins/jdSlider/jdSlider.js"></script>

  <!-- Knob -->
  <script src="<?php echo $path ?>views/assets/js/plugins/knob/knob.js"></script>

  <!-- ALERTS-->
  <script src="<?php echo $path ?>views/assets/js/alerts/alerts.js"></script>

  <!-- NOTIE ALERTS-->
  <script src="<?php echo $path ?>views/assets/js/plugins/notie/notie.min.js"></script>

  <!--SWEET ALERTS-->
  <script src="<?php echo $path ?>views/assets/js/plugins/sweetalert/sweetalert.min.js"></script>

  <!--TOAST ALERTS-->
  <script src="<?php echo $path ?>views/assets/js/plugins/toastr/toastr.min.js"></script>

   <!--preload  ALERTS-->
   <script src="<?php echo $path ?>views/assets/js/plugins/material-preloader/material-preloader.js"></script>

   <!-- plugin tags input -->
  <link rel="stylesheet" href="<?php echo $path ?>views/assets/js/plugins/tags-input/tags-input.js">
     
  <!-- DataTables  & Plugins -->
  <script src="<?php echo $path ?>views/assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="<?php echo $path ?>views/assets/js/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="<?php echo $path ?>views/assets/js/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="<?php echo $path ?>views/assets/js/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
  <script src="<?php echo $path ?>views/assets/js/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
  <script src="<?php echo $path ?>views/assets/js/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
  <script src="<?php echo $path ?>views/assets/js/plugins/jszip/jszip.min.js"></script>
  <script src="<?php echo $path ?>views/assets/js/plugins/pdfmake/pdfmake.min.js"></script>
  <script src="<?php echo $path ?>views/assets/js/plugins/pdfmake/vfs_fonts.js"></script>
  <script src="<?php echo $path ?>views/assets/js/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
  <script src="<?php echo $path ?>views/assets/js/plugins/datatables-buttons/js/buttons.print.min.js"></script>
  <script src="<?php echo $path ?>views/assets/js/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
  
  <!-- bosstrap swch -->
  <script src="<?php echo $path ?>views/assets/js/plugins/bootstrap-switch/bootstrap-switch.min.js"></script>
</head>

<body class="hold-transition sidebar-collapse layout-top-nav">

<input type="hidden" id="urlPath" value="<?php echo $path ?>">

<div class="wrapper">

	<?php 
  //include "modules/welcome.php";
  include "modules/top.php"; 
  include "modules/slider2.php"; 
  include "modules/navbar.php"; 

  
  if(isset($_SESSION["admin"])){
    include "modules/sidebar.php"; 
   }

    // condici0n para la captua de l array 
   if(!empty($routesArray[0])){

    if($routesArray[0] == "admin" ||
      $routesArray[0] == "salir" //||  
      //$routesArray[0] == "index2.php" 
      )   

      {
    
        
      include "pages/".$routesArray[0]."/".$routesArray[0].".php";
      
    }else{

      include "pages/404/404.php";
    }
 
  }else{

    include "pages/home/home.php";
  }

    //include "modules/footer.php"; 

    include "modules/modals.php"; 
  ?>

</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- AdminLTE App -->
<script src="<?php echo $path ?>views/assets/js/plugins/adminlte/adminlte.min.js"></script>
<script src="<?php echo $path ?>views/assets/js/products/products.js"></script>

</body>
</html>

