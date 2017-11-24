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

$fecha1[0] = date("Y-m-d");
$fecha1[1] = date("Y-m-d");
$subtramo = $_SESSION['S_Subtramo'];
$base = $_SESSION['S_Base'];
$periodo = "De ".$fecha1[0]." A ".$fecha1[1];
/***************************************************FILTRO*************************************************/	
	if (isset($_REQUEST['filtro'])) {
		$tramo = $_POST["tramo"];
		$base = $_POST["baseFiltro"];
		$rango = $_POST["rango"];
		$fecha1 = explode("*", $rango);
		
		if (isset($_POST['subtramo'])){
		  $subtramo = $_POST['subtramo'];
	  }else{
		   $subtramo ="";
	  }
	
		if ($tramo == "TODOS"){
			$sql = "SELECT DISTINCT(TRAMO) FROM CatTramos";
			$subtramo =$_SESSION['S_Subtramo'];
			$base = $_SESSION['S_Base'];
			$sql2 = "DELETE FROM ProrrateoMAQ WHERE PERIODO = 'De ".$fecha1[0]." A ".$fecha1[1]."'";
			
		}else{
			
			$sql = "SELECT DISTINCT(TRAMO), SUBTRAMO FROM CatTramos WHERE TRAMO = '".$tramo."' AND SUBTRAMO = '".$subtramo."'";	
			$sql2 = "DELETE FROM ProrrateoMAQ WHERE PERIODO = 'De ".$fecha1[0]." A ".$fecha1[1]."' AND TRAMO = '".$tramo."' AND BASE = '".$base."'";
		}
		
	}else{		
		$sql = "SELECT DISTINCT(TRAMO) FROM CatTramos";	
		$sql2 = "DELETE FROM ProrrateoMAQ WHERE PERIODO = 'De ".$fecha1[0]." A ".$fecha1[1]."'";	
	}

#echo $sql2;
$rs2 = odbc_exec( $conn2, $sql2 );
if ( !$rs2 ) { 
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


			$("#tramo").click(function(){
				$("#tramo option:selected").each(function () {
			     elegido=$(this).val();
			     $.post("consultaAvanceDiario.php", { tramoPro: elegido }, function(data){				   								
					 $("#subtramo").html(data);
					 $("#subtramo").click();
			   });			
              });				
			});
			$("#subtramo").click(function(){
				var Tramo = $("#tramo").val();
			 	//alert(Tramo);
				$("#subtramo option:selected").each(function () {
			     elegido=$(this).val();
			     $.post("consultaAvanceDiario.php", { buscarSub: '', subtramoPro: elegido, tramoPro1: Tramo }, function(data){
					 $("#baseFiltro").val(data);
					 //alert(data);
			   });			
              });				
			});

		});//document.ready

		$(window).load(function() {

			$("#tramo").click();
			
		});//$(window).load	
		

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
			<span class="glyphicon glyphicon-download"></span> &rlm; Exportar
		</div> </a>
	</div>

	<div id="contenido">
		<br>
		<center>
<form action="Prorrateo.php" method="post" enctype="multipart/form-data" >
		<table width="1300" height="68" border="1" id="tabla">
		  <thead>
		    <tr align="center" class="titulo">
		      <td width="350">SUBCUENTA/ACTIVIDAD</td>
		      <td width="125">TOTAL HORAS</td>	 
		      <td width="130">ACTIVO FIJO</td>	     
		      <td width="133">COMBUSTIBLE</td>     
		      <td width="162">MANTENIMIENTO</td>     
		      <td width="100">RENTA</td>     
		      <td width="100">LLANTAS</td>     
		      <td width="100">SEGUROS</td>     
		      <td width="100">OTROS</td>
		    </tr>
		  </thead>
		  <tbody>  
		<?php 
		$tramo1 = "";
		$cont = 0;
		$act1 = '';
		$sub1 = '';		
		$por_hora = 0;	
		$mtto_hora = 0;
		$otros_hora = 0;
		$costo_hora = 0;
		$renta_hora = 0;
		$seguro_hora = 0;
		$combus_hora = 0;
		$llantas_hora = 0;
		$suma = 0;
		$activo_fijo = 0;	
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
			//$subtramo = odbc_result($rs, 'SUBTRAMO'); 

				echo "<tbody>";
				   if ($tramo1 != $tramo){
				    	echo "<tr class = 'base'>
				   			<td align='center' colspan='9'><strong><h4>".$tramo."</h4></strong></td>
				   		 </tr>";
				   		$tramo1 = $tramo;	 	
				   }//IF BASE
			$sql2 = "SELECT DISTINCT CatConcepto.SubCtaDes, AvanceDiario.ACTIVIDAD, AvanceDiario.TRAMO, AvanceDiario.ACTIVIDAD_ID FROM AvanceDiario LEFT OUTER JOIN CatConcepto ON AvanceDiario.ACTIVIDAD = CatConcepto.DesCpt COLLATE SQL_Latin1_General_CP1_CI_AS WHERE (AvanceDiario.ACTIVIDAD <> '') AND (AvanceDiario.TRAMO='".$tramo."') AND (AvanceDiario.SUBTRAMO IN ('".$subtramo."')) AND (CatConcepto.SubCtaDes <> '') AND (AvanceDiario.FECHA BETWEEN '".$fecha1[0]."' AND '".$fecha1[1]."') ORDER BY CatConcepto.SubCtaDes";
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
				$sql3 = "SELECT DISTINCT PuntoTrabajado.NOMBRE, PuntoTrabajado.NUMERO, SUM(PuntoTrabajado.HORAS) AS HORAS, AvanceDiario.TRAMO FROM PuntoTrabajado INNER JOIN AvanceDiario ON PuntoTrabajado.AVANCE_ID = AvanceDiario.AVANCE_ID WHERE (PuntoTrabajado.TIPO = 'MAQUINARIA') AND (PuntoTrabajado.ACTIVIDAD_ID <> '') AND (PuntoTrabajado.HORAS <> '0') AND (PuntoTrabajado.ACTIVIDAD_ID = '".$act_id."') AND (PuntoTrabajado.FECHA BETWEEN '".$fecha1[0]."' AND '".$fecha1[1]."') AND (AvanceDiario.TRAMO = '".$tramo."') AND (AvanceDiario.SUBTRAMO IN ('".$subtramo."')) GROUP BY PuntoTrabajado.NOMBRE, PuntoTrabajado.NUMERO, PuntoTrabajado.ACTIVIDAD_ID, AvanceDiario.TRAMO ORDER BY PuntoTrabajado.NOMBRE";
				#echo $sql3."<br>";
				$rs3 = odbc_exec( $conn3, $sql3);
				if ( !$rs3 ) { 
					exit( "Error en la consulta SQL" );
				}
				while ( odbc_fetch_row($rs3) ) { 
				$nombre = odbc_result($rs3, 'NOMBRE');
				$horas_act = odbc_result($rs3, 'HORAS');
				$no_eco = odbc_result($rs3, 'NUMERO');	

					$sql4 = "SELECT SUM(PuntoTrabajado.HORAS) AS HORAS, CostoProrrateoMaq.OTROS, CostoProrrateoMaq.SEGUROS, CostoProrrateoMaq.LLANTAS, CostoProrrateoMaq.RENTA, CostoProrrateoMaq.MANTENIMIENTO, CostoProrrateoMaq.ACTIVO_FIJO, CostoProrrateoMaq.COMBUSTIBLE, CostoProrrateoMaq.BASE FROM PuntoTrabajado INNER JOIN CostoProrrateoMaq ON PuntoTrabajado.NUMERO = CostoProrrateoMaq.NOECO INNER JOIN CatTramos ON PuntoTrabajado.SUBTRAMO = CatTramos.SUBTRAMO AND CostoProrrateoMaq.BASE = CatTramos.BASE WHERE (PuntoTrabajado.TIPO = 'MAQUINARIA') AND (PuntoTrabajado.FECHA BETWEEN '".$fecha1[0]."' AND '".$fecha1[1]."') AND (PuntoTrabajado.NUMERO = '".$no_eco."') AND CostoProrrateoMaq.BASE IN ('".$base."') GROUP BY CostoProrrateoMaq.OTROS, CostoProrrateoMaq.SEGUROS, CostoProrrateoMaq.LLANTAS, CostoProrrateoMaq.RENTA, CostoProrrateoMaq.MANTENIMIENTO, CostoProrrateoMaq.ACTIVO_FIJO, CostoProrrateoMaq.COMBUSTIBLE, CostoProrrateoMaq.BASE";
					#echo $sql4."<br>";
					$rs4 = odbc_exec( $conn4, $sql4);
					if ( !$rs4 ) { 
						exit( "Error en la consulta SQL" );
					}
					while ( odbc_fetch_row($rs4) ) { 
						$otros = odbc_result($rs4, 'OTROS');
						$seguro = odbc_result($rs4, 'SEGUROS');
						$llantas = odbc_result($rs4, 'LLANTAS');
						$renta = odbc_result($rs4, 'RENTA');
						$mtto = odbc_result($rs4, 'MANTENIMIENTO');
						$activo = odbc_result($rs4, 'ACTIVO_FIJO');
						$combus = odbc_result($rs4, 'COMBUSTIBLE');
						$horas = odbc_result($rs4, 'HORAS');
						$cont++;							
						
						//COSTO DE OTROS POR HORA
						if ($otros == '0'){
							$otros_hora = 0;
							$otros_act = 0;
						}else{
							$otros_hora = ($otros / $horas) * $horas_act;
						}
						//COSTO DE SEGURO POR HORA
						if ($seguro == '0'){
							$seguro_hora = 0;
							$seguro_act = 0;
						}else{
							$seguro_hora = ($seguro / $horas) * $horas_act;
						}
						//COSTO DE LLANTAS POR HORA
						if ($llantas == '0'){
							$llantas_hora = 0;
							$llantas_act = 0;							
						}else{
							$llantas_hora = ($llantas / $horas) * $horas_act;
						}
						//COSTO DE RENTA POR HORA
						if ($renta == '0'){
							$renta_hora = 0;
							$renta_act = 0;
						}else{
							$renta_hora = ($renta / $horas) * $horas_act;
						}
						//COSTO DE MTTO POR HORA
						if ($mtto == '0'){
							$mtto_hora = 0;
							$mtto_act = 0;
						}else{
							$mtto_hora = ($mtto / $horas) * $horas_act;
						}
						//COSTO DE COMBUSTIBLE POR HORA
						if ($combus == '0'){
							$combus_hora = 0;
							$combus_act = 0;
						}else{
							$combus_hora = ($combus / $horas) * $horas_act;
						}
						//ACTIVO POR HORA	
						$activo_fijo = ($activo / $horas) * $horas_act;
						//echo "**".$horas;
						//echo "--".$activo_fijo;
						
						$periodo = "De ".$fecha1[0]." A ".$fecha1[1];
						
						//QUERY INSERT
						$sql5 = "SELECT * FROM ProrrateoMAQ WHERE NO_ECO='".$no_eco."' AND ACTIVIDAD='".$act."' AND PERIODO='".$periodo."' AND TRAMO='".$tramo."'";
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
							$sql5 = "INSERT INTO ProrrateoMAQ VALUES ('".$no_eco."','".$nombre."','".$tramo."','".$sub."','".$act."','".$horas_act."','".$activo_fijo."','".$combus_hora."','".$mtto_hora."','".$renta_hora."','".$llantas_hora."','".$seguro_hora."','".$otros_hora."','".$base."','".$periodo."','".date("m")."')";
							//echo $sql5;
							$rs5 = odbc_exec( $conn5, $sql5);
							if ( !$rs5 ) {
								exit( "Error en la consulta ProrrateoMAQ" );
							}	
						}//if
					}//while4 OBTENEMOS EL DETALLE DE CADA EMPLEADO
				   }//while3 OBTENEMOS LOS EMPLEADOS	

					//INICIALIZAMOS LAS VARIABLES					
					$por_hora = 0;	
					$mtto_hora = 0;
					$otros_hora = 0;
					$costo_hora = 0;
					$renta_hora = 0;
					$seguro_hora = 0;
					$combus_hora = 0;
					$llantas_hora = 0;
					$cont = 0;
				   // $total = 0;
					$activo_fijo = 0;
					//$por_hora = 0;
					//$suma = 0;					
				}//WHILE 2 OBTENEMOS LAS ACTIVIDADES
		}//while
		
		$sql4 = "SELECT DISTINCT(SUBCUENTA) FROM ProrrateoMAQ WHERE  BASE IN ('".$base."') AND PERIODO = '".$periodo."' AND TRAMO='".$tramo."'";	
		$rs4 = odbc_exec( $conn4, $sql4);
		#echo $sql4."<br>";
		if ( !$rs4 ) { 
			exit( "Error en la consulta SQL" );
		}
		while ( odbc_fetch_row($rs4) ) {
			$subcuenta = odbc_result($rs4, 'SUBCUENTA');
			
			$sql5 = "SELECT SUM(HORAS) AS HORAS, SUM(ACTIVO_FIJO) AS ACTIVO, SUM(COMBUSTIBLE) AS COMBUSTIBLE, SUM(MANTENIMIENTO) AS MANTENIMIENTO, SUM(RENTA) AS RENTA, SUM(LLANTAS) AS LLANTAS, SUM(SEGUROS) AS SEGUROS, SUM(OTROS) AS OTROS FROM ProrrateoMAQ WHERE SUBCUENTA='".$subcuenta."' AND BASE IN ('".$base."') AND PERIODO = '".$periodo."' AND TRAMO='".$tramo."'";	
			$rs5 = odbc_exec( $conn5, $sql5);
			#echo $sql5."<br>";
			if ( !$rs5 ) { 
				exit( "Error en la consulta SQL" );
			}
			while ( odbc_fetch_row($rs5) ) {
				$activo1 = odbc_result($rs5, 'ACTIVO');
				$horas1 = odbc_result($rs5, 'HORAS');
				$combus1 = odbc_result($rs5, 'COMBUSTIBLE');
				$mtto1 = odbc_result($rs5, 'MANTENIMIENTO');
				$renta1 = odbc_result($rs5, 'RENTA');
				$llantas1 = odbc_result($rs5, 'LLANTAS');
				$seguros1 = odbc_result($rs5, 'SEGUROS');
				$otros1 = odbc_result($rs5, 'OTROS');
			}
			echo "<tr class = 'tabla'>
			<td align='center'><strong><h4>".$subcuenta."</h4></strong></td>
			<td align='center'>".number_format($horas1,0)."</td>
			<td align='right'>$".number_format($activo1,3,'.',',')."</td>
			<td align='right'>$".number_format($combus1,3,'.',',')."</td>
			<td align='right'>$".number_format($mtto1,3,'.',',')."</td>
			<td align='right'>$".number_format($renta1,3,'.',',')."</td>
			<td align='right'>$".number_format($llantas1,3,'.',',')."</td>
			<td align='right'>$".number_format($seguros1,3,'.',',')."</td>
			<td align='right'>$".number_format($otros1,3,'.',',')."</td>
			</tr>";

			$sql3 = "SELECT DISTINCT(ACTIVIDAD) FROM ProrrateoMAQ WHERE SUBCUENTA = '".$subcuenta."' AND BASE IN ('".$base."') AND PERIODO = '".$periodo."' AND TRAMO='".$tramo."' GROUP BY ACTIVIDAD";		
			$rs3 = odbc_exec( $conn3, $sql3);
			if ( !$rs3 ) { 
				exit( "Error en la consulta SQL" );
			}
			while ( odbc_fetch_row($rs3) ) {
				$actividad = odbc_result($rs3, 'ACTIVIDAD');
				
				$sql5 = "SELECT SUM(HORAS) AS HORAS, SUM(ACTIVO_FIJO) AS ACTIVO, SUM(COMBUSTIBLE) AS COMBUSTIBLE, SUM(MANTENIMIENTO) AS MANTENIMIENTO, SUM(RENTA) AS RENTA, SUM(LLANTAS) AS LLANTAS, SUM(SEGUROS) AS SEGUROS, SUM(OTROS) AS OTROS FROM ProrrateoMAQ WHERE SUBCUENTA='".$subcuenta."' AND ACTIVIDAD='".$actividad."' AND BASE IN ('".$base."') AND PERIODO = '".$periodo."' AND TRAMO='".$tramo."'";
				$rs5 = odbc_exec( $conn5, $sql5);
				if ( !$rs5 ) { 
					exit( "Error en la consulta SQL" );
				}
				while ( odbc_fetch_row($rs5) ) {
					$activo2 = odbc_result($rs5, 'ACTIVO');
					$horas2 = odbc_result($rs5, 'HORAS');
					$combus2 = odbc_result($rs5, 'COMBUSTIBLE');
					$mtto2 = odbc_result($rs5, 'MANTENIMIENTO');
					$renta2 = odbc_result($rs5, 'RENTA');
					$llantas2 = odbc_result($rs5, 'LLANTAS');
					$seguros2 = odbc_result($rs5, 'SEGUROS');
					$otros2 = odbc_result($rs5, 'OTROS');
				}

				echo "<tr class = 'tabla2'>
				<td align='left'><strong><h4>".$actividad."</h4></strong></td>
				<td align='center'>".number_format($horas2,0)."</td>
				<td align='right'>$".number_format($activo2,3,'.',',')."</td>
				<td align='right'>$".number_format($combus2,3,'.',',')."</td>
				<td align='right'>$".number_format($mtto2,3,'.',',')."</td>
				<td align='right'>$".number_format($renta2,3,'.',',')."</td>
				<td align='right'>$".number_format($llantas2,3,'.',',')."</td>
				<td align='right'>$".number_format($seguros2,3,'.',',')."</td>
				<td align='right'>$".number_format($otros2,3,'.',',')."</td>
				</tr>";

				$sql2 = "SELECT DISTINCT(DESCRIPCION) FROM ProrrateoMAQ WHERE ACTIVIDAD = '".$actividad."' AND SUBCUENTA = '".$subcuenta."' AND BASE IN ('".$base."') AND PERIODO = '".$periodo."' AND TRAMO='".$tramo."'";	
				//echo $sql2;
				$rs2 = odbc_exec( $conn2, $sql2);
				if ( !$rs2 ) { 
					exit( "Error en la consulta SQL" );
				}
				while ( odbc_fetch_row($rs2) ) {
					$nombre = odbc_result($rs2, 'DESCRIPCION');

					$sql5 = "SELECT SUM(HORAS) AS HORAS, SUM(ACTIVO_FIJO) AS ACTIVO, SUM(COMBUSTIBLE) AS COMBUSTIBLE, SUM(MANTENIMIENTO) AS MANTENIMIENTO, SUM(RENTA) AS RENTA, SUM(LLANTAS) AS LLANTAS, SUM(SEGUROS) AS SEGUROS, SUM(OTROS) AS OTROS FROM ProrrateoMAQ WHERE SUBCUENTA='".$subcuenta."' AND ACTIVIDAD='".$actividad."' AND DESCRIPCION='".$nombre."' AND BASE IN ('".$base."') AND PERIODO = '".$periodo."' AND TRAMO='".$tramo."'";		
					$rs5 = odbc_exec( $conn5, $sql5);
					if ( !$rs5 ) { 
						exit( "Error en la consulta SQL" );
					}
					while ( odbc_fetch_row($rs5) ) {
						$activo3 = odbc_result($rs5, 'ACTIVO');
						$horas3 = odbc_result($rs5, 'HORAS');
						$combus3 = odbc_result($rs5, 'COMBUSTIBLE');
						$mtto3 = odbc_result($rs5, 'MANTENIMIENTO');
						$renta3 = odbc_result($rs5, 'RENTA');
						$llantas3 = odbc_result($rs5, 'LLANTAS');
						$seguros3 = odbc_result($rs5, 'SEGUROS');
						$otros3 = odbc_result($rs5, 'OTROS');
					}

					echo "<tr class = 'tabla2'>
					<td align='left'><strong><h5>".$nombre."<h5></strong></td>
					<td align='center'>".number_format($horas3,0)."</td>
					<td align='right'>$".number_format($activo3,3,'.',',')."</td>
					<td align='right'>$".number_format($combus3,3,'.',',')."</td>
					<td align='right'>$".number_format($mtto3,3,'.',',')."</td>
					<td align='right'>$".number_format($renta3,3,'.',',')."</td>
					<td align='right'>$".number_format($llantas3,3,'.',',')."</td>
					<td align='right'>$".number_format($seguros3,3,'.',',')."</td>
					<td align='right'>$".number_format($otros3,3,'.',',')."</td>
					</tr>";					
				}// PERSONAL
			}// ACTIVIDAD
		}// SUBCUENTA
		$sql = "SELECT SUM(HORAS) AS HORAS, SUM(ACTIVO_FIJO) AS ACTIVO, SUM(COMBUSTIBLE) AS COMBUSTIBLE, SUM(MANTENIMIENTO) AS MANTENIMIENTO, SUM(RENTA) AS RENTA, SUM(LLANTAS) AS LLANTAS, SUM(SEGUROS) AS SEGUROS, SUM(OTROS) AS OTROS FROM ProrrateoMAQ WHERE BASE IN ('".$base."') AND PERIODO = '".$periodo."' AND TRAMO='".$tramo."'";		
		$rs = odbc_exec( $conn, $sql);
		if ( !$rs ) {
			exit( "Error en la consulta SQL" );
		}
		while ( odbc_fetch_row($rs) ) {
			$activo4 = odbc_result($rs, 'ACTIVO');
			$horas4 = odbc_result($rs, 'HORAS');
			$combus4 = odbc_result($rs, 'COMBUSTIBLE');
			$mtto4 = odbc_result($rs, 'MANTENIMIENTO');
			$renta4 = odbc_result($rs, 'RENTA');
			$llantas4 = odbc_result($rs, 'LLANTAS');
			$seguros4 = odbc_result($rs, 'SEGUROS');
			$otros4 = odbc_result($rs, 'OTROS');
		}
		echo "<tfoot><tr class = 'tabla'>
				<td align='center'><strong><h4>TOTALES</h4></strong></td>
				<td align='center'>".number_format($horas4,0)."</td>
				<td align='right'>$".number_format($activo4,3,'.',',')."</td>
				<td align='right'>$".number_format($combus4,3,'.',',')."</td>
				<td align='right'>$".number_format($mtto4,3,'.',',')."</td>
				<td align='right'>$".number_format($renta4,3,'.',',')."</td>
				<td align='right'>$".number_format($llantas4,3,'.',',')."</td>
				<td align='right'>$".number_format($seguros4,3,'.',',')."</td>
				<td align='right'>$".number_format($otros4,3,'.',',')."</td>
			 </tr></tfoot>";
		echo "</tbody>";
		echo "<tr><td style='display:none'><input type='hidden' name='contador' id='contador' value='".$cont."'></td></tr>";		
		?>
	</table>
</form>
		</center>
	</div>

<!-- FILTRO -->
<form action="ProrrateoMaquinaria.php" method="post" enctype="multipart/form-data" >
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
            	<select class="form-control" id="tramo" name="tramo">
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
           	<div class="col-md-3">Subtramo:</div>
           	<div class="col-md-6"><select class="form-control" name="subtramo" id="subtramo"></select></div>
         </div><br><input type="hidden" id="baseFiltro" name="baseFiltro">
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
<form action="Excel_ProMAQ.php" method="post" enctype="multipart/form-data" >
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
        <button type="submit" class="btn btn-success" name="descargar_maq" id="descargar_maq">Exportar</button>
      </div>
    </div>
  </div>
  </div>
</form>  

</body>
</html>