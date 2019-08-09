<?php

class Companies {
	
	function tableHead() {
		$out = "<tr>
    <th>#</th>
    <th>Фирма</th>
    <th>Директор</th>
    <th></th>
    </tr>";
    return $out;
  }

  function tableHeadButtons() {
    $out = "
    <button type=\"button\" class=\"btn btn-sm btn-outline-secondary edit-company\" data-toggle=\"modal\" data-target=\".bd-example-modal-lg\" data-id=\"\">
    <span data-feather=\"plus\"></span>
    Добавить
    </button>";

    return $out;
  }

  function tableContent() {
    $connect = new mysqli("localhost","root","zhopa13","zmem_db");
    $connect->set_charset("utf8");
    $result = $connect->query("SELECT * FROM COMPANY");
    while($row= mysqli_fetch_array($result)) { 
      $out .= "<tr>";
      $out .= "<td>" . $row['id_company'] . "</td>";
      $out .= "<td>" . $row['company_name'] . "</td>";
      $out .= "<td>" . $row['director'] . "</td>";
      $out .= "<td>
      <button type=\"button\" class=\"btn btn-sm btn-outline-secondary\" onclick=\"show_company_blank(".$row['id_company'].")\" \"><span data-feather=\"eye\"></span></button>
      <button type=\"button\" class=\"btn btn-sm btn-outline-secondary edit-company\" data-toggle=\"modal\" data-target=\".bd-example-modal-lg\" data-id=\"".$row['id_company']."\"><span data-feather=\"edit\"></span></button>
      </td>";
      $out .= "</tr>";
    }

    return $out;
  }

  function showContent() {

    $head = $this->tableHead();
    $table = $this->tableContent();
    $buttons = $this->tableHeadButtons();

    $n = new parser("table.tpl");
    $n->get_tpl();
    $n->set_tpl("%TITLE%","Фирмы");
    $n->set_tpl("%TABLE_HEAD%", $head);
    $n->set_tpl("%BUTTONS%", $buttons);
    $n->set_tpl("%TABLE_BODY%", $table);

    return $n->tpl_parse();
  }

}
?>
