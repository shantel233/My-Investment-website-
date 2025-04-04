<?php session_start();
if(isset($_SESSION['start_time']) && (time() - $_SESSION['start_time'] > 21600)){
    session_destroy();
    session_start();
}
else {
    $_SESSION['start_time'] = time();
    session_regenerate_id(true);
}
if(isset($_SESSION['start_time'])){
    if(time() - $_SESSION['start_time'] > 180) {
        session_regenerate_id(true);
    }
}
if(!isset($_SESSION['user_mail'])){
    header("Location: ../");
    exit();
}
require "../DB/db.php";
$stmt = mysqli_stmt_init($conn);
$unseen = "unseen";
$all = "all";
$sql = "SELECT * FROM notifications WHERE (to_who=? OR to_who=?) AND status=?";
if(!mysqli_stmt_prepare($stmt , $sql)){
    echo "SQLNotPreapred";
    exit();
}
else{
    mysqli_stmt_bind_param($stmt , "sss" , $_SESSION['user_mail'],$all,$unseen);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $notify_num = mysqli_stmt_num_rows($stmt);
}

$sql = "SELECT * FROM users WHERE mail=?";
if(!mysqli_stmt_prepare($stmt , $sql)){
    echo "SQLNotPreapred";
    exit();
}
else{
    mysqli_stmt_bind_param($stmt , "s" , $_SESSION['user_mail']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if($row = mysqli_fetch_assoc($res)){
        $user_name = $row['name'];
    }
}

$sql = "SELECT * FROM notifications WHERE to_who=? OR to_who=? ORDER BY id DESC LIMIT 15";
if(!mysqli_stmt_prepare($stmt , $sql)){
    echo "SQLNotPreapred";
    exit();
}
else{
    mysqli_stmt_bind_param($stmt , "ss" , $all,$_SESSION['user_mail']);
    mysqli_stmt_execute($stmt);
    $res3 = mysqli_stmt_get_result($stmt);
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl" class="rtl">
    <head>
        <title><?php require "page-titles.php"; ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="fontiran.com:license" content="NE29X">
        <link rel="shortcut icon" href="../assets/images/favicon.png">

        <!-- BEGIN CSS -->
        <link href="../assets/plugins/bootstrap/bootstrap5/css/bootstrap.rtl.min.css" rel="stylesheet">
        <link href="../assets/plugins/metisMenu/dist/metisMenu.min.css" rel="stylesheet">
        <link href="../assets/plugins/simple-line-icons/css/simple-line-icons.min.css" rel="stylesheet">
        <link href="../assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="../assets/plugins/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">
        <link href="../assets/plugins/switchery/dist/switchery.min.css" rel="stylesheet">
        <link href="../assets/plugins/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
        <link href="../assets/plugins/paper-ripple/dist/paper-ripple.min.css" rel="stylesheet">
        <link href="../assets/plugins/iCheck/skins/square/_all.css" rel="stylesheet">
        <link href="../assets/css/style.css" rel="stylesheet">
        <link href="../assets/css/colors.css" rel="stylesheet">
        <link href="../assets/css/wenk.css" rel="stylesheet">
        <link href="../assets/plugins/data-table/DataTables-1.10.16/css/jquery.dataTables.css" rel="stylesheet">
        <link href="../assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet">
        <!-- END CSS -->
        <script src="../assets/plugins/jquery/dist/jquery-3.1.0.js"></script>

        
    </head>
    <body class="active-ripple theme-cyan fix-header sidebar-extra">
        <!-- BEGIN LOEADING -->
        <div id="loader">
            <div class="spinner"></div>
        </div><!-- /loader -->
        <!-- END LOEADING -->
        
        <!-- BEGIN HEADER -->
        <div class="navbar navbar-fixed-top" id="main-navbar">            
            <div class="header-right">
                <a href="/" class="logo-con">
                    <img src="../assets/images/logo.png" class="img-responsive center-block">
                </a>
            </div><!-- /.header-right -->
            <div class="header-left">
                <div class="top-bar">                        
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="#" class="btn" id="toggle-sidebar" data-wenk="بستن | باز کردن منو" data-wenk-pos="bottom">
                                <span class="menu"></span>
                            </a>
                        </li>
                        <!-- <li>                                
                            <a href="#" class="btn open" id="toggle-sidebar-top">
                                <i class="icon-user-following"></i>
                            </a>
                        </li> -->
                        
                        <li>                                
                            <a href="#" class="btn" id="toggle-dark-mode" data-wenk="حالت شب | روز" data-wenk-pos="bottom">
                                <i class="icon-bulb"></i>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-left">
                        <li class="dropdown">
                            <a href="#" class="btn" id="toggle-fullscreen" data-wenk="تمام صفحه" data-wenk-pos="bottom">
                                <i class="icon-size-fullscreen"></i>
                            </a>
                        </li>
                        <li class="dropdown dropdown-announces" data-wenk="اعلانات" data-wenk-pos="bottom">
                            <a href="#" class="dropdown-toggle btn notf-btn" data-bs-toggle="dropdown">
                                <i class="icon-bell"></i>
                                <span class="badge badge-warning mg1">
                                    <?php echo $notify_num; ?>
                                </span>
                            </a>
                            <ul class="dropdown-menu has-scrollbar">
                                <li class="dropdown-header clearfix">
                                    <span class="float-start">

                                        <span>
                                            شما <span class="mg2"><?php echo $notify_num; ?></span> اعلان تازه دارید.
                                        </span>
                                    </span>
                                </li>
                                <li class="dropdown-body" style="max-height: 300px;overflow-y: scroll;">
                                    <ul class="dropdown-menu-list" >

                                        <?php while($row = mysqli_fetch_assoc($res3)){ ?>
                                        <li class="clearfix">
                                            <a href="?notification=show&id=<?php echo $row['id']; ?>">
                                                <p class="clearfix">
                                                    <strong class="float-start"><?php echo $row['from_who']; ?></strong> 
                                                </p>
                                                <p><?php echo $row['short_msg']; ?></p>
                                            </a>
                                        </li>
                                        <?php } ?>
                                        
                                        
                                    </ul>
                                </li>
                                <li class="dropdown-footer clearfix">
                                    <a href="?notification=list">
                                        <i class="icon-list fa-flip-horizontal"></i>
                                        مشاهده همه اعلانات  
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="dropdown dropdown-user">
                            <a href="#" class="dropdown-toggle dropdown-hover" data-bs-toggle="dropdown">
                                <img src="../assets/images/user/48.png" alt="عکس پرفایل" class="img-circle img-responsive">
                                <span><?php echo $user_name; ?></span>
                                <i class="icon-arrow-down"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="?profile">
                                        <i class="icon-note"></i>
                                        ویرایش پروفایل
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="../logout">
                                        <i class="icon-power"></i>
                                        خروج
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul><!-- /.navbar-left -->
                </div><!-- /.top-bar -->
            </div><!-- /.header-left -->
        </div><!-- /.navbar -->
        <!-- END HEADER -->
              
        <!-- BEGIN WRAPPER -->
        <div id="wrapper">

            <!-- BEGIN SIDEBAR -->
            <div id="sidebar">
                <!-- <div class="sidebar-top">

                    <div class="user-box">
                        <div class="user-details text-center p-t-10 p-b-20">
                            <h4>حمید آفرینش فر</h4>
                        </div>
                    </div>
                </div> -->
                <div class="side-menu-container">
                    <ul class="metismenu" id="side-menu">
                        <li>
                            <a href="/" class="<?php if(empty($_GET)){echo "current";} ?>">
                                <i class="icon-home"></i>
                                <span>پیشخوان</span>
                            </a>
                        </li>
                        <li>
                            <a href="?inbox=list" class="<?php if(isset($_GET['inbox']) && $_GET['inbox'] == "list"){echo "current";} ?>">
                                <i class="icon-layers"></i>
                                <span>صندوق های سرمایه گذاری</span>
                            </a>
                        </li>
                        <li>
                            <a href="?cardex=list" class="<?php if(isset($_GET['cardex'])){echo "current";} ?>">
                                <i class="icon-chart"></i>
                                <span>کاردکس</span>
                            </a>
                        </li>
                        <li>
                            <a href="?request=list" class="<?php if(isset($_GET['request'])){echo "current";} ?>">
                                <i class="fa fa-list-alt"></i>
                                <span>درخواست های خرید / فروش</span>
                            </a>
                        </li>
                        <!-- <li>
                            <a href="#" class="">
                                <i class="icon-share-alt"></i>
                                <span>درخواست فروش</span>
                            </a>
                        </li> -->
                        <li>
                            <a href="?wallet=list" class="<?php if(isset($_GET['wallet'])){echo "current";} ?>">
                                <i class="icon-wallet"></i>
                                <span>کیف پول ها (Wallet)</span>
                            </a>
                        </li>
                        <li>
                            <a href="?profile" class="<?php if(isset($_GET['profile'])){echo "current";} ?>">
                                <i class="icon-user"></i>
                                <span>پروفایل کاربری</span>
                            </a>
                        </li>
                        <li>
                            <a href="?notification=list" class="<?php if(isset($_GET['notification'])){echo "current";} ?>">
                                <i class="icon-bell"></i>
                                <span>اعلانات</span>
                            </a>
                        </li>
                        <li>
                            <a href="?support" class="<?php if(isset($_GET['support'])){echo "current";} ?>">
                                <i class="icon-support"></i>
                                <span>پشتیبانی</span>
                            </a>
                        </li>
                        <li>
                            <a href="../logout" class="">
                                <i class="icon-power"></i>
                                <span>خروج</span>
                            </a>
                        </li>
                    </ul><!-- /#side-menu -->
                </div><!-- /.side-menu-container -->
            </div><!-- /#sidebar -->
            <!-- END SIDEBAR -->            
            <!-- BEGIN PAGE CONTENT -->


            <?php
                if(empty($_GET)){
                    require "dash.php";
                }
                elseif(isset($_GET['support'])){
                    require "support.php";
                }
                elseif(isset($_GET['profile'])){
                    require "profile.php";
                }
                elseif(isset($_GET['inbox']) && $_GET['inbox'] == "list"){
                    require "inbox-list.php";
                }
                elseif(isset($_GET['inbox']) && $_GET['inbox'] == "inbox1"){
                    require "inbox-inbox1.php";
                }
                elseif(isset($_GET['inbox']) && $_GET['inbox'] == "inbox2"){
                    require "inbox-inbox2.php";
                }
                elseif(isset($_GET['inbox']) && $_GET['inbox'] == "inbox3"){
                    require "inbox-inbox3.php";
                }
                elseif(isset($_GET['notification']) && $_GET['notification'] == "list"){
                    require "notification-list.php";
                }
                elseif(isset($_GET['notification']) && $_GET['notification'] == "show"){
                    require "notification-show.php";
                }
                elseif(isset($_GET['wallet']) && $_GET['wallet'] == "list"){
                    require "wallet-list.php";
                }
                elseif(isset($_GET['wallet']) && $_GET['wallet'] == "add"){
                    require "wallet-add.php";
                }
                elseif(isset($_GET['request']) && $_GET['request'] == "list"){
                    require "request-list.php";
                }
                elseif(isset($_GET['request']) && $_GET['request'] == "buy"){
                    require "request-buy.php";
                }
                elseif(isset($_GET['request']) && $_GET['request'] == "sell"){
                    require "request-sell.php";
                }
                elseif(isset($_GET['cardex']) && $_GET['cardex'] == "list"){
                    require "cardex-list.php";
                }
                elseif(isset($_GET['cardex']) && $_GET['cardex'] == "inbox1"){
                    require "cardex-inbox1.php";
                }
                elseif(isset($_GET['cardex']) && $_GET['cardex'] == "inbox2"){
                    require "cardex-inbox2.php";
                }
                elseif(isset($_GET['cardex']) && $_GET['cardex'] == "inbox3"){
                    require "cardex-inbox3.php";
                }
            ?>

        </div>
        
              
        
        <!-- BEGIN JS -->
        <script src="../assets/plugins/bootstrap/bootstrap5/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/plugins/metisMenu/dist/metisMenu.min.js"></script>
        <script src="../assets/plugins/paper-ripple/dist/PaperRipple.min.js"></script>
        <script src="../assets/plugins/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
        <script src="../assets/plugins/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="../assets/plugins/screenfull/dist/screenfull.min.js"></script>
        <script src="../assets/plugins/iCheck/icheck.min.js"></script>
        <script src="../assets/plugins/select2/dist/js/select2.full.min.js"></script>
        <script src="../assets/plugins/select2/dist/js/i18n/fa.js"></script>
        <script src="../assets/js/pages/select2.js"></script>
        <script src="../assets/js/core.js"></script>
        
        <script>
            document.querySelector(".notf-btn").addEventListener("click" , function(){
                $.ajax({
                    url: "controllers/see-notification.php",
                    data: {
                        condition: "true"
                    },
                    type: "post",
                    success: function(res){
                        if(res == "success"){
                            document.addEventListener("click" , function(event){
                                const withinBoundaries = event.composedPath().includes(document.querySelector(".notf-btn"));
                                if(withinBoundaries){
    
                                }
                                else{
                                    document.querySelector(".mg1").innerText = "0";
                                    document.querySelector(".mg2").innerText = "0";
                                }
                            });
                        }
                    }
                });
            });
        </script>
            
    </body>
</html>