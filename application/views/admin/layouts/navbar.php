<nav class="navbar navbar-static-top" role="navigation">
  <!-- Sidebar toggle button-->
  <a href="" class="sidebar-toggle" data-toggle="offcanvas" role="button">
    <span class="sr-only">Toggle navigation</span>
  </a>

  <div class="navbar-custom-menu">
    <ul class="nav navbar-nav">
      <!-- User Account: style can be found in dropdown.less -->
      <li class="dropdown user user-menu">
        <a href="<?php echo base_url().$this->config->item('admin_softlink').'logout'?>" >
          Sign Out
        </a>
        <ul class="dropdown-menu">
          <!-- User image -->
          <li class="user-header">

            <p>
              Admin
            </p>
          </li>
          <!-- Menu Body -->
          <!-- Menu Footer-->
          <li class="user-footer">
            <div class="pull-left">
            </div>
            <div class="pull-right">
              <a href="<?php echo base_url().$this->config->item('admin_softlink').'logout'?>" class="btn btn-default btn-flat">Sign out</a>
            </div>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>
