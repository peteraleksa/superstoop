<?php
header("Content-type: text/xml");
require_once("connect.php"); // mysql connection

// 
// function for parsing to xml
//

function toXML($htmlString) {

	$xmlString = str_replace('<', '&lt;', $htmlString);
	$xmlString = str_replace('>', '&gt;', $xmlString);
	$xmlString = str_replace('"', '&quot;', $xmlString);
	$xmlString = str_replace("'", '&#39;', $xmlString);
	$xmlString = str_replace("&", '&amp;', $xmlString);
	return $xmlString;

}

//
// form variables
//

$address = $_GET['address'];
$firstname = $_GET['firstName'];
$lastname = $_GET['lastName'];
$date = $_GET['date'];
$category = $_GET['category'];
$maxdist = $_GET['radius'];
$origin_lat = $_GET['address_lat'];
$origin_long = $_GET['address_long'];

// find locations within max distance from origin
// math help goes to google developers tutorials on this one!
// https://developers.google.com/maps/articles/phpsqlsearch_v3
//


$selectClauseNear = "SELECT events.event_id, events.title, events.date, events.category,
                        address.address_num, address.address_street, address.address_city, address.address_state, address.address_zip, address.address_lat, address.address_long,
			users.name_first AS fname, users.name_last AS lname, users.username AS uname,
			        ( 3959 * acos( cos( radians({$origin_lat}) ) * cos( radians( address.address_lat ) ) * cos( radians( address.address_long ) - radians({$origin_long}) )
                                + sin( radians({$origin_lat}) ) * sin( radians( address.address_lat ) ) ) )
                                AS distance
			FROM events
			LEFT JOIN address
				ON events.address_id = address.address_id
			LEFT JOIN eventowners
				ON events.event_id = eventowners.event_id
			LEFT JOIN users
				ON eventowners.user_id = users.user_id
			WHERE address.address_lat IS NOT NULL
			AND address.address_long IS NOT NULL ";

$firstnameClause = "AND fname LIKE '%{$firstname}%' ";

$lastnameClause = "AND lname LIKE '%{$lastname}%' ";

$havingClause = "HAVING distance < {$maxdist} ";

$orderbyClause = "ORDER BY distance LIMIT 0, 20;";

// right now you have to have an address entered
$query = $selectClauseNear; 

// add firstname clause if applicable
if ($firstname) {
	$query = $query . $firstnameClause;
}

// add lastname clause if applicable
 if ($lastname) {
	$query = $query . $lastnameClause;
}

// add date clause if applicable
//if ($date) {
//	$query = $query . $dateClause;
//}

// add having and order by clauses
$query = $query . $havingClause . $orderbyClause;


//
// run query
//

$result = mysql_query( $query ) or die ("Could not perform query: " . mysql_error() );


//
// write search results to xml doc
//

	echo "<events>";

	while ( $row = mysql_fetch_array($result, MYSQL_ASSOC) ) {	
	// add to xml
		echo "<event>" ;
		echo "<event_id>" . toXML($row['event_id']) . "</event_id>" ;
		echo "<title>" . toXML($row['title']) . "</title>" ;
		echo "<date>" . toXML($row['date']) . "</date>" ;
		echo "<category>" . toXML($row['category']) . "</category>" ;
		echo "<address_num>" . toXML($row['address_num']) . "</address_num>" ;
		echo "<address_street>" . toXML($row['address_street']) . "</address_street>" ;
		echo "<address_city>" . toXML($row['address_city']) . "</address_city>" ;
		echo "<address_state>" . toXML($row['address_state']) . "</address_state>" ;
		echo "<address_zip>" . toXML($row['address_zip']) . "</address_zip>" ;
		echo "<address_lat>" . toXML($row['address_lat']) . "</address_lat>" ;
		echo "<address_long>" . toXML($row['address_long']) . "</address_long>" ;
		echo "<distance>" . toXML($row['distance']) . "</distance>" ;  
		echo "<owner>" . toXML($row['fname']) . " " . toXML($row['lname']) . "</owner>";
		echo "<owner_username>" . toXML($row['uname']) . "</owner_username>";

		echo "</event>" ;
	}

//
// clean up
//

echo "</events>";
mysql_free_result( $result );

// need to manually close connection since footer.php not included
mysql_close($conn);

?>
