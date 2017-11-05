<?php

require_once(dirname(__FILE__) . '/lib.php');
require_once(dirname(__FILE__) . '/couchsimple.php');

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
			
	$url = 'https://rdmpage:peacrab280398@rdmpage.cloudant.com/zenodo/_design/search/_search/figure?' .  http_build_query($parameters);

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
			<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		
			<script>
			</script>
		
		<!--
			<style>
				.highlight { font-weight: bold; background-color:orange; }
			</style> -->
			
			<style>
			
			
			 body {
			   font-family:sans-serif;
			   background-color:rgb(248,248,248);
			   margin:0px;
			}
			
			
/* https://www.sitepoint.com/using-modern-css-to-build-a-responsive-image-grid/ */
div {
  display: flex;
  flex-wrap: wrap;
}

a {
  font-size: 12px; 
  display: inline-block;
  margin-bottom: 8px;
  width: calc(50% - 4px);
  margin-right: 8px;
  /*border: 1px solid rgb(192,192,192);*/
  background-color:white;
}

a:nth-of-type(2n) {
  margin-right: 0;
}

@media screen and (min-width: 50em) {
  a {
    width: calc(25% - 6px);
  }

  a:nth-of-type(2n) {
    margin-right: 8px;
  }

  a:nth-of-type(4n) {
    margin-right: 0;
  }
}

.top{position: fixed; width: 100%; top: 0; height: 40px; background-color:white;border-bottom:1px solid rgb(192,192,192);}
.push{margin-bottom: 40px}

</style>			
		
		</head>
		<body>';
	
	
	echo '<div class="top">';
	echo '<form >
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

	echo '<div>';
	foreach ($obj->rows as $row)
	{
	
	
	

		echo '<a href="https://zenodo.org/record/' . str_replace('oai:zenodo.org:', '', $row->id) . '">';
		echo '<figure>';
		//echo '<img src="http://exeg5le.cloudimg.io/s/width/128/https://zenodo.org/record/' . str_replace('oai:zenodo.org:', '', $row->id) . '/files/figure.png" />';
		
		echo '<img src="proxy.php?bucket=' . $row->doc->links->bucket . '" />';
		
		
		echo '<figcaption>' . substr($row->fields->default, 0, 100) . '...</figcaption>';
		echo '</figure>';
		echo '</a>';

		echo "\n";
		
		
		/*
		echo '<div style="font-size:18px;font-weight:bold;">' . $row->fields->default . '</div>';
		
		echo '<div style="font-size:14px;color:gray;">';
		foreach ($row->highlights->default as $highlight)
		{
			echo $highlight . '<br />';
		}
		echo '</div>';
		
		echo '<div>';
		
		echo '<img style="border:1px solid rgb(192,192,192);" src="http://exeg5le.cloudimg.io/s/width/200/https://zenodo.org/record/' . str_replace('oai:zenodo.org:', '', $row->id) . '/files/figure.png" />';
		echo '<br />';
		echo  '<div style="width:200px;font-size:10px;">' . $row->fields->default . '</div>';
		
		echo '</div>';
		
		
	
		echo '</div>';*/
	}
	
	echo '</div>';

	
		echo
	'	</body>
	</html>';
}

//----------------------------------------------------------------------------------------
function default_display()
{
	echo 'Hi';
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