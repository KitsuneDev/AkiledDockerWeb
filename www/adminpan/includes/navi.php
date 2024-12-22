<div class="container-scroller">
  <!-- partial:partials/_sidebar.html -->
  <nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
      <a class="sidebar-brand brand-logo text-danger" href="<?= $config['hotelUrl'];?>/adminpan/dash"><?= $config['hotelName'] ?> Panel</a>
      <a class="sidebar-brand brand-logo-mini text-info" href="<?= $config['hotelUrl'];?>/adminpan/dash">HK</a>
    </div>
    <ul class="nav">
      <li class="nav-item profile">
        <div class="profile-desc">
          <div class="profile-pic">
            <div class="count-indicator">
              <img class="img-xs rounded-circle" src="<?php echo $config['lookUrl']; ?><?php echo User::userData('look'); ?>&direction=3&head_direction=3&gesture=none&action=none&size=n&headonly=1" alt="">
              <span class="count bg-success"></span>
            </div>
            <div class="profile-name">
              <p class="mb-0 d-none d-sm-block navbar-profile-name"> <b><?php echo User::userData('username'); ?></b></p>
              <span>Staff Team </span>
            </div>
          </div>
          <a href="#" id="profile-dropdown" data-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></a>
          <div class="dropdown-menu dropdown-menu-right sidebar-dropdown preview-list" aria-labelledby="profile-dropdown">
            <a href="/settings_privacy" class="dropdown-item preview-item">
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
            <a href="/settings_password" class="dropdown-item preview-item">
              <div class="preview-thumbnail">
                <div class="preview-icon bg-dark rounded-circle">
                  <i class="mdi mdi-onepassword  text-info"></i>
                </div>
              </div>
              <div class="preview-item-content">
                <p class="preview-subject ellipsis mb-1 text-small">Change Password</p>
              </div>
            </a>
          </div>
        </div>
      </li>
      <li class="nav-item nav-category">
        <span class="nav-link">Menu</span>
      </li>

      <li class="nav-item menu-items">
        <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/dash">
          <span class="menu-icon">
            <i class="mdi mdi-speedometer"></i>
          </span>
          <span class="menu-title">Dashboard</span>
        </a>
      </li>

      <?php
        if (User::userData('rank') >= '19') {
      ?>

      <li class="nav-item menu-items">
        <a class="nav-link" data-toggle="collapse" href="#ui-basic1" aria-expanded="false" aria-controls="ui-basic">
          <span class="menu-icon">
            <i class="mdi mdi-settings-box"></i>
          </span>
          <span class="menu-title">Hotel Options</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="ui-basic1">
          <ul class="nav flex-column sub-menu">
            
              <li class="nav-item"> <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/settings">Hotel Settings</a></li>
              <li class="nav-item"> <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/happyhour">HappyHour Settings</a></li>
              <li class="nav-item"> <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/permissions">Edit Ranks</a></li>
              <li class="nav-item"> <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/tareas">Tasks</a></li>
              <li class="nav-item"> <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/comandos">Commands</a></li>
              <li class="nav-item"> <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/update">Update</a></li>
              <!--  <li class="nav-item"> <a class="nav-link" href="pages/ui-features/buttons.html">Estadísticas usuarios</a></li> -->
            
          </ul>
        </div>
      </li>

      <?php } ?>

      <?php
        if (User::userData('rank') >= '10') {
      ?>

      <li class="nav-item menu-items">
        <a class="nav-link" data-toggle="collapse" href="#ui-basic2" aria-expanded="false" aria-controls="ui-basic">
          <span class="menu-icon">
            <i class="mdi mdi-settings-box"></i>
          </span>
          <span class="menu-title">Moderation</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="ui-basic2">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/bans">Bans</a></li>
            <li class="nav-item"> <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/chatlogs">Room Monitoring</a></li>
			      <li class="nav-item"> <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/commandlogs">Command Monitoring</a></li>
            <li class="nav-item"> <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/chatlogs_console">Private Messages</a></li>
            <li class="nav-item"> <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/clones">Search Clones</a></li>
            <li class="nav-item"> <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/logsname">Nname Changes</a></li>
          </ul>
        </div>
      </li>

      <?php } ?>

      <?php
        if (User::userData('rank') >= '13') {
      ?>

        <li class="nav-item menu-items">
          <a class="nav-link" data-toggle="collapse" href="#ui-basic3" aria-expanded="false" aria-controls="ui-basic">
            <span class="menu-icon">
              <i class="mdi mdi-account-check"></i>
            </span>
            <span class="menu-title">User Management</span>
            <i class="menu-arrow"></i>
          </a>
          <div class="collapse" id="ui-basic3">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/online">Online Users</a></li>
              <li class="nav-item"> <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/zoekgebruiker">Edit Users</a></li>
              <li class="nav-item"> <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/zoekgerank">Rank Management</a></li>
              <li class="nav-item"> <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/password">Change Password</a></li>

            </ul>
          </div>
        </li>

      <?php } ?>

      <?php
        if (User::userData('rank') >= '12') {
      ?>

        <li class="nav-item menu-items">
          <a class="nav-link" data-toggle="collapse" href="#ui-basic4" aria-expanded="false" aria-controls="ui-basic">
            <span class="menu-icon">
              <i class="mdi mdi-table-large"></i>
            </span>
            <span class="menu-title">Web Utilities</span>
            <i class="menu-arrow"></i>
          </a>
          <div class="collapse" id="ui-basic4">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/news">News</a></li>
              <li class="nav-item"> <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/infosindex">Infos Index</a></li>
              <li class="nav-item"> <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/fansites">Fan Sites</a></li>
              <li class="nav-item"> <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/eventos">Events</a></li>
              <li class="nav-item"> <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/Shopy">Store Mezz</a></li>
            </ul>
          </div>
        </li>

      <?php } ?>

      <?php
        if (User::userData('rank') >= '17') {
      ?>

      <li class="nav-item menu-items">
        <a class="nav-link" data-toggle="collapse" href="#ui-basic5" aria-expanded="false" aria-controls="ui-basic">
          <span class="menu-icon">
            <i class="mdi mdi-auto-fix"></i>
          </span>
          <span class="menu-title">Salas</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="ui-basic5">
          <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/salas">Rooms</a></li>
              <li class="nav-item"> <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/publicroom">Add public room</a></li>
          </ul>
        </div>
      </li>

      <?php } ?>

      <?php
        if (User::userData('rank') >= '13') {
      ?>

      <li class="nav-item menu-items">
        <a class="nav-link" data-toggle="collapse" href="#ui-basic6" aria-expanded="false" aria-controls="ui-basic">
          <span class="menu-icon">
            <i class="mdi mdi-auto-fix"></i>
          </span>
          <span class="menu-title">Badges</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="ui-basic6">
          <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/uploadbadge">Upload Badge to the hotel</a></li>
              <li class="nav-item"> <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/shop">Badge Store (In CMS)</a></li>
          </ul>
        </div>
      </li>

      <?php } ?>

	    <?php
		    if (User::userData('rank') >= '17') {
      ?>

	  <li class="nav-item menu-items">
        <a class="nav-link" data-toggle="collapse" href="#ui-basic7" aria-expanded="false" aria-controls="ui-basic">
          <span class="menu-icon">
            <i class="mdi mdi-shopping"></i>
          </span>
          <span class="menu-title">Store</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="ui-basic7">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/pagescatalog">Shop Pages</a></li>
            <li class="nav-item"> <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/pagemuebles">Edit Furniture (CI)</a></li>
            <li class="nav-item"> <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/roleplayitems">RolePlay Items</a></li>
            <li class="nav-item"> <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/pagefurniture">Edit Furniture</a></li>
          </ul>
        </div>
      </li>

	  <?php } ?>
	  
    <?php
		  if (User::userData('rank') >= '19') {
    ?>

	  <li class="nav-item menu-items">
        <a class="nav-link" data-toggle="collapse" href="#ui-basic8" aria-expanded="false" aria-controls="ui-basic">
          <span class="menu-icon">
            <i class="mdi mdi-lock" style="color: #607d8b;"></i>
          </span>
          <span class="menu-title">Other Functions</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="ui-basic8">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/groups">Edit Groups</a></li>
            <li class="nav-item"> <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/vacantes">Vacancies</a></li>
            <li class="nav-item"> <a class="nav-link" href="<?= $config['hotelUrl'];?>/adminpan/report">Reports</a></li>
          </ul>
        </div>
      </li>

	  <?php } ?>

      <li class="nav-item menu-items">
        <a class="nav-link" href="https://discord.com/invite/fPm78zt4/" Target="_blank">
          <span class="menu-icon">
            <i class="mdi mdi-file-document-box"></i>
          </span>
          <span class="menu-title">Help and Support</span>
        </a>
      </li>
    </ul>
  </nav>