<?
session_start();
include_once '../Index/head.php';
include_once '../Bin/MySqlHelper.class.php';
include_once '../Bin/Function.php';
include_once 'action/config.php';
$Mysql=new MySqlHelper();
$userid=$_SESSION['coustomid'];

//查询商品信息
$sql="SELECT o.orderid,o.money,o.orderstate,o.ordernum,o.ordertime,GROUP_CONCAT(gd.dpic) as picstr,gd.dname,g.gdname,COUNT(d.orderdetailid) AS dordernum FROM sh_store_order o JOIN sh_store_order_detail d ON o.orderid=d.orderid JOIN sh_store_goods_detail  gd ON d.goodsid=gd.detailid  JOIN sh_store_goods g ON gd.gdid=g.gdid WHERE userid=188 and o.delstate='N'  GROUP BY orderid ORDER BY ordertime DESC LIMIT 5";

$sqllimit="SELECT o.orderid,o.money,o.orderstate,o.ordernum,o.ordertime,GROUP_CONCAT(gd.dpic) as picstr,gd.dname,g.gdname,COUNT(d.orderdetailid) AS dordernum FROM sh_store_order o JOIN sh_store_order_detail d ON o.orderid=d.orderid JOIN sh_store_goods_detail  gd ON d.goodsid=gd.detailid  JOIN sh_store_goods g ON gd.gdid=g.gdid WHERE userid=188 and o.delstate='N'  GROUP BY orderid  ORDER BY ordertime DESC LIMIT ";


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
$(document).ready(function(){
	gotolink();
});


function gotolink(){
	$(".defult").click(function(e) {
    var orderNum=$(this).attr("id");
	location.href='ECshopOrderPay.php?orderNum='+orderNum;
	});
	
	$(".back").click(function(e) {
		var orderNum=$(this).attr("id");
		//alert(orderNum);return;
		location.href='ECshopOrderCancel.php?orderNum='+orderNum;
	});	
	
	$(".get").click(function(e) {
		var orderNum=$(this).attr("id");
		location.href='ECshopOrderGet.php?orderNum='+orderNum;
	});
	
	$(".get_bg").click(function(e) {
		if(confirm("是否确定确认收货？")){
		  var orderNum=$(this).attr("id");
		  var params="orderNum="+orderNum+"&m=GET";
		  var url="action/orderAction.php";
		  $.ajax({  
			  type: "post",  
			  url: url,  
			  dataType: "json",  
			  data: params,  
			  success: function(msg){ 
				  if(msg.code==200){
					  location.reload();	
				  }else{
					  alert(msg.mess);
				  }
				  
			  }
			   
		  }); 
		}
	});
	
	$(".order_info").click(function(e) {
		var orderNum=$(this).attr("id");
		location.href='ECshopOrderInfo.php?orderNum='+orderNum;
	});	
}

	
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
			 var url = "action/getOrderInfo.php" ; 
			 $.ajax({  
				type: "post",  
				url: url,  
				dataType: "json",  
				data: params,  
				success: function(msg){
					//alert(msg);
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
					
					
					if(msg!=null){
						$.each(msg, function(index, array) {
						//arrpic=array['pictureurl'].split("|");
						//alert(arrpic[0]);
						//添加的html内容，与当前已加载的商品对应。
						var pstr="";
						var classtype="";
						if(array['orderstate']=="FORCANCEL"){
							 pstr="退款审核中";
							classtype="order_info";
						}else if(array['orderstate']=='WDELIVER'){
							pstr="待发货";
							classtype="back";	
						}else if(array['orderstate']=='PRE'){
							pstr="去付款";
							classtype="defult"; 
						}else if(array['orderstate']=='CANCEL'){
							pstr="已取消";
							classtype="order_info";
						}else if(array['orderstate']=='DELIVERING'){
							pstr="已发货";
							classtype="get";
						}else if(array['orderstate']=='FINISH'){
							pstr="已完成";
							classtype="order_info";
						}
						var str="<table >";
						str=str+" <tr id=\""+array['ordernum']+"\" class=\""+classtype+"\">";
						if(array['dordernum']>1){
							var tempnum=array['dordernum']>3?4:array['dordernum'];
							var obj=array['picstr'].split(",");
							//alert(obj[0]); 
							str=str+"<td colspan=\"3\" >";
							for(var i=0;i<tempnum;i++){
								str=str+"<div style=\"float:left; margin-right:10px;\"><img src=\"<?=UPLOADURL?>"+obj[i]+"\" width=\"60px\"  /></div>";
							}
							str=str+"</td>";
						}else{
							str=str+"<td colspan=\"1\"  ><img src=\"<?=UPLOADURL?>"+array['picstr']+"\" width=\"60px\"  /> </td>";
							str=str+"<td     colspan=\"2\" ><p  style=\" height:40px;overflow:hidden;text-overflow:ellipsis;\">"+array['gdname']+"——"+array['dname']+"</p></td>";
						}
						str=str+"<tr> <td width=\"30\" style=\" padding:8px 0px 8px 5px;\" colspan=\"1\"  >"+array['money']+"</td>";
						str=str+"<td width=\"45\" style=\" padding:8px 0px;\">小车券</td>";
						str=str+" <td width=\"144\" colspan=\"1\" style=\" text-align:right\" ><span"; 
						if(array['orderstate']=='PRE'){
							str=str+" class='address_defult defult defult_bg' id=\""+array['ordernum']+"\" ";	
						}
						
						str=str+" >"+pstr+"</span>";
						
						
						if(array['orderstate']=='WDELIVER'){
					  
						str=str+"&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<span id=\""+array['ordernum']+"\"  class=\"address_defult back back_bg\">申请退款</span>"
						
						}else if(array['orderstate']=='DELIVERING'){
						
						str=str+"&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<span id=\""+array['ordernum']+"\"  class=\"address_defult  get_bg\" >确认收货</span>";
						
						}
						str=str+"</td></tr></table>";
						
						$("#container").append(str); 
						
					}); 
						gotolink(); 
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


<style>


	table {
		overflow:hidden;
		border:1px solid #d3d3d3;
		background:#fefefe;
		width:100%;
		margin-top:20px;
		
		-moz-border-radius:5px; /* FF1+ */
		-webkit-border-radius:5px; /* Saf3-4 */
		border-radius:5px;
		-moz-box-shadow: 0 0 4px rgba(0, 0, 0, 0.2);
		-webkit-box-shadow: 0 0 4px rgba(0, 0, 0, 0.2);
		font-size:14px;
		
	}
	
	th, td {padding:8px 5px 8px; text-align:left;  }
	
	th {  background:#e8eaeb; text-align:center; font-weight:200;}
	
	
	
	tr.odd-row td {background:#f6f6f6;}
	
	td.first, th.first {text-align:left}
	
	td.last {border-right:none;}
	
	/*
	Background gradients are completely unnecessary but a neat effect.
	*/
	
	td {
		background: -moz-linear-gradient(100% 25% 90deg, #fefefe, #f9f9f9);
		background: -webkit-gradient(linear, 0% 0%, 0% 25%, from(#f9f9f9), to(#fefefe));
	}
	
	tr.odd-row td {
		background: -moz-linear-gradient(100% 25% 90deg, #f6f6f6, #f1f1f1);
		background: -webkit-gradient(linear, 0% 0%, 0% 25%, from(#f1f1f1), to(#f6f6f6));
	}
	
	th {
		background: -moz-linear-gradient(100% 20% 90deg, #e8eaeb, #ededed);
		background: -webkit-gradient(linear, 0% 0%, 0% 20%, from(#ededed), to(#e8eaeb));
	}
	
	/*
	I know this is annoying, but we need additional styling so webkit will recognize rounded corners on background elements.
	Nice write up of this issue: http://www.onenaught.com/posts/266/css-inner-elements-breaking-border-radius
	
	And, since we've applied the background colors to td/th element because of IE, Gecko browsers also need it.
	*/
	
	tr:first-child th.first {
		-moz-border-radius-topleft:5px;
		-webkit-border-top-left-radius:5px; /* Saf3-4 */
	}
	
	tr:first-child th.last {
		-moz-border-radius-topright:5px;
		-webkit-border-top-right-radius:5px; /* Saf3-4 */
	}
	
	tr:last-child td.first {
		-moz-border-radius-bottomleft:5px;
		-webkit-border-bottom-left-radius:5px; /* Saf3-4 */
	}
	
	tr:last-child td.last {
		-moz-border-radius-bottomright:5px;
		-webkit-border-bottom-right-radius:5px; /* Saf3-4 */
	}

		
	
	
   .th-groups  {
    background-color: rgba(0,0,0,0.07);
    border-right: 1px solid #fff;
    text-align: center;
}
@media screen and (max-width: 72em) {
    
    .financial-table-reflow th: first-child {
        color: #fff;
        background-color: #555;
        font-size: 1.2em;
        padding: .3em .6em .3em .6em;
        -webkit-text-shadow: none;
        -moz-text-shadow: none;
        text-shadow: none;
    }
   
    .financial-table-reflow th: first-child .ui-table-cell-label {
        display: none;
    }
    
    .ui-table-reflow th .ui-table-cell-label-top,
    .ui-table-reflow td .ui-table-cell-label-top {
        font-weight: bold;
        color: #319B47;
        font-size: 1.1em;
    }
}

@media screen and (min-width: 72em) {
    
    .financial-table-reflow td,
    .financial-table-reflow th,
    .financial-table-reflow tbody th,
    .financial-table-reflow tbody td,
    .financial-table-reflow thead td,
    .financial-table-reflow thead th {
        display: table-cell;
        margin: 0;
    }
   
    .financial-table-reflow td .ui-table-cell-label,
    .financial-table-reflow th .ui-table-cell-label {
        display: none;
    }
}
@media (max-width: 72em) {
    .financial-table-reflow td,
    .financial-table-reflow th {
        width: 100%;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        float: left;
        clear: left;
    }

}

.address_defult{
	padding:2px 7px 2px 7px;
	color:#fff;
	height:30px;
	background:#373737;	
    -moz-border-radius: 4px;
    -webkit-border-radius: 4px;
    border-radius:4px;
	text-shadow: 0px 0px 0px #F3F3F3;
	
}


.defult{
	
}

.defult_bg{
	background:#e03737;	
}

.get{
	
}

.get_bg{
	background:#27A744;	
}
.back{
	
}

.back_bg{
	background:#373737;	
}

#container{
	padding:0px 10px 25px 10px;
}

.order_info{
	
}
</style>  



</head>
<body><!--从这里开始自定义编写-->
<div data-role="page">
  <div data-role="header">
<? 
include_once '../Navigation/head_navigation.php';

?>
<h1><div style="height:30px; font-size:18px;">全部订单</div></h1>
 
  </div>

    <div class="jfycontent">
     <input type="hidden" name="sqllimit" id="sqllimit"  value="<?=$sqllimit?>" >
 	   <input type="hidden" name="i" id="i"  value="<?=$i=1?>" >
    
      <div id="container"> 
		  <?
              foreach($dataTable as $Items){
          ?>
          <table >
   		
        	 <?
		   		if($Items['orderstate']=='FORCANCEL'){
					$pstr="退款审核中";
					$class="order_info";
				}elseif($Items['orderstate']=='WDELIVER'){
					$pstr="待发货";
					$class="back";	
				}elseif($Items['orderstate']=='PRE'){
					$pstr="去付款";
					$class="defult"; 
				}elseif($Items['orderstate']=='CANCEL'){
					$pstr="已取消";
					$class="order_info";
				}elseif($Items['orderstate']=='DELIVERING'){
					$pstr="已发货";
					$class="get";
				}elseif($Items['orderstate']=='FINISH'){
					$pstr="已完成";
					$class="order_info";
				}
				
		   ?>
        
      
        <tr id="<?=$Items['ordernum']?>" class="<?=$class?>" >
         <?
	   		if($Items['dordernum']>1){
	     ?>
        
            <td colspan="3" >
            <?
				$pircarray=explode(",",$Items['picstr']);
				$i=0;
				foreach($pircarray as $itempic){
			?>
              <div style="float:left; margin-right:10px;">
                 <img src="<?=UPLOADURL.$itempic?>" width="60px"  />
              </div>
            <?
					if($i==3){
						break;	
					}
				}
			?>
            </td>  
          <?
			}else{	
		  ?> 
          <td colspan="1"  >
          	<img src="<?=UPLOADURL.$Items['picstr']?>" width="60px"  />
          </td>
          <td     colspan="2" >
          	<p  style=" height:40px;overflow:hidden;text-overflow:ellipsis;"><?=$Items['gdname']."——".$Items['dname']?></p>
          </td>
          <?
			}
		  ?> 
        </tr>

        <tr>
            <td width="30" style=" padding:8px 0px 8px 5px;" colspan="1"  >
			<?=$Items['money']?>
            </td>
            <td width="45" style=" padding:8px 0px;">
            小车券
            </td>
            <td width="144" colspan="1" style=" text-align:right" >
          
            <span  <? if($Items['orderstate']=='PRE'){?> class='address_defult defult defult_bg' id="<?=$Items['ordernum']?>" <? }?>><?=$pstr?></span>
           
		   <?
            if($Items['orderstate']=='WDELIVER'){
           ?>
			&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<span id="<?=$Items['ordernum']?>"  class="address_defult back back_bg">申请退款</span>
			<?
            }elseif($Items['orderstate']=='DELIVERING'){
            ?>
            &nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<span id="<?=$Items['ordernum']?>"  class="address_defult  get_bg" >确认收货</span>
            <?
			}
			?>
            
            </td>
           
            
        </tr>
        
    
</table>
          <?
              }
          ?>
     
     
      	</div> 
        <p style="text-align:center; color:#929292; display:none;" id="over" >上拉显示周边商户</p>  
        <p style="text-align:center; color:#929292; display:none" id="overfall" onClick="javascript:document.getElementsByTagName('BODY')[0].scrollTop=0;">回到顶部</p>  
    </div><!--ebd content-->
</div>
 
<?
include_once 'ECshopLoading.php';
?>
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