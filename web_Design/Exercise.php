<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
        <title>练习</title>
        <style>
            table{border-collapse:collapse;border:1px solid #000;background-color:rgba(255,255,255,0.5)}
            input{width:100px}
        </style>
    </head>
    <?php
    include("menu.php"); //导入标题栏
    include("lianjie.php"); //导入数据库连接语句
    if (!isset($_SESSION['user_ID'])) { //没登录
        echo "<center>请先<a href='index.php'>登录</a></center>";
        exit();
    }
    if (!isset($_GET['QS_id'])) { //如果没有设置问题ID，则初始化变量
        $_GET['QS_id'] = "0";
    }
//根据传来的题组ID 查找题组内全部编号
    $sql = "SELECT [QS_tizu_ID],[QS_id],[QS_NO],[QS_CA] FROM [question] where QS_tizu_ID = '{$_GET['id']}' order by [QS_NO]";
    $atmt = sqlsrv_query($conn, $sql);
    ?>
    <?php
    //收藏题目
    if (isset($_GET['tjtm'])) {
        if (!isset($_SESSION['user_ID'])) { //检测是否已经登录
            echo "<center>请先<a href='index.php'>登录</a></center>";
            exit();
        } else { //登录了
            //检查是否已经收藏
            $sql = "SELECT [QS_id] FROM [question_col] WHERE user_ID = '{$_SESSION['user_ID']}' and QS_id ='{$_GET['tjtm']}'";
            $Ktmt = sqlsrv_query($conn, $sql);
            $xxow = sqlsrv_fetch_array($Ktmt, SQLSRV_FETCH_ASSOC);
            sqlsrv_free_stmt($Ktmt);
            if ($xxow !== NULL) {
                //已经收藏过
                echo "<center>您已收藏过这道题 <a href='javascript:history.back(-1);'>返回</a></center>";
                exit();
            } else {
                //没有收藏过则添加收藏
                $sql = "INSERT INTO [question_col] (user_ID,QS_id) VALUES('{$_SESSION['user_ID']}','{$_GET['tjtm']}')";
                $Ktmt = sqlsrv_query($conn, $sql);
                sqlsrv_free_stmt($Ktmt);
            }
        }
    }
    ?>
    <?php
    //向数据库添加用户做题记录
    if (isset($_GET['choose'])) {
        $sql = "SELECT [QS_done_id] FROM [user_QS_done] WHERE user_ID = '{$_SESSION['user_ID']}' and QS_done_id ='{$_GET['QS_id']}'";
        $zatmt = sqlsrv_query($conn, $sql);
        $zbow = sqlsrv_fetch_array($zatmt, SQLSRV_FETCH_ASSOC);
        if ($zbow == NULL) {
            $sql = "INSERT INTO [user_QS_done] (user_ID,QS_done_id,user_choose) VALUES('{$_SESSION['user_ID']}','{$_GET['QS_id']}','{$_GET['choose']}')";
            $zatmt = sqlsrv_query($conn, $sql);
            sqlsrv_free_stmt($zatmt);
        }
    }
    ?>
    <?php
    //重置用户的题组做题记录
    if (isset($_GET['reset'])) {
        $sql = "SELECT [QS_id] FROM [question] where QS_tizu_ID = '{$_GET['id']}'";
        $zctmt = sqlsrv_query($conn, $sql);
        while ($zcow = sqlsrv_fetch_array($zctmt, SQLSRV_FETCH_ASSOC)) {
            $sql = "DELETE FROM [user_QS_done] WHERE user_ID = '{$_SESSION['user_ID']}' and QS_done_id= '{$zcow['QS_id']}'";
            $zdtmt = sqlsrv_query($conn, $sql);
            sqlsrv_free_stmt($zdtmt);
        }
        sqlsrv_free_stmt($zctmt);
    }
    ?>
    <?php
    if ($_GET['QS_id'] == 0) {
        //如果没有设置显示哪道题，则查找第一题的ID用于传递
        $sql = "SELECT [QS_id] FROM [question] where QS_tizu_ID = '{$_GET['id']}' and QS_NO = '1' ";
        $XXtmt = sqlsrv_query($conn, $sql);
        $KXKow = sqlsrv_fetch_array($XXtmt, SQLSRV_FETCH_ASSOC);
        sqlsrv_free_stmt($XXtmt);
    } else { //否侧传递问题id
        $KXKow['QS_id'] = $_GET['QS_id'];
    }
    //根据传来的题目ID 查找题目内容
    $sql = "SELECT * FROM [question] where QS_id = '{$KXKow['QS_id']}'";
    $btmt = sqlsrv_query($conn, $sql);
    $bow = sqlsrv_fetch_array($btmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($btmt);
    ?>
    <?php
    //根据传来的题组ID 查询题组的名称
    $sql = "SELECT [tizu_name] FROM [tizu] where tizu_ID ='{$_GET['id']}'";
    $ctmt = sqlsrv_query($conn, $sql);
    $cow = sqlsrv_fetch_array($ctmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($ctmt);
    ?>    
    <?php
    //预先查询下一道题的ID 
    $QS_next_NO = $bow['QS_NO'] + 1;
    $sql = "SELECT [QS_id] FROM [question] where QS_tizu_id ='{$_GET['id']}' and QS_NO='$QS_next_NO'";
    $dtmt = sqlsrv_query($conn, $sql);
    $dow = sqlsrv_fetch_array($dtmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($dtmt);
    ?>
    <?php
    //预先查询上一道题的ID 
    $QS_prior_NO = $bow['QS_NO'] - 1;
    $sql = "SELECT [QS_id] FROM [question] where QS_tizu_id ='{$_GET['id']}' and QS_NO='$QS_prior_NO'";
    $etmt = sqlsrv_query($conn, $sql);
    $eow = sqlsrv_fetch_array($etmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($etmt);
    ?>
    <?php
    //查询该题组最后一道题的内编号
    $sql = "SELECT top 1 [QS_NO] FROM [question] where QS_tizu_ID = '{$_GET['id']}' order by [QS_NO] desc ";
    $ftmt = sqlsrv_query($conn, $sql);
    $fow = sqlsrv_fetch_array($ftmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($ftmt);
    $QS_max_NO = $fow['QS_NO'];
    ?>
    <?php
    //查询此题的是否完成,选择的什么
    $sql = "SELECT [QS_done_id],[user_choose] FROM [user_QS_done] where user_ID ='{$_SESSION['user_ID']}' and QS_done_id ='{$bow['QS_id']}'";
    $zatmt = sqlsrv_query($conn, $sql);
    $zaow = sqlsrv_fetch_array($zatmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($zatmt);
    ?>
    <body class="body2">  
    <center>
        <table  border="1" width=1200 height=800 cellspacing="10px" cellpadding="20px">
            <tr>
                <td width="20%" style="text-align:center"><?php echo "{$cow['tizu_name']}"; ?><br>
                    <?php
                    //自动显示收藏按钮
                    //  检查是否已经登录
                    if (!isset($_SESSION['user_ID'])) { 
                        echo "<a href='index.php'><br><input type='button' value='登录后可订阅本题组' class='button gray small'></a>";
                    } else {
                        $sql = "SELECT [tizu_ID] FROM [tizu_sub] WHERE user_ID = '{$_SESSION['user_ID']}' and tizu_ID ='{$_GET['id']}'";
                        $Ptmt = sqlsrv_query($conn, $sql);
                        $ppow = sqlsrv_fetch_array($Ptmt, SQLSRV_FETCH_ASSOC); 
                        sqlsrv_free_stmt($Ptmt);
                        if ($ppow !== NULL) { //已经订阅
                            echo "<br>您已订阅此题组";
                        } else { //没订阅
                            echo "<br><a href='tizu.php?dytz={$bow['QS_tizu_ID']}'><input type='button' value='订阅题组' class='button green small'></a>";
                        }
                    }
                    //自动显示重置做题情况按钮
                    echo "<br><a href='Exercise.php?id={$bow['QS_tizu_ID']}&QS_id={$bow['QS_id']}&reset=1'><input type='button' class='button gray small' value='重置做题记录'></a>";
                    ?>
                </td>
                <td rowspan="3" width="80%" >
                    <table width="100%" height="100%" border="1">
                        <?php
                        //输出选项，包含提交做题息信息的超链接
                        echo "<tr><td colspan='3' style='padding-left:30px'> {$bow['QS_content']}</td></tr>";
                        if ($bow['QS_Types'] != '问答题' && $bow['QS_NO'] != '0') {
                            echo "<tr><td colspan='3' style='padding-left:30px'>选项A.<a href ='Exercise.php?id={$bow['QS_tizu_ID']}&QS_id={$bow['QS_id']}&choose=<p>A<br></p>'>{$bow['QS_A']}</a> </td></tr>";
                            echo " <tr><td colspan='3' style='padding-left:30px'>选项B.<a href ='Exercise.php?id={$bow['QS_tizu_ID']}&QS_id={$bow['QS_id']}&choose=<p>B<br></p>'>{$bow['QS_B']}</a> </td></tr>";
                            echo "<tr><td colspan='3' style='padding-left:30px'>选项C.<a href ='Exercise.php?id={$bow['QS_tizu_ID']}&QS_id={$bow['QS_id']}&choose=<p>C<br></p>'>{$bow['QS_C']}</a> </td></tr>";
                            echo "<tr><td colspan='3'style='padding-left:30px'>选项D.<a href ='Exercise.php?id={$bow['QS_tizu_ID']}&QS_id={$bow['QS_id']}&choose=<p>D<br></p>'>{$bow['QS_D']}</a> </td></tr>";
                        } else if ($bow['QS_NO'] != '0') {
                            echo "<tr><td colspan='3'style='padding-left:30px'><a href ='Exercise.php?id={$bow['QS_tizu_ID']}&QS_id={$bow['QS_id']}&choose=AA'>点击显示答案</a> </td></tr>";
                        }
                        ?>
                        <tr><td colspan="3" style='padding-left:30px;display:none' id="zqjx" >解析:<?php echo "{$bow['QS_jiexi']}"; ?></td></tr>
                        <tr><td colspan="3" style='padding-left:30px;display:none;color:#1e88e5' id="zqda" >正确答案:<?php echo "{$bow['QS_CA']}"; ?></td></tr>
                        <?php
                        //如果做过 显示答案和解析 用户的选择
                        if ($zaow != NULL) {
                            echo "<script type='text/javascript'>window.onload=function(){xs();}; </script>"; //自动调用函数显示解析和答案
                            if ($zaow['user_choose'] != 'AA') {
                                if ($zaow['user_choose'] == $bow['QS_CA']) {
                                    $textcolor = '#1e88e5';
                                } else {
                                    $textcolor = 'red';
                                }
                                echo "<tr><td colspan='3'style='padding-left:30px;color:$textcolor'>您的选择:{$zaow['user_choose']}</td></tr>";
                            }
                        }
                        ?>
                        <tr>
                            <td width="33%">
                                <?php
                                //自动显示上一页 下一页按钮
                                if ($QS_prior_NO <= 0) {
                                    
                                } else {
                                    echo "<a href='Exercise.php?id={$bow['QS_tizu_ID']}&QS_id={$eow['QS_id']}'><input type='button'class='button gray small' value='上一题'></a> ";
                                }
                                ?> 
                            </td>
                            <td width="33%">
                                <?php
                                //下一页按钮
                                if ($QS_next_NO > $QS_max_NO) {
                                    
                                } else {
                                    echo "<a href='Exercise.php?id={$bow['QS_tizu_ID']}&QS_id={$dow['QS_id']}'><input type='button'class='button gray small' value='下一题'></a> ";
                                }
                                ?>
                            </td>
                            <td width="33%"><?php
                                //自动显示收藏按钮
                                if (!isset($_SESSION['user_ID'])) { //没登录
                                    echo "<td><a href='index.php'><input type='button' value='登录后可收藏本题'></a></td></tr>";
                                } else {
                                    $sql = "SELECT [QS_id] FROM [question_col] WHERE user_ID = '{$_SESSION['user_ID']}' and QS_id ='{$bow['QS_id']}'";
                                    $Ptmt = sqlsrv_query($conn, $sql);
                                    $ppow = sqlsrv_fetch_array($Ptmt, SQLSRV_FETCH_ASSOC);
                                    sqlsrv_free_stmt($Ptmt);
                                    if ($ppow !== NULL) { //已经收藏
                                        echo "已收藏这道题";
                                    } else { //没收藏
                                        echo "<a id='sct' style='display:none;' href='Exercise.php?id={$_GET['id']}&QS_id={$bow['QS_id']}&tjtm={$bow['QS_id']}'><input type='button' value='收藏这道题'></a>";
                                    }
                                }
                                ?>
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>
            <tr>
                <td > 
                    <table border="1" width="100%" >
                        <?php
                        //输出题号表格，根据做题的对错自动改变框体背景颜色
                        $i = 0; //用于控制换行
                        echo "<tr style='height:30px'>";
                        //输出题目ID 的超链接
                        while ($aow = sqlsrv_fetch_array($atmt, SQLSRV_FETCH_ASSOC)) {
                            if ($aow['QS_NO'] == '0') {
                                continue;
                            }//查询这道题 如果做过改变表格颜色
                            $jgys = ""; //用于保存背景颜色的变量
                            $sql = "select [QS_done_id],[user_choose] from [user_QS_done] where QS_done_ID = {$aow['QS_id']} and user_ID = '{$_SESSION['user_ID']}'";
                            $Ztmt = sqlsrv_query($conn, $sql);
                            $zow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC);
                            if ($zow != NULL) { //用户做了
                                //如果选对 是蓝色的 
                                if ($aow['QS_CA'] == $zow['user_choose']) {
                                    $jgys = "#1e88e5";
                                } else if ($zow['user_choose'] == 'AA') {
                                    $jgys = "#1e88e5";
                                }
                                //选错是红色的
                                else {
                                    $jgys = "#CD5555";
                                }
                            }
                            sqlsrv_free_stmt($Ztmt);
                            //输出题号表格
                            if ($i < 5) {
                                echo "<td style='text-align:center;background-color:$jgys'><a href='Exercise.php?id={$aow['QS_tizu_ID']}&QS_id={$aow['QS_id']}'>{$aow['QS_NO']}</a></td>";
                                $i = $i + 1;
                                //输出题目ID超链接 i++
                            } else {
                                echo "</tr>";
                                echo "<tr style='height:30px'>";
                                $i = 1;
                                echo "<td style='text-align:center;background-color:$jgys'><a href='Exercise.php?id={$aow['QS_tizu_ID']}&QS_id={$aow['QS_id']}'>{$aow['QS_NO']}</a></td>";
                                //换行 i = 1
                            }
                        }
                        echo "</tr>";
                        ?>
                    </table>
                </td></tr>
            <?php
//完成度计算
//查询当前目总数
            $sql = "select count(QS_id) as jishu from [question] where QS_tizu_ID = '{$_GET['id']}' ";
            $Ztmt = sqlsrv_query($conn, $sql);
            $zow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC);
            $timuzongshu = $zow['jishu'] - 1; //题目总数
            sqlsrv_free_stmt($Ztmt);
            //查询完成的数量
            $sql = "SELECT [QS_id] FROM [question] where QS_tizu_ID = '{$_GET['id']}'";
            $Ztmt = sqlsrv_query($conn, $sql);
            $yztmsl = 0;
            while ($zow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC)) {
                $sql = "select [QS_done_id] from [user_QS_done] where QS_done_ID = {$zow['QS_id']} and user_ID = '{$_SESSION['user_ID']}'";
                $ZBBtmt = sqlsrv_query($conn, $sql);
                $bbbow = sqlsrv_fetch_array($ZBBtmt, SQLSRV_FETCH_ASSOC);
                if ($bbbow != NULL) {
                    $yztmsl = $yztmsl + 1; //循环统计完成题数
                }
                sqlsrv_free_stmt($ZBBtmt);
            }

            sqlsrv_free_stmt($Ztmt);
            ?>
            <tr>
                <td>
                    <table width="100%" height="100%">
                        <tr><td>当前第 <?php echo $bow['QS_NO'] ?> 题</td></tr>
                        <tr><td>完成度: <?php echo "$yztmsl/$timuzongshu " ?></td></tr>

                    </table>
                </td>
            </tr>
        </table>
    </center>
</body>
<?php
//清空并关闭连接
sqlsrv_free_stmt($atmt);
sqlsrv_close($conn);
?>
<script type="text/javascript">
//    用于显示解析和答案收藏按钮的脚本
    function xs() {
        document.getElementById("zqda").style.display = "table-cell";
        document.getElementById("zqjx").style.display = "table-cell";
        document.getElementById("sct").style.display = "block";
    }
</script>
</html>
