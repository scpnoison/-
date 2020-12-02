
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
        <title>首页</title>
        <link rel="stylesheet" href="css/base1.css">
        <link rel="stylesheet" type="text/css" href="css/buttons/buttons.css" />
        <style>
            .mytd1{
                text-align: right;
            }
            .dd{text-overflow:ellipsis;overflow:hidden;}
            .body1{ background:url(img/bg2.jpg);background-size:100%;background-attachment:fixed;}
            /*            background-repeat:no-repeat;*/
            .body2{ background:url(img/t2.jpg);background-size:100%;background-attachment:fixed;}
            a:link {color:black;}
            a:hover {text-decoration:underline;color:red;}
            a:active {text-decoration:none;color:yellow;}
            a:visited {text-decoration:none;color:black;}
            .return-top{
                width:30px;
                height:30px;
                font:bold 10px/30px "宋体";
                color:#fafafa;
                background-color:#8d6e63;
                position:fixed;
                bottom:20px;
                right:20px;
                text-align:center;
                cursor:pointer;
                display:none;
            }
            .return-top.active{
                display:block;
            }
            input{width:100px}
        </style>
    </head>
    <body>
        <?php
        include ('lianjie.php'); //导入标题栏
        //查询所有的课程分类
        $sql = "SELECT distinct [course_Types] FROM [course]";
        $CTLtmt = sqlsrv_query($conn, $sql);
        $sql = "SELECT distinct [tizu_Types] FROM [tizu]";
        $TZLtmt = sqlsrv_query($conn, $sql);
        session_start();
        header('Content-Type:text/html; charset=utf-8');
        ?>
    <center>
        <!--包含超链接的导航栏-->
        <table border="1" name="tb1" cellspacing="0" class="w-all">
            <tr align="center">
                <td width="245" class="sy">
                    <a href='home.php'  >首页</a> 
                </td>
                <td width="245"  class="noDecor" onMouseOver="document.getElementById('xw').style.visibility = 'visible'" onMouseOut="document.getElementById('xw').style.visibility = 'hidden'">
                    <a href='news.php'  >新闻</a><br/>
                    <table class="noShow" id="xw"  style="visibility: hidden;position: absolute" >
                        <tr >
                            <td class='mytd'><a href='news.php'>新闻看看</a></td>
                        </tr>
                        <tr>
                            <td class='mytd'><a href='Notice.php'>网站通知</a></td>
                        </tr>

                    </table>
                </td>
                <td width="245"   class="noDecor" onMouseOver="document.getElementById('kc').style.visibility = 'visible'" onMouseOut="document.getElementById('kc').style.visibility = 'hidden'">
                    <a href='course.php'  >课程</a><br/>
                    <table class="noShow" id="kc"  style="visibility: hidden;position: absolute" >

                        <?php
                        //显示已有课程分类
                        while ($CTTow = sqlsrv_fetch_array($CTLtmt, SQLSRV_FETCH_ASSOC)) {
                            echo " <tr ><td class='mytd'><a href='course.php?type={$CTTow['course_Types']}'>{$CTTow['course_Types']}</a></td> </tr>";
                        }
                        sqlsrv_free_stmt($CTLtmt);
                        ?>
                    </table>


                </td>
                <td width="245"   class="noDecor" onMouseOver="document.getElementById('tk').style.visibility = 'visible'" onMouseOut="document.getElementById('tk').style.visibility = 'hidden'">
                    <a href='tizu.php'  >题库</a><br/>
                    <table class="noShow" id="tk"  style="visibility: hidden;position: absolute" >
                        <?php
                        //显示题组分类链接
                        while ($TZLow = sqlsrv_fetch_array($TZLtmt, SQLSRV_FETCH_ASSOC)) {
                            echo " <tr ><td class='mytd'><a href='tizu.php?type={$TZLow['tizu_Types']}'>{$TZLow['tizu_Types']}</a></td> </tr>";
                        }
                        sqlsrv_free_stmt($TZLtmt);
                        ?>

                    </table>
                </td>
                <td width="293" class="qz"><a href="forum.php" >圈子论坛</a></td>
                <td width="261" class="zn"><a href="网站指南.php" >网站指南</a></td>
            </tr>
        </table>
    </center>
    <p/>
    <div>
        <form name="frm1" >
            <table border="0" cellspacing="0" name="tb2" width="800" height="50" align="center">
                <tr>
                    <!--判断网页当前位置-->
                    <td  style="font-size: 14px;text-align:right">当前位置 <?php
                        switch ($_SERVER['PHP_SELF']) {
                            case "/web_Design/index.php":echo "登录页面";
                                break;
                            case "/web_Design/home.php":echo "主页";
                                break;
                            case "/web_Design/Exercise.php":echo "练习";
                                break;
                            case "/web_Design/Notice.php":echo "通知";
                                break;
                            case "/web_Design/ch_edit.php":echo "章节编辑";
                                break;
                            case "/web_Design/course.php":echo "课程浏览";
                                break;
                            case "/web_Design/look_news.php":echo "新闻浏览";
                                break;
                            case "/web_Design/look_qs.php":echo "题目浏览";
                                break;
                            case "/web_Design/manage.php":echo "管理中心";
                                break;
                            case "/web_Design/mypage.php":echo "个人中心";
                                break;
                            case "/web_Design/news.php":echo "新闻列表";
                                break;
                            case "/web_Design/qs_edit.php":echo "题目编辑";
                                break;
                            case "/web_Design/study.php":echo "课程学习";
                                break;
                            case "/web_Design/tizu.php":echo "题组浏览";
                                break;
                            case "/web_Design/forum.php":echo "圈子论坛";
                                break;
                            case "/web_Design/floor.php":echo "查看帖子";
                                break;
                        }
                        ?></td>
                    <td  bordercolor="white" class="mytd1">
                        <?php
//                        显示用户信息或者显示登录按钮
                        if (!isset($_SESSION['user_name'])) {
                            echo "<a href='index.php'><input type='button' class='button orange small' value='请先登录'></a>";
                        } else {
                            echo '欢迎，', $_SESSION['user_name'];
                            echo ' <a href="login.php?action=logout">注销</a> ';
                            //查询用户权限
                            $sql = "SELECT [user_ability] FROM [user] where user_ID = '{$_SESSION['user_ID']}'";
                            $TTtmt = sqlsrv_query($conn, $sql);
                            $TTow = sqlsrv_fetch_array($TTtmt, SQLSRV_FETCH_ASSOC);
                            sqlsrv_free_stmt($TTtmt);
                            if ($TTow['user_ability'] == '2') {
                                //若是管理员显示管理网站的按钮
                                echo "<a href='manage.php?id={$_SESSION['user_ID']}&action=course_gl'><input type='button'class='button blue small' value='管理网站' style='align:center;height:30px'></a>";
                            }
                            echo " <a href='mypage.php?id={$_SESSION['user_ID']}'> <input type='button'class='button orange small' value='个人中心' style='align:center;height:30px'></a>";
                        }
                        ?>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <div class="return-top" id="return_top">▲</div>
    <script>
//        回到顶部的功能
        /*
         思路：
         第一步：当页面加载完后，获取所要操作的 节点对象
         第二步：为window对象添加一个滚动条滚动事件onscroll
         第三步：
         在滚动条滚动的过程中，不断的获取滚动条距离顶部的距离数值
         当这个数值大于300的时候，显示出回到顶部图标
         否则，隐藏回到顶部图标
         第四步：
         为回到顶部图标添加一个点击事件，滚动条回到顶部。            
         */
        var return_top = document.getElementById("return_top");
        var sTop;
        window.onscroll = function () {
            //sTop :滚动条距离顶部的距离数值
            sTop = document.body.scrollTop || document.documentElement.scrollTop;
            if (sTop > 10) {
                return_top.className = "return-top active";
            } else {
                return_top.className = "return-top";
            }
        }
        return_top.onclick = function () {
            var termId = setInterval(function () {
                sTop -= 800;
                if (sTop <= 0) {
                    clearInterval(termId);
                }
                window.scrollTo(0, sTop);
            }, 1);
        }
    </script>
</body>

</html>
<?php
//关闭数据库连接
sqlsrv_close($conn);
?>