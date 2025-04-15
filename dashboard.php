<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$role = $_SESSION['role'];
$bio = $_SESSION['bio'];
$username = $_SESSION['username'];
?>

<?php include 'header.php'; ?>

<!-- Include Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<div class="container-scroller">

<?php include 'sidebar.php'; ?>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row mb-4">
            <div class="col-12 col-xl-8">
                <h3 class="font-weight-bold">Welcome <?= htmlspecialchars($username) ?></h3>
                <h6 class="font-weight-normal mb-0">All systems are running smoothly!</h6>
            </div>
        </div>

        <!-- Form + Marker List in One Row -->
        <div class="row mb-4">
          <!-- Form (30%) -->
          <div class="col-md-4">
            <div class="card p-4">
              <h4 class="mb-3">Designation Area</h4>
              <form id="addMarkerForm">
                <select id="address" class="form-control" name="address" required>
                  <option value="" disabled selected>Select a location in Bago City</option>
                  <?php
                  $barangays = [
                    "Abuanan", "Alianza", "Antipuluan", "Balingasag", "Bagroy", "Binubuhan", "Busay",
                    "Calumangan", "Caridad", "Dulao", "Ilijan", "Lag-asan", "Ma-ao", "Malingin",
                    "Napoles", "Pacol", "Poblacion", "Sagasa", "Tabunan", "Taloc"
                  ];
                  foreach ($barangays as $barangay) {
                    echo "<option value=\"$barangay\">$barangay</option>";
                  }
                  ?>
                </select>

                <select class="form-control mt-2" name="status" id="status" required>
                  <option value="" disabled selected>Select status</option>
                  <option value="pending">Pending</option>
                  <option value="ongoing">Ongoing</option>
                  <option value="done">Done</option>
                </select>

                <textarea class="form-control mt-2" name="details" placeholder="Enter details here..." rows="4" required></textarea>

                <button id="submitButton" class="btn btn-primary mt-3 w-100" type="submit">Save</button>
              </form>
            </div>
          </div>

          <!-- Marker List (70%) -->
            <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                <p class="card-title mb-0">Top Markers</p>

                <!-- Search Bar -->
                <div class="input-group mb-3">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search by address or status..." oninput="filterMarkers()">
                </div>

                <!-- Table Style Marker List -->
                <div class="table-responsive">
                    <table class="table table-striped table-borderless">
                    <thead>
                        <tr>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Details</th>
                        <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="markerList">
                        <tr id="loadingIndicator">
                        <td colspan="4" class="text-center">
                            <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted">Loading markers...</p>
                        </td>
                        </tr>
                    </tbody>
                    </table>
                </div>

                <!-- Footer Buttons -->
                <div class="mt-4 d-flex justify-content-between">
                    <button class="btn btn-secondary" onclick="window.location.href='index.php'">Go to Map</button>
                    <button class="btn btn-danger" onclick="deleteAllMarkers()">Delete All Markers</button>
                </div>
                </div>
            </div>
            </div>


      

<?php include'assets\js\add-marker.js'?>

<?php include'footer.php'?>
