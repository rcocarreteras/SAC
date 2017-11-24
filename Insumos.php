<?php
require_once('Connections/sac2.php'); 
header( 'Content-type: text/html; charset=iso-8859-1' );
date_default_timezone_set('America/Mexico_City');
//print_r($_POST);

$fecha = date('Y-m-d');
//$hora = date("H:i:s");
$periodo = date('Y').date("m");

//echo $hora;
//$fecha = date('Y-m-j');

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
$tramo = $_SESSION['S_Tramo'];
$subtramo = $_SESSION['S_Subtramo'];
//--------------------------------------GUARDAR INSUMO-----------------------------------------------------------
if (isset($_REQUEST['inicio_insumo'])) {
  $tramo1 = $_POST["insumo1"];
  	//INSUMOS
	 for ($x = 1; $x <= 5; $x++) {	  
	  if ($_POST["ins".$x.""] <> ""){	
	    $ins = $_POST["ins".$x.""]; 	
	    $cant = $_POST["cant".$x.""]; 		
		
		$sql = "SELECT * FROM CatInsumos WHERE DescIns = '".$ins."'";
 		 //echo $sql;
		 $rs = odbc_exec( $conn, $sql );
  			if ( !$rs ) { 
    		exit( "Error en la consulta SQL" ); 
  		}    
 		 while ( odbc_fetch_row($rs) ) { 
  		  $numero = odbc_result($rs, 'CvIns');
		  $unidad = odbc_result($rs, 'Unid');
 		 }
	$sql = "INSERT INTO Rud VALUES ('".$numero."','".$ins."','".$cant."','','".$unidad."','".$cant."','0','0','ORDINARIO','INSUMO','".$fecha."','INCOMPLETO','".$periodo."','".$tramo1."','','','".$_SESSION['S_Subtramo']."')";
  			//echo $sql; 
			$rs = odbc_exec( $conn, $sql );
			  if ( !$rs ) {
				  //REGISTRO DUPLICADO
			      header("Location: insumos.php");  
    			  //exit( "Error en la consulta Rud" ); 
  			} 			
	    $ins=""; 
		$numero="";
     }//FIN IF   
   }//FIN FOR*/
}

if (isset($_REQUEST['fin'])) {
	$folio = $_POST["folio"];
	$fecha2 = $_POST["fecha"];
	$fin = $_POST["entrada"];
			
	//OBTENEMOS EL INICIO
	$sql = "SELECT * FROM Rud WHERE FOLIO = '". $folio ."' and FECHA = '".$fecha2."' ";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
     exit( "Error en la consulta SQL" ); 
    }    
    while ( odbc_fetch_row($rs) ) { 
     $inicio = odbc_result($rs, 'INICIO');
     $subtramo = odbc_result($rs, 'SUBTRAMO');	   	  
    }
	
	$dif=$inicio - $fin;		
	
	$sql = "UPDATE Rud SET FIN = '".$fin."', ESTATUS = 'COMPLETO', CANTIDAD = '".$dif."', DIFERENCIA = '".$dif."' WHERE FOLIO = '".$folio."' AND FECHA = '".$fecha2."' AND TIPO = 'ORDINARIO' AND ESTATUS = 'INCOMPLETO' AND SUBTRAMO = '".$_SESSION['S_Subtramo']."'";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) {
	  //REGISTRO DUPLICADO
		header("Location: insumos.php");           
    }   
}
//********************************************RUD************************************************************
$bandera=FALSE;
$sql = "SELECT * FROM Rud WHERE DESCRIPCION='INSUMO' AND ESTATUS='INCOMPLETO' AND FECHA < '".$fecha."' AND TRAMO in ('".$_SESSION['S_Tramo']."')";
$rs = odbc_exec( $conn, $sql );
if ( !$rs ) { 
	exit( "Error en la consulta SQL" ); 
}    
while ( odbc_fetch_row($rs) ) {
	$rudID = odbc_result($rs, 'RUD_ID');
	$rudCantidad = odbc_result($rs, 'CANTIDAD');
	
	$sql2 = "UPDATE Rud SET CANTIDAD_AD = '".$rudCantidad."', DIFERENCIA = '0', ESTATUS = 'COMPLETO' WHERE RUD_ID = '".$rudID."' AND TRAMO in ('".$_SESSION['S_Tramo']."')";
    //echo $sql2;
    $rs2 = odbc_exec( $conn, $sql2 );
   	if ( !$rs2 ) {
	    exit( "Error en la consulta SQL" );            
   	}
}
//--------------------------------------AUTOCOMPLETAR---------------------------------------------------------
$insumo = array();
$x=0;

$sql = "select * from CatInsumos";
  //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }    
  while ( odbc_fetch_row($rs) ) { 
    $insumo[$x] = "\"" .odbc_result($rs, 'DescIns'). "\",";	
	$x++;    
}//While
$insumo[$x-1] = str_replace(",","",$insumo[$x-1]);	
//---------------------------------------------------------FILTRO-----------------------------------
if (isset($_REQUEST['filtrar'])) {
  $tramo = $_POST['tramo'];
  $_SESSION['S_Tramo'] = $tramo; 
  $subtramo = $_POST['subtramo'];
  $_SESSION['S_Subtramo'] = $subtramo; 
  
  //echo $_SESSION['S_Subtramo'];
}	
//---------------------------------------------GRID-----------------------------------------------------------
$x=0;
$filas = array();
$sql = "SELECT * FROM Rud WHERE DESCRIPCION='INSUMO' AND ESTATUS<>'TERMINADO' AND TRAMO in ('".$tramo."') AND FECHA = '".$fecha."' AND SUBTRAMO in ('".$_SESSION['S_Subtramo']."') ORDER BY ESTATUS DESC";
 //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }   //OBTENEMOS TODA LA FILA 
 while ($row = odbc_fetch_array($rs)) {    
  $filas[$x] = array_map('utf8_encode',$row);    
   $x++;    
 }//While
$datos =  $filas; 
//echo json_encode($datos);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
body {
	background: url(images/insumos_old.jpg);
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
    <link rel="stylesheet" href="css/styl.css">
    <link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />
    <script type="text/javascript" src="scripts/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdata.js"></script> 
    <script type="text/javascript" src="jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxscrollbar.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxlistbox.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdropdownlist.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxmenu.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxgrid.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxgrid.sort.js"></script> 
    <script type="text/javascript" src="jqwidgets/jqxgrid.pager.js"></script> 
    <script type="text/javascript" src="jqwidgets/jqxgrid.columnsresize.js"></script> 
    <script type="text/javascript" src="jqwidgets/jqxgrid.selection.js"></script> 
    <script type="text/javascript" src="jqwidgets/jqxinput.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxpanel.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxmaskedinput.js"></script> 
    <script type="text/javascript" src="jqwidgets/jqxnumberinput.js"></script>      
    <script type="text/javascript" src="scripts/demos.js"></script>    
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxtabs.js"></script>
    
 <script type="text/javascript">
 $(document).ready(function () {
			

	 		//CREAMOS EL TAB			
            $('#tabsWidget').jqxTabs({ width: 700, height: 300, position: 'top'});           
            $('#tabsWidget').jqxTabs('focus');	 
			
			//-----------------------TABLA----------------------------------------------
			 var data =  <?php echo json_encode($datos); ?>;				    
		    var source =
            {
                datafields: [
                    { name: 'AVANCE_ID', type: 'string' },	
					{ name: 'FOLIO', type: 'string' },
					{ name: 'NOMBRE', type: 'string' },	
					{ name: 'ESTATUS', type: 'string' },				                 
                    { name: 'INICIO', type: 'string' },				                 
                    { name: 'FIN', type: 'string' },		                 
                    { name: 'UNIDAD', type: 'string' },					                 
                    { name: 'FECHA', type: 'string' }								
                ],
                localdata: data 
            };
            var dataAdapter = new $.jqx.dataAdapter(source);

            $("#tab1").jqxGrid(
            {
                width: 670,
                height: 250,
                source: dataAdapter,
                
                keyboardnavigation: false,
                columns: [
				  { text: 'Folio', dataField: 'FOLIO', width: 100 }, 
				  { text: 'Nombre', dataField: 'NOMBRE', width: 250 }, 
                  { text: 'Inicio', dataField: 'INICIO', width: 100 },
                  { text: 'Fin', dataField: 'FIN', width: 100 },				  
                  { text: 'Estatus', dataField: 'ESTATUS', width: 100 }		  
                ]
            });		
			$('#tab1').on('rowclick', function (event){					
				$("#entradains").modal('show'); 			        			
            });
			
			$("#tab1").on('rowselect', function (event) {			
				var insumo = event.args.row.NOMBRE;		
				var fecha = event.args.row.FECHA;			
				var folio = event.args.row.FOLIO;			
				var cantidad = event.args.row.INICIO;		
				var unidad = event.args.row.UNIDAD;		
				var estatus = event.args.row.ESTATUS;
				
				//alert(estatus);
				
				if (estatus == 'INCOMPLETO'){
					$("#entrada").jqxInput({height: 15, width: 50, minLength: 1});
					$("#entrada").css('background-color' , '#FFFFFF');
					$('#entrada').removeAttr('readonly');
					$("#salida").jqxInput({value: cantidad, height: 15, width: 50, minLength: 1});
					$("#salida").css('background-color' , '#DEDEDE');
					$("#folio").jqxInput({value: folio, height: 15, width: 90, minLength: 1});
					$("#folio").css('background-color' , '#DEDEDE');
					$("#fecha").jqxInput({value: fecha, height: 15, width: 110, minLength: 1});
					$("#fecha").css('background-color' , '#DEDEDE');
					$("#insu").jqxInput({value: insumo, height: 15, width: 250, minLength: 1});
					$("#insu").css('background-color' , '#DEDEDE');
					$("#unidad").jqxInput({value: unidad, height: 15, width: 50, minLength: 1});
					$("#unidad").css('background-color' , '#DEDEDE');					
				}else{
					$("#entrada").jqxInput({height: 15, width: 50, minLength: 1});
					$("#entrada").css('background-color' , '#DEDEDE');
					$('#entrada').attr('readonly', 'true');
					$("#salida").jqxInput({value: cantidad, height: 15, width: 50, minLength: 1});
					$("#salida").css('background-color' , '#DEDEDE');
					$("#folio").jqxInput({value: folio, height: 15, width: 90, minLength: 1});
					$("#folio").css('background-color' , '#DEDEDE');
					$("#fecha").jqxInput({value: fecha, height: 15, width: 110, minLength: 1});
					$("#fecha").css('background-color' , '#DEDEDE');
					$("#insu").jqxInput({value: insumo, height: 15, width: 250, minLength: 1});
					$("#insu").css('background-color' , '#DEDEDE');
					$("#unidad").jqxInput({value: unidad, height: 15, width: 50, minLength: 1});
					$("#unidad").css('background-color' , '#DEDEDE');							
				}
				
				
			});		
			
			//AUTOCOMPLETAR
			var insumo = new Array(<?php  
	        foreach ($insumo as &$valor) {
              echo $valor;
            }		
	        ?>);	
			//MAQUINARIA E INSUMOS
			for (var i = 1; i <= 5; i++) {
				$("#ins" + i).jqxInput({placeHolder: "Elige un insumo", height: 15, width: 250, minLength: 1, source: insumo});	
				$("#cant" + i).jqxInput({height: 15, width: 50, minLength: 1});
				$("#uni" + i).jqxInput({height: 15, width: 50, minLength: 1});
				$("#uni" + i).css('background-color' , '#DEDEDE');
			}
			
			//UNIDAD1
			$('#ins1').on('blur', function (event){
			     var elegido = $('#ins1').val();
				  elegido=$(this).val();
				  $.post("consulta.php", { insumo: elegido }, function(data){
					  var token = data.split();
				      var variable = token[0].split('*');
					      $('#uni1').val(variable[1]);
			      });
			});
			
			//UNIDAD2
			$('#ins2').on('blur', function (event){
			     var elegido = $('#ins2').val();
				  elegido=$(this).val();
				  $.post("consulta.php", { insumo: elegido }, function(data){
					  var token = data.split();
				      var variable = token[0].split('*');
					      $('#uni2').val(variable[1]);
			      });
			});
			
			//UNIDAD3
			$('#ins3').on('blur', function (event){
			     var elegido = $('#ins3').val();
				  elegido=$(this).val();
				  $.post("consulta.php", { insumo: elegido }, function(data){
					  var token = data.split();
				      var variable = token[0].split('*');
					      $('#uni3').val(variable[1]);
			      });
			});
			
			//UNIDAD4
			$('#ins4').on('blur', function (event){
			     var elegido = $('#ins4').val();
				  elegido=$(this).val();
				  $.post("consulta.php", { insumo: elegido }, function(data){
					  var token = data.split();
				      var variable = token[0].split('*');
					      $('#uni4').val(variable[1]);
			      });
			});
			
			//UNIDAD5
			$('#ins5').on('blur', function (event){
			     var elegido = $('#ins5').val();
				  elegido=$(this).val();
				  $.post("consulta.php", { insumo: elegido }, function(data){
					  var token = data.split();
				      var variable = token[0].split('*');
					      $('#uni5').val(variable[1]);
			      });
			});
			
			$("#tramo").click(function(){				    			
				$("#tramo option:selected").each(function () {
				 elegido=$(this).val();
			     $("#insu1").text(elegido);
				 $("#insumo1").val(elegido);
				 //alert(elegido);
              });				 
			});
			
			$("#tramo").click(function () {
   		      $("#tramo option:selected").each(function () {				 
			     elegido=$(this).val();							 					 
			     $.post("consultaAvanceDiario.php", { tramoAd: elegido }, function(data){									 
			     $("#subtramo").html(data);			    
			   });			
              });
            }); 
			
 });//Fin document.ready
 $(window).load(function() {
	 $("#tramo").click();
	 

 });//$(window).load
 
 
 $('#ModalEntrada').on('shown.bs.modal', function () {
  $('#myInput').focus()
})
 $('#ModalSalida').on('shown.bs.modal', function () {
  $('#myInput').focus()
})
 $('#ModalExtra').on('shown.bs.modal', function () {
  $('#myInput').focus()
})
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
<body class="default">
<?php if ($_SESSION['S_Privilegios'] == 'SOBRESTANTE'){ ?>
<div id='cssmenu'>
<ul>
   <li><a href='Inicio.php'><span>Inicio</span></a></li>
   <li><a href='#'><span>Cat&aacute;logos</span></a>
      <ul id="catalogos">
         <li><a href='catconceptos.php'><span>Conceptos</span></a>
         <li><a href='catempleados.php'><span>Empleados</span></a>
         <li><a href='catmaquinaria.php'><span>Maquinaria</span></a>
         <li><a href='catins.php'><span>Insumos</span></a>                  
         </li>
      </ul>
   </li>
   <li><a href='#'><span>Avance de Obra</span></a>   
     <ul id="avance">
        <?php 
		 if($_SESSION['S_Privilegios'] == 'SOBRESTANTE'){
			?><li><a href='AvanceDiario.php'><span>Avance Diario</span></a> 			
			<?php }else{?> 
        <li><a href='AvanceDiario2.php'><span>Avance Diario</span></a>
        <?php } ?>              
     </ul>
   </li>
     <table width="500" border="0" align="right" style="'Oxygen Mono', Tahoma, Arial, sans-serif; font-size:15px; color: #58D3F7">
     <tr>
       <td width="150" height="30" align="right">Bienvenid@ :</td>
       <td width="200" align="center"><?php echo $_SESSION['S_Usuario']; ?></td>
       <td width="100" valign="bottom" class="btn btn-info">  
       <form id="salir" action="avanceDiario.php" method="post" enctype="multipart/form-data" >      
       <button  class="btn btn-info" border ="0" id="cerrar_sesion" name="cerrar_sesion">Cerrar Sesion</button> 
       </form>   
       </td>
     </tr>
   </table>
</ul>
</div>
<?php }else{ ?>
<div id='cssmenu'>
<ul>
   <li><a href='Inicio.php'><span>Inicio</span></a></li>
   <li><a href='#'><span>Cat&aacute;logos</span></a>
      <ul id="catalogos">
         <li><a href='catconceptos.php'><span>Conceptos</span></a>
         <li><a href='catempleados.php'><span>Empleados</span></a>
         <li><a href='catmaquinaria.php'><span>Maquinaria</span></a>
         <li><a href='catins.php'><span>Insumos</span></a>         
        <!-- <li><a href='catgeneral.php'><span>General</span></a> -->
         </li>
      </ul>
   </li>
   <li><a href='#'><span>Almacen</span></a>   
     <ul>
         <li><a href='#'><span>Contable</span></a>
         <li><a href='#'><span>Fisico</span></a>                     
     </ul>
   </li>
   <li><a href='#'><span>Avance de Obra</span></a>   
     <ul>
        <li><a href='progsemanal.php'><span>Prog. Semanal</span></a>
        <?php 
		 if($_SESSION['S_Privilegios'] == 'SOBRESTANTE' || $_SESSION['S_Privilegios'] == 'SUPERVISOR' ||  $_SESSION['S_Privilegios'] == 'JEFE DE TRAMO'){
			?><li><a href='AvanceDiario.php'><span>Avance Diario</span></a> 			
			<?php }else{?> 
        <li><a href='AvanceDiario2.php'><span>Avance Diario</span></a>
        <?php } ?>                   
     </ul>
   </li>
   <li><a href='#'><span>Contratos</span></a>   
     <ul>
         <li><a href='#'><span>OPEX</span></a>
         <li><a href='#'><span>CAPEX</span></a>                     
     </ul>
   </li>     
   <li><a href='#'><span>Presupuestos</span></a>   
     <ul>
         <li><a href='#'><span>OPEX</span></a>
         <li><a href='#'><span>CAPEX</span></a>                     
     </ul>
   </li>  
   <li class='last' id="asistencia"><a href='Asistencia.php' target="_blank"><span>Asistencia</span></a>
     <ul>
         <li><a href='EntradaManual.php'><span>Entrada Manual</span></a>                   
     </ul>
 </li>
    <li><a href='Maquinaria.php'><span>Maquinaria</span></a></li>
    <li class='active has-sub'><a href='Insumos.php'><span>Insumos</span></a></li> 
 
  
     <table width="500" border="0" align="right" style="'Oxygen Mono', Tahoma, Arial, sans-serif; font-size:15px; color: #58D3F7">
     <tr>
       <td width="150" height="30" align="right">Bienvenid@ :</td>
       <td width="200" align="center"><?php echo $_SESSION['S_Usuario']; ?></td>
       <td width="100" valign="bottom" class="btn btn-info">
       <form id="salir" action="avanceDiario.php" method="post" enctype="multipart/form-data" >             
          <button type="submit" class="btn btn-info" border ="0" id="cerrar_sesion" name="cerrar_sesion" > Cerrar Sesion</button> 
       </form> 
       </td>
     </tr>
   </table>
</ul>
</div>
<?php } ?>
<p>
<p>
<CENTER>
 <iframe src="http://www.zeitverschiebung.net/clock-widget-iframe?language=es&timezone=America%2FMexico_City" width="100%" height="130" frameborder="0" seamless></iframe>
 
 </div>
</CENTER>
<center>
<table width="256" height="106" border="1">
  <tbody>
    <tr>
      <td width="120" height="96">
        <!-- Boton Modal Entrada --><center>
  		<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".ModalEntrada"> <img src="images/salida_insumos.png" width="55" height="56" /> <br />
  		* Salida *</button></td>
    </tr>
  </tbody>
</table>
<center>
<br />
<br />
<table width="700" height="58" border="0" align="center">
    <tr>
  <td width="47%" height="54" valign="baseline" bgcolor="#FFFFFF">
   <img src="images/entrada_insumos.png" width="55" height="56" />
  <td width="53%" align="left" valign="baseline" scope="col" bgcolor="#FFFFFF">
   <form id="Filtro" action="Insumos.php" method="post" enctype="multipart/form-data" >  
    <table width="367" height="76" align="center">  
      <tr>
      <td width="82" height="22"><strong>Tramo:</strong></td>
      <td width="249"> <?php echo $_SESSION['S_Tramo']; ?></td>
      </tr>
      <tr>
      <td width="82"><strong>SubTramo:</strong></td>
      <td width="249"> 	<?php echo $_SESSION['S_Subtramo']; ?></td>
      </tr>
      <tr>
      <td height="22" colspan="2" align="right">
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#filtro_ins">Filtro</button></td>
      </tr>
	</table>
    </form>
  </td>  
 </tr> 
 </table>
</center>
<center>
<div id='jqxWidget'>
        <div id="tabsWidget">
            <ul style="margin-left: 30px;">
                <li>Insumos</li>                            
            </ul>
            <div>
              <center>
                 <div id="tab1"></div>  
              </center>               
         </div>            
</div>
</center>  



<!-- INICIO DE MODALES -->
<!-- Modal de SALIDA  -->
<form id="formulario" action="Insumos.php" method="post" enctype="multipart/form-data" >
<div class="modal fade ModalEntrada" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
     <h4 class="modal-title" id="myModalLabel">REGISTRO DE SALIDA</h4>
      </div>
      <div class="modal-body">
        <!--CUERPO-->
<table width="500" height="auto" border="0">
<tr>
<td><input type="hidden" id="insumo1" name="insumo1" /></td>
</tr>
<center><strong><span name="insu1" id="insu1"></span></strong></center>
<br />
<?php 
   for ($i = 1; $i <= 5; $i++) {
	   $x = $i + 200;
	   $z = $x + 1;
	   if ($i==1){
		   echo "<tr name='".$x."' id='".$x."'>
		            <td width='29'>".$i."</td>
                    <td width='150'><input type='text' id='ins".$i."' name='ins".$i."' accept-charset='utf-8'  autocomplete='off' /></td>
                    <td width='90' align='center'>Unidad</td>
                    <td width='30'><input type='text' id='uni".$i."' name='uni".$i."' accept-charset='utf-8' readonly  /></td>	
                    <td width='90' align='center'>Cantidad</td>
                    <td width='30'><input type='number' id='cant".$i."' name='cant".$i."' accept-charset='utf-8' step='any'  /></td>	
                    <td width='30'><a href=\"javascript:MostrarFilas('".$z."')\"><img id='sac".$x."' src='images/plus2.png' width='30' height='30'></a></td>			
                </tr>";
	   }else{
		   echo "<tr name='".$x."' id='".$x."' style='display:none;'> 
		            <td width='29'>".$i."</td>
                    <td width='150'><input type='text' id='ins".$i."' name='ins".$i."' accept-charset='utf-8'  autocomplete='off' /></td>
                    <td width='90' align='center'>Unidad</td>
                    <td width='30'><input type='text' id='uni".$i."' name='uni".$i."' accept-charset='utf-8' readonly /></td>	
                    <td width='90' align='center'>Cantidad</td>
                    <td width='30'><input type='number' id='cant".$i."' name='cant".$i."' accept-charset='utf-8' step='any'  /></td>	
                    <td width='30'><a href=\"javascript:MostrarFilas('".$z."')\"><img id='sac".$x."' src='images/plus2.png' width='30' height='30'></a></td>					
                </tr>";		  
	   }	   
   }	
?>
</table>         
        <!--FIN-->
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary" name="inicio_insumo" id="inicio_insumo">Grabar</button>
      </div>
  </div>
    </div>
  </div>
</form>     

  
<!-- MODAL DE ENTRADA -->
<form id="formulario" action="Insumos.php" method="post" enctype="multipart/form-data" >
<div class="modal fade entradains" id="entradains" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">REGISTRO DE ENTRADA</h4>
      </div>
      <div class="modal-body">
        <!--CUERPO-->
<table width="526" height="auto" border="0">
	<tr>
      <td width="71" height="33">&nbsp;</td>
      <td width="144"></td>	
   	  <td width='77'>&nbsp;</td>
        <td width='216' align="right">Fecha:           <input type='text' id='fecha' name='fecha' readonly="readonly" /></td>			
  </tr>
	<tr>
        <td height="33">Folio:</td>
        <td><input type='text' id='folio' name='folio' accept-charset='utf-8' readonly="readonly" /></td>	
    	<td width='77'>Nombre:</td>
        <td width='216'><input type='text' id='insu' name='insu' accept-charset='utf-8' readonly="readonly"  /></td>			
     </tr>
	<tr>
        <td height="33">Unidad:</td>
        <td><input type='text' id='unidad' name='unidad' accept-charset='utf-8' readonly="readonly" /></td>	
        <td height="33">Cantidad:</td>
        <td colspan="2"><input type='text' id='salida' name='salida' accept-charset='utf-8' readonly="readonly" />		Sobrante:	<input type='number' id='entrada' name='entrada' accept-charset='utf-8' value="0" /></td>
        <td width='216'></td>			
     </tr>     
</table>        
        <!--FIN-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary" name="fin" id="fin">Grabar</button>
      </div>
    </div>
  </div>
</div>
</form>

<!-- Modal de Filtro  -->
<form  action="Insumos.php" method="post" enctype="multipart/form-data" >
<div class="modal fade filtro_ins" id="filtro_ins" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Filtro</h4>
      </div>
      <div class="modal-body">
        <!--CUERPO-->
<table width="376" height="40" align="center">  
      <tr>
      <td width="58" height="34">Tramo:</td>
      <td width="281">
      <select class="form-control" name="tramo" id="tramo">
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
	   if ($_SESSION['S_Tramo']==$tramo)
		    $selected="selected";
	   else
			$selected=""; 
       echo "<option id='".$i."'".$selected.">".$tramo."</option>";
	    $i++;
      }//While 
     ?>
      </select>
      </td>
      </tr>
      <tr>
      <td width="62">SubTramo:</td>
      <td width="308">
      <select class="form-control" name="subtramo" id="subtramo">
      </select>
      </td>
      </tr>
	</table> 
        <!--FIN-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">CANCELAR</button>
        <button type="submit" class="btn btn-primary" name="filtrar" id="flitrar">FILTRAR</button>
      </div>
    </div>
  </div>
</div>
</form>
<!--FIN MODAL-->
</body>
</html>
