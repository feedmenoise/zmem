<?php
require('pdf.class.php');

class Generate {

	function createBlank($id) {
		$pdf = new PDF();

		$connect = new mysqli("localhost","root","zhopa13","zmem_db");
		$connect->set_charset("utf8");

		$company = $connect->query("SELECT * FROM COMPANY WHERE id_company=$id");
		$company = mysqli_fetch_array($company);

		$title = $company['title'];
		$address = $company['address'];
		$requisites = $company['requisites'];
		$image = $company['image'];
		$director = $company['director'];

		$pdf->AliasNbPages();
		$pdf->AddFont('TIMES','','TIMES.php');
		$pdf->AddPage();
		$pdf->SetLeftMargin(25);
		$pdf->Header($title,$address,$requisites,$image);
		mysqli_close($connect);
		$pdf->Output("../pdf/temp.pdf","F");

		$url = "http://".$_SERVER["HTTP_HOST"]."/zmem/pdf/temp.pdf";

		echo $url;

	}

	function createContract($id) {
		$pdf = new PDF();

		$connect = new mysqli("localhost","root","zhopa13","zmem_db");
		$connect->set_charset("utf8");

		$contract = $connect->query("SELECT * FROM COMPANY,CONTRACT WHERE CONTRACT.id_company=COMPANY.id_company AND CONTRACT.id_contract=$id");
		$contract = mysqli_fetch_array($contract);
		$adresat = $connect->query("SELECT * FROM CONTRACT_RECIPIENT WHERE flag=1");
		$adresat = mysqli_fetch_array($adresat);

		$name = $adresat['name'];
		$post = $adresat['post'];

		$adresat = $post . $name;

		$title = $contract['title'];
		$address = $contract['address'];
		$requisites = $contract['requisites'];
		$image = $contract['image'];

		$number = $contract['number'];
		$note = $contract['note'];
		$date = date('d.m.Y', strtotime($contract['date']));
		$time = $contract['time'];
		$date_unix = strtotime($date);
		$date_unix = strtotime('+'.$time.' MONTH', $date_unix);
		$redline = date('d.m.Y', $date_unix);

		$director = $contract['director'];

		if ($note == "true") {
			$add_contracts = $connect->query("SELECT add_time FROM ADD_CONTRACT WHERE id_contract=$id");
			while ($row = mysqli_fetch_array($add_contracts)) {
				$redline_unix = strtotime($redline);
				$add_time = $row['add_time'];
				$redline_unix = strtotime('+'.$add_time.' MONTH', $redline_unix);
				$redline =  date('d.m.Y', $redline_unix);
			}
		}
		$redline = strtotime('+ 1 YEAR', strtotime($redline));
		$redline = date('d.m.Y', $redline);
		//$content = iconv('utf-8', 'windows-1251', $content);


		$pdf->AliasNbPages();
		$pdf->AddFont('TIMES','','TIMES.php');
		$pdf->AddPage();
		$pdf->SetLeftMargin(25);
		$pdf->Header($title,$address,$requisites,$image);
		$pdf->Content($number,$date,$redline,$adresat);
		$pdf->Footer($director);
		mysqli_close($connect);
		$pdf->Output("/var/www/html/zmem/pdf/temp_contract.pdf","F");

		$url = "http://".$_SERVER["HTTP_HOST"]."/zmem/pdf/temp_contract.pdf";

		echo $url;
	}
}

?>
