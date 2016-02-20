<?php
$debug = 0;
$page = htmlspecialchars($_SERVER["QUERY_STRING"]);
$httphost = htmlspecialchars($_SERVER["HTTP_HOST"]);
$urlfile = "/somewhere/urls.txt";

##set admin/debug the dirty way. uncomment this for testing.
#if ($_GET["admin"] == "1") { $debug = 1; } else { logpage(); }

##scan redirect db and jfdi
do_redirect();

?>


<html>
  <head>
	<META HTTP-EQUIV="EXPIRES" CONTENT="Mon, 22 Jul 2002 11:12:01 GMT">
	<meta name="google-site-verification" content="i867Erkto_3ewYRaMzHrbSq3hPm-5MuNS6Wl8l6RgY4" />
	<meta name="msvalidate.01" content="" />
	<meta name="description" content="GM URLs - The Tiny tinyurl site" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=1024" />
	<meta name="Keywords" content="tinyurl tiny url website shortener" />
	<meta name="robots" content="all" />
	<meta name="revisit-after" content="1 days" />
	<meta name="distribution" content="Global" />
	<title>Welcome to GM URLs</title>
 	<link rel="stylesheet" href="style.css">
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	  ga('create', 'UA-73865313-1', 'auto');
	  ga('send', 'pageview');
	</script>
  </head>
  <body>
  <h1>GM URLs are here!</h1></center>
  <br/>

<form action="submit.php" method="post" id="suburls">
	Short URL: (eg http://<?php echo $httphost; ?>/?<i><b>shorturl</b></i>)<br>
	<input type="text" name="shorturl" size="50" placeholder="shorturl"><br>
	Long URL:<br>
	<input type="text" name="longurl" size="50" placeholder="http://www.url.com"><br>
	Name/Email/Reference:<br>
	<input type="text" name="reference" size="50" placeholder="your@email.com / nickname"><br>
        Date/Time/IP:<br>
        <input type="text" name="datetime"  size="50" disabled="true" value="
<?php	echo date("Y/m/d") . " " . date("H:i:s") . " | " . $_SERVER["REMOTE_ADDR"] . " (saved for logging)"; ?> "><br>
	<input type="submit" value="Submit URL">
</form>
	</br>

	GM URL's is an open source project hosted on github, aiming to have 
	the shortest tiny URL site around, on a single webpage.
	</br></br>
	Although this site is fully functional it's also an example of this site in action
	</br></br>
	An API to add urls from other methods will come soon!
	</br>

<?php
#remove this to readd the url table
if ($debug == 1) {

	echo "<p><b>URL List :-</b></p>";
	echo "<table id=\"urllist\"><tr>";
	echo "<td><b>Short URL</b></td>";
	echo "<td><b>Long URL</b></td>";

    if ($debug) { echo "<td><b>Time/Date/IP/EMail</b></td></tr>"; }

##scan redirect db and display in a table
$handle = fopen($urlfile, "r");

if ($handle) {
    while (($shorturl = fgets($handle, 4096)) !== false) {
                $longurl = fgets($handle, 4096);
		$reference = fgets($handle, 4096);
		echo "<tr>";
		echo "<td><center>";
		$fullurl = "http:/" . "/" . $httphost . "/?" . $shorturl;
		echo "<a target = '_blank' href = '" . $fullurl . "'>" . $fullurl . "</a>";
		echo "</center></td>";
		echo "<td><center>" . $longurl . "</center></td>";
		if ($debug) { echo "<td><center>" . $reference . "</center></td>"; }
		echo "</tr>";
    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
}
fclose($handle);
	echo "</table><br/></br>";

} #remove this to readd the redirect table
echo "</br></br></br></br></br></br></br></br>";

?>

	<table id="footer">
	<tr>
		<td>
			<a target = "_blank" href="https://www.facebook.com/dave.gungan">(c)Dave Gungan 2016</a></br>
		</td>
		<td>
			Open source on <a target = "_blank" href="https://github.com">Github!</a></br>
		</td>
                <td>
			Thanks to <a target = "_blank" href="http://england.ma.cx">http://england.ma.cx</a></br>
                </td>
	</tr>
	</table>
  </body>
</html>

<?php


## redirect url if called that way
function do_redirect() {
$handle = fopen("/somewhere/urls.txt", "r");
$page = htmlspecialchars($_SERVER["QUERY_STRING"]);
if ($handle) {
    while (($buffer = fgets($handle, 4096)) !== false) {
        if (trim($buffer,"\n") == trim($page,"\n")) {
                $redirectpage = fgets($handle, 4096);
                $reference = fgets($handle, 4096);
        }
    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
        die("File open failed.");
    }
}
fclose($handle);

##redirect page with no error checks
header('Location: ' . $redirectpage);

}


##log page url and requester ip etc on log.txt
function logpage() {
	$logfile = "/somewhere/logs.txt";
	$logdata = date("Y/m/d") . " " . date("h:i:sa") . " | " . $_SERVER["REMOTE_ADDR"] . " | ";
	$logdata = $logdata . "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . " | ";
	$logdata = $logdata . $_SERVER["HTTP_USER_AGENT"] . "\r\n\r\n";
	file_put_contents($logfile, $logdata, FILE_APPEND);
}

?>
