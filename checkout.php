<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 2016/11/26
 * Time: 10:51
 * Function: 结帐表单页面，用于获取用户的详细信息
 */

    // include our function set
    include ('book_sc_fns.php');

    // The shopping cart needs session, so start one
    session_start();

    do_html_header("Checkout");

    if( ($_SESSION['cart']) && (array_count_values($_SESSION['cart']) ) ){
        display_cart($_SESSION['cart'], false, 0);
        display_checkout_form();
    } else {
        echo "<p>There are no items in your cart</p>";
    }

    display_button("show_cart.php", "continue-shopping", "Continue Shopping");

    do_html_footer();