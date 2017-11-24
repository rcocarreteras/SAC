<?php 
// Se define la cadena de conexión 
 $dsn = "Driver={SQL Server}; 
 Server=192.168.130.129;Database=Sac;Integrated Security=SSPI;Persist Security Info=False;";
  $conn = odbc_connect( $dsn, 'sa', 'S1st3m45' );
 if (!$conn) { 
 exit( "Error al conectar: " . $conn);
 }

 session_start ();
 
 $resultado = "";
 $options = "";
//$resultado = print_r($_POST);
//$resultado= $_POST['concepto'];

//------------------------------------------------OBTENEMOS EL TRAMO--------------------------------------
if (isset($_POST['base'])){
	$valor = utf8_decode($_POST['base']);
	//$concepto = utf8_encode($utf);	
	
    $sql = "SELECT DISTINCT (BASE) FROM CatTramos WHERE PLAZA = '".$valor."'";
    //echo $sql;
  	$rs = odbc_exec( $conn, $sql );
   	if ( !$rs ) { 
  	    exit( "Error en la consulta SQL" ); 
  	} 	
	while ( odbc_fetch_row($rs) ) {  
	$resultado = odbc_result($rs, 'BASE');
	$options = $options.= "<option value='".$resultado."'>".$resultado."</option>";
    }//While       
	echo $options;	
}
//------------------------------------------------OBTENEMOS EL SUBTRAMO--------------------------------------
if (isset($_POST['tramo'])){
	$valor = utf8_decode($_POST['tramo']);
	$valor1 = utf8_decode($_POST['plaza1']);
	//$concepto = utf8_encode($utf);	
	
    $sql = "SELECT DISTINCT (SUBTRAMO) FROM CatTramos WHERE TRAMO = '".$valor."' AND PLAZA='".$valor1."'";
    //echo $sql;
  	$rs = odbc_exec( $conn, $sql );
   	if ( !$rs ) { 
  	    exit( "Error en la consulta SQL" ); 
  	} 	
	while ( odbc_fetch_row($rs) ) {  
	$resultado = odbc_result($rs, 'SUBTRAMO');
	$options = $options.= "<option value='".$resultado."'>".$resultado."</option>";
    }//While       
	echo $options;	
}
//------------------------------------------------OBTENEMOS EL TRAMO--------------------------------------
if (isset($_POST['plaza'])){
	$valor = utf8_decode($_POST['plaza']);
	//$concepto = utf8_encode($utf);	
	
    $sql = "SELECT DISTINCT (TRAMO) FROM CatTramos WHERE PLAZA = '".$valor."'";
    //echo $sql;
  	$rs = odbc_exec( $conn, $sql );
   	if ( !$rs ) { 
  	    exit( "Error en la consulta SQL" ); 
  	} 	
	while ( odbc_fetch_row($rs) ) {  
	$resultado = odbc_result($rs, 'TRAMO');
	$options = $options.= "<option value='".$resultado."'>".$resultado."</option>";
    }//While       
	echo $options;	
}
//------------------------------------------------OBTENEMOS EL TRAMO--------------------------------------
if (isset($_POST['subtramo'])){
	$valor = utf8_decode($_POST['subtramo']);
	$valor1 = utf8_decode($_POST['tramo2']);
	$valor2 = utf8_decode($_POST['plaza2']);
	//$concepto = utf8_encode($utf);	
	
    $sql = "SELECT DISTINCT (BASE) FROM CatTramos WHERE SUBTRAMO = '".$valor."' AND TRAMO='".$valor1."' AND PLAZA='".$valor2."'";
    //echo $sql;
  	$rs = odbc_exec( $conn, $sql );
   	if ( !$rs ) { 
  	    exit( "Error en la consulta SQL" ); 
  	} 	
	while ( odbc_fetch_row($rs) ) {  
	$resultado = odbc_result($rs, 'BASE');
	$options = $resultado;
    }//While       
	echo $options;	
}
//------------------------------------------------OBTENEMOS EL NUM EMPLEADO--------------------------------------
if (isset($_POST['numemp'])){
	$valor = utf8_decode($_POST['numemp']);
	//$concepto = utf8_encode($utf);	
	
    $sql = "SELECT DISTINCT (USUARIO_ID) FROM Usuarios WHERE NOMBRE = '".$valor."'";
    //echo $sql;
  	$rs = odbc_exec( $conn, $sql );
   	if ( !$rs ) { 
  	    exit( "Error en la consulta SQL" ); 
  	} 	
	while ( odbc_fetch_row($rs) ) {  
	$resultado = odbc_result($rs, 'USUARIO_ID');
	$options = $resultado;
    }//While       
	echo $options;	
}
?>