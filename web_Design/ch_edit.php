<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
        <title></title>
        <style>
            table{border-collapse:collapse;border:1px solid #000;background-color:rgba(255,255,255,0.5)}
            td{white-space:nowrap;text-align:center;}
            input{width:100px;text-align:center;}
/*            富文本工具栏样式*/
            .toolbar {
                border: 1px solid #ccc;
            }
        </style>
    </head>
    <?php
    include("menu.php");//导入标题栏
    include("lianjie.php");//导入数据库连接语句
    if (!isset($_SESSION['user_ID'])) { //检测是否已经登录
        echo "<center>请先<a href='index.php'>登录</a></center>";
        exit();
    } else {//权限检测
        $sql = "SELECT [user_ability] FROM [user] where user_ID = '{$_SESSION['user_ID']}'";
        $Ztmt = sqlsrv_query($conn, $sql);
        $Zow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC);
        sqlsrv_free_stmt($Ztmt);
        //若不是管理员则不加载后续界面
        if ($Zow['user_ability'] != '2') {
            echo "<center>你不是管理员<a href='home.php'>返回</a></center>";
            exit();
        }
    }
    ?>
    <?php
    //查询章节最大编号
    $sql = "SELECT top 1 [CH_id] FROM [chapter] order by [CH_id] desc  ";
    $Ztmt = sqlsrv_query($conn, $sql);
    $Zow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC);
    $new_CH_id = $Zow['CH_id'] + 1; //新的章节ID号
    sqlsrv_free_stmt($Ztmt);
    ?>
    <?php
    //查询章节最大内编号
    $sql = "SELECT top 1 [CH_no] FROM [chapter] where CH_course_ID = {$_GET['course_id']} order by [CH_no] desc  ";
    $Ztmt = sqlsrv_query($conn, $sql);
    $Zow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC);
    $new_CH_no = $Zow['CH_no'] + 1; //新的章节内编号号
    sqlsrv_free_stmt($Ztmt);
    ?>

    <?php
    //删除选定章节
    if (isset($_GET['ch_del_id'])) {
        $sql = "DELETE FROM [chapter] WHERE [CH_id]= {$_GET['ch_del_id']}";
        $Ztmt = sqlsrv_query($conn, $sql);
        sqlsrv_free_stmt($Ztmt);
    }
    //添加新的章节
    if (isset($_GET['action'])) {
        if ($_GET['action'] == 'ch_add') {
            $sql = "INSERT INTO [chapter] (CH_id,CH_course_ID,CH_no,CH_name,CH_content) VALUES($new_CH_id,{$_GET['course_id']},$new_CH_no,?,?)";
            $comments1 = "编辑章节名称";
            $comments2 = "编辑章节内容";
            //给予中文内容 转码再排序成数组
            $params1 = array(array($comments1, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8')), array($comments2, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8'))); //把字符串放入数组变量并且转码utf-8
            $Ztmt = sqlsrv_query($conn, $sql, $params1);
            sqlsrv_free_stmt($Ztmt); //清理
        }
    }
    //更新章节
    if (isset($_GET['ch_up_id'])) {
        $sql = "update [chapter] set CH_no={$_POST['ch_no']}, CH_name= ? ,CH_content= ? where CH_id = {$_POST['ch_id']}";
        $comments1 = "{$_POST['ch_name']}";
        $comments2 = "{$_POST['ch_content_text']}";
        //给予中文内容 转码再排序成数组
        $params1 = array(array($comments1, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8')), array($comments2, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8'))); //把字符串放入数组变量并且转码utf-8
        $Ztmt = sqlsrv_query($conn, $sql, $params1);
        sqlsrv_free_stmt($Ztmt); //清理
    }
    ?>

    <?php
    //查询相关课程的所有信息Aow 第一行显示
    if (isset($_GET['course_id'])) {
        $sql = "SELECT * FROM [course] WHERE course_ID = {$_GET['course_id']}";
        $Ztmt = sqlsrv_query($conn, $sql);
        $Aow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC);
        sqlsrv_free_stmt($Ztmt);
    }
    ?>
    <?php
    //查询相关的所有章节Bow 列表用
    if (isset($_GET['course_id'])) {
        $sql = "SELECT [CH_id],[CH_course_ID],[CH_name],[CH_no] FROM [chapter] WHERE CH_course_ID = {$_GET['course_id']}";
        $Btmt = sqlsrv_query($conn, $sql);
    }
    //查询正在编辑章节的所有信息Cow
    if (isset($_GET['ch_edit_id'])) {
        $sql = "SELECT * FROM [chapter] WHERE CH_id = {$_GET['ch_edit_id']}";
        $Ztmt = sqlsrv_query($conn, $sql);
        $Cow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC);
        sqlsrv_free_stmt($Ztmt);
    }
    ?>

    <body class="body1">

    <center>
<!--        自动生成表单提交数据-->
        <form name="frm1" id="frm1" method="post" action="ch_edit.php?course_id=<?php echo $_GET['course_id'] ?>&ch_up_id=<?php
        if (isset($_GET['ch_edit_id'])) {
            echo $_GET['ch_edit_id'];
        }
        ?>">
<!--            显示所属课程信息-->
            <table border="1" width="1200px" height="800px" style="table-layout: fixed" cellspacing="0">
                <tr><td align="center" width="60px" height="30">课程ID: </td>
                    <td width="200" > <?php echo "{$Aow['course_ID']}"; ?></td>
                    <td width="80" align="center">课程名称: </td>
                    <td> <?php echo "{$Aow['course_name']}"; ?></td>
                </tr>
                <tr> <td colspan="2" align="center" height="30px">章节列表(点击可编辑) </td>
                    <td colspan="2" align="center">章节内容管理 </td>
                </tr>
                <tr> <td colspan="2" rowspan="1" valign="top">
                        <div >
                            <table border="1" height="100%" width="100%" style="table-layout: fixed">
                                <?php
                                //显示章节列表 包含编辑超链接
                                while ($Bow = sqlsrv_fetch_array($Btmt, SQLSRV_FETCH_ASSOC)) {
                                    echo "<tr height='30'><td>{$Bow['CH_no']}</td>"
                                    . "<td width='60%' class='dd'><a href='ch_edit.php?course_id={$Bow['CH_course_ID']}&ch_edit_id={$Bow['CH_id']}'>{$Bow['CH_name']}</a></td>"
                                    . "<td ><a href='ch_edit.php?course_id={$Bow['CH_course_ID']}&ch_del_id={$Bow['CH_id']}'><input type='button' value='删除' style='width:45px;'></a></td>"
                                    . "</tr>";
                                }
                                ?>
                                <tr> 
                                    <!--添加一章的按钮-->
                                    <td align="center" colspan="3" height="40px">
                                        <a href="ch_edit.php?course_id=<?php echo $_GET['course_id'] ?>&action=ch_add"><input type="button" value="添加一章" style="height: 30px"></a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                    <td colspan="2" valign="top" >
                        <div>
                            <!--显示相关章节内容-->
                            <table border="1" style="table-layout: fixed" width="927px" cellpadding="0" cellspacing="0">
                                <tr align="center">
                                    <td width=700 height=30> 章节名称:</td>
                                    <td> 章节ID</td>
                                    <td>章节内编号</td>
                                </tr>
                                <tr align="center">
                                    <td height=30><input name="ch_name" id="ch_name" type="text" style="width: 550px" value="<?php
                                        if (isset($_GET['ch_edit_id'])) {
                                            echo $Cow['CH_name'];
                                        }
                                        ?>"></td>
                                    <td height=30><input name="ch_id" id="ch_id" type="text" style="width: 80px" readonly value="<?php
                                        if (isset($_GET['ch_edit_id'])) {
                                            echo $Cow['CH_id'];
                                        }
                                        ?>"> </td>
                                    <td><input name="ch_no" type="text" id="ch_no" style="width: 80px" value="<?php
                                        if (isset($_GET['ch_edit_id'])) {
                                            echo $Cow['CH_no'];
                                        }
                                        ?>"> </td>
                                </tr>
                                <tr height="20px"><td colspan="3" style="font-size:20px">章节内容</td></tr>
                                <tr>
                                    <td colspan="3"  height=600 style="text-align:left;">
                                        <div style="position: absolute;display: none"><textarea name="ch_content_text" id="ch_content_text"></textarea></div> 
                                        <div id="div1" class="toolbar"></div>
                                        <div id="ch_content_editor" style="height:600px"></div> </td>
                                </tr>
<!--                                提交和返回按钮-->
                                <tr><td colspan="3">
                                        <input type="button" value="保存修改" style="height: 40px" class="button blue medium" onclick="dl()">&nbsp;&nbsp;&nbsp;
                                        <a href="manage.php?action=course_gl"><input type="button" style="height: 40px" class="button gray medium"value="返回"></a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </center>
</body>
<script src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/wangEditor.js"></script>
<script type="text/javascript">
    var E = window.wangEditor;
    //添加富文本框和监听textarea
    var ch_content_editor = new E('#div1', '#ch_content_editor');
    var $ch_content_text = $('#ch_content_text');
    //                代码保存图片
            ch_content_editor.customConfig.uploadImgShowBase64 = true; 
    ch_content_editor.customConfig.onchange = function (html) {
        // 监控变化，同步更新到 textarea
        $ch_content_text.val(html);
    };
    ch_content_editor.create();
    // 关闭粘贴样式的过滤
    ch_content_editor.customConfig.pasteFilterStyle = false;
    //设置富文本框的内容
    ch_content_editor.txt.html('<?php
                                               if (isset($_GET['ch_edit_id'])) {
                                                   echo "{$Cow['CH_content']}";
                                               }
                                        ?>');
    // 初始化 textarea 的值
    $ch_content_text.val(ch_content_editor.txt.html());
    
    
    
//    检查填写情况，若为空则阻止提交
    function dl() {
        
        if (document.getElementById("ch_name").value == "") {

            alert("章节名称为空！");

        } else if (document.getElementById("ch_id").value == "") {

            alert("章节ID为空");

        } else if (document.getElementById("ch_no").value == "") {

            alert("章节内编号为空");

        } 
        else {
            //提交表单
            var form1 = document.getElementById('frm1');
            form1.submit();
        }

    }
</script>

</html>
<?php
//清空并关闭连接
sqlsrv_free_stmt($Btmt);
sqlsrv_close($conn);
?>