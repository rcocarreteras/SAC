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
$subcuenta = "";
$tramo = "";
/***************************************************FILTRO*************************************************/	
	if (isset($_REQUEST['filtro'])) {
		$tramo = $_POST["tramoFiltro"];

		if ($tramo == "Todos"){
			$sql = "SELECT TRAMO, SUBCUENTA, SUM(CANTIDAD) AS SUM_PRESUPUESTO FROM PresupuestoDet WHERE CLASIFICACION = 'CAPEX' AND PERIODO = '".date("Y")."' GROUP BY TRAMO, SUBCUENTA ORDER BY TRAMO";
		}else{

			$sql = "SELECT TRAMO, SUBCUENTA, SUM(CANTIDAD) AS SUM_PRESUPUESTO FROM PresupuestoDet WHERE CLASIFICACION = 'CAPEX' AND PERIODO = '".date("Y")."' AND TRAMO='".$tramo."' GROUP BY TRAMO, SUBCUENTA ORDER BY TRAMO";			
		}
		
	}else{

		$sql = "SELECT TRAMO, SUBCUENTA, SUM(CANTIDAD) AS SUM_PRESUPUESTO FROM PresupuestoDet WHERE CLASIFICACION = 'CAPEX' AND PERIODO = '".date("Y")."' AND SUBCUENTA LIKE '%SUPERVI%' AND TRAMO='El Desperdicio - Lagos de Moreno' GROUP BY TRAMO, SUBCUENTA ORDER BY TRAMO";
		//
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
			/*margin-right: 10px;*/
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
		.cantidad{	
			font-family: ubuntu_titlingbold;
			font-size: 20px;
		}
		.tabla{			
			font-family: ubuntu_titlingbold;
			font-size: 14px;
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
		<!--<img src="images/HEADBIOMETRICO.png"  width="153" height="46">-->
		<a href="index.php"><img class="derecha" src="images/cerrarsesion.png"></a>
		<a href="Almacen.php"><span>Insumos</span></a>
		<a href="Salidas.php"><span>Salida Insumos</span></a>
		<a href="AlmacenMaq.php"><span>Maquinaria</span></a>
		<a href="SalidasMaq.php"><span>Entrada Maq</span></a>
		<a href="AvanceDiarioPlus.php"><span>Avance Diario</span></a>
		<a href="Contratos.php"><span>Contratos</span></a>
		<a href="Comparativo.php"><span>Comparativa</span></a>
	</header>
	<!--<div id="encabezadoFijo">
		<a href="#" data-toggle="modal" data-target=".filtro"><div class="izquierda">
			<span class="glyphicon glyphicon-search"></span> &rlm; Filtrar
		</div> </a>
		<a href="#" data-toggle="modal" data-target=".nuevo"><div class="derecha">
			<span class="glyphicon glyphicon-plus"></span> &rlm; Agregar Contrato
		</div> </a>
		<a href="#" data-toggle="modal" data-target=".estimacion"><div class="derecha">
			<span class="glyphicon glyphicon-plus"></span> &rlm; Agregar Estimacion
		</div> </a>
	</div>-->

	<div id="contenido">
		<br>
		<center>
        <span class="cantidad">CANTIDADES</span><br><br>
		<table width="auto" height="auto" border="1" id="tabla">
		  <thead>
		    <tr align="center" class="titulo">
		      <td width="300px">TRAMO/SUBCUENTA</td>	
		      <td width="200px">PRESUPUESTADO</td>
		      <td width="200px">EJECUTADO</td>
              <td width="200px">DIF PRES-EJER</td>
		    </tr>
		  </thead>
		  <tbody>  
		<?php 
		$tramo1 = "";
		$presupuesto2 = 0;
		//$actividad = array();
		$diferencia2 = 0;
		$ejecutado2 = 0;
		$resta = 0;
		$importe = 0;
		$monto = 0;
		$diferencia = 0;
		$cont = 0;
		$sub_presupuesto = 0;
		$sub_ejecutado = 0;
		$sub_diferencia = 0;
		$x = 0;

		//echo $sql."<br>";
		$rs = odbc_exec( $conn, $sql );
		if ( !$rs ) { 
			exit( "Error en la consulta SQL" ); 
		}
		while ( odbc_fetch_row($rs) ) {			 
			$presupuesto = odbc_result($rs, 'SUM_PRESUPUESTO');
			$subcuenta = odbc_result($rs, 'SUBCUENTA');
			$tramo = odbc_result($rs, 'TRAMO');

			$sql2 = "SELECT SUM (AvanceDiario.CANTIDAD) AS EJECUTADO FROM AvanceDiario INNER JOIN CatConcepto ON AvanceDiario.ACTIVIDAD = CatConcepto.DesCpt COLLATE SQL_Latin1_General_CP1_CI_AS WHERE TRAMO='".$tramo."' AND YEAR(FECHA)='".date("Y")."' AND Subcuenta='".$subcuenta."'";
			//echo $sql2."<br>";
			$rs2 = odbc_exec( $conn2, $sql2 );
			if ( !$rs2 ) { 
				exit( "Error en la consulta AvanceDiario" ); 
			} 
			while ( odbc_fetch_row($rs2) ) { 
				$ejecutado = odbc_result($rs2, 'EJECUTADO');
			}

				echo "<tbody>";
				   if ($tramo1 != $tramo){
				   		if($cont != 0){
				   			echo "<tr class = 'tabla'>
								<td align='center'><strong><h5>SUB TOTAL</h5></strong></td>
								<td align='right'>".number_format($sub_presupuesto,2,'.',',')."</td>
								<td align='right'>".number_format($sub_ejecutado,2,'.',',')."</td>
								<td align='right'>".number_format($sub_diferencia,2,'.',',')."</td>
							</tr>";
							$sub_presupuesto = 0;
							$sub_ejecutado = 0;
							$sub_diferencia = 0;
				   		}

				   echo "<tr class = 'tabla'>";	
					   echo "<td align='center' colspan='4' style='color:'><strong><h4>".$tramo."</h4></strong></td>
				   </tr>";
				   }
				   $tramo1 = $tramo;
				   
				   $diferencia = $presupuesto - $ejecutado;

					echo "<tr class = 'tabla'>
					          <td align='center'>".$subcuenta."</td>
							  <td align='right'>".number_format($presupuesto,2,'.',',')."</td>
							  <td align='right'>".number_format($ejecutado,2,'.',',')."</td>
							  <td align='right'>".number_format($diferencia,2,'.',',')."</td>
					      </tr>";

					  $sub_presupuesto += $presupuesto;
					  $sub_ejecutado += $ejecutado;
					  $sub_diferencia += $diferencia;

					  $presupuesto2 += $presupuesto;
					  $ejecutado2 += $ejecutado;

					$sql2 = "SELECT CUENTA, SUM(CANTIDAD) AS IMPORTE FROM PresupuestoDet WHERE CLASIFICACION='CAPEX' AND SUBCUENTA='".$subcuenta."' AND TRAMO ='".$tramo."' AND PERIODO = '".date("Y")."' GROUP BY CUENTA";
					//echo $sql2."<br>";
					$rs2 = odbc_exec( $conn2, $sql2 );
					if ( !$rs2 ) {
						exit( "Error en la consulta PresupuestoDet" ); 
					} 
					while ( odbc_fetch_row($rs2) ) { 
						$importe = odbc_result($rs2, 'IMPORTE');							
						$actividad = odbc_result($rs2, 'CUENTA');
						//$x++;
								echo "<tr class = 'tabla'>
										<td align='left'>".$actividad."</td>
										<td align='right'>".number_format($importe,2,'.',',')."</td>";

						$sql3 = "SELECT SUM (AvanceDiario.CANTIDAD) AS EJECUTADO, ACTIVIDAD FROM AvanceDiario INNER JOIN CatConcepto ON AvanceDiario.ACTIVIDAD = CatConcepto.DesCpt COLLATE SQL_Latin1_General_CP1_CI_AS WHERE TRAMO='".$tramo."' AND YEAR(FECHA)='".date("Y")."' AND Subcuenta='".$subcuenta."' GROUP BY ACTIVIDAD";
						//echo $sql3."<br>";
						$rs3 = odbc_exec( $conn3, $sql3 );
						if ( !$rs3 ) { 
							exit( "Error en la consulta SQL" );	
						}
						while ( odbc_fetch_row($rs3) ) {
							$monto = odbc_result($rs3, 'EJECUTADO');
							$actividad2 = odbc_result($rs3, 'ACTIVIDAD');

						$resta = $importe - $monto;
							if($actividad == $actividad2){
								echo"	<td align='right'>".number_format($monto,2,'.',',')."</td>
										<td align='right'>".number_format($resta,2,'.',',')."</td>
									  </tr>";
							}else{
								echo "	<td align='right'>".number_format(0,2,'.',',')."</td>
										<td align='right'>".number_format(0,2,'.',',')."</td>
									  </tr>
									<tr class = 'tabla'>
										<td align='left'>".$actividad2."</td>
										<td align='right'>".number_format("0",2,'.',',')."</td>
										<td align='right'>".number_format($monto,2,'.',',')."</td>
										<td align='right'>".number_format($resta,2,'.',',')."</td>
									  </tr>";							
							}
						//$e++;
						}//while3

						$importe = 0;
						$monto = 0;
						$resta = 0;	
					}//While2
					$cont++;
		}//while

		$diferencia2 = $presupuesto2 - $ejecutado2;
					echo "<tfoot>
							<tr class = 'tabla'>
								<td align='center'><strong><h5>TOTALES</h5></strong></td>
								<td align='right'>".number_format($presupuesto2,2,'.',',')."</td>
								<td align='right'>".number_format($ejecutado2,2,'.',',')."</td>
								<td align='right'>".number_format($diferencia2,2,'.',',')."</td>
							</tr>
						   </tfoot>";
		?>
		</table>
		</center>	
	</div>
    
<!-- FILTRO -->
<form action="ComparativoCantidades1.php" method="post" enctype="multipart/form-data" >
<div class="modal fade filtro" id="filtro" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
      </div>
      <div class="modal-body">
      	<!--CUERPO-->
        <div class="row">
        	<div class="col-lg-2">Tramo:</div>
        	<div class="col-lg-8">
            	<select class="form-control" id="tramoFiltro" name="tramoFiltro">
                	<?php
		  			$i=1;
		 			$selected="";
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
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="refresh()">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="filtro" id="filtro">Filtrar</button>
      </div>
    </div>
  </div>
</div>
</form>       	   	
    	
</body>
</html>