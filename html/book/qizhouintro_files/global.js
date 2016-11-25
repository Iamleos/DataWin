var _isSelectAll = false;
$(function(){
	
	//设定body的宽度为当前文档的宽度
	var docWidth = $(document).width();
	$("body").css("width",docWidth);
	
	
	//头部点击筛选搜索条件
	$(".input-group-btn .dropdown-menu li a").click(function(){
		var liText = $(this).text();
		$(this).parents(".dropdown-menu").siblings(".btn").find(".btnTxt").text(liText);
	})
	$(".dropdown-menu").mouseover(function(){
		$(this).show();
	}).mouseout(function(){
		$(this).hide().parents(".btn-group").removeClass("open");
		$(".dropdown-backdrop").hide();
	});
	
	
	//左侧菜单点击效果
	$(".nav-stacked .nav-header").click(function(){
		$(this).siblings(".secondmenu,.backstage-menu").slideToggle().parent().siblings("li").find(".secondmenu,.backstage-menu").hide();
		$(this).children(".fa").toggleClass("fa-angle-up")
	})
	
	
	//媒体报道，鼠标滑过显示变色
	$(".interview-list li").mouseenter(function(){
		$(this).children(".interview-date").css("background","#ff7800").parent().siblings("li").children(".interview-date").css("background","#47a0e4");
		$(this).find("h4").css("color","#47a0e4").parents("li").siblings("li").find("h4").css("color","#424242");
	})
	
	//冲榜入口-点击显示地域
	$(".place .btn").click(function(){
		$(this).toggleClass("btn-succes");
		$(this).siblings(".p_local").toggle();  
	})
	
	$(".my_sf input").click(function(){
	  $(this).parent().siblings(".my_p").toggle();  
	})
	
	$(".delet").click(function(){
	  $(this).parents(".fill_pop").hide();
	})
	
	
	//后台登录页面--头部账号下拉
	$(".personal span").click(function(){
		$(this).siblings(".personal-List").toggle();
	});
	$(".personal-List").mouseover(function(){
		$(this).show();
	}).mouseout(function(){
		$(this).hide();
	})
	
	//后台-榜单页面点击设置分组
	$(".set-btn").click(function(){
		$(".set-up").toggle();
	});
	$(".setup-ul .caret").click(function(){
		$(this).parent().siblings(".set-option").toggle();
		$(this).parents("li").siblings("li").find(".set-option").hide();
	})
	
	/*----------  后台登录后界面---弹框 ----------*/
	$(".content-lt .caret").click(function(){
		$(this).siblings("ul").slideToggle();
	});
	
	$(".tab-top ul li").click(function(){
		var liIndex = $(this).index();
		$(this).toggleClass("active").siblings("li").removeClass("active");
		$(".content-rt table").eq(liIndex).show().siblings("table").hide();
	});
	$(".modal-title ul li").click(function(){
		var modalLi = $(this).index();
		$(this).addClass("active").siblings("li").removeClass("active");
		$(this).parents(".modal-header").siblings(".modal-body").eq(modalLi).show().siblings(".modal-body").hide();
	});
	$(".templete").click(function(){
		$(this).addClass("active").siblings("a").removeClass("active")
	});
	
	
	
	//后台页面------添加我的榜单 弹框
	$(".body-title li").click(function(){
		var titleList = $(this).index();
		$(this).addClass("active").siblings("li").removeClass("active");
		$(".addWay").eq(titleList).show().siblings(".addWay").hide();
	})
	
	//榜单页全选
	$("#wxSelectAll").click(function(){
		_isSelectAll = !_isSelectAll
		$("input[name='wxCB']").each(function(){
			$(this).prop("checked",_isSelectAll);
		});
	})	
	
	//鼠标滑动显示底部二维码
	$(".qrCode .fa-qrcode").mouseenter(function(){
		$(".qrImg").show('600');
	}).mouseout(function(){
		$(".qrImg").hide();
	})
	
})

