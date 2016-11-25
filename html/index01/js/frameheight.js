// JavaScript Document
function iFrameHeight() {   
	
	
	<script type="text/javascript"> 
//** iframe自动适应页面 **// 
//输入你希望根据页面高度自动调整高度的iframe的名称的列表 
//用逗号把每个iframe的ID分隔. 例如: ["myframe1", "myframe2"]，可以只有一个窗体，则不用逗号。 
//定义iframe的ID 
var iframeids=["test"] 
//如果用户的浏览器不支持iframe是否将iframe隐藏 yes 表示隐藏，no表示不隐藏 
var iframehide="yes" 
function dyniframesize() 
{ 
var dyniframe=new Array() 
for (i=0; i<iframeids.length; i++) 
{ 
if (document.getElementById) 
{ 
//自动调整iframe高度 
dyniframe[dyniframe.length] = document.getElementById(iframeids); 
if (dyniframe && !window.opera) 
{ 
dyniframe.style.display="block" 
if (dyniframe.contentDocument && dyniframe.contentDocument.body.offsetHeight) //如果用户的浏览器是NetScape 
dyniframe.height = dyniframe.contentDocument.body.offsetHeight; 
else if (dyniframe.Document && dyniframe.Document.body.scrollHeight) //如果用户的浏览器是IE 
dyniframe.height = dyniframe.Document.body.scrollHeight; 
} 
} 
//根据设定的参数来处理不支持iframe的浏览器的显示问题 
if ((document.all || document.getElementById) && iframehide=="no") 
{ 
var tempobj=document.all? document.all[iframeids] : document.getElementById(iframeids) 
tempobj.style.display="block" 
} 
} 
} 
if (window.addEventListener) 
window.addEventListener("load", dyniframesize, false) 
else if (window.attachEvent) 
window.attachEvent("onload", dyniframesize) 
else 
window.onload=dyniframesize 
</script>
} 