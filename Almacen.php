<?php 
require_once('Connections/sac2.php'); 
//require_once('Connections/biometrico.php'); 
header( 'Content-type: text/html; charset=iso-8859-1' );
date_default_timezone_set('America/Mexico_City');
//-------------------------------VALIDAMOS QUE ESTE LOGEADO-----------------------------
if (!isset($_SESSION)) {
  session_start();
}

//print_r($_POST);
// print_r($_GET);
//echo $_SESSION['S_Lugar'];
$login_ok = $_SESSION['login_ok'];
//RESTRINGIMOS EL ACCESO A USUARIOS NO IDENTIFICADOS
if ($login_ok == "identificado"){
 
}else{
echo "No Identificado";
	//session_unset();  // remove all session variables
	session_destroy();  // destroy the session 
	header("Location: index.php");
} 

//CARGAMOS LOS ACCESOS EN VARIABLES DE SESION
$i=0;
$sql = "SELECT DISTINCT (TRAMO), BASE, PLAZA, SUBTRAMO FROM Accesos WHERE Usuario_ID='".$_SESSION['S_UsuarioID']."'";
//echo $sql;
$rs = odbc_exec( $conn, $sql );
if ( !$rs ) { 
	exit( "Error en la consulta SQL" ); 
}     
while ( odbc_fetch_row($rs) ) {
  $_SESSION['S_Lugar']  = odbc_result($rs, 'BASE');    
	if ($i == 0){
		$_SESSION['S_Tramo'] = odbc_result($rs, 'TRAMO'); 
		$_SESSION['S_Base'] = odbc_result($rs, 'BASE');
		$_SESSION['S_Plaza'] = odbc_result($rs, 'PLAZA');
		$_SESSION['S_Subtramo'] = odbc_result($rs, 'SUBTRAMO');
	}else{
		$_SESSION['S_Tramo'] .= "','". odbc_result($rs, 'TRAMO');
		$_SESSION['S_Base'] .= "','". odbc_result($rs, 'BASE');
		$_SESSION['S_Plaza'] .= "','". odbc_result($rs, 'PLAZA');
		$_SESSION['S_Subtramo'] .= "','". odbc_result($rs, 'SUBTRAMO');	
	}
	$i++;
}//While 

if (isset($_POST['cerrar_sesion'])) {
	session_destroy();  // destroy the session 
	header("Location: index.php");
	
}
//echo $_SESSION['S_Base'];
if ($_SESSION['S_Base'] == "EN01','EN01','OC01','USA01','USA01','OC01','OC01','PA01','PA01','ZI01','ZI01','SB1','PA01','JA01','TE01','TO01','TO01"){
		$mostrar="Todas";
}else{
	$mostrar = $_SESSION['S_Lugar'];
}
$salida_dia = '';
$base_salida = '';
//---------------------------------------------------------FILTRO-----------------------------------
$buscarbase = $_SESSION['S_Base'];
 	
if (isset($_REQUEST['filtrar'])) {

	$buscarbase = $_POST['buscarbase'];
	
	
if ($buscarbase == "EN01','EN01','OC01','USA01','USA01','OC01','OC01','PA01','PA01','ZI01','ZI01','SB1','JA01','TE01','TO01','TO01"){
		$mostrar="Todas";
	}else{
		$mostrar = $buscarbase;
	}
  	
	if ($buscarbase == 'EN01'){
  		$sql1 = "select * from Almacen where Base in ('USA01') AND Ubicacion_almacen='Oracle' order by Ubicacion_almacen";
  		$sql3 = "select * from Almacen where Base in ('USA01') AND Ubicacion_almacen='Sac' order by Ubicacion_almacen";
		$sql = "select SUM(Importe) as TOTAL from Almacen where Base in ('USA01')";
	}else if($buscarbase == 'JA01'){
  		$sql1 = "select * from Almacen where Base in ('TE01') AND Ubicacion_almacen='Oracle' order by Ubicacion_almacen";
  		$sql3 = "select * from Almacen where Base in ('TE01') AND Ubicacion_almacen='Sac' order by Ubicacion_almacen";
		$sql = "select SUM(Importe) as TOTAL from Almacen where Base in ('TE01')";
	}else{
  		$sql1 = "select * from Almacen where Base in ('".$buscarbase."') AND Ubicacion_almacen='Oracle' order by Ubicacion_almacen";
  		$sql3 = "select * from Almacen where Base in ('".$buscarbase."') AND Ubicacion_almacen='Sac' order by Ubicacion_almacen";
		$sql = "select SUM(Importe) as TOTAL from Almacen where Base in ('".$buscarbase."')";
	}

	$sql2 = "select * from Salidas where Base in ('".$buscarbase."') and FECHA = '".date("Y-m-d")."'";
	
    //echo $sql;
  	$rs = odbc_exec( $conn, $sql );
   	if ( !$rs ) { 
  	    exit( "Error en la consulta SQL" ); 
  	} 	
	while ( odbc_fetch_row($rs) ) {  
		$total = odbc_result($rs, 'TOTAL');
	}
 	  
}else{
	
	$sql1 = "select * from Almacen where Base in ('".$_SESSION['S_Base']."','".$_SESSION['S_Plaza']."') AND Ubicacion_almacen = 'Oracle' order by Ubicacion_almacen";
	$sql3 = "select * from Almacen where Base in ('".$_SESSION['S_Base']."','".$_SESSION['S_Plaza']."') AND Ubicacion_almacen = 'Sac' order by Ubicacion_almacen";
	$sql2 = "select * from Salidas where Base in ('".$_SESSION['S_Base']."','".$_SESSION['S_Plaza']."') and FECHA = '".date("Y-m-d")."'";	
	$sql = "select SUM(Importe) as TOTAL from Almacen where Base in ('".$_SESSION['S_Base']."','".$_SESSION['S_Plaza']."')";
    //echo $sql;
  	$rs = odbc_exec( $conn, $sql );
   	if ( !$rs ) { 
  	    exit( "Error en la consulta SQL" ); 
  	} 	
	while ( odbc_fetch_row($rs) ) {  
	$total = odbc_result($rs, 'TOTAL');
	}
}
/***********************************BUSCAR SALIDAS DEL DÍA**********************************************/

if ($mostrar != 'Todas'){
    //echo $sql2;
  	$rs = odbc_exec( $conn, $sql2 );
   	if ( !$rs ) { 
  	    exit( "Error en la consulta SQL" ); 
  	} 	
	while ( odbc_fetch_row($rs) ) {  
		$salida_dia = odbc_result($rs, 'ID_SALIDA');
		$base_salida = odbc_result($rs, 'BASE');
	}
}else{
	$base_salida = 'Todas';
}

/*************************************************GRID*************************************************/
$x=0;
$filas = array();
  //echo $sql1;
  $rs = odbc_exec( $conn, $sql1 );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }   //OBTENEMOS TODA LA FILA 
 while ($row = odbc_fetch_array($rs)) {  
	 $filas[$x] = array_map('utf8_encode',$row);    
	 $x++;    
 }//While
$datos =  $filas; 

$x=0;
$filas3 = array();
  //echo $sql2;
  $rs = odbc_exec( $conn, $sql3 );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }   //OBTENEMOS TODA LA FILA 
 while ($row = odbc_fetch_array($rs)) {  
	 $filas3[$x] = array_map('utf8_encode',$row);    
	 $x++;    
 }//While
$datos3 =  $filas3;

/**************************************************GUARDAR ARTICULO************************************************/
if (isset($_REQUEST['guardaralmacen'])) {
	
	$base_alm = $_POST["base_almacen"];
	$presupuesto = $_POST["presupuesto"];
	$desc = $_POST["desc_almacen"];
	$unidad = $_POST["unidad_almacen"];
	$precio = $_POST["precio_almacen"];
	$exist = $_POST["exixstencia_almacen"];
	
	$importe = $precio * $exist;
	
	$sql = "SELECT TOP 1 * FROM Almacen WHERE Ubicacion_almacen ='Sac' order by Articulo desc";   
  //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }     
  while ( odbc_fetch_row($rs) ) { 
    $registro = odbc_result($rs, 'Articulo');	
  }//While   
  $registro = substr($registro,3,5); 
  $maximo = intval($registro); 
  $maximo++;
    
  $longitud = strlen($maximo);
  switch ($longitud) {
    case "1":
        $articulo = "IN-0000".$maximo;
        break;
    case "2":
        $articulo = "IN-000".$maximo;
        break;
    case "3":
       $articulo = "IN-00".$maximo;
        break;
    case "4":
       $articulo = "IN-0".$maximo;
        break;
    case "5":
       $articulo = "IN-".$maximo;
        break;
  } 
  //echo $articulo;
	
	$sql = "INSERT INTO Almacen VALUES ('".$base_alm."','','".$articulo."','".$desc."','".$unidad."','".$precio."','".$exist."','".$importe."','','".$presupuesto."','Sac')";
	//echo $sql;
	$rs = odbc_exec( $conn, $sql );
	if ( !$rs ) { 
		exit( "Error en la consulta Almacen" ); 
	}
	
	header("Location: Almacen.php");
}

/**************************************************EDITAR ARTICULO************************************************/
if (isset($_REQUEST['editaralmacen'])) {
	
	$edit_art = $_POST["edit_art"];
	$edit_unidad = $_POST["edit_unidad"];
	$edit_exist = $_POST["edit_exist"];
	$edit_base = $_POST["edit_base"];
	$edit_precio = $_POST["edit_precio"];
	$edit_desc = $_POST["edit_desc"];

	//$importe = $precio * $exist;
	
	$sql = "UPDATE Almacen SET Articulo = '".$edit_art."', Unidad = '".$edit_unidad."', Exist = '".$edit_exist."', Base = '".$edit_base."', Precio_Unitario = '".$edit_precio."' WHERE Descrip = '".$edit_desc."'";
	//echo $sql;
	$rs = odbc_exec( $conn, $sql );
	if ( !$rs ) { 
		exit( "Error en la consulta Almacen" ); 
	}
	
	header("Location: Almacen.php");
}

/**************************************************ELIMINAR FOLIO************************************************/
if (isset($_REQUEST['eliminarFolio'])) {
	
	$eliminarFol = $_POST["eliminarFol"];
	//$importe = $precio * $exist;
	
	$sql = "DELETE FROM Salidas WHERE ID_SALIDA = '".$eliminarFol."'";
	//echo $sql;
	$rs = odbc_exec( $conn, $sql );
	if ( !$rs ) { 
		exit( "Error en la consulta Salidas" ); 
	}
	
	//header("Location: Almacen.php");
}

/**************************************AUTOCOMPLETAR ALMACEN******************************************************/
$descripcion = array();
$x=0;
  $sql3 = "SELECT * FROM Almacen WHERE Ubicacion_almacen = 'Sac'";
  $rs = odbc_exec( $conn, $sql3 );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }
  while ( odbc_fetch_row($rs) ) { 
    $descripcion[$x] = "\"" .odbc_result($rs, 'Descrip'). "\",";	
	$x++;
}//While
if ($x > 0){
    $descripcion[$x-1] = str_replace(",","",$descripcion[$x-1]);
}

/**************************************AUTOCOMPLETAR FOLIO SALIDA******************************************************/
$fol_sal = array();
$x=0;
  $sql3 = "SELECT DISTINCT(ID_SALIDA) FROM Salidas ORDER BY ID_SALIDA";
  $rs = odbc_exec( $conn, $sql3 );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }
  while ( odbc_fetch_row($rs) ) { 
    $fol_sal[$x] = "\"" .odbc_result($rs, 'ID_SALIDA'). "\",";	
	$x++;
}//While
if ($x > 0){
    $fol_sal[$x-1] = str_replace(",","",$fol_sal[$x-1]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title id='Description'>Almacén 2</title>
    <link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />    
	<link rel="stylesheet" href="CssLocal/Menus.css"><!--Necesario para Menu 1-->  
	<link rel="stylesheet" href="CssLocal/Menu1.css"><!--Necesario para Menu 1-->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    
    <script type="text/javascript" src="scripts/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdraw.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxtooltip.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxchart.core.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdata.js"></script>
	<script type="text/javascript" src="scripts/menu1.js"></script><!--Necesario para Menu 1-->
	<script type="text/javascript" src="js/bootstrap.min.js"></script>		
	<script type="text/javascript" src="js/jquery.bpopup-0.11.0.min.js"></script>
    <script type="text/javascript" src="scripts/demos.js"></script>
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
		font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;
		float: left;
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
		height: 50px;
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
		height: 50px;
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
		height: 50px;
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
		margin-right: 5px;		
		padding: 5px;
		width: 49%;
		height: 100%;
		float: left;			
		box-sizing: border-box;		
		overflow: hidden;			
	}	

	#almacenSac{
		margin-right: 2%;
		float: left;
		padding-top: 5px;
		width: 35%;
		height: 70%;
		border: 2px dashed #49A2FF;
		border-radius: 10px;
		box-sizing: border-box;
		overflow-y: scroll;
		overflow-x: hidden;
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
	.total {				
		border-radius: 10px;
		margin-top: 10px;
		margin-left: 5px;
		margin-right: 15px;
		position: relative;				
		background: black;
		font-family: arial;
		font-size: 23px;
		font-weight: bold;
		color: white;
		padding-top: 30px;
		padding-left: 15px;
		padding-right: 15px;				
		width: 35%;
		height: 10%;
		float: left;					
		overflow: hidden;
		box-sizing: border-box;
		cursor:pointer;
	}		
	.total1 {				
		border-radius: 10px;
		margin-top: 5px;
		margin-left: 5px;
		margin-right: 15px;
		position: relative;				
		background: black;
		font-family: arial;
		font-size: 23px;
		font-weight: bold;
		color: white;
		padding-top: 15px;
		padding-left: 15px;
		padding-right: 15px;				
		width: 35%;
		height: 5%;
		float: left;					
		overflow: hidden;
		box-sizing: border-box;
		cursor:not-allowed;
	}
	.money2 {
		position: relative;
		float: right;		
		text-align: right;
		font-family: arial;
		font-size: 23px;
		font-weight: bold;		
	}
	input.inputstyle{
		font-family: Arial; 
		font-size: 3pt; 
		background-color: #00FF00;
	}
</style>
<script type="text/javascript">
$(document).ready(function () {
			var cont=0;
			var total=0;           
            var arrayconcep=[];
            var precio=[];
			var cant=[];

            //EFECTOS EN EL MENU
            $("#menu1").mouseenter(function(e){
				$("#menu1").css({"background": "#49A2FF", "border":"0px"});			  
			});
			$("#menu1").mouseleave(function(e){
				$("#menu1").css({"background": "#2c2c2c"});			  
			});
            $("#menu2").mouseenter(function(e){
				$("#menu2").css({"background": "#49A2FF", "border":"0px"});			  
			});
			$("#menu2").mouseleave(function(e){
				$("#menu2").css({"background": "#49A2FF"});			  
			});
			$("#menu3").mouseenter(function(e){
				$("#menu3").css({"background": "#49A2FF", "border":"0px"});			  
			});
			$("#menu3").mouseleave(function(e){
				$("#menu3").css({"background": "#2c2c2c"});			  
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
			$("#menu10").mouseenter(function(e){
				$("#menu10").css({"background": "#49A2FF", "border":"0px"});			  
			});
			$("#menu10").mouseleave(function(e){
				$("#menu10").css({"background": "#2c2c2c"});			  
			});	
            $("#menu11").mouseenter(function(e){
				$("#menu11").css({"background": "#49A2FF", "border":"0px"});			  
			});
			$("#menu11").mouseleave(function(e){
				$("#menu11").css({"background": "#2c2c2c"});			  
			});
            $("#menu12").mouseenter(function(e){
				$("#menu12").css({"background": "#49A2FF", "border":"0px"});			  
			});
			$("#menu12").mouseleave(function(e){
				$("#menu12").css({"background": "#2c2c2c"});			  
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
			
			//CREAMOS EL TAB			
            $('#tabsWidget').jqxTabs({ width: '100%', height: '80%', position: 'top'});
            $('#tabsWidget').jqxTabs('focus');
			
			
			var mostrar = '<?php echo $mostrar; ?>';
			
			if (mostrar == 'Todas'){
				$("#generaSalida").hide();
				$("#buscarArt").attr("disabled",true);
				$("#buscarArt").attr("placeholder","Filtra una base");
			}else{
				$("#generaSalida").show();
				$("#buscarArt").attr("disabled",false);
			}
		
		
			//GRID ALMACEN ORACLE
			var data =  <?php echo json_encode($datos); ?>;				    
		    var source =
            {
                datafields: [
					{ name: 'Importe', type: 'smallmoney' },
					{ name: 'Unidad', type: 'nvarchar' },
                    { name: 'Exist', type: 'number' },	
					{ name: 'Precio_Unitario', type: 'number' },
					{ name: 'Observaciones', type: 'varchar' },		
					{ name: 'Almacen', type: 'string' },
					{ name: 'Articulo', type: 'nchar' },
					{ name: 'Descrip', type: 'nvarchar' },
					{ name: 'Ubicacion_almacen', type: 'string' },
					{ name: 'Base', type: 'nvarchar' }													
                ],
                localdata: data 
            };
			//var bandera = false;
			var cellclass = function (row, columnfield, value) {	
				//alert(value);			
                if (value == "Sac") {
					//alert("Color");
					//bandera == true;
                    return 'lightgrey';
                }
            }
            var dataAdapter = new $.jqx.dataAdapter(source);

            $("#almacen").jqxGrid(
            {
                width: '100%',
				height: '90%',
                source: dataAdapter,
                
                keyboardnavigation: false,
                columns: [
				  { text: 'Articulo', dataField: 'Articulo', width: '15%' },
				  { text: 'Descripcion', dataField: 'Descrip', width: '48%' },
				  { text: 'Unidad', dataField: 'Unidad', cellsAlign: 'center', width: '10%' },
				  { text: 'P / Unitario', dataField: 'Precio_Unitario', cellsformat: 'c2', cellsAlign: 'right', width: '15%' },
				  { text: 'Existencia', dataField: 'Exist', cellsformat: 'f2', cellsAlign: 'right', width: '12%' },
                ]
            });
			
			//GRID ALMACEN SAC
			var data =  <?php echo json_encode($datos3); ?>;				    
		    var source =
            {
                datafields: [
					{ name: 'Importe', type: 'smallmoney' },
					{ name: 'Unidad', type: 'nvarchar' },
                    { name: 'Exist', type: 'number' },	
					{ name: 'Presupuesto', type: 'string' },
					{ name: 'Precio_Unitario', type: 'number' },
					{ name: 'Observaciones', type: 'varchar' },		
					{ name: 'Almacen', type: 'string' },
					{ name: 'Articulo', type: 'nchar' },
					{ name: 'Descrip', type: 'nvarchar' },
					{ name: 'Ubicacion_almacen', type: 'string' },
					{ name: 'Base', type: 'nvarchar' }													
                ],
                localdata: data 
            };
			
            var dataAdapter = new $.jqx.dataAdapter(source);

            $("#almacenS").jqxGrid(
            {
                width: '100%',
				height: '90%',
                source: dataAdapter,
                
                keyboardnavigation: false,
                columns: [
				  { text: 'Articulo', dataField: 'Articulo', width: '15%' },
				  { text: 'Descripcion', dataField: 'Descrip', width: '38%' },
				  { text: 'Unidad', dataField: 'Unidad', cellsAlign: 'center', width: '10%' },
				  { text: 'P / Unitario', dataField: 'Precio_Unitario', cellsformat: 'c2', cellsAlign: 'right', width: '12%' },
				  { text: 'Existencia', dataField: 'Exist', cellsformat: 'f2', cellsAlign: 'right', width: '12%' },
				  { text: 'Presupuesto', dataField: 'Presupuesto', width: '12%' },
                ]
            });
						
			
            $("#buscarArt").keypress(function(e){
            	if(e.which == 13){
            		var articulo = $("#buscarArt").val();
				
					$("#buscarArt").val('');					
					$.post("consultaAvanceDiario.php", { art: articulo }, function(data){
						var consecutivo = $("#consecutivo").val();
						//alert(data);
						if (data == 0){
							alert("No hay existencias");
						}else{
							var token = data.split();
							var variable = token[0].split('*');

							if (variable == ""){
								alert('Articulo no encontrado');
									
							}else{			
							//alert(cont);
								arrayconcep[cont] = variable[0];
								precio[cont]=variable[1];
								cant[cont]=1;
								consecutivo++;
								$("#almacenSac").append("<div class='titulo2' id='art"+ cont + "'>"+consecutivo+"</div><div class='titulo1'><input type='hidden' name='articulo-"+ cont +"' id='articulo-"+ cont +"' value='"+articulo+"'><input type='hidden' name='descripcion-"+ cont +"' id='descripcion-"+ cont +"' value='"+variable[0]+"'>"+variable[0]+"</div><div class='titulo2'><input type='number' value='1' id='cantidad-"+ cont + "' style='width:50' step='any'></div><div class='titulo3'><span id='importe"+ cont +"'>$"+variable[1]+"</span></div>");
								cont ++;
								var subtotal = parseFloat($("#subtotal").val());
								total += parseFloat(variable[1]);
								var resultado = total + subtotal;
								$("#total").text(formatNumber.new(resultado, '$'));
								$("#subtotal").val(resultado);
								$("#consecutivo").val(consecutivo);
								//alert();
				      		}
						}
			     	});
            	}//Si presiona Enter
				// <input type='number' name='cant"+ cont + "' value='1'>
            });

			$('#almacenSac').click(function(e){
					var id = e.target.id;
					var token = id.split();
					var variable = token[0].split('-');
					var campo = variable[1];
					var subt2 = 0;

				$('#cantidad-' + campo).blur(function(){
					cant[campo] = $('#cantidad-' + campo).val();
					var imp = $('#importe' + campo).text();
					var subtotal = $("#subtotal").val();
					var articulo = $('#articulo-' + campo).val();
					precio[campo]=0;
					
					$.post("consultaAvanceDiario.php", { art: articulo }, function(data){
						//alert(data);
						var token = data.split();
						var variable = token[0].split('*');

						imp = imp.replace("$", "");
						subtotal = subtotal - imp;
						subt2 = variable[1] * cant[campo];
						total = subtotal + subt2;
						//alert(subt2);
						precio[campo]=subt2;
						//alert(precio[campo]);
						$('#importe' + campo).text('$ ' + subt2);
						$("#subtotal").val(total);
						$("#total").text(formatNumber.new(total, '$'));
					 });//post
				});//blur
			});//click

				//GUARDAR
		function Guardar(){
			if(cont == 0){
				alert("No hay articulos seleccionados o no se agregaron nuevos");

			}else{
				//SE SACA EL ULTIMO FOLIO, SE LE SUMA 1 Y SE GUARDA CON ESE NÚMERO
				var base = '<?php echo $mostrar; ?>';
				$.post("consultaAvanceDiario.php", { FolioSalida: '', Base: base }, function(data){
					//alert(cont);
					var folio = data;

					for (var i = 0; i < cont; i++) {
						//var cant = $('#cantidad-'+i).val();
						//var cant = document.getElementById("cantidad-"+i).value;
						//alert(cant[i]);

						$.post("consultaAvanceDiario.php", { GuardaSalida1: '', FolioSa: folio, Base: base, Concepto: arrayconcep[i], cantidad: cant[i], Importe: precio[i] }, function(data2){
						//alert(data2);	

						});//post
							location.reload();
							//window.print("Hola");
					}
				});	
			}//Else	
		}
		$("#generaSalida").click(function(){
				$("#Modal").modal('show');							
		})
		$("#Guardar").click(function(){
				Guardar();	
		});

		 var descripcion = new Array(<?php  
	        foreach ($descripcion as &$valor) {
              echo $valor;			  
            }		
	        ?>);
		$("#buscar_desc").jqxInput({placeHolder: "Introcude descripcion", minLength: 1,  source: descripcion });
		
		 var folio_salida = new Array(<?php  
	        foreach ($fol_sal as &$valor) {
              echo $valor;			  
            }		
	        ?>);
		$("#eliminarFol").jqxInput({placeHolder: "Introcude folio", minLength: 1,  source: folio_salida });
		
		$("#buscar_desc").blur(function(){
			var desc = $(this).val();
			
			if (desc == ""){
				alert("Poner un material valido");
			}else{
			
				$.post("consultaAvanceDiario.php", { AlmacenSac: '', Desc: desc }, function(data){
					var token = data.split();
					var variable = token[0].split('*');
					if (variable[1] == ""){
						alert("Poner un material valido");
					}else{
					
						$("#edit_art").val(variable[0]);
						$("#edit_unidad").val(variable[1]);
						$("#edit_exist").val(variable[2]);
						$("#edit_base").val(variable[3]);
						$("#edit_precio").val(variable[4]);
						$("#edit_desc").val(desc);
						$("#buscar_desc").attr("disabled",true);
						
						document.getElementById("mostrar").style.display = "block";
					}
				});
			}
			
		});		

});//document.ready
$(window).load(function() {
	/********************************VERIFICAR SI HAY SALIDAS DEL DÍA*******************************************/
	var base = '<?php echo $base_salida; ?>';
	if(base == 'Todas'){
		$.post("consultaAvanceDiario.php", { BuscarTodas: '' }, function(data){		
				var token = data.split();
				var variable = token[0].split('*');				
				$("#almacenSac").html(variable[0]);
				$("#totalsalida").text(formatNumber.new(variable[1], '$'));
		});
	}else 		
	
		$.post("consultaAvanceDiario.php", { BuscarSalida2: '', Base: base }, function(data){	
				//alert(data);
				var token = data.split();
				var variable = token[0].split('*');
				$("#almacenSac").html(variable[0]);
				$("#consecutivo").val(variable[2]);				
				$("#subtotal").val(variable[1]);
				//var n = variable[1].toFixed(2);
				if (variable[1] == ''){
					$("#total").text("$0.00");		
					$("#subtotal").val(0);
				}else{
					$("#total").text(formatNumber.new(variable[1], '$'));
				}//
		});
	
});//$(window).load	
/**********************************FUNCION FORMATO NUMEROS**********************************/
var formatNumber = {
 separador: ",", // separador para los miles
 sepDecimal: '.', // separador para los decimales
 formatear:function (num){
  num +='';
  var splitStr = num.split('.');
  var splitLeft = splitStr[0];
  var splitRight = splitStr.length > 1 ? this.sepDecimal + splitStr[1] : '';
  var regx = /(\d+)(\d{3})/;
  while (regx.test(splitLeft)) {
  splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
  }
  return this.simbol + splitLeft  +splitRight;
 },
 new:function(num, simbol){
  this.simbol = simbol ||'';
  return this.formatear(num);
 }
}
</script>    
</head>
<body>

<div class="contenedor">

	<header>
		<a href="index.php"><img class="derecha" src="images/cerrarsesion.png"></a>
		<a href="Almacen.php"><span>Insumos</span></a>
		<a href="Salidas.php"><span>Salida Insumos</span></a>
		<a href="AlmacenMaq.php"><span>Maquinaria</span></a>
		<a href="SalidasMaq.php"><span>Entrada Maq</span></a>
		<a href="AvanceDiarioPlus.php"><span>Avance Diario</span></a>
            <?php 
			if($_SESSION['S_Privilegios'] == 'ADMINISTRADOR' || $_SESSION['S_Privilegios'] == 'COORDINADOR'){
			 ?>
		<a href="Contratos.php"><span>Contratos</span></a>
		<a href="Comparativo.php"><span>Comparativa</span></a>
			<?php } ?>  
		<a href="Prorrateo.php"><span>Carga Costos MO</span></a>
		<a href="ProrrateoAct.php"><span>Prorrateo MO</span></a>
		<a href="ProrrateoMaq.php"><span>Carga Costos Maq</span></a>
		<a href="ProrrateoMaquinaria.php"><span>Prorrateo Maq</span></a> 
	</header>

	<div class="menulateral">
        <div class="submenu_lateral_encabezado">
        	<span class="glyphicon glyphicon-wrench"></span> &rlm; HERRAMIENTAS
        </div>
		<a href="#" data-toggle="modal" data-target=".filtro" onClick="Limpiar()"><div class="submenu_lateral" id="sub1">
			<span class="glyphicon glyphicon-filter"></span> &rlm; Filtro 
		</div></a>
		<?php if ($_SESSION['S_Privilegios'] == "COORDINADOR" || $_SESSION['S_Privilegios'] == "ADMINISTRADOR"){ ?>
            <a href="#" data-toggle="modal" data-target=".guardar_almacen" onClick="Limpiar()"><div class="submenu_lateral" id="sub2">
                <span class="glyphicon glyphicon-upload"></span> &rlm; Agregar Almac&eacute;n 
            </div></a>
            <a href="#" data-toggle="modal" data-target=".editar_almacen" onClick="Limpiar()"><div class="submenu_lateral" id="sub3">
                <span class="glyphicon glyphicon-edit"></span> &rlm; Editar Almac&eacute;n 
            </div></a>
           <a href="#" data-toggle="modal" data-target=".eliminar_folio" onClick="Limpiar()"><div class="submenu_lateral" id="sub4">
                <span class="glyphicon glyphicon-trash"></span> &rlm; Eliminar Folio 
            </div></a>
          <?php } ?>
		<!-- <a href="#" data-toggle="modal" data-target=".descargar" onClick="Limpiar()"><div class="submenu_lateral" id="sub3">
			<span class="glyphicon glyphicon-download"></span> &rlm; Descargar
		</div> </a>-->
 </form>          
	</div>

	<div id="buscar">
		<center><!-- <strong>B&uacute;squeda:</strong>  --><input type="text" id="buscarArt" value="" size="10"class="form-control" placeholder="Buscar" autofocus/></center>
		<!--<button id="test">ok</button>-->	
	</div>	

	<div id="main">	      
        <div id='jqxWidget'>
          <div id="tabsWidget">
            <ul style="margin-left: 30px;">
            	<li>Almacen Oracle</li>        
            	<li>Almacen Sac</li>                  
            </ul>         
            <div>
            	<br>
                <div class="row">
                	<div class="col-lg-6" align="center"><strong>Base: <span><?php echo $mostrar; ?></span></strong></div>
                	<div class="col-lg-6" align="center"><strong>Total Almacen (<span><?php echo $mostrar; ?></span>): $<?php echo number_format($total, 2, '.', ','); ?></strong></div>
                </div>
            	<br>
                <div id="almacen"></div>              
            </div>      
            <div>
            	<br>
                <div class="row">
                	<div class="col-lg-6" align="center"><strong>Base: <span><?php echo $mostrar; ?></span></strong></div>
                	<!--<div class="col-lg-6" align="center"><strong>Total Almacen (<span><?php echo $mostrar; ?></span>): $<?php echo number_format($total, 2, '.', ','); ?></strong></div>-->
                </div>
            	<br>
                <div id="almacenS"></div>              
            </div>
          </div>
        </div>


	</div>	<input type='hidden' id='subtotal' value='0'><input type='hidden' id='consecutivo' value='0'>
	<div id="almacenSac">
		<div id="art">
			<span class="titulo">#</span>			
		</div>
		<div id="descripcion">
			<span class="titulo">Descripcion</span>			
		</div>
		<div id="cantidad">
			<span class="titulo">Cantidad</span>			
		</div>
		<div id="importe">
			<span class="titulo">Importe</span>			
		</div>	
	</div>
	<div class="total" id="generaSalida">					
		Total <label class="money2"><label id="total">$0.00</label></label>
	</div>
</div>

<!-- Genera Salidas -->   
<div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Genarar Salidas de Almacen</h4>
      </div>
      <div class="modal-body">
        <center>&iquest;Deseas guardar una salida de almacen?</center>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" id="Guardar">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<!--DESCARGAR-->
<form id="formulario5" action="Excel_AlmacenSalidas.php" method="post" enctype="multipart/form-data" >
<div class="modal fade" id="descargar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Salidas de Almacen</h4>
      </div>
      <div class="modal-body">
	<input type="hidden" name="fol_desc" id="fol_desc">
        <center>&iquest;Deseas descargar una salida de almacen?</center>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" id="Descargar">Descargar</button>
      </div>
    </div>
  </div>
</div>
</form>

<!-- FILTRO -->
<form id="formulario5" action="Almacen.php" method="post" enctype="multipart/form-data" >      
<div class="modal fade filtro" id="filtro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><center>  <img src="images/filtro_header.png" height="40"> FILTRO DE INFORMACI&Oacute;N <img src="images/filtro_header1.png" height="40">  </center></h4>
      </div>
      <div class="modal-body">
      <!--CUERPO-->
     <div class="panel-group" >
    <div class="panel panel-default">
    				<div class="panel-heading" role="tab" id="headingOne">
      					<h4 class="panel-title">
         					Base
      					</h4>
	    			</div>
    			<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
      		<div class="panel-body">
<table width="547" border="0">
  <tr>
    <td width="60">Seleccionar: </td>
    <td width="251">
    	<select class="form-control" name="buscarbase" id="buscarbase" style="width:200px" >
        	<?php
			if ($_SESSION['S_Privilegios'] == 'ADMINISTRADOR'){
			?>	
			<option value="EN01">EN01</option>
			<option value="JA01">JA01</option>
			<option value="OC01">OC01</option>
			<option value="PA01">PA01</option>
			<option value="TE01">TE01</option>
			<option value="TO01">TO01</option>
			<option value="USA0">USA01</option>
			<option value="ZI01">ZI01</option>
			<option value="SB1">SB1</option>
			<option value="EN01','USA01','OC01','PA01','ZI01','JA01','TE01','TO01','SB1">Todas</option>				
			<?php	
			}else{
			  $i=1;
			  $selected="";
			  $sql = "SELECT DISTINCT Base FROM Accesos WHERE Usuario_ID='".$_SESSION['S_UsuarioID']."'";
			  echo $sql;
			  $rs = odbc_exec( $conn, $sql );
			  if ( !$rs ) { 
			   exit( "Error en la consulta SQL" ); 
			  }     
			  while ( odbc_fetch_row($rs) ) { 
				$base = odbc_result($rs, 'Base');
			   echo "<option id='".$i."'>".$base."</option>";
			   $i++;
			  }//While 	
			}
	   		?>
        </select>
    </td>
  </tr>
</table>       
     		 </div>
    	</div>
  	</div>
  </div>
	<!--FIN-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>        
        <button type="submit" class="btn btn-primary" name="filtrar" id="filtrar">Aceptar</button>
      </div>
    </div>
  </div>
  </div>
</div>
</form> 
<!--Fin Modal-->

<!--NUEVO ALMACEN--> 
<form id="formulario5" action="Almacen.php" method="post" enctype="multipart/form-data" >      
<div class="modal fade guardar_almacen" id="guardar_almacen" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
<!--CUERPO-->  
	<div class="row"><!--
    	<div class="col-lg-2">Art&iacute;culo:</div>
    	<div class="col-lg-3"><input type="text" id="articulo_almacen" name="articulo_almacen" class="form-control" placeholder="IN-000-0000" required></div>-->
    	<div class="col-lg-2">Unidad:</div>
    	<div class="col-lg-3"><input type="text" id="unidad_almacen" name="unidad_almacen" class="form-control" required></div>
    	<div class="col-lg-2">Exist:</div>
    	<div class="col-lg-3"><input type="number" min="0" id="exixstencia_almacen" name="exixstencia_almacen" class="form-control" required></div>
    </div>
    <br>
	<div class="row">
    	<div class="col-lg-2">Base:</div>
    	<div class="col-lg-3">
        	<select class="form-control" id="base_almacen" name="base_almacen">
            	<option value="EN01">EN01</option>
                <option value="JA01">JA01</option>
                <option value="OC01">OC01</option>
                <option value="PA01">PA01</option>
                <option value="TE01">TE01</option>
                <option value="TO01">TO01</option>
                <option value="USA01">USA01</option>
                <option value="ZI01">ZI01</option>
                <option value="SB1">SB1</option>
            </select>
        </div>
    	<div class="col-lg-2">Precio:</div>
    	<div class="col-lg-3"><input type="number" min="0" step="any" id="precio_almacen" name="precio_almacen" class="form-control" required></div>
    </div>
    <br>
	<div class="row">
    	<div class="col-lg-2">Presupuesto:</div>
    	<div class="col-lg-4">
        	<select class="form-control" id="presupuesto" name="presupuesto">
            	<option value="Capex">Capex</option>
            	<option value="Caja Chica">Caja Chica</option>
            	<option value="Recuperado">Recuperado</option>
            </select>
        </div>
    </div>
    <br>
	<div class="row">
    	<div class="col-lg-2">Descripci&oacute;n:</div>
    	<div class="col-lg-10"><input type="text" id="desc_almacen" name="desc_almacen" class="form-control" required></div>
    </div>
<!--FIN--> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>        
        <button type="submit" class="btn btn-primary" name="guardaralmacen" id="guardaralmacen">Guardar</button>
      </div>
    </div>
  </div>
  </div>
</div>
</form>

<!--EDITAR ALMACEN--> 
<form id="formulario5" action="Almacen.php" method="post" enctype="multipart/form-data" >      
<div class="modal fade editar_almacen" id="editar_almacen" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
<!--CUERPO-->  
	<div class="row">
    	<div class="col-lg-3">Buscar art&iacute;culo:</div>
    	<div class="col-lg-7"><input type="text" id="buscar_desc" name="buscar_desc" class="form-control" required></div>
    	<div class="col-lg-1"><img src="images/LUPA.png" width="25" height="25" alt="" style="cursor:pointer"/></div>
    </div>
<br>
<div id="mostrar" style="display:none">
	<div class="row">
    	<div class="col-lg-2">Art&iacute;culo:</div>
    	<div class="col-lg-3"><input type="text" id="edit_art" name="edit_art" class="form-control" readonly></div>
    	<div class="col-lg-1">Unidad:</div>
    	<div class="col-lg-2"><input type="text" id="edit_unidad" name="edit_unidad" class="form-control" required></div>
    	<div class="col-lg-1">Exist:</div>
    	<div class="col-lg-2"><input type="number" min="0" id="edit_exist" name="edit_exist" class="form-control" required></div>
    </div>
    <br>
	<div class="row">
    	<div class="col-lg-2">Base:</div>
    	<div class="col-lg-3">
        	<select class="form-control" id="edit_base" name="edit_base">
            	<option value="EN01">EN01</option>
                <option value="JA01">JA01</option>
                <option value="OC01">OC01</option>
                <option value="PA01">PA01</option>
                <option value="TE01">TE01</option>
                <option value="TO01">TO01</option>
                <option value="USA01">USA01</option>
                <option value="ZI01">ZI01</option>
                <option value="SB1">SB1</option>
            </select>
        </div>
    	<div class="col-lg-1">Precio:</div>
    	<div class="col-lg-3"><input type="number" min="0" step="any" id="edit_precio" name="edit_precio" class="form-control" required></div>
    </div>
    <br>
	<div class="row">
    	<div class="col-lg-2">Descripci&oacute;n:</div>
    	<div class="col-lg-10"><input type="text" id="edit_desc" name="edit_desc" class="form-control" readonly></div>
    </div>
</div>    
<!--FIN--> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>        
        <button type="submit" class="btn btn-primary" name="editaralmacen" id="editaralmacen">Editar</button>
      </div>
    </div>
  </div>
  </div>
</div>
</form>

<!-- ELIMINAR FOLIO -->
<form id="formulario5" action="Almacen.php" method="post" enctype="multipart/form-data" >      
<div class="modal fade eliminar_folio" id="eliminar_folio" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body">
      <!--CUERPO-->
     <div class="panel-group" >
    <div class="panel panel-default">
    				<div class="panel-heading" role="tab" id="headingOne">
      					<h4 class="panel-title">
         					Folio
      					</h4>
	    			</div>
    			<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
      		<div class="panel-body">
            <table width="547" border="0">
              <tr>
                <td width="60">Escribe el folio: </td>
                <td width="251"><input type="text" id="eliminarFol" name="eliminarFol" class="form-control"></td>
              </tr>
            </table>       
     		</div>
    	</div>
  	</div>
  </div>
	<!--FIN-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>        
        <button type="submit" class="btn btn-primary" name="eliminarFolio" id="eliminarFolio">Aceptar</button>
      </div>
    </div>
  </div>
  </div>
</div>
</form> 
<!--Fin Modal-->

</body>
</html>