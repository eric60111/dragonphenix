<?php

	include 'lib_link2DB.php';

	$MaterialPurchaseID = !empty($_POST['MaterialPurchaseID']) ? $_POST['MaterialPurchaseID'] : "%"; 
		
	$sql = 
	"SELECT MPD.MaterialPurchaseDetailID, MPD.MaterialPurchaseID, M.MaterialID, M.Name As MaterialName, MPD.UnitPrice, MPD.Quantity, MPD.ActualQuantity, M.Unit ".
	"FROM MaterialPurchaseDetails MPD, Materials M ".
	"WHERE ";
	if($MaterialPurchaseID != "%") {
		$sql = $sql."MPD.MaterialPurchaseID = '$MaterialPurchaseID' AND ";
	}
	$sql = $sql.
	"MPD.MaterialID = M.MaterialID AND ".
	"MPD.State = 1 ".
	"ORDER BY MPD.MaterialPurchaseDetailID ASC ;";

	
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