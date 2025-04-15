<?php include 'db.php'; ?>
<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<style>
  /* Marquee */
  .top-banner-marquee {
	display: flex;
	justify-content: center;
	overflow: hidden;
	white-space: nowrap;
	gap: 20px;
	position: relative;
	width: 100%;
	padding: 20px;
	background-color: #007bff;
}
.banner-items {
  display: flex;
  gap: 35px;
  align-items: center;
  animation: marquee 60s linear infinite;
}
@keyframes marquee {
  0% {
    transform: translateX(100%);
  }
  100% {
    transform: translateX(-100%);
  }
}
.banner-inner-item {
  display: flex;
  align-items: center;
  gap: 8px;
}

.banner-inner-item h4 {
  font-size: 15px;
  line-height: 18px;
  color: #fff;
  font-weight: 400;
  margin: 0 !important;
  text-transform: uppercase;
}

.banner-inner-item img {
  width: 15px;
}
.form-container {
	max-width: 100%;
	margin: 0;
	padding: 30px;
	border-radius: 0;
	background-color: #f9f9f900;
	box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
	width: 100% !important;
}
.main-panel {
  transition:
width 0.25s ease, margin 0.25s ease;
 
  min-height: calc(100vh - 60px);
  display: -webkit-flex;
  display: flex;
  -webkit-flex-direction:
column;
  flex-direction: column;
  overflow:
hidden;
}
.marker-sidebar {
  position: fixed;
  top: 0;
  right: 0;
  width: 400px;
  max-width: 100%;
  height: 100vh;
  background: #fff;
  border-left: 1px solid #ddd;
  box-shadow: -2px 0 6px rgba(0, 0, 0, 0.2);
  overflow-y: auto;
  z-index: 9999;
  transition: transform 0.3s ease-in-out;
}
.marker-sidebar.d-none {
  transform: translateX(100%);
  
}
.sidebar-header {
  padding: 15px;
  border-bottom: 1px solid #ddd;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.form-check .form-check-label {
	min-height: 18px;
	display: block;
	margin-left: 0;
	font-size: 0.875rem;
	line-height: 1.5;
}
.form-check-input {
	margin-right: 6px;
	margin-left: 2px !important;
}
</style>

<!-- Splash Screen -->
<div id="splash">
  <div class="splash-content">
    <h3 class="display-6 fw-bold">Welcome to Geo-Location App</h3>
    <p class="lead">Please allow location to continue</p>
    <button class="btn btn-light mt-3" onclick="handleSplashContinue()">Allow Location</button>
    <div id="loadingSpinner" class="mt-4 d-none">
      <div class="spinner-border text-light" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
      <p class="mt-2">Loading map...</p>
    </div>
  </div>
</div>

<div id="markerSidebar" class="marker-sidebar d-none">
  <div class="sidebar-header">
    <h5>Edit Marker</h5>
    <button class="btn-close" onclick="closeSidebar()"></button>
  </div>
  <form id="editFormSidebar" class="p-3"></form>
</div>

<div class="main-panel">
  <!-- Marker Sidebar -->

    <div class="content-wrapper p-0">
    <?php include 'partials/marquee.php'; ?>
    
    <div id="map" style="height: 100vh;"></div>
    
 

    <script>
  const splashShown = sessionStorage.getItem('splashShown');
  if (splashShown) {
    document.getElementById('splash').style.display = 'none';
    window.onload = () => initMap();
  } else {
    window.onload = () => {
      gsap.to(".splash-content", {
        opacity: 1,
        scale: 1,
        duration: 1,
        ease: "power2.out"
      });
    };
  }

  function handleSplashContinue() {
    document.getElementById('loadingSpinner').classList.remove('d-none');
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        () => splashComplete(),
        () => splashComplete()
      );
    } else {
      splashComplete();
    }
  }

  function splashComplete() {
    sessionStorage.setItem('splashShown', 'true');
    gsap.to("#splash", {
      opacity: 0,
      duration: 1,
      onComplete: () => {
        document.getElementById('splash').style.display = 'none';
        initMap();
      }
    });
  }

  let map;
  let markersArray = []; // Store all markers for future updates
  let infoWindow;
 

  const bagoBounds = {
    north: 10.68,
    south: 10.41,
    west: 122.72,
    east: 123.1
  };


  let editingMarker = null;

function initMap() {
  const bagoCity = { lat: 10.5376, lng: 122.8380 };
  map = new google.maps.Map(document.getElementById("map"), {
    center: bagoCity,
    zoom: 13,
    mapId: "eafb253f7f6aa624",
    restriction: {
      latLngBounds: bagoBounds,
      strictBounds: true,
    },
    mapTypeControl: false,
    streetViewControl: false,
  });

  fetch("get_all_markers.php")
    .then((res) => res.json())
    .then((data) => {
      if (!data || !Array.isArray(data.markers)) {
        console.error("No markers data available or invalid format");
        return;
      }

      markersArray.forEach((marker) => marker.setMap(null)); // Clear previous markers
      markersArray = []; // Reset array

      data.markers.forEach((markerData) => {
        const lat = parseFloat(markerData.lat);
        const lng = parseFloat(markerData.lng);

        const gMarker = new google.maps.marker.AdvancedMarkerElement({
          map,
          position: { lat, lng },
          title: markerData.name,
          content: createMarkerContent(markerData.status),
          gmpClickable: true,
        });

        markersArray.push(gMarker);

        gMarker.addEventListener("gmp-click", () => {
          openEditSidebar(markerData);
          editingMarker = gMarker; // Store the marker being edited
        });
      });
    })
    .catch((err) => {
      console.error("Error fetching markers:", err);
      alert("Failed to load markers.");
    });
}


function escapeHtml(text) {
  const div = document.createElement("div");
  div.textContent = text;
  return div.innerHTML;
}

// Open the sidebar with the form to edit a marker
function openEditSidebar(markerData) {
  const form = document.getElementById("editFormSidebar");
  const status = markerData.status?.toLowerCase() || '';
  form.innerHTML = `
    <input type="hidden" name="id" value="${markerData.id}">
    <input type="hidden" name="lat" value="${markerData.lat}">
    <input type="hidden" name="lng" value="${markerData.lng}">
    <div class="mb-3"><label class="form-label">Establishment Name</label><input class="form-control" name="name" value="${escapeHtml(markerData.name || '')}" required></div>
    <div class="mb-3"><label class="form-label">Address</label><input class="form-control" name="address" value="${escapeHtml(markerData.address || '')}"></div>
    <div class="mb-3"><label class="form-label">Owner/Manager</label><input class="form-control" name="owner_manager" value="${escapeHtml(markerData.owner_manager || '')}"></div>
    <div class="mb-3"><label class="form-label">No. of Personnel</label><input class="form-control" name="num_of_personnel" type="number" value="${markerData.num_of_personnel || 0}"></div>
    <div class="mb-3"><label class="form-label">With Health Cert</label><input class="form-control" name="with_health_cert" type="number" value="${markerData.with_health_cert || 0}"></div>
    <div class="mb-3"><label class="form-label">Sanitary Permit #</label><input class="form-control" name="sanitary_permit_no" value="${escapeHtml(markerData.sanitary_permit_no || '')}"></div>
    <div class="mb-3"><label class="form-label">Demerits (NON-COMPLIANT):</label>
        <div class="form-check"><input class="form-check-input" type="checkbox" name="cleaning_food_utensils" ${markerData.cleaning_food_utensils ? 'checked' : ''}><label class="form-check-label">Cleaning Food Utensils</label></div>
        <div class="form-check"><input class="form-check-input" type="checkbox" name="food_protection" ${markerData.food_protection ? 'checked' : ''}><label class="form-check-label">Food Protection</label></div>
    </div>
    <div class="mb-3"><label class="form-label">Sanitation Inspector</label><input class="form-control" name="inspected_by" value="${escapeHtml(markerData.inspected_by || '')}"></div>
    <div class="mb-3"><label class="form-label">Owner/Operator/Manager</label><input class="form-control" name="received_by" value="${escapeHtml(markerData.received_by || '')}"></div>
    <div class="mb-3"><label class="form-label">Details</label><textarea class="form-control" name="details" rows="4">${escapeHtml(markerData.details || '')}</textarea></div>
    <div class="mb-3"><label class="form-label">Status</label>
      <select class="form-select" name="status" required>
        <option value="Pending" ${status === 'pending' ? 'selected' : ''}>Pending</option>
        <option value="Ongoing" ${status === 'ongoing' ? 'selected' : ''}>Ongoing</option>
        <option value="Done" ${status === 'done' ? 'selected' : ''}>Done</option>
      </select>
    </div>
    <button class="btn btn-primary w-100" type="submit">Update</button>
    <button class="btn btn-danger w-100 mt-2" type="button" onclick="deleteMarker(${markerData.id})">Delete</button>
  `;

  form.onsubmit = updateMarker;
  document.getElementById("markerSidebar").classList.remove("d-none");
}

function updateMarker(e) {
  e.preventDefault();
  const formData = new FormData(e.target);
  const lat = parseFloat(formData.get('lat'));
  const lng = parseFloat(formData.get('lng'));
  const status = formData.get('status') || 'Pending';  // Ensure 'status' is set

  if (isNaN(lat) || isNaN(lng)) {
    alert("Invalid latitude or longitude.");
    return;
  }

  fetch("update_marker.php", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        alert(data.message);
        closeSidebar();

        // After updating the marker, re-fetch the markers and reload the map
        initMap();  // Reinitialize the map to reload all markers
      } else {
        alert(data.message || 'Error updating marker');
      }
    })
    .catch((err) => {
      console.error('Error:', err);
      alert('An error occurred while updating the marker.');
    });
}



function createMarkerContent(status) {
  const pulseDiv = document.createElement("div");
  pulseDiv.className = "pulse-marker";
  pulseDiv.style.backgroundColor = getMarkerColor(status);

  const textDiv = document.createElement("div");
  textDiv.className = "status-text";
  textDiv.textContent = getMarkerText(status);

  pulseDiv.appendChild(textDiv);
  return pulseDiv;
}

function getMarkerColor(status) {
  switch (status.toLowerCase()) {
    case 'done':
      return '#28a745'; // Green for done
    case 'pending':
      return '#ffc107'; // Yellow for pending
    default:
      return '#007bff'; // Blue for ongoing
  }
}

function getMarkerText(status) {
  switch (status.toLowerCase()) {
    case 'done':
      return 'Done';
    case 'pending':
      return 'Pending';
    default:
      return 'Ongoing';
  }
}


// Close the sidebar
function closeSidebar() {
    document.getElementById("markerSidebar").classList.add("d-none");
}


  // Function to delete a marker
  function deleteMarker(markerId) {
    if (confirm("Are you sure you want to delete this marker?")) {
      fetch('delete_marker.php', {
        method: 'POST',
        body: new URLSearchParams({
          id: markerId
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Marker deleted successfully.');
          initMap(); // Re-fetch markers after deletion
        } else {
          alert('Failed to delete marker.');
        }
      })
      .catch(error => {
        console.error('Error deleting marker:', error);
        alert('Error deleting marker.');
      });
    }
  }
</script>


<?php include 'footer.php'; ?>
