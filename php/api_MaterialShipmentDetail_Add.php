<?php

	include 'lib_link2DB.php';
	
	/** Add Material Shipment Detail **/
	$MaterialShipmentID = !empty($_POST['MaterialShipmentID']) ? $_POST['MaterialShipmentID'] : null;
	$MaterialID = !empty($_POST['MaterialID']) ? $_POST['MaterialID'] : null; 
	$SoldPrice = !empty($_POST['SoldPrice']) ? $_POST['SoldPrice'] : null; 
	$Quantity = !empty($_POST['Quantity']) ? $_POST['Quantity'] : null; 
		
	$sql = "INSERT INTO MaterialShipmentDetails VALUES ";
	$sql .= "(NULL, '$MaterialShipmentID', '$MaterialID', '$SoldPrice', '$Quantity', '$Quantity', 1)";
	mysqli_query($lib_link, stripslashes($sql));	
	$MaterialShipmentDetailID = mysqli_insert_id($lib_link);
	LogSql($lib_link, $sql);
	
	$sql = 
	"UPDATE Materials SET ".
	"SoldPrice = '$SoldPrice' ".
	"WHERE MaterialID = '$MaterialID'";
	mysqli_query($lib_link, stripslashes($sql));
	LogSql($lib_link, $sql);
	mysqli_close($lib_link);
	
	/** Response **/
	$resp = array(
        'result' => "successful",
        'MaterialShipmentDetailID' => $MaterialShipmentDetailID
    );
	
	print(json_encode($resp));
?>