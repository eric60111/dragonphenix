<?php

	include 'lib_link2DB.php';
	
	/** Add Product Shipment	**/
	$CompanyID = !empty($_POST['CompanyID']) ? $_POST['CompanyID'] : null;
	$EmployeeID = !empty($_POST['EmployeeID']) ? $_POST['EmployeeID'] : null; 
	$SetupDate = !empty($_POST['SetupDate']) ? $_POST['SetupDate'] : null; 
	
	
	$sql = "INSERT INTO ProductShipments VALUES ";
	$sql .= "(NULL, '$CompanyID', '$EmployeeID', '', '$SetupDate', '0000-00-00', 0, 0, 1)";
	mysqli_query($lib_link, stripslashes($sql));
	$ProductShipmentID = mysqli_insert_id($lib_link);	
	LogSql($lib_link, $sql);
	$Barcode = date_format(date_create($SetupDate), "ymd").'9'.sprintf("%03d", $ProductShipmentID);

	$sql = 
	"UPDATE ProductShipments SET ".
	"Barcode = '$Barcode' ".
	"WHERE ProductShipmentID = '$ProductShipmentID'";
	mysqli_query($lib_link, stripslashes($sql));
	LogSql($lib_link, $sql);
	mysqli_close($lib_link);
	
	/** Response **/
	$resp = array(
        'result' => "successful",
        'ProductShipmentID' => $ProductShipmentID
    );
	
	print(json_encode($resp));
?>