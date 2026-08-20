<?php
require __DIR__ . '/settings/db_credentials.php';


$ip = $_REQUEST['REMOTE_ADDR']; // the IP address to query
$query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
if($query && $query['status'] == 'success') {
    $autocountrycode = $query['countryCode'];
} else {
    $autocountrycode = "";
}


$countrycode = htmlspecialchars($_POST['countrycode']);
$wwff = htmlspecialchars($_POST['wwff']);
$pota = htmlspecialchars($_POST['pota']);
$sota = htmlspecialchars($_POST['sota']);
$gma = htmlspecialchars($_POST['gma']);
$bota = htmlspecialchars($_POST['bota']);

$activated = htmlspecialchars($_POST['activated']);
if ($activated=="") $activated="all";

//if($autocountrycode!="" and $countrycode==""){
//    $countrycode = $autocountrycode;
//    $wwff ="Yes";
//    $pota ="Yes";
//    $sota ="Yes";
//    $gma ="Yes";
//}

if($countrycode=="") $countrycode="*";

if($countrycode=="*"){
    $wwff = "Yes";
    $pota = "";
    $sota = "";
    $gma = "";
    $bota = "";
}

// Available values : cs, de, el, en, es, fr, it, nl, pl, pt, ru, sk, tr, uk
$mlang = "en";
//if(strtolower($autocountrycode)=="cz") $mlang = "cs";
//if(strtolower($autocountrycode)=="sk") $mlang = "sk";
//if(strtolower($autocountrycode)=="fr") $mlang = "fr";
//if(strtolower($autocountrycode)=="de") $mlang = "de";
//if(strtolower($autocountrycode)=="pl") $mlang = "pl";
//if(strtolower($autocountrycode)=="it") $mlang = "it";


$mysqli = new mysqli($host, $user, $pass, $db, $port);   // mysql db credentials

$mysqli->set_charset($charset);



?><html lang="en">
<head>
   <title>HAM-MAP.com - Outdoor activity HAM map by OK1SIM</title>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
   <meta name="robots" content="noindex, nofollow" />
   <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
   <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css"/>
   <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css"/>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.81.0/dist/L.Control.Locate.min.css" />
   <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
   <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.81.0/dist/L.Control.Locate.min.js" charset="utf-8"></script>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
   <link rel="stylesheet" href="slidemenu/L.Control.SlideMenu.css">
   <script src="slidemenu/L.Control.SlideMenu.js"></script>
   <style>
       body {
        padding: 0;
        margin: 0;
       }
       html, body {
        height: 100%;
        width: 100vw;
       }
       #map {
        height: 100%;
        width: 100vw;
       }
       .myControl {
        background-color: white;
        border: 1px solid black;
        padding: 4px;
        border-radius: 5px;
       }
       a:link { text-decoration: none; }
       a:visited { text-decoration: none; }
       a:hover { text-decoration: none; }
       a:active { text-decoration: none; }
       .myloading {
            text-align: center;
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            display: block;
        }
        .centered-div {
            width: 200px;
            height: 200px;
            border: 2px solid black;
            border-radius: 50%;
        }
        img.huechange { filter: hue-rotate(150deg); }
        .content {
        margin: 0.25rem;
        border-top: 1px solid #000;
        padding-top: 0.5rem;
        font-size: 15px;
        }
        .notes {
        font-size: 0.7em;
        color: #000;
        }
        .header {
        font-size: 1.8rem;
        color: #7f7f7f;
        }
        .bottom {
        margin-top: 32px;
        font-size: 0.8rem;
        color: #7f7f7f;
        }
        .title {
        font-size: 1.1rem;
        color: #7f7f7f;
        font-weight: bold;
        }
   </style>
</head>
<body>    
    <div class="myloading" id="myloading"><br /><br /><img src="img/spinner.gif" /><br /><br /><br />QRX few seconds please ...<br /><br />building map for you<br /><br /><a href="https://www.toplist.cz/stat/1836411/"><script language="JavaScript" type="text/javascript" charset="utf-8">
<!--
document.write('<img src="https://toplist.cz/count.asp?id=1836411&logo=btn&start=2213&http='+
encodeURIComponent(document.referrer)+'&t='+encodeURIComponent(document.title)+'&l='+encodeURIComponent(document.URL)+
'&wi='+encodeURIComponent(window.screen.width)+'&he='+encodeURIComponent(window.screen.height)+'&cd='+
encodeURIComponent(window.screen.colorDepth)+'" width="80" height="15" border=0 alt="TOPlist" />');
//--></script><noscript><img src="https://toplist.cz/count.asp?id=1836411&logo=btn&start=2213&njs=1" border="0"
alt="TOPlist" width="80" height="15" /></noscript></a></div>
    <div id="map"></div>
    <script>
        
        // own API key for api.mapy.cz - get one at https://developer.mapy.cz/
        const API_KEY = '<?php echo htmlspecialchars(getenv('MAPY_CZ_API_KEY') ?: '', ENT_QUOTES); ?>';

        // map definition with starting point and zoom level
        var map = L.map('map', {
        <?php
        if($countrycode=="*") { $countrycodee=""; } else { $countrycodee=$countrycode; }
        $query = "SELECT * FROM country WHERE code = '$countrycodee';";
        $result = $mysqli->query($query);
        while($row = $result->fetch_assoc()) {
            $clatitude = $row["center_latitude"];
            $clongitude = $row["center_longitude"];
        }
        if ($clatitude!="" and $clongitude!="") {
            echo "center: [$clatitude, $clongitude],".PHP_EOL;
            echo "zoom: 5".PHP_EOL;
        } else {
            echo "center: [50.0271658, 14.3201225],".PHP_EOL;
            echo "zoom: 3".PHP_EOL;
        }
        ?>
        });


        // definition of layers (type of maps)
        const tileLayers = {
        'Basic map': L.tileLayer(`https://api.mapy.cz/v1/maptiles/basic/256/{z}/{x}/{y}?apikey=${API_KEY}&lang=<?php echo strtolower($mlang); ?>`, {
            minZoom: 0,
            maxZoom: 19,
            attribution: '<a href="https://wwff.co/">WWFF</a> | <a href="https://www.ok1omg.cz/">OK1OMG</a> | <a href="https://api.mapy.cz/copyright" target="_blank">&copy; Seznam.cz a.s. a další</a>',
        }),
        'Tourist map': L.tileLayer(`https://api.mapy.cz/v1/maptiles/outdoor/256/{z}/{x}/{y}?apikey=${API_KEY}&lang=<?php echo strtolower($mlang); ?>`, {
            minZoom: 0,
            maxZoom: 19,
            attribution: '<a href="https://wwff.co/">WWFF</a> | <a href="https://www.ok1omg.cz/">OK1OMG</a> | <a href="https://api.mapy.cz/copyright" target="_blank">&copy; Seznam.cz a.s. a další</a>',
        }),
        'Photo map': L.tileLayer(`https://api.mapy.cz/v1/maptiles/aerial/256/{z}/{x}/{y}?apikey=${API_KEY}&lang=<?php echo strtolower($mlang); ?>`, {
            minZoom: 0,
            maxZoom: 19,
            attribution: '<a href="https://wwff.co/">WWFF</a> | <a href="https://www.ok1omg.cz/">OK1OMG</a> | <a href="https://api.mapy.cz/copyright" target="_blank">&copy; Seznam.cz a.s. a další</a>',
        }),
        //'OpenStreet map': L.tileLayer('//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        //    minZoom: 0,
        //    maxZoom: 19,
        //    attribution: '<a href="https://wwff.co/">WWFF</a> | <a href="https://www.ok1omg.cz/">OK1OMG</a> | &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        //}),
    };

    var myIconSota = L.icon({
        iconUrl: 'img/icon-blue.png',
        iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [0, -43]
    });

    var myIconPota = L.icon({
        iconUrl: 'img/icon-green.png',
        iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [0, -43]
    });

    var myIconWwff = L.icon({
        iconUrl: 'img/icon-red.png',
        iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [0, -43]
    });
    
    var myIconGma = L.icon({
        iconUrl: 'img/icon-violet.png',
        iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [0, -43]
    });

    var myIconBota = L.icon({
        iconUrl: 'img/icon-orange.png',
        iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [0, -43]
    });

    // default layers add to map
    tileLayers['Tourist map'].addTo(map);

    // show layer control on map
    L.control.layers(tileLayers).addTo(map);

    // go to actual possition
    L.control.locate({ flyTo : false,
       returnToPrevBounds : true,
       strings: { title: "Show my QTH!" },
       locateOptions: { maxZoom: 12 }
    }).addTo(map);


    // define logo
    const LogoControl = L.Control.extend({
        options: {
            position: 'bottomleft',
        },

        onAdd: function (map) {
            const container = L.DomUtil.create('div');
            const link = L.DomUtil.create('a', '', container);

            link.setAttribute('href', 'http://mapy.cz/');
            link.setAttribute('target', '_blank');
            link.innerHTML = '<img decoding="async" src="https://api.mapy.cz/img/api/logo.svg" />';
            L.DomEvent.disableClickPropagation(link);

            return container;
        },
    });
        
    // add logo to map
    new LogoControl().addTo(map);

    <?php
    if($countrycode=="*") {
    ?>
    // add marker with first information popup
    L.marker([50.6271658, 14.3201225]).addTo(map)
        .bindPopup('<center><strong>Scroll, click and enjoy!</strong><br /><br /><a href="https://ham-map.com/">ham-map.com</a><br /><br />for community<br />by Michal <a href="https://www.qrz.com/db/OK1SIM/">OK1SIM<a/><br /><strong>(club <a href="https://www.ok1omg.cz/">OK1OMG</a>)</strong><br /><br /><a href="https://wwff.co/"><img src="https://wwff.co/wwff_cont/uploads/2016/02/makenatureyourshack.png" width="70%"></a><br /><a href="https://wwff.co/">wwff.co</a></center>', { minWidth : 150 }).openPopup()
        ._icon.classList.add("huechange");
    <?php
    }
    ?>

    // start work with marker cluster
    var markers = L.markerClusterGroup({
        showCoverageOnHover: false,
        zoomToBoundsOnClick: true
    });


<?php

$markersonmap = 0;

if ($countrycode!="*") { 
    $query = "SELECT * FROM country WHERE code = '$countrycode';";
} else { $query = "SELECT * FROM country"; }

$result = $mysqli->query($query);
while($row = $result->fetch_assoc()) {
    $wwffcode = $row["wwff_code"];
    $sotacode = $row["sota_code"];
    $potacode = $row["pota_code"];
    $gmacode = $row["gma_code"];
    $botacode = $row["bota_code"];
    }

if($wwffcode=="") $wwffcode="none-never-found"; // pokud neexistuje code pro aktivitu nastavi se nesmysl ktery nemuze nikdy byt nalezen
if($potacode=="") $potacode="none-never-found";
if($sotacode=="") $sotacode="none-never-found";
if($gmacode=="") $gmacode="none-never-found";
if($botacode=="") $botacode="none-never-found";

if($countrycode=="*") $wwffcode=""; // pokud je nastaveny cely svet, pak se smaze kod pro aktivitu u WWFF aby se zobrazily vsechny z db



//----------------------------------------------------------------------------------
//----------------------------------------------------------------------------------
//----------------------------------------------------------------------------------
//----------------------------------------------------------------------------------
//----------------------------------------------------------------------------------



// vlozeni znacek WWFF do clustergroupy ....
if ($wwff=="Yes") {

if ($activated=="all") $query = "SELECT * FROM wwff_area WHERE reference LIKE '$wwffcode%' AND status='active' AND (latitude<>'' AND latitude <>'0.00000') AND (longitude<>'' AND latitude <>'0.00000');";
if ($activated=="activated") $query = "SELECT * FROM wwff_area WHERE reference LIKE '$wwffcode%' AND qsoCount>0 AND status='active' AND (latitude<>'' AND latitude <>'0.00000') AND (longitude<>'' AND latitude <>'0.00000');";
if ($activated=="nonactivated") $query = "SELECT * FROM wwff_area WHERE reference LIKE '$wwffcode%' AND qsoCount=0 AND status='active' AND (latitude<>'' AND latitude <>'0.00000') AND (longitude<>'' AND latitude <>'0.00000');";

//$query = "SELECT * FROM wwff_area WHERE reference LIKE '$wwffcode%';";
//qsoCount

$result = $mysqli->query($query);

while($row = $result->fetch_assoc()) {
    if ($row["qsoCount"]==0) $row["qsoCount"]="none";
    if ($row["lastAct"]=="1980-01-01") $row["lastAct"]="none";

   echo "   markers.addLayer(L.marker([".$row["latitude"].", ".$row["longitude"]."], { icon: myIconWwff }).bindPopup('<strong>WWFF</strong><br />".$row["reference"]."<br /> ".htmlspecialchars($row['name'])."<br />Total QSO: ".$row["qsoCount"]."<br />Last QSO: ".$row["lastAct"]."'));".PHP_EOL;
   $markersonmapwwff++;

   }
    
}
//----------------------------------------------------------------------------------


// vlozeni znacek SOTA do clustergroupy ....
if ($sota=="Yes") {

$querys = "SELECT * FROM sota_area WHERE reference LIKE '$sotacode%'";
$results = $mysqli->query($querys);

while($row = $results->fetch_assoc()) {

    echo "   markers.addLayer(L.marker([".$row["latitude"].", ".$row["longitude"]."], { icon: myIconSota }).bindPopup('<strong>SOTA</strong><br />".$row["reference"]."<br /> ".htmlspecialchars($row['name'])."<br />Alt: ".$row["altitude"]."m<br />Activations: ".$row["activation"]."'));".PHP_EOL;
    $markersonmapsota++;

    }

}
//----------------------------------------------------------------------------------



// vlozeni znacek POTA do clustergroupy ....
if ($pota=="Yes") {

$querys = "SELECT * FROM pota_area WHERE reference LIKE '$potacode%'";
$results = $mysqli->query($querys);

while($row = $results->fetch_assoc()) {

    echo "   markers.addLayer(L.marker([".$row["latitude"].", ".$row["longitude"]."], { icon: myIconPota }).bindPopup('<strong>POTA</strong><br />".$row["reference"]."<br /> ".htmlspecialchars($row['name'])."'));".PHP_EOL;
    //echo "   layer._path.classList.add('huechange');".PHP_EOL;
    $markersonmappota++;

    }

}
//----------------------------------------------------------------------------------






// vlozeni znacek GMA do clustergroupy ....
if ($gma=="Yes") {

$querys = "SELECT * FROM gma_area WHERE reference LIKE '$gmacode%'";
$results = $mysqli->query($querys);

while($row = $results->fetch_assoc()) {

    echo "   markers.addLayer(L.marker([".$row["latitude"].", ".$row["longitude"]."], { icon: myIconGma }).bindPopup('<strong>GMA</strong><br />".$row["reference"]."<br /> ".htmlspecialchars($row['name'])."'));".PHP_EOL;
    $markersonmapgma++;

    }

}
//----------------------------------------------------------------------------------







// vlozeni znacek BOTA do clustergroupy ....
if ($bota=="Yes") {

    $querys = "SELECT * FROM bota_area WHERE scheme LIKE '$botacode%'";
    $results = $mysqli->query($querys);
    
    while($row = $results->fetch_assoc()) {
    
        echo "   markers.addLayer(L.marker([".$row["latitude"].", ".$row["longitude"]."], { icon: myIconBota }).bindPopup('<strong>BOTA</strong><br />".$row["reference"]."<br /> ".htmlspecialchars($row['name'])."'));".PHP_EOL;
        $markersonmapbota++;
    
        }
    
    }
    //----------------------------------------------------------------------------------
    




?>

    map.addLayer(markers);

    
    /* contents */
    const left = '<div class="header">HAM-MAP.com</div>';
    let contents = `
        <div class="content">
            <div class="title">View options:</div>
            <p></p>
            Country (Code):<br />
            <form action="" method="post">
            <select name="countrycode" >
                <option value='*'<?php if($countrycode=="*") echo " selected"; ?>>All world (WWFF only)</option>
                <?php
                $querys = "SELECT * FROM country ORDER BY name";
                $results = $mysqli->query($querys);
                while($row = $results->fetch_assoc()) {
                    
                    // count wwff/pota/sota/gma markers in database
                    $wwffcode = $row["wwff_code"];
                    if($wwffcode=="") $wwffcode="none-never-found";
                    $wwffno = mysqli_num_rows($mysqli->query("SELECT name FROM wwff_area WHERE reference LIKE '$wwffcode%';"));

                    $potacode = $row["pota_code"];
                    if($potacode=="") $potacode="none-never-found";
                    $potano = mysqli_num_rows($mysqli->query("SELECT name FROM pota_area WHERE reference LIKE '$potacode%';"));
                    
                    $sotacode = $row["sota_code"];
                    if($sotacode=="") $sotacode="none-never-found";
                    $sotano = mysqli_num_rows($mysqli->query("SELECT name FROM sota_area WHERE reference LIKE '$sotacode%';"));

                    $gmacode = $row["gma_code"];
                    if($gmacode=="") $gmacode="none-never-found";
                    $gmano = mysqli_num_rows($mysqli->query("SELECT name FROM gma_area WHERE reference LIKE '$gmacode%';"));

                    $botacode = $row["bota_code"];
                    if($botacode=="") $botacode="none-never-found";
                    $botano = mysqli_num_rows($mysqli->query("SELECT name FROM bota_area WHERE scheme LIKE '$botacode%';"));


                    if (($wwffno + $potano + $sotano + $gmano + $botano)>0) {
                            // if all together is ZERO than not shown in list of country
                            echo "<option value='".$row['code']."'";
                            if($countrycode==$row['code']) echo " selected";
                            echo ">".substr($row['name'], 0, 17)." (".$row['code'].")";
                            //echo " ".$wwffno."/".$potano."/".$sotano."/".$gmano."/".$botano;
                            echo "</option>";
                            echo PHP_EOL;
                            }
                    }
                ?>
            </select>
            <br />
            <br />
            Show activity on map (if there is):<br />
            <img src=\"img/icon-red.png\" height=\"14\"> <input type="checkbox" name="wwff" value="Yes"<?php if ($wwff=="Yes") echo " checked"?>> WWFF<br />
            <img src=\"img/icon-green.png\" height=\"14\"> <input type="checkbox" name="pota" value="Yes"<?php if ($pota=="Yes") echo " checked"?>> POTA<br />
            <img src=\"img/icon-blue.png\" height=\"14\"> <input type="checkbox" name="sota" value="Yes"<?php if ($sota=="Yes") echo " checked"?>> SOTA<br />
            <img src=\"img/icon-violet.png\" height=\"14\"> <input type="checkbox" name="gma" value="Yes"<?php if ($gma=="Yes") echo " checked"?>> GMA<br />
            <img src=\"img/icon-orange.png\" height=\"14\"> <input type="checkbox" name="bota" value="Yes"<?php if ($bota=="Yes") echo " checked"?>> BOTA<br />
            <br />
            Show (for WWFF only!):<br />
            <input type="radio" id="all" name="activated" value="all"<?php if ($activated=="all") echo " checked"?>>
            <label for="none">all</label><br>
            <input type="radio" id="activatedonly" name="activated" value="activated"<?php if ($activated=="activated") echo " checked"?>>
            <label for="activatedonly">only activated</label><br>
            <input type="radio" id="noneactivatedonly" name="activated" value="nonactivated"<?php if ($activated=="nonactivated") echo " checked"?>>
            <label for="noneactivatedonly">only non-activated</label><br>
            <br />
            <button type="submit">REBUILD MAP</button><br />
            </form>
            <br />
            <p><?php echo ($markersonmapwwff + $markersonmappota + $markersonmapsota + $markersonmapgma + $markersonmapbota); ?> markers showed on map now.</p>
            <div class="notes"><p>This map was created from the need to have a map for WWFF activity. 
            The thanks go to the people from WWFF who provided the very first data. 
            At the beginning it was a simple map that didn't work on a mobile phone. 
            Later I added more features, better backend and frontend and other data sources such as POTA, SOTA etc.</p></div>
            <div class="notes"><p><a href='old/'>Original (old) version of this map</a>.</p></div>
            <div class="bottom">
                <span>created by Michal <a href="https://www.qrz.com/db/OK1SIM">OK1SIM</a> for community</span>
            </div>
        </div>`;

    /* left */
    L.control.slideMenu(left + contents).addTo(map);

    // create own control for GPX generator site
    myControl = L.control({position: 'bottomright'});
    myControl.onAdd = function(map) {
            this._div = L.DomUtil.create('div', 'myControl');
            this._div.innerHTML = ''+
                                  '<?php if ($activated=="activated") echo "Activated only!<br />"; ?>'+
                                  '<?php if ($activated=="nonactivated") echo "NON-activated only!<br />"; ?>'+
                                  '<?php if ($markersonmapwwff>0) echo "<img src=\"img/icon-red.png\" height=\"10\"> WWFF: ".intval($markersonmapwwff)."<br />"; ?>'+
                                  '<?php if ($markersonmappota>0) echo "<img src=\"img/icon-green.png\" height=\"10\"> POTA: ".intval($markersonmappota)."<br />"; ?>'+
                                  '<?php if ($markersonmapsota>0) echo "<img src=\"img/icon-blue.png\" height=\"10\"> SOTA: ".intval($markersonmapsota)."<br />"; ?>'+
                                  '<?php if ($markersonmapgma>0) echo "<img src=\"img/icon-violet.png\" height=\"10\"> GMA: ".intval($markersonmapgma)."<br />"; ?>'+
                                  '<?php if ($markersonmapbota>0) echo "<img src=\"img/icon-orange.png\" height=\"10\"> BOTA: ".intval($markersonmapbota).""; ?>'+
                                  ''
            return this._div;
    }
    myControl.addTo(map);

   </script>
   <script>
    $(document).ready(function() {
        document.getElementById("myloading").style.display='none';
    });
    </script>    
   
</body>
</html>