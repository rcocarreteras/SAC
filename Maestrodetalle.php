<?php 
require_once('Connections/sac2.php'); 
//MAESTRO
$x=0;
$filas3 = array();
$sql = "SELECT AVANCE_ID, ACTIVIDAD_ID, ACTIVIDAD, UNIDAD, KM_INI, KM_FIN, CUERPO, ZONA, CANTIDAD, OBSERVACIONES, SOB_ID FROM AvanceDiario";
 //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }   
 while ($row = odbc_fetch_array($rs)) {    
  $filas3[$x] = array_map('utf8_encode',$row);    
   $x++;    
 }//While
$maestro =  $filas3; 
//echo json_encode($maestro); 
//----------------------------------------------------------------------------------------------------
//DETALLE
$x=0;
$filas4 = array();
$sql = "SELECT * FROM ManoDeObra";
 //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }   
 while ($row = odbc_fetch_array($rs)) {    
  $filas4[$x] = array_map('utf8_encode',$row);    
   $x++;    
 }//While
$detalle =  $filas4; 
echo json_encode($detalle); 


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title id='Description'>This example shows how to implement Master-Details binding scenario with two Grids.</title>
    <link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />
    <script type="text/javascript" src="Scripts/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxscrollbar.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxmenu.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxgrid.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxgrid.selection.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxgrid.columnsresize.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxgrid.pager.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxlistbox.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdropdownlist.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdata.js"></script>
    <script type="text/javascript" src="scripts/demos.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            // MAESTRO
			var data =  <?php echo json_encode($maestro); ?>;	
		    var source =
            {
                datafields: [
				    { name: 'AVANCE_ID', type: 'number' },
                    { name: 'ACTIVIDAD_ID', type: 'number' },
                    { name: 'ACTIVIDAD', type: 'string' },
                    { name: 'UNIDAD', type: 'string' },                    
                    { name: 'KM_INI', type: 'string' },
                    { name: 'KM_FIN', type: 'string' },
					{ name: 'CUERPO', type: 'string' },  
					{ name: 'ZONA', type: 'string' },
					{ name: 'CANTIDAD', type: 'number' },					
					{ name: 'OBSERVACIONES', type: 'string' },
					{ name: 'SOB_ID', type: 'string' }					
                ],
                localdata: data
            };
            var dataAdapter = new $.jqx.dataAdapter(source);				
			

            $("#maestro").jqxGrid(
            {
                width: 850,
                height: 250,
                source: dataAdapter,
                
                keyboardnavigation: false,
                columns: [
                    { text: 'Company Name', datafield: 'AVANCE_ID', width: 250 },
                    { text: 'Contact Name', datafield: 'ACTIVIDAD_ID', width: 150 },
                    { text: 'Contact Title', datafield: 'ACTIVIDAD', width: 180 },
                    { text: 'City', datafield: 'UNIDAD', width: 120 }                   					
                ]
            });

            // DETALLE            	
            var dataFields = [
                    { name: 'AVANCE_ID', type: 'number' },
                    { name: 'EMPLEADO_ID', type: 'number' },
                    { name: 'NOMBRE', type: 'string' },                    
                    { name: 'HORAS', type: 'number' }  
                    ];

            var source =
            {
                datafields: dataFields,
                localdata: <?php echo json_encode($detalle); ?>
				
            };

            var dataAdapter = new $.jqx.dataAdapter(source);
            dataAdapter.dataBind();
			
			

            $("#maestro").on('rowselect', function (event) {
                var maestro = event.args.row.AVANCE_ID;
                var records = new Array();
                var length = dataAdapter.records.length;
                for (var i = 0; i < length; i++) {
                    var record = dataAdapter.records[i];
                    if (record.AVANCE_ID == maestro) {
                        records[records.length] = record;
                    }
                }

                var dataSource = {
                    datafields: dataFields,
                    localdata: records
                }
                var adapter = new $.jqx.dataAdapter(dataSource);
        
                // update data source.
                $("#detalle").jqxGrid({ source: adapter });
            });

            $("#detalle").jqxGrid(
            {
                width: 850,
                height: 250,
                keyboardnavigation: false,
                columns: [
                    { text: 'Empleado', datafield: 'EMPLEADO_ID', width: 100 },
                    { text: 'Nombre', datafield: 'NOMBRE',  width: 150 },
                    { text: 'Hroas', datafield: 'HORAS',  width: 150 }                   
                ]
            });

            $("#maestro").jqxGrid('selectrow', 0);
        });
    </script>
</head>
<body class='default'>
    <div id='jqxWidget' style="font-size: 13px; font-family: Verdana; float: left;">
        <h3>
            Customers</h3>
        <div id="maestro">
        </div>
        <h3>
            Orders by Customer</h3>
        <div id="detalle">
        </div>
    </div>
</body>
</html>
