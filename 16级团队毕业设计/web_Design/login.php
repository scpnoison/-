
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
        <title>注册</title>
    </head>

    <body >
        <?php
//登录
        header('Content-Type:text/html; charset=utf-8');

        if (!isset($_GET['action'])) {
            exit('非法访问!');
        }      
        //用户登录
        if ($_GET['action'] == "login") {
            include('lianjie.php'); //导入数据库连接语句
            //检测用户名及密码是否正确
            $sql = "select [user_name] from [user] where user_ID='{$_POST['user_ID']}' and user_password='{$_POST['user_password']}' ";
            $stmt = sqlsrv_query($conn, $sql);
            if ($stmt === false) {
                exit('<center>数据查询失败！点击此处 <a href="javascript:history.back(-1);">返回</a> 重试</center>');
            }
            $result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            sqlsrv_free_stmt($stmt); 
            if ($result !== NULL) {
                //查到账号，登录成功
                session_start();
                //将用户信息设置到全局变量中储存，用于判断用户是否登录和用户信息
                $_SESSION['user_ID'] = $_POST['user_ID'];
                $_SESSION['user_name'] = $result['user_name']; 
                echo "<center>{$_SESSION['user_name']},登录成功 <a href='home.php'>进入主页</a><br /></center>";
                echo "<script type='text/javascript'> window.onload=function(){tz();};</script>";
            } else {
                //登录错误时
                echo "<center>账号或密码错误！点击此处 <a href='javascript:history.back(-1);'>返回</a> 重试....3秒后自动跳转</center>";
                echo "<script type='text/javascript'> window.onload=function(){tc2();};</script>";
            }
            //关闭连接
            sqlsrv_close($conn);
        }
        //注册用户
        if ($_GET['action'] == "register") {
            $user_ID = $_POST['re_user_ID'];
            $user_password = $_POST['re_user_password'];
            $user_phone = $_POST['re_user_phone'];
            $user_name = $_POST['re_user_name'];
            include('lianjie.php');////导入数据库连接语句
            //查询账号是否存在
            $sql = "select [user_ID] from [user] where user_ID ='$user_ID'";
            $stmt = sqlsrv_query($conn, $sql);
            if ($stmt === false) { //此处返回是否查询成功 而不是查没查到
                exit('<center>数据查询失败！，请联系管理员. 点击此处 <a href="javascript:history.back(-1);">返回</a> 重试</center>');
                die(print_r(sqlsrv_errors(), true));
            }
            $xxow = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC); 
            if ($xxow !== NULL) { //账号已经存在
                echo "<center>此账号已存在，注册失败！ <a href='javascript:history.back(-1);'>返回</a></center>";
                exit();
                sqlsrv_free_stmt($stmt);
                sqlsrv_close($conn);
            }
            if ($xxow == NULL) { //账号未被注册
                $sql = "INSERT INTO [user] (user_ID,user_password,user_name,user_phone) VALUES('$user_ID','$user_password',?,'$user_phone')";
                $params1 = array($user_name, SQLSRV_PHPTYPE_STRING('UTF-8')); //转码utf8
                $Ctmt = sqlsrv_query($conn, $sql, $params1);
                if ($Ctmt === false) { //如果执行失败则显示错误信息
                    echo "执行注册失败，请联系管理员.";
                    die(print_r(sqlsrv_errors(), true));
                }
                echo "<center>注册成功!返回<a href='index.php'>登录</a> ...3秒后自动跳转</center>";
                echo "<script type='text/javascript'> window.onload=function(){tc2();};</script>";
//                清空和关闭连接
                sqlsrv_free_stmt($Ctmt);
                sqlsrv_free_stmt($stmt);
                sqlsrv_close($conn);
            }
        }
        //注销登录
        if ($_GET['action'] == "logout") {
            session_start();
            unset($_SESSION['user_ID']);
            unset($_SESSION['user_name']);
            echo '<center>注销登录成功！点击此处 <a href="index.php">登录</a></center>';
            echo "<script type='text/javascript'> window.onload=function(){tc();};</script>";
        }
        ?>
    </body>
    <script type="text/javascript">
//        自动跳转页面的脚本
        function tz() {
            window.setTimeout("window.location='Home.php'", 100);
        }
        function tc() {
            window.setTimeout("window.location='index.php'", 100);
        }
        function tc2() {
            window.setTimeout("window.location='index.php'", 3000);
        }
    </script>
</html>