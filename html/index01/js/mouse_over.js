function displaydiv(e){
	
	var name=e.firstChild;
	var type=name.textContent;
	
	if(type=="数据报告")
	{
		$(".data").show();
		
	}else if(type=="项目专栏"){
		
		$(".project").show();
		
	}else if(type=="行业资讯"){
		
		$(".news").show();
		
	}
	
	
}

function hidediv(e){
	
	var name=e.firstChild;
	var type=name.textContent;
	
	if(type=="数据报告")
	{
		$(".data").hide();
		
	}else if(type=="项目专栏"){
		
		$(".project").hide();
		
	}else if(type=="行业资讯"){
		
		$(".news").hide();
		
	}
}

function show_wx(e){
	
	$(".wx").show();
		
}

function hide_wx(e){
	
	$(".wx").hide();
		
}

function show_tel(e){
	
	$(".tel").show();
		
}

function hide_tel(e){
	
	$(".tel").hide();
		
}