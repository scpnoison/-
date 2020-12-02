<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
        <title></title>
        <style>
            table{border-collapse:collapse;border:1px solid #000;background-color:rgba(255,255,255,0.5)}
            td{white-space:nowrap;text-align:center;}
            input{width:50px;text-align:center;}
            /*富文本工具栏样式*/
            .toolbar {
                border: 1px solid #ccc;
            }
        </style>
    </head>
    <?php
    include("menu.php");//导入标题栏
    include("lianjie.php");//导入数据库连接语句
    //检测是否已经登录
    if (!isset($_SESSION['user_ID'])) { 
        echo "<center>请先<a href='index.php'>登录</a></center>";
        exit();
    } else {
        //检查用户权限
        $sql = "SELECT [user_ability] FROM [user] where user_ID = '{$_SESSION['user_ID']}'";
        $Ztmt = sqlsrv_query($conn, $sql);
        $Zow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC);
        sqlsrv_free_stmt($Ztmt);
        if ($Zow['user_ability'] != '2') {
            echo "<center>你不是管理员<a href='home.php'>返回</a></center>";
            exit(); 
        }
    }
    ?>
    <?php
    //查询问题最大编号
    $sql = "SELECT top 1 [QS_id] FROM [question] order by [QS_id] desc  ";
    $Ztmt = sqlsrv_query($conn, $sql);
    $Zow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC);
    $new_QS_id = $Zow['QS_id'] + 1; //新的问题ID号
    sqlsrv_free_stmt($Ztmt);
    ?>
    <?php
    //查询问题最大内编号
    $sql = "SELECT top 1 [QS_NO] FROM [question] where QS_tizu_ID = {$_GET['tizu_id']} order by [QS_NO] desc  ";
    $Ztmt = sqlsrv_query($conn, $sql);
    $Zow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC);
    $new_QS_no = $Zow['QS_NO'] + 1; //新的问题内编号号
    sqlsrv_free_stmt($Ztmt);
    ?>

    <?php
    //删除选定问题
    if (isset($_GET['qs_del_id'])) {
        $sql = "DELETE FROM [question] WHERE [QS_id]= {$_GET['qs_del_id']}";
        $Ztmt = sqlsrv_query($conn, $sql);
        sqlsrv_free_stmt($Ztmt);
    }
    //添加新的问题
    if (isset($_GET['action'])) {
        if ($_GET['action'] == 'qs_add') {
            $sql = "INSERT INTO [question] (QS_id,QS_tizu_ID,QS_NO,QS_content,QS_Types) VALUES($new_QS_id,{$_GET['tizu_id']},$new_QS_no,?,?)";
            $comments1 = "编辑问题";
            $comments2 = "选择题";
            //给予中文内容 转码再排序成数组
            $params1 = array(array($comments1, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8')), array($comments2, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8'))); //把字符串放入数组变量并且转码utf-8
            $Ztmt = sqlsrv_query($conn, $sql, $params1);
            sqlsrv_free_stmt($Ztmt); //清理
        }
    }
    //更新问题
    if (isset($_GET['qs_up_id'])) {
        $sql = "update [question] set QS_NO={$_POST['qs_no']},QS_content=?,QS_A=?,QS_B=?,QS_C=?,QS_D=?,QS_jiexi=?,QS_CA=?,QS_Types = ? where QS_id = {$_POST['qs_id']}";
        $comments1 = "{$_POST['qs_content_text']}";
        $comments2 = "{$_POST['qs_a_text']}";
        $comments3 = "{$_POST['qs_b_text']}";
        $comments4 = "{$_POST['qs_c_text']}";
        $comments5 = "{$_POST['qs_d_text']}";
        $comments6 = "{$_POST['qs_jiexi_text']}";
        $comments7 = "{$_POST['qs_ca_text']}";
        $comments8 = "{$_POST['qs_types']}";
        //给予中文内容 转码再排序成数组
        $params1 = array(array($comments1, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8')), array($comments2, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8')), array($comments3, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8')), array($comments4, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8')), array($comments5, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8')), array($comments6, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8')), array($comments7, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8')), array($comments8, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8'))); //把字符串放入数组变量并且转码utf-8
        $Ztmt = sqlsrv_query($conn, $sql, $params1);
        if ($Ztmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        sqlsrv_free_stmt($Ztmt); //清理
    }
    ?>
    <?php
    //查询相关题组的所有信息Aow 第一行显示
    if (isset($_GET['tizu_id'])) {
        $sql = "SELECT * FROM [tizu] WHERE tizu_ID = {$_GET['tizu_id']}";
        $Ztmt = sqlsrv_query($conn, $sql);
        $Aow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC);
        sqlsrv_free_stmt($Ztmt);
    }
    ?>
    <?php
    //查询相关的所有问题Bow 列表用
    if (isset($_GET['tizu_id'])) {
        $sql = "SELECT [QS_id],[QS_tizu_ID],[QS_NO],[QS_content] FROM [question] WHERE QS_tizu_ID = {$_GET['tizu_id']}";
        $Btmt = sqlsrv_query($conn, $sql);
    }
    //查询正在编辑问题的所有信息Cow
    if (isset($_GET['qs_edit_id'])) {
        $sql = "SELECT * FROM [question] WHERE QS_id = {$_GET['qs_edit_id']}";
        $Ztmt = sqlsrv_query($conn, $sql);
        $Cow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC);
        sqlsrv_free_stmt($Ztmt);
    }
    ?>
    <body class="body1">
    <center>
        <!--输出当前题目的表单提交信息-->
        <form name="frm1" method="post" id="frm1" action="qs_edit.php?tizu_id=<?php echo $_GET['tizu_id'] ?>&qs_up_id=<?php
        if (isset($_GET['qs_edit_id'])) {
            echo $_GET['qs_edit_id'];
        }
        ?>">
            <!--显示所属题组的信息-->
            <table border="1" width="1200px" height="800px" style="table-layout: fixed" cellspacing="0">
                <tr>
                    <td align="center" width="60px" height="30">
                        题组ID:
                    </td>
                    <td width="200" >
                        <?php echo "{$Aow['tizu_ID']}"; ?>
                    </td>
                    <td width="80" align="center">
                        题组名称:
                    </td>
                    <td>
                        <?php echo "{$Aow['tizu_name']}"; ?>
                    </td>

                </tr>
                <tr>
                    <td colspan="2" align="center" height="30px">
                        题目列表
                    </td>
                    <td colspan="2" align="center">
                        题目内容管理
                    </td>
                </tr>
                <tr>
                    <td colspan="2" rowspan="2" valign="top">
                        <div >
                            <table border="1" height="100%" width="100%" style="table-layout: fixed">
                                <?php
                                //显示题目列表，和点击可以编辑内容的超链接。
                                while ($Bow = sqlsrv_fetch_array($Btmt, SQLSRV_FETCH_ASSOC)) {
                                    echo "<tr height='30'><td>{$Bow['QS_NO']}</td>"
                                    . "<td width='60%' class='dd'><a href='qs_edit.php?tizu_id={$Bow['QS_tizu_ID']}&qs_edit_id={$Bow['QS_id']}'>{$Bow['QS_content']}</a></td>"
                                    . "<td ><a href='qs_edit.php?tizu_id={$Bow['QS_tizu_ID']}&qs_del_id={$Bow['QS_id']}'><input type='button' value='删除' style='width:45px;'></a></td>"
                                    . "</tr>";
                                }
                                ?>
                                <tr>
                                    
                                    <td align="center" colspan="3" height="40px">
                                        <a href="qs_edit.php?tizu_id=<?php echo $_GET['tizu_id'] ?>&action=qs_add"><input type="button" value="添加一题" style="height: 30px;width:100px;"></a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                    <td colspan="2" valign="top" >
                        <div>
                            <!--显示正在编辑的题目所有信息-->
                            <table border="1" style="table-layout: fixed" width="925px" cellpadding="0" cellspacing="0">
                                <tr align="center">
                                    <td width=280 height="30">
                                        题目ID
                                    </td>
                                    <td width="160px">
                                        <input type="text" name="qs_id" id="qs_id" style="width: 90%" value="<?php
                                        if (isset($_GET['qs_edit_id'])) {
                                            echo $Cow['QS_id'];
                                        }
                                        ?>" readonly="true">
                                    </td>
                                    <td>    
                                        题组内编号
                                    </td>
                                    <td>
                                        <input type="text" name="qs_no" style="width: 90%" value="<?php
                                        if (isset($_GET['qs_edit_id'])) {
                                            echo $Cow['QS_NO'];
                                        }
                                        ?>" readonly="true">
                                    </td>
                                    <td>类型:</td><td>
                                        <select name="qs_types" id="qs_types" onchange="show_abcd(this)" >
                                            <?php
                                            if (isset($_GET['qs_edit_id'])) {
                                                if ($Cow['QS_Types'] == "选择题") {
                                                    echo "<option value ='选择题' selected>选择题</option>";
                                                    echo" <option value ='问答题'>问答题</option>";
                                                } else if ($Cow['QS_Types'] == "问答题") {
                                                    echo "<option value ='选择题' >选择题</option>";
                                                    echo" <option value ='问答题'selected>问答题</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                            <!--表单提交按钮-->
                            <table border=1 cellspacing="0" width="925" height="">
                                <tr>
                                    <td colspan="2" height="50px" align="center">
                                        <input type="button" value="保存修改" style="height: 30px;width: 100px" class="button blue medium" onclick="dl()">&nbsp;&nbsp;&nbsp;
                                        <a href="manage.php?action=tizu_gl"><input type="button" style="height: 30px" class="button gray medium"value="返回"></a>
                                    </td>
                                </tr>
                                <tr align="center">
                                    <td height="80" width="90">
                                        问题
                                    </td>
                                    <td width="750px" style="text-align:left;">
                                        <div style="position: absolute;display: none"><textarea name="qs_content_text" id="qs_content_text"></textarea></div> 
                                        <div id="div1" class="toolbar" ></div>
                                        <div id="qs_content_editor" style="height:100px;width:767px;border: 1px solid #ccc;"></div>

                                    </td>
                                </tr>
                                <tr >
                                    <td height="100" align="center">
                                        解析
                                    </td>
                                    <td colspan="3" style="text-align:left;">
                                        <div style="position: absolute;display: none"><textarea name="qs_jiexi_text" id="qs_jiexi_text"></textarea></div>
                                        <div id="div7" class="toolbar"></div>
                                        <div id="qs_jiexi_editor" style="height:50px;width:767px;border: 1px solid #ccc;"></div>
                                    </td>
                                </tr>
                                <tr><td width="150" >
                                        正确答案
                                    </td>
                                    <td  style="text-align:left;">
                                        <div style="position: absolute;display: none"><textarea name="qs_ca_text" id="qs_ca_text"></textarea></div>
                                        <div id="div2" class="toolbar"></div>
                                        <div id="qs_ca_editor" style="height:80px;width:767px;border: 1px solid #ccc;"></div>
                                    </td>
                                </tr>
                                <tr id="AH">
                                    <td height="80" align="center">
                                        选项A
                                    </td>
                                    <td colspan="3" style="text-align:left;">
                                        <div style="position: absolute;display: none"><textarea name="qs_a_text" id="qs_a_text"></textarea></div>
                                        <div id="div3" class="toolbar"></div>
                                        <div id="qs_a_editor" style="height:50px;width:767px;border: 1px solid #ccc;"></div>
                                    </td>
                                </tr>
                                <tr id="BH">
                                    <td height="80" align="center">
                                        选项B
                                    </td>
                                    <td colspan="3" style="text-align:left;">
                                        <div style="position: absolute;display: none"><textarea name="qs_b_text" id="qs_b_text"></textarea></div>
                                        <div id="div4" class="toolbar"></div>
                                        <div id="qs_b_editor" style="height:50px;width:767px;border: 1px solid #ccc;"></div>
                                    </td>
                                </tr>
                                <tr id="CH">
                                    <td height="80" align="center">
                                        选项C
                                    </td>
                                    <td  colspan="3" style="text-align:left;">
                                        <div style="position: absolute;display: none"><textarea name="qs_c_text" id="qs_c_text"></textarea></div>
                                        <div id="div5" class="toolbar"></div>
                                        <div id="qs_c_editor" style="height:50px;width:767px;border: 1px solid #ccc;"></div>
                                    </td>
                                </tr>
                                <tr id="DH">
                                    <td height="80" align="center" >
                                        选项D
                                    </td>
                                    <td colspan="3" style="text-align:left;">
                                        <div style="position: absolute;display: none"><textarea name="qs_d_text" id="qs_d_text"></textarea></div>
                                        <div id="div6" class="toolbar"></div>
                                        <div id="qs_d_editor" style="height:50px;width:767px;border: 1px solid #ccc;"></div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" height="50px" align="center">
                          <!--表单提交按钮-->
                        <input type="button" value="保存修改" style="height: 50px;width: 100px" class="button blue medium" onclick="dl()">&nbsp;&nbsp;&nbsp;
                        <a href="manage.php?action=tizu_gl"><input type="button" style="height: 50px" class="button gray medium"value="返回"></a>
                    </td>
                </tr>
            </table>
        </form>
    </center>
</body>
<script src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/wangEditor.js"></script>
<script type="text/javascript">
                            //如果检测到问答题类型，则隐藏abcd框
                            window.onload = function () {
                                show_abcd(document.getElementById("qs_types"));
                            };
                            function show_abcd(a) {
                                if (a.value == "问答题") {
                                    document.getElementById("AH").style.display = "none";
                                    document.getElementById("BH").style.display = "none";
                                    document.getElementById("CH").style.display = "none";
                                    document.getElementById("DH").style.display = "none";
                                } else {
                                    document.getElementById("AH").style.display = "table-row";
                                    document.getElementById("BH").style.display = "table-row";
                                    document.getElementById("CH").style.display = "table-row";
                                    document.getElementById("DH").style.display = "table-row";
                                }
                            }
//       
</script>
<script type="text/javascript">
//    富文本编辑器的相关代码
    var E = window.wangEditor;
    //问题内容的监听
    var qs_content_editor = new E('#div1', '#qs_content_editor');
    var $qs_content_text = $('#qs_content_text');
    //                代码保存图片
            qs_content_editor.customConfig.uploadImgShowBase64 = true; 
    qs_content_editor.customConfig.onchange = function (html) {
        $qs_content_text.val(html);
    };
    qs_content_editor.create();
    qs_content_editor.customConfig.pasteFilterStyle = false;
    qs_content_editor.txt.html('<?php
                                            if (isset($_GET['qs_edit_id'])) {
                                                echo "{$Cow['QS_content']}";
                                            }
                                            ?>');
    $qs_content_text.val(qs_content_editor.txt.html());
    //正确答案的监听
    var qs_ca_editor = new E('#div2', '#qs_ca_editor');
    var $qs_ca_text = $('#qs_ca_text');
    //                代码保存图片
            qs_ca_editor.customConfig.uploadImgShowBase64 = true; 
    qs_ca_editor.customConfig.onchange = function (html) {
        $qs_ca_text.val(html);
    };
    qs_ca_editor.create();
    qs_ca_editor.customConfig.pasteFilterStyle = false;
    qs_ca_editor.txt.html('<?php
                                            if (isset($_GET['qs_edit_id'])) {
                                                echo "{$Cow['QS_CA']}";
                                            }
                                            ?>');
    $qs_ca_text.val(qs_ca_editor.txt.html());
    //选项A的监听
    var qs_a_editor = new E('#div3', '#qs_a_editor');
    var $qs_a_text = $('#qs_a_text');
    //                代码保存图片
            qs_a_editor.customConfig.uploadImgShowBase64 = true; 
    qs_a_editor.customConfig.onchange = function (html) {
        $qs_a_text.val(html);
    };
    qs_a_editor.create();
    qs_a_editor.customConfig.pasteFilterStyle = false;
    qs_a_editor.txt.html('<?php
                                            if (isset($_GET['qs_edit_id'])) {
                                                echo "{$Cow['QS_A']}";
                                            }
                                            ?>');
    $qs_a_text.val(qs_a_editor.txt.html());
    //选项B的监听
    var qs_b_editor = new E('#div4', '#qs_b_editor');
    var $qs_b_text = $('#qs_b_text');
    //                代码保存图片
            qs_b_editor.customConfig.uploadImgShowBase64 = true; 
    qs_b_editor.customConfig.onchange = function (html) {
        $qs_b_text.val(html);
    };
    qs_b_editor.create();
    qs_b_editor.customConfig.pasteFilterStyle = false;
    qs_b_editor.txt.html('<?php
                                            if (isset($_GET['qs_edit_id'])) {
                                                echo "{$Cow['QS_B']}";
                                            }
                                            ?>');
    $qs_b_text.val(qs_b_editor.txt.html());
    //选项C的监听
    var qs_c_editor = new E('#div5', '#qs_c_editor');
    var $qs_c_text = $('#qs_c_text');
    //                代码保存图片
            qs_c_editor.customConfig.uploadImgShowBase64 = true; 
    qs_c_editor.customConfig.onchange = function (html) {
        $qs_c_text.val(html);
    };
    qs_c_editor.create();
    qs_c_editor.customConfig.pasteFilterStyle = false;
    qs_c_editor.txt.html('<?php
                                            if (isset($_GET['qs_edit_id'])) {
                                                echo "{$Cow['QS_C']}";
                                            }
                                            ?>');
    $qs_c_text.val(qs_c_editor.txt.html());
    //选项D的监听
    var qs_d_editor = new E('#div6', '#qs_d_editor');
    var $qs_d_text = $('#qs_d_text');
    //                代码保存图片
            qs_d_editor.customConfig.uploadImgShowBase64 = true; 
    qs_d_editor.customConfig.onchange = function (html) {
        $qs_d_text.val(html);
    };
    qs_d_editor.create();
    qs_d_editor.customConfig.pasteFilterStyle = false;
    qs_d_editor.txt.html('<?php
                                            if (isset($_GET['qs_edit_id'])) {
                                                echo "{$Cow['QS_D']}";
                                            }
                                            ?>');
    $qs_d_text.val(qs_d_editor.txt.html());
    //解析的监听
    var qs_jiexi_editor = new E('#div7', '#qs_jiexi_editor');
    var $qs_jiexi_text = $('#qs_jiexi_text');
    //                代码保存图片
            qs_jiexi_editor.customConfig.uploadImgShowBase64 = true; 
    qs_jiexi_editor.customConfig.onchange = function (html) {
        $qs_jiexi_text.val(html);
    };
    qs_jiexi_editor.create();
    qs_jiexi_editor.customConfig.pasteFilterStyle = false;
    qs_jiexi_editor.txt.html('<?php
                                            if (isset($_GET['qs_edit_id'])) {
                                                echo "{$Cow['QS_jiexi']}";
                                            }
                                            ?>');
    $qs_jiexi_text.val(qs_jiexi_editor.txt.html());
//检查并提交表单的脚本
    function dl() {
        if (document.getElementById("qs_id").value == "") {
            alert("提交无效，选择题目再编辑！");
        } else {
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