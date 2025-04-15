<script>
let allMarkers = [];
let filteredMarkers = [];
let currentPage = 1;
const itemsPerPage = 5;

$(document).ready(function () {
fetchMarkers();
setInterval(fetchMarkers, 5000);

$('#address').select2({
    placeholder: 'Select a location in Bago City',
    allowClear: true,
    width: '100%'
});


$('#addMarkerForm').on('submit', function (e) {
    e.preventDefault();
    const $btn = $('#submitButton');
    $btn.prop('disabled', true).text('Saving...');

    const address = $('#address').val();
    const geocodeUrl = `https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(address + ', Bago City, Philippines')}&key=AIzaSyAmo6ZfKZZ4YeYvaGwpmaom_-ZmtYiWT74`;

    fetch(geocodeUrl)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'OK') {
                const location = data.results[0].geometry.location;
                const formData = $(e.target).serialize() + `&lat=${location.lat}&lng=${location.lng}`;

                $.ajax({
                    url: 'add_marker_action.php',
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function (response) {
                        $btn.prop('disabled', false).text('Save');
                        if (response.status === 'success') {
                            $('#addMarkerForm')[0].reset();
                            const newMarker = response.marker;

                            if (!allMarkers.find(m => m.id == newMarker.id)) {
                                allMarkers.unshift(newMarker);
                                filterMarkers();
                                const modal = new bootstrap.Modal(document.getElementById('markerSuccessModal'));
                                modal.show();
                                modal._element.addEventListener('hidden.bs.modal', () => {
                                    window.location.href = `clients_map.php?address=${encodeURIComponent(newMarker.address)}`;
                                });
                            }
                        } else {
                            alert(response.message || 'Failed to add marker.');
                        }
                    },
                    error: function () {
                        $btn.prop('disabled', false).text('Save');
                        alert('An error occurred while saving the marker.');
                    }
                });
            } else {
                $btn.prop('disabled', false).text('Save');
                alert('Failed to geocode address.');
            }
        });
    });
});

function fetchMarkers() {
    $('#loadingIndicator').show();

    $.ajax({
        url: 'get_markers.php',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            $('#loadingIndicator').hide();

            if (Array.isArray(data)) {
                const preservePage = currentPage; // preserve current page
                allMarkers = data;
                filterMarkers();
                currentPage = preservePage; // restore page after filtering
                renderMarkerList();
            } else {
                $('#markerList').html('<p class="text-muted">No markers found.</p>');
            }
        },
        error: function () {
            $('#loadingIndicator').hide();
            $('#markerList').html('<p class="text-danger">Failed to load markers. Please try again later.</p>');
        }
    });
}


function filterMarkers() {
    const query = $('#searchInput').val().toLowerCase();
    filteredMarkers = allMarkers.filter(marker =>
        marker.address.toLowerCase().includes(query) ||
        marker.status.toLowerCase().includes(query)
    );

    // Only reset to page 1 if query changes
    if (query) {
        currentPage = 1;
    }

    renderMarkerList();
}


function renderMarkerList() {
    const markerList = $('#markerList');
    markerList.empty();

    if (filteredMarkers.length === 0) {
        markerList.html('<p class="text-muted">No markers found.</p>');
        return;
    }

    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const paginated = filteredMarkers.slice(start, end);

    paginated.forEach(marker => {
        const row = `
            <tr class="p-2">
                <td class="p-2">${marker.address}</td>
                <td class="p-2">
                   <div class="badge badge-${getStatusClass(marker.status)} text-capitalize">${marker.status}</div>
                </td >
                <td class="p-2" >
                    <div class="details-wrapper">
                        <p class="marker-details">${marker.details}</p>
                    </div>
                </td>
                <td class="p-2">
                    <button class="btn p-0" onclick="editMarker(${marker.id}, '${marker.address}')">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="btn p-0" onclick="deleteMarker(${marker.id})">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        markerList.append(row);
    });

    renderPaginationControls();
}

function renderPaginationControls() {
    const totalPages = Math.ceil(filteredMarkers.length / itemsPerPage);
    if (totalPages <= 1) return;

    const pagination = $('<div class="pagination mt-3 d-flex gap-2"></div>');

    for (let i = 1; i <= totalPages; i++) {
        const btn = $(`<a href="#" class="btn btn-sm ${i === currentPage ? 'btn' : 'btn'}">${i}</a>`);
        
        btn.on('click', function (e) {
            e.preventDefault(); // prevent navigation
            currentPage = i;
            renderMarkerList();
        });

        pagination.append(btn);
    }

    $('#markerList').append(pagination);
}


function getStatusClass(status) {
    switch (status.toLowerCase()) {
        case 'done': return 'success';
        case 'pending': return 'warning';
        case 'ongoing': return 'primary';
        default: return 'secondary';
    }
}

function deleteAllMarkers() {
    if (confirm('Are you sure you want to delete all markers?')) {
        $.post('delete_all_markers.php', function () {
            allMarkers = [];
            filteredMarkers = [];
            renderMarkerList();
            alert('All markers deleted.');
        }).fail(function () {
            alert('Failed to delete markers.');
        });
    }
}

function deleteMarker(id) {
    if (confirm('Delete this marker?')) {
        $.post('delete.php', { id }, function () {
            allMarkers = allMarkers.filter(m => m.id != id);
            filterMarkers();
        }).fail(function () {
            alert('Failed to delete marker.');
        });
    }
}

function editMarker(id, address) {
    window.location.href = `index.php?address=${encodeURIComponent(address)}&edit=${id}`;
}
</script>
