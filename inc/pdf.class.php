<?php 
require('fpdf.php');


class PDF extends FPDF {
	function Header($title,$address,$requisites,$image) {

		$title = iconv('utf-8', 'windows-1251', $title);
		$address = iconv('utf-8', 'windows-1251', $address);
		//$date = iconv('utf-8', 'windows-1251', $date);
		$requisites = iconv('utf-8', 'windows-1251', $requisites);
		$address_rows = substr_count( $address, "\n" );
		$requisites_rows = substr_count( $requisites, "\n" );
		$title_rows = substr_count($title,"\n");
		$row = $requisites_rows;

		$this->SetFont('TIMES','',16);
		$this->MultiCell(0,5,$title,0,'C');
		$this->Ln(1);
		$this->Line(25, 20, 210-10, 20);
		$this->SetFont('TIMES','',10);
		$this->MultiCell(0,5,$address,0,'C');
		
		if ($address_rows < 2)
			$this->Ln(10);
		else 
			$this->Ln(5);

		if ($requisites_rows < 3) 
			$this->Ln(10);

		$this->MultiCell(0,5,$requisites,0,'J');
		$this->Line(25, 65, 210-10, 65);
		if ($image != null) {
			$this->Image('/var/www/html/zmem/img/'.$image,160,25,40,0,'JPG');
		}
		$this->Ln(15);
	}

	function Footer($director) {
		$this->SetFont('TIMES','',14);
		$this->SetY(-45);
		$this->Cell(0,5,iconv("utf-8", "windows-1251", "З повагою,"),0,0,"L");
		$this->Ln(10);
		$this->Cell(45,5,iconv("utf-8", "windows-1251", "Директор"),0,0,"L");
		$this->Cell(80,5,iconv("utf-8", "windows-1251", " __________________"),0,0,"C");
		$this->Cell(0,5,iconv("utf-8", "windows-1251", $director),0,0,"L");
	}

	function Content($number,$date,$redline,$adresat) {
 
		$this->SetFont('TIMES','',12);
		$this->Cell(0,5,iconv("utf-8", "windows-1251", "вих. _____________ вiд _____________ "),0,0,"L");
		$this->Ln(25);
		$this->SetFont('TIMES','',14);
		$this->Multicell(0,5,iconv("utf-8", "windows-1251", $adresat),0,"R");
		$this->Ln(45);
		$this->Multicell(0,5,iconv("utf-8", "windows-1251", "			У зв’язку із складною соціально-економічною ситуацією в Україні, та загальною напруженою обстановкою, наша компанія не встигла, згідно умов договору № $number від $date р. ввести лінії ОВЛ у необхідному обсязі.\n\n 			Враховуючи вищевказані обставини прошу погодити термін введення ліній ОВЛ до $redline р."),0,"L");
	
	}
}
?>
