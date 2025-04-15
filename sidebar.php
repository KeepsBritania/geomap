<!-- partial -->
<div class="container-fluid page-body-wrapper">
  <!-- partial:partials/_sidebar.html -->
  <nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">

      <!-- Dashboard with submenu -->
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#dashboardMenu" aria-expanded="false" aria-controls="dashboardMenu">
          <i class="icon-grid menu-icon"></i>
          <span class="menu-title">Dashboard</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="dashboardMenu">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="dashboard.php">Admin</a></li>
            <li class="nav-item"> <a class="nav-link" href="pages/dashboard/client.html">Client</a></li>
            <li class="nav-item"> <a class="nav-link" href="clients_map.php">Field Worker</a></li>
          </ul>
        </div>
      </li>

      <!-- Schedule Appointment -->
      <li class="nav-item">
        <a class="nav-link" href="pages/schedule/appointment.html">
          <i class="icon-calendar menu-icon"></i>
          <span class="menu-title">Schedule Appointment</span>
        </a>
      </li>

      <!-- Analytics -->
      <li class="nav-item">
        <a class="nav-link" href="pages/analytics/index.html">
          <i class="icon-bar-graph menu-icon"></i>
          <span class="menu-title">Analytics</span>
        </a>
      </li>

    </ul>
  </nav>
