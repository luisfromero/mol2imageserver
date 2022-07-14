<?php
//header('Access-Control-Allow-Origin: *');
//header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
//header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
$method = $_SERVER['REQUEST_METHOD'];
if($method == "OPTIONS") {
    die();
}
$debug=false;
if($debug){
	
	
	#!/usr/bin/php
ini_set("display_errors", 1);
ini_set("track_errors", 1);
ini_set("html_errors", 1);
error_reporting(E_ALL);
}

if(!isset($_GET['mol'])){echo "Usage https://molserver.feliperomero.es?mol=DB00114&num=6";exit;}
$molecule=$_GET['mol'];
if(isset($_GET['num']))$num=$_GET['num'];else$num=4;

//*****************************************************************************************************************
//               Si no existe el archivo mol2, lo descargamos de la base de datos y lo convertimos con openbabel
//*****************************************************************************************************************


if(!file_exists("mol2/$molecule.mol2")){
	//$debug=true;
	$path="https://go.drugbank.com/structures/small_molecule_drugs/{$molecule}.mol";
	if($debug)
	echo "downloading $path<br>";
	if(!($src = file_get_contents($path)) ){echo "negativo";exit;}
	$archivo="mol/{$molecule}.mol";
	file_put_contents($archivo, $src);
	$convert="\"C:\\Program Files\\OpenBabel-2.4.1\\obabel\" --title {$molecule} -i mol d:\\datos\\mol2imageserver\\mol\\{$molecule}.mol -o ml2 -O d:\\datos\\mol2imageserver\\mol2\\{$molecule}.mol2";
	//$convert="\"C:\\Program Files\\OpenBabel-2.4.1\\obabel\" -H";
	if($debug)echo "<br>$convert<br>";
	$salida=shell_exec(($convert));
	//echo $salida;
	$lineas=preg_split('/\r\n|\r|\n/', $salida);
	for($i=0;$i<count($lineas)-1;$i++)
	{
		if($debug)echo "<a href=$lineas[$i] target=_blank >$lineas[$i]</a><br>";
		else echo "$lineas[$i]<br>";
	}

}

$molecula="mol2/$molecule.mol2";


$codeexec="d:/onedrive/proyectos/cudaMol/cmake-build-debug-visual-studio/Debug/mol2image.exe $molecula $num";

//*****************************************************************************************************************
//               Obtiene N vistas
//*****************************************************************************************************************


if($debug)echo "<br>Executing: $codeexec<br><br>";
$time_start = microtime(true); 
$archivos=shell_exec($codeexec);
$lineas=preg_split('/\r\n|\r|\n/', $archivos);
$time_end = microtime(true); 
//*****************************************************************************************************************
//               Mostramos los archivos generados (salida del shell_exec)
//*****************************************************************************************************************


for($i=0;$i<count($lineas)-1;$i++)
{
	if($debug)echo "<a href=$lineas[$i] target=_blank >$lineas[$i]</a><br>";
	else echo "$lineas[$i]<br>";
}

if($num==count($lineas)-1)if($debug)echo "<br><b>Command executed succesfully!. </b><br><br>";

$execution_time = ($time_end - $time_start);

//execution time of the script
if($debug)echo '<b>Total Execution Time:</b> '.$execution_time.' secs';


