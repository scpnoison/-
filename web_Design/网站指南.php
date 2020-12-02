<!DOCTYPE html>
<?php include 'menu.php';?>   
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>网站指南</title>
<style type="text/css">
body,html{
	margin: 0;
	padding: 0;
	overflow: hidden;
}
.screen{
	width: 100%;
	height: 100%;
	background-repeat: no-repeat;
	background-size: 100%;
	background-position: 50% 50%;
	text-align: center;
}
.screen img{
	width: 100%;
	height: 100%;
}
#screen1{background:url(images/c2/1.jpg);}
#screen2{background:url(images/c2/2.jpg);}

#tag{
	padding: 20px 50px;
	background-color: #fff;
	border-radius: 50px;
	position: fixed;
	right: 50px;
	top: 50px;
	z-index: 999;
	color: orange;
	font-size: 36px;
	text-align: center;
	line-height: 50px;
	opacity:0.3;
	-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=30)";
	filter:alpha(opacity=30);
}
</style>
</head>
<body>
<div id="content">
	<div id="tag">
		<div>asd</div>
		<div>asd</div>
	</div>
	<div class="screen" id="screen-1" style="z-index:-100;position: relative;"><img src="images/c2/1.jpg"/></div>
</div>
<script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="js/jquery.screenscroll.js"></script>
<script type="text/javascript">
$(function(){
	$('body,html').animate({scrollTop:0},300);
	$.cxycs.screenscroll.init({
		screens:["#screen-1"],//滚动标记的数组，类似于锚点数组
		timer:500,//滚动间隔，默认500毫秒，500毫秒内鼠标滚动事件不执行页面滚动
		speed:500,//滚动时间，默认500毫秒，500毫秒完成页面滚动动画
		mouseScrollEventOnOff:true//鼠标滚动事件，默认为true，即开启，开启后，滚动事件由插件执行，若为false，则可以提出滚动事件自己来写滚动事件
	});
	$("#tag div").eq(1).html(($.cxycs.screenscroll.index+1)+"/"+num);
	loadimages(2);
});
var num=2;
function loadimages(i){
	if (i>num) return false;
	$("#tag div").eq(0).html("正在加载第"+i+"张图片...");
	var img = new Image();
	img.src="images/c2/"+i+".jpg";
	img.onload=function(){
		var html = '<div class="screen" id="screen-'+i+'"><img src="images/c2/'+i+'.jpg"/></div>';
		$("#content").append(html);
		$.cxycs.screenscroll.opts.screens.push("#screen-"+i);
		$("#tag div").eq(0).html("已加载"+i+"张图片");
		return loadimages(i+1);
	};
}
function screenScrollCallback(){
	$("#tag div").eq(1).html(($.cxycs.screenscroll.index+1)+"/"+num);
}
</script>

</body>
</html>

