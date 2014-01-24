<?php

	require_once "header.php";  // include header
		
?>

<form name="userSearch" action="user_search_results.php" method="GET">
	
	<fieldset id="nameSet">

		<label for="first">First Name: </label>
		<input type="text" name="first" value="" />
		<label for="last">Last Name: </label>
		<input type="text" name="last" value="" />
	</fieldset>

	<fieldset id="citySet">
		<label for="city">In City: </label>
		<input type="text" name="city" value="" />
	</fieldset>

	<input type="submit" value="Submit" />
</form>

<?php
	
	
	require_once "footer.php"; // include footer

?>
