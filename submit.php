<?php



##check for an adition to the db
if ($_POST ["shorturl"] !=  "") {
if ($_POST ["longurl"] !=  "") {
if ($_POST ["reference"] !=  "") {

##insert db entry here if its not been used
#echo "findme" . $_GET ["shorturl"];

##scan db to prevent dupe urls
$urlexists = 0;
$handle = fopen("/somewhere/urls.txt", "r");
if ($handle) {
    while (($buffer = fgets($handle, 4096)) !== false) {
        if (trim($buffer,"\n") == trim($_POST ["longurl"],"\n")) {
                $redirectpage = fgets($handle, 4096);
		$urlexists = 1;
        }
    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
        die("File open failed.");
    }
}
fclose($handle);

##add to db if its unique todo: add locking file
if ($urlexists == 0) {
	$handle = fopen("/somewhere/urls.txt", 'a') or die("can't open file");
	$data = $_POST ["shorturl"] . "\n";
	fwrite($handle, $data);
        $data = $_POST ["longurl"] . "\n";
        fwrite($handle, $data);
        $data = date("Y/m/d") . " " . date("h:i:sa") . " | " . $_SERVER["REMOTE_ADDR"] . " | " . $_POST ["reference"] . "\n";
        fwrite($handle, $data);

	die("Added URL to file.");
}

die("URL not added. Possible duplicate.");

} } } 


?>
