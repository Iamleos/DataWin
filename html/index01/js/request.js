 // JavaScript Document
function loadnews(){
	paper_type1();
	paper_type2();
	paper_type3();
	paper_type4();
}

function paper_type1(){
	
	//var timestamp = (new Date()).valueOf();;
	//var url = "http://115.159.205.133/V/index.php/Home/User/log.html";
	var url ="./php/index_report.php";
	//alert(url);
	//创建对象
	var xhr = createXhr();
	//初始化
	xhr.open('get',url,true);
	//设置回调函数
	xhr.onreadystatechange = function() {
		//接收数据完毕
			
		if (xhr.readyState == 4) {
			
			if (xhr.status == 200) {
				//将结果写到span标签中
				//$('result').innerHTML = xhr.responseText;
				//var obj = eval(xhr.responseText);
				
				var jsonstr=xhr.responseText;
				var json=JSON.parse(jsonstr);
				
				
					
				var title=json.morning['data'][0].title;
				
				var imgpath =json.morning['data'][0].photo;
				
				
				$(".paper_type1 div a").text(title+"");
				$(".paper_type1 img").attr({ src:"http://"+imgpath});
				
			
			}else{
					alert(xhr.status);
			}
		}
	};
	//发送请求
	xhr.send(null);
}

function paper_type2(){
	
	//var timestamp = (new Date()).valueOf();;
	//var url = "http://115.159.205.133/website/chendu.php?timestamp="+timestamp;
	var url ="./php/index_report.php";
	//alert(url);
	//创建对象
	var xhr = createXhr();
	//初始化
	xhr.open('get', url,true);
	//设置回调函数
	xhr.onreadystatechange = function() {
		//接收数据完毕
		
		if (xhr.readyState == 4) {
			
			if (xhr.status == 200) {
				var jsonstr=xhr.responseText;
				var json=JSON.parse(jsonstr);
				
				
					
				var title=json.moviepro['data'][0].title;
				
				var imgpath =json.moviepro['data'][0].photo;
				//alert(imgpath);
				$(".paper_type2 div a").text(title+"");
				$(".paper_type2 img").attr({ src:"http://"+imgpath});
			}
		}
	};
	//发送请求
	xhr.send(null);
}
function paper_type3(){
	
	//var timestamp = (new Date()).valueOf();;
	//var url = "http://115.159.205.133/website/chendu.php?timestamp="+timestamp;
	var url ="./php/index_report.php";
	//alert(url);
	//创建对象
	var xhr = createXhr();
	//初始化
	xhr.open('get', url,true);
	//设置回调函数
	xhr.onreadystatechange = function() {
		//接收数据完毕
		
		if (xhr.readyState == 4) {
			
			if (xhr.status == 200) {
				//将结果写到span标签中
				//$('result').innerHTML = xhr.responseText;
				//var obj = eval(xhr.responseText);
				
				var jsonstr=xhr.responseText;
				var json=JSON.parse(jsonstr);
				var title=json.actor['data'][0].title;
				
				var imgpath =json.actor['data'][0].photo;
				
				$(".paper_type3 div a").text(title+"");
				$(".paper_type3 img").attr({ src:"http://"+imgpath});
			}
		}
	};
	//发送请求
	xhr.send(null);
}
function paper_type4(){
	
	//var timestamp = (new Date()).valueOf();;
	//var url = "http://115.159.205.133/website/chendu.php?timestamp="+timestamp;
	var url ="./php/index_report.php";
	//alert(url);
	//创建对象
	var xhr = createXhr();
	//初始化
	xhr.open('get', url,true);
	//设置回调函数
	xhr.onreadystatechange = function() {
		//接收数据完毕
		
		if (xhr.readyState == 4) {
			
			if (xhr.status == 200) {
				var jsonstr=xhr.responseText;
				var json=JSON.parse(jsonstr);
				var title=json.study['data'][0].title;
				
				var imgpath =json.study['data'][0].photo;
				
				$(".paper_type4 div a").text(title+"");
				$(".paper_type4 img").attr({ src:"http://"+imgpath});
			}
		}
	};
	//发送请求
	xhr.send(null);
}
function createXhr() {
	try {
		return new XMLHttpRequest();
	} catch (e) {

	}

	try {
		return new ActiveXObject('Microsoft.XMLHTTP');
	} catch (e) {

	}

}