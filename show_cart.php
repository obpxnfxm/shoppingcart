<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 2016/11/25
 * Time: 13:15
 * Function: 控制购物车脚本。包括显示车，添加物品到购物车及保存购物车的修改结果
 */

    include('book_sc_fns.php');
    // The shopping cart needs sessions, so start one
    session_start();

    @$new = $_GET['new'];

    // 若向购物车添加新商品，将购物车商品信息（类别、数量、单价）及总价、总数量保存到会话变量中
    if($new){
        // new item selected （购物车为空，创建一个购物车（创建相关 session 变量））
        if(!isset($_SESSION['cart'])){
            $_SESSION['cart'] = array();
            $_SESSION['item'] = 0;
            $_SESSION['total_price'] = '0.00';
        }

        // 若果购物车不为空或刚刚已经创建，判断添加的商品条目在购物中是否已存在
        if( isset($_SESSION['cart'][$new]) ){
            $_SESSION['cart'][$new]++;
        } else {
            $_SESSION['cart'][$new] = 1;
        }

        $_SESSION['total_price'] = calculate_price($_SESSION['cart']);
        $_SESSION['items'] = caculate_items($_SESSION['cart']);
    }

    // 对 “修改了购物车中物品的数量后，点击 save 按钮提交” 的处理
    if( isset($_POST['save']) ){
        foreach ( $_SESSION['cart'] as $isbn => $qty){
            // 若商品数量变为 0, 则删除该商品
            if($_POST[$isbn] == '0'){
                unset($_SESSION['cart']['new']);
            } else { // 否则，则 POST 方式传入的商品数量赋值到 $_SESSION 中
                $_SESSION['cart'][$isbn] = $_POST[$isbn];
            }
        }
        $_SESSION['total_price'] = calculate_price($_SESSION['cart']);
        $_SESSION['items'] = calculate_items($_SESSION['cart']);
    }

    // 打印标题栏：总结购物车中的物品，如总数量，总价钱
    do_html_header('Your shopping cart');

    // 若购物车 session 变量设置且购物车商品价钱不为 0, 显示购物车
    if( ($_SESSION['cart']) && (array_count_values($_SESSION['cart'])) ){
        display_cart($_SESSION['cart']);
    } else {
        echo "<p>There are no items in your cart</p><hr />";
    }

    $target = "index.php";

    // 若向购物车添加新商品，继续在此类别购物（$target 变量保存相应的URL）
    // $target 变量在where使用？
    if($new){
        $details = get_book_details($new);
        if($details['catid']){
            $tartget = "show_cat.php?catid=".$details['catid'];
        }
    }

    // 显示checkout按钮。如果开启了SSL，地址变为HTTPS开关的安全地址
    if($_SERVER['HTTPS'] == 'on'){
        $path = $_SERVER['PHP_SELF'];
        $server = $_SERVER['SERVER_NAME'];
        // 脚本相对于主机的位置，去掉其名称*.php
        $path = str_replace('show_cart.php', '', $path);
        // 改为安全的checkout页的地址，并显示checkout按钮
        display_button("https://".$server.$path."checkout.php", "go-to-checkout", "Go To Checkout");
    } else {
        // if no SSL use below code
        display_button("checkout.php", "go-to-checkout", "Go To Checkout");
    }

    do_html_footer();