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
$folio ='';
/***************************************************GUARDAR PRESUPUESTO**************************************************/	
if (isset($_REQUEST['guardarpresupuesto'])) {
	$presupuesto = $_POST["nuevopresupuesto"];	
	$clasificacion = $_POST["nuevaclasificacion"];	
	$anio = $_POST["anio"];
	
	$sql = "INSERT INTO Presupuesto VALUES('".$presupuesto."','".$clasificacion."','".$anio."')";
	//echo $sql;
	$rs = odbc_exec( $conn, $sql );
	if ( !$rs ) {
		exit( "Error en la consulta SQL" ); 
	}  

}
/***************************************************MODIFICAR PRESUPUESTO**************************************************/	
if (isset($_REQUEST['modificarpresupuesto'])) {
	$actividad = $_POST["actividad"];
	$tramo = $_POST["tramo"];
	$subcuenta = $_POST["subcuenta"];
	$concepto = $_POST["concepto"];
	$unidad = $_POST["unidad"];
	$precio = $_POST["precio"];
	$cantidad = $_POST["cantidad"];
	$importe = $_POST["importe"];
	$presupuestoid = $_POST["presupuestoid"];

	$sql = "INSERT INTO PresupuestoDet VALUES('".$presupuestoid."','".$subcuenta."','".$concepto."','".$unidad."','".$precio."','".$cantidad."','".$importe."','".$tramo."','".$actividad."')";
	//echo $sql;
	$rs = odbc_exec( $conn, $sql );
	if ( !$rs ) { 
		exit( "Error en la consulta SQL" );  
	}  

}
/***************************************************FILTRO*************************************************/
if (isset($_REQUEST['filtro'])) {
	$clasificacion = $_POST["clasFiltro"];
	$anio = $_POST["anioFiltro"];
	$presupuesto = $_POST["presupuestoFiltro"];


	if ($clasificacion <> "TODOS" && $clasificacion <> ""){
		$opc1 = " AND CLASIFICACION = '".$clasificacion."'";
	}else{
		$opc1 = "";
	}
	if ($anio <> "TODOS" && $anio <> ""){
		$opc1.= " AND ANIO = '".$anio."'";
	}else{
		$opc1.= "";
	}
	if ($presupuesto <> "TODOS" && $presupuesto <> ""){
		$opc1.= " AND DESCRIPCION = '".$presupuesto."'";
	}else{
		$opc1.= "";
	}

	$sql = "SELECT * FROM PresupuestoDet WHERE DESCRIPCION <> '' ".$opc1."";
	//echo $sql;
	
}else{
	$sql = "SELECT * FROM PresupuestoDet";
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Presupuestos</title>
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
			color:white; 
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
			
			$("#subcuenta").click(function(){	
				//alert();			    			
				$("#subcuenta option:selected").each(function () {
			     	elegido = $(this).val();
			     	$.post("consultaAvanceDiario.php", { BuscarSubcuenta: '', subcuenta: elegido }, function(data){	
					 	//alert(data);
					 	$("#concepto").html(data);
			   		});			
              });				
			});
			
			$("#concepto").click(function(){	
				//alert();			    			
				$("#concepto option:selected").each(function () {
			     	elegido = $(this).val();
			     	$.post("consultaAvanceDiario.php", { BuscarConcepto: '', concepto: elegido }, function(data){	
					 	//alert(data);
					 	$("#unidad").val(data);
			   		});			
              });				
			});
			
			$("#presupuesto").click(function(){	
				//alert();			    			
				$("#presupuesto option:selected").each(function () {
			     	elegido = $(this).val();
			     	$.post("consultaAvanceDiario.php", { BuscarId: '', id: elegido }, function(data){	
					 	//alert(data);
					 	$("#presupuestoid").val(data);
			   		});			
              });				
			});
			
			/*$("#contratoest").click(function(){
				var clasificacion = $("#clasificacionest").val();				    			
				$("#contratoest option:selected").each(function () {
			     	elegido = $(this).val();
			     	$.post("consultaAvanceDiario.php", { Mostrar: "", contrato: elegido, Clasificacion: clasificacion }, function(data){
						var token = data.split();
						var variable = token[0].split('*');	
						
					 	$("#mostrar_estimacion").html(variable[0]);
						$("#saldo_anticipo").val(variable[1]);
						$("#anticipo_monto").val("$ "+variable[2]);
						document.getElementById("fechas").style.display = "block";
			   		});			
              });				
			});

			$("#tabla tbody tr").click(function (){			
				var id = $(this).attr("id");				
				//alert(id);
				
				$("#detalle").modal('show');
				$("#contrato1").val($(this).find("td").eq(0).html());
				$("#clasificacion1").val($(this).find("td").eq(1).html());
				$("#subcuenta1").val($(this).find("td").eq(2).html());				
				$("#fecha_ini1").val($(this).find("td").eq(8).html());
				$("#fecha_fin1").val($(this).find("td").eq(9).html());
				$("#tramos1").val($(this).find("td").eq(10).html());
				$("#empresa1").val($(this).find("td").eq(11).html());
				$("#id_contrato").val(id);
			});	*/	
						

		});//document.ready
		
		/************************************CALCULO DE IMPORTE Y ANTICIPO*********************************/
			function Calculo(){
				//alert();
				$("#importe").val(parseFloat($("#precio").val()) * parseFloat($("#cantidad").val()));
																	
			}
			
			
	</script>


</head>

<body>
	<header>
		<img src="images/HEADBIOMETRICO.png"  width="153" height="46">			
		<a href="index.php"><img class="derecha" src="images/cerrarsesion.png"></a>
		<a href="Inicio.php"><span>Cat&aacute;logos</span></a>
		<a href="Almacen.php"><span>Almac&eacute;n</span></a>
		<a href=""><span>Avance de Obra</span></a>
		<a href=""><span>Presupuestos</span></a>
		<a href=""><span>Maquinaria</span></a>
	</header>
	<div id="encabezadoFijo">
		<a href="#" data-toggle="modal" data-target=".filtro"><div class="izquierda">
			<span class="glyphicon glyphicon-search"></span> &rlm; Filtrar
		</div> </a>			
		<a href="#" data-toggle="modal" data-target=".nuevo"><div class="derecha">
			<span class="glyphicon glyphicon-plus"></span> &rlm; Agregar Presupuesto
		</div> </a>
		<a href="#" data-toggle="modal" data-target=".modificar"><div class="derecha">
			<span class="glyphicon glyphicon-edit"></span> &rlm; Modificar Presupuesto 
		</div> </a>
		 		
	</div>    

	<div id="contenido">
		<br>
		<center>
		<table width="auto" height="auto" border="1" id="tabla">
		  <thead>
		    <tr align="center" class="titulo">
		      <td colspan="3"></td>
		      <td colspan="3">FARAC I</td>
              <td colspan="3">Los Fresnos - Zapotlanejo</td>
              <td colspan="3">Zapotlanejo - Guadalajara</td>
              <td colspan="3">Zapotlanejo - El Desperdicio</td>
              <td colspan="3">El Desperdicio - Lagos de Moreno</td>
              <td colspan="3">El Desperdicio - Sta. Maria</td>
              <td colspan="3">Leon - Aguascalientes</td>
              <td colspan="3">Maravatio - Los Fresnos</td>
			</tr>
            <tr align='center' class='titulo'>
            	<td width='180px'>SUBCUENTA</td>
                <td width='200px'>CONCEPTO</td>
                <td width='80px'>UNIDAD</td>
                <td width='100px'>PU</td>
                <td width='100px'>CANTIDAD</td>
                <td width='100px'>IMPORTE</td> 
                <td width='100px'>PU</td>
                <td width='100px'>CANTIDAD</td> 
                <td width='100px'>IMPORTE</td> 
                <td width='100px'>PU</td>
                <td width='100px'>CANTIDAD</td>
                <td width='100px'>IMPORTE</td> 
                <td width='100px'>PU</td>
                <td width='100px'>CANTIDAD</td>
                <td width='100px'>IMPORTE</td>
                <td width='100px'>PU</td>
                <td width='100px'>CANTIDAD</td>
                <td width='100px'>IMPORTE</td> 
                <td width='100px'>PU</td>
                <td width='100px'>CANTIDAD</td>
                <td width='100px'>IMPORTE</td> 
                <td width='100px'>PU</td>
                <td width='100px'>CANTIDAD</td>
                <td width='100px'>IMPORTE</td> 
                <td width='100px'>PU</td>
                <td width='100px'>CANTIDAD</td>
                <td width='100px'>IMPORTE</td>  
			</tr>
		   </thead>
		   <tbody>
           <?php
				//$sql = "SELECT * FROM PresupuestoDet";
				//echo $sql;
				$rs = odbc_exec( $conn, $sql );
				if ( !$rs ) { 
					exit( "Error en la consulta SQL" ); 
				} 		
				while ( odbc_fetch_row($rs) ) { 					
					$cuenta = odbc_result($rs, 'CUENTA');
					$subcuenta = odbc_result($rs, 'SUBCUENTA');
					$tramo1 = "<td></td><td></td><td></td>";
					$tramo2 = "<td></td><td></td><td></td>";
					$tramo3 = "<td></td><td></td><td></td>";
					$tramo4 = "<td></td><td></td><td></td>";
					$tramo5 = "<td></td><td></td><td></td>";
					$tramo6 = "<td></td><td></td><td></td>";
					$tramo7 = "<td></td><td></td><td></td>";
					$tramo8 = "<td></td><td></td><td></td>";

					$sql2 = "SELECT * FROM PresupuestoDet WHERE CUENTA = '".$cuenta."' AND SUBCUENTA = '".$subcuenta."'";
					//echo $sql2;
					$rs2 = odbc_exec( $conn2, $sql2 );
					if ( !$rs2 ) { 
						exit( "Error en la consulta SQL" ); 
					}
					while ( odbc_fetch_row($rs2) ) {
						$tramo = odbc_result($rs2, 'TRAMO');
						//$total = number_format(odbc_result($rs2, 'TOTAL'),2,'.',',');
						switch ($tramo) {
						  	case 'Los Fresnos - Zapotlanejo':
						  		$tramo2 = "<td>$".number_format(odbc_result($rs2, 'PU'),2,'.',',')."</td><td>".number_format(odbc_result($rs2, 'CANTIDAD'),2,'.',',')."</td><td align='right'>$".number_format(odbc_result($rs2, 'IMPORTE'),2,'.',',')."</td>";
						  		break;
						  	case 'Zapotlanejo - Guadalajara':
						  		$tramo3 = "<td>$".number_format(odbc_result($rs2, 'PU'),2,'.',',')."</td><td>".number_format(odbc_result($rs2, 'CANTIDAD'),2,'.',',')."</td><td align='right'>$".number_format(odbc_result($rs2, 'IMPORTE'),2,'.',',')."</td>";
						  		break;
						  	case 'Zapotlanejo - El Desperdicio':
								$tramo4 = "<td>$".number_format(odbc_result($rs2, 'PU'),2,'.',',')."</td><td>".number_format(odbc_result($rs2, 'CANTIDAD'),2,'.',',')."</td><td align='right'>$".number_format(odbc_result($rs2, 'IMPORTE'),2,'.',',')."</td>";
						  		break;
						  	case 'El Desperdicio - Lagos de Moreno':
								$tramo5 = "<td>$".number_format(odbc_result($rs2, 'PU'),2,'.',',')."</td><td>".number_format(odbc_result($rs2, 'CANTIDAD'),2,'.',',')."</td><td align='right'>$".number_format(odbc_result($rs2, 'IMPORTE'),2,'.',',')."</td>";
						  		break;
						  	case 'El Desperdicio - Santa Maria de En Medio':
								$tramo6 = "<td>$".number_format(odbc_result($rs2, 'PU'),2,'.',',')."</td><td>".number_format(odbc_result($rs2, 'CANTIDAD'),2,'.',',')."</td><td align='right'>$".number_format(odbc_result($rs2, 'IMPORTE'),2,'.',',')."</td>";
						  		break;
						  	case 'Leon - Aguascalientes':
								$tramo7 = "<td>$".number_format(odbc_result($rs2, 'PU'),2,'.',',')."</td><td>".number_format(odbc_result($rs2, 'CANTIDAD'),2,'.',',')."</td><td align='right'>$".number_format(odbc_result($rs2, 'IMPORTE'),2,'.',',')."</td>";
						  		break;
						  	case 'Maravatio - Los Fresnos':
						  		$tramo8 = "<td>$".number_format(odbc_result($rs2, 'PU'),2,'.',',')."</td><td>".number_format(odbc_result($rs2, 'CANTIDAD'),2,'.',',')."</td><td align='right'>$".number_format(odbc_result($rs2, 'IMPORTE'),2,'.',',')."</td>";
						  		break;
						  }
					}//while

					echo "<tr class = 'tabla' id='".odbc_result($rs, 'PRESUPUESTO_ID')."'>		
						  <td>".$subcuenta."</td>
						  <td>".$cuenta."</td>  
						  <td align='center'>".odbc_result($rs, 'UNIDAD')."</td>
						  <td align='right'>PU</td>
						  <td align='right'>CANTIDAD</td>
						  <td align='right'>IMPORTE</td>
						  ".$tramo2.$tramo3.$tramo4.$tramo5.$tramo6.$tramo7.$tramo8."</tr>"; 
						 
				}//While 
			?>
		  </tbody>
		</table>
		</center>	
	</div>


<!-- MODIFICAR PRESUPUESTO -->
<form action="Presupuestos.php" method="post" enctype="multipart/form-data" >
<div class="modal fade modificar" id="modificar" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
      	<!--CUERPO-->   
        <div class="row">
        	<div class="col-lg-2">Presupuesto:</div>
        	<div class="col-lg-6">
            	<select class="form-control" id="presupuesto" name="presupuesto" required>
                	<option value="">Selecciona una opci&oacute;n</option>
					<?php	
                      $i=1;				  
                      $sql = "SELECT DISTINCT DESCRIPCION, PRESUPUESTO_ID FROM Presupuesto";
                      echo $sql;
                      $rs = odbc_exec( $conn, $sql );
                      if ( !$rs ) { 
                       exit( "Error en la consulta SQL" ); 
                      }     
                      while ( odbc_fetch_row($rs) ) { 
                       $desc = odbc_result($rs, 'DESCRIPCION');  
                       echo "<option id='".$i."'>".$desc."</option>";
                       $i++;
                      }//While 	 
                    ?> 
                </select>
                <input type="hidden" class="form-control" id="presupuestoid" name="presupuestoid">
            </div>
        </div>
        <br />   
        <div class="row">
        	<div class="col-lg-2">Tramo:</div>
        	<div class="col-lg-6">
            	<select class="form-control" id="tramo" name="tramo">
                	<!--<option value="Farac I">Farac I</option>-->
                	<option value="Maravatio - Los Fresnos">Maravatio - Los Fresnos</option>
                	<option value="Los Fresnos - Zapotlanejo">Los Fresnos - Zapotlanejo</option>
                	<option value="Zapotlanejo - Guadalajara">Zapotlanejo - Guadalajara</option>
                	<option value="Zapotlanejo - El Desperdicio">Zapotlanejo - El Desperdicio</option>
                	<option value="El Desperdicio - Lagos de Moreno">El Desperdicio - Lagos de Moreno</option>
                	<option value="El Desperdicio - Santa Maria de En Medio">El Desperdicio - Santa Maria de En Medio</option>
                	<option value="Leon - Aguascalientes">Leon - Aguascalientes</option>
                </select>
            </div>
        </div>
        <br />
        <div class="row">
        	<div class="col-lg-2">Subcuenta:</div>
        	<div class="col-lg-6">
                <select class="form-control" id="subcuenta" name="subcuenta">
                  	<option value="DERECHO DE VIA">DERECHO DE VIA</option>
                	<option value="DRENAJE">DRENAJE</option>
                    <option value="INVERSI&Oacute;N SUJETA A ESTUDIO">INVERSI&Oacute;N SUJETA A ESTUDIO</option>
                	<option value="PLAZAS DE COBRO">PLAZAS DE COBRO</option>
                	<option value="PUENTES Y ESTRUCTURAS">PUENTES Y ESTRUCTURAS</option>
                	<option value="REUBICACI&Oacute;N DE BASES DE CONSERVACI&Oacute;N">REUBICACI&Oacute;N DE BASES DE CONSERVACI&Oacute;N</option>
                	<option value="SE&Ntilde;ALAMIENTO HORIZONTAL">SE&Ntilde;ALAMIENTO HORIZONTAL</option>
                	<option value="SE&Ntilde;ALAMIENTO VERTICAL">SE&Ntilde;ALAMIENTO VERTICAL</option>
                	<option value="SUPERFICIE DE RODAMIENTO">SUPERFICIE DE RODAMIENTO</option>
                	<option value="SUPERVISI&Oacute;N ">SUPERVISI&Oacute;N </option>
                </select>
            </div>                     	
        </div><br/>   
        <div class="row">
        	<div class="col-lg-2">Concepto:</div>
        	<div class="col-lg-6"><select class="form-control" id="concepto" name="concepto"></select></div>
        	<div class="col-lg-1">Unidad:</div>
        	<div class="col-lg-2"><input type="text" class="form-control" id="unidad" name="unidad" readonly></div>
        </div><br>  
        <div class="row">
        	<div class="col-lg-2">Actividad:</div>
        	<div class="col-lg-6"><input type="text" class="form-control" id="actividad" name="actividad"></div>
        </div><br>
        <div class="row">
        	<div class="col-lg-2">Precio Unitario:</div>
        	<div class="col-lg-2"><input type="number" class="form-control" id="precio" name="precio" min="0" value="0" onChange="Calculo()" step="any"></div>
        	<div class="col-lg-2">Cantidad:</div>
        	<div class="col-lg-2"><input type="number" class="form-control" id="cantidad" name="cantidad" min="0" value="0" onChange="Calculo()" step="any"></div>
        	<div class="col-lg-1">Importe:</div>
        	<div class="col-lg-2"><input type="number" class="form-control" id="importe" name="importe" value="0" readonly></div>
        </div>  
        <!--FIN-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="refresh()">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="modificarpresupuesto" id="modificarpresupuesto">Guardar</button>
      </div>
    </div>
  </div>
</div>
</form>


<!-- NUEVO PRESUPUESTO -->
<form action="Presupuestos.php" method="post" enctype="multipart/form-data" >
<div class="modal fade nuevo" id="nuevo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
      	<!--CUERPO-->   
        <div class="row">
        	<div class="col-lg-3">Nombre Presupuesto:</div>
        	<div class="col-lg-6"><input type="text" class="form-control" id="nuevopresupuesto" name="nuevopresupuesto"></div>
        	<div class="col-lg-1">A&ntilde;o:</div>
        	<div class="col-lg-2">
            	<select class="form-control" id="anio" name="anio">
                	<option value="2016">2016</option>
                	<option value="2017">2017</option>
                </select>
            </div>
        </div>
   		<br/>
        <div class="row">
        	<div class="col-lg-3">Clasificaci&oacute;n:</div>
        	<div class="col-lg-6">
            	<select class="form-control" id="nuevaclasificacion" name="nuevaclasificacion">
                	<option value="CAPEX">CAPEX</option>
                	<option value="CAPEX AMERICAS">CAPEX AMERICAS</option>
                	<option value="OPEX">OPEX</option>
                	<option value="OPEX AMERICAS">OPEX AMERICAS</option>
                	<!--<option value="OPEX LA JOYA">OPEX LA JOYA</option>
                	<option value="OT OPEX">OT OPEX</option>
                	<option value="OT CAPEX LA JOYA">OT CAPEX LA JOYA</option>-->                
                </select>
            </div>                   	
        </div>
        <!--FIN-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="refresh()">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="guardarpresupuesto" id="guardarpresupuesto">Guardar</button>
      </div>
    </div>
  </div>
</div>
</form>


<!-- FILTRO -->
<form action="Presupuestos.php" method="post" enctype="multipart/form-data" >
<div class="modal fade filtro" id="filtro" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
      </div>
      <div class="modal-body">
      	<!--CUERPO-->
        <div class="row">
        	<div class="col-lg-2">Presupuesto:</div>
        	<div class="col-lg-6">
            	<select class="form-control" id="presupuestoFiltro" name="presupuestoFiltro">
                	<?php	
					  $i=1;				  
					  $sql = "SELECT DISTINCT DESCRIPCION, PRESUPUESTO_ID FROM Presupuesto";
					  echo $sql;
					  $rs = odbc_exec( $conn, $sql );
					  if ( !$rs ) { 
					   exit( "Error en la consulta SQL" ); 
					  }     
					  while ( odbc_fetch_row($rs) ) { 
					   $desc = odbc_result($rs, 'DESCRIPCION');  
					   echo "<option id='".$i."'>".$desc."</option>";
					   $i++;
					  }//While 	 
					?> 
                	<option value="TODOS">TODOS</option>
                </select>
            </div>
        </div>
        <br/>
        <div class="row">
        	<div class="col-lg-2">Clasificaci&oacute;n:</div>
        	<div class="col-lg-4">
            	<select id="clasFiltro" name="clasFiltro" class="form-control">
                	<option value="">Selecciona una opci&oacute;n</option>
                	<option value="CAPEX">CAPEX</option>
                	<option value="CAPEX AMERICAS">CAPEX AMERICAS</option>
                	<option value="OPEX">OPEX</option>
                	<option value="OPEX AMERICAS">OPEX AMERICAS</option>
                	<!--<option value="OPEX LA JOYA">OPEX LA JOYA</option>
                	<option value="OT CAPEX">OT OPEX</option>
                	<option value="OT CAPEX LA JOYA">OT CAPEX LA JOYA</option>-->
                	<option value="TODOS">TODOS</option>
                </select>
            </div>
        	<div class="col-lg-2" align="center">A&ntilde;o:</div>
        	<div class="col-lg-4">
            	<select id="anioFiltro" name="anioFiltro" class="form-control">
                	<option value="2016">2016</option>
                	<option value="2017">2017</option>
            		<!--<option value="">Selecciona una opci&oacute;n</option>
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
                	<option value="SUPERVISION">SUPERVISION</option>-->
                	<option value="TODOS">TODOS</option>
                </select>
            </div>
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

</body>
</html>