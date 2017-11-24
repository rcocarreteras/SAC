<?php
require_once('Connections/sac2.php');
header( 'Content-type: text/html; charset=iso-8859-1' );
date_default_timezone_set('America/Mexico_City');
//print_r($_POST);

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

/***************************************************FILTRO*************************************************/    
    if (isset($_REQUEST['filtro'])) {
        $baseFiltro = $_POST["baseFiltro"];
        $rango = $_POST["rango"];
        $fecha1 = explode("*", $rango);
    
        if ($baseFiltro == "TODOS"){
            $sql = "SELECT DISTINCT(TRAMO) FROM CatTramos";         
        }else{
            $sql = "SELECT DISTINCT(TRAMO) FROM CatTramos WHERE TRAMO = '".$baseFiltro."'";     
        }
        
    }else{
        
        $sql = "SELECT DISTINCT(TRAMO) FROM CatTramos";// WHERE BASE='TO01'
        
    }   

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title id='Description'>jqxChart Line serie symbols and custom labels</title>  
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />
    <link rel="stylesheet" href="CssLocal/menuSac.css"><!--Necesario para Menu 1--> 
    <link href="css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="scripts/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="scripts/demos.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdraw.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxchart.core.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdata.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxinput.js"></script>
    <link href="css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="js/bootstrap-dialog.min.js"></script>
    <script type="text/javascript" src="js/jqueryFileTree.js"></script>

      
    <link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />
    <script type="text/javascript" src="scripts/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdata.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdraw.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxchart.core.js"></script>
    <style type="text/css">
        /*FUENTES*/
        @font-face {
            font-family: 'ubuntu_titlingbold';
            src: url('Fuentes/ubuntu/UbuntuTitling-Bold-webfont.eot');
            src: url('Fuentes/ubuntu/UbuntuTitling-Bold-webfont.eot?#iefix') format('embedded-opentype'),
            url('Fuentes/ubuntu/UbuntuTitling-Bold-webfont.woff') format('woff'),
            url('Fuentes/ubuntu/UbuntuTitling-Bold-webfont.ttf') format('truetype'),
            url('Fuentes/ubuntu/UbuntuTitling-Bold-webfont.svg#ubuntu_titlingbold') format('svg');          
            font-weight: normal;
            font-style: normal;
        }
        @font-face {    
            font-family: 'commandocommando';
            src: url('Fuentes/commando/commando-webfont.eot');
            src: url('Fuentes/commando/commando-webfont.eot?#iefix') format('embedded-opentype'), 
            url('Fuentes/commando/commando-webfont.woff') format('woff'), 
            url('Fuentes/commando/commando-webfont.ttf') format('truetype'), 
            url('Fuentes/commando/commando-webfont.svg#commandocommando') format('svg');
            font-weight: normal;
            font-style: normal;
        }
        header{
            margin:0;           
            background: #2c2c2c;
            height:65px; 
            width:100%;
            border-bottom: 3px solid #0057B3;
        }
        header img{         
            float: left;
        }
        header span{            
            float: left;
            padding-top: 20px;
            width: 140px;
            height: 65px;
            background: #2c2c2c;
            border-bottom: 3px solid #0057B3;

            font-family: 'ubuntu_titlingbold';
            font-size: 20px;
            text-align: center;
            color: white;
        }

        /*IDENTIFICADORES*/
        #contenido{
            width: 100%;
            height: auto;

        }
        #encabezadoFijo{
            margin:0;
            padding-top: 20px;          
            padding-left: 20px;
            background:white;           
            font-family: 'commandocommando';
            font-size: 18px;
            color:white; 
            text-align:left;
            height:60px;
            width:100%;
            color:#000; 
            z-index: 1;    
        }

        /*CLASES*/
        .izquierda{
            float: left;
            margin-left: 60px;
            color:#000;
        }
        .derecha{
            float: right;
            /*margin-right: 10px;*/
            color:#000;
        }
        .fixed{
            position:fixed;
            border-bottom: 2px solid #0057B3; 
            top:0           
        }
        .titulo{            
            font-family: ubuntu_titlingbold;
            font-size: 20px;
            background-color:#C0DCF3;
            
                }
        .tabla{         
            font-family: ubuntu_titlingbold;
            font-size: 14px;
            color:#104A7A;
        }
        .tabla2{            
            font-family: ubuntu_titlingbold;
            font-size: 13px;
        }
        .base{          
            font-family: ubuntu_titlingbold;
            font-size: 15px;
            color:#00507C;
        }
        
        /*EFECTOS*/
        header span:hover {
            text-decoration: none;
            background: #49A2FF;
            color:black;
        }
        tbody tr:nth-child(even){
            background-color: #f2f2f2;
        }
        tbody tr:hover{
            background-color:#acd0e9;
        }

    </style>
    <script type="text/javascript">
        $(document).ready(function () {
            // prepare chart data as an array
            var sampleData = [
                    { Day: 'Monday', Running: 30, Swimming: 10, Cycling: 25, Goal: 40 },
                    { Day: 'Tuesday', Running: 25, Swimming: 15, Cycling: 10, Goal: 50 },
                    { Day: 'Wednesday', Running: 30, Swimming: 10, Cycling: 25, Goal: 60 },
                    { Day: 'Thursday', Running: 40, Swimming: 20, Cycling: 25, Goal: 40 },
                    { Day: 'Friday', Running: 45, Swimming: 20, Cycling: 25, Goal: 50 },
                    { Day: 'Saturday', Running: 30, Swimming: 20, Cycling: 30, Goal: 60 },
                    { Day: 'Sunday', Running: 20, Swimming: 30, Cycling: 10, Goal: 90 }
                ];
            // prepare jqxChart settings
            var settings = {
                title: "Fitness & exercise weekly scorecard",
                description: "Time spent in vigorous exercise by activity",
                enableAnimations: true,
                showLegend: true,
                padding: { left: 10, top: 10, right: 15, bottom: 10 },
                titlePadding: { left: 90, top: 0, right: 0, bottom: 10 },
                source: sampleData,
                colorScheme: 'scheme05',
                xAxis: {
                    dataField: 'Day',
                    unitInterval: 1,
                    tickMarks: { visible: true, interval: 1 },
                    gridLinesInterval: { visible: true, interval: 1 },
                    valuesOnTicks: false,
                    padding: { bottom: 10 }
                },
                valueAxis: {
                    unitInterval: 10,
                    minValue: 0,
                    maxValue: 50,
                    title: { text: 'Time in minutes<br><br>' },
                    labels: { horizontalAlignment: 'right' }
                },
                seriesGroups:
                    [
                        {
                            type: 'line',
                            series:
                            [
                                {
                                    dataField: 'Swimming',
                                    symbolType: 'square',
                                    labels:
                                    {
                                        visible: true,
                                        backgroundColor: '#FEFEFE',
                                        backgroundOpacity: 0.2,
                                        borderColor: '#7FC4EF',
                                        borderOpacity: 0.7,
                                        padding: { left: 5, right: 5, top: 0, bottom: 0 }
                                    }
                                },
                                {
                                    dataField: 'Running',
                                    symbolType: 'square',
                                    labels:
                                    {
                                        visible: true,
                                        backgroundColor: '#FEFEFE',
                                        backgroundOpacity: 0.2,
                                        borderColor: '#7FC4EF',
                                        borderOpacity: 0.7,
                                        padding: { left: 5, right: 5, top: 0, bottom: 0 }
                                    }
                                }
                            ]
                        }
                    ]
            };
            // setup the chart
            $('#chartContainer').jqxChart(settings);
        });
    </script>
</head>
<body>
    <header>
        <a href="index.php"><img class="derecha" src="images/cerrarsesion.png"></a>
        <a href="Almacen.php"><span>Insumos</span></a>
        <a href="Salidas.php"><span>Salida Insumos</span></a>
        <a href="AlmacenMaq.php"><span>Maquinaria</span></a>
        <a href="SalidasMaq.php"><span>Entrada Maq</span></a>
        <a href="AvanceDiarioPlus.php"><span>Avance Diario</span></a>
        <a href="Contratos.php"><span>Contratos</span></a>
        <a href="Comparativo.php"><span>Comparativa</span></a>
    </header>
    <div id="contenido">
    <br>
        <center>
        <table width="auto" height="auto" border="1" id="tabla">
            <thead>
            <tr align="center" class="titulo">
              <td width="100"></td>
              <td colspan="2">Mano de Obra</td>
              <td width="163" colspan="2">Maquinaria</td>        
              <td width="126" colspan="2">Materiales</td>
              <td width="126" colspan="2">Contratos</td>
              <td width="126" colspan="2">Acumulados</td>
            </tr>
            <tr align="center" class="titulo">
              <td width="100">Mes</td>
              <td width="150">Programado</td>
              <td width="150">Ejecutado</td>         
              <td width="150">Programado</td>
              <td width="150">Ejecutado</td>
              <td width="150">Programado</td>
              <td width="150">Ejecutado</td>
              <td width="150">Programado</td>
              <td width="150">Ejecutado</td>
              <td width="150">Programado</td>
              <td width="150">Ejecutado</td>
            </tr>
          </thead>
          <tbody>


        
          </tbody>
        </table>
        <div id='chartContainer' style="width:850px; height:500px">
        </div>
        </center>
    </div>

</body>
</html>