/*=============================================
  tabla admins 
 =============================================*/

 if($(".adminsTable").length > 0){
  var url = "/ajax/data.admins.ajax.php"
  var columns =[
    {"data":"id_admin"},
    {"data":"name_admin"},
    {"data":"email_admin"},
    {"data":"rol_admin"},
    {"data":"date_updated_admin"},
    {"data":"actions", "orderable":false, "searchable":false}
    ]
    var order = [0,"desc"];
 }




 /*=============================================
  tabala categorias
 =============================================*/
 if($(".categoriesTable").length > 0){
   var url = "/ajax/data.categories.ajax.php"
  var columns =[
    {"data":"id_category"},
    {"data":"status_category"},
    {"data":"name_category"},
    {"data":"url_category"},
    {"data":"image_category"},
    {"data":"description_category"},
    {"data":"keywords_category"},
    {"data":"subcategories_category"},
    {"data":"products_category"},
    {"data":"views_category"},
    {"data":"date_updated_category"},
    {"data":"actions", "orderable":false, "searchable":false}
    ]

    var order = [0,"desc"];
  
 }



 

 /*=============================================
  tabala Subcategorias
 =============================================*/
 if($(".subcategoriesTable").length > 0){
  var url = "/ajax/data.subcategories.ajax.php"
 var columns =[
   {"data":"id_subcategory"},
   {"data":"status_subcategory"},
   {"data":"name_subcategory"},
   {"data":"url_subcategory"},
   {"data":"image_subcategory"},
   {"data":"description_subcategory"},
   {"data":"keywords_subcategory"},
   {"data":"name_category"},
   {"data":"products_subcategory"},
   {"data":"views_subcategory"},
   {"data":"date_updated_subcategory"},
   {"data":"actions", "orderable":false, "searchable":false}
   ]

   var order = [0,"desc"];
 
}


 /*=============================================
  tabla productos
 =============================================*/
 if($(".productsTable").length > 0){
  var url = "/ajax/data-products.ajax.php"
 var columns =[
   {"data":"id_product"},
   {"data":"status_product"},
   {"data":"name_product"},
   {"data":"url_product"},
   {"data":"image_product"},
   {"data":"description_product"},
   {"data":"keywords_product"},
   {"data":"name_category"},
   {"data":"name_subcategory"},
   {"data":"views_product"},
    
   {"data":"date_updated_product"},
   {"data":"actions", "orderable":false, "searchable":false}
   ]

   var order = [0,"desc"];
 
}



$("#tables").DataTable({
    "responsive":true,
    "aLengthMenu":[[10, 25, 50, 100],[10, 25, 50, 100]],
    "order":[order],
    "lengthChange": true, 
    "autoWidth": false,
    "processing":true,
    "serverSide": true,
    "ajax":{
	"url":url,
	"type": "POST"
},

    "columns":columns,

    "language":{

        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":    "",
        "sSearch":         "Buscar:",
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
          "sFirst":    "Primero",
          "sLast":     "Último",
          "sNext":     "Siguiente",
          "sPrevious": "Anterior"
        },
        "oAria": {
          "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
          "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
    }
  


});


/*=============================================
Eliminar item
=============================================*/

$(document).on("click",".deleteItem", function(){

  var idItem = $(this).attr("idItem");
  //console.log("idItem: ", idItem);
  var table = $(this).attr("table");
  //console.log("table: ", table);
  var column =  $(this).attr("column");
  //console.log("column: ", column);
  var rol =  $(this).attr("rol");
 // console.log("rol: ", rol);

 fncSweetAlert("confirm", "¿Está seguro de borrar este item?", "").then(resp => {

  if(resp){
    
    fncMatPreloader("on");
    fncSweetAlert("loading", "", "");
    
    if(rol == "admin"){

      var token = localStorage.getItem("token-admin");
      var url = "/ajax/delete-admin.ajax.php";
    
    }
    var data = new FormData();
    data.append("token", token);
    data.append("table", table);
    data.append("id", idItem);
    data.append("nameId", "id_"+column);

    $.ajax({

      url: url,
      method: "POST",
      data: data,
      contentType: false,
      cache: false,
      processData: false,
      success: function (response){

        if(response == 200){
          fncMatPreloader("off");
          fncSweetAlert(
            "success",
            "Item eliminado correctamente",
            location.reload()
          )

        }else if(response == "no-borrar"){

          if (table == "categories"){
            fncMatPreloader("off");
            fncToastr("warning","Este item no se puede borrar, por que tiene subcategorias");
          }else{
            fncMatPreloader("off");
            fncToastr("warning","Este item no se puede borrar");

          }

      
        }else{
          fncMatPreloader("off");
          fncToastr("error","Este item no se puede borrar");
        }

      }
    })
  }
});


});


// /////// swich   ////////

$("#tables").on("draw.dt", function(){
  $("input[data-bootstrap-switch]").each(function(){
    $(this).bootstrapSwitch({

      onSwitchChange: function(event, state){

        var idItem = $(event.target).attr("idItem");
        var table = $(event.target).attr("table");
        var column =  $(event.target).attr("column");
        var status = 0;
       // console.log("state", state);//

        if(state){

          status = 1;

        }else{

          status = 0;

        }

        var token = localStorage.getItem("token-admin");

        var data = new FormData();
        data.append("token", token);
        data.append("table", table);
        data.append("id", idItem);
        data.append("status", status);
        data.append("column", column);

        $.ajax({

          url: "/ajax/status-admin.ajax.php",
          method: "POST",
          data: data,
          contentType: false,
          cache: false,
          processData: false,
          success: function (response){

           if(response == 200){

              fncMatPreloader("off");
              fncToastr(
                "success",
                "El item ha sido actualizado correctamente"
                )

            }else{

              fncMatPreloader("off");
              fncToastr("Error","Este item no se pudo actualizar");

            }

          }

        })


      }


    });

  })
})
