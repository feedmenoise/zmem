<?php
require('generate.class.php');

if (isset($_POST['method'])) {
	if ($_POST['method'] == "show_contract_modal") 
		echo editContractForm($_POST['id']);

	else if ($_POST['method'] == "show_add_contract_modal") 
		echo editAddContractForm($_POST['id']);

	else if ($_POST['method'] == "save_contract")
		echo new_contract($_POST['contract']);

	else if ($_POST['method'] == "delete_contract")
		echo deleteContract($_POST['id']);

	else if ($_POST['method'] == "save_add_contract")
		echo newAddContract($_POST['add_contract']);

	else if ($_POST['method'] == "delete_add_contract")
		echo deleteAddContract($_POST['id']);

	else if ($_POST['method'] == "show_recipients_modal")
		echo editRecipientForm($_POST['id']);

	else if ($_POST['method'] == "save_recipient")
		echo saveRecipient($_POST['recipient']);

	else if ($_POST['method'] == "delete_recipient")
		echo deleteRecipient($_POST['id']);

	else if ($_POST['method'] == "show_company_modal")
		echo editCompanyForm($_POST['id']);

	else if ($_POST['method'] == "save_company")
		echo saveCompany($_POST['company']);

	else if ($_POST['method'] == "delete_company")
		echo deleteCompany($_POST['id']);

	else if ($_POST['method'] == "show_company_blank") {
		$blank = new Generate();
		echo $blank->createBlank($_POST['id']);
	}
	else if ($_POST['method'] == "show_contract") {
		$blank = new Generate();
		echo $blank->createContract($_POST['id']);
	}
	else if ($_POST['method'] == "show_adresat_modal")
		echo editAdresatForm($_POST['id']);

	else if ($_POST['method'] == "save_adresat")
		echo saveAdresat($_POST['adresat']);

	else if ($_POST['method'] == "delete_adresat")
		echo deleteAdresat($_POST['id']);
}
else {
	echo "что-то не работает";
}


function editContractForm($id) {

	if ($id != null) {

		$connect = new mysqli("localhost","root","zhopa13","zmem_db");
		$connect->set_charset("utf8");
		$result = $connect->query("SELECT * FROM CONTRACT WHERE id_contract=$id");

		$row = mysqli_fetch_array($result);

		$number = $row['number'];
		$date = $row['date'];
		$cost = $row['cost'];
		$time = $row['time'];
		$count = $row['count'];
		$count_in_use = $row['count_in_use'];
		$region = $row['region'];
		$company = $row['id_company'];
		$flag = $row['flag'];
		//var_dump($row);

		$title = 'Редактировать договор #'.$number;
	}

	else {
		$number = "";
		$date = "";
		$cost = "";
		$time = "";
		$count = "";
		$count_in_use = "";
		$region = "";
		$company = "";
		$flag = "";

		$title = 'Добавить новый договор';
	}

	$out = '
	<div class="modal-header alert-dark">
	<h5 class="modal-title">'.$title.'</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	<span aria-hidden="true">&times;</span>
	</button>
	</div>
	<div class="modal-body">
	<form method="post" action="">
	<div class="form-row">
	<div class="col-md-3 mb-4">
	<label>Номер договора</label>
	<input class="form-control" maxlength="50" size="15" id="edit_number" value="'.$number.'">
	</div>
	<div class="col-md-3 mb-4">
	<label>Дата составления</label>
	<input class="form-control" type="date" name="calendar" id="edit_date" value="'.$date.'">
	</div>
	<div class="col-md-3 mb-4">
	<label>Стоимость </label>
	<input class="form-control" maxlength="50" size="15" id="edit_cost" value="'.$cost.'"></p>
	</div>
	<div class="col-md-4 mb-4">
	<label>Срок ввода в эксплутацию (месяцев) </label>
	<input class="form-control" maxlength="50" size="15" id="edit_time" value="'.$time.'"></p>
	</div>
	<div class="col-md-4 mb-3">
	<label>Количество опор </label>
	<input class="form-control" maxlength="50" size="15" id="edit_count" value="'.$count.'"></p>
	</div>
	<div class="col-md-4 mb-3">
	<label>Введено</label>
	<input class="form-control" maxlength="50" size="15" id="edit_count_in_use" value="'.$count_in_use.'"></p>
	</div>
	<div class="col-md-4 mb-3">
	<label>Район или населенный пункт </label>
	<input class="form-control" maxlength="50" size="40" id="edit_region" value="'.$region.'"></p>
	</div>
	<div class="col-md-4 mb-3">
	<label>Фирма </label>
	'.companyList($company).'</p>
	</div>    	
	</div>
	<label>Включить оповещение </label>'.(($flag == 1) ? '<input class="form-check" type="checkbox" id="edit_flag" value="1" checked>' : '<input class="form-check" type="checkbox" id="edit_flag" value="1">').'
	</form>
	</div>
	<div class="modal-footer">
	<button type="button" class="btn btn-sm btn btn-outline-success edit-contract-save" type="button" data-dismiss="modal" onclick="contract_save('.$id.')"><span data-feather="check-circle">Сохранить</span></button>'.
	(($id != "") ? '<button type="button" class="btn btn-sm btn-outline-danger" type="button" id="contract_delete" onclick="contract_delete('.$id.')">Удалить</button>' : "").'
	<button type="button" class="btn btn-sm btn-outline-secondary" type="button" data-dismiss="modal">Закрыть</button>
	</div>';

	return $out;
}

function editAddContractForm($id){

	if ($id != null) {
		$connect = new mysqli("localhost","root","zhopa13","zmem_db");
		$connect->set_charset("utf8");
		$result = $connect->query("SELECT * FROM ADD_CONTRACT WHERE id_add_contract=$id");

		$row = mysqli_fetch_array($result);

		$add_number = $row['add_number'];
		$add_date = $row['add_date'];
		$add_time = $row['add_time'];
		$id_contract = $row['id_contract'];
		$title = "Редактировать доп. соглашение #".$add_number;
	}
	else {
		$add_number = "";
		$add_date = "";
		$add_time = "";
		$id_contract = "";

		$title = "Добавить новое доп. соглашение";
	}
	$out = '
	<div class="modal-header alert-dark">
	<h5 class="modal-title">'.$title.'</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	<span aria-hidden="true">&times;</span>
	</button>
	</div>
	<div class="modal-body">
	<form method="post" action="">
	<div class="form-row">
	<div class="col-md-3 mb-4">
	<label>Номер доп. соглашения</label>
	<input class="form-control" maxlength="50" size="15" id="add_number" value="'.$add_number.'">
	</div>
	<div class="col-md-3 mb-4">
	<label>Дата составления</label>
	<input class="form-control" type="date" name="calendar" id="add_date" value="'.$add_date.'">
	</div>
	<div class="col-md-4 mb-4">
	<label>Срок ввода в эксплутацию (месяцев) </label>
	<input class="form-control" maxlength="50" size="15" id="add_time" value="'.$add_time.'"></p>
	</div>
	<div class="col-md-6 mb-3">
	<label>Фирма </label>
	'.contractList($id_contract).'</p>
	</div>
	</div>
	</form>
	</div>
	<div class="modal-footer">
	<button type="button" class="btn btn-sm btn btn-outline-success" type="button" onclick="add_contract_save('.$id.')"><span data-feather="check-circle">Сохранить</span></button>'.
	(($id != "") ? '<button type="button" class="btn btn-sm btn-outline-danger" type="button" onclick="add_contract_delete('.$id.')"><span data-feather="trash-2">Удалить</span></button>' : "").'
	<button type="button" class="btn btn-sm btn-outline-secondary" type="button" data-dismiss="modal"><span data-feather="x"></span>Закрыть</button>
	</div>';

	return $out;
}

function contractList($checked) {
	$connect = new mysqli("localhost","root","zhopa13","zmem_db");
	$connect->set_charset("utf8");
	$result = $connect->query("SELECT id_contract,number,region FROM CONTRACT");

	$out = '<select class="form-control" name="select" id="id_contract" size="1">';
	while($row= mysqli_fetch_array($result)) {
		if ($row['id_contract'] == $checked) 
			$out .= "<option selected value=\"".$row['id_contract']."\">".$row['number']."  ".$row['region']."</option>";	
		else
			$out .= "<option value=\"".$row['id_contract']."\">".$row['number']." - ".$row['region']."</option>";	
	}
	$out .= '</select>';
	return $out;
}

function companyList($checked) {

	$connect = new mysqli("localhost","root","zhopa13","zmem_db");
	$connect->set_charset("utf8");
	$result = $connect->query("SELECT id_company,company_name FROM COMPANY");

	$out = '<select class="form-control" name="select" id="edit_company" size="1">';
	while($row= mysqli_fetch_array($result)) {
		if ($row['id_company'] == $checked) 
			$out .= "<option selected value=\"".$row['id_company']."\">".$row['company_name']."</option>";	
		else
			$out .= "<option value=\"".$row['id_company']."\">".$row['company_name']."</option>";
	}
	$out .= '</select>';
	return $out;
}

function new_contract($contract) {

	$connect = new mysqli("localhost","root","zhopa13","zmem_db");
	$connect->set_charset("utf8");

	$contract = json_decode($contract);
	$id = $contract->id;
	if ($id == "new") 
		$stmt = mysqli_prepare($connect, "INSERT INTO `CONTRACT`(`number`, `date`, `cost`, `time`, `count`, `count_in_use`, `region`, `id_company`, `flag`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
	else 
		$stmt = mysqli_prepare($connect, "UPDATE `CONTRACT` SET `number`=?,`date`=?,`cost`=?,`time`=?,`count`=?,`count_in_use`=?,`region`=?,`id_company`=?,`flag`=? WHERE id_contract=$id");

	mysqli_stmt_bind_param($stmt, "ssssssssi", $contract->number, $contract->date, $contract->cost, $contract->time, $contract->count, $contract->count_in_use, $contract->region, $contract->id_company, $flag);	
	
	if ($contract->flag == null) 
		$flag = 0;
	else
		$flag = 1;
	
	mysqli_stmt_execute($stmt);
	echo "Row inserted: ". mysqli_stmt_affected_rows($stmt);

	mysqli_stmt_close($stmt);
	mysqli_close($connect);

}

function deleteContract($id) {

	$connect = new mysqli("localhost","root","zhopa13","zmem_db");
	$connect->set_charset("utf8");

	$result = $connect->query("SELECT note FROM `CONTRACT` WHERE id_contract=$id");
	$row= mysqli_fetch_array($result);

	if ($row['note'] == "true") 
		echo "У договора есть доп. соглашения, не возможно удалить";
	else {
		$connect->query("DELETE FROM `CONTRACT` WHERE id_contract=$id");

	}

	mysqli_close($connect);
}

function newAddContract($add_contract) {

	$connect = new mysqli("localhost","root","zhopa13","zmem_db");
	$connect->set_charset("utf8");

	$add_contract = json_decode($add_contract);
	$id_contract = $add_contract->id_contract;
	$id = $add_contract->id;

	$result = $connect->query("SELECT `id_contract`,`note` FROM `CONTRACT` WHERE `id_contract`=$id_contract");
	$contract = mysqli_fetch_array($result);

	if ($id == "new") {
		$stmt = mysqli_prepare($connect, "INSERT INTO `ADD_CONTRACT`(`add_number`, `add_date`, `add_time`, `id_contract`) VALUES (?, ?, ?, ?)");
		if ($contract['note'] != "true") 
			$connect->query("UPDATE `CONTRACT` SET `note`=\"true\" WHERE id_contract=$id_contract");	
	}		
	else 
		$stmt = mysqli_prepare($connect, "UPDATE `ADD_CONTRACT` SET `add_number`=?,`add_date`=?, `add_time`=?,`id_contract`=? WHERE id_add_contract=$id");

	mysqli_stmt_bind_param($stmt, "ssss", $add_contract->add_number, $add_contract->add_date, $add_contract->add_time, $add_contract->id_contract);	
	
	mysqli_stmt_execute($stmt);
	//echo "Row inserted: ". mysqli_stmt_affected_rows($stmt);

	mysqli_stmt_close($stmt);
	mysqli_close($connect);
}

function deleteAddContract($id) {

	$connect = new mysqli("localhost","root","zhopa13","zmem_db");
	$connect->set_charset("utf8");

	$contract = $connect->query("SELECT id_contract FROM `ADD_CONTRACT` WHERE id_add_contract=$id");
	$contract = mysqli_fetch_array($contract);
	$contract = $contract['id_contract'];
	
	$contract_note = $connect->query("SELECT note FROM `CONTRACT` WHERE id_contract=$contract");
	$contract_note = mysqli_fetch_array($contract_note);
	$contract_note = $contract_note['note'];
	
	$connect->query("DELETE FROM `ADD_CONTRACT` WHERE id_add_contract=$id");

	$another_add_contracts = $connect->query("SELECT id_add_contract FROM `ADD_CONTRACT` WHERE id_contract=$contract");
	
	if ($contract_note == "true") {
		if (mysqli_fetch_array($another_add_contracts) == null){
			$stmt = mysqli_prepare($connect, "UPDATE `CONTRACT` SET `note`=? WHERE `id_contract`=?");
			$note = "false";
			mysqli_stmt_bind_param($stmt, "si", $note, $contract);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
		}
	}

	mysqli_close($connect);
}

function editRecipientForm($id) {
	if ($id != null) {

		$connect = new mysqli("localhost","root","zhopa13","zmem_db");
		$connect->set_charset("utf8");
		$result = $connect->query("SELECT * FROM RECIPIENTS WHERE id_recipient=$id");

		$row = mysqli_fetch_array($result);

		$id_recipient = $row['id_recipient'];
		$name = $row['name'];
		$email = $row['email'];

		$title = 'Редактировать получателя #'.$id_recipient;
	}

	else {
		$id_recipient = "";
		$name = "";
		$email = "";
		$title = 'Добавить нового получателя';
	}

	$out = '
	<div class="modal-header alert-dark">
	<h5 class="modal-title">'.$title.'</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	<span aria-hidden="true">&times;</span>
	</button>
	</div>
	<div class="modal-body">
	<form method="post" action="">
	<div class="form-row">
	<div class="col-md-4 mb-4">
	<label>ФИО</label>
	<input class="form-control" maxlength="50" size="15" id="name" value="'.$name.'">
	</div>
	<div class="col-md-4 mb-4">
	<label>email</label>
	<input class="form-control" placeholder="name@example.com" type="email"  id="email" value="'.$email.'">
	</div>
	</form>
	</div>
	<div class="modal-footer">
	<button type="button" class="btn btn-sm btn btn-outline-success edit-contract-save" type="button" data-dismiss="modal" onclick="recipient_save('.$id_recipient.')"><span data-feather="check-circle">Сохранить</span></button>'.
	(($id_recipient != "") ? '<button type="button" class="btn btn-sm btn-outline-danger" type="button" id="contract_delete" onclick="recipient_delete('.$id_recipient.')">Удалить</button>' : "").'
	<button type="button" class="btn btn-sm btn-outline-secondary" type="button" data-dismiss="modal">Закрыть</button>
	</div>';

	return $out;

}

function editCompanyForm($id) {
	if ($id != null) {

		$connect = new mysqli("localhost","root","zhopa13","zmem_db");
		$connect->set_charset("utf8");
		$result = $connect->query("SELECT * FROM COMPANY WHERE id_company=$id");

		$row = mysqli_fetch_array($result);

		$id_company = $row['id_company'];
		$company_name = $row['company_name'];
		$company_title = $row['title'];
		$address = $row['address'];
		$requisites = $row['requisites'];
		$image = $row['image'];
		$director = $row['director'];
		$title = 'Редактировать #'.$company_name;
	}

	else {
		$id_company = "";
		$company_name = "";
		$company_title = "";
		$address = "";
		$requisites = "";
		$image = "";
		$director = "";
		$title = 'Добавить новую фирму';
	}

	$out = '
	<div class="modal-header alert-dark">
	<h5 class="modal-title">'.$title.'</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	<span aria-hidden="true">&times;</span>
	</button>
	</div>
	<div class="modal-body">
	<form method="post" action="">
	<div class="form-row">
	<div class="col-md-6 mb-4">
	<label>Фирма</label>
	<input class="form-control" maxlength="50" size="15" id="name" value="'.$company_name.'">
	</div>
	<div class="col-md-6 mb-4">
	<label>Директор</label>
	<input class="form-control" maxlength="50" size="15"  id="director" value="'.$director.'">
	</div>
	<div class="col-md-4 mb-4">
	<label>Логотип (если есть)</label> 
	<input class="form-control" maxlength="50" size="15"  id="image" value="'.$image.'">
	</div>
	</div>
	<div class="form-row">
	<div class="col-md-10 mb-8">
	<label>Титул</label>
	<textarea class="form-control" type="textarea" id="company_title" rows="6">'.$company_title.'</textarea>
	</div>
	</div>
	<div class="form-row">
	<div class="col-md-10 mb-8">
	<label>Юридический адрес</label>
	<textarea class="form-control" type="textarea" id="address" rows="6">'.$address.'</textarea>
	</div>
	</div>
	<div class="form-row">
	<div class="col-md-10 mb-8">
	<label>Реквизиты</label>
	<textarea class="form-control" type="textarea" id="requisites" rows="6">'.$requisites.'</textarea>

	</div>


	</form>
	</div>
	<div class="modal-footer">
	<button type="button" class="btn btn-sm btn btn-outline-success edit-company-save" type="button" data-dismiss="modal" onclick="company_save('.$id_company.')"><span data-feather="check-circle">Сохранить</span></button>'.
	(($id_company != "") ? '<button type="button" class="btn btn-sm btn-outline-danger" type="button" id="contract_delete" onclick="company_delete('.$id_company.')">Удалить</button>' : "").'
	<button type="button" class="btn btn-sm btn-outline-secondary" type="button" data-dismiss="modal">Закрыть</button>
	</div>';

	return $out;
}

function saveRecipient($recipient){
	
	$connect = new mysqli("localhost","root","zhopa13","zmem_db");
	$connect->set_charset("utf8");

	$recipient = json_decode($recipient);
	$id = $recipient->id;
	if ($id == "new") 
		$stmt = mysqli_prepare($connect, "INSERT INTO `RECIPIENTS`(`name`, `email`) VALUES (?, ?)");
	else 
		$stmt = mysqli_prepare($connect, "UPDATE `RECIPIENTS` SET `name`=?,`email`=? WHERE id_recipient=$id");

	mysqli_stmt_bind_param($stmt, "ss", $recipient->name, $recipient->email);	
	
	mysqli_stmt_execute($stmt);
	echo "Row inserted: ". mysqli_stmt_affected_rows($stmt);

	mysqli_stmt_close($stmt);
	mysqli_close($connect);

}

function deleteRecipient($id) {

	$connect = new mysqli("localhost","root","zhopa13","zmem_db");
	$connect->set_charset("utf8");

	$connect->query("DELETE FROM `RECIPIENTS` WHERE id_recipient=$id");

	mysqli_close($connect);
}

function saveCompany($company) {

	$connect = new mysqli("localhost","root","zhopa13","zmem_db");
	$connect->set_charset("utf8");

	$company = json_decode($company);
	$id = $company->id;
	if ($id == "new") 
		$stmt = mysqli_prepare($connect, "INSERT INTO `COMPANY`(`company_name`, `title`, `address`, `requisites`, `image`, `director`) VALUES (?, ?, ?, ?, ?, ?)");
	else 
		$stmt = mysqli_prepare($connect, "UPDATE `COMPANY` SET `company_name`=?,`title`=?, `address`=?, `requisites`=?, `image`=?, `director`=? WHERE id_company=$id");

	mysqli_stmt_bind_param($stmt, "ssssss", $company->name, $company->company_title, $company->address, $company->requisites, $company->image, $company->director);	
	
	mysqli_stmt_execute($stmt);
	echo "Row inserted: ". mysqli_stmt_affected_rows($stmt);

	mysqli_stmt_close($stmt);
	mysqli_close($connect);
}

function deleteCompany($id) {

	$connect = new mysqli("localhost","root","zhopa13","zmem_db");
	$connect->set_charset("utf8");

	$check_contracts = $connect->query("SELECT id_contract FROM CONTRACT WHERE id_company=$id");

	if (mysqli_fetch_array($check_contracts) != null)
		return "Нельзя удалить фирму у которой есть договор(а).";
	else
		$connect->query("DELETE FROM `COMPANY` WHERE id_company=$id");

	mysqli_close($connect);
}

function showCompanyBlank($id) {
	$blank = new Generate();
	$blank->createBlank($id);
}

function editAdresatForm($id) {
	if ($id != null) {

		$connect = new mysqli("localhost","root","zhopa13","zmem_db");
		$connect->set_charset("utf8");
		$result = $connect->query("SELECT * FROM CONTRACT_RECIPIENT WHERE id_con_rec=$id");

		$row = mysqli_fetch_array($result);

		$id_con_rec = $row['id_con_rec'];
		$post = $row['post'];
		$name = $row['name'];
		$flag = $row['flag'];

		$title = 'Редактировать адресата #'.$id_recipient;
	}

	else {
		$id_con_rec = "";
		$post = "";
		$name = "";
		$flag = "0";
		$title = 'Добавить нового адресата';
	}

	$out = '
	<div class="modal-header alert-dark">
	<h5 class="modal-title">'.$title.'</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	<span aria-hidden="true">&times;</span>
	</button>
	</div>
	<div class="modal-body">
	<form method="post" action="">
	<div class="form-row">
	<div class="col-md-4 mb-4">
	<label>ФИО (кому?)</label>
	<input class="form-control" maxlength="50" size="15" id="name" value="'.$name.'">
	</div>
	<div class="col-md-4 mb-4">
	<label>Активен</label>'.
	(($flag == 1) ? '<input class="form-check" type="checkbox" id="edit_flag" value="1" checked>' : '<input class="form-check" type="checkbox" id="edit_flag" value="1">').'
	</div>
	</div>
	<div class="form-row">
	<div class="col-md-10 mb-8">
	<label>Должность</label>
	<textarea class="form-control" type="textarea" id="post" rows="6">'.$post.'</textarea>
	</div>
	</form>
	</div>
	<div class="modal-footer">
	<button type="button" class="btn btn-sm btn btn-outline-success edit-contract-save" type="button" data-dismiss="modal" onclick="adresat_save('.$id_con_rec.')"><span data-feather="check-circle">Сохранить</span></button>'.
	(($id_con_rec != "") ? '<button type="button" class="btn btn-sm btn-outline-danger" type="button" id="contract_delete" onclick="adresat_delete('.$id_con_rec.')">Удалить</button>' : "").'
	<button type="button" class="btn btn-sm btn-outline-secondary" type="button" data-dismiss="modal">Закрыть</button>
	</div>';

	return $out;

}
function saveAdresat($adresat){
	
	$connect = new mysqli("localhost","root","zhopa13","zmem_db");
	$connect->set_charset("utf8");

	$adresat = json_decode($adresat);
	if ($adresat->flag == null) 
		$flag = 0;
	else
		$flag = 1;
	$id = $adresat->id;
	if ($id == "new") 
		$stmt = mysqli_prepare($connect, "INSERT INTO `CONTRACT_RECIPIENT`(`post`, `name`, `flag`) VALUES (?, ?, ?)");
	else 
		$stmt = mysqli_prepare($connect, "UPDATE `CONTRACT_RECIPIENT` SET `post`=?, `name`=?,`flag`=? WHERE id_con_rec=$id");

	mysqli_stmt_bind_param($stmt, "ssi", $adresat->post, $adresat->name, $flag);	
	
	mysqli_stmt_execute($stmt);
	echo "Row inserted: ". mysqli_stmt_affected_rows($stmt);

	mysqli_stmt_close($stmt);
	mysqli_close($connect);

}

function deleteAdresat($id) {

	$connect = new mysqli("localhost","root","zhopa13","zmem_db");
	$connect->set_charset("utf8");

	$connect->query("DELETE FROM `CONTRACT_RECIPIENT` WHERE id_con_rec=$id");

	mysqli_close($connect);
}

?>

