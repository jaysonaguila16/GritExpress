<?php
require('../fpdf.php');

$d=date('d_m_Y');

class PDF extends FPDF
{

function Header()
{
    $this->SetFont('Helvetica','',25);
	$this->SetFontSize(40);
    //Move to the right
    $this->Cell(90);
    //Line break
    $this->Ln();
}

//Page footer
function Footer()
{
   
}

//Load data
function LoadData($file)
{
	//Read file lines
	$lines=file($file);
	$data=array();
	foreach($lines as $line)
		$data[]=explode(';',chop($line));
	return $data;
}

//Simple table
function BasicTable($header,$data)
{ 

$this->SetFillColor(200,255,255);
//$this->SetDrawColor(255, 0, 0);
$w=array(50,70,40,65,50,20,20,20,18,15,15,15,15);
	
	
	//Header
	$this->SetFont('Arial','B',9);
	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],7,$header[$i],1,0,'C',true);
	$this->Ln();
	
	//Data
	$this->SetFont('Arial','',9);
	foreach ($data as $eachResult) 
	{ //width
		$this->Cell(50,10,$eachResult["Cust_Id"],1,0,'C');
		$this->Cell(70,10,$eachResult["FullName"],1,0,'C');
		$this->Cell(40,10,$eachResult["Phone"],1,0,'C');
		$this->Cell(65,10,$eachResult["Adress"],1,0,'C');
		$this->Cell(50,10,$eachResult["Email"],1,0,'C');
		$this->Ln();
		 	 	 	 	
	}
}

//Better table
}



$pdf=new PDF();

	

$header=array('Customer ID','Customer FullName','Phone','Address','Email Address');
//Data loading
//*** Load MySQL Data ***//

//db settings
$db_username = 'root';
$db_password = '';
$db_name = 'gritexpress';
$db_host = 'localhost';
$mysqli = new mysqli($db_host, $db_username, $db_password,$db_name);

$currDate = date('Y-m-d');
$strSQL = "Select* from customer";
$objQuery = mysqli_query($mysqli,$strSQL);
$resultData = array();
for ($i=0;$i<mysqli_num_rows($objQuery);$i++) {
	$result = mysqli_fetch_array($objQuery);
	array_push($resultData,$result);
}
//************************//


//*** Table 1 ***//
$pdf->AddPage('L','A4',0);
	
	$pdf->SetFont('Helvetica','b',14);
	$pdf->Cell(276,5,'GRiT Web Services, Co.',0,0,'L');
	$pdf->Ln();
	
	$pdf->SetFont('Helvetica','b',14);
	$pdf->Cell(276,7,'GRiT Express',0,0,'L');
	$pdf->Ln();

	$pdf->SetFont('Helvetica','b',10);
	$pdf->Cell(276,9,'CUSTOMER REPORT',0,0,'L');
	$pdf->Ln();
	
	$pdf->SetFontSize(7);
	$pdf->Cell(276,11);
	$result=mysqli_query($mysqli,"select date_format(now(), '%W, %M %d, %Y') as date");
	while( $row=mysqli_fetch_array($result) )
	{
	$pdf->Write(5,$row['date']);
	}
	$pdf->Ln();
	
	$pdf->Cell(0);
	$pdf->SetFontSize(10);
	$pdf->Cell(54);
	//$get_user = $_GET['username'];
	//$pdf->Write(5, 'Printed By: '.$get_user.'');
	$pdf->Ln(-1);
	
	//display numbers of reports
	$result=mysqli_query($mysqli,"Select * from customer") or die ("Database query failed: $query" . mysqli_error());
	
	$count = mysqli_num_rows($result);
	$pdf->Cell(0);
	$pdf->Write(5, ' Customer Record: '.$count.'');
	$pdf->Ln();

	$pdf->Ln(5);

$pdf->Ln(0);
$pdf->BasicTable($header,$resultData);
//forme();
//$pdf->Output("$d.pdf","F");
$pdf->Output();

?>