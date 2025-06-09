<?php

require_once "../controllers/curl.controller.php";

class DeleteController{

	public $token;
    public $table;
    public $id;
    public $nameId;

    public function ajaxDelete(){
    	
    	if($this->table == "admins" && base64_decode($this->id) == "1"){

			echo "no-borrar";
            return;	

    	}

        if($this->table == "categories"){

            $select = "url_category,image_category,subcategories_category";
            $url = "categories?linkTo=id_category&equalTo=".base64_decode($this->id)."&select=".$select; 
            $method = "GET";
            $fields = array();

            $dataItem = CurlController::request($url, $method, $fields)->results[0];

            /*=============================================
            No Borrar categoría si tiene subcategorías vinculadas 
            =============================================*/

            if($dataItem->subcategories_category > 0){

                echo "no-borrar";
                return;

            }

            /*=============================================
            Borrar Imagen
            =============================================*/

            unlink("../views/assets/img/categories/".$dataItem->url_category."/".$dataItem->image_category);

            /*=============================================
            Borrar Directorio
            =============================================*/

            rmdir("../views/assets/img/categories/".$dataItem->url_category);

       }

       $url = $this->table."?id=".base64_decode($this->id)."&nameId=".$this->nameId."&token=".$this->token."&table=admins&suffix=admin";
       $method ="DELETE";
       $fields = array();

       $delete= CurlController::request($url, $method, $fields);
       
       echo $delete->status;


    }

}

if(isset($_POST["token"])){

    $Delete = new DeleteController();
    $Delete -> token = $_POST["token"];
    $Delete -> table = $_POST["table"];
    $Delete -> id = $_POST["id"];
    $Delete -> nameId = $_POST["nameId"];
    $Delete -> ajaxDelete();

}


