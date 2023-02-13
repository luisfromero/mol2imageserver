<?php
//header('Access-Control-Allow-Origin: *');
//header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
//header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
$method = $_SERVER['REQUEST_METHOD'];
if($method == "OPTIONS") {
    die();
}
$seed=$hq=$debug=false;
//print_r($_GET);
if(isset($_GET['debug']))$debug=true;
if(isset($_GET['hq']))$hq=true;
if(isset($_GET['seed']))$seed=(int)($_GET['seed']);
if($debug){
	#!/usr/bin/php
ini_set("display_errors", 1);
ini_set("track_errors", 1);
ini_set("html_errors", 1);
error_reporting(E_ALL);
}
$host= gethostname();
//print_r($_SERVER);
//echo $_SERVER['LOCAL_ADDR'].$host;

$link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
if(!isset($_GET['mol'])){echo "Usage: $link?mol=DB00114&num=6   [&debug] [&seed] [&hq]";exit;}
$molecule=$_GET['mol'];
if(isset($_GET['num']))$num=$_GET['num'];else$num=8;
//if(isset($_GET['debug']))$debug=true;


//*****************************************************************************************************************
//               Si no existe el archivo mol2, lo descargamos de la base de datos y lo convertimos con openbabel
//				 la versión .mol es utilizada por el visor 3D de chemdoodle
//*****************************************************************************************************************
if($host=="cactus")
	{
	$mol2file="mol2/$molecule.mol2";
	$molfile="mol/{$molecule}.mol";
	$codeexec="d:/onedrive/proyectos/cudaMol/cmake-build-debug-visual-studio/Debug/mol2image.exe $molecule.mol2 $num";
	$convert="\"C:\\Program Files\\OpenBabel-2.4.1\\obabel\" --title {$molecule} -i mol d:\\datos\\mol2imageserver\\mol\\{$molecule}.mol -o ml2 -O d:\\datos\\mol2imageserver\\mol2\\{$molecule}.mol2";
	}
else
	{
	// Este es el final
	$hq=$hq?"-Q":"";
	$seed="-s".($seed?$seed:mt_rand()); //If not set in mol2images, seed=0
	$mol2file="/var/molecules/mol2/$molecule.mol2";
	$molfile="/var/www/mol2imageserver/mol/{$molecule}.mol";
	$codeexec="/opt/mol2image/mol2image $hq $seed $molecule.mol2 $num";
	$convert="obabel --title {$molecule} -i mol $molfile -o ml2 -O $mol2file";
	}


if(!file_exists($mol2file)){
		$path="https://go.drugbank.com/structures/small_molecule_drugs/{$molecule}.mol";
		if($debug)	echo "downloading $path<br>";
		if(!($src = file_get_contents($path)) ){echo "negativo";exit;}
		
		file_put_contents($molfile, $src);

		if($debug)echo "<br>$convert<br>";
		$salida=shell_exec(($convert));
		$lineas=preg_split('/\r\n|\r|\n/', $salida);
		for($i=0;$i<count($lineas)-1;$i++)
		{
			if($debug)echo "<a href=$lineas[$i] target=_blank >$lineas[$i]</a><br>";
			else echo "$lineas[$i]<br>";
		}

		}

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
	if($debug)echo "<a href=mol2images/$lineas[$i] target=_blank >mol2images/$lineas[$i]</a><br>";
	else echo "mol2images/$lineas[$i]<br>";
}
if($debug)
	for($i=0;$i<count($lineas)-1;$i++)
	{
		echo "<img src=mol2images/$lineas[$i]><br>";
	}

if($num==count($lineas)-1)if($debug)echo "<br><b>Command executed succesfully!. </b><br><br>";

$execution_time = ($time_end - $time_start);

//execution time of the script
if($debug)echo '<b>Total Execution Time:</b> '.$execution_time.' secs';


