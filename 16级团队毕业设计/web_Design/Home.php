
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
        <title>主页</title>
        <style>
            table{border-collapse:collapse;border:1px solid #000;background-color:rgba(255,255,255,0.5)}
/*            显示轮播图的表格样式*/
            .mytable{ border-collapse:collapse;border:0px solid #000;background-color:rgba(255,255,255,0)} 
            td{white-space:nowrap}
        </style>
    </head>
    <body class="body2">
        <?php
        
        include("menu.php");//导入标题栏
        include("lianjie.php");//导入数据库连接语句
        //查询6条课程推荐
        $sql = "SELECT top 6 [course_ID],[course_name] FROM [course] ";
        $atmt = sqlsrv_query($conn, $sql);
        if ($atmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        //查询6条习题推荐
        $sql = "SELECT top 6 [tizu_ID],[tizu_name] FROM [tizu] ";
        $btmt = sqlsrv_query($conn, $sql);
        if ($btmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        //查询6条新闻推荐
        $sql = "SELECT top 6 [news_id],[news_title],[news_time] FROM [news] where news_Types = '1'";
        $ctmt = sqlsrv_query($conn, $sql);
        if ($ctmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        //查询6条通知
        $sql = "SELECT top 6 [news_id],[news_title],[news_time] FROM [news] where news_Types = '2'";
        $dtmt = sqlsrv_query($conn, $sql);
        if ($dtmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        ?>
        <div >
            <center>
<!--插入轮播图-->
                <table class="mytable" style="width:800px;height:400px"  >
                    <tr>
                        <td >   
                            <iframe  name="b1" src="新闻轮播.html" width="800" height="360"  frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling="no" allowtransparency="yes"></iframe>
                        </td>
                    </tr>
                </table>
                <br>
<!--                课程推荐框-->
                <div class="app" >
                    <div style="display:inline-block;" class="">
                        课程推荐
                        <table style="width:500px;height:280px;table-layout: fixed">
                            <tr><td  width=100% style='text-align:left;padding-left:15px;padding-top:20px ' class='dd' valign='top'>
                                    <?php
                                    //显示课程推荐
                                    while ($aow = sqlsrv_fetch_array($atmt, SQLSRV_FETCH_ASSOC)) {
                                        echo "<a href='study.php?id={$aow['course_ID']}'>{$aow['course_name']}</a><br> <br>";  //带id的标题超链接 上交到。php
                                    }
                                    ?>
                                </td></tr>
                        </table>
                    </div>
<!--                    习题推荐框-->
                    <div style="display:inline-block;margin-left:10px;" class="">
                        习题推荐
                        <table style="width:500px;height:280px">
                            <tr><td  width=500 style='text-align:left;padding-left:15px;padding-top:20px' class='dd' valign='top'>
                            <?php
                            //显示题组推荐
                            while ($bow = sqlsrv_fetch_array($btmt, SQLSRV_FETCH_ASSOC)) {
                                echo "<a href='Exercise.php?id={$bow['tizu_ID']}'>{$bow['tizu_name']}</a><br><br> ";  //带id的标题超链接 上交到。php
                            }
                            ?>    
                                    </td></tr>
                        </table>
                    </div>
                    
                </div>
                <br><br>
<!--                新闻推荐框-->
                <div class="app" >
                    <div style="display:inline-block;" class="">
                        新闻推荐
                        <table style="width:500px;height:280px;">
                            <tr><td  width=500 style='text-align:left;padding-left:15px;padding-top:20px' class='dd' valign='top' >
                                    <?php
                                    //显示新闻推荐

                                    while ($cow = sqlsrv_fetch_array($ctmt, SQLSRV_FETCH_ASSOC)) {
                                        echo "<a href='look_news.php?id={$cow['news_id']}'>{$cow['news_title']}</a>  {$cow['news_time']}<br><br>";  //带id的标题超链接 上交到。php
                                    }
                                    ?> 
                                </td></tr>
                        </table>
                    </div>
                    <!--网站通知框-->
                    <div style="display:inline-block;margin-left:10px;" class="">
                        网站通知
                        <table style="width:500px;height:280px">
                            <tr><td  width=500 style='text-align:left;padding-left:15px;padding-top:20px' class='dd' valign='top'>
                            <?php
                            //显示通知
                            while ($dow = sqlsrv_fetch_array($dtmt, SQLSRV_FETCH_ASSOC)) {
                                echo "<a href='look_news.php?id={$dow['news_id']}'>{$dow['news_title']}</a>   {$dow['news_time']}<br><br>";  //带id的标题超链接 上交到。php
                            }
                            ?>   
                                </td></tr>
                        </table>
                    </div>
                </div>  
                <?php
//清空并关闭连接
                sqlsrv_free_stmt($atmt);
                sqlsrv_free_stmt($ctmt);
                sqlsrv_free_stmt($btmt);
                sqlsrv_free_stmt($dtmt);
                sqlsrv_close($conn);
                ?>
            </center>
        </div>
    </body>
</html>