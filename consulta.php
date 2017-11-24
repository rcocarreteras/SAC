<?php 
// Se define la cadena de conexión 
 $dsn = "Driver={SQL Server}; 
 Server=192.168.130.129;Database=Sac;Integrated Security=SSPI;Persist Security Info=False;";
  $conn = odbc_connect( $dsn, 'sa', 'S1st3m45' );
 if (!$conn) { 
 exit( "Error al conectar: " . $conn);
 }

$resultado = "";
if (!isset($_SESSION)) {
  session_start();
}
 header( 'Content-type: text/html; charset=iso-8859-1' );
//$resultado = print_r($_POST);
//$resultado= $_POST['concepto'];
//------------------------------------------------OBTENEMOS LA FORMULA--------------------------------------
if (isset($_POST['concepto'])){
	$utf = utf8_decode($_POST['concepto']);
	$concepto = utf8_encode($utf);	
	
    $sql = "SELECT * FROM CatConcepto WHERE DesCpt = '".$utf."'";
    //echo $sql;
  $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta SQL" ); 
    } 
	while ( odbc_fetch_row($rs) ) {         
      $resultado = odbc_result($rs, 'Formula');	 
    }//While       
	echo $resultado;	
}
//------------------------------------------------OBTENEMOS LA UNIDAD--------------------------------------
if (isset($_POST['concepto2'])){
	$utf = utf8_decode($_POST['concepto2']);
	$concepto = utf8_encode($utf);	
	
    $sql = "SELECT * FROM CatConcepto WHERE DesCpt = '".$utf."'";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta SQL" ); 
    } 
	while ( odbc_fetch_row($rs) ) {         
      $resultado = odbc_result($rs, 'Unid');	 
    }//While       
	echo $resultado;
}
//------------------------------------------------OBTENEMOS LA UNIDAD DEL INSUMO--------------------------------------
if (isset($_POST['concepto3'])){
	$valor = utf8_decode($_POST['concepto3']);	
    $sql = "SELECT * FROM CatInsumos WHERE DescIns = '".$valor."'";
    //echo $sql;
  $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta SQL" ); 
    } 
	while ( odbc_fetch_row($rs) ) {         
      $resultado = odbc_result($rs, 'Unid');	 
    }//While       
	echo $resultado;	
}
//------------------------------------------OBTENEMOS LOS DATOS DE LOS INSUMOS--------------------------------
if (isset($_POST['insumo'])){
	$valor = utf8_decode($_POST['insumo']);
	$sql = "SELECT * FROM CatInsumos WHERE DescIns='".$valor."'";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta SQL" ); 
    } 	
	while ( odbc_fetch_row($rs) ) {         
      $numero = odbc_result($rs, 'CvIns');
	  $unidad = odbc_result($rs, 'Unid');	
      
	  $options = $numero."*".$unidad;
	}//While 	
	echo $options;		
}
//------------------------------------------OBTENEMOS LOS DATOS DEL SUPERVISOR--------------------------------
if (isset($_POST['user'])){
	$user = utf8_decode($_POST['user']);
	$pass = base64_encode($_POST['pass']);
	$options = "invalido";
	$sql = "select * from Usuarios where PRIVILEGIOS='SUPERVISOR' AND USUARIO='".$user."' AND PASSWORD='".$pass."'";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta SQL" ); 
    } 	
	while ( odbc_fetch_row($rs) ) {         
      $options = "valido";
	}//While 	
	echo $options;		
}
//------------------------------------------OBTENEMOS LOS DEL EMPLEADO--------------------------------
if (isset($_POST['nombre'])){
	$valor = utf8_decode($_POST['nombre']);
	$sql = "SELECT Empleado FROM CatEmpleados WHERE NoEmp='".$valor."'";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta SQL" ); 
    } 	
	while ( odbc_fetch_row($rs) ) {         
      $options = odbc_result($rs, 'Empleado');
	}//While 	
	echo utf8_encode($options);		
}
//------------------------------------------OBTENEMOS LOS DEL EMPLEADO--------------------------------
if (isset($_POST['numero'])){
	$valor = utf8_decode($_POST['numero']);
	$sql = "SELECT NoEmp FROM CatEmpleados WHERE Empleado='".$valor."'";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta SQL" ); 
    } 	
	while ( odbc_fetch_row($rs) ) {         
      $options = odbc_result($rs, 'NoEmp');
	}//While 	
	echo $options;		
}
//------------------------------------------OBTENEMOS SOBRESTANTE--------------------------------
if (isset($_POST['subtSobres'])){
	$valor = utf8_decode($_POST['subtSobres']);
	$valor2 = utf8_decode($_POST['tramoSub']);
	
	$sql = "select DISTINCT(SOBRESTANTE) from Accesos where TRAMO='".$valor2."' and SUBTRAMO = '".$valor."'";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta SQL" ); 
    } 	
	while ( odbc_fetch_row($rs) ) {         
      $resultado = odbc_result($rs, 'SOBRESTANTE');
	  $resultado = utf8_encode($resultado);
	  $options.= "<option value='".$resultado."'>".$resultado."</option>";
	}//While 		
	echo $options;	
}


//------------------------------------------------OBTENEMOS EL SUBTRAMO--------------------------------------
if (isset($_POST['tramo'])){
	$valor = utf8_decode($_POST['tramo']);
	$valor2 = utf8_decode($_POST['id2']);
	$valor3 = utf8_decode($_POST['plaza2']);
	
    $sql = "SELECT DISTINCT (SUBTRAMO) FROM Accesos WHERE TRAMO = '".$valor."' AND USUARIO_ID = '".$valor2."' AND PLAZA = '".$valor3."'";
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
//--------------------------------------------OBTENEMOS EL TRAMO DEL FILTRO DE AVANCE DIARIO--------------------------------------
if (isset($_POST['plaza'])){
	$valor = utf8_decode($_POST['plaza']);
	$valor2 = utf8_decode($_POST['id2']);	
	
    $sql = "SELECT DISTINCT (TRAMO) FROM Accesos WHERE PLAZA = '".$valor."' AND USUARIO_ID = '".$valor2."'";
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
//------------------------------------------------OBTENEMOS LA SEMANA DEL FILTRO DE AVANCE DIARIO --------------------------------------
if (isset($_POST['tramo2'])){
	$valor = utf8_decode($_POST['tramo2']);
	//$concepto = utf8_encode($utf);	
	
    $sql = "SELECT DISTINCT (SEMANA) FROM AvanceDiario WHERE TRAMO = '".$valor."'";
    //echo $sql;
  	$rs = odbc_exec( $conn, $sql );
   	if ( !$rs ) { 
  	    exit( "Error en la consulta SQL" ); 
  	} 	
	while ( odbc_fetch_row($rs) ) {  
	$resultado = odbc_result($rs, 'SEMANA');
	//$options = $resultado;
	$options = $options.= "<option value='".$resultado."'>".$resultado."</option>";
    }//While       
	echo $options;	
}

//------------------------------------------OBTENEMOS SUBTRAMO--------------------------------
if (isset($_POST['tramo3'])){
	$valor = utf8_decode($_POST['tramo3']);
	$sql = "SELECT SUBTRAMO FROM CatTramos where TRAMO='".$valor."'";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta SQL" ); 
    } 	
	while ( odbc_fetch_row($rs) ) {         
      $resultado = odbc_result($rs, 'SUBTRAMO');
	  $options.= "<option value='".$resultado."'>".$resultado."</option>";
	}//While 	
	//echo "<option id='".$_SESSION['S_Usuario']."'>".$_SESSION['S_Usuario']."</option>";	
	echo $options;	
}
//------------------------------------------------OBTENEMOS EL KILOMETRAJE--------------------------------------
if (isset($_POST['km'])){
	$valor = utf8_decode($_POST['km']);
		
    $sql = "SELECT DISTINCT (SUBTRAMO), KM_INI, KM_FIN FROM Accesos WHERE SUBTRAMO = '".$valor."'";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta SQL" ); 
    } 	
	while ( odbc_fetch_row($rs) ) {         
      $km1 = odbc_result($rs, 'KM_INI');
	  $km2 = odbc_result($rs, 'KM_FIN');	
	  for ($i = $km1; $i <= $km2; $i++) {
           $options.= "<option value=".$i.">".$i."</option>";
      }
    }//While       
	echo $options;	
}

?>
