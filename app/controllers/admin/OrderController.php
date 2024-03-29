<?php


namespace app\controllers\admin;


use ishop\libs\Pagination;
use RedBeanPHP\R;

class OrderController extends AppController
{

    public function indexAction()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perpage = 10;
        $count = R::count('orders');
        $pagination = new Pagination($page, $perpage, $count);
        $start = $pagination->getStart();

        $orders = R::getAll("SELECT `orders`.`id`, `orders`.`user_id`, `orders`.`status`, `orders`.`sum`, `orders`.`date`, `orders`.`update_at`, 
                                  `orders`.`currency`, `user`.`fname`, `user`.`lname`,
                                  COUNT(`order_product`.id) AS `productsCount`
                                  FROM `orders`
                                  JOIN `user` ON `orders`.`user_id` = `user`.`id`
                                  JOIN `order_product` ON `orders`.`id` = `order_product`.`order_id`
                                  GROUP BY `orders`.`id` ORDER BY `orders`.`status`, `orders`.`id` DESC LIMIT $start, $perpage");

        $this->setMeta('Список заказов');
        $this->set(compact('orders', 'pagination', 'count'));
    }

    public function viewAction()
    {
        $order_id = $this->getRequestID();
        $order = R::getRow("SELECT `orders`.*,  `user`.`fname`, `user`.`lname`, `user_delivery`.`delivery_city` AS deliveryCity, `user_delivery`.`delivery_branch` AS deliveryBranch,
                                                `delivery_methods`.`title` AS deliveryMethod, `delivery_methods`.`price` AS deliveryPrice,
                                                 `payment_methods`.`title` AS paymentMethod,
                                                 IF(`currency`.`symbol_left`, `currency`.`symbol_left`, `currency`.`symbol_right`) AS currency
                                FROM `orders`
                                JOIN `user` ON `orders`.`user_id` = `user`.`id`
                                JOIN `order_product` ON `orders`.`id` = `order_product`.`order_id`
                                LEFT JOIN `user_delivery` ON `user_delivery`.`order_id` = `orders`.`id`
                                LEFT JOIN `delivery_methods` ON `delivery_methods`.`id` = `user_delivery`.`delivery_method`
                                LEFT JOIN `payment_methods` ON `payment_methods`.`id` = `user_delivery`.`payment_method`
                                 LEFT JOIN `currency` ON `currency`.`code` = `orders`.`currency`
                                WHERE `orders`.`id` = ?
                                GROUP BY `orders`.`id` ORDER BY `orders`.`status`, `orders`.`id` LIMIT 1", [$order_id]);
        if(!$order){
            throw new \Exception('Страница не найдена', 404);
        }

        $order_products = R::findAll('order_product', "order_id = ?", [$order_id]);
        $this->setMeta("Заказ №{$order_id}");
        $this->set(compact('order', 'order_products'));
    }


    public function changeAction()
    {
        $order_id = $this->getRequestID();
        $status = !empty($_GET['status'])  && $_GET['status'] == 1 ? 'completed' : 'new';
        $order = R::load('orders', $order_id);
        if(!$order){
            throw new \Exception('Страница не найдена', 404);
        }
        $update_at = date("Y-m-d H:i:s");

        //  R::exec( "UPDATE orders SET status='{$status}', update_at = '{$update_at}' WHERE id = {$order_id}");
        $order->status = $status;
        $order->update_at = $update_at;
        R::store($order);

        $_SESSION['success'] = 'Изменения сохранены';
        redirect();
    }

    public function deleteAction()
    {
        $order_id = $this->getRequestID();
        $order = R::load('orders', $order_id);
        R::trash($order);
        $_SESSION['success'] = 'Заказ удален';
        redirect(ADMIN . '/order');
    }

}