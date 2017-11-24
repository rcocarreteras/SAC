<?php 
// Se define la cadena de conexión 
 $dsn = "Driver={SQL Server}; 
 Server=192.168.130.129;Database=Sac;Integrated Security=SSPI;Persist Security Info=False;";
  $conn = odbc_connect( $dsn, 'sa', 'S1st3m45' );
 if (!$conn) { 
 exit( "Error al conectar: " . $conn);
 }
 
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

//print_r($_POST);

$fecha = date('Y-m-j');
$folio=0; 
$longitud=0;
$ancho=0; 
$espesor=0;
$cantidad=0;
$clavecon="0";
$unidad = "";
$clavesob = "";
$sobres="";
$hora=0;
$horaex=0;
$hk_ini=0;
$hk_fin=0; 
$costo="NO";
$cant=0;
$validar = "";
//------------------------------------------------OBTENEMOS EL TRAMO--------------------------------------
if (isset($_POST['Guardar'])){	
    $fechareg= $_POST['Fecha'];
    $numsem= $_POST['Numsem'];
    $semana= $_POST['Semana'];   
    $actividad = utf8_decode($_POST['Concepto']);    
    $tramo = $_POST['Tramo'];
    $subtramo = $_POST['Subtramo'];
	$cuerpo = $_POST['Cuerpo'];
    $zona = $_POST['Zona'];	
    $km_ini = $_POST['Km_ini'];
    $enca_ini = $_POST['Enca_ini'];
    $km_fin = $_POST['Km_fin'];
    $enca_fin = $_POST['Enca_fin'];
	$longitud = $_POST['Longitud'];
	$ancho = $_POST['Ancho'];
	$espesor = $_POST['Espesor'];    
    $cantidad = $_POST['Cantidad'];  	
	$observacion = utf8_decode($_POST['Observacion']);
	$km1 = $km_ini."+".$enca_ini;  
    $km2 = $km_fin."+".$enca_fin;
	
	//VALIDACION DE VARIABLES
  if ($cantidad == ""){
	$cantidad=0;  
  }
  if ($longitud == ""){
	$longitud=0;  
  }
  if ($ancho == ""){
	$ancho=0;  
  }
  if ($espesor == ""){
	$espesor=0;  
  }
	
	$sql = "SELECT TOP 1 * FROM AvanceDiario WHERE ALTA = '".$_SESSION['S_Usuario']."' AND FECHA = '".$fechareg."' AND TRAMO = '".$tramo."'";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta SQL" ); 
    }    
    while ( odbc_fetch_row($rs) ) { 
      $actividad_ver = odbc_result($rs, 'ACTIVIDAD');
      $kmini = odbc_result($rs, 'KM_INI');
      $kmfin = odbc_result($rs, 'KM_FIN');
      $zona_ver = odbc_result($rs, 'ZONA');  	  
    }
	
 
    $sql = "SELECT * FROM CatConcepto WHERE DesCpt = '". $actividad ."'";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta CatConcepto" ); 
    }    
    while ( odbc_fetch_row($rs) ) { 
      $clavecon = odbc_result($rs, 'CvCpt');
	  $unidad = odbc_result($rs, 'Unid');
    }
  
   $sql = "SELECT * FROM Accesos WHERE Subtramo = '". $subtramo ."' and Tramo = '".$tramo."'";
  //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }    
  while ( odbc_fetch_row($rs) ) { 
    $clavesob = odbc_result($rs, 'INICIALES');
	$sobres = odbc_result($rs, 'SOBRESTANTE');
	$plaza = odbc_result($rs, 'Plaza');
  }    
  		   
  $sql = "INSERT INTO AvanceDiario VALUES ('".$folio."','".$numsem."','".$semana."','".$clavecon."','".$actividad."','".$unidad."','".$km1."','".$km2."','".$cuerpo."','".$zona."','".$longitud."','".$ancho."','".$espesor."','".$cantidad."','".$clavesob."','".$sobres."','".$tramo."','".$observacion."','EJECUTADO','".$fechareg."','".$_SESSION['S_Usuario']."','','".$fecha."','".$plaza."','".$subtramo."')";
     //echo $sql;
      $rs = odbc_exec( $conn, $sql );
      if ( !$rs ) { 
        exit( "Error en la consulta AvanceDiario" ); 
      }   
	
  //OBTENEMOS EL REGISTRO QUE ACABAMOS DE GUARDAR
  $sql = "SELECT TOP 1 * FROM AvanceDiario ORDER BY AVANCE_ID DESC";
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }    
  while ( odbc_fetch_row($rs) ) { 
    $avance_id = odbc_result($rs, 'AVANCE_ID');	
  }   
  //MANO DE OBRA  
  for ($x = 1; $x <= 30; $x++) {	  
	  if ($_POST["Nombre".$x.""] <> ""){	
	    $nombre = utf8_decode($_POST["Nombre".$x.""]); 
	    $hora = $_POST["Horas".$x.""];
	    $horaex = $_POST["Horasex".$x.""];		 
	 
	    $sql = "SELECT * FROM CatEmpleados WHERE Empleado = '".$nombre."'";
        $rs = odbc_exec( $conn, $sql );
        if ( !$rs ) { 
          exit( "Error en la consulta CatEmpleados" ); 
        }    
        while ( odbc_fetch_row($rs) ) { 
          $emp = odbc_result($rs, 'NoEmp');	
        }	 
	   
	    $sql = "INSERT INTO PuntoTrabajado VALUES ('".$avance_id."','".$emp."','".$nombre."','".$hora."','".$horaex."', '0','MANO DE OBRA','SI','','','".$fechareg."','".$clavecon."','".$subtramo."')";	   
        //echo $sql;
        $rs = odbc_exec( $conn, $sql );
        if ( !$rs ) { 
          exit( "Error en la consulta PuntoTrabajado" ); 
        }  
	    $emp = "";
	    $nombre="";
	    $hora=0;
        $horaex=0;    
     }   
 }
 //MAQUINARIA
 for ($x = 1; $x <= 5; $x++) {
	 if ($_POST["Maquinaria".$x.""] <> ""){
	 	$maquinaria = utf8_decode($_POST["Maquinaria".$x.""]);
	 	$hora = $_POST["Horasmaq".$x.""];
	 	$hk_ini = $_POST["Horaskmini".$x.""];
	    $hk_fin = $_POST["Horaskmini".$x.""];	
	 
	 	$sql = "SELECT * FROM CatMaquinaria WHERE Descrip = '".$maquinaria."'";
     	$rs = odbc_exec( $conn, $sql );
     	if ( !$rs ) { 
      		exit( "Error en la consulta SQL" ); 
     	}    
     	while ( odbc_fetch_row($rs) ) { 
       		$eco = odbc_result($rs, 'NoEco');	
    	} 
	 
	 	$sql = "INSERT INTO PuntoTrabajado VALUES ('".$avance_id."','".$eco."','".$maquinaria."','".$hora."','0','0','MAQUINARIA','SI','".$hk_ini."','".$hk_fin."','".$fechareg."','".$clavecon."','".$subtramo."')";	 	
      	//echo $sql;
      	$rs = odbc_exec( $conn, $sql );
      	if ( !$rs ) { 
        	exit( "Error en la consulta SQL" ); 
      	}  
	  	$eco="";
	  	$maquinaria="";
	  	$hora="";
	  	$hk_ini="";
	  	$hk_fin="";
 	} 
 }
 //INSUMOS
 for ($x = 1; $x <= 5; $x++) {
	 if ($_POST["Insumos".$x.""] <> ""){
	 	$insumo = utf8_decode($_POST["Insumos".$x.""]);
	 	$cant = $_POST["Insumoscant".$x.""];	 
	 	if (isset($_POST["costo".$x.""])) {	  	 
			$costo = "SI";
	 	}	 
	 
	 	$sql = "SELECT * FROM CatInsumos WHERE DescIns = '".$insumo."'";
     	$rs = odbc_exec( $conn, $sql );
     	if ( !$rs ) { 
      		exit( "Error en la consulta SQL" ); 
     	}    
     	while ( odbc_fetch_row($rs) ) { 
       		$cvins = odbc_result($rs, 'CvIns');	
    	} 
	 	
		$sql = "INSERT INTO PuntoTrabajado VALUES ('".$avance_id."','".$cvins."','".$insumo."','0','0','".$cant."','INSUMO','".$costo."','','','".$fechareg."','".$clavecon."','".$subtramo."')";	 	
      	//echo $sql;
      	$rs = odbc_exec( $conn, $sql );
      	if ( !$rs ) { 
        	exit( "Error en la consulta SQL" ); 
      	}  
	  	$cvins="";
	  	$insumo="";
	  	$cant="";
 	}	 
 }
 
}//FIN ISSET

?>
