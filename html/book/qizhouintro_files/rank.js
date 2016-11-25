$(function ()
{
	if (window.location.hostname != "www.gsdata.cn" && window.location.hostname != "search.gsdata.cn")
	{
		//alert("Gsdata提醒您：请不要抓取我们的数据");
		//window.top.location.href = "http://open.gsdata.cn/";
		//return false;
	}
});
/**
 * 排行榜相关
 * @author: @debug2012
 */
var RANK = {};
RANK.loading = function (show) {
	var loading = $('.loading');
	if (show) {
		loading.show();
	} else {
		loading.hide();
	}
}
RANK.dates = function () {
	var d = new Date();
	var h = d.getHours();
	var lostday;
	if (h > 12) {
		lostday = 1;
	} else {
		lostday = 2;
	}
	d.setDate(d.getDate() - lostday);
	var month = d.getMonth() + 1;
	var day = d.getDate();
	if (month < 10) {
		month = "0" + month;
	}
	if (day < 10) {
		day = "0" + day;
	}
	var val = d.getFullYear() + "-" + month + "-" + day;
	return val;

	//var dd = new Date();
	//var month = dd.getMonth() < 10 ? (dd.getMonth()+1):dd.getMonth();
	//var date = dd.getDate() - 2;
	//return dd.getFullYear()+'-'+month+'-'+date;
}
/**
 * 排行榜
 * @param gid
 * @param date_str
 */
RANK.getRanks = function (gid, date_str, page) {
	RANK.loading(true);
	date_str = date_str ? date_str : null;
	$.ajax({
		type: 'GET',
		url: SITE.ensureUrl('rank/ranks'),
		dataType: 'json',
		data: {gid: gid, date: date_str, page: page, type: CONFIG.request_params.type, t: Math.random()},
		success: function (data) {
			console.log(data);
			data = data.data;
			if (data.errcode) {
				RANK.loading();
				alert('暂无统计数据');
			} else {
				var rows = data.rows;

				if (data.date) {
					$("#rank-date").val(data.date);
					$("#statistic-date").text(data.end_date);
				}
				var tr = '';
				var index = 0;
				var per = 50;
				for (var i = 0, n = rows.length; i < n; i++) {

					var row = rows[i];
					if (typeof row.wx_nickname == "undefined")
						continue;
					//if(row.wci == 0) continue;
					index++;
					//index = index + 1 +  per*(page-1);

					if (i % 2) {
						tr += '<tr class="tr_1">';
					} else {
						tr += '<tr class="tr_2">';
					}

					if (CONFIG.uid > 0) {
						tr += '<td><input type="checkbox" name="wxCB" value="' + row.nickname_id + '" /></td>';
					} else {
						tr += '<td></td>';
					}
					if (i == 0 && page == 1) {
						// 第一名
						tr += '<td><span><img src="' + SITE.ensureThemeUrl('images/rw/one.png') + '"></span></td>';
					} else if (i == 1 && page == 1) {
						tr += '<td><span><img src="' + SITE.ensureThemeUrl('images/rw/two.png') + '"></span></td>';
					} else if (i == 2 && page == 1) {
						tr += '<td><span><img src="' + SITE.ensureThemeUrl('images/rw/three.png') + '"></span></td>';
					} else {
						tr += '<td><span class="bd-name">' + (index + per * (page - 1)) + '</span></td>';
					}

					tr += '<td><a href="' + SITE.ensureUrl('rank/single?id=' + row.nickname_id) + '" target="_blank"><span class="rank_wxname">' + SITE.htmlencode(row.wx_nickname) + '</span><br/>' + SITE.htmlencode(row.wx_name) + '</a></td>' +
							'<td>' + row.url_times + '/' + row.url_num + '</td>' +
							'<td>' + row.readnum_all + '</td>' +
							'<td>' + row.readnum_max + '</td>' +
							'<td>' + row.readnum_av + '</td>' +
							'<td>' + row.likenum_all + '</td>' +
							'<td>' + (typeof row.wci != "undefined" ? row.wci.toFixed(2) : 0) + '</td>' +
							'<td><a href="' + SITE.ensureUrl('rank/single?id=' + row.nickname_id) + '" target="_blank" class="details">进入</a></td>';
				}
				tr += '</tr>';
				RANK.loading();
				$('#rank-table').find('tr:gt(0)').remove().end().append(tr);
				loadStaticData(data);

				RANK.getPages(data.total, per, page, 'RANK.getRanks');
			}
		},
		error: function (xhr, status, error) {
			//alert(error);
		}
	});
}

/**
 * 热门文章
 * @param gid
 * @param date_str
 */
var isfirst = 1;
RANK.getArticles = function (gid, date_str, page) {
	RANK.loading(true);

	date_str = date_str || RANK.dates;
	// if(gid ==0)   opensearch 取数据，返回数据一致
	if (gid == 0) {
		var _datas = {gid: gid, date: date_str, page: page, post_time: 5, sort: -2};
		var _urls = SITE.ensureUrl('rank/SearchArticles');
	} else {
		var _datas = {gid: gid, date: date_str, page: page};
		var _urls = SITE.ensureUrl('rank/articles');
	}
	$.ajax({
		type: 'GET',
		url: _urls,
		dataType: 'json',
		data: _datas,
		success: function (data) {
			RANK.loading();
			data = data.data;
			if (data.date) {
				$("#rank-date").val(data.date);
				$("#statistic-date").text(data.end_date);
			}
			if (data.rows) {
				$("#article-container").html($.templates("#article-tpl").render(data.rows));
				var _image_url = 'http://img1.gsdata.cn/index.php/rank/getImageUrl?callback=?';
				$("img[id='m_img']").each(function ()
				{
					var img_this = $(this);
					var hash = $(this).data('hash');
					$.getJSON(_image_url, {hash: hash}, function (result) {
						img_this.attr('src', result.url);
					});
				});

				/* 解决异步加载分享js的问题  */
				baiduShareFixed();

				loadStaticData(data);

				RANK.getPages(data.total, 10, page, 'RANK.getArticles');
			} else {
				removeStaticData();
				$("#article-container").text('暂无数据...');
				RANK.getPages(0, 10, page, 'RANK.getArticles');
			}
		},
		error: function (xhr, status, error) {
			alert(error);
		}
	});


}

var ismasonry = 0;
//var page = 1;
RANK.getPics = function (gid, date_str, page) {
	if (gid == 0)
	{
		gid = 1;
	}
	RANK.loading(true);
	date_str = date_str || null;
	$.ajax({
		type: 'GET',
		url: SITE.ensureUrl('rank/Pics'),
		dataType: 'json',
		data: {gid: gid, date: date_str, page: page},
		success: function (data) {
			data = data.data;
			if (data.date) {
				$("#rank-date").val(data.date);
				$("#statistic-date").text(data.end_date);
			}

			if (data.rows) {
				var $box = $($.templates("#pics-tpl").render(data.rows));
				$('.hotImg #container ul').html($.templates("#pics-tpl").render(data.rows));
				//$('.hotImg #container ul').html('');
				for (var i = 0; i < data.rows.length; i++) {
					$minUl = getMinUl();

					$minUl.append($.templates("#pics-tpl").render(data.rows[i]));

				}
				var _image_url = 'http://img1.gsdata.cn/index.php/rank/getImageUrl?callback=?';
				$(".water_pic img").each(function ()
				{
					var img_this = $(this);
					var hash = $(this).data('hash');
					$.getJSON(_image_url, {hash: hash}, function (result) {
						img_this.css('margin-top', '0');
						img_this.css('margin-left', '0');
						img_this.attr('src', result.url);
					});
				});
//            	$minUl = getMinUl();
//            	$minUl.append($.templates("#pics-tpl").render(data.rows));
//            	var $img = $('.hotImg #container img');
//                $img.load(function () {
//                    $('.hotImg #container').masonry();
//                });

				//$('.hotImg #container').masonry();
				//$(".hotImg #container").html($.templates("#pics-tpl").render(data.rows));

				/*var $container = $('#container');
				 
				 if(ismasonry == 1)
				 $container.masonry('reloadItems');
				 
				 $container.masonry({
				 itemSelector: '.case_aterfall_li'
				 });*/
//        		$container.imagesLoaded(function(){
//        		  $container.masonry({
//        		  itemSelector: '.case_aterfall_li'
//        		});
//        	  }); 

				loadStaticData(data);
//            	ismasonry = 1;

				RANK.getPages(data.total, 25, page, 'RANK.getPics');

				RANK.loading();
			} else {
				$(".hotImg #container").text('暂无数据...');
				//RANK.getPages(0,25,page,'RANK.getPics');
			}
		},
		error: function (xhr, status, error) {
			alert(error);
		}
	});
}

RANK.getVideos = function (gid, date_str, page) {
	RANK.loading(true);
	date_str = date_str || null;
	$.ajax({
		type: 'GET',
		url: SITE.ensureUrl('rank/Videos'),
		dataType: 'json',
		data: {date: date_str, page: page},
		success: function (data) {

			data = data.data;
			if (data.date) {
				$("#rank-date").val(data.date);
				$("#statistic-date").text(data.end_date);
			}

			if (data.rows) {
				//var $box = $($.templates("#pics-tpl").render(data.rows));
				$('.hotVideo .videoUl').html($.templates("#videos-tpl").render(data.rows));

				RANK.getPages(data.total, 20, page, 'RANK.getVideos');

				RANK.loading();
			} else {
				$(".hotVideo .videoUl").text('暂无数据...');
				//RANK.getPages(0,20,page,'RANK.getVideos');
			}

		},
		error: function (xhr, status, error) {
			alert(error);
		}
	});
}

RANK.getPages = function (total, perpage, page, cb) {
	total = Math.min(perpage * 5, total);
	var tpls = [];
	var maxpage = Math.ceil(total / perpage);
	if (maxpage <= 1) {

	} else
	{
		var $class = "";

		var f_tpl = '<li class="first"><a href="javascript:;" onclick="' + cb + '(' + CONFIG.request_params.gid + ',\'' + $("#rank-date").val() + '\',1)">首页</a></li>';
		var p_tpl = '<li class="previous"><a href="javascript:;" onclick="' + cb + '(' + CONFIG.request_params.gid + ',\'' + $("#rank-date").val() + '\',' + (page - 1) + ')">&lt;</a></li>';
		var e_tpl = '<li class="next"><a href="javascript:;" onclick="' + cb + '(' + CONFIG.request_params.gid + ',\'' + $("#rank-date").val() + '\',' + (page + 1) + ')">&gt;</a></li>';
		var l_tpl = '<li class="last"><a href="javascript:;" onclick="' + cb + '(' + CONFIG.request_params.gid + ',\'' + $("#rank-date").val() + '\',' + maxpage + ')">尾页</a></li>';
		var _tpl = '<li class="page {$select}"><a href="javascript:;" onclick="' + cb + '(' + CONFIG.request_params.gid + ',\'' + $("#rank-date").val() + '\',__PAGE__)">__PAGE__</a></li>';

		if (page != 1) {
			tpls.push(f_tpl);
			tpls.push(p_tpl);
		}

		var start = Math.max(page - 2, 1);
		var end = Math.min(maxpage, page + 3);

		for (var i = start; i <= end; i++) {
			$class = i == page ? "aLink" : "";

			tpls.push(_tpl.replace(/__PAGE__/g, i).replace("{$select}", $class));
		}

		if (page != maxpage) {
			tpls.push(e_tpl);
			tpls.push(l_tpl);
		}
	}

	$("#yw1").html(tpls.join(""));
}
/**
 * 分组切换
 */
RANK.changeGroup = function () {

	$('.secondmenu li').click(function () {
		$(".nav-stacked").find('li').removeClass('active');
		$(this).addClass('active');
		//$group_name.text($(this).text());
		var gid = $(this).data('gid');
		requestGid = gid;

		$("#group-title").html($(this).find("a").text());

		CONFIG.request_params.gid = gid;
		if ($("#rank-container:visible").size()) {
			RANK.getRanks(gid, $("#rank-date").val(), 1);
		} else if ($("#article-container:visible").size()) {
			RANK.getArticles(CONFIG.request_params.gid, $("#rank-date").val(), 1);
		} else {
			RANK.getPics(CONFIG.request_params.gid, $("#rank-date").val(), 1);
		}
		return false;
	});

	$('.backstage-menu li').click(function () {
		$(".nav-stacked").find('li').removeClass('active');
		$(this).addClass('active');
		//$group_name.text($(this).text());
		var gid = $(this).data('gid');
		requestGid = gid;

		$("#group-title").html($(this).find("a").text());

		CONFIG.request_params.gid = gid;
		if ($("#rank-container:visible").size()) {
			RANK.getRanks(gid, $("#rank-date").val(), 1);
		} else if ($("#article-container:visible").size()) {
			RANK.getArticles(CONFIG.request_params.gid, $("#rank-date").val(), 1);
		} else {
			RANK.getPics(CONFIG.request_params.gid, $("#rank-date").val(), 1);
		}
		return false;
	});

	// open current group
	$('li[data-gid="' + CONFIG.request_params.gid + '"]').click().parent().parent().click();
}
/**
 *
 * @type {{}}
 */
RANK.single = {};
RANK.single.statistic = function (id) {
	// 获取统计信息
	$.ajax({
		type: "GET",
		url: SITE.ensureUrl('rank/singleStatistic'),
		dataType: 'json',
		data: {id: id},
		success: function (data) {
			if (data.error == 1) {
				//alert(data.error_msg);
			} else {
				data = data.data;
				for (var i in data) {
					$("#" + i).text(data[i]);
				}
			}
		},
		error: function (xhr, status, error) {
			alert(error);
		}
	});
	$("#statistic-tab li").click(function () {
		$(this).parent().find('li').toggleClass('active');
		$(".statistic-container").toggle();
	});
}
/**
 * echarts
 * @param id
 */
RANK.single.charts = function (id) {
	"use strict";
	// WCI指数
	function chart_post_num(dates, chart_data, id) {
		require(
				[
					'echarts',
					'echarts/chart/line' // 使用柱状图就加载bar模块，按需加载
				],
				function (ec) {
					// 基于准备好的dom，初始化echarts图表
					var myChart = ec.init(document.getElementById(id));

					var option = {
						tooltip: {
							show: true
						},
						legend: {
							data: ['WCI指数']
						},
						xAxis: [
							{
								type: 'category',
								data: dates
							}
						],
						yAxis: [
							{
								type: 'value'
							}
						],
						axis: [
							{
								axisLine: [{
										lineStyle: {
											color: '#72b216'
										}
									}]
							}
						],
						series: [
							{
								"name": "WCI指数",
								"type": "line",
								"data": chart_data.post_count,
								markLine: {
									data: [
										{type: 'average', name: '平均值'}
									]
								}
							}
						]
					};

					// 为echarts对象加载数据
					myChart.setOption(option);
				}
		);
	}
	// 总阅读数
	function chart_read_num(dates, chart_data, id) {
		require(
				[
					'echarts',
					'echarts/chart/line' // 使用柱状图就加载bar模块，按需加载
				],
				function (ec) {
					// 基于准备好的dom，初始化echarts图表
					var myChart = ec.init(document.getElementById(id));

					var option = {
						tooltip: {
							show: true
						},
						legend: {
							data: ['总阅读数']
						},
						xAxis: [
							{
								type: 'category',
								data: dates
							}
						],
						yAxis: [
							{
								type: 'value'
							}
						],
						series: [
							{
								"name": "总阅读数",
								"type": "line",
								"data": chart_data.read_count,
								markLine: {
									data: [
										{type: 'average', name: '平均值'}
									]
								}
							}
						]
					};

					// 为echarts对象加载数据
					myChart.setOption(option);
				}
		);
	}
	// 头条总阅读数
	function chart_headline_read_num(dates, chart_data, id) {
		require(
				[
					'echarts',
					'echarts/chart/line' // 使用柱状图就加载bar模块，按需加载
				],
				function (ec) {
					// 基于准备好的dom，初始化echarts图表
					var myChart = ec.init(document.getElementById(id));

					var option = {
						tooltip: {
							show: true
						},
						legend: {
							data: ['头条总阅读数']
						},
						xAxis: [
							{
								type: 'category',
								data: dates
							}
						],
						yAxis: [
							{
								type: 'value'
							}
						],
						series: [
							{
								"name": "头条总阅读数",
								"type": "line",
								"data": chart_data.headline_read_count,
								markLine: {
									data: [
										{type: 'average', name: '平均值'}
									]
								}
							}
						]
					};

					// 为echarts对象加载数据
					myChart.setOption(option);
				}
		);
	}

	// 平均阅读数
	function chart_avg_read_num(dates, chart_data, id) {
		require(
				[
					'echarts',
					'echarts/chart/line' // 使用柱状图就加载bar模块，按需加载
				],
				function (ec) {
					// 基于准备好的dom，初始化echarts图表
					var myChart = ec.init(document.getElementById(id));

					var option = {
						tooltip: {
							show: true
						},
						legend: {
							data: ['平均阅读数']
						},
						xAxis: [
							{
								type: 'category',
								data: dates
							}
						],
						yAxis: [
							{
								type: 'value'
							}
						],
						series: [
							{
								"name": "平均阅读数",
								"type": "line",
								"data": chart_data.avg_read_count,
								markLine: {
									data: [
										{type: 'average', name: '平均值'}
									]
								}
							}
						]
					};

					// 为echarts对象加载数据
					myChart.setOption(option);
				}
		);
	}

	// 点赞数
	function chart_avg_like_num(dates, chart_data, id) {
		require(
				[
					'echarts',
					'echarts/chart/line' // 使用柱状图就加载bar模块，按需加载
				],
				function (ec) {
					// 基于准备好的dom，初始化echarts图表
					var myChart = ec.init(document.getElementById(id));

					var option = {
						tooltip: {
							show: true
						},
						legend: {
							data: ['点赞数']
						},
						xAxis: [
							{
								type: 'category',
								data: dates
							}
						],
						yAxis: [
							{
								type: 'value'
							}
						],
						series: [
							{
								"name": "点赞数",
								"type": "line",
								"data": chart_data.like_count,
								markLine: {
									data: [
										{type: 'average', name: '平均值'}
									]
								}
							}
						]
					};

					// 为echarts对象加载数据
					myChart.setOption(option);
				}
		);
	}

	$.ajax({
		type: 'GET',
		url: SITE.ensureUrl('rank/chartsData'),
		data: {id: id},
		success: function (data) {
			if (data.error == 1) {
				//alert(data.error_msg);
			} else {
				// 路径配置
				require.config({
					paths: {
						echarts: 'http://echarts.baidu.com/build/dist'
					}
				});

				var chart_data = {};
				chart_data.post_count = [];
				chart_data.read_count = [];
				chart_data.headline_read_count = [];
				chart_data.avg_read_count = [];
				chart_data.like_count = [];
				data = data.data;
				var dates = [];
				var fansCount = 0;
				var _fc = 0;	//天数
				var fbCount = 0;	//发布次数
				for (var i in data) {
					dates.push(i);
					chart_data.post_count.push(data[i].post_count);
					chart_data.read_count.push(data[i].read_count);
					chart_data.headline_read_count.push(data[i].headline_read_count);
					chart_data.avg_read_count.push(data[i].avg_read_count);
					chart_data.like_count.push(data[i].like_count);
					if (_fc < 7) {
						fansCount += data[i].headline_read_count;
						fbCount += data[i].url_times;
						_fc++;
					}
				}
				//预估粉丝
				if (_fc == 0 || fbCount == 0) {
					fansCount = 0;
				} else {
					fansCount = fansCount / _fc;
					fansCount = fansCount / fbCount;
					fansCount = parseInt((fansCount * 10) / 1000) * 1000;
				}

				$("#ygfs").text(fansCount);

				$("#charts-tab li").click(function () {
					$(this).siblings().removeClass('active').end().addClass('active');
					$(".chart-container").hide();
					var id = $(this).attr('href');
					$("#" + id).show();
					var func = eval(id);
					func(dates, chart_data, id);
				}).eq(0).trigger('click');
			}
		},
		error: function (xhr, status, error) {
			alert(error);
		}
	});
}
/**
 * 推荐文章
 * @param id
 */
RANK.single.recommend = function (id) {
	var recommend_container = $(".article-ul");
	$(".recommend-tab").click(function () {
		if ($("input[name='radio']:checked").val() == 1) {
			var index = 0;
		} else {
			var index = 1;
		}
		// 最新
		if (index == 0) {
			var type = 'recently';
		} else {
			var type = 'hot';
		}
		var scroll_top = $(window).scrollTop();

		$.ajax({
			type: 'GET',
			url: SITE.ensureUrl('rank/recommendArticles'),
			dataType: 'json',
			data: {id: id, type: type},
			success: function (data) {
				var div = '';
				if (data.result && data.result.items) {
					div = $.templates("#recommend-tpl").render(data.result.items);
				}

				$('.article-ul').eq(index).html(div);

				var _image_url = 'http://img1.gsdata.cn/index.php/rank/getImageUrl?callback=?';
				$(".wx-img img").each(function ()
				{
					var img_this = $(this);
					var hash = $(this).data('hash');
					$.getJSON(_image_url, {hash: hash}, function (result) {
						img_this.attr('src', result.url);
					});
				});

				/* 解决异步加载分享js的问题  */
				baiduShareFixed();
				$('body').scrollTop(scroll_top);
			},
			error: function (xhr, status, error) {
				alert('error');
			}
		});
		//}
		recommend_container.hide().eq(index).show();
		$(this).find('>div').show();
		$(this).siblings().find('>div').hide();

	}).eq(0).trigger('click');
}

// init scripts
$(function () {
	var params = CONFIG.request_params;
	switch (CONFIG.action_id) {
		case 'detail':

			var type = CONFIG.request_params.type;
			/*if(type == "month") {
			 RANK.getRanks(params.gid,$("#rank-date").val(),1);
			 }else {
			 // 初始化排行榜数据
			 RANK.getRanks(params.gid,$("#rank-date").val(),1);
			 }*/
			if (CONFIG.request_params.gname)
			{
				RANK.getRanks(params.gid, $("#rank-date").val(), 1);
			}
			var $rank_date = $("#rank-date");
			// change group
			RANK.changeGroup();

			// switch radio
			$("#phb,#rmwz,.rtu,.rs").click(function () {
				$('.phb_1,.rmwz_1,.rmwz_2,.rmwz_3').hide();
				$(this).find('>div').show();
				if ($(this).attr('id') == 'rmwz') {
					// 热门文章
					$("#phb").removeClass("active");
					$("#rmwz").addClass("active");
					if (CONFIG.request_params.uid == undefined) {
						$("#removebd").removeClass("active");
						$("#addrw").addClass("active");
					}
					$("#article-container").show();
					$("#rank-container,.hotImg,.hotVideo").hide();
					$(".tab-set").hide();
					RANK.getArticles(params.gid, $rank_date.val(), 1);
					RANK.getRanks(params.gid, $rank_date.val(), 1);
				} else if ($(this).attr('id') == 'phb') {
					$(".tab-set").show();
					$("#rmwz").removeClass("active");
					$("#phb").addClass("active");
					if (CONFIG.request_params.uid == undefined) {
						$("#removebd").addClass("active");
						$("#addrw").removeClass("active");
					}
					RANK.getRanks(params.gid, $rank_date.val(), 1);
					$("#rank-container").show();
					$("#article-container,.hotImg,.hotVideo").hide();
				} else if ($(this).hasClass('rs')) {
					$(".hotVideo").show();
					$("#article-container,#rank-container,.hotImg").hide();
					RANK.getVideos(0, $rank_date.val(), 1);
				} else {
					$(".hotImg").show();
					$("#article-container,#rank-container,.hotVideo").hide();
					RANK.getPics(params.gid, $rank_date.val(), 1);
				}
			});

			$(".zhou_mate li").click(function () {
				RANK.getRanks(CONFIG.request_params.gid, $(this).find("a").text(), 1);
			});

			break;
		case 'single':
			// rank single page
			RANK.single.statistic(params.id);
			// echarts
			setTimeout(function () {
				RANK.single.charts(params.id);
			}, 500);
			// recommend
			setTimeout(function () {
				RANK.single.recommend(params.id);
			}, 800);
			break;
		default:
			break;
	}
	if (1 == isClz)
	{
		//$("#rmwz").click(); //兼容问题，放到视图里处理，不直接调用click事件
	}
});


function locationZB() {
	var name = $('.active-nav .active').text();
	if (name == '热文') {
		var clz = 1;
	} else {
		var clz = 0;
	}
	var url = SITE.ensureUrl('rank/detail') + "?gid=" + requestGid + "&type=month" + "&clz=" + clz;
	if (requestGname) {
		url += "&gname=" + requestGname;
	}

	location.href = url;
}

function getMinUl() {//每次获取最短的ul,将图片放到其后
	var $arrUl = $("#container .col");
	var $minUl = $arrUl.eq(0);
	$arrUl.each(function (index, elem) {
		if ($(elem).height() < $minUl.height()) {
			$minUl = $(elem);
		}
	});
	return $minUl;
}

function locationRB() {
	var name = $('.active-nav .active').text();
	if (name == '热文') {
		var clz = 1;
	} else {
		var clz = 0;
	}
	var url = SITE.ensureUrl('rank/detail') + "?gid=" + requestGid + "&clz=" + clz;
	if (requestGname) {
		url += "&gname=" + requestGname;
	}
	location.href = url;
}


//微博周榜重定向
function locationWbZB() {
	var url = SITE.ensureUrl('rank/WbDetail') + "?gid=" + requestGid + "&type=month";
	if (requestGname) {
		url += "&gname=" + requestGname;
	}

	location.href = url;
}

//微博日榜重定向
function locationWbRB() {
	var url = SITE.ensureUrl('rank/WbDetail') + "?gid=" + requestGid;
	if (requestGname) {
		url += "&gname=" + requestGname;
	}

	location.href = url;
}

function loadStaticData(data) {
	$("#StaticData-account_num").html(data.account_num);	//公众号数量
	$("#StaticData-article_num").html(data.article_num);	//文章数量
	$("#StaticData-readnum_all").html(Math.ceil(data.readnum_all / 10000) + "万+");	//阅读数量
}

function removeStaticData() {
	$("#StaticData-account_num").html('');	//公众号数量
	$("#StaticData-article_num").html('');	//文章数量
	$("#StaticData-readnum_all").html('');	//阅读数量
}

function baiduShareFixed() {
	/* 解决异步加载分享js的问题  */
	var script = document.createElement("script");
	script.setAttribute("id", "bdshare_js");
	script.setAttribute("data", "type=tools");
	document.getElementsByTagName("head")[0].appendChild(script);

	var script1 = document.createElement("script");
	script1.setAttribute("id", "bdshell_js");
	document.getElementsByTagName("head")[0].appendChild(script1);

	document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + Math.ceil(new Date() / 3600000);

}