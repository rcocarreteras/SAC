<?php
// Sección de Conexión a la base de datos

 $dsn = "Driver={SQL Server};
 
 Server=192.168.130.129;Database=Sac;Integrated Security=SSPI;Persist Security Info=False;";
  
 // Se realiza la conexón con los datos especificados anteriormente
 $conn = odbc_connect( $dsn, 'sa', 'S1st3m45' );
 if (!$conn) { 
 exit( "Error al conectar: " . $conn);
 }

// ------------------------------------------  Conexion a la base

if (isset($_POST['selAmes'])){
	$argums = utf8_decode($_POST['selAmes']);
	$anio = substr($argums,0,4);
	$mes =  substr($argums,-2);
	
	$x=0;
    $filas = array();
	
	$sql = "SELECT  NoEmp, Nombre, SubCtaDes, CvCpt, Actividad, NoHr, HrExt, Costo, CvBase
	FROM  PratMdO  WHERE (Anio = ".$anio.") AND (NuMes = ".$mes.")";
    //echo $sql;
	
    $rs = odbc_exec($conn, $sql);
    if ( !$rs ) { 
      exit( "Error en la consulta SQL" ); 
    } 
	while ($row = odbc_fetch_array($rs)) {    
  		$filas[$x] = array_map('utf8_encode',$row);    
   		$x++;   	 
    }//While    
	$resultado =  $filas;  
	echo json_encode($resultado);
}

?>