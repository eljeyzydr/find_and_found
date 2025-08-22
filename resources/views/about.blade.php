@extends('layouts.app')

@section('title', 'About Us - Find & Found')

@section('content')
<!-- Hero Section -->
<section class="about-hero py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="hero-title">About Find & Found</h1>
                <p class="hero-subtitle">Connecting communities to reunite lost items with their owners through technology and compassion.</p>
            </div>
            <div class="col-lg-6">
                <div class="hero-stats">
                    <div class="stat-card">
                        <div class="stat-number">{{ number_format($stats['total_items']) }}</div>
                        <div class="stat-label">Items Reported</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ number_format($stats['resolved_items']) }}</div>
                        <div class="stat-label">Successfully Reunited</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ number_format($stats['active_users']) }}</div>
                        <div class="stat-label">Active Users</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ number_format($stats['cities_covered']) }}</div>
                        <div class="stat-label">Cities Covered</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission Section -->
<section class="mission-section py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title">Our Mission</h2>
                <p class="section-description">
                    Find & Found was created with a simple belief: every lost item has a story, and every person who finds something has the power to make someone's day. We're building a community-driven platform that makes it easier than ever to reunite lost belongings with their rightful owners.
                </p>
            </div>
        </div>
        
        <div class="row mt-5">
            <div class="col-md-4 mb-4">
                <div class="mission-card">
                    <div class="mission-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h4>Community First</h4>
                    <p>We believe in the power of community and the kindness of strangers. Our platform is built to foster trust and connection between people.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="mission-card">
                    <div class="mission-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4>Safe & Secure</h4>
                    <p>Privacy and safety are our top priorities. We provide secure ways for people to connect without compromising personal information.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="mission-card">
                    <div class="mission-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h4>Easy to Use</h4>
                    <p>We've designed our platform to be intuitive and accessible for everyone, regardless of their technical expertise.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="how-it-works py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">How It Works</h2>
            <p class="section-description">Simple steps to help reunite lost items</p>
        </div>
        
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <div class="step-icon">
                        <i class="fas fa-plus"></i>
                    </div>
                    <h5>Report Item</h5>
                    <p>Post details about your lost item or something you found, including photos and location.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="step-card">
                    <div class="step-number">2</div>
                    <div class="step-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h5>Search & Browse</h5>
                    <p>Browse through listings or use our search filters to find items that match your description.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="step-card">
                    <div class="step-number">3</div>
                    <div class="step-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h5>Connect Safely</h5>
                    <p>Use our secure messaging system to communicate with potential matches and verify details.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="step-card">
                    <div class="step-number">4</div>
                    <div class="step-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h5>Reunite</h5>
                    <p>Arrange a safe meeting in a public place to return the item to its rightful owner.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Impact Section -->
<section class="impact-section py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="section-title text-white">Making a Real Impact</h2>
                <p class="section-description text-white-50">
                    Every successful reunion through our platform represents more than just a returned item—it's a moment of human connection, a restored sense of community, and proof that kindness still exists in our world.
                </p>
                <div class="impact-stats mt-4">
                    <div class="impact-stat">
                        <strong>{{ $stats['total_items'] > 0 ? round(($stats['resolved_items'] / $stats['total_items']) * 100) : 0 }}%</strong>
                        <span>Success Rate</span>
                    </div>
                    <div class="impact-stat">
                        <strong>24h</strong>
                        <span>Average Resolution Time</span>
                    </div>
                    <div class="impact-stat">
                        <strong>100%</strong>
                        <span>Free Service</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <p>"I lost my wedding ring at the park and thought it was gone forever. Thanks to Find & Found, a kind stranger found it and returned it to me within hours. This platform gives me hope in humanity!"</p>
                    </div>
                    <div class="testimonial-author">
                        <img src="{{ asset('images/testimonial-1.jpg') }}" alt="Sarah M." class="author-avatar">
                        <div>
                            <strong>Sarah M.</strong>
                            <span>Jakarta</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="team-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Our Values</h2>
            <p class="section-description">The principles that guide everything we do</p>
        </div>
        
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h4>Community</h4>
                    <p>We believe in the power of community and that together we can create positive change in the world.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h4>Trust</h4>
                    <p>Trust is the foundation of our platform. We work hard to create an environment where people feel safe to help each other.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h4>Innovation</h4>
                    <p>We continuously improve our platform with new features and technologies to make reuniting lost items easier.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section py-5 bg-light">
    <div class="container text-center">
        <h2 class="section-title">Join Our Community</h2>
        <p class="section-description">Be part of a movement that's making the world a little bit kinder, one lost item at a time.</p>
        <div class="cta-buttons mt-4">
            @guest
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg me-3">
                    <i class="fas fa-user-plus me-2"></i>Join Now
                </a>
                <a href="{{ route('items.index') }}" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-search me-2"></i>Browse Items
                </a>
            @else
                <a href="{{ route('items.create') }}" class="btn btn-primary btn-lg me-3">
                    <i class="fas fa-plus me-2"></i>Post an Item
                </a>
                <a href="{{ route('items.index') }}" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-search me-2"></i>Help Others Find
                </a>
            @endguest
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
/* Hero Section */
.about-hero {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

.hero-title {
    font-size: 3rem;
    font-weight: 700;
    color: #212529;
    margin-bottom: 1rem;
}

.hero-subtitle {
    font-size: 1.25rem;
    color: #6c757d;
    line-height: 1.6;
}

.hero-stats {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #2563eb;
    display: block;
}

.stat-label {
    color: #6c757d;
    font-size: 0.9rem;
    margin-top: 0.25rem;
}

/* Sections */
.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #212529;
    margin-bottom: 1rem;
}

.section-description {
    font-size: 1.1rem;
    color: #6c757d;
    line-height: 1.6;
    max-width: 600px;
    margin: 0 auto;
}

/* Mission Section */
.mission-card {
    text-align: center;
    padding: 2rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    height: 100%;
}

.mission-icon {
    width: 80px;
    height: 80px;
    background: #2563eb;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    margin: 0 auto 1.5rem;
}

.mission-card h4 {
    color: #212529;
    margin-bottom: 1rem;
}

.mission-card p {
    color: #6c757d;
    line-height: 1.6;
}

/* How It Works */
.step-card {
    text-align: center;
    padding: 2rem 1rem;
    position: relative;
}

.step-number {
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 40px;
    height: 40px;
    background: #2563eb;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.2rem;
}

.step-icon {
    width: 80px;
    height: 80px;
    background: #f8f9fa;
    border: 3px solid #2563eb;
    color: #2563eb;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin: 2rem auto 1.5rem;
}

.step-card h5 {
    color: #212529;
    margin-bottom: 1rem;
}

.step-card p {
    color: #6c757d;
    line-height: 1.6;
}

/* Impact Section */
.impact-stats {
    display: flex;
    gap: 2rem;
}

.impact-stat {
    text-align: center;
}

.impact-stat strong {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    color: white;
}

.impact-stat span {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
}

.testimonial-card {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 2rem;
    backdrop-filter: blur(10px);
}

.testimonial-content p {
    font-style: italic;
    margin-bottom: 1.5rem;
    color: white;
    font-size: 1.1rem;
    line-height: 1.6;
}

.testimonial-author {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.author-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.testimonial-author strong {
    display: block;
    color: white;
}

.testimonial-author span {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
}

/* Values Section */
.value-card {
    text-align: center;
    padding: 2rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    height: 100%;
    transition: transform 0.2s;
}

.value-card:hover {
    transform: translateY(-5px);
}

.value-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin: 0 auto 1.5rem;
}

.value-card h4 {
    color: #212529;
    margin-bottom: 1rem;
}

.value-card p {
    color: #6c757d;
    line-height: 1.6;
}

/* CTA Section */
.cta-buttons {
    display: flex;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .hero-stats {
        grid-template-columns: 1fr;
        gap: 0.75rem;
        margin-top: 2rem;
    }
    
    .impact-stats {
        flex-direction: column;
        gap: 1rem;
    }
    
    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .cta-buttons .btn {
        width: 100%;
        max-width: 300px;
    }
    
    .testimonial-author {
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;
    }
}

@media (max-width: 576px) {
    .mission-card,
    .value-card {
        padding: 1.5rem;
    }
    
    .step-card {
        padding: 1.5rem 0.5rem;
    }
    
    .testimonial-card {
        padding: 1.5rem;
    }
}
</style>
@endpush