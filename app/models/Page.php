<?php

namespace app\models;

use RedBeanPHP\R;

class Page extends AppModel {

  public function getPages()
  {
   return object_to_array(R::getAll('SELECT title, alias FROM pages WHERE status = "visible" ORDER BY id'));
  }


}