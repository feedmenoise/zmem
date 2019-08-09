<?php

class Nav {

	function showLeftMenu($active) {

		$items = 
		 (($active == "home") ? "<li class=\"nav-item\"><a class=\"nav-link active\"" : "<li class=\"nav-item\"><a class=\"nav-link\"" ) ." href=\"?p=home\"><span data-feather=\"home\"></span>Главная</a></li>" .
         (($active == "contracts" ) ? "<li class=\"nav-item\"><a class=\"nav-link active\"" : "<li class=\"nav-item\"><a class=\"nav-link\"" ) ." href=\"?p=contracts\"><span data-feather=\"file\"></span>Договора</span></a></li>" .
         (($active == "recipients" ) ? "<li class=\"nav-item\"><a class=\"nav-link active\"" : "<li class=\"nav-item\"><a class=\"nav-link\"" ) ." href=\"?p=recipients\"><span data-feather=\"users\"></span>Получатели рассылки</a></li>" .
         (($active == "companies" ) ? "<li class=\"nav-item\"><a class=\"nav-link active\"" : "<li class=\"nav-item\"><a class=\"nav-link\"" ) ." href=\"?p=companies\"><span data-feather=\"briefcase\"></span>Фирмы</a></li>" .
         (($active == "adresat" ) ? "<li class=\"nav-item\"><a class=\"nav-link active\"" : "<li class=\"nav-item\"><a class=\"nav-link\"" ) ." href=\"?p=adresat\"><span data-feather=\"users\"></span>Адресат</a></li>";

         #.(($active == "generate" ) ? "<li class=\"nav-item\"><a class=\"nav-link active\"" : "<li class=\"nav-item\"><a class=\"nav-link\"" ) ." href=\"?p=generate\"><span data-feather=\"bar-chart-2\"></span>Сгенерировать</a></li>"
         return $items;

	}

	function showLeftMenuFilters($active) {
		$items = "<h6 class=\"sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted\">
          <span>Фильтры</span>
          <a class=\"d-flex align-items-center text-muted\" href=\"#\">
          </a>
        </h6>
        <ul class=\"nav flex-column mb-2\">";

		$items .= 
		  (($active == "all" ) ? "<li class=\"nav-item\"><a class=\"nav-link active\"" : "<li class=\"nav-item\"><a class=\"nav-link\"" ) . " href=\"?p=contracts&f=all\"><span data-feather=\"file-text\"></span>Показать все</a></li>" .
          (($active == "active" ) ? "<li class=\"nav-item\"><a class=\"nav-link active\"" : "<li class=\"nav-item\"><a class=\"nav-link\"" ) . " href=\"?p=contracts&f=active\"><span data-feather=\"file-text\"></span>Активные</a></li>" .
          (($active == "inactive" ) ? "<li class=\"nav-item\"><a class=\"nav-link active\"" : "<li class=\"nav-item\"><a class=\"nav-link\"" ) . " href=\"?p=contracts&f=inactive\"><span data-feather=\"file-text\"></span>Не активные</a></li>" .
          (($active == "soon" ) ? "<li class=\"nav-item\"><a class=\"nav-link active\"" : "<li class=\"nav-item\"><a class=\"nav-link\"" ) . " href=\"?p=contracts&f=soon\"><span data-feather=\"file-text\"></span>Скоро истечет</a></li>" .
          (($active == "failed" ) ? "<li class=\"nav-item\"><a class=\"nav-link active\"" : "<li class=\"nav-item\"><a class=\"nav-link\"" ) . " href=\"?p=contracts&f=failed\"><span data-feather=\"file-text\"></span>Просроченые</a></li>";

        $items .= "</ul>";
        
        return $items;
	}

	function mainMenu() {

		if (isset($_GET["p"]))
			$page = $_GET["p"];
		else
			$page = "home";


		if ($page == "contracts") {
			if (isset($_GET["f"]))
				$filter = $_GET["f"];
			else 
				$filter = "all";

			$filters = $this->showLeftMenuFilters($filter);

		}

		$items = $this->showLeftMenu($page);
		

		$n = new parser("leftmenu.tpl");
        $n->get_tpl();
        $n->set_tpl("%ITEMS%", $items);
        $n->set_tpl("%FILTERS%", $filters);
        
        return $n->tpl_parse();
	}
}

?>