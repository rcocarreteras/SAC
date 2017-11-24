<title>SAC</title>
<?php
 // Se define la cadena de conexión  
 $dsn = "Driver={SQL Server};
 
 Server=192.168.130.129;Database=Sac;Integrated Security=SSPI;Persist Security Info=False;";
  
 // Se realiza la conexón con los datos especificados anteriormente
 $conn = odbc_connect( $dsn, 'sa', 'S1st3m45' );
 if (!$conn) { 
 exit( "Error al conectar: " . $conn);
 }

 // Se define la cadena de conexión  
 $dsn2 = "Driver={SQL Server};
 
 Server=192.168.130.129;Database=Sac;Integrated Security=SSPI;Persist Security Info=False;";
  
 // Se realiza la conexón con los datos especificados anteriormente
 $conn2 = odbc_connect( $dsn2, 'sa', 'S1st3m45' );
 if (!$conn2) { 
 exit( "Error al conectar: " . $conn2);
 }

 // Se define la cadena de conexión  
 $dsn3 = "Driver={SQL Server};
 
 Server=192.168.130.129;Database=Sac;Integrated Security=SSPI;Persist Security Info=False;";
  
 // Se realiza la conexón con los datos especificados anteriormente
 $conn3 = odbc_connect( $dsn3, 'sa', 'S1st3m45' );
 if (!$conn3) { 
 exit( "Error al conectar: " . $conn3);
 }
 
  // Se define la cadena de conexión  
 $dsn4 = "Driver={SQL Server};
 
 Server=192.168.130.129;Database=Sac;Integrated Security=SSPI;Persist Security Info=False;";
  
 // Se realiza la conexón con los datos especificados anteriormente
 $conn4 = odbc_connect( $dsn4, 'sa', 'S1st3m45' );
 if (!$conn4) { 
 exit( "Error al conectar: " . $conn4);
 }
 
   // Se define la cadena de conexión  
 $dsn5 = "Driver={SQL Server};
 
 Server=192.168.130.129;Database=Sac;Integrated Security=SSPI;Persist Security Info=False;";
  
 // Se realiza la conexón con los datos especificados anteriormente
 $conn5 = odbc_connect( $dsn5, 'sa', 'S1st3m45' );
 if (!$conn5) { 
 exit( "Error al conectar: " . $conn5);
 }
?> 