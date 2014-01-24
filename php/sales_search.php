<?php

	require_once "header.php";  // include header
		
?>
<script src="http://maps.googleapis.com/maps/api/js?sensor=false" type="text/javascript"></script>
<script src="./js/search_functions.js" type="text/javascript"></script>

<script type="text/javascript">document.getElementsByTagName("body")[0].setAttribute("onload", "load()");</script>

<form name="salesSearch">
	
	<fieldset id="addressSet">
	     	<label for="addressInput">Search for sales near: </label>
		<input type="text" id="addressInput" />
    		<select id="radiusSelect">
      			<option value="1" selected>1mi</option>
      			<option value="5">5mi</option>
      			<option value="10">10mi</option>
			<option value="15">15mi</option>
    			<option value="20">20mi</option>
			<option value="25">25mi</option>
		</select>
	</fieldset>

	<fieldset id="userSet">
		<label>By User </label><br/>
		<label for="firstName">First name: </label>
		<input type="text" name="firstName" />
		<label for="lastName">Last name: </label>
                <input type="text" name="lastName" />
		
	</fieldset>

	<fieldset id="saleInfoSet">
		<label for="saleType">Type of sale: </label>
		<input type="checkbox" onChange="searchLocations()" name="saleType" value="Books" />Books		
		<input type="checkbox" onChange="searchLocations()" name="saleType" value="Clothes" />Clothes
		<input type="checkbox" onChange="searchLocations()" name="saleType" value="CD&#39;s" />CD's
	</fieldset>

	<fieldset>
		<label for="saleDate">On date: </label>
		<select name="saleDay">
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option	value="5">5</option>
			<option	value="6">6</option>
			<option value="7">7</option>
			<option	value="8">8</option>
			<option	value="9">9</option>
			<option value="10">10</option>
			<option	value="11">11</option>
			<option	value="12">12</option>
			<option value="13">13</option>
			<option	value="14">14</option>
			<option	value="15">15</option>
			<option value="16">16</option>
			<option	value="17">17</option>
			<option	value="18">18</option>
			<option value="19">19</option>
			<option	value="20">20</option>
			<option	value="21">21</option>
			<option value="22">22</option>
			<option	value="23">23</option>
			<option	value="24">24</option>
			<option value="25">25</option>
			<option	value="26">26</option>
			<option	value="27">27</option>
			<option	value="28">28</option>
			<option value="29">29</option>
			<option	value="30">30</option>
			<option	value="31">31</option>
		</select>

		<label for="saleMonth">Month: </label>
		<select	name="saleMonth">
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option	value="5">5</option>
			<option	value="6">6</option>
			<option value="7">7</option>
			<option	value="8">8</option>
			<option	value="9">9</option>
			<option value="10">10</option>
			<option	value="11">11</option>
			<option	value="12">12</option>
		</select>

		<label for="saleYear">Year: </label>
		<input name="saleYear" type="text" />
		</fieldset>

    	<input type="button" onclick="searchLocations()" value="Search"/>
</form>
    	
	<div><select id="locationSelect" style="width:100%"></select></div>
    	
	<div id="map" style="width: 100%; height: 600px"></div>
		

<?php
	
	
	require_once "footer.php"; // include footer

?>
