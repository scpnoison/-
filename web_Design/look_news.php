
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
        <title>新闻看看</title>
        <style>
            table{border-collapse:collapse;border:1px solid #000;background-color:rgba(255,255,255,0.3)}
        </style>
    </head>
    <?php
    include("menu.php"); //导入标题栏
    include("lianjie.php"); //导入数据库连接语句
    if (isset($_GET['tjxw'])) { //检测到添加收藏
        if (!isset($_SESSION['user_ID'])) { //检测是否已经登录
            echo "<center>请先<a href='index.php'>登录</a></center>";
            exit();
        } else { //登录了
            //查看用户是否已经订阅
            $sql = "SELECT [news_id] FROM [news_col] WHERE user_ID = '{$_SESSION['user_ID']}' and news_id ='{$_GET['tjxw']}'";
            $Ktmt = sqlsrv_query($conn, $sql);
            $xxow = sqlsrv_fetch_array($Ktmt, SQLSRV_FETCH_ASSOC);
            sqlsrv_free_stmt($Ktmt);
            if ($xxow !== NULL) { //已经订阅
                echo "<center>您已收藏过此文章 <a href='javascript:history.back(-1);'>返回</a></center>";
                exit();
            } else {
                //没有订阅过则添加订阅
                $sql = "INSERT INTO [news_col] (user_ID,news_id) VALUES('{$_SESSION['user_ID']}','{$_GET['tjxw']}')";
                $Ctmt = sqlsrv_query($conn, $sql);
                sqlsrv_free_stmt($Ctmt);
            }
        }
    }
    ?>
    <?php
    //根据id查询新闻内容
    $sql = "SELECT * FROM [news] where news_ID = '{$_GET['id']}'";
    $atmt = sqlsrv_query($conn, $sql);
    $aow = sqlsrv_fetch_array($atmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($atmt);
    ?>
    <body  class="body2">
    <center>
        <table border=1 width="1000" height="1000"><tr><td width="75%">
                    <!--显示新闻或通知的标题 时间 作者 类型-->
                    <table border=1 width="100%" height="100%">
                        <tr >
                            <td colspan="3" style="padding-top:20px;padding-left:10px;font-size: 30px;font-family:楷体;height:100px">
                                <?php echo $aow['news_title']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="height:30px">作者:<?php echo $aow['news_Author']; ?></td><td>时间:<?php echo $aow['news_time']; ?></td><td>类型:<?php
                                if ($aow['news_Types'] == '1') {
                                    echo "新闻";
                                } else if ($aow['news_Types'] == '2') {
                                    echo "通知";
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                </td>
                <td rowspan="2"  style="vertical-align: top">
                    <table border="1" style="min-height:500px" width="100%" >
                        <tr >
                            <td style="text-align: center;height:50px" > <?php
                                //自动显示收藏按钮
                                if (!isset($_SESSION['user_ID'])) { //没登录
                                    echo "<a href='index.php'><input type='button' value='登录后可收藏'></a>";
                                } else {
                                    $sql = "SELECT [news_id] FROM [news_col] WHERE user_ID = '{$_SESSION['user_ID']}' and news_id ='{$_GET['id']}'";
                                    $Ptmt = sqlsrv_query($conn, $sql);
                                    $ppow = sqlsrv_fetch_array($Ptmt, SQLSRV_FETCH_ASSOC);
                                    sqlsrv_free_stmt($Ptmt);
                                    if ($ppow !== NULL) { //已经收藏
                                        echo "已收藏此文章";
                                    } else { //没收藏
                                        echo "<a href='look_news.php?id={$_GET['id']}&tjxw={$_GET['id']}'><input type='button' class='button blue medium ' value='收藏此文章'></a>";
                                    }
                                }
                                ?></td>
                        </tr>
                        <!--显示15条推荐新闻-->
                        <tr>
                            <td style="text-align:center;height:50px">
                                推荐内容
                            </td>
                        <tr><td   style='text-align:center;' valign="top" class="dd">
                                <?php
                                $sql = "SELECT top 15 [news_id],[news_title] FROM [news] where news_Types = '1'";
                                $btmt = sqlsrv_query($conn, $sql);
                                while ($bow = sqlsrv_fetch_array($btmt, SQLSRV_FETCH_ASSOC)) {
                                    echo "<a href='look_news.php?id={$bow['news_id']}'>{$bow['news_title']}</a><br><br> ";  //带id的标题超链接 
                                }
                                ?> 
                            </td></tr>
                    </table>
                </td>
            </tr>
            <!--输出新闻内容-->
            <tr height="80%"><td style="padding-left:20px">
                    <?php echo $aow['news_content']; ?>
                </td>
            </tr>
        </table>
    </center>
</body>
<?php
//清空并关闭连接
sqlsrv_free_stmt($btmt);
sqlsrv_close($conn);
?>
</html>
