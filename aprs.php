<?php
ini_set( "user_agent", "Midnight Cheese (+http://midnightcheese.com/)" );
?>
<head>
   <title>HAM-MAP.com - Outdoor activity HAM map by OK1SIM</title>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
   <meta name="robots" content="noindex, nofollow" />
   <style>
       body {
        padding: 5;
        margin: 5;
       }
       html, body {
        height: 100%;
        width: 100vw;
       }
       iframe {
        outline: none;
       }
       iframe[seamless] {
        display: block;
       }
   </style>
</head>
<body>
<h3>Aktuální teplota</h3>
<i>živá data data sbíraná z amatérských meteostanic (WX APRS)</i><br /><br />
<?php

function display_APRS() {
	// get a free key at https://aprs.fi/page/api
	$apikey = getenv('APRSFI_API_KEY') ?: '';
	// comma separated list of APRS station names to show, e.g. "OK1PNK-5,LKLN,OK5TVR-5,GrArber,Churanov,EW5956"
	$stations = getenv('APRS_STATIONS') ?: '';
	$json_url = "http://api.aprs.fi/api/get?apikey=".urlencode($apikey)."&name=".urlencode($stations)."&what=wx&format=json";
	$json = file_get_contents( $json_url, 0, null, null );
	$json_output = json_decode( $json, true);
	$station_array = $json_output[ 'entries' ];
	foreach ( $station_array as $station ) {
        $name="";
		if ($station['name']=="LKLN") $name="Letiště Líně";
        if ($station['name']=="EW5956") $name="Šibenik - HR";
        if ($station['name']=="GrArber") $name="Velký Javor 1456m - DE";
        if ($station['name']=="OK5TVR-5") $name="Malesice";
        if ($station['name']=="OK1PNK-5") $name="Nýřany";
        if ($station['name']=="Churanov") $name="Churáňov 1050m";
        if ($name=="") $name=$station['name'];
		$temp = $station['temp'];
		echo "".$temp."°C ".$name."<br />";
	}
}

display_APRS();

?>
<br />
<br />
<h3>Předpověď počasí Stod YR.NO:</h3>
<iframe src="https://www.yr.no/en/content/2-3065116/table.html" width="100%" height="600" frameBorder="0" title="YR.no forecast"></iframe>
</body>
</html>