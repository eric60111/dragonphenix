<?php

	include 'lib_link2DB.php';
	
	/** Add Product Purchase **/
	$CompanyID = !empty($_POST['CompanyID']) ? $_POST['CompanyID'] : null;
	$EmployeeID = !empty($_POST['EmployeeID']) ? $_POST['EmployeeID'] : null; 
	$SetupDate = !empty($_POST['SetupDate']) ? $_POST['SetupDate'] : date("Y-m-d"); 
	
	
	$sql = "INSERT INTO ProductPurchases VALUES ";
	$sql .= "(NULL, '$CompanyID', '$EmployeeID', '', '$SetupDate', '0000-00-00', 0, 0, 1)";
	mysqli_query($lib_link, stripslashes($sql));	
	$ProductPurchaseID = mysqli_insert_id($lib_link);
	LogSql($lib_link, $sql);
	$Barcode = date_format(date_create($SetupDate), "ymd").'6'.sprintf("%03d", $ProductPurchaseID);

	$sql = 
	"UPDATE ProductPurchases SET ".
	"Barcode = '$Barcode' ".
	"WHERE ProductPurchaseID = '$ProductPurchaseID'";
	mysqli_query($lib_link, stripslashes($sql));
	LogSql($lib_link, $sql);
	mysqli_close($lib_link);
	
	/** Response **/
	$resp = array(
        'result' => "successful",
        'ProductPurchaseID' => $ProductPurchaseID
    );
	
	print(json_encode($resp));
?>