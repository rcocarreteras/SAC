<?php
 $dsn = "Driver={SQL Server};
 
 Server=192.168.130.129;Database=Sac;Integrated Security=SSPI;Persist Security Info=False;";
  
 // Se realiza la conexón con los datos especificados anteriormente
 $conn = odbc_connect( $dsn, 'sa', 'S1st3m45' );
 if (!$conn) { 
 exit( "Error al conectar: " . $conn);
 }
 
header( 'Content-type: text/html; charset=iso-8859-1' );

/** Error reporting */
error_reporting(E_ALL);
date_default_timezone_set('America/Mexico_City');

/** PHPExcel */
include ('../PHPExcel/Classes/PHPExcel.php');
include ('../PHPExcel/Classes/PHPExcel/IOFactory.php');


if (isset($_POST['Descargar'])){

	$semana = $_POST['Semana'];
	$l = $_POST['l'];
	$m = $_POST['m'];
	$mr = $_POST['mr'];
	$j = $_POST['j'];
	$v = $_POST['v'];
	$s = $_POST['s'];
	$d = $_POST['d'];
	
}else{
	$semana = "Del 2016-05-02 al 2016-05-08";
}

//Creamos un Documento nuevo
$objPHPExcel = new PHPExcel();

//Cargamos un documento
$objPHPExcel = PHPExcel_IOFactory::load("../Sac/xls/Prenomina.xlsx");

//Propiedades del documento
$objPHPExcel->getProperties()->setCreator("SAC")
							 ->setLastModifiedBy("SAC")
							 ->setTitle("Formato de Prenómina")
							 ->setSubject("Formato de Prenómina")
							 ->setDescription("Formato de Prenómina SAC")
							 ->setKeywords("Formato de Prenómina SAC")
							 ->setCategory("SAC Prenómina");


  $cont=14;			
  $sql = "SELECT * FROM Prenomina where semana='".$semana."' ORDER BY NOMBRE";
  //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }    
  while ( odbc_fetch_row($rs) ) {
	  $noemp = odbc_result($rs, 'NO_EMP');
	  $nombre = utf8_encode(odbc_result($rs, 'NOMBRE'));
	  $ubicacion = odbc_result($rs, 'UBICACION');
	  $dia1 = odbc_result($rs, 'DIA1');
	  $dia2 = odbc_result($rs, 'DIA2');
	  $dia3 = odbc_result($rs, 'DIA3');
	  $dia4 = odbc_result($rs, 'DIA4');
	  $dia5 = odbc_result($rs, 'DIA5');
	  $dia6 = odbc_result($rs, 'DIA6');
	  $dia7 = odbc_result($rs, 'DIA7');
	  $ext2 = odbc_result($rs, 'EXTRA2');
	  $ext3 = odbc_result($rs, 'EXTRA3');
	  $festivo = odbc_result($rs, 'FEST_LAB');
	  $descanso = odbc_result($rs, 'DESC_LAB');
	  $prima = odbc_result($rs, 'PRIMA_DOM');
	  $otro = odbc_result($rs, 'OTRO');
	  $observaciones = odbc_result($rs, 'OBSERVACIONES');
		
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('F12', $l)   	
			->setCellValue('G12', $m)			
			->setCellValue('H12', $mr)     	   
    		->setCellValue('I12', $j)
			->setCellValue('J12', $v)
			->setCellValue('K12', $s)
			->setCellValue('L12', $d)
			->setCellValue('C9', $semana)   	
			->setCellValue('D5', $ubicacion)			
			->setCellValue('C'.$cont, $noemp)     	   
    		->setCellValue('D'.$cont,$nombre)
			->setCellValue('F'.$cont, $dia1)
			->setCellValue('G'.$cont, $dia2)
			->setCellValue('H'.$cont, $dia3)
			->setCellValue('I'.$cont, $dia4)
			->setCellValue('J'.$cont, $dia5)
			->setCellValue('K'.$cont, $dia6)
			->setCellValue('L'.$cont, $dia7)
			->setCellValue('N'.$cont, $ext2)
			->setCellValue('O'.$cont, $ext3)
			->setCellValue('P'.$cont, $festivo)
			->setCellValue('Q'.$cont, $descanso)
			->setCellValue('R'.$cont, $prima)
			->setCellValue('S'.$cont, $otro)
			->setCellValue('T'.$cont, $observaciones);   			
				
	$cont++;
  }			

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Prenómina');

// Establecer índice de hoja activa a la primera hoja , por lo que Excel abre esto como la primera hoja
$objPHPExcel->setActiveSheetIndex(0);

//**********************************************************************************************************************************
// Descargamos el archivo por el web browser (Excel2007)

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Formato de Prenómina.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
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