@extends('layouts.app')

@section('title', 'Contact Us - Find & Found')
@section('description', 'Get in touch with Find & Found team. Send us your questions, feedback, or report issues.')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="contact-header mb-5">
        <div class="text-center">
            <h1 class="page-title">Get in Touch</h1>
            <p class="page-subtitle">We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
        </div>
    </div>

    <div class="row">
        <!-- Contact Form -->
        <div class="col-lg-8 mb-5">
            <div class="contact-form-section">
                <div class="section-header mb-4">
                    <h3><i class="fas fa-envelope me-2"></i>Send us a Message</h3>
                    <p class="text-muted">Fill out the form below and we'll get back to you within 24 hours.</p>
                </div>

                <form action="{{ route('contact.submit') }}" method="POST" class="contact-form">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required
                                   placeholder="Enter your full name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required
                                   placeholder="Enter your email address">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                        <select class="form-select @error('subject') is-invalid @enderror" 
                                id="subject" 
                                name="subject" 
                                required>
                            <option value="">Select a subject</option>
                            <option value="general" {{ old('subject') == 'general' ? 'selected' : '' }}>General Inquiry</option>
                            <option value="technical" {{ old('subject') == 'technical' ? 'selected' : '' }}>Technical Support</option>
                            <option value="feedback" {{ old('subject') == 'feedback' ? 'selected' : '' }}>Feedback & Suggestions</option>
                            <option value="report" {{ old('subject') == 'report' ? 'selected' : '' }}>Report an Issue</option>
                            <option value="partnership" {{ old('subject') == 'partnership' ? 'selected' : '' }}>Partnership</option>
                            <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('message') is-invalid @enderror" 
                                  id="message" 
                                  name="message" 
                                  rows="6" 
                                  required
                                  placeholder="Tell us how we can help you...">{{ old('message') }}</textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <span id="messageCount">0</span>/2000 characters
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="privacy" name="privacy" required>
                            <label class="form-check-label" for="privacy">
                                I agree to the <a href="{{ route('privacy') }}" target="_blank">Privacy Policy</a> and consent to my data being processed.
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane me-2"></i>Send Message
                    </button>
                </form>
            </div>
        </div>

        <!-- Contact Info Sidebar -->
        <div class="col-lg-4">
            <!-- Contact Information -->
            <div class="contact-info-card mb-4">
                <div class="contact-info-header">
                    <h4><i class="fas fa-info-circle me-2"></i>Contact Information</h4>
                </div>
                <div class="contact-info-list">
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h6>Email</h6>
                            <p><a href="mailto:support@findandfound.com">support@findandfound.com</a></p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-details">
                            <h6>Phone</h6>
                            <p><a href="tel:+6281234567890">+62 812-3456-7890</a></p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h6>Address</h6>
                            <p>Jl. Teknologi No. 123<br>Jakarta, Indonesia 12345</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-details">
                            <h6>Business Hours</h6>
                            <p>Monday - Friday: 9:00 AM - 6:00 PM<br>
                               Saturday: 9:00 AM - 1:00 PM<br>
                               Sunday: Closed</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Quick Links -->
            <div class="faq-links-card mb-4">
                <div class="faq-header">
                    <h4><i class="fas fa-question-circle me-2"></i>Quick Help</h4>
                </div>
                <div class="faq-list">
                    <a href="{{ route('faq') }}" class="faq-item">
                        <i class="fas fa-chevron-right me-2"></i>
                        Frequently Asked Questions
                    </a>
                    <a href="{{ route('about') }}" class="faq-item">
                        <i class="fas fa-chevron-right me-2"></i>
                        About Find & Found
                    </a>
                    <a href="{{ route('terms') }}" class="faq-item">
                        <i class="fas fa-chevron-right me-2"></i>
                        Terms & Conditions
                    </a>
                    <a href="{{ route('privacy') }}" class="faq-item">
                        <i class="fas fa-chevron-right me-2"></i>
                        Privacy Policy
                    </a>
                </div>
            </div>

            <!-- Social Media -->
            <div class="social-media-card">
                <div class="social-header">
                    <h4><i class="fas fa-share-alt me-2"></i>Follow Us</h4>
                </div>
                <div class="social-links">
                    <a href="#" class="social-link facebook">
                        <i class="fab fa-facebook-f"></i>
                        <span>Facebook</span>
                    </a>
                    <a href="#" class="social-link twitter">
                        <i class="fab fa-twitter"></i>
                        <span>Twitter</span>
                    </a>
                    <a href="#" class="social-link instagram">
                        <i class="fab fa-instagram"></i>
                        <span>Instagram</span>
                    </a>
                    <a href="#" class="social-link whatsapp">
                        <i class="fab fa-whatsapp"></i>
                        <span>WhatsApp</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Response Time Notice -->
    <div class="response-notice mt-5">
        <div class="notice-card">
            <div class="notice-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="notice-content">
                <h5>Response Time</h5>
                <p>We typically respond to all inquiries within 24 hours during business days. For urgent technical issues, please include "URGENT" in your subject line.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Page Header */
.contact-header {
    text-align: center;
    margin-bottom: 3rem;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #212529;
    margin-bottom: 1rem;
}

.page-subtitle {
    font-size: 1.2rem;
    color: #6c757d;
    max-width: 600px;
    margin: 0 auto;
}

/* Contact Form Section */
.contact-form-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.section-header h3 {
    color: #212529;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.contact-form .form-label {
    font-weight: 500;
    color: #495057;
    margin-bottom: 0.5rem;
}

.contact-form .form-control,
.contact-form .form-select {
    border-radius: 8px;
    border: 1px solid #dee2e6;
    padding: 0.75rem 1rem;
    transition: all 0.2s;
}

.contact-form .form-control:focus,
.contact-form .form-select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.1);
}

.contact-form textarea {
    resize: vertical;
    min-height: 120px;
}

.form-text {
    display: flex;
    justify-content: space-between;
    margin-top: 0.25rem;
}

/* Contact Info Cards */
.contact-info-card,
.faq-links-card,
.social-media-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.contact-info-header,
.faq-header,
.social-header {
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e9ecef;
}

.contact-info-header h4,
.faq-header h4,
.social-header h4 {
    color: #212529;
    font-weight: 600;
    margin-bottom: 0;
}

/* Contact Items */
.contact-info-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.contact-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.contact-icon {
    width: 40px;
    height: 40px;
    background: #2563eb;
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.contact-details h6 {
    font-weight: 600;
    color: #212529;
    margin-bottom: 0.25rem;
}

.contact-details p {
    color: #6c757d;
    margin-bottom: 0;
    line-height: 1.5;
}

.contact-details a {
    color: #2563eb;
    text-decoration: none;
}

.contact-details a:hover {
    text-decoration: underline;
}

/* FAQ Links */
.faq-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.faq-item {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 8px;
    text-decoration: none;
    color: #495057;
    transition: all 0.2s;
}

.faq-item:hover {
    background: #e9ecef;
    color: #2563eb;
    text-decoration: none;
    transform: translateX(4px);
}

/* Social Links */
.social-links {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.social-link {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem;
    border-radius: 8px;
    text-decoration: none;
    color: white;
    transition: all 0.2s;
}

.social-link:hover {
    text-decoration: none;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.social-link.facebook {
    background: #1877f2;
}

.social-link.twitter {
    background: #1da1f2;
}

.social-link.instagram {
    background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
}

.social-link.whatsapp {
    background: #25d366;
}

.social-link i {
    width: 20px;
    text-align: center;
}

/* Response Notice */
.response-notice {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.notice-card {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 2rem;
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border-radius: 12px;
}

.notice-icon {
    width: 60px;
    height: 60px;
    background: #2563eb;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.notice-content h5 {
    color: #212529;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.notice-content p {
    color: #495057;
    margin-bottom: 0;
    line-height: 1.6;
}

/* Loading State */
.btn.loading {
    pointer-events: none;
}

.btn.loading i {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive */
@media (max-width: 768px) {
    .page-title {
        font-size: 2rem;
    }
    
    .contact-form-section {
        padding: 1.5rem;
    }
    
    .contact-info-card,
    .faq-links-card,
    .social-media-card {
        padding: 1rem;
    }
    
    .contact-item {
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }
    
    .notice-card {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
        padding: 1.5rem;
    }
}

@media (max-width: 576px) {
    .page-title {
        font-size: 1.75rem;
    }
    
    .page-subtitle {
        font-size: 1rem;
    }
    
    .contact-form-section {
        padding: 1rem;
    }
    
    .social-links {
        gap: 0.5rem;
    }
    
    .social-link {
        padding: 0.5rem;
        font-size: 0.9rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const messageTextarea = document.getElementById('message');
    const messageCount = document.getElementById('messageCount');
    const contactForm = document.querySelector('.contact-form');
    const submitBtn = contactForm.querySelector('button[type="submit"]');
    
    // Character counter
    messageTextarea.addEventListener('input', function() {
        const currentLength = this.value.length;
        const maxLength = 2000;
        
        messageCount.textContent = currentLength;
        
        if (currentLength > maxLength) {
            messageCount.style.color = '#dc3545';
            messageCount.parentElement.classList.add('text-danger');
        } else if (currentLength > maxLength * 0.9) {
            messageCount.style.color = '#ffc107';
            messageCount.parentElement.classList.remove('text-danger');
        } else {
            messageCount.style.color = '#6c757d';
            messageCount.parentElement.classList.remove('text-danger');
        }
    });
    
    // Form submission
    contactForm.addEventListener('submit', function(e) {
        // Show loading state
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;
        
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
        
        // If there's an error, restore the button (this would be handled by backend validation)
        setTimeout(() => {
            if (document.querySelector('.is-invalid')) {
                submitBtn.classList.remove('loading');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        }, 100);
    });
    
    // Auto-fill user data if logged in
    @auth
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        
        if (!nameInput.value) {
            nameInput.value = '{{ auth()->user()->name }}';
        }
        
        if (!emailInput.value) {
            emailInput.value = '{{ auth()->user()->email }}';
        }
    @endauth
    
    // Subject change handler
    const subjectSelect = document.getElementById('subject');
    const messageTextarea = document.getElementById('message');
    
    subjectSelect.addEventListener('change', function() {
        if (!messageTextarea.value) {
            const templates = {
                'technical': 'I am experiencing a technical issue with...',
                'feedback': 'I would like to provide feedback about...',
                'report': 'I would like to report an issue regarding...',
                'partnership': 'I am interested in discussing a partnership opportunity...',
                'general': 'I have a question about...'
            };
            
            if (templates[this.value]) {
                messageTextarea.value = templates[this.value];
                messageTextarea.focus();
                messageTextarea.setSelectionRange(messageTextarea.value.length, messageTextarea.value.length);
            }
        }
    });
});

// Smooth scroll to form if there are validation errors
@if($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('.contact-form-section').scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    });
@endif
</script>
@endpush