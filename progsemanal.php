<?php
require_once('Connections/sac2.php'); 
header( 'Content-type: text/html; charset=iso-8859-1' );
date_default_timezone_set('America/Mexico_City');

//print_r($_POST);
$numsem = 0;
$clavecon = 0;
$cant_prog = 0;
$cant_real = 0;
$unidad ="";
$bloqueo = "";
    	
if (!isset($_SESSION)) {
  session_start();
}
$login_ok = $_SESSION['login_ok'];
//RESTRINGIMOS EL ACCESO A USUARIOS NO IDENTIFICADOS
if ($login_ok == "identificado"){
 //echo $_SESSION['S_Tramo'];
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

/*
if (isset($_COOKIE['concepto'])) { 
$actividad =  $_COOKIE['concepto'];
}*/


//OBTENEMOS LAS FECHAS DE LA SEMANA ACTUAL, PASADA Y SIGUIENTE
$fecha = date('Y-m-j');
$fechanue = strtotime ( '+7 day' , strtotime ( $fecha ) ) ;
$fechanue = date ( 'Y-m-j' , $fechanue );

$fechavie = strtotime ( '-7 day' , strtotime ( $fecha ) ) ;
$fechavie = date ( 'Y-m-j' , $fechavie );

//SEMANA ACTUAL
$year=substr($fecha, 0,4);
$month=substr($fecha, 5,2);
$day=substr($fecha, 8,2);

# Obtenemos el numero de la semana
$semana=date("W",mktime(0,0,0,$month,$day,$year));
# Obtenemos el día de la semana de la fecha dada
$diaSemana=date("w",mktime(0,0,0,$month,$day,$year));
# el 0 equivale al domingo...
if($diaSemana==0)
    $diaSemana=7;
# A la fecha recibida, le restamos el dia de la semana y obtendremos el lunes
$primerDia=date("d-m-Y",mktime(0,0,0,$month,$day-$diaSemana+1,$year));
# A la fecha recibida, le sumamos el dia de la semana menos siete y obtendremos el domingo
$ultimoDia=date("d-m-Y",mktime(0,0,0,$month,$day+(7-$diaSemana),$year));
$semanactual = "Del " . $primerDia . " Al " . $ultimoDia;  
//echo $semanactual; 

//SEMANA PASADA
$year=substr($fechavie, 0,4);
$month=substr($fechavie, 5,2);
$day=substr($fechavie, 8,2);
 
$semanavie=date("W",mktime(0,0,0,$month,$day,$year));
$diaSemana=date("w",mktime(0,0,0,$month,$day,$year));
if($diaSemana==0)
    $diaSemana=7;
$primerDia=date("d-m-Y",mktime(0,0,0,$month,$day-$diaSemana+1,$year));
$ultimoDia=date("d-m-Y",mktime(0,0,0,$month,$day+(7-$diaSemana),$year));
$semanapasada = "Del " . $primerDia . " Al " . $ultimoDia;
//echo $semanapasada; 

//SEMANA SIGUIENTE
$year=substr($fechanue, 0,4);
$month=substr($fechanue, 5,2);
$day=substr($fechanue, 8,2);
 
$semananue=date("W",mktime(0,0,0,$month,$day,$year));
$diaSemana=date("w",mktime(0,0,0,$month,$day,$year));
if($diaSemana==0)
    $diaSemana=7;
$primerDia=date("d-m-Y",mktime(0,0,0,$month,$day-$diaSemana+1,$year));
$ultimoDia=date("d-m-Y",mktime(0,0,0,$month,$day+(7-$diaSemana),$year));
$semanasiguiente = "Del " . $primerDia . " Al " . $ultimoDia;
//echo $semanasiguiente; 
//---------------------------------------------------------FILTRO-----------------------------------
$buscarsemana="";
 	
$sql = "SELECT DISTINCT SEMANA, SEMANA_ID   FROM ProgSemanal order by SEMANA_ID  asc";
//echo $sql;
$rs = odbc_exec( $conn, $sql );
if ( !$rs ) { 
  exit( "Error en la consulta SQL" ); 
}     
while ( odbc_fetch_row($rs) ) { 
  $buscarsemana = odbc_result($rs, 'SEMANA'); 	  
}//While 
  


if (isset($_REQUEST['filtrar'])) {
  $buscartramo = $_POST['buscartramo'];
  $_SESSION['S_Tramo'] = $buscartramo;
  $buscarsemana = $_POST['buscarsemana'];    
}//-------------------------------------------------------------------------------------------------

if (isset($_REQUEST['guardar'])) { 
  $actividad = $_POST['concepto'];  
  $tramo = $_POST['tramo'];
  $subtramo = $_POST['subtramo'];
  $semana = $_POST['semana'];	
  $numsem = $_POST['numsem'];    
  $km_ini = $_POST['km_ini'];
  $enca_ini = $_POST['enca_ini'];
  $km_fin = $_POST['km_fin'];
  $enca_fin = $_POST['enca_fin'];
  $cuerpo = $_POST['cuerpo'];
  $zona = $_POST['zona'];	
  $cant_prog = $_POST['cant_prog'];  
  $observacion = $_POST['observacion']; 
  $km1 = $km_ini."+".$enca_ini;  
  $km2 = $km_fin."+".$enca_fin;
  if ($_POST['cant_prog'] == ""){
	$cant_prog = 0;  
  }
   
  
 $sql = "SELECT * FROM CatConcepto WHERE DesCpt = '". $actividad ."'";
  //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }    
  while ( odbc_fetch_row($rs) ) { 
    $clavecon = odbc_result($rs, 'CvCpt');
	$unidad = odbc_result($rs, 'Unid');
  }

  $sql = "SELECT TOP 1 * FROM Accesos WHERE Subtramo = '". $subtramo ."'";
  //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }    
  while ( odbc_fetch_row($rs) ) { 
    $clavesob = odbc_result($rs, 'INICIALES');
	$sobres = odbc_result($rs, 'SOBRESTANTE');
  }       
  
   $sql = "INSERT INTO ProgSemanal VALUES ('".$numsem."','".$semana."','".$clavecon."','".$actividad."','".$unidad."','".$km1."','".$km2."','".$cuerpo."','".$zona."','".$cant_prog."','".$clavesob."','".$sobres."','".$tramo."','".$subtramo."','".$observacion."','ABIERTO','".$_SESSION['S_Usuario']."','','".$fecha."')";
  //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }  
}
//ACTUALIZAMOS EL ESTATUS Y APROBAMOS LAS ACTIVIDADES PROGRAMADAS
if (isset($_REQUEST['aprobar'])) { 
  $folio = $_POST['folio1']; 
  $estatus = $_POST['estatus1'];
  $tramo = $_POST['tramo1'];
  $subtramo = $_POST['subtramo1'];
  $km_ini = $_POST['km_ini1'];
  $enca_ini = $_POST['enca_ini1'];
  $km_fin = $_POST['km_fin1'];
  $enca_fin = $_POST['enca_fin1'];
  $cuerpo = $_POST['cuerpo1'];
  $zona = $_POST['zona1'];  
  $cantidad = $_POST['cantidad1'];
    
  $km1 = $km_ini."+".$enca_ini;  
  $km2 = $km_fin."+".$enca_fin;  
  
  if($cantidad==""){
	  $cantidad="0";
  }
  
  
  
   $sql = "UPDATE ProgSemanal SET  ESTATUS = '".$estatus."', CANTIDAD = '".$cantidad."', CUERPO = '".$cuerpo."', ZONA = '".$zona."', KM_INI = '".$km1."', KM_FIN = '".$km2."' WHERE PROG_ID = '".$folio."'";
  //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }  
  
  if ($estatus=="APROBADO"){
	  $sql = "SELECT * FROM ProgSemanal WHERE PROG_ID = '".$folio."'";
      //echo $sql;
      $rs = odbc_exec( $conn, $sql );
      if ( !$rs ) { 
        exit( "Error en la consulta SQL" ); 
      }    
      while ( odbc_fetch_row($rs) ) { 
        $actividad = odbc_result($rs, 'ACTIVIDAD');
        $clavecon = odbc_result($rs, 'ACTIVIDAD_ID');
		$numsem = odbc_result($rs, 'SEMANA_ID');
		$semana = odbc_result($rs, 'SEMANA');
    	$unidad = odbc_result($rs, 'UNIDAD');
	    $clavesob = odbc_result($rs, 'SOB_ID');
	    $sobres = odbc_result($rs, 'SOBRESTANTE');	    
      }  
	  
	  $sql = "SELECT * FROM Accesos WHERE Subtramo = '". $subtramo ."' and Tramo = '".$tramo."'";
  //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }    
  while ( odbc_fetch_row($rs) ) { 
	$plaza = odbc_result($rs, 'PLAZA');
  }  
	   
	  $sql = "INSERT INTO AvanceDiario VALUES ('".$folio."','".$numsem."','".$semana."','".$clavecon."','".$actividad."','".$unidad."','".$km1."','".$km2."','".$cuerpo."','".$zona."','0','0','0','".$cantidad."','".$clavesob."','".$sobres."','".$tramo."','','ABIERTO','','".$_SESSION['S_Usuario']."' ,'','".$fecha."','".$plaza."','".$subtramo."')";
      //echo $sql;
      $rs = odbc_exec( $conn, $sql );
      if ( !$rs ) { 
        exit( "Error en la consulta SQL" ); 
      }  
  }
}
//--------------------------------------AUTOCOMPLETAR---------------------------------------------------------
$concepto = array();
$x=0;

$sql = "SELECT * FROM CatConcepto";
  //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }    
  while ( odbc_fetch_row($rs) ) { 
    $concepto[$x] = "\"" .odbc_result($rs, 'DesCpt'). "\",";	
	$x++;    
}//While
$concepto[$x-1] = str_replace(",","",$concepto[$x-1]);	

//--------------------------------------GRID---------------------------------------------------------
$x=0;
$filas = array();
$sql = "SELECT * FROM ProgSemanal WHERE TRAMO in ('".$_SESSION['S_Tramo']."') and SEMANA = '".$buscarsemana."' ORDER BY ESTATUS";
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
//----------------------------------------------------------------------------------------------------
 if ($_SESSION['S_Privilegios']  == "SUPERVISOR"){	 	
  	
	$bloqueo = "<script>
  $(function() {
	  document.getElementById('estatus1').options[0].disabled = true;
	  document.getElementById('estatus1').options[1].disabled = true;
	  document.getElementById('estatus1').options[2].disabled = true;	   	    
  });
  </script>";
   }//Bandera   
?>

<!DOCTYPE html>
<html lang="en"><head>
    <title id='Description'>Programacíon Semanal</title>
        
    <link rel='stylesheet' href='http://s.codepen.io/assets/reset/reset.css'>
    <link href="css/bootstrap.min.css" rel="stylesheet" />  
    <link rel="stylesheet" href="css/styl.css">
    <link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />
    <link rel="shortcut icon" href="images/favicon.ICO" /> 
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
 
<?PHP  echo $bloqueo; ?>    
<script type="text/javascript">
function consultaUnidad(valor){
	//alert(valor);
    var parametros = {
       "concepto2" : valor               
    };
    $.ajax({
       data:  parametros,
       url:   'consulta.php',
       type:  'post',
       beforeSend: function () {
          //$("#resultado").html("Procesando, espere por favor...");
       },	  
		success:  function (response) {			
            var campos = response;
			$("#unidad").val(response);			
        }//success  
    
    });
}
</script>
<script type="text/javascript">
$(document).ready(function () {
	
	//AUTOCOMPLETAR		
	var concepto = new Array(<?php  
	  foreach ($concepto as &$valor) {
        echo $valor;
      }		
	?>);				
    $("#concepto").jqxInput({placeHolder: "Escribe una actividad", height: 25, width: 450, minLength: 1,  source: concepto });	
	$("#unidad").jqxInput({ height: 25, width: 50, minLength: 1});	
	$('#unidad').attr('readonly', 'true');
	$('#unidad').css('background-color' , '#DEDEDE');
    $("#cant_prog").jqxInput({ width: 80, height: '25px', minLength: 1 });	
         
    //CATALOGOS		
	$("#tramo").click(function () {
   		$("#tramo option:selected").each(function () {
			elegido=$(this).val();
			$.post("consultaAvanceDiario.php", { tramoAd: elegido }, function(data){						
			$("#subtramo").html(data);					
			});			
        });
    });	
	$("#subtramo").click(function () {				
			$("#km_ini").empty();	
			$("#km_fin").empty();	
   		$("#subtramo option:selected").each(function () {
			elegido=$(this).val();
			$.post("consultaAvanceDiario.php", { subtramoAd: elegido }, function(data){						
			$("#km_ini").html(data);
			$("#km_fin").html(data);
			});			
        });
    }); 
	$("#cuerpo").click(function () {
   		$("#cuerpo option:selected").each(function () {
			elegido=$(this).val();
			$.post("catalogos.php", { cuerpo: elegido }, function(data){						
			$("#zona").html(data);			
			});			
        });
    }); 
	$("#tramo1").click(function () {
   		$("#tramo1 option:selected").each(function () {
			elegido=$(this).val();
			$.post("consultaAvanceDiario.php", { tramoAd: elegido }, function(data){		
			$("#subtramo1").html(data);			
			});			
        });			
    });
	$("#subtramo1").click(function () {
   		$("#subtramo1 option:selected").each(function () {
			elegido=$(this).val();
			$.post("consultaAvanceDiario.php", { subtramoAd: elegido }, function(data){					
			$("#km_ini1").html(data);
			$("#km_fin1").html(data);
			});			
        });
    }); 
	$("#cuerpo1").click(function () {
   		$("#cuerpo1 option:selected").each(function () {
			elegido=$(this).val();
			$.post("catalogos.php", { cuerpo: elegido }, function(data){						
			$("#zona1").html(data);			
			});			
        });
    });    
	 
			
 //GRID---------------------------------------------------------------         
			var data =  <?php echo json_encode($datos); ?>;		
		    
            var customsortfunc = function (column, direction) {
                var sortdata = new Array();
                if (direction == 'ascending') direction = true;
                if (direction == 'descending') direction = false;
                if (direction != null) {
                    for (i = 0; i < data.length; i++) {
                        sortdata.push(data[i]);
                    }
                }
                else sortdata = data;
                var tmpToString = Object.prototype.toString;
                Object.prototype.toString = (typeof column == "function") ? column : function () { return this[column] };
                if (direction != null) {
                    sortdata.sort(compare);
                    if (!direction) {
                        sortdata.reverse();
                    }
                }
                source.localdata = sortdata;
                $("#programacion").jqxGrid('updatebounddata', 'sort');
                Object.prototype.toString = tmpToString;
            }
            // custom comparer.
            var compare = function (value1, value2) {
                value1 = String(value1).toLowerCase();
                value2 = String(value2).toLowerCase();
                try {
                    var tmpvalue1 = parseFloat(value1);
                    if (isNaN(tmpvalue1)) {
                        if (value1 < value2) { return -1; }
                        if (value1 > value2) { return 1; }
                    }
                    else {
                        var tmpvalue2 = parseFloat(value2);
                        if (tmpvalue1 < tmpvalue2) { return -1; }
                        if (tmpvalue1 > tmpvalue2) { return 1; }
                    }
                }
                catch (error) {
                    var er = error;
                }
                return 0;
            };
            var source =
            {
                localdata: data,
                sort: customsortfunc,
                datafields:
                [
                    { name: 'PROG_ID', type: 'number' },
					{ name: 'SEMANA_ID', type: 'number' },
  				    { name: 'SEMANA', type: 'string' },	
					{ name: 'ACTIVIDAD_ID', type: 'number' },
                    { name: 'ACTIVIDAD', type: 'string' },
                    { name: 'UNIDAD', type: 'string' }, 
					{ name: 'TRAMO', type: 'string' }, 
					{ name: 'SUBTRAMO', type: 'string' },                   
                    { name: 'KM_INI', type: 'string' },
                    { name: 'KM_FIN', type: 'string' },
					{ name: 'CUERPO', type: 'string' },  
					{ name: 'ZONA', type: 'string' },
					{ name: 'CANTIDAD', type: 'number' },					
					{ name: 'OBSERVACIONES', type: 'string' },
					{ name: 'SOB_ID', type: 'string' },
					{ name: 'ESTATUS', type: 'string' }					
                ],
                datatype: "array"
            };
            var dataAdapter = new $.jqx.dataAdapter(source);
            $("#programacion").jqxGrid(
            {
                width: 1200,
                source: dataAdapter,
                sortable: true,
                pageable: true,
                autoheight: true,
                ready: function () {
                    $("#programacion").jqxGrid('sortby', 'DesCpt', 'asc');
                },
                columns: [                
				  { text: 'Clave', dataField: 'ACTIVIDAD_ID', width: 60 },
                  { text: 'Actividad', dataField: 'ACTIVIDAD', width: 430 },
                  { text: 'Unid', dataField: 'UNIDAD', width: 40 },
                  { text: 'Km_Ini', dataField: 'KM_INI', width: 70 },
                  { text: 'Km_Fin', dataField: 'KM_FIN', width: 70 },
                  { text: 'Cpo', dataField: 'CUERPO', width: 40 },
                  { text: 'Zona', dataField: 'ZONA', width: 90 },
				  { text: 'Cantidad', dataField: 'CANTIDAD', width: 70, cellsalign: 'right' },				
				  { text: 'Observaciones', dataField: 'OBSERVACIONES', width: 200 },
				  { text: 'Sbst', dataField: 'SOB_ID', width: 50 },
				  { text: 'Estatus', dataField: 'ESTATUS', width: 85 }					 		 
                ]
            });
			
			$('#programacion').on('rowclick', function (event){ 										       
			    $("#aprobar").modal('show'); 			        			
            });
			$("#programacion").on('rowselect', function (event) {	
				var folio = event.args.row.PROG_ID;	
				var semana = event.args.row.SEMANA;
				var numsem = event.args.row.SEMANA_ID;				
				var actividad = event.args.row.ACTIVIDAD;	
				var cantidad = event.args.row.CANTIDAD;	
				var estatus = event.args.row.ESTATUS;
				var tramo = event.args.row.TRAMO;
				var subtramo = event.args.row.SUBTRAMO;	
									
				var token = event.args.row.KM_INI.split();			
				var km_ini = token[0].split('+');				
				var token2 = event.args.row.KM_FIN.split();
				var km_fin = token2[0].split('+');
								
				//alert(km_ini[0]);		
				$("#folio1").jqxInput({value: folio, height: 20, width: 50, minLength: 1});	
				$("#semana1").jqxInput({value: semana, height: 20, width: 210, minLength: 1});			
				$("#numsem1").jqxInput({value: numsem, height: 20, width: 50, minLength: 1});
				$("#actividad1").jqxInput({value: actividad, height: 20, width: 450, minLength: 1});				  	
				$("#km_ini1").empty();			
				$("<option value='"+km_ini[0]+"'>"+km_ini[0]+"</option>").appendTo("#km_ini1");
				$("#km_fin1").empty();			
				$("<option value='"+km_fin[0]+"'>"+km_fin[0]+"</option>").appendTo("#km_fin1");				
				$("#enca_ini1").jqxInput({value: km_ini[1], height: 20, width: 70, minLength: 1 });
				$("#enca_fin1").jqxInput({value: km_fin[1], height: 20, width: 70, minLength: 1 });
				$("#cantidad1").jqxInput({value: cantidad, height: 20, width: 70, minLength: 1 });	
				$("#tramo1").val(tramo);	
				$("#subtramo1").val(subtramo);				
				
				if (estatus == "APROBADO"){		
				   
    				$('#tramo1').prop('disabled', true);
				    $('#tramo1').css('background-color' , '#DEDEDE');
					//$('#edit-submitted-bring-alcohol').val('no');
					//$("#subtramo1").empty();
					$('#subtramo1').prop('disabled', true);
				    $('#subtramo1').css('background-color' , '#DEDEDE');
					$('#cuerpo1').prop('disabled', true);
				    $('#cuerpo1').css('background-color' , '#DEDEDE');
					$('#zona1').prop('disabled', true);
				    $('#zona1').css('background-color' , '#DEDEDE');
					$('#km_ini1').prop('disabled', true);
				    $('#km_ini1').css('background-color' , '#DEDEDE');
					$('#enca_ini1').prop('disabled', true);
				    $('#enca_ini1').css('background-color' , '#DEDEDE');
					$('#km_fin1').prop('disabled', true);
				    $('#km_fin1').css('background-color' , '#DEDEDE');
					$('#enca_fin1').prop('disabled', true);
				    $('#enca_fin1').css('background-color' , '#DEDEDE');
					$('#estatus1').prop('disabled', true);
				    $('#estatus1').css('background-color' , '#DEDEDE');
					$('#cantidad1').attr('readonly', 'true');
				    $('#cantidad1').css('background-color' , '#DEDEDE');
				}else{
					$('#tramo1').prop('disabled', false);
				    $('#tramo1').css('background-color' , '#FFFFFF');
					$('#subtramo1').prop('disabled', false);
				    $('#subtramo1').css('background-color' , '#FFFFFF');
					$('#cuerpo1').prop('disabled', false);
				    $('#cuerpo1').css('background-color' , '#FFFFFF');
					$('#zona1').prop('disabled', false);
				    $('#zona1').css('background-color' , '#FFFFFF');
					$('#km_ini1').prop('disabled', false);
				    $('#km_ini1').css('background-color' , '#FFFFFF');
					$('#enca_ini1').prop('disabled', false);
				    $('#enca_ini1').css('background-color' , '#FFFFFF');
					$('#km_fin1').prop('disabled', false);
				    $('#km_fin1').css('background-color' , '#FFFFFF');
					$('#enca_fin1').prop('disabled', false);
				    $('#enca_fin1').css('background-color' , '#FFFFFF');
					$('#estatus1').prop('disabled', false);
				    $('#estatus1').css('background-color' , '#FFFFFF');
					$('#cantidad1').removeAttr('readonly');					
				    $('#cantidad1').css('background-color' , '#FFFFFF');
				}
				
			});
			
			
			$("#buscartramo").change(function(){
				//alert("hola");
				//$("#filtrar").submit();
				$("#filtrar").click();				
			});
			$("#buscarsemana").change(function(){
				//alert("hola");
				//$("#filtrar").submit();
				$("#filtrar").click();				
			});
			$("#enca_ini").change(function(){				    
				var longitud = $("#enca_ini").val().length;
				var encadenamiento = $("#enca_ini").val();				
							
				switch(longitud) {					
					case 1:					                    				
					$("#enca_ini").val( "00"+ encadenamiento);
					break;
					case 2:
					$("#enca_ini").val( "0"+ encadenamiento);
					break;	
					case 3:
					$("#enca_ini").val(encadenamiento);
					break;				
					default:
					$("#enca_ini").val("000");
					break;
				}				
			});	
			$("#enca_fin").change(function(){				    
				var longitud = $("#enca_fin").val().length;
				var encadenamiento = $("#enca_fin").val();				
							
				switch(longitud) {					
					case 1:					                    				
					$("#enca_fin").val( "00"+ encadenamiento);
					break;
					case 2:
					$("#enca_fin").val( "0"+ encadenamiento);
					break;	
					case 3:
					$("#enca_fin").val(encadenamiento);
					break;				
					default:
					$("#enca_fin").val("000");
					break;
				}				
			});	
			$("#enca_ini1").change(function(){				    
				var longitud = $("#enca_ini1").val().length;
				var encadenamiento = $("#enca_ini1").val();				
							
				switch(longitud) {					
					case 1:					                    				
					$("#enca_ini1").val( "00"+ encadenamiento);
					break;
					case 2:
					$("#enca_ini1").val( "0"+ encadenamiento);
					break;	
					case 3:
					$("#enca_ini1").val(encadenamiento);
					break;				
					default:
					$("#enca_ini1").val("000");
					break;
				}				
			});	
			$("#enca_fin1").change(function(){				    
				var longitud = $("#enca_fin1").val().length;
				var encadenamiento = $("#enca_fin1").val();				
							
				switch(longitud) {					
					case 1:					                    				
					$("#enca_fin1").val( "00"+ encadenamiento);
					break;
					case 2:
					$("#enca_fin1").val( "0"+ encadenamiento);
					break;	
					case 3:
					$("#enca_fin1").val(encadenamiento);
					break;				
					default:
					$("#enca_fin1").val("000");
					break;
				}				
			});				
			
});//$(document).ready		
$(window).load(function() {	
	
  //HABILITO LOS KILOMETROS AL CARGAR LA WEB
  $("#tramo option:selected").each(function () {
	elegido=$(this).val();
	$.post("consultaAvanceDiario.php", { tramoAd: elegido }, function(data){		
	$("#subtramo").html(data);	
	});			
  }); 
  $("#subtramo option:selected").each(function () {
	elegido=$(this).val();
	$.post("consultaAvanceDiario.php", { subtramoAd: elegido }, function(data){						
	$("#km_ini").html(data);
	$("#km_fin").html(data);
	});			
  });
   $("#tramo1 option:selected").each(function () {
	elegido=$(this).val();
	$.post("consultaAvanceDiario.php", { tramoAd: elegido }, function(data){		
	$("#subtramo1").html(data);	
	});			
  }); 
  $("#subtramo1 option:selected").each(function () {
	elegido=$(this).val();
	$.post("consultaAvanceDiario.php", { subtramoAd: elegido }, function(data){						
	$("#km_ini1").html(data);
	$("#km_fin1").html(data);
	});			
  });
  $("#cuerpo option:selected").each(function () {
	elegido=$(this).val();
	$.post("catalogos.php", { cuerpo: elegido }, function(data){						
	$("#zona").html(data);			
	});			
  });
  $("#cuerpo1 option:selected").each(function () {
	elegido=$(this).val();
	$.post("catalogos.php", { cuerpo: elegido }, function(data){						
	$("#zona1").html(data);			
	});			
  });
      
});//$(window).load		
</script>
</head>
<body class='default'> 
<?php if ($_SESSION['S_Privilegios'] == 'SOBRESTANTE'){ ?>
<div id='cssmenu'>
<ul>
   <li class='active has-sub'><a href='Inicio.php'><span>Inicio</span></a></li>
   <li><a href='#'><span>Cat&aacute;logos</span></a>
      <ul>
         <li><a href='catconceptos.php'><span>Conceptos</span></a></li>
         <li><a href='catempleados.php'><span>Empleados</span></a></li>
         <li><a href='catmaquinaria.php'><span>Maquinaria</span></a></li>
         <li><a href='catins.php'><span>Insumos</span></a></li>
      </ul>
   </li>
   <li><a href='#'><span>Avance de Obra</span></a>   
     <ul>
        <?php 
		 if($_SESSION['S_Privilegios'] == 'SOBRESTANTE'){
			?><li><a href='AvanceDiario.php'><span>Avance Diario</span></a> </li>			
			<?php }else{?> 
        <li><a href='AvanceDiario1.php'><span>Avance Diario</span></a></li>
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
   <li class='active has-sub'><a href='Inicio.php'><span>Inicio</span></a></li>
   <li><a href='#'><span>Cat&aacute;logos</span></a>
      <ul>
         <li><a href='catconceptos.php'><span>Conceptos</span></a></li>
         <li><a href='catempleados.php'><span>Empleados</span></a></li>
         <li><a href='catmaquinaria.php'><span>Maquinaria</span></a></li>
         <li><a href='catins.php'><span>Insumos</span></a></li>
      </ul>
   </li>
   <li><a href='#'><span>Almacen</span></a>   
     <ul>
         <li><a href='almacen_contable.php'><span>Contable</span></a></li>
         <li><a href='almacen_fisico.php'><span>Fisico</span></a></li>
     </ul>
   </li>
   <li><a href='#'><span>Avance de Obra</span></a>   
     <ul>
        <li><a href='progsemanal.php'><span>Prog. Semanal</span></a></li>
        <?php 
		 if($_SESSION['S_Privilegios'] == 'SOBRESTANTE' || $_SESSION['S_Privilegios'] == 'SUPERVISOR' ||  $_SESSION['S_Privilegios'] == 'JEFE DE TRAMO'){
			?><li><a href='AvanceDiario.php'><span>Avance Diario</span></a></li>			
			<?php }else{?> 
        <li><a href='AvanceDiario1.php'><span>Avance Diario</span></a></li>
        <?php } ?>                   
     </ul>
   </li>
   <li><a href='#'><span>Contratos</span></a>   
     <ul>
         <li><a href='#'><span>OPEX</span></a></li>
         <li><a href='#'><span>CAPEX</span></a></li>                     
     </ul>
   </li>     
   <li><a href='#'><span>Presupuestos</span></a>   
     <ul>
         <li><a href='#'><span>OPEX</span></a>
         	<ul>
            	<a href='Prorrateo.php'><span>PRORRATEOS</span></a>
            </ul> 
          </li>  
         <li><a href='#'><span>CAPEX</span></a></li>                     
     </ul>
   </li>  
   <li class='last' id="asistencia"><a href='Asistencia.php' target="_blank"><span>Asistencia</span></a>
     <ul>
         <li><a href='EntradaManual.php'><span>Entrada Manual</span></a> </li>                  
     </ul>
 </li>
    <li><a href='Maquinaria.php'><span>Maquinaria</span></a></li>
    <li class='last'><a href='Insumos.php'><span>Insumos</span></a></li>
 
  
     <table width="500" border="0" align="right" style="'Oxygen Mono', Tahoma, Arial, sans-serif; font-size:15px; color: #58D3F7">
     <tr>
       <td width="150" height="30" align="right">Bienvenid@ :</td>
       <td width="200" align="center"><?php echo $_SESSION['S_Usuario']; ?></td>
       <td width="100" valign="bottom" class="btn btn-info">
       <form id="salir" action="AvanceDiario1.php" method="post" enctype="multipart/form-data" >             
          <button type="submit" class="btn btn-info" border ="0" id="cerrar_sesion" name="cerrar_sesion" > Cerrar Sesion</button> 
       </form> 
       </td>
     </tr>
   </table>
</ul>
</div>
<?php } ?>

<div align="center">
<img src="images/prog_sem.png" width="1005" height="218" border="0" /></div>

<!-- Tabla-->
<center>
  <table width="1200px" height="63" border="0" align="center">
    <tr>
  <td width="77%" valign="middle">
  <br>
  <br>
  <a href="#myModal" data-toggle="modal" id ="myModal" name="myModal" data-target=".bs-example-modal-lg" title="Programacion"><img src="images/plus.png" width="65" height="62" /></a><br></td>   
  <td width="23%" align="left" valign="top" scope="col">
   <form id="Filtro" action="progsemanal.php" method="post" enctype="multipart/form-data" >        
    <fieldset>
    <legend>Filtro de informaci&oacute;n:</legend>
    <table width="380" height="63" align="center">      
      <tr>
      <td width="23%" height="25">Tramo:</td>           
      <td width="77%">
           
      <select class="form-control" name="buscartramo" id="buscartramo"  >
      <?php	
	  $i=1;
	  $selected="";
      $sql = "SELECT DISTINCT TRAMO FROM Accesos WHERE Usuario_ID='".$_SESSION['S_UsuarioID']."'";
      //echo $sql;
      $rs = odbc_exec( $conn, $sql );
      if ( !$rs ) { 
       exit( "Error en la consulta SQL" ); 
      }     
      while ( odbc_fetch_row($rs) ) { 
       $tramoF = odbc_result($rs, 'TRAMO'); 
	   if ($_SESSION['S_Tramo']==$tramoF)
		    $selected="selected";			
		else
			$selected="";   
       echo "<option id='".$i."'".$selected.">".$tramoF."</option>";
	   $i++;
      }//While 	 
	  ?>            	
   	  </select>                     
      </td>
      </tr>
      <tr>
      <td width="23%" height="30"><span class="roja">Semana:</span></td>
      <td width="77%">             
      <select class="form-control" name="buscarsemana" id="buscarsemana">
      <?php	
	  $i=1;
	  $selected="";
      $sql = "SELECT DISTINCT (SEMANA), SEMANA_ID FROM ProgSemanal order by SEMANA_ID desc";
      //echo $sql;
      $rs = odbc_exec( $conn, $sql );
      if ( !$rs ) { 
       exit( "Error en la consulta SQL" ); 
      }     
      while ( odbc_fetch_row($rs) ) { 
       $semanaF = odbc_result($rs, 'SEMANA'); 
	   if ($buscarsemana==$semanaF)
		    $selected="selected";
	   else
			$selected="";  
       echo "<option id='".$i."'".$selected.">".$semanaF."</option>";
	   $i++;
      }//While 
      ?>  
      </select>
      </td>
      <td>
      <div id='ocultar' style='display:none;'>
          <button type="submit" class="btn btn-info" border ="0" id="filtrar" name="filtrar" > Filtrar</button>
      </div>      
      </td>
      </tr>
	</table>
    </fieldset>
    </form>
  </td>  
 </tr> 
 </table>
 </center>
 
<center>
 <div id="programacion"></div>  
</center>

<form id="formulario" action="progsemanal.php" method="post" enctype="multipart/form-data" >
<!-- Modal -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <center> <img src="images/sac1.png" /></center>
      </div>
      <div class="modal-body" align="center">
<!--CUERPO-->
<table width="649" height="430" border="0">
  <tr>
    <td width="531">    
   
    <table width="700" height="53" border="0">
     <tr>
       <td width="10%" height="23"> Semana: </td>
       <td width="362"><input type="text" id="semana" name="semana" class="form-control" value="<?PHP echo $semanasiguiente; ?>" readonly /></td>
       <td align="center"> # Sem: </td>
       <td width="80"><input type="textbox" id="numsem" name="numsem" size="7" class="form-control" value="<?PHP echo $semananue; ?>" readonly /></td>
      </tr>
      <tr>
      <tr>
      <td width="10%" height="25">Tramo:</td>           
      <td width="38%">
         <select name="tramo" id="tramo" class="form-control">   	  	    
  		    <!--<option value="10">Seleccione un tramo</option> -->
            <?php	
	        $i=1;
	        $selected="";
            $sql = "SELECT DISTINCT TRAMO FROM Accesos WHERE Usuario_ID='".$_SESSION['S_UsuarioID']."'";
            echo $sql;
            $rs = odbc_exec( $conn, $sql );
            if ( !$rs ) { 
              exit( "Error en la consulta SQL" ); 
            }     
            while ( odbc_fetch_row($rs) ) { 
              $tramoF = odbc_result($rs, 'TRAMO'); 
	          if ($_SESSION['S_Tramo']==$tramoF)
		        $selected="selected";			
		      else
			    $selected="";   
              echo "<option id='".$i."'".$selected.">".$tramoF."</option>";
	          $i++;
            }//While 	 
	        ?>                        
    	 </select>                          
      </td>     
      <td width="10%" height="25">SubTramo:</td>           
      <td width="38%">         
         <select name="subtramo" id="subtramo" class="form-control">           
         </select>                          
      </td>  	 	     
    </tr>
    <tr> <p></tr>
    </table>
    <br>
    <br>
<hr size="3" />
<table width="647" border="0">
  <tr>    
    <td width="88" align="center">Actividad</td>   
    <td width="371"><input type="text" id="concepto" name="concepto" accept-charset="utf-8" onblur="consultaUnidad($('#concepto').val());" required autocomplete="off"/></td>   
    <td width="88" align="center">Unidad</td>   
    <td width="371"><input type="text" id="unidad" name="unidad" accept-charset="utf-8" /></td>   
  </tr>
</table>
<hr size="3" />
<table width="400" height="10" border="0" >
  <tr>         
    <td width="50px">Cuerpo</td>
    <td  width="100px">
    <select name="cuerpo" id="cuerpo">
      <option value="A"> A </option>
      <option value="B"> B </option>
      <option value="C"> C </option>
    </select>
    </td>
    <td width="40px"></td>
    <td width="10px"></td>
    <td>Zona</td>
    <td >
    <select name="zona" id="zona">     
    </select>
    </td> 
    </tr>
   <br>
    <tr>
    <td >Km Ini</td>
    <td colspan="3">   
     <select name="km_ini" id="km_ini" >      
     </select>
    +
    <input type="text" id="enca_ini" name="enca_ini" value="000" size="3">
    </td> 
    </tr>
    <tr>    
    <td >Km Fin</td>
    <td colspan="3">
     <select name="km_fin" id="km_fin" >           
     </select>
     +
     <input type="text" id="enca_fin" name="enca_fin" value="000" size="3">
     </td>     
    <td> Cantidad: </td>
     <td >
     <input type="number" id="cant_prog" name="cant_prog" step="any">
     </td>
    
    </tr>      
  </tr>  
 </table> 
  <br>
  <br>
<hr size="3" />
<center>
<table width="639" height="40" border="0">
  <tr>
    <td width="114"  valign="middle">Observaciones</td>
    <td width="525"><textarea rows="2" cols="40" id="observacion" name="observacion"></textarea></td>
  </tr>
</table>
</td>
</center>
  </tr>  
</table>

<!--FIN-->
</div>
      <div class="modal-footer">       
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="guardar" id="guardar">Guardar</button>
      </div>
    </div>
  </div>
</div>
<!--Modal-->
</form>
       
  
       
       
       <!-- MODAL DE APROBACION DE ACTIVIDADES-->
<form id="formulario" action="progsemanal.php" method="post" enctype="multipart/form-data" >
<div class="modal fade" id="aprobar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Aprobar Actividades Programadas</h4>
      </div>
      <div class="modal-body">
      <!--Cuerpo-->
     <table width="580" height="319" border="0"  valign="top">
     <tr>
       <td width="63" height="35"> Folio: </td>
       <td ><input type="text" id="folio1" name="folio1" class="form-control" readonly /></td> 
       <td></td>     
       </tr>       
       <tr>
       <td > Semana: </td>
       <td ><input type="text" id="semana1" name="semana1"  class="form-control" readonly /></td>   
       <td height="32"> # Sem: </td>
       <td><input type="text" id="numsem1" name="numsem1"  class="form-control" readonly /></td>   
       <td> </td>    
     </tr>
     <br>
     <tr>
      <td height="33" align="left">Tramo:</td>                 
      <td>
  	  <select name="tramo1" id="tramo1">   	  	    
  	  <?php	
	  $i=1;
	  $selected="";
      $sql = "SELECT DISTINCT TRAMO FROM Accesos WHERE Usuario_ID='".$_SESSION['S_UsuarioID']."'";
      echo $sql;
      $rs = odbc_exec( $conn, $sql );
      if ( !$rs ) { 
       exit( "Error en la consulta SQL" ); 
      }     
      while ( odbc_fetch_row($rs) ) { 
       $tramoF = odbc_result($rs, 'TRAMO'); 
	   if ($_SESSION['S_Tramo']==$tramoF)
		    $selected="selected";			
		else
			$selected="";   
       echo "<option id='".$i."'".$selected.">".$tramoF."</option>";
	   $i++;
      }//While 	 
	 ?>                             
      </select>
      </td> 
      <td height="33">SubTramo:</td>           
      <td>         
         <select name="subtramo1" id="subtramo1">                       
         </select>                          
      </td>
      </tr>
       <tr></tr>
  <tr>
    <td  height="33">Actividad</td>
    <td colspan="5"><input type="textbox" id="actividad1" name="actividad1"  class="form-control" readonly /></td>
  </tr>
  <tr></tr>
  <tr>
   <td  height="35">Cuerpo</td>
     <td >
     <select name="cuerpo1" id="cuerpo1">
       <option id="1"> A </option>
       <option id="2"> B </option>
       <option id="3"> C </option>
     </select>
     Zona
     <select name="zona1" id="zona1" >
     </select>
     </td>    
  </tr>
  <tr>
  <td width="100" height="37">Km Inicial</td>
    <td width="61">
    <select name="km_ini1" id="km_ini1">      
    </select>
    +
    <input type="text" id="enca_ini1" name="enca_ini1" value="000" size="3">
    </td>
  </tr>
  <tr>
    <td width="100" height="37">Km Final</td>
    <td width="61">
    <select name="km_fin1" id="km_fin1">      
    </select>
    +
    <input type="text" id="enca_fin1" name="enca_fin1" value="000" size="3">
    </td>
  
  <td height="40"> Cantidad: </td>
     <td width="144">
     <input type="number" id="cantidad1" name="cantidad1" class="form-control" step="any" />
     </td>
  </tr>
   <tr></tr>
  <tr>
    <td height="41" >Estatus</td>    
    <td colspan="3" >
    <select name="estatus1" id="estatus1" >
        <option value="ABIERTO">Abierto</option>   
        <option value="APROBADO">Aprobar</option>     
     	<option value="ELIMINAR">Eliminar</option>            	
   	  </select> 
    </td>
  </tr>
</table>          
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="aprobar" id="aprobar">Guardar</button>
      </div>
    </div>
  </div>
</div>
<!--FIN-->
</form>
</body>
</html>