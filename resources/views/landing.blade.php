<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.common.portal_name') }} - {{ __('messages.landing.hero_subtitle') }}</title>
    <link rel="stylesheet" href="/css/portal.css">
    <style>
        /* Landing Page Styles */
        body { background: #ffffff; }
        
        .landing-header {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            padding: 0 2rem;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
        
        .landing-logo {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .landing-nav {
            display: flex;
            gap: 12px;
            align-items: center;
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #005FB8 0%, #003d75 100%);
            color: white;
            padding: 5rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.4;
        }
        
        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        
        .hero h1 {
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 1rem;
            line-height: 1.1;
            letter-spacing: -1px;
        }
        
        .hero-subtitle {
            font-size: 20px;
            opacity: 0.95;
            margin-bottom: 2.5rem;
            line-height: 1.6;
            font-weight: 400;
        }
        
        .hero-cta {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-hero {
            padding: 14px 32px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-hero-primary {
            background: white;
            color: var(--primary);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
        }
        
        .btn-hero-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }
        
        .btn-hero-secondary {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }
        
        .btn-hero-secondary:hover {
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.5);
        }
        
        /* Trust Badges */
        .trust-badges {
            display: flex;
            justify-content: center;
            gap: 32px;
            margin-top: 3rem;
            flex-wrap: wrap;
        }
        
        .trust-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            opacity: 0.9;
        }
        
        .trust-badge svg {
            width: 20px;
            height: 20px;
        }
        
        /* Features Section */
        .features {
            padding: 5rem 2rem;
            background: #f9fafb;
        }
        
        .features-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .section-title {
            font-size: 36px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 0.75rem;
            letter-spacing: -0.5px;
        }
        
        .section-subtitle {
            font-size: 18px;
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        
        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            border: 1px solid var(--border);
            transition: all 0.3s;
        }
        
        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
            border-color: var(--primary);
        }
        
        .feature-icon {
            width: 48px;
            height: 48px;
            background: var(--primary-light);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
        }
        
        .feature-icon svg {
            width: 24px;
            height: 24px;
            color: var(--primary);
        }
        
        .feature-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.75rem;
        }
        
        .feature-description {
            font-size: 15px;
            color: var(--text-muted);
            line-height: 1.6;
        }
        
        /* How It Works */
        .how-it-works {
            padding: 5rem 2rem;
            background: white;
        }
        
        .steps-container {
            max-width: 900px;
            margin: 0 auto;
        }
        
        .step {
            display: flex;
            gap: 2rem;
            margin-bottom: 3rem;
            align-items: flex-start;
        }
        
        .step-number {
            width: 56px;
            height: 56px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 700;
            flex-shrink: 0;
        }
        
        .step-content h3 {
            font-size: 22px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.5rem;
        }
        
        .step-content p {
            font-size: 16px;
            color: var(--text-muted);
            line-height: 1.6;
        }
        
        /* Security Section */
        .security {
            padding: 5rem 2rem;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        }
        
        .security-grid {
            max-width: 1000px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }
        
        .security-item {
            text-align: center;
        }
        
        .security-icon {
            width: 64px;
            height: 64px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 4px 12px rgba(0, 95, 184, 0.1);
        }
        
        .security-icon svg {
            width: 32px;
            height: 32px;
            color: var(--primary);
        }
        
        .security-item h4 {
            font-size: 18px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.5rem;
            word-wrap: break-word;
        }
        
        .security-item p {
            font-size: 14px;
            color: var(--text-muted);
            line-height: 1.5;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        /* CTA Section */
        .cta-section {
            padding: 5rem 2rem;
            background: var(--primary);
            color: white;
            text-align: center;
        }
        
        .cta-section h2 {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .cta-section p {
            font-size: 18px;
            opacity: 0.95;
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Footer */
        .footer {
            background: #1a1a1a;
            color: #9ca3af;
            padding: 3rem 2rem 2rem;
            text-align: center;
        }
        
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        
        .footer-links a {
            color: #9ca3af;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s;
        }
        
        .footer-links a:hover {
            color: white;
        }
        
        .footer-bottom {
            padding-top: 2rem;
            border-top: 1px solid #374151;
            font-size: 13px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 { font-size: 32px; }
            .hero-subtitle { font-size: 16px; }
            .section-title { font-size: 28px; }
            .step { flex-direction: column; }
            .features-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="landing-header">
        <a href="/" class="landing-logo">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
            </svg>
            {{ __('messages.common.portal_name') }}
        </a>
        <nav class="landing-nav">
            @include('partials.lang-switcher')
            @auth
                @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.reports') }}" class="btn btn-secondary btn-sm">{{ __('messages.admin.dashboard') }}</a>
                @else
                    <a href="{{ route('user.dashboard') }}" class="btn btn-secondary btn-sm">{{ __('messages.user.my_reports') }}</a>
                @endif
            @else
                <a href="{{ route('user.login') }}" class="btn btn-secondary btn-sm">{{ __('messages.common.login') }}</a>
                <a href="{{ route('register') }}" class="btn btn-secondary btn-sm">{{ __('messages.auth.register') }}</a>
            @endauth
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>{{ __('messages.landing.hero_title') }}</h1>
            <p class="hero-subtitle">
                {{ __('messages.landing.hero_subtitle') }}
            </p>
            <div class="hero-cta">
                <a href="/submit" class="btn-hero btn-hero-primary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14M5 12h14"></path>
                    </svg>
                    {{ __('messages.landing.cta_submit') }}
                </a>
                <a href="#how-it-works" class="btn-hero btn-hero-secondary">
                    {{ __('messages.landing.cta_how_it_works') }}
                </a>
            </div>
            
            <div class="trust-badges">
                <div class="trust-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <span>{{ __('messages.landing.trust_encrypted') }}</span>
                </div>
                <div class="trust-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                    <span>{{ __('messages.landing.trust_compliant') }}</span>
                </div>
                <div class="trust-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                        <line x1="1" y1="1" x2="23" y2="23" stroke-width="2"></line>
                    </svg>
                    <span>{{ __('messages.landing.trust_anonymous') }}</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="features-container">
            <div class="section-header">
                <h2 class="section-title">{{ __('messages.landing.features_title') }}</h2>
                <p class="section-subtitle">
                    {{ __('messages.landing.features_subtitle') }}
                </p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <h3 class="feature-title">{{ __('messages.landing.feature_anonymous.title') }}</h3>
                    <p class="feature-description">
                        {{ __('messages.landing.feature_anonymous.desc') }}
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </div>
                    <h3 class="feature-title">{{ __('messages.landing.feature_security.title') }}</h3>
                    <p class="feature-description">
                        {{ __('messages.landing.feature_security.desc') }}
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="feature-title">{{ __('messages.landing.feature_communication.title') }}</h3>
                    <p class="feature-description">
                        {{ __('messages.landing.feature_communication.desc') }}
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                        </svg>
                    </div>
                    <h3 class="feature-title">{{ __('messages.landing.feature_tracking.title') }}</h3>
                    <p class="feature-description">
                        {{ __('messages.landing.feature_tracking.desc') }}
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                        </svg>
                    </div>
                    <h3 class="feature-title">{{ __('messages.landing.feature_documents.title') }}</h3>
                    <p class="feature-description">
                        {{ __('messages.landing.feature_documents.desc') }}
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            <path d="M9 12l2 2 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="feature-title">{{ __('messages.landing.feature_compliant.title') }}</h3>
                    <p class="feature-description">
                        {{ __('messages.landing.feature_compliant.desc') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works" id="how-it-works">
        <div class="steps-container">
            <div class="section-header">
                <h2 class="section-title">{{ __('messages.landing.how_it_works_title') }}</h2>
                <p class="section-subtitle">
                    {{ __('messages.landing.how_it_works_subtitle') }}
                </p>
            </div>
            
            <div class="step">
                <div class="step-number">1</div>
                <div class="step-content">
                    <h3>{{ __('messages.landing.step_1.title') }}</h3>
                    <p>
                        {{ __('messages.landing.step_1.desc') }}
                    </p>
                </div>
            </div>
            
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-content">
                    <h3>{{ __('messages.landing.step_2.title') }}</h3>
                    <p>
                        {{ __('messages.landing.step_2.desc') }}
                    </p>
                </div>
            </div>
            
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-content">
                    <h3>{{ __('messages.landing.step_3.title') }}</h3>
                    <p>
                        {{ __('messages.landing.step_3.desc') }}
                    </p>
                </div>
            </div>
            
            <div class="step">
                <div class="step-number">4</div>
                <div class="step-content">
                    <h3>{{ __('messages.landing.step_4.title') }}</h3>
                    <p>
                        {{ __('messages.landing.step_4.desc') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Security Section -->
    <section class="security">
        <div class="features-container">
            <div class="section-header">
                <h2 class="section-title">{{ __('messages.landing.security_title') }}</h2>
                <p class="section-subtitle">
                    {{ __('messages.landing.security_subtitle') }}
                </p>
            </div>
            
            <div class="security-grid">
                <div class="security-item">
                    <div class="security-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                    </div>
                    <h4>{{ __('messages.landing.security_encryption.title') }}</h4>
                    <p>{{ __('messages.landing.security_encryption.desc') }}</p>
                </div>
                
                <div class="security-item">
                    <div class="security-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 6v6l4 2"></path>
                        </svg>
                    </div>
                    <h4>{{ __('messages.landing.security_audit.title') }}</h4>
                    <p>{{ __('messages.landing.security_audit.desc') }}</p>
                </div>
                
                <div class="security-item">
                    <div class="security-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                            <line x1="1" y1="1" x2="23" y2="23"></line>
                        </svg>
                    </div>
                    <h4>{{ __('messages.landing.security_ip.title') }}</h4>
                    <p>{{ __('messages.landing.security_ip.desc') }}</p>
                </div>
                
                <div class="security-item">
                    <div class="security-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </div>
                    <h4>{{ __('messages.landing.security_passwords.title') }}</h4>
                    <p>{{ __('messages.landing.security_passwords.desc') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <h2>{{ __('messages.landing.cta_title') }}</h2>
        <p>
            {{ __('messages.landing.cta_subtitle') }}
        </p>
        <a href="/submit" class="btn-hero btn-hero-primary">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 5v14M5 12h14"></path>
            </svg>
            {{ __('messages.landing.cta_submit') }}
        </a>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-links">
                <a href="#">{{ __('messages.landing.footer_privacy') }}</a>
                <a href="#">{{ __('messages.landing.footer_imprint') }}</a>
                <a href="#">{{ __('messages.landing.footer_terms') }}</a>
                <a href="#">{{ __('messages.landing.footer_contact') }}</a>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} {{ __('messages.common.portal_name') }}. {{ __('messages.landing.footer_copyright') }}</p>
                <p style="margin-top: 0.5rem; font-size: 12px;">
                    {{ __('messages.landing.footer_compliance') }}
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
