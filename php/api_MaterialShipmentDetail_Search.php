<?php

	include 'lib_link2DB.php';

	$MaterialShipmentID = !empty($_POST['MaterialShipmentID']) ? $_POST['MaterialShipmentID'] : "%"; 
		
	$sql = 
	"SELECT MSD.MaterialShipmentDetailID, MSD.MaterialShipmentID, M.MaterialID, M.Name As MaterialName, MSD.SoldPrice, MSD.Quantity, MSD.ActualQuantity, M.Unit ".
	"FROM MaterialShipmentDetails MSD, Materials M ".
	"WHERE ";
	if($MaterialShipmentID != "%") {
		$sql = $sql."MSD.MaterialShipmentID = '$MaterialShipmentID' AND ";
	}
	$sql = $sql.
	"MSD.MaterialID = M.MaterialID AND ".
	"MSD.State = 1 ".
	"ORDER BY MSD.MaterialShipmentDetailID ASC ;";

	
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