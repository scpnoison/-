
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
        <title>修改密码</title>
        <style>
            table{border-collapse:collapse;border:1px solid #000;background-color:rgba(255,255,255,0.5)}
        </style>
    </head>
    <?php
    include("menu.php");//导入标题栏
    include("lianjie.php");//导入数据库连接语句
    if (!isset($_SESSION['user_ID'])) { //检测是否已经登录
        echo "<center>请先<a href='index.php'>登录</a></center>";
        exit();
    }
//修改密码
    if ($_GET['action'] == "change") {
        $user_ID = $_SESSION['user_ID'];
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        //查询原有信息
        $sql = "SELECT [user_ID] FROM [user] WHERE user_ID = '$user_ID' and user_password ='$old_password'";
        $Ktmt = sqlsrv_query($conn, $sql);
        $xxow = sqlsrv_fetch_array($Ktmt, SQLSRV_FETCH_ASSOC); 
        sqlsrv_free_stmt($Ktmt);
        if ($xxow == NULL) { //输入的原密码错误
            echo "<center>输入的原密码错误 <a href='javascript:history.back(-1);'>返回</a></center>";
            exit();
        } else {
            //更新密码
            $sql = "update [user] set [user_password] = '$new_password' where user_ID = '$user_ID'";
            $Ktmt = sqlsrv_query($conn, $sql);
            if ($Ktmt == false) {
                die(print_r(sqlsrv_errors(), true));
            } else {
                echo "<center>密码修改成功,<br>";
                echo "请重新<a href='index.php'>登录</a> ...3秒后自动跳转</center>";
                  echo "<script type='text/javascript'> window.onload=function(){tc2();};</script>";
                  //退出登录 注销用户信息
                unset($_SESSION['user_ID']);
                unset($_SESSION['user_name']);
            }
            sqlsrv_free_stmt($Ktmt);
            sqlsrv_close($conn);
        }
    }
    ?>
    <body class="body1">
 <script type="text/javascript">
       //跳转到登录页脚本
        function tc2() {
            window.setTimeout("window.location='index.php'", 3000);
        }
    </script>
    </body>

</html>
