<?php


if (isset( $_POST["file"] ) && ((string)($_POST["file"])!==""))
{
	
//	$file = @file( $_POST["file"], 1 );
    // fictional URL to an existing file with no data in it (ie. 0 byte file)
    $url = $_POST["file"];

    $curl = curl_init();
    
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);

    // execute and return string (this should be an empty string '')
    $file = curl_exec($curl);
    curl_close($curl);
 	
	// $content = join( "", $file );
	$content = $file;

	$endline = "\n";

	$endtag = ";";

	$new_content= split( $endline, $content );

	foreach( $new_content as $key => $value )
		$new_content[ $key ] = split( $endtag, trim( ereg_replace( "\n", "", $value ))  );
		
	$tag = isset($_POST[ "line" ]);

	 	
	$titre = isset($_POST[ "title" ]);
	if (!$titre) 
		$start = 0;
	else
	{
		$start = 1;
		$col = $new_content[0];
	}

	header('Content-type: text/xml');	
	echo( "<file>\n" );
	for( $i=$start; $i< count($new_content); $i++)
	{
		$line = $new_content[ $i ];
		if( !$tag )
		{
			echo( "<data>\n" );
			foreach( $line as $key => $value )
				if ( !$titre )
					echo( "<t$key>$value</t$key>\n" );
				else
					echo( "<".$col[$key].">$value</".$col[$key].">\n" );
				
			echo( "</data>\n" );
		}
		else	
		{
			echo( "<".$line[0].">\n" );
			for( $j=1; $j<count($line); $j++ )
			{
				$value = $line[$j];
				if ( !$titre )
					echo( "<t$j>$value</t$j>\n" );
				else
					echo( "<".$col[$j].">$value</".$col[$j].">\n" );
			}
			echo( "</".$line[0].">\n" );
			
		}
	}		
	echo( "</file>\n" );
	exit();
}

?>
<html>
<body>
<h1><a href="http://www.lbcd78.fr">lbcd78</a> t2x 2.0</h1>
<p>A simple tool to transform a text file into an xml file.</p>
<br />
<h2>Form to fill to transform txt or csv to xml</h2>
* all line must be ended with "\n" and <br />
* all line must use the seperator ";" <br />
<br />
Test with : http://www.lbcd78.fr/blugle/texte.txt (paste Url)<br/>
Use "Back" after submit

<h2> 3 steps to follow and submit </h2>


<form method="post" action="t2x.php">
1) Give Url txt file<input type="text" name="file"/><br />
2) Is there some title in the first line <input name="title" type="checkbox"/><br />
3) The first column is a "top descriptor" <input name="line" type="checkbox"/><br />
<input type="submit" />
</form>
</body>
</html>
