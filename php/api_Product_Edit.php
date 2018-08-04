<?php

	include 'lib_link2DB.php';
	
	/** Edit Product **/
	$ProductID = isset($_POST['ProductID']) ? $_POST['ProductID'] : null; 
	$Name = isset($_POST['Name']) ? $_POST['Name'] : null;
	$Unit = isset($_POST['Unit']) ? $_POST['Unit'] : null;
	$Inventory = isset($_POST['Inventory']) ? $_POST['Inventory'] : null;
	$UnitPrice = isset($_POST['UnitPrice']) ? $_POST['UnitPrice'] : null;
	$SoldPrice = isset($_POST['SoldPrice']) ? $_POST['SoldPrice'] : null;
	$Number = isset($_POST['Number']) ? $_POST['Number'] : null;
	$Color = isset($_POST['Color']) ? $_POST['Color'] : null;
	$State = isset($_POST['State']) ? $_POST['State'] : null; 

	$sql = 
	"UPDATE Products SET ".
	"Name = '$Name', ".
	"Unit = '$Unit', ";
	if(!is_null($Inventory)) {
		$sql = $sql."Inventory = '$Inventory', ";
	}
	if(!is_null($UnitPrice)) {
		$sql = $sql."UnitPrice = '$UnitPrice', ";
	}
	if(!is_null($SoldPrice)) {
		$sql = $sql."SoldPrice = '$SoldPrice', ";
	}
	if(!is_null($Number)) {
		$sql = $sql."Number = '$Number', ";
	}
	if(!is_null($Color)) {
		$sql = $sql."Color = '$Color', ";
	}
	$sql = $sql.
	"State = '$State' ".
	"WHERE ProductID = '$ProductID'";
	mysqli_query($lib_link, stripslashes($sql));	
	LogSql($lib_link, $sql);
	mysqli_close($lib_link);

	/** Response **/
	$resp = array(
        'result' => "successful",
        'ProductID' => $ProductID
    );
	
	print(json_encode($resp));
?>