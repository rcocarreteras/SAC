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
$mes = date("m") - 1;

if($mes <= 9){
	$mes = "0".$mes;
}else{
	$mes = $mes;
}
/*******************************************GUARDAR COSTO MO******************************************/
if (isset($_REQUEST['GuardarCosto'])) { 
	$fechaIni = $_POST["fechaIni"]; 
	$fechaFin = $_POST["fechaFin"]; 
	$contador = $_POST["contador"]; 
	
	for ($x = 1; $x <= $contador; $x++) {	  
	  if ($_POST["costo".$x.""] <> ""){	
	    $nombre = $_POST["nombre".$x.""]; 
	    $noemp = $_POST["noemp".$x.""]; 
	    $base = $_POST["base".$x.""]; 
	    $costo = $_POST["costo".$x.""];
	    $extra = $_POST["extra".$x.""];
	    $bandera = $_POST["bandera".$x.""];
		
		if($bandera == ""){
			$sql = "INSERT INTO CostoProrrateo VALUES ('".$noemp."','".$nombre."','".$base."','".$costo."','".$extra."','".$fechaIni."','".$fechaFin."','".date("m")."')";
		}else{
			$sql = "UPDATE CostoProrrateo SET COSTO='".$costo."', COSTO_EXTRA='".$extra."' WHERE NO_EMP='".$noemp."' AND FECHA_INICIO='".$fechaIni."'";
		}	    
        //echo $sql;
        $rs = odbc_exec( $conn, $sql );
        if ( !$rs ) { 
          exit( "Error en la consulta CostoProrrateo" ); 
        }
     } 
  }
}
/***************************************************FILTRO*************************************************/	
	if (isset($_REQUEST['filtro'])) {
		$baseFiltro = $_POST["baseFiltro"];
	
		if ($baseFiltro == "TODOS"){
			$sql = "SELECT DISTINCT(CvBase) FROM CatEmpleados";			
		}else{
			$sql = "SELECT DISTINCT(CvBase) FROM CatEmpleados WHERE CvBase = '".$baseFiltro."'";		
		}
		
	}else{
		
		$sql = "SELECT DISTINCT(CvBase) FROM CatEmpleados";
		# WHERE CvBase = 'TO01'
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
		var total = 0;
		
		/************************************CALCULO DE IMPORTE Y ANTICIPO********************************
			function Importe(){
				var valor = $("#contador").val();
				//alert();
				for (var i = 1; i <= valor; i++) {
					var subtotal = parseFloat($("#costo"+i).val());
					total += subtotal;
					$("#totalO").val(total);
											
				}//for
				//alert(subtotal);
			}//Fin function  */
		
				
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
<form action="Prorrateo.php" method="post" enctype="multipart/form-data" >
		<table width="auto" height="auto" border="1" id="tabla">
		  <thead>
		    <tr align="center" class="titulo">
		      <td width="300px">NOMBRE</td>	     
		      <td width="200px">COSTO ORDINARIO</td>
		      <td width="200px">COSTO EXTRAORDINARIO</td>
		    </tr>
		    <tr align="center" class="titulo"><!--2016-09-26 2016-10-25-->
		      <td width="300px">RANGO DE FECHAS</td>
		      <td><input type="date" id="fechaIni" name="fechaIni" class="form-control" value="<?php echo date("Y")."-".$mes; ?>-26" readonly></td>	     
		      <td><input type="date" id="fechaFin" name="fechaFin" class="form-control" value="<?php echo date("Y-m"); ?>-25" readonly></td>
		    </tr>
		  </thead>
		  <tbody>  
		<?php 
		$base1 = "";
		$cont = 0;
		//echo $sql;
		$rs = odbc_exec( $conn, $sql );
		if ( !$rs ) { 
			exit( "Error en la consulta SQL" ); 
		}
		while ( odbc_fetch_row($rs) ) {			 
			$base = odbc_result($rs, 'CvBase');

				echo "<tbody>";
				   if ($base1 != $base){
				   echo "<tr class = 'tabla'>
				   			<td align='center' colspan='3'><strong><h4>".$base."</h4></strong></td>
				   		 </tr>";
			$sql2 = "SELECT DISTINCT(Empleado), NoEmp FROM CatEmpleados WHERE CvBase = '".$base."' ORDER BY Empleado";
			//echo $sql2;
			$rs2 = odbc_exec( $conn2, $sql2 );
			if ( !$rs2 ) { 
				exit( "Error en la consulta SQL" );
			}
			while ( odbc_fetch_row($rs2) ) { 
				$nombre = odbc_result($rs2, 'Empleado');
				$noemp = odbc_result($rs2, 'NoEmp');
				$costo = 0;
				$costo_extra = 0;
				$bandera = "";
				$cont++;//2016-09-26
				$sql3 = "SELECT * FROM CostoProrrateo WHERE NO_EMP='".$noemp."' AND FECHA_INICIO='".date("Y")."-".$mes."-26'";
				//echo $sql3;
				$rs3 = odbc_exec( $conn3, $sql3);
				if ( !$rs3 ) { 
					exit( "Error en la consulta SQL" );
				}
				while ( odbc_fetch_row($rs3) ) { 
					$costo = odbc_result($rs3, 'COSTO');
					$costo_extra = odbc_result($rs3, 'COSTO_EXTRA');
					$bandera = "Si";
				}
				
				echo "<tr class = 'tabla'>
						<td><input type='hidden' id='nombre".$cont."' name='nombre".$cont."' value='".$nombre."'>".$nombre."</td>
						<td align='right'><input type='number' id='costo".$cont."' name='costo".$cont."' class='form-control' onblur='Importe()' value='".$costo."'><input type='hidden' id='noemp".$cont."' name='noemp".$cont."' value='".$noemp."'><input type='hidden' id='bandera".$cont."' name='bandera".$cont."' value='".$bandera."'><input type='hidden' id='base".$cont."' name='base".$cont."' value='".$base."'></td>
						<td align='right'><input type='number' id='extra".$cont."' name='extra".$cont."' class='form-control' onblur='Importe()' value='".$costo_extra."'></td>
					  </tr>
					</tbody>";
				   $base1 = $base;
				   }
				}//while	  
		}//while
		//echo "<tr class='tabla'><td align='center'>TOTAL</td><td><input type='text' name='totalO' id='totalO' class='form-control' readonly></td><td><input type='text' name='totalE' id='totalE' class='form-control' readonly></td></tr>";
		echo "<tr><td style='display:none'><input type='text' name='contador' id='contador' value='".$cont."'></td></tr>";					
		
		?>
        <tfoot>
        	<tr>
            	<td colspan="3" align="center"><button type="submit" class="btn btn-primary btn-lg" name="GuardarCosto" id="GuardarCosto">Guardar</button></td>
            </tr>
        </tfoot>
	</table>
</form>
		</center>	
	</div>
    
<!-- FILTRO -->
<form action="Prorrateo.php" method="post" enctype="multipart/form-data" >
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
        	<div class="col-lg-3"></div>
        	<div class="col-lg-2">Base:</div>
        	<div class="col-lg-3">
            	<select class="form-control" id="baseFiltro" name="baseFiltro">
					<?php
					  $i=1;
					  $sql = "SELECT DISTINCT CvBase FROM CatEmpleados";
					  echo $sql;
					  $rs = odbc_exec( $conn, $sql );
					  if ( !$rs ) { 
					   exit( "Error en la consulta SQL" ); 
					  }     
					  while ( odbc_fetch_row($rs) ) { 
						$base = odbc_result($rs, 'CvBase');
					   echo "<option id='".$i."'>".$base."</option>";
					   $i++;
					  }//While 	 
                	?>
                    <option value="TODOS">TODOS</option>
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