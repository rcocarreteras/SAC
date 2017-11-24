<?php 
// Se define la cadena de conexión 
 $dsn = "Driver={SQL Server}; 
 Server=192.168.130.129;Database=Sac;Integrated Security=SSPI;Persist Security Info=False;";
  $conn = odbc_connect( $dsn, 'sa', 'S1st3m45' );
  date_default_timezone_set('America/Mexico_City');  
 if (!$conn) { 
 exit( "Error al conectar: " . $conn); 
 }

$resultado = "";
$options = "";
$bandera = "";
if (!isset($_SESSION)) {
  session_start();
}
 header( 'Content-type: text/html; charset=iso-8859-1' );
//$resultado = print_r($_POST);
//$resultado= $_POST['concepto'];

/*************************************************ACTIVIDAD FOTOS*************************************************/
if (isset($_POST['BuscarActividad'])){
	$tramo_adjuntar = utf8_decode($_POST['tramo']);
	$periodo_adjuntar = utf8_decode($_POST['periodo']);
	
    $sql = "SELECT DISTINCT(ACTIVIDAD) FROM AvanceDiario WHERE TRAMO='".$tramo_adjuntar."' AND FECHA LIKE '".$periodo_adjuntar."%' AND ACTIVIDAD<>''";
    //echo $sql;
  	$rs = odbc_exec( $conn, $sql );
   	if ( !$rs ) { 
  	    exit( "Error en la consulta SQL" ); 
  	} 	
	while ( odbc_fetch_row($rs) ) {  
	$resultado = odbc_result($rs, 'ACTIVIDAD');
	$options.= "<option value='".$resultado."'>".$resultado."</option>";
	$bandera = "Si";
    }//While       
	echo $bandera."*".$options;		
}

/*************************************************ENCADENAMIENTO SCT*************************************************/
if (isset($_POST['BuscarEnca'])){
	$tramoSCT = utf8_decode($_POST['tramoSCT']);
	
    $sql = "SELECT DISTINCT(ENCADENAMIENTO), AUTOPISTA, SUBTRAMO FROM CatTramos WHERE TRAMO='".$tramoSCT."'";
    //echo $sql;
  	$rs = odbc_exec( $conn, $sql );
   	if ( !$rs ) { 
  	    exit( "Error en la consulta SQL" ); 
  	} 	
	while ( odbc_fetch_row($rs) ) {  
	$enca = odbc_result($rs, 'ENCADENAMIENTO');
	$autopista = odbc_result($rs, 'AUTOPISTA');
	$cadenamiento = odbc_result($rs, 'SUBTRAMO');
    }//While       
	$options = $enca."*".$autopista."*".$cadenamiento;
	echo $options;		
}

/*************************************************CONTAR ACTIVIDAD POR TRAMO*************************************************/
if (isset($_POST['ContarActividad'])){
	$tramoAdjuntar = utf8_decode($_POST['tramoAdjuntar']);
	$actividad = utf8_decode($_POST['actividadAdjuntar']);
	
    $sql = "SELECT COUNT(*) TOTAL FROM AvanceDiario WHERE ACTIVIDAD='".$actividad."' AND TRAMO='".$tramoAdjuntar."' AND FECHA like '%".date("Y-m")."%'";
    //echo $sql;
  	$rs = odbc_exec( $conn, $sql );
   	if ( !$rs ) { 
  	    exit( "Error en la consulta SQL" ); 
  	} 	
	while ( odbc_fetch_row($rs) ) {  
		$options = odbc_result($rs, 'TOTAL');
    }//While       
	echo $options;		
}
/*************************************************ABREVIATURA DE LA ACTIVIDAD (SCT)*************************************************/
if (isset($_POST['BuscarAbrev'])){
	$actividad = utf8_decode($_POST['actividad']);
	
    $sql = "SELECT DISTINCT(Abreviatura) FROM CatConcepto WHERE DesCpt='".$actividad."'";
    //echo $sql;
  	$rs = odbc_exec( $conn, $sql );
   	if ( !$rs ) { 
  	    exit( "Error en la consulta SQL" ); 
  	} 	
	while ( odbc_fetch_row($rs) ) {  
		$options = odbc_result($rs, 'Abreviatura');
    }//While       
	echo $options;		
}
//-------------------------------OBTENEMOS EL TRAMO EN EL FILTRO DE AVANCE DIARIO------------------------------
if (isset($_POST['plaza'])){
	$valor = utf8_decode($_POST['plaza']);
	$valor2 = utf8_decode($_POST['id']);	
	
    $sql = "SELECT DISTINCT (TRAMO) FROM Accesos WHERE PLAZA = '".$valor."' AND USUARIO_ID = '".$valor2."'";
    //echo $sql;
  	$rs = odbc_exec( $conn, $sql );
   	if ( !$rs ) { 
  	    exit( "Error en la consulta SQL" ); 
  	} 	
	while ( odbc_fetch_row($rs) ) {  
	$resultado = odbc_result($rs, 'TRAMO');
	$options.= "<option value='".$resultado."'>".$resultado."</option>";
    }//While       
	echo $options;	
}
//---------------------------------OBTENEMOS EL SUBTRAMO EN EL FILTRO DE AVANCE DIARIO-------------------------
if (isset($_POST['tramo'])){
	$valor = utf8_decode($_POST['tramo']);
	$valor2 = utf8_decode($_POST['id2']);
	$valor3 = utf8_decode($_POST['plaza2']);
	
    $sql = "SELECT DISTINCT (SUBTRAMO) FROM Accesos WHERE PLAZA = '".$valor3."' and TRAMO = '".$valor."' AND USUARIO_ID = '".$valor2."'";
    //echo $sql;
  	$rs = odbc_exec( $conn, $sql );
   	if ( !$rs ) { 
  	    exit( "Error en la consulta SQL" ); 
  	} 	
	while ( odbc_fetch_row($rs) ) {  
	$resultado = odbc_result($rs, 'SUBTRAMO');
	$options.= "<option value='".$resultado."'>".$resultado."</option>";
    }//While       
	echo $options;	
}
//------------------------------OBTENEMOS LA SEMANA EN EL FILTRO DE AVANCE DIARIO ------------------------------
if (isset($_POST['subtramo'])){
	$valor = utf8_decode($_POST['subtramo']);
	$valor2 = utf8_decode($_POST['tramo2']);	
	$x=0;
		
    $sql = "SELECT DISTINCT (SEMANA), REGISTRO FROM AvanceDiario WHERE SUBTRAMO = '".$valor."' AND TRAMO = '".$valor2."' ORDER BY REGISTRO DESC";
    //echo $sql;
  	$rs = odbc_exec( $conn, $sql );
   	if ( !$rs ) { 
  	    exit( "Error en la consulta SQL" ); 
  	} 	
	while ( odbc_fetch_row($rs) ) {  
	$resultado = odbc_result($rs, 'SEMANA');
	if ($x==0){
		$options.= "<option value='".$resultado."'>".$resultado."</option>";
		$semana = $resultado;				
	}else{
		if($semana != $resultado){
			$options.= "<option value='".$resultado."'>".$resultado."</option>";
			$semana = $resultado;
		}
		
	}
	$x++;
    }//While       
	echo $options;	
	//echo $sql;
}
//---------------------------OBTENEMOS EL SUBTRAMO EN EL AVANCE DIARIO-----------------------------
if (isset($_POST['tramoAd'])){
	$valor = utf8_decode($_POST['tramoAd']);		
	
    $sql = "SELECT DISTINCT (SUBTRAMO), PLAZA FROM Accesos WHERE TRAMO = '".$valor."' AND USUARIO_ID = '".$_SESSION['S_UsuarioID']."' ORDER BY PLAZA";
    //echo $sql;
  	$rs = odbc_exec( $conn, $sql );
   	if ( !$rs ) { 
  	    exit( "Error en la consulta SQL" ); 
  	} 	
	while ( odbc_fetch_row($rs) ) {  
	$resultado = odbc_result($rs, 'SUBTRAMO');
	$options.= "<option value='".$resultado."'>".$resultado."</option>";
    }//While       
	echo $options;	
}
//---------------------------OBTENEMOS EL SUBTRAMO EN EL AVANCE DIARIO-----------------------------
if (isset($_POST['tramoPro'])){
	$valor = utf8_decode($_POST['tramoPro']);		
	
    $sql = "SELECT DISTINCT (SUBTRAMO) FROM CatTramos WHERE TRAMO = '".$valor."'";
    //echo $sql;
  	$rs = odbc_exec( $conn, $sql );
   	if ( !$rs ) { 
  	    exit( "Error en la consulta SQL" ); 
  	} 	
	while ( odbc_fetch_row($rs) ) {  
	$resultado = odbc_result($rs, 'SUBTRAMO');
	$options.= "<option value='".$resultado."'>".$resultado."</option>";
    }//While       
	echo $options;	
}
//---------------------------OBTENEMOS LA BASE EN EL AVANCE DIARIO-----------------------------
if (isset($_POST['buscarSub'])){
	$valor = utf8_decode($_POST['subtramoPro']);
	$valor2 = utf8_decode($_POST['tramoPro1']);		
	
    $sql = "SELECT DISTINCT (BASE) FROM CatTramos WHERE TRAMO = '".$valor2."' AND SUBTRAMO='".$valor."'";
    //echo $sql;
  	$rs = odbc_exec( $conn, $sql );
   	if ( !$rs ) { 
  	    exit( "Error en la consulta SQL" ); 
  	} 	
	while ( odbc_fetch_row($rs) ) {  
	$resultado = odbc_result($rs, 'BASE');
    }//While       
	echo $resultado;	
}
//---------------------------OBTENEMOS EL SUBTRAMO EN EL AVANCE DIARIO SI HUBO APOYO-----------------------------
if (isset($_POST['tramoApoyo'])){
	$valor = utf8_decode($_POST['tramoApoyo']);		
	
    $sql = "SELECT DISTINCT (SUBTRAMO), PLAZA FROM Accesos WHERE TRAMO = '".$valor."' ORDER BY PLAZA";
    //echo $sql;
  	$rs = odbc_exec( $conn, $sql );
   	if ( !$rs ) { 
  	    exit( "Error en la consulta SQL" ); 
  	} 	
	while ( odbc_fetch_row($rs) ) {  
	$resultado = odbc_result($rs, 'SUBTRAMO');
	$options.= "<option value='".$resultado."'>".$resultado."</option>";
    }//While       
	echo $options;	
}
//---------------------------OBTENEMOS EL SUBTRAMO EN EL AVANCE DIARIO SI NO HUBO APOYO-----------------------------
if (isset($_POST['TramoRegular'])){
	$valor = utf8_decode($_POST['TramoRegular']);		
	
    $sql = "SELECT DISTINCT (TRAMO) FROM Accesos WHERE USUARIO_ID = '".$_SESSION['S_UsuarioID']."'";
    //echo $sql;
  	$rs = odbc_exec( $conn, $sql );
   	if ( !$rs ) { 
  	    exit( "Error en la consulta SQL" ); 
  	} 	
	while ( odbc_fetch_row($rs) ) {  
	$resultado = odbc_result($rs, 'TRAMO');
	$options.= "<option value='".$resultado."'>".$resultado."</option>";
    }//While       
	echo $options;	
}
//----------------------------------OBTENEMOS EL KILOMETRAJE EN EL AVANCE DIARIO-----------------------
if (isset($_POST['subtramoAd'])){
	$valor = utf8_decode($_POST['subtramoAd']);
		
    $sql = "SELECT DISTINCT (SUBTRAMO), KM_INI, KM_FIN FROM Accesos WHERE SUBTRAMO = '".$valor."'";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta SQL" ); 
    } 	
	while ( odbc_fetch_row($rs) ) {               
	  $token1 = explode ("+", odbc_result($rs, 'KM_INI')); 
	  $token2 = explode ("+", odbc_result($rs, 'KM_FIN'));
	  //VALIDAMOS CUAL KILOMETRAJE ES MAYOR
	  if($token1[0] < $token2[0]){
		  $km1 = $token1[0];		  
		  $km2 = $token2[0];
	  }else{
		  $km1 = $token2[0];
		  $km2 = $token1[0];
	  }
	  echo $token1[0];	  	
	  for ($i = $km1; $i <= $km2; $i++) {		 
           $options.= "<option value=".$i.">".$i."</option>";
      }
    }//While       
	echo $options;			
}
//---------------------------OBTENEMOS LA INFORMACION DEL AVANCE PARA MULTIPUNTO-----------------------
if (isset($_POST['Folio'])){
	$valor = utf8_decode($_POST['Folio']);
		
    $sql = "SELECT * FROM AvanceDiario WHERE AVANCE_ID = '".$valor."' ";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta SQL" ); 
    } 	
	while ( odbc_fetch_row($rs) ) {         
      $tramo = odbc_result($rs, 'TRAMO');
	  $subtramo = odbc_result($rs, 'SUBTRAMO');
      
	  $options = $tramo."*".$subtramo;
	}//While 	
	echo $options;		
}
//--------------------------------------------BORRAMOS LA INFORMACION DE LA SEMANA--------------------------------------
if (isset($_POST['BorrarSemana'])){
	
	$semana = $_POST["Semana"];
		
   	$sql = "DELETE FROM Prenomina WHERE SEMANA = '".$semana."'";
    //echo $sql;
  	$rs = odbc_exec( $conn, $sql );
   	if ( !$rs ) { 
  	    echo "Error en la consulta Prenomina";
  	}
	
}

//--------------------------------------------GUARDAMOS LA INFORMACION DE LA PRENOMINA--------------------------------------
if (isset($_POST['GuardaBiometrico'])){
	
	$num = $_POST["Noemp"];
	$nombre = utf8_decode($_POST["Nombre"]);
	$dia1 = $_POST["Dia1"];
	$dia2 = $_POST["Dia2"];
	$dia3 = $_POST["Dia3"];
	$dia4 = $_POST["Dia4"];
	$dia5 = $_POST["Dia5"];
	$dia6 = $_POST["Dia6"];
	$dia7 = $_POST["Dia7"];
	$total = $_POST["Total"];
	$ext2 = $_POST["Ext2"];
	$ext3 = $_POST["Ext3"];
	$festivo = $_POST["Festivo"];
	$descanso = $_POST["Descanso"];
	$prima = $_POST["Prima"];
	$otro = $_POST["Otro"];
	$observaciones = $_POST["Observaciones"];
	$semana = $_POST["Semana"];
	$numsem = $_POST["Numsem"];
	$base = $_POST["Base"];
	
	$sql = "INSERT INTO Prenomina VALUES ('".$num."','".$nombre."','".$dia1."','".$dia2."','".$dia3."','".$dia4."','".$dia5."','".$dia6."','".$dia7."','".$total."','".$ext2."','".$ext3."','".$festivo."','".$descanso."','".$prima."','".$otro."','".$observaciones."','".$semana."','".$numsem."','".$base."')";
    //echo $sql;
  	$rs = odbc_exec( $conn, $sql );
   	if ( !$rs ) { 
  	    echo "Error";
  	}
}


/***********************************************CONSULTA DE ALMACEN****************************************/
if (isset($_POST['art1'])){
	$valor = utf8_decode($_POST['art1']);
	$valor3 = utf8_decode($_POST['Base']);
	
	$valor2 = str_replace("'","-",$valor);
	//echo $valor2;	
    $sql = "SELECT CvIns as Articulo, Descrip, Unid as Unidad, CASE WHEN SUM(Cant) = 0 THEN 0 ELSE ROUND((SUM(Importe)/SUM(Cant)), 2) END as Precio_Unitario, SUM(Cant) as Exist, SUM(ISNULL(Importe,0)) * SUM(Cant) as Importe, '' as Observaciones, '' as Presupuesto, 'Oracle' as Ubicacion_almacen FROM MovAlmac where Base IN ('".$valor3."') AND Importe > 0 AND CvIns LIKE '%".$valor2."%' GROUP BY CvIns, Descrip, Unid";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta Almacen" ); 
    } 	
	while ( odbc_fetch_row($rs) ) {         
      $desc = odbc_result($rs, 'Descrip');
	  $importe = odbc_result($rs, 'Precio_Unitario');
	  $existencia = odbc_result($rs, 'Exist');
	  //echo "Existencia: ".$existencia."nada";
	 /* if ($existencia == '0'){
		  
		  $options = 0;
	  }else{*/
      
	  $options = $desc."*".$importe;
	  //}
	}//While 	
	echo $options;		
}

/***********************************************CONSULTA DE ALMACEN****************************************/
if (isset($_POST['art'])){
	$valor = utf8_decode($_POST['art']);
	//$valor3 = utf8_decode($_POST['Base']);
	
	$valor2 = str_replace("'","-",$valor);
	//echo $valor2;	
    $sql = "SELECT * FROM Almacen WHERE Articulo = '".$valor2."'";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta Almacen" ); 
    } 	
	while ( odbc_fetch_row($rs) ) {         
      $desc = odbc_result($rs, 'Descrip');
	  $importe = odbc_result($rs, 'Precio_Unitario');
	  $existencia = odbc_result($rs, 'Exist');
	  //echo "Existencia: ".$existencia."nada";
	 /* if ($existencia == '0'){
		  
		  $options = 0;
	  }else{*/
      
	  $options = $desc."*".$importe;
	  //}
	}//While 	
	echo $options;		
}

//-----------------------------------------Folio de Salida
if (isset($_POST['FolioSalida'])){
	$valor = $_POST['Base'];
	
  	//VALIDAR SI EXISTE ALGUNA SALIDA DEL MISMO DIA Y MISMA BASE
	$sql = "SELECT * FROM Salidas WHERE BASE='".$valor."' AND FECHA='".date("Y-m-d")."'";   
  	//echo $sql;
  	$rs = odbc_exec( $conn, $sql );
  	if ( !$rs ) { 
    	exit( "Error en la consulta Salidas" ); 
  	} 
	while ( odbc_fetch_row($rs) ) { 
    	$resultado = odbc_result($rs, 'ID_SALIDA');	
  	}//While
	
	if($resultado != ''){
		
		echo $resultado;
		
	}else{
		
	  //SI NO EXISTE CREAMOS UN FOLIO	
	  $sql = "SELECT TOP 1 * FROM Salidas order by ID_SALIDA desc";   
	  //echo $sql;
	  $rs = odbc_exec( $conn, $sql );
	  if ( !$rs ) { 
		exit( "Error en la consulta Salidas" ); 
	  }     
	  while ( odbc_fetch_row($rs) ) { 
		$resultado = odbc_result($rs, 'ID_SALIDA');	
	  }//While   
	  $resultado = substr($resultado,4,4); 
	  $maximo = intval($resultado); 
	  $maximo++;
		
	  $longitud = strlen($maximo);
	  switch ($longitud) {
		case "1":
			$options = "SAL-000".$maximo."-".date("y");
			break;
		case "2":
			$options = "SAL-00".$maximo."-".date("y");
			break;
		case "3":
		   $options = "SAL-0".$maximo."-".date("y");
			break;
		case "4":
		   $options = "SAL-".$maximo."-".date("y");
			break;
	  }   
	  echo $options;
	}
  
}
/*************************************GUARDAR SALIDA*********************************************/
if (isset($_POST['GuardaSalida1'])){
	
	$folio = $_POST["FolioSa"];
	$base = $_POST["Base"];
	$concepto = utf8_decode($_POST["Concepto"]);
	$importe = $_POST["Importe"];
	$cantidad = $_POST["cantidad"];
	$periodo = date("Y").date("m");
	
	  $sql = "SELECT * FROM Almacen WHERE Descrip='".$concepto."'";   
	  //echo $sql;
	  $rs = odbc_exec( $conn, $sql );
	  if ( !$rs ) { 
		exit( "Error en la consulta Almacen" ); 
	  }     
	  while ( odbc_fetch_row($rs) ) { 
		$articulo = odbc_result($rs, 'Articulo');
		$unidad = odbc_result($rs, 'Unidad');	
		$precio = odbc_result($rs, 'Precio_Unitario');	
	  }//While 
	  
	  //$importe = $precio * $cantidad;
	
	$sql = "INSERT INTO Salidas VALUES ('".$folio."','".$articulo."','".$concepto."','".$unidad."','".$precio."','".$cantidad."','".$importe."','".$periodo."','ABIERTO','S','".date('Y-m-d')."','".$base."','','".$_SESSION['S_Usuario']."')";
    //echo $sql;
  	$rs = odbc_exec( $conn, $sql );
   	if ( !$rs ) { 
  	    exit( "Error en la consulta Salidas" ); 
  	}
	//echo "-----".$cantidad."*".$precio;
}

/*************************************GUARDAR SALIDA ORACLE*********************************************/
if (isset($_POST['GuardaSalida'])){
	
	$folio = $_POST["FolioSa"];
	$base = $_POST["Base"];
	$concepto = utf8_decode($_POST["Concepto"]);
	$importe = $_POST["Importe"];
	$cantidad = $_POST["cantidad"];
	$mostrar = $_POST["Mostrar"];
	$periodo = date("Y").date("m");
	
	
	if ($base == 'EN01'){
  		$sql = "SELECT CvIns as Articulo, Descrip, Unid as Unidad, CASE WHEN SUM(Cant) = 0 THEN 0 ELSE ROUND((SUM(Importe)/SUM(Cant)), 2) END as Precio_Unitario, SUM(Cant) as Exist, SUM(ISNULL(Importe,0)) * SUM(Cant) as Importe, '' as Observaciones, '' as Presupuesto, 'Oracle' as Ubicacion_almacen from MovAlmac where base IN ('USA01') AND Importe > 0 AND Descrip='".$concepto."' GROUP BY CvIns, Descrip, Unid";
	}else if($base == 'JA01'){
  		$sql = "SELECT CvIns as Articulo, Descrip, Unid as Unidad, CASE WHEN SUM(Cant) = 0 THEN 0 ELSE ROUND((SUM(Importe)/SUM(Cant)), 2) END as Precio_Unitario, SUM(Cant) as Exist, SUM(ISNULL(Importe,0)) * SUM(Cant) as Importe, '' as Observaciones, '' as Presupuesto, 'Oracle' as Ubicacion_almacen from MovAlmac where base IN ('TEPATITLAN') AND Importe > 0 AND Descrip='".$concepto."' GROUP BY CvIns, Descrip, Unid";
	}else{
  		$sql = "SELECT CvIns as Articulo, Descrip, Unid as Unidad, CASE WHEN SUM(Cant) = 0 THEN 0 ELSE ROUND((SUM(Importe)/SUM(Cant)), 2) END as Precio_Unitario, SUM(Cant) as Exist, SUM(ISNULL(Importe,0)) * SUM(Cant) as Importe, '' as Observaciones, '' as Presupuesto, 'Oracle' as Ubicacion_almacen from MovAlmac where base IN ('".$mostrar."') AND Importe > 0 AND Descrip='".$concepto."' GROUP BY CvIns, Descrip, Unid"; 
	}   
	  //echo $sql;
	  $rs = odbc_exec( $conn, $sql );
	  if ( !$rs ) { 
		exit( "Error en la consulta Almacen" ); 
	  }     
	  while ( odbc_fetch_row($rs) ) { 
		$articulo = odbc_result($rs, 'Articulo');
		$unidad = odbc_result($rs, 'Unidad');	
		$precio = odbc_result($rs, 'Precio_Unitario');	
		$exist = odbc_result($rs, 'Exist');		
	  }//While 
	  
	  //$importe = $precio * $cantidad;
	
	$sql = "INSERT INTO Salidas VALUES ('".$folio."','".$articulo."','".$concepto."','".$unidad."','".$precio."','".$cantidad."','".$importe."','".$periodo."','ABIERTO','S','".date('Y-m-d')."','".$base."','','".$_SESSION['S_Usuario']."')";
    //echo $sql;
  	$rs = odbc_exec( $conn, $sql );
   	if ( !$rs ) { 
  	    exit( "Error en la consulta Salidas" ); 
  	}
	
	$existencia = $exist - $cantidad;
	$import = $existencia * $precio;
	
	$art = explode("-", $articulo);
	
	if($art[0] == "IN"){
		$sql = "UPDATE Almacen SET Exist='".$existencia."', Importe='".$import."' WHERE Base='".$base."' AND Articulo='".$articulo."'";
		//echo $sql;
		$rs = odbc_exec( $conn, $sql );
		if ( !$rs ) { 
			exit( "Error en la consulta Salidas" ); 
		}	
	}
	
	//echo "-----".$cantidad."*".$precio;
}
/***********************************************CONSULTA DE ALMACEN****************************************/
if (isset($_POST['Salida'])){
	$valor = utf8_decode($_POST['Salida']);
	$cont = 0;
	//error_reporting(0);
	//echo $valor;
	$options.="<table width='803' style='font-family:comic sans'><tr bgcolor='#CBCBCB'><td width='244' align='center' height='20'><input type='hidden' name='folio_sal' id='folio_sal' value='".$valor."'><strong>Descripcion</strong></td><td width='49'><strong>Unidad</strong></td><td width='97' align='center'><strong>P.U.</strong></td><td width='75'><strong>Cantidad</strong></td><td width='114' align='center'><strong>Importe</strong></td><td width='90' align='center'><strong>Entrega</strong></td><td width='102'><strong>Fecha</strong></td></tr>";

    $sql = "SELECT DISTINCT(ARTICULO), DESCRIPCION, UNIDAD, PRECIO_UNITARIO, SUM(CANTIDAD) AS CANTIDAD, SUM(IMPORTE) AS IMPORTE, ESTATUS FROM Salidas WHERE ID_SALIDA = '".$valor."' GROUP BY ARTICULO,DESCRIPCION,UNIDAD,PRECIO_UNITARIO,ESTATUS";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta Salidas" ); 
    } 	    
	while ( odbc_fetch_row($rs) ) { 
	$estatus = odbc_result($rs, 'ESTATUS');
	 
	  $cont++;
	  $options.="<tr><td><input type='hidden' name='desc".$cont."' id='desc".$cont."' value='".odbc_result($rs, 'DESCRIPCION')."'>".odbc_result($rs, 'DESCRIPCION')."</td>";
	  $options.="<td align='center'><input type='hidden' name='unidad".$cont."' id='unidad".$cont."' value='".odbc_result($rs, 'UNIDAD')."'>".odbc_result($rs, 'UNIDAD')."</td>";
	  $options.="<td align='right'><input type='hidden' name='precio".$cont."' id='precio".$cont."' value='".odbc_result($rs, 'PRECIO_UNITARIO')."'>$".number_format(odbc_result($rs, 'PRECIO_UNITARIO'), 3, '.', ',')."</td>";
	  $options.="<td align='center'><input type='hidden' name='cantidad".$cont."' id='cantidad".$cont."' value='".odbc_result($rs, 'CANTIDAD')."'>".odbc_result($rs, 'CANTIDAD')."</td>";
	  $options.="<td align='right'><input type='hidden' name='importe".$cont."' id='importe".$cont."' value='".odbc_result($rs, 'IMPORTE')."'>$".number_format(odbc_result($rs, 'IMPORTE'), 3, '.', ',')."</td>";
	  if ($estatus == 'ABIERTO'){
		  $options.="<td align='center'><input type='number' min='0' max='".odbc_result($rs, 'CANTIDAD')."' value='0' style='width:50px' name='entrega".$cont."' id='entrega".$cont."' step='any'></td>";
	  }else{
		  $options.="<td align='center'></td>";
	  }
	  $options.="<td><input type='date' name='fecha".$cont."' id='fecha".$cont."' value='".date("Y-m-d")."'></td></tr>";
	  
	}//While
	$options.= "</table>"; 
	//$options.= "<br/>"; 
	$options.= "<div align='right' width='770'><input type='hidden' name='contador' id='contador' value='".$cont."'></div>"; 	
	echo $options;		
}

/*************************************GUARDAR ENTRADA*********************************************/

if (isset($_POST['GuardaEntrada'])){
	
	$folio = $_POST["Folio2"];
	$concepto = utf8_decode($_POST["Desc"]);
	$unidad = $_POST["Unidad"];
	$precio = $_POST["Precio"];
	$ent = $_POST["Entrega"];
	$base = $_POST["Base"];
	$fecha = $_POST["Fecha"];
	$periodo = date("Y").date("m");
	
	if ($base == 'EN01'){
  		$sql = "SELECT * FROM Almacen WHERE Descrip='".$concepto."' AND Base in ('USA01')";
	}else if($base == 'JA01'){
  		$sql = "SELECT * FROM Almacen WHERE Descrip='".$concepto."' AND Base in ('TE01')";
	}else{
  		$sql = "SELECT * FROM Almacen WHERE Descrip='".$concepto."' AND BASE='".$base."'"; 
	}
	
	
		 //$sql = "SELECT * FROM Almacen WHERE Descrip='".$concepto."' AND BASE='".$base."'";   
		  //echo $sql;
		  $rs = odbc_exec( $conn, $sql );
		  if ( !$rs ) { 
			exit( "Error en la consulta Almacen" ); 
		  }     
		  while ( odbc_fetch_row($rs) ) { 
			$articulo = odbc_result($rs, 'Articulo');
			$exist = odbc_result($rs, 'Exist');
			$precio = odbc_result($rs, 'Precio_Unitario');
		  }//While 
	
	if($ent == "0"){
		
		$sql = "UPDATE Salidas SET ESTATUS = 'CERRADO', FECHA='".$fecha."' WHERE ID_SALIDA='".$folio."' and ARTICULO = '".$articulo."'";
		//echo $sql;
		$rs = odbc_exec( $conn, $sql );
		if ( !$rs ) { 
			exit( "Error en la consulta Entradas" ); 
		}		 
	}else{
		  
		  $cantidad = $ent * -1;
		  $total = $precio * $cantidad;
		  
		$sql = "INSERT INTO Salidas VALUES ('".$folio."','".$articulo."','".$concepto."','".$unidad."','".$precio."','".$cantidad."','".$total."','".$periodo."','CERRADO','E','".$fecha."','".$base."','','".$_SESSION['S_Usuario']."')";
		//echo $sql;
		$rs = odbc_exec( $conn, $sql );
		if ( !$rs ) { 
			exit( "Error en la consulta Entradas" ); 
		}
		
		$sql = "UPDATE Salidas SET ESTATUS = 'CERRADO', FECHA = '".$fecha."' WHERE ID_SALIDA='".$folio."' and ARTICULO = '".$articulo."'";
		//echo $sql;
		$rs = odbc_exec( $conn, $sql );
		if ( !$rs ) { 
			exit( "Error en la consulta Entradas" ); 
		}
		
		$existencia = $exist + $ent;
		$import = $existencia * $precio;
		
		$art = explode("-", $articulo);
	
		if($art[0] == "IN"){
			$sql = "UPDATE Almacen SET Exist='".$existencia."', Importe='".$import."' WHERE Base='".$base."' AND Articulo='".$articulo."'";
			//echo $sql;
			$rs = odbc_exec( $conn, $sql );
			if ( !$rs ) { 
				exit( "Error en la consulta Salidas" ); 
			}	
		}
	}
}

/***********************************************BUSCAR SALIDAS****************************************/
if (isset($_POST['BuscarSalida2'])){
	$base = utf8_decode($_POST['Base']);
	$cont = 0;

	//BUSCAMOS LAS SALIDAS DEL DIA DE LA BASE

	$options .= "<div id='art'><span class='titulo'>#</span></div><div id='descripcion'><span class='titulo'>Descripcion</span></div><div id='cantidad'><span class='titulo'>Cantidad</span></div><div id='importe'><span class='titulo'>Importe</span></div>";
		
    $sql = "SELECT DISTINCT(DESCRIPCION), SUM(CANTIDAD) AS CANTIDAD, SUM(IMPORTE) AS IMPORTE FROM Salidas WHERE BASE = '".$base."' and FECHA = '".date("Y-m-d")."' GROUP BY ARTICULO,DESCRIPCION";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta Salidas" ); 
    }
	while ( odbc_fetch_row($rs) ) { 
	$cont++;
	$options.="<div class='titulo2'>".$cont."</div><div class='titulo1'><input type='hidden' name='descripcion".$cont."' id='descripcion".$cont."' value='".odbc_result($rs, 'DESCRIPCION')."'>".odbc_result($rs, 'DESCRIPCION')."</div><div class='titulo2' id='cantidad".$cont."'>".odbc_result($rs, 'CANTIDAD')."</div><div class='titulo3' id='importe".$cont."'><input type='hidden' name='importe".$cont."' id='importe".$cont."' value='".odbc_result($rs, 'IMPORTE')."'>$".number_format(odbc_result($rs, 'IMPORTE'),3,'.',',')."</div>";
	}//While
	
	$sql = "SELECT SUM(IMPORTE) AS IMPORTE FROM Salidas WHERE BASE = '".$base."' and FECHA = '".date("Y-m-d")."'";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta Salidas" ); 
    } 	    
	while ( odbc_fetch_row($rs) ) { 
	$total = odbc_result($rs, 'IMPORTE');
	}
	echo $options."*".$total."*".$cont;		
}

/***********************************************BUSCAR SALIDAS****************************************/
if (isset($_POST['BuscarTodas'])){
	$cont = 1;
	
	$options.="<div id='art'>#</div><div id='descripcion'>Folio de salida</div><div id='cantidad'>Base</div><div id='importe'>Total</div></div>";
	
    $sql = "SELECT DISTINCT(ID_SALIDA), BASE, SUM(IMPORTE) as IMPORTE FROM Salidas WHERE FECHA = '".date("Y-m-d")."' GROUP BY ID_SALIDA, BASE";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta Salidas" ); 
    } 	    
	while ( odbc_fetch_row($rs) ) { 
	$options.="<div class='titulo2'>".$cont."</div><div class='titulo1'>".odbc_result($rs, 'ID_SALIDA')."</div><div class='titulo2'>".odbc_result($rs, 'BASE')."</div><div class='titulo3'>$".number_format(odbc_result($rs, 'IMPORTE'),3,'.',',')."</div>";
	$cont ++;
	}//While
	
	$sql = "SELECT SUM(IMPORTE) AS IMPORTE FROM Salidas WHERE FECHA = '".date("Y-m-d")."'";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta Salidas" ); 
    } 	    
	while ( odbc_fetch_row($rs) ) { 
	$total = odbc_result($rs, 'IMPORTE');
	}
	echo $options."*".$total;		
}
/***********************************************CONSULTA DE ALMACEN****************************************/
if (isset($_POST['BuscarBase'])){
	$valor = utf8_decode($_POST['Tramo']);
	$valor2 = utf8_decode($_POST['Subtramo']);
	
    $sql = "SELECT * FROM CatTramos WHERE TRAMO = '".$valor."' AND SUBTRAMO = '".$valor2."'";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta CatTramos" ); 
    } 	
	while ( odbc_fetch_row($rs) ) {         
      $options = odbc_result($rs, 'BASE');
	}//While 	
	echo $options;
}
/***********************************************BUSCAR SALIDAS NUEVO AVANCE****************************************/
if (isset($_POST['BuscarSalidaAvance'])){
	$base = $_POST['Base'];
	$fecha = $_POST['Fecha'];
	$cont = 0;

	//BUSCAMOS LAS SALIDAS DEL DIA DE LA BASE
	$options .= "<strong>Salida de almacen</strong>";
	$options .= "<div style='padding:50px'>";
	$options .= "<div class='row' id='encabezado'><div class='col-md-1'><strong>#</strong></div><div class='col-md-7'><strong>Descripcion</strong></div><div class='col-md-1'><strong>Unidad</strong></div><div class='col-md-1'><strong>Disponible</strong></div><div class='col-md-2' align='right'><strong>Uso</strong></div></div>";
		
    $sql = "SELECT DISTINCT(DESCRIPCION), SUM(CANTIDAD) AS CANTIDAD, SUM(IMPORTE) AS IMPORTE, ARTICULO, ID_SALIDA, UNIDAD, PRECIO_UNITARIO, FECHA FROM Salidas WHERE BASE = '".$base."' AND FECHA='".$fecha."' GROUP BY ARTICULO,DESCRIPCION, UNIDAD, ID_SALIDA, PRECIO_UNITARIO, FECHA";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta Salidas" );
    }
	while ( odbc_fetch_row($rs) ) { 
	$cant = odbc_result($rs, 'CANTIDAD');
	$art = odbc_result($rs, 'ARTICULO');
	$desc = odbc_result($rs, 'DESCRIPCION');
	$folio = odbc_result($rs, 'ID_SALIDA');
	$unidad = odbc_result($rs, 'UNIDAD');
	$precio = odbc_result($rs, 'PRECIO_UNITARIO');
	$fecha = odbc_result($rs, 'FECHA');
	$cont++;
	$options.="<div class='row'><div class='col-md-1'>".$cont."</div><div class='col-md-7' align='left'>".$desc."<input type='hidden' id='fecha".$cont."' name='fecha".$cont."' value='".$fecha."'><input type='hidden' id='art".$cont."' name='art".$cont."' value='".$art."'><input type='hidden' id='folio".$cont."' name='folio".$cont."' value='".$folio."'><input type='hidden' id='desc".$cont."' name='desc".$cont."' value='".$desc."'><input type='hidden' id='unidad".$cont."' name='unidad".$cont."' value='".$unidad."'><input type='hidden' id='precio".$cont."' name='precio".$cont."' value='".$precio."'></div><div class='col-md-1'>".$unidad."</div><div class='col-md-1'>".number_format($cant, 3, '.', ',')."</div>";
	
		//VALIDACIÓN DE DISPONIBILIDAD DE INSUMOS
		if ($cant == '0'){
		$options.="<div class='col-md-2' align='right'><input type='number' id='usado".$cont."' name='usado".$cont."' max='".$cant."' class='form-control' min='0' value='0' readonly></div></div>";
		}else{
		$options.="<div class='col-md-2' align='right'><input type='number' id='usado".$cont."' name='usado".$cont."' max='".$cant."' class='form-control' min='0' value='0' step='any'></div></div>";		
		}
		
	}//While
	$options .= "<div/>";
	$options .= "<hr size='3'>";
	echo $options."*".$cont;
}

/***********************************************CONSULTA DE MAQUINARIA****************************************/
if (isset($_POST['noeco'])){
	$valor = utf8_decode($_POST['noeco']);
	$valor2 = utf8_decode($_POST['Base']);
	$folio = "";
	
	$sql = "SELECT * FROM SalidasMaq WHERE NO_ECO = '".$valor."' and FECHA = '".date("Y-m-d")."' AND BASE='".$valor2."'";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
		exit( "Error en la consulta CatMaquinaria" );
    }while ( odbc_fetch_row($rs) ) {
		$folio = odbc_result($rs, 'ID_SALIDAMAQ');
	}
	
	if ($folio != ""){
		
		echo $options = "Error";
		
	}else{
		
		$sql = "SELECT * FROM CatMaquinaria WHERE NoEco = '".$valor."'";
		//echo $sql;
		$rs = odbc_exec( $conn, $sql );
		if ( !$rs ) { 
		  exit( "Error en la consulta CatMaquinaria" ); 
		} 	
		while ( odbc_fetch_row($rs) ) {         
		  $options = odbc_result($rs, 'Descrip');
		  
		}//While 	
		echo $options;	
	}
}

/***********************************************BUSCAR SALIDAS****************************************/
if (isset($_POST['BuscarSalida3'])){
	$base = utf8_decode($_POST['Base']);
	$cont = 0;

	//BUSCAMOS LAS SALIDAS DEL DIA DE LA BASE

	$options .= "<div id='art'><span class='titulo'>#</span></div><div id='descripcion1'><span class='titulo'>Descripcion</span></div>";
		
    $sql = "SELECT DISTINCT(DESCRIPCION), NO_ECO FROM SalidasMaq WHERE BASE = '".$base."' AND FECHA ='".date("Y-m-d")."' GROUP BY NO_ECO,DESCRIPCION";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta Salidas" ); 
    }
	while ( odbc_fetch_row($rs) ) { 
	$cont++;
	$options.="<div class='titulo2'>".$cont."</div><div class='titulo1'><input type='hidden' name='descripcion1".$cont."' id='descripcion1".$cont."' value='".odbc_result($rs, 'DESCRIPCION')."'>".odbc_result($rs, 'DESCRIPCION')."</div>";
	}//While
	
	echo $options."*".$cont;		
}

//-----------------------------------------Folio de Salida
if (isset($_POST['FolioSalidaMaq'])){
	$valor = $_POST['Base'];
	
  	//VALIDAR SI EXISTE ALGUNA SALIDA DEL MISMO DIA Y MISMA BASE
	$sql = "SELECT * FROM SalidasMaq WHERE BASE='".$valor."' AND FECHA='".date("Y-m-d")."'";   
  	//echo $sql;
  	$rs = odbc_exec( $conn, $sql );
  	if ( !$rs ) { 
    	exit( "Error en la consulta Salidas" ); 
  	} 
	while ( odbc_fetch_row($rs) ) { 
    	$resultado = odbc_result($rs, 'ID_SALIDAMAQ');	
  	}//While
	
	if($resultado != ''){
		
		echo $resultado;
		
	}else{
		
	  //SI NO EXISTE CREAMOS UN FOLIO	
	  $sql = "SELECT TOP 1 * FROM SalidasMaq order by ID_SALIDAMAQ desc";   
	  //echo $sql;
	  $rs = odbc_exec( $conn, $sql );
	  if ( !$rs ) { 
		exit( "Error en la consulta Salidas" ); 
	  }     
	  while ( odbc_fetch_row($rs) ) { 
		$resultado = odbc_result($rs, 'ID_SALIDAMAQ');
	  }//While   
	  $resultado = substr($resultado,4,4); 
	  $maximo = intval($resultado); 
	  $maximo++;
		
	  $longitud = strlen($maximo);
	  switch ($longitud) {
		case "1":
			$options = "SAL-000".$maximo."-".date("y");
			break;
		case "2":
			$options = "SAL-00".$maximo."-".date("y");
			break;
		case "3":
		   $options = "SAL-0".$maximo."-".date("y");
			break;
		case "4":
		   $options = "SAL-".$maximo."-".date("y");
			break;
	  }   
	  echo $options;
	}
}
/*************************************GUARDAR SALIDA MAQUINARIA*********************************************/
if (isset($_POST['GuardaSalidaMaq'])){
	
	$folio = $_POST["FolioSa"];
	$base = $_POST["Base"];
	$maquinaria = utf8_decode($_POST["Maquinaria"]);
	$periodo = date("Y").date("m");
	
	  $sql = "SELECT * FROM CatMaquinaria WHERE Descrip='".$maquinaria."'";   
	  //echo $sql;
	  $rs = odbc_exec( $conn, $sql );
	  if ( !$rs ) { 
		exit( "Error en la consulta CatMaquinaria" ); 
	  }     
	  while ( odbc_fetch_row($rs) ) { 
		$noeco = odbc_result($rs, 'NoEco');	
	  }//While 
	
	$sql = "INSERT INTO SalidasMaq VALUES ('".$folio."','".$noeco."','".$maquinaria."','".$periodo."','ABIERTO','S','".date('Y-m-d')."','".$base."','".date("H:i:s")."','',0,'','".$_SESSION['S_Usuario']."')";
    //echo $sql;
  	$rs = odbc_exec( $conn, $sql );
   	if ( !$rs ) { 
  	    exit( "Error en la consulta SalidasMaq" ); 
  	}
}

/*************************************GUARDAR ENTRADA MAQUINARIA*********************************************/

if (isset($_POST['GuardaEntradaMaq'])){
	
	$folio = $_POST["Folio2"];
	$descripcion = utf8_decode($_POST["Desc"]);
	$hrini = $_POST["Hrini"];
	$hrfin = date("H:i:s");
	$noeco = $_POST["Noeco"];
	$base = $_POST["Base"];
	$total = $_POST["Total"];
	$fecha = $_POST["Fecha"];
	$periodo = date("Y").date("m");

	
		  //$total = ($hrini - $hrfin) * -1;
		  
		$sql = "UPDATE SalidasMaq SET FECHA = '".$fecha."', ESTATUS = 'ENTRADA', HR_FIN = '".$hrfin."', TIPO = 'E', TOTAL_HRS = '".$total."' WHERE ID_SALIDAMAQ='".$folio."' AND NO_ECO = '".$noeco."'";
		//echo $sql;
		$rs = odbc_exec( $conn, $sql );
		if ( !$rs ) { 
			exit( "Error en la consulta Entradas" ); 
		}	
}

/**********************************************BUSCAR SALIDAS DE MAQUINARIA****************************************/
if (isset($_POST['BuscarSalida'])){
	$base = $_POST['Base'];
	$fecha = $_POST['Fecha'];
	$cont = 0;

	//BUSCAMOS LAS SALIDAS DEL DIA DE LA BASE
	$options .= "<strong>Salida de maquinaria</strong>";
	$options .= "<div style='padding:50px'>";
	$options .= "<div class='row' id='encabezado'><div class='col-md-1'><strong>#</strong></div><div class='col-md-6'><strong>Descripcion</strong></div><div class='col-md-2'><strong>Total de Hrs</strong></div><div class='col-md-2'><strong>Hrs de Uso</strong></div></div>";
		
    $sql = "SELECT DISTINCT(NO_ECO), DESCRIPCION, FECHA, ID_SALIDAMAQ, HR_FIN, HR_INI, SUM(TOTAL_HRS) AS TOTAL_HRS FROM SalidasMaq WHERE BASE = '".$base."' AND FECHA='".$fecha."' GROUP BY NO_ECO,DESCRIPCION,ID_SALIDAMAQ,FECHA, HR_FIN,HR_INI";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta Salidas" );
    }
	while ( odbc_fetch_row($rs) ) {
		$noeco = odbc_result($rs, 'NO_ECO');
		$desc = odbc_result($rs, 'DESCRIPCION');
		$folio = odbc_result($rs, 'ID_SALIDAMAQ');
		$fecha = odbc_result($rs, 'FECHA');
		$hrs = odbc_result($rs, 'TOTAL_HRS');
		$hrini = odbc_result($rs, 'HR_INI');
		$hrfin = odbc_result($rs, 'HR_FIN');
	
		if ($hrs > 0){
			$cont++;
			$options.="<div class='row'><div class='col-md-1'>".$cont."</div><div class='col-md-6' align='left'>".$desc."<input type='hidden' id='fechamaq".$cont."' name='fechamaq".$cont."' value='".$fecha."'><input type='hidden' id='noeco".$cont."' name='noeco".$cont."' value='".$noeco."'><input type='hidden' id='foliomaq".$cont."' name='foliomaq".$cont."' value='".$folio."'><input type='hidden' id='hrini".$cont."' name='hrini".$cont."' value='".$hrini."'><input type='hidden' id='hrfin".$cont."' name='hrfin".$cont."' value='".$hrfin."'><input type='hidden' id='descripcion".$cont."' name='descripcion".$cont."' value='".$desc."'></div><div class='col-md-2'>".$hrs."</div><div class='col-md-2'><input type='number' id='hruso".$cont."' name='hruso".$cont."' class='form-control' min='0' max = '".$hrs."' value='0' step='any'></div></div>";
		}
	}//While
	$options .= "<div/>";
	echo $options."*".$cont;
}

/***********************************************CONSULTA DE ALMACEN MAQUINARIA****************************************/
if (isset($_POST['SalidaMaq'])){
	$valor = utf8_decode($_POST['SalidaMaq']);
	$cont = 0;
	//error_reporting(0);
	//echo $valor;
	$options.="<table width='740' style='font-family:comic sans'><tr bgcolor='#CBCBCB'><td width='112'><strong>No. Econ&oacute;mico</strong></td><td width='245' align='center' height='20'><input type='hidden' name='folio_sal' id='folio_sal' value='".$valor."'><strong>Descripcion</strong></td><td width='117' align='center'><strong>Hora Salida</strong></td><td width='135' align='center'><strong>Hora Entrada</strong></td><td width='116' align='center'><strong>Total Horas</strong></td><td align='center'><strong>Fecha</strong></td></tr>";

    $sql = "SELECT DISTINCT(NO_ECO), DESCRIPCION, HR_INI, ESTATUS FROM SalidasMaq WHERE ID_SALIDAMAQ = '".$valor."' GROUP BY NO_ECO,DESCRIPCION,HR_INI,ESTATUS";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta Salidas" ); 
    } 	    
	while ( odbc_fetch_row($rs) ) { 
	$estatus = odbc_result($rs, 'ESTATUS');
	$hrini = odbc_result($rs, 'HR_INI');
	$descripcion = odbc_result($rs, 'DESCRIPCION');
	$noeco = odbc_result($rs, 'NO_ECO');
	
	$total = ($hrini - date("H:i:s")) * -1;
	 
	  $cont++;
	  $options.="<tr><td align='center'><input type='hidden' name='noeco".$cont."' id='noeco".$cont."' value='".$noeco."'>".$noeco."</td>";
	  $options.="<td align='center'><input type='hidden' name='desc".$cont."' id='desc".$cont."' value='".$descripcion."'>".$descripcion."</td>";
	  $options.="<td align='center'><input type='hidden' name='hrini".$cont."' id='hrini".$cont."' value='".$hrini."'>".$hrini."</td>";
	  $options.="<td align='center'>".date("H:i:s")."</td>";	
	  $options.="<td align='center'><input type='number' id='total".$cont."' name='total".$cont."' style='width:50' value='".$total."'></td>";
	  $options.="<td align='center'><input type='date' id='fecha".$cont."' name='fecha".$cont."' value='".date("Y-m-d")."'></td></tr>";
	  
	}//While
	$options.= "</table>"; 
	//$options.= "<br/>"; 
	$options.= "<div align='right' width='770'><input type='hidden' name='contador' id='contador' value='".$cont."'></div>"; 	
	echo $options."*".$estatus;		
}

/**********************************************BUSCAR ALMACEN SAC****************************************/
if (isset($_POST['AlmacenSac'])){
	$desc = $_POST['Desc'];
		
    $sql = "SELECT * FROM Almacen WHERE Ubicacion_almacen = 'Sac' AND Descrip = '".$desc."'";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta Salidas" );
    }
	while ( odbc_fetch_row($rs) ) {
	$articulo = odbc_result($rs, 'Articulo');
	$unidad = odbc_result($rs, 'Unidad');
	$existencia = odbc_result($rs, 'Exist');
	$base = odbc_result($rs, 'Base');
	$precio = odbc_result($rs, 'Precio_Unitario');
	
	}//While
	
	$options = $articulo."*".$unidad."*".$existencia."*".$base."*".$precio;
	
	echo $options;
}
/*********************************OBTENEMOS LA CLASIFICACION DE LOS CONTRATOS***********************************/
if (isset($_POST['clasificacion'])){
	$valor = utf8_decode($_POST['clasificacion']);
	
    $sql = "SELECT DISTINCT (CONTRATO) FROM Contratos WHERE CLASIFICACION = '".$valor."'";
    //echo $sql;
  	$rs = odbc_exec( $conn, $sql );
   	if ( !$rs ) { 
  	    exit( "Error en la consulta SQL" ); 
  	} 	
	while ( odbc_fetch_row($rs) ) {  
	$resultado = odbc_result($rs, 'CONTRATO');
	$options.= "<option value='".$resultado."'>".$resultado."</option>";
    }//While       
	echo $options;	
}

/*****************************BUSCAR EL DETALLE DEL CONTRATO******************************/
if (isset($_POST['Detalle'])){
	$valor = utf8_decode($_POST['Id']);
		
    $sql = "SELECT * FROM Contratos WHERE CONTRATO_ID = '".$valor."'";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta SQL" ); 
    } 	
	while ( odbc_fetch_row($rs) ) {         
      $empresa = odbc_result($rs, 'EMPRESA');
	  $anticipo = odbc_result($rs, 'ANTICIPO');
      $fechainicio = odbc_result($rs, 'FECHA_INICIO');
	  $fechafin = odbc_result($rs, 'FECHA_FINAL');
      $tramo = odbc_result($rs, 'TRAMO');
      
	}//While 	
	  $options = $tramo."*".$empresa."*".$anticipo."*".$fechainicio."*".$fechafin;
	echo $options;		
}

/*****************************BUSCAR DETALLE PARA ESTIMACION******************************/
if (isset($_POST['Mostrar'])){
	$valor = utf8_decode($_POST['Clasificacion']);
	$valor2 = utf8_decode($_POST['contrato']);
	$estimacion = "";
	$monto = 0;
	$retencion = 0;
	$devolucion = 0;
	$factura = 0;
	$amortizacion = 0;
	$estatus = "";
	$cont = 0;
	$est_id = 0;
	
	$options.="<table width='874' height='41' border='0'><thead><tr><td width='96' align='center'>Monto Estimado</td><td width='99' align='center'>Retenci&oacute;n</td><td width='99' align='center'>Penalizaci&oacute;n</td><td width='118' align='center'>Devoluci&oacute;n</td><td width='121' align='center'>Amortizaci&oacute;n</td><td width='96' align='center'>Importe</td></tr></thead>";

    $sql = "SELECT * FROM Contratos WHERE CLASIFICACION = '".$valor."' AND CONTRATO = '".$valor2."'";
    //echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
      exit( "Error en la consulta Contratos" ); 
    } 	    
	while ( odbc_fetch_row($rs) ) { 
	$tramo = odbc_result($rs, 'TRAMO');
	$fechainicio = odbc_result($rs, 'FECHA_INICIO');
	$fechafin = odbc_result($rs, 'FECHA_FINAL');
	$importe = odbc_result($rs, 'MONTO');
	$anticipo = odbc_result($rs, 'ANTICIPO');
	$saldo = number_format(odbc_result($rs, 'SALDO_ANTICIPO'), 3, '.', ',');
	 
	  $cont++;
			  $options.="<tbody><tr><td align='left' colspan='3'><input type='hidden' name='estid".$cont."' id='estid".$cont."' value='".$est_id."'><input type='hidden' name='estimacion".$cont."' id='estimacion".$cont."'><input type='hidden' name='tramoest".$cont."' id='tramoest".$cont."' value='".$tramo."'><strong>".$tramo."</strong></td><td align='center'  style='color:#FF0004'>Monto por amortizar</td><td align='center' colspan='2'><strong><span style='color:#FF0004' id='amor_sug".$cont."' name='amor_sug".$cont."'></span></strong></td></tr>";
			  $options.="<tr><td align='center'><input type='number' name='montoest".$cont."' id='montoest".$cont."' class='form-control' value='0' onChange='Importe()' min='0' step='any'></td>";
			  $options.="<td align='center'><input type='number' name='retencion".$cont."' id='retencion".$cont."' class='form-control' value='0' onChange='Importe()' min='0' step='any'></td>";
			  $options.="<td align='center'><input type='number' name='penalizacion".$cont."' id='penalizacion".$cont."' class='form-control' value='0' onChange='Importe()' min='0' step='any'></td>";
			  $options.="<td align='center'><input type='number' name='devolucion".$cont."' id='devolucion".$cont."' class='form-control' value='0' onChange='Importe()' min='0' step='any'></td>";
			  $options.="<td align='center'><input type='number' name='amortizacion".$cont."' id='amortizacion".$cont."' class='form-control' value='0' onChange='CalAmort()' min='0' step='any'></td>";
			  $options.="<td align='center'><input type='number' name='importeest".$cont."' id='importeest".$cont."' value='0' min='0' class='form-control' readonly step='any'></td></tr></tbody>";
	  
	}//While
	$options.= "</table>"; 
	$options.= "<div align='right' width='770'><input type='hidden' name='contador' id='contador' value='".$cont."'></div>";
	
	echo $options."*".$anticipo."*".$saldo;		
}

/*************************************DETALLE DE ESTIMACION************************************/
if (isset($_POST['DetalleEstimacion'])){		
	$clasificacion = $_POST['Clasificacion'];
	$contrato = $_POST['Contrato'];
		
	$options.= "<div class=\"row\">";
	$options.= "<div class=\"col-md-12\" align=\"left\">";
	$options.= "<strong style=\"font-family:verdana; font-size:16px;\">Detalle de la estimaci&oacute;n</strong>";	 
	$options.= "</div>";	 
	$options.= "</div>";
		
	$sql = "SELECT DISTINCT(ESTIMACION),SUM(MONTO) AS MONTO FROM Estimaciones WHERE CLASIFICACION = '".$clasificacion."' AND CONTRATO = '".$contrato."' GROUP BY CONTRATO, ESTIMACION";
	//echo $sql;
	$rs = odbc_exec( $conn, $sql );
	if ( !$rs ) { 
		exit( "Error en la consulta Estimaciones" ); 
	}
	while ( odbc_fetch_row($rs) ) {				
		$estimacion = odbc_result($rs, 'ESTIMACION');
		$monto = odbc_result($rs, 'MONTO');
		
		$options.= "<div class=\"row\">";
		$options.= "<div class=\"col-md-1\" align=\"center\"></div>";
		$options.= "<div class=\"col-md-5\" align=\"center\" style=\"color:#575757\"><strong>No. Estimaci&oacute;n:</strong></div>";
		$options.= "<div class=\"col-md-5\" align=\"center\" style=\"color:#575757\"><strong>Monto:</strong></div>";
		$options.= "<div class=\"col-md-1\" align=\"center\"></div>";	 			
		$options.= "</div>";
		$options.= "<div class=\"row\">";
		$options.= "<div class=\"col-md-1\" align=\"center\"></div>";	 		
		$options.= "<div class=\"col-md-5\" align=\"center\">".$estimacion."</div>";
		$options.= "<div class=\"col-md-5\" align=\"right\">$".number_format($monto,3,'.',',')."</div>";
		$options.= "<div class=\"col-md-1\" align=\"center\"></div>";
		$options.= "</div>";		
	}
	echo $options;
}
/*************************************DETALLE DE ESTIMACION COMPLETO************************************/
if (isset($_POST['DetalleEstimacion2'])){		
	$clasificacion = $_POST['Clasificacion'];
	$contrato = utf8_decode($_POST['Contrato']);
	$resta = 0;
	$total1 = 0;
	$total2 = 0;
	$total3 = 0;
	$total4 = 0;
	$total5 = 0;
	$total6 = 0;
	$final = 0;
	$cancelar = 0;
	$estatus = "";
	$pagar = 0;
	$total = 0;
	$porejecutar = 0;
	$penalizacion = 0;
	$cont = 0;
	$options1 = "";
		
	$options1.= "<div class=\"row\">";
	$options1.= "<div class=\"col-md-12\" align=\"center\">";
	$options1.= "<strong style=\"font-family:verdana; font-size:16px;\">Detalle de la estimaci&oacute;n</strong>";	 
	$options1.= "</div>";	 
	$options1.= "</div>";
	$options1.="<table width='874' height='41' border='1'><thead><tr><td width='45' align='center'>Est</td><td width='120' align='center'>Tramo</td><td width='100' align='center'>Monto</td><td width='100' align='center'>Retenci&oacute;n</td><td width='100' align='center'>Penalizaci&oacute;n</td><td width='100' align='center'>Devoluci&oacute;n</td><td width='100' align='center'>Amortizaci&oacute;n</td><td width='108' align='center'>A pagar sin IVA</td><td width='104' align='center'>Factura</td></tr></thead>";

	$sql1 = "SELECT SUM(MONTO) AS MONTO, SUM(IMPORTE_CANCELAR) AS CANCELAR, ESTATUS FROM Contratos WHERE CLASIFICACION = '".$clasificacion."' AND CONTRATO = '".$contrato."' GROUP BY ESTATUS";
	//echo $sql1;
	$rs = odbc_exec( $conn, $sql1 );
	if ( !$rs ) {
		exit( "Error en la consulta Estimaciones" ); 
	}
	while ( odbc_fetch_row($rs) ) {				
		$total = odbc_result($rs, 'MONTO');	
		$cancelar = odbc_result($rs, 'CANCELAR');
		$estatus = odbc_result($rs, 'ESTATUS');
		
	}
	
	$resultado = "$".number_format($total,3,'.',',');
		
	$sql = "SELECT ESTIMACION_ID,ESTIMACION,TRAMO,MONTO,RETENCION,DEVOLUCION,AMORTIZACION,FACTURA,PENALIZACION FROM Estimaciones WHERE CLASIFICACION = '".$clasificacion."' AND CONTRATO = '".$contrato."'";
	//echo $sql;
	$rs = odbc_exec( $conn, $sql );
	if ( !$rs ) { 
		exit( "Error en la consulta Estimaciones" ); 
	}
	while ( odbc_fetch_row($rs) ) {				
		$estimacion = odbc_result($rs, 'ESTIMACION');
		$monto = odbc_result($rs, 'MONTO');	
		$estimacionId = odbc_result($rs, 'ESTIMACION_ID');
		$tramo = odbc_result($rs, 'TRAMO');	
		$retencion = odbc_result($rs, 'RETENCION');
		$devolucion = odbc_result($rs, 'DEVOLUCION');	
		$amortizacion = odbc_result($rs, 'AMORTIZACION');
		$factura = odbc_result($rs, 'FACTURA');
		$penalizacion = odbc_result($rs, 'PENALIZACION');
		
		$pagar = $monto - $retencion - $penalizacion + $devolucion - $amortizacion;
		$total1 += $monto;
		$total2 += $retencion;
		$total6 += $penalizacion;
		$total3 += $devolucion;
		$total4 += $amortizacion;
		$total5 += $pagar;
		
		$cont++;
		$options.="<tbody><tr><td align='center'><input type='hidden' id='id_estimacion".$cont."' name='id_estimacion".$cont."' value='".$estimacionId."'>".$estimacion."</td>";
		$options.="<td align='left'>".$tramo."</td>";
		$options.="<td align='right'>$".number_format($monto,3,'.',',')."</td>";
		$options.="<td align='right'>$".number_format($retencion,3,'.',',')."</td>";
		$options.="<td align='right'>$".number_format($penalizacion,3,'.',',')."</td>";
		$options.="<td align='right'>$".number_format($devolucion,3,'.',',')."</td>";
		$options.="<td align='right'>$".number_format($amortizacion,3,'.',',')."</td>";
		$options.="<td align='right'>$".number_format($pagar,3,'.',',')."</td>";
		$options.="<td align='center'><input type='text' id='factura_est".$cont."' name='factura_est".$cont."' class='form-control' value='".$factura."'></td></tr></tbody>";
				
	}
	$options1.="<tr><td align='center' colspan='2'><strong>Totales</strong></td>";
	$options1.="<td align='right'><strong>$".number_format($total1,3,'.',',')."</strong></td>";
	$options1.="<td align='right'><strong>$".number_format($total2,3,'.',',')."</strong></td>";
	$options1.="<td align='right'><strong>$".number_format($total6,3,'.',',')."</strong></td>";
	$options1.="<td align='right'><strong>$".number_format($total3,3,'.',',')."</strong></td>";
	$options1.="<td align='right'><strong>$".number_format($total4,3,'.',',')."</strong></td>";
	$options1.="<td align='right'><strong>$".number_format($total5,3,'.',',')."</strong></td>";
	$options1.="<td align='center'></td></tr></tbody>";	
	$options.= "</table>";
	$options.= "<div align='right' width='770'><input type='hidden' name='contadorest' id='contadorest' value='".$cont."'></div>";
	$resta = $total - $total5 - $total6 - $cancelar;
	//$resta = $resta;
	//$penalizacion += $penalizacion;
	$porejecutar = "$".number_format($resta,3,'.',',');
	$penalizacion = "$".number_format($total6,3,'.',',');
	$cancelar = "$".number_format($cancelar,3,'.',',');
    echo $options1.$options."*".$resultado."*".$porejecutar."*".$penalizacion."*".$estatus."*".$cancelar."*".$resta;
	//echo $total;
}

/*************************************************SUBCUENTA*************************************************/
if (isset($_POST['BuscarSubcuenta'])){
	$valor = utf8_decode($_POST['subcuenta']);
	
    $sql = "SELECT DISTINCT (CONCEPTO) FROM CatConceptoCapex WHERE SUBCUENTA = '".$valor."'";
    //echo $sql;
  	$rs = odbc_exec( $conn, $sql );
   	if ( !$rs ) { 
  	    exit( "Error en la consulta SQL" ); 
  	} 	
	while ( odbc_fetch_row($rs) ) {  
	$resultado = odbc_result($rs, 'CONCEPTO');
	$options.= "<option value='".$resultado."'>".$resultado."</option>";
    }//While       
	echo $options;	
}

/*************************************************SUBCUENTA*************************************************/
if (isset($_POST['BuscarConcepto'])){
	$valor = utf8_decode($_POST['concepto']);
	
    $sql = "SELECT DISTINCT (UNIDAD) FROM CatConceptoCapex WHERE CONCEPTO = '".$valor."'";
    //echo $sql;
  	$rs = odbc_exec( $conn, $sql );
   	if ( !$rs ) { 
  	    exit( "Error en la consulta SQL" ); 
  	} 	
	while ( odbc_fetch_row($rs) ) {  
	$options = odbc_result($rs, 'UNIDAD');
	
    }//While       
	echo $options;	
}

/*************************************************PRESUPUESTO ID*************************************************/
if (isset($_POST['BuscarId'])){
	$valor = utf8_decode($_POST['id']);
	
    $sql = "SELECT DISTINCT (PRESUPUESTO_ID) FROM Presupuesto WHERE DESCRIPCION = '".$valor."'";
    //echo $sql;
  	$rs = odbc_exec( $conn, $sql );
   	if ( !$rs ) { 
  	    exit( "Error en la consulta SQL" ); 
  	} 	
	while ( odbc_fetch_row($rs) ) {  
	$options = odbc_result($rs, 'PRESUPUESTO_ID');
	
    }//While       
	echo $options;	
}
/***************************************************GUARDAR ESTIMACION**************************************************/	
if (isset($_POST['GuardarEstimacion'])) {
	$clasificacionest = $_POST["clasificacionest"];
	$contratoest = utf8_decode($_POST["contratoest"]);
	$fechainicioest = $_POST["fechainicioest"];
	$fechafinest = $_POST["fechafinest"];
	$estimacion = $_POST["no_estimacion"];
	$radiobutton = $_POST["detalleInfo"];
	$factura = $_POST["factura"];
	
	$montoest = $_POST["montoest"]; 
	$tramoest = $_POST["tramoest"];
	$retencion = $_POST["retencion"]; 
	$devolucion = $_POST["devolucion"];
	$amortizacion = $_POST["amortizacion"];
	$penalizacion = $_POST["penalizacion"];
	$estid = $_POST["estid"];
	
	//if($montoest  != '0'){
		//echo "Lleno";
		$sql = "INSERT INTO Estimaciones VALUES('".$clasificacionest."','".$contratoest."','".$tramoest."','".$estimacion."','".$montoest."','".$fechainicioest."','".$fechafinest."','".$retencion."','".$devolucion."','".$factura."','".$amortizacion."','".$radiobutton."','".$penalizacion."')";
		echo $sql;
		$rs = odbc_exec( $conn, $sql );
		if ( !$rs ) {
			exit( "Error en la consulta SQL" );  
		}  
	//}
}
?>