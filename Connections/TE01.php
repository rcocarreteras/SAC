 <title>Biometrico</title>
<?php
 // Se define la cadena de conexión  
 $dsn2 = "Driver={SQL Server};
 Server=192.168.108.120\MSSQLSERVER,1433;Database=CNLEHFDB;";
  
 // Se realiza la conexón con los datos especificados anteriormente
 $conn2 = odbc_connect( $dsn2, 'biometrico', 'JoyaSistemas2014' );
 if (!$conn2) { 
 exit( "Error al conectar: " . $conn2);
 }
?> 