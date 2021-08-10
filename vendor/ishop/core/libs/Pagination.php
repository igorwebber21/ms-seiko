<?php


    namespace ishop\libs;

    class Pagination{

        public $currentPage;
        public $perpage;
        public $total;
        public $countPages;
        public $uri;

        public function __construct($page, $perpage, $total)
        {
            $this->perpage = $perpage;
            $this->total = $total;
            $this->countPages = $this->getCountPages();
            $this->currentPage = $this->getCurrentPage($page);
            $this->uri = $this->getParams();
           // var_dump($this->uri);
        }

        public function getHtml()
        {
            $back = null; // ссылка НАЗАД
            $forward = null; // ссылка ВПЕРЕД
            $startpage = null; // ссылка В НАЧАЛО
            $endpage = null; // ссылка В КОНЕЦ
            $page2left = null; // вторая страница слева
            $page1left = null; // первая страница слева
            $page2right = null; // вторая страница справа
            $page1right = null; // первая страница справа

            if( $this->currentPage > 1 ){
                $back = "<li><a class='nav-link' href='{$this->uri}page=" .($this->currentPage - 1). "'>&lt;</a></li>";
            }
            if( $this->currentPage < $this->countPages ){
                $forward = "<li><a class='nav-link' href='{$this->uri}page=" .($this->currentPage + 1). "'>&gt;</a></li>";
            }
            if( $this->currentPage > 3 ){
                $startpage = "<li><a class='nav-link' href='{$this->uri}page=1'>&laquo;</a></li>";
            }
            if( $this->currentPage < ($this->countPages - 2) ){
                $endpage = "<li><a class='nav-link' href='{$this->uri}page={$this->countPages}'>&raquo;</a></li>";
            }
            if( $this->currentPage - 2 > 0 ){
                $page2left = "<li><a class='nav-link' href='{$this->uri}page=" .($this->currentPage-2). "'>" .($this->currentPage - 2). "</a></li>";
            }
            if( $this->currentPage - 1 > 0 ){
                $page1left = "<li><a class='nav-link' href='{$this->uri}page=" .($this->currentPage-1). "'>" .($this->currentPage-1). "</a></li>";
            }
            if( $this->currentPage + 1 <= $this->countPages ){
                $page1right = "<li><a class='nav-link' href='{$this->uri}page=" .($this->currentPage + 1). "'>" .($this->currentPage+1). "</a></li>";
            }
            if( $this->currentPage + 2 <= $this->countPages ){
                $page2right = "<li><a class='nav-link' href='{$this->uri}page=" .($this->currentPage + 2). "'>" .($this->currentPage + 2). "</a></li>";
            }

            return '<ul class="pagination">' . $startpage.$back.$page2left.$page1left.'<li class="active"><a>'.$this->currentPage.'</a></li>'.$page1right.$page2right.$forward.$endpage . '</ul>';
        }

        public function __toString()
        {
            return $this->getHtml();
        }

        public function getCountPages()
        {
            return ceil($this->total / $this->perpage) ?: 1;
        }

        public function getCurrentPage($page)
        {
            if(!$page || $page < 1) $page = 1;
            if($page > $this->countPages) $page = $this->countPages;
            return $page;
        }

        public function getStart()
        {
            return ($this->currentPage - 1) * $this->perpage;
        }

        public function getParams()
        {
            $url = $_SERVER['REQUEST_URI'];
            // echo $url;

            // 1) вырезать второй параметр filter из /category/bryuki?filter=1,2,3,&filter=1,2,3,5,
            preg_match_all("#filter=[\d,&]#", $url, $matches);
            if(count($matches[0]) > 1){
                $url = preg_replace("#filter=[\d,&]+#", "", $url, 1);
            }
            $url = preg_replace("#filter=&+#", "", $url, 1);


            // 2) вырезать второй параметр sort из /category/bryuki?sort=date_desc&sort=price_asc
            preg_match_all("#sort=[\w_]+#", $url, $matches2);
            if(count($matches2[0]) > 1){
                $url = preg_replace("#sort=[\w_]+&#", "", $url, 1);
            }

            // 3) Если есть GET параметр productsPerPage - удаляем его со значением ?productsPerPage=12&page=4
            $url = preg_replace("#productsPerPage=[\d&]+#", "", $url, 1);

            // 4) Если есть GET параметр productsMode - удаляем его со значением ?productsMode=products-listview&page=4
            $url = preg_replace("#productsMode=[\w-&]+#", "", $url, 1);

            // 5) вырезать второй параметр minPrice из /category/bryuki?sort=date_desc&minPrice=800
            preg_match_all("#minPrice=[\d&]#", $url, $matches2);
            if(count($matches2[0]) > 1){
              $url = preg_replace("#minPrice=[\d&]+&#", "", $url, 1);
            }

            // 6) вырезать второй параметр maxPrice из /category/bryuki?sort=date_desc&maxPrice=1400
            preg_match_all("#maxPrice=[\d&]#", $url, $matches2);
            if(count($matches2[0]) > 1){
              $url = preg_replace("#maxPrice=[\d&]+&#", "", $url, 1);
            }

            // если есть лишний амперсант в конце строки, то удаляем его
            $url = trim($url,'&');

            $url = explode('?', $url);
            $uri = $url[0] . '?';
            if(isset($url[1]) && $url[1] != ''){
                $params = explode('&', $url[1]);
                foreach($params as $param){
                    if(!preg_match("#page=#", $param)) $uri .= "{$param}&amp;";
                }
            }
            return urldecode($uri);
        }

    }