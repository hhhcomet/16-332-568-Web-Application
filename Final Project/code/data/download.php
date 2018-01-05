<!-- written by Jingxuan Chen
     Debugged by Rong Zhang
     Assisted by Xiuqi YE -->
<?php
ini_set("output_buffering", "1"); 
function downfile($stock, $day, $month, $year)
{
$a = $month - 1;
$b = $day;
$c = $year - 1;
$d = $month;
$e = $day;
$f = $year;
//$filename=$fileurl;
$filename = "http://ichart.yahoo.com/table.csv?s=$stock&a=$a&b=$b&c=$c&d=$d&e=$e&f=$f&g=d&ignore=.csv";
// echo $filename;
$file  =  fopen($filename, "rb"); 
//Header( "Content-type:  application/octet-stream "); 
//Header( "Accept-Ranges:  bytes "); 
//Header( "Content-Disposition:  attachment;  filename= 4.doc"); 
$contents = "";
while (!feof($file)) {
 $contents .= fread($file, 8192);
}
$ofilename = "./historical-time/".$stock."_historical.csv";
// check whether the file exists
if (file_exists($ofilename)) {
	unlink($ofilename);
}
$ofile = fopen($ofilename,"w");
fwrite($ofile,$contents);
// echo $contents > "hahaha.csv";
fclose($file); 
fclose($ofile);
}
?>