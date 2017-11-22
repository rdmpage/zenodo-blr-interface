<?php

require_once(dirname(__FILE__) . '/lib.php');

//----------------------------------------------------------------------------------------
function display_search($q, $bookmark = '')
{

	$parameters = array(
			'q'					=> $q,
			'highlight_fields' 	=> '["default"]',
			'highlight_pre_tag' => '"<span class=\"highlight\">"',
			'highlight_post_tag'=> '"</span>"',
			'highlight_number'	=> 5,
			'counts'			=> '["tag"]',
			'include_docs'		=> 'true',
			'limit' 			=> 100
		);
	
	if ($bookmark != '')
	{
		$parameters['bookmark'] = $bookmark;
	}
	
	$username = getenv('CLOUDANT_USERNAME');
	$password = getenv('CLOUDANT_PASSWORD');
			
	$url = 'https://' . $username . ':' . $password . '@rdmpage.cloudant.com/zenodo/_design/search/_search/figure?' .  http_build_query($parameters);

	//echo $url . '<br />';

	$json = get($url);
	$obj = json_decode($json);

	if (0)
	{
		echo '<pre>';
		print_r($obj);
		echo '</pre>';
	}
		// Display...
		echo 
	'<!DOCTYPE html>
	<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<meta name="viewport" content="width=device-width, initial-scale=1" />
			<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		
			<link rel="stylesheet" href="style.css" />
		</head>
		<body>';
	
	
	echo '<div class="top">';
	
	echo '<form >
	<a href=".">Home</a>
	  <input style="font-size:24px;" name="q" placeholder="Search term" value="' . $q . '" >
	  <button style="font-size:24px;">Search</button>
	</form>';
	echo '</div>';
	echo '<div class="push"></div>';
	

/*
	echo '<div style="position:relative">';

	echo '<div style="left:610px;position:absolute;width:400px;line-height:1.2em;font-size:0.8em;padding:5px;color:gray;">';

	// facets
	if ($obj->counts)
	{
		foreach ($obj->counts as $k => $v)
		{
			echo '<h4>' . $k . '</h4>';
	
			echo '<ol>';
	
			if ($v)
			{
				foreach ($v as $value => $count)
				{
					echo '<li>' . $value . ' [' . $count . ']' . '</li>';
				}
			}	
			echo '</ol>';
		}
	}


	echo '</div>';
	
	*/

	echo '<div class="box">';
	foreach ($obj->rows as $row)
	{
	
	
	
		echo '<div class="image">';
		
		echo '<a href="https://zenodo.org/record/' . str_replace('oai:zenodo.org:', '', $row->id) . '" target="_new">';
		echo '<figure>';
		//echo '<img src="http://exeg5le.cloudimg.io/s/width/128/https://zenodo.org/record/' . str_replace('oai:zenodo.org:', '', $row->id) . '/files/figure.png" />';
		
		echo '<img src="proxy.php?bucket=' . $row->doc->links->bucket . '" />';
		
		
		echo '<figcaption>' . substr($row->fields->default, 0, 100) . '...</figcaption>';
		echo '</figure>';
		echo '</a>';
		echo '</div>';

	}
	
	echo '</div>';

	
		echo
	'	</body>
	</html>';
}

//----------------------------------------------------------------------------------------
function default_display()
{
	$q = '';
		// Display...
		echo 
	'<!DOCTYPE html>
	<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<meta name="viewport" content="width=device-width, initial-scale=1" />
			<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		
			<link rel="stylesheet" href="style.css" />
		</head>
		<body>';
	
	
	echo '<div class="top">';
	echo '<form >
	<a href=".">Home</a>
	  <input style="font-size:24px;" name="q" placeholder="Search term" value="' . $q . '" >
	  <button style="font-size:24px;">Search</button>
	</form>';
	echo '</div>';
	echo '<div class="push"></div>';
	echo '<div style="padding:30px;">';
	echo '<h1>Biodiversity Literature Repository Image Search</h1>';
	echo '<p>Explore images in the <a href="https://zenodo.org/communities/biosyslit">Biodiversity Literature Repository</a></p>';
	
	echo '<p>Examples:';
	echo '<ul>';
	echo '<li><a href="?q=distribution map">distribution map</a></li>';
	echo '<li><a href="?q=amnh">AMNH specimens</a></li>';
	echo '<li><a href="?q=phylogeny">phylogeny</a></li>';
	echo '<li><a href="?q=neotype">neotype</a></li>';
	echo '<li><a href="?q=dorsal habitus">dorsal habitus</a></li>';
	echo '<li><a href="?q=paratype">paratype</a></li>';
	
	
	
	echo '</ul>';
	echo '</p>';
	
	echo '</div>';
	echo '</body>';
	echo '</html>';

}

//----------------------------------------------------------------------------------------
function main()
{
	$query = '';
	$bookmark = '';
		
	// If no query parameters 
	if (count($_GET) == 0)
	{
		default_display();
		exit(0);
	}

	
	// Show search (text, author)
	if (isset($_GET['q']))
	{	
		$query = $_GET['q'];
		$bookmark = '';
		if (isset($_GET['bookmark']))
		{
			$bookmark = $_GET['bookmark'];
		}
		display_search($query, $bookmark);
		exit(0);
	}	
	
}


main();

?>