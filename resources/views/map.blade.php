@extends('layouts.app')

@section('title', 'Map View - Find & Found')

@section('content')
<div class="map-page">
    <!-- Map Controls -->
    <div class="map-controls">
        <div class="container">
            <div class="controls-header">
                <h4><i class="fas fa-map-marker-alt me-2"></i>Map View</h4>
                <div class="map-stats">
                    <span class="stat-item">
                        <span class="stat-number" id="totalItems">0</span>
                        <span class="stat-label">Items</span>
                    </span>
                    <span class="stat-item">
                        <span class="stat-number" id="visibleItems">0</span>
                        <span class="stat-label">Visible</span>
                    </span>
                </div>
            </div>
            
            <div class="controls-content">
                <div class="row g-3">
                    <!-- Filters -->
                    <div class="col-md-3">
                        <select class="form-select" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="lost">Lost Items</option>
                            <option value="found">Found Items</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="categoryFilter">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" id="searchFilter" placeholder="Search items...">
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary" onclick="getCurrentLocation()">
                                <i class="fas fa-crosshairs"></i>
                            </button>
                            <button class="btn btn-outline-secondary" onclick="clearFilters()">
                                <i class="fas fa-times"></i> Clear
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Container -->
    <div class="map-container-full" id="mapContainer">
        <div id="map"></div>
        
        <!-- Map Legend -->
        <div class="map-legend">
            <h6>Legend</h6>
            <div class="legend-items">
                <div class="legend-item">
                    <div class="legend-marker lost"></div>
                    <span>Lost Items</span>
                </div>
                <div class="legend-item">
                    <div class="legend-marker found"></div>
                    <span>Found Items</span>
                </div>
                <div class="legend-item">
                    <div class="legend-marker resolved"></div>
                    <span>Resolved</span>
                </div>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div class="map-loading" id="mapLoading">
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading items...</p>
            </div>
        </div>
    </div>

    <!-- Item Details Modal -->
    <div class="modal fade" id="itemModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="itemModalTitle">Item Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="itemModalBody">
                    <!-- Content will be loaded dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="#" class="btn btn-primary" id="viewItemBtn">View Full Details</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Map Page Layout */
.map-page {
    padding: 0;
    margin-top: -1rem; /* Offset container padding */
}

/* Map Controls */
.map-controls {
    background: white;
    border-bottom: 1px solid #dee2e6;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    position: relative;
    z-index: 1000;
}

.controls-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0 0.5rem;
}

.controls-header h4 {
    margin-bottom: 0;
    color: #212529;
    font-weight: 600;
}

.map-stats {
    display: flex;
    gap: 2rem;
}

.stat-item {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: #2563eb;
}

.stat-label {
    font-size: 0.8rem;
    color: #6c757d;
}

.controls-content {
    padding-bottom: 1rem;
}

/* Map Container */
.map-container-full {
    height: calc(100vh - 200px);
    position: relative;
    background: #f8f9fa;
}

#map {
    height: 100%;
    width: 100%;
}

/* Map Legend */
.map-legend {
    position: absolute;
    top: 20px;
    right: 20px;
    background: white;
    padding: 1rem;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    z-index: 1000;
    min-width: 150px;
}

.map-legend h6 {
    margin-bottom: 0.75rem;
    color: #212529;
    font-weight: 600;
}

.legend-items {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
}

.legend-marker {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
}

.legend-marker.lost {
    background: #ffc107;
}

.legend-marker.found {
    background: #28a745;
}

.legend-marker.resolved {
    background: #dc3545;
}

/* Loading Overlay */
.map-loading {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2000;
}

.loading-spinner {
    text-align: center;
    color: #6c757d;
}

.loading-spinner i {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: #2563eb;
}

/* Custom Marker Styles */
.custom-marker {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    cursor: pointer;
    transition: transform 0.2s;
}

.custom-marker:hover {
    transform: scale(1.2);
}

.custom-marker.lost {
    background: #ffc107;
}

.custom-marker.found {
    background: #28a745;
}

.custom-marker.resolved {
    background: #dc3545;
}

/* Popup Styles */
.leaflet-popup-content-wrapper {
    border-radius: 8px;
}

.item-popup {
    max-width: 250px;
}

.popup-image {
    width: 100%;
    height: 120px;
    object-fit: cover;
    border-radius: 4px;
    margin-bottom: 0.5rem;
}

.popup-title {
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #212529;
}

.popup-meta {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.popup-status {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 0.5rem;
}

.popup-status.lost {
    background: #ffc107;
    color: #212529;
}

.popup-status.found {
    background: #28a745;
    color: white;
}

.popup-actions {
    display: flex;
    gap: 0.5rem;
}

.popup-actions .btn {
    font-size: 0.8rem;
    padding: 0.25rem 0.5rem;
}

/* Responsive */
@media (max-width: 768px) {
    .map-container-full {
        height: calc(100vh - 250px);
    }
    
    .controls-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .map-stats {
        justify-content: center;
        gap: 1rem;
    }
    
    .map-legend {
        top: 10px;
        right: 10px;
        left: 10px;
        padding: 0.75rem;
    }
    
    .legend-items {
        flex-direction: row;
        justify-content: space-around;
    }
    
    .controls-content .row {
        gap: 0.5rem;
    }
    
    .controls-content .col-md-3 {
        flex: 0 0 100%;
        max-width: 100%;
    }
}

@media (max-width: 576px) {
    .map-container-full {
        height: calc(100vh - 280px);
    }
    
    .stat-number {
        font-size: 1.2rem;
    }
    
    .item-popup {
        max-width: 200px;
    }
    
    .popup-image {
        height: 100px;
    }
}
</style>
@endpush

@push('scripts')
<script>
let map;
let markers = [];
let allItems = [];

// Initialize map
document.addEventListener('DOMContentLoaded', function() {
    initMap();
    loadMapData();
    setupEventListeners();
});

function initMap() {
    // Default to Jakarta coordinates
    map = L.map('map').setView([-6.2088, 106.8456], 10);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Try to get user location
    getCurrentLocation();
}

function loadMapData() {
    showLoading(true);
    
    const params = new URLSearchParams({
        status: document.getElementById('statusFilter').value,
        category: document.getElementById('categoryFilter').value,
        search: document.getElementById('searchFilter').value
    });
    
    fetch(`{{ route('api.map.data') }}?${params}`)
        .then(response => response.json())
        .then(data => {
            allItems = data.markers;
            updateMarkers();
            updateStats();
            showLoading(false);
        })
        .catch(error => {
            console.error('Error loading map data:', error);
            showLoading(false);
            alert('Failed to load map data. Please try again.');
        });
}

function updateMarkers() {
    // Clear existing markers
    markers.forEach(marker => map.removeLayer(marker));
    markers = [];
    
    // Add new markers
    allItems.forEach(item => {
        const marker = createCustomMarker(item);
        markers.push(marker);
        marker.addTo(map);
    });
    
    // Fit map to markers if any exist
    if (markers.length > 0) {
        const group = new L.featureGroup(markers);
        map.fitBounds(group.getBounds().pad(0.1));
    }
}

function createCustomMarker(item) {
    const markerIcon = L.divIcon({
        className: 'custom-div-icon',
        html: `<div class="custom-marker ${item.status} ${item.is_resolved ? 'resolved' : ''}" 
                    title="${item.title}"></div>`,
        iconSize: [24, 24],
        iconAnchor: [12, 12]
    });
    
    const marker = L.marker([item.latitude, item.longitude], { icon: markerIcon });
    
    // Create popup content
    const popupContent = `
        <div class="item-popup">
            <img src="${item.first_photo}" alt="${item.title}" class="popup-image">
            <h6 class="popup-title">${item.title}</h6>
            <div class="popup-meta">
                <i class="fas fa-map-marker-alt"></i> ${item.address}<br>
                <i class="fas fa-calendar"></i> ${item.event_date}
            </div>
            <span class="popup-status ${item.status}">${item.status === 'lost' ? 'Lost' : 'Found'}</span>
            <div class="popup-actions">
                <button class="btn btn-primary btn-sm" onclick="showItemDetails(${item.id})">
                    View Details
                </button>
            </div>
        </div>
    `;
    
    marker.bindPopup(popupContent);
    
    return marker;
}

function showItemDetails(itemId) {
    const item = allItems.find(i => i.id === itemId);
    if (!item) return;
    
    document.getElementById('itemModalTitle').textContent = item.title;
    document.getElementById('viewItemBtn').href = item.url;
    
    const modalBody = document.getElementById('itemModalBody');
    modalBody.innerHTML = `
        <div class="row">
            <div class="col-md-4">
                <img src="${item.first_photo}" alt="${item.title}" class="img-fluid rounded">
            </div>
            <div class="col-md-8">
                <h5>${item.title}</h5>
                <p class="text-muted">${item.category}</p>
                <p><strong>Status:</strong> 
                    <span class="badge bg-${item.status === 'lost' ? 'warning' : 'success'}">
                        ${item.status === 'lost' ? 'Lost' : 'Found'}
                    </span>
                </p>
                <p><strong>Location:</strong> ${item.address}</p>
                <p><strong>Date:</strong> ${item.event_date}</p>
                <p><strong>Distance:</strong> <span id="itemDistance">Calculating...</span></p>
            </div>
        </div>
    `;
    
    // Calculate distance if user location is available
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const distance = calculateDistance(
                position.coords.latitude, 
                position.coords.longitude,
                item.latitude, 
                item.longitude
            );
            document.getElementById('itemDistance').textContent = `${distance.toFixed(1)} km away`;
        });
    }
    
    const modal = new bootstrap.Modal(document.getElementById('itemModal'));
    modal.show();
}

function updateStats() {
    document.getElementById('totalItems').textContent = allItems.length;
    document.getElementById('visibleItems').textContent = markers.length;
}

function setupEventListeners() {
    document.getElementById('statusFilter').addEventListener('change', loadMapData);
    document.getElementById('categoryFilter').addEventListener('change', loadMapData);
    
    // Debounce search input
    let searchTimeout;
    document.getElementById('searchFilter').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(loadMapData, 500);
    });
}

function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            map.setView([lat, lng], 13);
            
            // Add user location marker
            const userIcon = L.divIcon({
                className: 'user-location-icon',
                html: '<div style="background: #2563eb; width: 16px; height: 16px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 6px rgba(0,0,0,0.3);"></div>',
                iconSize: [16, 16],
                iconAnchor: [8, 8]
            });
            
            L.marker([lat, lng], { icon: userIcon })
                .addTo(map)
                .bindPopup('Your location')
                .openPopup();
                
        }, function(error) {
            console.log('Geolocation error:', error);
        });
    }
}

function clearFilters() {
    document.getElementById('statusFilter').value = '';
    document.getElementById('categoryFilter').value = '';
    document.getElementById('searchFilter').value = '';
    loadMapData();
}

function showLoading(show) {
    const loadingElement = document.getElementById('mapLoading');
    loadingElement.style.display = show ? 'flex' : 'none';
}

function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371; // Radius of the Earth in kilometers
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
        Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}

// Refresh data every 30 seconds
setInterval(loadMapData, 30000);
</script>
@endpush