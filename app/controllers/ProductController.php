<?php


    namespace app\controllers;


    use app\models\Breadcrumbs;
    use app\models\Product;
    use ishop\App;
    use RedBeanPHP\R;

    class ProductController extends AppController
    {
        public function viewAction()
        {
            //current currency
            $curr = App::$app->getProperty('currency');

            //current cats
            $cats = App::$app->getProperty('cats');

            $alias = $this->route['alias'];
            $product = R::findOne('product', "alias = ? AND status ='visible'", [$alias]);

            if(!$product){
                throw new \Exception('Страница не найдена', 404);
            }

            // breadcrumbs
            $breadcrumbs = Breadcrumbs::getBreadcrumbs($product->category_id, $product->title);

            // related products
            $related = R::getAll("SELECT * FROM related_product JOIN product 
                                       ON product.id = related_product.related_id 
                                       WHERE related_product.product_id = ?", [$product->id]);

            // add to cookie selected product
            $p_model = new Product();
            $p_model->setRecentlyViewed($product->id);

            // viewed products
            $r_viewed = $p_model->getRecentlyViewed($product->id);
            //debug($r_viewed);
            $recentlyViewed = null;
            if($r_viewed){
                $recentlyViewed = R::find('product', ' id IN (' . R::genSlots($r_viewed). ') LIMIT 3', $r_viewed);
                $recentlyViewed = object_to_array($recentlyViewed);
                $recentlyViewed = $p_model->sortRecentlyViewed($r_viewed, $recentlyViewed);
            }


            // get Product Sizes
            if($recentlyViewed){
              for ($z=0; $z<count($recentlyViewed); $z++){
                $getProductSizes = R::getAll("SELECT  GROUP_CONCAT(attribute_value.value) AS value
                                                                FROM attribute_value
                                                                LEFT JOIN attribute_product ON attribute_value.id = attribute_product.attr_id
                                                                WHERE attribute_product.product_id = {$recentlyViewed[$z]['id']} AND attribute_value.attr_group_id = 6");
                $recentlyViewed[$z]['sizes'] = $getProductSizes[0]['value'];
              }
            }

            // gallery
            $gallery = R::findAll("gallery", 'product_id = ? ', [$product->id]);


            // modifications
            $modes = R::findAll('modification', 'product_id = ?', [$product->id]);
            //debug(object_to_array($modes));

            // sizes
           $productFilters = R::getAll("SELECT attribute_value.value,  attribute_value.attr_group_id, attribute_group.title
                                        FROM `attribute_value`
                                        LEFT JOIN attribute_product
                                        ON attribute_product.attr_id = attribute_value.id
                                        LEFT JOIN attribute_group
                                        ON attribute_value.attr_group_id = attribute_group.id
                                        WHERE attribute_product.product_id = $product->id");

           $sizes = R::findAll("attribute_value", 'attr_group_id = ? ', [6]);

           $productFiltersArr = [];
           if($productFilters){
             foreach ($productFilters as $productFilter){
               $productFiltersArr[$productFilter['attr_group_id']]['Items'][] = $productFilter;
               $productFiltersArr[$productFilter['attr_group_id']]['Title'] = $productFilter['title'];
             }
           }

            $this->setMeta($product->title, $product->description, $product->keywords);
            $this->set(compact('product', 'curr', 'cats', 'related', 'gallery', 'recentlyViewed', 'breadcrumbs', 'modes', 'productFiltersArr', 'sizes'));
        }
    }