<?php
// synchro Countrys ISO 3316-2 from csv file to mysql db
// https://raw.githubusercontent.com/lukes/ISO-3166-Countries-with-Regional-Codes/master/all/all.csv
//
//
//   0     1  
// name,alpha-2,alpha-3,country-code,iso_3166-2,region,sub-region,intermediate-region,region-code,sub-region-code,intermediate-region-code
// Afghanistan,AF,AFG,004,ISO 3166-2:AF,Asia,Southern Asia,"",142,034,""
// Ă…land Islands,AX,ALA,248,ISO 3166-2:AX,Europe,Northern Europe,"",150,154,""
// Albania,AL,ALB,008,ISO 3166-2:AL,Europe,Southern Europe,"",150,039,""
// Algeria,DZ,DZA,012,ISO 3166-2:DZ,Africa,Northern Africa,"",002,015,""
// American Samoa,AS,ASM,016,ISO 3166-2:AS,Oceania,Polynesia,"",009,061,""
// Andorra,AD,AND,020,ISO 3166-2:AD,Europe,Southern Europe,"",150,039,""


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

// locale storage of file with wwff directory
$datovy_soubor_cesta = dirname(__FILE__) ."/../data_files/countrys-iso3316-2.csv";

// download actual .csv file from wwff.co
download_file("https://raw.githubusercontent.com/lukes/ISO-3166-Countries-with-Regional-Codes/master/all/all.csv", $datovy_soubor_cesta);

echo "csv file donwloaded ... importing to db now ... wait ... ";
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
    echo $row[0]." - ".$row[1]."<br />";
    $potacode=$row[1]."-";
    $wwffcode="";
    //$stmt = $mysqli->prepare("INSERT INTO country (name, code, wwff_code, pota_code) VALUES (?, ?, ?, ?)");
    //$stmt->bind_param("ssss", $row[0], $row[1], $wwffcode, $potacode);
    //$stmt->execute();
    $pocet++;
}

echo "<br />$pocet records wrote! $pocetvyrazenych isnt valid for import!";

echo " ... done!<br /><br /><br />";
echo '<a href="../index.php">... go to main page ...</a></center>';

$mysqli->close();
