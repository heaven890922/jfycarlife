<?
session_start();
include_once '../Index/head.php';
include_once '../Bin/MySqlHelper.class.php';
include_once '../Bin/Function.php';
$_SESSION['coustomid']=2;

$userID=$_SESSION['coustomid'];

$Mysql=new MySqlHelper();


//查询商品信息
$sql="SELECT c.*,d.dname,d.dpic,d.dprice,d.dnum FROM sh_store_cart c JOIN sh_store_goods_detail d ON c.gdetailid =d.detailid WHERE c.state='ON' and c.userid=".$userID;

//$sqllimit="select g.gdname,g.gddescribe,g.bigpic,d.dprice,SUM(salenum) AS totalsalenum from sh_store_goods  g LEFT  JOIN sh_store_goods_detail d ON g.gdid=d.gdid   WHERE d.state='ON'   GROUP BY g.gdid  ORDER BY dprice LIMIT";
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
	 //alert(11);
	 if(!allcheck){                       
		 
		  $("input[name='childs[]']").filter(':checkbox').prop('checked', true).checkboxradio("refresh");
		  allcheck=true;
	 }else{
	 //全不选
		 $("input[name='childs[]']").filter(':checkbox').prop('checked', false).checkboxradio("refresh");
		 allcheck=false;	 
	 }
	 
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
				 $("#check_"+id).filter(':checkbox').prop('checked', true).checkboxradio("refresh");
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
				  $("#check_"+id).filter(':checkbox').prop('checked', true).checkboxradio("refresh");
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
		$("#checkboxall").filter(':checkbox').prop('checked', true).checkboxradio("refresh");	
		allcheck=true;
	}	
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
		  $("#formcart").submit();
	  }	
}



		
</script>
 
 

 


<style>
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
        <div style=" border-bottom:#ccc 1px solid; border-top:#ccc 1px solid; margin-top:5px; padding-right:12px; ">
         <input  onclick="selectAll(1)" class="tongji" id="checkboxall" type="checkbox"  style="margin:4px 0px 0px 2px; ">
         <p style="color:#8c4; text-align:right; padding:5px 0px; margin:0; margin-bottom:5px;">小车生活，品质保证，放心购买！</p>
        </div>
        
    	<div id="container"> 
      <form name="formcart" id="formcart" method="post" action="ECshopOrder.php">   
     <?
	
	 foreach($dataTable as $Items){
	 ?>
     <div style=" border-bottom: #ccc dashed 1px;  height:110px; margin:2%; padding:1%;  " id="carts_<?=$Items['id']?>" >
         <div style=" width:10%; float:left; height:inherit;  ">
            
            <input name="childs[]" class="tongji" id="check_<?=$Items['gdetailid']?>"  type="checkbox"  style="margin:30px 0px 0px -7px;" onClick="checkIfAll()" value="<?=$Items['gdetailid']?>" <?=$Items['checkstate']=='ON'?'checked':''?>>
           
         </div>
         
         <div style=" width:29%; float:left; height:inherit;" id="picc">
         <img src="<?=UPLOADURL.$Items['dpic']?>" width="85px;" height="100px;"  >
         </div>
         <div  class="cartinfo">
             <p><?=csubstr($Items['gsname'],10,$charset="utf-8")?></p>
             <p><?=csubstr($Items['gdname'],10,$charset="utf-8")?></p>
             <p style="padding-top:5px;">￥<span id="pre_price_<?=$Items['gdetailid']?>"><?=$Items['dprice']?></span></p>
             
             <input  id="sdnum_<?=$Items['gdetailid']?>" type="text"  class="spinnerExample"  name="sdnum[]"  data-role="none" value="<?=$Items['snum']?>" onChange="changenum_input(<?=$Items['gdetailid']?>)"/>
             
                
              
         </div>
         <div style=" float: right;"  id="deleteinfo_<?=$Items['id']?>">
         	<img src="image/delect.png"  style=" float:right; " width="30px" height="35px"  onClick="deletecart(<?=$Items['id']?>)">
         </div>
     </div>    
  	
     <?
	 }
	
	 ?>     
     </form>
     
   		
     
      </div>    
     
      <p style="text-align:center; color:#929292; display:none;" id="over" >上拉显示周边商户</p>  
      <p style="text-align:center; color:#929292; display:none" id="overfall" onClick="javascript:document.getElementsByTagName('BODY')[0].scrollTop=0;">回到顶部</p>  
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
    <li style="border-right:none;" onClick="accountit()"><a href="#" >去结算</a></li>
    
  </ul>
 
</div>


</body>



<?
include_once 'CarwashNav.php';
include_once '../Index/foot.php';
?>