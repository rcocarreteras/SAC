<?php
require_once('Connections/sac2.php'); 
header( 'Content-type: text/html; charset=iso-8859-1' );
date_default_timezone_set('America/Mexico_City');
//-------------------------------VALIDAMOS QUE ESTE LOGEADO-----------------------------
if (!isset($_SESSION)) {
  session_start();
}

$login_ok = $_SESSION['login_ok'];
//RESTRINGIMOS EL ACCESO A USUARIOS NO IDENTIFICADOS
if ($login_ok == "identificado"){

}else{
echo "No Identificado";	 
	//session_unset();  // remove all session variables
	session_destroy();  // destroy the session 
	header("Location: index.php");
}

if (isset($_POST['cerrar_sesion'])) {
	session_destroy();  // destroy the session 
	header("Location: index.php");
	
}
//HACEMOS EL ARMADO DEL GRID 
$x=0;
$y=0;
$horas = "";
$filas = array();
$filas[$x] = "[";
$sql = "SELECT DISTINCT(ACTIVIDAD_ID), ACTIVIDAD FROM AvanceDiario WHERE ACTIVIDAD_ID <> '0' ";
//echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta SQL" ); 
    }    
    while ( odbc_fetch_row($rs) ) { 
      $clave = odbc_result($rs, 'ACTIVIDAD_ID'); 
      $actividad = odbc_result($rs, 'ACTIVIDAD'); 

    $y++;	
    $filas[$x].= "{\"id\": \"".$y."\", \"Clave\": \"".$clave."\", \"Actividad\": \"".$actividad."\", \"Km_ini\": \"\" },";
    //$filas[$x].= "{\"id\": \"".$y."\", \"Clave\": \"".$clave."\", \"Actividad\": \"".$actividad."\", \"grupo\": [";
	
	// $sql2 = "SELECT AvanceDiario.KM_INI, AvanceDiario.KM_FIN, AvanceDiario.CUERPO, AvanceDiario.ZONA, AvanceDiario.LONGITUD, AvanceDiario.ANCHO, AvanceDiario.ESPESOR, AvanceDiario.CANTIDAD AS CANTIDAD, SUM(PuntoTrabajado.HORAS) as HORAS FROM AvanceDiario INNER JOIN PuntoTrabajado ON AvanceDiario.AVANCE_ID = PuntoTrabajado.AVANCE_ID WHERE AvanceDiario.ACTIVIDAD_ID = '".$clave."' AND AvanceDiario.FECHA BETWEEN '2016-04-01' AND '2016-04-30' GROUP BY KM_INI, KM_FIN, CUERPO, ZONA, LONGITUD, ANCHO, ESPESOR, AvanceDiario.CANTIDAD"; 
	// //echo $sql2;
 //    $rs2 = odbc_exec( $conn, $sql2 );
 //    if ( !$rs2 ) { 
 //     exit( "Error en la consulta SQL2" ); 
 //    }  
 //    while (odbc_fetch_row($rs2) ) { 
 //      $km_ini = odbc_result($rs2, 'KM_INI'); 
 //      $km_fin = odbc_result($rs2, 'KM_FIN');
 //      $cuerpo = odbc_result($rs2, 'CUERPO'); 
 //      $zona = odbc_result($rs2, 'ZONA');
 //      $cantidad = odbc_result($rs2, 'CANTIDAD');
 //      $longitud = odbc_result($rs2, 'LONGITUD');
 //      $ancho = odbc_result($rs2, 'ANCHO'); 
 //      $espesor = odbc_result($rs2, 'ESPESOR');
 //      $horas = odbc_result($rs2, 'HORAS'); 
 //         $y++;
 //        // $filas[$x].= "{\"id\": \"".$y."\", \"Km_Ini\": \"".$km_ini."\", \"Km_Fin\": \"".$km_fin."\", \"Cuerpo\": \"".$cuerpo."\", \"Zona\": \"".$zona."\", \"Longitud\": \"".$longitud."\", \"Ancho\": \"".$ancho."\", \"Espesor\": \"".$espesor."\", \"Cantidad\": \"".$cantidad."\", \"Horas\": \"".$horas."\" },";
 //         $filas[$x].= "{\"id\": \"".$y."\", \"Clave\": \"".$km_ini."\"},";
	// }//while
 //         $filas[$x] = substr("$filas[$x]", 0, -1); 
 //         $filas[$x].="]},";
 // 		 //echo $filas[$x];

}//While  
  $filas[$x] = substr("$filas[$x]", 0, -1);
  $filas[$x].="];";
  //echo $filas[$x];
  
?>
<!doctype html>
<html>
    <head>
    <meta charset="utf-8">    
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" type="text/css" href="CSSLocal/menu.css">  
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/bootstrap-dialog.min.css" type="text/css" />
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">  
    <link type="text/css" rel="Stylesheet" href="jqwidgets/styles/jqx.base.css" /> 
	<link rel="stylesheet" href="CssLocal/Menus.css"><!--Necesario para Menu 1-->
	<script type="text/javascript" src="scripts/menu1.js"></script><!--Necesario para Menu 1-->
    <script type="text/javascript" src="scripts/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxscrollbar.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdatatable.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxtreegrid.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdata.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcheckbox.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxlistbox.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdraw.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxknob.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxnumberinput.js"></script>
    <script type="text/javascript" src="scripts/demos.js"></script>    
<!--CSS AJUSTE PANTALLA-->
	<style>		
      
	body, html {
		width: 100%;
		/*height: 100%;*/
		overflow: hidden;
		   
	}
	.contenedor {
		width: 100%;
		/*height: 100%;*/
		overflow: hidden;		
		box-sizing: border-box;
		padding: 0px;
		margin: 0 auto;
		/*max-width: 1000px;*/
		font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;
	}
	
	.encabezado{
		width: 48%;
		height: 5%;
		alignment-adjust:central;
		
	}

	.titulo{
		padding: 5px;
		border:#000000;
		background: #d8d8d8;
	}
	.titulo1 {
		font-family: arial;
		font-size: 10px;
		padding: 10px;	
		border: 3px solid #FF8C00;
		border-bottom-width: 3px;
		border-left-width: 0px;
		border-right-width: 0px;
		border-top-width: 0px;
		width: 55%;
		float: left;
		box-sizing: border-box;	
	}
	.titulo2 {
		font-family: arial;
		font-size: 10px;
		text-align: center;		
		padding: 10px;	
		border: 3px solid #FF8C00;
		border-bottom-width: 3px;
		border-left-width: 0px;
		border-right-width: 0px;
		border-top-width: 0px;
		width: 15%;
		float: left;
		box-sizing: border-box;	
	}

	.titulo3 {
		font-family: arial;
		font-size: 10px;
		text-align: right;
		padding: 10px;	
		border: 3px solid #FF8C00;
		border-bottom-width: 3px;
		border-left-width: 0px;
		border-right-width: 0px;
		border-top-width: 0px;
		width: 15%;
		float: left;
		box-sizing: border-box;	
	}
	
	#buscar {		
		margin: 0 auto;
		padding: 5px;
		font-size: 1em;
		width: 500px;
		height: 50px;	
	}

	#main {
		margin-left: 5px;
		margin-right: 15px;		
		padding: 5px;
		width: 10%;
		height: 100%;
		float: left;			
		box-sizing: border-box;
		
	}	

	#almacenSac{
		margin-right: 2%;
		float: right;
		padding-top: 5px;
		width: 35%;
		height: 80%;
		border: 2px dashed #49A2FF;
		border-radius: 10px;
	}

	#art{
		padding: 5px;
		background: #d8d8d8;				
		width: 15%;
		float: left;		
	}

	#descripcion{
		padding: 5px;
		background: #d8d8d8;				
		width: 55%;
		float: left;		
	}
	#cantidad{
		padding: 5px;
		background: #d8d8d8;				
		width: 15%;
		float: left;
		text-align: center;
	}
	#importe{
		padding: 5px;
		background: #d8d8d8;				
		width: 15%;
		float: left;
		text-align: center;
	}		
</style>
    <script type="text/javascript">	
	    $(document).ready(function () {
        //EFECTOS EN EL MENU
            $("#menu2").mouseenter(function(e){
				$("#menu2").css({"background": "#49A2FF", "border":"0px"});			  
			});
			$("#menu2").mouseleave(function(e){
				$("#menu2").css({"background": "#2c2c2c"});			  
			});
			$("#menu3").mouseenter(function(e){
				$("#menu3").css({"background": "#49A2FF", "border":"0px"});			  
			});
			$("#menu3").mouseleave(function(e){
				$("#menu3").css({"background": "#49A2FF"});			  
			});			
			$("#menu4").mouseenter(function(e){
				$("#menu4").css({"background": "#49A2FF", "border":"0px"});			  
			});
			$("#menu4").mouseleave(function(e){
				$("#menu4").css({"background": "#2c2c2c"});			  
			});	
			$("#menu5").mouseenter(function(e){
				$("#menu5").css({"background": "#49A2FF", "border":"0px"});			  
			});
			$("#menu5").mouseleave(function(e){
				$("#menu5").css({"background": "#2c2c2c"});			  
			});	
			$("#menu6").mouseenter(function(e){
				$("#menu6").css({"background": "#49A2FF", "border":"0px"});			  
			});
			$("#menu6").mouseleave(function(e){
				$("#menu6").css({"background": "#2c2c2c"});			  
			});	
			$("#menu7").mouseenter(function(e){
				$("#menu7").css({"background": "#49A2FF", "border":"0px"});			  
			});
			$("#menu7").mouseleave(function(e){
				$("#menu7").css({"background": "#2c2c2c"});			  
			});	
			$("#menu8").mouseenter(function(e){
				$("#menu8").css({"background": "#49A2FF", "border":"0px"});			  
			});
			$("#menu8").mouseleave(function(e){
				$("#menu8").css({"background": "#2c2c2c"});			  
			});	
			$("#menu9").mouseenter(function(e){
				$("#menu9").css({"background": "#49A2FF", "border":"0px"});			  
			});
			$("#menu9").mouseleave(function(e){
				$("#menu9").css({"background": "#2c2c2c"});			  
			});	
			
			//EFECTOS EN EL MENU LATERAL
            $("#sub1").mouseenter(function(e){
				$("#sub1").css({"background": "#49A2FF", "border":"1px dotted #0057b3"});  
			});
			$("#sub1").mouseleave(function(e){
				$("#sub1").css({"background": "#2c2c2c"});			  
			});
			$("#sub2").mouseenter(function(e){
				$("#sub2").css({"background": "#49A2FF", "border":"1px dotted #0057b3"});			  
			});
			$("#sub2").mouseleave(function(e){
				$("#sub2").css({"background": "#2c2c2c"});
			});
			$("#sub3").mouseenter(function(e){
				$("#sub3").css({"background": "#49A2FF", "border":"1px dotted #0057b3"});			  
			});
			$("#sub3").mouseleave(function(e){
				$("#sub3").css({"background": "#2c2c2c"});			  
			});
			
			       
			var data =  <?php echo ($filas[$x]); ?>;			
            var source =
             {
                 dataType: "json",
                 dataFields: [
                 	  { name: "id", type: "number" },
                 	  { name: "Clave", type: "number" },
                      { name: "Actividad", type: "string" },
                      { name: "Km_ini", type: "string" },
                     
                      //{ name: "HORAS", type: "number" },
                      
                      //{ name: "grupo", type: "array" }
                 ],
                 hierarchy:
                     {
                         root: "grupo"
                     },
                 localData: data,
                 id: "id"
             };
            var dataAdapter = new $.jqx.dataAdapter(source, {
                loadComplete: function () {
                }
            });
            // create jqxTreeGrid.
            $("#detalle").jqxTreeGrid(
            {
                source: dataAdapter,
                altRows: true,
                width: 850,
                height: 850,
                showSubAggregates: true,
                columnsResize: true,
                ready: function () {
                    $("#detalle").jqxTreeGrid('expandRow', '1');
                    $("#detalle").jqxTreeGrid('expandRow', '2');
                },
                columns: [
                  { text: "Clave", dataField: "Clave", cellsAlign: "center", width: 60 },
                  { text: "Actividad", dataField: "Actividad", cellsAlign: "left", width: 450 },
                  { text: "Km_Ini", dataField: "km_ini", cellsAlign: "left", width: 450 }

                  /*,
				  { text: "Horas", dataField: "HORAS", cellsAlign: "center", width: 150 }*/
				  // {
      //                 text: "Venta Total", cellsAlign: "center", align: "center", dataField: "precio", cellsFormat: "c2",
      //                 aggregates: ['sum'],
      //                 aggregatesRenderer: function (aggregatesText, column, element, aggregates, type) {
      //                     if (type == "aggregates") {
      //                         var renderString = "<div style='margin: 4px; float: right;  height: 100%;'>";
      //                     }
      //                     else {
      //                         var renderString = "<div style='float: right;  height: 100%;'>";
      //                     }                          
      //                     var subtotal = dataAdapter.formatNumber(aggregates.sum, "f2");;
      //                     renderString += "<table><tr><td><strong>Total: </strong></td><td>$" + subtotal + " Pesos</td></tr></table>";
      //                     return renderString;
      //                 }
      //             }
                ]
            });

        });//Fin $(document).ready 
</script>
</head>
<body>
<div class="contenedor">
    <div class="menusuperior">
			<div class="logo">				
					<center><img src="images/HEADBIOMETRICO.png" width="153" height="46" ></center>
	  		</div>
			<a href="Inicio.php"><div class="submenu_superior" id="menu2">				
				Cat&aacute;logos							
			</div></a> 
			<div class="submenu_superior_sel" id="menu3">				
					Almac&eacute;n							
			</div>
			<div class="submenu_superior" id="menu4">				
					Avance de Obra							
			</div>		
            <div class="submenu_superior" id="menu5">				
					Contratos						
			</div>
			<div class="submenu_superior" id="menu6">				
					Presupuestos							
			</div>
			<a href="asistencia.php"><div class="submenu_superior" id="menu7">				
					Asistencia							
			</div></a>
            <div class="submenu_superior" id="menu8">				
					Maquinaria						
			</div>
			<div class="submenu_superior" id="menu9">				
					Insumos							
			</div>           			
	</div>

	<div class="menulateral">
        <div class="submenu_lateral_encabezado">
        	<span class="glyphicon glyphicon-wrench"></span> &rlm; HERRAMIENTAS
        </div>
		<div class="submenu_lateral" id="sub1">
			<span class="glyphicon glyphicon-list-alt"></span> &rlm; Herramienta 1  
		</div>
		<div class="submenu_lateral" id="sub2">
			<span class="glyphicon glyphicon-user"></span> &rlm; Herramienta 2 
		</div>
		<div class="submenu_lateral" id="sub3">
			<span class="glyphicon glyphicon-compressed"></span> &rlm; Herramienta 3
		</div>          
	</div> 
      
        <center><div id="detalle"></div></center>
</div>
</body>
</html>