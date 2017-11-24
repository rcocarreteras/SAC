<?php
require_once('Connections/sac2.php');
 header( 'Content-type: text/html; charset=iso-8859-1' );

//print_r($_POST);

$tramo = $_GET['tramo'];
$actividad = $_GET['actividad'];
$cantidad = $_GET['cantidad'];
$abrev = $_GET['abrev'];
$periodo = $_GET['periodo'];

//$periodo = date("Ym");

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
    	//echo "Se envio una imagen.";
		// En versiones de PHP anteriores a la 4.1.0, debería utilizarse $HTTP_POST_FILES en lugar de $_FILES.		
		$carpeta="../Global/Sac/Avance/".$periodo."/". $tramo ."/". $abrev ."/";
		if (!file_exists($carpeta)) {
    		//mkdir($path, 0700);
			mkdir($carpeta, 0777, true);
		}
			$destino = $carpeta . "1.jpg";//rename file
			$file = basename($destino);
			$i=1;
			while(file_exists($destino)){
				$destino = $carpeta . "1".$i.".jpg";//rename file
				 $i++;
			 }
			
		move_uploaded_file($_FILES["archivo1"]["tmp_name"], $destino);  
		//print_r(basename($destino));
    	//print_r($_FILES);
	}else{
		//echo "No se envio nada";
	}
	
	if ($_FILES["archivo2"]["size"] > 0) {
		$carpeta="../Global/Sac/Avance/".$periodo."/". $tramo ."/". $abrev ."/";
		if (!file_exists($carpeta)) {
    		//mkdir($path, 0700);
			mkdir($carpeta, 0777, true);
		}
			$destino = $carpeta . "2.jpg";//rename file
			$file = basename($destino);
			$i=1;
			while(file_exists($destino)){
				$destino = $carpeta . "2".$i.".jpg";//rename file
				 $i++;
			 }
			
		move_uploaded_file($_FILES["archivo2"]["tmp_name"], $destino);  
		//print_r(basename($destino));
    	//print_r($_FILES);
	}else{
		//echo "No se envio nada";
	}
	
	if ($_FILES["archivo3"]["size"] > 0) {    	
		$carpeta="../Global/Sac/Avance/".$periodo."/". $tramo ."/". $abrev ."/";
		if (!file_exists($carpeta)) {
    		//mkdir($path, 0700);
			mkdir($carpeta, 0777, true);
		}
			$destino = $carpeta . "3.jpg";//rename file
			$file = basename($destino);
			$i=1;
			while(file_exists($destino)){
				$destino = $carpeta . "3".$i.".jpg";//rename file
				 $i++;
			 }
			
		move_uploaded_file($_FILES["archivo3"]["tmp_name"], $destino);  
		//print_r(basename($destino));
    	//print_r($_FILES);
	}else{
		//echo "No se envio nada";
	}
	
	if ($_FILES["archivo4"]["size"] > 0) {    	
		$carpeta="../Global/Sac/Avance/".$periodo."/". $tramo ."/";
		if (!file_exists($carpeta)) {
    		//mkdir($path, 0700);
			mkdir($carpeta, 0777, true);
		}		
		$destino = $carpeta . "portada.jpg";//rename file
		move_uploaded_file($_FILES["archivo4"]["tmp_name"], $destino);  
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
				<h1>Fotos de Avance Diario</h1>
				<h2><strong>Tramo:</strong> <?php echo $tramo; ?></h2>
                <h2><strong>Actividad:</strong> <?php echo $actividad; ?> <strong>Abreviatura:</strong> <?php echo $abrev; ?></h2>
                <h2><strong>N&uacute;mero de fotograf&iacute;as:</strong> <?php echo $cantidad; ?></h2>
			</div>
            
            <center><h3><strong>SELECCIONE PARA CARGAR IMAGENES</strong></h3></center>
    		<br><br>
    		<center>
            <form action="" method="post" enctype="multipart/form-data">
            <table width="80%" border="0">
                <tr>
                    <td> <input type="file" name="archivo1" id="archivo1" accept="image/png, image/jpeg, image/jpg, image/bmp"> </td>
                    <td> <input type="file" name="archivo2" id="archivo2" accept="image/png, image/jpeg, image/jpg, image/bmp"> </td>
                    <td> <input type="file" name="archivo3" id="archivo3" accept="image/png, image/jpeg, image/jpg, image/bmp"> </td>
                    <td> <input type="file" name="archivo4" id="archivo4" accept="image/png, image/jpeg, image/jpg, image/bmp"> </td>
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
					<div class="tj_nav">
						<span id="tj_prev" class="tj_prev">Previous</span>
						<span id="tj_next" class="tj_next">Next</span>
					</div>
                    <div class="col-lg-10">
                        <div class="tj_wrapper">
                            <ul class="tj_gallery">
                            
                             <?php
                                 $carpeta="../Global/Sac/Avance/".$periodo."/". $tramo ."/". $abrev ."/";		 
                                     if(is_dir($carpeta)){
                                        if($dir = opendir($carpeta)){	
                                            //echo $directorio;
                                            while ($archivo = readdir($dir)){ //obtenemos un archivo y luego otro sucesivamente							
                                                if (is_dir($archivo)){//verificamos si es o no un directorio	    					
                                                 //echo "[".$archivo . "]<br />"; //de ser un directorio lo envolvemos entre corchetes
                                                }else{												
                                                    //echo $archivo;
                                                     if ($archivo!='Thumbs.db'){	
                                                        echo "<li><a href='#'><img src='". $carpeta.$archivo ."' alt='image01' height='190' width='190' onclick='myfunction(\"".$carpeta.$archivo."\")'  /></a></li>";	
                                                     }							
                                                }
                                            }
                                        }
                                     }
                                ?>                            
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2">
					<div class="tj_wrapper">
						<ul class="tj_gallery">                        
                        <h1>Portada</h1>                        
                         <?php
						 
						 $dir = "../Global/Sac/Avance/".$periodo."/".$tramo."/";		
							if (is_dir($dir)){
							  if ($dh = opendir($dir)){
								while (($file = readdir($dh)) !== false){
								  $ext = pathinfo($file, PATHINFO_EXTENSION);
								  if ($ext == 'jpg'){
									  echo "<li><a href='#'><img src='". $dir."portada.jpg' alt='image01' height='190' width='190' onclick='myfunction(\"".$dir."portada.jpg\")'  /></a></li>";
									}
								}
								closedir($dh);
							  }
							}
							?>                            
						</ul>
					</div>
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
            text: 'Antes',
            validators: {
                maxWidth:  3648,
                maxHeight: 2736
            },
            reject: function(file, errors) {
                if (errors.mimeType) {
                    alert(file.name + ' debe ser una imagen.');
                }

                if (errors.maxWidth) {
                    alert(file.name + ' ancho incorrecto.');
                }

                if (errors.maxHeight) {
                    alert(file.name + ' alto incorrecto.');
                }
            }
        });
		 $('[id="archivo2"]').ezdz({
            text: 'Durante',
            validators: {
                maxWidth:  3648,
                maxHeight: 2736
            },
            reject: function(file, errors) {
                if (errors.mimeType) {
                    alert(file.name + ' debe ser una imagen.');
                }

                if (errors.maxWidth) {
                    alert(file.name + ' ancho incorrecto.');
                }

                if (errors.maxHeight) {
                    alert(file.name + ' alto incorrecto.');
                }
            }
        });
		$('[id="archivo3"]').ezdz({
            text: 'Despues',
            validators: {
                maxWidth:  3648,
                maxHeight: 2736
            },
            reject: function(file, errors) {
                if (errors.mimeType) {
                    alert(file.name + ' debe ser una imagen.');
                }

                if (errors.maxWidth) {
                    alert(file.name + ' ancho incorrecto.');
                }

                if (errors.maxHeight) {
                    alert(file.name + ' alto incorrecto.');
                }
            }
        });
		$('[id="archivo4"]').ezdz({
            text: 'Portada',
            validators: {
                maxWidth:  3648,
                maxHeight: 2736
            },
            reject: function(file, errors) {
                if (errors.mimeType) {
                    alert(file.name + ' debe ser una imagen.');
                }

                if (errors.maxWidth) {
                    alert(file.name + ' ancho incorrecto.');
                }

                if (errors.maxHeight) {
                    alert(file.name + ' alto incorrecto.');
                }
            }
        });
    </script>
</body>
</html>