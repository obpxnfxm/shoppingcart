<?php //记得去除此头部
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 2016/11/25
 * Time: 8:54
 */

// 打印出每页标题栏上给出的购物车总结（购物车物品数量，总价）
function do_html_header(){
    if (!$_SESSION['items']){
        $_SESSION['items'] = '0';
    }
    if (!$_SESSION['total_price']){
        $_SESSION['total_price'] = '0.00';
    }

}