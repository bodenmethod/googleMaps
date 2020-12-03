<?php 

//
	require 'bfcmatest.php';
	$cus = new bfcmatest;
	$cus->setId($_REQUEST['id']);
	$cus->setLat($_REQUEST['lat']);
	$cus->setLng($_REQUEST['lng']);
	$status = $cus->updateCustomersWithLatLng();
	if($status == true) {
		echo "Updated...";
	} else {
		echo "Failed...";
	}

 
 
 ?>