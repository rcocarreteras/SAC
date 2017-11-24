<?php
require_once('Connections/sac2.php'); 
header( 'Content-type: text/html; charset=iso-8859-1' );
//print_r($_POST);

// Desactivar toda notificación de error
error_reporting(0);

$ip_maquina = $_SERVER['REMOTE_ADDR'];

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d');
$bandera=FALSE;
$bandera2=FALSE;
$notificacion = "";


if (isset($_REQUEST['entrada'])) {
	$empleado = $_POST["empleado1"];	
	$password = $_POST["password"];
	$hora=  date('H:i:s', time());
		
	$sql = "SELECT * FROM CatEmpleados WHERE NoEmp = '". $empleado ."' and Password = '".$password."'";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta SQL" ); 
    }    
    while ( odbc_fetch_row($rs) ) { 
      $nombre = odbc_result($rs, 'Empleado');
	  $bandera=TRUE;	  	  
    }
	
	if ($bandera==TRUE){		
		//VALIDAMOS QUE NO EXISTA OTRO REGISTRO EN LA MISMA FECHA		
		$sql = "SELECT * FROM Asistencia WHERE No_Empleado = '". $empleado ."' and Fecha_Entrada = '".$fecha."' and Tipo = 'ORDINARIO'";
        //echo $sql;
        $rs = odbc_exec( $conn, $sql );
        if ( !$rs ) { 
          exit( "Error en la consulta SQL" ); 
        }    
        while ( odbc_fetch_row($rs) ) { 
          $folio = odbc_result($rs, 'ASISTENCIA_ID');
		  $bandera2=TRUE;		  		  	      	  	  
        }
		if ($bandera2==TRUE){
			$notificacion = "errorEntrada";			
		}else{
			$sql = "INSERT INTO Asistencia VALUES ('".$empleado."','".$nombre."','".$fecha."','".$hora."','','','ORDINARIO', 'INCOMPLETO', '".$ip_maquina."')";		
        	//echo $sql;
        	$rs = odbc_exec( $conn, $sql );
        	if ( !$rs ) {
				//REGISTRO DUPLICADO			       
				$notificacion = "errorEntrada";
        	}else{
				$notificacion = "success";		
			}
			
		}//IF BANDERA2	
	}else{
		$notificacion = "errorInvalido"; 
	}//IF BANDERA
}
//---------------------------------------------------------------------------SALIDA---------------------------------------------------------------------------------
if (isset($_REQUEST['salida'])) {
	$empleado = $_POST["empleado2"];	
	$password = $_POST["password2"];
	$hora=  date('H:i:s', time());
	$periodo = date('Y').date("m");
		
	$sql = "SELECT * FROM CatEmpleados WHERE NoEmp = '". $empleado ."' and Password = '".$password."'";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta SQL" ); 
    }    
    while ( odbc_fetch_row($rs) ) { 
      $nombre = odbc_result($rs, 'Empleado');
	  $bandera=TRUE;	  	  
    }
	
	if ($bandera==TRUE){	
		//VALIDAMOS QUE EL USUARIO TENGA UNA ENTRADA REGISTRADA
		$sql = "SELECT * FROM Asistencia WHERE No_Empleado = '". $empleado ."' and Estatus = 'INCOMPLETO' and Tipo = 'ORDINARIO'";
        //echo $sql;
        $rs = odbc_exec( $conn, $sql );
        if ( !$rs ) { 
          exit( "Error en la consulta SQL" ); 
        }    
        while ( odbc_fetch_row($rs) ) { 
          $folio = odbc_result($rs, 'ASISTENCIA_ID');
		  $bandera2=TRUE;		  		  	      	  	  
        }
		if ($bandera2==TRUE){
			$sql = "UPDATE Asistencia SET FECHA_SALIDA = '".$fecha."', HORA_SALIDA = '".$hora."', ESTATUS = 'COMPLETO' WHERE ASISTENCIA_ID = '".$folio."'";
        	//echo $sql;
        	$rs = odbc_exec( $conn, $sql );				
        	if ( !$rs ) {
				//REGISTRO DUPLICADO
				//header("Location: asistencia.php");           
        	}else{
				$notificacion = "success";		
			}						
		}else{
			$notificacion = "errorRegistro";			
		}//IF BANDERA2
		
		
		
		
		//******************************RUD*********************************		
		//OBTENEMOS LA HORA DE ENTRADA
	    $sql = "SELECT * FROM Asistencia WHERE NO_EMPLEADO = '". $empleado ."' ";
       //echo $sql;
       $rs = odbc_exec( $conn, $sql );
       if ( !$rs ) { 
        exit( "Error en la consulta SQL" ); 
       }    
       while ( odbc_fetch_row($rs) ) { 
        $entrada = odbc_result($rs, 'HORA_ENTRADA');	   	  
      }
	  
	  $dif=date("H:i:s", strtotime("00:00:00") + strtotime($hora) - strtotime($entrada));	  
	  //echo $dif & " ";
	  
	  $horaDif = substr($dif,0,2);
	  $minutoDif = substr($dif,3,2);
	  $total = $horaDif + ($minutoDif/60);
	     
	  //INSERTAMOS EN LA TABLA RUD	
	  $sql = "INSERT INTO Rud VALUES ('".$empleado."','".$nombre."','".$entrada."','".$hora."','Hrs','".$total."','0','".$total."','ORDINARIO','MANO DE OBRA','".$fecha."','COMPLETO','".$periodo."','')";
        //echo $sql;
        $rs = odbc_exec( $conn, $sql );
        if ( !$rs ) {
			//REGISTRO DUPLICADO
			//header("Location: asistencia.php");           
        }
	}else{
		$notificacion = "errorInvalido";  
	}//IF
}
//--------------------------------------------------------------------ENTRADA EXTRA---------------------------------------------------------------------
if (isset($_REQUEST['entradaextra'])) {
	$empleado = $_POST["empleado3"];	
	$password = $_POST["password3"];
	$hora=  date('H:i:s', time());
		
	$sql = "SELECT * FROM CatEmpleados WHERE NoEmp = '". $empleado ."' and Password = '".$password."'";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta SQL" ); 
    }    
    while ( odbc_fetch_row($rs) ) { 
      $nombre = odbc_result($rs, 'Empleado');
	  $bandera=TRUE;	  	  
    }
	
	if ($bandera==TRUE){
		//VALIDAMOS QUE EL USUARIO TENGA UNA ENTRADA REGISTRADA
		$sql = "SELECT * FROM Asistencia WHERE No_Empleado = '". $empleado ."' and Estatus = 'INCOMPLETO' and Tipo = 'EXTRAORDINARIO'";
        //echo $sql;
        $rs = odbc_exec( $conn, $sql );
        if ( !$rs ) { 
          exit( "Error en la consulta SQL" ); 
        }    
        while ( odbc_fetch_row($rs) ) { 
          $folio = odbc_result($rs, 'ASISTENCIA_ID');
		  $bandera2=TRUE;		  		  	      	  	  
        }
		if ($bandera2==TRUE){
			$notificacion = "errorEntrada";			
		}else{
			$sql = "INSERT INTO Asistencia VALUES ('".$empleado."','".$nombre."','".$fecha."','".$hora."','','','EXTRAORDINARIO', 'INCOMPLETO', '".$ip_maquina."')";		
        	//echo $sql;
        	$rs = odbc_exec( $conn, $sql );
        	if ( !$rs ) {
				//REGISTRO DUPLICADO			       
				$notificacion = "errorEntrada";
        	}else{
				$notificacion = "success";		
			}			
		}//IF BANDERA2					
	}else{
		$notificacion = "errorInvalido";  
	}//IF BANDERA
}
//------------------------------------------------------------------------SALIDA EXTRA------------------------------------------------------------------------------
if (isset($_REQUEST['salidaextra'])) {
	$empleado = $_POST["empleado4"];	
	$password = $_POST["password4"];
	$hora=  date('H:i:s', time());
		
	$sql = "SELECT * FROM CatEmpleados WHERE NoEmp = '". $empleado ."' and Password = '".$password."'";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta SQL" ); 
    }    
    while ( odbc_fetch_row($rs) ) { 
      $nombre = odbc_result($rs, 'Empleado');
	  $bandera=TRUE;	  	  
    }
	
	if ($bandera==TRUE){		
		//VALIDAMOS QUE EL USUARIO TENGA UNA ENTRADA REGISTRADA
		$sql = "SELECT * FROM Asistencia WHERE No_Empleado = '". $empleado ."' and Estatus = 'INCOMPLETO' and Tipo = 'EXTRAORDINARIO'";
        //echo $sql;
        $rs = odbc_exec( $conn, $sql );
        if ( !$rs ) { 
          exit( "Error en la consulta SQL" ); 
        }    
        while ( odbc_fetch_row($rs) ) { 
          $folio = odbc_result($rs, 'ASISTENCIA_ID');
		  $bandera2=TRUE;		  		  	      	  	  
        }
		if ($bandera2==TRUE){
			$sql = "UPDATE Asistencia SET FECHA_SALIDA = '".$fecha."', HORA_SALIDA = '".$hora."', ESTATUS = 'COMPLETO' WHERE ASISTENCIA_ID = '".$folio."'";
        	//echo $sql;
        	$rs = odbc_exec( $conn, $sql );				
        	if ( !$rs ) {
				//REGISTRO DUPLICADO
				//header("Location: asistencia.php");           
        	}else{
				$notificacion = "success";		
			}						
		}else{
			$notificacion = "errorRegistro";			
		}//IF BANDERA2			
	}else{
		$notificacion = "errorInvalido"; 
	}//IF BANDERA
}

//--------------------------------------AUTOCOMPLETAR---------------------------------------------------------

$nombre = array();
$x=0;

$sql = "select * from CatEmpleados";
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

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
body {
	background: url(images/asistencia.jpg);
 background-size: cover;
        -moz-background-size: cover;
        -webkit-background-size: cover;
        -o-background-size: cover;
.NEG_WHITE {
	font-weight: bold;
	color: #FFF;
}
</style>
<title>Control de Asistencia SAC</title>	
   
    <link rel='stylesheet' href='http://s.codepen.io/assets/reset/reset.css'>
    <link href="css/bootstrap.min.css" rel="stylesheet" />  
    <link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />
    <link type="text/css" rel="Stylesheet" href="jqwidgets/styles/jqx.base.css" />
    <script type="text/javascript" src="scripts/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdata.js"></script> 
    <script type="text/javascript" src="jqwidgets/jqxinput.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxpanel.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxmaskedinput.js"></script> 
    <script type="text/javascript" src="jqwidgets/jqxnumberinput.js"></script>      
    <script type="text/javascript" src="scripts/demos.js"></script>   
    <script type="text/javascript" src="jqwidgets/jqxfileupload.js"></script> 
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="scripts/demos.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxnotification.js"></script>
    <!-- PNotify -->
	<script type="text/javascript" src="src/pnotify.core.js"></script>
	<link href="src/pnotify.core.css" rel="stylesheet" type="text/css" />
	<link href="src/pnotify.brighttheme.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="src/pnotify.buttons.js"></script>
	<link href="src/pnotify.buttons.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="src/pnotify.confirm.js"></script>
	<script type="text/javascript" src="src/pnotify.nonblock.js"></script>
	<script type="text/javascript" src="src/pnotify.desktop.js"></script>
	<script type="text/javascript" src="src/pnotify.history.js"></script>
	<link href="src/pnotify.history.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="src/pnotify.callbacks.js"></script>
	<script type="text/javascript" src="src/pnotify.reference.js"></script>
	<link href="src/pnotify.picon.css" rel="stylesheet" type="text/css" />   
    
 	<script type="text/javascript">
	$(document).ready(function(){
		
		//CONFIGURACION DE LAS NOTIFICACIONES		
		var Tipo = '<?php echo $notificacion;  ?>';		
		PNotify.prototype.options.styling = "bootstrap3";
		
		 switch (Tipo) {
			 case 'success':
			 	new PNotify({
					title: "Exito",
					text: "Registro guardado.",
					delay: 2000,
					animation: 'fade', //'show', 'slide', animation: { effect_in: 'show', effect_out: 'slide' }				
					nonblock: {
						nonblock: false
					},
					type: "success"
				});					
        		break;
				case 'errorEntrada':
			 	new PNotify({
					title: "Error",
					text: "Registre la Salida. ",
					delay: 2000,
					animation: 'fade', //'show', 'slide', animation: { effect_in: 'show', effect_out: 'slide' }				
					nonblock: {
						nonblock: false
					},
					type: "error"
				});					
        		break;
				case 'errorInvalido':
			 	new PNotify({
					title: "Error",
					text: "Datos Invalidos. ",
					delay: 2000,
					animation: 'fade', //'show', 'slide', animation: { effect_in: 'show', effect_out: 'slide' }				
					nonblock: {
						nonblock: false
					},
					type: "error"
				});					
        		break;
				case 'errorRegistro':
			 	new PNotify({
					title: "Error",
					text: "Registro Duplicado. ",
					delay: 2000,
					animation: 'fade', //'show', 'slide', animation: { effect_in: 'show', effect_out: 'slide' }				
					nonblock: {
						nonblock: false
					},
					type: "error"
				});			 
		 }//Swicth
		
		
		//---------------------------------ACCESO SUPERVISOR-----------------------------
		$("#ingresosupervisor").click(function () {
			var usuario = $("#usuario").val();
			var password = $("#passw").val();
			$.post("consulta.php", { user: usuario, pass: password }, function(data){
				$("#valido").html(data);
			});
			/*
			document.getElementById('tabla1').style.display = 'block';	
			document.getElementById('tablalogin').style.display = 'none';*/
        });
		//NOMBRE DE EMPLEADO
		$('#empleado1').on('blur', function (event){
		     var elegido = $('#empleado1').val();
			  $.post("consulta.php", { nombre: elegido }, function(data){
				  $("#nombre1").html(data);
		      });//FIN POST
		});//Fin Funcion BLUR
		
		$('#empleado2').on('blur', function (event){
		     var elegido = $('#empleado2').val();
			  $.post("consulta.php", { nombre: elegido }, function(data){
				  $("#nombre2").html(data);
		      });//FIN POST
		});//Fin Funcion BLUR
		
		$('#empleado3').on('blur', function (event){
		     var elegido = $('#empleado3').val();
			  $.post("consulta.php", { nombre: elegido }, function(data){
				  $("#nombre3").html(data);
		      });//FIN POST
		});//Fin Funcion BLUR
		
		$('#empleado4').on('blur', function (event){
		     var elegido = $('#empleado4').val();
			  $.post("consulta.php", { nombre: elegido }, function(data){
				  $("#nombre3").html(data);
		      });//FIN POST
		});//Fin Funcion BLUR
		
   });//$(document).ready 
</script>
 
<script>		
		function Alerta() {			
			PNotify.prototype.options.styling = "bootstrap3";			
			new PNotify({
				title: "Exito",
				text: "Registro guardado.",
				delay: 2000,
				animation: 'fade', //'show', 'slide', animation: { effect_in: 'show', effect_out: 'slide' }				
				nonblock: {
					nonblock: false
				},
				type: "success",//"info", "success", "error"
				//SE DISPARA SI CIERRAN LA VENTANA
				/*before_close: function(PNotify){					
					PNotify.update({
						title: PNotify.options.title+" - Enjoy your Stay",
						before_close: null
					});
					PNotify.queueRemove();
					return false;
				}*/
			});			
		}

function Limpiar(){
	var x = '';	
	document.getElementById("formulario1").reset();
	document.getElementById("formulario2").reset();
	document.getElementById("formulario3").reset(); 
	document.getElementById("formulario4").reset();
	document.getElementById("formulario5").reset();  
	
	$("#nombre1").html('<strong><span></span></strong>');
	$("#nombre2").html('<strong><span></span></strong>');
	$("#nombre3").html('<strong><span></span></strong>');		
}

function salir(){
	document.getElementById('tabla1').style.display = 'none';
}
</script>
</head>
<body>
<p><p>
<CENTER>
 <iframe src="http://www.zeitverschiebung.net/clock-widget-iframe?language=es&timezone=America%2FMexico_City" width="100%" height="130" frameborder="0" seamless></iframe>
 <p>&nbsp;</p>
 </div>
</CENTER>
<center>
<table width="213" height="216" border="1">
  <tbody>
    <tr>
      <td width="87" height="96" align="center">
        <!-- Boton Modal Entrada --><center>
  		<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".ModalEntrada"> <img src="images/entrar.png" width="48" height="50" /> <br />
  		*  Entrada  *</button></td>
  		<td width="8"><td width="77" height="96" align="center">
   	    <!-- Boton Modal Salida --><center>
   		<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".ModalSalida"> <img src="images/salida.png" width="48" height="50" /> <br />
    	*  Salida  *</button></td>
  		<td width="13"></tr>
    <tr>
      <td height="55" colspan="3">
     	<!-- Boton Modal Extra -->
  		<center><button type="button" class="btn btn-primary" data-toggle="modal" data-target=".ModalExtra"> 
  		<p><img src="images/extra.png" width="48" height="50" /><br />E x t r a o r d i n a r i o
        </button></center></td>
    </tr>
    <tr height="12" colspan="3"></tr>
    <tr>
      <td height="55" colspan="3">
     	<!-- Boton Modal Supervisor 
        <center><button type="button" class="btn btn-primary" data-toggle="modal" data-target=".ModalSupervisor"> 
  		<p><img src="images/supervisor.png" width="50" height="52" /><br />* S u p e r v i s o r *
        </button></center>--></td>
    </tr>
  </tbody>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p><br>
  <left></left><br><br><br>
</p>
<p><br>
  <br>
<strong></strong></p>


<!-- INICIO DE MODALES -->

<!-- Modal de ENTRADA  -->
<form id="formulario1" action="Asistencia.php" method="post" enctype="multipart/form-data" >
<div class="modal fade ModalEntrada" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
     <h4 class="modal-title" id="myModalLabel">REGISTRO DE ENTRADA</h4>
      </div>
      <div class="modal-body">
    <table class="table table-bordered" border="2" align="center">
  <tr>
    <td width="200"><img src="images/gafete.png" alt="" height="281" weight="300"/></td>
    <td widht="100"><p>
      <label for="name"><script type="text/javascript" src="http://localtimes.info/clock.php?continent=North America&country=Mexico&province=Jalisco&city=Guadalajara&cp1_Hex=000000&cp2_Hex=FFFFFF&cp3_Hex=000000&fwdt=200&ham=0&hbg=0&hfg=0&sid=0&mon=0&wek=0&wkf=0&sep=0&widget_number=1024"></script>
      <p></p></label>          
        <label for="name">Num. de empleado</label>
        <br />
        <input id="empleado1" class="input" name="empleado1" type="number" value="" size="30" />
        <p>
          <label for="contrasena">Contrase&ntilde;a:</label>
          <br />
          <input id="password" class="input" name="password" type="password" value="" size="20" />
     
        </td>
  </tr>
  <tr>
    <td colspan="2"><center><strong><span name="nombre1" id="nombre1"></span></strong></center></td>
  </tr>
</table>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="Limpiar()">CANCELAR</button>
        <button type="submit" class="btn btn-primary" name="entrada" id="entrada">GRABAR</button>       
      </div>
  </div>
    </div>
  </div>
</form>     

  
<!-- Modal de SALIDA -->
<form id="formulario2" action="Asistencia.php" method="post" enctype="multipart/form-data" >
<div class="modal fade ModalSalida" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">REGISTRO DE SALIDA</h4>
      </div>
      <div class="modal-body">
<table  class="table table-bordered"  bordercolor="#000000">
  <tr>
    <td width="200"><img src="images/gafete.png" alt="" height="281" weight="300"/></td>
    <td><p>
      <label for="name"><script type="text/javascript" src="http://localtimes.info/clock.php?continent=North America&country=Mexico&province=Jalisco&city=Guadalajara&cp1_Hex=000000&cp2_Hex=FFFFFF&cp3_Hex=000000&fwdt=200&ham=0&hbg=0&hfg=0&sid=0&mon=0&wek=0&wkf=0&sep=0&widget_number=1024"></script>
      <p>
        <label for="name">Num. de empleado</label>
        <br />
        <input id="empleado2" class="input" name="empleado2" type="number" value="" size="30" />
        <p>
          <label for="contrasena1">Contrase&ntilde;a:</label>
          <br />
          <input id="password2" class="input" name="password2" type="password" value="" size="20" />
       
        </td>
  </tr>
  <tr>
    <td colspan="2"><center><strong><span name="nombre2" id="nombre2"></span></strong></center></td>
  </tr>
</table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="Limpiar()">CANCELAR</button>
        <button type="submit" class="btn btn-primary" name="salida" id="salida">GRABAR</button>
      </div>
    </div>
  </div>
</div>
</form>



<!-- Modal de EXTRAORDINARIO  -->

<div class="modal fade ModalExtra" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">REGISTRO DE JORNADA EXTRAORDINARIA</h4>
      </div>
      <div class="modal-body">
       
   <script type="text/javascript" src="http://localtimes.info/clock.php?continent=North America&country=Mexico&province=Jalisco&city=Guadalajara&cp1_Hex=000000&cp2_Hex=FFFFFF&cp3_Hex=000000&fwdt=200&ham=0&hbg=0&hfg=0&sid=0&mon=0&wek=0&wkf=0&sep=0&widget_number=1024"></script>
     
      
     <table width="468" class="table table-hover" height="379" border="1" bordercolor="#000000" align="center">
 <!-- REGISTRO ENTRADA  -->
 <form id="formulario3" action="Asistencia.php" method="post" enctype="multipart/form-data" >
  <tr align="center">
    <td width="200"><center><IMG src="images/ENTRANCE.png" /></center></td>
    <td width="200"><p>
      <label for="name3">Num. de empleado</label>
      <br />
      <input id="empleado3" class="input" name="empleado3" type="number" value="" size="30" />
    </p>
      <p>
        <label for="contrasena3">Contrase&ntilde;a:</label>
        <br />
        <input id="password3" class="input" name="password3" type="password" value="" size="20" />
    </p>
    <P>
    <button type="button" class="btn btn-default" data-dismiss="modal" onclick="Limpiar()">CANCELAR</button>
    <button type="submit" class="btn btn-primary" name="entradaextra" id="entradaextra">GRABAR</button> 
    
    </td>    
  </tr>
  </form>
  <tr>
  </tr>
  <!-- REGISTRO SALIDA  -->
 <form id="formulario4" action="Asistencia.php" method="post" enctype="multipart/form-data" >
  <tr align="center">
    <td width="200"><center><IMG src="images/SALIDAS.png" /></center></td>
    <td width="200"><p>
      <label for="name3">Num. de empleado</label>
      <br />
      <input id="empleado4" class="input" name="empleado4" type="number" value="" size="30" />
    </p>
      <p>
        <label for="contrasena4">Contrase&ntilde;a:</label>
        <br />
        <input id="password4" class="input" name="password4" type="password" value="" size="20" />
    </p>
    <P>
    <button type="button" class="btn btn-default" data-dismiss="modal" onclick="Limpiar()">CANCELAR</button>
    <button type="submit" class="btn btn-primary" name="salidaextra" id="salidaextra">GRABAR</button>    
    </td>
  </tr>
  </form>
  <tr>
    <td colspan="2"><center><strong><span name="nombre3" id="nombre3"></span></strong></center></td>
  </tr>
</table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar Ventana</button>      
      </div>
    </div>
  </div>
</div>


</body>
</html>
