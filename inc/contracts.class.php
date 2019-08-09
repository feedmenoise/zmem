<?php

class Contracts {
	
	function tableHead() {
		$out = "<tr>
		<th>#</th>
		<th>Дата</th>
		<th>Оплата</th>
		<th>Срок</th>
		<th>Общее кол-во</th>
		<th>Введено</th>
		<th>Остаток</th>
		<th><font color=red>Red Line</font></th>
		<th>Район</th>
		<th>Фирма</th>
		<th></th>
		<th></th>
		</tr>";

		return $out;
	}

	function tableHeadButtons() {

		$out = "
		<button type=\"button\" class=\"btn btn-sm btn-outline-secondary edit-contract \" data-toggle=\"modal\" data-target=\".bd-example-modal-lg\" data-id=\"\">
		<span data-feather=\"plus\"></span>
		Договор
		</button>
		<button type=\"button\" class=\"btn btn-sm btn-outline-secondary edit-add-contract \" data-toggle=\"modal\" data-target=\".bd-example-modal-lg\" data-id=\"\">
		<span data-feather=\"plus\"></span>
		Доп. соглашение
		</button>";

		return $out;
	}

	function tableContent() {

		$connect = new mysqli("localhost","root","zhopa13","zmem_db");
		$connect->set_charset("utf8");
		$out = "";
		if (isset($_GET['f'])) {
			if ($_GET['f'] == "active" || $_GET['f'] == "soon" || $_GET['f'] == "failed")
				$query_filter = " AND flag=1 ";
			else if ($_GET['f'] == "inactive")
				$query_filter = " AND flag=0 ";
			else 
				$query_filter = "";
		}
		else 
			$query_filter = "";

		$result = $connect->query("SELECT * FROM COMPANY,CONTRACT WHERE CONTRACT.id_company=COMPANY.id_company $query_filter ORDER BY date DESC");

		while($row= mysqli_fetch_array($result)) {

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

			if ($flag == 0) 
				$redline = "";
			else 
				$redline = $this->redLine($date,$time);


			$current_date = date('Y-m-d');
			$current_date_unix = strtotime($current_date);
			//$month = 2592000;
			$month = 3888000;
			$redline_timestamp = strtotime($redline);



			if ($row['note'] == "true") {
				$additional = "<button type=\"button\" class=\"btn btn-sm btn-outline-secondary\" type=\"button\" onclick=\"showElements('show_".$number."');\">
				<span data-feather=\"arrow-down-circle\"></span>
				</button>";
				$check_add_contract = $connect->query("SELECT * FROM ADD_CONTRACT WHERE id_contract=$id");
				
				$add_contracts = "";

				while ($row_add_contract=mysqli_fetch_array($check_add_contract)){
					if ($flag == 1) {
						$current_rl = $this->redLine($row_add_contract['add_date'], $row_add_contract['add_time']);
						if ($current_rl > $redline) {
							$time = $row_add_contract['add_time'];
							$redline = $current_rl;
							$redline_timestamp = strtotime($redline);
						} 
					}
					//*****//
					if ($_GET['f'] == "soon") {
						if ($redline_timestamp-$current_date_unix < $month && $redline_timestamp-$current_date_unix > 0) {
							$add_contracts .= $this->tableRow_toString($row_add_contract['id_add_contract'],$row_add_contract['add_number'],$row_add_contract['add_date'],"",$row_add_contract['add_time'],"","","","","","","","none",$number);
						}

					} 
					else if ($_GET['f'] == "failed") {
						if ($current_date_unix >= $redline_timestamp)
							$add_contracts .= $this->tableRow_toString($row_add_contract['id_add_contract'],$row_add_contract['add_number'],$row_add_contract['add_date'],"",$row_add_contract['add_time'],"","","","","","","","none",$number);
					}
					else 
						$add_contracts .= $this->tableRow_toString($row_add_contract['id_add_contract'],$row_add_contract['add_number'],$row_add_contract['add_date'],"",$row_add_contract['add_time'],"","","","","","","","none",$number);
				}
				//******//
				if ($_GET['f'] == "soon") {
					if ($redline_timestamp-$current_date_unix < $month && $redline_timestamp-$current_date_unix > 0) {
						$out .= $this->tableRow_toString($id,$number,$row['date'],$cost,$time,$count,$count_in_use,$remains,$redline,$region,$company_name,$additional,"visible","");
						$out .= $add_contracts; 
					}

				} 
				else if ($_GET['f'] == "failed") {
					if ($current_date_unix >= $redline_timestamp) {
						$out .= $this->tableRow_toString($id,$number,$row['date'],$cost,$time,$count,$count_in_use,$remains,$redline,$region,$company_name,$additional,"visible","");
						$out .= $add_contracts; 
					}
				}
				else {
					$out .= $this->tableRow_toString($id,$number,$row['date'],$cost,$time,$count,$count_in_use,$remains,$redline,$region,$company_name,$additional,"visible","");
					$out .= $add_contracts; 
				}
			}	

			else 
				//******//
				if ($_GET['f'] == "soon") {
					if ($redline_timestamp-$current_date_unix < $month && $redline_timestamp-$current_date_unix > 0)
						$out .= $this->tableRow_toString($id,$number,$date,$cost,$time,$count,$count_in_use,$remains,$redline,$region,$company_name,"","visible","");
				} 
				else if ($_GET['f'] == "failed") {
					if ($current_date_unix >= $redline_timestamp)
						$out .= $this->tableRow_toString($id,$number,$date,$cost,$time,$count,$count_in_use,$remains,$redline,$region,$company_name,"","visible","");
				}
				else {
					$out .= $this->tableRow_toString($id,$number,$date,$cost,$time,$count,$count_in_use,$remains,$redline,$region,$company_name,"","visible","");
				}

			}

			return $out;
		}

		function tableRow_toString($id,$number,$date,$cost,$time,$count,$count_in_use,$remains,$redline,$region,$company_name,$additional,$type,$spoiler) {

			if ($type == "none") {
				$out = "<tr class=\"show_".$spoiler." hidcont \">";
				$buttons = "
				<button type=\"button\" class=\"btn btn-sm btn-outline-secondary edit-add-contract \" data-toggle=\"modal\" data-target=\".bd-example-modal-lg\" data-id=\"".$id."\">
				<span data-feather=\"edit\" ></span>
				</button>

				";
			}
			else {
				$out = "<tr>";
				$buttons = "
				<button type=\"button\" class=\"btn btn-sm btn-outline-secondary\" onclick=\"show_contract(".$id.")\" \"><span data-feather=\"eye\"></span></button>
				<button type=\"button\" class=\"btn btn-sm btn-outline-secondary edit-contract \" data-toggle=\"modal\" data-target=\".bd-example-modal-lg\" data-id=\"".$id."\">
				<span data-feather=\"edit\" ></span>
				</button>";
			}

			$out .= "<td>".$number."</td>";
			$out .= "<td>".$date."</td>";
			$out .= "<td>".$cost."</td>";
			$out .= "<td>".$time." мес </td>";
			$out .= "<td>".$count."</td>";
			$out .= "<td>".$count_in_use."</td>";
		$out .= "<td>".$remains."</td>"; #остаток
		$out .= "<td><font color=\"red\">".$redline."</font></td>"; #red line
		$out .= "<td>".$region."</td>";
		$out .= "<td>".$company_name."</td>";
		$out .= "<td>".$additional."</td>";
		$out .= "<td>".$buttons."</td>";
		$out .= "</tr>";

		return $out;
	}

	function redLine($date, $time) {

		$dateAt = strtotime('+'.$time.' MONTH', strtotime($date));
		$redline = date('Y-m-d', $dateAt);

		return $redline;
	}

	//function modal() {
	//	//<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Большое модальное окно</button>
	//	$out = '
	//			<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  	//				<div class="modal-dialog modal-lg">
    //					<div class="modal-content">
    //  						%CONTENT HERE%
    //					</div>
  	//				</div>
	//			</div>';
	//	return $out;
	//}

	function showContent() {

		$head = $this->tableHead();
		$buttons = $this->tableHeadButtons();
		$content = $this->tableContent();
		//$modal = $this->modal();
		$n = new parser("table.tpl");
		$n->get_tpl();
		$n->set_tpl("%TITLE%","Договора");
		$n->set_tpl("%TABLE_HEAD%", $head);
		$n->set_tpl("%BUTTONS%", $buttons);
		$n->set_tpl("%TABLE_BODY%", $content);
		//$n->set_tpl("%MODAL%", $modal);

		return $n->tpl_parse();
	}

}
?>
