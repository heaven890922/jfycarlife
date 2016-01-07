<?
session_start();
include_once '../Index/head.php';
include_once '../Bin/MySqlHelper.class.php';
include_once '../Bin/Function.php';
//图片链接，运费等配置信息
include_once 'action/config.php';
$Mysql=new MySqlHelper();


//查询商品信息
$sql="select g.gdid,g.gdname,g.gddescribe,g.bigpic,d.dprice,SUM(salenum) AS totalsalenum from sh_store_goods  g LEFT  JOIN sh_store_goods_detail d ON g.gdid=d.gdid   WHERE d.state='ON'   GROUP BY g.gdid  ORDER BY dprice LIMIT 5";

$sqllimit="select g.gdid,g.gdname,g.gddescribe,g.bigpic,d.dprice,SUM(salenum) AS totalsalenum from sh_store_goods  g LEFT  JOIN sh_store_goods_detail d ON g.gdid=d.gdid   WHERE d.state='ON'   GROUP BY g.gdid  ORDER BY dprice LIMIT";
$dataTable=$Mysql->FetchData($sql);


 function csubstr($str, $length,$charset="utf-8")
{ 
	$suffix=true;
 	$start=0;
   if(function_exists("mb_substr")) 
 
   { 
 
       if(mb_strlen($str, $charset) <= $length) return $str; 
 
       $slice = mb_substr($str, $start, $length, $charset); 
 
   } 
 
   else
 
   { 
 
       $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/"; 
 
       $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/"; 
 
       $re['gbk']          = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/"; 
 
       $re['big5']          = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/"; 
 
       preg_match_all($re[$charset], $str, $match); 
 
       if(count($match[0]) <= $length) return $str; 
 
       $slice = join("",array_slice($match[0], $start, $length)); 
 
   } 
 
   if($suffix) return $slice."…"; 
 
   return $slice; 
 
}


?>
 <link rel="stylesheet" href="ResponsiveSlides/responsiveslides.css">
 <link rel="stylesheet" href="ResponsiveSlides/demo/demo.css">
 

        
       
 <script src="../Myjquery/JQUERY-CONFIRM/js/pretty.js"></script>
        
        
<!-- Add the minified version of files from the /dist/ folder. -->
<!-- jquery-confirm files -->
<link rel="stylesheet" type="text/css" href="../Myjquery/JQUERY-CONFIRM/css/jquery-confirm.css" />
<script type="text/javascript" src="../Myjquery/JQUERY-CONFIRM/js/jquery-confirm.js"></script>
 
 
 <script type="text/javascript">  
var isLoad = true;
		$(function() { 
		//isTouchDevice();
		$("#over").html("上拉加载更多");	
			
			//var winH = $(window).height();
			//alert(winH); //页面可视区域高度  
			//var i = 1;
			 $("#over").hide();
			 $("#overfall").hide();
			if(i==0){
			$("#over").show();
			$("#over").html("上拉显示周边商户");	
				}
		});  
</script>  

        <script type="text/javascript"> 
		
		
		
		$(function(){
			var wd=document.body.clientWidth;
			var height=wd/3.441;
			var upheight=height/1.46;
			var footheight=height-height/1.46;
			$(".picc").css("background-size","100% "+height+"px");
			$(".picc").css("height",height+"px");
			$(".upinfo").css("height",upheight+"px");
			$(".footheight").css("height",footheight+"px");
			//alert(height);
			})
		

		
		 
		//截取字符串函数
		 function cutstr(str, len) {
				var str_length = 0;
				var str_len = 0;
				str_cut = new String();
				str_len = str.length;
				for (var i = 0; i < str_len; i++) {
					a = str.charAt(i);
					str_length++;
					if (escape(a).length > 4) {
						//中文字符的长度经编码之后大于4  
						str_length++;
					}
					str_cut = str_cut.concat(a);
					if (str_length >= len) {
						str_cut = str_cut.concat("...");
						return str_cut;
					}
				}
				//如果给定字符串小于指定长度，则返回源字符串；  
				if (str_length < len) {
					return str;
				}
			}
		
		
		
            //全局变量，触摸开始位置  
            //var startX = 0, startY = 0;  
              
            //touchstart事件  
           /* function touchSatrtFunc(evt) {  
                try  
                {  
                    //evt.preventDefault(); //阻止触摸时浏览器的缩放、滚动条滚动等  
  
                    var touch = evt.touches[0]; //获取第一个触点  
                    var x = Number(touch.pageX); //页面触点X坐标  
                    var y = Number(touch.pageY); //页面触点Y坐标  
                    //记录触点初始位置  
                    startX = x;  
                    startY = y;  
  
                    var text = 'TouchStart事件触发：（' + x + ', ' + y + '）';  
					//alert("TouchStart事件触发");
                   // document.getElementById("result").innerHTML = text;  
                }  
                catch (e) {  
                    alert('touchSatrtFunc：' + e.message);  
                }  
            }  */
  
            //touchmove事件，这个事件无法获取坐标
			
			
			
			var flog=true;
			var winH = $(window).height();
            function touchMoveFunc(evt) { 
				 
                try  
                {  
                    //evt.preventDefault(); //阻止触摸时浏览器的缩放、滚动条滚动等  
                    var touch = evt.touches[0]; //获取第一个触点  
                    var x = Number(touch.pageX); //页面触点X坐标  
                    var y = Number(touch.pageY); //页面触点Y坐标  
  
                    var text = 'TouchMove事件触发：（' + x + ', ' + y + '）';  					
				    var sqllimit=$("#sqllimit").val();
					//var i=$("#i").val();
						//alert(i);
					    //encodeURI(sqllimit).replace(/\+/g,'%2B');
					   //alert(sqllimit) ;
					
                        var pageH = document.body.scrollHeight; 
						//alert(pageH); 
                        var scrollT = $(window).scrollTop(); //滚动条top
						 //alert(scrollT); 
                        var aa = (pageH - winH - scrollT) / winH;  
						if(isLoad==true){
							isLoad = false;
							return;
							}
					  
                        if (aa < 0.0001&&flog) { 
						//alert(sqllimit);
						  flog=false;
						$("#over").show();
						$("#sl").html("周边商户");
						//alert(i); 
                         var params ="sqllimit="+sqllimit+"&page=" + i;
						 //alert(params);return; 
						 var url = "getGoodsPic.php" ; 
						 $.ajax({  
							type: "post",  
							url: url,  
							dataType: "json",  
							data: params,  
							success: function(msg){
								var str = "";
								//alert(i);
								if(msg==null){
									$("#over").hide();
									$("#overfall").show();	
								//alert(1);
									}
								else{
									$("#over").html("加载更多...");	
									$("#over").show();	
									
									}	
									//alert(msg);return;
								$.each(msg, function(index, array) {
									//arrpic=array['pictureurl'].split("|");
									//alert(arrpic[0]);
									//添加的html内容，与当前已加载的商品对应。
									var money=array['dprice']/2;
									str="";
									str=str+"<div  id=\"picc\" class=\"picc\" style=\" background:url(<?=UPLOADURL?>"+array['bigpic']+")  no-repeat; \"  onClick=\"location.href='ECshopGoodsDetailIndex.php?gdid="+array['gdid']+"'\">";
									str=str+"<div  class=\"upinfo\">";
									str=str+"<div  style=\"width:20%;  float:left; padding:0% 2%; \">";
									str=str+"<p style=\" font-size:1.4em; font-weight:900; color:#e60012;\">"+array['dprice']+"</p>";
									str=str+"</div>";
									str=str+" <div  style=\"width:40%; padding-left:3%; padding-top:3%;    float:left; \" >";
									str=str+" <p style=\"font-size:0.70em; color:#000000;\">"+cutstr(array['gdname'],18)+" </p>";
									str=str+"<p style=\"font-size:0.70em; color:#e60012;\">小车券  相当于￥"+money+" </p>";
									str=str+"</div>";
									str=str+"</div>";
									str=str+" <div  class=\"footheight\" style=\" width:60%;\">";
									str=str+" <p style=\"text-align:left; padding-left:4%; font-size:0.70em;  padding-top:0px; \">销量："+array['totalsalenum']+"</p>";
									str=str+"</div>";
									str=str+"</div>";
								$("#container").append(str); 
								var wd=document.body.clientWidth;
								var height=wd/3.441;
								var upheight=height/1.46;
								var footheight=height-height/1.46; 
								$(".picc").css("background-size","100% "+height+"px");
								$(".picc").css("height",height+"px");
								$(".upinfo").css("height",upheight+"px");
								$(".footheight").css("height",footheight+"px");	
														});  
								if(msg!=null){
									i++;
									flog=true;
									} 
								
								}
								
						 });

                        } 
  
                    //判断滑动方向  
                    
  				//alert("TouchMove事件触发");
                   // document.getElementById("result").innerHTML = text;  
                }  
                catch (e) {  
                    alert('touchMoveFunc：' + e.message);  
                }  
            }  
  
            //touchend事件  
           /* function touchEndFunc(evt) {  
                try {  
                    //evt.preventDefault(); //阻止触摸时浏览器的缩放、滚动条滚动等  
  
                    var text = 'TouchEnd事件触发'; 
					alert("TouchEnd事件触发"); 
                    //document.getElementById("result").innerHTML = text;  
                }  
                catch (e) {  
                    alert('touchEndFunc：' + e.message);  
                }  
            }  */
  
            //绑定事件  
            function bindEvent() {  
                //document.addEventListener('touchstart', touchSatrtFunc, false);  
                document.addEventListener('touchmove', touchMoveFunc, false);  
                //document.addEventListener('touchend', touchEndFunc, false);  
            }  
  
            //判断是否支持触摸事件  
            function isTouchDevice() {  
                //document.getElementById("version").innerHTML = navigator.appVersion;  
  
                try {  
                    document.createEvent("TouchEvent");  
                    //alert("支持TouchEvent事件！");  
  
                    bindEvent(); //绑定事件  
                }  
                catch (e) {  
                    alert("不支持TouchEvent事件！" + e.message);  
                }  
            }  
  
            window.onload = isTouchDevice;  
			
			

			
    </script>
 
 
 
 
 <script src="ResponsiveSlides/responsiveslides.min.js"></script>
<script language="javascript">
 
function myfav(shopid,userID){
	if(userID==0){
		
		alert('请先登录');
		return false;
		
		}
	var thistype = $('#myfav').attr('class');
	var state = 1;
	if(thistype=='favoury'){
	$('#myfav').attr('class','unlike');
	state = 0;
	}
	else{
	$('#myfav').attr('class','favoury');
	state = 1;
	}
	
	 $.get('Action.php?m=FAV&shopID='+shopid+"&state="+state, function (data) {//get方式请求服务器数据
	 
	 
	 });
	
	
	}
	
	

	
</script>
<style>
#img {height: auto; width: auto\9; width:100%; display:block; margin:0 auto;}

.picc{
	margin:10px 10px;
	height:100px;
	background-size:100% 102px;
	background-repeat:no-repeat;
	}
.datagrid{
	border-collapse:collapse;
    width:100%;
	color:#707070;
	font-size:16px;
	
}
.datagrid th{
	background:#93c2e8;
	border:1px solid #70a4cf;
	text-align:left;
	padding:3px;
	padding-left:5px;
	
}

.datagrid td{
	border:1px solid #d0d0d0;
	padding:8px;
	padding-left:8px;
}
.ui-overlay-a,a{
	color:#1f1c1c;;
	}
.buttons{
	width:88%;
	margin:auto;
	}	
.btn-default{
	color:#FFF;
	padding:8px;
	margin:5px 2% ;
	width:95%;
	background:#88cc44;
	}
.btn-default1{
	color:#51960d;
	padding:8px;
	margin:10px 2% 5px 2% ;
	width:95%;
	background:#dbf0c7;
	}
.jconfirm-box{
	margin:auto;
	width:88%;
	
	}
	
	
div.title {
  font-size: 16px;
  font-weight: bold;
  font-family: inherit;
  padding: 10px 15px 5px;
}		
</style>




</head>
<body><!--从这里开始自定义编写-->
<div data-role="page">
  <div data-role="header">
<? 
include_once '../Navigation/head_navigation-4.php';

?>

 
  </div>
   <input type="hidden" name="sqllimit"
 id="sqllimit"  value="<?=$sqllimit?>" >
 	   <input type="hidden" name="i"
 id="i"  value="<?=$i=1?>" >
    <div class="jfycontent">
    	<div id="container"> 
     <?
	
	 foreach($dataTable as $Items){
	 ?>
         
  	<div  id="picc" class="picc" style="  background:url(<?=UPLOADURL.$Items['bigpic']?>)  no-repeat; " onClick="location.href='ECshopGoodsDetailIndex.php?gdid=<?=$Items['gdid']?>'">
    	<!--图片信息div-->
        <div  class="upinfo">
        	<!--价格的div-->
            <div  style="width:20%;  float:left; padding:0% 2%; ">
            <p style=" font-size:1.4em; font-weight:900; color:#e60012; padding-bottom:0px;"><?=$Items['dprice']?></p>
            </div>
            <!--商品名称和价格信息的div-->
            <div  style="width:40%; padding-left:3%; padding-top:3%;    float:left; " >
            <p style="font-size:0.70em; color:#000000; padding-top:0px;"><?=csubstr($Items['gdname'],10,$charset="utf-8")?> </p>
            <p style="font-size:0.70em; color:#e60012; padding-bottom:0px;">小车券  相当于￥<?=$Items['dprice']/2?> </p>
            </div>
        
        </div>
        <!--型号名称（品牌信息）div-->
        <div  class="footheight" style=" width:70%">
        <p style="text-align:left; padding-left:4%; font-size:0.70em;">销量：<?=$Items['totalsalenum']?></p>
        </div>
      
    </div>
     <?
	 }
	
	 ?>     
     
      </div>    
     
      <p style="text-align:center; color:#929292; display:none;" id="over" >上拉显示周边商户</p>  
      <p style="text-align:center; color:#929292; display:none" id="overfall" onClick="javascript:document.getElementsByTagName('BODY')[0].scrollTop=0;">回到顶部</p>  
    </div><!--ebd content-->
   </div>
 

<!--page end-->
<script>
var i=$('#i').val();
if(i==0)
{
	//alert(11);
$("#over").show();	
}

	

</script>
</body>

<?
include_once 'CarwashNav.php';
include_once '../Index/foot.php';
?>