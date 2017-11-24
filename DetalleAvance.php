<?php 
require_once('Connections/sac2.php'); 
//require_once('Connections/biometrico.php'); 
header( 'Content-type: text/html; charset=iso-8859-1' );
date_default_timezone_set('America/Mexico_City');
//-------------------------------VALIDAMOS QUE ESTE LOGEADO-----------------------------
if (!isset($_SESSION)) {
  session_start();
}

// print_r($_POST);
// print_r($_GET);

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
$sql = "SELECT DISTINCT PuntoTrabajado.ACTIVIDAD_ID, AvanceDiario.ACTIVIDAD, PuntoTrabajado.SUBTRAMO, SUM(PuntoTrabajado.HORAS) AS HORAS, CatTramos.BASE,  CatConcepto.SubCtaDes FROM PuntoTrabajado INNER JOIN AvanceDiario ON PuntoTrabajado.ACTIVIDAD_ID = AvanceDiario.ACTIVIDAD_ID AND PuntoTrabajado.SUBTRAMO = AvanceDiario.SUBTRAMO INNER JOIN CatTramos ON AvanceDiario.TRAMO = CatTramos.TRAMO AND PuntoTrabajado.SUBTRAMO = CatTramos.SUBTRAMO INNER JOIN CatConcepto ON PuntoTrabajado.ACTIVIDAD_ID = CatConcepto.CvCpt WHERE (PuntoTrabajado.FECHA BETWEEN '2016-04-01' AND '2016-04-30') AND (PuntoTrabajado.SUBTRAMO IN ('".$_SESSION['S_Subtramo']."')) AND SubCtaDes = 'SdR' GROUP BY PuntoTrabajado.ACTIVIDAD_ID, AvanceDiario.ACTIVIDAD, PuntoTrabajado.SUBTRAMO, CatTramos.BASE, CatConcepto.SubCtaDes ORDER BY PuntoTrabajado.ACTIVIDAD_ID, CatTramos.BASE";
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

//DREN
$x=0;
$filas2 = array();
$sql = "SELECT DISTINCT PuntoTrabajado.ACTIVIDAD_ID, AvanceDiario.ACTIVIDAD, PuntoTrabajado.SUBTRAMO, SUM(PuntoTrabajado.HORAS) AS HORAS, CatTramos.BASE,  CatConcepto.SubCtaDes FROM PuntoTrabajado INNER JOIN AvanceDiario ON PuntoTrabajado.ACTIVIDAD_ID = AvanceDiario.ACTIVIDAD_ID AND PuntoTrabajado.SUBTRAMO = AvanceDiario.SUBTRAMO INNER JOIN CatTramos ON AvanceDiario.TRAMO = CatTramos.TRAMO AND PuntoTrabajado.SUBTRAMO = CatTramos.SUBTRAMO INNER JOIN CatConcepto ON PuntoTrabajado.ACTIVIDAD_ID = CatConcepto.CvCpt WHERE (PuntoTrabajado.FECHA BETWEEN '2016-04-01' AND '2016-04-30') AND (PuntoTrabajado.SUBTRAMO IN ('".$_SESSION['S_Subtramo']."')) AND SubCtaDes = 'Dren' GROUP BY PuntoTrabajado.ACTIVIDAD_ID, AvanceDiario.ACTIVIDAD, PuntoTrabajado.SUBTRAMO, CatTramos.BASE, CatConcepto.SubCtaDes ORDER BY PuntoTrabajado.ACTIVIDAD_ID, CatTramos.BASE";
 //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }   //OBTENEMOS TODA LA FILA 
 while ($row = odbc_fetch_array($rs)) {    
  $filas2[$x] = array_map('utf8_encode',$row);    
   $x++;    
 }//While
$dren =  $filas2; 
//echo json_encode($dren);

//SV
$x=0;
$filas3 = array();
$sql = "SELECT DISTINCT PuntoTrabajado.ACTIVIDAD_ID, AvanceDiario.ACTIVIDAD, PuntoTrabajado.SUBTRAMO, SUM(PuntoTrabajado.HORAS) AS HORAS, CatTramos.BASE,  CatConcepto.SubCtaDes FROM PuntoTrabajado INNER JOIN AvanceDiario ON PuntoTrabajado.ACTIVIDAD_ID = AvanceDiario.ACTIVIDAD_ID AND PuntoTrabajado.SUBTRAMO = AvanceDiario.SUBTRAMO INNER JOIN CatTramos ON AvanceDiario.TRAMO = CatTramos.TRAMO AND PuntoTrabajado.SUBTRAMO = CatTramos.SUBTRAMO INNER JOIN CatConcepto ON PuntoTrabajado.ACTIVIDAD_ID = CatConcepto.CvCpt WHERE (PuntoTrabajado.FECHA BETWEEN '2016-04-01' AND '2016-04-30') AND (PuntoTrabajado.SUBTRAMO IN ('".$_SESSION['S_Subtramo']."')) AND SubCtaDes = 'SV' GROUP BY PuntoTrabajado.ACTIVIDAD_ID, AvanceDiario.ACTIVIDAD, PuntoTrabajado.SUBTRAMO, CatTramos.BASE, CatConcepto.SubCtaDes ORDER BY PuntoTrabajado.ACTIVIDAD_ID, CatTramos.BASE";
 //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }   //OBTENEMOS TODA LA FILA 
 while ($row = odbc_fetch_array($rs)) {    
  $filas3[$x] = array_map('utf8_encode',$row);    
   $x++;    
 }//While
$sv =  $filas3; 
//echo json_encode($sv);

//SH
$x=0;
$filas4 = array();
$sql = "SELECT DISTINCT PuntoTrabajado.ACTIVIDAD_ID, AvanceDiario.ACTIVIDAD, PuntoTrabajado.SUBTRAMO, SUM(PuntoTrabajado.HORAS) AS HORAS, CatTramos.BASE,  CatConcepto.SubCtaDes FROM PuntoTrabajado INNER JOIN AvanceDiario ON PuntoTrabajado.ACTIVIDAD_ID = AvanceDiario.ACTIVIDAD_ID AND PuntoTrabajado.SUBTRAMO = AvanceDiario.SUBTRAMO INNER JOIN CatTramos ON AvanceDiario.TRAMO = CatTramos.TRAMO AND PuntoTrabajado.SUBTRAMO = CatTramos.SUBTRAMO INNER JOIN CatConcepto ON PuntoTrabajado.ACTIVIDAD_ID = CatConcepto.CvCpt WHERE (PuntoTrabajado.FECHA BETWEEN '2016-04-01' AND '2016-04-30') AND (PuntoTrabajado.SUBTRAMO IN ('".$_SESSION['S_Subtramo']."')) AND SubCtaDes = 'SH' GROUP BY PuntoTrabajado.ACTIVIDAD_ID, AvanceDiario.ACTIVIDAD, PuntoTrabajado.SUBTRAMO, CatTramos.BASE, CatConcepto.SubCtaDes ORDER BY PuntoTrabajado.ACTIVIDAD_ID, CatTramos.BASE";
 //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }   //OBTENEMOS TODA LA FILA 
 while ($row = odbc_fetch_array($rs)) {    
  $filas4[$x] = array_map('utf8_encode',$row);    
   $x++;    
 }//While
$sh =  $filas4; 
//echo json_encode($sh);

//SUP
$x=0;
$filas5 = array();
$sql = "SELECT DISTINCT PuntoTrabajado.ACTIVIDAD_ID, AvanceDiario.ACTIVIDAD, PuntoTrabajado.SUBTRAMO, SUM(PuntoTrabajado.HORAS) AS HORAS, CatTramos.BASE,  CatConcepto.SubCtaDes FROM PuntoTrabajado INNER JOIN AvanceDiario ON PuntoTrabajado.ACTIVIDAD_ID = AvanceDiario.ACTIVIDAD_ID AND PuntoTrabajado.SUBTRAMO = AvanceDiario.SUBTRAMO INNER JOIN CatTramos ON AvanceDiario.TRAMO = CatTramos.TRAMO AND PuntoTrabajado.SUBTRAMO = CatTramos.SUBTRAMO INNER JOIN CatConcepto ON PuntoTrabajado.ACTIVIDAD_ID = CatConcepto.CvCpt WHERE (PuntoTrabajado.FECHA BETWEEN '2016-04-01' AND '2016-04-30') AND (PuntoTrabajado.SUBTRAMO IN ('".$_SESSION['S_Subtramo']."')) AND SubCtaDes = 'SUP' GROUP BY PuntoTrabajado.ACTIVIDAD_ID, AvanceDiario.ACTIVIDAD, PuntoTrabajado.SUBTRAMO, CatTramos.BASE, CatConcepto.SubCtaDes ORDER BY PuntoTrabajado.ACTIVIDAD_ID, CatTramos.BASE";
 //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }   //OBTENEMOS TODA LA FILA 
 while ($row = odbc_fetch_array($rs)) {    
  $filas5[$x] = array_map('utf8_encode',$row);    
   $x++;    
 }//While
$sup =  $filas5; 
//echo json_encode($sup);

//ADA
$x=0;
$filas6 = array();
$sql = "SELECT DISTINCT PuntoTrabajado.ACTIVIDAD_ID, AvanceDiario.ACTIVIDAD, PuntoTrabajado.SUBTRAMO, SUM(PuntoTrabajado.HORAS) AS HORAS, CatTramos.BASE,  CatConcepto.SubCtaDes FROM PuntoTrabajado INNER JOIN AvanceDiario ON PuntoTrabajado.ACTIVIDAD_ID = AvanceDiario.ACTIVIDAD_ID AND PuntoTrabajado.SUBTRAMO = AvanceDiario.SUBTRAMO INNER JOIN CatTramos ON AvanceDiario.TRAMO = CatTramos.TRAMO AND PuntoTrabajado.SUBTRAMO = CatTramos.SUBTRAMO INNER JOIN CatConcepto ON PuntoTrabajado.ACTIVIDAD_ID = CatConcepto.CvCpt WHERE (PuntoTrabajado.FECHA BETWEEN '2016-04-01' AND '2016-04-30') AND (PuntoTrabajado.SUBTRAMO IN ('".$_SESSION['S_Subtramo']."')) AND SubCtaDes = 'AdA' GROUP BY PuntoTrabajado.ACTIVIDAD_ID, AvanceDiario.ACTIVIDAD, PuntoTrabajado.SUBTRAMO, CatTramos.BASE, CatConcepto.SubCtaDes ORDER BY PuntoTrabajado.ACTIVIDAD_ID, CatTramos.BASE";
 //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }   //OBTENEMOS TODA LA FILA 
 while ($row = odbc_fetch_array($rs)) {    
  $filas6[$x] = array_map('utf8_encode',$row);    
   $x++;    
 }//While
$ada =  $filas6; 
//echo json_encode($ada);

//DDV
$x=0;
$filas7 = array();
$sql = "SELECT DISTINCT PuntoTrabajado.ACTIVIDAD_ID, AvanceDiario.ACTIVIDAD, PuntoTrabajado.SUBTRAMO, SUM(PuntoTrabajado.HORAS) AS HORAS, CatTramos.BASE,  CatConcepto.SubCtaDes FROM PuntoTrabajado INNER JOIN AvanceDiario ON PuntoTrabajado.ACTIVIDAD_ID = AvanceDiario.ACTIVIDAD_ID AND PuntoTrabajado.SUBTRAMO = AvanceDiario.SUBTRAMO INNER JOIN CatTramos ON AvanceDiario.TRAMO = CatTramos.TRAMO AND PuntoTrabajado.SUBTRAMO = CatTramos.SUBTRAMO INNER JOIN CatConcepto ON PuntoTrabajado.ACTIVIDAD_ID = CatConcepto.CvCpt WHERE (PuntoTrabajado.FECHA BETWEEN '2016-04-01' AND '2016-04-30') AND (PuntoTrabajado.SUBTRAMO IN ('".$_SESSION['S_Subtramo']."')) AND SubCtaDes = 'Ddv' GROUP BY PuntoTrabajado.ACTIVIDAD_ID, AvanceDiario.ACTIVIDAD, PuntoTrabajado.SUBTRAMO, CatTramos.BASE, CatConcepto.SubCtaDes ORDER BY PuntoTrabajado.ACTIVIDAD_ID, CatTramos.BASE";
 //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }   //OBTENEMOS TODA LA FILA 
 while ($row = odbc_fetch_array($rs)) {    
  $filas7[$x] = array_map('utf8_encode',$row);    
   $x++;    
 }//While
$dv =  $filas7; 
//echo json_encode($dv);  
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title id='Description'>Detalle Avance Mensual</title>
    <link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />   
    <link rel="stylesheet" href="js/jquery.ezdz.min.css"><!-- Para adjuntar --> 
	<link rel="stylesheet" href="CssLocal/Menus.css"><!--Necesario para Menu 1-->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <script src="http://code.jquery.com/jquery.min.js"></script><!-- Para adjuntar --> 
    <script src="js/jquery.ezdz.min.js"></script><!-- Para adjuntar --> 
	<script type="text/javascript" src="js/bootstrap.min.js"></script>		
	<script type="text/javascript" src="js/jquery.bpopup-0.11.0.min.js"></script>
	<script type="text/javascript" src="scripts/menu1.js"></script><!--Necesario para Menu 1-->
    <script type="text/javascript" src="scripts/demos.js"></script>
    <script type="text/javascript" src="scripts/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdraw.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxtooltip.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxchart.core.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdata.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxtabs.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcheckbox.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxmenu.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxgrid.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxgrid.selection.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxscrollbar.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxlistbox.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdropdownlist.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxchart.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxinput.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdatatable.js"></script>
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
			var cont=0;

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
			
			
			//CREAMOS EL TAB			
            $('#tabsWidget').jqxTabs({ width: '80%', height: '70%', position: 'top'});
            // Focus jqxTabs.
            $('#tabsWidget').jqxTabs('focus');
			
			//DERECHO DE VIA--------------------------------------------------------------------------			       
			var data =  <?php echo json_encode($dv); ?>;				    
		    var source =
            {
                datafields: [
                    { name: 'HORAS', type: 'number' },	
					{ name: 'ACTIVIDAD_ID', type: 'number' },				
                    { name: 'ACTIVIDAD', type: 'string' },			
                    { name: 'SUBTRAMO', type: 'string' },	
                    { name: 'BASE', type: 'string' }					
                ],
                localdata: data
            };
            var dataAdapter = new $.jqx.dataAdapter(source);

            $("#dv").jqxGrid(
            {
                width: '68%',
               // height: 510,
                source: dataAdapter,
                
                keyboardnavigation: false,
                columns: [
				    { text: 'Clave', dataField: 'ACTIVIDAD_ID', width: 60 },
				    { text: 'Actividad', dataField: 'ACTIVIDAD', width: 450 },
                    { text: 'Horas', dataField: 'HORAS', width: 50 },
                    { text: 'Subtramo', dataField: 'SUBTRAMO', width: 150 }	,
                    { text: 'Base', dataField: 'BASE', width: 80 }					   		                  
                ]
            });
			
			
			
			//SUPERFICIE DE RODAMIENTO--------------------------------------------------------------------------
			
			var data =  <?php echo json_encode($sdr); ?>;				    
		    var source =
            {
                datafields: [
                    { name: 'HORAS', type: 'number' },	
					{ name: 'ACTIVIDAD_ID', type: 'number' },				
                    { name: 'ACTIVIDAD', type: 'string' },			
                    { name: 'SUBTRAMO', type: 'string' },	
                    { name: 'BASE', type: 'string' }					
                ],
                localdata: data
            };
            var dataAdapter = new $.jqx.dataAdapter(source);

            $("#sdr").jqxGrid(
            {
                width: '68%',
               // height: 510,
                source: dataAdapter,
                
                keyboardnavigation: false,
                columns: [
				    { text: 'Clave', dataField: 'ACTIVIDAD_ID', width: 60 },
				    { text: 'Actividad', dataField: 'ACTIVIDAD', width: 450 },
                    { text: 'Horas', dataField: 'HORAS', width: 50 },
                    { text: 'Subtramo', dataField: 'SUBTRAMO', width: 150 }	,
                    { text: 'Base', dataField: 'BASE', width: 80 }					   		                  
                ]
            });
			
			//ATENCION DE ACCIDENTES--------------------------------------------------------------------------
			
			var data =  <?php echo json_encode($ada); ?>;				    
		    var source =
            {
                datafields: [
                    { name: 'HORAS', type: 'number' },	
					{ name: 'ACTIVIDAD_ID', type: 'number' },				
                    { name: 'ACTIVIDAD', type: 'string' },			
                    { name: 'SUBTRAMO', type: 'string' },	
                    { name: 'BASE', type: 'string' }					
                ],
                localdata: data
            };
            var dataAdapter = new $.jqx.dataAdapter(source);

            $("#ada").jqxGrid(
            {
                width: '68%',
               // height: 510,
                source: dataAdapter,
                
                keyboardnavigation: false,
                columns: [
				    { text: 'Clave', dataField: 'ACTIVIDAD_ID', width: 60 },
				    { text: 'Actividad', dataField: 'ACTIVIDAD', width: 450 },
                    { text: 'Horas', dataField: 'HORAS', width: 50 },
                    { text: 'Subtramo', dataField: 'SUBTRAMO', width: 150 }	,
                    { text: 'Base', dataField: 'BASE', width: 80 }						   		                  
                ]
            });
			
			//SEÑALAMIENTO VERTICAL--------------------------------------------------------------------------
			
			var data =  <?php echo json_encode($sv); ?>;				    
		    var source =
            {
                datafields: [
                    { name: 'HORAS', type: 'number' },	
					{ name: 'ACTIVIDAD_ID', type: 'number' },				
                    { name: 'ACTIVIDAD', type: 'string' },			
                    { name: 'SUBTRAMO', type: 'string' },	
                    { name: 'BASE', type: 'string' }					
                ],
                localdata: data
            };
            var dataAdapter = new $.jqx.dataAdapter(source);

            $("#sv").jqxGrid(
            {
                width: '68%',
               // height: 510,
                source: dataAdapter,
                
                keyboardnavigation: false,
                columns: [
				    { text: 'Clave', dataField: 'ACTIVIDAD_ID', width: 60 },
				    { text: 'Actividad', dataField: 'ACTIVIDAD', width: 450 },
                    { text: 'Horas', dataField: 'HORAS', width: 50 },
                    { text: 'Subtramo', dataField: 'SUBTRAMO', width: 150 }	,
                    { text: 'Base', dataField: 'BASE', width: 80 }					   		                  
                ]
            });
			
			//SEÑALAMIENTO HORIZONTAL--------------------------------------------------------------------------
			
			var data =  <?php echo json_encode($sh); ?>;				    
		    var source =
            {
                datafields: [
                    { name: 'HORAS', type: 'number' },	
					{ name: 'ACTIVIDAD_ID', type: 'number' },				
                    { name: 'ACTIVIDAD', type: 'string' },			
                    { name: 'SUBTRAMO', type: 'string' },	
                    { name: 'BASE', type: 'string' }					
                ],
                localdata: data
            };
            var dataAdapter = new $.jqx.dataAdapter(source);

            $("#sh").jqxGrid(
            {
                width: '68%',
               // height: 510,
                source: dataAdapter,
                
                keyboardnavigation: false,
                columns: [
				    { text: 'Clave', dataField: 'ACTIVIDAD_ID', width: 60 },
				    { text: 'Actividad', dataField: 'ACTIVIDAD', width: 450 },
                    { text: 'Horas', dataField: 'HORAS', width: 50 },
                    { text: 'Subtramo', dataField: 'SUBTRAMO', width: 150 }	,
                    { text: 'Base', dataField: 'BASE', width: 80 }						   		                  
                ]
            });
			
			//DRENAJE--------------------------------------------------------------------------
			
			var data =  <?php echo json_encode($dren); ?>;				    
		    var source =
            {
                datafields: [
                    { name: 'HORAS', type: 'number' },	
					{ name: 'ACTIVIDAD_ID', type: 'number' },				
                    { name: 'ACTIVIDAD', type: 'string' },			
                    { name: 'SUBTRAMO', type: 'string' },	
                    { name: 'BASE', type: 'string' }					
                ],
                localdata: data
            };
            var dataAdapter = new $.jqx.dataAdapter(source);

            $("#dren").jqxGrid(
            {
                width: '68%',
               // height: 510,
                source: dataAdapter,
                
                keyboardnavigation: false,
                columns: [
				    { text: 'Clave', dataField: 'ACTIVIDAD_ID', width: 60 },
				    { text: 'Actividad', dataField: 'ACTIVIDAD', width: 450 },
                    { text: 'Horas', dataField: 'HORAS', width: 50 },
                    { text: 'Subtramo', dataField: 'SUBTRAMO', width: 150 }	,
                    { text: 'Base', dataField: 'BASE', width: 80 }					   		                  
                ]
            });
			
			//SUPERVISION--------------------------------------------------------------------------
			
			var data =  <?php echo json_encode($sup); ?>;				    
		    var source =
            {
                datafields: [
                    { name: 'HORAS', type: 'number' },	
					{ name: 'ACTIVIDAD_ID', type: 'number' },				
                    { name: 'ACTIVIDAD', type: 'string' },			
                    { name: 'SUBTRAMO', type: 'string' },	
                    { name: 'BASE', type: 'string' }					
                ],
                localdata: data
            };
            var dataAdapter = new $.jqx.dataAdapter(source);

            $("#sup").jqxGrid(
            {
                width: '68%',
               // height: 510,
                source: dataAdapter,
                
                keyboardnavigation: false,
                columns: [
				    { text: 'Clave', dataField: 'ACTIVIDAD_ID', width: 60 },
				    { text: 'Actividad', dataField: 'ACTIVIDAD', width: 450 },
                    { text: 'Horas', dataField: 'HORAS', width: 50 },
                    { text: 'Subtramo', dataField: 'SUBTRAMO', width: 150 }	,
                    { text: 'Base', dataField: 'BASE', width: 80 }					   		                  
                ]
            });
			
});


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
        <!--CUERPO DE DOCTO-->
        <br />
        <div class="main">
            <center>
                <div id='jqxWidget'>
                        <div id="tabsWidget">
                            <ul style="margin-left: 30px;">
                                <li>Superficie de Rodamiento</li>  
                                <li>Se&ntilde;alamiento Horizontal</li>  
                                <li>Supervisi&oacute;n</li>  
                                <li>Se&ntilde;alamiento Vertical</li>  
                                <li>Drenaje</li>  
                                <li>Derecho de V&iacute;a</li>  
                                <li>Atenci&oacute;n de Accidentes</li>                            
                            </ul>
                             <div>     
                                <br /> 
                                <div class="row">
                                	<div class="col-lg-9">                    
                                    	<div id="sdr"></div>
                                    </div>   
                                	<div class="col-lg-3" align="left">   
                                    	<input type="file" name="archivo1" id="archivo1" accept="image/png, image/jpeg, image/jpg, image/bmp">   
                                    </div>   
                                 </div>
                                  
                            </div>
                             <div>     
                                <br />                     
                                    <div id="sh"></div>        
                            </div>
                             <div>     
                                <br />                    
                                    <div id="sup"></div>         
                            </div>
                             <div>     
                                <br />                     
                                    <div id="sv"></div>    
                            </div>
                             <div>     
                                <br />                     
                                    <div id="dren"></div>    
                            </div>
                             <div>     
                                <br />                      
                                    <div id="dv"></div>     
                            </div>
                             <div>     
                                <br />                      
                                    <div id="ada"></div>       
                            </div>                                 
                        </div>              
                </div>
            </center>      
        </div>
</div>

<script>
        $('[id="archivo1"]').ezdz({
            text: 'Actividad Inicio',
            validators: {
                maxWidth:  3648,
                maxHeight: 2736
            },
            reject: function(file, errors) {
                if (errors.mimeType) {
                    alert(file.name + ' must be an image.');
                }

                if (errors.maxWidth) {
                    alert(file.name + ' ');
                }

                if (errors.maxHeight) {
                    alert(file.name + ' ');
                }
            }
        });
		 /*$('[id="archivo2"]').ezdz({
            text: 'Actividad Proceso',
            validators: {
                maxWidth:  3648,
                maxHeight: 2736
            },
            reject: function(file, errors) {
                if (errors.mimeType) {
                    alert(file.name + ' must be an image.');
                }

                if (errors.maxWidth) {
                    alert(file.name + ' ');
                }

                if (errors.maxHeight) {
                    alert(file.name + ' ');
                }
            }
        });
		$('[id="archivo3"]').ezdz({
            text: 'Actividad Fin',
            validators: {
                maxWidth:  3648,
                maxHeight: 2736
            },
            reject: function(file, errors) {
                if (errors.mimeType) {
                    alert(file.name + ' must be an image.');
                }

                if (errors.maxWidth) {
                    alert(file.name + ' ');
                }

                if (errors.maxHeight) {
                    alert(file.name + ' ');
                }
            }
        });*/
    </script>
</body>
</html>