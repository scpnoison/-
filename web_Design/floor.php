
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
        <title></title>
        <style>
            table{border-collapse:collapse;border:1px solid #000;background-color:rgba(255,255,255,0.8)}
            td{white-space:nowrap;text-align:center;}
            /*富文本工具栏样式*/
            .toolbar {
                border: 1px solid #ccc;
            }
        </style>
        <script type="text/javascript">
            //自动跳转登录界面脚本
            function qudenglu() {
                window.setTimeout("window.location='index.php'", 100);
            }
        </script>
        <?php
        include("menu.php"); //导入标题栏
        include("lianjie.php"); //导入数据库连接语句
        if (!isset($_SESSION['user_ID'])) { //检测是否已经登录
            echo "<center>请先<a href='index.php'>登录</a></center>";
            echo "<script type='text/javascript'> window.onload=function(){qudenglu();};</script>";
        } else {
            //查询用户权限
            $sql = "SELECT [user_ability] FROM [user] where user_ID = '{$_SESSION['user_ID']}'";
            $Ztmt = sqlsrv_query($conn, $sql);
            $yhqx = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC); //记录用户权限
            sqlsrv_free_stmt($Ztmt);
        }
        ?>
        <?php
        //查询回复id编号
        $sql = "SELECT top 1 [floor_id] FROM [floor] order by [floor_id] desc  ";
        $Ztmt = sqlsrv_query($conn, $sql);
        $Zow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC);
        $new_floor_id = $Zow['floor_id'] + 1; //新的楼层ID号
        sqlsrv_free_stmt($Ztmt);


        //查询楼层最大编号
        $sql = "SELECT top 1 [floor_no] FROM [floor] where floor_tz_id ={$_GET['tz_id']} order by [floor_no] desc  ";
        $Ztmt = sqlsrv_query($conn, $sql);
        $Zow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC);
        $new_floor_no = $Zow['floor_no'] + 1; //新的楼层ID号
        sqlsrv_free_stmt($Ztmt);
        //添加回复------------------------------------------------
        $newtime = date('Y-m-d H:i:s', time()); //获得当前时间
        //查询帖子的回复数量$ABow
        $sql = "SELECT [post_renum] FROM [post] WHERE post_id = '{$_GET['tz_id']}'";
        $Ztmt = sqlsrv_query($conn, $sql);
        $ABow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC);
        $renum = $ABow['post_renum']; //当前回复数
        if (isset($_GET['action'])) {
            if ($_GET['action'] == 're') { //添加帖子 //添加楼层
                $sql = "INSERT INTO [floor] (floor_id,floor_tz_id,floor_user_id,floor_content,floor_time,floor_no) VALUES($new_floor_id,{$_GET['tz_id']},'{$_SESSION['user_ID']}',?,?,$new_floor_no)";
                $comments1 = "{$_POST['tz_re_text']}";
                $comments2 = "{$newtime}";
                //给予中文内容 转码再排序成数组
                $params1 = array(array($comments1, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8')), array($comments2, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8'))); //把字符串放入数组变量并且转码utf-8
                $Ztmt = sqlsrv_query($conn, $sql, $params1);
                sqlsrv_free_stmt($Ztmt); //清理
                $renum = $renum + 1; //增加回复数
                $sql = "update [post] set [post_renum] = $renum where post_id = '{$_GET['tz_id']}' ";
                $Ztmt = sqlsrv_query($conn, $sql);
                sqlsrv_free_stmt($Ztmt);
            }
        }
        ?>
        <?php
        //删除楼层
        if (isset($_GET['lc_del_id'])) {
//            $sql = "DELETE FROM [post] WHERE [post_id]= '{$_GET['lc_del_id']}'";
//            $Ztmt = sqlsrv_query($conn, $sql); //执行
            $sql = "DELETE FROM [floor] WHERE [floor_id]= '{$_GET['lc_del_id']}'";
            $Ztmt = sqlsrv_query($conn, $sql); //执行
            $renum = $renum - 1; //减少回复数
            $sql = "update [post] set [post_renum] = $renum where post_id = '{$_GET['tz_id']}' ";
            $Ztmt = sqlsrv_query($conn, $sql);
            sqlsrv_free_stmt($Ztmt);
        }
        ?>

        <?php
        //查询帖子的信息$AAow
        $sql = "SELECT * FROM [post] WHERE post_id = '{$_GET['tz_id']}'";
        $Ztmt = sqlsrv_query($conn, $sql);
        $AAow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC);

        //查询显示帖子楼层
        $sql = "SELECT * FROM [floor] where floor_tz_id={$_GET['tz_id']} order by [floor_no]";
        $atmt = sqlsrv_query($conn, $sql);

        sqlsrv_free_stmt($Ztmt);
        ?>

    </head>
    <body class="body1">
    <center>
        <!--显示回复和返回论坛按钮-->
        <table style="width:1000px;min-height:50px;">
            <tr>
                <td style="text-align: left;padding-left: 20px;">
                    <input type="button" value="回复" class="button blue big" onclick="xstz_re()" >&nbsp;&nbsp;
                    <a href="forum.php"><input type="button" style="width:150px" value="返回论坛" class="button blue big" ></a>&nbsp;&nbsp;
                </td>
            </tr>
        </table>
        <!--显示帖子主题和时间-->
        <table border="1"style="width:1000px;min-height:200px;">
            <tr style="height:30px"><td width="20%"><?php echo $AAow['post_time'] ?></td><td><?php echo $AAow['post_title'] ?></td></tr>
            <?php
            
            while ($aow = sqlsrv_fetch_array($atmt, SQLSRV_FETCH_ASSOC)) {
                $sql = "SELECT [user_name] FROM [user] WHERE user_ID = '{$aow['floor_user_id']}' ";
                $btmt = sqlsrv_query($conn, $sql);
                $bow = sqlsrv_fetch_array($btmt, SQLSRV_FETCH_ASSOC);
                echo "<tr style='min-height:200px'>";
                echo "<td>{$bow['user_name']}<br><br>{$aow['floor_no']}楼<br><br>";

                if ($yhqx['user_ability'] == '2') {
                    if ($aow['floor_no'] == '1') {
                        echo "<a href='forum.php?tz_del_id={$aow['floor_tz_id']}'>删除本帖</a>";
                    } else {
                        echo "<a href='floor.php?lc_del_id={$aow['floor_id']}&tz_id={$_GET['tz_id']}'>管理员删除</a><br>{$aow['floor_time']}</td>";
                    }
                } else if ($aow['floor_user_id'] == $_SESSION['user_ID']) {
                    if ($aow['floor_no'] == '1') {
                        echo "<a href='forum.php?tz_del_id={$aow['floor_tz_id']}'>删除本帖</a>";
                    } else {
                        echo "<a href='floor.php?lc_del_id={$aow['floor_id']}&tz_id={$_GET['tz_id']}'>删除</a><br>{$aow['floor_time']}</td>";
                    }
                } else {
                    echo "<br>{$aow['floor_time']}</td>";
                }
                echo "<td style='text-align:left;padding-left: 20px'> {$aow['floor_content']}</td>";
                echo "</tr>";
                sqlsrv_free_stmt($btmt);
            }
            ?>

        </table>
        <!--在下方显示楼层信息 发帖人 时间 删除按钮 内容 层数-->
        <table style="width:1000px;min-height:50px;">
            <tr>

                <td style="text-align: left;padding-left: 20px">
                    <input type="button" value="回复" class="button blue big" onclick="xstz_re()" >&nbsp;&nbsp;
                    <a href="forum.php"><input type="button" style="width:150px"  value="返回论坛" class="button blue big" ></a>&nbsp;&nbsp;
                </td>
            </tr>
        </table>
        <!--隐藏的富文本输入框,点击回复后会显示-->
        <div id="tz_re"  style="z-index:9999; display:none;position:fixed;border: 0px solid #000;right:25%;top: 50%;width: 1000px;height: 400px;">
            <form id="frm_tz_re" method="post" action="floor.php?action=re&tz_id=<?php echo $_GET['tz_id'] ?>">
                <table border="1" width="100%" height="100%">
                    <tr >
                        <td colspan="1" style="text-align: left">
                            <div id="div1" class="toolbar"></div><div id="tz_re_editor" style="height:300px;width:100%"></div> 
                        </td>
                    </tr>
                    <tr height="15%"> 
                        <td colspan="1">输入验证 :12+3=<input type="text" id="yzm" style="width:30px">&nbsp;
                            <input type="button" value="回复" style="width:100px" class="button blue big" onclick="tz_rejc()">&nbsp;
                            <input type="button" value="取消" style="width:100px" class="button gray big" onclick="yctz_re()">
                        </td></tr>
                    <div style="display:none;position:fixed; top:440px;right:0px;"> <textarea id="tz_re_text"  name="tz_re_text" style="width:800px; height:600px;"readonly></textarea></div>
                </table>
            </form>
        </div>
    </center>
    <!--导入富文本框-->
    <script src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/wangEditor.js"></script>
    <script type="text/javascript">
                                var E = window.wangEditor;
                                //帖子添加的富文本框和监听textarea
                                var tz_re_editor = new E('#div1', '#tz_re_editor');
                                var $tz_re_text = $('#tz_re_text');
                                //                代码保存图片
                                tz_re_editor.customConfig.uploadImgShowBase64 = true;
                                tz_re_editor.customConfig.onchange = function (html) {
                                    // 监控变化，同步更新到 textarea
                                    $tz_re_text.val(html);
                                };
                                tz_re_editor.customConfig.pasteFilterStyle = false;
                                tz_re_editor.create();
                                // 初始化 textarea 的值
                                $tz_re_text.val(tz_re_editor.txt.html());
                                //--------------------------------------------------------------------
                                function xstz_re() { //显示发帖窗口
                                    document.getElementById("tz_re").style.display = "block";
                                }
                                function yctz_re() { //隐藏发帖窗口
                                    document.getElementById("tz_re").style.display = "none";
                                }
                                // 检查输入内容和验证码
                                function tz_rejc() {
                                    if (document.getElementById("tz_re_text").value == "") {
                                        alert("回复内容不能为空");
                                    } else if (document.getElementById("yzm").value !== "15") {
                                        alert("验证码错误");
                                    } else {
                                        var form1 = document.getElementById('frm_tz_re');
                                        form1.submit();
                                    }
                                }


    </script>
</body>
</html>
<?php
//清理和关闭数据库连接
sqlsrv_free_stmt($atmt);
sqlsrv_close($conn);
?>
