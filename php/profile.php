<?php

// header
require_once('header.php');

// database variables
require_once('connect.php');

$id = $_GET['id'];

$query = "SELECT users.name_first, users.name_last, users.username,
		address.address_num, address.address_street, address.address_apt, address.address_city, address.address_state, address.address_zip,  
	  	pictures.picture_loc
	  FROM users 
	  LEFT JOIN address ON users.address_id = address.address_id 
	  LEFT JOIN pictures ON users.profile_picture = pictures.picture_id
	  WHERE users.user_id = {$id}";

$result = mysql_query( $query ) or die( "Query failed: " . mysql_error() );

$query = "SELECT events.event_id, events.title, eventowners.user_id AS owner
	  FROM events
	  INNER JOIN eventowners ON events.event_id = eventowners.event_id
	  WHERE eventowners.user_id = {$id}";

$events = mysql_query( $query ) or die( "Query failed: " . mysql_error() ); 

$row = mysql_fetch_array( $result, MYSQL_ASSOC );

echo "<img src=\"./{$row['picture_loc']}\" alt=\"profile image\" />";
echo "<br />";
echo "First Name: {$row['name_first']}";
echo "<br />";
echo "Last Name: {$row['name_last']}";
echo"<br />";
echo "Username: {$row['username']}";
echo"<br />";
echo "Address: {$row['address_num']} {$row['address_street']} {$row['address_apt']} {$row['address_city']} {$row['address_state']} {$row['address_zip']}";
echo "<br />";
echo "About: {$row['about']}";
echo "<br />";
echo "Contact: {$row['contact']}";
echo "<br />";
echo "Events:";
echo "<br />";

while ( $eventrow = mysql_fetch_array( $events, MYSQL_ASSOC ) ) {
       	
	echo "<a href=\"sale.php?id={$eventrow['event_id']}\" >{$eventrow['title']}</a>";
	echo "<br />";

}



echo "</body></html>";

mysql_free_result( $result );
mysql_free_result( $events );

require_once( 'footer.php' );

?>
