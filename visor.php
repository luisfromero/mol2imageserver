<?php
/**
 * @file
 * @brief Visualiza una pareja de moléculas
 * @author Felipe Romero, Luis F. Romero
 * 
 * Muestra dos canas en los que se visualizan dos moléculas en 3D que 
 * se pueden rotar solidariamente.
 * 
 * El código Javascript es que se encarga de casi todo
 * 
 */

//header('Access-Control-Allow-Origin: *');
//header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
//header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
$method = $_SERVER['REQUEST_METHOD'];
if($method == "OPTIONS")     die();

function generaScriptPreparaArchivos()
{
	global $link,$_SERVER;
	$link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://".$_SERVER['HTTP_HOST'];
	// Two ways to access the viewer: molserver.feliperomero.es/visor vs molfinder.ual.es/moserver
	//echo ;
	$molserver = strpos(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),"molserver")===false; ///< El servidor es molfinder.ual.es/moserver
	$urlc=$link.($molserver?"/mol/":"/molserver/mol/"); ///< Url donde están las moléculas
	echo "\nconsole.log('Descargando $urlc'+molecula1);";
	echo "\nChemDoodle.io.file.content('$urlc'+molecula1,callback1 );";
	echo "\nconsole.log('Descargando $urlc'+molecula2);";
	echo "\nChemDoodle.io.file.content('$urlc'+molecula2,callback2 );";

}

//*****************************************************************************************************************
//               Generamos la página
//*****************************************************************************************************************
?>

<html>
<head>
<meta http-equiv="" content="chrome=1">

<link rel="stylesheet" href="css/ChemDoodleWeb.css" type="text/css">
<script type="text/javascript" src="js/ChemDoodleWeb.js"></script>
<script type="text/javascript" src="js/visor.js"></script>

</head>


<body>


<script>
//****************************************************************************************
// Tras definir el tamaño, creamos los canvas, capturamos los eventos
//****************************************************************************************
	var wh=500;
	let canvas1 = new ChemDoodle.TransformCanvas3D('canvas1', wh, wh);
	let canvas2 = new ChemDoodle.TransformCanvas3D('canvas2', wh, wh);
	cargaInicial(canvas1,nombre1);
	cargaInicial(canvas2,nombre2);
</script>
	
<script>
	if(true){

	//callback1=function(fileContent) {estilo(canvas1);carga(canvas1,fileContent,nombre1);}
	//callback2=function(fileContent) {estilo(canvas2);carga(canvas2,fileContent,nombre2);}
	
	//Estas funciones se deben llamar cuando se sabe que los archivos mol están disponibles
	// Los 1969 de la base de datos original ya están
	// Y los del query se generan a la vez que las imágenes aleatorias, así que 

	<?php
	generaScriptPreparaArchivos();
	?>
	
	}
	/* Ejemplo uso servicio en la nube
	if(UrlExists('http://molserver.feliperomero.es/mol/'+molecula2))
		ChemDoodle.io.file.content('http://molserver.feliperomero.es/mol/'+molecula2,callback2 );
	else
	{
		ChemDoodle.iChemLabs.getMoleculeFromDatabase(nombre1, {'database': 'pubchem'}, function(mol,canvas) {
		recarga(mol,canvas);});			
	}
	*/


</script>
<br><h1><input type=checkbox id=cb  onclick='contador=0;' checked>Mover solidariamente</h1>
</html>