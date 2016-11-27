<?php  # php开始标记记得删除
/**
 * Author: leo
 * Date: 2016-11-25
 * Name: book functions
 * Function: 用以保存和获取图书数据的函数集合
 */

// 从数据库中取回一个目录列表
function get_categories(){
	// query database for a list of categories
	$conn = db_connect();
	$query = "select catid, catname from categories";
	$result = @$conn->query($query);
	if(!$result){
		echo "DB query error!<br />";
		return false;
	}
	$num_cats = @$result->num_rows;
	if($num_cats == 0){
		echo "No books in this category.<br />";
		return false;
	}
	$result = db_result_to_array($result);
    return $result;
}

// 将一个目录标识符转换为一个目录名
function get_category_name($catid){
    // query database for the name for a category id
    $conn = db_connect();
    $query = "select catname from categories
              where catid = '".$catid."'";
    $result = @$conn->query($query);
    if(!result){
        echo "<p>Error: Query name for a category id in categories table.</p>";
        return false;
    }
    $num_cats = @$result->num_rows;
    if ($num_cats == 0){
        echo "<p>No data: Query name for a category id in categories table.</p>";
        return false;
    }
    $row = $result->fetch_object();
    return $row->catname;
}

// 计算和返回购物车中的总价格
function calculate_price($cart){
    $price = 0.0;
    if(is_array($cart)){
        $conn = db_connect();
        foreach($cart as $isbn => $qty){
            $query = "select price from books where isbn='".$isbn."'";
            $result = $conn->query($query);
            if($result){
                $item = $result->fetch_object();
                $item_price = $item->price;
                $price = $item_price * $qty;
            }
        }
    }
    return $price;
}

// 计算并返回购物车中物品的总数
function calculate_items($cart){
    $items = 0;
    if(is_array($cart)){
        foreach($cart as $isbn => $qty){
            $items += $qty;
        }
    }
    return $items;
}