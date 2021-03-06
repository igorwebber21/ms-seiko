<?php

    namespace app\controllers;

    use app\models\AppModel;
    use app\widgets\currency\Currency;
    use ishop\App;
    use ishop\base\Controller;
    use ishop\Cache;
    use RedBeanPHP\R;

    class AppController extends Controller
    {
        public $responseData = [
            'status' => 0,
            'message' => ''
        ];

        public function __construct($route)
        {
            parent::__construct($route);
            new AppModel();
            App::$app->setProperty('currencies', Currency::getCurrencies());

            App::$app->setProperty('currency', Currency::getCurrency(App::$app->getProperty('currencies')));

            App::$app->setProperty('cats', self::cacheCategory());


//            $cats = R::getAssoc("SELECT * FROM category");
//            debug($cats);


            //debug( App::$app->getProperties());
        }

        public static function cacheCategory(){
            $cache = Cache::instance();
            $cats =  $cache->get('cats');

            if(!$cats){
                $cats = R::getAssoc("SELECT * FROM category");
                $cache->set('cats', $cats);
            }

            return $cats;
        }

        public static function sendResponse($data)
        {
            echo json_encode($data);
            exit;
        }

    }