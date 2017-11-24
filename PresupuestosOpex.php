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
if (isset($_REQUEST['nuevo'])) {
	$tramo = $_POST["tramo"];
	$tipo = $_POST["tipo"];
	$mes = $_POST["mes"];
	$concepto = $_POST["concepto"];
	$cantidad = $_POST["cantidad"];
	$importe = $_POST["importe"];

	$periodo = date('Y').$mes;


	$sql = "INSERT INTO PresupuestoOpex VALUES('".$concepto."','".$tipo."','".$cantidad."','".$importe."','".$tramo."','".$periodo."')";
	//echo $sql;  
	$rs = odbc_exec( $conn, $sql );
		$notificacion='Guardado';
	if ( !$rs ) { 
		exit( "Error en el registro" );
	}

}
$tabla="";
$ejecutadoTotal = array();	
$programadoTotal = array();
$filtro = "";
$baseFiltro = "Farac";
$anioFiltro = date("Y");
/***************************************************FILTRO*************************************************/	
	if (isset($_REQUEST['filtro'])) {
		$baseFiltro = $_POST["baseFiltro"];
		$anioFiltro = $_POST["anioFiltro"];
		
		if ($baseFiltro != "TODOS"){
			$filtro = " AND TRAMO = '".$baseFiltro."' ";
		}else{
			$baseFiltro = "Farac";
		}
	}

	//CARGAMOS LA TABLA
	for ($i=0; $i <= 11; $i++) {
		$ejecutadoMO = 0;
		$ejecutadoMA = 0;
		$ejecutadoIN = 0;
		$ejecutadoCO = 0;
		$programadoMO = 0;
		$programadoMA = 0;
		$programadoIN = 0;
		$programadoCO = 0;
		$monto = 0;
		$retencion = 0;
		$penalizacion = 0;
		$devolucion = 0;
		$amortizacion = 0;
		  
		switch ($i) {
			case '0':
				$mes = "Enero";
				$mesN = 1;
				$m = 12;
				$periodo = $anioFiltro."01";
				break;
			case '1':
				$mes = "Febrero";
				$mesN = 2;
				$m = 1;
				$periodo = $anioFiltro."02";
				break;
			case '2':
				$mes = "Marzo";
				$mesN = 3;
				$m = 2;
				$periodo = $anioFiltro."03";
				break;
			case '3':
				$mes = "Abril";
				$mesN = 4;
				$m = 3;
				$periodo = $anioFiltro."04";
				break;
			case '4':
				$mes = "Mayo";
				$mesN = 5;
				$m = 4;
				$periodo = $anioFiltro."05";
				break;
			case '5':
				$mes = "Junio";
				$mesN = 6;
				$m = 5;
				$periodo = $anioFiltro."06";
				break;
			case '6':
				$mes = "Julio";
				$mesN = 7;
				$m = 6;
				$periodo = $anioFiltro."07";
				break;
			case '7':
				$mes = "Agosto";
				$mesN = 8;
				$m = 7;
				$periodo = $anioFiltro."08";
				break;
			case '8':
				$mes = "Septiembre";
				$mesN = 9;
				$m = 8;
				$periodo = $anioFiltro."09";
				break;
			case '9':
				$mes = "Octubre";
				$mesN = 10;
				$m = 9;
				$periodo = $anioFiltro."10";
				break;
			case '10':
				$mes = "Noviembre";
				$mesN = 11;
				$m = 10;
				$periodo = $anioFiltro."11";
				break;
			case '11':
				$mes = "Diciembre";
				$mesN = 12;
				$m = 11;
				$periodo = $anioFiltro."12";
				break; 	
		}
		$sql1 = "SELECT DISTINCT(MES), SUM(COSTO) AS TOTAL FROM ProrrateoMO WHERE MES ='".$mesN."' ".$filtro." AND PERIODO LIKE '%".$anioFiltro."%' GROUP BY MES";
		$sql2 = "SELECT DISTINCT(MES), SUM(ACTIVO_FIJO+COMBUSTIBLE+MANTENIMIENTO+RENTA+LLANTAS+SEGUROS+OTROS) AS TOTAL FROM ProrrateoMAQ WHERE MES ='".$mesN."' ".$filtro." AND PERIODO LIKE '%".$anioFiltro."%' GROUP BY MES";
		$sql3 = "SELECT SUM(IMPORTE)*-1 AS TOTAL FROM AvanceDiario INNER JOIN Salidas ON AvanceDiario.AVANCE_ID = Salidas.AVANCE_ID WHERE AvanceDiario.FECHA BETWEEN('".$anioFiltro."-".$m."-26') AND ('".$anioFiltro."-".$mesN."-25')" .$filtro." ";
		$sql4 = "SELECT SUM(MONTO) AS MONTO, SUM(RETENCION) AS RETENCION, SUM(PENALIZACION) AS PENALIZACION, SUM(DEVOLUCION) AS DEVOLUCION, SUM(AMORTIZACION) AS AMORTIZACION FROM Estimaciones WHERE FECHA_INICIO BETWEEN('".$anioFiltro."-".$m."-26') AND ('".$anioFiltro."-".$mesN."-25') AND CLASIFICACION LIKE '%OPEX%'" .$filtro." ";		
		$sql5 = "SELECT SUM(IMPORTE) AS TOTAL FROM PresupuestoOpex WHERE TIPO = 'MO'  AND PERIODO='".$periodo."' ".$filtro." ";
		$sql6 = "SELECT SUM(IMPORTE) AS TOTAL FROM PresupuestoOpex WHERE TIPO = 'MA'  AND PERIODO='".$periodo."' ".$filtro." ";		
		$sql7 = "SELECT SUM(IMPORTE) AS TOTAL FROM PresupuestoOpex WHERE TIPO = 'IN'  AND PERIODO='".$periodo."' ".$filtro." ";
		$sql8 = "SELECT SUM(IMPORTE) AS TOTAL FROM PresupuestoOpex WHERE TIPO = 'CON' AND PERIODO='".$periodo."' ".$filtro." ";
			
		//echo "SQL1: ".$sql1;
		$rs = odbc_exec( $conn, $sql1);
		if ( !$rs ) { 
			exit( "Error en la consulta SQL" );
		}
		while ( odbc_fetch_row($rs) ) { 
			$ejecutadoMO = odbc_result($rs, 'TOTAL');					
		}
		
		//echo "SQL2: ".$sql2;
		$rs = odbc_exec( $conn, $sql2);
		if ( !$rs ) { 
			exit( "Error en la consulta SQL" );
		}
		while ( odbc_fetch_row($rs) ) { 
			$ejecutadoMA = odbc_result($rs, 'TOTAL');
		}
		//$sql3 = "SELECT SUM(IMPORTE) AS TOTAL FROM Salidas WHERE TIPO IN('S','E') AND FECHA BETWEEN('2016-".$m."-26') AND ('2016-".$mesN."-25')";
		//echo $sql3;
		$rs = odbc_exec( $conn, $sql3);
		if ( !$rs ) { 
			exit( "Error en la consulta SQL" );
		}
		while ( odbc_fetch_row($rs) ) { 
			$ejecutadoIN = odbc_result($rs, 'TOTAL');					
		}
		
		//echo $sql4."<br>";
		$rs = odbc_exec( $conn, $sql4);
		if ( !$rs ) { 
			exit( "Error en la consulta SQL" );
		}
		while ( odbc_fetch_row($rs) ) {
			$monto = odbc_result($rs, 'MONTO');					
			$retencion = odbc_result($rs, 'RETENCION');					
			$penalizacion = odbc_result($rs, 'PENALIZACION');
			$devolucion = odbc_result($rs, 'DEVOLUCION');
			$amortizacion = odbc_result($rs, 'AMORTIZACION');					

			$ejecutadoCO = $monto - $retencion - $penalizacion + $devolucion - $amortizacion;				
		}

		$ejecutadoTotal[$i] = $ejecutadoMO + $ejecutadoMA + $ejecutadoIN + $ejecutadoCO;

		//echo $sql5;
		$rs = odbc_exec( $conn, $sql5);
		if ( !$rs ) { 
			exit( "Error en la consulta SQL" );
		}
		while ( odbc_fetch_row($rs) ) { 
			$programadoMO = odbc_result($rs, 'TOTAL');					
		}
				
		//echo $sql6;
		$rs = odbc_exec( $conn, $sql6);
		if ( !$rs ) { 
			exit( "Error en la consulta SQL" );
		}
		while ( odbc_fetch_row($rs) ) { 
			$programadoMA = odbc_result($rs, 'TOTAL');					
		}
				
		//echo $sql7;
		$rs = odbc_exec( $conn, $sql7);
		if ( !$rs ) { 
			exit( "Error en la consulta SQL" );
		}
		while ( odbc_fetch_row($rs) ) { 
			$programadoIN = odbc_result($rs, 'TOTAL');					
		}

		//echo $sql8. "<br>";
		$rs = odbc_exec( $conn, $sql8);
		if ( !$rs ) { 
			exit( "Error en la consulta SQL" );
		}
		while ( odbc_fetch_row($rs) ) { 
			$programadoCO = odbc_result($rs, 'TOTAL');					
		}
		
		$programadoTotal[$i] = $programadoMO + $programadoMA + $programadoIN + $programadoCO;
			
		$tabla.="<tr class = 'tabla'>";
		$tabla.="<td width='100'>".$mes."</td>";
		$tabla.="<td align='right'>$".number_format($programadoMO,2)."</td>";
		$tabla.="<td align='right'>$".number_format($ejecutadoMO,2)."</td>";
		$tabla.="<td align='right'>$".number_format($programadoMA,2)."</td>";
		$tabla.="<td align='right'>$".number_format($ejecutadoMA,2)."</td>";
		$tabla.="<td align='right'>$".number_format($programadoIN,2)."</td>";
		$tabla.="<td align='right'>$".number_format($ejecutadoIN,2)."</td>";
		$tabla.="<td align='right'>$".number_format($programadoCO,2)."</td>";
		$tabla.="<td align='right'>$".number_format($ejecutadoCO,2)."</td>";
		$tabla.="<td align='right'>$".number_format($programadoTotal[$i],2)."</td>";
		$tabla.="<td align='right'>$".number_format($ejecutadoTotal[$i],2)."</td></tr>";
	}//for
	$tramo = $baseFiltro;


//AUTOCOMPLETAMOS 
$concepto = array();
$bandera = false;
$x=0;
$sql = "SELECT DISTINCT(CONCEPTO) FROM PresupuestoOpex";
  //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }    
  while ( odbc_fetch_row($rs) ) { 
    $concepto[$x] = "\"" .odbc_result($rs, 'CONCEPTO'). "\",";	
	$x++;

	$bandera = true;
}//While
if ($bandera == true) {	
	$concepto[$x-1] = str_replace(",","",$concepto[$x-1]);
	$datos =  $concepto;
}else{
	$datos="";
}

//echo json_encode($datos);

// $maxProg = max($programadoTotal);
// $maxEjec = max($ejecutadoTotal);
// echo $maxProg."<br>";
// echo $maxEjec."<br>";

// if (max($programadoTotal) > max($ejecutadoTotal)) {
// 	$maximo = max($programadoTotal);
// }else{
// 	$maximo = max($ejecutadoTotal);
// }



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
    <script type="text/javascript" src="jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdata.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxinput.js"></script>
    <link href="css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="js/bootstrap-dialog.min.js"></script>
    <script type="text/javascript" src="js/jqueryFileTree.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdraw.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxchart.core.js"></script>
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
		#resumen{
			width: 50%;
			height: 400px;
		}
		#grafica{
			width: 50%;
			height: 400px;
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
		.derecha2{
			float: right;
			margin-right: 60px;
			color:#000;
		}
		.tramo{
			font-family: 'commandocommando';
			font-size: 18px;
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
			//AUTOCOMPLETAR
			var concepto = new Array(<?php  
	        foreach ($concepto as &$valor) {
              echo $valor;
            }		
	        ?>);
			$("#concepto").jqxInput({placeHolder: "Escribe un concepto", minLength: 1,  source: concepto});

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
				
			var eneE = <?php echo $ejecutadoTotal[0]; ?>;
			var febE = <?php echo $ejecutadoTotal[0] + $ejecutadoTotal[1]; ?>;
			var marE = <?php echo $ejecutadoTotal[0] + $ejecutadoTotal[1] + $ejecutadoTotal[2]; ?>;
			var abrE = <?php echo $ejecutadoTotal[0] + $ejecutadoTotal[1] + $ejecutadoTotal[2] + $ejecutadoTotal[3]; ?>;
			var mayE = <?php echo $ejecutadoTotal[0] + $ejecutadoTotal[1] + $ejecutadoTotal[2] + $ejecutadoTotal[3] + $ejecutadoTotal[4]; ?>;
			var junE = <?php echo $ejecutadoTotal[0] + $ejecutadoTotal[1] + $ejecutadoTotal[2] + $ejecutadoTotal[3] + $ejecutadoTotal[4] + $ejecutadoTotal[5]; ?>;
			var julE = <?php echo $ejecutadoTotal[0] + $ejecutadoTotal[1] + $ejecutadoTotal[2] + $ejecutadoTotal[3] + $ejecutadoTotal[4] + $ejecutadoTotal[5] + $ejecutadoTotal[6]; ?>;
			var agoE = <?php echo $ejecutadoTotal[0] + $ejecutadoTotal[1] + $ejecutadoTotal[2] + $ejecutadoTotal[3] + $ejecutadoTotal[4] + $ejecutadoTotal[5] + $ejecutadoTotal[6] + $ejecutadoTotal[7];?>;
			var sepE = <?php echo $ejecutadoTotal[0] + $ejecutadoTotal[1] + $ejecutadoTotal[2] + $ejecutadoTotal[3] + $ejecutadoTotal[4] + $ejecutadoTotal[5] + $ejecutadoTotal[6] + $ejecutadoTotal[7] + $ejecutadoTotal[8]; ?>;
			var octE = <?php echo $ejecutadoTotal[0] + $ejecutadoTotal[1] + $ejecutadoTotal[2] + $ejecutadoTotal[3] + $ejecutadoTotal[4] + $ejecutadoTotal[5] + $ejecutadoTotal[6] + $ejecutadoTotal[7] + $ejecutadoTotal[8] + $ejecutadoTotal[9]; ?>;
			var novE = <?php echo $ejecutadoTotal[0] + $ejecutadoTotal[1] + $ejecutadoTotal[2] + $ejecutadoTotal[3] + $ejecutadoTotal[4] + $ejecutadoTotal[5] + $ejecutadoTotal[6] + $ejecutadoTotal[7] + $ejecutadoTotal[8] + $ejecutadoTotal[9] + $ejecutadoTotal[10]; ?>;
			var dicE = <?php echo $ejecutadoTotal[0] + $ejecutadoTotal[1] + $ejecutadoTotal[2] + $ejecutadoTotal[3] + $ejecutadoTotal[4] + $ejecutadoTotal[5] + $ejecutadoTotal[6] + $ejecutadoTotal[7] + $ejecutadoTotal[8] + $ejecutadoTotal[9] + $ejecutadoTotal[10] + $ejecutadoTotal[11]; ?>;

			var eneP = <?php echo $programadoTotal[0]; ?>;
			var febP = <?php echo $programadoTotal[0] + $programadoTotal[1]; ?>;
			var marP = <?php echo $programadoTotal[0] + $programadoTotal[1] + $programadoTotal[2]; ?>;
			var abrP = <?php echo $programadoTotal[0] + $programadoTotal[1] + $programadoTotal[2] + $programadoTotal[3]; ?>;
			var mayP = <?php echo $programadoTotal[0] + $programadoTotal[1] + $programadoTotal[2] + $programadoTotal[3] + $programadoTotal[4]; ?>;
			var junP = <?php echo $programadoTotal[0] + $programadoTotal[1] + $programadoTotal[2] + $programadoTotal[3] + $programadoTotal[4] + $programadoTotal[5]; ?>;
			var julP = <?php echo $programadoTotal[0] + $programadoTotal[1] + $programadoTotal[2] + $programadoTotal[3] + $programadoTotal[4] + $programadoTotal[5] + $programadoTotal[6]; ?>;
			var agoP = <?php echo $programadoTotal[0] + $programadoTotal[1] + $programadoTotal[2] + $programadoTotal[3] + $programadoTotal[4] + $programadoTotal[5] + $programadoTotal[6] + $programadoTotal[7];?>;
			var sepP = <?php echo $programadoTotal[0] + $programadoTotal[1] + $programadoTotal[2] + $programadoTotal[3] + $programadoTotal[4] + $programadoTotal[5] + $programadoTotal[6] + $programadoTotal[7] + $programadoTotal[8]; ?>;
			var octP = <?php echo $programadoTotal[0] + $programadoTotal[1] + $programadoTotal[2] + $programadoTotal[3] + $programadoTotal[4] + $programadoTotal[5] + $programadoTotal[6] + $programadoTotal[7] + $programadoTotal[8] + $programadoTotal[9]; ?>;
			var novP = <?php echo $programadoTotal[0] + $programadoTotal[1] + $programadoTotal[2] + $programadoTotal[3] + $programadoTotal[4] + $programadoTotal[5] + $programadoTotal[6] + $programadoTotal[7] + $programadoTotal[8] + $programadoTotal[9] + $programadoTotal[10]; ?>;
			var dicP = <?php echo $programadoTotal[0] + $programadoTotal[1] + $programadoTotal[2] + $programadoTotal[3] + $programadoTotal[4] + $programadoTotal[5] + $programadoTotal[6] + $programadoTotal[7] + $programadoTotal[8] + $programadoTotal[9] + $programadoTotal[10] + $programadoTotal[11]; ?>;

			if (dicP > dicE) {
				var max = dicP + 5000000;
			}else{
				var max = dicE + 5000000;
			}


			var sampleData = [					
                    { Mes: 'Enero', Programado: eneP, Ejecutado: eneE, Cycling: 25, Goal: 40 },
                    { Mes: 'Febrero', Programado: febP, Ejecutado: febE, Cycling: 10, Goal: 50 },
                    { Mes: 'Marzo', Programado: marP, Ejecutado: marE, Cycling: 25, Goal: 60 },
                    { Mes: 'Abril', Programado: abrP, Ejecutado: abrE, Cycling: 25, Goal: 40 },
                    { Mes: 'Mayo', Programado: mayP, Ejecutado: mayE, Cycling: 25, Goal: 50 },
                    { Mes: 'Junio', Programado: junP, Ejecutado: junE, Cycling: 30, Goal: 60 },
                    { Mes: 'Julio', Programado: julP, Ejecutado: julE, Cycling: 10, Goal: 90 },
                    { Mes: 'Agosto', Programado: agoP, Ejecutado: agoE, Cycling: 30, Goal: 60 },
                    { Mes: 'Septiembre', Programado: sepP, Ejecutado: sepE, Cycling: 30, Goal: 60 },
                    { Mes: 'Octubre', Programado: octP, Ejecutado: octE, Cycling: 30, Goal: 60 },
                    { Mes: 'Noviembre', Programado: novP, Ejecutado: novE, Cycling: 30, Goal: 60 },
                    { Mes: 'Diciembre', Programado: dicP, Ejecutado: dicE, Cycling: 30, Goal: 60 },
                ];
            // prepare jqxChart settings
            var settings = {
                title: "PRESUPUESTO OPEX",
                description: "Programado vs Ejecutado",
                enableAnimations: true,
                showLegend: true,
                padding: { left: 10, top: 10, right: 15, bottom: 10 },
                titlePadding: { left: 90, top: 0, right: 0, bottom: 10 },
                source: sampleData,
                colorScheme: 'scheme05',
                xAxis: {
                    dataField: 'Mes',
                    unitInterval: 1,
                    tickMarks: { visible: true, interval: 1 },
                    gridLinesInterval: { visible: true, interval: 1 },
                    valuesOnTicks: false,                    
                    padding: { bottom: 10 }
                },
                valueAxis: {
                    unitInterval: 5000000,
                    minValue: eneP,
                    maxValue: max,
                    title: { text: 'Presupuesto<br><br>' },
                    labels: { horizontalAlignment: 'right'},
                    formatSettings: { 
                    	//sufix: '$',
                    	perfix: '$',
                    	decimalPlaces: 0,
                    	decimalSeparator: '.',
                    	thousandsSeparator :',',
                    	negativeWithBrackets: true
                	}                  
                },

                seriesGroups:
                    [
                        {
                            type: 'line',
                            series:
                            [
                                {
                                    dataField: 'Ejecutado',
                                    symbolType: 'square',
                                    labels:
                                    {	
                                        visible: true,
                                        backgroundColor: '#FEFEFE',
                                        backgroundOpacity: 0.2,
                                        borderColor: '#7FC4EF',
                                        borderOpacity: 0.7,                                        
                                        padding: { left: 5, right: 5, top: 0, bottom: 0 }
                                    },
                                    formatSettings: {
                                    	//sufix: '$',
                    					perfix: '$',
                    					decimalPlaces: 2,
                    					decimalSeparator: '.',
                    					thousandsSeparator :',',
                    					negativeWithBrackets: true
                					}    
                                },
                                {
                                    dataField: 'Programado',
                                    symbolType: 'square',
                                    labels:
                                    {
                                        visible: true,
                                        backgroundColor: '#FEFEFE',
                                        backgroundOpacity: 0.2,
                                        borderColor: '#7FC4EF',
                                        borderOpacity: 0.7,
                                        padding: { left: 5, right: 5, top: 0, bottom: 0 }
                                    },
                                    formatSettings: {
                                    	//sufix: '$',
                    					perfix: '$',
                    					decimalPlaces: 2,
                    					decimalSeparator: '.',
                    					thousandsSeparator :',',
                    					negativeWithBrackets: true
                					}   
                                }
                            ]
                        }
                    ]
            };
            // setup the chart
            $('#chartContainer').jqxChart(settings);
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
		<a href="#" data-toggle="modal" data-target=".agregar"><div class="derecha2"><span class="glyphicon glyphicon-plus"></span> Nuevo</div> </a>
	</div>

	<div id="contenido">
		<center><span class="tramo"><?php echo $tramo; ?></span>
			<table width="100%" height="auto" border="1" id="tabla">
				<thead>
			    <tr align="center" class="titulo">
			      <td width="100"></td>
			      <td colspan="2">Mano de Obra </td>
			      <td width="163" colspan="2">Maquinaria</td>	     
			      <td width="126" colspan="2">Materiales</td>
			      <td width="126" colspan="2">Contratos</td>
			      <td width="126" colspan="2">Acumulados</td>
			    </tr>
			    <tr align="center" class="titulo">
			      <td width="100">Mes</td>
			      <td width="150">Programado</td>
			      <td width="150">Ejecutado</td>	     
			      <td width="150">Programado</td>
			      <td width="150">Ejecutado</td>
			      <td width="150">Programado</td>
			      <td width="150">Ejecutado</td>
			      <td width="150">Programado</td>
			      <td width="150">Ejecutado</td>
			      <td width="150">Programado</td>
			      <td width="150">Ejecutado</td>
			    </tr>
			  </thead>
			  <tbody>
			  <?php echo $tabla; ?>
			  </tbody>
			</table>	
			<br>
			<div id='chartContainer' style="width:100%; height:400px"></div>	
		</center>		
	</div>

	

		

<!-- AGREGAR -->
<form action="PresupuestosOpex.php" method="post" enctype="multipart/form-data" >
<div class="modal fade agregar" id="agregar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><center> Agregar Programaci&oacute;n OPEX </center></h4>
      </div>
      <div class="modal-body">
      	<!--CUERPO-->
        <div class="row">        	
        	<div class="col-lg-2">Tramo:</div>
        	<div class="col-lg-9">
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
                </select>
            </div>
        </div><br>
        <div class="row">
        	<div class="col-lg-2">Tipo:</div>
        	<div class="col-lg-4">
        		<select class="form-control" id="tipo" name="tipo">
        			<option value='MO'>Mano de Obra</option>
        			<option value='MA'>Maquinaria</option>
        			<option value='IN'>Materiales</option>
        		</select>        		
        	</div>        	
        	<div class="col-lg-1">Mes:</div>
        	<div class="col-lg-4">
        		<select class="form-control" id="mes" name="mes">
        			<option value='01'>Enero</option>
        			<option value='02'>Febrero</option>
        			<option value='03'>Marzo</option>
        			<option value='04'>Abril</option>
        			<option value='05'>Mayo</option>
        			<option value='06'>Junio</option>
        			<option value='07'>julio</option>
        			<option value='08'>Agosto</option>
        			<option value='09'>Septiembre</option>
        			<option value='10'>Octubre</option>
        			<option value='11'>Noviembre</option>
        			<option value='12'>Diciembre</option>
        		</select>        		
        	</div>
        </div><br>
        <div class="row">
        	<div class="col-lg-2">Concepto:</div>
        	<div class="col-lg-9">
            	<input type="text" class="form-control" id="concepto" name="concepto" required="true" autocomplete="false">
            </div>
        </div><br>
        <div class="row">
        	<div class="col-lg-2">Cantidad:</div><div class="col-lg-3"><input type="number" min="0" class="form-control" id="cantidad" name="cantidad" value="0" step="0.01"></div>
        	<div class="col-lg-1"></div>
        	<div class="col-lg-2">Importe:</div><div class="col-lg-3"><input type="number" min="0" class="form-control" id="importe" name="importe" value="0" step="0.01"></div>
        </div><br>
        <!--FIN-->
   </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="nuevo" id="nuevo">Guardar</button>
      </div>
    </div>
  </div>
  </div>
</form>       	  


    
<!-- FILTRO -->
<form action="PresupuestosOpex.php" method="post" enctype="multipart/form-data" >
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
        	<div class="col-lg-1">Tramo:</div>
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
                    <option value="TODOS">Farac</option>
                </select>
            </div>
        	<div class="col-lg-1">A&ntilde;o:</div>
        	<div class="col-lg-3"><input type="number" class="form-control" id="anioFiltro" name="anioFiltro">
            </div>
        </div><br>        
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
    	
</body>
</html>