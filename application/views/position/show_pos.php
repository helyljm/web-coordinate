<!DOCTYPE html>
<html>
<head>
</head>
<body>
<label>记录数:<div id="myDiv"><h2></h2></div></label>
<label>最新值:<div id="myDiv2"><h2></h2></div></label>
<label>坐标:<div id="myDiv4"><h2></h2></div></label>

<form>
<input type="button" value="开始" onClick="sendHTTPRequest()">
<input type="button" value="停止！" onClick="stopCount()">
</form>
<hr />
<canvas id="myCanvas" width="920" height="302" style="border:1px solid #c3c3c3;" onmousemove="cnvs_getCoordinates(event)" onmouseout="cnvs_clearCoordinates()">your browser does not support the canvas tag </canvas>
<canvas id="myCanvas2" width="920" height="50" style="border:1px solid #c3c3c3;" >your browser does not support the canvas tag </canvas>
<hr />
<script type="text/javascript">


var xmlhttp = null;   
function initXMLRequest() {   
	if (window.ActiveXObject) {   
	xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");   
	} else {   
	if (window.XMLHttpRequest) {   
	xmlhttp = new XMLHttpRequest();   
	}   
	}   
}
function sendHTTPRequest() {
	initXMLRequest();
	
	if (xmlhttp) {   
	xmlhttp.open("GET","http://localhost/index.php/position/get_n/1000/10/90",true);
	xmlhttp.send(); 
	xmlhttp.onreadystatechange = loadXMLDoc; 
	//使用方法回调，每1秒调用一次
	setTimeout("sendHTTPRequest()",1000);
	}   
}  

/* 
function isDataExists() {   
	if (xmlhttp.readyState == 4) {   
	if (xmlhttp.status == 200) {
	
	}
}
*/




function loadXMLDoc()
{
    /*var xmlhttp;
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }else{// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
	*/
	//xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
            // 1.得到内容串
		    var str = xmlhttp.responseText;
			var dataObj = eval("(" + str + ")");
			
			// 2.获取画布
			var canvas=document.getElementById('myCanvas');
            var cxt=canvas.getContext('2d');
			cxt.clearRect(0, 0, 920, 302);
			
			// 3.绘制坐标系
			// 1.竖线
			var i=40;
			for (i=40;i<=920;i=i+40){
				if(i == 40){
					// 第一条线，绘制分隔控制
					cxt.strokeStyle='#000000';
					cxt.lineWidth = 2;
				}else{
				    cxt.strokeStyle='#C0C0C0';
					cxt.lineWidth = 1;
				}
				
				cxt.lineWidth = 1;
                cxt.moveTo(i,0);
				cxt.lineTo(i,260);
				cxt.stroke();
				
				// 画x坐标值 - 横线
				cxt.font = "10px Courier New";
                //设置字体填充颜色
                cxt.fillStyle = "blue";
                //从坐标点(50,50)开始绘制文字
                cxt.fillText(String(i-40), i, 280);
            }
			// 2.横线
			i=20;
			for (i=20;i<=260;i=i+20){
				if(i == 20){
					// 第一条线，绘制分隔控制
					cxt.strokeStyle='#000000';
					cxt.lineWidth = 2;
				}else{
				    cxt.strokeStyle='#C0C0C0';
					cxt.lineWidth = 1;	
				}
				
				cxt.lineWidth = 1;
                cxt.moveTo(40,i);
				cxt.lineTo(920,i);
				cxt.stroke();
				
				// 画y坐标值
				cxt.font = "10px Courier New";
                //设置字体填充颜色
                cxt.fillStyle = "blue";
                //从坐标点(50,50)开始绘制文字
                cxt.fillText(String(260-i), 10, i);
            }
			
			// 3.轨迹点
			var radio = 4;//半径
			var num =0;
			for(num=0;num<100;num++){
				if(dataObj.hasOwnProperty(String(num))){
					if(num ==0){
						document.getElementById("myDiv2").innerHTML = String(dataObj[String(num)].x);
					}
					var oldx=x;
					var oldy=y;
					var x=40+num*10;// 10的步进
					var y=260 - dataObj[String(num)].x;
					
					// 3.1 记录点
					cxt.fillStyle='#FF0000';
			        cxt.beginPath();
                    cxt.arc(x,y,radio,0,Math.PI*2,true);
                    cxt.closePath();
                    cxt.fill();
			
			        // 3.2 记录点的中心点
			        cxt.fillStyle='#000F0F';
			        cxt.beginPath();
                    cxt.arc(x,y,1,0,Math.PI*2,true);
                    cxt.closePath();
                    cxt.fill();
					
					// 3.3 连线
					cxt.strokeStyle='#00000F';
			        cxt.lineWidth = 2; 
			        cxt.moveTo(oldx,oldy);
                    cxt.lineTo(x,y);
                    cxt.stroke();
					
				}else{
					document.getElementById("myDiv").innerHTML=num;
					break;
				}
			}
			
			
			// 第二个画布，描绘动点
			var canvas2=document.getElementById('myCanvas2');
            var cxt2=canvas2.getContext('2d');
			cxt2.clearRect(0, 0, 920, 50);
			if(dataObj.hasOwnProperty(String(0))){		
			    //document.getElementById("myDiv3").innerHTML = String(dataObj[String(num)].x);
				var bx = 60;
				var by = 30;
				var radio2 = 10;
				// 3.基准点
                cxt2.fillStyle='#FF0000';
			    cxt2.beginPath();
                cxt2.arc(bx,by,radio2,0,Math.PI*2,true);
                cxt2.closePath();
                cxt2.fill();
			
			    // 3.1 基准点的中心点
			    cxt2.fillStyle='#FFFFFF';
			    cxt2.beginPath();
                cxt2.arc(bx,by,1,0,Math.PI*2,true);
                cxt2.closePath();
                cxt2.fill();
	
	            // 4.动点
				var lx = (bx+Number(dataObj[String(0)].x*5));
				var ly = (by+Number(dataObj[String(0)].y));
				cxt2.fillStyle='#00FF00';
				cxt2.beginPath();
				cxt2.arc(lx,ly,radio2,0,Math.PI*2,true);
				cxt2.closePath();
				cxt2.fill();
			
				// 4.1 动点的中心点
				cxt2.fillStyle='#FFFFFF';
				cxt2.beginPath();
				cxt2.arc(lx,ly,1,0,Math.PI*2,true);
				cxt2.closePath();
				cxt2.fill();
						
				// 5.连接线
				cxt2.strokeStyle='#0F000F';
				cxt2.lineWidth = 2; 
				cxt2.moveTo(bx,by);
				cxt2.lineTo(lx,ly);
				cxt2.stroke();
				
				// 显示值
				cxt2.font = "15px Courier New";
                //设置字体填充颜色
                cxt2.fillStyle = "red";
                //从坐标点(50,50)开始绘制文字
                cxt2.fillText("当前值:"+String(Number(dataObj[String(0)].x)), 100, 25);
			}
					
	        t=setTimeout("loadXMLDoc()",1000);
        }
    //}

	//xmlhttp.open("GET","http://localhost/index.php/position/get_n/1000/10/90",true);
    //xmlhttp.send();
}
function stopCount(){
   clearTimeout(t);
}
function cnvs_getCoordinates(e){
	var canvas=document.getElementById('myCanvas');
	var rect = canvas.getBoundingClientRect();
	x=e.clientX - rect.left;
	y=e.clientY - rect.top;
    document.getElementById("myDiv4").innerHTML="(" + parseInt(x-40) + "," + parseInt(260-y) + ")";
}
 
function cnvs_clearCoordinates(){
    document.getElementById("myDiv4").innerHTML="(0,0)";
}
</script>

</body>
</html>
