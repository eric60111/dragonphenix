<?php

	include 'lib_link2DB.php';
	
	/** Add Product Purchase **/
	$EmployeeID = !empty($_POST['EmployeeID']) ? $_POST['EmployeeID'] : null; 
	$Date = !empty($_POST['Date']) ? $_POST['Date'] : date("Y-m-d"); 
	
	
	$sql = "INSERT INTO ProductInventories VALUES ";
	$sql .= "(NULL, '$EmployeeID', '', '$Date', 1)";
	mysqli_query($lib_link, stripslashes($sql));	
	$ProductInventoryID = mysqli_insert_id($lib_link);
	LogSql($lib_link, $sql);
	$Barcode = date_format(date_create($Date), "ymd").'P'.sprintf("%03d", $ProductInventoryID);

	$sql = 
	"UPDATE ProductInventories SET ".
	"Barcode = '$Barcode' ".
	"WHERE ProductInventoryID = '$ProductInventoryID'";
	mysqli_query($lib_link, stripslashes($sql));
	LogSql($lib_link, $sql);
	mysqli_close($lib_link);
	
	/** Response **/
	$resp = array(
        'result' => "successful",
        'ProductInventoryID' => $ProductInventoryID
    );
	
	print(json_encode($resp));
?>