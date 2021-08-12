<?php

    namespace app\controllers;

    use ishop\App;
    use RedBeanPHP\R as R;
    use ishop\Cache;

    class MainController extends AppController {

//    public $layout = 'test';

        public function indexAction(){
//        $this->layout = 'test';
//        echo __METHOD__;

            $brands = R::find('brand', 'LIMIT 3');

            $hits = R::find('product', "hit = 'yes' AND status = 'visible' LIMIT 8");
            $hits = object_to_array($hits);
            //current currency
            $curr = App::$app->getProperty('currency');

            $canonical = PATH;

            $featuresProducts = R::getAll("SELECT product.id, product.title, product.alias, product.price, 
                                        product.old_price, product.img, product.hit,  
                                        GROUP_CONCAT(product_base_img.img SEPARATOR ',') AS base_img
                                        FROM product 
                                        LEFT JOIN product_base_img   ON product_base_img.product_id = product.id
                                        WHERE product.old_price != 0 OR product.hit = 'yes' AND product.status = 'visible'
                                        GROUP BY product.id ORDER BY product.id DESC LIMIT 16");

            if($featuresProducts){
              for ($z=0; $z<count($featuresProducts); $z++){
                $getProductSizes = R::getAll("SELECT  GROUP_CONCAT(attribute_value.value) AS value
                                                                    FROM attribute_value
                                                                    LEFT JOIN attribute_product ON attribute_value.id = attribute_product.attr_id
                                                                    WHERE attribute_product.product_id = {$featuresProducts[$z]['id']} AND attribute_value.attr_group_id = 6");
                $featuresProducts[$z]['sizes'] = $getProductSizes[0]['value'];
              }
            }

            $articles = R::getAll("SELECT  id, name, alias, img_preview, title
                                        FROM articles
                                        WHERE status = 'visible' ORDER BY rand() LIMIT 4");

            $saleRandomProducts = R::getAll("SELECT product.id, product.title, product.alias, product.price, 
                                        product.old_price, product.img, product.hit,  
                                        GROUP_CONCAT(product_base_img.img SEPARATOR ',') AS base_img
                                        FROM product 
                                        LEFT JOIN product_base_img   ON product_base_img.product_id = product.id
                                        WHERE product.old_price != 0 AND product.status = 'visible'
                                        GROUP BY product.id ORDER BY rand() LIMIT 8");

            if($saleRandomProducts){
              for ($z=0; $z<count($saleRandomProducts); $z++){
                $getProductSizes = R::getAll("SELECT  GROUP_CONCAT(attribute_value.value) AS value
                                                                      FROM attribute_value
                                                                      LEFT JOIN attribute_product ON attribute_value.id = attribute_product.attr_id
                                                                      WHERE attribute_product.product_id = {$saleRandomProducts[$z]['id']} AND attribute_value.attr_group_id = 6");
                $saleRandomProducts[$z]['sizes'] = $getProductSizes[0]['value'];
              }
            }

            $sizes = R::findAll("attribute_value", 'attr_group_id = ? ', [6]);

            $this->set(compact('brands', 'hits', 'curr', 'canonical', 'featuresProducts', 'sizes', 'articles', 'saleRandomProducts'));

            $this->setMeta('Демо версия интернет магазина на системе управления MegaShop',
                      'Интернет магазин от MegaShop: тестируй весь функционал на демо версии магазина',
                       'магазин, интернет магазин, megashop, cms, движок');

        }

    }