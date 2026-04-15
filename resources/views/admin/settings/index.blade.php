@extends('layouts.app')
@section('title', 'Settings')
@section('page-title', 'Settings')
@section('sidebar-menu')
    @include('admin.partials.sidebar')
@endsection

@section('content')
<div class="row g-3">
    <!-- Settings Navigation Tabs -->
    <div class="col-12">
        <ul class="nav nav-pills mb-3 flex-wrap gap-1" id="settingsTabs">
            <li class="nav-item"><a class="nav-link active" href="#general" data-bs-toggle="tab"><i class="bi bi-gear me-1"></i>General</a></li>
            <li class="nav-item"><a class="nav-link" href="#smtp" data-bs-toggle="tab"><i class="bi bi-envelope me-1"></i>SMTP</a></li>
            <li class="nav-item"><a class="nav-link" href="#seo" data-bs-toggle="tab"><i class="bi bi-search me-1"></i>SEO</a></li>
            <li class="nav-item"><a class="nav-link" href="#payment" data-bs-toggle="tab"><i class="bi bi-credit-card me-1"></i>Payment</a></li>
            <li class="nav-item"><a class="nav-link" href="#features" data-bs-toggle="tab"><i class="bi bi-toggles me-1"></i>Features</a></li>
        </ul>
    </div>

    <div class="col-12">
        <div class="tab-content">
            <!-- General Settings -->
            <div class="tab-pane fade show active" id="general">
                <div class="form-card">
                    <h5 class="fw-bold mb-4"><i class="bi bi-building me-2"></i>General Settings</h5>
                    <form method="POST" action="{{ route('admin.settings.general') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">School/Site Name *</label>
                                <input type="text" name="site_name" class="form-control"
                                    value="{{ $settings['site_name'] ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tagline</label>
                                <input type="text" name="site_tagline" class="form-control"
                                    value="{{ $settings['site_tagline'] ?? '' }}" placeholder="Empowering Education">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contact Email</label>
                                <input type="email" name="contact_email" class="form-control"
                                    value="{{ $settings['contact_email'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contact Phone</label>
                                <input type="text" name="contact_phone" class="form-control"
                                    value="{{ $settings['contact_phone'] ?? '' }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Contact Address</label>
                                <textarea name="contact_address" class="form-control" rows="2">{{ $settings['contact_address'] ?? '' }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Current Academic Year</label>
                                <input type="text" name="academic_year" class="form-control"
                                    value="{{ $settings['academic_year'] ?? date('Y') }}" placeholder="2024-25">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Footer Text</label>
                                <input type="text" name="footer_text" class="form-control"
                                    value="{{ $settings['footer_text'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">School Logo</label>
                                @if(!empty($settings['logo']))
                                    <div class="mb-2"><img src="{{ $settings['logo'] }}" alt="Logo" style="height:50px;" class="border rounded p-1"></div>
                                @endif
                                <input type="file" name="logo" class="form-control" accept="image/*">
                                <div class="form-text">Recommended: SVG or PNG, max 2MB</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Favicon</label>
                                @if(!empty($settings['favicon']))
                                    <div class="mb-2"><img src="{{ $settings['favicon'] }}" alt="Favicon" style="height:32px;" class="border rounded p-1"></div>
                                @endif
                                <input type="file" name="favicon" class="form-control" accept=".ico,.png">
                                <div class="form-text">ICO or PNG, 32x32px recommended</div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-2"></i>Save General Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- SMTP Settings -->
            <div class="tab-pane fade" id="smtp">
                <div class="form-card">
                    <h5 class="fw-bold mb-4"><i class="bi bi-envelope-check me-2"></i>SMTP Email Settings</h5>
                    <div class="alert alert-info small">
                        <i class="bi bi-info-circle me-2"></i>
                        For Gmail: Enable 2FA and use an App Password. Go to Google Account → Security → App Passwords.
                    </div>
                    <form method="POST" action="{{ route('admin.settings.smtp') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">SMTP Host *</label>
                                <input type="text" name="mail_host" class="form-control"
                                    value="{{ $settings['mail_host'] ?? 'smtp.gmail.com' }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">SMTP Port *</label>
                                <select name="mail_port" class="form-select">
                                    <option value="587" {{ ($settings['mail_port'] ?? '587') == '587' ? 'selected' : '' }}>587 (TLS)</option>
                                    <option value="465" {{ ($settings['mail_port'] ?? '') == '465' ? 'selected' : '' }}>465 (SSL)</option>
                                    <option value="25" {{ ($settings['mail_port'] ?? '') == '25' ? 'selected' : '' }}>25 (Default)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">SMTP Username *</label>
                                <input type="text" name="mail_username" class="form-control"
                                    value="{{ $settings['mail_username'] ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">SMTP Password / App Password *</label>
                                <input type="password" name="mail_password" class="form-control"
                                    value="{{ $settings['mail_password'] ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">From Email *</label>
                                <input type="email" name="mail_from" class="form-control"
                                    value="{{ $settings['mail_from'] ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">From Name *</label>
                                <input type="text" name="mail_name" class="form-control"
                                    value="{{ $settings['mail_name'] ?? ($settings['site_name'] ?? 'School ERP') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Encryption</label>
                                <select name="mail_encryption" class="form-select">
                                    <option value="tls" {{ ($settings['mail_encryption'] ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                                    <option value="ssl" {{ ($settings['mail_encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-2"></i>Save SMTP Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- SEO Settings -->
            <div class="tab-pane fade" id="seo">
                <div class="form-card">
                    <h5 class="fw-bold mb-4"><i class="bi bi-search me-2"></i>SEO Settings</h5>
                    <form method="POST" action="{{ route('admin.settings.seo') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Meta Title</label>
                                <input type="text" name="meta_title" class="form-control"
                                    value="{{ $settings['meta_title'] ?? '' }}" placeholder="School Name - Best School in City">
                                <div class="form-text">Recommended: 50-60 characters</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Meta Description</label>
                                <textarea name="meta_description" class="form-control" rows="2"
                                    placeholder="Describe your school in 150-160 characters for search engines">{{ $settings['meta_description'] ?? '' }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Meta Keywords</label>
                                <input type="text" name="meta_keywords" class="form-control"
                                    value="{{ $settings['meta_keywords'] ?? '' }}"
                                    placeholder="school, education, cbse, icse, english medium">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">OG Title (Social Media)</label>
                                <input type="text" name="og_title" class="form-control"
                                    value="{{ $settings['og_title'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">OG Image URL</label>
                                <input type="text" name="og_image" class="form-control"
                                    value="{{ $settings['og_image'] ?? '' }}" placeholder="https://...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Google Analytics ID</label>
                                <input type="text" name="google_analytics" class="form-control"
                                    value="{{ $settings['google_analytics'] ?? '' }}" placeholder="G-XXXXXXXXXX">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Google Site Verification</label>
                                <input type="text" name="google_site_verification" class="form-control"
                                    value="{{ $settings['google_site_verification'] ?? '' }}">
                            </div>
                            <div class="col-12"><hr><h6>Schema / Structured Data</h6></div>
                            <div class="col-md-6">
                                <label class="form-label">Organization Name</label>
                                <input type="text" name="schema_name" class="form-control"
                                    value="{{ $settings['schema_name'] ?? ($settings['site_name'] ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Organization Phone</label>
                                <input type="text" name="schema_phone" class="form-control"
                                    value="{{ $settings['schema_phone'] ?? ($settings['contact_phone'] ?? '') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Organization Description</label>
                                <textarea name="schema_description" class="form-control" rows="2">{{ $settings['schema_description'] ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-2"></i>Save SEO Settings
                            </button>
                            <a href="{{ route('sitemap') }}" target="_blank" class="btn btn-outline-secondary">
                                <i class="bi bi-file-code me-2"></i>View Sitemap
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Payment Settings -->
            <div class="tab-pane fade" id="payment">
                <div class="form-card">
                    <h5 class="fw-bold mb-4"><i class="bi bi-credit-card me-2"></i>PayU Payment Gateway</h5>
                    <div class="alert alert-warning small">
                        <i class="bi bi-shield-exclamation me-2"></i>
                        Keep your merchant key and salt confidential. Never share with anyone.
                    </div>
                    <form method="POST" action="{{ route('admin.settings.payment') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">PayU Merchant Key *</label>
                                <input type="text" name="payu_merchant_key" class="form-control"
                                    value="{{ $settings['payu_merchant_key'] ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">PayU Merchant Salt *</label>
                                <input type="password" name="payu_merchant_salt" class="form-control"
                                    value="{{ $settings['payu_merchant_salt'] ?? '' }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Mode</label>
                                <select name="payu_mode" class="form-select">
                                    <option value="test" {{ ($settings['payu_mode'] ?? 'test') == 'test' ? 'selected' : '' }}>Test</option>
                                    <option value="live" {{ ($settings['payu_mode'] ?? '') == 'live' ? 'selected' : '' }}>Live</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-2"></i>Save Payment Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Features -->
            <div class="tab-pane fade" id="features">
                <div class="form-card">
                    <h5 class="fw-bold mb-4"><i class="bi bi-toggles me-2"></i>Feature Management</h5>
                    <p class="text-muted small mb-4">Enable or disable system features. Changes take effect immediately.</p>
                    <div class="row g-3">
                        @foreach([
                            'online_admission' => ['Online Admission Form', 'Allow public admission applications', 'person-plus'],
                            'fee_payment' => ['Online Fee Payment', 'Enable PayU payment gateway', 'credit-card'],
                            'blog' => ['Blog / Announcements', 'Public blog and news section', 'newspaper'],
                            'student_portal' => ['Student Portal', 'Student login and dashboard', 'mortarboard'],
                            'parent_portal' => ['Parent Portal', 'Parent login and dashboard', 'people'],
                            'attendance' => ['Attendance Tracking', 'Attendance management module', 'calendar-check'],
                        ] as $featureKey => $featureData)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-between p-3 border rounded-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div style="width:40px;height:40px;background:#f1f5f9;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#4f46e5;font-size:1.1rem;">
                                        <i class="bi bi-{{ $featureData[2] }}"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold small">{{ $featureData[0] }}</div>
                                        <div class="text-muted" style="font-size:0.75rem;">{{ $featureData[1] }}</div>
                                    </div>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input feature-toggle" type="checkbox"
                                        data-feature="{{ $featureKey }}"
                                        {{ ($settings['feature_' . $featureKey] ?? '1') == '1' ? 'checked' : '' }}
                                        style="width:2.5em;height:1.4em;">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.feature-toggle').forEach(toggle => {
    toggle.addEventListener('change', async function() {
        const feature = this.dataset.feature;
        const value = this.checked ? '1' : '0';
        try {
            await fetch('{{ route("admin.settings.feature") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ feature, value })
            });
        } catch(e) {
            this.checked = !this.checked; // Revert on error
        }
    });
});
</script>
@endpush
