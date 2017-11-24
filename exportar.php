<?php 
// Se define la cadena de conexión 
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

if (isset($_GET['Exportar'])){
	
	header("Content-type: application/x-msexcel");
    header("Content-Disposition: attachment; filename=Avance Diario.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    header("Content-Description: PHP/INTERBASE Generated Data" ); 
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
    header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT"); 
    header("Cache-Control: no-cache, must-revalidate");
	$semana= $_GET['Semana'];
	$semana = trim($semana);
	$tramo= $_GET['Tramo'];
	$tramo = trim($tramo);
	$bandera=true;
}

?>

<!DOCTYPE html><html class=''>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Exportar Avance Diario</title>
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

<table width="auto" height="290"  border="2px" align="center">
	<thead style="border:thick">
	<tr> 
		<th height="198" colspan="3" align="left" nowrap="nowrap"><!--<img src="http://192.168.130.131/SAC/images/imagen.png" alt="" width="201" height="79">--></th>
        <th colspan="19" align="left"><p>Fecha: <?php echo date('Y-m-j'); ?></p>
        <p>Tramo: <?php echo $tramo; ?></p>
        <!--<p>Base de Conservaci&oacute;n:</p>--></th>        
	</tr>
  </thead>
    <tr>
     <th height="38" colspan="4" bgcolor="#E0ED0A">Destino de Costo</th>
     <th colspan="4"> Ubicaci&oacute;n</th>
     <th colspan="3" nowrap="nowrap"> Dimensiones</th>
     <th> Cantidad </th>
     <th colspan="2" bgcolor="#B8CCE4"> Mano de Obra </th>
     <th colspan="5" bgcolor="#D8E4BC"> Maquinaria  / Veh&iacute;culos</th>
     <th colspan="3" bgcolor="#FABF8F">Insumos</th>     
   </tr>
   <tr>
   
     <td width="auto" nowrap="nowrap"><strong>Concepto de Obra</strong></td>
     <td width="auto"><strong>SubCta</strong></td>
     <td width="auto"><strong>Clave</strong></td>
     <td width="auto" nowrap="nowrap"><strong>Unidad</strong></td>
     <td width="auto" nowrap="nowrap"><strong>KM Inicial</strong></td>
     <td width="auto" nowrap="nowrap"><strong>KM Final</strong></td>
     <td width="auto" nowrap="nowrap"><strong>Cuerpo</strong></td>
     <td width="auto" nowrap="nowrap"><strong>Zona</strong></td>
     <td width="auto" nowrap="nowrap"><strong>Longitud</strong></td>
     <td width="auto" nowrap="nowrap"><strong>Ancho</strong></td>
     <td width="auto" nowrap="nowrap"><strong>Espesor</strong></td>
     <td width="auto"><strong>Volumen o &Aacute;rea</strong></td>
     <td width="auto" nowrap="nowrap"><strong>Empleado</strong></td>
     <td width="auto" nowrap="nowrap"><strong>Hrs</strong></td>
     <td width="auto"><strong>Equipo</strong></td>
     <td width="auto"><strong>Tipo</strong></td>
     <td width="auto"><strong>Hor&oacute;metro Inicial</strong></td>
     <td width="auto"><strong>Horómetro Final</strong></td>
     <td width="auto"><strong>Hrs</strong></td>
     <td width="auto"><strong>Material</strong></td>
     <td width="auto"><strong>Unid</strong></td>
     <td width="auto"><strong>Cantidad</strong></td>     
  
   </tr>
  <tbody>
  <?php
if ($bandera==true){
 
 //SACAMOS LA ACTIVIDAD DEL PRIMER FOLIO
  $sql = "SELECT * FROM AvanceDiario WHERE SEMANA='". $semana ."' AND TRAMO = '".$tramo."' order by ACTIVIDAD ";
 //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
  exit( "Error en la consulta SQL" ); 
  }    
 while ( odbc_fetch_row($rs) ) { 
   $folio = odbc_result($rs, 'AVANCE_ID');
   $actividad  = odbc_result($rs, 'ACTIVIDAD');
   $km_ini  = odbc_result($rs, 'KM_INI');
   $km_fin  = odbc_result($rs, 'KM_FIN');
   $cuerpo  = odbc_result($rs, 'CUERPO');
   $zona  = odbc_result($rs, 'ZONA');
   $longitud  = odbc_result($rs, 'LONGITUD');
   $ancho  = odbc_result($rs, 'ANCHO');
   $espesor  = odbc_result($rs, 'ESPESOR');
   $cantidad  = odbc_result($rs, 'CANTIDAD');
   
   //OBTENEMOS LOS DATOS DE LA ACTIVIDAD
   $sql2 = "SELECT * FROM CatConcepto WHERE DesCpt='".$actividad."'";
   //echo $sql;
   $rs2 = odbc_exec( $conn, $sql2 );
   if ( !$rs2 ) { 
     exit( "Error en la consulta SQL" ); 
   }    
   while ( odbc_fetch_row($rs2) ) {
	   $DesCpt = odbc_result($rs2, 'DesCpt');
	   $SubCta = odbc_result($rs2, 'SubCta');
	   $CvCpt = odbc_result($rs2, 'CvCpt');
	   $Unid = odbc_result($rs2, 'Unid'); 
   }//While   
   
    echo "<tr>";   
	  echo "<td>".$DesCpt."</td>";
	  echo "<td>".$SubCta."</td>";
	  echo "<td>".$CvCpt."</td>";
	  echo "<td align='right'>".$Unid."</td>";  
	  echo "<td align='right'>".$km_ini."</td>";  
      echo "<td align='right'>".$km_fin."</td>"; 
	  echo "<td>".$cuerpo."</td>";
	  echo "<td>".$zona."</td>";
	  echo "<td>".$longitud."</td>";
	  echo "<td>".$ancho."</td>";
	  echo "<td>".$espesor."</td>";
	  echo "<td>".$cantidad."</td>";
     
      
	  //OBTENEMOS EL NUMERO MAYOR DE LOS PUNTOS TRABAJADOS
	  $tipo=0;
	  $total=0;	  
	  $sql2 = "SELECT DISTINCT(TIPO), COUNT(*) AS TOTAL  FROM PuntoTrabajado  WHERE AVANCE_ID = '".$folio."' GROUP BY TIPO ORDER BY TOTAL DESC ";
      //echo $sql;
      $rs2 = odbc_exec( $conn, $sql2 );
      if ( !$rs2 ) { 
        exit( "Error en la consulta SQL" ); 
      }    
      while ( odbc_fetch_row($rs2) ) {
		  $tipo = odbc_result($rs2, 'TIPO');
		  $total = odbc_result($rs2, 'TOTAL');		 
		  break;	
      }//While  
	  
	  for ($i = 1; $i <= $total; $i++) {
		 	$bandera = false;
			$x=0;	  
		   //OBTENEMOS LOS DATOS DE LA MANO DE OBRA
           $sql2 = "SELECT TOP ".$i." * FROM PuntoTrabajado  WHERE AVANCE_ID = '".$folio."' AND TIPO ='MANO DE OBRA' ";
           //echo $sql;
           $rs2 = odbc_exec( $conn, $sql2 );
           if ( !$rs2 ) { 
             exit( "Error en la consulta SQL" ); 
           }    
           while ( odbc_fetch_row($rs2) ) {			   
		      if ($i == $x + 1) {
			    echo "<td>".$nombre = odbc_result($rs2, 'NOMBRE')."</td>";
			    echo "<td>".$horas = odbc_result($rs2, 'HORAS')."</td>";
				$bandera = true;								
		      }
			  $x++;     
           }//While 
		   if ($bandera == false) {
		      echo "<td></td>";
		      echo "<td></td>";	  
	       } 
		   $x=0;
		   $bandera = false;
		   //OBTENEMOS LOS DATOS DE LA MAQUINARIA
		   $sql2 = "SELECT TOP ".$i." * FROM PuntoTrabajado  WHERE AVANCE_ID = '".$folio."' AND TIPO ='MAQUINARIA' ";
           //echo $sql;
           $rs2 = odbc_exec( $conn, $sql2 );
           if ( !$rs2 ) { 
             exit( "Error en la consulta SQL" ); 
           }    
           while ( odbc_fetch_row($rs2) ) {			   
		      if ($i == $x + 1) {
			    echo "<td>".$nombre = odbc_result($rs2, 'NOMBRE')."</td>";
			    echo "<td></td>";
				echo "<td>".$HK_INI = odbc_result($rs2, 'HK_INI')."</td>";
				echo "<td>".$HK_FIN = odbc_result($rs2, 'HK_FIN')."</td>";
			    echo "<td>".$horas = odbc_result($rs2, 'HORAS')."</td>";	
				$bandera = true;			
		      }
			  $x++;		      	      
           }//While
		   if ($bandera == false ) {
		      echo "<td></td>";
		      echo "<td></td>";	 
			  echo "<td></td>";
			  echo "<td></td>";
			  echo "<td></td>"; 
	       } 
		 
		   $x=0; 
		   $bandera = false;
		  //OBTENEMOS LOS DATOS DE LOS INSUMOS
		   $sql2 = "SELECT TOP ".$i." * FROM PuntoTrabajado  WHERE AVANCE_ID = '".$folio."' AND TIPO ='INSUMO' ";
           //echo $sql;
           $rs2 = odbc_exec( $conn, $sql2 );
           if ( !$rs2 ) { 
             exit( "Error en la consulta SQL" ); 
           }    
           while ( odbc_fetch_row($rs2) ) {			   
		      if ($i == $x + 1) {
			    echo "<td>".$nombre = odbc_result($rs2, 'NOMBRE')."</td>";			   
				echo "<td></td>";
				echo "<td>".$horas = odbc_result($rs2, 'HORAS')."</td>";
				$bandera = true;					    
			  }
				$x++;	
           }//While
		   if ($bandera == false ) {
		      echo "<td></td>";
		      echo "<td></td>";	 
			  echo "<td></td>";			   
	       } 		
		   $x=0;
		   
		    if ($i < $total ) {
		 	  echo "</tr>";
		      echo "<tr>";   
		      echo "<td></td>";
			  echo "<td></td>";
			  echo "<td></td>";
			  echo "<td></td>";
			  echo "<td></td>";
			  echo "<td></td>";
			  echo "<td></td>";
			  echo "<td></td>";
			  echo "<td></td>";
			  echo "<td></td>";
			  echo "<td></td>";
			  echo "<td></td>";			  
	        }
		    	
      }//FIN FOR   
	  if ($total == 0) {
		 	 // echo "</tr>";
		      //echo "<tr>";   
		      echo "<td></td>";
			  echo "<td></td>";
			  echo "<td></td>";
			  echo "<td></td>";
			  echo "<td></td>";
			  echo "<td></td>";
			  echo "<td></td>";
			  echo "<td></td>";
			  echo "<td></td>";
			  echo "<td></td>";
			  echo "</tr>";
	   } else{
		    echo "</tr>";    
	   }
	   
   
   
  
   		 
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
