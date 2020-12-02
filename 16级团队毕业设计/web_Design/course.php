
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
        <title>课程</title>
        <script type="text/javascript">

        </script>
        <style>
            table{border-collapse:collapse;border:1px solid #000;background-color:rgba(255,255,255,0.5)}
            input{width:100px}
           
        </style>
    </head>
    <?php
    include("menu.php");//导入标题栏
    include("lianjie.php");//导入数据库连接语句
    //订阅课程
    if (isset($_GET['dykc'])) { 
        if (!isset($_SESSION['user_ID'])) { //检测是否已经登录
            echo "<center>请先<a href='index.php'>登录</a></center>";
            exit();
        } else { //登录了 查询用户是否订阅过
            $sql = "SELECT [course_ID] FROM [course_sub] WHERE user_ID = '{$_SESSION['user_ID']}' and course_ID ='{$_GET['dykc']}'";
            $Ktmt = sqlsrv_query($conn, $sql);
            $xxow = sqlsrv_fetch_array($Ktmt, SQLSRV_FETCH_ASSOC); 
            sqlsrv_free_stmt($Ktmt);
            if ($xxow !== NULL) { //已经订阅
                echo "<center>您已添加过此课程 <a href='javascript:history.back(-1);'>返回</a></center>";
                exit();
            } else { //没有订阅过则添加
                $sql = "INSERT INTO [course_sub] (user_ID,course_ID) VALUES('{$_SESSION['user_ID']}','{$_GET['dykc']}')";
                $Ktmt = sqlsrv_query($conn, $sql);
                sqlsrv_free_stmt($Ktmt);
            }
        }
    }
    //查询所有课程
    if(!isset($_GET['type'])){
    $sql = "SELECT [course_ID],[course_name],[course_Introduction] FROM [course] ";
    $atmt = sqlsrv_query($conn, $sql);
    }else {  $sql = "SELECT [course_ID],[course_name],[course_Introduction] FROM [course] WHERE course_Types = '{$_GET['type']}' ";
    $atmt = sqlsrv_query($conn, $sql);
    }
    
    ?>
    <body class="body2">
    <center>
        <table border="1" width="800" height=600 background="">
            <?php
            if(isset($_GET['type'])){
            echo "<tr><td colspan='2' height='30' style='text-align:center;font-size:30px'>{$_GET['type']}</td></tr>";}
            //显示所有课程标题，简介 学习超链接
            while ($aow = sqlsrv_fetch_array($atmt, SQLSRV_FETCH_ASSOC)) {
                echo "<tr align=center height=10% style='font-size:20;font-family:楷体'><td  width=500 >{$aow['course_name']} </td><td align=center width=20% ><a href='study.php?id={$aow['course_ID']}'><input type='button' value='开始学习' class='button blue small'></a></td></tr>";
                echo "<tr ><td  width=500 align=left valign=top>{$aow['course_Introduction']} </td>";
                //自动显示订阅按钮
                if (!isset($_SESSION['user_ID'])) { //没登录
                    echo "<td align=center><a href='index.php'><input type='button' value='登录后可收藏' class='button gray small'></a></td></tr>";
                } else {
                    //登录了 查询用户是否订阅过
                    $sql = "SELECT [course_ID] FROM [course_sub] WHERE user_ID = '{$_SESSION['user_ID']}' and course_ID ='{$aow['course_ID']}'";
                    $Ptmt = sqlsrv_query($conn, $sql);
                    $ppow = sqlsrv_fetch_array($Ptmt, SQLSRV_FETCH_ASSOC); 
                    sqlsrv_free_stmt($Ptmt);
                    if ($ppow !== NULL) { //已经订阅
                        echo "<td align=center> 您已订阅此课程</td></tr>";
                    } else { //没订阅
                        echo "<td align=center><a href='course.php?dykc={$aow['course_ID']}'><input type='button' value='订阅课程' class='button green small'></a></td></tr>";
                    }
                }
            }
            ?>
        </table>
    </center>
</body>
<?php
//清空并关闭连接
sqlsrv_free_stmt($atmt);
sqlsrv_close($conn);
?>
</html>
