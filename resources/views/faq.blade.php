@extends('layouts.app')

@section('title', 'Frequently Asked Questions - Find & Found')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="text-center mb-5">
        <h1 class="page-title">Frequently Asked Questions</h1>
        <p class="page-subtitle">Find answers to common questions about using Find & Found</p>
    </div>

    <!-- Search FAQ -->
    <div class="faq-search mb-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control form-control-lg" id="faqSearch" 
                           placeholder="Search for answers...">
                    <button class="btn btn-primary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Categories -->
    <div class="faq-categories mb-4">
        <div class="d-flex justify-content-center flex-wrap gap-2">
            <button class="btn btn-outline-primary active" data-category="all">All</button>
            <button class="btn btn-outline-primary" data-category="getting-started">Getting Started</button>
            <button class="btn btn-outline-primary" data-category="posting">Posting Items</button>
            <button class="btn btn-outline-primary" data-category="safety">Safety</button>
            <button class="btn btn-outline-primary" data-category="account">Account</button>
            <button class="btn btn-outline-primary" data-category="technical">Technical</button>
        </div>
    </div>

    <!-- FAQ Content -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="faq-container">
                
                <!-- Getting Started -->
                <div class="faq-section" data-category="getting-started">
                    <h3 class="section-title">
                        <i class="fas fa-rocket me-2"></i>Getting Started
                    </h3>
                    
                    <div class="faq-item">
                        <div class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq1">
                            <h5>Bagaimana cara menggunakan Find & Found?</h5>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="collapse faq-answer" id="faq1">
                            <div class="faq-content">
                                <p>Find & Found adalah platform yang menghubungkan orang yang kehilangan barang dengan yang menemukannya. Berikut langkah-langkahnya:</p>
                                <ol>
                                    <li><strong>Daftar akun</strong> - Buat akun gratis untuk mulai menggunakan platform</li>
                                    <li><strong>Laporkan barang</strong> - Posting barang yang hilang atau yang Anda temukan</li>
                                    <li><strong>Cari & Browse</strong> - Gunakan fitur pencarian untuk menemukan barang</li>
                                    <li><strong>Hubungi pemilik</strong> - Chat langsung dengan pemilik/penemu barang</li>
                                    <li><strong>Bertemu aman</strong> - Temui di tempat umum untuk mengembalikan barang</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq2">
                            <h5>Apakah Find & Found gratis?</h5>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="collapse faq-answer" id="faq2">
                            <div class="faq-content">
                                <p>Ya, Find & Found sepenuhnya gratis untuk semua pengguna. Tidak ada biaya untuk:</p>
                                <ul>
                                    <li>Mendaftar akun</li>
                                    <li>Posting barang hilang atau ditemukan</li>
                                    <li>Mencari dan browsing items</li>
                                    <li>Menggunakan fitur chat</li>
                                    <li>Semua fitur platform lainnya</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq3">
                            <h5>Apakah saya perlu membuat akun untuk menggunakan platform ini?</h5>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="collapse faq-answer" id="faq3">
                            <div class="faq-content">
                                <p>Anda dapat melihat dan mencari barang tanpa akun, namun untuk fitur lengkap Anda perlu mendaftar:</p>
                                <ul>
                                    <li><strong>Tanpa akun:</strong> Browsing dan pencarian barang</li>
                                    <li><strong>Dengan akun:</strong> Posting barang, chat, komentar, notifikasi</li>
                                </ul>
                                <p>Proses pendaftaran hanya membutuhkan email dan password, sangat mudah dan cepat!</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Posting Items -->
                <div class="faq-section" data-category="posting">
                    <h3 class="section-title">
                        <i class="fas fa-plus-circle me-2"></i>Posting Items
                    </h3>

                    <div class="faq-item">
                        <div class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq4">
                            <h5>Bagaimana cara melaporkan barang hilang?</h5>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="collapse faq-answer" id="faq4">
                            <div class="faq-content">
                                <p>Untuk melaporkan barang hilang:</p>
                                <ol>
                                    <li>Login ke akun Anda</li>
                                    <li>Klik tombol "Tambah Item" atau "Post Item"</li>
                                    <li>Pilih "Barang Hilang"</li>
                                    <li>Isi informasi lengkap: nama barang, deskripsi, kategori</li>
                                    <li>Upload foto barang (jika ada)</li>
                                    <li>Tentukan lokasi dan tanggal kehilangan</li>
                                    <li>Klik "Simpan" untuk mempublikasikan</li>
                                </ol>
                                <p><strong>Tips:</strong> Semakin detail informasi yang Anda berikan, semakin besar peluang barang ditemukan!</p>
                            </div>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq5">
                            <h5>Bagaimana cara melaporkan barang yang saya temukan?</h5>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="collapse faq-answer" id="faq5">
                            <div class="faq-content">
                                <p>Untuk melaporkan barang yang Anda temukan:</p>
                                <ol>
                                    <li>Login ke akun Anda</li>
                                    <li>Klik "Tambah Item"</li>
                                    <li>Pilih "Barang Ditemukan"</li>
                                    <li>Foto barang yang Anda temukan</li>
                                    <li>Deskripsikan barang sejelas mungkin</li>
                                    <li>Tentukan lokasi dan waktu penemuan</li>
                                    <li>Publish untuk membantu mencari pemiliknya</li>
                                </ol>
                                <p><strong>Penting:</strong> Simpan barang dengan aman sampai pemilik asli ditemukan!</p>
                            </div>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq6">
                            <h5>Berapa lama postingan saya akan aktif?</h5>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="collapse faq-answer" id="faq6">
                            <div class="faq-content">
                                <p>Postingan Anda akan tetap aktif sampai:</p>
                                <ul>
                                    <li>Anda menandainya sebagai "Resolved" (selesai)</li>
                                    <li>Anda menghapusnya sendiri</li>
                                    <li>Admin menonaktifkannya karena melanggar aturan</li>
                                </ul>
                                <p>Tidak ada batas waktu otomatis, sehingga barang Anda tetap bisa ditemukan kapan saja. Jangan lupa update status jika barang sudah ketemu!</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Safety -->
                <div class="faq-section" data-category="safety">
                    <h3 class="section-title">
                        <i class="fas fa-shield-alt me-2"></i>Safety & Security
                    </h3>

                    <div class="faq-item">
                        <div class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq7">
                            <h5>Bagaimana cara bertemu dengan aman?</h5>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="collapse faq-answer" id="faq7">
                            <div class="faq-content">
                                <p>Ikuti tips keamanan berikut saat bertemu:</p>
                                <ul>
                                    <li><strong>Tempat umum:</strong> Selalu pilih lokasi ramai seperti mall, kafe, atau stasiun</li>
                                    <li><strong>Siang hari:</strong> Bertemu pada siang hari lebih aman</li>
                                    <li><strong>Bawa teman:</strong> Ajak teman atau keluarga jika memungkinkan</li>
                                    <li><strong>Beri tahu orang lain:</strong> Informasikan rencana pertemuan ke teman/keluarga</li>
                                    <li><strong>Verifikasi barang:</strong> Pastikan barang sesuai deskripsi sebelum bertemu</li>
                                    <li><strong>Trust your instincts:</strong> Jika merasa tidak aman, batalkan pertemuan</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq8">
                            <h5>Bagaimana jika ada pengguna yang mencurigakan?</h5>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="collapse faq-answer" id="faq8">
                            <div class="faq-content">
                                <p>Jika Anda menemukan aktivitas mencurigakan:</p>
                                <ul>
                                    <li><strong>Report user:</strong> Gunakan tombol "Report" pada profil pengguna</li>
                                    <li><strong>Report item:</strong> Laporkan postingan yang mencurigakan atau palsu</li>
                                    <li><strong>Block user:</strong> Blokir pengguna yang mengganggu</li>
                                    <li><strong>Jangan transfer uang:</strong> Find & Found tidak melibatkan transaksi uang</li>
                                    <li><strong>Kontak admin:</strong> Hubungi tim support untuk bantuan lebih lanjut</li>
                                </ul>
                                <p>Keamanan komunitas adalah prioritas utama kami!</p>
                            </div>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq9">
                            <h5>Apakah informasi kontak saya aman?</h5>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="collapse faq-answer" id="faq9">
                            <div class="faq-content">
                                <p>Ya, informasi pribadi Anda terlindungi:</p>
                                <ul>
                                    <li><strong>Email & telepon:</strong> Tidak ditampilkan publik tanpa persetujuan</li>
                                    <li><strong>Chat internal:</strong> Komunikasi awal melalui sistem chat platform</li>
                                    <li><strong>Kontrol privasi:</strong> Anda yang menentukan kapan membagikan kontak</li>
                                    <li><strong>Data encryption:</strong> Informasi sensitif dienkripsi dengan aman</li>
                                    <li><strong>No spam:</strong> Kami tidak membagikan data ke pihak ketiga</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account -->
                <div class="faq-section" data-category="account">
                    <h3 class="section-title">
                        <i class="fas fa-user-cog me-2"></i>Account Management
                    </h3>

                    <div class="faq-item">
                        <div class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq10">
                            <h5>Bagaimana cara mengedit profil saya?</h5>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="collapse faq-answer" id="faq10">
                            <div class="faq-content">
                                <p>Untuk mengedit profil:</p>
                                <ol>
                                    <li>Klik nama Anda di pojok kanan atas</li>
                                    <li>Pilih "Profil" dari dropdown menu</li>
                                    <li>Klik tombol "Edit Profile"</li>
                                    <li>Ubah informasi yang diinginkan</li>
                                    <li>Upload foto profil baru (opsional)</li>
                                    <li>Klik "Simpan" untuk menyimpan perubahan</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq11">
                            <h5>Lupa password, bagaimana reset?</h5>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="collapse faq-answer" id="faq11">
                            <div class="faq-content">
                                <p>Untuk reset password:</p>
                                <ol>
                                    <li>Klik "Lupa Password?" di halaman login</li>
                                    <li>Masukkan email yang terdaftar</li>
                                    <li>Cek email untuk link reset password</li>
                                    <li>Klik link dan buat password baru</li>
                                    <li>Login dengan password baru</li>
                                </ol>
                                <p><strong>Tidak menerima email?</strong> Cek folder spam atau hubungi support.</p>
                            </div>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq12">
                            <h5>Bagaimana cara menghapus akun?</h5>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="collapse faq-answer" id="faq12">
                            <div class="faq-content">
                                <p>Untuk menghapus akun:</p>
                                <ol>
                                    <li>Masuk ke halaman Profil</li>
                                    <li>Scroll ke bawah ke bagian "Danger Zone"</li>
                                    <li>Klik "Delete Account"</li>
                                    <li>Konfirmasi dengan memasukkan password</li>
                                    <li>Klik "Yes, Delete My Account"</li>
                                </ol>
                                <p><strong>Peringatan:</strong> Penghapusan akun bersifat permanen dan tidak dapat dibatalkan!</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Technical -->
                <div class="faq-section" data-category="technical">
                    <h3 class="section-title">
                        <i class="fas fa-cogs me-2"></i>Technical Support
                    </h3>

                    <div class="faq-item">
                        <div class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq13">
                            <h5>Mengapa foto saya tidak bisa diupload?</h5>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="collapse faq-answer" id="faq13">
                            <div class="faq-content">
                                <p>Kemungkinan penyebab dan solusi:</p>
                                <ul>
                                    <li><strong>Ukuran file:</strong> Maksimal 2MB per foto, compress jika terlalu besar</li>
                                    <li><strong>Format file:</strong> Gunakan JPG, PNG, atau GIF</li>
                                    <li><strong>Koneksi internet:</strong> Pastikan koneksi stabil</li>
                                    <li><strong>Browser:</strong> Coba refresh halaman atau ganti browser</li>
                                    <li><strong>Cache:</strong> Clear cache dan cookies browser</li>
                                </ul>
                                <p>Jika masih bermasalah, hubungi support dengan detail error yang muncul.</p>
                            </div>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq14">
                            <h5>Kenapa notifikasi tidak muncul?</h5>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="collapse faq-answer" id="faq14">
                            <div class="faq-content">
                                <p>Untuk mengaktifkan notifikasi:</p>
                                <ul>
                                    <li><strong>Browser:</strong> Izinkan notifikasi dari website kami</li>
                                    <li><strong>Email:</strong> Cek folder spam, tambahkan email kami ke contact</li>
                                    <li><strong>Settings:</strong> Periksa pengaturan notifikasi di profil</li>
                                    <li><strong>Firewall:</strong> Pastikan tidak diblokir oleh firewall/antivirus</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq15">
                            <h5>Website lambat atau tidak bisa diakses?</h5>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="collapse faq-answer" id="faq15">
                            <div class="faq-content">
                                <p>Langkah troubleshooting:</p>
                                <ol>
                                    <li><strong>Refresh halaman:</strong> Tekan Ctrl+F5 (Windows) atau Cmd+R (Mac)</li>
                                    <li><strong>Clear cache:</strong> Hapus cache dan cookies browser</li>
                                    <li><strong>Coba browser lain:</strong> Test dengan Chrome, Firefox, atau Safari</li>
                                    <li><strong>Koneksi internet:</strong> Test koneksi dengan website lain</li>
                                    <li><strong>Device lain:</strong> Coba akses dari HP atau komputer lain</li>
                                </ol>
                                <p>Jika masih bermasalah, kemungkinan ada maintenance server. Coba lagi beberapa saat.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Support -->
    <div class="support-section">
        <div class="text-center">
            <h3>Masih butuh bantuan?</h3>
            <p>Tim support kami siap membantu Anda 24/7</p>
            <div class="support-options">
                <a href="{{ route('contact') }}" class="btn btn-primary btn-lg me-3">
                    <i class="fas fa-envelope me-2"></i>Contact Support
                </a>
                <a href="mailto:support@findandfound.com" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-mail-bulk me-2"></i>Email Us
                </a>
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
    margin-bottom: 1rem;
}

.page-subtitle {
    font-size: 1.2rem;
    color: #6c757d;
    margin-bottom: 0;
}

/* FAQ Search */
.faq-search .form-control {
    border-radius: 25px;
    border-right: none;
}

.faq-search .btn {
    border-radius: 25px;
    border-left: none;
}

/* FAQ Categories */
.faq-categories .btn {
    border-radius: 20px;
    margin: 0.25rem;
}

.faq-categories .btn.active {
    background-color: #2563eb;
    border-color: #2563eb;
    color: white;
}

/* FAQ Container */
.faq-container {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.faq-section {
    margin-bottom: 3rem;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #212529;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e9ecef;
}

.faq-item {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    margin-bottom: 1rem;
    overflow: hidden;
}

.faq-question {
    padding: 1.25rem;
    background: #f8f9fa;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: background-color 0.2s;
}

.faq-question:hover {
    background: #e9ecef;
}

.faq-question h5 {
    margin-bottom: 0;
    font-weight: 600;
    color: #212529;
}

.faq-question i {
    color: #6c757d;
    transition: transform 0.2s;
}

.faq-question[aria-expanded="true"] i {
    transform: rotate(180deg);
}

.faq-answer {
    border-top: 1px solid #e9ecef;
}

.faq-content {
    padding: 1.25rem;
    color: #495057;
    line-height: 1.6;
}

.faq-content p {
    margin-bottom: 1rem;
}

.faq-content ul,
.faq-content ol {
    margin-bottom: 1rem;
    padding-left: 1.5rem;
}

.faq-content li {
    margin-bottom: 0.5rem;
}

.faq-content strong {
    color: #212529;
}

/* Support Section */
.support-section {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
    color: white;
    border-radius: 12px;
    padding: 3rem 2rem;
    margin-top: 3rem;
    text-align: center;
}

.support-section h3 {
    margin-bottom: 1rem;
}

.support-section p {
    margin-bottom: 2rem;
    opacity: 0.9;
}

.support-options .btn-outline-primary {
    border-color: white;
    color: white;
}

.support-options .btn-outline-primary:hover {
    background: white;
    color: #2563eb;
}

/* Hidden sections for filtering */
.faq-section.hidden {
    display: none;
}

.faq-item.hidden {
    display: none;
}

/* Responsive */
@media (max-width: 768px) {
    .page-title {
        font-size: 2rem;
    }
    
    .faq-container {
        padding: 1.5rem;
    }
    
    .faq-question {
        padding: 1rem;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .faq-content {
        padding: 1rem;
    }
    
    .support-section {
        padding: 2rem 1rem;
    }
    
    .support-options {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        align-items: center;
    }
    
    .support-options .btn {
        width: 100%;
        max-width: 300px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('faqSearch');
    const categoryButtons = document.querySelectorAll('[data-category]');
    const faqItems = document.querySelectorAll('.faq-item');
    const faqSections = document.querySelectorAll('.faq-section');

    // Search functionality
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question h5').textContent.toLowerCase();
            const answer = item.querySelector('.faq-content').textContent.toLowerCase();
            
            if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                item.classList.remove('hidden');
            } else {
                item.classList.add('hidden');
            }
        });
    });

    // Category filtering
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            const category = this.dataset.category;
            
            // Update active button
            categoryButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Show/hide sections
            if (category === 'all') {
                faqSections.forEach(section => section.classList.remove('hidden'));
                faqItems.forEach(item => item.classList.remove('hidden'));
            } else {
                faqSections.forEach(section => {
                    if (section.dataset.category === category) {
                        section.classList.remove('hidden');
                    } else {
                        section.classList.add('hidden');
                    }
                });
            }
            
            // Clear search
            searchInput.value = '';
        });
    });

    // Smooth scroll to section when opened
    document.querySelectorAll('.faq-question').forEach(question => {
        question.addEventListener('click', function() {
            setTimeout(() => {
                this.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 300);
        });
    });
});
</script>
@endpush