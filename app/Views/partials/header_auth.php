<?php
$session = session();
$name = esc($session->get('fullname') ?? $session->get('email'));
$role = esc($session->get('role') ?? '');
?>

<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- tap on top starts-->
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->

    <!-- page-wrapper Start-->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
      <!-- Page Header Start-->
      <div class="page-header">
        <div class="header-wrapper row m-0">
          

          <div class="nav-right col-xxl-7 col-xl-6 col-md-7 col-8 pull-right right-header p-0 ms-auto">
            <ul class="nav-menus">
              
              <li class="fullscreen-body">                      
                 <span>
                  <svg id="maximize-screen">
                    <use href="../assets/svg/icon-sprite.svg#full-screen"></use>
                  </svg></span>
                </li>
              <li>
                <div class="mode">
                  <svg>
                    <use href="../assets/svg/icon-sprite.svg#moon"></use>
                  </svg>
                </div>
              </li>
             
            
              <li class="profile-nav onhover-dropdown pe-0 py-0">
                <div class="d-flex profile-media"><img class="b-r-10" src="../assets/images/dashboard/profile.png" alt="">
                  <div class="flex-grow-1"><span>  <?= $name ?></span>
                    <p class="mb-0">User <i class="middle fa-solid fa-angle-down"></i></p>
                  </div>
                </div>
                <ul class="profile-dropdown onhover-show-div">
                  <li><a href="<?= site_url('settings') ?>"><i data-feather="settings"></i><span>Settings</span></a></li>
                   <li><a class="dropdown-item" href="<?= site_url('profile') ?>">Profile</a></li>                   
                  <li><a class="dropdown-item btn btn-danger text-white" href="<?= site_url('logout') ?>">Logout</span></a></li>
                </ul>
              </li>
            </ul>
          </div>
          <script class="result-template" type="text/x-handlebars-template">
            <div class="ProfileCard u-cf">                        
            <div class="ProfileCard-avatar"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay m-0"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path><polygon points="12 15 17 21 7 21 12 15"></polygon></svg></div>
            <div class="ProfileCard-details">
            <div class="ProfileCard-realName">{{name}}</div>
            </div>
            </div>
          </script>
          <script class="empty-template" type="text/x-handlebars-template"><div class="EmptyMessage">is down</div></script>
        </div>
      </div>
      <!-- Page Header Ends                              -->
      <!-- Page Body Start-->
      <div class="page-body-wrapper">
        <!-- Page Sidebar Start-->
        <div class="sidebar-wrapper" data-sidebar-layout="stroke-svg">
          <div>
            <div class="logo-wrapper"><a href="index.html"><img class="img-fluid for-light" src="../assets/images/logo/" alt=""><img class="img-fluid for-dark" src="../assets/images/logo/" alt=""></a>
              <div class="back-btn"><i class="fa-solid fa-angle-left"></i></div>
              <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
            </div>
            <div class="logo-icon-wrapper"><a href="index.html"><img class="img-fluid" src="../assets/images/logo/" alt=""></a></div>
            <nav class="sidebar-main">
              <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
              <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar">
                  <li class="back-btn"><a href="index.html"><img class="img-fluid" src="../assets/images/logo/" alt=""></a>
                    <div class="mobile-back text-end"><span>Back</span><i class="fa-solid fa-angle-right ps-2" aria-hidden="true"></i></div>
                  </li>
                  <li class="pin-title sidebar-main-title">
                    <div> 
                      <h6>Pinned</h6>
                    </div>
                  </li>
                  <li class="sidebar-main-title">
                    <div>
                      <h6 class="lan-1">General</h6>
                    </div>
                  </li>
                  <li class="sidebar-list"><i class="fa-solid fa-thumbtack"></i>
                    <a class="sidebar-link sidebar-title" href="#">
                      <svg class="stroke-icon">
                        <use href="../assets/svg/icon-sprite.svg#stroke-home"></use>
                      </svg>
                      <svg class="fill-icon">
                        <use href="<?= base_url('dashboard') ?>"></use>
                      </svg><span class="lan-3">Dashboard          </span></a>
                    <ul class="sidebar-submenu">
                     
                    <?php $cat = $user['category'] ?? 'non_academic'; ?>
                      <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('profile/overview') ?>">Profile</a>
                        <ul class="nav flex-column ms-3">
                          <li><a href="<?= site_url('profile/personal') ?>">Personal</a></li>
                          <li><a href="<?= site_url('profile/employment') ?>">Employment</a></li>
                          <li><a href="<?= site_url('profile/professional') ?>">Professional</a></li>
                        </ul>
                      </li>
                  </div>
              
              <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
            </nav>
          </div>
        </div>
        <!-- Page Sidebar Ends-->
     <div class="page-body">