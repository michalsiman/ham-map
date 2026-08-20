<?php
// synchro Center of countrys from csv file to mysql db
//
//     0         1       2     3
// longitude,latitude,COUNTRY,ISO,COUNTRYAFF,AFF_ISO
// -170.7007316174498,-14.305711987770538,American Samoa,AS,United States,US
// 166.63800339749642,19.302045812215958,United States Minor Outlying Islands,UM,United States,US
// -159.78768870952257,-21.222613253399842,Cook Islands,CK,New Zealand,NZ
// -149.40041671099763,-17.674684080120677,French Polynesia,PF,France,FR
// -169.86878106699083,-19.05230921680393,Niue,NU,New Zealand,NZ
// -128.3149848627581,-24.366121747565458,Pitcairn,PN,United Kingdom,GB
// -172.44107655740137,-13.634252953274622,Samoa,WS,Samoa,WS


ini_set('display_errors', 1);
@ini_set('zlib.output_compression',0);
@ini_set('implicit_flush',1);
@ob_end_clean();
set_time_limit(0);
ini_set('user_agent', 'My-Application/2.5');
ini_set('memory_limit', '1024M'); // or you could use 1G
header( 'Content-type: text/html; charset=utf-8' );
// ----------

echo '<center><img src="../img/load-loading.gif" alt="Loading ..."><br />';
echo "Syncing Country codes ... ";
flush();

function download_file($url, $path) {

    $newfilename = $path;
    $file = fopen ($url, "rb");
    if ($file) {
      $newfile = fopen ($newfilename, "wb");
  
      if ($newfile)
      while(!feof($file)) {
        fwrite($newfile, fread($file, 1024 * 8 ), 1024 * 8 );
      }
    }
  
    if ($file) {
      fclose($file);
    }
    if ($newfile) {
      fclose($newfile);
    }
}

$datovy_soubor_cesta = dirname(__FILE__) ."/../data_files/countries_center.csv";

echo "csv file setup ... importing to db now ... wait ... ";
flush();

require __DIR__ . '/../settings/db_credentials.php';
$mysqli = new mysqli($host, $user, $pass, $db, $port);   // mysql db credentials
$mysqli->set_charset($charset);
$mysqli->query("SET collation_connection = $collation");

$pocet = 0;
$pocetvyrazenych = 0;

$file = fopen($datovy_soubor_cesta, "r");
fgets($file); // preskoceni jednoho radku v nactenem CSV souboru
while (($row = fgetcsv($file)) !== FALSE) {
    echo $row[3]." - ".$row[0]." - ".$row[1]."<br />";
    //$mysqli->query("UPDATE country SET center_longitude ='".$row[0]."', center_latitude = '".$row[1]."' WHERE code = '".$row[3]."' ");
    $pocet++;
}

echo "<br />$pocet records wrote! $pocetvyrazenych isnt valid for import!";

echo " ... done!<br /><br /><br />";
echo '<a href="../index.php">... go to main page ...</a></center>';

$mysqli->close();
