@extends('layouts.app')

@section('title', 'Tambah Item - Find & Found')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Page Header -->
            <div class="text-center mb-4">
                <h1 class="h3 fw-bold">Laporkan Barang Hilang atau Ditemukan</h1>
                <p class="text-muted">Bantu sesama dengan melaporkan barang yang hilang atau yang Anda temukan</p>
            </div>

            <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" id="itemForm">
                @csrf

                <!-- Step 1: Basic Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Dasar</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Status -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Status Barang <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check form-check-inline p-3 border rounded {{ old('status') === 'lost' ? 'border-danger bg-light' : '' }}">
                                            <input class="form-check-input" type="radio" name="status" id="statusLost" value="lost" {{ old('status') === 'lost' ? 'checked' : '' }} required>
                                            <label class="form-check-label w-100" for="statusLost">
                                                <div class="text-center">
                                                    <i class="fas fa-exclamation-circle fa-2x text-danger mb-2"></i>
                                                    <h6 class="fw-bold">Barang Hilang</h6>
                                                    <small class="text-muted">Saya kehilangan barang ini</small>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-check-inline p-3 border rounded {{ old('status') === 'found' ? 'border-success bg-light' : '' }}">
                                            <input class="form-check-input" type="radio" name="status" id="statusFound" value="found" {{ old('status') === 'found' ? 'checked' : '' }} required>
                                            <label class="form-check-label w-100" for="statusFound">
                                                <div class="text-center">
                                                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                                    <h6 class="fw-bold">Barang Ditemukan</h6>
                                                    <small class="text-muted">Saya menemukan barang ini</small>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @error('status')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Title -->
                            <div class="col-md-12 mb-3">
                                <label for="title" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required placeholder="Contoh: iPhone 14 Pro Max Warna Biru">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Berikan nama yang jelas dan spesifik</div>
                            </div>

                            <!-- Category -->
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Event Date -->
                            <div class="col-md-6 mb-3">
                                <label for="event_date" class="form-label">Tanggal Kejadian <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('event_date') is-invalid @enderror" id="event_date" name="event_date" value="{{ old('event_date') }}" max="{{ date('Y-m-d') }}" required>
                                @error('event_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Kapan barang hilang/ditemukan?</div>
                            </div>

                            <!-- Description -->
                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">Deskripsi Detail <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required placeholder="Jelaskan detail barang, ciri khas, kondisi, dan informasi penting lainnya...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Semakin detail, semakin besar kemungkinan barang ditemukan</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Photos -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-camera me-2"></i>Foto Barang</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="photos" class="form-label">Upload Foto (Maksimal 5 foto)</label>
                            <input type="file" class="form-control @error('photos') is-invalid @enderror" id="photos" name="photos[]" accept="image/*" multiple>
                            @error('photos')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('photos.*')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Format: JPG, PNG, GIF. Ukuran maksimal: 2MB per foto</div>
                        </div>

                        <!-- Photo Preview -->
                        <div id="photoPreview" class="row g-2"></div>
                    </div>
                </div>

                <!-- Step 3: Location -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Lokasi Kejadian</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Address -->
                            <div class="col-md-8 mb-3">
                                <label for="address" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2" required placeholder="Contoh: Jl. Sudirman No. 123, Menteng, Jakarta Pusat">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- City -->
                            <div class="col-md-4 mb-3">
                                <label for="city" class="form-label">Kota <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}" required placeholder="Contoh: Jakarta">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Province -->
                            <div class="col-md-6 mb-3">
                                <label for="province" class="form-label">Provinsi</label>
                                <input type="text" class="form-control @error('province') is-invalid @enderror" id="province" name="province" value="{{ old('province') }}" placeholder="Contoh: DKI Jakarta">
                                @error('province')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Coordinates (Hidden) -->
                            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                            <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">

                            <!-- Map -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Pilih Lokasi di Peta</label>
                                <div class="d-flex gap-2 mb-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="getCurrentLocation()">
                                        <i class="fas fa-crosshairs me-1"></i>Gunakan Lokasi Saya
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="searchLocation()">
                                        <i class="fas fa-search me-1"></i>Cari di Peta
                                    </button>
                                </div>
                                <div id="map" class="map-container border rounded"></div>
                                <div class="form-text">Klik pada peta untuk menandai lokasi yang tepat</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                                <label class="form-check-label" for="agreeTerms">
                                    Saya setuju bahwa informasi yang saya berikan adalah benar dan saya bersedia untuk dihubungi
                                </label>
                            </div>
                            <div>
                                <a href="{{ route('items.index') }}" class="btn btn-outline-secondary me-2">Batal</a>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save me-2"></i>Simpan Item
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let map, marker;

// Initialize map
function initMap() {
    // Default to Jakarta coordinates
    const defaultLat = -6.2088;
    const defaultLng = 106.8456;
    
    map = L.map('map').setView([defaultLat, defaultLng], 10);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Add click event to map
    map.on('click', function(e) {
        setLocation(e.latlng.lat, e.latlng.lng);
        reverseGeocode(e.latlng.lat, e.latlng.lng);
    });
}

// Set location on map
function setLocation(lat, lng) {
    if (marker) {
        map.removeLayer(marker);
    }
    
    marker = L.marker([lat, lng]).addTo(map);
    map.setView([lat, lng], 15);
    
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;
}

// Get current location
function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            setLocation(lat, lng);
            reverseGeocode(lat, lng);
        }, function(error) {
            alert('Gagal mendapatkan lokasi. Pastikan Anda mengizinkan akses lokasi.');
        });
    } else {
        alert('Browser Anda tidak mendukung geolokasi.');
    }
}

// Reverse geocoding (get address from coordinates)
function reverseGeocode(lat, lng) {
    // TODO: Implement reverse geocoding API
    // For now, just update coordinates
    console.log('Reverse geocoding:', lat, lng);
}

// Search location
function searchLocation() {
    const address = document.getElementById('address').value;
    if (!address) {
        alert('Masukkan alamat terlebih dahulu');
        return;
    }
    
    // TODO: Implement geocoding API
    console.log('Searching location:', address);
}

// Photo preview
document.getElementById('photos').addEventListener('change', function(e) {
    const preview = document.getElementById('photoPreview');
    preview.innerHTML = '';
    
    const files = Array.from(e.target.files);
    
    if (files.length > 5) {
        alert('Maksimal 5 foto');
        e.target.value = '';
        return;
    }
    
    files.forEach((file, index) => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col-md-2 col-4';
                col.innerHTML = `
                    <div class="position-relative">
                        <img src="${e.target.result}" class="img-fluid rounded" style="height: 100px; width: 100%; object-fit: cover;">
                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1" onclick="removePhoto(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                preview.appendChild(col);
            };
            reader.readAsDataURL(file);
        }
    });
});

// Remove photo
function removePhoto(index) {
    const input = document.getElementById('photos');
    const dt = new DataTransfer();
    const files = input.files;
    
    for (let i = 0; i < files.length; i++) {
        if (i !== index) {
            dt.items.add(files[i]);
        }
    }
    
    input.files = dt.files;
    input.dispatchEvent(new Event('change'));
}

// Status radio styling
document.querySelectorAll('input[name="status"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.form-check').forEach(function(check) {
            check.classList.remove('border-danger', 'border-success', 'bg-light');
        });
        
        const selectedCheck = this.closest('.form-check');
        if (this.value === 'lost') {
            selectedCheck.classList.add('border-danger', 'bg-light');
        } else {
            selectedCheck.classList.add('border-success', 'bg-light');
        }
    });
});

// Form validation
document.getElementById('itemForm').addEventListener('submit', function(e) {
    const lat = document.getElementById('latitude').value;
    const lng = document.getElementById('longitude').value;
    
    if (!lat || !lng) {
        e.preventDefault();
        alert('Silakan pilih lokasi di peta terlebih dahulu');
        return false;
    }
    
    const agreeTerms = document.getElementById('agreeTerms').checked;
    if (!agreeTerms) {
        e.preventDefault();
        alert('Anda harus menyetujui syarat dan ketentuan');
        return false;
    }
    
    // Show loading state
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
});

// Initialize map when document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Set default date to today
    document.getElementById('event_date').value = new Date().toISOString().split('T')[0];
    
    // Initialize map
    initMap();
    
    // Auto-fill city from address
    document.getElementById('address').addEventListener('blur', function() {
        const address = this.value;
        const cityInput = document.getElementById('city');
        
        if (address && !cityInput.value) {
            // Try to extract city from address
            const parts = address.split(',');
            if (parts.length > 1) {
                cityInput.value = parts[parts.length - 2].trim();
            }
        }
    });
});

// Character counter for description
document.getElementById('description').addEventListener('input', function() {
    const maxLength = 1000;
    const currentLength = this.value.length;
    const remaining = maxLength - currentLength;
    
    let counter = document.getElementById('descriptionCounter');
    if (!counter) {
        counter = document.createElement('div');
        counter.id = 'descriptionCounter';
        counter.className = 'form-text text-end';
        this.parentNode.appendChild(counter);
    }
    
    counter.textContent = `${currentLength}/${maxLength} karakter`;
    
    if (remaining < 50) {
        counter.classList.add('text-warning');
    } else {
        counter.classList.remove('text-warning');
    }
});
</script>
@endpush

@push('styles')
<style>
.form-check {
    cursor: pointer;
    transition: all 0.2s;
}

.form-check:hover {
    background-color: #f8f9fa !important;
}

.map-container {
    height: 300px;
    min-height: 300px;
}

#photoPreview img {
    transition: transform 0.2s;
}

#photoPreview img:hover {
    transform: scale(1.05);
}

.card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border: none;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

@media (max-width: 768px) {
    .map-container {
        height: 250px;
    }
}
</style>
@endpush