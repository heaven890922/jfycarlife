<?
session_start();
include_once '../Index/head.php';
include_once '../Bin/MySqlHelper.class.php';
include_once '../Bin/Function.php';
//图片链接，运费等配置信息
include_once 'action/config.php';

$userID=$_SESSION['coustomid'];

$Mysql=new MySqlHelper();


//查询商品信息
$sql="SELECT c.*,d.dname,d.dpic,d.dprice,d.dnum FROM sh_store_cart c JOIN sh_store_goods_detail d ON c.gdetailid =d.detailid WHERE c.state='ON' and c.userid=".$userID;

//$sqllimit="select g.gdname,g.gddescribe,g.bigpic,d.dprice,SUM(salenum) AS totalsalenum from sh_store_goods  g LEFT  JOIN sh_store_goods_detail d ON g.gdid=d.gdid   WHERE d.state='ON'   GROUP BY g.gdid  ORDER BY dprice LIMIT";
$dataTable=$Mysql->FetchData($sql);
$row=count($dataTable);

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

<!--控制数量加减的js和css-->
 <link href="spinner/css/jquery.spinner.css" rel="stylesheet" type="text/css">
 <script type="text/javascript" src="spinner/js/jquery.spinner.js"></script>       
<!--控制数量加减的js和css-->

       
 <script src="../Myjquery/JQUERY-CONFIRM/js/pretty.js"></script>
        
        
<!-- Add the minified version of files from the /dist/ folder. -->
<!-- jquery-confirm files -->
<link rel="stylesheet" type="text/css" href="../Myjquery/JQUERY-CONFIRM/css/jquery-confirm.css" />
<script type="text/javascript" src="../Myjquery/JQUERY-CONFIRM/js/jquery-confirm.js"></script>
<link rel="stylesheet" href="jiaoben897/css/zzsc.css"> 

   

 <script type="text/javascript"> 

var allcheck=false;	
$(function(){
	var wd=document.body.clientWidth;
	var height=wd/3.441;
	var upheight=height/1.46;
	var footheight=height-height/1.46;
	//$(".cartinfo").css("width",wd-260+"px");
	$(".picc").css("background-size","100% "+height+"px");
	$(".picc").css("height",height+"px");
	$(".upinfo").css("height",upheight+"px");
	$(".footheight").css("height",footheight+"px");
	
	
	//alert(height);
})

//统计所选商品总价格

function tongji(){
	$(".show-page-loading-msg").click();
	var snum=0;
	var billmoney=0;
	var strid='';
	$("input[name='childs[]']:checkbox:checked").each(function(){ 
		var id=$(this).val();
		strid=strid+id+",";
		var itemmoney = $('#pre_price_'+id).html();
		var num=$("#sdnum_"+id).val();
		snum++;
		billmoney=parseFloat(billmoney)+(parseFloat(itemmoney)*parseFloat(num));
	});
	//alert(strid);return; 
    var params="strid="+strid+"&m=CHECK";
	var url="action/carAction.php";
	$.ajax({  
		type: "post",  
		url: url,  
		dataType: "json",  
		data: params,  
		success: function(msg){ 
		//alert(msg.code);
		$(".hide-page-loading-msg").click();
			 /* if( msg.code==200 ) 
			   {  
				  
			   }else{
				 //location.reload();  
				  //alert(msg.mess); 
				 $(".hide-page-loading-msg").click(); 
			   }*/
			  
      		}  
  
		}); 
	
	$('#total_money').html(billmoney);
}


//仅仅统计，不会更新购物车的check状态
function justtongji(){
	var snum=0;
	var billmoney=0;
	$("input[name='childs[]']:checkbox:checked").each(function(){ 
		var id=$(this).val();
		var itemmoney = $('#pre_price_'+id).html();
		var num=$("#sdnum_"+id).val();
		snum++;
		billmoney=parseFloat(billmoney)+(parseFloat(itemmoney)*parseFloat(num));
	});
	$('#total_money').html(billmoney);
}


//页面加载完成
$(function(){
	
	justtongji();
	checkIfAll();
	$(".tongji").click(function(e) {
      	tongji();  
    });	
	
})



//删除购物车中的商品
function deletecart(id){
	if(confirm("是否删除该商品？")){
			$(this).hide();
	$("#deleteinfo_"+id).html("...");
	var params="id="+id+"&m=DELETE";	
	var url="action/carAction.php";
	
	  $.ajax({  
		type: "post",  
		url: url,  
		dataType: "json",  
		data: params,  
		success: function(msg){ 
		//alert(msg.code);
			  if( msg.code ==200 ) 
			   {  
			 	location.reload();
			   } 
			   else{
				   var mess = msg.mess;   
				   alert(mess);
				   
				 $("#deleteinfo_"+id).html("<img src=\"image/delect.png\"  style=\" float:right; \" width=\"30px\" height=\"35px\"  onClick=\"deletecart("+id+")\">");
				 
			   }
      		}  
  
		}); 
		}
}

//全选和全不选切换
function selectAll(type){
	 //alert(flag);
	 if(type==2){
		var checkall=document.getElementById("checkboxall");
		if(!allcheck){
			checkall.checked=true;
			
		}else{
			checkall.checked=false;	
			
		}
 	 }
	
	 
	 var ids = document.getElementsByName("childs[]");
	 //全选
	
	 if(!allcheck){                       
		  //alert(allcheck);
		  $("input[name='childs[]']").each(function() {
       		 $(this).prop("checked",true); 
			});
		  
		  allcheck=true;
		  
	 }else{
	 //全不选
		  $("input[name='childs[]']").each(function() {
       		 $(this).prop("checked",false); 
			});
		 allcheck=false;	 
	 }
	tongji(); 
}

//手动输入商品数量是AJAX改变购物车表sh_store_cart的snum商品数量
function changenum_input(id){
	$(".show-page-loading-msg").click();
	var tempnum=$("#sdnum_"+id).val();
	var params="id="+id+"&num="+tempnum+"&m=CHANGENUM";
	var url="action/carAction.php";
	
	$.ajax({  
		type: "post",  
		url: url,  
		dataType: "json",  
		data: params,  
		success: function(msg){ 
			$(".hide-page-loading-msg").click();
			 if(msg.code==200){
				 $("#check_"+id).prop("checked", true);
				  tongji();  
			  }else{
				  alert(msg.mess);  
			  }
			
      	}  
  
	}); 
}





//修改sh_store_cart购物车的商品数量。
function changenum(id,type){
	$(".show-page-loading-msg").click();
	var tempnum=0;
	tempnum=$("#sdnum_"+id).val();
	if(type==1){
		tempnum++;	
	}else{
		tempnum--;	
	}
	//alert(tempnum);
	var params="id="+id+"&num="+tempnum+"&m=CHANGENUM";
	var url="action/carAction.php";
	
	$.ajax({  
		type: "post",  
		url: url,  
		dataType: "json",  
		data: params,  
		success: function(msg){ 
			$(".hide-page-loading-msg").click();
			  if(msg.code==200){
				  $("#check_"+id).prop("checked", true);
				 tongji(); 
			  }else{
				  alert(msg.mess);	  
			  }
			
      		}  
  
		}); 
		
}
//检查是否全部手动选中，选中则把checkall选中
function checkIfAll(){
	var flag=true;
	$("input[name='childs[]']").each(function(){ 
		var check=$(this).is(':checked');
		if(!check){
			flag=false;	
		}
	});
	if(flag){
		$("#checkboxall").prop("checked", true);	
		allcheck=true;
	}else{
		$("#checkboxall").prop("checked", false);
	}
	
	
		
	//alert(allcheck);
}



//去结算
function accountit(){
	// var ids = document.getElementsByName("childs[]");               	
	  var flag = false;
	  var str="";
	  var i=0;			
	 $("input[name='childs[]']:checkbox:checked").each(function(){ 
		var id=$(this).val();
		var num=$("#sdnum_"+id).val();
		str=str+"&resdnum["+i+"]="+num;
		flag=true;
		i++;
		
	}) 
	  if(!flag){
		  alert("请最少选择一项！");
		  return false;
	  }else{
		 /* var params=$("#formcart").serialize();
		  params=params+"&m=TOACCOUNT";
		  params=params+str;
		  var url="action/carAction.php";
		  alert(params);return;
		  $.ajax({  
			  type: "post",  
			  url: url,  
			  dataType: "json",  
			  data: params,  
			  success: function(msg){ 
			  alert(msg.mess);
			  if( msg.code ==200 ) 
			   {  
				location.reload();
			   } 
			   else{
				 var mess = msg.mess;   
				 alert(mess); 
				 $("#deleteinfo_"+id).html("<img src=\"image/delect.png\"  style=\" float:right; \" width=\"30px\" height=\"35px\"  onClick=\"deletecart("+id+")\">");
			  }
			}  
		  });     */
		 location.href="ECshopOrder.php";
	  }	
}



		
</script>
 
 

 


<style>

table {
		overflow:hidden;
		/*border:1px solid #d3d3d3;*/
		background:#fefefe;
		width:100%;
		margin-top:20px;
		
		-moz-border-radius:5px; /* FF1+ */
		-webkit-border-radius:5px; /* Saf3-4 */
		border-radius:5px;
		-moz-box-shadow: 0 0 4px rgba(0, 0, 0, 0.2);
		-webkit-box-shadow: 0 0 4px rgba(0, 0, 0, 0.2);
		
	}
	
	th, td { padding:2px 0px; text-align:left;  }
	
	th {  background:#e8eaeb; text-align:center; font-weight:200;}
	
	/*td {border-top:1px solid #e0e0e0;}*/
	
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




#picc img {
	magin:0px 1%;
	border:#ccc 1px solid;
}
.cartinfo{
	width:48%;
	float:left; 
	height:inherit;
	 padding-left:2%;
}
.cartinfo p{
	margin:0;
	padding:0;
}		

.noGoods{
 widows:100%; padding:10px;
 text-align:center;
 	
}
.noGoods img{
	margin:0px auto;	
}	
</style>




</head>
<body><!--从这里开始自定义编写-->
<div data-role="page">
  <div data-role="header">
<? 
include_once '../Navigation/head_navigation.php';

?>
<h1><div style="height:30px; font-size:18px;">购物车</div></h1>
 
  </div>
 	
    <div class="jfycontent" style="padding-bottom:45px;">
    <?
		if($row>0){
	?>
    	
    
        <div style=" border-bottom:#ccc 1px solid; border-top:#ccc 1px solid; margin-top:5px; padding:8px 0px 0px 5px;   ">
         <input    onClick="selectAll(1);"  name="checkbox" class="tongji" id="checkboxall" type="checkbox"  style="margin:4px 0px 0px 0px; " data-role="none">
         <p style="color:#8c4; text-align:right; padding:5px 0px; margin:0; margin-top:-25px;  margin-bottom:5px;">小车生活，品质保证，放心购买！</p>
        </div>
        
    	<div id="container"> 
      <form name="formcart" id="formcart" method="post" action="ECshopOrder.php">   
     <?
	
	 foreach($dataTable as $Items){
	 ?>
     <table>
     	<tr>
            <td rowspan="3" width="23px">
                <input name="childs[]" class="tongji" id="check_<?=$Items['gdetailid']?>"  type="checkbox"   onClick="checkIfAll()" style=" margin-left:2px;"  data-role="none"  value="<?=$Items['gdetailid']?>" <?=$Items['checkstate']=='ON'?'checked':''?>>
            </td>
            <td rowspan="3" width="70px">
                 <img src="<?=UPLOADURL.$Items['dpic']?>" width="65px;" height="80px;"  >
            </td>
            <td rowspan="1" >
                <?=csubstr($Items['gsname']."-".$Items['gdname'],20,$charset="utf-8")?>
            </td>
            <td rowspan="3">
            <img src="image/delect.png"  style=" float:right; " width="30px" height="35px"  onClick="deletecart(<?=$Items['id']?>)">
            </td>
        </tr>
        
        <tr>
            <td>
            	￥<span id="pre_price_<?=$Items['gdetailid']?>"><?=$Items['dprice']?></span>
            </td>
        </tr>
        <tr>
            <td>
            	 <input  id="sdnum_<?=$Items['gdetailid']?>" type="text"  class="spinnerExample"  name="sdnum[]"  data-role="none"  value="<?=$Items['snum']?>" onChange="changenum_input(<?=$Items['gdetailid']?>)"/>
            </td>
        </tr>
     </table>
     
     <?
	 }
	
	 ?>     
     </form>
     
   		
     
      </div>    
     <?	 
		}else{
	 ?>
      <div class="noGoods">
      <img src="image/storecart.png" width="160" height="160">
      </div>
      <div class="noGoods" style="font-size:14px;">
      	购物车还是空的，去挑几件中意的东西吧！
      </div>
      <br/>
      <br/>
      <a href="#" onClick="location.href='ECshopIndex.php'" class="ui-btn ui-corner-all" style=" border-color:#88cc44; color:#88cc44; width:76%; margin:auto;  padding: 0.5em 1em;">去逛逛</a>
	  <?
          }	
      ?>
    </div><!--ebd content-->
<?
include_once 'ECshopLoading.php';
?>
    
   </div>
 <!--控制数量加减的js初始化-->
<script type="text/javascript">
    $('.spinnerExample').spinner({});
	
</script>
<!--控制数量加减的js初始化-->


<!--page end-->
<script>
var i=$('#i').val();
if(i==0)
{
	//alert(11);
$("#over").show();	
}

	

</script>

<div id="stickey_footer">
  <ul id="footer_menu">
   
    <li class="tongji" onClick="selectAll(2)"><a href="#" >全选</a></li>
    <li><p style="margin:0; color:#F00;">总金额：￥<span id="total_money">0</span></p></li>
    <li style="border-right:none;" id="gotoaccount" onClick="accountit()"><a href="#" >去结算</a></li>
    
  </ul>
 
</div>


</body>



<?
include_once 'CarwashNav.php';
include_once '../Index/foot.php';
?>