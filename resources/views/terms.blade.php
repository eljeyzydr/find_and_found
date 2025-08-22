@extends('layouts.app')

@section('title', 'Terms & Conditions - Find & Found')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="text-center mb-5">
        <h1 class="page-title">Terms & Conditions</h1>
        <p class="page-subtitle">Last updated: {{ date('F d, Y') }}</p>
    </div>

    <!-- Table of Contents -->
    <div class="row">
        <div class="col-lg-3">
            <div class="toc-sidebar">
                <h5>Table of Contents</h5>
                <nav class="toc-nav">
                    <a href="#acceptance" class="toc-link">1. Acceptance of Terms</a>
                    <a href="#description" class="toc-link">2. Service Description</a>
                    <a href="#user-accounts" class="toc-link">3. User Accounts</a>
                    <a href="#user-conduct" class="toc-link">4. User Conduct</a>
                    <a href="#content" class="toc-link">5. Content & Posting</a>
                    <a href="#safety" class="toc-link">6. Safety Guidelines</a>
                    <a href="#intellectual" class="toc-link">7. Intellectual Property</a>
                    <a href="#privacy" class="toc-link">8. Privacy & Data</a>
                    <a href="#disclaimers" class="toc-link">9. Disclaimers</a>
                    <a href="#limitation" class="toc-link">10. Limitation of Liability</a>
                    <a href="#termination" class="toc-link">11. Termination</a>
                    <a href="#changes" class="toc-link">12. Changes to Terms</a>
                    <a href="#contact" class="toc-link">13. Contact Information</a>
                </nav>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="terms-content">
                <!-- Section 1 -->
                <section id="acceptance" class="terms-section">
                    <h2>1. Acceptance of Terms</h2>
                    <p>Dengan mengakses dan menggunakan platform Find & Found ("Platform", "Layanan", "Kami"), Anda menyetujui untuk terikat oleh Syarat dan Ketentuan ini ("Terms"). Jika Anda tidak setuju dengan terms ini, mohon tidak menggunakan layanan kami.</p>
                    <p>Terms ini berlaku untuk semua pengguna platform, termasuk pengunjung, pengguna terdaftar, dan kontributor konten.</p>
                </section>

                <!-- Section 2 -->
                <section id="description" class="terms-section">
                    <h2>2. Service Description</h2>
                    <p>Find & Found adalah platform online yang memfasilitasi koneksi antara orang yang kehilangan barang dengan orang yang menemukan barang tersebut. Layanan kami meliputi:</p>
                    <ul>
                        <li>Platform untuk melaporkan barang hilang dan ditemukan</li>
                        <li>Sistem pencarian dan kategorisasi barang</li>
                        <li>Fitur komunikasi antar pengguna</li>
                        <li>Sistem notifikasi dan alert</li>
                        <li>Tools untuk verifikasi dan keamanan</li>
                    </ul>
                    <p><strong>Penting:</strong> Kami hanya menyediakan platform; kami tidak bertanggung jawab atas barang fisik, transaksi, atau interaksi antar pengguna.</p>
                </section>

                <!-- Section 3 -->
                <section id="user-accounts" class="terms-section">
                    <h2>3. User Accounts</h2>
                    <h4>3.1 Registrasi Akun</h4>
                    <p>Untuk menggunakan fitur lengkap platform, Anda harus membuat akun dengan memberikan informasi yang akurat, lengkap, dan terkini. Anda bertanggung jawab untuk:</p>
                    <ul>
                        <li>Menjaga kerahasiaan password dan informasi akun</li>
                        <li>Semua aktivitas yang terjadi di bawah akun Anda</li>
                        <li>Segera memberi tahu kami jika ada penggunaan tidak sah</li>
                    </ul>

                    <h4>3.2 Persyaratan Akun</h4>
                    <ul>
                        <li>Anda harus berusia minimal 17 tahun untuk membuat akun</li>
                        <li>Satu orang hanya boleh memiliki satu akun aktif</li>
                        <li>Informasi yang diberikan harus benar dan dapat diverifikasi</li>
                        <li>Anda tidak boleh memberikan akses akun Anda kepada orang lain</li>
                    </ul>
                </section>

                <!-- Section 4 -->
                <section id="user-conduct" class="terms-section">
                    <h2>4. User Conduct</h2>
                    <p>Dengan menggunakan platform kami, Anda setuju untuk TIDAK:</p>
                    
                    <h4>4.1 Aktivitas Terlarang</h4>
                    <ul>
                        <li>Memposting informasi palsu, menyesatkan, atau tidak akurat</li>
                        <li>Menggunakan platform untuk penipuan atau aktivitas ilegal</li>
                        <li>Meniru identitas orang lain atau entitas lain</li>
                        <li>Mengganggu, melecehkan, atau mengancam pengguna lain</li>
                        <li>Memposting konten yang melanggar hukum, cabul, atau tidak pantas</li>
                        <li>Menggunakan platform untuk tujuan komersial tanpa izin</li>
                        <li>Mengakses sistem secara tidak sah atau mencoba merusak platform</li>
                    </ul>

                    <h4>4.2 Standar Komunitas</h4>
                    <p>Kami mendorong komunitas yang positif dan saling membantu. Pengguna diharapkan:</p>
                    <ul>
                        <li>Bersikap sopan dan hormat terhadap pengguna lain</li>
                        <li>Memberikan informasi yang akurat dan berguna</li>
                        <li>Melaporkan konten atau pengguna yang melanggar aturan</li>
                        <li>Menggunakan fitur komunikasi dengan bijak</li>
                    </ul>
                </section>

                <!-- Section 5 -->
                <section id="content" class="terms-section">
                    <h2>5. Content & Posting</h2>
                    
                    <h4>5.1 User Generated Content</h4>
                    <p>Anda mempertahankan kepemilikan atas konten yang Anda posting, namun memberikan kami lisensi untuk:</p>
                    <ul>
                        <li>Menampilkan, mendistribusikan, dan mempromosikan konten Anda</li>
                        <li>Menggunakan konten untuk tujuan operasional platform</li>
                        <li>Memodifikasi format konten untuk kompatibilitas teknis</li>
                    </ul>

                    <h4>5.2 Content Guidelines</h4>
                    <p>Semua konten yang diposting harus:</p>
                    <ul>
                        <li>Berkaitan dengan barang hilang atau ditemukan yang legitimate</li>
                        <li>Mengandung informasi yang akurat dan tidak menyesatkan</li>
                        <li>Sesuai dengan kategori dan format yang benar</li>
                        <li>Tidak melanggar hak kekayaan intelektual pihak lain</li>
                        <li>Tidak mengandung virus, malware, atau kode berbahaya</li>
                    </ul>

                    <h4>5.3 Content Moderation</h4>
                    <p>Kami berhak untuk:</p>
                    <ul>
                        <li>Meninjau, mengedit, atau menghapus konten yang melanggar aturan</li>
                        <li>Menangguhkan atau mengakhiri akun yang berulang kali melanggar</li>
                        <li>Menggunakan teknologi otomatis untuk deteksi konten bermasalah</li>
                    </ul>
                </section>

                <!-- Section 6 -->
                <section id="safety" class="terms-section">
                    <h2>6. Safety Guidelines</h2>
                    
                    <h4>6.1 Pertemuan dan Interaksi</h4>
                    <p><strong>Penting:</strong> Kami sangat menyarankan untuk mengikuti panduan keamanan saat bertemu dengan pengguna lain:</p>
                    <ul>
                        <li>Selalu bertemu di tempat umum dan ramai</li>
                        <li>Beri tahu teman atau keluarga tentang rencana pertemuan</li>
                        <li>Verifikasi identitas dan barang sebelum bertemu</li>
                        <li>Percayai insting Anda - jika merasa tidak aman, batalkan pertemuan</li>
                        <li>Jangan memberikan informasi pribadi yang tidak perlu</li>
                    </ul>

                    <h4>6.2 Transaksi dan Imbalan</h4>
                    <ul>
                        <li>Platform ini TIDAK mendukung transaksi komersial</li>
                        <li>Imbalan atau hadiah bersifat opsional dan menjadi kesepakatan antar pengguna</li>
                        <li>Kami tidak bertanggung jawab atas sengketa finansial antar pengguna</li>
                        <li>Waspadai penipuan yang meminta pembayaran di muka</li>
                    </ul>
                </section>

                <!-- Section 7 -->
                <section id="intellectual" class="terms-section">
                    <h2>7. Intellectual Property</h2>
                    
                    <h4>7.1 Platform Rights</h4>
                    <p>Find & Found platform, termasuk desain, logo, teks, grafik, dan software adalah milik kami dan dilindungi oleh hukum kekayaan intelektual. Anda tidak boleh:</p>
                    <ul>
                        <li>Menyalin, memodifikasi, atau mendistribusikan elemen platform</li>
                        <li>Menggunakan nama, logo, atau merek dagang kami tanpa izin</li>
                        <li>Melakukan reverse engineering pada sistem kami</li>
                        <li>Membuat karya turunan dari platform kami</li>
                    </ul>

                    <h4>7.2 User Content Rights</h4>
                    <p>Anda menyatakan bahwa konten yang Anda posting:</p>
                    <ul>
                        <li>Adalah milik Anda atau Anda memiliki hak untuk mempostingnya</li>
                        <li>Tidak melanggar hak cipta, trademark, atau IP rights lainnya</li>
                        <li>Tidak mengandung informasi rahasia atau proprietary</li>
                    </ul>
                </section>

                <!-- Section 8 -->
                <section id="privacy" class="terms-section">
                    <h2>8. Privacy & Data Protection</h2>
                    <p>Privasi Anda penting bagi kami. Pengumpulan, penggunaan, dan perlindungan data pribadi Anda diatur oleh <a href="{{ route('privacy') }}">Privacy Policy</a> kami, yang merupakan bagian integral dari Terms ini.</p>
                    
                    <h4>8.1 Data Collection</h4>
                    <p>Kami mengumpulkan data yang diperlukan untuk:</p>
                    <ul>
                        <li>Menyediakan dan meningkatkan layanan</li>
                        <li>Memfasilitasi komunikasi antar pengguna</li>
                        <li>Menjaga keamanan dan mencegah penyalahgunaan</li>
                        <li>Memberikan dukungan pelanggan</li>
                    </ul>

                    <h4>8.2 Data Sharing</h4>
                    <p>Kami tidak akan membagikan data pribadi Anda kepada pihak ketiga kecuali:</p>
                    <ul>
                        <li>Dengan persetujuan eksplisit Anda</li>
                        <li>Diperlukan oleh hukum atau proses legal</li>
                        <li>Untuk melindungi keamanan platform dan pengguna</li>
                        <li>Dengan penyedia layanan yang terikat kontrak confidentiality</li>
                    </ul>
                </section>

                <!-- Section 9 -->
                <section id="disclaimers" class="terms-section">
                    <h2>9. Disclaimers</h2>
                    
                    <h4>9.1 Platform Availability</h4>
                    <p>Layanan disediakan "as is" dan "as available". Kami tidak menjamin bahwa:</p>
                    <ul>
                        <li>Platform akan selalu tersedia atau bebas dari gangguan</li>
                        <li>Semua fitur akan berfungsi sempurna setiap saat</li>
                        <li>Platform bebas dari bug, virus, atau masalah keamanan</li>
                        <li>Data akan selalu akurat atau up-to-date</li>
                    </ul>

                    <h4>9.2 User Interactions</h4>
                    <p>Kami tidak bertanggung jawab atas:</p>
                    <ul>
                        <li>Akurasi informasi yang diposting oleh pengguna</li>
                        <li>Kualitas, keamanan, atau legalitas barang yang dilaporkan</li>
                        <li>Perilaku atau tindakan pengguna lain</li>
                        <li>Sengketa, kerugian, atau kerusakan dari interaksi antar pengguna</li>
                        <li>Kehilangan atau kerusakan barang selama proses pengembalian</li>
                    </ul>
                </section>

                <!-- Section 10 -->
                <section id="limitation" class="terms-section">
                    <h2>10. Limitation of Liability</h2>
                    <p>Sejauh diizinkan oleh hukum yang berlaku, Find & Found dan afiliasinya tidak akan bertanggung jawab atas:</p>
                    
                    <h4>10.1 Damages</h4>
                    <ul>
                        <li>Kerugian langsung, tidak langsung, insidental, atau consequential</li>
                        <li>Kehilangan data, profit, atau goodwill</li>
                        <li>Kerusakan yang diakibatkan oleh pengguna lain</li>
                        <li>Gangguan layanan atau downtime</li>
                        <li>Biaya untuk mendapatkan layanan pengganti</li>
                    </ul>

                    <h4>10.2 Maximum Liability</h4>
                    <p>Dalam hal apapun, total liability kami kepada Anda tidak akan melebihi jumlah yang Anda bayarkan kepada kami dalam 12 bulan terakhir, atau $100, mana yang lebih kecil.</p>
                </section>

                <!-- Section 11 -->
                <section id="termination" class="terms-section">
                    <h2>11. Termination</h2>
                    
                    <h4>11.1 Termination by User</h4>
                    <p>Anda dapat mengakhiri akun kapan saja dengan:</p>
                    <ul>
                        <li>Menggunakan fitur delete account di settings</li>
                        <li>Menghubungi customer support</li>
                        <li>Berhenti menggunakan platform</li>
                    </ul>

                    <h4>11.2 Termination by Us</h4>
                    <p>Kami berhak menangguhkan atau mengakhiri akun Anda jika:</p>
                    <ul>
                        <li>Anda melanggar Terms ini atau kebijakan kami</li>
                        <li>Kami yakin tindakan Anda dapat membahayakan pengguna lain</li>
                        <li>Diperlukan oleh hukum atau permintaan pemerintah</li>
                        <li>Untuk melindungi integritas platform</li>
                    </ul>

                    <h4>11.3 Effect of Termination</h4>
                    <p>Setelah terminasi:</p>
                    <ul>
                        <li>Akses Anda ke platform akan dihentikan</li>
                        <li>Konten Anda mungkin akan dihapus</li>
                        <li>Beberapa provisions dalam Terms ini tetap berlaku</li>
                    </ul>
                </section>

                <!-- Section 12 -->
                <section id="changes" class="terms-section">
                    <h2>12. Changes to Terms</h2>
                    <p>Kami dapat mengubah Terms ini dari waktu ke waktu. Perubahan akan:</p>
                    <ul>
                        <li>Dipublikasikan di halaman ini dengan tanggal "Last Updated" yang baru</li>
                        <li>Diberitahukan kepada pengguna melalui email atau notifikasi platform</li>
                        <li>Berlaku efektif 30 hari setelah publikasi untuk perubahan material</li>
                        <li>Berlaku segera untuk perubahan non-material</li>
                    </ul>
                    <p>Penggunaan platform setelah perubahan Terms menandakan persetujuan Anda terhadap Terms yang baru.</p>
                </section>

                <!-- Section 13 -->
                <section id="contact" class="terms-section">
                    <h2>13. Contact Information</h2>
                    <p>Jika Anda memiliki pertanyaan tentang Terms ini, hubungi kami:</p>
                    <div class="contact-info">
                        <p><strong>Email:</strong> legal@findandfound.com</p>
                        <p><strong>Phone:</strong> +62-21-1234-5678</p>
                        <p><strong>Address:</strong><br>
                        Find & Found Legal Department<br>
                        Jl. Teknologi No. 123<br>
                        Jakarta 12345, Indonesia</p>
                        <p><strong>Business Hours:</strong> Senin - Jumat, 09:00 - 17:00 WIB</p>
                    </div>
                </section>

                <!-- Footer -->
                <div class="terms-footer">
                    <p class="text-muted">
                        <small>
                            These Terms & Conditions are governed by Indonesian law. 
                            Any disputes will be resolved in Jakarta courts.
                            <br><br>
                            <strong>Effective Date:</strong> {{ date('F d, Y') }}
                        </small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Page Styles */
.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #212529;
    margin-bottom: 0.5rem;
}

.page-subtitle {
    color: #6c757d;
    font-size: 1.1rem;
}

/* Table of Contents */
.toc-sidebar {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 2rem;
    height: fit-content;
}

.toc-sidebar h5 {
    color: #212529;
    font-weight: 600;
    margin-bottom: 1rem;
}

.toc-nav {
    display: flex;
    flex-direction: column;
}

.toc-link {
    color: #6c757d;
    text-decoration: none;
    padding: 0.5rem 0;
    border-left: 3px solid transparent;
    padding-left: 1rem;
    margin-left: -1rem;
    transition: all 0.2s;
    font-size: 0.9rem;
}

.toc-link:hover,
.toc-link.active {
    color: #2563eb;
    border-left-color: #2563eb;
    background: rgba(37, 99, 235, 0.05);
    text-decoration: none;
}

/* Terms Content */
.terms-content {
    background: white;
    border-radius: 12px;
    padding: 2.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.terms-section {
    margin-bottom: 3rem;
    scroll-margin-top: 2rem;
}

.terms-section h2 {
    color: #212529;
    font-weight: 600;
    font-size: 1.5rem;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e9ecef;
}

.terms-section h4 {
    color: #495057;
    font-weight: 600;
    font-size: 1.1rem;
    margin: 1.5rem 0 0.75rem 0;
}

.terms-section p {
    color: #495057;
    line-height: 1.7;
    margin-bottom: 1rem;
}

.terms-section ul,
.terms-section ol {
    color: #495057;
    line-height: 1.6;
    margin-bottom: 1rem;
    padding-left: 1.5rem;
}

.terms-section li {
    margin-bottom: 0.5rem;
}

.terms-section strong {
    color: #212529;
    font-weight: 600;
}

/* Contact Info */
.contact-info {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1.5rem;
    border-left: 4px solid #2563eb;
}

.contact-info p {
    margin-bottom: 0.5rem;
}

.contact-info strong {
    color: #2563eb;
}

/* Terms Footer */
.terms-footer {
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 1px solid #e9ecef;
    text-align: center;
}

/* Links */
.terms-content a {
    color: #2563eb;
    text-decoration: none;
}

.terms-content a:hover {
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 992px) {
    .toc-sidebar {
        position: static;
        margin-bottom: 2rem;
    }
    
    .toc-nav {
        flex-direction: row;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .toc-link {
        border: 1px solid #dee2e6;
        border-radius: 20px;
        padding: 0.5rem 1rem;
        margin: 0;
        border-left: 1px solid #dee2e6;
        background: #f8f9fa;
        font-size: 0.8rem;
    }
    
    .toc-link:hover,
    .toc-link.active {
        background: #2563eb;
        color: white;
        border-color: #2563eb;
    }
}

@media (max-width: 768px) {
    .page-title {
        font-size: 2rem;
    }
    
    .terms-content {
        padding: 1.5rem;
    }
    
    .terms-section h2 {
        font-size: 1.3rem;
    }
    
    .contact-info {
        padding: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for TOC links
    document.querySelectorAll('.toc-link').forEach(link => {
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
                document.querySelectorAll('.toc-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            }
        });
    });

    // Update active TOC link on scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const id = entry.target.getAttribute('id');
                document.querySelectorAll('.toc-link').forEach(link => {
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
    document.querySelectorAll('.terms-section').forEach(section => {
        observer.observe(section);
    });
});
</script>
@endpush