<?
session_start();
include_once '../Index/head.php';
include_once '../Bin/MySqlHelper.class.php';
include_once '../Bin/Function.php';
//图片链接，运费等配置信息
include_once 'action/config.php';
$Mysql=new MySqlHelper();
$userid=$_SESSION['coustomid'];
$sqlprovince="SELECT * FROM sh_store_area WHERE fid=0";
$dataTableProvince=$Mysql->FetchData($sqlprovince);
//查询商品信息
$gdetailid=$_REQUEST['gdetailid'];
$sdnum=$_REQUEST['sdnum'];
if(($gdetailid!='')&&($sdnum!='')){
	$flag=true;
}






?>
 <link rel="stylesheet" href="ResponsiveSlides/responsiveslides.css">
 <link rel="stylesheet" href="ResponsiveSlides/demo/demo.css">
 

        
       
 <script src="../Myjquery/JQUERY-CONFIRM/js/pretty.js"></script>
        
        
<!-- Add the minified version of files from the /dist/ folder. -->
<!-- jquery-confirm files -->
<link rel="stylesheet" type="text/css" href="../Myjquery/JQUERY-CONFIRM/css/jquery-confirm.css" />
<script type="text/javascript" src="../Myjquery/JQUERY-CONFIRM/js/jquery-confirm.js"></script>

<script>
//联动city信息
function getarea(areaID,type){
	//加载启动
	$(".show-page-loading-msg").click();	
	var params="areaID="+areaID+"&m=GETAREA";
	var url="action/areaAction.php";
	
	$.ajax({  
			type: "post",  
			url: url,  
			dataType: "json",  
			data: params,  
			success: function(msg){
				//alert(msg.areaid);
				if(msg==null){	
				}else{
					if(type==2){
						$("#country").empty();
						var strarea="<option value=\"\" selected>选择县/区</option>";
					}else{
						$("#city").empty();
						var strarea="<option value=\"\" selected>选择市</option>";	
					}
					
					$.each(msg, function(index, array) {
						 strarea=strarea+ "<option value=\""+array['areaid']+"\">"+array['areaname']+"</option>";
					//alert(strarea);	
						});
					//alert(strarea);	
					if(type==2){	
						$("#country").append(strarea);	
					}else{
						$("#city").append(strarea);
					}
				}
				//加载关闭				
				$(".hide-page-loading-msg").click();			
			}
						
	});
	
}

$(function(){
	$("#province").change(function(e) {
		
        getarea($(this).children('option:selected').val(),1);
    });
	$("#city").change(function(e) {
     	getarea($(this).children('option:selected').val(),2);   
    });		
})

function addAdress(){
	var name=$("#name").val();
	if(name==''){
		alert("请输入姓名！");
		return;	
	}
	
	var tel=$("#tel").val();
	if(tel.length!=11){
		alert("请输入正确的电话号码！");
		return;	
	}
	
	var province=$("#province").val();
	if(province==''){
		alert("请选择省份！");
		return;	
	}
	
	var city=$("#city").val();
	if(city==''){
		alert("请选择市！");
		return;	
	}
	
	var country=$("#country").val();
	if(country==''){
		alert("请选择县/区！");
		return;	
	}
	
	var detail_address=$("#detail_address").val();
	if(detail_address==''){
		alert("请输入详细地址！");
		return;	
	}
	$(".show-page-loading-msg").click();
	var params= $("#form1").serialize();
	params=params+"&m=LISTADDADDRESS";
	var url="action/addressAction.php";
	$.ajax({  
			type: "post",  
			url: url,  
			dataType: "json",  
			data: params,  
			success: function(msg){
				$(".hide-page-loading-msg").click();
				//alert(msg.mess);
				if(msg.code==200){
					location.href="ECshopOrder<? if($flag){?>Now<? }?>.php?id="+msg.mess<? if($flag){?>+"&gdetailid=<?=$gdetailid?>&sdnum=<?=$sdnum?>" <? }?>;
				}else{
					alert("添加失败，请返回重试！");	
				}
				
				//加载关闭			
							
			}
						
	});
	
	
		
}




</script>

<style>


#container{
	padding:10px 10px 25px 10px;
}

</style>  



</head>
<body><!--从这里开始自定义编写-->
<div data-role="page">
  <div data-role="header">
<? 
include_once '../Navigation/head_navigation.php';

?>
<h1>
  <div style="height:30px; font-size:18px;">添加收货人信息</div></h1>
 
  </div>

    <div class="jfycontent">
      <div id="container">
      	<form  id="form1" name="form1">
         <label for="text-3">收货人姓名:</label>
         <input data-clear-btn="true" name="name" id="name" value="" type="text">
		 <label for="tel-2">手机号码:</label>
     	 <input data-clear-btn="true" name="tel" id="tel" value="" type="tel">
       
		 <fieldset data-role="controlgroup">
            <legend>收货地址:</legend>
            
            <select name="province" id="province" data-native-menu="true">
                <option value="" selected>选择省份</option>
               <?
			   		foreach($dataTableProvince as $itemProvince){
			   ?>
               		<option value="<?=$itemProvince['areaid']?>"><?=$itemProvince['areaname']?></option> 
               <?
					}
			   ?>
            </select>
           
            <select name="city" id="city" data-native-menu="true">
                <option value="" selected>选择市</option>
                
            </select>
            
            <select name="country" id="country" data-native-menu="true">
                <option value="" selected>选择县/区</option>
               
            </select>
          </fieldset> 
          <label for="textarea-enhanced">详细地址:</label>
   			 <textarea name="detail_address" id="detail_address" data-enhanced="true" class="ui-input-text ui-shadow-inset ui-body-inherit ui-corner-all"></textarea>
          
       </form>   
         <br/>
     	
         <a href="#" class="ui-btn ui-corner-all" style=" border-color:#88cc44; color:#88cc44; width:76%; margin:auto;  padding: 0.5em 1em;" onClick="addAdress()">保存并使用</a> 
      </div>    
    </div><!--ebd content-->
</div>
<?
include_once 'ECshopLoading.php';
?>

<!--page end-->

</body>

<?
include_once 'CarwashNav.php';
include_once '../Index/foot.php';
?>