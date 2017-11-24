<?php
require_once('Connections/sac2.php'); 
header( 'Content-type: text/html; charset=iso-8859-1' );
date_default_timezone_set('America/Mexico_City');
//print_r($_POST);

if (!isset($_SESSION)) {
  session_start();
}
$login_ok = $_SESSION['login_ok'];
if ($login_ok == "identificado"){
 
}else{
echo "No Identificado";	 
	session_destroy();  // destroy the session 
	header("Location: index.php");
}
if (isset($_POST['cerrar_sesion'])) {
	session_destroy();  // destroy the session 
	header("Location: index.php");	
}
$periodo = date('Y').date("m");
$time = date('H:i:s');
$fecha = date("Y-m-d");
//--------------------------------------GUARDAR MANO DE OBRA-----------------------------------------------------------
if (isset($_REQUEST['manobra'])) { 
  
  	//MANO DE OBRA
	 for ($x = 1; $x <= 30; $x++) {	  
	  if ($_POST["nom".$x.""] <> ""){	
	    $nom = $_POST["nom".$x.""]; 
		$horas = $_POST["hora".$x.""];
		
		$sql = "SELECT * FROM CatEmpleados WHERE Empleado = '".$nom."'";
 		 //echo $sql;
		 $rs = odbc_exec( $conn, $sql );
  			if ( !$rs ) { 
    		exit( "Error en la consulta SQL" ); 
  		}    
 		 while ( odbc_fetch_row($rs) ) { 
  		  $numero = odbc_result($rs, 'NoEmp');
  		  $base = odbc_result($rs, 'CvBase');
 		 }
		
			$sql = "INSERT INTO Rud VALUES (0,'".$numero."','".$nom."','".$horas."','','','',0,0,'',0,'MANO DE OBRA','".$fecha."','INCOMPLETO','".$periodo."','".$base."')";
  			//echo $sql; 
			$rs = odbc_exec( $conn, $sql );
			  if ( !$rs ) { 
    			exit( "Error en la consulta Rud" ); 
  			} 
	    $nom="";
		$horas="";  
		$numero = ""; 
		$base = "";  
     }//FIN IF   
   }//FIN FOR
}
//--------------------------------------ACTUALIZAR MANO DE OBRA-----------------------------------------------------------
if (isset($_REQUEST['actualizarmanobra'])) { 
  $rud = $_POST['rud'];
  $horasal = $_POST['horas'];
		
	
			$sql = "UPDATE Rud SET HORAS_SALIDA='".$horasal."', ESTATUS='COMPLETO' WHERE RUD_ID='".$rud."'";
  			echo $sql; 
			$rs = odbc_exec( $conn, $sql );
			  if ( !$rs ) { 
    			exit( "Error en la consulta Rud" ); 
  			}  
}
//--------------------------------------GUARDAR MAQUINARIA-----------------------------------------------------------
if (isset($_REQUEST['maquinaria'])) { 
  
  	//CAUSAS
	 for ($x = 1; $x <= 5; $x++) {	  
	  if ($_POST["maq".$x.""] <> ""){	
	    $maq = $_POST["maq".$x.""]; 
		$horas = $_POST["horasm".$x.""];		
		
		$sql = "SELECT * FROM CatMaquinaria WHERE Descrip = '".$maq."'";
 		 //echo $sql;
		 $rs = odbc_exec( $conn, $sql );
  			if ( !$rs ) { 
    		exit( "Error en la consulta SQL" ); 
  		}    
 		 while ( odbc_fetch_row($rs) ) { 
  		  $numero = odbc_result($rs, 'NoEco');
 		 }
		    
			$sql = "INSERT INTO Rud VALUES (0,'".$numero."','".$maq."','".$horas."','','','',0,0,'',0,'MAQUINARIA','".$fecha."','INCOMPLETO','".$periodo."','')";
  			//echo $sql; 
			$rs = odbc_exec( $conn, $sql );
			  if ( !$rs ) { 
    			exit( "Error en la consulta Rud" ); 
  			} 
	    $maq="";
		$horas=""; 
		$numero="";
     }//FIN IF   
   }//FIN FOR
}
//--------------------------------------ACTUALIZAR MAQUINARIA-----------------------------------------------------------
if (isset($_REQUEST['actualizarmaquinaria'])) { 
  $rudid = $_POST['rudid'];
  $horasm = $_POST['horasmsa'];
		    
			$sql = "UPDATE Rud SET HORAS_SALIDA='".$horasm."', ESTATUS='COMPLETO' WHERE RUD_ID='".$rudid."'";
  			echo $sql; 
			$rs = odbc_exec( $conn, $sql );
			  if ( !$rs ) { 
    			exit( "Error en la consulta Rud" ); 
  			} 
}
//--------------------------------------GUARDAR INSUMOS-----------------------------------------------------------
if (isset($_REQUEST['insumos'])) { 
  
  	//CAUSAS
	 for ($x = 1; $x <= 5; $x++) {	  
	  if ($_POST["insu".$x.""] <> ""){	
	    $insu = $_POST["insu".$x.""]; 
		$cant = $_POST["insucant".$x.""];
		$numero = $_POST["numero".$x.""];	
		$unidad = $_POST["insunid".$x.""];	
		
			$sql = "INSERT INTO Rud VALUES (0,'".$numero."','".$insu."','','','','".$unidad."',".$cant.",0,'',0,'INSUMO','".$fecha."','INCOMPLETO','".$periodo."','')";
  			echo $sql; 
			$rs = odbc_exec( $conn, $sql );
			  if ( !$rs ) { 
    			exit( "Error en la consulta Rud" ); 
  			} 
	    $insu="";
		$cant="";
		$numero="";  
		$unidad="";  
     }//FIN IF   
   }//FIN FOR
}
//--------------------------------------ACTUALIZAR INSUMOS-----------------------------------------------------------
if (isset($_REQUEST['actualizarinsumos'])) { 
  $sobrante = $_POST['sobrante'];  
  $rud_id = $_POST['rud_id'];

		
			$sql = "UPDATE Rud SET CANTIDAD_SOBRANTE='".$sobrante."', ESTATUS='COMPLETO' WHERE RUD_ID='".$rud_id."'";
  			//echo $sql; 
			$rs = odbc_exec( $conn, $sql );
			  if ( !$rs ) { 
    			exit( "Error en la consulta Rud" ); 
  			} 
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

$maquina = array();
$x=0;

$sql = "select * from CatMaquinaria";
  //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }    
  while ( odbc_fetch_row($rs) ) { 
    $maquina[$x] = "\"" .odbc_result($rs, 'Descrip'). "\",";	
	$x++;    
}//While
$maquina[$x-1] = str_replace(",","",$maquina[$x-1]);	

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
//----------------------------------TABLAS---------------------------------------------

//MANO DE OBRA
$x=0;
$filas = array();
$sql = "SELECT * FROM Rud WHERE TIPO='MANO DE OBRA' AND ESTATUS='INCOMPLETO'";
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

//AVANCE
$x=0;
$filas2 = array();
$sql = "SELECT * FROM Rud WHERE TIPO='MAQUINARIA' AND ESTATUS='INCOMPLETO'";
 //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }   //OBTENEMOS TODA LA FILA 
 while ($row = odbc_fetch_array($rs)) {    
  $filas2[$x] = array_map('utf8_encode',$row);    
   $x++;    
 }//While
$datos2 =  $filas2; 
//echo json_encode($datos2);

//AVANCE
$x=0;
$filas3 = array();
$sql = "SELECT * FROM Rud WHERE TIPO='INSUMO' AND ESTATUS='INCOMPLETO'";
 //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }   //OBTENEMOS TODA LA FILA 
 while ($row = odbc_fetch_array($rs)) {    
  $filas3[$x] = array_map('utf8_encode',$row);    
   $x++;    
 }//While
$datos3 =  $filas3; 
//echo json_encode($datos3); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>RUD</title>
        
    <link rel='stylesheet' href='http://s.codepen.io/assets/reset/reset.css'>
    <link href="css/bootstrap.min.css" rel="stylesheet" />  
    <link rel="stylesheet" href="css/styl.css">
    <link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />
    <link type="text/css" rel="stylesheet" href="js/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></link>
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
	<script type="text/javascript" src="js/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
    
<script type="text/javascript">
function Limpiar(){	
	document.getElementById("formulario").reset();
	document.getElementById("formulario2").reset();
	document.getElementById("formulario3").reset(); 
}
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
<script type="text/javascript">
        $(document).ready(function () {
			
			//CREAMOS EL TAB			
            $('#tabsWidget').jqxTabs({ width: 900, height: 400, position: 'top'});
            // Focus jqxTabs.
            $('#tabsWidget').jqxTabs('focus');
			
			//AUTOCOMPLETAR
			var nombre = new Array(<?php  
	        foreach ($nombre as &$valor) {
              echo $valor;			  
            }		
	        ?>);
			var maquina = new Array(<?php  
	        foreach ($maquina as &$valor) {
              echo $valor;
            }		
	        ?>);
			var insumo = new Array(<?php  
	        foreach ($insumo as &$valor) {
              echo $valor;
            }		
	        ?>);				           
			
			//MANO DE OBRA
			for (var i = 1; i <= 30; i++) {
				$("#nom" + i).jqxInput({placeHolder: "Elige un empleado", height: 15, width: 250, minLength: 1, source: nombre});
				$("#hora" + i).jqxInput({value: '0', height: 15, width: 50, minLength: 1});		
				$("#hora" + i).jqxMaskedInput({mask: '##:##'});							
			}		
			//MAQUINARIA E INSUMOS
			for (var i = 1; i <= 5; i++) {
				$("#maq" + i).jqxInput({placeHolder: "Elige la maquinaria", height: 15, width: 250, minLength: 1, source: maquina});
				$("#horasm" + i).jqxInput({value: '0', height: 15, width: 50, minLength: 1});				
			 	$("#insu" + i).jqxInput({placeHolder: "Elige el insumo", height: 15, width: 250, minLength: 1, source: insumo});
				$("#insunid" + i).jqxInput({height: 15, width: 50, minLength: 1});
				$('#insunid' + i).attr('readonly', 'true');		
				$('#insunid' + i).css('background-color' , '#DEDEDE');
				$("#insucant" + i).jqxInput({value: '0', height: 15, width: 50, minLength: 1});	
			}
			
			
			//TABLA DE MANO DE OBRA		
			var data =  <?php echo json_encode($datos); ?>;				    
		    var source =
            {
                datafields: [
                    { name: 'RUD_ID', type: 'number' },	
					{ name: 'AVANCE_ID', type: 'number' },
					{ name: 'FOLIO', type: 'string' },				                 
                    { name: 'NOMBRE', type: 'string' }, 
                    { name: 'HORAS_ENTRADA', type: 'string' },
					{ name: 'HORAS_SALIDA', type: 'string' },
					{ name: 'EXTRAS', type: 'string' },
					{ name: 'UNIDAD', type: 'string' },	
					{ name: 'CANTIDAD', type: 'number' },
					{ name: 'HORAS_AD', type: 'string' },				                 
                    { name: 'CANTIDAD_AD', type: 'number' }, 
                    { name: 'FECHA', type: 'string' },
					{ name: 'PERIODO', type: 'number' },
					{ name: 'BASE', type: 'string' }									
                ],
                localdata: data 
            };
            var dataAdapter = new $.jqx.dataAdapter(source);

            $("#manobra").jqxGrid(
            {
                width: 630,
                height: 300,
                source: dataAdapter,
                
                keyboardnavigation: false,
                columns: [
				  { text: 'Folio', dataField: 'RUD_ID', width: 80 }, 
				  { text: 'No. Empleado', dataField: 'FOLIO', width: 100 },
				  { text: 'Nombre', dataField: 'NOMBRE', width: 250 }, 
                  { text: 'Horas', dataField: 'HORAS_ENTRADA', width: 100 }	,
                  { text: 'Fecha', dataField: 'FECHA', width: 100 }		  
                ]
            });	
			
			$('#manobra').on('rowclick', function (event){													       
			    $("#myModal3").modal('show'); 			        			
            });
			
			$("#manobra").on('rowselect', function (event) {								
			    var rud = event.args.row.RUD_ID;
				var nombre = event.args.row.NOMBRE;			
				var horas = event.args.row.HORAS_ENTRADA;		
						
				$("#nom").jqxInput({value: nombre, height: 15, width: 180, minLength: 1});
				$('#nom').attr('readonly', 'true');		
				$('#nom').css('background-color' , '#DEDEDE');
				$("#hora").jqxInput({value: horas, height: 15, width: 50, minLength: 1});
				$('#hora').attr('readonly', 'true');		
				$('#hora').css('background-color' , '#DEDEDE');
				$("#horas").jqxInput({height: 15, width: 50, minLength: 1});
				$("#rud").jqxInput({value: rud});	
			});
			
			//TABLA DE MAQUINARIA	
			var data =  <?php echo json_encode($datos2); ?>;				    
		    var source =
            {
                datafields: [
                    { name: 'RUD_ID', type: 'number' },	
					{ name: 'AVANCE_ID', type: 'number' },
					{ name: 'FOLIO', type: 'string' },				                 
                    { name: 'NOMBRE', type: 'string' }, 
                    { name: 'HORAS_ENTRADA', type: 'string' },
					{ name: 'HORAS_SALIDA', type: 'string' },
					{ name: 'EXTRAS', type: 'string' },
					{ name: 'UNIDAD', type: 'string' },	
					{ name: 'CANTIDAD', type: 'number' },
					{ name: 'HORAS_AD', type: 'string' },				                 
                    { name: 'CANTIDAD_AD', type: 'number' }, 
                    { name: 'FECHA', type: 'string' },
					{ name: 'PERIODO', type: 'number' },
					{ name: 'BASE', type: 'string' }									
                ],
                localdata: data 
            };
            var dataAdapter = new $.jqx.dataAdapter(source);

            $("#maquinaria").jqxGrid(
            {
                width: 630,
                height: 300,
                source: dataAdapter,
                
                keyboardnavigation: false,
                columns: [
				  { text: 'Folio', dataField: 'RUD_ID', width: 80 }, 
				  { text: '#', dataField: 'FOLIO', width: 100 },
				  { text: 'Maquinaria', dataField: 'NOMBRE', width: 250 }, 
                  { text: 'Horas', dataField: 'HORAS_ENTRADA', width: 100 },	
                  { text: 'Fecha', dataField: 'FECHA', width: 100 }				  
                ]
            });	
			
			$('#maquinaria').on('rowclick', function (event){													       
			    $("#myModal4").modal('show'); 			        			
            });
			
			$("#maquinaria").on('rowselect', function (event) {								
			    var maquinaria = event.args.row.NOMBRE;
				var horas = event.args.row.HORAS_ENTRADA;
				var rudid = event.args.row.RUD_ID;				
				
				$("#maq").jqxInput({value: maquinaria, height: 15, width: 180, minLength: 1});
				$('#maq').attr('readonly', 'true');		
				$('#maq').css('background-color' , '#DEDEDE');
				$("#horasm").jqxInput({value: horas, height: 15, width: 50, minLength: 1});
				$('#horasm').attr('readonly', 'true');		
				$('#horasm').css('background-color' , '#DEDEDE');
				$("#horasmsa").jqxInput({height: 15, width: 50, minLength: 1});
				$("#rudid").jqxInput({value: rudid});	
			});
			
			//TABLA DE INSUMOS 		
			var data =  <?php echo json_encode($datos3); ?>;				    
		    var source =
            {
                datafields: [
                    { name: 'RUD_ID', type: 'number' },	
					{ name: 'AVANCE_ID', type: 'number' },
					{ name: 'FOLIO', type: 'string' },				                 
                    { name: 'NOMBRE', type: 'string' }, 
                    { name: 'HORAS_ENTRADA', type: 'string' },
					{ name: 'HORAS_SALIDA', type: 'string' },
					{ name: 'EXTRAS', type: 'string' },
					{ name: 'UNIDAD', type: 'string' },	
					{ name: 'CANTIDAD', type: 'number' },
					{ name: 'HORAS_AD', type: 'string' },				                 
                    { name: 'CANTIDAD_AD', type: 'number' }, 
                    { name: 'FECHA', type: 'string' },
					{ name: 'PERIODO', type: 'number' },
					{ name: 'BASE', type: 'string' }									
                ],
                localdata: data 
            };
            var dataAdapter = new $.jqx.dataAdapter(source);

            $("#insumo").jqxGrid(
            {
                width: 670,
                height: 300,
                source: dataAdapter,
                
                keyboardnavigation: false,
                columns: [
				  { text: 'Folio', dataField: 'RUD_ID', width: 80 }, 
				  { text: '#', dataField: 'FOLIO', width: 100 },
				  { text: 'Insumo', dataField: 'NOMBRE', width: 250 }, 
                  { text: 'Unidad', dataField: 'UNIDAD', width: 60 },	
                  { text: 'Cantidad', dataField: 'CANTIDAD', width: 80 },
                  { text: 'Fecha', dataField: 'FECHA', width: 100 }					  
                ]
            });	
			
			$('#insumo').on('rowclick', function (event){													       
			    $("#myModal5").modal('show'); 			        			
            });
			
			$("#insumo").on('rowselect', function (event) {								
			    var insumo = event.args.row.NOMBRE;
				var unidad = event.args.row.UNIDAD;			
				var cantidad = event.args.row.CANTIDAD;	
				var rud = event.args.row.RUD_ID;				
				
				$("#insu").jqxInput({value: insumo, height: 15, width: 180, minLength: 1});
				$("#insunid").jqxInput({value: unidad, height: 15, width: 50, minLength: 1});
				$('#insunid').attr('readonly', 'true');		
				$('#insunid').css('background-color' , '#DEDEDE');
				$("#insucant").jqxInput({value: cantidad, height: 15, width: 50, minLength: 1});
				$("#rud_id").jqxInput({value: rud});
				$("#sobrante").jqxInput({height: 15, width: 50, minLength: 1});
			});
			
			
			//NUMERO, UNIDAD1
			$('#insu1').on('blur', function (event){
			     var elegido = $('#insu1').val();
				  elegido=$(this).val();
				  $.post("consulta.php", { insumo: elegido }, function(data){
					  var token = data.split();
				      var variable = token[0].split('*');		
						  $('#numero1').val(variable[0]);
					      $('#insunid1').val(variable[1]);
			      });//FIN POST
			});//Fin Funcion BLUR
			
			//NUMERO, UNIDAD2
			$('#insu2').on('blur', function (event){
			     var elegido = $('#insu2').val();
				  elegido=$(this).val();
				  $.post("consulta.php", { insumo: elegido }, function(data){
					  var token = data.split();
				      var variable = token[0].split('*');		
						  $('#numero2').val(variable[0]);
					      $('#insunid2').val(variable[1]);
			      });//FIN POST
			});//Fin Funcion BLUR
			
			//NUMERO, UNIDAD3
			$('#insu3').on('blur', function (event){
			     var elegido = $('#insu3').val();
				  elegido=$(this).val();
				  $.post("consulta.php", { insumo: elegido }, function(data){
					  var token = data.split();
				      var variable = token[0].split('*');		
						  $('#numero3').val(variable[0]);
					      $('#insunid3').val(variable[1]);
			      });//FIN POST
			});//Fin Funcion BLUR
			
			//NUMERO, UNIDAD4
			$('#insu4').on('blur', function (event){
			     var elegido = $('#insu4').val();
				  elegido=$(this).val();
				  $.post("consulta.php", { insumo: elegido }, function(data){
					  var token = data.split();
				      var variable = token[0].split('*');		
						  $('#numero4').val(variable[0]);
					      $('#insunid4').val(variable[1]);
			      });//FIN POST
			});//Fin Funcion BLUR
			
			//NUMERO, UNIDAD5
			$('#insu5').on('blur', function (event){
			     var elegido = $('#insu5').val();
				  elegido=$(this).val();
				  $.post("consulta.php", { insumo: elegido }, function(data){
					  var token = data.split();
				      var variable = token[0].split('*');		
						  $('#numero5').val(variable[0]);
					      $('#insunid5').val(variable[1]);
			      });//FIN POST
			});//Fin Funcion BLUR
			
			$('.time').mask('00:00');
			
});//$(document).ready		
	
</script>
</head>

<body class='default'> 

<div id='cssmenu'>
<ul>
   <li class='active has-sub'><a href='Inicio.php'><span>Inicio</span></a></li>
   <li><a href='#'><span>Cat&aacute;logos</span></a>
      <ul>
         <li><a href='catconceptos.php'><span>Conceptos</span></a>
         <li><a href='catempleados.php'><span>Empleados</span></a>
         <li><a href='catmaquinaria.php'><span>Maquinaria</span></a>
         <li><a href='catins.php'><span>Insumos</span></a>         
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
         <li><a href='avancediario.php'><span>Avance Diario</span></a>
         <!--  <li><a href='#'><span>RUD</span></a> 
         	<ul>
            	<li><a href='Maquinaria.php'><span>Maquinaria</span></a>
        		<li><a href='Insumos.php'><span>Insumos</span></a>    
              </ul>
          </li>   --> 
                              
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
 
  
     <table width="500" border="0" align="right" style="'Oxygen Mono', Tahoma, Arial, sans-serif; font-size:15px; color: #58D3F7">
     <tr>
       <td width="150" height="30" align="right">Bienvenid@ :</td>
       <td width="200" align="center"><?php /* echo $_SESSION['S_Usuario'];*/ ?></td>
       <td width="100" valign="bottom" class="btn btn-info">
       <form id="salir" action="avanceDiario.php" method="post" enctype="multipart/form-data" >             
          <button type="submit" class="btn btn-info" border ="0" id="cerrar_sesion" name="cerrar_sesion" > Cerrar Sesion</button> 
       </form> 
       </td>
     </tr>
   </table>
</ul>
</div>

<div align="center">
<!--<img src="images/avancediario.png" width="912" height="165">-->
</div>


<!-- Tabla-->
<center>
  <br />
  <br />
  <table width="956" height="235" border="1" align="center">
  <tr>
  <td colspan="3" align="center" height="67" style="color:#09F; font-size:24px"><strong>NUEVO REGISTRO RUD</strong></td>
  </tr>
  <tr>
  <td width="33.33%" align="center" valign="middle">
  
  <a href="#"data-toggle="modal" data-target="#myModal" onClick="Limpiar()"><img src="images/manoobra.png" width="160" height="160" /></a>
  </td> 
    <td width="33.33%" align="center" valign="middle">
  
  <a href="#"data-toggle="modal" data-target="#myModal1" onClick="Limpiar()"><img src="images/maquinaria_2.png" width="160" height="160" /></a>
  
  </td> 
    <td width="33.33%" align="center" valign="middle">
  
  <a href="#"data-toggle="modal" data-target="#myModal2" onClick="Limpiar()"><img src="images/insumo_2.png" width="160" height="160" /></a>
  </td> 
 </tr> 
 </table>
  <br />
  <br />
 </center>
 
<center>
<div id='jqxWidget'>
        <div id="tabsWidget">
            <ul style="margin-left: 30px;">
                <li>Mano de Obra</li>
                <li>Maquinaria</li> 
                <li>Isumos</li>                             
            </ul>
             <div>       
             <br />   
            <br />                
                 <center>                 
                    <div id="manobra"></div>
                </center>      
            </div>   
            <div>    
            <br />  
            <br />      
               <center>
               <div id="maquinaria" > </div>
               </center>
            </div>  
            <div>
            <br />
            <br />
            <center>
               <div id="insumo"></div>
               </center>
            </div>                       
        </div>              
    </div>
        <div id='jqxWidget' style="font-size: 13px; font-family: Verdana; float: left;">
</div>
</center>  
<div id="cellbegineditevent"></div>
<div style="margin-top: 10px;" id="cellendeditevent"></div>




<!--MODALES-->


<!--MANO DE OBRA-->
<form id="formulario" action="rud2.php" method="post" enctype="multipart/form-data" >
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Mano de Obra</h4>
      </div>
      <div class="modal-body">
        <!--CUERPO-->
<p align="center"><strong>Mano de Obra</strong></p>
<table width="500" height="auto" border="0">
<?php 
   for ($i = 1; $i <= 30; $i++) {
	   $x = $i + 100;
	   $z = $x + 1;
	   if ($i==1){
		   echo "<tr name='".$x."' id='".$x."'>
		           	<td width='29'>".$i."</td>
                    <td width='150'><input type='text' id='nom".$i."' name='nom".$i."' accept-charset='utf-8'  /></td>
                    <td width='50'>Horas</td>
                    <td width='40'><input type='text' id='hora".$i."' name='hora".$i."' time-mask='00:00' accept-charset='utf-8' size='6' /></td>
                    <td width='30'><a href=\"javascript:MostrarFilas('".$z."')\"><img id='sac".$x."' src='images/plus2.png' width='30' height='30'></a></td>					
                </tr>";
	   }else{
		   echo "<tr name='".$x."' id='".$x."' style='display:none;'>
		            <td width='29'>".$i."</td>
                    <td width='150'><input type='text' id='nom".$i."' name='nom".$i."' accept-charset='utf-8'  /></td>
                    <td width='50'>Horas</td>
                    <td width='40'><input type='text' id='hora".$i."' name='hora".$i."' time-mask='00:00' accept-charset='utf-8' size='6' /></td>
                    <td width='30'><a href=\"javascript:MostrarFilas('".$z."')\"><img id='sac".$x."' src='images/plus2.png' width='30' height='30'></a></td>					
                </tr>";		  
	   }	   
   }	
?>
</table>        
        <!--FIN-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="Limpiar()">Cerrar</button>
        <button type="submit" class="btn btn-primary" id="manobra" name="manobra">Guardar</button>
      </div>
    </div>
  </div>
</div>
</form>

<!--MANO DE OBRA SALIDA-->
<form id="formulario4" action="rud2.php" method="post" enctype="multipart/form-data" >
<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">RUD</h4>
      </div>
      <div class="modal-body">
        <!--CUERPO-->
<p align="center"><strong>Mano de Obra</strong></p>
<table width="550" height="auto" border="0">
  <tr>
	<td width='29'><input type='hidden' id='rud' name='rud'/></td>
    <td width='150'><input type='text' id='nom' name='nom' accept-charset='utf-8'  /></td>
    <td width='90'>Hora Entrada:</td>
    <td width='40'><input type="text" id='hora' name='hora' accept-charset='utf-8' size='6' /></td>	
    <td width='70'>Hora Salida:</td>
    <td width='40'><input type='text' id='horas' name='horas' accept-charset='utf-8' size='6' /></td>			
  </tr>
</table>        
        <!--FIN-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="Limpiar()">Cerrar</button>
        <button type="submit" class="btn btn-primary" id="actualizarmanobra" name="actualizarmanobra">Guardar</button>
      </div>
    </div>
  </div>
</div>
</form>








<!--MAQUINARIA-->
<form id="formulario2" action="rud2.php" method="post" enctype="multipart/form-data" >
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Maquinaria</h4>
      </div>
      <div class="modal-body">
        <!--CUERPO-->
<p align="center"><strong>Maquinaria</strong></p>
<table width="500" height="auto" border="0">
<?php 
   for ($i = 1; $i <= 5; $i++) {
	   $x = $i + 200;
	   $z = $x + 1;
	   if ($i==1){
		   echo "<tr name='".$x."' id='".$x."'>
		           	<td width='29'>".$i."</td>
                    <td width='150'><input type='text' id='maq".$i."' name='maq".$i."' accept-charset='utf-8'  /></td>
                    <td width='50'>Horas</td>
                    <td width='40'><input type='text' id='horasm".$i."' name='horasm".$i."' accept-charset='utf-8' size='6' /></td>
                    <td width='30'><a href=\"javascript:MostrarFilas('".$z."')\"><img id='sac".$x."' src='images/plus2.png' width='30' height='30'></a></td>					
                </tr>";
	   }else{
		   echo "<tr name='".$x."' id='".$x."' style='display:none;'>
		            <td width='29'>".$i."</td>
                    <td width='150'><input type='text' id='maq".$i."' name='maq".$i."' accept-charset='utf-8'  /></td>
                    <td width='50'>Horas</td>
                    <td width='40'><input type='text' id='horasm".$i."' name='horasm".$i."' accept-charset='utf-8' size='6' /></td>
                    <td width='30'><a href=\"javascript:MostrarFilas('".$z."')\"><img id='sac".$x."' src='images/plus2.png' width='30' height='30'></a></td>					
                </tr>";		  
	   }	   
   }	
?>
</table>         
        <!--FIN-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="Limpiar()">Cerrar</button>
        <button type="submit" class="btn btn-primary" id="maquinaria" name="maquinaria">Guardar</button>
      </div>
    </div>
  </div>
</div>
</form>

<!--MAQUINARIA SALIDA-->
<form id="formulario5" action="rud2.php" method="post" enctype="multipart/form-data" >
<div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">RUD</h4>
      </div>
      <div class="modal-body">
        <!--CUERPO-->
<p align="center"><strong>Maquinaria</strong></p>
<table width="550" height="auto" border="0">
  <tr>
  	<td><input type='hidden' id='rudid' name='rudid'/></td>
	<td width='29'>Maquinaria:</td>
    <td width='150'><input type='text' id='maq' name='maq' accept-charset='utf-8'  /></td>
    <td width='90'>Hora Entrada:</td>
    <td width='40'><input type='text' id='horasm' name='horasm' accept-charset='utf-8' size='6' /></td>
    <td width='80'>Hora Salida:</td>
    <td width='40'><input type='text' id='horasmsa' name='horasmsa' accept-charset='utf-8' size='6' /></td>					
  </tr>
</table>         
        <!--FIN-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="Limpiar()">Cerrar</button>
        <button type="submit" class="btn btn-primary" id="actualizarmaquinaria" name="actualizarmaquinaria">Guardar</button>
      </div>
    </div>
  </div>
</div>
</form>






<!--INSUMOS-->
<form id="formulario3" action="rud2.php" method="post" enctype="multipart/form-data" >
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Insumos</h4>
      </div>
      <div class="modal-body">
        <!--CUERPO-->
<table width="550" height="auto" border="0">
<?php 
   for ($i = 1; $i <= 5; $i++) {
	   $x = $i + 300;
	   $z = $x + 1;
	   if ($i==1){
		   echo "<tr name='".$x."' id='".$x."'>
		            <td width='29'>".$i."</td>
					<td width='10'><input type='hidden' id='numero".$i."' name='numero".$i."'/></td>
                    <td width='150'><input type='text' id='insu".$i."' name='insu".$i."' accept-charset='utf-8'  /></td>
                    <td width='50'>Unidad</td>
                    <td width='40'><input type='text' id='insunid".$i."' name='insunid".$i."' accept-charset='utf-8' size='6' /></td>
					<td width='50'>Cantidad</td>
                    <td width='40'><input type='text' id='insucant".$i."' name='insucant".$i."' accept-charset='utf-8' size='6' /></td>
                    <td width='30'><a href=\"javascript:MostrarFilas('".$z."')\"><img id='sac".$x."' src='images/plus2.png' width='30' height='30'></a></td>					
                </tr>";
	   }else{
		   echo "<tr name='".$x."' id='".$x."' style='display:none;'>
		            <td width='29'>".$i."</td>
					<td width='10'><input type='hidden' id='numero".$i."' name='numero".$i."'/></td>
                    <td width='150'><input type='text' id='insu".$i."' name='insu".$i."' accept-charset='utf-8'  /></td>
                    <td width='50'>Horas</td>
                    <td width='40'><input type='text' id='insunid".$i."' name='insunid".$i."' accept-charset='utf-8' size='6' /></td>
					<td width='50'>Cantidad</td>
                    <td width='40'><input type='text' id='insucant".$i."' name='insucant".$i."' accept-charset='utf-8' size='6' /></td>
                    <td width='30'><a href=\"javascript:MostrarFilas('".$z."')\"><img id='sac".$x."' src='images/plus2.png' width='30' height='30'></a></td>					
                </tr>";		  
	   }	   
   }	
?>
</table>        
        <!--FIN-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="Limpiar()">Cerrar</button>
        <button type="submit" class="btn btn-primary" id="insumos" name="insumos">Guardar</button>
      </div>
    </div>
  </div>
</div>
</form>


<!--INSUMOS-->
<form id="formulario6" action="rud2.php" method="post" enctype="multipart/form-data" >
<div class="modal fade" id="myModal5" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">RUD</h4>
      </div>
      <div class="modal-body">
        <!--CUERPO-->
<table width="550" height="auto" border="0">
	<tr>
		<td width='10'><input type='hidden' id='rud_id' name='rud_id'/></td>
		<td width='29'>Insumo:</td>
        <td width='150'><input type='text' id='insu' name='insu' accept-charset='utf-8'  /></td>
        <td width='50'>Unidad:</td>
        <td width='40'><input type='text' id='insunid' name='insunid' accept-charset='utf-8' size='6' /></td>
		<td width='50'>Cantidad:</td>
         <td width='40'><input type='text' id='insucant' name='insucant' accept-charset='utf-8' size='6' /></td>	
		<td width='50'>Sobrante:</td>
         <td width='40'><input type='text' id='sobrante' name='sobrante' accept-charset='utf-8' size='6' /></td>			
     </tr>
</table>        
        <!--FIN-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="Limpiar()">Cerrar</button>
        <button type="submit" class="btn btn-primary" id="actualizarinsumos" name="actualizarinsumos">Guardar</button>
      </div>
    </div>
  </div>
</div>
</form>

</body>
</html>