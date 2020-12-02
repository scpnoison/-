
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
        <title>学习页面</title>
        <style>
            table{border-collapse:collapse;border:1px solid #000;background-color:rgba(255,255,255,0.5)}
            td{text-align:center;}
        </style>
    </head>
    <body class="body2">
        <?php
        include("menu.php"); //导入标题栏
        include("lianjie.php"); //导入数据库连接语句
        //如果访问没设置章节ID 默认为1
        if (!isset($_SESSION['user_ID'])) { //没登录
            echo "<center>请先<a href='index.php'>登录</a></center>";
            exit();
        }
        //订阅课程
        if (isset($_GET['dykc'])) {
            //  检查是否订阅过
            $sql = "SELECT [course_ID] FROM [course_sub] WHERE user_ID = '{$_SESSION['user_ID']}' and course_ID ='{$_GET['dykc']}'";
            $Ztmt = sqlsrv_query($conn, $sql);
            $zow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC);
            sqlsrv_free_stmt($Ztmt);
            //没有订阅过则添加订阅
            if ($zow == NULL) {
                $sql = "INSERT INTO [course_sub] (user_ID,course_ID) VALUES('{$_SESSION['user_ID']}','{$_GET['dykc']}')";
                $Ztmt = sqlsrv_query($conn, $sql);
                sqlsrv_free_stmt($Ztmt);
            } else { //已经订阅
                echo "<center>您已添加过此课程 <a href='javascript:history.back(-1);'>返回</a></center>";
                exit();
            }
        }
        //如果没有设置显示哪个章节，则查找第零章节的ID用于传递
        if (!isset($_GET['CH_id'])) {
            //查找第零章的ID
            $sql = "SELECT [CH_id] FROM [chapter] where CH_course_ID = '{$_GET['id']}' and CH_no = 0 ";
            $Xtmt = sqlsrv_query($conn, $sql);
            $KKow = sqlsrv_fetch_array($Xtmt, SQLSRV_FETCH_ASSOC);
            sqlsrv_free_stmt($Xtmt);
        } else {
            $KKow['CH_id'] = $_GET['CH_id'];
        }
        //查询章节名称
        $sql = "SELECT [course_name] FROM [course] where course_ID = '{$_GET['id']}'";
        $Xtmt = sqlsrv_query($conn, $sql);
        $kow = sqlsrv_fetch_array($Xtmt, SQLSRV_FETCH_ASSOC);
        sqlsrv_free_stmt($Xtmt);
        //查询章节的内容
        $sql = "SELECT [CH_name],[CH_content],[CH_no] FROM [chapter] where CH_ID = '{$KKow['CH_id']}'";
        $Xtmt = sqlsrv_query($conn, $sql);
        $cow = sqlsrv_fetch_array($Xtmt, SQLSRV_FETCH_ASSOC);
        sqlsrv_free_stmt($Xtmt);
        ?>


        <?php
        //预先查询下一章的ID 给now
        $CH_next_NO = $cow['CH_no'] + 1;
        $sql = "SELECT [CH_id] FROM [chapter] where CH_course_ID ='{$_GET['id']}' and CH_no='$CH_next_NO'";
        $Ztmt = sqlsrv_query($conn, $sql);
        $now = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC);
        sqlsrv_free_stmt($Ztmt);
        ?>
        <?php
        //预先查询上一章的ID 给pow
        $CH_prior_NO = $cow['CH_no'] - 1;
        $sql = "SELECT [CH_id] FROM [chapter] where CH_course_ID ='{$_GET['id']}' and CH_no='$CH_prior_NO'";
        $Ztmt = sqlsrv_query($conn, $sql);
        $pow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC);
        sqlsrv_free_stmt($Ztmt);
        ?>
        <?php
        //查询该课程最后一章的内编号
        $sql = "SELECT top 1 [CH_no] FROM [chapter] where CH_course_ID = '{$_GET['id']}' order by [CH_no] desc ";
        $Ztmt = sqlsrv_query($conn, $sql);
        $fow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC);
        sqlsrv_free_stmt($Ztmt);
        $CH_max_NO = $fow['CH_no'];
        ?>
    <center>
        <table  border="1" width=1200 height=800 cellspacing="10px" cellpadding="20px">
            <tr><td>
                    <div style="width: 100%;height: 100%">
                        <table border = "1" style="width:100%;height:100%;">
                            <tr>
                                <td>
                                    <!--显示课程信息和订阅按钮-->
                            <center><?php echo"{$kow['course_name']}"; ?>
                                <?php
                                $sql = "SELECT [course_ID] FROM [course_sub] WHERE user_ID = '{$_SESSION['user_ID']}' and course_ID ='{$_GET['id']}'";
                                $Ztmt = sqlsrv_query($conn, $sql);
                                $ppow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC); 
                                sqlsrv_free_stmt($Ztmt);
                                if ($ppow !== NULL) { //已经订阅
                                    echo "<br> 您已订阅此课程";
                                } else { //没订阅
                                    echo "<br><a href='study.php?dykc={$_GET['id']}&id={$_GET['id']}'><input type='button' value='订阅课程' class='button green small'></a>";
                                }
                                ?>
                            </center>
                        </td>
                        </tr>
                            <?php
                            //自动生成章节超链接
                            //查询相关章节的ID
                            $sql = "SELECT [CH_id] FROM [chapter] where CH_course_ID = '{$_GET['id']}'"; //根据传来的课程ID查询所有章节ID 
                            $atmt = sqlsrv_query($conn, $sql);
                            if ($atmt === false) {
                                die(print_r(sqlsrv_errors(), true));
                            } else { //查询相关章节的name
                                while ($aow = sqlsrv_fetch_array($atmt, SQLSRV_FETCH_ASSOC)) {
                                    $sql = "SELECT [CH_name] FROM [chapter] WHERE CH_id = '{$aow['CH_id']}'"; //根据章节ID 查询章节名称
                                    $btmt = sqlsrv_query($conn, $sql);
                                   
                                    while ($bow = sqlsrv_fetch_array($btmt, SQLSRV_FETCH_ASSOC)) {
                                        echo "<tr>";
                                        echo "<td  style='text-align:center'><a href='study.php?id={$_GET['id']}&CH_id={$aow['CH_id']}'>{$bow['CH_name']}</a><br> </td>";
                                        echo "</tr>";
                                    }
                                }
                            }
                            ?> 
                        </table>
                    </div>
                </td>
                <td  width="70%" style="text-align: left;" valign=top>
                   
                    <table border="1" width="100%" height="100%">
                        <tr style="height:30px"><td colspan="2" style="font-size:22px"> <?php echo "{$cow['CH_name']}"; ?></td></tr>
                        <tr><td width="50%"><?php
                                //自动显示上一页 下一页按钮
                                if ($CH_prior_NO <= 0) {
                                    
                                } else {
                                    echo "<a href='study.php?id={$_GET['id']}&CH_id={$pow['CH_id']}'><input type='button'class='button gray small' value='上一章'></a> ";
                                }
                                ?> </td><td width="50%">
                                <?php
                                if ($CH_next_NO > $CH_max_NO) {
                                    
                                } else {
                                    echo "<a href='study.php?id={$_GET['id']}&CH_id={$now['CH_id']}'><input type='button'class='button gray small' value='下一章'></a> ";
                                }
                                ?>
                            </td></tr>
                        <tr> <!--显示章节内容-->
                            <td colspan="2" width="100%" height="95%" valign=top> <?php echo "{$cow['CH_content']}"; ?></td></tr>
                    </table>
                </td></tr>
        </table>
    </center>
</body>
<?php
//清空并关闭连接
sqlsrv_free_stmt($atmt);
sqlsrv_free_stmt($btmt);
sqlsrv_close($conn);
?>
</html>
