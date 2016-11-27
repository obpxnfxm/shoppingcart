<?php  //记得删除此行
/**
 * Author: leo
 * Function: 连接 book_sc 数据库函数集合
 */

// 将一个 MySQL 结果标识符转换为结果数组
function db_result_to_array($result){
    $res_array = array();

    for($count = 0; $row = $result->fetch_assoc(); $count++){
        $res_array[$count] = $row;
    }

    return $res_array;
}

function db_connect(){
    $result = new mysqli('localhost', 'book_sc', 'password', 'book_sc');
    if(!result){
        return false;
    }
    // 打开自动提交，避免其它地方使用事务
    $result->autocommit(TRUE);
}
