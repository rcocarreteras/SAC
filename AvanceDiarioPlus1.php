<?php 
require_once('Connections/sac2.php');
require_once('fechas.php');
header( 'Content-type: text/html; charset=iso-8859-1' );
date_default_timezone_set('America/Mexico_City');

//print_r($_POST);

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

$tramo = $_SESSION['S_Tramo'];
$lugar = $_SESSION['S_Plaza'];
$fecha_filtro = date("Y-m-d");


if ($lugar =="ENCARNACION','ENCARNACION','OCOTLAN','OCOTLAN','USA','USA','OCOTLAN','OCOTLAN','PANINDICUARO','PANINDICUARO','ZINAPECUARO','ZINAPECUARO','JALOSTOTITLAN','TEPATITLAN','TONALA','TONALA"){
	$lugar='Todas';
}

if ($tramo == "El Desperdicio - Lagos de Moreno','El Desperdicio - Santa Maria de En Medio','La Barca - Jiquilpan','La Barca - Jiquilpan','Leon - Aguascalientes','Leon - Aguascalientes','Los Fresnos - Zapotlanejo','Los Fresnos - Zapotlanejo','Los Fresnos - Zapotlanejo','Maravatio - Los Fresnos','Maravatio - Los Fresnos','Maravatio - Los Fresnos','Zapotlanejo - El Desperdicio','Zapotlanejo - El Desperdicio','Zapotlanejo - El Desperdicio','Zapotlanejo - Guadalajara"){
	$tramo='Todos';
}
//---------------------------------------------------------FILTRO-----------------------------------
if (isset($_REQUEST['filtrar'])) {
  $plaza = $_POST['plaza'];
  $tramo = $_POST['tramo'];
  $subtramo = $_POST['subtramo'];
  $fecha_filtro = $_POST['fecha_filtro'];
  $lugar = $plaza;
  
  	$sql = "SELECT * FROM AvanceDiario WHERE TRAMO = '".$tramo."' and FECHA = '".$fecha_filtro."' and SUBTRAMO = '".$subtramo."' and ESTATUS = 'EJECUTADO'";  
	//echo $sql;
	$sql3 = "SELECT DISTINCT(ID_SALIDA) FROM Salidas WHERE BASE in ('".$_SESSION['S_Base']."') and FECHA = '".$fecha_filtro."'";
  //echo $sql3;
}else{
	$fecha_filtro = date("Y-m-d");
	$sql = "SELECT * FROM AvanceDiario WHERE TRAMO in ('".$_SESSION['S_Tramo']."') and FECHA = '".$fecha_filtro."' and ESTATUS = 'EJECUTADO'";
	
	$sql3 = "SELECT DISTINCT(ID_SALIDA) FROM Salidas WHERE BASE in ('".$_SESSION['S_Base']."') and FECHA = '".date("Y-m-d")."'";
  //echo $sql3;
  
}

//---MAESTRO DETALLE-----------------------------------------------
//MAESTRO
$x=0;
$y=0;
$filas = array();
$filas2 = array();
$detalle="";

  //echo $sql."<br>";
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }   
 while ($row = odbc_fetch_array($rs)) {
  $avanceID = odbc_result($rs, 'AVANCE_ID'); 	   
  $filas[$x] = array_map('utf8_encode',$row);
  
  //DETALLE    
  $sql2 = "SELECT * FROM PuntoTrabajado Where AVANCE_ID = '".$avanceID."' order by tipo";
  //echo $sql2."<br>";
  $rs2 = odbc_exec( $conn, $sql2 );
  if ( !$rs2 ) { 
	exit( "Error en la consulta SQL" ); 
  }   
  while ($row2 = odbc_fetch_array($rs2)) {    
	$filas2[$y] = array_map('utf8_encode',$row2);    
	$y++;    
  }//While
  $x++;    
 }//While
 $maestro =  $filas;
 $detalle =  $filas2; 
 //echo json_encode($maestro); 
 //echo json_encode($detalle);
  if ($x==0){
  $m="[{\"AVANCE_ID\":\"0\",\"SEMANA_ID\":\"0\",\"ACTIVIDAD\":\" \",\"UNIDAD\":\" \",\"KM_INI\":\"\",\"KM_FIN\":\" \",\"CUERPO\":\" \",\"ZONA \":\"  \",\"CANTIDAD\":\"0\",\"OBSERVACIONES\":\" \",\"SOB_ID\":\" \"}]"; 
    $maestro = json_decode($m);
 }
 if ($y==0){    
    $d = "[{\"AVANCE_ID\":\"0\",\"NUMERO\":\"0\",\"NOMBRE\":\" \",\"HORAS\":\"0\",\"TIPO\":\" \"}]";
    $detalle = json_decode($d); 
  } 

/********************************ASIGNAMOS LA SALIDA AL AVANCE DIARIO***********************************/

if (isset($_REQUEST['enviaSalidas'])) { 
	$contador = $_POST["contador"];
	$salida = $_POST["salidas"]; 
	$folio = $_POST["folio"]; 
	$subtramo = $_POST["subtramo1"];
	$clave = $_POST["act"];

  //echo "Este es el contador ".$contador;

  for ($x = 1; $x <= $contador; $x++) {
    if(isset($_POST["check".$x])){
        $descripcion = $_POST["desc".$x.""]; 
        $cantidad = $_POST["cantidad".$x.""];
        $articulo = $_POST["articulo".$x.""];
        $importe = $_POST["importe".$x.""];
        //echo "Mande datos del check".$x;
		
        $sql = "UPDATE Salidas SET ID_AVANCE = '".$folio."' WHERE ID_SALIDA='".$salida."' and DESCRIPCION = '".$descripcion."'";
        //echo $sql;
        $rs = odbc_exec( $conn, $sql );
        if ( !$rs ) { 
            exit( "Error en la consulta Entradas" ); 
        }
		
		$sql = "INSERT INTO PuntoTrabajado VALUES ('".$folio."','".$articulo."','".$descripcion."','0','0','".$cantidad."','INSUMO','NO','','','".date("Y-m-d")."','".$clave."','".$subtramo."')";
      	//echo $sql;
      	$rs = odbc_exec( $conn, $sql );
      	if ( !$rs ) { 
        	exit( "Error en la consulta SQL" ); 
      	} 
    }		
  } //For
}//enviaSalidas

/***************************************AUTOCOMPLETAR EMPLEADOS*****************************************/
$nombre = array();
$x=0;

$sql = "select * from CatEmpleados WHERE PLAZA IN ('".$_SESSION['S_Plaza']."')";	
  //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }    
  while ( odbc_fetch_row($rs) ) { 
    $nombre[$x] = "\"" .odbc_result($rs, 'Empleado'). "\",";	
	$x++;    
}//While
$nombre[$x-1] = str_replace(",","",$nombre[$x-1]);
/*****************************************CONCEPTO****************************************/
$x=0;
$filas = array();
$sql = "SELECT * FROM CatConcepto";
 //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }   //OBTENEMOS TODA LA FILA 
 while ($row = odbc_fetch_array($rs)) {    
  $filas[$x] = array_map('utf8_encode',$row);    
   $x++;    
 }//While
$concepto =  $filas; 
//echo json_encode($concepto);

/**************************************SALIDAS******************************************************/
$folsal = array();
$x=0;

  $rs = odbc_exec( $conn, $sql3 );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }
  while ( odbc_fetch_row($rs) ) { 
    $folsal[$x] = "\"" .odbc_result($rs, 'ID_SALIDA'). "\",";	
	$x++;
}//While
if ($x > 0){
    $folsal[$x-1] = str_replace(",","",$folsal[$x-1]);
}

//----------------------------------------------------NUEVO AVANCE------------------------------------------------------
if (isset($_REQUEST['guardaravance'])) { 
  $folio = "0";
  $fechaact= $_POST['fechaactividad'];
  $clavecon="0";
  $actividad = $_POST['concepto'];   
  $unidad = "";
  $clavesob = "";
  $sobres="";
  $tramo = $_POST['tramo2'];
  $base = $_POST['base'];
  $subtramo = $_POST['subtramo2'];
  $km_ini3 = $_POST['km_ini'];
  $enca_ini3 = $_POST['enca_ini'];
  $km_fin3 = $_POST['km_fin'];
  $enca_fin3 = $_POST['enca_fin'];
  $cuerpo1 = $_POST['cuerpo'];
  $zona = $_POST['zona'];
  $observacion = $_POST['observacion'];
  $cantidad = $_POST['cantidad'];  
  $espesor = $_POST['espesor'];
  $longitud = $_POST['longitud'];
  $ancho = $_POST['ancho']; 
  $contador = $_POST['contador']; 
  $contadormaq = $_POST['contadormaq']; 
  $costo = "NO"; 
 
  $km1 = $km_ini3."+".$enca_ini3;  
  $km2 = $km_fin3."+".$enca_fin3;

   //VALIDACION DE VARIABLES
  if ($cantidad == ""){
    $cantidad=0;  
  }
  if ($longitud == ""){
    $longitud=0;  
  }
  if ($ancho == ""){
    $ancho=0;  
  }
  if ($espesor == ""){
    $espesor=0;  
  }
  
 $sql = "SELECT * FROM CatConcepto WHERE DesCpt = '". $actividad ."'";
  //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }    
  while ( odbc_fetch_row($rs) ) { 
    $clavecon = odbc_result($rs, 'CvCpt');
    $unidad = odbc_result($rs, 'Unid');
  }
   
   $sql = "SELECT * FROM Accesos WHERE Subtramo = '". $subtramo ."' and Tramo = '".$tramo."'";
  //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }    
  while ( odbc_fetch_row($rs) ) { 
    $clavesob = odbc_result($rs, 'INICIALES');
    $sobres = odbc_result($rs, 'SOBRESTANTE');
    $plaza = odbc_result($rs, 'Plaza');
  }  
   
      $sql = "INSERT INTO AvanceDiario VALUES ('".$folio."','".$numsemactual."','".$semanactual."','".$clavecon."','".$actividad."','".$unidad."','".$km1."','".$km2."','".$cuerpo1."','".$zona."','".$longitud."','".$ancho."','".$espesor."','".$cantidad."','".$clavesob."','".$sobres."','".$tramo."','".$observacion."','EJECUTADO','".$fechaact."','".$_SESSION['S_Usuario']."','','".$fecha."','".$plaza."','".$subtramo."')";
     //echo $sql;
      $rs = odbc_exec( $conn, $sql );
      if ( !$rs ) { 
        $notificacion = "errorEntrar";
      }       
      
//OBTENEMOS EL REGISTRO QUE ACABAMOS DE GUARDAR
$sql = "SELECT TOP 1 * FROM AvanceDiario ORDER BY AVANCE_ID DESC";
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }    
  while ( odbc_fetch_row($rs) ) { 
    $avance_id = odbc_result($rs, 'AVANCE_ID'); 
  } 
  
  //MANO DE OBRA  
  for ($x = 1; $x <= 30; $x++) {      
      if ($_POST["nombre".$x.""] <> ""){    
        $nombre = $_POST["nombre".$x.""]; 
        $hora = $_POST["horas".$x.""];
        $horaex = $_POST["horasex".$x.""];
        if ($hora == ""){
            $hora=0;  
        }
        if ($horaex == ""){
            $horaex=0;  
        }        
     
        $sql = "SELECT * FROM CatEmpleados WHERE Empleado = '".$nombre."'";
        $rs = odbc_exec( $conn, $sql );
        if ( !$rs ) { 
          exit( "Error en la consulta CatEmpleados" ); 
        }    
        while ( odbc_fetch_row($rs) ) { 
          $emp = odbc_result($rs, 'NoEmp'); 
        }    
      
        $sql = "INSERT INTO PuntoTrabajado VALUES ('".$avance_id."','".$emp."','".$nombre."','".$hora."','".$horaex."','0','MANO DE OBRA','SI','','','".$fechaact."','".$clavecon."','".$subtramo."')";
        //echo $sql;
        $rs = odbc_exec( $conn, $sql );
        if ( !$rs ) { 
          //exit( "Error en la consulta PuntoTrabajado" ); 
        }  
        $emp = "";
        $nombre="";
        $hora=0;
        $horaex=0;    
     }   
 }
 //SALIDA 
  for ($x = 1; $x <= $contador; $x++) {
	  if ($_POST["usado".$x.""] <> "0"){
         $articulo = utf8_decode($_POST["art".$x.""]);
         $usado = $_POST["usado".$x.""];
         $id_salida = $_POST["folio".$x.""];
         $concepto = $_POST["desc".$x.""];
         $unidad = $_POST["unidad".$x.""];
         $precio = $_POST["precio".$x.""];
         $fecha = $_POST["fecha".$x.""];
		 
		 $total = $precio * $usado;
		 
		 $sql = "INSERT INTO PuntoTrabajado VALUES ('".$avance_id."','".$articulo."','".$concepto."','0','0','".$usado."','INSUMO','SI','','','".$fechaact."','".$clavecon."','".$subtramo."')";
      	//echo $sql;
      	$rs = odbc_exec( $conn, $sql );
      	if ( !$rs ) { 
        	exit( "Error en la consulta SQL" ); 
      	} 
		 
		 $cantidad = $usado * -1;
		 $total = $precio * $cantidad;

         $sql = "INSERT INTO Salidas VALUES ('".$id_salida."','".$articulo."','".$concepto."','".$unidad."','".$precio."','".$cantidad."','".$total."','".date("Y").date("m")."','CERRADO','U','".$fecha."','".$base."','".$avance_id."','".$_SESSION['S_Usuario']."')";
		//echo $sql;
		$rs = odbc_exec( $conn, $sql );
		if ( !$rs ) { 
			exit( "Error en la consulta Entradas" );
		}
		
         $articulo = "";
         $usado = "0";
         $id_salida = "";
         $concepto = "";
         $unidad = "";
         $precio = "";
       } 
  }
  //SALIDA MAQUINARIA
  for ($x = 1; $x <= $contadormaq; $x++) {
	  if ($_POST["hruso".$x.""] <> "0"){
         $hruso = $_POST["hruso".$x.""];
         $hrini = $_POST["hrini".$x.""];
         $hrfin = $_POST["hrfin".$x.""]; 
         $idsalida = $_POST["foliomaq".$x.""];
         //$fecha = $_POST["fechamaq".$x.""];
         $noeco = $_POST["noeco".$x.""];
         $maquinaria = $_POST["descripcion".$x.""];
		 		 			 
	 	$sql = "INSERT INTO PuntoTrabajado VALUES ('".$avance_id."','".$noeco."','".$maquinaria."','".$hruso."','0','0','MAQUINARIA','SI','','','".$fechaact."','".$clavecon."','".$subtramo."')";
      	//echo $sql;
      	$rs = odbc_exec( $conn, $sql );
      	if ( !$rs ) { 
        	exit( "Error en la consulta SQL" ); 
      	}

		 $hruso = $hruso * -1;
		 
		 $sql = "INSERT INTO SalidasMaq VALUES ('".$idsalida."','".$noeco."','".$maquinaria."','".date("Y").date("m")."','CERRADO','C','".$fechaact."','".$base."','".$hrini."','".$hrfin."','".$hruso."','".$avance_id."','".$_SESSION['S_Usuario']."')";
    	 //echo $sql;
  		 $rs = odbc_exec( $conn, $sql );
   		 if ( !$rs ) { 
  	    	 exit( "Error en la consulta SalidasMaq" ); 
  		 } 
		 

		  
         $hruso = "0";
         $idsalida = "";
         $noeco = "";
       } 
  }
  
  
header("Location: AvanceDiarioPlus.php");
}// FIN NUEVO AVANCE

//----------------------------------------------------GUARDAR MULTIPUNTO----------------------------------
if (isset($_REQUEST['multipunto'])){	
    $fecha = $_POST['fechamulti1'];
	$tramo = $_POST['tramomulti1'];
	$subtramo = $_POST['subtramomulti1'];
	$actividad = $_POST['actividadmulti1'];
	$unidad = $_POST['unidadmulti1'];
	$folio = $_POST['foliomulti1'];
	$clavecon = $_POST['actidmulti1'];
  	$km_ini = $_POST['km_inimulti'];
	$enca_ini = $_POST['enca_inimulti'];
  	$km_fin = $_POST['km_finmulti'];
  	$enca_fin = $_POST['enca_finmulti'];
  	$cuerpo = $_POST['cuerpomulti'];
  	$zona = $_POST['zonamulti'];
  	$longitud = $_POST['longitudmulti'];
  	$ancho = $_POST['anchomulti'];
  	$espesor = $_POST['espesormulti'];
  	$cantidad = $_POST['cantidadmulti'];
  	$clavesob = $_POST['sb_id'];
  	$sobres = $_POST['sobrestante'];
  	$plaza = $_POST['plazamulti'];
	
	//VALIDACION DE VARIABLES
  if ($cantidad == ""){
	$cantidad=0;  
  }
  if ($longitud == ""){
	$longitud=0;  
  }
  if ($ancho == ""){
	$ancho=0;  
  }
  if ($espesor == ""){
	$espesor=0;  
  }
  
  $km1 = $km_ini."+".$enca_ini;  
  $km2 = $km_fin."+".$enca_fin;   
  		   
  $sql = "INSERT INTO AvanceDiario VALUES ('0','".$numsemactual."','".$semanactual."','".$clavecon."','".$actividad."','".$unidad."','".$km1."','".$km2."','".$cuerpo."','".$zona."','".$longitud."','".$ancho."','".$espesor."','".$cantidad."','".$clavesob."','".$sobres."','".$tramo."','','EJECUTADO','".$fecha."','".$_SESSION['S_Usuario']."','','".date('Y-m-d')."','".$plaza."','".$subtramo."')";
     //echo $sql;
      $rs = odbc_exec( $conn, $sql );
      if ( !$rs ) { 
        $notificacion = "errorEntrar";
      }
 
}//FIN MULTIPUNTO
/************************************BORRAR AVANCE***************************************/
if (isset($_REQUEST['borrar_avance'])) { 
 $folio = $_POST["borrar1"];
 
	 $sql3 = "DELETE FROM AvanceDiario WHERE AVANCE_ID='".$folio."'";
    	//echo $sql;
    	$rs = odbc_exec( $conn, $sql3 );
   		if ( !$rs ) {
	     exit( "Error en la consulta AvanceDiario" );            
   		} 
		
	$sql3 = "DELETE FROM PuntoTrabajado WHERE AVANCE_ID='".$folio."'";
    	//echo $sql;
    	$rs = odbc_exec( $conn, $sql3 );
   		if ( !$rs ) {
	     exit( "Error en la consulta PuntoTrabajado" );            
   		}
		
		$sql3 = "DELETE FROM Salidas WHERE AVANCE_ID='".$folio."'";
    	//echo $sql;
    	$rs = odbc_exec( $conn, $sql3 );
   		if ( !$rs ) {
	     exit( "Error en la consulta PuntoTrabajado" );            
   		} 
		
		$sql3 = "DELETE FROM SalidasMaq WHERE AVANCE_ID='".$folio."'";
    	//echo $sql;
    	$rs = odbc_exec( $conn, $sql3 );
   		if ( !$rs ) {
	     exit( "Error en la consulta PuntoTrabajado" );            
   		}
		
		//header("Location: AvanceDiarioPlus.php");
} 	
 ?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Avance Diario</title>
	<link href="css/bootstrap.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />
    <link rel="stylesheet" href="CssLocal/menuSac.css"><!--Necesario para Menu 1--> 
    <link rel="stylesheet" href="CssLocal/Menu1.css"><!--Necesario para Menu 1-->    
	<script type="text/javascript" src="scripts/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="scripts/demos.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxtabs.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcheckbox.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxscrollbar.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxmenu.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxgrid.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxgrid.selection.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxgrid.columnsresize.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxgrid.pager.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxlistbox.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdropdownlist.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxinput.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdata.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxchart.core.js"></script>
    <script type="text/javascript" src="formula.js"></script>
  
	<!-- PNotify -->
	<link href="src/pnotify.core.css" rel="stylesheet" type="text/css" />
	<link href="src/pnotify.brighttheme.css" rel="stylesheet" type="text/css" />
	<link href="src/pnotify.buttons.css" rel="stylesheet" type="text/css" />
	<link href="src/pnotify.history.css" rel="stylesheet" type="text/css" />
	<link href="src/pnotify.picon.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="src/pnotify.confirm.js"></script>
	<script type="text/javascript" src="src/pnotify.core.js"></script>
	<script type="text/javascript" src="src/pnotify.buttons.js"></script>
	<script type="text/javascript" src="src/pnotify.nonblock.js"></script>
	<script type="text/javascript" src="src/pnotify.desktop.js"></script>
	<script type="text/javascript" src="src/pnotify.history.js"></script>
	<script type="text/javascript" src="src/pnotify.callbacks.js"></script>
	<script type="text/javascript" src="src/pnotify.reference.js"></script>
	    <!--CSS AJUSTE PANTALLA-->
	<style>
	#encabezado{
		/*padding-left:20px;
		padding-right:100px;*/
		background:#C1C1C1;
	}
	#divAD{
		height:5px;
	}
	</style>
	<script type="text/javascript">
		$(document).ready(function () {
			var checked = false;
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
			
			//------------------AUTOCOMPLETAR-------------------
			 var folsal = new Array(<?php  
	         foreach ($folsal as &$valor) {
               echo $valor;
             }		
	         ?>);				
			 var nombre = new Array(<?php  
	        foreach ($nombre as &$valor) {
              echo $valor;
            }		
	        ?>);
			
			//MANO DE OBRA
			for (var i = 1; i <= 30; i++) {
				$("#nombre" + i).jqxInput({placeHolder: "Elige un empleado", minLength: 1,  source: nombre});
			}
			
			//CONCEPTOS
			var data =  <?php echo json_encode($concepto); ?>;						
					var source =
				{
					datatype: "json",
					datafields: [
						{ name: 'DesCpt' },	
						{ name: 'Unid' }                    				
					],
					localdata: data
				};
              	var dataAdapter = new $.jqx.dataAdapter(source);
                // Create a jqxInput
                $("#concepto").jqxInput({ source: dataAdapter, placeHolder: "Escribe una actividad", displayMember: "DesCpt", valueMember: "Unid"});			
                $("#concepto").on('select', function (event) {					
                    if (event.args) {
                        var item = event.args.item;
                        if (item) {
							//item.label (Descriciondel concepto) 
							$("#unidad").val(item.value)
                        }
                    }
                });
				
			/*********************************ENCADENAMIENTOS***************************************/
			
			$("#enca_ini").change(function(){				    
				var longitud = $("#enca_ini").val().length;
				var encadenamiento = $("#enca_ini").val();				
							
				switch(longitud) {					
					case 1:					                    				
					$("#enca_ini").val( "00"+ encadenamiento);
					break;
					case 2:
					$("#enca_ini").val( "0"+ encadenamiento);
					break;	
					case 3:
					$("#enca_ini").val(encadenamiento);
					break;				
					default:
					$("#enca_ini").val("000");
					break;
				}				
			});	
			$("#enca_fin").change(function(){				    
				var longitud = $("#enca_fin").val().length;
				var encadenamiento = $("#enca_fin").val();				
							
				switch(longitud) {					
					case 1:					                    				
					$("#enca_fin").val( "00"+ encadenamiento);
					break;
					case 2:
					$("#enca_fin").val( "0"+ encadenamiento);
					break;	
					case 3:
					$("#enca_fin").val(encadenamiento);
					break;				
					default:
					$("#enca_fin").val("000");
					break;
				}				
			});	
			
			//CREAMOS LOS TABS
			$('#jqxTabs').jqxTabs({ width: '85%', height: '90%', position: 'top', selectionTracker: true});
                     
           // MAESTRO
			var data =  <?php echo json_encode($maestro); ?>;	
		    var source =
            {
                datafields: [
				    { name: 'AVANCE_ID', type: 'number' },
					{ name: 'FECHA', type: 'string' },
                    { name: 'ACTIVIDAD_ID', type: 'number' },
                    { name: 'ACTIVIDAD', type: 'string' },
                    { name: 'UNIDAD', type: 'string' },                    
                    { name: 'KM_INI', type: 'string' },
                    { name: 'KM_FIN', type: 'string' },
					{ name: 'CUERPO', type: 'string' },  
					{ name: 'ZONA', type: 'string' },
					{ name: 'CANTIDAD', type: 'number' },					
					{ name: 'OBSERVACIONES', type: 'string' },			
					{ name: 'SOBRESTANTE', type: 'string' },
					{ name: 'TRAMO', type: 'number' },					
					{ name: 'PLAZA', type: 'string' },			
					{ name: 'SUBTRAMO', type: 'string' },
					{ name: 'SOB_ID', type: 'string' }
                ],
                localdata: data
            };
            var dataAdapter = new $.jqx.dataAdapter(source);	

            $("#maestro").jqxGrid(
            {
                width: '90%',
                height: '35%',
                source: dataAdapter,                
                keyboardnavigation: false,
                columns: [
                    { text: 'Folio', datafield: 'AVANCE_ID', width: 50 },
                    { text: 'Clave', datafield: 'ACTIVIDAD_ID', width: 50 },
                    { text: 'Actividad', datafield: 'ACTIVIDAD', width: 280 },
                    { text: 'Unidad', datafield: 'UNIDAD', width: 50 },
					{ text: 'Km Ini', datafield: 'KM_INI', width: 80 },
					{ text: 'Km Fin', datafield: 'KM_FIN', width: 80 },
					{ text: 'Cpo', datafield: 'CUERPO', width: 40 },
					{ text: 'Zona', datafield: 'ZONA', width: 80 },
					{ text: 'Cant', datafield: 'CANTIDAD', width: 70 },
					{ text: 'Observacion', datafield: 'OBSERVACIONES', width: 280 },		 
				  	{ text: 'Multipunto', dataField: 'Multipunto', width: 75, columntype: 'button', cellsrenderer: function () {
            				return "Agregar";
        		 	}},                  					
                ]
            });

            // DETALLE         	
            var dataFields = [
                    { name: 'AVANCE_ID', type: 'number' },
                    { name: 'NUMERO', type: 'number' },
                    { name: 'NOMBRE', type: 'string' },                    
                    { name: 'HORAS', type: 'number' },
					{ name: 'HORA_EXTRA', type: 'number' },
					{ name: 'CANTIDAD', type: 'number' },
					{ name: 'TIPO', type: 'string' }    
                    ];

            var source =
            {
                datafields: dataFields,
                localdata: <?php echo json_encode($detalle); ?>
				
            };

            var dataAdapter = new $.jqx.dataAdapter(source);
            dataAdapter.dataBind();
			
			$("#maestro").bind('cellclick', function (event) {						
				var column = args.datafield;				
               	var row = args.rowindex;
               	var value = args.value;
				row["name"] =  false;														
                var rowindex = event.args.rowindex;
				var folio = $("#maestro").jqxGrid('getcellvalue', rowindex, 'AVANCE_ID');
				var fecha = $("#maestro").jqxGrid('getcellvalue', rowindex, 'FECHA');
				var act = $("#maestro").jqxGrid('getcellvalue', rowindex, 'ACTIVIDAD');
				var unidad = $("#maestro").jqxGrid('getcellvalue', rowindex, 'UNIDAD');
				var clave = $("#maestro").jqxGrid('getcellvalue', rowindex, 'ACTIVIDAD_ID');
				var sb = $("#maestro").jqxGrid('getcellvalue', rowindex, 'SOB_ID');
				var sobre = $("#maestro").jqxGrid('getcellvalue', rowindex, 'SOBRESTANTE');
				var tramo = $("#maestro").jqxGrid('getcellvalue', rowindex, 'TRAMO');
				var plaza = $("#maestro").jqxGrid('getcellvalue', rowindex, 'PLAZA');
				var subtr = $("#maestro").jqxGrid('getcellvalue', rowindex, 'SUBTRAMO');
            //----------------------------------------------MULTIPUNTO---------------------------------
				if (event.args.datafield == 'Multipunto') {					
					$("#multipunto").modal('show');	
					$("#foliomulti").text(folio);
					$("#fechamulti").text(fecha);
					$("#actividadmulti").text(act);
					$("#unidadmulti").text(unidad);
					$("#foliomulti1").val(folio);
					$("#fechamulti1").val(fecha);
					$("#actividadmulti1").val(act);
					$("#unidadmulti1").val(unidad);
					$("#actidmulti1").val(clave);
					$("#sb_id").val(sb);
					$("#sobrestante").val(sobre);
					$("#plazamulti").val(plaza);
					$("#tramomulti").text(tramo);
					$("#subtramomulti").text(subtr);
					$("#tramomulti1").val(tramo);
					$("#subtramomulti1").val(subtr);
					
					$.post("consulta.php", { concepto: act }, function(data){
					  $("#formulamulti").val(data);	
					  $("#multipunto").ready( consultaUnidad(data) );
					});
										
					  $.post("consultaAvanceDiario.php", { subtramoAd: subtr }, function(data){
						  //alert(data);
						  $("#km_inimulti").html(data);
						  $("#km_finmulti").html(data);
					  });
				}
			});

            $("#maestro").on('rowselect', function (event) {
                var maestro = event.args.row.AVANCE_ID;
                var folio = event.args.row.AVANCE_ID;
                var borrar = event.args.row.AVANCE_ID;
                var clave = event.args.row.ACTIVIDAD_ID;
                var subt = event.args.row.SUBTRAMO;
                var records = new Array();
                var length = dataAdapter.records.length;
                for (var i = 0; i < length; i++) {
                    var record = dataAdapter.records[i];
                    if (record.AVANCE_ID == maestro) {
                        records[records.length] = record;
                    }
                }
                var dataSource = {
                    datafields: dataFields,
                    localdata: records
                }
                var adapter = new $.jqx.dataAdapter(dataSource);        
                // update data source.
                $("#detalle").jqxGrid({ source: adapter });
				
			
				//Asigna Folios de Avance Diario a los campos
				$("#borrar").text(borrar);
				$("#borrar1").val(borrar);
				if($("#borrar1").val()==""){
					document.getElementById("vacio").style.display = 'block';
				}else{
					document.getElementById("lleno").style.display = 'block';
				}
            });

            $("#detalle").jqxGrid(
            {
                width: '71%',
                height: '40%',
                keyboardnavigation: false,
                columns: [
                    { text: '#', datafield: 'NUMERO', width: 100 },
                    { text: 'Nombre', datafield: 'NOMBRE',  width: 350 },
                    { text: 'Horas', datafield: 'HORAS',  width: 50 },
					{ text: 'Horas Ex.', datafield: 'HORA_EXTRA',  width: 80 },
					{ text: 'Cantidad.', datafield: 'CANTIDAD',  width: 80 },					
				    { text: 'Tipo', datafield: 'TIPO',  width: 150 }                    
                ]
            });		
            $("#maestro").jqxGrid('selectrow', 0);
     		
     		//---FILTRO DE AVANCE DIARIO--------------------------------
			$("#plaza").click(function(){	
				$("#base").val();	
				$("#subtramo").empty()	
				var id =  <?php echo $_SESSION['S_UsuarioID']; ?>;		
				$("#plaza option:selected").each(function () {
			     elegido=$(this).val();
			     $.post("consultaAvanceDiario.php", { plaza: elegido, id: id }, function(data){
				 	 //alert(data);	   								
					$("#tramo").html(data);			    
			   });			
              });				
			});
			
			$("#tramo").click(function(){
				var plaza = $("#plaza").val();	
				var id =  <?php echo $_SESSION['S_UsuarioID']; ?>;				    			
				$("#tramo option:selected").each(function () {
			     elegido=$(this).val();
			     $.post("consultaAvanceDiario.php", { tramo: elegido, plaza2: plaza, id2: id }, function(data){	
					 //alert(data);					   								
					 $("#subtramo").html(data);
			   });			
              });				
			});
			
			$("#subtramo").click(function(){	
				var tramo = $("#tramo").val();			    			
				$("#subtramo option:selected").each(function () {
			     elegido=$(this).val();				
			     $.post("consultaAvanceDiario.php", { subtramo: elegido, tramo2: tramo }, function(data){	
					 //alert(data);					   								
					 $("#semana").html(data);
			   });			
              });				
			});
			
            $("#buscarfecha").click(function(){
				//alert();
                var fecha = $("#fechaactividad").val();
                var tramo = $("#tramo2").val();
                var subtramo = $("#subtramo2").val();
				//alert(fecha+"-"+subtramo+"-"+tramo);
				
				$.post("consultaAvanceDiario.php", { BuscarBase: '', Tramo: tramo, Subtramo: subtramo }, function(data){
					var base = data;
					$("#base").val(base);
					//alert(data);
					$.post("consultaAvanceDiario.php", { BuscarSalidaAvance: '', Fecha: fecha, Base: base }, function(data2){
						var token = data2.split();
				      	var variable = token[0].split('*');
						
						$("#contador").val(variable[1]);
						
						if (variable[1] == '0'){
							$("#mostrar_salida2").html("<strong>No hay salidas de almacen</strong><hr size='3'>");
						}else{
							$("#mostrar_salida2").html(variable[0]);
						}
						//alert(data);
                 	});//Fin POST
					
					$.post("consultaAvanceDiario.php", { BuscarSalida: '', Fecha: fecha, Base: base }, function(data3){
						//alert(data);
						var token = data3.split();
				      	var variable = token[0].split('*');
						
						$("#contadormaq").val(variable[1]);
						
						if (variable[1] == '0'){
							$("#mostrar_salida").html("<strong>No hay salidas de maquinaria</strong>");
						}else{
							$("#mostrar_salida").html(variable[0]);
						}
						//alert(data);
                 	});//Fin POST
					
                  });//Fin POST
            });
			
			//--------------------------------MODAL PARA AGREGAR NUEVO REGISTRO----------------------------
			$("#tramo2").click(function(){
				var plaza = $("#plaza").val();	
				var id =  <?php echo $_SESSION['S_UsuarioID']; ?>;				    			
				$("#tramo2 option:selected").each(function () {
			     elegido=$(this).val();
				 //alert(checked);
				 if (checked){
					 $.post("consultaAvanceDiario.php", { tramoApoyo: elegido }, function(data){	
						 //alert(data);
						 $("#subtramo2").html(data);
					 });
				 }else{
					 $.post("consultaAvanceDiario.php", { tramoAd: elegido }, function(data){	
						 //alert(data);
						 $("#subtramo2").html(data);
					 });	
				 }
              });				
			});
			
			$("#subtramo2").click(function(){		
				$("#km_ini").val();	   								
				$("#km_fin").val();
				$("#subtramo2 option:selected").each(function () {
			     elegido=$(this).val();
			     $.post("consultaAvanceDiario.php", { subtramoAd: elegido }, function(data){	
					 //alert(data);					   								
					 $("#km_ini").html(data);
					 $("#km_fin").html(data);
			   	});			
              });
			});
			
			$("#apoyo").jqxCheckBox({ width: 10, height: 10, checked: false});

            $("#apoyo").on('change', function (event) {           
               checked = event.args.checked;
                //alert(checked);                
                if (checked) {
					$("#tramo2").empty();
					$("#subtramo2").empty();
					$("#tramo2").append("<option value='El Desperdicio - Lagos de Moreno'>El Desperdicio - Lagos de Moreno</option>");
					$("#tramo2").append("<option value='El Desperdicio - Santa Maria de En Medio'>El Desperdicio - Santa Maria de En Medio</option>");
					$("#tramo2").append("<option value='Leon - Aguascalientes'>Leon - Aguascalientes</option>");
					$("#tramo2").append("<option value='Los Fresnos - Zapotlanejo'>Los Fresnos - Zapotlanejo</option>");						
					$("#tramo2").append("<option value='Maravatio - Los Fresnos'>Maravatio - Los Fresnos</option>");
					$("#tramo2").append("<option value='Zapotlanejo - El Desperdicio'>Zapotlanejo - El Desperdicio</option>");
					$("#tramo2").append("<option value='Zapotlanejo - Guadalajara'>Zapotlanejo - Guadalajara</option>");
                    //$("#amonestacion").find('span')[1].innerHTML = 'Checked'; //Cambiar el texto del checkbox
                }
                else {
					$("#tramo2").empty();
					$("#subtramo2").empty();
					$.post("consultaAvanceDiario.php", { TramoRegular: '' }, function(data){	
						 //alert(data);					   								
						 $("#tramo2").html(data);
			   		});	
                }  
                             
            });
			
			$("#cuerpo").click(function () {
   		      $("#cuerpo option:selected").each(function () {
			     elegido=$(this).val();
			     $.post("catalogos.php", { cuerpo: elegido }, function(data){						
			     $("#zona").html(data);			
			   });			
              });
            });
			
			$("#cuerpomulti").click(function () {
   		      $("#cuerpomulti option:selected").each(function () {
			     elegido=$(this).val();
			     $.post("catalogos.php", { cuerpo: elegido }, function(data){						
			     $("#zonamulti").html(data);			
			   });			
              });
            });
			
			//----------------------------------------EXPORTAR REPORTE---------------------------------
			$("#plaza_exportar").click(function(){	
				$("#base").val()	
				$("#subtramo_exportar").empty()
				$("#usuario_exportar").empty()	
				var id =  <?php echo $_SESSION['S_UsuarioID']; ?>;		
				$("#plaza_exportar option:selected").each(function () {
			     elegido=$(this).val();
			     $.post("consultaAvanceDiario.php", { plaza: elegido, id: id }, function(data){
				 	 //alert(data);	   								
					$("#tramo_exportar").html(data);			    
			   });			
              });				
			});
			
			$("#tramo_exportar").click(function(){
				var plaza = $("#plaza_exportar").val();	
				var id =  <?php echo $_SESSION['S_UsuarioID']; ?>;				    			
				$("#tramo_exportar option:selected").each(function () {
			     elegido=$(this).val();
			     $.post("consultaAvanceDiario.php", { tramo: elegido, plaza2: plaza, id2: id }, function(data){	
					 //alert(data);					   								
					 $("#subtramo_exportar").html(data);
			   });
              });
			});
			
			$("#subtramo_exportar").click(function () {
				var tramoS = $("#tramo_exportar").val();
   		      $("#subtramo_exportar option:selected").each(function () {
			     elegido=$(this).val();
			     $.post("consulta.php", { subtSobres: elegido, tramoSub: tramoS }, function(data){		
					//alert(data);
			     	$("#usuario_exportar").html(data);
			   });
              });
            });
			
			
			/***********************************************ADJUNTAR FOTOS*********************************************/
			
			$("#tramo_adjuntar").click(function(){
				var periodo1 = $("#periodo_adjuntar").val();			
			     elegido=$(this).val();
			     $.post("consultaAvanceDiario.php", { BuscarActividad: '', tramo: elegido, periodo: periodo1 }, function(data){	
					 //alert(data);				
					 var token = data.split();
				     var variable = token[0].split('*');	   								
					 $("#actividad_adjuntar").html(data);
					 
					 if (variable[0] === ""){
						 document.getElementById("mensaje_foto").style.display = "block";
						 document.getElementById("mostrar").style.display = "none";
						 $("#Adjuntar").attr("disabled", true);
					 }else{
						 document.getElementById("mensaje_foto").style.display = "none";
						 document.getElementById("mostrar").style.display = "block";
						 $("#actividad_adjuntar").click();
					 }
			   });
			});
			
			$("#actividad_adjuntar").click(function(){	
				//alert();	
			     elegido=$(this).val();
			     $.post("consultaAvanceDiario.php", { BuscarAbrev: '', actividad: elegido }, function(data){	
					 //alert(data);					   								
					 $("#abrev").val(data);
			   });
			});
			
			$("#tramoSCT").click(function(){	
				//alert();	
			     elegido=$(this).val();
			     $.post("consultaAvanceDiario.php", { BuscarEnca: '', tramoSCT: elegido }, function(data){	
					 //alert(data);
					 var token = data.split();
				     var variable = token[0].split('*');
					 $("#encaSCT").val(variable[0]);
					 $("#autoSCT").val(variable[1]);
					 $("#cadeSCT").val(variable[2]);
			   });
			});
			
			
			$("#calcula_fotos").jqxCheckBox({ width: 10, height: 10, checked: false});
			
			$("#calcula_fotos").on('change', function (event) {           
                var checked = event.args.checked;                          
                if (checked) {
					tramoAdjuntar = $("#tramo_adjuntar").val();
					 actividadAdjuntar = $("#actividad_adjuntar").val();
					 $.post("consultaAvanceDiario.php", { ContarActividad: "", tramoAdjuntar: tramoAdjuntar, actividadAdjuntar: actividadAdjuntar }, function(data){
						//alert(data);
						
						if (data >= 1 && data <= 31){
							$("#numero_fotos").val("3");
						}else{
							if (data >= 32 && data <= 62){
								$("#numero_fotos").val("6");
							}
						}
				   });
				   $("#Adjuntar").attr("disabled", false);
                }else {
					$("#Adjuntar").attr("disabled", true);
					$("#numero_fotos").empty();
					$("#abrev").empty();
                }
            }); 
			
			$("#Adjuntar").click(function () {
				var tramo = $("#tramo_adjuntar").val();
				var actividad = $("#actividad_adjuntar").val();
				var cantidad = $("#numero_fotos").val();
				var abrev = $("#abrev").val();
				var periodo = $("#periodo_adjuntar").val();
				
				window.open("AdjuntarAvance.php?tramo=" + tramo + "&actividad=" + actividad + "&cantidad=" + cantidad + "&abrev=" + abrev + "&periodo=" + periodo );				
				location.reload();
            });

			$("#generar").click(function () {
				$(this).removeClass('Generar');
				$(this).addClass('Procesando...');
				$(this).text($(this).attr('on-clic'));
            });


			/***********************CALCULO DE HORAS Y HORAS EXTRAS (MANO DE OBRA AVANCE)*****************/
			
			$("#horas1").keyup(function (){	
			    switch ($('#formula').val()){			
			      case '5':
			      var total = parseInt($("#horas1").val());	
				  var total2 = parseInt($("#horasex1").val());

	              $("#cantidad").val(total + (total2 * 2));
	              break;
	            }	
			});
			$("#horas2").keyup(function (){		
			    switch ($('#formula').val()){			
			      case '5':
			      var total = parseInt($("#horas1").val()) + parseInt($("#horas2").val()) ;	
	               var total2 = parseInt($("#horasex1").val()) + parseInt($("#horasex2").val());
				  
	              $("#cantidad").val(total + (total2 * 2));       
	              break;
	            }	
			});
			$("#horas3").keyup(function (){		
			    switch ($('#formula').val()){			
			      case '5':
			      var total = parseInt($("#horas1").val()) + parseInt($("#horas2").val()) + parseInt($("#horas3").val());	
	               var total2 = parseInt($("#horasex1").val()) + parseInt($("#horasex2").val()) + parseInt($("#horasex3").val());
				  
	              $("#cantidad").val(total + (total2 * 2));        
	              break;
	            }	
			});
			$("#horas4").keyup(function (){		
			    switch ($('#formula').val()){			
			      case '5':
			      var total = parseInt($("#horas1").val()) + parseInt($("#horas2").val()) + parseInt($("#horas3").val()) + parseInt($("#horas4").val());	
	               var total2 = parseInt($("#horasex1").val()) + parseInt($("#horasex2").val()) + parseInt($("#horasex3").val()) + parseInt($("#horasex4").val());
				  
	              $("#cantidad").val(total + (total2 * 2));        
	              break;
	            }	
			});
			$("#horas5").keyup(function (){		
			    switch ($('#formula').val()){			
			      case '5':
			      var total = parseInt($("#horas1").val()) + parseInt($("#horas2").val()) + parseInt($("#horas3").val()) + parseInt($("#horas4").val()) + parseInt($("#horas5").val());	
	               var total2 = parseInt($("#horasex1").val()) + parseInt($("#horasex2").val()) + parseInt($("#horasex3").val()) + parseInt($("#horasex4").val()) + parseInt($("#horasex5").val());
				  
	              $("#cantidad").val(total + (total2 * 2));       
	              break;
	            }	
			});
			$("#horas6").keyup(function (){		
			    switch ($('#formula').val()){			
			      case '5':
			      var total = parseInt($("#horas1").val()) + parseInt($("#horas2").val()) + parseInt($("#horas3").val()) + parseInt($("#horas4").val()) + parseInt($("#horas5").val()) + parseInt($("#horas6").val());	
	               var total2 = parseInt($("#horasex1").val()) + parseInt($("#horasex2").val()) + parseInt($("#horasex3").val()) + parseInt($("#horasex4").val()) + parseInt($("#horasex5").val()) + parseInt($("#horasex6").val());
				  
	              $("#cantidad").val(total + (total2 * 2));        
	              break;
	            }	
			});
			$("#horas7").keyup(function (){		
			    switch ($('#formula').val()){			
			      case '5':
			      var total = parseInt($("#horas1").val()) + parseInt($("#horas2").val()) + parseInt($("#horas3").val()) + parseInt($("#horas4").val()) + parseInt($("#horas5").val()) + parseInt($("#horas6").val()) + parseInt($("#horas7").val());	
	               var total2 = parseInt($("#horasex1").val()) + parseInt($("#horasex2").val()) + parseInt($("#horasex3").val()) + parseInt($("#horasex4").val()) + parseInt($("#horasex5").val()) + parseInt($("#horasex6").val()) + parseInt($("#horasex7").val());
				  
	              $("#cantidad").val(total + (total2 * 2));        
	              break;
	            }	
			});
			$("#horas8").keyup(function (){		
			    switch ($('#formula').val()){			
			      case '5':
			      var total = parseInt($("#horas1").val()) + parseInt($("#horas2").val()) + parseInt($("#horas3").val()) + parseInt($("#horas4").val()) + parseInt($("#horas5").val()) + parseInt($("#horas6").val()) + parseInt($("#horas7").val()) + parseInt($("#horas8").val());	
	               var total2 = parseInt($("#horasex1").val()) + parseInt($("#horasex2").val()) + parseInt($("#horasex3").val()) + parseInt($("#horasex4").val()) + parseInt($("#horasex5").val()) + parseInt($("#horasex6").val()) + parseInt($("#horasex7").val()) + parseInt($("#horasex8").val());
				  
	              $("#cantidad").val(total + (total2 * 2));        
	              break;
	            }	
			});
			$("#horas9").keyup(function (){		
			    switch ($('#formula').val()){			
			      case '5':
			      var total = parseInt($("#horas1").val()) + parseInt($("#horas2").val()) + parseInt($("#horas3").val()) + parseInt($("#horas4").val()) + parseInt($("#horas5").val()) + parseInt($("#horas6").val()) + parseInt($("#horas7").val()) + parseInt($("#horas8").val()) + parseInt($("#horas9").val());	
	              var total2 = parseInt($("#horasex1").val()) + parseInt($("#horasex2").val()) + parseInt($("#horasex3").val()) + parseInt($("#horasex4").val()) + parseInt($("#horasex5").val()) + parseInt($("#horasex6").val()) + parseInt($("#horasex7").val()) + parseInt($("#horasex8").val()) + parseInt($("#horasex9").val());
				  
	              $("#cantidad").val(total + (total2 * 2));        
	              break;
	            }	
			});
			$("#horas10").keyup(function (){		
			    switch ($('#formula').val()){			
			      case '5':
			      var total = parseInt($("#horas1").val()) + parseInt($("#horas2").val()) + parseInt($("#horas3").val()) + parseInt($("#horas4").val()) + parseInt($("#horas5").val()) + parseInt($("#horas6").val()) + parseInt($("#horas7").val()) + parseInt($("#horas8").val()) + parseInt($("#horas9").val()) + parseInt($("#horas10").val());	
	              var total2 = parseInt($("#horasex1").val()) + parseInt($("#horasex2").val()) + parseInt($("#horasex3").val()) + parseInt($("#horasex4").val()) + parseInt($("#horasex5").val()) + parseInt($("#horasex6").val()) + parseInt($("#horasex7").val()) + parseInt($("#horasex8").val()) + parseInt($("#horasex9").val()) + parseInt($("#horasex10").val());
				  
	              $("#cantidad").val(total + (total2 * 2));       
	              break;
	            }	
			});
			
			
			$("#horasex1").keyup(function (){		
			    switch ($('#formula').val()){			
			      case '5':
			      var total = parseInt($("#horas1").val());	
				  var total2 = parseInt($("#horasex1").val());
				  
	              $("#cantidad").val(total + (total2 * 2));  
	              break;
	            }	
			});
			$("#horasex2").keyup(function (){		
			    switch ($('#formula').val()){			
			      case '5':
			      var total = parseInt($("#horas1").val()) + parseInt($("#horas2").val());	
	               var total2 = parseInt($("#horasex1").val()) + parseInt($("#horasex2").val());
				  
	              $("#cantidad").val(total + (total2 * 2));       
	              break;
	            }	
			});
			$("#horasex3").keyup(function (){		
			    switch ($('#formula').val()){			
			      case '5':
			      var total = parseInt($("#horas1").val()) + parseInt($("#horas2").val()) + parseInt($("#horas3").val());	
	               var total2 = parseInt($("#horasex1").val()) + parseInt($("#horasex2").val()) + parseInt($("#horasex3").val());
				  
	              $("#cantidad").val(total + (total2 * 2));        
	              break;
	            }	
			});
			$("#horasex4").keyup(function (){		
			    switch ($('#formula').val()){			
			      case '5':
			      var total = parseInt($("#horas1").val()) + parseInt($("#horas2").val()) + parseInt($("#horas3").val()) + parseInt($("#horas4").val());	
	               var total2 = parseInt($("#horasex1").val()) + parseInt($("#horasex2").val()) + parseInt($("#horasex3").val()) + parseInt($("#horasex4").val());
				  
	              $("#cantidad").val(total + (total2 * 2));        
	              break;
	            }	
			});
			$("#horasex5").keyup(function (){		
			    switch ($('#formula').val()){			
			      case '5':
			      var total = parseInt($("#horas1").val()) + parseInt($("#horas2").val()) + parseInt($("#horas3").val()) + parseInt($("#horas4").val()) + parseInt($("#horas5").val());	
	               var total2 = parseInt($("#horasex1").val()) + parseInt($("#horasex2").val()) + parseInt($("#horasex3").val()) + parseInt($("#horasex4").val()) + parseInt($("#horasex5").val());
				  
	              $("#cantidad").val(total + (total2 * 2));       
	              break;
	            }	
			});
			$("#horasex6").keyup(function (){		
			    switch ($('#formula').val()){			
			      case '5':
			      var total = parseInt($("#horas1").val()) + parseInt($("#horas2").val()) + parseInt($("#horas3").val()) + parseInt($("#horas4").val()) + parseInt($("#horas5").val()) + parseInt($("#horas6").val());	
	               var total2 = parseInt($("#horasex1").val()) + parseInt($("#horasex2").val()) + parseInt($("#horasex3").val()) + parseInt($("#horasex4").val()) + parseInt($("#horasex5").val()) + parseInt($("#horasex6").val());
				  
	              $("#cantidad").val(total + (total2 * 2));        
	              break;
	            }	
			});
			$("#horasex7").keyup(function (){		
			    switch ($('#formula').val()){			
			      case '5':
			      var total = parseInt($("#horas1").val()) + parseInt($("#horas2").val()) + parseInt($("#horas3").val()) + parseInt($("#horas4").val()) + parseInt($("#horas5").val()) + parseInt($("#horas6").val()) + parseInt($("#horas7").val());	
	               var total2 = parseInt($("#horasex1").val()) + parseInt($("#horasex2").val()) + parseInt($("#horasex3").val()) + parseInt($("#horasex4").val()) + parseInt($("#horasex5").val()) + parseInt($("#horasex6").val()) + parseInt($("#horasex7").val());
				  
	              $("#cantidad").val(total + (total2 * 2));        
	              break;
	            }	
			});
			$("#horasex8").keyup(function (){		
			    switch ($('#formula').val()){			
			      case '5':
			      var total = parseInt($("#horas1").val()) + parseInt($("#horas2").val()) + parseInt($("#horas3").val()) + parseInt($("#horas4").val()) + parseInt($("#horas5").val()) + parseInt($("#horas6").val()) + parseInt($("#horas7").val()) + parseInt($("#horas8").val());	
	               var total2 = parseInt($("#horasex1").val()) + parseInt($("#horasex2").val()) + parseInt($("#horasex3").val()) + parseInt($("#horasex4").val()) + parseInt($("#horasex5").val()) + parseInt($("#horasex6").val()) + parseInt($("#horasex7").val()) + parseInt($("#horasex8").val());
				  
	              $("#cantidad").val(total + (total2 * 2));        
	              break;
	            }	
			});
			$("#horasex9").keyup(function (){		
			    switch ($('#formula').val()){
			      case '5':
			      var total = parseInt($("#horas1").val()) + parseInt($("#horas2").val()) + parseInt($("#horas3").val()) + parseInt($("#horas4").val()) + parseInt($("#horas5").val()) + parseInt($("#horas6").val()) + parseInt($("#horas7").val()) + parseInt($("#horas8").val()) + parseInt($("#horas9").val());	
	              var total2 = parseInt($("#horasex1").val()) + parseInt($("#horasex2").val()) + parseInt($("#horasex3").val()) + parseInt($("#horasex4").val()) + parseInt($("#horasex5").val()) + parseInt($("#horasex6").val()) + parseInt($("#horasex7").val()) + parseInt($("#horasex8").val()) + parseInt($("#horasex9").val());
				  
	              $("#cantidad").val(total + (total2 * 2));        
	              break;
	            }	
			});
			$("#horasex10").keyup(function (){		
			    switch ($('#formula').val()){
			      case '5':
			      var total = parseInt($("#horas1").val()) + parseInt($("#horas2").val()) + parseInt($("#horas3").val()) + parseInt($("#horas4").val()) + parseInt($("#horas5").val()) + parseInt($("#horas6").val()) + parseInt($("#horas7").val()) + parseInt($("#horas8").val()) + parseInt($("#horas9").val()) + parseInt($("#horas10").val());	
	              var total2 = parseInt($("#horasex1").val()) + parseInt($("#horasex2").val()) + parseInt($("#horasex3").val()) + parseInt($("#horasex4").val()) + parseInt($("#horasex5").val()) + parseInt($("#horasex6").val()) + parseInt($("#horasex7").val()) + parseInt($("#horasex8").val()) + parseInt($("#horasex9").val()) + parseInt($("#horasex10").val());
				  
	              $("#cantidad").val(total + (total2 * 2));       
	              break;
	            }	
			});
			
			
		});//$(window).ready	
		
		$(window).load(function() {
		$("#plaza").click();
		$("#tramoSCT").click();
		$("#tramo_adjuntar").click();
		setTimeout(function(){
  			$("#actividad_adjuntar").click();
		}, 1000);
		
		setTimeout(function(){
  			$("#tramo").click();
		}, 1000);
		
		setTimeout(function(){
  			$("#subtramo").click();
		}, 2000);
		
		$("#tramo2").click();
		setTimeout(function(){
			$("#subtramo2").click();
		}, 1000);
		
		$("#fechaactividad").change();
		
		$("#plaza_exportar").click();
		setTimeout(function(){
  			$("#tramo_exportar").click();
		}, 1000);
		
		setTimeout(function(){
  			$("#subtramo_exportar").click();
		}, 2000);
		
		
		$("#cuerpo").click();
		$("#cuerpomulti").click();
	});//$(window).load	
function MostrarFilas(Fila) {
	var quitar = Fila -1;	
	document.getElementById("sac"+quitar).style.display = "none";
	
	var elementos = document.getElementsByName(Fila);    
    for (i = 0; i< elementos.length; i++) {
        if(navigator.appName.indexOf("Microsoft") > -1){
               var visible = 'block'
        } else {
               var visible = 'table-row';
        }
	elementos[i].style.display = visible;
        }
}

function OcultarFilas(Fila) {	
    var elementos = document.getElementsByName(Fila);
    for (k = 0; k< elementos.length; k++) {
               elementos[k].style.display = "none";
    }
}


	
	</script>

</head>
<body>

	<header>
		<a href="index.php"><img class="derecha" src="images/cerrarsesion.png"></a>
		<a href="Almacen.php"><span>Insumos</span></a>
		<a href="Salidas.php"><span>Salida Insumos</span></a>
		<a href="AlmacenMaq.php"><span>Maquinaria</span></a>
		<a href="SalidasMaq.php"><span>Entrada Maq</span></a>
		<a href="AvanceDiarioPlus1.php"><span>Avance Diario</span></a>
            <?php 
			if($_SESSION['S_Privilegios'] == 'ADMINISTRADOR' || $_SESSION['S_Privilegios'] == 'COORDINADOR'){
			 ?>
		<a href="Contratos.php"><span>Contratos</span></a>
		<a href="Comparativo.php"><span>Comparativa</span></a>
			<?php } ?>   
	</header>

    <!--<div id="menusuperior">
			<div class="logo">				
					<center><img src="images/HEADBIOMETRICO.png" width="153" height="46" ></center>
	  		</div>
			<a href="Almacen.php"><div class="submenu_superior" id="menu2">				
				Insumos en Tramo
			</div></a> 
			<a href="Salidas.php"><div class="submenu_superior" id="menu3">				
					Regreso Insumos
			</div></a>
            <a href="AlmacenMaq.php"><div class="submenu_superior" id="menu8">				
					Salida Maq
			</div></a>
            <a href="SalidasMaq.php"><div class="submenu_superior" id="menu9">				
					Entrada Maq
			</div></a>
			<a href="AvanceDiarioPlus.php"><div class="submenu_superior_sel" id="menu4">				
					Avance Diario
			</div></a>
			<!--<a href="asistencia.php" target="_blank"><div class="submenu_superior" id="menu7">				
					Asistencia
			</div></a>
             <a href="Contratos.php"><div class="submenu_superior" id="menu5">				
					Contratos
			</div></a>
			<a href="Comparativo.php"><div class="submenu_superior" id="menu6">				
					Comparativa
			</div></a>	 
			<a href="PresupuestosCapex.php"><div class="submenu_superior" id="menu10">				
					Ptto Capex
			</div></a>     
			<a href="Prorrateo.php"><div class="submenu_superior" id="menu1">				
					Carga Costos MO
			</div></a>
			<a href="ProrrateoAct.php"><div class="submenu_superior" id="menu5">
					Prorrateo MO
			</div></a>
			<a href="ProrrateoMaq.php"><div class="submenu_superior" id="menu12">				
					Carga Costos Maq
			</div></a>
			<a href="ProrrateoMaquinaria.php"><div class="submenu_superior" id="menu11">				
					Prorrateo Maq
			</div></a> 
	</div>-->

	<div id="menulateral">
        <div class="submenu_lateral_encabezado">
        	<span class="glyphicon glyphicon-wrench"></span> &rlm; HERRAMIENTAS
        </div>
		<a href="#" data-toggle="modal" data-target=".filtro"><div class="submenu_lateral" id="sub3">
			<span class="glyphicon glyphicon-filter"></span> &rlm; Filtro
		</div> </a>  
        <a href="#" data-toggle="modal" data-target=".agregaravance" onClick="Limpiar()"><div class="submenu_lateral" id="sub4">
			<span class="glyphicon glyphicon-plus"></span> &rlm; Agregar Avance
		</div> </a>
		<a href="#" data-toggle="modal" data-target=".exportar" onClick="Limpiar()"><div class="submenu_lateral" id="sub1">
			<span class="glyphicon glyphicon-download"></span> &rlm; Descargar Avance 
		</div></a>
		<a href="#" data-toggle="modal" data-target=".borraravance" onClick="Limpiar()"><div class="submenu_lateral" id="sub2">
			<span class="glyphicon glyphicon-remove"></span> &rlm;  Eliminar Avance
		</div></a>
		<a href="#" data-toggle="modal" data-target=".adjuntar" onClick="Limpiar()"><div class="submenu_lateral" id="sub5">
			<span class="glyphicon glyphicon-upload"></span> &rlm;  Subir Fotos
		</div></a>
		<a href="#" data-toggle="modal" data-target=".sct" onClick="Limpiar()"><div class="submenu_lateral" id="sub5">
			<span class="glyphicon glyphicon-asterisk"></span> &rlm;  Generar Entregable SCT
		</div></a>
	</div>
	
    <div id="contenido">
    	<div class="row">
        	<div class="col-lg-4" align="center"><strong><strong>Plaza: <?php echo $lugar; ?></strong></strong></div>
        	<div class="col-lg-4" align="center"><strong>Tramo: <?php echo $tramo; ?></strong></div>
        </div>
        <br>
    	<div id='jqxWidget'>
            <div id='jqxTabs'>
                <ul>
                    <li style="margin-left: 30px;">Actividades del  <?php echo $fecha_filtro; ?></li>
                </ul>
                <div>
                    <div id="maestro" > </div>
                    	<h4 style="font-family:Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', serif">Recursos Utilizados</h4>
                    <div id="detalle"></div>
                </div>     
            </div>  
        </div>
    </div>

<!-- BORRAR FOLIO-->
<form id="formulario" action="AvanceDiarioPlus.php" method="post" enctype="multipart/form-data" >
<div class="modal fade borraravance" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
     <h4 class="modal-title" id="myModalLabel">Borrar Folio</h4>
      </div>
      <div class="modal-body">
        <!--CUERPO-->          
<center>      
<table width="466" height="63" border="0" align="center">
  <tr>
  	<td><input type="hidden" id="borrar1" name="borrar1"> </td>
  </tr>	 
  <tr id="lleno" style="display:none">
    <td>&iquest;Seguro que desea eliminar el folio <strong><span name="borrar" id="borrar"></span></strong>?</td>
  </tr>
  <tr id="vacio" style="display:none">
    <td align="center">No se ha seleccionado ning&uacute;n folio.</td>
  </tr>
</table>        
</center> 
        <!--FIN-->
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onClick="Limpiar()">Cancelar</button>
        <button type="submit" class="btn btn-primary" name="borrar_avance" id="borrar_avance">Aceptar</button>
      </div>
  </div>
    </div>
  </div>
</form>

<!-- FILTRO -->
<form id="formulario4" action="AvanceDiarioPlus.php" method="post" enctype="multipart/form-data" >      
<div class="modal fade filtro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><center>  <img src="images/filtro_header.png" height="40"> Filtro de informaci&oacute;n <img src="images/filtro_header1.png" height="40">  </center></h4>
      </div>
      <div class="modal-body">
      <!--CUERPO-->
    <div class="panel-group" >
  				<div class="panel panel-default">
    				<div class="panel-heading" role="tab" id="headingOne">
      					<h4 class="panel-title">
         					Seleccionar Ubicaci&oacute;n
      					</h4>
	    			</div>
    			<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
      		<div class="panel-body">
       	<div class="row"> 
           	<div class="col-md-3"> 
            Plaza:
             </div>
           	<div class="col-md-8"> 
            <select class="form-control" name="plaza" id="plaza">
            <?php
		  $i=1;
		  $selected="";
	      $sql = "SELECT DISTINCT PLAZA FROM Accesos WHERE USUARIO_ID = '".$_SESSION['S_UsuarioID']."'";
	      echo $sql;
    	  $rs = odbc_exec( $conn, $sql );
	      if ( !$rs ) { 
    	   exit( "Error en la consulta SQL" ); 
	      }     
    	  while ( odbc_fetch_row($rs) ) { 
	        $plaza = odbc_result($rs, 'PLAZA');
	       echo "<option id='".$i."'".$selected.">".$plaza."</option>";
		   $i++;
	      }//While 	 
			?>
           	</select>
             </div>
         </div> 
         <div class="row"><div class="col-md-12"><br></div></div> 
       	<div class="row"> 
           	<div class="col-md-3">Tramo:</div>
           	<div class="col-md-8"><select class="form-control" name="tramo" id="tramo"></select></div>
         </div> 
         <div class="row"><div class="col-md-12"><br></div></div>
         </div>   
         <div class="row">    
           	<div class="col-md-3">Subtramo:</div>
           	<div class="col-md-6"><select class="form-control" name="subtramo" id="subtramo"></select></div>
         </div>       
     		 </div>
    	</div>
  	</div>
   </div>
     
    <div class="panel-group" >
  				<div class="panel panel-default">
    				<div class="panel-heading" role="tab" id="headingOne">
      					<h4 class="panel-title">
         					Seleccionar Fecha:
      					</h4>
	    			</div>
    			<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
      		<div class="panel-body">
       	<div class="row"> 
           	<div class="col-md-3"> Fecha:</div>
           	<div class="col-md-6"><input type="date" id="fecha_filtro" name="fecha_filtro" class="form-control" value="<?php echo date("Y-m-d"); ?>"></div>
         </div>          
     		 </div>
    	</div>
  	</div>
   </div>
	<!--FIN-->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>        
        <button type="submit" class="btn btn-primary" name="filtrar" id="filtrar">Aceptar</button>
      </div>
    </div>
  </div>
  </div>
</form> 
<!--Fin Modal-->

<!--Agregar nuevo avance-->
<form id="formulario5" action="AvanceDiarioPlus.php" method="post" enctype="multipart/form-data" >
<div class="modal fade agregaravance" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <center> <img src="images/sac1.png" /></center>
      </div>
      <!--Cuerpo-->
      <center>
      <div class="bg-primary" id="divAD"><br> </div>
          <div class="row">
          	<div class="col-md-12"><input type="hidden" id="formula" name="formula" class="form-control" readonly /><br></div>
          </div>
          <div class="row">              
            <div class="col-md-2" align="right">Fecha:</div>
            <div class="col-md-3" align="left"><input type="text" placeholder="aaaa-mm-dd" name="fechaactividad" id="fechaactividad" class="form-control" required></div>
            <div class="col-md-1" align="right"><img src="images/LUPA.png" width="30" height="30" alt="Buscar" id="buscarfecha" name="buscarfecha"/></div>
            <div class="col-md-2" align="right">Numero: #<strong><?PHP echo $numsemactual; ?></strong></div> 
            <div class="col-md-1" align="right"> Semana: </div>
            <div class="col-md-3"><strong><?PHP echo $semanactual; ?></strong></div>            
          </div>      
          <div class="row">
            <div class="col-md-2"><br></div>
          </div>
          <div class="row">      	
            <div class="col-md-2" align="right">Tramo:</div>
            <div class="col-md-4" align="left">
                <select name="tramo2" id="tramo2" class="form-control">   	  	    
                  <?php	
                  $i=1;
                  $selected="";
                  $sql = "SELECT DISTINCT (TRAMO) FROM Accesos WHERE USUARIO_ID = '".$_SESSION['S_UsuarioID']."'";	  
                  //echo $sql;
                  $rs = odbc_exec( $conn, $sql );
                  if ( !$rs ) { 
                   exit( "Error en la consulta SQL" ); 
                  }     
                  while ( odbc_fetch_row($rs) ) { 
                   $tramoF = odbc_result($rs, 'TRAMO'); 
                   if ($_SESSION['S_Tramo']==$tramoF)
                        $selected="selected";			
                    else
                        $selected="";   
                   echo "<option id='".$i."'".$selected.">".$tramoF."</option>";
                   $i++;
                  }//While 	 
                  ?>
                 </select>
            </div>
            <div class="col-md-1" align="right">SubTramo: </div>  
            <div class="col-md-3" align="left"><select name="subtramo2" id="subtramo2" class="form-control"> </select></div>       
              <div class="col-md-2">
                <div id='apoyo' style='margin-left: 10px; float: left;'><span>Apoyo</span></div>
              </div>
          </div>
        <div class="row">
      	  <div class="col-md-12" align="right"><br></div>
      	</div>
      <div class="bg-primary" id="divAD"><br></div>  
      <div><br></div>
      <div class="row">
      <div class="col-md-2" align="right">Actividad:</div>
        <div class="col-md-6" align="left"><input type="text" id="concepto" name="concepto" onBlur="consultaUnidad1(document.getElementById('concepto').value)" class="form-control" autocomplete='off'/></div> 
        <div class="col-md-1" align="left">Unidad:</div>
        <div class="col-md-2" align="left"><input type="text" id="unidad" name="unidad" accept-charset="utf-8" class="form-control" readonly /></div>
      </div>
      <div class="row">
        <div class="col-md-12"><hr size="3"></div>
      </div>  
          <div class="row">
            <div class="col-md-2" align="right">Km Ini:</div>
            <div class="col-md-2" align="left"><select name="km_ini" id="km_ini" class="form-control"></select></div>   
            <div class="col-md-1" align="center"><strong>+</strong></div>  
            <div class="col-md-2" align="right"><input type="number" id="enca_ini" name="enca_ini" value="000" size="3" class="form-control"></div>        
         	<div class="col-md-1" align="right"></div>
            <div class="col-md-1" align="right">Longitud:</div>
         	<div class="col-md-2" align="left"><input type="number" id="longitud" oninput="calculaLon1($('#longitud').val())" name="longitud" size="5"  step="any" class="form-control"></div>     
       </div>
       <br>
          <div class="row">
            <div class="col-md-2" align="right">Km Fin:</div>
            <div class="col-md-2" align="left"><select name="km_fin" id="km_fin" class="form-control"></select></div>   
            <div class="col-md-1" align="center"><strong>+</strong></div>  
            <div class="col-md-2" align="right"><input type="number" id="enca_fin" name="enca_fin" value="000" size="3" class="form-control"></div>        
          	<div class="col-md-1" align="right"></div>
            <div class="col-md-1" align="right">Ancho:</div>
         	<div class="col-md-2" align="left"><input type="number" id="ancho" oninput="calculaAnc1($('#ancho').val())" name="ancho" size="5"  step="any" class="form-control"></div>    
      </div>
      <div class="row">
         <div class="col-md-12" ><br></div>
      </div>
      <div class="row">
      	<div class="col-md-2" align="right">Cuerpo:</div>
        <div class="col-md-2" align="right">
          <select name="cuerpo" id="cuerpo" class="form-control">
            <option id="1"> A </option>
            <option id="2"> B </option>
            <option id="3"> C </option>
          </select>
        </div>
        <div class="col-md-1" align="right">Zona:</div>
        <div class="col-md-3" align="right">
          <select name="zona" id="zona" class="form-control">      
          </select>
        </div>
         <div class="col-md-1" align="right">Espesor:</div>
         <div class="col-md-2" align="left"><input type="number" id="espesor" oninput="calculaEsp1($('#espesor').val())" name="espesor" size="5"  step="any" class="form-control"></div>
      </div>
      <br>    
      <div class="row">
      	 <div class="col-md-5" align="left"></div>
        <div class="col-md-3" align="center">
         <label>
         	C&aacute;lculo autom&aacute;tico <input type="checkbox" name="calculakm" id="calculakm" onClick="calculaKm1($('#calculakm').val())" size="5">
         </label>
        </div> 
      	 <div class="col-md-1" align="left">Cantidad</div>
         <div class="col-md-2" align="left"><input type="number" id="cantidad" name="cantidad" size="8" class="form-control" readonly step="any"></div> 
      </div>
      <div><br></div>
      <div class="bg-primary" id="divAD"><br></div> 
      <div><br></div>
<p align="center"><strong>Mano de Obra</strong></p>
<table>
<?php 
   for ($i = 1; $i <= 30; $i++) { 
	   $x = $i + 100;
	   $z = $x + 1;
	   if ($i==1){
		   echo "<tr name='".$x."'>
         			<td style='width:50px'>".$i."</td>
         			<td style='width:250px'><input type='text' id='nombre".$i."' name='nombre".$i."' autocomplete='off' class='form-control'/></td>
        			<td style='width:100px' align='center'>Horas</td>
         			<td style='width:100px'><input type='number' id='horas".$i."' name='horas".$i."' class='form-control'/></td>
         			<td align='center' style='width:150px'>Horas Extra</td>
         			<td style='width:100px'><input type='number' id='horasex".$i."' name='horasex".$i."' value='0' class='form-control'/></td>
         			<td style='width:80px' align='center'><a href=\"javascript:MostrarFilas('".$z."')\"><img id='sac".$x."' src='images/plus2.png' width='30' height='30'></a></td>
     			</tr>";
	   }else{
		   echo "<tr name='".$x."' style='display:none'>
         			<td style='width:50px'>".$i."</td>
         			<td style='width:250px'><input type='text' id='nombre".$i."' name='nombre".$i."' autocomplete='off' class='form-control'/></td>
        			<td style='width:100px' align='center'>Horas</td>
         			<td style='width:100px'><input type='number' id='horas".$i."' name='horas".$i."' class='form-control'/></td>
         			<td align='center' style='width:150px'>Horas Extra</td>
         			<td style='width:100px'><input type='number' id='horasex".$i."' name='horasex".$i."' value='0' class='form-control'/></td>
         			<td style='width:80px' align='center'><a href=\"javascript:MostrarFilas('".$z."')\"><img id='sac".$x."' src='images/plus2.png' width='30' height='30'></a></td>
     			</tr>";		  
	   }	   
   }	
?>
</table>
<hr size="3" />
<div class="row">
    <div class="col-lg-3"></div>
    <div class="col-lg-2"><input type="hidden" name="contador" id="contador"><input type="hidden" name="contadormaq" id="contadormaq"><input type="hidden" name="base" id="base"></div>
    <div class="col-lg-1">
	</div>
</div>
<div class="row">
	<div class="col-lg-12"><div id="mostrar_salida2"></div></div>
</div>
<div class="row">
	<div class="col-lg-12"><div id="mostrar_salida"></div></div>
</div>
<br>
<div class="row">
	<div class="col-lg-1"></div>
</div>
<hr size="3">
      <div class="row">
         <div class="col-md-4">Observaciones</div>
         <div class="col-md-7"><textarea rows="2" cols="60" id="observacion" name="observacion" class="form-control"></textarea></div>
      </div>
</center>
      <!--Fin-->
    <div class="modal-footer">     
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="refresh()">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="guardaravance" id="guardaravance">Guardar</button>
      </div>
    </div>
  </div>
</div>
</form>
<!--Fin-->

<!--MULTIPUNTO-->
<form id="formulario2" action="AvanceDiarioPlus.php" method="post" enctype="multipart/form-data" >
<div class="modal fade multipunto" id="multipunto" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
     <h4 class="modal-title" id="myModalLabel">Agregar Multipunto <br> <center>Folio: <span id="foliomulti"></span></h4>
      </div>
      <div class="modal-body">
       <!--Cuerpo-->
      <center>
      <input type="hidden" id="foliomulti1" name="foliomulti1">
      <input type="hidden" id="fechamulti1" name="fechamulti1">
      <input type="hidden" id="actividadmulti1" name="actividadmulti1">
      <input type="hidden" id="unidadmulti1" name="unidadmulti1">
      <input type="hidden" id="tramomulti1" name="tramomulti1">
      <input type="hidden" id="subtramomulti1" name="subtramomulti1">
      <input type="hidden" id="actidmulti1" name="actidmulti1">
      <input type="hidden" id="formulamulti" name="formulamulti">
      <input type="hidden" id="sb_id" name="sb_id">
      <input type="hidden" id="sobrestante" name="sobrestante">
      <input type="hidden" id="plazamulti" name="plazamulti">
      <div class="bg-primary" id="divAD"><br> </div>
          <div class="row">              
          	<br>
            <div class="col-md-2" align="right">Fecha:</div>
            <div class="col-md-2" align="left"><strong><span id="fechamulti"></span></strong></div>
            <div class="col-md-2" align="right">Numero: #<strong><?PHP echo $numsemactual; ?></strong></div> 
            <div class="col-md-2" align="right"> Semana: </div>
            <div class="col-md-4" align="left"><strong><?PHP echo $semanactual; ?></strong></div>            
          </div>      
          <div class="row">
            <div class="col-md-2"><br></div>
          </div>
          <div class="row">      	
            <div class="col-md-2" align="right">Tramo:</div>
            <div class="col-md-4" align="left"><strong><span id="tramomulti"></span></strong></div>
            <div class="col-md-2" align="right">SubTramo: </div>  
            <div class="col-md-3" align="left"><strong><span id="subtramomulti"></span></strong></div>       
          </div>
        <div class="row">
      	  <div class="col-md-12" align="right"><br></div>
      	</div>
     
      <div class="bg-primary" id="divAD"><br></div>
      <div class="row">
      <br>
      <div class="col-md-2" align="right">Actividad:</div>
        <div class="col-md-6" align="left"><strong><span id="actividadmulti"></span></strong></div> 
        <div class="col-md-1" align="left">Unidad:</div>
        <div class="col-md-2" align="left"><strong><span id="unidadmulti"></span></strong></div>
      </div>
      <div class="row">
        <div class="col-md-12"><hr size="3"></div>
      </div>   
      <div class="row">
            <div class="col-md-2" align="right">Km Ini:</div>
            <div class="col-md-2" align="left"><select name="km_inimulti" id="km_inimulti" class="form-control"></select></div>   
            <div class="col-md-1" align="center"><strong>+</strong></div>  
            <div class="col-md-2" align="right"><input type="number" id="enca_inimulti" name="enca_inimulti" value="000" size="3" class="form-control"></div>        
         	<div class="col-md-1" align="right"></div>
            <div class="col-md-1" align="right">Longitud:</div>
         	<div class="col-md-2" align="left"><input type="number" id="longitudmulti" name="longitudmulti" size="5"  step="0.001" class="form-control" oninput="calculaLon($('#longitudmulti').val())"></div>     
       </div>
       <br>
          <div class="row">
            <div class="col-md-2" align="right">Km Fin:</div>
            <div class="col-md-2" align="left"><select name="km_finmulti" id="km_finmulti" class="form-control"></select></div>   
            <div class="col-md-1" align="center"><strong>+</strong></div>  
            <div class="col-md-2" align="right"><input type="number" id="enca_finmulti" name="enca_finmulti" value="000" size="3" class="form-control"></div>        
          	<div class="col-md-1" align="right"></div>
            <div class="col-md-1" align="right">Ancho:</div>
         	<div class="col-md-2" align="left"><input type="number" id="anchomulti" name="anchomulti" size="5"  step="0.001" class="form-control" oninput="calculaAnc($('#anchomulti').val())"></div>    
      </div>
      <div class="row">
         <div class="col-md-12" ><br></div>
      </div>
      <div class="row">
      	<div class="col-md-2" align="right">Cuerpo:</div>
        <div class="col-md-2" align="right">
          <select name="cuerpomulti" id="cuerpomulti" class="form-control">
            <option id="1"> A </option>
            <option id="2"> B </option>
            <option id="3"> C </option>
          </select>
        </div>
        <div class="col-md-1" align="right">Zona:</div>
        <div class="col-md-3" align="right">
          <select name="zonamulti" id="zonamulti" class="form-control">      
          </select>
        </div>
         <div class="col-md-1" align="right">Espesor:</div>
         <div class="col-md-2" align="left"><input type="number" id="espesormulti" name="espesormulti" size="5"  step="0.001" class="form-control" oninput="calculaEsp($('#espesormulti').val())"></div>
      </div>
      <br>    
      <div class="row">
      	 <div class="col-md-5" align="left"></div>
        <div class="col-md-3" align="center">
         <label>
            	C&aacute;lculo autom&aacute;tico <input type="checkbox" name="calcular" id="calcular" onClick="calculaKm($('#calcular').val())"> 
            </label>
        </div> 
      	 <div class="col-md-1" align="left">Cantidad</div>
         <div class="col-md-2" align="left"><input type="number" id="cantidadmulti" name="cantidadmulti" step="any" size="8" class="form-control"></div> 
      </div>   
      </center>
        <!--FIN-->
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onClick="Limpiar()">Cancelar</button>
        <button type="submit" class="btn btn-primary" name="multipunto" id="multipunto">Guardar</button>
      </div>
  </div>
    </div>
  </div>
</form>	

<!--DESCARGAR-->
<form id="formulario3" action="Excel_Avance.php" method="post" enctype="multipart/form-data" >
<div class="modal fade exportar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
     <h4 class="modal-title" id="myModalLabel">Descargar</h4>
      </div>
      <div class="modal-body">
       <!--CUERPO-->
    <div class="panel-group" >
  				<div class="panel panel-default">
    				<div class="panel-heading" role="tab" id="headingOne">
      					<h4 class="panel-title">
         					Seleccionar Ubicaci&oacute;n
      					</h4>
	    			</div>
    			<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
      		<div class="panel-body">
       	<div class="row"> 
           	<div class="col-md-3">Plaza:</div>
           	<div class="col-md-8"> 
            	<select class="form-control" name="plaza_exportar" id="plaza_exportar">
            		<?php
		  			$i=1;
		 			$selected="";
	      			$sql = "SELECT DISTINCT PLAZA FROM Accesos WHERE USUARIO_ID = '".$_SESSION['S_UsuarioID']."'";
	      			echo $sql;
    	  			$rs = odbc_exec( $conn, $sql );
	      			if ( !$rs ) { 
    	   				exit( "Error en la consulta SQL" ); 
	      			}     
    	 			while ( odbc_fetch_row($rs) ) { 
	        			$plaza = odbc_result($rs, 'PLAZA');
	       				echo "<option id='".$i."'>".$plaza."</option>";
		   				$i++;
	      			}//While 	 
					?>
           		</select>
             </div>
         </div> 
         <div class="row"><div class="col-md-12"><br></div></div> 
       	 <div class="row"> 
           	<div class="col-md-3">Tramo:</div>
           	<div class="col-md-8"><select class="form-control" name="tramo_exportar" id="tramo_exportar"></select></div>
         </div> 
         <div class="row"><div class="col-md-12"><br></div></div>   
         <div class="row">    
           	<div class="col-md-3">Subtramo:</div>
           	<div class="col-md-6"><select class="form-control" name="subtramo_exportar" id="subtramo_exportar"></select></div>
         </div>       
     	</div>
    </div>
  	</div>
   </div>
   <div class="panel-group" >
  	<div class="panel panel-default">
    	<div class="panel-heading" role="tab" id="headingOne">
      		<h4 class="panel-title">
         		Sobrestante
      		</h4>
	    </div>
    	<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
      		<div class="panel-body">
       		<div class="row"> 
           		<div class="col-md-3">Nombre:</div>
           		<div class="col-md-6"><select id="usuario_exportar" name="usuario_exportar" class="form-control"></select></div>
         	</div>          
     		</div>
    	</div>
  	</div>
   </div>
    <div class="panel-group" >
  				<div class="panel panel-default">
    				<div class="panel-heading" role="tab" id="headingOne">
      					<h4 class="panel-title">
         					Seleccionar D&iacute;a
      					</h4>
	    			</div>
    			<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
      		<div class="panel-body">
       	<div class="row"> 
           	<div class="col-md-3">D&iacute;a:</div>
           	<div class="col-md-6"><input type="date" id="fecha_exportar" name="fecha_exportar" value="<?php echo date("Y-m-d");?>" class="form-control"></div>
         </div>          
     		 </div>
    	</div>
  	</div>
   </div>
        <!--FIN-->
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onClick="Limpiar()">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="exportar_excel" id="exportar_excel">Descargar</button>
      </div>
  </div>
    </div>
  </div>
</form>




<!--ADJUNTAR
<form action="AvanceDiarioPlus.php" method="post" enctype="multipart/form-data" >-->
<div class="modal fade adjuntar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
     <h4 class="modal-title" id="myModalLabel">Adjuntar Avance</h4>
      </div>
      <div class="modal-body">
       <!--CUERPO-->
    <div class="panel-group" >
  				<div class="panel panel-default">
    				<div class="panel-heading" role="tab" id="headingOne">
      					<h4 class="panel-title">
         					Selecci&oacute;n
      					</h4>
	    			</div>
    			<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
      		<div class="panel-body">
       	<div class="row"> 
           	<div class="col-md-2">Periodo:</div>
           	<div class="col-md-3"> 
            	<input type="text" value="<?php echo date("Y-m"); ?>" class="form-control" name="periodo_adjuntar" id="periodo_adjuntar">
            </div>
           	<div class="col-md-3"> 
            	<input type="hidden" class="form-control" name="abrev" id="abrev" readonly>
            </div>
         </div>  <br>
       	<div class="row"> 
           	<div class="col-md-2">Tramo:</div>
           	<div class="col-md-8"> 
            	<select class="form-control" name="tramo_adjuntar" id="tramo_adjuntar">
            		<?php
		  			$i=1;
		 			$selected="";
	      			$sql = "SELECT DISTINCT TRAMO FROM Accesos WHERE USUARIO_ID = '".$_SESSION['S_UsuarioID']."'";
	      			echo $sql;
    	  			$rs = odbc_exec( $conn, $sql );
	      			if ( !$rs ) { 
    	   				exit( "Error en la consulta SQL" ); 
	      			}     
    	 			while ( odbc_fetch_row($rs) ) { 
	        			$tramo = odbc_result($rs, 'TRAMO');
	       				echo "<option id='".$i."'>".$tramo."</option>";
		   				$i++;
	      			}//While
					?>
           		</select>
             </div> 
         </div>  <br>
       	<div class="row"> 
           	<div class="col-md-2">Actividad:</div>
           	<div class="col-md-10"> 
            	<select class="form-control" name="actividad_adjuntar" id="actividad_adjuntar"></select>
             </div> 
         </div>      
     	</div>
    </div>
  	</div>
   </div>
   <div id="mensaje_foto" style="display:none" align="center">
   	<!--<span id="">
    </span>-->
    	<strong>No hay actividades registradas en este tramo.</strong>
   </div>
   <div id="mostrar" style="display:none">
        <div class="panel-group" >
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingOne">
                            <h4 class="panel-title">
                                Fotograf&iacute;as
                            </h4>
                        </div>
                    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
            <div class="row">
            	<div class="col-md-4" align="right">                  
                    <div id='calcula_fotos' style='margin-left: 10px; float: left;'><span>Calcular fotos</span></div>
                </div>
                <div class="col-md-5">
                    N&uacute;m de fotograf&iacute;as necesarias:
                </div>
                <div class="col-md-2"><input type="text" id="numero_fotos" name="numero_fotos" class="form-control" readonly></div>
             </div>   
            </div>
        </div>
        </div>
       </div>
   </div>
        <!--FIN-->
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onClick="Limpiar()">Cerrar</button>
        <button type="button" class="btn btn-primary" name="Adjuntar" id="Adjuntar">Adjuntar</button>
      </div>
  </div>
    </div>
  </div>
<!--</form>-->

<!--MTTO MENOR-->
<form action="Excel_SCT.php" method="post" enctype="multipart/form-data" >
<div class="modal fade sct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
     <h4 class="modal-title" id="myModalLabel">Generar Entregable</h4>
      </div>
      <div class="modal-body">
       <!--CUERPO-->
    <div class="panel-group">
  				<div class="panel panel-default">
    				<div class="panel-heading" role="tab" id="headingOne">
      					<h4 class="panel-title">
         					Selecci&oacute;n
      					</h4>
	    			</div>
    			<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
      		<div class="panel-body">
       	<div class="row"> 
           	<div class="col-md-3">Periodo:</div>
           	<div class="col-md-3"> 
            	<input type="text" value="<?php echo date("Y-m"); ?>" class="form-control" name="periodoSCT" id="periodoSCT">
            </div>
         </div>  <br>
       	<div class="row"> 
           	<div class="col-md-3">Encadenamiento:</div>
           	<div class="col-md-5"> 
            	<input type="text" class="form-control" name="encaSCT" id="encaSCT" readonly>
            	<input type="hidden" class="form-control" name="autoSCT" id="autoSCT" readonly>
            	<input type="hidden" class="form-control" name="cadeSCT" id="cadeSCT" readonly>
             </div>
         </div>  <br>
       	<div class="row"> 
           	<div class="col-md-3">Tramo:</div>
           	<div class="col-md-8"> 
            	<select class="form-control" name="tramoSCT" id="tramoSCT">
            		<?php
		  			$i=1;
		 			$selected="";
	      			$sql = "SELECT DISTINCT TRAMO FROM Accesos WHERE USUARIO_ID = '".$_SESSION['S_UsuarioID']."'";
	      			echo $sql;
    	  			$rs = odbc_exec( $conn, $sql );
	      			if ( !$rs ) { 
    	   				exit( "Error en la consulta SQL" ); 
	      			}     
    	 			while ( odbc_fetch_row($rs) ) { 
	        			$tramo = odbc_result($rs, 'TRAMO');
	       				echo "<option id='".$i."'>".$tramo."</option>";
		   				$i++;
	      			}//While
					?>
           		</select>
             </div> 
         </div>  <br>     
     	</div>
    </div>
  	</div>
   </div>
        <!--FIN-->
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onClick="Limpiar()">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="generar" id="generar" on-clic="Procesando...">Generar</button>
      </div>
  </div>
    </div>
  </div><!---->
</form>


</body>
</html>