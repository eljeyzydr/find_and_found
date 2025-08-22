@extends('layouts.app')

@section('title', 'Privacy Policy - Find & Found')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="text-center mb-5">
        <h1 class="page-title">Privacy Policy</h1>
        <p class="page-subtitle">Last updated: {{ date('F d, Y') }}</p>
        <p class="privacy-intro">Your privacy is important to us. This Privacy Policy explains how Find & Found collects, uses, and protects your personal information.</p>
    </div>

    <!-- Quick Summary -->
    <div class="privacy-summary mb-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="summary-card">
                    <h3><i class="fas fa-info-circle me-2"></i>Quick Summary</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="summary-item">
                                <i class="fas fa-check text-success"></i>
                                <span>We only collect necessary information</span>
                            </div>
                            <div class="summary-item">
                                <i class="fas fa-check text-success"></i>
                                <span>Your data is encrypted and secure</span>
                            </div>
                            <div class="summary-item">
                                <i class="fas fa-check text-success"></i>
                                <span>You control your privacy settings</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="summary-item">
                                <i class="fas fa-times text-danger"></i>
                                <span>We never sell your personal data</span>
                            </div>
                            <div class="summary-item">
                                <i class="fas fa-times text-danger"></i>
                                <span>No tracking for advertising purposes</span>
                            </div>
                            <div class="summary-item">
                                <i class="fas fa-times text-danger"></i>
                                <span>No spam or unwanted communications</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-lg-3">
            <div class="privacy-nav">
                <h5>Contents</h5>
                <nav class="nav-links">
                    <a href="#information-we-collect" class="nav-link">Information We Collect</a>
                    <a href="#how-we-use" class="nav-link">How We Use Information</a>
                    <a href="#information-sharing" class="nav-link">Information Sharing</a>
                    <a href="#data-security" class="nav-link">Data Security</a>
                    <a href="#your-rights" class="nav-link">Your Rights</a>
                    <a href="#cookies" class="nav-link">Cookies & Tracking</a>
                    <a href="#data-retention" class="nav-link">Data Retention</a>
                    <a href="#children" class="nav-link">Children's Privacy</a>
                    <a href="#international" class="nav-link">International Transfers</a>
                    <a href="#changes" class="nav-link">Policy Changes</a>
                    <a href="#contact-privacy" class="nav-link">Contact Us</a>
                </nav>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="privacy-content">
                <!-- Information We Collect -->
                <section id="information-we-collect" class="privacy-section">
                    <h2>1. Information We Collect</h2>
                    
                    <h4>1.1 Information You Provide</h4>
                    <div class="info-grid">
                        <div class="info-card">
                            <h5><i class="fas fa-user"></i> Account Information</h5>
                            <ul>
                                <li>Name and email address</li>
                                <li>Phone number (optional)</li>
                                <li>Profile photo (optional)</li>
                                <li>Password (encrypted)</li>
                            </ul>
                        </div>
                        <div class="info-card">
                            <h5><i class="fas fa-box"></i> Item Information</h5>
                            <ul>
                                <li>Item descriptions and photos</li>
                                <li>Location data (where item was lost/found)</li>
                                <li>Date and time information</li>
                                <li>Category and tags</li>
                            </ul>
                        </div>
                        <div class="info-card">
                            <h5><i class="fas fa-comments"></i> Communication Data</h5>
                            <ul>
                                <li>Messages between users</li>
                                <li>Comments on posts</li>
                                <li>Support communications</li>
                                <li>Feedback and reports</li>
                            </ul>
                        </div>
                    </div>

                    <h4>1.2 Information We Collect Automatically</h4>
                    <ul>
                        <li><strong>Usage Data:</strong> How you interact with our platform, pages visited, features used</li>
                        <li><strong>Device Information:</strong> Browser type, operating system, device identifiers</li>
                        <li><strong>Location Data:</strong> Approximate location based on IP address (for relevant search results)</li>
                        <li><strong>Log Data:</strong> Access times, error logs, performance data</li>
                    </ul>

                    <h4>1.3 Information from Third Parties</h4>
                    <p>We may receive information from:</p>
                    <ul>
                        <li>Social media platforms (if you choose to connect accounts)</li>
                        <li>Payment processors (for premium features, if applicable)</li>
                        <li>Analytics and security services</li>
                        <li>Public databases for verification purposes</li>
                    </ul>
                </section>

                <!-- How We Use Information -->
                <section id="how-we-use" class="privacy-section">
                    <h2>2. How We Use Your Information</h2>
                    
                    <div class="usage-grid">
                        <div class="usage-card primary">
                            <h5><i class="fas fa-cog"></i> Service Operation</h5>
                            <ul>
                                <li>Create and manage your account</li>
                                <li>Process and display your posts</li>
                                <li>Facilitate communication between users</li>
                                <li>Provide search and matching functionality</li>
                                <li>Send notifications about relevant items</li>
                            </ul>
                        </div>
                        
                        <div class="usage-card secondary">
                            <h5><i class="fas fa-chart-line"></i> Improvement & Analytics</h5>
                            <ul>
                                <li>Analyze usage patterns to improve features</li>
                                <li>Conduct research and development</li>
                                <li>Monitor platform performance</li>
                                <li>Generate anonymized statistics</li>
                            </ul>
                        </div>
                        
                        <div class="usage-card accent">
                            <h5><i class="fas fa-shield-alt"></i> Safety & Security</h5>
                            <ul>
                                <li>Detect and prevent fraud or abuse</li>
                                <li>Verify user identity when necessary</li>
                                <li>Investigate reports and violations</li>
                                <li>Comply with legal obligations</li>
                            </ul>
                        </div>
                        
                        <div class="usage-card info">
                            <h5><i class="fas fa-envelope"></i> Communication</h5>
                            <ul>
                                <li>Send important service updates</li>
                                <li>Respond to support requests</li>
                                <li>Notify about policy changes</li>
                                <li>Send security alerts</li>
                            </ul>
                        </div>
                    </div>

                    <div class="legal-basis">
                        <h4>Legal Basis for Processing</h4>
                        <p>We process your personal data based on:</p>
                        <ul>
                            <li><strong>Contract:</strong> To provide the services you've requested</li>
                            <li><strong>Legitimate Interest:</strong> To improve our platform and ensure security</li>
                            <li><strong>Consent:</strong> For marketing communications and optional features</li>
                            <li><strong>Legal Obligation:</strong> To comply with applicable laws and regulations</li>
                        </ul>
                    </div>
                </section>

                <!-- Information Sharing -->
                <section id="information-sharing" class="privacy-section">
                    <h2>3. Information Sharing and Disclosure</h2>
                    
                    <div class="sharing-policy">
                        <div class="policy-highlight">
                            <h4><i class="fas fa-shield-check text-success"></i> We DO NOT sell your personal data</h4>
                            <p>Your personal information is not for sale. We only share data in limited circumstances outlined below.</p>
                        </div>
                    </div>

                    <h4>3.1 When We Share Information</h4>
                    <div class="sharing-scenarios">
                        <div class="scenario-card">
                            <h5><i class="fas fa-users"></i> With Other Users</h5>
                            <p>Information visible to other users:</p>
                            <ul>
                                <li>Your name and profile photo</li>
                                <li>Items you've posted (as per your settings)</li>
                                <li>Comments you make publicly</li>
                                <li>Basic activity indicators (member since, items posted)</li>
                            </ul>
                            <p><strong>Note:</strong> Contact information is only shared with your explicit consent.</p>
                        </div>

                        <div class="scenario-card">
                            <h5><i class="fas fa-handshake"></i> With Service Providers</h5>
                            <p>We work with trusted third parties who help us operate our platform:</p>
                            <ul>
                                <li>Cloud hosting and storage providers</li>
                                <li>Email and notification services</li>
                                <li>Analytics and monitoring tools</li>
                                <li>Payment processors (if applicable)</li>
                            </ul>
                            <p>These providers are bound by strict confidentiality agreements.</p>
                        </div>

                        <div class="scenario-card">
                            <h5><i class="fas fa-balance-scale"></i> Legal Requirements</h5>
                            <p>We may disclose information when required by:</p>
                            <ul>
                                <li>Valid legal process or court orders</li>
                                <li>Government investigations</li>
                                <li>Protection of our rights or property</li>
                                <li>Prevention of fraud or illegal activity</li>
                                <li>Protection of user safety</li>
                            </ul>
                        </div>

                        <div class="scenario-card">
                            <h5><i class="fas fa-random"></i> Business Transfers</h5>
                            <p>In the event of a merger, acquisition, or sale of our company, user information may be transferred as part of the transaction. We will notify users beforehand and ensure the same privacy protections apply.</p>
                        </div>
                    </div>
                </section>

                <!-- Data Security -->
                <section id="data-security" class="privacy-section">
                    <h2>4. Data Security</h2>
                    
                    <div class="security-measures">
                        <div class="security-grid">
                            <div class="security-card">
                                <i class="fas fa-lock"></i>
                                <h5>Encryption</h5>
                                <p>All data is encrypted in transit and at rest using industry-standard encryption protocols.</p>
                            </div>
                            
                            <div class="security-card">
                                <i class="fas fa-server"></i>
                                <h5>Secure Infrastructure</h5>
                                <p>Our servers are hosted in secure, certified data centers with 24/7 monitoring.</p>
                            </div>
                            
                            <div class="security-card">
                                <i class="fas fa-user-shield"></i>
                                <h5>Access Controls</h5>
                                <p>Strict access controls ensure only authorized personnel can access user data.</p>
                            </div>
                            
                            <div class="security-card">
                                <i class="fas fa-eye"></i>
                                <h5>Regular Audits</h5>
                                <p>We conduct regular security audits and vulnerability assessments.</p>
                            </div>
                            
                            <div class="security-card">
                                <i class="fas fa-backup"></i>
                                <h5>Data Backups</h5>
                                <p>Regular encrypted backups ensure data integrity and availability.</p>
                            </div>
                            
                            <div class="security-card">
                                <i class="fas fa-exclamation-triangle"></i>
                                <h5>Incident Response</h5>
                                <p>We have procedures in place to quickly respond to any security incidents.</p>
                            </div>
                        </div>
                    </div>

                    <div class="security-note">
                        <p><strong>Important:</strong> While we implement strong security measures, no system is 100% secure. We encourage users to use strong, unique passwords and report any suspicious activity immediately.</p>
                    </div>
                </section>

                <!-- Your Rights -->
                <section id="your-rights" class="privacy-section">
                    <h2>5. Your Privacy Rights</h2>
                    
                    <div class="rights-overview">
                        <p>You have several rights regarding your personal data. Here's how to exercise them:</p>
                    </div>

                    <div class="rights-grid">
                        <div class="right-card">
                            <h5><i class="fas fa-eye"></i> Right to Access</h5>
                            <p>Request a copy of all personal data we hold about you.</p>
                            <button class="btn btn-outline-primary btn-sm">Request Data Export</button>
                        </div>
                        
                        <div class="right-card">
                            <h5><i class="fas fa-edit"></i> Right to Rectification</h5>
                            <p>Correct any inaccurate or incomplete personal data.</p>
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm">Update Profile</a>
                        </div>
                        
                        <div class="right-card">
                            <h5><i class="fas fa-trash"></i> Right to Erasure</h5>
                            <p>Request deletion of your personal data under certain circumstances.</p>
                            <button class="btn btn-outline-danger btn-sm">Delete Account</button>
                        </div>
                        
                        <div class="right-card">
                            <h5><i class="fas fa-ban"></i> Right to Restrict Processing</h5>
                            <p>Limit how we process your data in specific situations.</p>
                            <a href="{{ route('contact') }}" class="btn btn-outline-secondary btn-sm">Contact Support</a>
                        </div>
                        
                        <div class="right-card">
                            <h5><i class="fas fa-download"></i> Right to Data Portability</h5>
                            <p>Receive your data in a machine-readable format.</p>
                            <button class="btn btn-outline-info btn-sm">Export Data</button>
                        </div>
                        
                        <div class="right-card">
                            <h5><i class="fas fa-times-circle"></i> Right to Object</h5>
                            <p>Object to processing based on legitimate interests.</p>
                            <button class="btn btn-outline-warning btn-sm">Manage Preferences</button>
                        </div>
                    </div>

                    <div class="rights-note">
                        <h4>How to Exercise Your Rights</h4>
                        <ul>
                            <li>Many rights can be exercised directly through your account settings</li>
                            <li>For complex requests, contact our privacy team at privacy@findandfound.com</li>
                            <li>We will respond to requests within 30 days</li>
                            <li>Some requests may require identity verification</li>
                        </ul>
                    </div>
                </section>

                <!-- Cookies & Tracking -->
                <section id="cookies" class="privacy-section">
                    <h2>6. Cookies and Tracking Technologies</h2>
                    
                    <div class="cookies-explanation">
                        <p>We use cookies and similar technologies to improve your experience and understand how our platform is used.</p>
                    </div>

                    <div class="cookie-types">
                        <div class="cookie-category">
                            <h4><i class="fas fa-cog text-primary"></i> Essential Cookies</h4>
                            <p>Required for basic functionality like login sessions and security features.</p>
                            <span class="badge bg-success">Always Active</span>
                        </div>
                        
                        <div class="cookie-category">
                            <h4><i class="fas fa-chart-bar text-info"></i> Analytics Cookies</h4>
                            <p>Help us understand how users interact with our platform to improve features.</p>
                            <div class="cookie-control">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" id="analyticsCookies">
                                    Allow Analytics Cookies
                                </label>
                            </div>
                        </div>
                        
                        <div class="cookie-category">
                            <h4><i class="fas fa-sliders-h text-warning"></i> Preference Cookies</h4>
                            <p>Remember your settings and preferences for a personalized experience.</p>
                            <div class="cookie-control">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" id="preferenceCookies">
                                    Allow Preference Cookies
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="cookie-management">
                        <h4>Managing Cookies</h4>
                        <p>You can control cookies through:</p>
                        <ul>
                            <li>Your browser settings (to block or delete cookies)</li>
                            <li>Our cookie preference center (link in footer)</li>
                            <li>Third-party opt-out tools for analytics services</li>
                        </ul>
                        <button class="btn btn-primary">Manage Cookie Preferences</button>
                    </div>
                </section>

                <!-- Data Retention -->
                <section id="data-retention" class="privacy-section">
                    <h2>7. Data Retention</h2>
                    
                    <div class="retention-policy">
                        <p>We retain your personal data only as long as necessary for the purposes outlined in this policy:</p>
                        
                        <div class="retention-table">
                            <div class="retention-row">
                                <div class="data-type">Account Information</div>
                                <div class="retention-period">Until account deletion + 30 days</div>
                            </div>
                            <div class="retention-row">
                                <div class="data-type">Posted Items</div>
                                <div class="retention-period">Until manually deleted or account closure</div>
                            </div>
                            <div class="retention-row">
                                <div class="data-type">Messages & Comments</div>
                                <div class="retention-period">Until deleted by user or account closure</div>
                            </div>
                            <div class="retention-row">
                                <div class="data-type">Usage Logs</div>
                                <div class="retention-period">12 months for analytics, 24 months for security</div>
                            </div>
                            <div class="retention-row">
                                <div class="data-type">Support Communications</div>
                                <div class="retention-period">3 years for quality and legal purposes</div>
                            </div>
                        </div>
                    </div>

                    <div class="retention-note">
                        <p><strong>Note:</strong> Some data may be retained longer if required by law or for legitimate business purposes (e.g., fraud prevention, legal disputes).</p>
                    </div>
                </section>

                <!-- Children's Privacy -->
                <section id="children" class="privacy-section">
                    <h2>8. Children's Privacy</h2>
                    
                    <div class="children-policy">
                        <div class="age-restriction">
                            <h4><i class="fas fa-child text-warning"></i> Age Requirement</h4>
                            <p>Our platform is intended for users 17 years and older. We do not knowingly collect personal information from children under 17.</p>
                        </div>
                        
                        <div class="parental-notice">
                            <h4>If You're a Parent or Guardian</h4>
                            <p>If you believe your child has provided us with personal information:</p>
                            <ul>
                                <li>Contact us immediately at privacy@findandfound.com</li>
                                <li>We will promptly delete the account and associated data</li>
                                <li>We may request verification of your parental status</li>
                            </ul>
                        </div>
                    </div>
                </section>

                <!-- International Transfers -->
                <section id="international" class="privacy-section">
                    <h2>9. International Data Transfers</h2>
                    
                    <div class="transfer-info">
                        <p>Our primary servers are located in Indonesia. However, some service providers may process data in other countries:</p>
                        
                        <div class="transfer-safeguards">
                            <h4>Data Protection Safeguards</h4>
                            <ul>
                                <li>All transfers comply with applicable data protection laws</li>
                                <li>We use Standard Contractual Clauses where required</li>
                                <li>Service providers must meet equivalent privacy standards</li>
                                <li>Data is encrypted during transfer and storage</li>
                            </ul>
                        </div>
                    </div>
                </section>

                <!-- Policy Changes -->
                <section id="changes" class="privacy-section">
                    <h2>10. Changes to This Privacy Policy</h2>
                    
                    <div class="changes-policy">
                        <p>We may update this Privacy Policy to reflect changes in our practices or legal requirements:</p>
                        
                        <div class="notification-process">
                            <h4>How We'll Notify You</h4>
                            <ul>
                                <li><strong>Email notification</strong> for significant changes</li>
                                <li><strong>In-app notification</strong> when you next visit</li>
                                <li><strong>Updated date</strong> at the top of this policy</li>
                                <li><strong>Summary of changes</strong> in plain language</li>
                            </ul>
                        </div>
                        
                        <div class="version-history">
                            <h4>Policy Version History</h4>
                            <div class="version-item">
                                <strong>Current Version:</strong> v2.0 ({{ date('F d, Y') }})
                                <br><em>Updated cookie policy and added user rights section</em>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Contact -->
                <section id="contact-privacy" class="privacy-section">
                    <h2>11. Contact Our Privacy Team</h2>
                    
                    <div class="contact-privacy-info">
                        <div class="contact-grid">
                            <div class="contact-method">
                                <h4><i class="fas fa-envelope"></i> Email</h4>
                                <p>privacy@findandfound.com</p>
                                <small>Response time: 1-2 business days</small>
                            </div>
                            
                            <div class="contact-method">
                                <h4><i class="fas fa-phone"></i> Phone</h4>
                                <p>+62-21-1234-5678 ext. 101</p>
                                <small>Mon-Fri, 9 AM - 5 PM WIB</small>
                            </div>
                            
                            <div class="contact-method">
                                <h4><i class="fas fa-map-marker-alt"></i> Mailing Address</h4>
                                <p>
                                    Privacy Officer<br>
                                    Find & Found<br>
                                    Jl. Teknologi No. 123<br>
                                    Jakarta 12345, Indonesia
                                </p>
                            </div>
                        </div>
                        
                        <div class="dpo-info">
                            <h4>Data Protection Officer</h4>
                            <p>For complex privacy matters, you can contact our Data Protection Officer directly at dpo@findandfound.com</p>
                        </div>
                    </div>
                </section>

                <!-- Footer -->
                <div class="privacy-footer">
                    <div class="footer-summary">
                        <h4>Remember</h4>
                        <ul>
                            <li>You control your privacy settings</li>
                            <li>We're committed to protecting your data</li>
                            <li>Contact us anytime with privacy questions</li>
                            <li>Review this policy periodically for updates</li>
                        </ul>
                    </div>
                    
                    <div class="legal-footer">
                        <p class="text-muted">
                            <small>
                                This Privacy Policy is governed by Indonesian data protection laws. 
                                For users in the European Union, additional GDPR protections apply.
                                <br><br>
                                <strong>Effective Date:</strong> {{ date('F d, Y') }} | 
                                <strong>Policy Version:</strong> 2.0
                            </small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Page Header */
.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #212529;
    margin-bottom: 0.5rem;
}

.page-subtitle {
    color: #6c757d;
    font-size: 1.1rem;
    margin-bottom: 1rem;
}

.privacy-intro {
    font-size: 1.1rem;
    color: #495057;
    max-width: 600px;
    margin: 0 auto;
}

/* Quick Summary */
.privacy-summary {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
    border-radius: 12px;
    padding: 2px;
    overflow: hidden;
}

.summary-card {
    background: white;
    border-radius: 10px;
    padding: 2rem;
}

.summary-card h3 {
    color: #212529;
    margin-bottom: 1.5rem;
    text-align: center;
}

.summary-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
    font-weight: 500;
}

.summary-item i {
    font-size: 1.1rem;
}

/* Navigation */
.privacy-nav {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 2rem;
}

.privacy-nav h5 {
    color: #212529;
    font-weight: 600;
    margin-bottom: 1rem;
}

.nav-links {
    display: flex;
    flex-direction: column;
}

.nav-link {
    color: #6c757d;
    text-decoration: none;
    padding: 0.5rem 0;
    border-left: 3px solid transparent;
    padding-left: 1rem;
    margin-left: -1rem;
    transition: all 0.2s;
    font-size: 0.9rem;
}

.nav-link:hover,
.nav-link.active {
    color: #2563eb;
    border-left-color: #2563eb;
    background: rgba(37, 99, 235, 0.05);
    text-decoration: none;
}

/* Main Content */
.privacy-content {
    background: white;
    border-radius: 12px;
    padding: 2.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.privacy-section {
    margin-bottom: 3rem;
    scroll-margin-top: 2rem;
}

.privacy-section h2 {
    color: #212529;
    font-weight: 600;
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e9ecef;
}

.privacy-section h4 {
    color: #495057;
    font-weight: 600;
    margin: 1.5rem 0 1rem 0;
}

.privacy-section p {
    color: #495057;
    line-height: 1.7;
    margin-bottom: 1rem;
}

.privacy-section ul {
    color: #495057;
    line-height: 1.6;
    margin-bottom: 1rem;
    padding-left: 1.5rem;
}

.privacy-section li {
    margin-bottom: 0.5rem;
}

/* Info Cards */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin: 1.5rem 0;
}

.info-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1.5rem;
    border-left: 4px solid #2563eb;
}

.info-card h5 {
    color: #212529;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-card i {
    color: #2563eb;
}

/* Usage Grid */
.usage-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin: 2rem 0;
}

.usage-card {
    border-radius: 8px;
    padding: 1.5rem;
    color: white;
}

.usage-card.primary {
    background: linear-gradient(135deg, #2563eb, #1e40af);
}

.usage-card.secondary {
    background: linear-gradient(135deg, #10b981, #059669);
}

.usage-card.accent {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.usage-card.info {
    background: linear-gradient(135deg, #6366f1, #4f46e5);
}

.usage-card h5 {
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Legal Basis */
.legal-basis {
    background: #e0f2fe;
    border-radius: 8px;
    padding: 1.5rem;
    margin-top: 2rem;
    border-left: 4px solid #0891b2;
}

/* Sharing Policy */
.policy-highlight {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    text-align: center;
}

.policy-highlight h4 {
    color: #15803d;
    margin-bottom: 0.5rem;
}

.sharing-scenarios {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin: 2rem 0;
}

.scenario-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1.5rem;
    border-top: 4px solid #2563eb;
}

.scenario-card h5 {
    color: #212529;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Security Measures */
.security-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin: 2rem 0;
}

.security-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1.5rem;
    text-align: center;
    border: 2px solid #e9ecef;
    transition: all 0.2s;
}

.security-card:hover {
    border-color: #2563eb;
    transform: translateY(-2px);
}

.security-card i {
    font-size: 2rem;
    color: #2563eb;
    margin-bottom: 1rem;
}

.security-card h5 {
    color: #212529;
    margin-bottom: 0.5rem;
}

.security-note {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 8px;
    padding: 1rem;
    margin-top: 1.5rem;
}

/* Rights Grid */
.rights-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin: 2rem 0;
}

.right-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1.5rem;
    text-align: center;
    border: 1px solid #e9ecef;
}

.right-card h5 {
    color: #212529;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.right-card i {
    color: #2563eb;
}

.rights-note {
    background: #e3f2fd;
    border-radius: 8px;
    padding: 1.5rem;
    margin-top: 2rem;
}

/* Cookie Management */
.cookie-types {
    margin: 2rem 0;
}

.cookie-category {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    border-left: 4px solid #2563eb;
}

.cookie-category h4 {
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.cookie-control {
    margin-top: 1rem;
}

.cookie-management {
    background: #e0f2fe;
    border-radius: 8px;
    padding: 1.5rem;
    margin-top: 2rem;
}

/* Data Retention */
.retention-table {
    background: #f8f9fa;
    border-radius: 8px;
    overflow: hidden;
    margin: 1.5rem 0;
}

.retention-row {
    display: grid;
    grid-template-columns: 2fr 3fr;
    border-bottom: 1px solid #e9ecef;
}

.retention-row:last-child {
    border-bottom: none;
}

.retention-row div {
    padding: 1rem 1.5rem;
}

.data-type {
    background: #e9ecef;
    font-weight: 600;
    color: #495057;
}

.retention-period {
    background: white;
    color: #212529;
}

.retention-note {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 8px;
    padding: 1rem;
    margin-top: 1rem;
}

/* Children's Policy */
.children-policy {
    margin: 2rem 0;
}

.age-restriction {
    background: #fef3c7;
    border: 1px solid #fbbf24;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.parental-notice {
    background: #f0f9ff;
    border: 1px solid #7dd3fc;
    border-radius: 8px;
    padding: 1.5rem;
}

/* Transfer Info */
.transfer-safeguards {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 8px;
    padding: 1.5rem;
    margin-top: 1.5rem;
}

/* Changes Policy */
.notification-process,
.version-history {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1.5rem;
    margin: 1.5rem 0;
}

.version-item {
    padding: 1rem;
    background: white;
    border-radius: 6px;
    border-left: 3px solid #2563eb;
}

/* Contact Grid */
.contact-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin: 2rem 0;
}

.contact-method {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1.5rem;
    text-align: center;
}

.contact-method h4 {
    color: #2563eb;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.dpo-info {
    background: #e0f2fe;
    border-radius: 8px;
    padding: 1.5rem;
    margin-top: 2rem;
}

/* Footer */
.privacy-footer {
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 1px solid #e9ecef;
}

.footer-summary {
    background: #f0fdf4;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.footer-summary h4 {
    color: #15803d;
    margin-bottom: 1rem;
}

.legal-footer {
    text-align: center;
}

/* Responsive */
@media (max-width: 992px) {
    .privacy-nav {
        position: static;
        margin-bottom: 2rem;
    }
    
    .nav-links {
        flex-direction: row;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .nav-link {
        border: 1px solid #dee2e6;
        border-radius: 20px;
        padding: 0.5rem 1rem;
        margin: 0;
        border-left: 1px solid #dee2e6;
        background: #f8f9fa;
        font-size: 0.8rem;
    }
    
    .nav-link:hover,
    .nav-link.active {
        background: #2563eb;
        color: white;
        border-color: #2563eb;
    }
    
    .info-grid,
    .usage-grid,
    .sharing-scenarios,
    .security-grid,
    .rights-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .retention-row {
        grid-template-columns: 1fr;
    }
    
    .data-type {
        background: #2563eb;
        color: white;
    }
}

@media (max-width: 768px) {
    .page-title {
        font-size: 2rem;
    }
    
    .privacy-content {
        padding: 1.5rem;
    }
    
    .summary-card {
        padding: 1.5rem;
    }
    
    .summary-item {
        font-size: 0.9rem;
    }
    
    .contact-grid {
        grid-template-columns: 1fr;
    }
}

/* Print Styles */
@media print {
    .privacy-nav,
    .btn,
    .cookie-control {
        display: none;
    }
    
    .privacy-content {
        box-shadow: none;
        padding: 0;
    }
    
    .privacy-section {
        break-inside: avoid;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for navigation links
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                
                // Update active link
                document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            }
        });
    });

    // Update active nav link on scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const id = entry.target.getAttribute('id');
                document.querySelectorAll('.nav-link').forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === `#${id}`) {
                        link.classList.add('active');
                    }
                });
            }
        });
    }, {
        rootMargin: '-20% 0px -35% 0px'
    });

    // Observe all sections
    document.querySelectorAll('.privacy-section').forEach(section => {
        observer.observe(section);
    });

    // Cookie preferences (mock functionality)
    document.getElementById('analyticsCookies')?.addEventListener('change', function() {
        console.log('Analytics cookies:', this.checked);
        // TODO: Implement actual cookie management
    });

    document.getElementById('preferenceCookies')?.addEventListener('change', function() {
        console.log('Preference cookies:', this.checked);
        // TODO: Implement actual cookie management
    });

    // Rights buttons (mock functionality)
    document.querySelectorAll('.right-card .btn').forEach(button => {
        button.addEventListener('click', function(e) {
            // Skip if it's an actual link
            if (this.tagName === 'A') return;
            
            e.preventDefault();
            const action = this.textContent.trim();
            alert(`This would ${action.toLowerCase()}. In a real implementation, this would redirect to the appropriate page or open a modal.`);
        });
    });

    // Cookie management button
    document.querySelector('.cookie-management .btn')?.addEventListener('click', function() {
        alert('This would open the cookie preference center. In a real implementation, this would show a detailed cookie management interface.');
    });
});
</script>
@endpush