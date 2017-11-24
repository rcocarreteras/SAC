<?php // Se define la cadena de conexión 
 $dsn = "Driver={SQL Server}; 
 Server=192.168.130.129;Database=Sac;Integrated Security=SSPI;Persist Security Info=False;";
  $conn = odbc_connect( $dsn, 'sa', 'S1st3m45' );
 if (!$conn) { 
 exit( "Error al conectar: " . $conn);
 }
date_default_timezone_set('America/Mexico_City');

//print_r($_GET);
//print_r($_POST);
$bandera=false;
$semana ="";
$tramo ="";

//if (isset($_GET['Exportar'])){
	
	header("Content-type: application/x-msexcel");
    header("Content-Disposition: attachment; filename=Prenomina.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    header("Content-Description: PHP/INTERBASE Generated Data" ); 
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
    header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT"); 
    header("Cache-Control: no-cache, must-revalidate");
	/*$semana= $_GET['Semana'];
	$semana = trim($semana);
	$tramo= $_GET['Tramo'];
	$tramo = trim($tramo);*/
	$bandera=true;
//}

?>

<!DOCTYPE html><html class=''>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Prenomina</title>
<style type="text/css">
/*table{border: black thick}
tr{
border: black thick;
border-top-width: 5px;
border-right-width: 5px;
border-bottom-width: 5px;
border-left-width: 5px;
}*/
</style>
</head><body>

<table width="2056" height="87"  border="2px" align="center">
	<thead style="border:thick">
	<tr>
	  <th height="23" colspan="16" >&nbsp;</th>
	  <th width="412" rowspan="3" >OBSERVACIONES</th>
	  </tr>
	<tr>
      <th width="263" rowspan="2" >Nombre</th>
      <th width="178" rowspan="2"> Puesto</th>
      <th height="23" >L</th>
      <th >M</th>
      <th >M</th>
      <th >J</th>
      <th >V</th>
      <th >S</th>
      <th >D</th>
      <th width="80" rowspan="2" >TOTALDIAS</th>
      <th width="69" rowspan="2" >HORAS EXT 2</th>
      <th width="75" rowspan="2" >HORAS EXT 3</th>
      <th width="108" rowspan="2" >FESTIVO LABORADO</th>
      <th width="108" rowspan="2" >DESCANSO LABORADO</th>
      <th width="114" rowspan="2" >PRIMA DOMINICAL</th>
      <th width="93" rowspan="2" >Otros Descuentos</th>
      </tr>
    <tr>
      <th width="63" height="23" >&nbsp;</th>
     <th width="65" >&nbsp;</th>
     <th width="65" >&nbsp;</th>
     <th width="62" >&nbsp;</th>
     <th width="63" >&nbsp;</th>
     <th width="63" >&nbsp;</th>
     <th width="67" >&nbsp;</th>
     </tr>
  
  <tbody>
  <?php
if ($bandera==true){
 
 //SACAMOS LA ACTIVIDAD DEL PRIMER FOLIO
  $sql = "select * from Asistencia where FECHA_ENTRADA = '2015-08-31' and ESTATUS ='completo' ";
 //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
  exit( "Error en la consulta SQL" ); 
  }    
 while ( odbc_fetch_row($rs) ) { 
   $nombre = odbc_result($rs, 'NOMBRE');
   
   
  
   
    echo "<tr>";   
	  echo "<td>".$nombre."</td>";	
	echo "</tr>";    
 }//While 
}//if

 ?>   
  
       
</tbody>
</table>

</body>
</html>
<?
odbc_close($conn);
?>
