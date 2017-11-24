<?php
require_once('Connections/sac2.php'); 
header( 'Content-type: text/html; charset=iso-8859-1' );
//print_r($_POST);


date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d');
$periodo = date('Y').date("m");


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
if (isset($_POST['cerrar_sesion'])) {
	session_destroy();  // destroy the session 
	header("Location: index.php");	
}
$tramo = $_SESSION['S_Tramo'];  
$subtramo = $_SESSION['S_Subtramo'];
//--------------------------------------GUARDAR MAQUINARIA-----------------------------------------------------------
if (isset($_REQUEST['inicio'])) {
	$hora=  date('H:i:s', time());  
	$tramo1 = $_POST["maqu1"];
  	//MAQUINARIA
	 for ($x = 1; $x <= 5; $x++) {	  
	  if ($_POST["maq".$x.""] <> ""){	
	    $maq = $_POST["maq".$x.""]; 
	    $hk_ini = $_POST["km_ini".$x.""]; 		
		
		$sql = "SELECT * FROM CatMaquinaria WHERE Descrip = '".$maq."'";
 		 //echo $sql;
		 $rs = odbc_exec( $conn, $sql );
  			if ( !$rs ) { 
    		exit( "Error en la consulta SQL" ); 
  		}    
 		 while ( odbc_fetch_row($rs) ) { 
  		  $numero = odbc_result($rs, 'NoEco');
		  $unidad = odbc_result($rs, 'CvTipMaq');
 		 }
		 //echo $numero."/".$unidad;
		 
		 $sql = "SELECT * FROM Accesos WHERE USUARIO_ID = '".$_SESSION['S_UsuarioID']."' AND TRAMO = '".$tramo1."'";
 		 //echo $sql;
		 $rs = odbc_exec( $conn, $sql );
  			if ( !$rs ) { 
    		exit( "Error en la consulta SQL" ); 
  		}    
 		 while ( odbc_fetch_row($rs) ) { 
  		  $subtramo = odbc_result($rs, 'SUBTRAMO');
 		 }
		    
			$sql = "INSERT INTO Rud VALUES ('".$numero."','".$maq."','".$hora."','','".$unidad."','0','0','0','ORDINARIO','MAQUINARIA','".$fecha."','INCOMPLETO','".$periodo."','".$tramo1."','".$hk_ini."','0','".$_SESSION['S_Subtramo']."')";
  			//echo $sql; 
			$rs = odbc_exec( $conn, $sql );
			  if ( !$rs ) {
				  //REGISTRO DUPLICADO
			      header("Location: maquinaria.php");  
    			  //exit( "Error en la consulta Rud" ); 
  			} 
	    $maq="";
		$numero="";
     }//FIN IF   
   }//FIN FOR
}

if (isset($_REQUEST['fin'])) {
	$folio = $_POST["folio"];
	$fecha2 = $_POST["fecha"];	
	$hk_fin = $_POST["hk_fin"];	
	$hora=  date('H:i:s', time());
	
	
	//OBTENEMOS EL INICIO
	$sql = "SELECT * FROM Rud WHERE FOLIO = '". $folio ."' and FECHA = '".$fecha2."' ";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
     exit( "Error en la consulta SQL" ); 
    }    
    while ( odbc_fetch_row($rs) ) { 
     $inicio = odbc_result($rs, 'INICIO');	   	  
    }
	
	$dif=date("H:i:s", strtotime("00:00:00") + strtotime($hora) - strtotime($inicio));
	$horaDif = substr($dif,0,2);
	$minutoDif = substr($dif,3,2);
	$total = $horaDif + ($minutoDif/60);
	
	if($total>=1){	
	    $sql = "UPDATE Rud SET FIN = '".$hora."', ESTATUS = 'COMPLETO', CANTIDAD = '".$total."', HK_FIN = '".$hk_fin."' WHERE FOLIO = '".$folio."' AND FECHA = '".$fecha2."' AND TIPO = 'ORDINARIO' AND ESTATUS = 'INCOMPLETO'";
    	//echo $sql;
    	$rs = odbc_exec( $conn, $sql );
    	if ( !$rs ) {
	  		//REGISTRO DUPLICADO
			header("Location: maquinaria.php");    
    	} 
  	}else{
		echo "<script languaje='javascript'>alert('Registro no actualizado. El tiempo de uso es de ".$total." hrs.');</script>";
	}
		
}
//---------------------------------------------------------FILTRO-----------------------------------
if (isset($_REQUEST['filtrar'])) {
  $tramo = $_POST['tramo'];
  $_SESSION['S_Tramo'] = $tramo; 
  $subtramo = $_POST['subtramo'];
  $_SESSION['S_Subtramo'] = $subtramo; 
  
  //echo $_SESSION['S_Subtramo'];
}	

$sql = "select * from CatTramos where TRAMO in ('".$tramo."')";
  //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }
  while ( odbc_fetch_row($rs) ) { 
     $base = odbc_result($rs, 'BASE');	   	  
  }

//--------------------------------------AUTOCOMPLETAR---------------------------------------------------------
$maquina = array();
$x=0;

$sql = "select * from CatMaquinaria where CvBase in ('".$base."')";
  //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }    
  while ( odbc_fetch_row($rs) ) { 
    $maquina[$x] = "\"" .odbc_result($rs, 'Descrip'). "\",";	
	$x++;    
}//While
$maquina[$x-1] = str_replace(",","",$maquina[$x-1]);

//---------------------------------------------GRID-----------------------------------------------------------
$x=0;
$filas = array();
$sql = "SELECT * FROM Rud WHERE DESCRIPCION='MAQUINARIA' AND ESTATUS<>'TERMINADO' AND TRAMO in ('".$tramo."') AND FECHA='".$fecha."' AND SUBTRAMO in ('".$_SESSION['S_Subtramo']."') ORDER BY ESTATUS DESC";
 //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }   //OBTENEMOS TODA LA FILA 
 while ($row = odbc_fetch_array($rs)) {    
  $filas[$x] = array_map('utf8_encode',$row);    
   $x++;    
 }//While
$datos =  $filas; 
//echo json_encode($datos); 
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
body {
	background: url(images/maquinaria_old.jpg);
 background-size: cover;
        -moz-background-size: cover;
        -webkit-background-size: cover;
        -o-background-size: cover;
.NEG_WHITE {
	font-weight: bold;
	color: #FFF;
}
</style>
	<title>Control de Asistencia SAC</title>    
    <link rel='stylesheet' href='http://s.codepen.io/assets/reset/reset.css'>
    <link href="css/bootstrap.min.css" rel="stylesheet" />  
    <link rel="stylesheet" href="css/styl.css">
    <link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />
    <script type="text/javascript" src="scripts/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdata.js"></script> 
    <script type="text/javascript" src="jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxscrollbar.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxlistbox.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdropdownlist.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxmenu.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxgrid.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxgrid.sort.js"></script> 
    <script type="text/javascript" src="jqwidgets/jqxgrid.pager.js"></script> 
    <script type="text/javascript" src="jqwidgets/jqxgrid.columnsresize.js"></script> 
    <script type="text/javascript" src="jqwidgets/jqxgrid.selection.js"></script> 
    <script type="text/javascript" src="jqwidgets/jqxinput.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxpanel.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxmaskedinput.js"></script> 
    <script type="text/javascript" src="jqwidgets/jqxnumberinput.js"></script>      
    <script type="text/javascript" src="scripts/demos.js"></script>    
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxtabs.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxnotification.js"></script>
    
 <script type="text/javascript">
 $(document).ready(function () {
	 
	 		//CREAMOS EL TAB			
            $('#tabsWidget').jqxTabs({ width: 700, height: 300, position: 'top'});           
            $('#tabsWidget').jqxTabs('focus');	 
			
			//-----------------------TABLA----------------------------------------------
			 var data =  <?php echo json_encode($datos); ?>;				    
		    var source =
            {
                datafields: [
                    { name: 'AVANCE_ID', type: 'string' },	
					{ name: 'FOLIO', type: 'string' },
					{ name: 'NOMBRE', type: 'string' },				                 
                    { name: 'INICIO', type: 'string' },
					{ name: 'FIN', type: 'string' },		
					{ name: 'ESTATUS', type: 'string' },
					{ name: 'HK_INI', type: 'string' },	
					{ name: 'HK_FIN', type: 'string' },	
					{ name: 'FECHA', type: 'string' }									
                ],
                localdata: data 
            };
            var dataAdapter = new $.jqx.dataAdapter(source);

            $("#tab1").jqxGrid(
            {
                width: 650,
                height: 250,
                source: dataAdapter,
                
                keyboardnavigation: false,
                columns: [
				  { text: 'Folio', dataField: 'FOLIO', width: 100 }, 
                  { text: 'Nombre', dataField: 'NOMBRE', width: 250 },				  
                  { text: 'Inicio', dataField: 'INICIO', width: 100 },
				  { text: 'Fin', dataField: 'FIN', width: 100 },		  
				  { text: 'Estatus', dataField: 'ESTATUS', width: 100 }
                ]
            });		
			$('#tab1').on('rowclick', function (event){													       
			    $("#entradamaq").modal('show'); 			        			
            });
			
			$("#tab1").on('rowselect', function (event) {			
				var maquinaria = event.args.row.NOMBRE;			
				var folio = event.args.row.FOLIO;			
				var fecha = event.args.row.FECHA;		
				var hkini = event.args.row.HK_INI;		
				var hkfin = event.args.row.HK_FIN;		
				
				$("#maquinaria").jqxInput({value: maquinaria, height: 15, width: 250, minLength: 1});
				$("#maquinaria").css('background-color' , '#DEDEDE');
				$("#maquinaria").attr('readonly' , true);
				$("#folio").jqxInput({value: folio, height: 15, width: 80, minLength: 1});
				$("#folio").css('background-color' , '#DEDEDE');
				$("#folio").attr('readonly' , true);
				$("#fecha").jqxInput({value: fecha, height: 15, width: 110, minLength: 1});
				$("#fecha").css('background-color' , '#DEDEDE');
				$("#fecha").attr('readonly' , true);
				$("#hk_ini").jqxInput({value: hkini, height: 15, width: 50, minLength: 1});	
				$("#hk_ini").css('background-color' , '#DEDEDE');
				$("#hk_ini").attr('readonly' , true);
				$("#hk_fin").jqxInput({value: hkfin, height: 15, width: 50, minLength: 1});	
			});		
			
			//AUTOCOMPLETAR
			var maquina = new Array(<?php  
	        foreach ($maquina as &$valor) {
              echo $valor;
            }		
	        ?>);
			
			//MAQUINARIA E INSUMOS
			for (var i = 1; i <= 5; i++) {
				$("#maq" + i).jqxInput({placeHolder: "Elige la maquinaria", height: 15, width: 250, minLength: 1, source: maquina});
				$("#km_ini" + i).jqxInput({value: '0', height: 15, width: 50, minLength: 1});	
			}
			
			$("#tramo").click(function(){				    			
				$("#tramo option:selected").each(function () {
				 elegido=$(this).val();
			     $("#tramo1").text(elegido);
			     $("#maqu1").val(elegido);
              });				
			});
			
			$("#tramo").click(function () {
   		      $("#tramo option:selected").each(function () {				 
			     elegido=$(this).val();							 					 
			     $.post("consultaAvanceDiario.php", { tramoAd: elegido }, function(data){									 
			     $("#subtramo").html(data);			    
			   });			
              });
            }); 
			
 });//Fin document.ready
 $(window).load(function() {
	 $("#tramo").click();
	 

 });//$(window).load
 
 $('#ModalEntrada').on('shown.bs.modal', function () {
  $('#myInput').focus()
})
 $('#ModalSalida').on('shown.bs.modal', function () {
  $('#myInput').focus()
})
 $('#ModalExtra').on('shown.bs.modal', function () {
  $('#myInput').focus()
})
function MostrarFilas(Fila) {
	var quitar = Fila -1;	
	document.getElementById("sac"+quitar).style.display = "none";
	
	var elementos = document.getElementsByName(Fila);    
    for (i = 0; i< elementos.length; i++) {
        if(navigator.appName.indexOf("Microsoft") > -1){
               var visible = 'block'
        } else {
               var visible = 'table-row';
        }
	elementos[i].style.display = visible;
        }
}

function OcultarFilas(Fila) {	
    var elementos = document.getElementsByName(Fila);
    for (k = 0; k< elementos.length; k++) {
               elementos[k].style.display = "none";
    }
}
</script>
</head>
<body class="default">
<?php if ($_SESSION['S_Privilegios'] == 'SOBRESTANTE'){ ?>
<div id='cssmenu'>
<ul>
   <li><a href='Inicio.php'><span>Inicio</span></a></li>
   <li><a href='#'><span>Cat&aacute;logos</span></a>
      <ul id="catalogos">
         <li><a href='catconceptos.php'><span>Conceptos</span></a>
         <li><a href='catempleados.php'><span>Empleados</span></a>
         <li><a href='catmaquinaria.php'><span>Maquinaria</span></a>
         <li><a href='catins.php'><span>Insumos</span></a>                   
         </li>
      </ul>
   </li>
   <li><a href='#'><span>Avance de Obra</span></a>   
     <ul id="avance">
        <?php 
		 if($_SESSION['S_Privilegios'] == 'SOBRESTANTE' ||  $_SESSION['S_Privilegios'] == 'JEFE DE TRAMO'){
			?><li><a href='AvanceDiario.php'><span>Avance Diario</span></a> 			
			<?php }else{?> 
        <li><a href='AvanceDiario2.php'><span>Avance Diario</span></a>
        <?php } ?>               
     </ul>
   </li>
     <table width="500" border="0" align="right" style="'Oxygen Mono', Tahoma, Arial, sans-serif; font-size:15px; color: #58D3F7">
     <tr>
       <td width="150" height="30" align="right">Bienvenid@ :</td>
       <td width="200" align="center"><?php echo $_SESSION['S_Usuario']; ?></td>
       <td width="100" valign="bottom" class="btn btn-info">  
       <form id="salir" action="avanceDiario.php" method="post" enctype="multipart/form-data" >      
       <button  class="btn btn-info" border ="0" id="cerrar_sesion" name="cerrar_sesion">Cerrar Sesion</button> 
       </form>   
       </td>
     </tr>
   </table>
</ul>
</div>
<?php }else{ ?>
<div id='cssmenu'>
<ul>
   <li><a href='Inicio.php'><span>Inicio</span></a></li>
   <li><a href='#'><span>Cat&aacute;logos</span></a>
      <ul id="catalogos">
         <li><a href='catconceptos.php'><span>Conceptos</span></a>
         <li><a href='catempleados.php'><span>Empleados</span></a>
         <li><a href='catmaquinaria.php'><span>Maquinaria</span></a>
         <li><a href='catins.php'><span>Insumos</span></a>         
         </li>
      </ul>
   </li>
   <li><a href='#'><span>Almacen</span></a>   
     <ul>
         <li><a href='#'><span>Contable</span></a>
         <li><a href='#'><span>Fisico</span></a>                     
     </ul>
   </li>
   <li><a href='#'><span>Avance de Obra</span></a>   
     <ul>
        <li><a href='progsemanal.php'><span>Prog. Semanal</span></a>
        <?php 
		 if($_SESSION['S_Privilegios'] == 'SOBRESTANTE' || $_SESSION['S_Privilegios'] == 'SUPERVISOR'){
			?><li><a href='AvanceDiario.php'><span>Avance Diario</span></a> 			
			<?php }else{?> 
        <li><a href='AvanceDiario2.php'><span>Avance Diario</span></a>
        <?php } ?>                   
     </ul>
   </li>
   <li><a href='#'><span>Contratos</span></a>   
     <ul>
         <li><a href='#'><span>OPEX</span></a>
         <li><a href='#'><span>CAPEX</span></a>                     
     </ul>
   </li>     
   <li><a href='#'><span>Presupuestos</span></a>   
     <ul>
         <li><a href='#'><span>OPEX</span></a>
         <li><a href='#'><span>CAPEX</span></a>                     
     </ul>
   </li>  
   <li class='last' id="asistencia"><a href='Asistencia.php' target="_blank"><span>Asistencia</span></a>
     <ul>
         <li><a href='EntradaManual.php'><span>Entrada Manual</span></a>                   
     </ul>
 </li>
    <li class='active has-sub'><a href='Maquinaria.php'><span>Maquinaria</span></a></li>
    <li class='last'><a href='Insumos.php'><span>Insumos</span></a></li>
 
  
     <table width="500" border="0" align="right" style="'Oxygen Mono', Tahoma, Arial, sans-serif; font-size:15px; color: #58D3F7">
     <tr>
       <td width="150" height="30" align="right">Bienvenid@ :</td>
       <td width="200" align="center"><?php echo $_SESSION['S_Usuario']; ?></td>
       <td width="100" valign="bottom" class="btn btn-info">
       <form id="salir" action="avanceDiario.php" method="post" enctype="multipart/form-data" >             
          <button type="submit" class="btn btn-info" border ="0" id="cerrar_sesion" name="cerrar_sesion" > Cerrar Sesion</button> 
       </form> 
       </td>
     </tr>
   </table>
</ul>
</div>
<?php } ?>
<p>
<p>
<CENTER>
 <iframe src="http://www.zeitverschiebung.net/clock-widget-iframe?language=es&timezone=America%2FMexico_City" width="100%" height="130" frameborder="0" seamless></iframe>
 </div>
</CENTER>
<center>
<table width="111" height="103" border="1">
  <tbody>
    <tr>
      <td width="120" height="96">
        <!-- Boton Modal Entrada --><center>
  		<button type="button" class="btn btn-success" data-toggle="modal" data-target=".ModalEntrada"> <img src="images/salida_maquinaria.png" width="48" height="50" /> <br />
  		* Salida *</button></td>
  		<td width="-2">
     </tr>
  </tbody>
</table>
<center>

<form>
 <label> INGRESA: </label> <input type="texto" value="">
</form>



<br />
<br />
<table width="700" height="54" border="0" align="center">
    <tr>
  <td width="47%" height="50" valign="baseline" bgcolor="#FFFFFF">
      <img src="images/entrada_maquinaria.png" width="48" height="50" />
  <td width="53%" align="left" valign="baseline" scope="col" bgcolor="#FFFFFF">
   <form id="filtro" action="Maquinaria.php" method="post" enctype="multipart/form-data" >   
    <table width="367" height="76" align="center">  
      <tr>
      <td width="82" height="22"><strong>Tramo:</strong></td>
      <td width="249"> <?php echo $_SESSION['S_Tramo']; ?></td>
      </tr>
      <tr>
      <td width="82"><strong>SubTramo:</strong></td>
      <td width="249"> 	<?php echo $_SESSION['S_Subtramo']; ?></td>
      </tr>
      <tr>
      <td height="22" colspan="2" align="right">
      <button type="button" class="btn btn-success" data-toggle="modal" data-target="#filtro_maq">Filtro</button></td>
      </tr>
	</table>
    </form>
  </td>  
 </tr> 
 </table>
</center>
<center>
<div id='jqxWidget'>
        <div id="tabsWidget">
            <ul style="margin-left: 30px;">
                <li>Maquinaria</li>                            
            </ul>
            <div>
              <center>
                 <div id="tab1"></div>  
              </center>               
         </div>            
</div>
</center>   


<!-- INICIO DE MODALES -->
<!-- Modal de INICIO  -->
<form id="formulario" action="Maquinaria.php" method="post" enctype="multipart/form-data" >
<div class="modal fade ModalEntrada" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
     <h4 class="modal-title" id="myModalLabel">Registro de Salida</h4>
      </div>
      <div class="modal-body">
        <!--CUERPO-->        
<table width="551" height="auto" border="0">
<center><strong><span name="tramo1" id="tramo1"></span></strong></center>
<br />
<tr>
<td><input type="hidden" id="maqu1" name="maqu1" /></td>
</tr>
<?php 
   for ($i = 1; $i <= 5; $i++) {
	   $x = $i + 200;
	   $z = $x + 1;
	   if ($i==1){
		   echo "<tr name='".$x."' id='".$x."'>
		           	<td width='29'>".$i."</td>
                    <td width='233'><input type='text' id='maq".$i."' name='maq".$i."' accept-charset='utf-8'  autocomplete='off' /></td>
					<td width='70'>H/K Ini:</td>
					<td width='144'><input type='text' id='km_ini".$i."' name='km_ini".$i."' accept-charset='utf-8' /></td>
                    <td width='30'><a href=\"javascript:MostrarFilas('".$z."')\"><img id='sac".$x."' src='images/plus2.png' width='30' height='30'></a></td>					
                </tr>";
	   }else{
		   echo "<tr name='".$x."' id='".$x."' style='display:none;'> 
		            <td width='29'>".$i."</td>
                    <td width='233'><input type='text' id='maq".$i."' name='maq".$i."' accept-charset='utf-8'  autocomplete='off' /></td>
					<td width='70'>H/K Ini:</td>
					<td width='144'><input type='text' id='km_ini".$i."' name='km_ini".$i."' accept-charset='utf-8' /></td>
                    <td width='30'><a href=\"javascript:MostrarFilas('".$z."')\"><img id='sac".$x."' src='images/plus2.png' width='30' height='30'></a></td>					
                </tr>";		  
	   }	   
   }	
?>
</table>         
        <!--FIN-->
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">CANCELAR</button>
        <button type="submit" class="btn btn-primary" name="inicio" id="inicio">GRABAR</button>
      </div>
  </div>
    </div>
  </div>
</form>    


<!-- Modal de ENTRADAMAQ  -->
<form  action="Maquinaria.php" method="post" enctype="multipart/form-data" >
<div class="modal fade entradamaq" id="entradamaq" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Registro de Entrada</h4>
      </div>
      <div class="modal-body">
        <!--CUERPO-->
<table width="492" height="auto" border="0">
	<tr>
      <td width="63" height="35">&nbsp;</td>
      <td width="144"></td>	
   	  <td width='71'>&nbsp;</td>
        <td width='196' align="right">Fecha:           <input type='date' id='fecha' name='fecha' value="<?php echo date("Y-m-d"); ?>"/></td>			
  </tr>
	<tr>
        <td height="33">Folio:</td>
        <td><input type='text' id='folio' name='folio' accept-charset='utf-8' /></td>	
    	<td width='71'>Nombre:</td>
        <td width='196'><input type='text' id='maquinaria' name='maquinaria' accept-charset='utf-8'  autocomplete='off'/></td>			
  </tr>
  <tr>
        <td height="33">H/K Ini:</td>
        <td><input type='text' id='hk_ini' name='hk_ini' accept-charset='utf-8' /></td>	
    	<td width='71'>H/K Fin:</td>
        <td width='196'><input type='text' id='hk_fin' name='hk_fin' accept-charset='utf-8' /></td>			
  </tr>
</table>  
        <!--FIN-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">CANCELAR</button>
        <button type="submit" class="btn btn-primary" name="fin" id="fin">GRABAR</button>
      </div>
    </div>
  </div>
</div>
</form>
<!--FIN MODAL-->

<!-- Modal de Filtro  -->
<form  action="Maquinaria.php" method="post" enctype="multipart/form-data" >
<div class="modal fade filtro_maq" id="filtro_maq" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Filtro</h4>
      </div>
      <div class="modal-body">
        <!--CUERPO-->
<table width="376" height="40" align="center">  
      <tr>
      <td width="58" height="34">Tramo:</td>
      <td width="281">
      <select class="form-control" name="tramo" id="tramo">
      <?php	
	  $i=1;
	  $selected="";
      $sql = "SELECT DISTINCT TRAMO FROM Accesos WHERE USUARIO_ID = '".$_SESSION['S_UsuarioID']."'";
      echo $sql;
      $rs = odbc_exec( $conn, $sql );
      if ( !$rs ) { 
       exit( "Error en la consulta SQL" ); 
      }     
      while ( odbc_fetch_row($rs) ) { 
       $tramo = odbc_result($rs, 'TRAMO'); 
	   if ($_SESSION['S_Tramo']==$tramo)
		    $selected="selected";
	   else
			$selected=""; 
       echo "<option id='".$i."'".$selected.">".$tramo."</option>";
	    $i++;
      }//While 
     ?>
      </select>
      </td>
      </tr>
      <tr>
      <td width="62">SubTramo:</td>
      <td width="308">
      <select class="form-control" name="subtramo" id="subtramo">
      </select>
      </td>
      </tr>
	</table> 
        <!--FIN-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">CANCELAR</button>
        <button type="submit" class="btn btn-success" name="filtrar" id="flitrar">FILTRAR</button>
      </div>
    </div>
  </div>
</div>
</form>
<!--FIN MODAL-->


</body>
</html>
