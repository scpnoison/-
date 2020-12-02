(function($){
	if(!$.cxycs) $.cxycs={};
	$.extend($.cxycs,{
		screenscroll:{
			opts:{
				screens:[],
				speed:500,
				timer:500,
				mouseScrollEventOnOff:true
			},
			index:0,
			onoff:false,
			mouseScrollDown:false,
			init:function(params){
				$.extend(this.opts,params);
				if (this.opts.mouseScrollEventOnOff) this.mouseScrollEvent();
			},
			resetOnOff:function(){
				$.cxycs.screenscroll.onoff=false;
			},
			mouseScrollEvent:function(){
				//firefox
				if (document.addEventListener) document.addEventListener('DOMMouseScroll', this.mouseScroll, false);
			    //ie,chrome
				window.onmousewheel = document.onmousewheel = this.mouseScroll;
			},
			mouseScroll:function(e){
				e = e || window.event;
				var mouseScrollDown=false;
				if (e.wheelDelta) {		//ie、chrome
					if (e.wheelDelta<0) mouseScrollDown=true;
				}else{					//firefox
					if (e.detail>0) mouseScrollDown=true;
				}
				$.cxycs.screenscroll.mouseScrollDown=mouseScrollDown;
				$.cxycs.screenscroll.screenScroll();
			},
			screenScroll:function(){
				if (this.onoff) return false;
				this.onoff=true;
				setTimeout(this.resetOnOff,this.opts.timer);
				var screens = this.opts.screens;
				var len = this.opts.screens.length;
				var index = this.index;
				if (this.mouseScrollDown && index<len-1) index++;
				if (!this.mouseScrollDown && index>0) index--;
				if (index != this.index) this.index=index;
				var scrollTop = $(screens[index]).offset().top;
				$('body,html').animate({scrollTop:scrollTop},this.opts.speed);
				if (typeof screenScrollCallback=='function') return screenScrollCallback();
				return false;
			}
		}
	});
}(jQuery));