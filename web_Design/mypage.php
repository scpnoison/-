
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
        <title>个人中心</title>
        <style>
            table{border-collapse:collapse;border:1px solid #000;background-color:rgba(255,255,255,0.5)}
            input{width:100px}
            .mytd2{text-align: center}
        </style>
    </head>
    <?php
    include("menu.php"); //导入标题栏
    include("lianjie.php"); //导入数据库连接语句
    //若没登录则拒绝访问
    if (!isset($_SESSION['user_name'])) {
        exit('非法访问!');
    }
    // 查询用户的信息
    $sql = "SELECT * FROM [user] WHERE user_ID = '{$_GET['id']}'";
    $atmt = sqlsrv_query($conn, $sql);
    if ($atmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    $aow = sqlsrv_fetch_array($atmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($atmt);
    ?>

    <?php
    //删除课程订阅
    if (isset($_GET['qdkc'])) {
        $sql = "DELETE FROM [course_sub] WHERE [course_ID] = '{$_GET['qdkc']}' and [user_ID]= '{$_SESSION['user_ID']}'";
        $Dtmt = sqlsrv_query($conn, $sql);
        if ($Dtmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        unset($_GET['qdkc']);
        sqlsrv_free_stmt($Dtmt);
    }
    ?>
    <?php
    //删除题组订阅
    if (isset($_GET['qdtz'])) {
        $sql = "DELETE FROM [tizu_sub] WHERE [tizu_ID] = '{$_GET['qdtz']}' and [user_ID]= '{$_SESSION['user_ID']}'";
        $Ztmt = sqlsrv_query($conn, $sql);
        if ($Ztmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        unset($_GET['qdtz']);
        sqlsrv_free_stmt($Ztmt);
    }
    ?>
    <?php
    //删除习题收藏
    if (isset($_GET['scxt'])) {
        $sql = "DELETE FROM [question_col] WHERE [QS_id] = '{$_GET['scxt']}' and [user_ID]= '{$_SESSION['user_ID']}'";
        $Xtmt = sqlsrv_query($conn, $sql);
        if ($Xtmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        unset($_GET['scxt']);
        sqlsrv_free_stmt($Xtmt);
    }
    ?>
    <?php
    //删除新闻收藏
    if (isset($_GET['scxw'])) {
        $sql = "DELETE FROM [news_col] WHERE [news_id] = '{$_GET['scxw']}' and [user_ID]= '{$_SESSION['user_ID']}'";
        $Wtmt = sqlsrv_query($conn, $sql);
        if ($Wtmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        unset($_GET['scxw']);
        sqlsrv_free_stmt($Wtmt);
    }
    ?>
    <body class="body1">
    <center>
        <!--显示用户的信息和修改密码的链接-->
        <table id="b1" border="1" width="1200"height=600 cellspacing="10px" cellpadding="20px" >
            <tr>
                <td rowspan="2" style="width: 20%;">
                    <table border="1" style="width: 100%;height: 100%">
                        <tr><td style="text-align:center">用户名：</td><td class="mytd2"><?php echo $aow['user_name']; ?></td></tr>
                        <tr><td style="width: 45%;text-align:center">注册时间：</td><td class="mytd2"><?php ?></td></tr>
                        <tr><td style="text-align:center">手机号：</td><td class="mytd2"><?php echo $aow['user_phone']; ?></td></tr>
                        <tr><td colspan="2" style="text-align:center"><?php echo"<a href='javascript:xgmm()'>修改密码 </a>"; ?></td></tr>
                        <tr><td colspan="2" style="text-align:center"> <?php
                                if ($aow['user_ability'] == '2') {
                                    echo "<a href='manage.php?id={$_GET['id']}&action=course_gl'>管理网站</a>";
                                }
                                ?></td></tr>
                    </table>
                </td>
                <td width="40%"><div style="width: 450px;height: 100%;overflow: scroll;" >
                        <!--显示用户订阅的课程和操作按钮-->
                        <table border = "1" style="width:100%;height:100%;table-layout: fixed">
                            <center>订阅的课程</center>
                            <?php
                            //查询用户订阅的课程ID
                            $sql = "SELECT [course_ID] FROM [course_sub] where user_ID = '{$_GET['id']}'";
                            $btmt = sqlsrv_query($conn, $sql);
                            if ($btmt === false) {
                                die(print_r(sqlsrv_errors(), true));
                            } else { //查询用户订阅的课程名称
                                $bow = sqlsrv_fetch_array($btmt, SQLSRV_FETCH_ASSOC);
                                if ($bow == NULL) {
                                    echo "你还没有订阅课程";
                                } else {
                                    $sql = "SELECT [course_ID] FROM [course_sub] where user_ID = '{$_GET['id']}'";
                                    $btmt = sqlsrv_query($conn, $sql);
                                    while ($bow = sqlsrv_fetch_array($btmt, SQLSRV_FETCH_ASSOC)) {
                                        $sql = "SELECT [course_name] FROM [course] WHERE course_ID = '{$bow['course_ID']}'";
                                        $ctmt = sqlsrv_query($conn, $sql);
                                        if ($ctmt === false) {
                                            die(print_r(sqlsrv_errors(), true));
                                        }
                                        while ($cow = sqlsrv_fetch_array($ctmt, SQLSRV_FETCH_ASSOC)) {
                                            echo "<tr >";
                                            echo "<td style='text-align:center;font-size:20px;font-family:楷体;text-overflow:ellipsis;width:40%;overflow:hidden;white-space:nowrap'>{$cow['course_name']}</td>
                                        <td style='text-align:center'><a href='study.php?id={$bow['course_ID']}'><input type='button' value='继续学习' class='button blue small'></a> </td>
                                        <td style='text-align:center'><a href='mypage.php?id={$_SESSION['user_ID']}&qdkc={$bow['course_ID']}'><input type='button' value='取消订阅' class='button gray small'></a></td>";
                                            echo "</tr>";
                                        }
                                        sqlsrv_free_stmt($ctmt);
                                    }
                                }
                            }
                            sqlsrv_free_stmt($btmt);
                            ?> 
                        </table></div></td>
                <td><div style="width: 100%;height: 100%;overflow: scroll;">
                        <!--显示用户订阅的题组和操作按钮-->
                        <table border = "1" style="width:100%;height:100%;table-layout: fixed">
                            <center>订阅的题组</center>
                            <?php
                            //查询用户订阅的题组ID
                            $sql = "SELECT [tizu_ID] FROM [tizu_sub] where user_ID = '{$_GET['id']}'";
                            $dtmt = sqlsrv_query($conn, $sql);
                            if ($dtmt === false) {
                                //  echo "你还没有订阅题组";
                                die(print_r(sqlsrv_errors(), true));
                            } else { //查询用户订阅的题组名称
                                $dow = sqlsrv_fetch_array($dtmt, SQLSRV_FETCH_ASSOC);
                                if ($dow == NULL) {
                                    echo "你还没有订阅题组";
                                } else {
                                    $sql = "SELECT [tizu_ID] FROM [tizu_sub] where user_ID = '{$_GET['id']}'";
                                    $dtmt = sqlsrv_query($conn, $sql);
                                    while ($dow = sqlsrv_fetch_array($dtmt, SQLSRV_FETCH_ASSOC)) {
                                        $sql = "SELECT [tizu_name] FROM [tizu] WHERE tizu_ID = '{$dow['tizu_ID']}'";
                                        $etmt = sqlsrv_query($conn, $sql);
                                        if ($etmt === false) {
                                            die(print_r(sqlsrv_errors(), true));
                                        }
                                        while ($eow = sqlsrv_fetch_array($etmt, SQLSRV_FETCH_ASSOC)) {
                                            echo "<tr>";
                                            echo "<td  style='text-align:center;font-size:20px;font-family:楷体;text-overflow:ellipsis;overflow:hidden;width:45%;white-space:nowrap'>{$eow['tizu_name']}</td>
                                        <td style='text-align:center'><a href='Exercise.php?id={$dow['tizu_ID']}'><input type='button' value='继续练题' class='button blue small'></a> </td>
                                        <td style='text-align:center'><a href='mypage.php?id={$_SESSION['user_ID']}&qdtz={$dow['tizu_ID']}'><input type='button' value='取消订阅' class='button gray small'></a></td>";
                                            echo "</tr>";
                                        }
                                        sqlsrv_free_stmt($etmt);
                                    }
                                }
                            }
                            sqlsrv_free_stmt($dtmt);
                            ?> 
                        </table></div></td>
            </tr>
            <tr>
                <td><div style="width: 100%;height: 100%;overflow: scroll">
                        <!--显示用户收藏的问题和操作按钮-->
                        <table border = "1" style="width:100%;height:100%;table-layout: fixed">
                            <center>收藏的问题</center>
                            <?php
                            //查询用户收藏的题目ID
                            $sql = "SELECT [QS_id] FROM [question_col] where user_ID = '{$_GET['id']}'";
                            $ftmt = sqlsrv_query($conn, $sql);
                            if ($ftmt === false) {
                                die(print_r(sqlsrv_errors(), true));
                            } else { //查询用户收藏的题目内容
                                $fow = sqlsrv_fetch_array($ftmt, SQLSRV_FETCH_ASSOC);
                                if ($fow == NULL) {
                                    echo "你还没有收藏题目";
                                } else {
                                    $sql = "SELECT [QS_id] FROM [question_col] where user_ID = '{$_GET['id']}'";
                                    $ftmt = sqlsrv_query($conn, $sql);
                                    while ($fow = sqlsrv_fetch_array($ftmt, SQLSRV_FETCH_ASSOC)) {
                                        $sql = "SELECT [QS_content] FROM [question] WHERE QS_id = '{$fow['QS_id']}'";
                                        $gtmt = sqlsrv_query($conn, $sql);
                                        if ($gtmt === false) {
                                            die(print_r(sqlsrv_errors(), true));
                                        }
                                        while ($gow = sqlsrv_fetch_array($gtmt, SQLSRV_FETCH_ASSOC)) {
                                            echo "<tr>";
                                            echo "<td width=90% height=30px style='text-align:center;text-overflow:ellipsis;overflow:hidden'><a href='look_qs.php?id={$fow['QS_id']}'>{$gow['QS_content']}</a><br> </td>
                                        <td style='text-align:center;white-space:nowrap'><a href='mypage.php?id={$_SESSION['user_ID']}&scxt={$fow['QS_id']}'>删除</a></td>";
                                            echo "</tr>";
                                        }
                                        sqlsrv_free_stmt($gtmt);
                                    }
                                }
                            }
                            sqlsrv_free_stmt($ftmt);
                            ?> 
                        </table>
                    </div></td>
                <td><div style="width: 100%;height: 100%;overflow: scroll">
                        <!--显示用户收藏的新闻和操作按钮-->
                        <table border = "1" style="width:100%;height:100%;table-layout: fixed">
                            <center>收藏的文章</center>
                            <?php
                            //查询用户收藏的新闻ID
                            $sql = "SELECT [news_ID] FROM [news_col] where user_ID = '{$_GET['id']}'";
                            $htmt = sqlsrv_query($conn, $sql);
                            if ($htmt === false) {
                                // echo "你还没有收藏文章";
                                die(print_r(sqlsrv_errors(), true));
                            } else { //查询用户收藏的新闻内容
                                $how = sqlsrv_fetch_array($htmt, SQLSRV_FETCH_ASSOC);
                                if ($how == NULL) {
                                    echo "你还没有收藏文章";
                                } else {
                                    $sql = "SELECT [news_ID] FROM [news_col] where user_ID = '{$_GET['id']}'";
                                    $htmt = sqlsrv_query($conn, $sql);
                                    while ($how = sqlsrv_fetch_array($htmt, SQLSRV_FETCH_ASSOC)) {
                                        $sql = "SELECT [news_title] FROM [news] WHERE news_ID = '{$how['news_ID']}'";
                                        $itmt = sqlsrv_query($conn, $sql);
                                        if ($itmt === false) {
                                            die(print_r(sqlsrv_errors(), true));
                                        }
                                        while ($iow = sqlsrv_fetch_array($itmt, SQLSRV_FETCH_ASSOC)) {
                                            echo "<tr>";
                                            echo "<td  width=90% style='text-align:center;text-overflow:ellipsis;overflow:hidden'><a href='look_news.php?id={$how['news_ID']}'>{$iow['news_title']}</a><br> </td>
                                        <td style='text-align:center;white-space:nowrap'><a href='mypage.php?id={$_SESSION['user_ID']}&scxw={$how['news_ID']}'>删除</a></td>";
                                            echo "</tr>";
                                        }
                                        sqlsrv_free_stmt($itmt);
                                    }
                                }
                            }
                            sqlsrv_free_stmt($htmt);
                            ?> 
                        </table>

                    </div></td>
            </tr>
        </table>
    </center>
    <!--密码修改框-->
    <div id="xg" style="z-index:9999;display: none; position:fixed;border: 0px solid #000;right:35%;top: 70px;width: 500;height: 300;">
        <form name='frm1' id="frm_changepassword" method='post' action="changepassword.php?action=change">
            <table  style="background-color:white;" border="1" width="400" height="542">
                <tr align="center">
                    <td width="175">
                        旧密码
                    </td>
                    <td width="209">
                        <input name="old_password" type="password" id="tx1"> 
                    </td>
                </tr>
                <tr align="center">
                    <td>
                        新密码
                    </td>
                    <td>
                        <input name="new_password" type="password" id="tx2">
                    </td>
                </tr>
                <tr align="center">
                    <td>
                        重复新密码
                    </td>
                    <td>
                        <input type="password" id="tx3">
                    </td>
                </tr>
                <tr align="center">
                    <td height="160" >
                        <input type="button" value="修改密码" class='button blue small' onclick="qr()">
                    </td>
                    <td  align="center">
                        <input type="button" value="取消" class='button gray small' onclick="qxxg()">
                    </td>
                </tr>

            </table>
        </form>
    </div>
    <?php
//清空并关闭连接
    sqlsrv_close($conn);
    ?>
</body>
<script type="text/javascript">
//    显示密码修改框
    function xgmm() {
        document.getElementById("xg").style.display = "block";
    }
//    隐藏密码修改框
    function qxxg() {
        document.getElementById("xg").style.display = "none";
    }
//    检查输入情况
    function qr() {
        if (document.getElementById("tx1").value == "") {
            alert("请输入旧密码！");
        } else

        if (document.getElementById("tx2").value == "") {
            alert("请输入新密码！")
        } else

        if (document.getElementById("tx3").value == "") {
            alert("请再次输入密码");
        } else
        if (document.getElementById("tx2").value != document.getElementById("tx3").value) {
            alert("两次输入密码不一致！");
        }else{ var form1 = document.getElementById('frm_changepassword');
            form1.submit();}
    }
</script>
</html>
