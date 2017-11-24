<?php 
require_once('Connections/sac2.php'); 

$x=0;
$filas5 = array();
$sql = "SELECT * FROM Rud WHERE ESTATUS = 'COMPLETO' ";
 //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }   //OBTENEMOS TODA LA FILA 
 while ($row = odbc_fetch_array($rs)) {    
  $filas5[$x] = array_map('utf8_encode',$row);    
   $x++;    
 }//While
$rud =  $filas5; 
//echo json_encode($rud); 


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title id='Description'>The sample illustrates how to add custom CSS styles to Grid cells under specific conditions.</title>
    <link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />
    <script type="text/javascript" src="scripts/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdata.js"></script> 
    <script type="text/javascript" src="jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxscrollbar.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxmenu.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcheckbox.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxlistbox.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdropdownlist.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxgrid.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxgrid.sort.js"></script> 
    <script type="text/javascript" src="jqwidgets/jqxgrid.pager.js"></script> 
    <script type="text/javascript" src="jqwidgets/jqxgrid.selection.js"></script> 
    <script type="text/javascript" src="jqwidgets/jqxgrid.edit.js"></script> 
    <script type="text/javascript" src="scripts/demos.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {       
			
			var data =  <?php echo json_encode($rud); ?>;				    
		    var source =
            {
                datafields: [                    	
					{ name: 'NOMBRE', type: 'string' },
					{ name: 'UNIDAD', type: 'string' },
					{ name: 'CANTIDAD', type: 'integer' },
					{ name: 'CANTIDAD_AD', type: 'integer' },
					{ name: 'DIFERENCIA', type: 'integer' }                   		
                ],
                localdata: data
            };
			//PARA UN SOLO CAMPO
          /* var cellclass = function (row, columnfield, value) {				
                if (value < 0) {
                    return 'red';
                }
               /* else if (value >= 20 && value < 50) {
                    return 'yellow';
                }
                else return 'green';
            }//esto es lo que se usa:  cellclassname: cellclass */
			
			var cellsrenderer = function (row, column, value, defaultHtml) {
                if (row == 0 || row == 2 || row == 5) {
					alert(column);									
                    var element = $(defaultHtml);
                    element.css({ 'background-color': 'Yellow', 'width': '100%', 'height': '100%', 'margin': '0px' });
                    return element[0].outerHTML;
                }
                return defaultHtml; //esto es lo que se usa:  cellsrenderer: cellsrenderer
            }
            var dataAdapter = new $.jqx.dataAdapter(source, {
                downloadComplete: function (data, status, xhr) { },
                loadComplete: function (data) { },
                loadError: function (xhr, status, error) { }
            });
            // initialize jqxGrid
            $("#jqxgrid").jqxGrid(
            {
                width: 850,
                source: dataAdapter,                
                pageable: true,
                autoheight: true,
                sortable: true,
                altrows: true,
                enabletooltips: true,
                editable: true,
                selectionmode: 'multiplecellsadvanced',
                columns: [
                  { text: 'Product Name', datafield: 'NOMBRE', width: 250},
                  { text: 'Quantity per Unit', datafield: 'UNIDAD', cellsalign: 'right', align: 'right', width: 120 },
                  { text: 'Cantidad', datafield: 'CANTIDAD', align: 'right', cellsalign: 'right', cellsformat: 'c2', width: 100 },
                  { text: 'Cantidad AD', datafield: 'CANTIDAD_AD', cellsalign: 'right', width: 100, cellsrenderer: cellsrenderer },
				  { text: 'Diferencia', datafield: 'DIFERENCIA', cellsalign: 'right', width: 100 }                 
                ]
            });
        });
    </script>
</head>
<body class='default'>
       <style>     
        .green {
            color: black\9;
            background-color: #b6ff00\9;
        }
        .yellow {
            color: black\9;
            background-color: yellow\9;
        }
        .red {
            color: black\9;
            background-color: #e83636\9;
        }
        .green:not(.jqx-grid-cell-hover):not(.jqx-grid-cell-selected), .jqx-widget .green:not(.jqx-grid-cell-hover):not(.jqx-grid-cell-selected) {
            color: black;
            background-color: #b6ff00;
        }
        .yellow:not(.jqx-grid-cell-hover):not(.jqx-grid-cell-selected), .jqx-widget .yellow:not(.jqx-grid-cell-hover):not(.jqx-grid-cell-selected) {
            color: black;
            background-color: yellow;
        }
        .red:not(.jqx-grid-cell-hover):not(.jqx-grid-cell-selected), .jqx-widget .red:not(.jqx-grid-cell-hover):not(.jqx-grid-cell-selected) {
            color: black;
            background-color: #e83636;
        }
    </style>
    <div id='jqxWidget' style="font-size: 13px; font-family: Verdana; float: left;">
        <div id="jqxgrid">
        </div>
     </div>
</body>
</html>