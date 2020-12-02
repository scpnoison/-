
<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
        <title>问题收集</title>
        <style>
            table{border-collapse:collapse;border:1px solid #000;background-color:rgba(255,255,255,0.3)}
        </style>
    </head>
    <body class="body2">
        <?php
        include("menu.php");//导入标题栏
        include("lianjie.php");//导入数据库连接语句
        //根据传来的题目ID 查找题目全部内容
        $sql = "SELECT * FROM [question] where QS_id = '{$_GET['id']}'"; 
        $atmt = sqlsrv_query($conn, $sql);
        $aow = sqlsrv_fetch_array($atmt, SQLSRV_FETCH_ASSOC);
        sqlsrv_free_stmt($atmt);
        ?>
    <center>
        <!--显示题目内容-->
        <table  border="1" width=1000 height=800 cellspacing="10px" cellpadding="20px">
            <tr><td width=" 750" valign="top">
                    <table border="1" width ="700" >
                        <?php echo "<tr><td>{$aow['QS_content']}</td></tr>"; ?>
                        <?php echo "<tr><td>A.{$aow['QS_A']}</td></tr>"; ?>
                        <?php echo "<tr><td>B.{$aow['QS_B']}</td></tr>"; ?>
                        <?php echo "<tr><td>C.{$aow['QS_C']}</td></tr>"; ?>
                        <?php echo "<tr><td>D.{$aow['QS_D']}</td></tr>"; ?>
                        <?php echo "<tr><td>解析:{$aow['QS_jiexi']}</td></tr>"; ?>
                        <?php echo "<tr><td>正确答案:{$aow['QS_CA']}</td></tr>"; ?>
                    </table>
                </td>
                <!--显示15条推荐新闻-->
                <td  style="text-align:center" valign="top">  
                    推荐阅读
                    <table  width="100%" style="min-height: 800px"> 
                        <tr width='100%' ><td style='text-align:center;height:20px;width:100%' class='dd'valign="top">
                                <?php
                                $sql = "SELECT top 15 [news_id],[news_title] FROM [news] where news_Types = '1'";
                                $ABtmt = sqlsrv_query($conn, $sql);
                                while ($ABow = sqlsrv_fetch_array($ABtmt, SQLSRV_FETCH_ASSOC)) {

                                    echo "<a href='look_news.php?id={$ABow['news_id']}'>{$ABow['news_title']}</a><br><br> ";  //带id的标题超链接 上交到。php
                                }
                                ?></td></tr></table>
                </td></tr>
        </table>
        <!--显示用户的题目收藏列表-->
        我的题目收集
        <table border="1">
            <?php
            //查询用户收藏的题目ID
            $sql = "SELECT [QS_id] FROM [question_col] where user_ID = '{$_SESSION['user_ID']}'";
            $btmt = sqlsrv_query($conn, $sql);
            //查询用户收藏的题目内容
                while ($bow = sqlsrv_fetch_array($btmt, SQLSRV_FETCH_ASSOC)) {
                    $sql = "SELECT [QS_content] FROM [question] WHERE QS_id = '{$bow['QS_id']}'";
                    $ctmt = sqlsrv_query($conn, $sql);
                    while ($cow = sqlsrv_fetch_array($ctmt, SQLSRV_FETCH_ASSOC)) {
                        echo "<tr >";
                        echo "<td  width=500 style='text-align:center' ><a href='look_qs.php?id={$bow['QS_id']}'>{$cow['QS_content']}</a><br> </td>";
                        echo "</tr>";
                    }
                }
            ?> 
        </table>
    </center>
</body>
<?php
//清空并关闭连接
sqlsrv_free_stmt($btmt);
sqlsrv_free_stmt($ctmt);
sqlsrv_close($conn);
?>
</html>
