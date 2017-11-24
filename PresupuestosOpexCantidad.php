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
/***************************************************FILTRO*************************************************/	
	if (isset($_REQUEST['filtro'])) {
		$tramo = $_POST["baseFiltro"];			
		$subcuenta = $_POST["subcuenta"];
		
		//if (isset($_POST['anioFiltro'])){
			  $anio = $_POST['anioFiltro'];
		  /*}else{
			   $anio = date("Y");
		  }*/
		
		if ($subcuenta == "TODOS"){
			$sql1 = "SELECT DISTINCT(Subcuenta), Orden FROM CatConcepto ORDER BY Orden";
		}else{
			$sql1 = "SELECT DISTINCT(Subcuenta), Orden FROM CatConcepto WHERE Subcuenta='".$subcuenta."'";	
		}
		
	}else{
		
		$tramo = "Zapotlanejo - El Desperdicio";
		$anio = date("Y");
		$sql1 = "SELECT DISTINCT(Subcuenta), Orden FROM CatConcepto ORDER BY Orden";
		
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
$PorEje = 0;
$PorProg = 0;
$prog1 = 0;
$Eje1 = 0;


//echo $sql1;
$subcuenta1 = "";
$rs = odbc_exec( $conn, $sql1);
if ( !$rs ) { 
	exit( "Error en la consulta SQL" );
}
while ( odbc_fetch_row($rs) ) { 
	$subcuenta = odbc_result($rs, 'Subcuenta');
	
	//OBTENEMOS LOS TOTALES POR TRAMO
	$actividadEje = array(0,0,0,0,0,0,0,0,0,0,0,0);
	$programado = array(0,0,0,0,0,0,0,0,0,0,0,0);
	

				 
		 /******************SACAR ACTIVIDADES*****************/
	 	$sql2 = "SELECT DISTINCT(DesCpt) FROM CatConcepto WHERE Subcuenta ='".$subcuenta."' ORDER BY DesCpt";
	 	//echo $sql2."<br>";
		$rs2 = odbc_exec( $conn2, $sql2);
		if ( !$rs2 ) { 
			exit( "Error en la consulta SQL" );
		}
		while ( odbc_fetch_row($rs2) ) { 
			$actividad = odbc_result($rs2, 'DesCpt');
			for ($i=0; $i <= 11; $i++) {
				switch ($i) {
					case '0':
						$mes = "01";
						break;
					case '1':
						$mes = "02";
						break;
					case '2':
						$mes = "03";
						break;
					case '3':
						$mes = "04";
						break;
					case '4':
						$mes = "05";
						break;
					case '5':
						$mes = "06";
						break;
					case '6':
						$mes = "07";
						break;
					case '7':
						$mes = "08";
						break;
					case '8':
						$mes = "09";
						break;
					case '9':
						$mes = "10";
						break;
					case '10':
						$mes = "11";
						break;
					case '11':
						$mes = "12";
						break;
				}//switch
			
			//ACTIVIDADES PROGRAMADAS
			$sql3 = "SELECT ISNULL(SUM(CANTIDAD), 0) AS PROGRAMADO FROM PresupuestoOpex WHERE TRAMO= '".$tramo."' AND CONCEPTO ='".$actividad."' AND PERIODO = '".$anio.$mes."'";
			//echo $sql3."<br>";
			$rs3 = odbc_exec( $conn3, $sql3);
			if ( !$rs3 ) { 
				exit( "Error en la consulta SQL" );
			}
			 while ( odbc_fetch_row($rs3) ) { 
				$programado[$i] = odbc_result($rs3, 'PROGRAMADO');
				//echo $actividad."-".$programado[$i]."<br>";
				$PorProg += $programado[$i]; 
				//echo $PorProg;

			}//while3	
			
			//ACTIVIDADES EJECUTADAS
			 $sql3 = "SELECT ISNULL(SUM(CANTIDAD), 0) AS TOTAL FROM AvanceDiario WHERE (FECHA LIKE '%".$anio."-".$mes."%') AND (ACTIVIDAD = '".$actividad."') AND (TRAMO = '".$tramo."') GROUP BY ACTIVIDAD";
			 //echo $sql3."<br>";
			 $rs3 = odbc_exec( $conn3, $sql3);
			 if ( !$rs3 ) { 
				exit( "Error en la consulta SQL" );
			 }
			 while ( odbc_fetch_row($rs3) ) { 
				$actividadEje[$i] = odbc_result($rs3, 'TOTAL');
				$PorEje += $actividadEje[$i]; 
				//echo $PorEje;
				
			 }//while3
			 
		}//for	
		$actividadEje[] = 0;
		$programado[] = 0;
				
			if($subcuenta1 != $subcuenta){
				$subcuenta1 = $subcuenta;
				
				$tabla.="<tr class = 'tablatd'><td width='300' align='center'>".$subcuenta."</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><div id='div".$i."'></div>";
				
			}else{

				if ($PorProg != 0){
					if ($PorEje != 0){
						$Eje1 = ($PorEje * 100) / $PorProg;					
					}
					
					$prog1 = "100";
				}
			}
			
				$tabla.="<tr class = 'tabla'>";
				$tabla.="<td width='300' class='tabla'>".$actividad."</td>";
				$tabla.="<td align='right'>".number_format($prog1,2)."%</td>";
				$tabla.="<td align='right'>".number_format($Eje1,2)."%</td>";
				$tabla.="<td align='right'>".number_format($programado[0],2)."</td>";
				$tabla.="<td align='right'>".number_format($actividadEje[0],2)."</td>";
				$tabla.="<td align='right'>".number_format($programado[1],2)."</td>";
				$tabla.="<td align='right'>".number_format($actividadEje[1],2)."</td>";
				$tabla.="<td align='right'>".number_format($programado[2],2)."</td>";
				$tabla.="<td align='right'>".number_format($actividadEje[2],2)."</td>";
				$tabla.="<td align='right'>".number_format($programado[3],2)."</td>";
				$tabla.="<td align='right'>".number_format($actividadEje[3],2)."</td>";
				$tabla.="<td align='right'>".number_format($programado[4],2)."</td>";
				$tabla.="<td align='right'>".number_format($actividadEje[4],2)."</td>";
				$tabla.="<td align='right'>".number_format($programado[5],2)."</td>";
				$tabla.="<td align='right'>".number_format($actividadEje[5],2)."</td>";
				$tabla.="<td align='right'>".number_format($programado[6],2)."</td>";
				$tabla.="<td align='right'>".number_format($actividadEje[6],2)."</td>";
				$tabla.="<td align='right'>".number_format($programado[7],2)."</td>";
				$tabla.="<td align='right'>".number_format($actividadEje[7],2)."</td>";
				$tabla.="<td align='right'>".number_format($programado[8],2)."</td>";
				$tabla.="<td align='right'>".number_format($actividadEje[8],2)."</td>";
				$tabla.="<td align='right'>".number_format($programado[9],2)."</td>";
				$tabla.="<td align='right'>".number_format($actividadEje[9],2)."</td>";
				$tabla.="<td align='right'>".number_format($programado[10],2)."</td>";
				$tabla.="<td align='right'>".number_format($actividadEje[10],2)."</td>";
				$tabla.="<td align='right'>".number_format($programado[11],2)."</td>";
				$tabla.="<td align='right'>".number_format($actividadEje[11],2)."</td></tr>";
				
			$PorEje = 0;
			$PorProg = 0;
		}//while
}//while

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Porcentajes Programado/Ejecutado</title>
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
			width:auto;
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
			width: auto;
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
		.tablatd{			
			font-family: ubuntu_titlingbold;
			font-size: 16px;
			/*background-color:#71b6ed;*/
		}
		.tramo{			
			font-family: ubuntu_titlingbold;
			font-size: 20px;
			color:#000000;
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
		<a href="Contratos.php"><span>Contratos</span></a>
		<a href="Comparativo.php"><span>Comparativa</span></a>
	</header>
	<div id="encabezadoFijo">
		<a href="#" data-toggle="modal" data-target=".filtro"><div class="izquierda">
			<span class="glyphicon glyphicon-search"></span> &rlm; Filtrar
		</div> </a>		
	</div>
    <center>
    <span class="tramo"><strong><?php echo $tramo; ?></strong></span>
    <br><br></center>

	<div id="contenido">	
		<center>		
			<table width="152%" height="auto" border="1" id="tabla">
				<thead>
				<tr align="center" class="titulo">
			      <td width="350"></td>
			      <td colspan="2">PORCENTAJE ANUAL</td>
			      <td colspan="2">ENERO</td>
			      <td colspan="2">FEBRERO</td>	     
			      <td colspan="2">MARZO</td>
			      <td colspan="2">ABRIL</td>
			      <td colspan="2">MAYO</td>
			      <td colspan="2">JUNIO</td>
			      <td colspan="2">JULIO</td>   
			      <td colspan="2">AGOSTO</td>
			      <td colspan="2">SEPTIEMBRE</td>
			      <td colspan="2">OCTUBRE</td>
			      <td colspan="2">NOVIEMBRE</td>
			      <td colspan="2">DICIEMBRE</td>				
				</tr>
			    <tr align="center" class="titulo">
			      <td>Subcuenta</td>
			      <td width="96">Programado</td>
			      <td width="90">Ejecutado</td>
			      <td width="94">Programado</td>
			      <td width="77">Ejecutado</td>
			      <td width="94">Programado</td>
			      <td width="77">Ejecutado</td>
			      <td width="94">Programado</td>
			      <td width="77">Ejecutado</td>
			      <td width="94">Programado</td>
			      <td width="78">Ejecutado</td>
			      <td width="94">Programado</td>
			      <td width="88">Ejecutado</td>
			      <td width="94">Programado</td>
			      <td width="94">Ejecutado</td>
			      <td width="94">Programado</td>
			      <td width="94">Ejecutado</td>	
			      <td width="96">Programado</td>
			      <td width="96">Ejecutado</td>
			      <td width="98">Programado</td>
			      <td width="100">Ejecutado</td>
			      <td width="106">Programado</td>
			      <td width="112">Ejecutado</td>
			      <td width="111">Programado</td>
			      <td width="113">Ejecutado</td>
			      <td width="115">Programado</td>
			      <td width="116">Ejecutado</td>			     
			    </tr>
			  </thead>
			  <tbody>
			  <?php echo $tabla; ?>
			  </tbody>
			</table>	
			<!--<br>
			<div id='chartContainer' style="width:100%; height:400px"></div>-->
		</center>
	</div>
	

    
<!-- FILTRO -->
<form action="PresupuestosOpexCantidad.php" method="post" enctype="multipart/form-data" >
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
        	<div class="col-lg-2">Subcuenta:</div>
        	<div class="col-lg-5">
            	<select class="form-control" id="subcuenta" name="subcuenta">
					<?php
					  $i=1;
					  $sql = "SELECT DISTINCT CUENTA FROM PresupuestoOpex WHERE CUENTA<>'' ORDER BY CUENTA";
					  echo $sql;
					  $rs = odbc_exec( $conn, $sql );
					  if ( !$rs ) { 
					   exit( "Error en la consulta SQL" ); 
					  }     
					  while ( odbc_fetch_row($rs) ) { 
						$cuenta = odbc_result($rs, 'CUENTA');
					   echo "<option id='".$i."'>".$cuenta."</option>";
					   $i++;
					  }//While 	 
                	?>
                    <option value="TODOS">Todas</option>
                </select>
            </div>
        	<div class="col-lg-1">A&ntilde;o:</div>
        	<div class="col-lg-3"><input type="number" maxlength="4" class="form-control" id="anioFiltro" name="anioFiltro" required>
            </div>
        </div> <br>
        <div class="row">
        	<div class="col-lg-2">Tramo:</div>
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
    	
</body>
</html>