<?php

$SetUpName = !empty($_GET['EmployeeName']) ? $_GET['EmployeeName'] : null; 
$LikeField = !empty($_GET['LikeField']) ? $_GET['LikeField'] : null; 
$LikeKeyword = !empty($_GET['LikeKeyword']) ? $_GET['LikeKeyword'] : "%"; 
$DateAfterValue = !empty($_GET['DateAfterValue']) ? $_GET['DateAfterValue'] : null; 
$DateBeforeValue = !empty($_GET['DateBeforeValue']) ? $_GET['DateBeforeValue'] : null; 

if(!is_null($LikeField))
	$LikeField = str_replace("EmployeeName","E.Name",$LikeField);

require_once('../lib_link2DB.php');
require_once('../tcpdf/config/lang/zho.php');
require_once('../tcpdf/tcpdf.php');
require_once('../tcpdf/tcpdf_barcodes_1d.php');

class MYPDF extends TCPDF {
		
	//Page header
	public function Header() {
		
		// Set font
		$this->SetFont('msjhbd', 'B', 20);
		
		// Title
		$this->Cell(0, 30, '原料盤點彙總表', 0, false, 'C', 0, '', 0, false, 'C', 'B');
	
		// Logo
		$image_file = '../tcpdf/image/logo.jpg';
		$this->Image($image_file, 0, 10, 40, '', 'JPG', '', 'T', false, 300, 'R', false, false, 0, false, false, false);
		
		// add separate line
		$pageWidth    = $this->getHeaderData()['logo_width'];
		$x1   = $this->getX();
		$x2   = $pageWidth - $x1;
		$y   = PDF_MARGIN_TOP;
		$style = array();
		$this->Line($x1, $y, $x2, $y, $style);
		
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}


// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set barcode number
$pdf->setHeaderData($ln='', $lw=$pdf->getPageWidth(), $ht='', $hs='', $tc=array(0,0,0), $lc=array(0,0,0));

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('識觀科技');
$pdf->SetTitle('原料盤點彙總表');

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$pdf->setCellPaddings(1,2,1,2);

// add a page
$pdf->AddPage();

// set font
$pdf->SetFont('msjhbd', 'BI', 12);

$pdf->Cell(0, 0, '表單產生日期：'.date("Y/m/d"), 0, 1, 'R', 0, '', 0);

// set font
$pdf->SetFont('msjhbd', 'BI', 14);

$tbl_header = 
	'<table cellpadding="5" border="1">'.
		'<tr>'.
			'<td colspan="5"  align="center">明細彙總</td>'.
		'</tr>'.
		'<tr bgcolor="#F2F2F2">'.
			'<td>建單日期</td>'.
			'<td>建單人員</td>'.
			'<td>品項名稱</td>'.
			'<td align="right">庫存數量</td>'.
			'<td align="right">數量差</td>'.
		'</tr>';
$tbl_footer = '</table>';
$tbl = '';

$sql = 
"SELECT I.IngredientInventoryID, I.Date, E.Name As EmployeeName ".
"From IngredientInventories I, Employees E ".
"WHERE ";
if(!is_null($LikeField)) {
	$sql = $sql.$LikeField." Like '%".$LikeKeyword."%' AND ";
}
if(!is_null($DateAfterValue)) {
	$sql = $sql." I.Date >= '".$DateAfterValue."' AND ";
}
if(!is_null($DateBeforeValue)) {
	$sql = $sql." I.Date <= '".$DateBeforeValue."' AND ";
}
$sql = $sql.
"I.EmployeeID = E.EmployeeID AND ".
"I.State = 1 ".
"ORDER BY I.IngredientInventoryID ASC ;";

$res = mysqli_query($lib_link, stripslashes($sql));
while($result = mysqli_fetch_assoc($res) ) {
	$resp[] = $result;
}
$ItemSum = array();
foreach ($resp as $value) {
	$IngredientInventoryID = $value['IngredientInventoryID'];
	$EmployeeName = $value['EmployeeName'];
	$Date = $value['Date'];
	
	$Detailsql = 
	"SELECT IID.IngredientInventoryDetailID, I.IngredientID, I.Name As IngredientName, IID.Inventory, IID.Difference, I.Unit ".
	"FROM IngredientInventoryDetails IID, Ingredients I ".
	"WHERE IID.IngredientInventoryID = '$IngredientInventoryID' AND ".
	"IID.IngredientID = I.IngredientID AND ".
	"IID.State = 1 ".
	"ORDER BY IID.IngredientInventoryDetailID ASC ;";
	$Detailres = mysqli_query($lib_link, stripslashes($Detailsql));
	$Detailresp = array();
	while($Detailresult = mysqli_fetch_assoc($Detailres) ) {
		$Detailresp[] = $Detailresult;
	}
	$i = 0;
	foreach ($Detailresp as $Detailvalue) {
		$IngredientID = $Detailvalue['IngredientID'];
		$IngredientName = $Detailvalue['IngredientName'];
		$Inventory = $Detailvalue['Inventory'];
		$Unit = $Detailvalue['Unit'];
		$Difference = $Detailvalue['Difference'];
		if($i++ == 0) {
			$tbl = $tbl.
			'<tr>'.
				'<td>'.date_format(date_create($Date), "Y/m/d").'</td>'.
				'<td>'.$EmployeeName.'</td>'.
				'<td>'.$IngredientName.'</td>'.
				'<td align="right">'.$Inventory.$Unit.'</td>'.
				'<td align="right">'.$Difference.$Unit.'</td>'.
			'</tr>';
		} else {
			$tbl = $tbl.
			'<tr>'.
				'<td></td>'.
				'<td></td>'.
				'<td>'.$IngredientName.'</td>'.
				'<td align="right">'.$Inventory.$Unit.'</td>'.
				'<td align="right">'.$Difference.$Unit.'</td>'.
			'</tr>';
		}
	}
}
//print_r($ItemSum);

$pdf->writeHTML($tbl_header.$tbl.$tbl_footer, true, false, false, false, '');

$pdf->Cell(0, 0, '彙總人員：'.$SetUpName, 0, 1, 'R', 0, '', 0);

ob_clean();
$pdf->Output($_SERVER['DOCUMENT_ROOT'].$_SERVER['DOCUMENT_ROOT'].$_SERVER['DOCUMENT_ROOT'].'IngredientPurchase.pdf', 'I');

mysqli_close($lib_link);
?>