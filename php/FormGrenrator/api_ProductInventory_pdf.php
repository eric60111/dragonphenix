<?php

$ProductInventoryID = !empty($_GET['ProductInventoryID']) ? $_GET['ProductInventoryID'] : null; 

require_once('../lib_link2DB.php');
require_once('../tcpdf/config/lang/zho.php');
require_once('../tcpdf/tcpdf.php');
require_once('../tcpdf/tcpdf_barcodes_1d.php');

$sql = 
"SELECT I.ProductInventoryID, I.Barcode, E.EmployeeID, E.Name as EmployeeName, I.Date ".
"From ProductInventories I, Employees E ".
"WHERE I.ProductInventoryID = '$ProductInventoryID' AND ".
"I.EmployeeID = E.EmployeeID AND ".
"I.State = 1 ".
"ORDER BY I.ProductInventoryID ASC ;";

$res = mysqli_query($lib_link, stripslashes($sql));
while($result = mysqli_fetch_assoc($res) ) {
	$resp[] = $result;
}


$Barcode = $resp[0]['Barcode'];
$EmployeeName = $resp[0]['EmployeeName'];
$Date = $resp[0]['Date'];

class MYPDF extends TCPDF {
		
	//Page header
	public function Header() {
		// Barcode
		$barcode = $this->getHeaderData()['string'];
		$style = array(
			'position' => '',
			'align' => 'C',
			'stretch' => false,
			'fitwidth' => true,
			'cellfitalign' => '',
			'border' => true,
			'hpadding' => 'auto',
			'vpadding' => 'auto',
			'fgcolor' => array(0,0,0),
			'bgcolor' => false, //array(255,255,255),
			'text' => true,
			'font' => 'helvetica',
			'fontsize' => 8,
			'stretchtext' => 4
		);
		$this->write1DBarcode($barcode, 'C128', '', '', '', 16, 0.4, $style, 'N');
		
		// Set font
		$this->SetFont('msjhbd', 'B', 20);
		
		// Title
		$this->Cell(0, 30, '產品盤點單', 0, false, 'C', 0, '', 0, false, 'L', 'C');
	
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
$pdf->setHeaderData($ln='', $lw=$pdf->getPageWidth(), $ht='', $hs=$Barcode, $tc=array(0,0,0), $lc=array(0,0,0));

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('識觀科技');
$pdf->SetTitle('產品盤點單');

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

$pdf->Cell(0, 0, '建單日期：'.date_format(date_create($Date), "Y/m/d"), 0, 1, 'R', 0, '', 0);

// set font
$pdf->SetFont('msjhbd', 'BI', 16);

$Detailsql = 
"SELECT IID.ProductInventoryDetailID, IID.ProductInventoryID, I.ProductID, I.Name As ProductName, IID.Inventory, IID.Difference, I.Unit ".
"FROM ProductInventoryDetails IID, Products I ".
"WHERE IID.ProductInventoryID = '$ProductInventoryID' AND ".
"IID.ProductID = I.ProductID AND ".
"IID.State = 1 ".
"ORDER BY IID.ProductInventoryDetailID ASC ;";

$Detailres = mysqli_query($lib_link, stripslashes($Detailsql));
while($Detailresult = mysqli_fetch_assoc($Detailres) ) {
	$Detailresp[] = $Detailresult;
}


$tbl_header = 
	'<table cellpadding="5" border="1">'.
		'<tr>'.
			'<td colspan="4"  align="center">產品盤點明細</td>'.
		'</tr>'.
		'<tr bgcolor="#F2F2F2">'.
			'<td>品名</td>'.
			'<td align="right">庫存數量</td>'.
			'<td align="right">數量差</td>'.
			'<td>單位</td>'.
		'</tr>';
$tbl_footer = '</table>';


$tbl = '';
$Sum = 0;
foreach ($Detailresp as $value) {
	$tbl = $tbl.
	'<tr>'.
		'<td>'.$value['ProductName'].'</td>'.
		'<td align="right">'.$value['Inventory'].'</td>'.
		'<td align="right">'.$value['Difference'].'</td>'.
		'<td>'.$value['Unit'].'</td>'.
	'</tr>';
}

$pdf->writeHTML($tbl_header.$tbl.$tbl_footer, true, false, false, false, '');

$pdf->Cell(50, 0, '建單人員', 0, 0, 'L', 0, '', 4);
$pdf->Cell(0, 0, '：'.$EmployeeName, 0, 1, 'L', 0, '', 0);

//Close and output PDF document

ob_clean();
$pdf->Output($_SERVER['DOCUMENT_ROOT'].'ProductInventory.pdf', 'I');

mysqli_close($lib_link);
?>