<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>sin título</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 0.14" />
    <link href="css/bootstrap.min.css" rel="stylesheet" /> 
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript">
	/*<![CDATA[*/
	var c=0;
	function newInput()
	{
		var inpt = document.createElement('input');
		inpt.type="text";
		inpt.name="empleado"+c;
		inpt.id="empleado"+c;
		c+=1;
		document.f1.appendChild(inpt);
		document.f1.innerHTML+="<br/>";
	}
	/*]]>*/
	</script>
</head>

<body>
<a href="javascript:newInput()" ><img src="images/AddUser.png" width="60" height="59" /></a>
<form action="session.php" method="get"  name="f1">


</form>	
</body>
</html>