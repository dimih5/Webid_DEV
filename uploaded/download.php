<?php
	$file = $_GET['file'];
	$filename= $_GET['filename'];
	
	if(!$file){
		die('file not found');
	}
	else {
		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/msword");
		header("Content-Transfer-Encoding: binary");
		
		readfile($file);
	}
?>