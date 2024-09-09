  <!-- Navbar -->
  <nav id="top-menu" class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
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
        <select id="select-languagelist" class="form-control languagelist"> </select>
      </li>
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
      <li class="nav-item">
        <a class="nav-link" href="/logout" role="button">
          <i class="far fa-user"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->