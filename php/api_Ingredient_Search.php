<?php

	include 'lib_link2DB.php';


	$IngredientID = !empty($_POST['IngredientID']) ? $_POST['IngredientID'] : "%";  
	$Name = !empty($_POST['Name']) ? $_POST['Name'] : "%";  
	
	$sql = 
	"SELECT * ".
	"FROM Ingredients ".
	"WHERE ";
	if($IngredientID != "%") {
		$sql = $sql."IngredientID = '$IngredientID' AND ";
	}
	$sql = $sql.
	"Name LIKE '%$Name%' AND ".
	"State = 1 ".
	"ORDER BY IngredientID ASC ;";


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