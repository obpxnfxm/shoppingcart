<?php // 此php开始标识记得去除
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 2016/11/25
 * Time: 8:57
 * Function: 函数模块，输出 HTML 函数集合
 */

// 以一列指向数组目录链接的形式显示一组目录
function display_categories($cat_array){
    if(!is_array($cat_array)){
        echo "<p>No categories currently available</p>";
        return;
    }

    foreach($cat_array as $row){
        $url = "show_cat.php?catid=".($row['catid']);
        $title = $row['catname'];
        echo "<li>";
        do_html_url($url, $title);
        echo "</li>";
    }

    echo '<ul>';
    echo '</ul>';
    echo '<hr />';
}

/**
 * 功能：显示购物车中的商品及其数量、价钱
 * $change 参数: 购物车中商品的数量是否可以修改
 * $images 参数：每种商品是否显示其图片，0 不显示，1 显示
 */
function display_cart($cart, $change = true, $images = 1){
    // table header, form is included in table and contains tr
    echo "<table border=\"0\" width = \"100%\" cellspacing=\"0\">
          <form action=\"show_cart.php\" method=\"post\">
          <tr><th colspan=\"".(1 + $images)."\" bgcolor=\"#cccccc\">Item</th>
          <th bgcolor=\"#cccccc\">Price</th>
          <th bgcolor=\"#cccccc\">Quantity</th>
          <th bgcolor=\"#cccccc\">Total</th>
          </tr>";

    // display each item as a table row
    foreach($cart as $isbn => $qty){
        $book = get_book_details($isbn);
        echo "<tr>";

        // 若有图片，显示第 1 列，商品缩略图片
        if($images == true){
            echo "<td align=\"left\">";
            if ( file_exists("images/".$isbn.".jpg") ){
                $size = GetImageSize("images/".$isbn.".jpg");
                if( ($size[0] > 0) && ($size[1] > 0) ){
                    echo "<img src=\"images/".$isbn.".jpg\"
                         style=\"border: 1px solid black\"
                         width=\"".($size[0]/3)."\"
                         height=\"".($size[1]/3)."\" />";
                }
            } else {
                echo "&nbsp;";
            }
            echo "</td>";
        }

        // 显示第 2 列，图书标题 + 作者信息；以及第 3 列，商品单价（保留 2 位小数）
        echo "<td align=\"left\">
              <a href=\"show_book.php?isbn=".$isbn."\">".$book['title']."</a>
              by ".$book['author']."</td>
              <td align=\"center\">\$".number_format($book['price'], 2)."</td>
              <td align=\"center\">";

        // 显示第 4 列：单个商品的数量，如果允许修改，则显示为一个文本框
        if($change == true){
            echo "<input type=\"text\" name=\"".$isbn."\" value=\"".$qty."\" size=\"3\" />";
        } else {
            echo $qty;
        }

        // 显示第 5 列，此商品的总价（单价 * 数量）
        echo "</td>
              <td align=\"center\">\$".$number_format($book['price']*$qty, 2)."</td>
              </tr>\n";
    }

    // display total row
    echo "<tr>
          <th colspan=\"".(2+$images)."\" bgcolor=\"#cccccc\">&nbsp;</th>
          <th align=\"center\" bgcolor=\"#cccccc\">".$_SESSION['items']."</th>
          <th align=\"center\" bgcolor=\"#cccccc\">\$"
            .number_format($_SESSION['prices'], 2)."</th>
          </tr>";

    // display save change button
    if($change == true){
        echo "<tr>
              <td clospan\"".(2+$images)."\">&nbsp;</td>
              <td align=\"center\">
                // 隐藏域，提交后，$_POST['save'] 为 TRUE
                <input type=\"hidden\" name=\"save\" value=\"true\" />
                <input type=\"image\" src=\"images/save-changes.gif\"
                    border=\"0\" alt=\"Save Changes\" />
              </td>
              <td>&nbsp;</td>
              </tr>";
    }
    echo "</form></table>";
}