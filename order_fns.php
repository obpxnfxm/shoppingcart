<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 2016/11/26
 * Time: 11:43
 * Function: 用以保存和获取订单数据的函数集合
 */

    function process_card($card_details){
        // connect to payment gateway or
        // use pgp to encrypt and mail or
        // store in DB if you really want to

        return true;
    }

    function insert_orders($order_details){
        // extract order_details out as variables
        extract($order_details);

        // set shipping address same as address
        if( (!$ship_name) && (!$ship_address) && (!$ship_city) && (!$ship_state) && ($!ship_zip) && (!&ship_country) ){
            $ship_name = $name;
            $ship_address = $address;
            $ship_city = $city;
            $ship_state = $state;
            $ship_zip = $zip;
            $ship_country = $country;
        }

        $conn = db_connect();

        // we want to insert the order as a transaction start one by turning off autocommit
        $conn->autocommit(FALSE);

        // insert customer address

        // 首先判断同样地址的用户是否存在，存在则读取客户ID，不存在则插入新客户ID
        $query = "select customerid from customers WHERE 
                  name = '".$name."' and address = '".$address."'
                  and city = '".$city."' and state = '".$state."'
                  and zip = '".$zip."' and country = '".$country."'";
        $result = $conn->query($query);

        if($result->num_rows > 0){
            $customer = $result->fetch_object();
            $customerid = $customer->customerid;
        } else {
            $query = "insert into customer VALUES 
                      ('', '".$name."', '".$address."', '".$city."', 
                       '".$state."', '".$zip."', '".$country."')";
            $result = $conn->query($query);
            if( !$result ){
                return false;
            }
        }

        $customerid = $conn->insert_id;

        $date = date("Y-m-d");

        // 插入订单
        $query = "insert into orders VALUES 
                  ('', '".$customerid."', '".$_SESSION['total_price']."', 
                   '".$date."', '".PARTIAL."', '".$ship_name."', 
                   '".$ship_address."', '".$ship_city."', 
                   '".$ship_state."', '".$ship_zip."', 
                   '".$ship_country."')";

        $result = $conn->query($query);
        if(!$result){
            return false;
        }

        // get orderid
        $query = "select orderid from orders WHERE 
                  customerid = '".$customerid."', and 
                  amount > (".$_SESSION['total_price']."-.001) and
                  amount < (".$_SESSION['total_price']."+.001) and
                  date = '".$date."' and
                  ship_name = '".$ship_name."' and 
                  ship_address = '".$ship_address."' and
                  ship_city = '".$sip_city."' and 
                  ship_state = '".$ship_state."' and 
                  ship_zip = '".$ship_zip."' and
                  ship_country = '".$ship_country."'";

        $result = $conn->query($query);

        if($result->num_row > 0){
            $order = $result->fetch_object();
            $orderid = $order->orderid;
        } else {
            return false;
        }

        // insert each book
        foreach($_SESSION['cart'] as $isbn => $qty){
            $detail = get_book_details($isbn);
            // 删掉旧的（怎么会有旧的数据的存在？）
            $query = "delete from order_items WHERE 
                      orderid = '".$orderid."' and isbn = '".$isbn."'";
            $result = $conn->query($query);
            $query = "insert into order_items VALUES 
                      ('".$orderid."', '".$isbn."', '".$detail['price']."', $quantity)";
            $result = $conn->query($query);
            if(!$result){
                return false;
            }
        }

        // end transaction
        $conn->commit();
        $conn->autocommit(TRUE):

        return $orderid;
    }