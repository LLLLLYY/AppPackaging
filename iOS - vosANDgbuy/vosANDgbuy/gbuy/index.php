<!-- <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> -->
<!-- <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> -->
<!DOCTYPE html>
<?php
session_start();
include('./lib/xmlrpc.inc');
include('./conn.php');

$client = new xmlrpc_client($conn_common);

$msg = new xmlrpcmsg("login");

$msg ->
addParam(new xmlrpcval($db,"string"));
$msg->addParam(new xmlrpcval($user,"string"));
$msg->addParam(new xmlrpcval($pass,"string"));

$resp = $client->send($msg);
$uid = $resp->value()->scalarval();

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="icon" type="image/x-icon" href="<? echo $img_logo_fpath; ?>" alt="<? echo $ttxt_en; ?>" />
  <link rel="shortcut icon" type="image/x-icon" href="<? echo $img_logo_fpath; ?>" alt="<? echo $ttxt_en; ?>" />
  <link rel="mask-icon" type="" href="<? echo $img_logo_fpath; ?>" alt="<? echo $ttxt_en; ?>" color="#111" />
  <title><? echo $ttxt_cn . " ::: " . $ttxt_en; ?></title> 
  
  <link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css">
  <script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.2.min.js"></script>
  <script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

  <link rel="stylesheet" type="text/css" href="./css/style.css" />
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css"> -->
  <link rel="stylesheet" type="text/css" href="./css/normalize.min.css" />
 
  <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
  <!-- <script src="https://use.fontawesome.com/f47f4563cb.js"></script> -->
  <script src="./lib/fontawesome_f47f4563cb.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css">

  <script type="text/javascript" language="javascript" charset="utf-8" src="./lib/nav.js"></script>
  <script type="text/javascript" language="javascript" charset="utf-8" src="./lib/core.js"></script>


</head>

<body translate="no" id="homeTop">

<div class="w3-center">
  <img src="<? echo $img_logo_fpath; ?>" alt="<? echo $ttxt_en; ?>" >
</div>
<div class="w3-container">
  <div class="w3-center">
    <? echo $ttxt_cn ?>
  </div>
</div>
<br />
    

<!-- http://www.jianshu.com/p/985d26b40199 -->
  <!-- 阿里高清方案 -->  
<script>!function(e){function t(a){if(i[a])return i[a].exports;var n=i[a]={exports:{},id:a,loaded:!1};return e[a].call(n.exports,n,n.exports,t),n.loaded=!0,n.exports}var i={};return t.m=e,t.c=i,t.p="",t(0)}([function(e,t){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=window;t["default"]=i.flex=function(e,t){var a=e||100,n=t||1,r=i.document,o=navigator.userAgent,d=o.match(/Android[\S\s]+AppleWebkit\/(\d{3})/i),l=o.match(/U3\/((\d+|\.){5,})/i),c=l&&parseInt(l[1].split(".").join(""),10)>=80,p=navigator.appVersion.match(/(iphone|ipad|ipod)/gi),s=i.devicePixelRatio||1;p||d&&d[1]>534||c||(s=1);var u=1/s,m=r.querySelector('meta[name="viewport"]');m||(m=r.createElement("meta"),m.setAttribute("name","viewport"),r.head.appendChild(m)),m.setAttribute("content","width=device-width,user-scalable=no,initial-scale="+u+",maximum-scale="+u+",minimum-scale="+u),r.documentElement.style.fontSize=a/2*s*n+"px"},e.exports=t["default"]}]);
    flex(100, 1);</script>


<!-- top menu -->
<ul class="topnav w3-green" id="topMenu">
  <li><a class="topnavActive" href="#home">首页</a></li>
  <li><a href="#impressum">公司信息</a></li>
  <!-- <li><a href="#contact" >联系方式</a></li>
  <li><a href="#about">关于我们</a></li> -->
  <li class="user">
    <i class="fa fa-user "></i>
    <? if (isset($_SESSION["user_name"])){
    echo $_SESSION["user_name"];
    echo " (" . $_SESSION["user_email"] . ")";
    } else {
      echo "用户未登陆";
    }
    ?>   
  </li>
  <li class="icon">
    <a href="javascript:void(0);" style="font-size:15px;" onclick="topNavMobile()">☰</a>
  </li>
</ul>


<script>
function topNavMobile() {
    var x = document.getElementById("topMenu");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}
</script>
<!-- end top menu -->


<div class="box">
  <div class="navRoot w3-green">
    <!-- href="javascript:void(0)"  -->
    <a href="#homeTop" class="tablinks navLink" onclick="openTab(event, 'productTab')" id="defaultOpen">
      <i class="fa fa-list navLinkIco fa-lg " aria-hidden="true"></i>
      <span class="navLinkText">产品列表</span>
    </a>
    <a href="#homeTop" class="tablinks navLink" onclick="openTab(event, 'cartTab'); refresh_cart()">
      <i class="fa fa-shopping-cart navLinkIco fa-lg" aria-hidden="true"></i>
      <span class="navLinkText">购物车</span>
    </a>
    <a href="#homeTop" class="tablinks navLink" onclick="openTab(event, 'orderTab'); refresh_order();">
      <!-- <i class="fa fa-sort-numeric-asc navLinkIco" aria-hidden="true"></i> -->
      <i class="fa fa-sort-numeric-desc navLinkIco fa-lg" aria-hidden="true"></i>
      <span class="navLinkText">订单</span>
    </a>
    <a href="#homeTop" class="tablinks navLink" onclick="openTab(event, 'postTab'); refresh_post();">
      <i class="fa fa-address-book navLinkIco fa-lg" aria-hidden="true"></i>
      <span class="navLinkText">地址簿</span>
    </a>
    
    <?php if (isset($_SESSION["user_name"])) { ?>
    <a href="#" class="navLink" id="logout">
      <i class="fa fa-sign-out navLinkIco fa-lg" aria-hidden="true"></i>
      <span class="navLinkText" >退出</span>
    </a>
    <?php } else { ?>
    <a href="#" class="navLink" onclick="document.getElementById('login-pop').style.display='block'" id="navLogin">
      <i class="fa fa-sign-in navLinkIco fa-lg" aria-hidden="true"></i>
      <span class="navLinkText" >登陆</span>
    </a>
    <?php } ?>
  </div>
  <!-- end navRoot -->
</div>
<!-- End of Box -->




<!-- login -->
<!-- <button onclick="document.getElementById('login-pop').style.display='block'" style="width:auto;">Login</button>
<span name ="user_info" id ="user_info"></span> -->

<br>

<div id="login-pop" class="modal">
  
  <form class="modal-content animate" action="index.php">
  <!-- <form class="modal-content animate" > -->
    <div class="imgcontainer">
      <!-- <span onclick="document.getElementById('login-pop').style.display='none'" class="close" title="Close Modal">&times;</span> -->
      <img src="<? echo $img_logo_fpath; ?>" alt="<? echo $ttxt_en; ?>">
    </div>

    <div class="login-container">
      <label><b>Email</b></label>
      <!-- <input type="text" id="email" placeholder="Enter Username" name="uname" required> -->
      <input class="loginInput" type="text" id="email" placeholder="Enter Email" name="email" required>

      <label><b>Password</b></label>
      <!-- <input type="password" id="pwd" placeholder="Enter Password" name="psw" required> -->
      <input class="loginInput" type="password" id="pwd" placeholder="Enter Password" name="pwd" required>
        
      <!-- <button type="submit" id = "login">Login</button> -->
      <button type="button" id = "login" class="loginButton">登陆</button>

      <div  class="w3-container w3-red" style="text-align: center; ">
        <p id = "login-error" />
      </div>  
      <!-- <input type="button" id = "login0" value="登 录" /> -->
      <!-- <input type="checkbox"  checked="checked"> Remember me -->
    </div>

    <div class="login-container" style="background-color:#f1f1f1">
      <button type="button" onclick="document.getElementById('login-pop').style.display='none'" class="cancelbtn loginButton">取消</button>
      <!-- <span class="psw">Forgot <a href="#">password?</a></span> -->
      <!-- <span id = "login-error" /> -->
      
    </div>
  </form>
</div>


<script>
// Get the modal
var modal = document.getElementById('login-pop');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>
<!-- end of login -->


<div id="dataView" class="w3-container w3-margin-top w3-margin-bottom">
  <!-- <div class="w3-center" >
    <h5>请注意，每周一北京时间18:00截止当周订货。请务必在18:00前提交购物车。18:00之后提交将移至下周处理。</h5>
    <br> -->
    <div id="productTab" class="tabcontent" >
<div class="w3-center  w3-margin-bottom">
  <span class="w3-large w3-border-green w3-border-bottom ">产品列表</span>
</div>
      <div id ="product_list">
        
        <div class="w3-row-padding w3-center w3-margin-top ">
          <form  id = "search_by" >
            <input type="radio" name="radioProduct" value="ar_no" checked>货号
            <input type="radio" name="radioProduct" value="ar_name">品名
          </form>
          
          <input class="searchInput " id = "product_search_term" type="text" onkeydown="if (event.keyCode == 13) document.getElementById('product_search').click()">
          <button  type="button" onclick="window.location.href('javascript:void(0)')" id = "product_search" style="width:auto;">查询</button>
          <button  type="button" onclick="product_search('ar_no','')" style="width:auto;">全部</button>
        </div>
        <div class="w3-row-padding w3-center w3-margin-bottom">
          <div class=" w3-right ">
            <span class="w3-text w3-padding  w3-hide-small">加入购物车</span>
            <a href="javascript:void(0)" id = "add_cart"  onclick="openTab(event, 'cartTab');"><span class="w3-xlarge">
            <img src="./img/Add_to_Cart-512.png" alt="add to cart" height="50" ></span></a>
          </div>
        </div>
        <div class="w3-responsive w3-card-4">
          <table class="w3-table w3-striped w3-bordered" >
            <thead>
              <tr class="w3-theme">
                <th>id</th>
                <th>货号</th>
                <th>品名</th>
                <th>目录价</th>
                <th>库存</th>
                <th>数量</th>
              </tr>
            </thead>
            <tbody name ="product_table" id ="product_table">
            </tbody>
          </table>
        </div>
        <div id ="p_page_line" class="w3-center w3-margin-top"></div>
      </div>
      <!-- end of product_list -->
    </div>
    <!-- end of productTab -->
    <div id="cartTab" class="tabcontent">
      <div id ="cart_list">
        <div class="w3-container">
          <div class="w3-center  w3-margin-bottom">
          <span class="w3-large w3-border-green w3-border-bottom ">购物车列表</span>
          </div>
          <div class="w3-container">
            <!-- <div style="width: 100%; height: 20px; margin: 10px 0px 0px 0px; ">
              <div class="w3-center"> -->
                <div style="position:relative;bottom:10px;z-index:1;" class="w3-left">
                  <!-- <span style = "float:left; height: 20px;"> -->
                  <a id ="c_page_line">页</a>
                <!-- </div>
                <div style="position:relative;bottom:1px;z-index:1;" class="w3-center"> -->
                  <a>选择地址:</a>
                  <a  class="ui-widget ">
                  <select id="combobox1"></select>
                </a>
              </div>
              <!-- </span> -->
              <!-- <span style = "float:right; height: 20px;"> -->
              <div style="position:relative;bottom:10px;z-index:1;" class="w3-right">
                <a id = "q2" href="#dialog-address" name="modal"></a>
                <!-- <a href = "javascript:void(0)" id = "update_cart" >刷新购物车</a> -->
                <!-- <a href = "javascript:void(0)" id = "submit_cart" >提交购物车</a> -->
              </div>

<div class=" w3-right ">
  <a href="javascript:void(0)" id = "submit_cart" >提交购物车</a>
</div>

<div class=" w3-right w3-margin-right">
  <a href="javascript:void(0)" id = "update_cart"  onclick="refresh_cart();">刷新购物车</a>
</div>



              <!-- </span> -->
              
            <!-- </div>
          </div> -->
        </div>
        <!-- <br style="clear: left;" /> -->
        <div class="w3-container">
          <div class="w3-responsive w3-card-4">
            <table class="w3-table w3-striped w3-bordered" >
              <thead>
                <tr class="w3-theme">
                  <th>id</th>
                  <th>物品</th>
                  <th>目录价</th>
                  <th>折扣</th>
                  <th>数量</th>
                  <th>价格</th>
                  <th>状态</th>
                  <th>操作</th>
                  <th>送货地址</th>
                </tr>
              </thead>
            <tbody name ="cart_table" id ="cart_table"></tbody>
          </table>
        </div>
      </div>
      <span style = "float:right" class="w3-margin-top">
        <a href = "javascript:void(0)" id ="o_sum">购物车总金额为：</a>
        <br />
      </span>
    </div>
  </div>
  <!-- cart_list -->
</div>
<!-- end cartTab -->



<div id="orderTab" class="tabcontent">
<div class="w3-center  w3-margin-bottom">
  <span class="w3-large w3-border-green w3-border-bottom ">我的订单</span>
</div>
  <div id ="order_list">
    <div id ="o_page_line"></div>
    <table id ="order_table" class="display" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th>id</th>
          <th>物品</th>
          <th>目录价</th>
          <th>折扣</th>
          <th>数量</th>
          <th>价格</th>
          <th>状态</th>
          <th>订货日期</th>
        </tr>
      </thead>
      <tfoot>
      <tr>
        <th>id</th>
        <th>物品</th>
        <th>目录价</th>
        <th>折扣</th>
        <th>数量</th>
        <th>价格</th>
        <th>状态</th>
        <th>订货日期</th>
      </tr>
      </tfoot>
    <tbody></tbody>
  </table>
</div>
</div>
<!-- end order tab -->


<div id="postTab" class="tabcontent">
<div class="w3-center  w3-margin-bottom">
  <span class="w3-large w3-border-green w3-border-bottom ">地址簿</span>
</div>
<div id ="post_list">
  <div class="w3-container">
    <!-- <div style="width: 100%; height: 20px; margin: 10px 0px 0px 0px; "> -->
    <div style="position:relative;bottom:1px;z-index:1;" class="w3-left">
      <div id ="d_page_line" >页</div>
    </div>
    <div style="position:relative;bottom:10px;z-index:1;" class="w3-tooltip1 w3-right">
      <span class="w3-text w3-padding  w3-hide-small">新增收件人</span>
      <a id = "q1" href="#dialog-address" name="modal"><span class=" w3-btn-floating w3-green">
      <i class="fa fa-user-plus"></i></span></a>
    </div>
    <!-- </div> -->
    <!-- <br style="clear: left;" /> -->
  </div>
  <div class="w3-responsive w3-card-4">
    <table class="w3-table w3-striped w3-bordered" style="width: 100%">
      <thead class="w3-theme">
        <tr>
          <th>id</th>
          <th>收件人姓名</th>
          <th>国家</th>
          <th>城市</th>
          <th>电话</th>
          <th>收件人地址</th>
          <th>邮编</th>
          <th>操作</th>
        </tr>
      </thead>
    <tbody name ="post_table" id ="post_table"></tbody>
  </table>
</div>

</div>
</div>
<!-- end post tab -->
</div>
<!-- end dataView -->



<!-- 添加地址 -->
<div id="dialog-address" class="modal">
  <form class="modal-content animate" >
    <!-- <form class="modal-content animate" > -->
    <div class="w3-center">
      地址添加
    </div>
    <div class="login-container">
      <label><b>收件人姓名</b></label>
      <input  type="text" id="delivery_name" placeholder="必填"  required>
      <label><b>收件人地址</b></label>
      <input  type="text" name="street" id="street" placeholder="必填"  required>
      <input type="text" name="street2" id="street2" placeholder="地址2" >
      <label><b>城市</b></label>
      <input  type="text" name="city" id="city" placeholder="必填"  required>
      <label><b>邮编</b></label>
      <input  type="text" name="postcode" id="postcode" placeholder="必填"  required>
      <label><b>电话</b></label>
      <input  type="text" name="phone" id="phone" placeholder="必填"  required>
      <label><b>邮件地址</b></label>
      <input  type="text" name="deli_email" id="deli_email" placeholder="mymail@mail.com 必填"  required>
      <label><b>国家</b></label>
      <select id="country">
        <option value=""></option>
      </select>
      <input  type="hidden" name="country_id" id="country_id"  required>
      <br>
      <button type="button" name="link_address" class="loginButton" onclick="add_address(); document.getElementById('dialog-address').style.display='none'">添加</button>
    </div>
    <div class="login-container" style="background-color:#f1f1f1">
      <button type="button" onclick="document.getElementById('dialog-address').style.display='none'" class="cancelbtn loginButton">取消</button>
      
    </div>
  </form>
</div>
<!-- end dialog-address -->

<script>
// Get the modal
var modal_dialog_address = document.getElementById('dialog-address');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal_dialog_address) {
        modal_dialog_address.style.display = "none";
    }
}
// document.getElementById("postTab").style.display="block";
</script>

<hr class="hr2">
<hr class="hr1">
<!-- impressum -->
<div class="w3-container  w3-margin-top w3-margin-bottom" id="impressum">
  <!-- <div class="w3-row">
    <div class="w3-col m5"> -->
      <div class="w3-padding-8"><span class="w3-xlarge w3-border-green w3-bottombar">公司信息</span></div>
      <h3><i class="fa fa-map-marker w3-text-green w3-xlarge" style="width:25px"></i> 地址</h3>
      <p><? echo $cname_full_cn; ?></p>
      <p><? echo $cstreet_cn; ?></p>
      <p><? echo $czip; ?> <? echo $ccity_cn; ?></p>
      <p><? echo $ccountry_zh; ?></p>
      <br>
      <p><i class="fa fa-phone w3-text-green w3-xlarge fa-fw" style="width:35px"></i>电话： <? echo $tel_zh; ?></p>
      <p><i class="fa fa-fax w3-text-green w3-xlarge fa-fw" style="width:35px"></i>传真：<? echo $fax_zh; ?></p>
      <p><i class="fa fa-envelope-o w3-text-green w3-xlarge fa-fw" style="width:35px"></i>电邮： <a href="mailto:<? echo $mail_zh; ?>"><? echo $mail_zh; ?></a></p>
      <p><i class="fa fa-globe w3-text-green w3-xlarge fa-fw" style="width:35px"></i>网址： <a href="<?echo $weblink; ?>" target="new"><? echo $weblink; ?></a></p>
    <!-- </div>
  </div> -->
</div>
<!-- end impressum -->

<hr class="hr1">
<!-- marke -->
<div class="w3-center  w3-margin-top w3-padding-16  w3-hover-opacity-off ">
  <div class="w3-xlarge w3-section marke">
    <a href = "http://www.rimowa.com/de-de/" target = "new" onclick = ""> <img src="./img/logo/logo-rimowa.png"></a>
<a href = "http://www.lamy.com/" target = "new" onclick = ""><img src="./img/logo/logo-lamy.png"></a>
<a href = "http://www.thermos.com" target = "new" onclick = ""> <img src="./img/logo/logo-thermos.png"></a>
<a href = "https://www.dyson.de" target = "new" onclick = ""> <img src="./img/logo/logo-dyson.png"></a>
<a href = "http://www.alfi.de" target = "new" onclick = ""> <img src="./img/logo/logo-alfi.png"></a>
<a href = "http://www.fissler.de/" target = "new" onclick = ""><img src="./img/logo/logo-fissler.png"></a>
<a href = "http://www.zwilling.com/" target = "new" onclick = ""><img src="./img/logo/logo-zwilling.png"></a>
<a href = "http://www.silit.de" target = "new" onclick = ""><img src="./img/logo/logo-silit.png"></a>
<a href = "http://www.lecreuset.de/" target = "new" onclick = ""> <img src="./img/logo/logo-le-creuset.png"></a>
<a href = "http://www.maxi-cosi.com/" target = "new" onclick = ""> <img src="./img/logo/logo-maxicosi.png"></a>
<a href = "http://www.montblanc.com" target = "new" onclick = ""> <img src="./img/logo/logo-montblanc.png"></a>
<a href = "http://www.quinny.com" target = "new" onclick = ""><img src="./img/logo/logo-quinny.png"></a>
<a href = "http://www.recaro.com/" target = "new" onclick = ""><img src="./img/logo/logo-recaro.png"></a>
<a href = "http://www.storchenmuehle.de" target = "new" onclick = ""><img src="./img/logo/logo-storchenmuehle.png"></a>
<a href = "http://www.swarovski.com" target = "new" onclick = ""><img src="./img/logo/logo-swarovski.png"></a>
<a href = "http://www.victorinox.com" target = "new" onclick = ""><img src="./img/logo/logo-victorinox.png"></a>
<a href = "http://www.wmf.de" target = "new" onclick = ""><img src="./img/logo/logo-wmf.png"></a>
<a href = "http://www.wuesthof.com" target = "new" onclick = ""><img src="./img/logo/logo-wuesthof.png"></a>
<a href = "http://www.zielonka-shop.com" target = "new" onclick = ""><img src="./img/logo/logo-zielonka.png"></a>
  </div> 
</div>
<!-- end marke -->


<!-- Footer -->
<footer class="w3-center w3-black w3-padding-64 w3-opacity w3-hover-opacity-off">
  <a href="#homeTop" id="goToTop" class="w3-btn w3-padding w3-light-grey w3-hover-grey"><i class="fa fa-arrow-up w3-margin-right"></i>返回顶部</a>
  <!-- <div class="w3-xlarge w3-section">
    <i class="fa fa-facebook-official w3-hover-text-indigo"></i>
    <i class="fa fa-instagram w3-hover-text-purple"></i>
    <i class="fa fa-snapchat w3-hover-text-yellow"></i>
    <i class="fa fa-pinterest-p w3-hover-text-red"></i>
    <i class="fa fa-twitter w3-hover-text-light-blue"></i>
    <i class="fa fa-linkedin w3-hover-text-indigo"></i>
    <i class="fa fa-weixin w3-hover-text-indigo"></i>
    <i class="fa fa-qq w3-hover-text-indigo"></i>
  </div> -->
  <br /><br /><br />
  <p>Powered by <a href="mailto:<? echo $mail_copyright_cn; ?>" title="<? echo $copyright_cn; ?>"  class="w3-hover-text-green"><? echo $copyright_cn; ?></a></p>
</footer>
<!-- end of footer -->


<!-- default show all product list -->
<script type="text/javascript">
  product_search('ar_no','');
  document.getElementById("productTab").style.display="block";
  // document.getElementById("defaultOpen").click();
  // document.getElementById("goToTop").click();//todo
</script>


</body>

</html>
