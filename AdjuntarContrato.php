<?php
require_once('Connections/sac2.php');

//print_r($GET);

if(isset($_GET["id_contrato"])) {
    $folio = $_GET['id_contrato'];
    $contrato = $_GET['contrato'];
    $empresa = $_GET['empresa'];
}

if (!isset($_SESSION)) {
  session_start();
}
$login_ok = $_SESSION['login_ok'];
//RESTRINGIMOS EL ACCESO A USUARIOS NO IDENTIFICADOS

if ($login_ok == "identificado"){
	
}else{
echo "No Identificado";	
	session_destroy();  // destroy the session 
	header("Location: index.php");
}

if(isset($_POST["upload"])) {
	if ($_FILES["archivo1"]["size"] > 0) {    			
		$carpeta="../Global/Sac/Contratos/". $folio ."/";
		if (!file_exists($carpeta)) {
    		//mkdir($path, 0700);
			mkdir($carpeta, 0777, true);
		}		
		$destino = $carpeta ."Licitacion.rar";
		move_uploaded_file($_FILES['archivo1']['tmp_name'], $destino);   
    	print_r($destino);
	}else{
		//echo "No se envio nada";
	}
	
	if ($_FILES["archivo2"]["size"] > 0) {
		$carpeta="../Global/Sac/Contratos/". $folio ."/";
		if (!file_exists($carpeta)) {
    		//mkdir($path, 0700);
			mkdir($carpeta, 0777, true);
		}		
		//$destino = $carpeta . basename($_FILES['archivo2']['name']);
		$destino = $carpeta ."Cuadro comparativo.pdf";
        move_uploaded_file($_FILES['archivo2']['tmp_name'], $destino);   
    	//print_r($_FILES);
	}else{
		//echo "No se envio nada";
	}
	
	if ($_FILES["archivo3"]["size"] > 0) {    	
		$carpeta="../Global/Sac/Contratos/". $folio ."/";;
		if (!file_exists($carpeta)) {
    		//mkdir($path, 0700);
			mkdir($carpeta, 0777, true);
		}		
		//$destino = $carpeta . basename($_FILES['archivo3']['name']);
        $destino = $carpeta ."Contrato.pdf";
		move_uploaded_file($_FILES['archivo3']['tmp_name'], $destino);   
    	//print_r($_FILES);
	}else{
		//echo "No se envio nada";
	}
    if ($_FILES["archivo4"]["size"] > 0) {      
        $carpeta="../Global/Sac/Contratos/". $folio ."/";;
        if (!file_exists($carpeta)) {
            //mkdir($path, 0700);
            mkdir($carpeta, 0777, true);
        }       
        //$destino = $carpeta . basename($_FILES['archivo4']['name']);
        $destino = $carpeta ."Finiquito.pdf";
        move_uploaded_file($_FILES['archivo4']['tmp_name'], $destino);   
        //print_r($_FILES);
    }else{
        //echo "No se envio nada";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Adjuntar Imagenes</title>
    <link rel="stylesheet" href="js/jquery.ezdz.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/Album/css/style.css" type="text/css" />
	<link rel="stylesheet" href="css/Album/css/gridNavigation.css" type="text/css" />
	<link rel='stylesheet' href='http://fonts.googleapis.com/css?family=PT+Sans+Narrow' type='text/css' />
	<link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Oswald' type='text/css' />    
  
    <script src="http://code.jquery.com/jquery.min.js"></script>
    <script src="js/jquery.ezdz.min.js"></script>
   
    <style type="text/css">
        .photo
        {
            width: 700px;
            height: 350px;
            background-color: #000;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
    <script type="text/javascript">
	
	function myfunction(ruta,archivo){			
			ventana=window.open("","ventana","resizable=yes, location=no");			
			ventana.document.write('<html><head><title>Visor de imagen</title></head><body style= "overflow-x:hidden; overflow-y:scroll; marginwidth=0; marginheight=0; topmargin=0; bottommargin=0; leftmargin=0; rightmargin=0;"><img src="' + ruta + '" onLoad="opener.redimensionar(this.width, this.height)">');
			ventana.document.close();
			//alert(ruta);	
		}
		
		function redimensionar(ancho, alto){
			ventana.resizeTo(ancho+12,alto+28);
			ventana.moveTo((screen.width-ancho)/2,(screen.height-alto)/2); //centra la ventana. Eliminar si no se quiere centrar el popup
		}
		
        $(document).ready(function () {						
			//COMIENZA EL VISOR DE IMAGENES
			$('#photoGallery').jqxScrollView({ width: 900, height: 550, buttonsOffset: [0, 0]});
            $('#StartBtn').jqxButton({ theme: theme });
            $('#StopBtn').jqxButton({ theme: theme });
			$('#Actualizar').jqxButton({ theme: theme });
         
		 
            $('#StartBtn').click(function () {
                $('#photoGallery').jqxScrollView({ slideShow: true });
            });
            $('#StopBtn').click(function () {
                $('#photoGallery').jqxScrollView({ slideShow: false });
            });
			
			$('#Actualizar').click(function() {
           		// Recargo la página
            	location.reload();
        	});
			
			$('#tj_container').gridnav({
				type	: {
					mode		: 'disperse', 	// use def | fade | seqfade | updown | sequpdown | showhide | disperse | rows
					speed		: 500,			// for fade, seqfade, updown, sequpdown, showhide, disperse, rows
					easing		: '',			// for fade, seqfade, updown, sequpdown, showhide, disperse, rows	
					factor		: '',			// for seqfade, sequpdown, rows
					reverse		: ''			// for sequpdown
				}
			});
							     				
            
        });//Fin $(document).ready
    </script>
    
    
</head>
<body>


 
    
    <center> 
   </center> 
   <div class="container">
			<div class="header">
				<h1>Contrato: <span><?php echo $contrato; ?></span></h1>
				<h2>Empresa <?php echo $empresa; ?></h2>
			</div>
            
            <center><h3><strong>SELECCIONE PARA CARGAR IMAGENES</strong></h3></center>
    		<br><br>
    		<center>
            <form action="" method="post" enctype="multipart/form-data">
            <table width="80%" border="0">
                <tr>
                    <td> <input type="file" name="archivo1" id="archivo1"> </td>
                    <td> <input type="file" name="archivo2" id="archivo2" accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf, image/*"> </td>
                    <td> <input type="file" name="archivo3" id="archivo3" accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf, image/*"> </td>
                    <td> <input type="file" name="archivo4" id="archivo4" accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf, image/*"> </td>                            
                </tr>
                <tr><td><br></td></tr>
                <tr>
                    <td><button type="submit" class="btn btn-primary" name="upload" id="upload">Adjuntar Todo</button> </td>
                </tr>                
            </table>
             
                
            </form>
            </center>
            <hr>            
			<div class="content example8">
				<div id="tj_container" class="tj_container">
					<!--<div class="tj_nav">
						<span id="tj_prev" class="tj_prev">Previous</span>
						<span id="tj_next" class="tj_next">Next</span>
					</div>-->
					<div class="tj_wrapper">
						<ul class="tj_gallery">
                        
                         <?php
							 $carpeta="../Global/Sac/Contratos/". $folio ."/";
							 
    						 if(is_dir($carpeta)){
       		 				 	if($dir = opendir($carpeta)){	
									//echo $directorio;
									while ($archivo = readdir($dir)){ //obtenemos un archivo y luego otro sucesivamente							
										if (is_dir($archivo)){//verificamos si es o no un directorio	    					
										 //echo "[".$archivo . "]<br />"; //de ser un directorio lo envolvemos entre corchetes
										}else{
											//echo $archivo;
											if ($archivo!='Thumbs.db'){
												echo "<a href='". $carpeta.$archivo ."' target='_blank'><h1><span>".$archivo."</span></h1></a>";	
											}
										}
									}
								}
							 }
							?>                            
						</ul>
					</div>
				</div>
			</div>
			<div class="more">
				<ul> 
				</ul>
			</div>			
		</div>
    
    <script>
        $('[id="archivo1"]').ezdz({
            text: 'Licitación.rar',
            validators: {
                maxWidth:  3648,
                maxHeight: 2736
            },
            reject: function(file, errors) {
                if (errors.mimeType) {
                    alert(file.name + ' -- Favor de adjuntar un archivo.rar');
                }

                if (errors.maxWidth) {
                    alert(file.name + ' ');
                }

                if (errors.maxHeight) {
                    alert(file.name + ' ');
                }
            }
        });
		 $('[id="archivo2"]').ezdz({
            text: 'Fallo.pdf',
            validators: {
                maxWidth:  3648,
                maxHeight: 2736
            },
            reject: function(file, errors) {
                if (errors.mimeType) {
                    alert(file.name + ' must be an image or document.');
                }

                if (errors.maxWidth) {
                    alert(file.name + ' ');
                }

                if (errors.maxHeight) {
                    alert(file.name + ' ');
                }
            }
        });
		$('[id="archivo3"]').ezdz({
            text: 'Contrato.pdf',
            validators: {
                maxWidth:  3648,
                maxHeight: 2736
            },
            reject: function(file, errors) {
                if (errors.mimeType) {
                    alert(file.name + ' must be an image or document.');
                }

                if (errors.maxWidth) {
                    alert(file.name + ' ');
                }

                if (errors.maxHeight) {
                    alert(file.name + ' ');
                }
            }
        });
        $('[id="archivo4"]').ezdz({
            text: 'Finiquito.pdf',
            validators: {
                maxWidth:  3648,
                maxHeight: 2736
            },
            reject: function(file, errors) {
                if (errors.mimeType) {
                    alert(file.name + ' must be an image or document.');
                }

                if (errors.maxWidth) {
                    alert(file.name + ' ');
                }

                if (errors.maxHeight) {
                    alert(file.name + ' ');
                }
            }
        });
    </script>
</body>
</html>