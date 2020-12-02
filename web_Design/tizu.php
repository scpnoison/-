
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
        <title>题库</title>
        <style>
            table{border-collapse:collapse;border:1px solid #000;background-color:rgba(255,255,255,0.5)}
            input{width:100px}
          
        </style>
    </head>
    <?php
    include("menu.php");//导入标题栏
    include("lianjie.php");//导入数据库连接语句
    // put your code here
    if (isset($_GET['dytz'])) { //检测到订阅题组
        if (!isset($_SESSION['user_ID'])) { //检测是否已经登录
            echo "<center>请先<a href='index.php'>登录</a></center>";
            exit();
        } else { //登录了
            $sql = "SELECT [tizu_ID] FROM [tizu_sub] WHERE user_ID = '{$_SESSION['user_ID']}' and tizu_ID ='{$_GET['dytz']}'";
            $Ktmt = sqlsrv_query($conn, $sql);
            $xxow = sqlsrv_fetch_array($Ktmt, SQLSRV_FETCH_ASSOC);
            if ($xxow !== NULL) { //已经订阅
                echo "<center>您已添加过此题组 <a href='javascript:history.back(-1);'>返回</a></center>";
                exit();
            } else {  //没有订阅过则添加
                $sql = "INSERT INTO [tizu_sub] (user_ID,tizu_ID) VALUES('{$_SESSION['user_ID']}','{$_GET['dytz']}')";
                $Ctmt = sqlsrv_query($conn, $sql);
            }
        }
        sqlsrv_free_stmt($Ctmt);
        sqlsrv_free_stmt($Ktmt);
    }
    //查询题组信息
    if(!isset($_GET['type'])){
    $sql = "SELECT [tizu_ID],[tizu_name],[tizu_Introduction] FROM [tizu] ";
    $atmt = sqlsrv_query($conn, $sql);
    }else{
         $sql = "SELECT [tizu_ID],[tizu_name],[tizu_Introduction] FROM [tizu] WHERE tizu_Types = '{$_GET['type']}'";
    $atmt = sqlsrv_query($conn, $sql);
    }
    ?>
    <body class="body2"> 
    <center>
        <table border="1" width="800" style="min-height:800px" >
            <?php
              if(isset($_GET['type'])){
            echo "<tr><td colspan='2' height='30' style='text-align:center;font-size:30px'>{$_GET['type']}</td></tr>";}
            //显示题组超链接
            while ($aow = sqlsrv_fetch_array($atmt, SQLSRV_FETCH_ASSOC)) {
                echo "<tr style='height:50px;'><td  align=center style='font-size:20;font-family:楷体'>{$aow['tizu_name']} </td><td align=center width=20%><a href='Exercise.php?id={$aow['tizu_ID']}'><input type='button' value='开始练题' class='button blue small'></a></td></tr>";
                echo "<tr style='height:100px;'><td  align=left valign=top>{$aow['tizu_Introduction']}</td> ";
                //自动显示订阅按钮
                if (!isset($_SESSION['user_ID'])) { //没登录
                    echo "<td align=center><a href='index.php'><input type='button' value='登录后可收藏' class='button gray small'></a></td></tr>";
                } else {
                    $sql = "SELECT [tizu_ID] FROM [tizu_sub] WHERE user_ID = '{$_SESSION['user_ID']}' and tizu_ID ='{$aow['tizu_ID']}'";
                    $Ptmt = sqlsrv_query($conn, $sql);
                    $ppow = sqlsrv_fetch_array($Ptmt, SQLSRV_FETCH_ASSOC); //传递结果
                    sqlsrv_free_stmt($Ptmt);
                    if ($ppow !== NULL) { //已经订阅
                        echo "<td  align=center>您已订阅此题组</td></tr>";
                    } else { //没订阅
                        echo "<td align=center><a href='tizu.php?dytz={$aow['tizu_ID']}'><input type='button' value='订阅题组' class='button green small'></a></td></tr>";
                    }
                }
            }
            ?>
        </table>
    </center>
    <?php
//清空并关闭连接
    sqlsrv_free_stmt($atmt);
    sqlsrv_close($conn);
    ?>
</body>
</html>
