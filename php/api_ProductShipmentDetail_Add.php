<?php

	include 'lib_link2DB.php';
	
	/** Add Product Shipment Detail **/
	$ProductShipmentID = !empty($_POST['ProductShipmentID']) ? $_POST['ProductShipmentID'] : null;
	$ProductID = !empty($_POST['ProductID']) ? $_POST['ProductID'] : null; 
	$SoldPrice = !empty($_POST['SoldPrice']) ? $_POST['SoldPrice'] : null; 
	$Quantity = !empty($_POST['Quantity']) ? $_POST['Quantity'] : null; 
		
	$sql = "INSERT INTO ProductShipmentDetails VALUES ";
	$sql .= "(NULL, '$ProductShipmentID', '$ProductID', '$SoldPrice', '$Quantity', '$Quantity', 1)";
	mysqli_query($lib_link, stripslashes($sql));	
	$ProductShipmentDetailID = mysqli_insert_id($lib_link);
	LogSql($lib_link, $sql);
	
	$sql = 
	"UPDATE Products SET ".
	"SoldPrice = '$SoldPrice' ".
	"WHERE ProductID = '$ProductID'";
	mysqli_query($lib_link, stripslashes($sql));
	LogSql($lib_link, $sql);
	mysqli_close($lib_link);
	
	/** Response **/
	$resp = array(
        'result' => "successful",
        'ProductShipmentDetailID' => $ProductShipmentDetailID
    );
	
	print(json_encode($resp));
?>