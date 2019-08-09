#!/usr/bin/php

<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'generate.class.php';

$current_date = strtotime(date('Y-m-d'));
//$month = 2592000;
$month = 3888000;
$connect = new mysqli("localhost","root","zhopa13","zmem_db");
$connect->set_charset("utf8");

$result = $connect->query("SELECT * FROM COMPANY,CONTRACT WHERE CONTRACT.id_company=COMPANY.id_company AND CONTRACT.flag=1 ORDER BY date DESC ");

while($row=mysqli_fetch_array($result)) {

	$id = $row['id_contract'];
	$number = $row['number'];
	$date = $row['date'];
	$cost = $row['cost'];
	$time = $row['time'];
	$count = $row['count'];
	$count_in_use = $row['count_in_use'];
	$region = $row['region'];
	$company_name = $row['company_name'];
	$flag = $row['flag'];
	$remains = $row['count'] - $row['count_in_use'];
	$redline = strtotime('+'.$time.' MONTH', strtotime($date));
	$note = $row['note'];
	$rest = $count - $count_in_use;
	if ($note == "true") {
		$add_contracts = $connect->query("SELECT add_time,add_date,id_contract FROM ADD_CONTRACT WHERE id_contract=$id ORDER BY add_date");
		while ($add_contract = mysqli_fetch_array($add_contracts)) {

			//если не важна дата составление доп. соглашения
			//$redline = strtotime('+'.$add_contract['add_time'].' MONTH', $redline);

			$redline_add_contr = strtotime('+' . $add_contract['add_time'] . ' MONTH', strtotime($add_contract['add_date']));
			if ($redline_add_contr > $redline)
				$redline = $redline_add_contr;
		}
	}

	//скоро истекает срок действия договора
	if($redline-$current_date < $month && $redline-$current_date > 0) {
		$title = "СКОРО ИСТЕКАЕТ";
		$result_message = '<b>Скоро закончится срок договора!</b> <br> 
		Договор: ' . $number .
		'<br> Дата составления: ' . $date . 
		'<br> Стоимость: ' . $cost .
		'<br> Длительность: ' . $time .
		'<br> Общее кол-во опор или метража: ' . $count .
		'<br> Введено в эксплуатацаию: ' . $count_in_use .
		'<br> Остаток: ' . $rest .
		'<br> Red Line: ' . date('Y-m-d', $redline) .
		'<br> Нас. пункт или район города: ' . $region .
		'<br> Фирма на которую оформлен договор: ' . $company_name . '<br><br>';
		
		$subject = "Gigabit: Отчет по договорам ЗМЕМ. Договор: " . $number . " " . $region . " - Скоро истекает!";
		$blank = new Generate();
		$blank->createContract($id);
		
		SendMail($connect,$result_message, $subject);
	}

	//истек срок действия договора
	if ($current_date >= $redline) {
		
		$result_message = '<b>Закончился срок договора!</b> <br> 
		Договор: '.$number.
		'<br> Дата составления: '.$date.
		'<br> Стоимость: '.$cost.
		'<br> Длительность: '.$time.
		'<br> Общее кол-во опор или метража: '.$count.
		'<br> Введено в эксплуатацаию: '.$count_in_use.
		'<br> Остаток: '.$rest.
		'<br> Red Line: '.date('Y-m-d', $redline).
		'<br> Нас. пункт или район города: '.$region.
		'<br> Фирма на которую оформлен договор: '.$company_name.'<br><br>';
		
		$subject = "Gigabit: Отчет по договорам ЗМЕМ. Договор: " . $number . " " . $region . " - Истек!";
		
		$blank = new Generate();
		$blank->createContract($id);
		SendMail($connect, $result_message, $subject);
		
	}

	//echo "CONTRACT: " . $number . " REDLINE: " . date('Y-m-d', $redline) . " - " . $title . PHP_EOL;
}


function SendMail($connect, $message, $subject) {

	$query = "SELECT * FROM RECIPIENTS";
	$result = $connect->query($query);

	while($row= mysqli_fetch_array($result)) {

		$mail = new PHPMailer();

		$mail->IsSMTP();
		$mail->CharSet = 'UTF-8';

		$mail->Host       = "mail.gigabit.zp.ua"; 
		$mail->SMTPDebug  = 0;                    
		$mail->SMTPAuth   = false;                 
		$mail->Port       = 25;                    

		$mail->setFrom('zmem@gigabit.zp.ua', 'Gigabit Contract Report');
		$mail->addAddress($row["email"]);     
		$mail->addReplyTo('feedme@gigabit.zp.ua', 'Information');
		$mail->addAttachment('../pdf/temp_contract.pdf');

		$mail->isHTML(true);                                  
		$mail->Subject = $subject;
		$mail->Body    = $message;
		$mail->AltBody = $message;

		$mail->send();
	}
}

?>
