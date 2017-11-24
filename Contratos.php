<?php 
require_once('Connections/sac2.php');
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
$folio = "";
$notificacion = "";
//echo date("Y-m-d\TH:i:s");
/***************************************************GUARDAR CONTRATO**************************************************/	
if (isset($_REQUEST['guardarcontrato'])) {
	$contrato = $_POST["contrato"];	
	$actividad = $_POST["actividad"];
	$objeto = $_POST["objeto"];	
	$clasificacion = $_POST["clasificacion"];
	$subcuenta = $_POST["subcuenta"];	
	$fecha_inicial = $_POST["fecha_inicial"];
	$fecha_final = $_POST["fecha_final"];	
	$anticipo = $_POST["anticipo"];
	$empresa = $_POST["empresa"];
	$anticipototal = $_POST["anticipototal"];
	
	
	  for ($x = 1; $x <= 13; $x++) {      
		  if ($_POST["tramo".$x.""] <> ""){    
				$tramo = $_POST["tramo".$x.""]; 
				$importe = $_POST["importe".$x.""];
				
				$sql = "INSERT INTO Contratos VALUES('".$objeto."','".$actividad."','".$empresa."','".$importe."','".$anticipo."','".$anticipototal."','".$fecha_inicial."','".$fecha_final."','".$clasificacion."','".$subcuenta."','".$tramo."','".$contrato."','VIGENTE','')";
				//echo $sql;
				$rs = odbc_exec( $conn, $sql );
				if ( !$rs ) { 
				  exit( "Error en la consulta SQL" ); 
				}  
				$tramo = "";
				$importe = 0;    
		}   
 	}
	
	header("Location: Contratos.php");
	
}
/***************************************************ACTUALIZAR CONTRATO**************************************************/	
if (isset($_REQUEST['actualizarcontrato'])) {
	$contrato = $_POST["contrato1"];	
	$clasificacion = $_POST["clasificacion1"];
	$subcuenta = $_POST["subcuenta1"];
	$actividad = $_POST["actividad1"];
	$contador = $_POST["contadorest"];
	$importe = $_POST["poreje"];

	 if (isset($_POST['cancelar'])){
		  $cancelar = $_POST['cancelar'];
	  }else{
		  $cancelar = "";
	  }

	  if($cancelar != "" && $cancelar <= $importe){
			  
			  $sql = "UPDATE Contratos SET IMPORTE_CANCELAR = '".$cancelar."', ESTATUS = 'TERMINADO' WHERE CONTRATO = '".$contrato."'";
			//echo $sql;
			$rs = odbc_exec( $conn, $sql );
			if ( !$rs ) { 
				exit( "Error en la consulta Contratos" ); 
			}

			$sql = "UPDATE Contratos SET CLASIFICACION = '".$clasificacion."', SUBCUENTA = '".$subcuenta."', ACTIVIDAD = '".$actividad."' WHERE CONTRATO = '".$contrato."'";
			//echo $sql;
			$rs = odbc_exec( $conn, $sql );
			if ( !$rs ) { 
				exit( "Error en la consulta Contratos" ); 
			}

	  }else{
		  

		$sql = "UPDATE Contratos SET CLASIFICACION = '".$clasificacion."', SUBCUENTA = '".$subcuenta."', ACTIVIDAD = '".$actividad."' WHERE CONTRATO = '".$contrato."'";
		//echo $sql;
		$rs = odbc_exec( $conn, $sql );
		if ( !$rs ) { 
			exit( "Error en la consulta Contratos" ); 
		}

		$sql = "UPDATE Estimaciones SET CLASIFICACION = '".$clasificacion."' WHERE CONTRATO = '".$contrato."'";
		//echo $sql;
		$rs = odbc_exec( $conn, $sql );
		if ( !$rs ) { 
			exit( "Error en la consulta Estimaciones" ); 
		} 

		for ($x = 1; $x <= $contador; $x++) {
			$factura = $_POST["factura_est".$x.""]; 
			$id_estimacion = $_POST["id_estimacion".$x.""]; 
					
			$sql = "UPDATE Estimaciones SET FACTURA = '".$factura."' WHERE ESTIMACION_ID='".$id_estimacion."'";
			//echo $sql;
			$rs = odbc_exec( $conn, $sql );
			if ( !$rs ) { 
				exit( "Error en la consulta SQL" );  
			} 
		}//for
		$notificacion = "error";
	  }//else
	
	//header("Location: Contratos.php");
 	
}

/*****************************************ACTIVIDADES****************************************/
$actividad = array();
$x=0;

$sql = "select * from CatConceptoCapex";	
  //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }    
  while ( odbc_fetch_row($rs) ) { 
    $actividad[$x] = "\"" .odbc_result($rs, 'CONCEPTO'). "\",";	
	$x++;    
}//While
$actividad[$x-1] = str_replace(",","",$actividad[$x-1]);

/*****************************************PROVEEDORES****************************************/
$proveedor = array();
$x=0;

$sql = "select * from Contratos";	
  //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }    
  while ( odbc_fetch_row($rs) ) { 
    $proveedor[$x] = "\"" .odbc_result($rs, 'EMPRESA'). "\",";	
	$x++;    
}//While
$proveedor[$x-1] = str_replace(",","",$proveedor[$x-1]);


/***************************************************FILTRO*************************************************/	
if (isset($_REQUEST['filtro'])) {
	$clasificacion = $_POST["clasFiltro"];
	$subcuenta = $_POST["subcuentaFiltro"];
	$tramo = $_POST["tramoFiltro"];
	$empresa = $_POST["empresaFiltro"];
	$anio = $_POST["anioFiltro"];


	if ($clasificacion <> "TODOS" && $clasificacion <> ""){
		$opc1 = " AND CLASIFICACION = '".$clasificacion."'";
	}else{
		$opc1 = "";
	}
	if ($empresa <> "TODOS" && $empresa <> ""){
		$opc1.= " AND EMPRESA = '".$empresa."'";
	}else{
		$opc1.= "";
	}
	if ($subcuenta <> "TODOS" && $subcuenta <> ""){
		$opc1.= " AND SUBCUENTA = '".$subcuenta."'";
	}else{
		$opc1.= "";
	}
	if ($tramo <> "TODOS" && $tramo <> ""){
		$opc1.= " AND TRAMO = '".$tramo."'";
	}else{
		$opc1.= "";
	}
	if ($anio <> ""){
		$opc1.= " AND YEAR(FECHA_INICIO) = '".$anio."'";
	}else{
		$opc1.= "";
	}

	$sql = "SELECT * FROM Contratos WHERE EMPRESA<>'' ".$opc1."";

}else{
	$sql = "SELECT * FROM Contratos WHERE EMPRESA<>'' AND YEAR(FECHA_INICIO) = '".date("Y")."'";
}
//print_r($_POST);
//echo $sql;
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Contratos</title>
	<link href="css/bootstrap.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />
    <link rel="stylesheet" href="CssLocal/menuSac.css"><!--Necesario para Menu 1--> 
	<link href="css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="js/bootstrap-dialog.min.js"></script>   
	<script type="text/javascript" src="scripts/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="scripts/demos.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxinput.js"></script>
    <script type="text/javascript" src="js/jqueryFileTree.js"></script>
    <link href="css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="js/bootstrap-dialog.min.js"></script>
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
	<style type="text/css">
		/*FUENTES*/
		@font-face {
			font-family: 'ubuntu_titlingbold';
    		src: url('Fuentes/ubuntu/UbuntuTitling-Bold-webfont.eot');
    		src: url('Fuentes/ubuntu/UbuntuTitling-Bold-webfont.eot?#iefix') format('embedded-opentype'),
         	url('Fuentes/ubuntu/UbuntuTitling-Bold-webfont.woff') format('woff'),
         	url('Fuentes/ubuntu/UbuntuTitling-Bold-webfont.ttf') format('truetype'),
         	url('Fuentes/ubuntu/UbuntuTitling-Bold-webfont.svg#ubuntu_titlingbold') format('svg');    		
			font-weight: normal;
			font-style: normal;
		}
		@font-face {
			font-family: 'commandocommando';
		    src: url('Fuentes/commando/commando-webfont.eot');
		    src: url('Fuentes/commando/commando-webfont.eot?#iefix') format('embedded-opentype'), 
		    url('Fuentes/commando/commando-webfont.woff') format('woff'), 
		    url('Fuentes/commando/commando-webfont.ttf') format('truetype'), 
		    url('Fuentes/commando/commando-webfont.svg#commandocommando') format('svg');
			font-weight: normal;
			font-style: normal;
		}
		header{
			margin:0;			
			background: #2c2c2c;
			height:65px; 
			width:100%;
			border-bottom: 3px solid #0057B3;
		}
		header img{			
			float: left;
		}
		header span{			
			float: left;
			padding-top: 20px;
			width: 150px;
			height: 65px;
			background: #2c2c2c;
			border-bottom: 3px solid #0057B3;

			font-family: 'ubuntu_titlingbold';
			font-size: 20px;
			text-align: center;
			color: white;
		}

		/*IDENTIFICADORES*/
		
		#contenido{
			width: 100%;
			height: auto;

		}
		#encabezadoFijo{
			margin:0;
			padding-top: 20px;			
			padding-left: 20px;
			background:white;			
			font-family: 'commandocommando';
			font-size: 18px;			
			text-align:left;
			height:60px;
			width:100%;
			color:#000;	
			z-index: 1;		 
			
		}


		/*CLASES*/
		.izquierda{
			float: left;
			margin-left: 60px;
			color:#000;
		}
		.derecha{
			float: right;
			margin-right: 60px;
			color:#000;
		}
		.fixed{
			position:fixed;
			border-bottom: 2px solid #0057B3; 
			top:0			
		}
		.titulo{			
			font-family: ubuntu_titlingbold;
			font-size: 16px;
			background-color:#C0DCF3;
			
				}
		.tabla{			
			font-family: ubuntu_titlingbold;
			font-size: 12px;
		}
		
		/*EFECTOS*/
		header span:hover {
			text-decoration: none;
			background: #49A2FF;
			color:black;
		}
		tbody tr:nth-child(even){
			background-color: #f2f2f2;
		}
		tbody tr:hover{
			background-color:#acd0e9;
		}

	</style>
	<script type="text/javascript">
		$(document).ready(function () {
			
			//CONFIGURACION DE LAS NOTIFICACIONES		
		var Tipo = '<?php echo $notificacion;  ?>';		
		PNotify.prototype.options.styling = "bootstrap3";
		
		 switch (Tipo) {
			 case 'error':
			 	new PNotify({
					title: "Error",
					text: "Contrato no cerrado.<br>Importe a cancelar incorrecto.",
					delay: 2000,
					animation: 'fade', //'show', 'slide', animation: { effect_in: 'show', effect_out: 'slide' }				
					nonblock: {
						nonblock: false
					},
					type: "error"
				});
        		break;		 
		 }//Swicth


			//AUTOCOMPLETAR
			var actividad = new Array(<?php  
	        foreach ($actividad as &$valor) {
              echo $valor;			  
            }		
	        ?>);
			
			var proveedor = new Array(<?php  
			
	        foreach ($proveedor as &$valor) {
              echo $valor;			  
            }		
	        ?>);					
			
			$("#actividad").jqxInput({placeHolder: "Elige una actividad", minLength: 1,  source: actividad});
			$("#empresa").jqxInput({placeHolder: "Elige una empresa", minLength: 1,  source: proveedor});
			$("#actividad1").jqxInput({placeHolder: "Elige una actividad", minLength: 1,  source: actividad});
			// ENCABEZADO FIJO
			$(window).scroll(function() {    
    			posicionarMenu();
			});
			function posicionarMenu() {
			    var alturaMenuSup = $('header').outerHeight(true);
			    var alturaEnabezadoFijo = $('#encabezadoFijo').outerHeight(true);

			    if ($(window).scrollTop() >= alturaMenuSup){
			        $('#encabezadoFijo').addClass('fixed');		        
			        $('section').css('margin-top', (alturaEnabezadoFijo) + 'px');
			    } else {
			        $('#encabezadoFijo').removeClass('fixed');
			        $('section').css('margin-top', '0');
			    }
			}
						
			$("#clasificacion").click(function(){		
				$("#clasificacion option:selected").each(function () {
			     	elegido = $(this).val();
					if(elegido.includes("OT")){
						$("#contrato").jqxInput({placeHolder: "000-"+<?php echo date("Y"); ?>, minLength: 1 });
					}else{
						if(elegido.includes("OPEX")){
							$("#contrato").jqxInput({placeHolder: "00-"+<?php echo date("y"); ?>+"-RCO-EMPRESA-OPEX", minLength: 1 });
						}else{
							if(elegido.includes("CAPEX")){
								$("#contrato").jqxInput({placeHolder: "00-"+<?php echo date("y"); ?>+"-RCO-EMPRESA-CAPEX", minLength: 1 });
							}else{
								if(elegido.includes("COMPRAVENTA")){
									$("#contrato").jqxInput({placeHolder: "000-"+<?php echo date("y"); ?>+"-RCO-EMPRESA-CV", minLength: 1 });
								}
							}
						}
					}
              });
			});
			
			$("#tabla tbody tr").click(function (){			
				var id = $(this).attr("id");
				var contrato = $(this).find("td").eq(0).html();
				var clasificacion = $(this).find("td").eq(1).html();
				//alert(id);
				$.post("ConsultaAvanceDiario.php", { DetalleEstimacion2: '', Clasificacion: clasificacion, Contrato: contrato }, function(data){
					var token = data.split();
				    var variable = token[0].split('*');
					$("#mostrarestimacion").html(variable[0]);
					//alert(variable[1]);
					$("#total").text(variable[1]);
					$("#por_ejecutar").text(variable[2]);
					$("#poreje").val(variable[6]);
					
					if (variable[3] == ""){
						$("#penalizaciones").text("0");
					}else{
						$("#penalizaciones").text(variable[3]);
					}
					
					if (variable[4] == "TERMINADO"){
						$("#actualizarcontrato").attr("disabled", true);
						$("#agregarEst").attr("disabled", true);
						$("#terminado").text("Contrato Finalizado");
						$("#cancelar").attr('type', 'text');
						$("#cancelar").attr('readonly', true);
						$("#cancelar").val(variable[5]);
					}
				});
				
				$("#contrato1").val(contrato);
				$("#clasificacion1 option[value='"+clasificacion+"']").prop('selected', true);
				$("#tramos1").val($(this).find("td").eq(2).html());
				$("#subcuenta1 option[value='"+$(this).find("td").eq(3).html()+"']").prop('selected', true);
				$("#actividad1").val($(this).find("td").eq(5).html());
				$("#fecha_ini1").val($(this).find("td").eq(8).html());
				$("#fecha_fin1").val($(this).find("td").eq(9).html());
				$("#empresa1").val($(this).find("td").eq(10).html());
				$("#id_contrato").val(id);
				$("#detalle").modal('show');			
				
			});		
			
			

		});//document.ready
		
		/************************************CALCULO DE IMPORTE Y ANTICIPO*********************************/
			function Calculo(){
				
				var total = parseFloat($("#importe1").val()) + parseFloat($("#importe2").val()) + parseFloat($("#importe3").val()) + parseFloat($("#importe4").val()) + parseFloat($("#importe5").val()) + parseFloat($("#importe6").val()) + parseFloat($("#importe7").val()) + parseFloat($("#importe8").val()) + parseFloat($("#importe9").val()) + parseFloat($("#importe10").val()) + parseFloat($("#importe11").val()) + parseFloat($("#importe12").val()) + parseFloat($("#importe13").val());
							
				$("#importetotal").val(total);
				
				var anticipo =  parseFloat($("#anticipo").val()) / 100;
				$("#anticipototal").val(anticipo * total);
								
			}
			
			function Importe(){
				var valor = $("input[name=detalleInfo]:checked").val();

				for (var i = 1; i <= 13; i++) {
					//var total = 
					$("#importeest"+i).val(parseFloat($("#montoest"+i).val()) - parseFloat($("#retencion"+i).val())  - parseFloat($("#penalizacion"+i).val()) + parseFloat($("#devolucion"+i).val()) - parseFloat($("#amortizacion"+i).val()));		
					//alert("importe "+$("#importeest"+i).val());
					//alert("anti "+parseFloat($("#saldo_anticipo").val()) / 100);
					//alert("total "+parseFloat($("#montoest"+i).val()) * (parseFloat($("#saldo_anticipo").val()) / 100));
					if(valor != "Anticipo") {
						$("#amor_sug"+i).text(parseFloat($("#montoest"+i).val()) * (parseFloat($("#saldo_anticipo").val()) / 100));
					}
				}//for
			}//Fin function 
			
			function CalAmort(){
				for (var i = 1; i <= 13; i++) {
					$("#importeest"+i).val(parseFloat($("#importeest"+i).val()) - parseFloat($("#amortizacion"+i).val()));			
				}//for
			}//Fin function  

			function VerEstimaciones(){
				var clasificacion = $("#clasificacionest").val();
				var contrato = $("#contratoest").val();
				
				$.post("ConsultaAvanceDiario.php", { DetalleEstimacion: '', Clasificacion: clasificacion, Contrato: contrato }, function(data){
					//alert(data);
					if (data == ''){
						BootstrapDialog.show({
							title: 'Estimaciones.',
							message: 'No hay estimaciones guardadas.',
							buttons: [{
								label: 'Cerrar',
								action: function(dialog) {
									dialog.close();
								}
							}]
						});	
					}else{
						BootstrapDialog.show({
							title: 'Estimaciones.',
							message: data,
							buttons: [{
								label: 'Cerrar',
								action: function(dialog) {
									dialog.close();
								}
							}]					
						});	
					}//fin else
				});
			}//Function

function Adjuntar(){
	
	var id_contrato = $("#id_contrato").val();
	var empresa = $("#empresa1").val();
	var contrato = $("#contrato1").val();
	
	window.open("AdjuntarContrato.php?id_contrato=" + id_contrato + "&contrato=" + contrato + "&empresa=" + empresa );	
}

function AgregarEstimacion(){
	var Contrato = $("#contrato1").val();
	var clasificacion = $("#clasificacion1").val();
	
	$("#clasificacionest").val(clasificacion);
	$("#contratoest").val(Contrato);
	
	$.post("consultaAvanceDiario.php", { Mostrar: "", contrato: Contrato, Clasificacion: clasificacion }, function(data){
		var token = data.split();
		var variable = token[0].split('*');	
						
		$("#mostrar_estimacion").html(variable[0]);
		$("#saldo_anticipo").val(variable[1]);
		$("#anticipo_monto").val("$ "+variable[2]);
		document.getElementById("fechas").style.display = "block";
	});
	$("#estimacion").modal('show');
}

function GuardaEstimacion(){
	//alert("funcion");
	var contador = $("#contador").val();
	var Clasificacion = $("#clasificacionest").val();
	var Contrato = $("#contratoest").val();
	var Fechaini = $("#fechainicioest").val();
	var Fechafin = $("#fechafinest").val();
	var Estimacion = $("#no_estimacion").val();
	var Radio = $("input:radio[name=detalleInfo]:checked").val();	
	var Factura = $("#factura").val();
	//alert(Radio);
	
	for (var i = 1; i <= contador; i++) {
		//alert("for");
		var monto = $("#montoest"+i).val();
		var tramo = $("#tramoest"+i).val();
		var Retencion = $("#retencion"+i).val();
		var Devolucion = $("#devolucion"+i).val();
		var Amortizacion = $("#amortizacion"+i).val();
		var Penalizacion = $("#penalizacion"+i).val();
		var Estid = $("#estid"+i).val();
		
		$.post("consultaAvanceDiario.php", { GuardarEstimacion: '', clasificacionest: Clasificacion, contratoest: Contrato, fechainicioest: Fechaini, fechafinest: Fechafin, no_estimacion: Estimacion, detalleInfo: Radio, factura: Factura, montoest: monto, tramoest: tramo, retencion: Retencion, devolucion: Devolucion, amortizacion: Amortizacion, estid: Estid, penalizacion: Penalizacion }, function(data){
			//alert(data);
			alert("Registro Guardado");	
		});//post
	}//for
	location.reload();
}//function
			
//MOSTRAR DE ACUERDO A SELECCION DE RADIO BUTTON
        function toggle(radio) {
          if(radio.value=="Anticipo"){
			  for (var i = 1; i <= 13; i++) {
					document.getElementById("no_estimacion").readOnly = true;
					document.getElementById("amortizacion"+i).readOnly = true;					
					document.getElementById("amortizacion"+i).value = 0;
			  }//for
           }else{
			   if(radio.value=="Finiquito"){
				   for (var i = 1; i <= 13; i++) {
					   document.getElementById("no_estimacion").readOnly = false;
					   document.getElementById("amortizacion"+i).readOnly = false;	
				    }//for
                }else{
					if (radio.value=="Estimacion"){
						for (var i = 1; i <= 13; i++) {
						   document.getElementById("no_estimacion").readOnly = false;
						   document.getElementById("amortizacion"+i).readOnly = false;	
						}//for
					}
				}
		   }//else
		}//Fin function			
	</script>


</head>

<body>
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
	<div id="encabezadoFijo">
		<a href="#" data-toggle="modal" data-target=".filtro"><div class="izquierda">
			<span class="glyphicon glyphicon-search"></span> &rlm; Filtrar
		</div> </a>
		<a href="#" data-toggle="modal" data-target=".nuevo"><div class="derecha">
			<span class="glyphicon glyphicon-plus"></span> &rlm; Agregar Contrato
		</div> </a>
		<!--<a href="#" data-toggle="modal" data-target=".estimacion"><div class="derecha">
			<span class="glyphicon glyphicon-plus"></span> &rlm; Agregar Estimacion
		</div></a>-->
		 		
	</div>    

	<div id="contenido">
		<br>
		<center>
		<table width="auto" height="auto" border="1" id="tabla">
		  <thead>
		    <tr align="center" class="titulo">
		      <td width="150px">CONTRATO</td>		      
		      <td width="100px">CLASIFICACION</td>  
		      <td width="auto">TRAMO</td>
		      <td width="300px">SUBCUENTA</td>    
		      <td width="auto">OBJETO</td>
		      <!--<td width="">ACTIVIDAD</td>-->
		      <td width="90px">MONTO</td>
		      <td width="90px">POR EJECUTAR</td>		
              <td width="80px">FECHA INICIO</td>
              <td width="80px">FECHA FIN</td>
              <td width="80px" style="display: none">EMPRESA</td>
		    </tr>
		  </thead>
		  <tbody>  
		<?php 
		$por_ejecutar = 0;

		//echo $sql;
		$rs = odbc_exec( $conn, $sql );
		if ( !$rs ) { 
			exit( "Error en la consulta SQL" ); 
		} 		
		while ( odbc_fetch_row($rs) ) { 
			$monto = odbc_result($rs, 'MONTO');
			$contrato = odbc_result($rs, 'CONTRATO');
			$clasificacion = odbc_result($rs, 'CLASIFICACION');
			$anticipo = odbc_result($rs, 'ANTICIPO');
			$totAnt = (($monto * $anticipo) / 100);

			$sql2 = "SELECT SUM(AMORTIZACION) AS AMORTIZACION FROM Estimaciones WHERE CLASIFICACION = '".$clasificacion."' AND CONTRATO = '".$contrato."'";
			//echo $sql;
			$rs2 = odbc_exec( $conn2, $sql2 );
			if ( !$rs2 ) { 
				exit( "Error en la consulta Estimaciones" ); 
			}
			while ( odbc_fetch_row($rs2) ) {				
				$amortizacion = odbc_result($rs2, 'AMORTIZACION');
			}
			
			$por_ejecutar = $monto - $amortizacion;
			
		    echo "<tr class = 'tabla' id='".$contrato."'>
				  <td>".odbc_result($rs, 'CONTRATO')."</td>			  
				  <td>".$clasificacion."</td>	  
				  <td>".odbc_result($rs, 'TRAMO')."</td>
				  <td>".odbc_result($rs, 'SUBCUENTA')."</td>
				  <td>".odbc_result($rs, 'OBJETO')."</td>
				  <td style='display: none'>".odbc_result($rs, 'ACTIVIDAD')."</td>
				  <td align='right'>$".number_format(odbc_result($rs, 'MONTO'),2,'.',',')."</td>
				  <td align='right'>$".number_format($por_ejecutar,2,'.',',')."</td>	
				  <td align='right'>".odbc_result($rs, 'FECHA_INICIO')."</td>
				  <td align='right'>".odbc_result($rs, 'FECHA_FINAL')."</td>
				  <td style='display: none'>".odbc_result($rs, 'EMPRESA')."</td>
				</tr>";
		}//While
		?>           
		  </tbody>
		</table>
		</center>	
	</div>


<!--NUEVO CONTRATO-->
<form action="Contratos.php" method="post" enctype="multipart/form-data" >
<div class="modal fade nuevo" id="nuevo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
      	<!--CUERPO-->
        <div class="row">
        	<div class="col-lg-2">Clasificaci&oacute;n:</div>
        	<div class="col-lg-3">
            	<select class="form-control" id="clasificacion" name="clasificacion">
                	<option value="CAPEX">CAPEX</option>
                	<option value="CAPEX AMERICAS">CAPEX AMERICAS</option>
                	<option value="OPEX AMERICAS">OPEX AMERICAS</option>
                	<option value="OPEX">OPEX</option>
                	<option value="OT CAPEX">OT OPEX</option>
                	<option value="OT CAPEX LA JOYA">OT CAPEX LA JOYA</option>
                </select>
            </div>
            <div class="col-lg-2" align="right"></div>
            <div class="col-lg-2" align="right">Fecha Inicial:</div>
        	<div class="col-lg-3"><input type="date" class="form-control" id="fecha_inicial" name="fecha_inicial" value="<?php echo date("Y-m-d"); ?>"></div>     	
        </div><br>
        <div class="row">
        	<div class="col-lg-2">Subcuenta:</div>
        	<div class="col-lg-5">
                <select class="form-control" id="subcuenta" name="subcuenta">
                	<option value="CONCURSOS Y LICITACIONES">CONCURSOS Y LICITACIONES</option>
                	<option value="DERECHO DE VIA">DERECHO DE VIA</option>
                	<option value="DRENAJE">DRENAJE</option>
                	<option value="ESTUDIOS Y PROYECTOS">ESTUDIOS Y PROYECTOS</option>
                	<option value="INVERSION NUEVA">INVERSION NUEVA</option>
                	<option value="PLAZAS DE COBRO">PLAZAS DE COBRO</option>
                	<option value="PUENTES Y ESTRUCTURAS">PUENTES Y ESTRUCTURAS</option>
                	<option value="REUBICACION DE BASES DE CONSERVACION">REUBICACION DE BASES DE CONSERVACION</option>
                	<option value="SE&Ntilde;ALAMIENTO HORIZONTAL">SE&Ntilde;ALAMIENTO HORIZONTAL</option>
                	<option value="SE&Ntilde;ALAMIENTO VERTICAL">SE&Ntilde;ALAMIENTO VERTICAL</option>
                	<option value="SUPERFICIE DE RODAMIENTO">SUPERFICIE DE RODAMIENTO</option>
                	<option value="SUPERVISION ">SUPERVISION </option>
                </select>
            </div>  
        	<div class="col-lg-2" align="right">Fecha Final:</div>
        	<div class="col-lg-3"><input type="date" class="form-control" id="fecha_final" name="fecha_final" value="<?php echo date("Y-m-d"); ?>"></div>                    	
        </div><br/>        
        <div class="row">
        	<div class="col-lg-2">Actividad:</div>
        	<div class="col-lg-6">
        	<input type="text" class="form-control" id="actividad" name="actividad" autocomplete="off"></div>
        </div><br>
        <div class="row">
        	<div class="col-lg-2">Objeto:</div>
        	<div class="col-lg-6"><textarea rows="1" cols="60" id="objeto" name="objeto" class="form-control"></textarea></div>
        </div><br>
        <div class="row">
        	<div class="col-lg-2">Contrato:</div>
        	<div class="col-lg-6"><input type="text" class="form-control" id="contrato" name="contrato" required placeholder="00-<?php echo date("y"); ?>-RCO-EMPRESA-CAPEX"></div>
        </div>
        <br/>
        
        <br/>
        <div class="row">
        	<div class="col-lg-2">Empresa:</div>
        	<div class="col-lg-4"><input type="text" class="form-control" id="empresa" name="empresa" required autocomplete="off"></div>
        	<div class="col-lg-1">Anticipo:</div>
        	<div class="col-lg-2"><input type="number" class="form-control" id="anticipo" name="anticipo" value="0" min="0"></div>
            <div class="col-lg-1" align="left"><strong>%</strong>&nbsp;</div>
        </div>  
        <br/>     
        <div class="row">
        	<div class="col-lg-2">Tramo 1:</div>
        	<div class="col-lg-4">
            	<select class="form-control" id="tramo1" name="tramo1">
                	<option value="">Seleccione una opci&oacute;n</option>
                	<option value="El Desperdicio - Lagos de Moreno">El Desperdicio - Lagos de Moreno</option>
                	<option value="El Desperdicio - Santa Maria de En Medio">El Desperdicio - Santa Maria de En Medio</option>
                	<option value="Leon - Aguascalientes">Leon - Aguascalientes</option>
                	<option value="Los Fresnos - Zapotlanejo">Los Fresnos - Zapotlanejo</option>
                	<option value="Maravatio - Los Fresnos">Maravatio - Los Fresnos</option>
                	<option value="Zapotlanejo - El Desperdicio">Zapotlanejo - El Desperdicio</option>
                	<option value="Zapotlanejo - Guadalajara">Zapotlanejo - Guadalajara</option>
                	<option value="La Barca - Jiquilpan">La Barca - Jiquilpan</option>
                	<option value="Tepic - San Blas">Tepic San Blas</option>
                	<option value="Ecuandureo - La Piedad">Ecuandureo - La Piedad</option>
                	<option value="Maravatio - Zitacuaro">Maravatio - Zitacuaro</option>
                	<option value="Libramiento Lagos de Moreno">Libramiento Lagos de Moreno</option>
                </select>
            </div>
        	<div class="col-lg-1">Importe:&nbsp;&nbsp;&nbsp;<strong>$</strong></div>
        	<div class="col-lg-2"><input type="number" id="importe1" name="importe1" class="form-control" value="0" onChange="Calculo()" min="0" step="any"></div>
        </div>    
        <div class="row">
        	<div class="col-lg-2">Tramo 2:</div>
        	<div class="col-lg-4">
            	<select class="form-control" id="tramo2" name="tramo2">
                	<option value="">Seleccione una opci&oacute;n</option>
                	<option value="El Desperdicio - Lagos de Moreno">El Desperdicio - Lagos de Moreno</option>
                	<option value="El Desperdicio - Santa Maria de En Medio">El Desperdicio - Santa Maria de En Medio</option>
                	<option value="Leon - Aguascalientes">Leon - Aguascalientes</option>
                	<option value="Los Fresnos - Zapotlanejo">Los Fresnos - Zapotlanejo</option>
                	<option value="Maravatio - Los Fresnos">Maravatio - Los Fresnos</option>
                	<option value="Zapotlanejo - El Desperdicio">Zapotlanejo - El Desperdicio</option>
                	<option value="Zapotlanejo - Guadalajara">Zapotlanejo - Guadalajara</option>
                	<option value="La Barca - Jiquilpan">La Barca - Jiquilpan</option>
                	<option value="Tepic - San Blas">Tepic San Blas</option>
                	<option value="Ecuandureo - La Piedad">Ecuandureo - La Piedad</option>
                	<option value="Maravatio - Zitacuaro">Maravatio - Zitacuaro</option>
                	<option value="Libramiento Lagos de Moreno">Libramiento Lagos de Moreno</option>
                </select>
            </div>
        	<div class="col-lg-1">Importe:&nbsp;&nbsp;&nbsp;<strong>$</strong></div>
        	<div class="col-lg-2"><input type="number" id="importe2" name="importe2" class="form-control" value="0" onChange="Calculo()" min="0" step="any"></div>
        </div>   
        <div class="row">
        	<div class="col-lg-2">Tramo 3:</div>
        	<div class="col-lg-4">
            	<select class="form-control" id="tramo3" name="tramo3">
                	<option value="">Seleccione una opci&oacute;n</option>
                	<option value="El Desperdicio - Lagos de Moreno">El Desperdicio - Lagos de Moreno</option>
                	<option value="El Desperdicio - Santa Maria de En Medio">El Desperdicio - Santa Maria de En Medio</option>
                	<option value="Leon - Aguascalientes">Leon - Aguascalientes</option>
                	<option value="Los Fresnos - Zapotlanejo">Los Fresnos - Zapotlanejo</option>
                	<option value="Maravatio - Los Fresnos">Maravatio - Los Fresnos</option>
                	<option value="Zapotlanejo - El Desperdicio">Zapotlanejo - El Desperdicio</option>
                	<option value="Zapotlanejo - Guadalajara">Zapotlanejo - Guadalajara</option>
                	<option value="La Barca - Jiquilpan">La Barca - Jiquilpan</option>
                	<option value="Tepic - San Blas">Tepic San Blas</option>
                	<option value="Ecuandureo - La Piedad">Ecuandureo - La Piedad</option>
                	<option value="Maravatio - Zitacuaro">Maravatio - Zitacuaro</option>
                	<option value="Libramiento Lagos de Moreno">Libramiento Lagos de Moreno</option>
                </select>
            </div>
        	<div class="col-lg-1">Importe:&nbsp;&nbsp;&nbsp;<strong>$</strong></div>
        	<div class="col-lg-2"><input type="number" id="importe3" name="importe3" class="form-control" value="0" onChange="Calculo()" min="0" step="any"></div>
        </div>      
        <div class="row">
        	<div class="col-lg-2">Tramo 4:</div>
        	<div class="col-lg-4">
            	<select class="form-control" id="tramo4" name="tramo4">
                	<option value="">Seleccione una opci&oacute;n</option>
                	<option value="El Desperdicio - Lagos de Moreno">El Desperdicio - Lagos de Moreno</option>
                	<option value="El Desperdicio - Santa Maria de En Medio">El Desperdicio - Santa Maria de En Medio</option>
                	<option value="Leon - Aguascalientes">Leon - Aguascalientes</option>
                	<option value="Los Fresnos - Zapotlanejo">Los Fresnos - Zapotlanejo</option>
                	<option value="Maravatio - Los Fresnos">Maravatio - Los Fresnos</option>
                	<option value="Zapotlanejo - El Desperdicio">Zapotlanejo - El Desperdicio</option>
                	<option value="Zapotlanejo - Guadalajara">Zapotlanejo - Guadalajara</option>
                	<option value="La Barca - Jiquilpan">La Barca - Jiquilpan</option>
                	<option value="Tepic - San Blas">Tepic San Blas</option>
                	<option value="Ecuandureo - La Piedad">Ecuandureo - La Piedad</option>
                	<option value="Maravatio - Zitacuaro">Maravatio - Zitacuaro</option>
                	<option value="Libramiento Lagos de Moreno">Libramiento Lagos de Moreno</option>
                </select>
            </div>
        	<div class="col-lg-1">Importe:&nbsp;&nbsp;&nbsp;<strong>$</strong></div>
        	<div class="col-lg-2"><input type="number" id="importe4" name="importe4" class="form-control" value="0" onChange="Calculo()" min="0" step="any"></div>
        </div>  
        <div class="row">
        	<div class="col-lg-2">Tramo 5:</div>
        	<div class="col-lg-4">
            	<select class="form-control" id="tramo5" name="tramo5">
                	<option value="">Seleccione una opci&oacute;n</option>
                	<option value="El Desperdicio - Lagos de Moreno">El Desperdicio - Lagos de Moreno</option>
                	<option value="El Desperdicio - Santa Maria de En Medio">El Desperdicio - Santa Maria de En Medio</option>
                	<option value="Leon - Aguascalientes">Leon - Aguascalientes</option>
                	<option value="Los Fresnos - Zapotlanejo">Los Fresnos - Zapotlanejo</option>
                	<option value="Maravatio - Los Fresnos">Maravatio - Los Fresnos</option>
                	<option value="Zapotlanejo - El Desperdicio">Zapotlanejo - El Desperdicio</option>
                	<option value="Zapotlanejo - Guadalajara">Zapotlanejo - Guadalajara</option>
                	<option value="La Barca - Jiquilpan">La Barca - Jiquilpan</option>
                	<option value="Tepic - San Blas">Tepic San Blas</option>
                	<option value="Ecuandureo - La Piedad">Ecuandureo - La Piedad</option>
                	<option value="Maravatio - Zitacuaro">Maravatio - Zitacuaro</option>
                	<option value="Libramiento Lagos de Moreno">Libramiento Lagos de Moreno</option>
                </select>
            </div>
        	<div class="col-lg-1">Importe:&nbsp;&nbsp;&nbsp;<strong>$</strong></div>
        	<div class="col-lg-2"><input type="number" id="importe5" name="importe5" class="form-control" value="0" onChange="Calculo()" min="0" step="any"></div>
        </div> 
        <div class="row">
        	<div class="col-lg-2">Tramo 6:</div>
        	<div class="col-lg-4">
            	<select class="form-control" id="tramo6" name="tramo6">
                	<option value="">Seleccione una opci&oacute;n</option>
                	<option value="El Desperdicio - Lagos de Moreno">El Desperdicio - Lagos de Moreno</option>
                	<option value="El Desperdicio - Santa Maria de En Medio">El Desperdicio - Santa Maria de En Medio</option>
                	<option value="Leon - Aguascalientes">Leon - Aguascalientes</option>
                	<option value="Los Fresnos - Zapotlanejo">Los Fresnos - Zapotlanejo</option>
                	<option value="Maravatio - Los Fresnos">Maravatio - Los Fresnos</option>
                	<option value="Zapotlanejo - El Desperdicio">Zapotlanejo - El Desperdicio</option>
                	<option value="Zapotlanejo - Guadalajara">Zapotlanejo - Guadalajara</option>
                	<option value="La Barca - Jiquilpan">La Barca - Jiquilpan</option>
                	<option value="Tepic - San Blas">Tepic San Blas</option>
                	<option value="Ecuandureo - La Piedad">Ecuandureo - La Piedad</option>
                	<option value="Maravatio - Zitacuaro">Maravatio - Zitacuaro</option>
                	<option value="Libramiento Lagos de Moreno">Libramiento Lagos de Moreno</option>
                </select>
            </div>
        	<div class="col-lg-1">Importe:&nbsp;&nbsp;&nbsp;<strong>$</strong></div>
        	<div class="col-lg-2"><input type="number" id="importe6" name="importe6" class="form-control" value="0" onChange="Calculo()" min="0" step="any"></div>
        </div>   
        <div class="row">
        	<div class="col-lg-2">Tramo 7:</div>
        	<div class="col-lg-4">
            	<select class="form-control" id="tramo7" name="tramo7">
                	<option value="">Seleccione una opci&oacute;n</option>
                	<option value="El Desperdicio - Lagos de Moreno">El Desperdicio - Lagos de Moreno</option>
                	<option value="El Desperdicio - Santa Maria de En Medio">El Desperdicio - Santa Maria de En Medio</option>
                	<option value="Leon - Aguascalientes">Leon - Aguascalientes</option>
                	<option value="Los Fresnos - Zapotlanejo">Los Fresnos - Zapotlanejo</option>
                	<option value="Maravatio - Los Fresnos">Maravatio - Los Fresnos</option>
                	<option value="Zapotlanejo - El Desperdicio">Zapotlanejo - El Desperdicio</option>
                	<option value="Zapotlanejo - Guadalajara">Zapotlanejo - Guadalajara</option>
                	<option value="La Barca - Jiquilpan">La Barca - Jiquilpan</option>
                	<option value="Tepic - San Blas">Tepic San Blas</option>
                	<option value="Ecuandureo - La Piedad">Ecuandureo - La Piedad</option>
                	<option value="Maravatio - Zitacuaro">Maravatio - Zitacuaro</option>
                	<option value="Libramiento Lagos de Moreno">Libramiento Lagos de Moreno</option>
                </select>
            </div>
        	<div class="col-lg-1">Importe:&nbsp;&nbsp;&nbsp;<strong>$</strong></div>
        	<div class="col-lg-2"><input type="number" id="importe7" name="importe7" class="form-control" value="0" onChange="Calculo()" min="0" step="any"></div>
        </div>  
        <div class="row">
        	<div class="col-lg-2">Tramo 8:</div>
        	<div class="col-lg-4">
            	<select class="form-control" id="tramo8" name="tramo8">
                	<option value="">Seleccione una opci&oacute;n</option>
                	<option value="El Desperdicio - Lagos de Moreno">El Desperdicio - Lagos de Moreno</option>
                	<option value="El Desperdicio - Santa Maria de En Medio">El Desperdicio - Santa Maria de En Medio</option>
                	<option value="Leon - Aguascalientes">Leon - Aguascalientes</option>
                	<option value="Los Fresnos - Zapotlanejo">Los Fresnos - Zapotlanejo</option>
                	<option value="Maravatio - Los Fresnos">Maravatio - Los Fresnos</option>
                	<option value="Zapotlanejo - El Desperdicio">Zapotlanejo - El Desperdicio</option>
                	<option value="Zapotlanejo - Guadalajara">Zapotlanejo - Guadalajara</option>
                	<option value="La Barca - Jiquilpan">La Barca - Jiquilpan</option>
                	<option value="Tepic - San Blas">Tepic San Blas</option>
                	<option value="Ecuandureo - La Piedad">Ecuandureo - La Piedad</option>
                	<option value="Maravatio - Zitacuaro">Maravatio - Zitacuaro</option>
                	<option value="Libramiento Lagos de Moreno">Libramiento Lagos de Moreno</option>
                </select>
            </div>
        	<div class="col-lg-1">Importe:&nbsp;&nbsp;&nbsp;<strong>$</strong></div>
        	<div class="col-lg-2"><input type="number" id="importe8" name="importe8" class="form-control" value="0" onChange="Calculo()" min="0" step="any"></div>
        </div>    
        <div class="row">
        	<div class="col-lg-2">Tramo 9:</div>
        	<div class="col-lg-4">
            	<select class="form-control" id="tramo9" name="tramo9">
                	<option value="">Seleccione una opci&oacute;n</option>
                	<option value="El Desperdicio - Lagos de Moreno">El Desperdicio - Lagos de Moreno</option>
                	<option value="El Desperdicio - Santa Maria de En Medio">El Desperdicio - Santa Maria de En Medio</option>
                	<option value="Leon - Aguascalientes">Leon - Aguascalientes</option>
                	<option value="Los Fresnos - Zapotlanejo">Los Fresnos - Zapotlanejo</option>
                	<option value="Maravatio - Los Fresnos">Maravatio - Los Fresnos</option>
                	<option value="Zapotlanejo - El Desperdicio">Zapotlanejo - El Desperdicio</option>
                	<option value="Zapotlanejo - Guadalajara">Zapotlanejo - Guadalajara</option>
                	<option value="La Barca - Jiquilpan">La Barca - Jiquilpan</option>
                	<option value="Tepic - San Blas">Tepic San Blas</option>
                	<option value="Ecuandureo - La Piedad">Ecuandureo - La Piedad</option>
                	<option value="Maravatio - Zitacuaro">Maravatio - Zitacuaro</option>
                	<option value="Libramiento Lagos de Moreno">Libramiento Lagos de Moreno</option>
                </select>
            </div>
        	<div class="col-lg-1">Importe:&nbsp;&nbsp;&nbsp;<strong>$</strong></div>
        	<div class="col-lg-2"><input type="number" id="importe9" name="importe9" class="form-control" value="0" onChange="Calculo()" min="0" step="any"></div>
        </div>  
        <div class="row">
        	<div class="col-lg-2">Tramo 10:</div>
        	<div class="col-lg-4">
            	<select class="form-control" id="tramo10" name="tramo10">
                	<option value="">Seleccione una opci&oacute;n</option>
                	<option value="El Desperdicio - Lagos de Moreno">El Desperdicio - Lagos de Moreno</option>
                	<option value="El Desperdicio - Santa Maria de En Medio">El Desperdicio - Santa Maria de En Medio</option>
                	<option value="Leon - Aguascalientes">Leon - Aguascalientes</option>
                	<option value="Los Fresnos - Zapotlanejo">Los Fresnos - Zapotlanejo</option>
                	<option value="Maravatio - Los Fresnos">Maravatio - Los Fresnos</option>
                	<option value="Zapotlanejo - El Desperdicio">Zapotlanejo - El Desperdicio</option>
                	<option value="Zapotlanejo - Guadalajara">Zapotlanejo - Guadalajara</option>
                	<option value="La Barca - Jiquilpan">La Barca - Jiquilpan</option>
                	<option value="Tepic - San Blas">Tepic San Blas</option>
                	<option value="Ecuandureo - La Piedad">Ecuandureo - La Piedad</option>
                	<option value="Maravatio - Zitacuaro">Maravatio - Zitacuaro</option>
                	<option value="Libramiento Lagos de Moreno">Libramiento Lagos de Moreno</option>
                </select>
            </div>
        	<div class="col-lg-1">Importe:&nbsp;&nbsp;&nbsp;<strong>$</strong></div>
        	<div class="col-lg-2"><input type="number" id="importe10" name="importe10" class="form-control" value="0" onChange="Calculo()" min="0" step="any"></div>
        </div>   
        <div class="row">
        	<div class="col-lg-2">Tramo 11:</div>
        	<div class="col-lg-4">
            	<select class="form-control" id="tramo11" name="tramo11">
                	<option value="">Seleccione una opci&oacute;n</option>
                	<option value="El Desperdicio - Lagos de Moreno">El Desperdicio - Lagos de Moreno</option>
                	<option value="El Desperdicio - Santa Maria de En Medio">El Desperdicio - Santa Maria de En Medio</option>
                	<option value="Leon - Aguascalientes">Leon - Aguascalientes</option>
                	<option value="Los Fresnos - Zapotlanejo">Los Fresnos - Zapotlanejo</option>
                	<option value="Maravatio - Los Fresnos">Maravatio - Los Fresnos</option>
                	<option value="Zapotlanejo - El Desperdicio">Zapotlanejo - El Desperdicio</option>
                	<option value="Zapotlanejo - Guadalajara">Zapotlanejo - Guadalajara</option>
                	<option value="La Barca - Jiquilpan">La Barca - Jiquilpan</option>
                	<option value="Tepic - San Blas">Tepic San Blas</option>
                	<option value="Ecuandureo - La Piedad">Ecuandureo - La Piedad</option>
                	<option value="Maravatio - Zitacuaro">Maravatio - Zitacuaro</option>
                	<option value="Libramiento Lagos de Moreno">Libramiento Lagos de Moreno</option>
                </select>
            </div>
        	<div class="col-lg-1">Importe:&nbsp;&nbsp;&nbsp;<strong>$</strong></div>
        	<div class="col-lg-2"><input type="number" id="importe11" name="importe11" class="form-control" value="0" onChange="Calculo()" min="0" step="any"></div>
        </div>    
        <div class="row">
        	<div class="col-lg-2">Tramo 12:</div>
        	<div class="col-lg-4">
            	<select class="form-control" id="tramo12" name="tramo12">
                	<option value="">Seleccione una opci&oacute;n</option>
                	<option value="El Desperdicio - Lagos de Moreno">El Desperdicio - Lagos de Moreno</option>
                	<option value="El Desperdicio - Santa Maria de En Medio">El Desperdicio - Santa Maria de En Medio</option>
                	<option value="Leon - Aguascalientes">Leon - Aguascalientes</option>
                	<option value="Los Fresnos - Zapotlanejo">Los Fresnos - Zapotlanejo</option>
                	<option value="Maravatio - Los Fresnos">Maravatio - Los Fresnos</option>
                	<option value="Zapotlanejo - El Desperdicio">Zapotlanejo - El Desperdicio</option>
                	<option value="Zapotlanejo - Guadalajara">Zapotlanejo - Guadalajara</option>
                	<option value="La Barca - Jiquilpan">La Barca - Jiquilpan</option>
                	<option value="Tepic - San Blas">Tepic San Blas</option>
                	<option value="Ecuandureo - La Piedad">Ecuandureo - La Piedad</option>
                	<option value="Maravatio - Zitacuaro">Maravatio - Zitacuaro</option>
                	<option value="Libramiento Lagos de Moreno">Libramiento Lagos de Moreno</option>
                </select>
            </div>
        	<div class="col-lg-1">Importe:&nbsp;&nbsp;&nbsp;<strong>$</strong></div>
        	<div class="col-lg-2"><input type="number" id="importe12" name="importe12" class="form-control" value="0" onChange="Calculo()" min="0" step="any"></div>
        </div>    
        <div class="row">
        	<div class="col-lg-2">Tramo 13:</div>
        	<div class="col-lg-4">
            	<select class="form-control" id="tramo13" name="tramo13">
                	<option value="">Seleccione una opci&oacute;n</option>
                	<option value="El Desperdicio - Lagos de Moreno">El Desperdicio - Lagos de Moreno</option>
                	<option value="El Desperdicio - Santa Maria de En Medio">El Desperdicio - Santa Maria de En Medio</option>
                	<option value="Leon - Aguascalientes">Leon - Aguascalientes</option>
                	<option value="Los Fresnos - Zapotlanejo">Los Fresnos - Zapotlanejo</option>
                	<option value="Maravatio - Los Fresnos">Maravatio - Los Fresnos</option>
                	<option value="Zapotlanejo - El Desperdicio">Zapotlanejo - El Desperdicio</option>
                	<option value="Zapotlanejo - Guadalajara">Zapotlanejo - Guadalajara</option>
                	<option value="La Barca - Jiquilpan">La Barca - Jiquilpan</option>
                	<option value="Tepic - San Blas">Tepic San Blas</option>
                	<option value="Ecuandureo - La Piedad">Ecuandureo - La Piedad</option>
                	<option value="Maravatio - Zitacuaro">Maravatio - Zitacuaro</option>
                	<option value="Libramiento Lagos de Moreno">Libramiento Lagos de Moreno</option>
                </select>
            </div>
        	<div class="col-lg-1">Importe:&nbsp;&nbsp;&nbsp;<strong>$</strong></div>
        	<div class="col-lg-2"><input type="number" id="importe13" name="importe13" class="form-control" value="0" onChange="Calculo()" min="0" step="any"></div>
        </div>   
        <br>
        <div class="row">
        	<div class="col-lg-5"></div>
        	<div class="col-lg-2"><strong>Importe Total:&nbsp;&nbsp;&nbsp;&nbsp;$</strong></div>
        	<div class="col-lg-2"><input type="number" id="importetotal" name="importetotal" class="form-control" value="0" readonly></div>
        	<div class="col-lg-1"><strong>Anticipo:&nbsp;$</strong></div>
        	<div class="col-lg-2"><input type="number" id="anticipototal" name="anticipototal" class="form-control" value="0" readonly></div>
        </div>
        <!--FIN-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="refresh()">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="guardarcontrato" id="guardarcontrato">Guardar</button>
      </div>
    </div>
  </div>
</div>
</form>

<!--FILTRO-->
<form action="Contratos.php" method="post" enctype="multipart/form-data" >
<div class="modal fade filtro" id="filtro" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
      	<!--CUERPO-->
        <div class="row">
        	<div class="col-lg-2">Clasificaci&oacute;n:</div>
        	<div class="col-lg-4">
            	<select id="clasFiltro" name="clasFiltro" class="form-control">
                	<option value="">Selecciona una opci&oacute;n</option>
                	<option value="CAPEX">CAPEX</option>
                	<option value="CAPEX AMERICAS">CAPEX AMERICAS</option>
                	<option value="OPEX AMERICAS">OPEX AMERICAS</option>
                	<option value="OPEX">OPEX</option>
                	<option value="OT CAPEX">OT OPEX</option>
                	<option value="OT CAPEX LA JOYA">OT CAPEX LA JOYA</option>
                	<option value="TODOS">TODOS</option>
                </select>
            </div>
        	<div class="col-lg-2">Subcuenta:</div>
        	<div class="col-lg-4">
            	<select id="subcuentaFiltro" name="subcuentaFiltro" class="form-control">
            	<option value="">Selecciona una opci&oacute;n</option>
                	<option value="CONCURSOS Y LICITACIONES">CONCURSOS Y LICITACIONES</option>
                	<option value="DERECHO DE VIA">DERECHO DE VIA</option>
                	<option value="DRENAJE">DRENAJE</option>
                	<option value="ESTUDIOS Y PROYECTOS">ESTUDIOS Y PROYECTOS</option>
                	<option value="INVERSION NUEVA">INVERSION NUEVA</option>
                	<option value="PLAZAS DE COBRO">PLAZAS DE COBRO</option>
                	<option value="PUENTES Y ESTRUCTURAS">PUENTES Y ESTRUCTURAS</option>
                	<option value="REUBICACION DE BASES DE CONSERVACION">REUBICACION DE BASES DE CONSERVACION</option>
                	<option value="SE&Ntilde;ALAMIENTO HORIZONTAL">SE&Ntilde;ALAMIENTO HORIZONTAL</option>
                	<option value="SE&Ntilde;ALAMIENTO VERTICAL">SE&Ntilde;ALAMIENTO VERTICAL</option>
                	<option value="SUPERFICIE DE RODAMIENTO">SUPERFICIE DE RODAMIENTO</option>
                	<option value="SUPERVISION">SUPERVISION</option>
                	<option value="TODOS">TODOS</option>
                </select>
            </div>
        </div>
        <br/>   
        <div class="row">
        	<div class="col-lg-2">Tramo:</div>
        	<div class="col-lg-4">
            	<select class="form-control" id="tramoFiltro" name="tramoFiltro">
                	<option value="">Seleccione una opci&oacute;n</option>
                	<?php 	
				      $i=1;
 				     //$selected="";
 				     $sql = "SELECT DISTINCT(TRAMO) from CatTramos";
				      echo $sql;
				      $rs = odbc_exec( $conn, $sql );
					      if ( !$rs ) { 
  						     exit( "Error en la consulta SQL" ); 
 					     }while ( odbc_fetch_row($rs) ) { 
       						$tramo = odbc_result($rs, 'TRAMO');	  	
      					 echo "<option id='".$tramo."'>".$tramo."</option>";
      					 $i++;
      					}//While  
     				?>
                	<option value="TODOS">TODOS</option>
                </select>
            </div>
        	<div class="col-lg-2">Proveedor:</div>
        	<div class="col-lg-4">
            	<select class="form-control" id="empresaFiltro" name="empresaFiltro">
                	<option value="">Seleccione una opci&oacute;n</option>
                    <?php 	
				      $i=1;
 				     //$selected="";	   
 				     $sql = "SELECT DISTINCT(EMPRESA) from Contratos order by EMPRESA";
				      echo $sql;
				      $rs = odbc_exec( $conn, $sql );
					      if ( !$rs ) { 
  						     exit( "Error en la consulta SQL" ); 
 					     }while ( odbc_fetch_row($rs) ) { 
       						$empresa = odbc_result($rs, 'EMPRESA');	  	
      					 echo "<option id='".$empresa."'>".$empresa."</option>";		   	     
      					 $i++;
      					}//While  
     				?>
                	<option value="TODOS">TODOS</option>
                </select>
            </div>
        </div>
        <br>
        <div class="row">
        	<div class="col-lg-2">A&ntilde;o:</div>
        	<div class="col-lg-2"><input type="number" class="form-control" id="anioFiltro" name="anioFiltro" maxlength="4"></div>
        </div> 
        <!--FIN-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="refresh()">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="filtro" id="filtro">Filtrar</button>
      </div>
    </div>
  </div>
</div>
</form>

<!-- VISUALIZACION DE CONTRATO -->
<form action="Contratos.php" method="post" enctype="multipart/form-data" >
<div class="modal fade detalle" id="detalle" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <center><h4><strong>Detalle de Contrato</strong></h4></center>
      </div>
      <div class="modal-body">
      	<!--CUERPO-->      	
      	<input type="hidden" class="form-control" id="id_contrato" name="id_contrato" value="<?php $folio; ?>">
      	<div class="row">
        	<div class="col-lg-2">Clasificaci&oacute;n:</div>
        	<div class="col-lg-2">
            	<select class="form-control" id="clasificacion1" name="clasificacion1">
                	<option value="">Selecciona una opci&oacute;n</option>
                	<option value="CAPEX">CAPEX</option>
                	<option value="CAPEX AMERICAS">CAPEX AMERICAS</option>
                	<option value="OPEX AMERICAS">OPEX AMERICAS</option>
                	<option value="OPEX">OPEX</option>
                	<option value="OT CAPEX">OT OPEX</option>
                	<option value="OT CAPEX LA JOYA">OT CAPEX LA JOYA</option>
                </select>
            </div>
            <div class="col-lg-1">Empresa:</div>
        	<div class="col-lg-3"><input type="text" class="form-control" id="empresa1" name="empresa1" readonly></div>
        	<div class="col-lg-1">Contrato:</div>
        	<div class="col-lg-3"><input type="text" class="form-control" id="contrato1" name="contrato1" readonly></div>         	        	
        </div><br>
        <div class="row">        	
        	<div class="col-lg-2">Subcuenta:</div>
        	<div class="col-lg-6">
            	<select class="form-control" id="subcuenta1" name="subcuenta1">
                	<option value="CONCURSOS Y LICITACIONES">CONCURSOS Y LICITACIONES</option>
                	<option value="DERECHO DE VIA">DERECHO DE VIA</option>
                	<option value="DRENAJE">DRENAJE</option>
                	<option value="ESTUDIOS Y PROYECTOS">ESTUDIOS Y PROYECTOS</option>
                	<option value="INVERSION NUEVA">INVERSION NUEVA</option>
                	<option value="PLAZAS DE COBRO">PLAZAS DE COBRO</option>
                	<option value="PUENTES Y ESTRUCTURAS">PUENTES Y ESTRUCTURAS</option>
                	<option value="REUBICACION DE BASES DE CONSERVACION">REUBICACION DE BASES DE CONSERVACION</option>
                	<option value="SE&Ntilde;ALAMIENTO HORIZONTAL">SE&Ntilde;ALAMIENTO HORIZONTAL</option>
                	<option value="SE&Ntilde;ALAMIENTO VERTICAL">SE&Ntilde;ALAMIENTO VERTICAL</option>
                	<option value="SUPERFICIE DE RODAMIENTO">SUPERFICIE DE RODAMIENTO</option>
                	<option value="SUPERVISION">SUPERVISION</option>
                </select>
            </div>
        	<div class="col-lg-2">Fecha Inicial:</div>
        	<div class="col-lg-2"><input type="text" class="form-control" id="fecha_ini1" name="fecha_ini1" readonly></div>
        </div><br/>  
        <div class="row">
        	<div class="col-lg-2">Tramo:</div>
        	<div class="col-lg-6"><input type="text" class="form-control" id="tramos1" name="tramos1" readonly></div>
        	<div class="col-lg-2">Fecha Final:</div>
        	<div class="col-lg-2"><input type="text" class="form-control" id="fecha_fin1" name="fecha_fin1" readonly></div>
        </div><br/>  
        <div class="row">
        	<div class="col-lg-2">Actividad:</div>
        	<div class="col-lg-6"><input type="text" class="form-control" id="actividad1" name="actividad1" autocomplete="off"></div>
        	<div class="col-lg-2">Total Contrato:</div>
        	<div class="col-lg-2" align="right"><span style="color:#FF0004" id="total"></span></div>
        </div><br/>  
        <div class="row">
        	<div class="col-lg-2">Importe a cancelar:</div>
        	<div class="col-lg-6"><input type="number" class="form-control" id="cancelar" name="cancelar" step="any"><input type="hidden" class="form-control" id="poreje" name="poreje"></div>
        	<div class="col-lg-2">Por Ejecutar:</div>
        	<div class="col-lg-2" align="right"><span style="color:#FF0004" id="por_ejecutar"></span></div>
        </div><br/>  
        <div class="row">
        	<div class="col-lg-8" align="center"><strong><span style="font-size:18px; color:#9A0002" id="terminado"></span></strong></div>
        	<div class="col-lg-2">Penalizaciones:</div>
        	<div class="col-lg-2" align="right"><span style="color:#FF0004" id="penalizaciones"></span></div>
        </div>
        <hr size="3">
        <div id="mostrarestimacion"></div>
        <!--FIN-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="refresh()">Cerrar</button>
        <button type='button' class='btn btn-warning' id="agregarEst" name="agregarEst" onClick="AgregarEstimacion()">Estimaci&oacute;n</button>
        <button type="submit" class="btn btn-success" id="actualizarcontrato" name="actualizarcontrato">Actualizar</button>
        <button type="submit" class="btn btn-primary" onclick="Adjuntar()">Adjuntar</button>
      </div>
    </div>
  </div>
</div>
</form>


<!-- NUEVA ESTIMACION -->
<form action="Contratos.php" method="post" enctype="multipart/form-data" >
<div class="modal fade estimacion" id="estimacion" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
      </div>
      <div class="modal-body">
      	<!--CUERPO-->
            	<input type="hidden" id="clasificacionest" name="clasificacionest" class="form-control">
            	<input type="hidden" id="contratoest" name="contratoest" class="form-control">
        <div class="row">
        	<div class="col-lg-1"></div>
        	<div class="col-lg-10" align="center">
                <label class="radio-inline">
                  <input type="radio" name="detalleInfo" id="inlineRadio1" onclick="toggle(this)" value="Anticipo"> Anticipo
                </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<label class="radio-inline">
                  <input type="radio" name="detalleInfo" id="inlineRadio3" onclick="toggle(this)" value="Estimacion"> Estimaci&oacute;n
                </label>              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
               <label class="radio-inline">
                  <input type="radio" name="detalleInfo" id="inlineRadio2" onclick="toggle(this)" value="Finiquito" checked> Finiquito
                </label> 
            </div>  
        	<div class="col-lg-1"></div>
        </div>
        <br/>
        <div id="fechas" style="display:none">
            <div class="row">
                <div class="col-lg-2">Estimaci&oacute;n No:</div>
                <div class="col-lg-3"><input type="text" name="no_estimacion" id="no_estimacion" class="form-control"></div>      
                <div class="col-lg-1"><a href="#" onClick="VerEstimaciones()"><strong><img src="images/LUPA.png" width="25" height="25" alt=""/></strong></a></div>   
                <div class="col-lg-2">Anticipo:</div>
                <div class="col-lg-3"><input type="hidden" name="saldo_anticipo" id="saldo_anticipo" class="form-control" readonly><input type="text" name="anticipo_monto" id="anticipo_monto" class="form-control" readonly></div>              
            </div>
            <br/>
            <div class="row">
                <div class="col-lg-2">Fecha Inicio:</div>
                <div class="col-lg-3"><input type="date" name="fechainicioest" id="fechainicioest" value="<?php echo date("Y-m-d"); ?>" class="form-control"></div>
                <div class="col-lg-1"></div>
                <div class="col-lg-2">Fecha Fin:</div>
                <div class="col-lg-3"><input type="date" name="fechafinest" id="fechafinest" value="<?php echo date("Y-m-d"); ?>" class="form-control"></div>
            </div>
            <br/>
            <div class="row">
                <div class="col-lg-2">Factura:</div>
                <div class="col-lg-3"><input type='text' name='factura' id='factura' class='form-control'></div>
                <!--<div class="col-lg-3" align="right">Monto por amortizar</div>
                <div class="col-lg-2"><input type="text" id="amor_sug1" name="amor_sug1" class="form-control" readonly></div>-->
        </div>
        <br/>
        <br/>
        <div id="mostrar_estimacion">
        </div>
        <!--FIN-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="refresh()" id="CerrarEstimacion" name="CerrarEstimacion">Cerrar</button>
        <button type="button" class="btn btn-primary" onClick="GuardaEstimacion()">Guardar</button>
      </div>
    </div>
  </div>
</div>
</form>

</body>
</html>