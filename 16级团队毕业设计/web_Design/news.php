
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
        <title>新闻主页</title>
        <style>
            table{border-collapse:collapse;border:1px solid #000;background-color:rgba(255,255,255,0.5)}
            td{white-space:nowrap}
        </style>
    </head>
    <body class="body2">
        <?php
        include("menu.php");//导入标题栏
        include("lianjie.php");//导入数据库连接语句
        //查询新闻
        $sql = "SELECT [news_id],[news_title],[news_time] FROM [news] where news_Types = '1'";
        $atmt = sqlsrv_query($conn, $sql);
        if ($atmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        ?>
    <center>
        <!--新闻轮播图-->
         <table  style="width:1000px;height:210"  class="div-a">
                    <tr>
                        <td >   
                            <iframe name="b1" src="lunbo.html" width="1000" height="210" style=""></iframe>
                        </td>
                    </tr>
                </table>
        <div class="app" style="width:1010px; min-height:800px;">
            <div style="display:inline-block;">
                
                <table style="width:800px;min-height:790px">新闻
                    <tr><td width=500 align=center valign=top>
                    <?php
                    //显示新闻标题链接
                    while ($aow = sqlsrv_fetch_array($atmt, SQLSRV_FETCH_ASSOC)) {
                        echo " <a href='look_news.php?id={$aow['news_id']}'>{$aow['news_title']}</a> {$aow['news_time']}<br><br>";  
                    }
                    ?> 
                    </td></tr>
                </table>
            </div>
        </div>
    </center>
    <?php
//清空并关闭连接
    sqlsrv_free_stmt($atmt);
    sqlsrv_close($conn);
    ?>
</body>
</html>
