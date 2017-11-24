<?php

/** PHPExcel */
include ('../PHPExcel/Classes/PHPExcel.php');
include ('../PHPExcel/Classes/PHPExcel/IOFactory.php');

 // Se define la cadena de conexión  
 $dsn = "Driver={SQL Server};

 Server=192.168.130.129;Database=SAC;Integrated Security=SSPI;Persist Security Info=False;";
 date_default_timezone_set('America/Mexico_City');
 header( 'Content-type: text/html; charset=iso-8859-1' );

// Se realiza la conexón con los datos especificados anteriormente
 $conn = odbc_connect( $dsn, 'sa', 'S1st3m45' );
 if (!$conn) { 
 exit( "Error al conectar1: " . $conn);
 }
 
  // Se define la cadena de conexión  
 $dsn2 = "Driver={SQL Server};
 
 Server=192.168.130.129;Database=Sac;Integrated Security=SSPI;Persist Security Info=False;";
  
 // Se realiza la conexón con los datos especificados anteriormente
 $conn2 = odbc_connect( $dsn2, 'sa', 'S1st3m45' );
 if (!$conn2) { 
 exit( "Error al conectar2: " . $conn2);
 }  
 
if (!isset($_SESSION)) {
  session_start();
} 
/** Error reporting */
error_reporting(E_ALL);

//Creamos un Documento nuevo
$objPHPExcel = new PHPExcel();
$objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
//Cargamos un documentogg
$objPHPExcel = PHPExcel_IOFactory::load("../sac/xls/SCT.xlsx");

//Propiedades del documento
$objPHPExcel->getProperties()->setCreator("SAC") // creador del documento
							 ->setLastModifiedBy("SAC")  //ultima modificacion
							 ->setTitle("SCT MANTTO MENOR") // titulo del doc
							 ->setSubject("SCT MANTTO MENOR")
							 ->setDescription("SCT MANTTO MENOR")//descripcion
							 ->setKeywords("SCT MANTTO MENOR")//palabras clave
							 ->setCategory("SCT MANTTO MENOR");// categoriau

//print_r($_POST);
 if (isset($_POST['generar'])){	
 	$tramo = $_POST['autoSCT'];
 	$encadenamiento = $_POST['encaSCT'];
 	$periodo = $_POST['periodoSCT'];
 	$subtramo = $_POST['tramoSCT'];
 	$cadenamiento = $_POST['cadeSCT'];
 }else{	
	$subtramo = "Zapotlanejo - Guadalajara";
	$encadenamiento = "Del 0+000 al 26+000";
	$cadenamiento = "Del 0 al 26";
	$periodo = "2017-05";
	$tramo = "ZAPOTLANEJO - GUADALAJARA";
 }

$actividad2 = "";
$letra = 73;
$letra3 = 65;
$cantidadTotal = 0;
$totalGral = 0;


$split = explode('-', $periodo);
$year = $split[0];
$month = $split[1];	
	
 switch($month){
 	case "01":
 		$mes = "ENERO";
 		$day = "31";
 		$fecha = "DEL 01 AL 31 DE ENERO DE ";
 	break;
 	case "02":
 		$mes = "Febrero";
 		if($year % 4 == 0 and $year % 100 != 0  or $year % 400 == 0){
 			$day = "29";
 			$fecha = "DEL 01 AL 29 DE FEBRERO DE ";
 		}else{
 			$day = "28";
 			$fecha = "DEL 01 AL 28 DE FEBRERO DE ";
 		}
 	break;
 	case "03":
 		$mes = "MARZO";
 		$day = "31";
 		$fecha = "DEL 01 AL 31 DE MARZO DE ";
 	break;
 	case "04":
 		$mes = "ABRIL";
 		$day = "30";
 		$fecha = "DEL 01 AL 30 DE ABRIL DE ";
 	break;
 	case "05":
 		$mes = "MAYO";
 		$day = "31";
 		$fecha = "DEL 01 AL 31 DE MAYO DE ";
 	break;
 	case "06":
 		$mes = "JUNIO";
 		$day = "30";
 		$fecha = "DEL 01 AL 30 DE JUNIO DE ";
 	break;
 	case "07":
 		$mes = "JULIO";
 		$day = "31";
 		$fecha = "DEL 01 AL 31 DE JULIO DE ";
 	break;
 	case "08":
 		$mes = "AGOSTO";
 		$day = "31";
 		$fecha = "DEL 01 AL 31 DE AGOSTO DE ";
 	break;
 	case "09":
 		$mes = "SEPTIEMBRE";
 		$day = "30";
 		$fecha = "DEL 01 AL 30 DE SEPTIEMBRE DE ";
 	break;
 	case "10":
 		$mes = "OCTUBRE";
 		$day = "31";
 		$fecha = "DEL 01 AL 31 DE OCTUBRE DE ";
 	break;
 	case "11":
 		$mes = "NOVIEMBRE";
 		$day = "30";
 		$fecha = "DEL 01 AL 30 DE NOVIEMBRE DE ";
 	break;
 	case "12":
 		$mes = "DICIEMBRE";
 		$day = "31";
 		$fecha = "DEL 01 AL 31 DE DICIEMBRE DE ";
 	break;
 }

	$objPHPExcel->setActiveSheetIndex(2)
		->setCellValue('C1', utf8_encode($tramo."\n SUBTRAMO ".$subtramo."\n KM ".$encadenamiento));
	$objPHPExcel->setActiveSheetIndex(3)
		->setCellValue('C1', utf8_encode($tramo."\n SUBTRAMO ".$subtramo."\n KM ".$encadenamiento));
	$objPHPExcel->setActiveSheetIndex(4)
		->setCellValue('C1', utf8_encode($tramo."\n SUBTRAMO ".$subtramo."\n KM ".$encadenamiento));
	$objPHPExcel->setActiveSheetIndex(5)
		->setCellValue('C3', utf8_encode("Base: ".$subtramo));	
	$objPHPExcel->setActiveSheetIndex(6)
		->setCellValue('B4', utf8_encode("Subtramo: ".$subtramo));	
			
				//HOJA 3 (EJECUTADO)
				$objPHPExcel->setActiveSheetIndex(3);
				for ($i=4; $i < 97; $i++) {
					
					$clave = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getValue();
					$letra = 72;
					$letra2 = "65";
					
					$valida = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getValue();
					if (trim($valida) == "") {
						continue;						
					}
					
					for ($j=1; $j <= 12 ; $j++) { 
						$precio = 0;	
						$cantidad = 0;
						$total = 0;	
						
						if($j < 10){
							$j = "0".$j;
						}					

						$sql2 = "SELECT ISNULL(SUM(AvanceDiario.CANTIDAD), 0) CANTIDAD, PrecioSCT.UNIDAD, PrecioSCT.PRECIO_UNITARIO FROM AvanceDiario INNER JOIN PrecioSCT ON AvanceDiario.ACTIVIDAD = PrecioSCT.CONCEPTO WHERE PrecioSCT.CLAVE_SCT = '".trim($clave)."' AND YEAR(FECHA) = '".$year."' AND MONTH(FECHA) = '".$j."' AND TRAMO='".$tramo."' GROUP BY PrecioSCT.UNIDAD, PrecioSCT.PRECIO_UNITARIO";
						$rs2 = odbc_exec( $conn2, $sql2);
						if ( !$rs2 ) { 
							exit( "Error en la consulta SQL" );
						}
						while ( odbc_fetch_row($rs2) ) {  
							$cantidad = odbc_result($rs2, 'CANTIDAD');	
							$precio = odbc_result($rs2, 'PRECIO_UNITARIO');	
							$total = $cantidad * $precio;						
						}

						if ($letra <= 89) {
								
							$objPHPExcel->setActiveSheetIndex(3)
								->setCellValue(chr($letra).$i, $cantidad)
								->setCellValue(chr($letra + 1).$i, $total)
								//
								->setCellValue('F'.$i, '=H'.$i.'+J'.$i.'+L'.$i.'+N'.$i.'+P'.$i.'+R'.$i.'+T'.$i.'+V'.$i.'+X'.$i.'+Z'.$i.'+AB'.$i.'+AD'.$i)
								->setCellValue(chr($letra + 1).'96', '='.chr($letra + 1).'73+'.chr($letra + 1).'65+'.chr($letra + 1).'51+'.chr($letra + 1).'29+'.chr($letra + 1).'18+'.chr($letra + 1).'4')
								->setCellValue('G'.$i, '=F'.$i.'*E'.$i)
								->setCellValue('G4', '=SUM(G5,G10,G13)')
								->setCellValue('G5', '=SUM(G6:G9)')
								->setCellValue('G10', '=SUM(G11:G12)')
								->setCellValue('G13', '=SUM(G14:G17)')
								->setCellValue('G18', '=SUM(G19)')
								->setCellValue('G19', '=SUM(G20:G28)')
								->setCellValue('G29', '=SUM(G30,G32,G36,G42,G49)')
								->setCellValue('G30', '=SUM(G31)')
								->setCellValue('G32', '=SUM(G33:G35)')
								->setCellValue('G36', '=SUM(G37:G41)')
								->setCellValue('G42', '=SUM(G43:G48)')
								->setCellValue('G49', '=SUM(G50)')
								->setCellValue('G51', '=SUM(G52)')
								->setCellValue('G52', '=SUM(G53:G64)')
								->setCellValue('G65', '=SUM(G66)')
								->setCellValue('G66', '=SUM(G67:G72)')
								->setCellValue('G73', '=SUM(G74,G84)')
								->setCellValue('G74', '=SUM(G75:G83)')
								->setCellValue('G84', '=SUM(G85:G95)')
								->setCellValue('G96', '=G4+G18+G29+G51+G65+G73')
								->setCellValue('I98', '=I96')
								->setCellValue('K98', '=I98+K96')
								->setCellValue('M98', '=M96+K98')
								->setCellValue('O98', '=M98+O96')
								->setCellValue('Q98', '=Q96+O98')
								->setCellValue('S98', '=Q98+S96')
								->setCellValue('U98', '=U96+S98')
								->setCellValue('W98', '=U98+W96')
								->setCellValue('Y98', '=Y96+W98')
								->setCellValue('AA98', '=Y98+AA96')
								->setCellValue('AC98', '=AC96+AA98')
								->setCellValue('AE98', '=AC98+AE96')
								//1
								->setCellValue(chr($letra + 1).'4', '=SUM('.chr($letra + 1).'5,'.chr($letra + 1).'10,'.chr($letra + 1).'13)')
								->setCellValue(chr($letra + 1).'5', '=SUM('.chr($letra + 1).'6:'.chr($letra + 1).'9)')
								->setCellValue(chr($letra + 1).'10', '=SUM('.chr($letra + 1).'11:'.chr($letra + 1).'12)')
								->setCellValue(chr($letra + 1).'13', '=SUM('.chr($letra + 1).'14:'.chr($letra + 1).'17)')
								//2
								->setCellValue(chr($letra + 1).'18', '=SUM('.chr($letra + 1).'19)')
								->setCellValue(chr($letra + 1).'19', '=SUM('.chr($letra + 1).'20:'.chr($letra + 1).'28)')
								//3
								->setCellValue(chr($letra + 1).'29', '=SUM('.chr($letra + 1).'30,'.chr($letra + 1).'32,'.chr($letra + 1).'36,'.chr($letra + 1).'42,'.chr($letra + 1).'49)')
								->setCellValue(chr($letra + 1).'30', '=SUM('.chr($letra + 1).'31)')
								->setCellValue(chr($letra + 1).'32', '=SUM('.chr($letra + 1).'33:'.chr($letra + 1).'35)')
								->setCellValue(chr($letra + 1).'36', '=SUM('.chr($letra + 1).'37:'.chr($letra + 1).'41)')
								->setCellValue(chr($letra + 1).'42', '=SUM('.chr($letra + 1).'43:'.chr($letra + 1).'48)')
								->setCellValue(chr($letra + 1).'49', '=SUM('.chr($letra + 1).'50)')
								//4
								->setCellValue(chr($letra + 1).'51', '=SUM('.chr($letra + 1).'52)')
								->setCellValue(chr($letra + 1).'52', '=SUM('.chr($letra + 1).'53:'.chr($letra + 1).'64)')
								//5
								->setCellValue(chr($letra + 1).'65', '=SUM('.chr($letra + 1).'66)')
								->setCellValue(chr($letra + 1).'66', '=SUM('.chr($letra + 1).'67:'.chr($letra + 1).'72)')
								//6
								->setCellValue(chr($letra + 1).'73', '=SUM('.chr($letra + 1).'74,'.chr($letra + 1).'84)')
								->setCellValue(chr($letra + 1).'74', '=SUM('.chr($letra + 1).'75:'.chr($letra + 1).'83)')
								->setCellValue(chr($letra + 1).'84', '=SUM('.chr($letra + 1).'85:'.chr($letra + 1).'95)');
								
						}elseif ($letra == 90) {														
							$objPHPExcel->setActiveSheetIndex(3)								
								->setCellValue(chr(90).$i, $cantidad)
								->setCellValue('A'.chr($letra2).$i, $total)
								->setCellValue('A'.chr($letra2).'96', '=A'.chr($letra2).'73+A'.chr($letra2).'65+A'.chr($letra2).'51+A'.chr($letra2).'29+A'.chr($letra2).'18+A'.chr($letra2).'4')
								//1
								->setCellValue('A'.chr($letra2).'4', '=SUM(A'.chr($letra2).'5, A'.chr($letra2).'10, A'.chr($letra2).'13)')
								->setCellValue('A'.chr($letra2).'5', '=SUM(A'.chr($letra2).'6:A'.chr($letra2).'9)')
								->setCellValue('A'.chr($letra2).'10', '=SUM(A'.chr($letra2).'11:A'.chr($letra2).'12)')
								->setCellValue('A'.chr($letra2).'13', '=SUM(A'.chr($letra2).'14:A'.chr($letra2).'17)')
								//2
								->setCellValue('A'.chr($letra2).'18', '=SUM(A'.chr($letra2).'19)')
								->setCellValue('A'.chr($letra2).'19', '=SUM(A'.chr($letra2).'20:A'.chr($letra2).'28)')
								//3
								->setCellValue('A'.chr($letra2).'29', '=SUM(A'.chr($letra2).'30,A'.chr($letra2).'32,A'.chr($letra2).'36,A'.chr($letra2).'42,A'.chr($letra2).'49)')
								->setCellValue('A'.chr($letra2).'30', '=SUM(A'.chr($letra2).'31)')
								->setCellValue('A'.chr($letra2).'32', '=SUM(A'.chr($letra2).'33:A'.chr($letra2).'35)')
								->setCellValue('A'.chr($letra2).'36', '=SUM(A'.chr($letra2).'37:A'.chr($letra2).'41)')
								->setCellValue('A'.chr($letra2).'42', '=SUM(A'.chr($letra2).'43:A'.chr($letra2).'48)')
								->setCellValue('A'.chr($letra2).'49', '=SUM(A'.chr($letra2).'50)')
								//4
								->setCellValue('A'.chr($letra2).'51', '=SUM(A'.chr($letra2).'52)')
								->setCellValue('A'.chr($letra2).'52', '=SUM(A'.chr($letra2).'53:A'.chr($letra2).'64)')
								//5
								->setCellValue('A'.chr($letra2).'65', '=SUM(A'.chr($letra2).'66)')
								->setCellValue('A'.chr($letra2).'66', '=SUM(A'.chr($letra2).'67:A'.chr($letra2).'72)')
								//6
								->setCellValue('A'.chr($letra2).'73', '=SUM(A'.chr($letra2).'74,A'.chr($letra2).'84)')
								->setCellValue('A'.chr($letra2).'74', '=SUM(A'.chr($letra2).'75:A'.chr($letra2).'83)')
								->setCellValue('A'.chr($letra2).'84', '=SUM(A'.chr($letra2).'85:A'.chr($letra2).'95)');
								$letra2++;
						}else{
							$objPHPExcel->setActiveSheetIndex(3)								
								->setCellValue('A'.chr($letra2).$i, $cantidad)
								->setCellValue('A'.chr($letra2 + 1).$i, $total)
								->setCellValue('A'.chr($letra2 + 1).'96', '=A'.chr($letra2 + 1).'73+A'.chr($letra2 + 1).'65+A'.chr($letra2 + 1).'51+A'.chr($letra2 + 1).'29+A'.chr($letra2 + 1).'18+A'.chr($letra2 + 1).'4')
								//1
								->setCellValue('A'.chr($letra2 + 1).'4', '=SUM(A'.chr($letra2 + 1).'5, A'.chr($letra2 + 1).'10, A'.chr($letra2 + 1).'13)')
								->setCellValue('A'.chr($letra2 + 1).'5', '=SUM(A'.chr($letra2 + 1).'6:A'.chr($letra2 + 1).'9)')
								->setCellValue('A'.chr($letra2 + 1).'10', '=SUM(A'.chr($letra2 + 1).'11:A'.chr($letra2 + 1).'12)')
								->setCellValue('A'.chr($letra2 + 1).'13', '=SUM(A'.chr($letra2 + 1).'14:A'.chr($letra2 + 1).'17)')
								//2
								->setCellValue('A'.chr($letra2 + 1).'18', '=SUM(A'.chr($letra2 + 1).'19)')
								->setCellValue('A'.chr($letra2 + 1).'19', '=SUM(A'.chr($letra2 + 1).'20:A'.chr($letra2 + 1).'28)')
								//3
								->setCellValue('A'.chr($letra2 + 1).'29', '=SUM(A'.chr($letra2 + 1).'30,A'.chr($letra2 + 1).'32,A'.chr($letra2 + 1).'36,A'.chr($letra2 + 1).'42,A'.chr($letra2 + 1).'49)')
								->setCellValue('A'.chr($letra2 + 1).'30', '=SUM(A'.chr($letra2 + 1).'31)')
								->setCellValue('A'.chr($letra2 + 1).'32', '=SUM(A'.chr($letra2 + 1).'33:A'.chr($letra2 + 1).'35)')
								->setCellValue('A'.chr($letra2 + 1).'36', '=SUM(A'.chr($letra2 + 1).'37:A'.chr($letra2 + 1).'41)')
								->setCellValue('A'.chr($letra2 + 1).'42', '=SUM(A'.chr($letra2 + 1).'43:A'.chr($letra2 + 1).'48)')
								->setCellValue('A'.chr($letra2 + 1).'49', '=SUM(A'.chr($letra2 + 1).'50)')
								//4
								->setCellValue('A'.chr($letra2 + 1).'51', '=SUM(A'.chr($letra2 + 1).'52)')
								->setCellValue('A'.chr($letra2 + 1).'52', '=SUM(A'.chr($letra2 + 1).'53:A'.chr($letra2 + 1).'64)')
								//5
								->setCellValue('A'.chr($letra2 + 1).'65', '=SUM(A'.chr($letra2 + 1).'66)')
								->setCellValue('A'.chr($letra2 + 1).'66', '=SUM(A'.chr($letra2 + 1).'67:A'.chr($letra2 + 1).'72)')
								//6
								->setCellValue('A'.chr($letra2 + 1).'73', '=SUM(A'.chr($letra2 + 1).'74,A'.chr($letra2 + 1).'84)')
								->setCellValue('A'.chr($letra2 + 1).'74', '=SUM(A'.chr($letra2 + 1).'75:A'.chr($letra2 + 1).'83)')
								->setCellValue('A'.chr($letra2 + 1).'84', '=SUM(A'.chr($letra2 + 1).'85:A'.chr($letra2 + 1).'95)');
								$letra2++;
								$letra2++;
						}

						$letra++;
						$letra++;
					}// RECORRE LOS 12 MESES 	
					
					$letra = 72;
					$letra2 = "65";
					$e = 9;

					for ($j=1; $j <= 12 ; $j++) { 
						$precio = 0;	
						$cantidad = 0;
						$total = 0;
						
						if($j < 10){
							$j = "0".$j;
						}

						$sql2 = "SELECT ISNULL(SUM(PresupuestoOpex.CANTIDAD), 0) CANTIDAD, PrecioSCT.PRECIO_UNITARIO FROM PresupuestoOpex INNER JOIN PrecioSCT ON PresupuestoOpex.ACTIVIDAD = PrecioSCT. CONCEPTO WHERE PrecioSCT.CLAVE_SCT = '".trim($clave)."' AND PERIODO = '".$year.$j."' AND TRAMO='".$tramo."' GROUP BY PrecioSCT.PRECIO_UNITARIO";
						$rs2 = odbc_exec( $conn2, $sql2);
						if ( !$rs2 ) { 
							exit( "Error en la consulta SQL" );
						}
						while ( odbc_fetch_row($rs2) ) {
							$cantidad = odbc_result($rs2, 'CANTIDAD');	
							$precio = odbc_result($rs2, 'PRECIO_UNITARIO');	
							$total = $cantidad * $precio;						
						}
						
						if ($letra <= 89) {
							
							$k = $e - 1;
							if ($k == 8){
								$sum = "";
							}else{
								$sum = '+E'.$k;
							}

							$objPHPExcel->setActiveSheetIndex(6)
								->setCellValue('B'.$e, '=Programa!'.chr($letra + 1).'96')
								->setCellValue('C'.$e, '=Historico!'.chr($letra + 1).'96')
								->setCellValue('E'.$e, '=B'.$e.$sum)
								->setCellValue('F'.$e, '=Historico!'.chr($letra + 1).'98');	
								$objPHPExcel->setActiveSheetIndex(2) 
								->setCellValue(chr($letra).$i, $cantidad)
								->setCellValue(chr($letra + 1).$i, $total)
								//
								->setCellValue('F'.$i, '=H'.$i.'+J'.$i.'+L'.$i.'+N'.$i.'+P'.$i.'+R'.$i.'+T'.$i.'+V'.$i.'+X'.$i.'+Z'.$i.'+AB'.$i.'+AD'.$i)
								->setCellValue('G'.$i, '=F'.$i.'*E'.$i)
								->setCellValue(chr($letra + 1).'96', '='.chr($letra + 1).'73+'.chr($letra + 1).'65+'.chr($letra + 1).'51+'.chr($letra + 1).'29+'.chr($letra + 1).'18+'.chr($letra + 1).'4')
								->setCellValue('G4', '=SUM(G5,G10,G13)')
								->setCellValue('G5', '=SUM(G6:G9)')
								->setCellValue('G10', '=SUM(G11:G12)')
								->setCellValue('G13', '=SUM(G14:G17)')
								->setCellValue('G18', '=SUM(G19)')
								->setCellValue('G19', '=SUM(G20:G28)')
								->setCellValue('G29', '=SUM(G30,G32,G36,G42,G49)')
								->setCellValue('G30', '=SUM(G31)')
								->setCellValue('G32', '=SUM(G33:G35)')
								->setCellValue('G36', '=SUM(G37:G41)')
								->setCellValue('G42', '=SUM(G43:G48)')
								->setCellValue('G49', '=SUM(G50)')
								->setCellValue('G51', '=SUM(G52)')
								->setCellValue('G52', '=SUM(G53:G64)')
								->setCellValue('G65', '=SUM(G66)')
								->setCellValue('G66', '=SUM(G67:G72)')
								->setCellValue('G73', '=SUM(G74,G84)')
								->setCellValue('G74', '=SUM(G75:G83)')
								->setCellValue('G84', '=SUM(G85:G95)')
								->setCellValue('G96', '=G4+G18+G29+G51+G65+G73')
								->setCellValue('I98', '=I96')
								->setCellValue('K98', '=I98+K96')
								->setCellValue('M98', '=M96+K98')
								->setCellValue('O98', '=M98+O96')
								->setCellValue('Q98', '=Q96+O98')
								->setCellValue('S98', '=Q98+S96')
								->setCellValue('U98', '=U96+S98')
								->setCellValue('W98', '=U98+W96')
								->setCellValue('Y98', '=Y96+W98')
								->setCellValue('AA98', '=Y98+AA96')
								->setCellValue('AC98', '=AC96+AA98')
								->setCellValue('AE98', '=AC98+AE96')
								//1
								->setCellValue(chr($letra + 1).'4', '=SUM('.chr($letra + 1).'5,'.chr($letra + 1).'10,'.chr($letra + 1).'13)')
								->setCellValue(chr($letra + 1).'5', '=SUM('.chr($letra + 1).'6:'.chr($letra + 1).'9)')
								->setCellValue(chr($letra + 1).'10', '=SUM('.chr($letra + 1).'11:'.chr($letra + 1).'12)')
								->setCellValue(chr($letra + 1).'13', '=SUM('.chr($letra + 1).'14:'.chr($letra + 1).'17)')
								//2
								->setCellValue(chr($letra + 1).'18', '=SUM('.chr($letra + 1).'19)')
								->setCellValue(chr($letra + 1).'19', '=SUM('.chr($letra + 1).'20:'.chr($letra + 1).'28)')
								//3
								->setCellValue(chr($letra + 1).'29', '=SUM('.chr($letra + 1).'30,'.chr($letra + 1).'32,'.chr($letra + 1).'36,'.chr($letra + 1).'42,'.chr($letra + 1).'49)')
								->setCellValue(chr($letra + 1).'30', '=SUM('.chr($letra + 1).'31)')
								->setCellValue(chr($letra + 1).'32', '=SUM('.chr($letra + 1).'33:'.chr($letra + 1).'35)')
								->setCellValue(chr($letra + 1).'36', '=SUM('.chr($letra + 1).'37:'.chr($letra + 1).'41)')
								->setCellValue(chr($letra + 1).'42', '=SUM('.chr($letra + 1).'43:'.chr($letra + 1).'48)')
								->setCellValue(chr($letra + 1).'49', '=SUM('.chr($letra + 1).'50)')
								//4
								->setCellValue(chr($letra + 1).'51', '=SUM('.chr($letra + 1).'52)')
								->setCellValue(chr($letra + 1).'52', '=SUM('.chr($letra + 1).'53:'.chr($letra + 1).'64)')
								//5
								->setCellValue(chr($letra + 1).'65', '=SUM('.chr($letra + 1).'66)')
								->setCellValue(chr($letra + 1).'66', '=SUM('.chr($letra + 1).'67:'.chr($letra + 1).'72)')
								//6
								->setCellValue(chr($letra + 1).'73', '=SUM('.chr($letra + 1).'74,'.chr($letra + 1).'84)')
								->setCellValue(chr($letra + 1).'74', '=SUM('.chr($letra + 1).'75:'.chr($letra + 1).'83)')
								->setCellValue(chr($letra + 1).'84', '=SUM('.chr($letra + 1).'85:'.chr($letra + 1).'95)');
								
						}elseif ($letra == 90) {	
															
								$objPHPExcel->setActiveSheetIndex(6)
									->setCellValue('B'.$e, '=Programa!A'.chr($letra2).'96')
									->setCellValue('C'.$e, '=Historico!A'.chr($letra2).'96')
									->setCellValue('E'.$e, '=B'.$e.$sum)
									->setCellValue('F'.$e, '=Historico!A'.chr($letra2).'98');
													
							$objPHPExcel->setActiveSheetIndex(2)
								->setCellValue(chr(90).$i, $cantidad)
								->setCellValue('A'.chr($letra2).$i, $total)
								->setCellValue('A'.chr($letra2).'96', '=A'.chr($letra2).'73+A'.chr($letra2).'65+A'.chr($letra2).'51+A'.chr($letra2).'29+A'.chr($letra2).'18+A'.chr($letra2).'4')
								//1
								->setCellValue('A'.chr($letra2).'4', '=SUM(A'.chr($letra2).'5, A'.chr($letra2).'10, A'.chr($letra2).'13)')
								->setCellValue('A'.chr($letra2).'5', '=SUM(A'.chr($letra2).'6:A'.chr($letra2).'9)')
								->setCellValue('A'.chr($letra2).'10', '=SUM(A'.chr($letra2).'11:A'.chr($letra2).'12)')
								->setCellValue('A'.chr($letra2).'13', '=SUM(A'.chr($letra2).'14:A'.chr($letra2).'17)')
								//2
								->setCellValue('A'.chr($letra2).'18', '=SUM(A'.chr($letra2).'19)')
								->setCellValue('A'.chr($letra2).'19', '=SUM(A'.chr($letra2).'20:A'.chr($letra2).'28)')
								//3
								->setCellValue('A'.chr($letra2).'29', '=SUM(A'.chr($letra2).'30,A'.chr($letra2).'32,A'.chr($letra2).'36,A'.chr($letra2).'42,A'.chr($letra2).'49)')
								->setCellValue('A'.chr($letra2).'30', '=SUM(A'.chr($letra2).'31)')
								->setCellValue('A'.chr($letra2).'32', '=SUM(A'.chr($letra2).'33:A'.chr($letra2).'35)')
								->setCellValue('A'.chr($letra2).'36', '=SUM(A'.chr($letra2).'37:A'.chr($letra2).'41)')
								->setCellValue('A'.chr($letra2).'42', '=SUM(A'.chr($letra2).'43:A'.chr($letra2).'48)')
								->setCellValue('A'.chr($letra2).'49', '=SUM(A'.chr($letra2).'50)')
								//4
								->setCellValue('A'.chr($letra2).'51', '=SUM(A'.chr($letra2).'52)')
								->setCellValue('A'.chr($letra2).'52', '=SUM(A'.chr($letra2).'53:A'.chr($letra2).'64)')
								//5
								->setCellValue('A'.chr($letra2).'65', '=SUM(A'.chr($letra2).'66)')
								->setCellValue('A'.chr($letra2).'66', '=SUM(A'.chr($letra2).'67:A'.chr($letra2).'72)')
								//6
								->setCellValue('A'.chr($letra2).'73', '=SUM(A'.chr($letra2).'74,A'.chr($letra2).'84)')
								->setCellValue('A'.chr($letra2).'74', '=SUM(A'.chr($letra2).'75:A'.chr($letra2).'83)')
								->setCellValue('A'.chr($letra2).'84', '=SUM(A'.chr($letra2).'85:A'.chr($letra2).'95)');
								$letra2++;
						}else{

							$objPHPExcel->setActiveSheetIndex(6)
								->setCellValue('B'.$e, '=Programa!A'.chr($letra2 + 1).'96')
								->setCellValue('C'.$e, '=Historico!A'.chr($letra2 + 1).'96')
								->setCellValue('E'.$e, '=B'.$e.$sum)
								->setCellValue('F'.$e, '=Historico!A'.chr($letra2 + 1).'98');
							
							$objPHPExcel->setActiveSheetIndex(2)								
								->setCellValue('A'.chr($letra2).$i, $cantidad)
								->setCellValue('A'.chr($letra2 + 1).$i, $total)
								->setCellValue('A'.chr($letra2 + 1).'96', '=A'.chr($letra2 + 1).'73+A'.chr($letra2 + 1).'65+A'.chr($letra2 + 1).'51+A'.chr($letra2 + 1).'29+A'.chr($letra2 + 1).'18+A'.chr($letra2 + 1).'4')
								//1
								->setCellValue('A'.chr($letra2 + 1).'4', '=SUM(A'.chr($letra2 + 1).'5, A'.chr($letra2 + 1).'10, A'.chr($letra2 + 1).'13)')
								->setCellValue('A'.chr($letra2 + 1).'5', '=SUM(A'.chr($letra2 + 1).'6:A'.chr($letra2 + 1).'9)')
								->setCellValue('A'.chr($letra2 + 1).'10', '=SUM(A'.chr($letra2 + 1).'11:A'.chr($letra2 + 1).'12)')
								->setCellValue('A'.chr($letra2 + 1).'13', '=SUM(A'.chr($letra2 + 1).'14:A'.chr($letra2 + 1).'17)')
								//2
								->setCellValue('A'.chr($letra2 + 1).'18', '=SUM(A'.chr($letra2 + 1).'19)')
								->setCellValue('A'.chr($letra2 + 1).'19', '=SUM(A'.chr($letra2 + 1).'20:A'.chr($letra2 + 1).'28)')
								//3
								->setCellValue('A'.chr($letra2 + 1).'29', '=SUM(A'.chr($letra2 + 1).'30,A'.chr($letra2 + 1).'32,A'.chr($letra2 + 1).'36,A'.chr($letra2 + 1).'42,A'.chr($letra2 + 1).'49)')
								->setCellValue('A'.chr($letra2 + 1).'30', '=SUM(A'.chr($letra2 + 1).'31)')
								->setCellValue('A'.chr($letra2 + 1).'32', '=SUM(A'.chr($letra2 + 1).'33:A'.chr($letra2 + 1).'35)')
								->setCellValue('A'.chr($letra2 + 1).'36', '=SUM(A'.chr($letra2 + 1).'37:A'.chr($letra2 + 1).'41)')
								->setCellValue('A'.chr($letra2 + 1).'42', '=SUM(A'.chr($letra2 + 1).'43:A'.chr($letra2 + 1).'48)')
								->setCellValue('A'.chr($letra2 + 1).'49', '=SUM(A'.chr($letra2 + 1).'50)')
								//4
								->setCellValue('A'.chr($letra2 + 1).'51', '=SUM(A'.chr($letra2 + 1).'52)')
								->setCellValue('A'.chr($letra2 + 1).'52', '=SUM(A'.chr($letra2 + 1).'53:A'.chr($letra2 + 1).'64)')
								//5
								->setCellValue('A'.chr($letra2 + 1).'65', '=SUM(A'.chr($letra2 + 1).'66)')
								->setCellValue('A'.chr($letra2 + 1).'66', '=SUM(A'.chr($letra2 + 1).'67:A'.chr($letra2 + 1).'72)')
								//6
								->setCellValue('A'.chr($letra2 + 1).'73', '=SUM(A'.chr($letra2 + 1).'74,A'.chr($letra2 + 1).'84)')
								->setCellValue('A'.chr($letra2 + 1).'74', '=SUM(A'.chr($letra2 + 1).'75:A'.chr($letra2 + 1).'83)')
								->setCellValue('A'.chr($letra2 + 1).'84', '=SUM(A'.chr($letra2 + 1).'85:A'.chr($letra2 + 1).'95)');
								$letra2++;
								$letra2++;
								//$e++;
						}
						
						$letra++;
						$letra++;
						$e++;
						
						
					}// RECORRE LOS 12 MESES

					$sql2 = "SELECT PRECIO_UNITARIO FROM PrecioSCT WHERE CLAVE_SCT = '".trim($clave)."'";
					$rs2 = odbc_exec( $conn2, $sql2);
					if ( !$rs2 ) {
						exit( "Error en la consulta SQL" );
					}
					while ( odbc_fetch_row($rs2) ) {
						$precio = odbc_result($rs2, 'PRECIO_UNITARIO');
					}			
					
					$objPHPExcel->setActiveSheetIndex(2)
						->setCellValue('E'.$i, $precio);
					$objPHPExcel->setActiveSheetIndex(3)
						->setCellValue('E'.$i, $precio);
						
					$sql2 = "SELECT ISNULL(SUM(AvanceDiario.CANTIDAD), 0) CANTIDAD FROM AvanceDiario INNER JOIN PrecioSCT ON AvanceDiario.ACTIVIDAD = PrecioSCT.CONCEPTO WHERE PrecioSCT.CLAVE_SCT = '".trim($clave)."' AND FECHA BETWEEN '".$year."-01-01' AND '".$year."-".$month."-".$day."' AND TRAMO='".$tramo."'";
						$rs2 = odbc_exec( $conn2, $sql2);
						if ( !$rs2 ) { 
							exit( "Error en la consulta SQL" );
						}
						while ( odbc_fetch_row($rs2) ) {  
							$cantidad = odbc_result($rs2, 'CANTIDAD');	
							$total = $cantidad * $precio;	
						
							$objPHPExcel->setActiveSheetIndex(4)
								->setCellValue('J'.$i, $cantidad)
								->setCellValue('K'.$i, $total);					
						}
	
						
					$sql2 = "SELECT ISNULL(SUM(PresupuestoOpex.CANTIDAD), 0) CANTIDAD, PrecioSCT.PRECIO_UNITARIO FROM PresupuestoOpex INNER JOIN PrecioSCT ON PresupuestoOpex.ACTIVIDAD = PrecioSCT. CONCEPTO WHERE PrecioSCT.CLAVE_SCT = '".trim($clave)."' AND PERIODO BETWEEN '".$year."01' AND '".$year.$month."' AND TRAMO='".$tramo."' GROUP BY PrecioSCT.PRECIO_UNITARIO";
					$rs2 = odbc_exec( $conn2, $sql2);
					if ( !$rs2 ) {
						exit( "Error en la consulta SQL" );
					}
					while ( odbc_fetch_row($rs2) ) {
						$cantidad = odbc_result($rs2, 'CANTIDAD');	
						$precio = odbc_result($rs2, 'PRECIO_UNITARIO');					
						$total = $cantidad * $precio;
						
						$objPHPExcel->setActiveSheetIndex(4)
							->setCellValue('H'.$i, $cantidad)
							->setCellValue('I'.$i, $total);
					}

					$objPHPExcel->setActiveSheetIndex(4)
						->setCellValue('F'.$i, '=H'.$i.'+J'.$i)
						->setCellValue('H2', 'PROGRAMA '.$mes)
						->setCellValue('J2', 'AVANCE '.$mes)
						->setCellValue('E'.$i, $precio) // solo una vez
						->setCellValue('G'.$i, '=F'.$i.'*E'.$i)
						->setCellValue('L'.$i, '=+J'.$i.'-H'.$i)
						->setCellValue('M'.$i, '=(L'.$i.'*$E'.$i.')')
						->setCellValue('G4', '=SUM(G5,G10,G13)')
						->setCellValue('G5', '=SUM(G6:G9)')
						->setCellValue('G10', '=SUM(G11:G12)')
						->setCellValue('G13', '=SUM(G14:G17)')
						->setCellValue('G18', '=SUM(G19)')
						->setCellValue('G19', '=SUM(G20:G28)')
						->setCellValue('G29', '=SUM(G30,G32,G36,G42,G49)')
						->setCellValue('G30', '=SUM(G31)')
						->setCellValue('G32', '=SUM(G33:G35)')
						->setCellValue('G36', '=SUM(G37:G41)')
						->setCellValue('G42', '=SUM(G43:G48)')
						->setCellValue('G49', '=SUM(G50)')
						->setCellValue('G51', '=SUM(G52)')#=SI(H6=0,"",J6/H6)("D$i", "=IF(C$i/B$i,0)");
						->setCellValue('G52', '=SUM(G53:G64)')
						->setCellValue('G65', '=SUM(G66)')
						->setCellValue('G66', '=SUM(G67:G72)')
						->setCellValue('G73', '=SUM(G74,G84)')
						->setCellValue('G74', '=SUM(G75:G83)')
						->setCellValue('G84', '=SUM(G85:G95)')
						->setCellValue('G96', '=G4+G18+G29+G51+G65+G73')

						->setCellValue('I4', '=SUM(I5,I10,I13)')
						->setCellValue('I5', '=SUM(I6:I9)')
						->setCellValue('I10', '=SUM(I11:I12)')
						->setCellValue('I13', '=SUM(I14:I17)')
						->setCellValue('I18', '=SUM(I19)')
						->setCellValue('I19', '=SUM(I20:I28)')
						->setCellValue('I29', '=SUM(I30,I32,I36,I42,I49)')
						->setCellValue('I30', '=SUM(I31)')
						->setCellValue('I32', '=SUM(I33:I35)')
						->setCellValue('I36', '=SUM(I37:I41)')
						->setCellValue('I42', '=SUM(I43:I48)')
						->setCellValue('I49', '=SUM(I50)')
						->setCellValue('I51', '=SUM(I52)')
						->setCellValue('I52', '=SUM(I53:I64)')
						->setCellValue('I65', '=SUM(I66)')
						->setCellValue('I66', '=SUM(I67:I72)')
						->setCellValue('I73', '=SUM(I74,I84)')
						->setCellValue('I74', '=SUM(I75:I83)')
						->setCellValue('I84', '=SUM(I85:I95)')
						->setCellValue('I96', '=I4+I18+I29+I51+I65+I73')
						
						->setCellValue('K4', '=SUM(K5,K10,K13)')
						->setCellValue('K5', '=SUM(K6:K9)')
						->setCellValue('K10', '=SUM(K11:K12)')
						->setCellValue('K13', '=SUM(K14:K17)')
						->setCellValue('K18', '=SUM(K19)')
						->setCellValue('K19', '=SUM(K20:K28)')
						->setCellValue('K29', '=SUM(K30,K32,K36,K42,K49)')
						->setCellValue('K30', '=SUM(K31)')
						->setCellValue('K32', '=SUM(K33:K35)')
						->setCellValue('K36', '=SUM(K37:K41)')
						->setCellValue('K42', '=SUM(K43:K48)')
						->setCellValue('K49', '=SUM(K50)')
						->setCellValue('K51', '=SUM(K52)')
						->setCellValue('K52', '=SUM(K53:K64)')
						->setCellValue('K65', '=SUM(K66)')
						->setCellValue('K66', '=SUM(K67:K72)')
						->setCellValue('K73', '=SUM(K74,K84)')
						->setCellValue('K74', '=SUM(K75:K83)')
						->setCellValue('K84', '=SUM(K85:K95)')
						->setCellValue('K96', '=K4+K18+K29+K51+K65+K73');
						
						$hache = $objPHPExcel->getActiveSheet()->getCell('H'.$i)->getValue();
						$jota = $objPHPExcel->getActiveSheet()->getCell('J'.$i)->getValue();
						
						if ($hache == 0){
							$val = 0;
							//$hache = "Si";
						}else{
							$val = $jota / $hache;
							//$hache = "No";
						}
						$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, $val);	
						
					/*	$cantidad = 0;
						$cantidad1 = 0;
						$precio = 0;
						$precio1 = 0;
						$total = 0;
						$total1 = 0;*/
						
				}	

$azul = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '002060'),
        'size'  => 10,
        'name'  => 'Arial Narrow'
    ));
	
$negrita = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '000000'),
        'size'  => 11,
        'name'  => 'Tahoma'
    ));	
	
$mas = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '000000'),
        'size'  => 14,
        'name'  => 'Tahoma'
    ));	
	
$centrado = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );	
	
$borde = array(
  'borders' => array(
    'outline' => array(
      'style' => PHPExcel_Style_Border::BORDER_THICK
    )
  )
);	

				
//ACTIVIDAD POR HOJA
	 $actividad2 = "";
	 $cont = 13;
	 $estilo = array();
	 $sql = "SELECT AvanceDiario.*, CatConcepto.Abreviatura FROM AvanceDiario INNER JOIN CatConcepto ON AvanceDiario.ACTIVIDAD_ID = CatConcepto.CvCpt WHERE YEAR(FECHA) = '".$year."' AND MONTH(FECHA) = '".$month."' AND TRAMO='".$tramo."' ORDER BY AvanceDiario.ACTIVIDAD, FECHA";
	 $rs = odbc_exec( $conn, $sql);
	 if ( !$rs ) { 
	 	exit( "Error en la consulta SQL" );
	 }
	 while ( odbc_fetch_row($rs) ) {
	 	$fecha1 = odbc_result($rs, 'FECHA');
	 	$km1 = odbc_result($rs, 'KM_INI');
	 	$km2 = odbc_result($rs, 'KM_FIN');
	 	$actividad = odbc_result($rs, 'ACTIVIDAD');
	 	$cuerpo = odbc_result($rs, 'CUERPO');
	 	$long = odbc_result($rs, 'LONGITUD');
	 	$ancho = odbc_result($rs, 'ANCHO');
	 	$espesor = odbc_result($rs, 'ESPESOR');
	 	$cantidad = odbc_result($rs, 'CANTIDAD');
	 	$unidad = odbc_result($rs, 'UNIDAD');
	 	$abrev = odbc_result($rs, 'Abreviatura');
		
		
	 	if ($actividad != $actividad2){
	 		$actividad2 = $actividad;
	 		$cont = 13;
	 		$total = $objPHPExcel->getSheetCount();
			$objPHPExcel->createSheet($total);
			$objPHPExcel->setActiveSheetIndex($total -1)
						->setCellValue('A5', 'PERIODO DE EJECUCION')
						->setCellValue('A8', 'CONCEPTO:')
						->setCellValue('A10', 'CALCULO:')
						->setCellValue('B1', 'NOMBRE DE LA EMPRESA:')
						->setCellValue('B2', "AUTOPISTA")
						->setCellValue('B3', "SUBTRAMO")
						->setCellValue('D1', "RED DE CARRETERAS DE OCCIDENTE SAB DE CV")
						->setCellValue('K4', "GENERADOR DE OBRA")						
						->setCellValue('A12', 'FECHA')
						->setCellValue('B12', 'DEL KM')
						->setCellValue('C12', 'AL KM')
						->setCellValue('D12', 'CUERPO')
						->setCellValue('E12', "LARGO")
						->setCellValue('F12', "ANCHO")
						->setCellValue('G12', "UNIDAD")
						->setCellValue('H12', "VOLUMEN")
						->setCellValue('K7', "HOJA:")
						->setCellValue('L7', "1")
						->setCellValue('M7', "DE:")
						->setCellValue('N7', "1")
						->setCellValue('C2', $actividad)
						->setCellValue('C2', utf8_encode($tramo))
						->setCellValue('C3', utf8_encode($subtramo))
						->setCellValue('C5', utf8_encode($fecha.$year))
	 					->setCellValue('B8', utf8_encode($actividad))
	 					->setCellValue('B10', utf8_encode($unidad))
	 					->setCellValue('A'. $cont, utf8_encode($fecha1))
	 					->setCellValue('B'. $cont, utf8_encode($km1))
	 					->setCellValue('C'. $cont, utf8_encode($km2))
	 					->setCellValue('D'. $cont, utf8_encode($cuerpo))
	 					->setCellValue('E'. $cont, utf8_encode($long))
	 					->setCellValue('F'. $cont, utf8_encode($ancho))
	 					->setCellValue('G'. $cont, utf8_encode($espesor))
	 					->setCellValue('H'. $cont, utf8_encode($cantidad))
	 					->setCellValue('I47', utf8_encode($unidad))
	 					->setCellValue('I48', utf8_encode($unidad))
	 					->setCellValue('G47', "TOTAL ESTA HOJA =")
	 					->setCellValue('G48', "TOTAL ACUMULADO =")
	 					->setCellValue('H47', "=SUMA(H13:H45)")
	 					->setCellValue('H48', "=(H47)");
						$objPHPExcel->getActiveSheet()->mergeCells('K9:L9');
						$objPHPExcel->getActiveSheet()->mergeCells('K10:L10');
						$objPHPExcel->getActiveSheet()->mergeCells('K23:L23');
						$objPHPExcel->getActiveSheet()->mergeCells('K36:L36');
						$objPHPExcel->getActiveSheet()->setCellValue('K9','FOTOGRAFIA');
						$objPHPExcel->getActiveSheet()->setCellValue('K10','ANTES');
						$objPHPExcel->getActiveSheet()->setCellValue('K23','DURANTE');
						$objPHPExcel->getActiveSheet()->setCellValue('K36','DESPUES');
						$objPHPExcel->getActiveSheet()->getStyle('C1:C5')->applyFromArray($azul);
						$objPHPExcel->getActiveSheet()->getStyle('D1')->applyFromArray($azul);
						$objPHPExcel->getActiveSheet()->getStyle('B8:B10')->applyFromArray($azul);						
						$objPHPExcel->getActiveSheet()->getStyle('B1:B3')->applyFromArray($negrita);
						$objPHPExcel->getActiveSheet()->getStyle('A8:A10')->applyFromArray($negrita);
						$objPHPExcel->getActiveSheet()->getStyle('K9')->applyFromArray($negrita);
						$objPHPExcel->getActiveSheet()->getStyle('K10')->applyFromArray($negrita);		
						$objPHPExcel->getActiveSheet()->getStyle('K23')->applyFromArray($negrita);
						$objPHPExcel->getActiveSheet()->getStyle('K36')->applyFromArray($negrita);			
						$objPHPExcel->getActiveSheet()->getStyle('A5')->applyFromArray($mas);
						$objPHPExcel->getActiveSheet()->getStyle('K4')->applyFromArray($mas);
						$objPHPExcel->getActiveSheet()->getStyle('G47:I48')->applyFromArray($negrita);	
						$objPHPExcel->getActiveSheet()->getStyle('G47:I48')->applyFromArray($borde);
						$objPHPExcel->getActiveSheet()->getStyle('A1:N4')->applyFromArray($borde);
						$objPHPExcel->getActiveSheet()->getStyle('A5:N2')->applyFromArray($borde);
						$objPHPExcel->getActiveSheet()->getStyle('A8:N49')->applyFromArray($borde);
						$objPHPExcel->getActiveSheet()->getStyle('J11:M22')->applyFromArray($borde);
						$objPHPExcel->getActiveSheet()->getStyle('J24:M35')->applyFromArray($borde);
						$objPHPExcel->getActiveSheet()->getStyle('J37:M48')->applyFromArray($borde);
						for($i = 65; $i <= 72; $i++){							
							$objPHPExcel->getActiveSheet()->getColumnDimension(chr($i))->setWidth(17.5);
							$objPHPExcel->getActiveSheet()->getStyle(chr($i).'12')->applyFromArray($negrita);
							$objPHPExcel->getActiveSheet()->getStyle(chr($i).$cont)->applyFromArray($centrado);
						}
						for($i = 73; $i <= 76; $i++){							
							$objPHPExcel->getActiveSheet()->getColumnDimension(chr($i))->setWidth(11.56);
						}
						for($i = 77; $i <= 78; $i++){							
							$objPHPExcel->getActiveSheet()->getColumnDimension(chr($i))->setWidth(7.50);
						}
						for($i = 6; $i <= 49; $i++){							
							$objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(15.60);
						}
						
						$objDrawing = new PHPExcel_Worksheet_Drawing();
						$objDrawing->setName('foto');
						$objDrawing->setDescription('foto');
						$objDrawing->setPath('../SAC/images/rco.jpg');
						$objDrawing->setCoordinates('A1');
						$objDrawing->setHeight(65);
						$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

						$cont1 = 1;
	 					$carpeta="../Global/Sac/Avance/".$periodo."/".$tramo."/".$abrev."";
	 					if(is_dir($carpeta)){
	 						if($dir = opendir($carpeta)){
	 							while ($archivo = readdir($dir)){ //obtenemos un archivo y luego otro sucesivamente
	 								if (is_dir($archivo)){//verificamos si es o no un directorio
	 								}else{
										if ($archivo!='Thumbs.db'){
												$objDrawing = new PHPExcel_Worksheet_Drawing();
												$objDrawing->setName('Foto');
												$objDrawing->setDescription('Foto');
												$objDrawing->setPath('../Global/Sac/Avance/'.$periodo.'/'.$tramo.'/'.$abrev.'/'.$archivo);
												switch($cont1){
													case "1":
														$y = 11;
													break;
													case "2":
														$y = 24;
													break;
													case "3":
														$y = 37;
													break;
												}
												$objDrawing->setCoordinates('J'.$y);
												$objDrawing->setWidthAndHeight(305,400);																																										
												$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
												$cont1++;
										}
	 								}
	 							}
	 						}
	 					}
	 		$objPHPExcel->getActiveSheet()->setTitle($abrev);
	 	}elseif ($cont > 13 && $cont<= 45) {
	 		$objPHPExcel->setActiveSheetIndex($total -1)
						->setCellValue('A5', 'PERIODO DE EJECUCION')
						->setCellValue('A8', 'CONCEPTO:')
						->setCellValue('A10', 'CALCULO:')
						->setCellValue('B1', 'NOMBRE DE LA EMPRESA:')
						->setCellValue('B2', "AUTOPISTA")
						->setCellValue('B3', "SUBTRAMO")
						->setCellValue('D1', "RED DE CARRETERAS DE OCCIDENTE SAB DE CV")
						->setCellValue('K4', "GENERADOR DE OBRA")						
						->setCellValue('A12', 'FECHA')
						->setCellValue('B12', 'DEL KM')
						->setCellValue('C12', 'AL KM')
						->setCellValue('D12', 'CUERPO')
						->setCellValue('E12', "LARGO")
						->setCellValue('F12', "ANCHO")
						->setCellValue('G12', "UNIDAD")
						->setCellValue('H12', "VOLUMEN")
						->setCellValue('K7', "HOJA:")
						->setCellValue('L7', "1")
						->setCellValue('M7', "DE:")
						->setCellValue('N7', "1")
						->setCellValue('C2', $actividad)
						->setCellValue('C2', utf8_encode($tramo))
						->setCellValue('C3', utf8_encode($subtramo))
						->setCellValue('C5', utf8_encode($fecha.$year))
	 					->setCellValue('B8', utf8_encode($actividad))
	 					->setCellValue('B10', utf8_encode($unidad))
	 					->setCellValue('A'. $cont, utf8_encode($fecha1))
	 					->setCellValue('B'. $cont, utf8_encode($km1))
	 					->setCellValue('C'. $cont, utf8_encode($km2))
	 					->setCellValue('D'. $cont, utf8_encode($cuerpo))
	 					->setCellValue('E'. $cont, utf8_encode($long))
	 					->setCellValue('F'. $cont, utf8_encode($ancho))
	 					->setCellValue('G'. $cont, utf8_encode($espesor))
	 					->setCellValue('H'. $cont, utf8_encode($cantidad))
	 					->setCellValue('I47', utf8_encode($unidad))
	 					->setCellValue('I48', utf8_encode($unidad))
	 					->setCellValue('G47', "TOTAL ESTA HOJA =")
	 					->setCellValue('G48', "TOTAL ACUMULADO =")
	 					->setCellValue('H47', "=SUMA(H13:H45)")
	 					->setCellValue('H48', "=(H47)");
						$objPHPExcel->getActiveSheet()->mergeCells('K9:L9');
						$objPHPExcel->getActiveSheet()->mergeCells('K10:L10');
						$objPHPExcel->getActiveSheet()->mergeCells('K23:L23');
						$objPHPExcel->getActiveSheet()->mergeCells('K36:L36');
						$objPHPExcel->getActiveSheet()->setCellValue('K9','FOTOGRAFIA');
						$objPHPExcel->getActiveSheet()->setCellValue('K10','ANTES');
						$objPHPExcel->getActiveSheet()->setCellValue('K23','DURANTE');
						$objPHPExcel->getActiveSheet()->setCellValue('K36','DESPUES');
						$objPHPExcel->getActiveSheet()->getStyle('C1:C5')->applyFromArray($azul);
						$objPHPExcel->getActiveSheet()->getStyle('D1')->applyFromArray($azul);
						$objPHPExcel->getActiveSheet()->getStyle('B8:B10')->applyFromArray($azul);
						$objPHPExcel->getActiveSheet()->getStyle('B1:B3')->applyFromArray($negrita);
						$objPHPExcel->getActiveSheet()->getStyle('A8:A10')->applyFromArray($negrita);		
						$objPHPExcel->getActiveSheet()->getStyle('K9')->applyFromArray($negrita);
						$objPHPExcel->getActiveSheet()->getStyle('K10')->applyFromArray($negrita);		
						$objPHPExcel->getActiveSheet()->getStyle('K23')->applyFromArray($negrita);
						$objPHPExcel->getActiveSheet()->getStyle('K36')->applyFromArray($negrita);	
						$objPHPExcel->getActiveSheet()->getStyle('G47:I48')->applyFromArray($negrita);	
						$objPHPExcel->getActiveSheet()->getStyle('G47:I48')->applyFromArray($borde);			
						$objPHPExcel->getActiveSheet()->getStyle('A5')->applyFromArray($mas);
						$objPHPExcel->getActiveSheet()->getStyle('K4')->applyFromArray($mas);
						for($i = 65; $i <= 72; $i++){							
							$objPHPExcel->getActiveSheet()->getColumnDimension(chr($i))->setWidth(17.5);
							$objPHPExcel->getActiveSheet()->getStyle(chr($i).'12')->applyFromArray($negrita);
							$objPHPExcel->getActiveSheet()->getStyle(chr($i).$cont)->applyFromArray($centrado);
						}
	 	}
		elseif($cont >= 63 && $cont<= 95){
			$objPHPExcel->getActiveSheet()->setCellValue('A55', 'PERIODO DE EJECUCION');
			$objPHPExcel->getActiveSheet()->setCellValue('A58', 'CONCEPTO:');
			$objPHPExcel->getActiveSheet()->setCellValue('A60', 'CALCULO:');
			$objPHPExcel->getActiveSheet()->setCellValue('B51', 'NOMBRE DE LA EMPRESA:');
			$objPHPExcel->getActiveSheet()->setCellValue('B52', "AUTOPISTA");
			$objPHPExcel->getActiveSheet()->setCellValue('B53', "SUBTRAMO");
			$objPHPExcel->getActiveSheet()->setCellValue('D51', "RED DE CARRETERAS DE OCCIDENTE SAB DE CV");
			$objPHPExcel->getActiveSheet()->setCellValue('K54', "GENERADOR DE OBRA");
			$objPHPExcel->getActiveSheet()->setCellValue('A62', 'FECHA');
			$objPHPExcel->getActiveSheet()->setCellValue('B62', 'DEL KM');
			$objPHPExcel->getActiveSheet()->setCellValue('C62', 'AL KM');
			$objPHPExcel->getActiveSheet()->setCellValue('D62', 'CUERPO');
			$objPHPExcel->getActiveSheet()->setCellValue('E62', "LARGO");
			$objPHPExcel->getActiveSheet()->setCellValue('F62', "ANCHO");
			$objPHPExcel->getActiveSheet()->setCellValue('G62', "UNIDAD");
			$objPHPExcel->getActiveSheet()->setCellValue('H62', "VOLUMEN");
			$objPHPExcel->getActiveSheet()->setCellValue('K57', "HOJA:");
			$objPHPExcel->getActiveSheet()->setCellValue('L57', "2");
			$objPHPExcel->getActiveSheet()->setCellValue('M57', "DE:");
			$objPHPExcel->getActiveSheet()->setCellValue('N57', "2");
			$objPHPExcel->getActiveSheet()->setCellValue('N7', "2");
			$objPHPExcel->getActiveSheet()->setCellValue('C52', utf8_encode($tramo));
	 	 	$objPHPExcel->getActiveSheet()->setCellValue('C53', utf8_encode($subtramo));
	 	 	$objPHPExcel->getActiveSheet()->setCellValue('C5', utf8_encode($fecha.$year));
	 	 	$objPHPExcel->getActiveSheet()->setCellValue('B58', utf8_encode($actividad));
	 	 	$objPHPExcel->getActiveSheet()->setCellValue('B60', utf8_encode($unidad));
	 	 	$objPHPExcel->getActiveSheet()->setCellValue('A'. $cont, utf8_encode($fecha1));
	 	 	$objPHPExcel->getActiveSheet()->setCellValue('B'. $cont, utf8_encode($km1));
	 	 	$objPHPExcel->getActiveSheet()->setCellValue('C'. $cont, utf8_encode($km2));
	 	 	$objPHPExcel->getActiveSheet()->setCellValue('D'. $cont, utf8_encode($cuerpo));
	 	 	$objPHPExcel->getActiveSheet()->setCellValue('E'. $cont, utf8_encode($long));
	 	 	$objPHPExcel->getActiveSheet()->setCellValue('F'. $cont, utf8_encode($ancho));
	 	 	$objPHPExcel->getActiveSheet()->setCellValue('G'. $cont, utf8_encode($espesor));
	 	 	$objPHPExcel->getActiveSheet()->setCellValue('H'. $cont, utf8_encode($cantidad));
	 	 	$objPHPExcel->getActiveSheet()->setCellValue('I97', utf8_encode($unidad));
	 	 	$objPHPExcel->getActiveSheet()->setCellValue('I98', utf8_encode($unidad));
	 		$objPHPExcel->getActiveSheet()->setCellValue('G97', "TOTAL ESTA HOJA =");
	 		$objPHPExcel->getActiveSheet()->setCellValue('G98', "TOTAL ACUMULADO =");
	 	 	$objPHPExcel->getActiveSheet()->setCellValue('H97', "=SUMA(H63:H95)");
	 	 	$objPHPExcel->getActiveSheet()->setCellValue('H98', "=SUMA(H48+H97)");
			$objPHPExcel->getActiveSheet()->getStyle('C51:C55')->applyFromArray($azul);
			$objPHPExcel->getActiveSheet()->getStyle('D51')->applyFromArray($azul);
			$objPHPExcel->getActiveSheet()->getStyle('B58:B60')->applyFromArray($azul);			
			$objPHPExcel->getActiveSheet()->getStyle('B51:B53')->applyFromArray($negrita);
			$objPHPExcel->getActiveSheet()->getStyle('A58:A60')->applyFromArray($negrita);		
			$objPHPExcel->getActiveSheet()->getStyle('K59')->applyFromArray($negrita);
			$objPHPExcel->getActiveSheet()->getStyle('K60')->applyFromArray($negrita);		
			$objPHPExcel->getActiveSheet()->getStyle('K73')->applyFromArray($negrita);
			$objPHPExcel->getActiveSheet()->getStyle('K86')->applyFromArray($negrita);				
			$objPHPExcel->getActiveSheet()->getStyle('A55')->applyFromArray($mas);
			$objPHPExcel->getActiveSheet()->getStyle('K54')->applyFromArray($mas);
			$objPHPExcel->getActiveSheet()->getStyle('G97:I98')->applyFromArray($negrita);	
			$objPHPExcel->getActiveSheet()->getStyle('G97:I98')->applyFromArray($borde);
			$objPHPExcel->getActiveSheet()->mergeCells('K59:L59');
			$objPHPExcel->getActiveSheet()->mergeCells('K60:L60');
			$objPHPExcel->getActiveSheet()->mergeCells('K73:L73');
			$objPHPExcel->getActiveSheet()->mergeCells('K86:L86');
			$objPHPExcel->getActiveSheet()->setCellValue('K59','FOTOGRAFIA');
			$objPHPExcel->getActiveSheet()->setCellValue('K60','ANTES');
			$objPHPExcel->getActiveSheet()->setCellValue('K73','DURANTE');
			$objPHPExcel->getActiveSheet()->setCellValue('K86','DESPUES');
	 	 	$objPHPExcel->getActiveSheet()->setTitle($abrev);
			for($i = 65; $i <= 72; $i++){							
				$objPHPExcel->getActiveSheet()->getStyle(chr($i).'62')->applyFromArray($negrita);
				$objPHPExcel->getActiveSheet()->getStyle(chr($i).$cont)->applyFromArray($centrado);
			}
			
		}
		elseif($cont >= 113 && $cont<= 145){
			$objPHPExcel->getActiveSheet()->setCellValue('A105', 'PERIODO DE EJECUCION');
			$objPHPExcel->getActiveSheet()->setCellValue('A108', 'CONCEPTO:');
			$objPHPExcel->getActiveSheet()->setCellValue('A110', 'CALCULO:');
			$objPHPExcel->getActiveSheet()->setCellValue('B101', 'NOMBRE DE LA EMPRESA:');
			$objPHPExcel->getActiveSheet()->setCellValue('B102', "AUTOPISTA");
			$objPHPExcel->getActiveSheet()->setCellValue('B103', "SUBTRAMO");
			$objPHPExcel->getActiveSheet()->setCellValue('D101', "RED DE CARRETERAS DE OCCIDENTE SAB DE CV");
			$objPHPExcel->getActiveSheet()->setCellValue('K104', "GENERADOR DE OBRA");
			$objPHPExcel->getActiveSheet()->setCellValue('A112', 'FECHA');
			$objPHPExcel->getActiveSheet()->setCellValue('B112', 'DEL KM');
			$objPHPExcel->getActiveSheet()->setCellValue('C112', 'AL KM');
			$objPHPExcel->getActiveSheet()->setCellValue('D112', 'CUERPO');
			$objPHPExcel->getActiveSheet()->setCellValue('E112', "LARGO");
			$objPHPExcel->getActiveSheet()->setCellValue('F112', "ANCHO");
			$objPHPExcel->getActiveSheet()->setCellValue('G112', "UNIDAD");
			$objPHPExcel->getActiveSheet()->setCellValue('H112', "VOLUMEN");
			$objPHPExcel->getActiveSheet()->setCellValue('K107', "HOJA:");
			$objPHPExcel->getActiveSheet()->setCellValue('L107', "3");
			$objPHPExcel->getActiveSheet()->setCellValue('M107', "DE:");
			$objPHPExcel->getActiveSheet()->setCellValue('N107', "3");
			$objPHPExcel->getActiveSheet()->setCellValue('N57', "3");
			$objPHPExcel->getActiveSheet()->setCellValue('N7', "3");
			$objPHPExcel->getActiveSheet()->setCellValue('C102', utf8_encode($tramo));
	 	 	$objPHPExcel->getActiveSheet()->setCellValue('C103', utf8_encode($subtramo));
	 	 	$objPHPExcel->getActiveSheet()->setCellValue('C105', utf8_encode($fecha.$year));
	 	 	$objPHPExcel->getActiveSheet()->setCellValue('B108', utf8_encode($actividad));
	 	 	$objPHPExcel->getActiveSheet()->setCellValue('B110', utf8_encode($unidad));
	 	 	$objPHPExcel->getActiveSheet()->setCellValue('A'. $cont, utf8_encode($fecha1));
	 	 	$objPHPExcel->getActiveSheet()->setCellValue('B'. $cont, utf8_encode($km1));
	 	 	$objPHPExcel->getActiveSheet()->setCellValue('C'. $cont, utf8_encode($km2));
	 	 	$objPHPExcel->getActiveSheet()->setCellValue('D'. $cont, utf8_encode($cuerpo));
	 	 	$objPHPExcel->getActiveSheet()->setCellValue('E'. $cont, utf8_encode($long));
	 	 	$objPHPExcel->getActiveSheet()->setCellValue('F'. $cont, utf8_encode($ancho));
	 	 	$objPHPExcel->getActiveSheet()->setCellValue('G'. $cont, utf8_encode($espesor));
	 	 	$objPHPExcel->getActiveSheet()->setCellValue('H'. $cont, utf8_encode($cantidad));
	 	 	$objPHPExcel->getActiveSheet()->setCellValue('I147', utf8_encode($unidad));
	 	 	$objPHPExcel->getActiveSheet()->setCellValue('I148', utf8_encode($unidad));
	 		$objPHPExcel->getActiveSheet()->setCellValue('G147', "TOTAL ESTA HOJA =");
	 		$objPHPExcel->getActiveSheet()->setCellValue('G148', "TOTAL ACUMULADO =");
	 	 	$objPHPExcel->getActiveSheet()->setCellValue('H147', "=SUMA(H113:H145)");
	 	 	$objPHPExcel->getActiveSheet()->setCellValue('H148', "=SUMA(H98+H147)");
			$objPHPExcel->getActiveSheet()->getStyle('C101:C105')->applyFromArray($azul);
			$objPHPExcel->getActiveSheet()->getStyle('D101')->applyFromArray($azul);
			$objPHPExcel->getActiveSheet()->getStyle('B108:B103')->applyFromArray($negrita);
			$objPHPExcel->getActiveSheet()->getStyle('A108:A110')->applyFromArray($negrita);		
			$objPHPExcel->getActiveSheet()->getStyle('K109')->applyFromArray($negrita);
			$objPHPExcel->getActiveSheet()->getStyle('K110')->applyFromArray($negrita);		
			$objPHPExcel->getActiveSheet()->getStyle('K123')->applyFromArray($negrita);
			$objPHPExcel->getActiveSheet()->getStyle('K136')->applyFromArray($negrita);				
			$objPHPExcel->getActiveSheet()->getStyle('A105')->applyFromArray($mas);
			$objPHPExcel->getActiveSheet()->getStyle('K104')->applyFromArray($mas);
			$objPHPExcel->getActiveSheet()->getStyle('G147:I148')->applyFromArray($negrita);	
			$objPHPExcel->getActiveSheet()->getStyle('G147:I148')->applyFromArray($borde);
			$objPHPExcel->getActiveSheet()->mergeCells('K109:L109');
			$objPHPExcel->getActiveSheet()->mergeCells('K110:L110');
			$objPHPExcel->getActiveSheet()->setCellValue('K109','FOTOGRAFIA');
			$objPHPExcel->getActiveSheet()->setCellValue('K110','ANTES');
			$objPHPExcel->getActiveSheet()->setCellValue('K123','DURANTE');
			$objPHPExcel->getActiveSheet()->setCellValue('K136','DESPUES');
			$objPHPExcel->getActiveSheet()->mergeCells('K123:L123');
			$objPHPExcel->getActiveSheet()->mergeCells('K136:L136');
	 	 	$objPHPExcel->getActiveSheet()->setTitle($abrev);
			for($i = 65; $i <= 72; $i++){							
				$objPHPExcel->getActiveSheet()->getStyle(chr($i).'112')->applyFromArray($negrita);
				$objPHPExcel->getActiveSheet()->getStyle(chr($i).$cont)->applyFromArray($centrado);
			}
		}
		$cont++;
	 }//query


$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('D3', "AUTOPISTA \n ".$tramo)
	->setCellValue('D4', 'Subtramo '.$subtramo)
	->setCellValue('C16', $fecha.$year);

$dir = "../Global/Sac/Avance/".$periodo."/".$tramo."/";		
if (is_dir($dir)){
  if ($dh = opendir($dir)){
	while (($file = readdir($dh)) !== false){
	  $ext = pathinfo($file, PATHINFO_EXTENSION);
	  if ($ext == 'jpg'){
			$objPHPExcel->setActiveSheetIndex(0);
			$objDrawing = new PHPExcel_Worksheet_Drawing();
			$objDrawing->setName('Portada');
			$objDrawing->setDescription('Portada');
			$objDrawing->setPath('../Global/Sac/Avance/'.$periodo.'/'.$tramo.'/portada.jpg');//
			$objDrawing->setCoordinates('C6');
			$objDrawing->setWidth(650);
			$objDrawing->setHeight(645);
			$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
		}
	}
	closedir($dh);
  }
}

$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('F2', $tramo )
			->setCellValue('F3', $subtramo )
			->setCellValue('D4', 'CADENAMIENTO: '.$cadenamiento )
			->setCellValue('H6', $mes." ".$year );


//$sheet = $objPHPExcel->getActiveSheet();
PHPExcel_Calculation::getInstance($objPHPExcel)
    ->getDebugLog()->setWriteDebugLog(true);

//testFormula($sheet,'I11');
PHPExcel_Calculation::getInstance($objPHPExcel)
    ->clearCalculationCache();

//testFormula($sheet,'I11');

//GRÁFICA			
$objPHPExcel->setActiveSheetIndex(5);



//////////////////////////////////////////////////////////////////////////////////////////////////////



$dataseriesLabels = array(
    new PHPExcel_Chart_DataSeriesValues('String', 'Datos!$E$8', null, 1),   //  2010
    new PHPExcel_Chart_DataSeriesValues('String', 'Datos!$F$8', null, 1),   //  2011
);
//  Set the X-Axis Labels
//      Datatype
//      Cell reference for data
//      Format Code
//      Number of datapoints in series
//      Data values
//      Data Marker
$xAxisTickValues = array(
    new PHPExcel_Chart_DataSeriesValues('String', 'Datos!$A$9:$A$20', null, 4),  //  Q1 to Q4
);
//  Set the Data values for each data series we want to plot
//      Datatype
//      Cell reference for data
//      Format Code
//      Number of datapoints in series
//      Data values
//      Data Marker
$dataSeriesValues = array(
    new PHPExcel_Chart_DataSeriesValues('Number', 'Datos!$E$9:$E$20', null, 4),
    new PHPExcel_Chart_DataSeriesValues('Number', 'Datos!$F$9:$F$20', null, 4),
);
//  Build the dataseries
$series = new PHPExcel_Chart_DataSeries(
    PHPExcel_Chart_DataSeries::TYPE_LINECHART,      // plotType
    PHPExcel_Chart_DataSeries::GROUPING_STACKED,    // plotGrouping
    range(0, count($dataSeriesValues)-1),           // plotOrder
    $dataseriesLabels,                              // plotLabel
    $xAxisTickValues,                               // plotCategory
    $dataSeriesValues                               // plotValues
);
//  Set the series in the plot area
$plotarea = new PHPExcel_Chart_PlotArea(null, array($series));
//  Set the chart legend
$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_TOPRIGHT, null, false);
$title = new PHPExcel_Chart_Title('Grafica');
$yAxisLabel = new PHPExcel_Chart_Title('Montos ($)');
$xAxisLabel = new PHPExcel_Chart_Title('Periodos');
//  Create the chart
$chart = new PHPExcel_Chart(
    'chart1',       // name
    $title,         // title
    $legend,        // legend
    $plotarea,      // plotArea
    true,           // plotVisibleOnly
    0,              // displayBlanksAs
    $xAxisLabel,    // xAxisLabel
    $yAxisLabel     // yAxisLabel
);
//  Set the position where the chart should appear in the worksheet
$chart->setTopLeftPosition('B5');
$chart->setBottomRightPosition('H31');
//  Add the chart to the worksheet
$objPHPExcel->getActiveSheet()->addChart($chart);

// Establecer índice de hoja activa a la primera hoja , por lo que Excel abre esto como la primera hoja
$objPHPExcel->setActiveSheetIndex(0); 
//**********************************************************************************************************************************
// Descargamos el archivo por el web browser (Excel2007)

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="SCT Mantto Menor.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->setIncludeCharts(TRUE);
//$objWriter->setPreCalculateFormulas(); 
$objWriter->save('php://output');
exit;
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
</head>
<body>
</body>
</html>