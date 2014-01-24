<?php

	require_once("header.php");
	require_once("connect.php");

	$first = $_GET['first'];
	$last = $_GET['last'];
	$city = $_GET['city'];	

	// if first last and city are entered
	if ($first && $last && $city)
		$query = "SELECT users.user_id, users.name_first, users.name_last, address.address_city, address.address_state, pictures.picture_loc FROM users
				LEFT JOIN address ON users.address_id = address.address_id 
				LEFT JOIN pictures ON users.profile_picture = pictures.picture_id 
				WHERE address.address_city = '{$city}' AND users.name_first LIKE '%{$first}%' AND users.name_last LIKE '%{$last}%';";

	// if both a first and last name are entered but no city
	else if ($first && $last)
		$query = "SELECT users.user_id, users.name_first, users.name_last, address.address_city, address.address_state, pictures.picture_loc FROM users 
				LEFT JOIN address ON users.address_id = address.address_id 
				LEFT JOIN pictures ON users.profile_picture = pictures.picture_id 
				WHERE name_first LIKE '%{$first}%' AND name_last LIKE '%{$last}%';";

	// if only a first name entered
	else if ($first)
		$query = "SELECT users.user_id, users.name_first, users.name_last, address.address_city, address.address_state, pictures.picture_loc FROM users 
				LEFT JOIN address ON users.address_id = address.address_id 
				LEFT JOIN pictures ON users.profile_picture = pictures.picture_id 
				WHERE name_first LIKE '%{$first}%';";

	// if only a last name is entered
	else if ($last)
		$query = "SELECT users.user_id, users.name_first, users.name_last, address.address_city, address.address_state, pictures.picture_loc FROM users 
				LEFT JOIN address ON users.address_id = address.address_id
				LEFT JOIN pictures ON users.profile_picture = pictures.picture_id
				WHERE name_last LIKE '%{$last}%';";
	

	$result = mysql_query( $query ) or die ("Could not query database " . mysql_error() );

	while ($row = mysql_fetch_array( $result, MYSQL_ASSOC )) {
	
		echo "<div class=\"userResult\">";
		echo "<aside><img src=\"./{$row['picture_loc']}\" height=\"40%\" width=\"auto\"  />";
		echo $row['name_first'] . " " . $row['name_last'] . "<br />";
		echo $row['address_city'] . ", " . $row['address_state'] . "<br />";;
		echo "<a href=\"./profile.php?id={$row['user_id']}\" >View Profile </a>";
		echo "</div>";
	}

	mysql_free_result( $result );
	//mysql_close( $conn );
	require_once("footer.php");



?>
