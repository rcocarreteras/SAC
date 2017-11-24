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
$periodo = "De ".date("Y-m-d")." A ".date("Y-m-d");
$base = $_SESSION['S_Base'];
//$base="EN01','JA01','TO01','OC01','PA01','TE01','USA01','ZI01','SB1','USA01";

$fecha1[0] = date("Y-m-d");
$fecha1[1] = date("Y-m-d");


/***************************************************FILTRO*************************************************/	
	if (isset($_REQUEST['filtro'])) {
		$baseFiltro = $_POST["baseFiltro"];
		$rango = $_POST["rango"];
		$fecha1 = explode("*", $rango);
	
		if ($baseFiltro == "TODOS"){
			$sql = "SELECT DISTINCT(TRAMO) FROM CatTramos";			
			$sql1 = "DELETE FROM ProrrateoMO WHERE PERIODO = 'De ".$fecha1[0]." A ".$fecha1[1]."'";
		}else{
			$sql = "SELECT DISTINCT(TRAMO) FROM CatTramos WHERE TRAMO = '".$baseFiltro."'";		
			$sql1 = "DELETE FROM ProrrateoMO WHERE PERIODO = 'De ".$fecha1[0]." A ".$fecha1[1]."' AND TRAMO = '".$baseFiltro."'";
		}
		
	}else{
		
		$sql = "SELECT DISTINCT(TRAMO) FROM CatTramos";// WHERE BASE='TO01'
		$sql1 = "DELETE FROM ProrrateoMO WHERE PERIODO = 'De ".$fecha1[0]." A ".$fecha1[1]."'";
		
	}	
//echo $sql;
$rs1 = odbc_exec( $conn, $sql1 );
if ( !$rs1 ) { 
	exit( "Error en la consulta SQL" );
}	
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
	<script type="text/javascript" src="scripts/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="scripts/demos.js"></script>
	<script type="text/javascript" src="jqwidgets/jqxdraw.js"></script>
	<script type="text/javascript" src="jqwidgets/jqxchart.core.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdata.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxinput.js"></script>
    <link href="css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="js/bootstrap-dialog.min.js"></script>
    <script type="text/javascript" src="js/jqueryFileTree.js"></script>
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
			width: 140px;
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
			margin-right: 10px;
			color:#000;
		}
		.fixed{
			position:fixed;
			border-bottom: 2px solid #0057B3; 
			top:0			
		}
		.titulo{			
			font-family: ubuntu_titlingbold;
			font-size: 20px;
			background-color:#C0DCF3;
			
				}
		.tabla{			
			font-family: ubuntu_titlingbold;
			font-size: 14px;
			color:#104A7A;
		}
		.tabla2{			
			font-family: ubuntu_titlingbold;
			font-size: 13px;
		}
		.base{			
			font-family: ubuntu_titlingbold;
			font-size: 15px;
			color:#00507C;
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

		});//document.ready
		
				
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
		<a href="PresupuestosOpex.php"><span>Ptto Opex</span></a>
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
		<a href="#" data-toggle="modal" data-target=".descargar"><div class="derecha">
			<span class="glyphicon glyphicon-download"></span> &rlm; Descargar
		</div> </a>
	</div>

	<div id="contenido">
		<br>
		<center>
<form action="Prorrateo.php" method="post" enctype="multipart/form-data" >
		<table width="849" height="auto" border="1" id="tabla">
		  <thead>
		    <tr align="center" class="titulo">
		      <td width="380">SUBCUENTA/ACTIVIDAD</td>
		      <td width="163">TOTAL HORAS</td>	     
		      <td width="126">TOTAL</td>
		    </tr>
		  </thead>
		  <tbody>  
		<?php 
		$tramo1 = "";
		$cont = 0;
		$act1 = '';
		$sub1 = '';		
		$sum_act = 0;
		$sum_sub = 0;
		$costo_hora = 0;
		$subtotal = 0;
		$costo_total = 0;
		$total = 0;
		$suma = 0;
		$por_hora = 0;
		$por_extra = 0;		
		$totalhoras = 0;
		$totalDinero = 0;
		$prueba = 0;
		//$sql = "SELECT DISTINCT(BASE) FROM CatTramos";
		//echo $sql;

		$rs = odbc_exec( $conn, $sql );
		if ( !$rs ) { 
			exit( "Error en la consulta SQL" ); 
		}
		while ( odbc_fetch_row($rs) ) {
			$tramo = odbc_result($rs, 'TRAMO'); 

				echo "<tbody>";
				   if ($tramo1 != $tramo){
				    	echo "<tr class = 'base'>
				   			<td align='center' colspan='4'><strong><h4>".$tramo."</h4></strong></td>
				   		 </tr>";
				   		$tramo1 = $tramo;	 	
				   }//IF BASE
			$sql2 = "SELECT DISTINCT CatConcepto.SubCtaDes, AvanceDiario.ACTIVIDAD, AvanceDiario.TRAMO, AvanceDiario.ACTIVIDAD_ID FROM AvanceDiario LEFT OUTER JOIN CatConcepto ON AvanceDiario.ACTIVIDAD = CatConcepto.DesCpt COLLATE SQL_Latin1_General_CP1_CI_AS WHERE (AvanceDiario.ACTIVIDAD <> '') AND (AvanceDiario.TRAMO='".$tramo."') AND (CatConcepto.SubCtaDes <> '') AND (AvanceDiario.FECHA BETWEEN '".$fecha1[0]."' AND '".$fecha1[1]."') ORDER BY CatConcepto.SubCtaDes";
			//echo $sql2;
			$rs2 = odbc_exec( $conn2, $sql2 );
			if ( !$rs2 ) { 
				exit( "Error en la consulta SQL" );
			}
			while ( odbc_fetch_row($rs2) ) { 
			//$cont2++;
			$act = odbc_result($rs2, 'ACTIVIDAD'); 
			$sub = odbc_result($rs2, 'SubCtaDes');
			$act_id = odbc_result($rs2, 'ACTIVIDAD_ID');	
								
			//AQUI
				$sql3 = "SELECT DISTINCT PuntoTrabajado.NOMBRE, PuntoTrabajado.NUMERO, SUM(PuntoTrabajado.HORAS) AS HORAS, SUM(PuntoTrabajado.HORA_EXTRA) AS EXTRAS, AvanceDiario.TRAMO FROM PuntoTrabajado INNER JOIN AvanceDiario ON PuntoTrabajado.AVANCE_ID = AvanceDiario.AVANCE_ID WHERE (PuntoTrabajado.TIPO = 'MANO DE OBRA') AND (PuntoTrabajado.ACTIVIDAD_ID <> '') AND (PuntoTrabajado.HORAS <> '0') AND (PuntoTrabajado.ACTIVIDAD_ID = '".$act_id."') AND (PuntoTrabajado.FECHA BETWEEN '".$fecha1[0]."' AND '".$fecha1[1]."') AND (AvanceDiario.TRAMO = '".$tramo."') GROUP BY PuntoTrabajado.NOMBRE, PuntoTrabajado.NUMERO, PuntoTrabajado.ACTIVIDAD_ID, AvanceDiario.TRAMO ORDER BY PuntoTrabajado.NOMBRE";
				#echo $sql3;
				$rs3 = odbc_exec( $conn3, $sql3);
				if ( !$rs3 ) { 
					exit( "Error en la consulta SQL" );
				}
				while ( odbc_fetch_row($rs3) ) { 
				$nombre = odbc_result($rs3, 'NOMBRE');
				$horas_act = odbc_result($rs3, 'HORAS');
				$no_emp = odbc_result($rs3, 'NUMERO');
				$extras_act = odbc_result($rs3, 'EXTRAS');
				
					$sql4 = "SELECT SUM(PuntoTrabajado.HORAS) AS HORAS, SUM(PuntoTrabajado.HORA_EXTRA) AS EXTRAS, CostoProrrateo.COSTO, CostoProrrateo.COSTO_EXTRA, CostoProrrateo.BASE FROM PuntoTrabajado INNER JOIN CostoProrrateo ON PuntoTrabajado.NUMERO = CostoProrrateo.NO_EMP INNER JOIN AvanceDiario ON PuntoTrabajado.AVANCE_ID = AvanceDiario.AVANCE_ID WHERE (PuntoTrabajado.TIPO = 'MANO DE OBRA') AND (PuntoTrabajado.FECHA BETWEEN '".$fecha1[0]."' AND '".$fecha1[1]."') AND FECHA_INICIO='".$fecha1[0]."' AND (PuntoTrabajado.NOMBRE = '".$nombre."') GROUP BY CostoProrrateo.COSTO, CostoProrrateo.COSTO_EXTRA, BASE";
					//echo $sql4;	
					$rs4 = odbc_exec( $conn4, $sql4);
					if ( !$rs4 ) { 
						exit( "Error en la consulta SQL" );
					}
					while ( odbc_fetch_row($rs4) ) { 
						//echo "Hola";
						$costoHora = odbc_result($rs4, 'COSTO');
						$costoExtra = odbc_result($rs4, 'COSTO_EXTRA');
						$horas = odbc_result($rs4, 'HORAS');
				 		$extras = odbc_result($rs4, 'EXTRAS');	
				 		$base = odbc_result($rs4, 'BASE');	
						$cont++;							
						
						//OBTENER COSTO POR HORA
						$por_hora = $costoHora / $horas;
						if ($extras != 0){
							$por_extra = $costoExtra / $extras;
						}else{
							$por_extra = 0;
						}
							
						//COSTO TOTAL DE HORAS LABORADAS
						$hora = $por_hora * $horas_act;
						$costo = $por_extra * $extras_act;	
						$totalDinero = $hora + $costo;
						$total_horas = $horas_act + $extras_act;
						
						//TOTAL COSTO Y HORAS POR ACTIVIDAD
						$subtotal += $total_horas;		
						
						$periodo = "De ".$fecha1[0]." A ".$fecha1[1];				
						//echo $periodo;
						//QUERY INSERT
						$sql5 = "SELECT * FROM ProrrateoMO WHERE NO_EMP='".$no_emp."' AND ACTIVIDAD='".$act."' AND PERIODO='".$periodo."' AND TRAMO='".$tramo."'";
						//echo $sql5;
						$rs5 = odbc_exec( $conn5, $sql5);
						if ( !$rs5 ) {
							exit( "Error en la consulta ProrrateoMO" );
						}while( odbc_fetch_row($rs5) ) { 
							$prueba = odbc_result($rs5, 'HORAS');
							//echo "entra";
						}

						if($prueba == 0){							
							//QUERY INSERT
							$sql5 = "INSERT INTO ProrrateoMO VALUES ('".$no_emp."','".$nombre."','".$tramo."','".$sub."','".$act."','".$total_horas."','".$totalDinero."','".$base."','".$periodo."','".date("m")."')";
							//echo $sql4;
							$rs5 = odbc_exec( $conn5, $sql5);
							if ( !$rs5 ) {
								exit( "Error en la consulta ProrrateoMO" );
							}	
						}//if
					}//while4 OBTENEMOS EL DETALLE DE CADA EMPLEADO
				   }//while3 OBTENEMOS LOS EMPLEADOS	

					//INICIALIZAMOS LAS VARIABLES
					$cont = 0;
				    $total = 0;
					$subtotal = 0;
					$sum_act = 0;
					$sum_sub = 0;					
				}//WHILE 2 OBTENEMOS LAS ACTIVIDADES
		}//while

//BASE IN ('".$base."') AND 
		$sql4 = "SELECT SUBCUENTA, SUM(HORAS) AS HORAS, SUM (COSTO) AS COSTO FROM ProrrateoMO WHERE PERIODO = '".$periodo."' AND TRAMO='".$tramo."' GROUP BY SUBCUENTA";		
		$rs4 = odbc_exec( $conn4, $sql4);
		#echo $sql4."<br>";
		if ( !$rs4 ) { 
			exit( "Error en la consulta SQL" );
		}
		while ( odbc_fetch_row($rs4) ) {
			$subcuenta = odbc_result($rs4, 'SUBCUENTA');
			$horas = odbc_result($rs4, 'HORAS');
			$costo = odbc_result($rs4, 'COSTO');

			echo "<tr class = 'tabla'>
			<td align='center'><strong><h4>".$subcuenta."</h4></strong></td>
			<td align='right'>".number_format($horas,0)."</td>
			<td align='right'>$".number_format($costo,3,'.',',')."</td>
			</tr>";
#BASE IN ('".$base."') AND
			$sql3 = "SELECT ACTIVIDAD, SUM(HORAS) AS HORAS, SUM (COSTO) AS COSTO FROM ProrrateoMO WHERE SUBCUENTA = '".$subcuenta."' AND PERIODO = '".$periodo."' AND TRAMO='".$tramo."' GROUP BY ACTIVIDAD";		
			$rs3 = odbc_exec( $conn3, $sql3);
			if ( !$rs3 ) { 
				exit( "Error en la consulta SQL" );
			}
			while ( odbc_fetch_row($rs3) ) {
				$actividad = odbc_result($rs3, 'ACTIVIDAD');
				$horas = odbc_result($rs3, 'HORAS');
				$costo = odbc_result($rs3, 'COSTO');

				echo "<tr class = 'tabla2'>
				<td align='left'><strong><h4>".$actividad."</h4></strong></td>
				<td align='right'>".number_format($horas,0)."</td>
				<td align='right'>$".number_format($costo,3,'.',',')."</td>
				</tr>";
# BASE IN ('".$base."') AND 
				$sql2 = "SELECT NOMBRE, SUM(HORAS) AS HORAS, SUM (COSTO) AS COSTO FROM ProrrateoMO WHERE PERIODO = '".$periodo."' AND ACTIVIDAD = '".$actividad."' AND TRAMO='".$tramo."' GROUP BY NOMBRE";		
				$rs2 = odbc_exec( $conn2, $sql2);
				if ( !$rs2 ) { 
					exit( "Error en la consulta SQL" );
				}
				while ( odbc_fetch_row($rs2) ) {
					$nombre = odbc_result($rs2, 'NOMBRE');
					$horas = odbc_result($rs2, 'HORAS');
					$costo = odbc_result($rs2, 'COSTO');

					echo "<tr class = 'tabla2'>
					<td align='left'><strong><h5>".$nombre."<h5></strong></td>
					<td align='right'>".number_format($horas,0)."</td>
					<td align='right'>$".number_format($costo,3,'.',',')."</td>
					</tr>";					
				}// PERSONAL
			}// ACTIVIDAD			
		}// SUBCUENTA
		echo "</tbody>";
		echo "<tr><td style='display:none'><input type='hidden' name='contador' id='contador' value='".$cont."'></td></tr>";		
		?>
	</table>
</form>
		</center>
	</div>
    
<!-- FILTRO -->
<form action="ProrrateoAct.php" method="post" enctype="multipart/form-data" >
<div class="modal fade filtro" id="filtro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><center>  <img src="images/filtro_header.png" height="40"> Filtro de informaci&oacute;n <img src="images/filtro_header1.png" height="40">  </center></h4>
      </div>
      <div class="modal-body">
      	<!--CUERPO-->
        <div class="row">
        	<!--<div class="col-lg-3"></div>-->
        	<div class="col-lg-3">Tramo:</div>
        	<div class="col-lg-7">
            	<select class="form-control" id="baseFiltro" name="baseFiltro">
					<?php
					  $i=1;
					  $sql = "SELECT DISTINCT TRAMO FROM CatTramos";
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
                    <option value="TODOS">TODOS</option>
                </select>
            </div>
        </div><br>
        <div class="row">
        	<div class="col-lg-3">Rango de fechas:</div>
        	<div class="col-lg-6">
            	<select id="rango" name="rango" class="form-control">
                	<?php
					  $i=1;
					  $sql = "SELECT DISTINCT(FECHA_INICIO), FECHA_FIN FROM CostoProrrateo";
					  echo $sql;
					  $rs = odbc_exec( $conn, $sql );
					  if ( !$rs ) { 
					   exit( "Error en la consulta SQL" ); 
					  }     
					  while ( odbc_fetch_row($rs) ) { 
						$fecha1 = odbc_result($rs, 'FECHA_INICIO');
						$fecha2 = odbc_result($rs, 'FECHA_FIN');
					   echo "<option id='".$i."' value='".$fecha1."*".$fecha2."'>De ".$fecha1." A ".$fecha2."</option>";
					   $i++;
					  }//While 	 
                	?>
                </select>
            </div>
        </div>
        <!--FIN-->
   </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="filtro" id="filtro">Aceptar</button>
      </div>
    </div>
  </div>
  </div>
</form>  

<!-- DESCARGAR -->
<form action="Excel_ProMO.php" method="post" enctype="multipart/form-data" >
<div class="modal fade descargar" id="descargar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><center> Descarga </center></h4>
      </div>
      <div class="modal-body">
      	<!--CUERPO-->
        <div class="row">
        	<!--<div class="col-lg-3"></div>-->
        	<div class="col-lg-1">Base:</div>
        	<div class="col-lg-3">
            	<select class="form-control" id="baseExcel" name="baseExcel">
					<?php
					  $i=1;
					  $sql = "SELECT DISTINCT BASE FROM CatTramos";
					  echo $sql;
					  $rs = odbc_exec( $conn, $sql );
					  if ( !$rs ) { 
					   exit( "Error en la consulta SQL" ); 
					  }     
					  while ( odbc_fetch_row($rs) ) { 
						$base = odbc_result($rs, 'BASE');
					   echo "<option id='".$i."'>".$base."</option>";
					   $i++;
					  }//While 	 
                	?>
                </select>
            </div>
        	<div class="col-lg-2">Periodo:</div>
        	<div class="col-lg-6">
            	<select id="rangoExcel" name="rangoExcel" class="form-control">
                	<?php
					  $i=1;
					  $sql = "SELECT DISTINCT(FECHA_INICIO), FECHA_FIN FROM CostoProrrateo";
					  echo $sql;
					  $rs = odbc_exec( $conn, $sql );
					  if ( !$rs ) { 
					   exit( "Error en la consulta SQL" ); 
					  }     
					  while ( odbc_fetch_row($rs) ) { 
						$fecha1 = odbc_result($rs, 'FECHA_INICIO');
						$fecha2 = odbc_result($rs, 'FECHA_FIN');
					   echo "<option id='".$i."' value='De ".$fecha1." A ".$fecha2."'>De ".$fecha1." A ".$fecha2."</option>";
					   $i++;
					  }//While 	 
                	?>
                </select>
            </div>
        </div>
        <!--FIN-->
   </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-success" name="descargar_mo" id="descargar_mo">Exportar</button>
      </div>
    </div>
  </div>
  </div>
</form>       	   	
    	
</body>
</html>