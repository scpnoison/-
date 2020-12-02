<?php
//连接数据库的语句
$serverName = "localhost"; //数据库地址
$connectionInfo = array("Database" => "web_db", "UID" => "sa", "PWD" => "sa12345678", "CharacterSet" => "UTF-8"); //数据库名称、账户、密码、编码
$conn = sqlsrv_connect($serverName, $connectionInfo) or die("连接失败,检查连接设置!");
//若果连接失败则显示错误信息
if ($conn === false) {
    echo "Could not connect.<br>";
    die(print_r(sqlsrv_errors(), true));
} else {
    
}
?>


