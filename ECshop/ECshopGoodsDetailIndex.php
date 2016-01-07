<?
session_start();
include_once '../Index/head.php';
include_once '../Bin/MySqlHelper.class.php';
include_once '../Bin/Function.php';
//图片链接，运费等配置信息
include_once 'action/config.php';
$mysql=new MySqlHelper();
$gdid=$_REQUEST['gdid'];

$sqlgoods="SELECT * FROM sh_store_goods WHERE gdid=".$gdid." and state='ON' ";
$datagoods=$mysql->FetchData($sqlgoods);

$sqlgoodsdetail="SELECT * FROM sh_store_goods_detail WHERE gdid=".$gdid." and state='ON' ";
$datagoodsdetail=$mysql->FetchData($sqlgoodsdetail);


$arrpic = explode("|",$datagoods[0]['gdpic']);
//print_r($datagoods);


$userID=$_SESSION['coustomid'];

if($userID==''){
	$userID='0';
	}

?>
 <link rel="stylesheet" href="../Carwash/ResponsiveSlides/responsiveslides.css">
 <link rel="stylesheet" href="../Carwash/ResponsiveSlides/demo/demo.css">
  <link rel="stylesheet" href="jiaoben897/css/zzsc.css">
 <link href="spinner/css/jquery.spinner.css" rel="stylesheet" type="text/css">

        
       
<script src="../Myjquery/JQUERY-CONFIRM/js/pretty.js"></script>
        
        
        <!-- Add the minified version of files from the /dist/ folder. -->
        <!-- jquery-confirm files -->
      
<script type="text/javascript" src="../Myjquery/JQUERY-CONFIRM/js/jquery-confirm.js"></script>
 
 
 
 
 <script src="../Carwash/ResponsiveSlides/responsiveslides.min.js"></script>
<script language="javascript">
//全局变量，用于保存所选商品剩余库存量
var kucun=0;
//全局变量，用于储存所选商品型号id
var cid=0;

$(document).ready(function(){
	$('.goods_detail').click(function(){
		
		$(".goods_detail").removeClass("goods_detail_on");
		//$('.pshow_class_item').toggleClass("pshow_class_item_on");
		$(this).addClass("goods_detail_on");
		
		});
	
	});


$(function () {

	// Slideshow 1
	$("#slider1").responsiveSlides({
	  maxwidth: 800,
	  speed: 800
	});
});

//AJAX获取选择商品的库存量
function getGoodClass(id,dname){
	$('#shownum').html("（库存正在获取...）");
	 cid = id;
	 kucun=0;
	$.ajax({  
      type:'post',  
      url:'action/getGoodDetailAction.php?id='+id,  
      data:{},  
      cache:false,  
      dataType:'json',  
      success:function(data){  
          if( data.msg ==200 ) //服务器返回false，就将validatePassword2的值改为pwd2Error，这是异步，需要考虑返回时间  
           {  
		   var num = data.num;
		  kucun = num;
		 var coupon=data.price;
		 var rmb=coupon/2;
		 $("#coupon").html(coupon);
		 $("#rmb").html(rmb);
		 
		  $('#sdnum').val(1);
		  $('#shownum').html("（库存"+num+"）");
		  $("#gdname").val(dname);
                return;  
            }  
      },  
 error:function(){alert('获取数据异常');}  
});

}


//添加商品到购物车
function addCart(){
	
	if(cid==0){
		
		alert('请选择商品型号');
	    return false;
		
		}
	//获取选择的商品数量
	var sdnum = $('#sdnum').val();
	
	if(parseFloat(sdnum)>parseFloat(kucun)){
		
		alert('抱歉，库存不足');
		return false;
		
		}
	
	var url ="action/carAction.php";	
	var params=$("#form1").serialize();
	params=params+"&gdetailid="+cid +"&sdnum="+sdnum+"&m=ADD";
	//alert(params) ;return;
	
      $.ajax({  
		type: "post",  
		url: url,  
		dataType: "json",  
		data: params,  
		success: function(msg){ 
		//alert(msg.code);
			  if( msg.code ==200 ) 
			   {  
			   var scarnum=msg.mess;
			   
			   $('#scarnum').show();
			   $('#scarnum').html(""+scarnum+"");
			   
			  //alert(scarnum);
			 
				  alert('成功加入购物车');
			 
			   } 
			   else{
				   
				   var mess = msg.mess;
				   if(msg.code==130&&ctype){
						location.href='mycart.php';
				   }else{   
				    	alert(mess);
				   }
				   
			   }
      	}  
  
	}); 
		
	
}

//马上购买
function buyNow(){
	if(cid==0){
		
		alert('请选择商品型号');
	    return false;
		
		}
	//获取选择的商品数量
	var sdnum = $('#sdnum').val();
	
	if(parseFloat(sdnum)>parseFloat(kucun)){
		
		alert('抱歉，库存不足');
		return false;
		
	}
	
	location.href="ECshopOrderNow.php?gdetailid="+cid+"&sdnum="+sdnum;
	
}




	

	
</script>
<style>
#img {height: auto; width: auto\9; width:100%; display:block; margin:0 auto;}

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
.detailtitle{
	padding-left:5px;
	color:#999;
	max-width:2100px;
	margin:0px auto 10px;
	}
	
.pcontent{
	padding-left:5px;
	 color:#000;
	}
		
.ptext{
	margin:10px;
	border-bottom: #CCC solid 1px;
	padding-bottom:8px;
	}
.textdescribe{
	padding:5px 5px 15px 5px;
	
	}
.textdescribe img{
	height: auto; width: auto\9; width:100%; display:block; margin:0 auto;
	
	}
.model img{
	height: auto; width: auto\9; width:100%; display:block; margin:0 auto;
	}
.goods_detail{
	border:#F9F9F9 solid 1px;
	}	
	
.goods_detail_on{
	border:#8c4 solid 1px;
	}		
		
</style>

<script type="text/javascript" src="spinner/js/jquery.spinner.js"></script>


</head>
<body><!--从这里开始自定义编写-->
<div data-role="page">
  <div data-role="header">
<? 
include_once '../Navigation/head_navigation.php';
?>
<h1><div style="height:30px; font-size:18px;">商品详情</div></h1>
 
  </div>
  	<div  id="picc">
    
      <div id="wrapper">    
            <ul class="rslides" id="slider1">
            <?
            for($i=0;$i<count($arrpic);$i++){
			?>
              <li><img src="<?=PUPLOADSURL.$arrpic[$i]?>" style="width:100%; height=150px;" alt=""></li>
           <?
			}
		   ?>
            </ul>
		</div>
    </div>
    <div class="jfycontent" style="margin-bottom:50px;"  >
    	<div class="ptext">
            <p class="detailtitle">商品名称：</p>
            <p class="pcontent" ><?=$datagoods[0]['gdname']?></p>
        </div>
        <div class="ptext">
            <p class="detailtitle">商品价格：</p>
            <p class="pcontent" style="color:#e60012" ><span id="coupon"><?=$datagoodsdetail[0]['dprice']?></span>小车券，相当于<span id="rmb"><?=$datagoodsdetail[0]['dprice']/2?></span>人民币</p>
        </div>
        
        <div class="ptext">
            <p class="detailtitle">商品型号：</p>
            <div style=" height:auto; ">
            <?
			
            foreach($datagoodsdetail as $itemGoodsDetail ){
			?>
                <div class="goods_detail" style=" float:left; margin:8px;  width:57px;  overflow:hidden;" onClick="getGoodClass(<?=$itemGoodsDetail['detailid']?>,'<?=$itemGoodsDetail['dname']?>')">
                    <div style=" width:57px; height:57px; " class="model">
                        <img src="<?=UPLOADURL.$itemGoodsDetail['dpic']?>" >
                        
                    </div>
                   <p style="text-align:center; margin:0px;"> <?=$itemGoodsDetail['dname']?></p>
                </div> 
             <?
			 }
			
			 ?>  
                <br style="clear:both;" />
                <br  />
            </div>
        </div>
         
        <div class="ptext">
           <p class="detailtitle"> 商品数量：</p><input  id="sdnum" type="text"  class="spinnerExample" data-role="none" value="1"/> <div id="shownum" style=" margin-left:115px; margin-top:-30px;" >库存（<?=$datagoodsdetail[0]['dnum']?>）</div>
          <br style="clear:both;" />  
        </div>
       <form name="form1" id="form1" >
       
        <input type="hidden" name="gsname"  value="<?=$datagoods[0]['gdname']?>"/>
        <input  type="hidden" name="gdname" value="" id="gdname" />
        
        </form>
        
        <div class="ptext">
            <p class="detailtitle">商品简介：</p>
            <div class="textdescribe"><?=$datagoods[0]['gddescribe']?></div>
        </div>
        
          
     
          
     
       
         
    </div><!--ebd content-->
   </div>
 <script type="text/javascript">
    $('.spinnerExample').spinner({});
</script>
<?
include_once 'ECfootNav.php';
?>


<!--page end-->

</body>

<?
include_once 'CarwashNav.php';
include_once '../Index/foot.php';
?>