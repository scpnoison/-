
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
        <title>登录</title>
        <style>
            table{border-collapse:collapse;border:1px solid #000;background-color:rgba(255,255,255,0.5)}
            input{width:100px}
        </style>
    </head> 
    <body class="body1">
        <?php
        include("menu.php"); //导入导航栏
        ?>
        <p></p>
    <center>
        <!--显示新闻轮播图-->
        <table width="1200" height="600" style="background-color:rgba(255,255,255,0);border:0px solid #000;">
                    <tr> 
                        <td>
                            <!--<img width="750" height="360" src="img/01.jpg">-->
                   <iframe  name="b1" src="新闻轮播.html" width="800" height="360"  frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling="no" allowtransparency="yes"></iframe>
                </td>
                <td width=30% style="text-align: center"> 
                    <!--登录框-->
                    <div id="dl" style="z-index:9999;display: block; border: 0px solid #000;" >
                        <form name="frm1" id="dlbd" method="post" action="login.php?action=login" >
                            <table	border="0.5" bordercolor="black" width="80%" height="300" cellspacing="0" bgcolor="##FFFFFF">
                                <tr>
                                    <td><table width="100%" height="150" >
                                            <caption >用户登录</caption>
                                            <tr  align="center">
                                                <td>用户名</td>
                                                <td><input type="text" name="user_ID" style="width:150px;" id="yh" class="input"></td>
                                            </tr>
                                            <tr align="center">
                                                <td>密码</td>
                                                <td><input type="password" name="user_password" style="width:150px;" id="dlmm" class="input"></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr><br>
                                <tr>
                                    <td><table width="100%" height="150" >
                                            <tr align="center">
                                                <td><input type="button"  value="登录" style="align:center;height:30px;" class='button blue small'onclick="dl()">&nbsp;&nbsp;<input type="button" value="注册" style="align:center;height:30px;" class='button blue small' onClick="zc()"></td>
                                            </tr>
                                            
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                    <!--注册框-->
                    <div id="zc" style="display:none;">
                        <form name="frm2" id="zcbd" method="post" action="login.php?action=register" >
                            <table border="1" cellspacing="0" width="80%" height="100%" >
                                <tr>
                                    <td>
                                        <table border="0" width="100%" height="350" cellspacing="0" align="center" cellpadding="0">
                                            <tr align="center">
                                                <td width="100">账号</td>
                                                <td colspan="2"><input type="text" name="re_user_ID" style="width:150px;" id="zh" onKeyUp="value = value.replace(/[\W]/g, '')"></td>
                                            </tr>
                                            <tr align="center">
                                                <td>密码</td>
                                                <td colspan="2"><input type="password" name="re_user_password" style="width:150px;" id="mm1" onKeyUp="value = value.replace(/[\W]/g, '')"></td>
                                            </tr>
                                            <tr align="center">
                                                <td >确认密码</td>
                                                <td colspan="2"><input type="password" style="width:150px;" id="mm2" onKeyUp="value = value.replace(/[\W]/g, '')"></td>
                                            </tr>
                                            <tr align="center">
                                                <td>昵称</td>
                                                <td colspan="2"><input type="text" name="re_user_name" style="width:150px;" id="nc"></td>
                                            </tr>
                                            <tr align="center">
                                                <td>手机号</td>
                                                <td colspan="2"><input type="text" name="re_user_phone" style="width:150px;" id="sj" onKeyUp="this.value = this.value.replace(/[^0-9-]+/, '')"></td>
                                            </tr>
                                            <tr > <td id="xx" style="display:none;text-align: center">提示信息</td></tr>
                                            <tr align="center">
                                               
                                                <td style="text-align: center;"><input type="button" value="注册" class='button blue small' style="height:30px;" onClick="zc1()"></td>
                                                <td style="text-align: center;"><input type="button" value="取消"  class='button blue small'style="height:30px;"onClick="qx()"></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </td></tr>
        </table>
    </center>
</body>
<script type="text/javascript">
//    检查输入正确性的脚本
    function zc() {
        document.getElementById("dl").style.display = "none";
        document.getElementById("zc").style.display = "block";
    }
    function qx() {
        document.getElementById("zc").style.display = "none";
        document.getElementById("dl").style.display = "block";
    }
    function zc1() {
        if (document.getElementById("zh").value == "") {
            document.getElementById("xx").style.display = "table-cell";
            document.getElementById("xx").innerHTML = "账号为空!";
            return false;
        } else

        if (document.getElementById("mm1").value == "") {
            document.getElementById("xx").style.display = "table-cell";
            document.getElementById("xx").innerHTML = "两次密码不有为空";
        } else

        if (document.getElementById("mm2").value == "") {
            document.getElementById("xx").style.display = "table-cell";
            document.getElementById("xx").innerHTML = "两次密码不有为空";
        } else

        if (document.getElementById("mm1").value !== document.getElementById("mm2").value) {
            document.getElementById("xx").style.display = "table-cell";
            document.getElementById("xx").innerHTML = "两次密码不一致!";
        } else

        if (document.getElementById("nc").value == "") {
            document.getElementById("xx").style.display = "table-cell";
            document.getElementById("xx").innerHTML = "昵称为空！";
        } else

        if (document.getElementById("sj").value == "") {
            document.getElementById("xx").style.display = "table-cell";
            document.getElementById("xx").innerHTML = "当前手机号为空！";
        } else {
            document.getElementById("xx").style.display = "none";
            var form2 = document.getElementById('zcbd');
            zcbd.submit();
        }
    }
//    检查输入的登录信息并提交表单
    function dl() {
        if (document.getElementById("yh").value == "") {
            alert("用户名不能为空！");
        } else if (document.getElementById("dlmm").value == "") {
            alert("密码不能为空!");
        } else {
            //提交表单
            var form1 = document.getElementById('dlbd');
            form1.submit();
        }

    }
</script>	
</html>


