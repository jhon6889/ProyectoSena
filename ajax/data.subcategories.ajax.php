<?php
require_once "../controllers/curl.controller.php";
require_once "../controllers/template.controller.php";

class DatatableController{

    public function data(){
        if(!empty($_POST)){

            

            /*=============================================
           post dt
            =============================================*/

			$draw = $_POST["draw"];  
			// echo '<pre>$draw '; print_r($draw); echo '</pre>';

			$orderByColumnIndex = $_POST["order"][0]["column"]; 
			// echo '<pre>$orderByColumnIndex '; print_r($orderByColumnIndex); echo '</pre>';

			$orderBy = $_POST["columns"][$orderByColumnIndex]["data"];
			// echo '<pre>$orderBy '; print_r($orderBy); echo '</pre>';

			$orderType = $_POST["order"][0]["dir"]; 
			// echo '<pre>$orderType '; print_r($orderType); echo '</pre>';

			$start = $_POST["start"];
			// echo '<pre>$start '; print_r($start); echo '</pre>';

			$length = $_POST["length"];
			// echo '<pre>$length '; print_r($length); echo '</pre>';

            /*=============================================
            El total de registros de la base de datos 
            =============================================*/
            $url = "relations?rel=subcategories,categories&type=subcategory,category&select=id_subcategory";
            $method = "GET";
            $fields = array();

            $response = CurlController::request($url, $method, $fields);
           
            if($response->status == 200){
                $totalData = $response->total;
               // echo '<pre>'; print_r( $totalData); echo '</pre>';
            }else{
                echo '{"Draw": 1,
                "recordsTotal": 0,
                "recordsFiltered": 0,
                "data":[]}';
                return;
            }
           
           
            $select = "id_subcategory,status_subcategory,name_subcategory,url_subcategory,image_subcategory,description_subcategory,keywords_subcategory,name_category,products_subcategory,views_subcategory,date_updated_subcategory";


             /*=============================================
           	condicion para el buscador 
            =============================================*/

            if(!empty($_POST['search']['value'])){

                if(preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/',$_POST['search']['value'])){
                
                $linkTo = ["name_subcategory", "url_subcategory", "description_subcategory", "keywords_subcategory", "date_updated_subcategory,name_subcategory"];
                $search = str_replace(" ","_",$_POST['search']['value']);

                foreach ($linkTo as $key => $value) {
                    $url = "relations?rel=subcategories,categories&type=subcategory,category&select=".$select."&orderBy=".$orderBy."&orderMode=".$orderType."&startAt=".$start."&endAt=".$length;
                    $data = CurlController::request($url, $method, $fields)->results;
                    if($data == "Not Found"){
                        $data = array();
                        $recordsFiltered = 0;
                    
                    }else{
                        $recordsFiltered = count($data);
                        break;
                    }

                }

                }else{
                    echo '{"Draw": 1,
                    "recordsTotal": 0,
                    "recordsFiltered": 0,
                    "data":[]}';
                    return;
                }


            }else{

            /*=============================================
	            Seleccionar datos
	        =============================================*/
	        $url = "relations?rel=subcategories,categories&type=subcategory,category&select=".$select."&orderBy=".$orderBy."&orderMode=".$orderType."&startAt=".$start."&endAt=".$length;
	        $data = CurlController::request($url, $method, $fields)->results;
           //echo '<pre>'; print_r( $data); echo '</pre>';
            $recordsFiltered = $totalData;

            }
	    
            /*=============================================
                Cuando la data viene vacía
            =============================================*/

            if(empty($data)){

                echo '{"Draw": 1,
                "recordsTotal": 0,
                "recordsFiltered": 0,
                "data":[]}';
                return;

            }

            /*=============================================
            Construimos el dato JSON a regresar
            =============================================*/

            $dataJson = '{
                "Draw": '.intval($draw).',
                "recordsTotal": '.$totalData.',
                "recordsFiltered": '.$recordsFiltered.',
                "data": [';
                foreach ($data as $key => $value) {

                    
                    /*=============================================
                    status
                    =============================================*/
                    

                    if($value->status_subcategory == 1){
                        $status_subcategory =	"<input type='checkbox' data-size='mini' data-bootstrap-switch data-off-color='danger' data-on-color='dark' checked='true' idItem='".base64_encode($value->id_subcategory)."' table='subcategories' column='subcategory'>";

                    }else{
                        $status_subcategory =	"<input type='checkbox' data-size='mini' data-bootstrap-switch data-off-color='danger' data-on-color='dark' idItem='".base64_encode($value->id_subcategory)."' table='subcategories' column='subcategory'>";
                    }
                    /*=============================================
                    textos
                    =============================================*/
                    
                    $name_subcategory =  $value->name_subcategory;
					$url_subcategory = "<a href='/".$value->url_subcategory."' target='_blank' class='badge badge-light px-3 py-1 border rounded-pill'>/".$value->url_subcategory."</a>";
                    $image_subcategory = "<img src='/views/assets/img/subcategories/".$value->url_subcategory."/".$value->image_subcategory."' class='img-thumbnail rounded'>";
                    $description_subcategory = templateController::reduceText($value->description_subcategory, 25);
                    $keywords_subcategory = ""; // revisar aqui por que esta vacia la variable
                    $keywordsArray = explode(",",$value->keywords_subcategory);
                    foreach ($keywordsArray as $index => $item) {
                        $keywords_subcategory.= "<span class='badge badge-primary border rounded-pill px-3 py-1'>".$item."</span> ";
                    }
                    $name_category = $value->name_category;
                    $products_subcategory = $value->products_subcategory;
                    $views_subcategory = "<span class='badge badge-warning rounded-pill px-3 py-1'><i class='fas fa-eye'></i> ".$value->views_subcategory."</span>";
            		$date_updated_subcategory = $value->date_updated_subcategory;
                    $actions = "<div class='btn-group'>
									<a href='/admin/subcategorias/gestion?subcategory=".base64_encode($value->id_subcategory)."' class='btn bg-purple border-0 rounded-pill mr-2 btn-sm px-3'>
										<i class='fas fa-pencil-alt text-white'></i>
									</a>
									<button class='btn btn-dark border-0 rounded-pill mr-2 btn-sm px-3 deleteItem' rol='admin'
                                    table='subcategories' column='subcategory' idItem='".base64_encode($value->id_subcategory)."'>
										<i class='fas fa-trash-alt text-white'></i>
									</button>
								</div>";
                    $actions = TemplateController::htmlClean($actions);
                    $dataJson.='{
                       	"id_subcategory":"'.($start+$key+1).'",
						"status_subcategory":"'.$status_subcategory.'",
						"name_subcategory":"'.$name_subcategory.'",
						"url_subcategory":"'.$url_subcategory.'",
						"image_subcategory":"'.$image_subcategory.'",
						"description_subcategory":"'.$description_subcategory.'",
						"keywords_subcategory":"'.$keywords_subcategory.'",
						"name_category":"'.$name_category.'",
						"products_subcategory":"'.$products_subcategory.'",
						"views_subcategory":"'.$views_subcategory.'",
						"date_updated_subcategory":"'.$date_updated_subcategory.'",
						"actions":"'.$actions.'"                   
                    },';
                }
            $dataJson = substr($dataJson,0,-1); 
            $dataJson .=  ']}';
            echo $dataJson;

        }

    }

}


$data = new DatatableController();
$data->data();