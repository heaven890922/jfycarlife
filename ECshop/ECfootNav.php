<?
$sqlnum="SELECT sum(snum) AS scarnum FROM sh_store_cart WHERE state='ON' AND userid=".$userID;
$datanum=$mysql->FetchData($sqlnum);
$num=$datanum[0][0];
?>




<div id="stickey_footer">
  <ul id="footer_menu">
   
    <li onclick="location.href='ECshopCart.php'"><a href="#">购物车<span id="scarnum" class="num"  style="display: <?=$num<1?'none':'' ?>" ><?=$num?></span></a></li>
    <li onClick="addCart()"><a href="#" >加入购物车</a></li>
    <li style="border-right:none;" onclick="buyNow()"><a href="#">立即购买</a></li>
    
  </ul>
 
</div>