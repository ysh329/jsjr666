<?php
// 连主库
$link=mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);

// 连从库
// $link=mysql_connect(SAE_MYSQL_HOST_S.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);

if($link)
{
    mysql_select_db(SAE_MYSQL_DB,$link);
    //your code goes here
    echo "link successfully!";
    $sql = "INSERT INTO `app_jsjr666`.`new_list2014` (`name`, `sex`, `class`, `telephone`, `skill`, `direction`, `words`) VALUES ('袁帅', '男', '智能1201', '15129268038', 'C', '数学建模', '没啥说的')";
    $query = mysql_query($sql);
}
?>