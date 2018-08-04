<?php

$EmployeeName = !empty($_GET['EmployeeName']) ? $_GET['EmployeeName'] : null; 
$CondField = !empty($_GET['CondField']) ? $_GET['CondField'] : null; 
$CondValue = !empty($_GET['CondValue']) ? $_GET['CondValue'] : "0"; 
$LikeField = !empty($_GET['LikeField']) ? $_GET['LikeField'] : null; 
$LikeKeyword = !empty($_GET['LikeKeyword']) ? $_GET['LikeKeyword'] : "%"; 
$DateAfterValue = !empty($_GET['DateAfterValue']) ? $_GET['DateAfterValue'] : null; 
$DateBeforeValue = !empty($_GET['DateBeforeValue']) ? $_GET['DateBeforeValue'] : null; 

if(!is_null($LikeField))
	$LikeField = str_replace("SetupEmployee","E.Name",$LikeField);

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
		$this->Cell(0, 30, '配方料出貨彙總表', 0, false, 'C', 0, '', 0, false, 'C', 'B');
	
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
$pdf->SetTitle('配方料出貨彙總表');

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
			'<td>廠商名稱</td>'.
			'<td>品項名稱</td>'.
			'<td align="right">品項數量</td>'.
			'<td align="right">品項售價</td>'.
		'</tr>';
$tbl_footer = '</table>';
$tbl = '';

$sql = 
"SELECT M.MaterialShipmentID, C.CompanyName, M.SetupDate ".
"From MaterialShipments M, Companies C, Employees E ".
"WHERE ";
if(!is_null($CondField)) {
	$sql = $sql."M.".$CondField."=".$CondValue." AND ";
}
if(!is_null($LikeField)) {
	$sql = $sql.$LikeField." Like '%".$LikeKeyword."%' AND ";
}
if(!is_null($DateAfterValue)) {
	$sql = $sql." M.SetupDate >= '".$DateAfterValue."' AND ";
}
if(!is_null($DateBeforeValue)) {
	$sql = $sql." M.SetupDate <= '".$DateBeforeValue."' AND ";
}
$sql = $sql.
"M.CompanyID = C.CompanyID AND ".
"M.SetupEmployeeID = E.EmployeeID AND ".
"M.State = 1 ".
"ORDER BY M.MaterialShipmentID ASC ;";

$res = mysqli_query($lib_link, stripslashes($sql));
while($result = mysqli_fetch_assoc($res) ) {
	$resp[] = $result;
}
$ItemSum = array();
foreach ($resp as $value) {
	$MaterialShipmentID = $value['MaterialShipmentID'];
	$CompanyName = $value['CompanyName'];
	$SetupDate = $value['SetupDate'];
	
	$Detailsql = 
	"SELECT IPD.MaterialShipmentDetailID, M.MaterialID, M.Name As MaterialName, IPD.SoldPrice, IPD.ActualQuantity, M.Unit ".
	"FROM MaterialShipmentDetails IPD, Materials M ".
	"WHERE IPD.MaterialShipmentID = '$MaterialShipmentID' AND ".
	"IPD.MaterialID = M.MaterialID AND ".
	"IPD.State = 1 ".
	"ORDER BY IPD.MaterialShipmentDetailID ASC ;";
	$Detailres = mysqli_query($lib_link, stripslashes($Detailsql));
	$Detailresp = array();
	while($Detailresult = mysqli_fetch_assoc($Detailres) ) {
		$Detailresp[] = $Detailresult;
	}
	$i = 0;
	foreach ($Detailresp as $Detailvalue) {
		$MaterialID = $Detailvalue['MaterialID'];
		$MaterialName = $Detailvalue['MaterialName'];
		$ActualQuantity = $Detailvalue['ActualQuantity'];
		$Unit = $Detailvalue['Unit'];
		$SoldPrice = $Detailvalue['SoldPrice'];
		if($i++ == 0) {
			$tbl = $tbl.
			'<tr>'.
				'<td>'.date_format(date_create($SetupDate), "Y/m/d").'</td>'.
				'<td>'.$CompanyName.'</td>'.
				'<td>'.$MaterialName.'</td>'.
				'<td align="right">'.$ActualQuantity.$Unit.'</td>'.
				'<td align="right">'.$SoldPrice.'</td>'.
			'</tr>';
		} else {
			$tbl = $tbl.
			'<tr>'.
				'<td></td>'.
				'<td></td>'.
				'<td>'.$MaterialName.'</td>'.
				'<td align="right">'.$ActualQuantity.$Unit.'</td>'.
				'<td align="right">'.$SoldPrice.'</td>'.
			'</tr>';
		}
		$Sum = $ActualQuantity * $SoldPrice;
		if(isset($ItemSum[$MaterialID]['Quantity'])) {
			$ActualQuantity += $ItemSum[$MaterialID]['Quantity'];
		}
		if(isset($ItemSum[$MaterialID]['Sum'])) {
			$Sum += $ItemSum[$MaterialID]['Sum'];
		}
		$ItemSum[$MaterialID] = array(
			'MaterialName' => $MaterialName,
			'Quantity' => $ActualQuantity,
			'Unit' => $Unit,
			'Sum' => $Sum
		);
	}
}
//print_r($ItemSum);

$pdf->writeHTML($tbl_header.$tbl.$tbl_footer, true, false, false, false, '');

$tbl_header = 
	'<table cellpadding="5" border="1">'.
		'<tr>'.
			'<td colspan="3"  align="center">品項彙總</td>'.
		'</tr>'.
		'<tr bgcolor="#F2F2F2">'.
			'<td>品項名稱</td>'.
			'<td align="right">品項總數</td>'.
			'<td align="right">品項總額</td>'.
		'</tr>';
$tbl_footer = '</table>';
$tbl = '';
$Sum = 0;
foreach ($ItemSum as $value) {
	
	$tbl = $tbl.
	'<tr>'.
		'<td>'.$value['MaterialName'].'</td>'.
		'<td align="right">'.$value['Quantity'].$value['Unit'].'</td>'.
		'<td align="right">'.$value['Sum'].'</td>'.
	'</tr>';
	$Sum += $value['Sum'];
}
$tbl = $tbl.
	'<tr>'.
		'<td colspan="2">總額</td>'.
		'<td align="right">'.$Sum.'</td>'.
	'</tr>';
$pdf->writeHTML($tbl_header.$tbl.$tbl_footer, true, false, false, false, '');
$pdf->Cell(0, 0, '彙總人員：'.$EmployeeName, 0, 1, 'R', 0, '', 0);
ob_clean();
$pdf->Output($_SERVER['DOCUMENT_ROOT'].'MaterialShipment.pdf', 'I');

mysqli_close($lib_link);
?>