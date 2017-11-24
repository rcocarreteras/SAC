<?php 
require_once('Connections/sac2.php'); 
//require_once('Connections/biometrico.php'); 
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
/*************************************************GRID*************************************************/
//SDR
$x=0;
$filas = array();
$sql = "SELECT DISTINCT PuntoTrabajado.ACTIVIDAD_ID, PuntoTrabajado.SUBTRAMO, CatConcepto.DesCpt, CatTramos.BASE, SUM(PuntoTrabajado.HORAS) as HORAS FROM PuntoTrabajado INNER JOIN CatConcepto ON PuntoTrabajado.ACTIVIDAD_ID = CatConcepto.CvCpt INNER JOIN CatTramos ON PuntoTrabajado.SUBTRAMO = CatTramos.SUBTRAMO WHERE (PuntoTrabajado.FECHA BETWEEN '2016-04-01' AND '2016-04-30') AND PuntoTrabajado.SUBTRAMO IN ('".$_SESSION['S_Subtramo']."') GROUP BY PuntoTrabajado.ACTIVIDAD_ID, PuntoTrabajado.SUBTRAMO, CatConcepto.DesCpt, CatTramos.BASE ORDER BY PuntoTrabajado.ACTIVIDAD_ID";
 //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }   //OBTENEMOS TODA LA FILA 
 while ($row = odbc_fetch_array($rs)) {    
  $filas[$x] = array_map('utf8_encode',$row);    
   $x++;    
 }//While
$sdr =  $filas; 
//echo json_encode($sdr);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title id='Description'>Data Filtering</title>
    <link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />
	<link rel="stylesheet" href="CssLocal/Menus.css"><!--Necesario para Menu 1-->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <script type="text/javascript" src="scripts/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="scripts/menu1.js"></script><!--Necesario para Menu 1-->
    <script type="text/javascript" src="jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdata.js"></script> 
    <script type="text/javascript" src="jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxscrollbar.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdatatable.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxtreegrid.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcheckbox.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxlistbox.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdropdownlist.js"></script>
    <script type="text/javascript" src="scripts/demos.js"></script>
    <!--CSS AJUSTE PANTALLA-->
	<style>		
       
	body, html {
		width: 100%;
		height: 100%;
		overflow: hidden;
		   
	}
	.contenedor {
		width: 100%;
		height: 100%;
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
			$("#sub4").mouseenter(function(e){
				$("#sub4").css({"background": "#49A2FF", "border":"1px dotted #0057b3"});			  
			});
			$("#sub4").mouseleave(function(e){
				$("#sub4").css({"background": "#2c2c2c"});			  
			});
			$("#sub5").mouseenter(function(e){
				$("#sub5").css({"background": "#49A2FF", "border":"1px dotted #0057b3"});			  
			});
			$("#sub5").mouseleave(function(e){
				$("#sub5").css({"background": "#2c2c2c"});			  
			});
			$("#sub6").mouseenter(function(e){
				$("#sub6").css({"background": "#49A2FF", "border":"1px dotted #0057b3"});			  
			});
			$("#sub6").mouseleave(function(e){
				$("#sub6").css({"background": "#2c2c2c"});			  
			});
			$("#sub7").mouseenter(function(e){
				$("#sub7").css({"background": "#49A2FF", "border":"1px dotted #0057b3"});			  
			});
			$("#sub7").mouseleave(function(e){
				$("#sub7").css({"background": "#2c2c2c"});			  
			});
			$("#sub8").mouseenter(function(e){
				$("#sub8").css({"background": "#49A2FF", "border":"1px dotted #0057b3"});			  
			});
			$("#sub8").mouseleave(function(e){
				$("#sub8").css({"background": "#2c2c2c"});			  
			});
			$("#sub9").mouseenter(function(e){
				$("#sub9").css({"background": "#49A2FF", "border":"1px dotted #0057b3"});			  
			});
			$("#sub9").mouseleave(function(e){
				$("#sub9").css({"background": "#2c2c2c"});			  
			});
			$("#sub10").mouseenter(function(e){
				$("#sub10").css({"background": "#49A2FF", "border":"1px dotted #0057b3"});			  
			});
			$("#sub10").mouseleave(function(e){

				$("#sub10").css({"background": "#2c2c2c"});			  
			});
			
			
			var conceptos = <?php echo json_encode($sdr); ?>;
            
            // prepare the data
            var source =
            {
                dataType: "json",
                dataFields: [
                    { name: 'ACTIVIDAD_ID', type: 'number' },
                    { name: 'DesCpt', type: 'string' },
                    { name: 'SUBTRAMO', type: 'string' },
                    { name: 'HORAS', type: 'string' },
                    { name: 'BASE', type: 'string' },
                    { name: 'SucCtaDes', type: 'string' }
                ],
                hierarchy:
                {
                    keyDataField: { name: 'ACTIVIDAD_ID' },
                    parentDataField: { name: 'SUBTRAMO' }
                },
                id: 'ACTIVIDAD_ID',
                localData: conceptos
            };
            var dataAdapter = new $.jqx.dataAdapter(source);
            // create Tree Grid
            $("#treeGrid").jqxTreeGrid(
            {
                width: 900,
                source: dataAdapter,
                pageable: true,
                columnsResize: true,
                sortable: true,
                filterable: true,
                ready: function () {
                    // expand row with 'EmployeeKey = 32'
                    $("#treeGrid").jqxTreeGrid('expandRow', 32);
                    $("#treeGrid").jqxTreeGrid('expandRow', 112);
                },
                columns: [
                  { text: 'Clave', dataField: 'ACTIVIDAD_ID', minWidth: 80, width: 80 },
                  { text: 'Actividad', dataField: 'DesCpt', width: 450 },
                  { text: 'Horas', dataField: 'HORAS', width: 100 },
                  { text: 'Subtramo', dataField: 'SUBTRAMO', width: 150 },
                  { text: 'Base', dataField: 'BASE', width: 80 }
                ]
            });
        });
    </script>
</head>
<body class='default'>
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
        <!--CUERPO DE DOCTO-->
        <br />
        <br />
        <br />
        <div class="main">
            <center>
                <div id="treeGrid">
    			</div>
            </center>      
        </div>
</div>
    
</body>
</html>