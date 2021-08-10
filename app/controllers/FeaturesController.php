<?php

namespace app\controllers;


use app\models\Breadcrumbs;
use app\models\Category;
use app\widgets\filter\Filter;
use ishop\App;
use RedBeanPHP\R;
use ishop\libs\Pagination;

class FeaturesController extends AppController
{
  public function viewAction()
  {

    $filterData['features'] = trim(explode('?', $_SERVER['REQUEST_URI'])[0], '/');
    $featuresCase = $filterData['features'];

    switch($featuresCase){
      case 'sale':
        $featuresQuery = "product.old_price != 0";
        $pageTitle = "Распродажа";
        break;
      case 'new';
        $featuresQuery = "product.hit = 'yes'";
        $pageTitle = "Новинки";
        break;
    }


    $curr = \ishop\App::$app->getProperty('currency');

      // 3. get product sort parameters
    $productsSort = App::$app->getProperty('productsSort');
    $sort = self::productSort();
    $productSortDB = self::productSortDB();
    $productsPerPage = App::$app->getProperty('productsPerPage');
    $perpage = self::getProductPerpage();
    $productsMode = self::getProductMode();

    // 4. filters for products
    $filter = null;
    $sql_part = '';
    if (!empty($_GET['filter'])) {
      new \app\widgets\filter\Filter($filterData);
      $filter = Filter::getFilter();

      if ($filter) {
        $countGroups = Filter::countGroups($filter);
        $sql_part = "AND product.id IN (
                    SELECT attribute_product.product_id
                    FROM attribute_product 
                    LEFT JOIN attribute_value ON attribute_value.id = attribute_product.attr_id
                    LEFT JOIN attribute_group ON attribute_group.id = attribute_value.attr_group_id
                    WHERE attribute_product.attr_id IN ({$filter})
                    GROUP BY  attribute_product.product_id
                    HAVING COUNT(DISTINCT attribute_value.attr_group_id) = {$countGroups}
                    )";
      }

      $filter = explode(',', $filter);
    }

    $filterPrice = null;
    if (!empty($_GET['minPrice']) && $_GET['maxPrice']) {
      $minPrice = round($_GET['minPrice'] / $curr['value']);
      $maxPrice = round($_GET['maxPrice'] / $curr['value']);
      $sql_part .= " AND product.price >= {$minPrice} AND product.price <= {$maxPrice} ";
    }

    // 5. find total (with filters) & get pagination
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $total = R::count('product', "$featuresQuery AND status = 'visible' $sql_part");
    $pagination = new Pagination($page, $perpage, $total);
    $start = $pagination->getStart();

    $products = R::getAll("SELECT product.*, GROUP_CONCAT(product_base_img.img SEPARATOR ',') AS base_img FROM product 
                                        LEFT JOIN product_base_img   ON product_base_img.product_id = product.id
                                        WHERE $featuresQuery AND product.status = 'visible' $sql_part
                                        GROUP BY product.id ORDER BY $productSortDB LIMIT $start, $perpage");

    if ($products) {
      for ($z = 0; $z < count($products); $z++) {
        $getProductSizes = R::getAll("SELECT  GROUP_CONCAT(attribute_value.value) AS value
                                                                  FROM attribute_value
                                                                  LEFT JOIN attribute_product ON attribute_value.id = attribute_product.attr_id
                                                                  WHERE attribute_product.product_id = {$products[$z]['id']} AND attribute_value.attr_group_id = 6");
        $products[$z]['sizes'] = $getProductSizes[0]['value'];
      }
    }

    $sizes = R::findAll("attribute_value", 'attr_group_id = ? ', [6]);

    $productRangeCount = ($perpage * ($pagination->currentPage - 1) + 1) . " - " . ($perpage * ($pagination->currentPage - 1) + count($products));


    $filterPrice = R::getRow("SELECT MIN(price) AS minPrice, MAX(price) AS maxPrice
                  FROM `product` 
                  WHERE hit = 'yes' AND status = 'visible'");

    $filterData['minPrice'] = round($filterPrice['minPrice'] * $curr['value']);
    $filterData['maxPrice'] = round($filterPrice['maxPrice'] * $curr['value']);


    if ($this->isAjax()) {
      $categoryViews['products'] = $this->loadViews('products', compact('products'));
      $categoryViews['productPagination'] = $this->loadViews('product_pagination', compact('pagination', 'total', 'productRangeCount'));
      $filterObj = new \app\widgets\filter\Filter($filterData);
      $categoryViews['productFilter'] = $filterObj->run();

      echo json_encode($categoryViews, true);
      die;
    }

  //  $this->setMeta($category->title, $category->description, $category->keywords);
    $this->set(compact('breadcrumbs', 'products',
      'pagination', 'total', 'perpage', 'productsPerPage', 'sizes', 'pageTitle',
      'productsSort', 'sort', 'productRangeCount', 'productsMode', 'filterData'));

  }

}