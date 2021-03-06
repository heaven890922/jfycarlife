<?
session_start();
include_once '../Index/head.php';
include_once '../Bin/MySqlHelper.class.php';
include_once '../Bin/Function.php';
//图片链接，运费等配置信息
include_once 'action/config.php';
$userID=$_SESSION['coustomid'];
$id=$_REQUEST['id'];
$gdetailid=$_REQUEST['gdetailid'];
$sdnum=$_REQUEST['sdnum'];

if($id!=''){
$addresswhere=" id=$id ";	
}else{
$addresswhere=" state ='ON' ";	
}


$Mysql=new MySqlHelper();


//查询商品信息
$sql="SELECT gd.detailid,g.gdname,gd.dname,gd.dpic,gd.dprice,gd.dnum FROM sh_store_goods_detail gd JOIN sh_store_goods g ON gd.gdid=g.gdid  WHERE gd.detailid=$gdetailid LIMIT 1";
//默认地址信息
$sqladdress="SELECT a.*,ar.areaname as coutryname,ar1.areaname as cityname,ar2.areaname as provincename FROM sh_store_address a JOIN sh_store_area ar  ON a.countyid=ar.areaid  JOIN sh_store_area ar1 ON a.cityid=ar1.areaid JOIN sh_store_area ar2 ON a.provinceid=ar2.areaid WHERE ".$addresswhere." AND a.userid=$userID AND delstate='N' limit 1";

//$sqllimit="select g.gdname,g.gddescribe,g.bigpic,d.dprice,SUM(salenum) AS totalsalenum from sh_store_goods  g LEFT  JOIN sh_store_goods_detail d ON g.gdid=d.gdid   WHERE d.state='ON'   GROUP BY g.gdid  ORDER BY dprice LIMIT";
$dataTable=$Mysql->FetchData($sql);
print_r($dataTable);
$dataTableAddress=$Mysql->FetchData($sqladdress);
$rowaddress=count($dataTableAddress);

$summoney=$dataTable[0]['dprice']*$sdnum+EXPRESSPRICE;

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



function accountit(){
	
	var gdetailid=$("#gdetailid").val();
	if(gdetailid==''){
		alert("没有提交商品，请返回重试！");
		return;
	}
	
	var sdnum=$("#sdnum").val();
	if(sdnum==''){
		sdnum=1;
	}
	
	
	var addressId=$("#addressId").val();
	if(addressId==''){
		alert("请选择收货地址！");
		return;	
	}
	
	var remarks=$("#remarks").val();
	
	if(remarks.length>50){
		alert("备注的字数不能超过50个字！");	
		return;
	}
	
	$(".show-page-loading-msg").click();
	var params="gdetailid="+gdetailid+"&addressId="+addressId+"&sdnum="+sdnum+"&remarks="+remarks+"&m=BUYNOW"
	//alert(params);
	var url="action/orderAction.php";
	 $.ajax({  
		type: "post",  
		url: url,  
		dataType: "json",  
		data: params,  
		success: function(msg){
			$(".hide-page-loading-msg").click();
			//alert(msg.code); 
			if(msg.code==200){
				location.href="ECshopOrderPay.php?orderNum="+msg.ordernum;
			}else{
				location.reload();	
			}
			
			
      	}  
  
	}); 
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
	
	th, td {padding:8px 10px 8px; text-align:left;  }
	
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

.address_defult{
	padding:2px 2px 2px 7px; width:52px; height:30px;
    background:#e03737; 
	color:#fff;
    -moz-border-radius: 4px;
    -webkit-border-radius: 4px;
    border-radius:4px;
	text-shadow: 0px 0px 0px #F3F3F3;
	
}		

	
</style>




</head>
<body style="background:#dadada;"><!--从这里开始自定义编写-->
<div data-role="page">
  <div data-role="header">
<? 
include_once '../Navigation/head_navigation.php';

?>
<h1><div style="height:30px; font-size:18px;">填写订单</div></h1>
 
  </div>
 	
    <div class="jfycontent" style="padding-bottom:45px;">
    <!--收件地址展示和选择开始-->
        <?
			if($rowaddress>0){
		?>
          
           <table width="100%" onClick="location.href='ECshopAddressList.php?id=<?=$dataTableAddress[0]['id']?>&gdetailid=<?=$gdetailid?>&sdnum=<?=$sdnum?>'">
               <tr>
                   <td style="padding:8px 4px;">
                        <img src="image/people.png" width="20px"   />
                   </td>
                   <td style="color:#000;">
                  <?=$dataTableAddress[0]['name']?>
                   </td>
                   
                   <td style="padding:8px 4px;" >
                        <img src="image/tel.png" width="20px"  />
                   </td>
                   <td style="color:#000;">
                        <?=substr($dataTableAddress[0]['tel'],0,3).'****'.substr($dataTableAddress[0]['tel'],7)?>
                   </td>
                   <td rowspan="2" >
                   <img src="image/toinfo.png" width="20px;"   />
                   </td>
               </tr>
               <tr>
                   <td colspan="4" style="font-size:14px;" >
                       <? if($dataTableAddress[0]['state']=='ON'){ ?><span class="address_defult">默认 </span> <? }?> <?=$dataTableAddress[0]['provincename'].$dataTableAddress[0]['cityname'].$dataTableAddress[0]['coutryname'].$dataTableAddress[0]['detailaddress']?>
                   </td>
                   
               </tr>
           </table> 
           
        <?
			}else{
		?>  
          <table width="100%"  onClick="location.href='ECshopAddressListAdd.php'" >
          	 <tr>
               <td style="text-align:center;">
                  <span class="address_defult" style="color:#000;">还未添加收货地址，去添加</span>
               </td>
               <td>
                <img src="image/toinfo.png" width="20px;"   />
               </td>
             </tr>
          </table>
     	<?
			}
		?>
        
    <!--收件地址展示和选择结束--> 
    <!--所选商品展示开始-->   
    	<div id="container"> 
           
     	   <div id="order_detail" style="margin:10px; font-size:14px;">
           <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; border:0; background-color:#fff1cc;" >
        
               <tr style=" margin:8px 0px;  " >
                   <td rowspan="2" style=" padding:6px 0px; width:70px; border-bottom:#ccc 1px solid;">
                    	<img src="<?=UPLOADURL.$dataTable[0]['dpic']?>" width="60px"  />
                   </td>
                   <td colspan="2"style="padding-top:7px;">
                  		<?=csubstr($dataTable[0]['gdname']."-".$dataTable[0]['dname'],25,$charset="utf-8")?>
                   </td>
               </tr>
               <tr style="border-bottom:#ccc 1px solid;" >
                   <td style="line-height:14px; border-bottom:#ccc 1px solid; ">
                   <?="×".$sdnum?>
                   </td>
                    
                   <td rowspan="1" style="border-bottom:#ccc 1px solid; color:#F00; width:70px; text-align:right;">
                   <?=$dataTable[0]['dprice']?>
                   </td>
               </tr>
          
            <tr>
                <td colspan="2">
                	&nbsp;&nbsp;&nbsp;配送费
                </td>
                <td style="color:#F00; text-align:right;">
                	<?=EXPRESSPRICE?>
                </td>
            </tr>
             <tr>
                <td colspan="2">
                	&nbsp;&nbsp;&nbsp;订单合计
                </td>
                <td style="color:#F00; width:85px; text-align:right;" >
                	<?=$summoney?>小车券
                </td>
            </tr>
            
           </table>
           
           </div>
           </br>
           <input type="hidden" name="gdetailid" id="gdetailid" value="<?=$dataTable[0]['detailid']?>">
           <input type="hidden" name="sdnum" id="sdnum" value="<?=$sdnum?>">
            <input type="hidden" name="addressId" id="addressId" value="<?=$dataTableAddress[0]['id']?>">
            <div style="padding:10px;">
            <label for="remarks">备注:</label>
        	<textarea style="box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.2) inset;border-radius: 0.3125em;" name="remarks" id="remarks" data-enhanced="true" class="ui-input-text ui-shadow-inset ui-body-inherit ui-corner-all"></textarea>
     		</div>
   		</div> 
    

      
    </div><!--ebd content-->
    <!--jquery-mobile加载事件控制开始-->
<?
include_once 'ECshopLoading.php';
?>
    <!--jquery-mobile加载事件控制结束-->
    
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
   
    <li class="tongji" style=" width:66.24%;">实付款:<span style="font-size:16px; color:#F00;"><?=$summoney?>小车券</span></li>
    
    <li style="border-right:none;" onClick="accountit()"><a href="#" >提交订单</a></li>
    
  </ul>
 
</div>


</body>



<?
include_once 'CarwashNav.php';
include_once '../Index/foot.php';
?>