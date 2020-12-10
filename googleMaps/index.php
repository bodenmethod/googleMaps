<!-- Auto page refresh every 5min -->
<?php
   $url=$_SERVER['REQUEST_URI'];
   header("Refresh: 300; URL=$url");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

	<script type="text/javascript" src=https://code.jquery.com/jquery-3.1.1.min.js></script>
	<script type="text/javascript" src="js/googlemap.js"></script>
	<style type="text/css">
		.container {
			height: 600px;
		}
		#map {
			width: 100%;
			height: 100%;
			border: 1px black;
		}
		html, body {
    		height: 50%;
    		margin: 0;
    		padding: 0;
  		}
		#data, #allData, #searchData {
			display: none;
		}
	</style>
</head>
<body>
	<div class="container">
		<center><a href="index.php"><h1>Google Maps API w/mysql localhost</h1></a></center>
		
		<?php 
			//create new object of db and call function to collect data from customers with lat lng of NULL. Pass to JS through json.
			require 'bfcmatest.php';
			$cus = new bfcmatest;
			$collect = $cus->getCustomersBlankLatLng();
			$collect = json_encode($collect, true);
			echo '<div id="data">' . $collect . '</div>';

			$allData = $cus->getAllCustomers();
			$allData = json_encode($allData, true);
			echo '<div id="allData">' . $allData . '</div>';

			$search = $cus->getSearchedCustomer();
			$search = json_encode($search, true);
			echo '<div id="searchData">' . $search . '</div>';
			
		 ?>

		 <!-- Create div to load map in -->
		<div id="map"></div>
	
		<!-- Create search Form to find customer by last name / address-->
  <div class="form-group mt-2">
  <fieldset>
                <form class="" action="" method="GET">
                <input type='text' name="Search" value="" placeholder="Search by name/address">
                <input type='submit' name="SearchButton" value="Search Customers">
                </form>
            </fieldset>     
	</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

</body>
	
	<!-- load the markerclustererplus library -->
<script src="https://unpkg.com/@googlemaps/markerclustererplus/dist/index.min.js"></script>

<!-- API KEY VERIFICATION SCRIPT FROM GOOGLE WITH CALLBACK PARAMETER -->
<script
async defer src="https://maps.googleapis.com/maps/api/js?key={your_api_key}&callback=initMap">
</script>


</html>
