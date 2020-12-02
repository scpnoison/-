
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
        <title>网站通知</title>
         <style>
            table{border-collapse:collapse;border:1px solid #000;background-color:rgba(255,255,255,0.5)}
            td{white-space:nowrap}
        </style>
    </head>
    <body class="body2">
        <?php
        include("menu.php");//导入标题栏
        include("lianjie.php");//导入数据库连接语句
        //查询通知
         $sql = "SELECT [news_id],[news_title],[news_time] FROM [news] where news_Types = '2'";
                    $atmt = sqlsrv_query($conn, $sql);
                    if ($atmt === false) {
                        die(print_r(sqlsrv_errors(), true));
                    }
        ?>
        <!--插入轮播图-->
    <center>
         <table  style="width:1000px;height:210"  class="div-a">
                    <tr>
                        <td >   
                            <iframe name="b1" src="lunbo.html" width="1000" height="210" style=""></iframe>
                        </td>
                    </tr>
                </table>
        <div class="app" style="width:1010px; height:800px;">
            <div style="display:inline-block;margin-left:10px;">
                <table  style="width:800px;height:790px;border-spacing: 10px 1px" >通知
                     <tr align=center  ><td  width=500px valign="top">
                    <?php
                    //自动显示通知标题超链接
                   
                    while ($aow = sqlsrv_fetch_array($atmt, SQLSRV_FETCH_ASSOC)) {
                       
                        echo "<a href='look_news.php?id={$aow['news_id']}'>{$aow['news_title']}</a>  {$aow['news_time']}<br><br>";  //带id的标题超链接 上交到。php
                      
                    }
                    ?>  </td></tr>
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
