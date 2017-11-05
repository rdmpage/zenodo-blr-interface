<?php

require_once(dirname(__FILE__) . '/lib.php');

$bucket = 'https://zenodo.org/api/files/c013299a-8bf1-48ec-b013-9c4b3c96d94b';

if (isset($_GET['bucket']))
{
	$bucket = $_GET['bucket'];
}

$json = get($bucket);
if ($json) {
	$obj = json_decode($json);
	
	if (0)
	{
		echo '<pre>';
		print_r($obj);
		echo '</pre>';
	}
		
	$url = $obj->contents[0]->links->self;
	
	$image = get('http://exeg5le.cloudimg.io/s/width/128/' . $url);
	
	header('Content-Type: ' . $obj->contents[0]->mimetype);
	header("Cache-control: max-age=3600");
	echo $image;



}


?>