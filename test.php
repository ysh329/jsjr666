<?php
$link = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);

// 连从库
// $link = mysql_connect(SAE_MYSQL_HOST_S.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);

if($link)
{
    mysql_select_db(SAE_MYSQL_DB, $link);
    //开始执行
    echo "link successfully!";
    $random_id = rand(1,4);
    $sql = "SELECT jokes_content FROM jokes WHERE jokes_id='$random_id'";
    $result = mysql_query($sql);//执行sql语句
    $row = mysql_fetch_row($result);
    print_r($row);
}
?>