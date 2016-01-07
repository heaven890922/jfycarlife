<?
session_start();
include_once '../Index/head.php';
include_once '../Bin/MySqlHelper.class.php';
include_once '../Bin/Function.php';
$Mysql=new MySqlHelper();
$userid=$_SESSION['coustomid'];
$id=$_REQUEST['id'];
//$id=19;
$sqlprovince="SELECT * FROM sh_store_area WHERE fid=0";
$dataTableProvince=$Mysql->FetchData($sqlprovince);

$sqladdress="SELECT * FROM sh_store_address WHERE id=$id and userid=$userid";
$dataTableAddress=$Mysql->FetchData($sqladdress);
$flag=count($dataTableAddress);

$sqlcity="SELECT * FROM sh_store_area WHERE fid=".$dataTableAddress[0]['provinceid'];
$dataTableCity=$Mysql->FetchData($sqlcity);

$sqlcountry="SELECT * FROM sh_store_area WHERE fid=".$dataTableAddress[0]['cityid'];
$dataTableCountry=$Mysql->FetchData($sqlcountry);
//查询商品信息







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

function UpdateAdress(){
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
	params=params+"&m=UPDATEADDRESS";
	var url="action/addressAction.php";
	$.ajax({  
			type: "post",  
			url: url,  
			dataType: "json",  
			data: params,  
			success: function(msg){
				alert(msg.mess);
				if(msg.code==200){
					location.href="ECshopAddressIndex.php";
				}else{
					location.reload();	
				}
				
				//加载关闭			
				$(".hide-page-loading-msg").click();			
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
      <?
	  	if($flag>0){
	  ?>
      	<form  id="form1" name="form1">
         <label for="text-3">收货人姓名:</label>
         <input data-clear-btn="true" name="name" id="name" value="<?=$dataTableAddress[0]['name']?>" type="text">
		 <label for="tel-2">手机号码:</label>
     	 <input data-clear-btn="true" name="tel" id="tel" value="<?=$dataTableAddress[0]['tel']?>" type="tel">
       
		 <fieldset data-role="controlgroup">
            <legend>收货地址:</legend>
            
            <select name="province" id="province" data-native-menu="true">
                <option value="" >选择省份</option>
               <?
			   		foreach($dataTableProvince as $itemProvince){
			   ?>
               		<option value="<?=$itemProvince['areaid']?>" <?=($itemProvince['areaid']==$dataTableAddress[0]['provinceid']?'selected':'') ?>><?=$itemProvince['areaname']?></option> 
               <?
					}
			   ?>
            </select>
           
            <select name="city" id="city" data-native-menu="true">
                <option value="" >选择市</option>
                 <?
			   		foreach($dataTableCity as $itemCity){
			     ?>
               		<option value="<?=$itemCity['areaid']?>" <?=($itemCity['areaid']==$dataTableAddress[0]['cityid']?'selected':'') ?>><?=$itemCity['areaname']?></option> 
              	 <?
					}
			  	 ?>
            </select>
            
            <select name="country" id="country" data-native-menu="true">
                <option value="" >选择县/区</option>
                 <?
			   		foreach($dataTableCountry as $itemCountry){
			     ?>
               		<option value="<?=$itemCountry['areaid']?>" <?=($itemCountry['areaid']==$dataTableAddress[0]['countyid']?'selected':'') ?>><?=$itemCountry['areaname']?></option> 
              	 <?
					}
			  	 ?>
            </select>
          </fieldset> 
          <label for="textarea-enhanced">详细地址:</label>
   			 <textarea name="detail_address" id="detail_address" data-enhanced="true" class="ui-input-text ui-shadow-inset ui-body-inherit ui-corner-all"><?=$dataTableAddress[0]['detailaddress']?></textarea>
          <input type="hidden"  name="id" id="id" value="<?=$id?>">
       </form>   
         <br/>
     	
         <a href="#" class="ui-btn ui-corner-all" style=" border-color:#88cc44; color:#88cc44; width:76%; margin:auto;  padding: 0.5em 1em;" onClick="UpdateAdress()">修改地址</a>
         <?
			}else{
		 ?>
          <p  style=" margin:25px auto; font-size:18px; color:#F00; text-align:center;">信息错误，请返回重试！</p>
          
         <?
			}
		 ?>
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