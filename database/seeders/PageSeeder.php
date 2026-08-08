<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

final class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Terms of Service',
                'slug' => 'terms-of-service',
                'content' => '
                    <h2 class="text-xl font-bold text-slate-900 mb-4">1. Ketentuan Penggunaan SaaS</h2>
                    <p class="mb-4">Dengan mendaftar dan mengaktifkan lisensi Cooca.id, Anda menyetujui untuk mengoperasikan sistem ERP sesuai regulasi hukum yang berlaku di Republik Indonesia.</p>
                    <h2 class="text-xl font-bold text-slate-900 mb-4">2. Lisensi & Hak Cipta</h2>
                    <p class="mb-4">Cooca.id memberikan lisensi non-transferable untuk mengakses platform sesuai paket subskripsi yang aktif. Hak cipta perangkat lunak tetap menjadi milik PT Cooca Digital Indonesia.</p>
                    <h2 class="text-xl font-bold text-slate-900 mb-4">3. Pembayaran & Perpanjangan</h2>
                    <p class="mb-4">Tagihan berlangganan dikirimkan 7 hari sebelum masa aktif berakhir. Sistem akan membatasi akses secara otomatis apabila lisensi tidak diperpanjang setelah tenggat waktu.</p>
                ',
                'is_published' => true,
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '
                    <h2 class="text-xl font-bold text-slate-900 mb-4">1. Pengumpulan Informasi</h2>
                    <p class="mb-4">Dalam rangka menyediakan automated provisioning dan layanan ERP, kami mengumpulkan:</p>
                    <ul class="list-disc pl-6 mb-4">
                        <li><strong>Data Profil &amp; Bisnis:</strong> Nama lengkap, alamat email, nomor WhatsApp aktif, nama perusahaan, serta alamat operasional.</li>
                        <li><strong>Data Log &amp; Teknis:</strong> Alamat IP, browser agent, sesi aktivitas (database-driven session audit), dan rekam jejak persetujuan trial.</li>
                        <li><strong>Data Transaksi:</strong> Riwayat penagihan dan status pembayaran (namun TIDAK termasuk rincian sensitif kartu kredit/debit, karena diproses secara aman oleh Midtrans).</li>
                    </ul>

                    <h2 class="text-xl font-bold text-slate-900 mb-4 mt-6">2. Penggunaan Informasi</h2>
                    <p class="mb-4">Data yang dikumpulkan akan digunakan secara spesifik untuk:</p>
                    <ul class="list-disc pl-6 mb-4">
                        <li><strong>Otomatisasi Sistem:</strong> Membuat dan mem-provisioning database ERP untuk ruang kerja bisnis Anda.</li>
                        <li><strong>Pemrosesan Pembayaran:</strong> Meneruskan referensi pesanan dan data penagihan kepada Midtrans guna otorisasi transaksi.</li>
                        <li><strong>Komunikasi Operasional:</strong> Mengirimkan invoice, status pembayaran, notifikasi OTP, pengumuman maintenance, dan tiket dukungan teknis melalui jalur SMTP (Email) atau WhatsApp.</li>
                    </ul>

                    <h2 class="text-xl font-bold text-slate-900 mb-4 mt-6">3. Pembagian Data (Third-Party Disclosure)</h2>
                    <p class="mb-4">Kami menjunjung tinggi prinsip kerahasiaan (Zero Privileged Data Selling). Kami tidak akan pernah menjual, menyewakan, atau menukar basis data pelanggan atau data transaksi tenant ERP Anda kepada pihak ketiga untuk tujuan pemasaran eksternal. Data hanya akan dibagikan kepada:</p>
                    <ul class="list-disc pl-6 mb-4">
                        <li><strong>Midtrans (Payment Gateway):</strong> Sebatas data esensial untuk validasi tagihan.</li>
                        <li><strong>Penyedia Layanan Pesan:</strong> Server SMTP dan WhatsApp API hanya digunakan untuk transmisi pesan operasional Anda.</li>
                        <li><strong>Otoritas Hukum:</strong> Jika diwajibkan oleh perintah pengadilan atau undang-undang Republik Indonesia yang sah.</li>
                    </ul>

                    <h2 class="text-xl font-bold text-slate-900 mb-4 mt-6">4. Standar Keamanan Data</h2>
                    <p class="mb-4">Karena COOCA.ID memfasilitasi transaksi pembayaran, komunikasi web ke server kami diamankan dengan protokol enkripsi SSL/TLS. Autentikasi Pengguna diaudit ketat menggunakan sistem sesi dan OTP yang terintegrasi. Kerahasiaan akun (kombinasi Email dan Password) adalah tanggung jawab penuh Anda. Segala kerugian akibat kebocoran akses kredensial dari pihak pengguna berada di luar tanggung jawab COOCA.ID.</p>

                    <h2 class="text-xl font-bold text-slate-900 mb-4 mt-6">5. Penghapusan Data (Data Retention)</h2>
                    <p class="mb-4">Jika langganan Anda berakhir dan tidak diperpanjang melewati batas masa tenggang (grace period), COOCA.ID memiliki hak penuh untuk menghapus instansi ERP, cadangan (backup), dan seluruh data bisnis Anda secara permanen guna membebaskan ruang server. Anda diwajibkan untuk mengekspor data Anda sebelum masa aktif langganan habis.</p>

                    <h2 class="text-xl font-bold text-slate-900 mb-4 mt-6">6. Kebijakan Cookie &amp; Analitik (UU PDP &amp; GDPR Compliance)</h2>
                    <p class="mb-4">COOCA.ID menggunakan teknologi cookie dan penyimpanan lokal (localStorage) dengan prinsip transparansi penuh:</p>
                    <ul class="list-disc pl-6 mb-4">
                        <li><strong>Cookie Esensial (Wajib):</strong> Dipasang otomatis demi menjaga sesi autentikasi (<code>cooca_session</code>), enkripsi keamanan form (<code>XSRF-TOKEN</code>), dan pencegahan bot spam (<code>captcha_answer</code>).</li>
                        <li><strong>Cookie Analitik (Pilihan):</strong> Skrip Google Analytics GA4 hanya akan diaktifkan jika Pengunjung atau Pengguna memberikan persetujuan eksplisit melalui tombol "Terima Semua". Anda dapat mengubah preferensi ini kapan saja via menu <em>Pengaturan Cookie</em> di footer.</li>
                    </ul>

                    <h2 class="text-xl font-bold text-slate-900 mb-4 mt-6">7. Persetujuan Pengguna</h2>
                    <p class="mb-4">Dengan menekan tombol "Daftar", "Ajukan Trial", atau "Bayar Tagihan", Anda menyatakan telah membaca, memahami, dan menyetujui seluruh isi Dokumen Legal ini tanpa paksaan. PT Cooca Digital Indonesia berhak mengubah dokumen ini kapan saja, dan versi pembaruan akan langsung berlaku sejak dipublikasikan di halaman ini.</p>
                ',
                'is_published' => true,
            ],
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'content' => '
                    <p class="mb-4">Cooca.id adalah penyedia platform Enterprise Resource Planning (ERP) terintegrasi yang berdedikasi untuk mendorong digitalisasi UMKM dan perusahaan modern di Indonesia.</p>
                ',
                'is_published' => true,
            ],
        ];

        foreach ($pages as $page) {
            Page::firstOrCreate(
                ['slug' => $page['slug']],
                $page
            );
        }

        echo "✅ Pages successfully seeded.\n";
    }
}
