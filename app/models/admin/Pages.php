<?php


namespace app\models\admin;

use app\models\AppModel;

class Pages extends AppModel
{

  public $attributes = [
    'title' => '',
    'text' => '',
    'alias' => '',
    'status' => ''
  ];

  public $rules = [
    'required' => [
          ['title'],
          ['text']
      ]
  ];


}