<?php

declare(strict_types=1);

namespace MyApp\Tasks;

use Phalcon\Cli\Task;

class MainTask extends Task
{
    public function mainAction($start_date, $end_date)
    {
        $sql = "SELECT * FROM `order` WHERE `order_date` >= '$start_date' AND `order_date` <= '$end_date'";
        $orders = $this->db->fetchAll(
            $sql,
            \Phalcon\Db\Enum::FETCH_ASSOC
        );

        echo "Following Orders were placed between $start_date and $end_date\n";
        foreach ($orders as $order) {
            echo "Order id : " . $order['oid'];
            echo " Product id : " . $order['pid'];
            echo " Order Date : " . $order['order_date'];
            echo PHP_EOL;
        }
    }

    public function importAction()
    {
        // will import from file
        $file = BASE_PATH . 'data/imported.csv';
        if (($handle = fopen($file, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $sql = "INSERT INTO `products` (`id`, `title`, `price`, `description`, `category`) VALUES (\"$data[0]\", \"$data[1]\", \"$data[2]\", \"$data[3]\", \"$data[4]\")";
                $this->db->execute($sql);
            }
            fclose($handle);
        }
    }

    public function exportAction()
    {
        // will export in the file
        $file = BASE_PATH . 'data/exported.csv';
        $output = fopen($file, "w");
        fputcsv($output, array('id', 'name', 'price', 'description', 'category'));
        $query = "SELECT * from products";
        $result = $this->db->fetchAll(
            $query,
            \Phalcon\Db\Enum::FETCH_ASSOC
        );
        foreach ($result as $value) {
            fputcsv($output, $value);
        }
        fclose($output);
    }
}
