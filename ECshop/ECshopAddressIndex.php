<?
session_start();
include_once '../Index/head.php';
include_once '../Bin/MySqlHelper.class.php';
include_once '../Bin/Function.php';
include_once 'action/config.php';
$Mysql=new MySqlHelper();
$userid=$_SESSION['coustomid'];

//查询商品信息
$sql="SELECT a.*,ar.areaname as coutryname,ar1.areaname as cityname,ar2.areaname as provincename FROM sh_store_address a JOIN sh_store_area ar  ON a.countyid=ar.areaid  JOIN sh_store_area ar1 ON a.cityid=ar1.areaid JOIN sh_store_area ar2 ON a.provinceid=ar2.areaid WHERE   a.userid=$userid AND delstate='N' order by a.state desc";


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
$(document).ready(function(){
	
  $('.address_defult').click(function(){
	  if(confirm("是否设为默认地址？")){
		  $(".show-page-loading-msg").click();
		  $(".address_defult").removeClass("defult");
		  $(this).addClass("defult");
		  var id=$(this).attr("id").substring(8, 30);
		  var params="id="+id+"&m=SETDEFULT";
		  var url="action/addressAction.php";
		   $.ajax({  
			  type: "post",  
			  url: url,  
			  dataType: "json",  
			  data: params,  
			  success: function(msg){ 
			  //alert(msg.mess);
				  if(msg.code==200){
					  $(".hide-page-loading-msg").click();	  
				  }else{
					  location.reload();	
				  }  
			  }	  
		
			}); 
		}
	});
});

//删除地址
function deleteAddress(id){
	if(confirm("是否确定删除该地址？")){
	  $(".show-page-loading-msg").click();
	  var params="id="+id+"&m=DELETE";
	  var url="action/addressAction.php";
	  $.ajax({  
		  type: "post",  
		  url: url,  
		  dataType: "json",  
		  data: params,  
		  success: function(msg){ 
		  //alert(msg.mess);
			  $(".hide-page-loading-msg").click();	
			  if(msg.code!=200){
				  alert(msg.mess);		
			  } 
			  location.reload();	
		  }	  
		
	  }); 
	}
}

	
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
		
	}
	
	th, td {padding:8px 10px 8px; text-align:left;  }
	
	th {  background:#e8eaeb; text-align:center; font-weight:200;}
	
	td {border-top:1px solid #e0e0e0;}
	
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
	padding:2px 5px 2px 7px;
	background:#0B1726; 
	 height:30px;
	 color:#fff;
    -moz-border-radius: 4px;
    -webkit-border-radius: 4px;
    border-radius:4px;
	text-shadow: 0px 0px 0px #F3F3F3;
	
}


.defult{
	background:#e03737;	
}

#container{
	padding:0px 10px 25px 10px;
}

</style>  



</head>
<body><!--从这里开始自定义编写-->
<div data-role="page">
  <div data-role="header">
<? 
include_once '../Navigation/head_navigation.php';

?>
<h1><div style="height:30px; font-size:18px;">收货地址管理</div></h1>
 
  </div>

    <div class="jfycontent">
      <div id="container"> 
		  <?
              foreach($dataTable as $Items){
          ?>
          <table >
   
        <tr class="th-groups">
            
            <th colspan="1"><?=$Items['name']?>
			</th>
            <th colspan="1">
            <?=substr($Items['tel'],0,3).'****'.substr($Items['tel'],7)?>
            </th>
            <th colspan="1">
            <? if($Items['state']=='ON'){?><span class="address_defult defult" id="address_<?=$Items['id']?>">默认地址</span> <? }else{?><span class="address_defult" id="address_<?=$Items['id']?>">默认地址</span>  <? }?>
			</th>
            
        </tr>
        <tr>
            <td colspan="3" ><?=$Items['provincename'].$Items['cityname'].$Items['coutryname'].$Items['detailaddress']?></td>
            
            
        </tr>

        <tr>
            
            <td colspan="3" style=" text-align:right" >
            <span onClick="location.href='ECshopAddressUpdate.php?id=<?=$Items['id']?>';">编辑</span>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<span onClick="deleteAddress(<?=$Items['id']?>)">删除</span>&nbsp;&nbsp;&nbsp;
            </td>
           
            
        </tr>
        
    
</table>
          <?
              }
          ?>
          <br/>
     		</br>
         <a href="#" onClick="location.href='ECshopAddressAdd.php'" class="ui-btn ui-corner-all" style=" border-color:#88cc44; color:#88cc44; width:76%; margin:auto;  padding: 0.5em 1em;">添加新地址</a> 
      </div>    
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