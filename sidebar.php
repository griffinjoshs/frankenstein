<?php
$menuItems = array(
    'portfolioTracker' => array(
        'menuItemName' => 'Portfolio Tracker',
        'menuItemIcon' => 'fa-solid fa-money-bill-trend-up',
        'menuItemUrl' => 'portfolio_tracker_summary.php',
        'menuItemPermission' => 1
    ),
    'videoPlayer' => array(
        'menuItemName' => 'Video Player',
        'menuItemIcon' => 'fa-solid fa-circle-play',
        'menuItemUrl' => 'video_player_summary.php',
        'menuItemPermission' => 1
    ),
    'spotSaver' => array(
        'menuItemName' => 'Spot Saver',
        'menuItemIcon' => 'fa-solid fa-map-pin',
        'menuItemUrl' => 'spot_saver_summary.php',
        'menuItemPermission' => 1
    )
);
?>
<nav class="sidebar" id="sidebar">
    
    <ul class="nav sidebar-nav">
        <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center">
            <a class="sidebar-brand brand-logo" href="<?=BASE_URL?>"><img src="<?=BASE_URL?>/assets/images/frankenstein-sm.png" alt="logo" /></a>
            <a class="sidebar-brand brand-logo-mini" href="<?=BASE_URL?>"><img src="<?=BASE_URL?>/assets/images/frankenstein-sm.png" alt="logo" /></a>
        </div>
        <li class="nav-item profile">
        <div class="profile-desc">
            <div class="profile-pic">
                <div class="count-indicator">
                    <img class="img-xs rounded-circle " src="" alt="">
                    <span class="count bg-success"></span>
                </div>
                <div class="profile-name">
                    <h5 class="mb-0 font-weight-normal"><?=$user->firstname?> <?=$user->lastname?></h5>
                </div>
            </div>
            <a href="#" id="profile-dropdown" data-bs-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></a>
            <div class="dropdown-menu dropdown-menu-right sidebar-dropdown preview-list" aria-labelledby="profile-dropdown">
            <a href="#" class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                <div class="preview-icon bg-dark rounded-circle">
                    <i class="mdi mdi-settings text-primary"></i>
                </div>
                </div>
                <div class="preview-item-content">
                <p class="preview-subject ellipsis mb-1 text-small">Account settings</p>
                </div>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                <div class="preview-icon bg-dark rounded-circle">
                    <i class="mdi mdi-onepassword  text-info"></i>
                </div>
                </div>
                <div class="preview-item-content">
                <p class="preview-subject ellipsis mb-1 text-small">Change Password</p>
                </div>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                <div class="preview-icon bg-dark rounded-circle">
                    <i class="mdi mdi-calendar-today text-success"></i>
                </div>
                </div>
                <div class="preview-item-content">
                <p class="preview-subject ellipsis mb-1 text-small">To-do list</p>
                </div>
            </a>
            </div>
        </div>
        </li>
        <li class="nav-item nav-category">
         <span class="nav-link">Navigation</span>
        </li>
        <?php foreach($menuItems as $mi) { ?>
        <li class="nav-item menu-items">
            <a class="nav-link" href="<?=BASE_URL . '/' . $mi['menuItemUrl']?>">
                <span class="menu-icon">
                <i class="<?=$mi['menuItemIcon']?>"></i>
                </span>
                <span class="menu-title"><?=$mi['menuItemName']?></span>
            </a>
        </li>
        <?php } ?>  
        <!-- <li class="nav-item menu-items">
            <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
                <span class="menu-icon">
                <i class="mdi mdi-security"></i>
                </span>
                <span class="menu-title">User Pages</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="auth">
                <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="pages/samples/blank-page.html"> Blank Page </a></li>
                <li class="nav-item"> <a class="nav-link" href="pages/samples/error-404.html"> 404 </a></li>
                <li class="nav-item"> <a class="nav-link" href="pages/samples/error-500.html"> 500 </a></li>
                <li class="nav-item"> <a class="nav-link" href="pages/samples/login.html"> Login </a></li>
                <li class="nav-item"> <a class="nav-link" href="pages/samples/register.html"> Register </a></li>
                </ul>
            </div>
        </li> -->
    </ul>
    <div class="sidebar-logout-wrapper nav-item">
        <button class="logout-button nav-link">
            <span class="menu-icon">
                <i class="fa fa-sign-out" aria-hidden="true"></i>
            </span>
            <span class="menu-title"> Log Out</span>
            </button>
    </div>
</nav>