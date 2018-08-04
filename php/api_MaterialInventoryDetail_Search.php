<?php

	include 'lib_link2DB.php';

	$MaterialInventoryID = !empty($_POST['MaterialInventoryID']) ? $_POST['MaterialInventoryID'] : "%"; 
		
	$sql = 
	"SELECT MID.MaterialInventoryDetailID, MID.MaterialInventoryID, M.MaterialID, M.Name As MaterialName, MID.Inventory, MID.Difference, M.Unit ".
	"FROM MaterialInventoryDetails MID, Materials M ".
	"WHERE ";
	if($MaterialInventoryID != "%") {
		$sql = $sql."MID.MaterialInventoryID = '$MaterialInventoryID' AND ";
	}
	$sql = $sql.
	"MID.MaterialID = M.MaterialID AND ".
	"MID.State = 1 ".
	"ORDER BY MID.MaterialInventoryDetailID ASC ;";

	
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