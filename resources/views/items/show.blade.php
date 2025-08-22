@extends('layouts.app')

@section('title', $item->title . ' - Find & Found')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Item Header -->
            <div class="item-header mb-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="item-status-badge status-{{ $item->status }} mb-2">
                            <i class="fas fa-{{ $item->status === 'lost' ? 'exclamation-circle' : 'check-circle' }} me-1"></i>
                            {{ $item->status_label }}
                        </div>
                        <h1 class="item-title">{{ $item->title }}</h1>
                        <div class="item-meta">
                            <span class="meta-location">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                {{ $item->location->city }}
                            </span>
                            <span class="meta-separator">•</span>
                            <span class="meta-time">{{ $item->created_at->diffForHumans() }}</span>
                            <span class="meta-separator">•</span>
                            <span class="meta-views">
                                <i class="fas fa-eye me-1"></i>
                                {{ $item->views_count }} views
                            </span>
                        </div>
                    </div>
                    @auth
                        @if($item->canBeEditedBy(auth()->user()))
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('items.edit', $item->id) }}">
                                        <i class="fas fa-edit me-2"></i>Edit
                                    </a></li>
                                    @if(!$item->is_resolved)
                                        <li>
                                            <form action="{{ route('items.mark-resolved', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="dropdown-item" type="submit">
                                                    <i class="fas fa-check me-2"></i>Mark as Resolved
                                                </button>
                                            </form>
                                        </li>
                                    @endif
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="dropdown-item text-danger" type="submit">
                                                <i class="fas fa-trash me-2"></i>Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Item Images -->
            @if($item->photos && count($item->photos) > 0)
                <div class="item-gallery mb-4">
                    <div id="itemCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($item->photo_urls as $index => $photo)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ $photo }}" class="d-block w-100" alt="{{ $item->title }}">
                                </div>
                            @endforeach
                        </div>
                        @if(count($item->photos) > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#itemCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#itemCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                            <div class="carousel-indicators">
                                @foreach($item->photos as $index => $photo)
                                    <button type="button" data-bs-target="#itemCarousel" data-bs-slide-to="{{ $index }}" 
                                            class="{{ $index === 0 ? 'active' : '' }}"></button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Item Description -->
            <div class="item-description mb-4">
                <h5>Description</h5>
                <div class="description-content">
                    {!! nl2br(e($item->description)) !!}
                </div>
            </div>

            <!-- Item Details -->
            <div class="item-details mb-4">
                <h5>Details</h5>
                <div class="details-grid">
                    <div class="detail-item">
                        <span class="detail-label">Category</span>
                        <span class="detail-value">{{ $item->category->name }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Date {{ $item->status === 'lost' ? 'Lost' : 'Found' }}</span>
                        <span class="detail-value">{{ $item->event_date->format('M d, Y') }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Location</span>
                        <span class="detail-value">{{ $item->location->full_address }}</span>
                    </div>
                    @if($item->is_resolved)
                        <div class="detail-item">
                            <span class="detail-label">Status</span>
                            <span class="detail-value">
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Resolved
                                </span>
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Map -->
            <div class="item-map mb-4">
                <h5>Location</h5>
                <div id="itemMap" class="map-container"></div>
            </div>

            <!-- Comments Section -->
            <div class="comments-section">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Comments ({{ $item->comments->count() }})</h5>
                </div>

                @auth
                    <!-- Add Comment Form -->
                    <div class="add-comment mb-4">
                        <form action="{{ route('comments.store', $item->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <textarea class="form-control" name="content" rows="3" 
                                          placeholder="Write a comment..." required>{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Post Comment</button>
                        </form>
                    </div>
                @else
                    <div class="auth-prompt mb-4">
                        <p class="text-muted">
                            <a href="{{ route('login') }}">Login</a> to post a comment
                        </p>
                    </div>
                @endauth

                <!-- Comments List -->
                <div class="comments-list">
                    @forelse($item->comments->where('is_approved', true) as $comment)
                        <div class="comment-item">
                            <div class="comment-avatar">
                                <img src="{{ $comment->user->avatar_url }}" alt="{{ $comment->user->name }}">
                            </div>
                            <div class="comment-content">
                                <div class="comment-header">
                                    <span class="comment-author">{{ $comment->user->name }}</span>
                                    <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="comment-text">{{ $comment->content }}</div>
                                @auth
                                    @if($comment->canBeEditedBy(auth()->user()))
                                        <div class="comment-actions">
                                            <button class="btn btn-link btn-sm text-muted p-0" onclick="editComment({{ $comment->id }})">
                                                Edit
                                            </button>
                                            <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-link btn-sm text-danger p-0" type="submit" 
                                                        onclick="return confirm('Delete this comment?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    @empty
                        <div class="empty-comments">
                            <p class="text-muted text-center">No comments yet. Be the first to comment!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Contact Card -->
            <div class="contact-card mb-4">
                <div class="contact-header">
                    <img src="{{ $item->user->avatar_url }}" alt="{{ $item->user->name }}" class="contact-avatar">
                    <div class="contact-info">
                        <h6 class="contact-name">{{ $item->user->name }}</h6>
                        <p class="contact-joined">Member since {{ $item->user->created_at->format('M Y') }}</p>
                    </div>
                </div>
                
                @auth
                    @if($item->user_id !== auth()->id())
                        <div class="contact-actions">
                            <a href="{{ route('chats.show', $item->user_id) }}" class="btn btn-primary w-100 mb-2">
                                <i class="fas fa-comments me-2"></i>Send Message
                            </a>
                            <button class="btn btn-outline-secondary w-100" onclick="showContactInfo()">
                                <i class="fas fa-phone me-2"></i>Show Contact Info
                            </button>
                        </div>
                    @endif
                @else
                    <div class="contact-actions">
                        <a href="{{ route('login') }}" class="btn btn-primary w-100">
                            <i class="fas fa-sign-in-alt me-2"></i>Login to Contact
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Safety Tips -->
            <div class="safety-tips mb-4">
                <h6><i class="fas fa-shield-alt me-2 text-success"></i>Safety Tips</h6>
                <ul class="tips-list">
                    <li>Meet in a public place</li>
                    <li>Bring a friend if possible</li>
                    <li>Verify item details before meeting</li>
                    <li>Trust your instincts</li>
                </ul>
            </div>

            <!-- Report Item -->
            @auth
                @if($item->user_id !== auth()->id())
                    <div class="report-item">
                        <a href="{{ route('reports.create', $item->id) }}" class="btn btn-outline-danger btn-sm w-100">
                            <i class="fas fa-flag me-2"></i>Report this item
                        </a>
                    </div>
                @endif
            @endauth

            <!-- Related Items -->
            @if($related_items->count() > 0)
                <div class="related-items mt-4">
                    <h6>Related Items</h6>
                    <div class="related-list">
                        @foreach($related_items as $related)
                            <a href="{{ route('items.show', $related->id) }}" class="related-item">
                                <img src="{{ $related->first_photo }}" alt="{{ $related->title }}">
                                <div class="related-content">
                                    <h6>{{ Str::limit($related->title, 40) }}</h6>
                                    <p>{{ $related->location->city }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Item Header */
.item-header {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.item-status-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-lost {
    background: #ffc107;
    color: #212529;
}

.status-found {
    background: #28a745;
    color: white;
}

.item-title {
    font-size: 2rem;
    font-weight: 700;
    color: #212529;
    margin-bottom: 0.5rem;
}

.item-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6c757d;
    font-size: 0.9rem;
}

.meta-separator {
    color: #dee2e6;
}

/* Item Gallery */
.item-gallery {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.carousel-item img {
    height: 400px;
    object-fit: cover;
}

/* Content Sections */
.item-description,
.item-details,
.item-map,
.comments-section {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.item-description h5,
.item-details h5,
.item-map h5,
.comments-section h5 {
    font-weight: 600;
    margin-bottom: 1rem;
    color: #212529;
}

.description-content {
    line-height: 1.6;
    color: #495057;
}

/* Details Grid */
.details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.detail-label {
    font-size: 0.85rem;
    color: #6c757d;
    font-weight: 500;
}

.detail-value {
    font-weight: 600;
    color: #212529;
}

/* Map */
.map-container {
    height: 250px;
    border-radius: 8px;
    overflow: hidden;
}

/* Comments */
.add-comment {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
}

.comment-item {
    display: flex;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid #e9ecef;
}

.comment-item:last-child {
    border-bottom: none;
}

.comment-avatar img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.comment-content {
    flex: 1;
}

.comment-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.25rem;
}

.comment-author {
    font-weight: 600;
    color: #212529;
}

.comment-time {
    font-size: 0.8rem;
    color: #6c757d;
}

.comment-text {
    color: #495057;
    line-height: 1.5;
    margin-bottom: 0.5rem;
}

.comment-actions {
    display: flex;
    gap: 1rem;
}

/* Contact Card */
.contact-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.contact-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.contact-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
}

.contact-name {
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #212529;
}

.contact-joined {
    font-size: 0.85rem;
    color: #6c757d;
    margin-bottom: 0;
}

/* Safety Tips */
.safety-tips {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.safety-tips h6 {
    margin-bottom: 1rem;
    color: #212529;
}

.tips-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.tips-list li {
    padding: 0.5rem 0;
    color: #495057;
    font-size: 0.9rem;
    position: relative;
    padding-left: 1.5rem;
}

.tips-list li::before {
    content: "✓";
    position: absolute;
    left: 0;
    color: #28a745;
    font-weight: bold;
}

/* Report Item */
.report-item {
    background: white;
    border-radius: 12px;
    padding: 1rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Related Items */
.related-items {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.related-items h6 {
    margin-bottom: 1rem;
    color: #212529;
    font-weight: 600;
}

.related-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.related-item {
    display: flex;
    gap: 1rem;
    text-decoration: none;
    color: inherit;
    padding: 0.75rem;
    border-radius: 8px;
    transition: background-color 0.2s;
}

.related-item:hover {
    background-color: #f8f9fa;
    text-decoration: none;
    color: inherit;
}

.related-item img {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    object-fit: cover;
}

.related-content h6 {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #212529;
}

.related-content p {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .item-title {
        font-size: 1.5rem;
    }
    
    .carousel-item img {
        height: 250px;
    }
    
    .details-grid {
        grid-template-columns: 1fr;
    }
    
    .contact-header {
        flex-direction: column;
        text-align: center;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Initialize map
let map, marker;

function initMap() {
    const lat = {{ $item->location->latitude }};
    const lng = {{ $item->location->longitude }};
    
    map = L.map('itemMap').setView([lat, lng], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    marker = L.marker([lat, lng]).addTo(map);
    marker.bindPopup(`
        <div class="map-popup">
            <h6>{{ $item->title }}</h6>
            <p>{{ $item->location->address }}</p>
        </div>
    `);
}

// Show contact info
function showContactInfo() {
    @auth
        alert('Contact info:\nEmail: {{ $item->user->email }}\nPhone: {{ $item->user->phone ?? 'Not provided' }}');
    @else
        window.location.href = '{{ route("login") }}';
    @endauth
}

// Edit comment functionality
function editComment(commentId) {
    // TODO: Implement inline comment editing
    console.log('Edit comment:', commentId);
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    initMap();
});
</script>
@endpush