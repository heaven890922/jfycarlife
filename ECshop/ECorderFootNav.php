<?
$sqlnum="SELECT sum(snum) AS scarnum FROM sh_store_cart WHERE userid=".$userID;
$datanum=$mysql->FetchData($sqlnum);
$num=$datanum[0][0];
?>




<div id="stickey_footer">
  <ul id="footer_menu">
   
    <li><a href="#">购物车<span id="scarnum" class="num"  style="display: <?=$num<1?'none':'' ?>" ><?=$num?></span></a></li>
    <li><a href="#" onClick="addCart()">加入购物车</a></li>
    <li style="border-right:none;"><a href="#">立即购买</a></li>
    
  </ul>
 
</div>