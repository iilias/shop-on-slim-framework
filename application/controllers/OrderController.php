<?php
include_once dirname(__DIR__)."\models\DB connection.php";
class OrderController
{
    public $m_twig_container;
    public $m_nav_container;
    public $db;
    public $auth;
    public $capsule;
    public function __construct($container)
    {
        $this->m_twig_container = $container['twig_c'];
        $this->m_nav_container = $container['nav_bar'];
        $this->auth = $container['auth'];
        $this->capsule = $container['db'];
        $this->db = connection::getInstance();
    }
    public function index($req, $resp, $arg)
    {
        $catalogName = $this->db->getConnection()->catalog;
        $template = $this->m_twig_container->loadTemplate('Order.html');


        $res = $this->capsule->table('products')
            ->join('orders', 'products.id', '=', 'orders.Product_id')
            ->select('products.Name', 'products.Price', 'orders.Date')
            ->where('orders.User_id', $this->auth->user()['id'])
            ->get();

        echo $template->render(
            array(
                'title' => 'Orders',
                'nav_list' => $this->m_nav_container,
                'catalog_buff' => $catalogName,
                'order_content' => $res
            ));
        return $resp;
    }
}