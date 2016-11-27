<--! 网站首页，显示系统中的图书目录 -->
<?php
    include ('book_sc_fns.php');
    session_start();
    do_html_header('Welcome to Book-O-Rama');

    echo "<p>Please choose a category:</p>";

    // get categories out of database
    $cat_array = get_categories();

    // display as links to cat pages
    display_categories($cat_array);

    // if logged in as admin, show add, delete, edit cat links
    if(isset($_SESSION['admin_user'])){
        display_button("admin.php", "admin-menu", "Admin Menu");
    }
    do_html_footer();