<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 2016/11/25
 * Time: 9:27
 * Function: 将 ISBN 作为参数，取回并显示该书的详细信息
 */

    include('book_sc_fns.php');
    // The shopping cart needs sessions, so start one
    session_start();

    $isbn = $_GET['isbn'];

    // Get this book out of database
    $book = get_book_details($isbn);
    do_html_header($book['title']);
    display_book_details($book);

    // Set url for "continue button"
    $target = "index.php";
    if($book['catid']){
        $target = "show_cat.php?catid=".$book['catid'];
    }

    // if logged in as admin, show edit book links
    if(check_admin_user()){
        display_button("edit_book_form.php?isbn=".$isbn, "edit-item", "Edit Item");
        display_button("admin.php", "admin-menu", "Admin Menu");
        display_button($target, "continue", "Continue");
    } else {
        display_button("show_cat.php?new=".$isbn, "add-to-cart", "Add".$book['title']."To My Shopping Cart");
        display_button($target, "continue-shopping", "Continue Shopping");
    }

    do_html_footer();