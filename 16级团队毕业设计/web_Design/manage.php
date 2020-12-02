
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
        <title></title>
        <style>
            table{border-collapse:collapse;border:1px solid #000;background-color:rgba(255,255,255,0.5)}
            td{white-space:nowrap;text-align:center;}
            input{width:100px;text-align:center;}
            /*富文本工具栏样式*/
            .toolbar {
                border: 1px solid #ccc;
            }
        </style>
    </head>
    <?php
    include("menu.php"); //导入标题栏
    include("lianjie.php"); //导入数据库连接语句
    if (!isset($_SESSION['user_ID'])) { //检测是否已经登录
        echo "<center>请先<a href='index.php'>登录</a></center>";
        exit();
    } else {
        //查询用户权限
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
    //执行课程添加
    if (isset($_GET['course_add_sql'])) {
        $sql = "INSERT INTO [course] (course_ID,course_name,course_Introduction,course_Types) VALUES({$_POST['course_id']},?,?,?)";
        $comments1 = "{$_POST['course_name']}";
        $comments2 = "{$_POST['course_introduction']}";
        $comments3 = "{$_POST['course_types']}";
        //给予中文内容 转码再排序成数组
        $params1 = array(array($comments1, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8')), array($comments2, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8')), array($comments3, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8'))); //把字符串放入数组变量并且转码utf-8
        $Ztmt = sqlsrv_query($conn, $sql, $params1);
        sqlsrv_free_stmt($Ztmt); //清理
//        //查询章节最大编号
        $sql = "SELECT top 1 [CH_id] FROM [chapter] order by [CH_id] desc  ";
        $Ztmt = sqlsrv_query($conn, $sql);
        $ZBow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC);
        $new_CH_id = $ZBow['CH_id'] + 1; //新的章节ID号
        sqlsrv_free_stmt($Ztmt);
        //自动加入第零章课程简介
        $sql = "INSERT INTO [chapter] (CH_id,CH_course_ID,CH_no,CH_name,CH_content) VALUES({$new_CH_id},{$_POST['course_id']},0,?,?)";
        $comments1 = "课程简介";
        $comments2 = "{$_POST['course_introduction']}";
        //给予中文内容 转码再排序成数组
        $params1 = array(array($comments1, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8')), array($comments2, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8'))); //把字符串放入数组变量并且转码utf-8
        $Ztmt = sqlsrv_query($conn, $sql, $params1);
        if ($Ztmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        sqlsrv_free_stmt($Ztmt); //清理
    }
    ?>
    <?php
    //执行题组添加
    if (isset($_GET['tizu_add_sql'])) {
        $sql = "INSERT INTO [tizu] (tizu_ID,tizu_name,tizu_Introduction,tizu_Types) VALUES({$_POST['tizu_id']},?,?,?)";
        $comments1 = "{$_POST['tizu_name']}";
        $comments2 = "{$_POST['tizu_introduction']}";
        $comments3 = "{$_POST['tizu_types']}";
        //给予中文内容 转码再排序成数组
        $params1 = array(array($comments1, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8')), array($comments2, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8')), array($comments3, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8'))); //把字符串放入数组变量并且转码utf-8
        $Ztmt = sqlsrv_query($conn, $sql, $params1);
        sqlsrv_free_stmt($Ztmt); //清理
        //查询问题最大编号
        $sql = "SELECT top 1 [QS_id] FROM [question] order by [QS_id] desc  ";
        $Ztmt = sqlsrv_query($conn, $sql);
        $ZBow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC);
        $new_QS_id = $ZBow['QS_id'] + 1; //新的题组ID号
        sqlsrv_free_stmt($Ztmt);
        //自动加入第零题题组简介
        $sql = "INSERT INTO [question] (QS_id,QS_tizu_ID,QS_no,QS_content) VALUES({$new_QS_id},{$_POST['tizu_id']},0,?)";
        $comments1 = "{$_POST['tizu_introduction']}";
        //给予中文内容 转码再排序成数组
        $params1 = array($comments1, SQLSRV_PHPTYPE_STRING('UTF-8')); //把字符串放入数组变量并且转码utf-8
        $Ztmt = sqlsrv_query($conn, $sql, $params1);
        if ($Ztmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        sqlsrv_free_stmt($Ztmt); //清理
    }
    ?>
    <?php
    //执行新闻添加
    if (isset($_GET['news_add_sql'])) {
        $sql = "INSERT INTO [news] (news_ID,news_Types,news_time,news_Author,news_title,news_content) VALUES({$_POST['news_id']},'{$_POST['news_types']}','{$_POST['news_time']}',?,?,?)";
        $comments1 = "{$_POST['news_author']}";
        $comments2 = "{$_POST['news_title']}";
        $comments3 = "{$_POST['news_add_text']}";
        //给予中文内容 转码再排序成数组
        $params1 = array(array($comments1, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8')), array($comments2, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8')), array($comments3, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8'))); //把字符串放入数组变量并且转码utf-8
        $Ztmt = sqlsrv_query($conn, $sql, $params1);
        sqlsrv_free_stmt($Ztmt); //清理
    }
    ?>
    <?php
    //执行课程信息修改
    if (isset($_GET['course_xg_sql'])) {
        $sql = "update [course] set course_ID = {$_POST['course_id']},course_name = ? ,course_Introduction = ? ,course_Types= ? where course_ID = {$_GET['course_xg_old_id']}";
        $comments1 = "{$_POST['course_name']}";
        $comments2 = "{$_POST['course_introduction']}";
        $comments3 = "{$_POST['course_types']}";
        //给予中文内容 转码再排序成数组
        $params1 = array(array($comments1, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8')), array($comments2, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8')), array($comments3, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8'))); //把字符串放入数组变量并且转码utf-8
        $Ztmt = sqlsrv_query($conn, $sql, $params1);
        sqlsrv_free_stmt($Ztmt); //清理
    }
    //执行题组信息修改
    if (isset($_GET['tizu_xg_sql'])) {
        $sql = "update [tizu] set tizu_ID = {$_POST['tizu_id']},tizu_name = ? ,tizu_Introduction = ?,tizu_Types = ?  where tizu_ID = {$_GET['tizu_xg_old_id']}";
        $comments1 = "{$_POST['tizu_name']}";
        $comments2 = "{$_POST['tizu_introduction']}";
        $comments3 = "{$_POST['tizu_types']}";
        //给予中文内容 转码再排序成数组
        $params1 = array(array($comments1, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8')), array($comments2, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8')), array($comments3, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8'))); //把字符串放入数组变量并且转码utf-8
        $Ztmt = sqlsrv_query($conn, $sql, $params1);
        sqlsrv_free_stmt($Ztmt); //清理
    }
    //执行新闻修改
    if (isset($_GET['news_xg_sql'])) {
        $sql = "update [news] set news_ID = {$_POST['news_id']},news_Types = '{$_POST['news_types']}',news_time ='{$_POST['news_time']}',news_Author = ?,news_title = ?,news_content = ? where news_ID = {$_GET['news_xg_old_id']}";
        $comments1 = "{$_POST['news_author']}";
        $comments2 = "{$_POST['news_title']}";
        $comments3 = "{$_POST['news_xg_text']}";
        //给予中文内容 转码再排序成数组
        $params1 = array(array($comments1, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8')), array($comments2, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8')), array($comments3, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING('UTF-8'))); //把字符串放入数组变量并且转码utf-8
        $Ztmt = sqlsrv_query($conn, $sql, $params1);
        sqlsrv_free_stmt($Ztmt); //清理
    }
    ?>
    <?php
    //删除课程和章节和订阅表
    if (isset($_GET['course_del_id'])) {
        $sql = "DELETE FROM [course] WHERE [course_id]= '{$_GET['course_del_id']}'";
        $Ztmt = sqlsrv_query($conn, $sql); //执行
        $sql = "DELETE FROM [chapter] WHERE [CH_course_ID]= '{$_GET['course_del_id']}'";
        $Ztmt = sqlsrv_query($conn, $sql); //执行
        $sql = "DELETE FROM [course_sub] WHERE [course_ID]= '{$_GET['course_del_id']}'";
        $Ztmt = sqlsrv_query($conn, $sql); //执行
        sqlsrv_free_stmt($Ztmt); //清理
    }
//删除题组 同时删除题目 题组订阅表 题目收集表
    if (isset($_GET['tizu_del_id'])) {
        //删除题组
        $sql = "DELETE FROM [tizu] WHERE [tizu_ID]= '{$_GET['tizu_del_id']}'";
        $Ztmt = sqlsrv_query($conn, $sql); //执行
        sqlsrv_free_stmt($Ztmt);
        //删除问题搜集表和完成的题目表
        $sql = "select [QS_id] from [question] where QS_tizu_ID = '{$_GET['tizu_del_id']}'";
        $Ztmt = sqlsrv_query($conn, $sql); //执行
        while ($ow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC)) {
            $sql = "DELETE FROM [question_col] WHERE [QS_id]= '{$ow['QS_id']}'";
            $BNtmt = sqlsrv_query($conn, $sql);
            $sql = "DELETE FROM [user_QS_done] WHERE [QS_done_id]= '{$ow['QS_id']}'";
            $BNtmt = sqlsrv_query($conn, $sql);
            sqlsrv_free_stmt($BNtmt);
        }
        sqlsrv_free_stmt($Ztmt);
        //删除所属题目
        $sql = "DELETE FROM [question] WHERE [QS_tizu_ID]= '{$_GET['tizu_del_id']}'";
        $Ztmt = sqlsrv_query($conn, $sql); //执行
        //删除相关题组订阅
        $sql = "DELETE FROM [tizu_sub] WHERE [tizu_ID]= '{$_GET['tizu_del_id']}'";
        $Ztmt = sqlsrv_query($conn, $sql); //执行
        sqlsrv_free_stmt($Ztmt); //清理
    }
//删除新闻
    if (isset($_GET['news_del_id'])) {
        $sql = "DELETE FROM [news] WHERE [news_id]= '{$_GET['news_del_id']}'";
        $Ztmt = sqlsrv_query($conn, $sql); //执行
        $sql = "DELETE FROM [news_col] WHERE [news_id]= '{$_GET['news_del_id']}'";
        $Ztmt = sqlsrv_query($conn, $sql); //执行
        sqlsrv_free_stmt($Ztmt); //清理
    }
//删除用户和其全部信息
    if (isset($_GET['user_del_id'])) {
        $sql = "DELETE FROM [user] WHERE [user_ID]= '{$_GET['user_del_id']}'";
        $Ztmt = sqlsrv_query($conn, $sql); //执行
        //删除其课程订阅信息
        $sql = "DELETE FROM [course_sub] WHERE [user_ID]= '{$_GET['user_del_id']}'";
        $Ztmt = sqlsrv_query($conn, $sql); //执行
        //删除题目搜集和做的题目
        $sql = "DELETE FROM [question_col] WHERE [user_ID]= '{$_GET['user_del_id']}'";
        $Ztmt = sqlsrv_query($conn, $sql);
        $sql = "DELETE FROM [user_QS_done] WHERE [user_ID]= '{$_GET['user_del_id']}'";
        $Ztmt = sqlsrv_query($conn, $sql);
        //搜集的文章
        $sql = "DELETE FROM [news_col] WHERE [user_ID]= '{$_GET['user_del_id']}'";
        $Ztmt = sqlsrv_query($conn, $sql); //执行
        sqlsrv_free_stmt($Ztmt);
    }
    ?>
    <?php
    //判断查询什么信息
    if (isset($_GET['action'])) {
        if ($_GET['action'] == "course_gl") {//查询所有课程信息
            $sql = "SELECT * FROM [course] ";
            $Atmt = sqlsrv_query($conn, $sql);
        }
        if ($_GET['action'] == "tizu_gl") { //查询所有题组信息
            $sql = "SELECT * FROM [tizu] ";
            $Btmt = sqlsrv_query($conn, $sql);
        }
        if ($_GET['action'] == "news_gl") {//查询所有新闻信息 除了内容
            $sql = "SELECT [news_ID],[news_Types],[news_Author],[news_time],[news_title] FROM [news] ";
            $Ctmt = sqlsrv_query($conn, $sql);
        }
        if ($_GET['action'] == "user_gl") {//查询所有用户信息
            $sql = "SELECT * FROM [user] order by user_NO ";
            $Dtmt = sqlsrv_query($conn, $sql);
        }
    }
    ?>
    <?php
    //判断添加什么
    if (isset($_GET['action'])) {
        if ($_GET['action'] == "course_add") {
            //查询课程最大编号
            $sql = "SELECT top 1 [course_ID] FROM [course] order by [course_id] desc  ";
            $Ztmt = sqlsrv_query($conn, $sql);
            $Zow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC);
            $new_course_id = $Zow['course_ID'] + 1; //新的课程ID号
            sqlsrv_free_stmt($Ztmt);
        }
        if ($_GET['action'] == "tizu_add") {
            //查询题组最大编号
            $sql = "SELECT top 1 [tizu_ID] FROM [tizu] order by [tizu_ID] desc  ";
            $Ztmt = sqlsrv_query($conn, $sql);
            $Zow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC);
            $new_tizu_id = $Zow['tizu_ID'] + 1; //新的题组ID号
            sqlsrv_free_stmt($Ztmt);
        }
        if ($_GET['action'] == "news_add") {
            //查询新闻最大编号
            $sql = "SELECT top 1 [news_ID] FROM [news] order by [news_ID] desc  ";
            $Ztmt = sqlsrv_query($conn, $sql);
            $Zow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC);
            $new_news_id = $Zow['news_ID'] + 1; //新的新闻ID号
            sqlsrv_free_stmt($Ztmt);
        }
    }
    ?>
    <?php
    //查询要修改的课程信息
    if (isset($_GET['course_xg_id'])) {
        $sql = "SELECT * FROM [course] where course_ID = {$_GET['course_xg_id']} ";
        $Ztmt = sqlsrv_query($conn, $sql);
        $Dow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC);
        sqlsrv_free_stmt($Ztmt);
    }
    //查询要修改的题组信息
    if (isset($_GET['tizu_xg_id'])) {
        $sql = "SELECT * FROM [tizu] where tizu_ID = {$_GET['tizu_xg_id']} ";
        $Ztmt = sqlsrv_query($conn, $sql);
        $Eow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC);
        sqlsrv_free_stmt($Ztmt);
    }
    //查询要修改的新闻信息
    if (isset($_GET['news_xg_id'])) {
        $sql = "SELECT * FROM [news] where news_ID = {$_GET['news_xg_id']} ";
        $Ztmt = sqlsrv_query($conn, $sql);
        $Fow = sqlsrv_fetch_array($Ztmt, SQLSRV_FETCH_ASSOC);
        sqlsrv_free_stmt($Ztmt);
    }
    ?>
    <body class="body1">
    <center>
        <div >
            <!--显示各种管理链接-->
            <table  border="0" width=800 height=100>
                <tr><td>
                        <table width="100%" height="100%"><tr><td>课程</td></tr><tr><td><a href='manage.php?action=course_gl'>课程管理</a></td></tr><tr><td><a href='manage.php?action=course_add'>课程添加</a></td></tr></table>
                    </td>
                    <td><table width="100%" height="100%"><tr><td>题库</td></tr><tr><td><a href='manage.php?action=tizu_gl'>题库管理</a></td></tr><tr><td><a href='manage.php?action=tizu_add'>题库添加</a></td></tr></table></td>
                    <td><table width="100%" height="100%"><tr><td>新闻</td></tr><tr><td><a href='manage.php?action=news_gl'>新闻管理</a></td></tr><tr><td><a href='manage.php?action=news_add'>新闻添加</a></td></tr></table></td>
                    <td><table width="100%" height="100%"><tr><td>用户</td></tr><tr><td><a href='manage.php?action=user_gl'>用户管理</a></td></tr></table></td>
                </tr>
            </table>

        </div>
    </center>
    <center>
        <?php
        //自动显示课程管理列表
        if (isset($_GET['action'])) {
            if ($_GET['action'] == "course_gl") {
                echo "<div id='kc_gl' style='display:none;'>";
                echo "<table border='1' width=800>";
                echo "<tr><td colspan='7' style='font-size:20px'>课程管理</td></tr>";
                echo "<tr><td>课程ID</td><td>分类</td><td>课程名称</td><td>课程简介</td><td colspan='3'>操作</td></tr>";
                while ($Aow = sqlsrv_fetch_array($Atmt, SQLSRV_FETCH_ASSOC)) {
                    echo "<tr>"
                    . "<td>{$Aow['course_ID']}</td>"
                    . "<td>{$Aow['course_Types']}</td>"
                    . "<td><textarea  cols='25' rows='2' readonly>{$Aow['course_name']}</textarea> </td>"
                    . "<td><textarea cols='30' rows='5' readonly>{$Aow['course_Introduction']}</textarea></td>"
                    . "<td><a href='ch_edit.php?course_id={$Aow['course_ID']}'>章节管理</a></td>"
                    . "<td><a href='manage.php?course_xg_id={$Aow['course_ID']}'>修改</a></td>"
                    . "<td><a href='manage.php?action=course_gl&course_del_id={$Aow['course_ID']}'>删除</a></td>"
                    . "</tr>";
                }
                echo "</table>
        </div>";
            }
            //自动显示题组管理列表
            if ($_GET['action'] == "tizu_gl") {
                echo "<div id='tz_gl'style='display:none;' >";
                echo "<table border='1' width=800>";
                echo "<tr><td colspan='7' style='font-size:20px'>题组管理</td></tr>";
                echo "<tr><td>题组ID</td><td>分类</td><td>题组名称</td><td>题组简介</td><td colspan='3'>操作</td></tr>";

                while ($Bow = sqlsrv_fetch_array($Btmt, SQLSRV_FETCH_ASSOC)) {
                    echo "<tr><td>{$Bow['tizu_ID']}</td>"
                    . "<td>{$Bow['tizu_Types']}</td>"
                    . "<td><textarea  cols='25' rows='2' readonly>{$Bow['tizu_name']}</textarea> </td>"
                    . "<td><textarea cols='30' rows='5' readonly>{$Bow['tizu_Introduction']}</textarea></td>"
                    . "<td><a href='qs_edit.php?tizu_id={$Bow['tizu_ID']}'>题组管理</a></td>"
                    . "<td><a href='manage.php?tizu_xg_id={$Bow['tizu_ID']}'>修改</a></td>"
                    . "<td><a href='manage.php?action=tizu_gl&tizu_del_id={$Bow['tizu_ID']}'>删除</a></td>"
                    . "</tr>";
                }
                echo "</table>
        </div>";
            }
            //自动显示新闻管理列表
            if ($_GET['action'] == "news_gl") {
                echo "<div id='xw_gl' style='display:none;'><table border='1' width=800>";
                echo "<tr><td colspan='8' style='font-size:20px'>新闻管理</td></tr>";
                echo "<tr><td>新闻ID</td><td>新闻类型</td><td>新闻标题</td><td>新闻作者</td><td>发布时间</td><td>新闻内容</td><td colspan='2'>操作</td></tr>";
                while ($Cow = sqlsrv_fetch_array($Ctmt, SQLSRV_FETCH_ASSOC)) {
                    echo "<tr><td>{$Cow['news_ID']}</td>";
                    if ($Cow['news_Types'] == '1') {
                        echo "<td>新闻</td>";
                    }
                    if ($Cow['news_Types'] == '2') {
                        echo "<td>通知</td>";
                    }
                    echo "<td><textarea cols='30' rows='2' readonly>{$Cow['news_title']}</textarea></td>";
                    echo "<td>{$Cow['news_Author']}</td><td>{$Cow['news_time']}</td>";
                    echo "<td>修改可见</td>";
                    echo "<td><a href='manage.php?news_xg_id={$Cow['news_ID']}'>修改</a></td>";
                    echo "<td><a href='manage.php?action=news_gl&news_del_id={$Cow['news_ID']}'>删除</a></td>";
                    echo "</tr>";
                }
                echo " </table>
         </div>";
            }
            //自动显示用户管理列表
            if (isset($_GET['action'])) {
                if ($_GET['action'] == "user_gl") {
                    echo "<div id='yh_gl' style='display:none;'>";
                    echo "<table border='1' width=800>";
                    echo "<tr><td colspan='7' style='font-size:20px'>用户管理</td></tr>";
                    echo "<tr><td>用户编号</td><td>权限</td><td>账号</td><td>密码</td><td>昵称</td><td>电话</td><td >操作</td></tr>";
                    while ($Dow = sqlsrv_fetch_array($Dtmt, SQLSRV_FETCH_ASSOC)) {
                        echo "<tr>"
                        . "<td>{$Dow['user_NO']}</td>"
                        . "<td>{$Dow['user_ability']}</td>"
                        . "<td>{$Dow['user_ID']}</td>"
                        . "<td><input type='password' readonly value='{$Dow['user_password']}'</td>"
                        . "<td>{$Dow['user_name']}</td>"
                        . "<td>{$Dow['user_phone']}</td>"
                        . "<td><a href='manage.php?action=user_gl&user_del_id={$Dow['user_ID']}'>删除</a></td>"
                        . "</tr>";
                    }
                    echo "</table>
        </div>";
                }
            }
        }
        ?>
        <!--自动显示课程添加框-->
        <div id="kc_add" style="display:none;">
            <form name="kcadd" id="frm_kc_add" method="post" action="manage.php?action=course_gl&course_add_sql=1">
                <center>
                    <table border="1" width="800" height="400">
                        <tr><td colspan="6" style="font-size:20px;">课程添加</td></tr>
                        <tr height="15%"><td width="80" >课程编号</td><td width="50"><input style="width:50px" id="kc_add_id" type="text" name="course_id" value="<?php
                                if (isset($_GET['action'])) {
                                    if ($_GET['action'] == "course_add") {
                                        echo $new_course_id;
                                    }
                                }
                                ?>" ></td>
                            <td>分类:</td><td><input list="course_types_add"  name="course_types"/>
                                <datalist id="course_types_add">
                                    <option value="语言类">
                                    <option value="科学类">
                                    <option value="计算类">
                                    <option value="思想类">
                                    <option value="艺术类"> 
                                </datalist></td>
                            <td width="80">课程名:</td><td><input style="width:500px" type="text" id="kc_add_name" name="course_name"></td></tr>
                        <tr height="70%"><td width="80">课程简介:</td><td colspan="5"><textarea name="course_introduction" cols="90" rows="15"></textarea></td></tr>
                        <tr height="15%"><td colspan="6" style=""><input type="button" value="添加" class="button blue medium" onclick="kc_addjc()">&nbsp;&nbsp;&nbsp;&nbsp; <a href="manage.php?action=course_gl"><input type="button" value="取消" class="button gray medium"></a></td></tr>
                    </table>
                </center>
            </form>
        </div>
        <!--自动显示课程修改框和课程信息-->
        <div id="kc_xg" style="display:none;">
            <form name="kcxg" id="frm_kc_xg" method="post" action="manage.php?action=course_gl&course_xg_sql=1&course_xg_old_id=<?php echo $_GET['course_xg_id']; ?>">
                <center>
                    <table border="1" width="800" height="400">
                        <tr><td colspan="6" style="font-size:20px;">课程修改</td></tr>
                        <tr height="15%"><td width="80" >课程编号</td><td width="50"><input style="width:50px" type="text" id="kc_xg_id" name="course_id" value="<?php
                                if (isset($_GET['course_xg_id'])) {
                                    echo $Dow['course_ID'];
                                }
                                ?>" ></td>
                            <td>分类</td><td>
                                <input list="course_types_xg"  name="course_types" value="<?php echo $Dow['course_Types'] ?>"/>
                                <datalist id="course_types_xg">
                                    <option value="语言类">
                                    <option value="科学类">
                                    <option value="计算类">
                                    <option value="思想类">
                                    <option value="艺术类"> 
                                </datalist></td>
                            <td width="80">课程名:</td><td><input style="width:500px" type="text" id="kc_xg_name" name="course_name" value="<?php
                                if (isset($_GET['course_xg_id'])) {
                                    echo $Dow['course_name'];
                                }
                                ?>"></td></tr>
                        <tr height="70%"><td width="80">课程简介:</td><td colspan="5"><textarea name="course_introduction" cols="90" rows="15"><?php
                                    if (isset($_GET['course_xg_id'])) {
                                        echo "{$Dow['course_Introduction']}";
                                    }
                                    ?></textarea></td></tr>
                        <tr height="15%"><td colspan="6" style=""><input type="button" value="修改" class="button blue medium" onclick="kc_xgjc()">&nbsp;&nbsp;&nbsp;&nbsp;<a href="manage.php?action=course_gl"><input type="button" value="取消" class="button gray medium"></a></td></tr>
                    </table>
                </center>
            </form>
        </div>
        <!--自动显示题组添加框-->
        <div id="tz_add" style="display:none;">
            <form name="tzadd" id="frm_tz_add" method="post" action="manage.php?action=tizu_gl&tizu_add_sql=1">
                <center>
                    <table border="1" width="800" height="400">
                        <tr><td colspan="6" style="font-size:20px;">题组添加</td></tr>
                        <tr height="15%"><td width="80" >题组编号</td>
                            <td width="50"><input style="width:50px" type="text" id="tz_add_id" name="tizu_id"value="<?php
                                if (isset($_GET['action'])) {
                                    if ($_GET['action'] == "tizu_add") {
                                        echo $new_tizu_id;
                                    }
                                }
                                ?>" ></td>
                            <td>分类</td><td> <input list="tizu_types_add" name="tizu_types"/>
                                <datalist id="tizu_types_add">
                                    <option value="语言类">
                                    <option value="计算类">
                                    <option value="科学类">
                                    <option value="计算机类">
                                    <option value="思想类">
                                    <option value="文化类"> 
                                </datalist></td>
                            <td width="80">题组名:</td><td><input style="width:500px" type="text" id="tz_add_name" name="tizu_name"></td></tr>
                        <tr height="70%"><td width="80">题组简介:</td><td colspan="5"><textarea name="tizu_introduction" cols="90" rows="15"></textarea></td></tr>
                        <tr height="15%"><td colspan="6" style=""><input type="button" value="添加" class="button blue medium" onclick="tz_addjc()">&nbsp;&nbsp;&nbsp;&nbsp;<a href='manage.php?action=tizu_gl'><input type="button" value="取消" class="button gray medium"></a></td></tr>
                    </table>
                </center>
            </form>
        </div>
        <!--自动显示题组修改框和修改信息-->
        <div id="tz_xg" style="display:none;">
            <form name="tzxg" id="frm_tz_xg" method="post" action="manage.php?action=tizu_gl&tizu_xg_sql=1&tizu_xg_old_id=<?php echo $_GET['tizu_xg_id']; ?>">
                <center>
                    <table border="1" width="800" height="400">
                        <tr><td colspan="6" style="font-size:20px;">题组修改</td></tr>
                        <tr height="15%"><td width="80" >题组编号</td>
                            <td width="50"><input style="width:50px" type="text" id="tz_xg_id" name="tizu_id" value="<?php
                                if (isset($_GET['tizu_xg_id'])) {
                                    echo $Eow['tizu_ID'];
                                }
                                ?>" ></td>
                            <td>分类</td><td> <input list="tizu_types_xg" name="tizu_types" value="<?php echo $Eow['tizu_Types'] ?>"/>
                                <datalist id="tizu_types_xg">
                                    <option value="语言类">
                                    <option value="计算类">
                                    <option value="科学类">
                                    <option value="计算机类">
                                    <option value="思想类">
                                    <option value="文化类"> 
                                </datalist></td>
                            <td width="80">题组名:</td><td><input style="width:500px" type="text" id="tz_xg_name" name="tizu_name" value="<?php
                                if (isset($_GET['tizu_xg_id'])) {
                                    echo $Eow['tizu_name'];
                                }
                                ?>"></td></tr>
                        <tr height="70%"><td width="80">题组简介:</td><td colspan="5"><textarea name="tizu_introduction" cols="90" rows="15"> <?php
                                    if (isset($_GET['tizu_xg_id'])) {
                                        echo "{$Eow['tizu_Introduction']}";
                                    }
                                    ?></textarea></td></tr>
                        <tr height="15%"><td colspan="6" style=""><input type="button" value="修改" class="button blue medium" onclick="tz_xgjc()">&nbsp;&nbsp;&nbsp;&nbsp;<a href='manage.php?action=tizu_gl'><input type="button" value="取消" class="button gray medium"></a></td></tr>
                    </table>
                </center>
            </form>
        </div>
        <!--自动显示新闻添加框-->
        <div id='xw_add' style="display:none;">
            <form name="xwadd" id="frm_xw_add" method="post" action="manage.php?action=news_gl&news_add_sql=1">
                <center>
                    <table border="1" width="800" height="600">
                        <tr><td colspan="6" style="font-size:20px;height:20px">新闻添加</td></tr>
                        <tr height='8%'><td>新闻ID：</td><td><input style="width:50px" type="text" id="xw_add_id" name="news_id"value="<?php
                                if (isset($_GET['action'])) {
                                    if ($_GET['action'] == "news_add") {
                                        echo $new_news_id;
                                    }
                                }
                                ?>"></td>
                            <td>类型：</td><td>
                                <select name="news_types"><option value="1">新闻</option><option value="2">通知</option></select>

                            </td>
                            <td>时间:</td><td><input style="width:150px" type="text" name="news_time" value="<?php echo date('Y-m-d', time()); ?>" readonly></td>
                        </tr>
                        <tr height='8%'><td>新闻标题:</td><td colspan="5"><input style="width:450px" type="text" id="xw_add_title" name="news_title"value=""></td></tr>
                        <tr height='8%'><td>作者:</td><td colspan="5"><input style="width:150px" type="text"  name="news_author"value=""></td></tr>
                        <tr height='8%'><td colspan="6">内容</td></tr>
                        <tr><td colspan='6' style="text-align: left"> <div id="div1" class="toolbar"></div><div id="news_add_editor" style="height:500px;width:800px"></div> </td></tr>
                        <tr height='15%'><td colspan="6"><input type="button" value="添加" class="button blue medium" onclick="xw_addjc()">&nbsp;&nbsp;&nbsp;&nbsp;<a href='manage.php?action=news_gl'><input type="button" value="取消" class="button gray medium"></a> </td></tr>
                    </table>
                </center>
                <!--隐藏的富文本监听文本框-->
                <div style="display:none;position:fixed; top:440px;right:0px;"> <textarea id="news_add_text"  name="news_add_text" style="width:800px; height:600px;"readonly></textarea></div>
            </form>
        </div>
        <!--自动显示新闻修改框和修改信息-->
        <div id='xw_xg' style="display:none;">
            <form name="xwxg" id="frm_xw_xg" method="post" action="manage.php?action=news_gl&news_xg_sql=1&news_xg_old_id=<?php echo $_GET['news_xg_id']; ?>">
                <center>
                    <table border="1" width="800" height="600">
                        <tr><td colspan="6" style="font-size:20px;height:20px">新闻修改</td></tr>
                        <tr height='8%'><td>新闻ID：</td><td><input style="width:50px" type="text" id="xw_xg_id" name="news_id" value="<?php
                                if (isset($_GET['news_xg_id'])) {
                                    echo $Fow['news_ID'];
                                }
                                ?>" ></td>
                            <td>类型：</td><td>

                                <?php
                                if (isset($_GET['news_xg_id'])) {
                                    if ($Fow['news_Types'] == '1') {
                                        echo "<select name='news_types'><option value='1' selected>新闻</option><option value='2' >通知</option></select>";
                                    }
                                    if ($Fow['news_Types'] == '2') {
                                        echo "<select name='news_types'><option value='1'>新闻</option><option value='2' selected>通知</option></select>";
                                    }
                                }
                                ?></td>
                            <td>时间:</td><td><input style="width:150px" type="text" name="news_time" value="<?php
                                if (isset($_GET['news_xg_id'])) {
                                    echo $Fow['news_time'];
                                }
                                ?>" ></td>
                        </tr>
                        <tr height='8%'><td>新闻标题:</td><td colspan="5"><input style="width:250px" type="text" id="xw_xg_title" name="news_title" value="<?php
                                if (isset($_GET['news_xg_id'])) {
                                    echo $Fow['news_title'];
                                }
                                ?>"></td></tr>
                        <tr height='8%'><td>作者:</td><td colspan="5"><input style="width:150px" type="text" name="news_author"value="<?php
                                if (isset($_GET['news_xg_id'])) {
                                    echo $Fow['news_Author'];
                                }
                                ?>"></td></tr>
                        <tr height='8%'><td colspan="6">内容</td></tr>
                        <tr ><td colspan='6' style="text-align: left"> <div id="div2" class="toolbar"></div><div id="news_xg_editor" style="height:500px;width:800px"><p><?php
                                        if (isset($_GET['news_xg_id'])) {
                                            echo $Fow['news_content'];
                                        }
                                        ?></p></div> </td></tr>
                        <tr height='15%'><td colspan="6"><input type="button" value="修改" class="button blue medium" onclick="xw_xgjc()">&nbsp;&nbsp;&nbsp;&nbsp;<a href='manage.php?action=news_gl'><input type="button" value="取消" class="button gray medium"></a> </td></tr>
                    </table>
                </center>
                 <!--隐藏的富文本监听文本框-->
                <div style="display:none;position:fixed; top:440px;right:0px;"> <textarea id="news_xg_text" name="news_xg_text" style="width:800px; height:600px;"readonly></textarea></div>
            </form>
        </div>

    </center>
</body>

<script type="text/javascript">
    function xskc_gl() { //显示课程管理
        document.getElementById("kc_gl").style.display = "block";
    }
    function xskc_add() {//显示课程添加
        document.getElementById("kc_add").style.display = "block";
    }
    function xskc_xg() {//显示课程修改
        document.getElementById("kc_xg").style.display = "block";
    }
    function xszj_gl() {
        document.getElementById("zj_gl").style.display = "block";
    }
    function xstz_gl() {//显示题组管理
        document.getElementById("tz_gl").style.display = "block";
    }
    function xstz_add() {//显示题组添加
        document.getElementById("tz_add").style.display = "block";
    }
    function xstz_xg() {//显示题组修改
        document.getElementById("tz_xg").style.display = "block";
    }
    function xstm_gl() {
        document.getElementById("tm_gl").style.display = "block";
    }
    function xsxw_gl() { //显示新闻管理
        document.getElementById("xw_gl").style.display = "block";
    }
    function xsxw_add() { //显示新闻添加
        document.getElementById("xw_add").style.display = "block";
    }
    function xsxw_xg() { //显示新闻修改
        document.getElementById("xw_xg").style.display = "block";
    }
    function xsyh_gl() { //显示用户管理
        document.getElementById("yh_gl").style.display = "block";
    }
</script>
<script src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/wangEditor.js"></script>
<script type="text/javascript">
    var E = window.wangEditor;
    //新闻添加的富文本框和监听textarea

    var news_add_editor = new E('#div1', '#news_add_editor');
    var $news_add_text = $('#news_add_text');
    //                代码保存图片
            news_add_editor.customConfig.uploadImgShowBase64 = true; 
    news_add_editor.customConfig.onchange = function (html) {
        // 监控变化，同步更新到 textarea
        $news_add_text.val(html);
    };
    news_add_editor.customConfig.pasteFilterStyle = false;
    news_add_editor.create();
    // 初始化 textarea 的值
    $news_add_text.val(news_add_editor.txt.html());
    //--------------------------------------------------------------------
    //新闻修改的富文本框和监听textarea
    var news_xg_editor = new E('#div2', '#news_xg_editor');
    var $news_xg_text = $('#news_xg_text');
    //                代码保存图片
            news_xg_editor.customConfig.uploadImgShowBase64 = true; 
    news_xg_editor.customConfig.onchange = function (html) {
        // 监控变化，同步更新到 textarea
        $news_xg_text.val(html);
    };
    news_xg_editor.customConfig.pasteFilterStyle = false;
    news_xg_editor.create();
    // 初始化 textarea 的值
    $news_xg_text.val(news_xg_editor.txt.html());

//检查填写情况，若不符合条件则阻止提交表单
    function kc_addjc() {
        if (document.getElementById("kc_add_name").value == "") {
            alert("课程名称为空！");
        } else if (document.getElementById("kc_add_id").value == "") {
            alert("课程ID为空！");
        } else {
            //提交表单
            var form1 = document.getElementById('frm_kc_add');
            form1.submit();
        }
    }
    function kc_xgjc() {
        if (document.getElementById("kc_xg_name").value == "") {
            alert("课程名称为空！");
        } else if (document.getElementById("kc_xg_id").value == "") {
            alert("课程ID为空！");
        } else {
            //提交表单
            var form2 = document.getElementById('frm_kc_xg');
            form2.submit();
        }
    }
    function tz_addjc() {
        if (document.getElementById("tz_add_name").value == "") {
            alert("题组名称为空！");
        } else if (document.getElementById("tz_add_id").value == "") {
            alert("题组ID为空！");
        } else {
            //提交表单
            var form4 = document.getElementById('frm_tz_add');
            form4.submit();
        }
    }
    function tz_xgjc() {
        if (document.getElementById("tz_xg_name").value == "") {
            alert("题组名称为空！");
        } else if (document.getElementById("tz_xg_id").value == "") {
            alert("题组ID为空！");
        } else {
            //提交表单
            var form3 = document.getElementById('frm_tz_xg');
            form3.submit();
        }
    }
    function xw_addjc() {
        if (document.getElementById("xw_add_title").value == "") {
            alert("新闻标题为空！");
        } else if (document.getElementById("xw_add_id").value == "") {
            alert("新闻ID为空！");
        } else {
            //提交表单
            var form5 = document.getElementById('frm_xw_add');
            form5.submit();
        }
    }
    function xw_xgjc() {
        if (document.getElementById("xw_xg_title").value == "") {
            alert("新闻标题为空！");
        } else if (document.getElementById("xw_xg_id").value == "") {
            alert("新闻ID为空！");
        } else {
            //提交表单
            var form6 = document.getElementById('frm_xw_xg');
            form6.submit();
        }
    }
</script>


</html>

<?php
//自动显示管理框
if (isset($_GET['course_xg_id'])) {
    echo"<script type='text/javascript'> window.onload=function(){xskc_xg();};</script>";
}
if (isset($_GET['tizu_xg_id'])) {
    echo"<script type='text/javascript'> window.onload=function(){xstz_xg();};</script>";
}
if (isset($_GET['news_xg_id'])) {
    echo"<script type='text/javascript'> window.onload=function(){xsxw_xg();};</script>";
}
if (isset($_GET['action'])) {
    if ($_GET['action'] == "course_gl") { //课程管理
        echo"<script type='text/javascript'> window.onload=function(){xskc_gl();};</script>";
    }
    if ($_GET['action'] == "course_add") { //课程添加
        echo"<script type='text/javascript'> window.onload=function(){xskc_add();};</script>";
    }
    if ($_GET['action'] == "chapter_gl") {//章节管理 二阶管理
        echo"<script type='text/javascript'> window.onload=function(){xszj_gl();};</script>";
    }
    if ($_GET['action'] == "tizu_gl") {//题组管理
        echo"<script type='text/javascript'> window.onload=function(){xstz_gl();};</script>";
    }
    if ($_GET['action'] == "tizu_add") {//题组添加
        echo"<script type='text/javascript'> window.onload=function(){xstz_add();};</script>";
    }
    if ($_GET['action'] == "timu_gl") {//题组管理 二阶管理
        echo"<script type='text/javascript'> window.onload=function(){xstm_gl();};</script>";
    }
    if ($_GET['action'] == "news_gl") {//新闻管理
        echo"<script type='text/javascript'> window.onload=function(){xsxw_gl();};</script>";
    }
    if ($_GET['action'] == "news_add") {//新闻添加
        echo"<script type='text/javascript'> window.onload=function(){xsxw_add();};</script>";
    }
    if ($_GET['action'] == "user_gl") { //用户管理
        echo"<script type='text/javascript'> window.onload=function(){xsyh_gl();};</script>";
    }
}
//清理和关闭连接
if (isset($_GET['action'])) {
    if ($_GET['action'] == "course_gl") {
        sqlsrv_free_stmt($Atmt);
    }
    if ($_GET['action'] == "tizu_gl") {
        sqlsrv_free_stmt($Btmt);
    }
    if ($_GET['action'] == "news_gl") {
        sqlsrv_free_stmt($Ctmt);
    }
}
//关闭和清理连接
sqlsrv_close($conn);
?>