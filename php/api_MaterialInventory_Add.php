<?php

	include 'lib_link2DB.php';
	
	/** Add Material Purchase **/
	$EmployeeID = !empty($_POST['EmployeeID']) ? $_POST['EmployeeID'] : null; 
	$Date = !empty($_POST['Date']) ? $_POST['Date'] : date("Y-m-d"); 
	
	
	$sql = "INSERT INTO MaterialInventories VALUES ";
	$sql .= "(NULL, '$EmployeeID', '', '$Date', 1)";
	mysqli_query($lib_link, stripslashes($sql));	
	$MaterialInventoryID = mysqli_insert_id($lib_link);
	LogSql($lib_link, $sql);
	$Barcode = date_format(date_create($Date), "ymd").'M'.sprintf("%03d", $MaterialInventoryID);

	$sql = 
	"UPDATE MaterialInventories SET ".
	"Barcode = '$Barcode' ".
	"WHERE MaterialInventoryID = '$MaterialInventoryID'";
	mysqli_query($lib_link, stripslashes($sql));
	LogSql($lib_link, $sql);
	mysqli_close($lib_link);
	
	/** Response **/
	$resp = array(
        'result' => "successful",
        'MaterialInventoryID' => $MaterialInventoryID
    );
	
	print(json_encode($resp));
?>