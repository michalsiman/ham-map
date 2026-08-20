<?php

// synchro BOTA from csv file to mysql db
// https://--------------------------
//
//
//    0        1         2       3      4      5     6        7
// "Scheme","DXCC","Reference","Name","Type","Lat","Long","Maidenhead"
// "UKBOTA",223,"B/G-0001","ROC Post Pavenham","ROC Bunker","52.189827","-0.580413","IO92RE"
// "UKBOTA",223,"B/G-0002","ROC Post Toddington","ROC Bunker","51.926889","-0.533749","IO91RW"
// "UKBOTA",223,"B/G-0003","ROC Post Ampthill","ROC Bunker","52.029724","-0.502983","IO92RA"
//
//

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
echo "Syncing BOTA ... ";
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

// locale storage of file with wwbota directory
$datovy_soubor_cesta = dirname(__FILE__) ."/../data_files/wwbota.csv";

// download actual .csv file from wwbota.org
download_file("https://wwbota.org/wwbota-3/", $datovy_soubor_cesta);

echo "csv file donwloaded ... saving to db now ... wait ... ";
flush();

require __DIR__ . '/../settings/db_credentials.php';
$mysqli = new mysqli($host, $user, $pass, $db, $port);   // mysql db credentials
$mysqli->set_charset($charset);
$mysqli->query("SET collation_connection = $collation");

// odstraneni _old tabulky uplne nakonec
$mysqli->query(" DROP TABLE IF EXISTS `bota_area_old`; ");
$mysqli->query(" DROP TABLE IF EXISTS `bota_area_pre`; ");

// založení nové tabulky
$mysqli->query(" CREATE TABLE IF NOT EXISTS bota_area_pre LIKE bota_area ");

$pocet = 0;
$pocetvyrazenych = 0;

$actualdate = date('Y-m-d');
echo "<br />Dnes je $actualdate.<br /><br />";

$file = fopen($datovy_soubor_cesta, "r");
fgets($file); // preskoceni jednoho radku v nactenem CSV souboru
while (($row = fgetcsv($file)) !== FALSE) {
    $row[1] = iconv('UTF-8','ASCII//TRANSLIT',$row[1]);
    $stmt = $mysqli->prepare("INSERT INTO bota_area_pre (scheme, reference, name, type, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?)");
    if ($row[0]!="" or $row[2]!="") {
      $stmt->bind_param("ssssss", $row[0], $row[2], $row[3], $row[4], $row[5], $row[6]);
      try {
        $stmt->execute();
      } catch (\Exception $e) {
        echo "$row[0], $row[2], $row[3], $row[4], $row[5], $row[6]<br />";
        echo "<br />Chyba $e";
      }
      //echo "$row[0], $row[2], $row[3], $row[4], $row[5], $row[6]<br />";
      $pocet++;
      } 
    
    //if($pocet>(20-1)) break; // po x radcich se import zastavi, pouzivam na testovani funkcnosti a ladeni
}

echo "<br />$pocet records wrote!";

// vyprazdneni aktualni tabulky, pote kopirovani z _pre a pak smazani _pre)
$mysqli->query(" TRUNCATE TABLE bota_area; ");
$mysqli->query(" INSERT INTO bota_area SELECT * FROM bota_area_pre; ");
$mysqli->query(" DROP TABLE IF EXISTS `bota_area_pre`; ");

echo " ... done!<br /><br /><br />";
echo '<a href="../index.php">... go to main page ...</a></center>';

$mysqli->close();