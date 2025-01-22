  <!-- Navbar -->
  <nav id="top-menu" class="main-header navbar navbar-expand navbar-white navbar-light" style="z-index:100000;">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item" onclick="sideBarCollapseTrigger()">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
        <script>
          window.sideBarCollapseTrigger = function(){
            //console.log("Hi");
            setTimeout(() => {
              var exists = document.querySelector('body.sidebar-collapse');
              //console.log(exists);
              if(exists)
                WebRequest2('GET', '/ui-settings/adminlte/sidebar_collapse/sidebar-collapse');
              else
                WebRequest2('GET', '/ui-settings-clear/adminlte/sidebar_collapse');
            }, 2000);
            
          }
          
        </script> 
      <!--
      <li class="nav-item d-none d-sm-inline-block">
        <a href="../index3.html" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>-->
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <!--<form class="form-inline">-->
            <div class="input-group input-group-sm">
              <input id="search-text-input" class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button id="search-text-submit" class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button id="search-text-cancel" class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          <!--</form>-->
        </div>
      </li>
      <!--
      <li class="nav-item">
        <select id="select-languagelist" class="form-control languagelist"> </select>
      </li>
      -->
        <li class="nav-item">
          <div id="branch-selector" style="padding-top:5px;">
            @if(!\iProtek\Core\Helpers\BranchSelectionHelper::disable_multi_branch())
                  <branch-selector input_size="input-group-sm" :is_system_select="true"/>
            @endif
          </div>
        </li>
      <!-- Messages Dropdown Menu -->
      <li id="message-el" class="nav-item dropdown">
        <message-notification></message-notification>
      </li>
      <!-- Notifications Dropdown Menu -->
      <li id="sys-notification-el" class="nav-item dropdown">
        <sys-notification></sys-notification>
      </li>
      
      <!-- Helpdesk Dropdown -->
      <li id="helpdesk-el" class="nav-item dropdown">
        <helpdesk-notification></helpdesk-notification>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
      <li class="nav-item" title="Logout Now!">
        <a class="nav-link bg-danger px-0" href="/logout" role="button">
          <i class="la--sign-out-alt text-white" style="height:25px;"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->