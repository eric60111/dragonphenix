<?php

	include 'lib_link2DB.php';
	
	/** Delete Ingredient Shipment**/
	$IngredientShipmentID = !empty($_POST['IngredientShipmentID']) ? $_POST['IngredientShipmentID'] : null; 
	$CompanyID = !empty($_POST['CompanyID']) ? $_POST['CompanyID'] : 0; 
	$VerifyState = !empty($_POST['VerifyState']) ? $_POST['VerifyState'] : null;
	$VerifyAction = !empty($_POST['VerifyAction']) ? $_POST['VerifyAction'] : 0;
	$State = !empty($_POST['State']) ? $_POST['State'] : null;
	$IngredientShipmentDetail = !empty($_POST['IngredientShipmentDetail']) ? $_POST['IngredientShipmentDetail'] : 'undefined';
	
	if($CompanyID != 0) {
		$sql = 
		"UPDATE IngredientShipments SET ".
		"CompanyID = '$CompanyID' ".
		"WHERE IngredientShipmentID = '$IngredientShipmentID'";
		mysqli_query($lib_link, stripslashes($sql));
		LogSql($lib_link, $sql);
	}
	if($State == 0) {
		$sql = 
		"UPDATE IngredientShipments SET ".
		"State = '0' ".
		"WHERE IngredientShipmentID = '$IngredientShipmentID'";
		
		mysqli_query($lib_link, stripslashes($sql));
		LogSql($lib_link, $sql);
		$sql = 
		"UPDATE IngredientShipmentDetails SET ".
		"State = '0' ".
		"WHERE IngredientShipmentID = '$IngredientShipmentID'";
		
		mysqli_query($lib_link, stripslashes($sql));	
		LogSql($lib_link, $sql);
		if($VerifyState == 1) {
			$sql = 
			"SELECT I.IngredientID, ISD.ActualQuantity, I.Inventory ".
			"FROM IngredientShipmentDetails ISD, Ingredients I ".
			"WHERE ISD.IngredientShipmentID = '$IngredientShipmentID' AND ".
			"ISD.IngredientID = I.IngredientID ".
			"ORDER BY I.IngredientID ASC ;";
			
			$res = mysqli_query($lib_link, stripslashes($sql));
			while($result = mysqli_fetch_assoc($res) ) {
				$resp[] = $result;
			}
			
			foreach ($resp as $value) {
				$sum = $value['Inventory'] + $value['ActualQuantity'];
				$IngredientID = $value['IngredientID'];
				
				$editsql = 
				"UPDATE Ingredients SET ".
				"Inventory = '$sum'".
				"WHERE IngredientID = '$IngredientID'";
				mysqli_query($lib_link, stripslashes($editsql));
				LogSql($lib_link, $editsql);
			}
		}
	} else if ($VerifyAction == 1) {
		$EmployeeID = !empty($_POST['EmployeeID']) ? $_POST['EmployeeID'] : null; 
		$VerifyDate = date("Y-m-d"); 
		$IsPrintBarcode = !empty($_POST['IsPrintBarcode']) ? $_POST['IsPrintBarcode'] : false; 
		
		$sql = "SELECT VerifyState FROM IngredientShipments WHERE IngredientShipmentID = '$IngredientShipmentID'";
		$res = mysqli_query($lib_link, stripslashes($sql));
		$nowverifystate = mysqli_fetch_assoc($res)['VerifyState'];
		
		if($nowverifystate == 0) {
			if($IngredientShipmentDetail != 'undefined') {
				$data = json_decode($IngredientShipmentDetail);
				foreach($data as $value) {
					if($value != null) {
						$IngredientShipmentDetailID = $value->{'IngredientShipmentDetailID'};
						$ActualQuantity = $value->{'ActualQuantity'};
						$SoldPrice = $value->{'SoldPrice'};
						$editsql = 
						"UPDATE IngredientShipmentDetails SET ".
						"ActualQuantity = '$ActualQuantity', ".
						"SoldPrice = '$SoldPrice' ".
						"WHERE IngredientShipmentDetailID = '$IngredientShipmentDetailID'";
						mysqli_query($lib_link, stripslashes($editsql));
						LogSql($lib_link, $editsql);
					}
				}
				
			}
			
			$sql = 
			"UPDATE IngredientShipments SET ".
			"VerifyState = '1', ".
			"VerifyEmployeeID = '$EmployeeID', ".
			"VerifyDate = '$VerifyDate'".
			"WHERE IngredientShipmentID = '$IngredientShipmentID'";
			
			mysqli_query($lib_link, stripslashes($sql));	
			LogSql($lib_link, $sql);
			$sql = 
			"SELECT I.IngredientID, ISD.ActualQuantity, I.Inventory ".
			"FROM IngredientShipmentDetails ISD, Ingredients I ".
			"WHERE ISD.IngredientShipmentID = '$IngredientShipmentID' AND ".
			"ISD.IngredientID = I.IngredientID AND ".
			"ISD.State = 1 ".
			"ORDER BY I.IngredientID ASC ;";
			
			$res = mysqli_query($lib_link, stripslashes($sql));
			while($result = mysqli_fetch_assoc($res) ) {
				$resp[] = $result;
			}
			
			foreach ($resp as $value) {
				$sum = $value['Inventory'] - $value['ActualQuantity'];
				$IngredientID = $value['IngredientID'];
				
				$editsql = 
				"UPDATE Ingredients SET ".
				"Inventory = '$sum'".
				"WHERE IngredientID = '$IngredientID'";
				mysqli_query($lib_link, stripslashes($editsql));
				LogSql($lib_link, $editsql);
				
				$Cost = 0;	
				$calCostSql =
				"SELECT IngredientPurchaseDetailID, ActualQuantity, UnitPrice ".
				"FROM IngredientPurchaseDetails ".
				"WHERE IngredientID = '$IngredientID' AND ".
				"State = 1 ".
				"ORDER BY IngredientPurchaseDetailID DESC ;";
				
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
				"UPDATE Ingredients SET ".
				"Cost = '$Cost'".
				"WHERE IngredientID = '$IngredientID'";
				mysqli_query($lib_link, stripslashes($editsql));
				LogSql($lib_link, $editsql);
			}
		}
	}
	/** Response **/
	$resp = array(
        'result' => "successful",
        'IngredientShipmentID' => $IngredientShipmentID
    );
	
	print(json_encode($resp));
	mysqli_close($lib_link);
?>