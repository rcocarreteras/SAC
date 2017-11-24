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
$anio = date("Y");
/***************************************************FILTRO*************************************************/	
	if (isset($_REQUEST['filtro'])) {
		$baseFiltro = $_POST["baseFiltro"];		
		$anio = $_POST["anioFiltro"];		
		
		if ($baseFiltro == "TODOS"){
			$sql = "SELECT DISTINCT(TRAMO) FROM CatTramos";			
		}else{
			$sql = "SELECT DISTINCT(TRAMO) FROM CatTramos WHERE TRAMO = '".$baseFiltro."'";	
		}
		
	}else{
				
		$sql = "SELECT DISTINCT(TRAMO) FROM CatTramos";// WHERE BASE='TO01'
		
	}

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


//CARGAMOS LA TABLA
$tabla="";
$tramosProg = array(0,0,0,0,0,0,0,0,0,0);
$tramosEjec = array(0,0,0,0,0,0,0,0,0,0);
$tramosCon = array(0,0,0,0,0,0,0,0,0,0);

$sql1 = "SELECT DISTINCT(SUBCUENTA) FROM PresupuestoDet";
//echo $sql1;
$rs = odbc_exec( $conn, $sql1);
if ( !$rs ) { 
	exit( "Error en la consulta SQL" );
}
while ( odbc_fetch_row($rs) ) { 
	$subcuenta = odbc_result($rs, 'SUBCUENTA');

	//OBTENEMOS LOS TOTALES POR TRAMO
	$programado1 = array(0,0,0,0,0,0,0,0,0,0);
	$ejecutado1 = array(0,0,0,0,0,0,0,0,0,0);
	$contratado1 = array(0,0,0,0,0,0,0,0,0,0);
	$programado = array(0,0,0,0,0,0,0,0,0,0);
	$ejecutado = array(0,0,0,0,0,0,0,0,0,0);
	$contratado = array(0,0,0,0,0,0,0,0,0,0);
	

	for ($i=0; $i < 10 ; $i++) { 
		switch ($i) {
			case '0':
				$tramo = "El Desperdicio - Lagos de Moreno";//
				break;
			case '1':
				$tramo = "El Desperdicio - Santa Maria de En Medio";//
				break;
			case '2':
				$tramo = "Leon - Aguascalientes";//
				break;
			case '3':
				$tramo = "Los Fresnos - Zapotlanejo";//
				break;
			case '4':
				$tramo = "Maravatio - Los Fresnos";//
				break;
			case '5':
				$tramo = "Zapotlanejo - El Desperdicio";//
				break;
			case '6':
				$tramo = "Zapotlanejo - Guadalajara";//
				break;
			case '7':
				$tramo = "La Barca - Jiquilpan";//
				break;
			case '8':
				$tramo = "Tepic - San Blas";//
				break;
			case '9':
				$tramo = "Zacapu - Panindicuaro";//
				break;
		}//switch
		//PROGRAMADO
	 	$sql2 = "SELECT SUM(IMPORTE) AS TOTAL FROM PresupuestoDet WHERE TRAMO= '".$tramo."' AND SUBCUENTA ='".$subcuenta."' AND CLASIFICACION = 'CAPEX' AND PERIODO='".$anio."'";
	 	//echo $sql2."<br>";
		$rs2 = odbc_exec( $conn2, $sql2);
		if ( !$rs2 ) { 
			exit( "Error en la consulta SQL" );
		}
		while ( odbc_fetch_row($rs2) ) { 
			$programado[$i] = odbc_result($rs2, 'TOTAL');
			$programado1[$i] = odbc_result($rs2, 'TOTAL');
			switch ($i) {
				case '0':
					$programado[$i] /= 1000000;
					$tramosProg[0] +=$programado[$i]; 
					break;
				case '1':
					$programado[$i] /= 1000000;
					$tramosProg[1] +=$programado[$i]; 
					break;
				case '2':
					$programado[$i] /= 1000000;
					$tramosProg[2] +=$programado[$i]; 
					break;
				case '3':
					$programado[$i] /= 1000000;
					$tramosProg[3] +=$programado[$i]; 
					break;
				case '4':
					$programado[$i] /= 1000000;
					$tramosProg[4] +=$programado[$i]; 
					break;
				case '5':
					$programado[$i] /= 1000000;
					$tramosProg[5] +=$programado[$i]; 
					break;
				case '6':
					$programado[$i] /= 1000000;
					$tramosProg[6] +=$programado[$i]; 
					break;
				case '7':
					$programado[$i] /= 1000000;
					$tramosProg[7] +=$programado[$i]; 
					break;
				case '8':
					$programado[$i] /= 1000000;
					$tramosProg[8] +=$programado[$i]; 
					break;
				case '9':
					$programado[$i] /= 1000000;
					$tramosProg[9] +=$programado[$i]; 
					break;
			}					
		}
		//EJECUTADO
		 $sql2 = "SELECT ISNULL(SUM(Estimaciones.MONTO), 0) - ISNULL(SUM(Estimaciones.RETENCION), 0) - ISNULL(SUM(Estimaciones.PENALIZACION), 0) + ISNULL(SUM(Estimaciones.DEVOLUCION), 0) - ISNULL(SUM(Estimaciones.AMORTIZACION), 0) AS TOTAL FROM Estimaciones INNER JOIN Contratos ON Estimaciones.CONTRATO = Contratos.CONTRATO AND Estimaciones.TRAMO = Contratos.TRAMO WHERE (Estimaciones.TRAMO = '".$tramo."') AND (Estimaciones.CLASIFICACION = 'CAPEX') AND (Contratos.SUBCUENTA = '".$subcuenta."') AND YEAR(Contratos.FECHA_INICIO) = '".$anio."'";
	  	//echo $sql2."<br>";
		 $rs2 = odbc_exec( $conn2, $sql2);
		 if ( !$rs2 ) { 
		 	exit( "Error en la consulta SQL" );
		 }
		 while ( odbc_fetch_row($rs2) ) { 
		 	$ejecutado[$i] = odbc_result($rs2, 'TOTAL');
		 	$ejecutado1[$i] = odbc_result($rs2, 'TOTAL');
		 	switch ($i) {
		 		case '0':
					$ejecutado[$i] /= 1000000;
		 			$tramosEjec[0] +=$ejecutado[$i]; 
		 			break;
		 		case '1':
					$ejecutado[$i] /= 1000000;
		 			$tramosEjec[1] +=$ejecutado[$i]; 
		 			break;
		 		case '2':
					$ejecutado[$i] /= 1000000;
		 			$tramosEjec[2] +=$ejecutado[$i]; 
		 			break;
		 		case '3':
					$ejecutado[$i] /= 1000000;
		 			$tramosEjec[3] +=$ejecutado[$i]; 
		 			break;
		 		case '4':
					$ejecutado[$i] /= 1000000;
		 			$tramosEjec[4] +=$ejecutado[$i]; 
		 			break;
		 		case '5':
					$ejecutado[$i] /= 1000000;
		 			$tramosEjec[5] +=$ejecutado[$i]; 
		 			break;
		 		case '6':
					$ejecutado[$i] /= 1000000;
		 			$tramosEjec[6] +=$ejecutado[$i]; 
		 			break;
		 		case '7':
					$ejecutado[$i] /= 1000000;
		 			$tramosEjec[7] +=$ejecutado[$i]; 
		 			break;
		 		case '8':
					$ejecutado[$i] /= 1000000;
		 			$tramosEjec[8] +=$ejecutado[$i]; 
		 			break;
		 		case '9':
					$ejecutado[$i] /= 1000000;
		 			$tramosEjec[9] +=$ejecutado[$i]; 
		 			break;
		 	}//switch			
		 }//while
		 
		 //CONTRATADO
		 $sql2 = "SELECT SUM(MONTO) AS TOTAL FROM Contratos WHERE (TRAMO = '".$tramo."') AND (CLASIFICACION = 'CAPEX') AND (SUBCUENTA = '".$subcuenta."') AND YEAR(FECHA_INICIO) = '".$anio."'";
	  	//echo $sql2."<br>";
		 $rs2 = odbc_exec( $conn2, $sql2);
		 if ( !$rs2 ) { 
		 	exit( "Error en la consulta SQL" );
		 }
		 while ( odbc_fetch_row($rs2) ) { 
		 	$contratado[$i] = odbc_result($rs2, 'TOTAL');
		 	$contratado1[$i] = odbc_result($rs2, 'TOTAL');
		 	switch ($i) {
		 		case '0':
					$contratado[$i] /= 1000000;
		 			$tramosCon[0] +=$contratado[$i]; 
		 			break;
		 		case '1':
					$contratado[$i] /= 1000000;
		 			$tramosCon[1] +=$contratado[$i]; 
		 			break;
		 		case '2':
					$contratado[$i] /= 1000000;
		 			$tramosCon[2] +=$contratado[$i]; 
		 			break;
		 		case '3':
					$contratado[$i] /= 1000000;
		 			$tramosCon[3] +=$contratado[$i]; 
		 			break;
		 		case '4':
					$contratado[$i] /= 1000000;
		 			$tramosCon[4] +=$contratado[$i]; 
		 			break;
		 		case '5':
					$contratado[$i] /= 1000000;
		 			$tramosCon[5] +=$contratado[$i]; 
		 			break;
		 		case '6':
					$contratado[$i] /= 1000000;
		 			$tramosCon[6] +=$contratado[$i]; 
		 			break;
		 		case '7':
					$contratado[$i] /= 1000000;
		 			$tramosCon[7] +=$contratado[$i]; 
		 			break;
		 		case '8':
					$contratado[$i] /= 1000000;
		 			$tramosCon[8] +=$contratado[$i]; 
		 			break;
		 		case '9':
					$contratado[$i] /= 1000000;
		 			$tramosCon[9] +=$contratado[$i]; 
		 			break;
		 	}//switch
		 }//while
	}//for


	$tabla.="<tr class = 'tabla'>";
	$tabla.="<td width='300'>".$subcuenta."</td>";
	$tabla.="<td align='right'>$".number_format($programado1[0],2)."</td>";
	$tabla.="<td align='right'>$".number_format($ejecutado1[0],2)."</td>";
	$tabla.="<td align='right'>$".number_format($programado1[1],2)."</td>";
	$tabla.="<td align='right'>$".number_format($ejecutado1[1],2)."</td>";
	$tabla.="<td align='right'>$".number_format($programado1[2],2)."</td>";
	$tabla.="<td align='right'>$".number_format($ejecutado1[2],2)."</td>";
	$tabla.="<td align='right'>$".number_format($programado1[3],2)."</td>";
	$tabla.="<td align='right'>$".number_format($ejecutado1[3],2)."</td>";
	$tabla.="<td align='right'>$".number_format($programado1[4],2)."</td>";
	$tabla.="<td align='right'>$".number_format($ejecutado1[4],2)."</td>";
	$tabla.="<td align='right'>$".number_format($programado1[5],2)."</td>";
	$tabla.="<td align='right'>$".number_format($ejecutado1[5],2)."</td>";
	$tabla.="<td align='right'>$".number_format($programado1[6],2)."</td>";
	$tabla.="<td align='right'>$".number_format($ejecutado1[6],2)."</td>";
	$tabla.="<td align='right'>$".number_format($programado1[7],2)."</td>";
	$tabla.="<td align='right'>$".number_format($ejecutado1[7],2)."</td>";
	$tabla.="<td align='right'>$".number_format($programado1[8],2)."</td>";
	$tabla.="<td align='right'>$".number_format($ejecutado1[8],2)."</td>";
	$tabla.="<td align='right'>$".number_format($programado1[9],2)."</td>";
	$tabla.="<td align='right'>$".number_format($ejecutado1[9],2)."</td></tr>";
	
}

//echo "Programado: ".max($programado);
//echo "Todo: ".max($tramosEjec)."	";
//echo "Contratado: ".max($contratado);
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
			

			var sampleData = [
                    { Tramo: 'DESP - LAGOS', Programado: <?php echo $tramosProg[0]; ?>, Contratado: <?php echo $tramosCon[0]; ?>, Ejecutado: <?php echo $tramosEjec[0]; ?> },
                    { Tramo: 'DESP - STA MARIA', Programado: <?php echo $tramosProg[1]; ?>, Contratado: <?php echo $tramosCon[1]; ?>, Ejecutado: <?php echo $tramosEjec[1]; ?> },
                    { Tramo: 'LEON - AGS', Programado: <?php echo $tramosProg[2]; ?>,  Contratado: <?php echo $tramosCon[2]; ?>, Ejecutado: <?php echo $tramosEjec[2]; ?> },
                    { Tramo: 'FRES - ZAP', Programado: <?php echo $tramosProg[3]; ?>, Contratado: <?php echo $tramosCon[3]; ?>, Ejecutado: <?php echo $tramosEjec[3]; ?> },
                    { Tramo: 'MAR - FRES', Programado: <?php echo $tramosProg[4]; ?>, Contratado: <?php echo $tramosCon[4]; ?>, Ejecutado: <?php echo $tramosEjec[4]; ?>},
                    { Tramo: 'ZAP - DESP', Programado: <?php echo $tramosProg[5]; ?>, Contratado: <?php echo $tramosCon[5]; ?>, Ejecutado: <?php echo $tramosEjec[5]; ?> },
                    { Tramo: 'ZAP - GDL', Programado: <?php echo $tramosProg[6]; ?>, Contratado: <?php echo $tramosCon[6]; ?>, Ejecutado: <?php echo $tramosEjec[6]; ?> },
                    { Tramo: 'BARCA - JUIQ', Programado: <?php echo $tramosProg[7]; ?>, Contratado: <?php echo $tramosCon[7]; ?>, Ejecutado: <?php echo $tramosEjec[7]; ?> },
                    { Tramo: 'TEPIC - SAN BLAS', Programado: <?php echo $tramosProg[8]; ?>, Contratado: <?php echo $tramosCon[8]; ?>, Ejecutado: <?php echo $tramosEjec[8]; ?> },
                    { Tramo: 'ZACAPU - PANI', Programado: <?php echo $tramosProg[9]; ?>, Contratado: <?php echo $tramosCon[9]; ?>, Ejecutado: <?php echo $tramosEjec[9]; ?> },
                ];
            // prepare jqxChart settings
            var settings = {
                title: "PRESUPUESTO CAPEX",
                description: "Programado vs Contratado vs Ejecutado",
                enableAnimations: true,
                showLegend: true,
                padding: { left: 10, top: 10, right: 15, bottom: 10 },
                titlePadding: { left: 90, top: 0, right: 0, bottom: 10 },
                source: sampleData,
                colorScheme: 'scheme05',
                xAxis: {
                    dataField: 'Tramo',
                    unitInterval: 1,
                    tickMarks: { visible: true, interval: 1 },
                    gridLinesInterval: { visible: true, interval: 1 },
                    valuesOnTicks: false,
                    padding: { bottom: 10 }
                },
                valueAxis: {
                    unitInterval: 100,
                    minValue: 10,
                    maxValue: 200,
                    title: { text: 'Presupuesto<br><br>' },
                    labels: { horizontalAlignment: 'right' }
                },
                seriesGroups:
                    [
                        {
                            type: 'column',
                            series:
                            [
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
                                    }
                                },
                                {
                                    dataField: 'Contratado',
                                    symbolType: 'square',
                                    labels:
                                    {
                                        visible: true,
                                        backgroundColor: '#FEFEFE',
                                        backgroundOpacity: 0.2,
                                        borderColor: '#7FC4EF',
                                        borderOpacity: 0.7,
                                        padding: { left: 5, right: 5, top: 0, bottom: 0 }
                                    }
                                },
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
			 if($_SESSION['S_Privilegios'] == 'ADMINISTRADOR'){
		?>
             <a href="Contratos.php"><span>Contratos</span></a>
		     <a href="Comparativo.php"><span>Comparativa</span></a>
		<?php } ?>    
		 <a href="PresupuestosCapex.php"><span>Ptto. Capex</span></a> 
	</header>
	<div id="encabezadoFijo">
		<a href="#" data-toggle="modal" data-target=".filtro"><div class="izquierda">
			<span class="glyphicon glyphicon-search"></span> &rlm; Filtrar
		</div> </a>	
        <form action="Excel_CAPEX.php" method="post" enctype="multipart/form-data" >
           <div class="derecha"><button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-download"></span> &rlm;descargar detalle</button>
                
            </div>
        </form>	
	</div>

	<div id="contenido">	
		<center>		
			<table width="2300" height="auto" border="1" id="tabla">
				<thead>
				<tr align="center" class="titulo">
			      <td width="200"></td>
			      <td colspan="2">DESP - LAGOS</td>
			      <td colspan="2">DESP - STA MARIA</td>	     
			      <td colspan="2">LEON - AGS</td>
			      <td colspan="2">FRES - ZAP</td>
			      <td colspan="2">MAR - FRES</td>
			      <td colspan="2">ZAP - DESP</td>
			      <td colspan="2">ZAP - GDL</td>
			      <td colspan="2">BARCA - JIQUILPAN</td>
			      <td colspan="2">TEPIC - SAN BLAS</td>	
			      <td colspan="2">ZACAPU - PANI</td>
				</tr>
			    <tr align="center" class="titulo">
			      <td>Actividad</td>
			      <td>Programado</td>
			      <td>Ejecutado</td>	     
			      <td>Programado</td>
			      <td>Ejecutado</td>
			      <td>Programado</td>
			      <td>Ejecutado</td>
			      <td>Programado</td>
			      <td>Ejecutado</td>
			      <td>Programado</td>
			      <td>Ejecutado</td>
			      <td>Programado</td>
			      <td>Ejecutado</td>
			      <td>Programado</td>
			      <td>Ejecutado</td>
			      <td>Programado</td>
			      <td>Ejecutado</td>
			      <td>Programado</td>
			      <td>Ejecutado</td>
			      <td>Programado</td>
			      <td>Ejecutado</td>			     
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
	

    
<!-- FILTRO -->
<form action="PresupuestosCapex.php" method="post" enctype="multipart/form-data" >
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
                    <option value="TODOS">TODOS</option>
                </select>
            </div>
        	<div class="col-lg-1">A&ntilde;o:</div>
        	<div class="col-lg-3"><input type="number" maxlength="4" class="form-control" id="anioFiltro" name="anioFiltro">
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
    	
</body>
</html>