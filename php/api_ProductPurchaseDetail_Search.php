<?php

	include 'lib_link2DB.php';

	$ProductPurchaseID = !empty($_POST['ProductPurchaseID']) ? $_POST['ProductPurchaseID'] : "%"; 
		
	$sql = 
	"SELECT PPD.ProductPurchaseDetailID, PPD.ProductPurchaseID, P.ProductID, P.Name As ProductName, PPD.UnitPrice, PPD.Quantity, PPD.ActualQuantity, P.Unit ".
	"FROM ProductPurchaseDetails PPD, Products P ".
	"WHERE ";
	if($ProductPurchaseID != "%") {
		$sql = $sql."PPD.ProductPurchaseID = '$ProductPurchaseID' AND ";
	}
	$sql = $sql.
	"PPD.ProductID = P.ProductID AND ".
	"PPD.State = 1 ".
	"ORDER BY PPD.ProductPurchaseDetailID ASC ;";

	
	$res = mysqli_query($lib_link, stripslashes($sql));
	while($result = mysqli_fetch_assoc($res) ) {
		$resp[] = $result;
	}
	mysqli_close($lib_link);
	
	if(isset($resp)) {
		print(json_encode($resp));
	}else{
		print(json_encode(array('result' => 'empty')));
	}
?>