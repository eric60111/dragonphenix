<?php

	include 'lib_link2DB.php';
	
	/** Add Ingredient Purchase **/
	$CompanyID = !empty($_POST['CompanyID']) ? $_POST['CompanyID'] : null;
	$EmployeeID = !empty($_POST['EmployeeID']) ? $_POST['EmployeeID'] : null; 
	$SetupDate = !empty($_POST['SetupDate']) ? $_POST['SetupDate'] : date("Y-m-d"); 
	
	
	$sql = "INSERT INTO IngredientPurchases VALUES ";
	$sql .= "(NULL, '$CompanyID', '$EmployeeID', '', '$SetupDate', '0000-00-00', 0, 0, 1)";
	mysqli_query($lib_link, stripslashes($sql));	
	$IngredientPurchaseID = mysqli_insert_id($lib_link);
	LogSql($lib_link, $sql);
	$Barcode = date_format(date_create($SetupDate), "ymd").'4'.sprintf("%03d", $IngredientPurchaseID);

	$sql = 
	"UPDATE IngredientPurchases SET ".
	"Barcode = '$Barcode' ".
	"WHERE IngredientPurchaseID = '$IngredientPurchaseID'";
	mysqli_query($lib_link, stripslashes($sql));
	LogSql($lib_link, $sql);
	mysqli_close($lib_link);
	
	/** Response **/
	$resp = array(
        'result' => "successful",
        'IngredientPurchaseID' => $IngredientPurchaseID
    );
	
	print(json_encode($resp));
?>