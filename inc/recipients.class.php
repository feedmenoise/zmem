<?php

class Recipients {
	
	function tableHead() {
		$out = "<tr>
    <th>#</th>
    <th>ФИО</th>
    <th>Почта</th>
    <th></th>
    </tr>";
    return $out;
  }

  function tableHeadButtons() {
    $out = "
    <button type=\"button\" class=\"btn btn-sm btn-outline-secondary edit-recipient\" data-toggle=\"modal\" data-target=\".bd-example-modal-lg\" data-id=\"\">
    <span data-feather=\"plus\"></span>
    Добавить
    </button>";

    return $out;
  }

  function tableContent() {
    $connect = new mysqli("localhost","root","zhopa13","zmem_db");
    $connect->set_charset("utf8");
    $result = $connect->query("SELECT * FROM RECIPIENTS");
    $out ="";
    while($row= mysqli_fetch_array($result)) { 
      $out .= "<tr>";
      $out .= "<td>" . $row['id_recipient'] . "</td>";
      $out .= "<td>" . $row['name'] . "</td>";
      $out .= "<td>" . $row['email'] . "</td>";
      $out .= "<td>
      <button type=\"button\" class=\"btn btn-sm btn-outline-secondary edit-recipient\" data-toggle=\"modal\" data-target=\".bd-example-modal-lg\" data-id=\"". $row['id_recipient'] ."\"><span data-feather=\"edit\"></span></button>
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
    $n->set_tpl("%TITLE%","Получатели");
    $n->set_tpl("%TABLE_HEAD%", $head);
    $n->set_tpl("%BUTTONS%", $buttons);
    $n->set_tpl("%TABLE_BODY%", $table);

    return $n->tpl_parse();
  }

}
?>
