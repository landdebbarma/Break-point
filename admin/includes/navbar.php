<!-- This is a reuseable navbar -->
<?php

if (isset($_GET['action']) && $_GET['action'] == 'logout') {

    session_unset();
    session_destroy();
    
    echo "<script>
        alert('You have been logged out successfully.');
        window.location.href = '/break-point/admin/login';
    </script>";
    exit;
}
?>


<nav class="navbar navbar-expand-lg bg-body-tertiary shadow" data-bs-theme="dark">
  <div class="container">
    <a class="navbar-brand" href="/break-point/admin">
        <img src="/break-point/img/break point.jpg" alt="Logo" width="35" height="34" class="d-inline-block align-text-top">
        Break Point
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/break-point/admin">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/break-point/admin/billboards">Billboards</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/break-point/admin/category">Menu Category</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/break-point/admin/items">Menu Items</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/break-point/admin/settings">Settings</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" onclick="confirmLogout()">Log Out</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<script>
    function confirmLogout() {

        if (confirm('Are you sure you want to log out?')) {
            window.location.href = '?action=logout';
        }
    }
</script>