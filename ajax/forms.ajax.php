<?php

require_once "../controllers/curl.controller.php";

class FormsController{

    /*=============================================
   	Validar títulos repetidos
    =============================================*/

    public $table;
    public $equalTo;
    public $linkTo;

    public function ajaxForms(){

    	$url = $this->table."?equalTo=".urlencode($this->equalTo)."&linkTo=".$this->linkTo."&select=".$this->linkTo;	
    	$method = "GET";
		$fields = array();

		$data = CurlController::request($url, $method, $fields);

		echo $data->status;

    }

    /*=============================================
   	Selector Anidado
    =============================================*/

    public $idCategory;

    public function listSubcategories(){

    	$select = "id_subcategory,name_subcategory";
    	$url = "subcategories?linkTo=id_category_subcategory&equalTo=".$this->idCategory."&select=".$select; 

    	$method = "GET";
        $fields = array();

        $data = CurlController::request($url, $method, $fields)->results; 

        echo json_encode($data);
    }


}

if(isset($_POST["table"])){

	$forms = new FormsController();
	$forms -> table = $_POST["table"];
	$forms -> equalTo = $_POST["equalTo"];
	$forms -> linkTo = $_POST["linkTo"];
	$forms -> ajaxForms();
}

if(isset($_POST["idCategory"])){

	$data = new FormsController();
	$data -> idCategory = $_POST["idCategory"];
	$data -> listSubcategories();
}