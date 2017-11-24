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
			$sql = "SELECT TRAMO, SUBCUENTA, ISNULL(SUM(IMPORTE),0) AS MONTO_PRESUPUESTO FROM PresupuestoDet WHERE CLASIFICACION = 'CAPEX' AND PERIODO = '".date("Y")."' GROUP BY TRAMO, SUBCUENTA ORDER BY TRAMO";			
		}else{
			
			$sql = "SELECT TRAMO, SUBCUENTA, ISNULL(SUM(IMPORTE),0) AS MONTO_PRESUPUESTO FROM PresupuestoDet WHERE CLASIFICACION = 'CAPEX' AND TRAMO='".$tramo."' AND PERIODO = '".date("Y")."' GROUP BY TRAMO, SUBCUENTA ORDER BY TRAMO";			
		}
		
	}else{
		
		$sql = "SELECT TRAMO, SUBCUENTA, ISNULL(SUM(IMPORTE),0) AS MONTO_PRESUPUESTO FROM PresupuestoDet WHERE CLASIFICACION = 'CAPEX' AND PERIODO = '".date("Y")."' GROUP BY TRAMO, SUBCUENTA ORDER BY TRAMO";
		
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
		<!--<a href="#" data-toggle="modal" data-target=".nuevo"><div class="derecha">
			<span class="glyphicon glyphicon-plus"></span> &rlm; Agregar Contrato
		</div> </a>
		<a href="#" data-toggle="modal" data-target=".estimacion"><div class="derecha">
			<span class="glyphicon glyphicon-plus"></span> &rlm; Agregar Estimacion
		</div> </a>-->
		 		
	</div>

	<div id="contenido">
		<br>
		<center>
		<table width="auto" height="auto" border="1" id="tabla">
		  <thead>
		    <tr align="center" class="titulo">
		      <td width="300px">TRAMO/SUBCUENTA</td>	
		      <td width="200px">PRESUPUESTO</td>
		      <td width="200px">CONTRATO</td>      
		      <td width="200px">EJERCIDO</td>
		      <td width="200px">DIF PRES-CON</td>	
              <td width="200px">DIF CON-EJER</td>
		    </tr>
		  </thead>
		  <tbody>  
		<?php 
		$tramo1 = "";
		$presupuesto2 = 0;
		$contrato2 = 0;
		$estimacion2 = 0;
		$resta = 0;
		$resta2 = 0;
		$importe = 0;
		$monto = 0;
		$monto1 = 0;
		$monto_est = 0;
		$pre_con = 0;
		$pre_con2 = 0;
		$con_eje2 = 0;
		$cont = 0;
		$con = 0;
		$eje = 0;
		$p_c = 0;
		$c_e = 0;
		$pres=0;

		
		//$sql = "SELECT TRAMO, SUBCUENTA, ISNULL(SUM(IMPORTE),0) AS MONTO_PRESUPUESTO FROM PresupuestoDet WHERE CLASIFICACION = 'CAPEX' GROUP BY TRAMO, SUBCUENTA ORDER BY TRAMO";
		//echo $sql;
		$rs = odbc_exec( $conn, $sql );
		if ( !$rs ) { 
			exit( "Error en la consulta SQL" ); 
		}
		while ( odbc_fetch_row($rs) ) {			 
			$presupuesto = odbc_result($rs, 'MONTO_PRESUPUESTO');
			$subcuenta = odbc_result($rs, 'SUBCUENTA');
			$tramo = odbc_result($rs, 'TRAMO');

			$sql2 = "SELECT DISTINCT(CONTRATO),SUM(MONTO) AS MONTO FROM Contratos WHERE SUBCUENTA = '".$subcuenta."' AND TRAMO ='".$tramo."' and CLASIFICACION='CAPEX' AND YEAR(FECHA_INICIO) = '".date("Y")."' GROUP BY CONTRATO";
			//echo $sql3;
			$rs2 = odbc_exec( $conn2, $sql2 );
			if ( !$rs2 ) { 
				exit( "Error en la consulta SQL" );
			}
			while ( odbc_fetch_row($rs2) ) { 
				$monto2 = odbc_result($rs2, 'MONTO');
				$contrato = odbc_result($rs2, 'CONTRATO');

				$sql3 = "SELECT SUM(MONTO) AS MONTO_EST FROM Estimaciones WHERE CONTRATO = '".$contrato."' AND YEAR(FECHA_INICIO) = '".date("Y")."' AND TRAMO = '".$tramo."'";
				//echo $sql3;
				$rs3 = odbc_exec( $conn3, $sql3 );
				if ( !$rs3 ) { 
					exit( "Error en la consulta SQL" );
				}
				while ( odbc_fetch_row($rs3) ) { 
					$monto_est = odbc_result($rs3, 'MONTO_EST');											
				}
				$monto1 += $monto2;
			}
			$pre_con = $presupuesto - $monto1;
			$con_eje = $monto1 - $monto_est;
				echo "<tbody>";
				   if ($tramo1 != $tramo){
				   		if($cont != 0){
				   			echo "<tr class = 'tabla'>
								<td align='center'><strong><h5>SUB TOTAL</h5></strong></td>
								<td align='right'>$".number_format($pres,2,'.',',')."</td>
								<td align='right'>$".number_format($con,2,'.',',')."</td>
								<td align='right'>$".number_format($eje,2,'.',',')."</td>
								<td align='right'>$".number_format($p_c,2,'.',',')."</td>
								<td align='right'>$".number_format($c_e,2,'.',',')."</td>
							</tr>";
							$pres = 0;
							$con = 0;
							$eje = 0;
							$p_c = 0;
							$c_e = 0;
				   		}
				    

				   echo "<tr class = 'tabla'>";	
					   echo "<td align='center' colspan='6' style='color:'><strong><h4>".$tramo."</h4></strong></td>
				   </tr>";
				   }				   
				   $tramo1 = $tramo;

					echo "<tr class = 'tabla'>
					          <td align='center'>".$subcuenta."</td>
							  <td align='right'>$".number_format($presupuesto,2,'.',',')."</td>
							  <td align='right'>$".number_format($monto1,2,'.',',')."</td>
							  <td align='right'>$".number_format($monto_est,2,'.',',')."</td>
							  <td align='right'>$".number_format($pre_con,2,'.',',')."</td>
							  <td align='right'>$".number_format($con_eje,2,'.',',')."</td></tr>";

							  $con += $monto1;
							  $monto1 = 0;
							  $pres += $presupuesto;
							  $eje += $monto_est;
							  $p_c += $pre_con;
							  $c_e += $con_eje;

					$presupuesto2 += $presupuesto;

					$sql2 = "SELECT ACTIVIDAD, SUM(IMPORTE) AS IMPORTE FROM PresupuestoDet WHERE CLASIFICACION='CAPEX' AND SUBCUENTA='".$subcuenta."' AND PERIODO = '".date("Y")."' AND PresupuestoDet.TRAMO ='".$tramo."' GROUP BY ACTIVIDAD";
						//echo $sql2;
					$rs2 = odbc_exec( $conn2, $sql2 );
					if ( !$rs2 ) { 
						exit( "Error en la consulta PresupuestoDet" ); 
					} 
					while ( odbc_fetch_row($rs2) ) { 
						$importe = odbc_result($rs2, 'IMPORTE');							
						$actividad = odbc_result($rs2, 'ACTIVIDAD');


						$sql3 = "SELECT * FROM Contratos WHERE SUBCUENTA = '".$subcuenta."' AND TRAMO ='".$tramo."' and CLASIFICACION='CAPEX' AND ACTIVIDAD = '".$actividad."' AND YEAR(FECHA_INICIO) = '".date("Y")."'";
						//echo $sql3;
						$rs3 = odbc_exec( $conn3, $sql3 );
						if ( !$rs3 ) { 
							exit( "Error en la consulta SQL" );
						}
						while ( odbc_fetch_row($rs3) ) { 
							$monto = odbc_result($rs3, 'MONTO');
							$contrato = odbc_result($rs3, 'CONTRATO');		

							$contrato2 += $monto;

							$sql4 = "SELECT SUM(MONTO) AS MONTO_EST FROM Estimaciones WHERE CONTRATO = '".$contrato."' AND TRAMO = '".$tramo."' AND YEAR(FECHA_INICIO) = '".date("Y")."'";
							//echo $sql3;
							$rs4 = odbc_exec( $conn4, $sql4 );
							if ( !$rs4 ) { 
								exit( "Error en la consulta SQL" );
							}
							while ( odbc_fetch_row($rs4) ) { 
								$monto_est = odbc_result($rs4, 'MONTO_EST');											
							}

							$resta = $importe - $monto;
							$resta2 = $monto - $monto_est;
							$estimacion2 += $monto_est;
							$con_eje2 += $resta2;
							$pre_con2 += $resta;
						}

						echo "<tr class = 'tabla'>
								<td align='left'>".$actividad."</td>
							  	<td align='right'>$".number_format($importe,2,'.',',')."</td>
							    <td align='right'>$".number_format($monto,2,'.',',')."</td>
							  	<td align='right'>$".number_format($monto_est,2,'.',',')."</td>
							  	<td align='right'>$".number_format($resta,2,'.',',')."</td>
							  	<td align='right'>$".number_format($resta2,2,'.',',')."</td>
							  </tr>";

						$importe = 0;
						$monto = 0;		
						$monto_est = 0;	
						$resta = 0;		
						$resta2 = 0;	
					}//While2
					$cont++;
					
			
		}//while						
					echo "<tfoot>
							<tr class = 'tabla'>
								<td align='center'><strong><h5>TOTALES</h5></strong></td>
								<td align='right'>$".number_format($presupuesto2,2,'.',',')."</td>
								<td align='right'>$".number_format($contrato2,2,'.',',')."</td>
								<td align='right'>$".number_format($estimacion2,2,'.',',')."</td>
								<td align='right'>$".number_format($pre_con2,2,'.',',')."</td>
								<td align='right'>$".number_format($con_eje2,2,'.',',')."</td>
							</tr>
						   </tfoot>";
		
		?>
		</table>
		</center>	
	</div>
    
<!-- FILTRO -->
<form action="Comparativo.php" method="post" enctype="multipart/form-data" >
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