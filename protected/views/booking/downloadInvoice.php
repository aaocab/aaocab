


<!--//Then download the zipped file.-->
<?php
if($filename!='' && $path!='' && $success== true && ($reqtype == 1 || $reqtype == 3))
{
	    header('Content-Type: application/zip');
	    header('Content-disposition: attachment; filename=' . $filename);
	    header('Content-Length: ' . filesize($filename));
	    readfile($path);

 echo "<h2><b>File downloaded.</b></h2>"; 
}
else if($filename!='' && $path!='' && $success== true && $reqtype == 2)
{
	    header('Content-Type: text/csv');
	    header('Content-disposition: attachment; filename=' . $filename);
	    header('Content-Length: ' . filesize($filename));
	    readfile($path);

 echo "<h2><b>File downloaded.</b></h2>"; 
}
else
{
echo "<h2><b>Invalid request.</b></h2>"; 
}

?>