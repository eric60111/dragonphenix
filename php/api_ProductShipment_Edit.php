<?php

	include 'lib_link2DB.php';
	
	/** Delete Product Shipment**/
	$ProductShipmentID = !empty($_POST['ProductShipmentID']) ? $_POST['ProductShipmentID'] : null; 
	$CompanyID = !empty($_POST['CompanyID']) ? $_POST['CompanyID'] : 0; 
	$VerifyState = !empty($_POST['VerifyState']) ? $_POST['VerifyState'] : null;
	$VerifyAction = !empty($_POST['VerifyAction']) ? $_POST['VerifyAction'] : 0;
	$State = !empty($_POST['State']) ? $_POST['State'] : null;
	$ProductShipmentDetail = !empty($_POST['ProductShipmentDetail']) ? $_POST['ProductShipmentDetail'] : 'undefined';
	
	if($CompanyID != 0) {
		$sql = 
		"UPDATE ProductShipments SET ".
		"CompanyID = '$CompanyID' ".
		"WHERE ProductShipmentID = '$ProductShipmentID'";
		mysqli_query($lib_link, stripslashes($sql));
		LogSql($lib_link, $sql);
	}
	if($State == 0) {
		$sql = 
		"UPDATE ProductShipments SET ".
		"State = '0' ".
		"WHERE ProductShipmentID = '$ProductShipmentID'";
		
		mysqli_query($lib_link, stripslashes($sql));
		LogSql($lib_link, $sql);
		$sql = 
		"UPDATE ProductShipmentDetails SET ".
		"State = '0' ".
		"WHERE ProductShipmentID = '$ProductShipmentID'";
		
		mysqli_query($lib_link, stripslashes($sql));	
		LogSql($lib_link, $sql);
		if($VerifyState == 1) {
			$sql = 
			"SELECT P.ProductID, PSD.ActualQuantity, P.Inventory ".
			"FROM ProductShipmentDetails PSD, Products P ".
			"WHERE PSD.ProductShipmentID = '$ProductShipmentID' AND ".
			"PSD.ProductID = P.ProductID ".
			"ORDER BY P.ProductID ASC ;";
			
			$res = mysqli_query($lib_link, stripslashes($sql));
			while($result = mysqli_fetch_assoc($res) ) {
				$resp[] = $result;
			}
			
			foreach ($resp as $value) {
				$sum = $value['Inventory'] + $value['ActualQuantity'];
				$ProductID = $value['ProductID'];
				
				$editsql = 
				"UPDATE Products SET ".
				"Inventory = '$sum'".
				"WHERE ProductID = '$ProductID'";
				mysqli_query($lib_link, stripslashes($editsql));
				LogSql($lib_link, $editsql);
			}
		}
	} else if ($VerifyAction == 1) {
		$EmployeeID = !empty($_POST['EmployeeID']) ? $_POST['EmployeeID'] : null; 
		$VerifyDate = date("Y-m-d"); 
		$IsPrintBarcode = !empty($_POST['IsPrintBarcode']) ? $_POST['IsPrintBarcode'] : false; 
		
		$sql = "SELECT VerifyState FROM ProductShipments WHERE ProductShipmentID = '$ProductShipmentID'";
		$res = mysqli_query($lib_link, stripslashes($sql));
		$nowverifystate = mysqli_fetch_assoc($res)['VerifyState'];
		
		if($nowverifystate == 0) {
			if($ProductShipmentDetail != 'undefined') {
				$data = json_decode($ProductShipmentDetail);
				foreach($data as $value) {
					if($value != null) {
						$ProductShipmentDetailID = $value->{'ProductShipmentDetailID'};
						$ActualQuantity = $value->{'ActualQuantity'};
						$SoldPrice = $value->{'SoldPrice'};
						$editsql = 
						"UPDATE ProductShipmentDetails SET ".
						"ActualQuantity = '$ActualQuantity', ".
						"SoldPrice = '$SoldPrice' ".
						"WHERE ProductShipmentDetailID = '$ProductShipmentDetailID'";
						mysqli_query($lib_link, stripslashes($editsql));
						LogSql($lib_link, $editsql);
					}
				}
				
			}
			
			$sql = 
			"UPDATE ProductShipments SET ".
			"VerifyState = '1', ".
			"VerifyEmployeeID = '$EmployeeID', ".
			"VerifyDate = '$VerifyDate'".
			"WHERE ProductShipmentID = '$ProductShipmentID'";
			
			mysqli_query($lib_link, stripslashes($sql));	
			LogSql($lib_link, $sql);
			$sql = 
			"SELECT P.ProductID, PSD.ActualQuantity, P.Inventory ".
			"FROM ProductShipmentDetails PSD, Products P ".
			"WHERE PSD.ProductShipmentID = '$ProductShipmentID' AND ".
			"PSD.ProductID = P.ProductID AND ".
			"PSD.State = 1 ".
			"ORDER BY P.ProductID ASC ;";
			
			$res = mysqli_query($lib_link, stripslashes($sql));
			while($result = mysqli_fetch_assoc($res) ) {
				$resp[] = $result;
			}
			
			foreach ($resp as $value) {
				$sum = $value['Inventory'] - $value['ActualQuantity'];
				$ProductID = $value['ProductID'];
				
				$editsql = 
				"UPDATE Products SET ".
				"Inventory = '$sum'".
				"WHERE ProductID = '$ProductID'";
				mysqli_query($lib_link, stripslashes($editsql));
				LogSql($lib_link, $editsql);
				$Cost = 0;	
				$calCostSql =
				"SELECT ProductPurchaseDetailID, ActualQuantity, UnitPrice ".
				"FROM ProductPurchaseDetails ".
				"WHERE ProductID = '$ProductID' AND ".
				"State = 1 ".
				"ORDER BY ProductPurchaseDetailID DESC ;";
				
				$calCostRes = mysqli_query($lib_link, stripslashes($calCostSql));
				while($calCostResult = mysqli_fetch_assoc($calCostRes) ) {
					$Quantity =  $calCostResult['ActualQuantity'];
					$UnitPrice =  $calCostResult['UnitPrice'];
					if($sum - $Quantity > 0) {
						$Cost += ($Quantity * $UnitPrice);
						$sum -= $Quantity;
					} else {
						$Cost += ($sum * $UnitPrice);
						break;
					}
				}
				
				$editsql = 
				"UPDATE Products SET ".
				"Cost = '$Cost'".
				"WHERE ProductID = '$ProductID'";
				mysqli_query($lib_link, stripslashes($editsql));
				LogSql($lib_link, $editsql);
			}
		}
	}
	/** Response **/
	$resp = array(
        'result' => "successful",
        'ProductShipmentID' => $ProductShipmentID
    );
	
	print(json_encode($resp));
	mysqli_close($lib_link);
?>