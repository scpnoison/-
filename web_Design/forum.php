
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
        <title>论坛</title>
        <style>
            table{border-collapse:collapse;border:1px solid #000;background-color:rgba(255,255,255,0.5)}
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
        //删除帖子和其楼层
        if (isset($_GET['tz_del_id'])) {
            if ($yhqx['user_ability'] == '2') { //权限检查
                $sql = "DELETE FROM [post] WHERE [post_id]= '{$_GET['tz_del_id']}'";
                $Ztmt = sqlsrv_query($conn, $sql); //执行
                $sql = "DELETE FROM [floor] WHERE [floor_tz_id]= '{$_GET['tz_del_id']}'";
                $Ztmt = sqlsrv_query($conn, $sql); //执行
                sqlsrv_free_stmt($Ztmt);
            } else if ($yhqx['user_ability'] == '1') { //权限检查 普通用户
                $sql = "select [post_launch_id] from [post] where post_id = {$_GET['tz_del_id']}";
                $Ztmt = sqlsrv_query($conn, $sql);
                $Zow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC);
                if ($Zow['post_launch_id'] == $_SESSION['user_ID']) {
                    $sql = "DELETE FROM [post] WHERE [post_id]= '{$_GET['tz_del_id']}'";
                    $Ztmt = sqlsrv_query($conn, $sql); //执行
                    $sql = "DELETE FROM [floor] WHERE [floor_tz_id]= '{$_GET['tz_del_id']}'";
                    $Ztmt = sqlsrv_query($conn, $sql); //执行
                    sqlsrv_free_stmt($Ztmt);
                } else {
                    echo "你没有次权限";
                }
            }
        }
        ?>
        <?php
        //查询帖子最大编号
        $sql = "SELECT top 1 [post_id] FROM [post] order by [post_id] desc  ";
        $Ztmt = sqlsrv_query($conn, $sql);
        $Zow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC);
        $new_post_id = $Zow['post_id'] + 1; //新的帖子ID号
        sqlsrv_free_stmt($Ztmt);
        //查询楼层最大编号
        $sql = "SELECT top 1 [floor_id] FROM [floor] order by [floor_id] desc  ";
        $Ztmt = sqlsrv_query($conn, $sql);
        $Zow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC);
        $new_floor_id = $Zow['floor_id'] + 1; //新的帖子ID号
        sqlsrv_free_stmt($Ztmt);

        $newtime = date('Y-m-d H:i:s', time()); //获得当前时间
        if (isset($_GET['action'])) {
            if ($_GET['action'] == 'add') { //添加帖子
                $sql = "INSERT INTO [post] (post_id,post_type,post_title,post_launch_id,post_time,post_renum) VALUES($new_post_id,?,?,'{$_SESSION['user_ID']}',?,1)";
                $comments1 = "{$_POST['post_type']}";
                $comments2 = "{$_POST['post_title']}";
                $comments3 = "{$newtime}";
                //给予中文内容 转码再排序成数组
                $params1 = array(array($comments1, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8')), array($comments2, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8')), array($comments3, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8'))); //把字符串放入数组变量并且转码utf-8
                $Ztmt = sqlsrv_query($conn, $sql, $params1);
                sqlsrv_free_stmt($Ztmt); //清理
                //添加1楼
                $sql = "INSERT INTO [floor] (floor_id,floor_tz_id,floor_user_id,floor_content,floor_time,floor_no) VALUES($new_floor_id,$new_post_id,'{$_SESSION['user_ID']}',?,?,1)";
                $comments1 = "{$_POST['tz_add_text']}";
                $comments2 = "{$newtime}";
                //给予中文内容 转码再排序成数组
                $params1 = array(array($comments1, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8')), array($comments2, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8'))); //把字符串放入数组变量并且转码utf-8
                $Ztmt = sqlsrv_query($conn, $sql, $params1);
                sqlsrv_free_stmt($Ztmt); //清理
            }
        }
        ?>
        <?php
//查询前50个帖子 
        if (isset($_GET['mytz'])) {
            $sql = "SELECT * FROM [post] WHERE post_launch_id = '{$_SESSION['user_ID']}' ";
            $atmt = sqlsrv_query($conn, $sql);
        } else if (isset($_GET['search'])) {
            //查询搜索
            $sql = "SELECT * FROM [post] WHERE post_title like '%{$_POST['search']}%' ";
            $atmt = sqlsrv_query($conn, $sql);
        } else if (!isset($_GET['type'])) {
            //显示全部种类
            $sql = "SELECT top 50 * FROM [post] ";
            $atmt = sqlsrv_query($conn, $sql);
        } else {//显示限定种类
            $sql = "SELECT * FROM [post] WHERE post_type = '{$_GET['type']}' ";
            $atmt = sqlsrv_query($conn, $sql);
        }
        ?> 
    </body>
</head>
<body class="body1">
<center>
    <!--提交查询信息的表单和表格-->
    <form method="post" action="forum.php?search=1">
        <table style="width:1200px;">
            <tr>
                <td colspan="3" style="text-align: left">&nbsp;<input type="text" name="search"  style="width:200px;height:30px"><input type="submit" value="搜索" style="height:30px"><br>&nbsp;</td>
            </tr>
            <tr>
                <td width="10%">&nbsp;<input type="button" style="width:150px" value="我要发帖" class="button blue big" onclick="xstz_add()" >&nbsp;&nbsp;</td>
                <td width="10%">&nbsp;<a href="forum.php?mytz=1"><input type="button" style="width:150px" value="我的帖子" class="button orange big" ></a></td>
                <td width="80%" style="text-align: left">&nbsp;&nbsp;<a href="forum.php">全部</a>&nbsp;&nbsp;<a href="forum.php?type=求助">求助</a>&nbsp;&nbsp;<a href="forum.php?type=心得">心得</a>&nbsp;&nbsp;<a href="forum.php?type=分享">分享</a>&nbsp;&nbsp;<a href="forum.php?type=聊天">聊天</a>&nbsp;&nbsp; 仅显示最新50条帖子</td> 
            </tr>
        </table>
    </form>
    <br>
    <table border="1" style="width:1200px;">
        <tr style="height:30px">
            <td>类型</td>
            <td style='width:60%'>主题</td>
            <td>发帖人</td>
            <td>时间</td>
            <td>回复数量</td>
            <td>操作</td>
        </tr>
        <?php
        // 显示帖子信息
        while ($aow = sqlsrv_fetch_array($atmt, SQLSRV_FETCH_ASSOC)) {
            $sql = "SELECT [user_name] FROM [user] WHERE user_ID = '{$aow['post_launch_id']}' ";
            $btmt = sqlsrv_query($conn, $sql);
            $bow = sqlsrv_fetch_array($btmt, SQLSRV_FETCH_ASSOC);
            echo "<tr style='height:30px'>";
            echo "<td>{$aow['post_type']}</td><td  class='dd'><a href='floor.php?tz_id={$aow['post_id']}'>{$aow['post_title']}</a></td><td>{$bow['user_name']}</td><td>{$aow['post_time']}</td><td>{$aow['post_renum']}</td><td>";
            if ($yhqx['user_ability'] == '2') {
                echo "<a href='forum.php?tz_del_id={$aow['post_id']}'>删除</a>";
            } else if ($aow['post_launch_id'] == $_SESSION['user_ID']) {
                echo "<a href='forum.php?tz_del_id={$aow['post_id']}'>删除</a>";
            }
            echo "</tr></td>";
            sqlsrv_free_stmt($btmt);
        }
        ?>
    </table>
    <!--隐藏的富文本输入框,点击发帖后会显示-->
    <div id="tz_add"  style="z-index:9999;display: none;position:fixed;border: 0px solid #000;right:25%;top: 50%;width: 1000px;height: 400px;">
        <form id="frm_tz_add" method="post" action="forum.php?action=add">
            <table border="1" width="100%" height="100%">
                <tr height="10%">
                    <td>类型</td><td>主题</td>
                </tr>
                <tr height="10%">
                    <td>
                        <select name="post_type" id="tz_type">
                            <option value ='求助'>求助</option>
                            <option value ='心得'>心得</option>
                            <option value ='分享' >分享</option>
                            <option value ='聊天'selected>聊天</option>
                        </select></td>
                    <td><input type="text" id="tz_title" name="post_title" style="width:600px"></td>
                </tr>
                <tr >
                    <td colspan="2" style="text-align: left">
                        <div id="div1" class="toolbar"></div><div id="tz_add_editor" style="height:300px;width:100%"></div> 
                    </td>
                </tr>
                <tr height="15%"> 
                    <td colspan="2">输入验证 :12+3=<input type="text" id="yzm" style="width:30px">&nbsp;
                        <input type="button" value="发帖" style="width:100px" class="button blue big" onclick="tz_addjc()">&nbsp;
                        <input type="button" value="取消" style="width:100px" class="button gray big" onclick="yctz_add()">
                    </td></tr>
                <div style="display:none;position:fixed; top:440px;right:0px;"> <textarea id="tz_add_text"  name="tz_add_text" style="width:800px; height:600px;"readonly></textarea></div>
            </table>
        </form>
    </div>
</center>
</body>
<script src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/wangEditor.js"></script>
<script type="text/javascript">
                            var E = window.wangEditor;
                            //帖子添加的富文本框和监听textarea
                            var tz_add_editor = new E('#div1', '#tz_add_editor');
                            var $tz_add_text = $('#tz_add_text');
                            //                代码保存图片
                            tz_add_editor.customConfig.uploadImgShowBase64 = true;
                            tz_add_editor.customConfig.onchange = function (html) {
                                // 监控变化，同步更新到 textarea
                                $tz_add_text.val(html);
                            };
                            tz_add_editor.customConfig.pasteFilterStyle = false;
                            tz_add_editor.create();
                            // 初始化 textarea 的值
                            $tz_add_text.val(tz_add_editor.txt.html());
                            //--------------------------------------------------------------------
                            function xstz_add() { //显示发帖窗口
                                document.getElementById("tz_add").style.display = "block";
                            }
                            function yctz_add() { //隐藏发帖窗口
                                document.getElementById("tz_add").style.display = "none";
                            }
                            function tz_addjc() {//检查输入内容是否为空 否则阻止提交表单
                                if (document.getElementById("tz_title").value == "") {
                                    alert("帖子主题不能为空！");
                                } else if (document.getElementById("yzm").value !== "15") {
                                    alert("验证码错误");
                                } else {
                                    var form1 = document.getElementById('frm_tz_add');
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
