<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\BlogPost;
use Illuminate\Http\Request;

/**
 * Landing Controller
 *
 * Handles public-facing landing pages including home, about, pricing, contact, and affiliate info.
 */
class LandingController extends Controller
{
    /**
     * Display the homepage.
     */
    public function index(Request $request)
    {
        $products = Product::where('is_active', true)
            ->with(['category', 'subscriptionPlans'])
            ->limit(6)
            ->get();

        $features = [
            [
                'icon' => '⚡',
                'title' => 'Fast Deployment',
                'description' => 'Trial aktif dalam kurang dari 1 jam',
            ],
            [
                'icon' => '🧩',
                'title' => 'Multi ERP System',
                'description' => 'Satu platform untuk banyak solusi bisnis',
            ],
            [
                'icon' => '🔐',
                'title' => 'Secure License System',
                'description' => 'Domain dan token based protection',
            ],
            [
                'icon' => '💸',
                'title' => 'Recurring Affiliate',
                'description' => 'Monetisasi berkelanjutan untuk mitra',
            ],
            [
                'icon' => '📢',
                'title' => 'Integrated Notification',
                'description' => 'Otomatisasi WhatsApp dan Email',
            ],
            [
                'icon' => '🚀',
                'title' => 'Scalable Platform',
                'description' => 'Siap berkembang bersama bisnis Anda',
            ],
        ];

        $testimonials = [
            [
                'name' => 'Ahmad Rizki',
                'business' => 'Restoran Padang Sari Rasa',
                'avatar' => 'AR',
                'rating' => 5,
                'comment' => 'Sistem ERP yang sangat membantu operasional restoran kami. Trial langsung aktif dan tim support sangat responsif.',
            ],
            [
                'name' => 'Dr. Siti Nurhaliza',
                'business' => 'Klinik Sehat Mandiri',
                'avatar' => 'SN',
                'rating' => 5,
                'comment' => 'Management pasien dan rekam medis jadi lebih teratur. Worth it banget untuk klinik kecil seperti kami.',
            ],
            [
                'name' => 'Budi Santoso',
                'business' => 'Bengkel Auto Care',
                'avatar' => 'BS',
                'rating' => 4,
                'comment' => 'Fitur booking dan tracking servis sangat berguna. Customer juga senang bisa monitor progress online.',
            ],
        ];

        return view('landing.home', compact('products', 'features', 'testimonials'));
    }

    /**
     * Display the about page.
     */
    public function about()
    {
        $stats = [
            ['label' => 'Active Customers', 'value' => '500+'],
            ['label' => 'ERP Systems', 'value' => '15+'],
            ['label' => 'Affiliators', 'value' => '200+'],
            ['label' => 'Uptime', 'value' => '99.9%'],
        ];

        $team = [
            [
                'name' => 'John Doe',
                'role' => 'CEO & Founder',
                'avatar' => 'JD',
            ],
            [
                'name' => 'Jane Smith',
                'role' => 'CTO',
                'avatar' => 'JS',
            ],
            [
                'name' => 'Michael Chen',
                'role' => 'Head of Product',
                'avatar' => 'MC',
            ],
        ];

        return view('landing.about', compact('stats', 'team'));
    }

    /**
     * Display the pricing page.
     */
    public function pricing()
    {
        $products = Product::where('is_active', true)
            ->with(['subscriptionPlans' => function ($query) {
                $query->where('is_active', true)->orderBy('price');
            }])
            ->get();

        $faq = [
            [
                'question' => 'Bagaimana cara memulai trial?',
                'answer' => 'Pilih produk ERP yang diinginkan, klik "Coba Gratis", isi data bisnis Anda, dan sistem akan aktif dalam 30 menit - 1 jam setelah verifikasi admin.',
            ],
            [
                'question' => 'Apakah ada biaya tersembunyi?',
                'answer' => 'Tidak sama sekali. Harga yang tertera adalah harga final. Tidak ada biaya setup, instalasi, atau maintenance tambahan.',
            ],
            [
                'question' => 'Bisakah upgrade/downgrade paket kapan saja?',
                'answer' => 'Ya, Anda dapat upgrade atau downgrade paket subscription kapan saja. Perubahan akan berlaku pada periode billing berikutnya.',
            ],
            [
                'question' => 'Apa yang terjadi setelah trial berakhir?',
                'answer' => 'Setelah trial 7 hari, Anda perlu berlangganan untuk terus menggunakan sistem. Data Anda tetap tersimpan dan dapat diakses segera setelah berlangganan.',
            ],
            [
                'question' => 'Apakah tersedia diskon untuk pembayaran tahunan?',
                'answer' => 'Ya, pembayaran tahunan mendapatkan diskon hingga 20% dibandingkan pembayaran bulanan.',
            ],
        ];

        return view('landing.pricing', compact('products', 'faq'));
    }

    /**
     * Display the contact page.
     */
    public function contact()
    {
        $contactInfo = [
            [
                'type' => 'email',
                'label' => 'Email Support',
                'value' => 'support@cooca.id',
                'icon' => '📧',
            ],
            [
                'type' => 'email',
                'label' => 'Email Sales',
                'value' => 'marketing@cooca.id',
                'icon' => '💼',
            ],
            [
                'type' => 'whatsapp',
                'label' => 'WhatsApp',
                'value' => '+62 812-3456-7890',
                'icon' => '💬',
            ],
            [
                'type' => 'address',
                'label' => 'Office',
                'value' => 'Jakarta, Indonesia',
                'icon' => '📍',
            ],
        ];

        return view('landing.contact', compact('contactInfo'));
    }

    /**
     * Display the affiliate program page.
     */
    public function affiliate()
    {
        $benefits = [
            [
                'icon' => '💰',
                'title' => 'Komisi 25%',
                'description' => 'Dapatkan 25% dari setiap pembayaran customer yang Anda referensikan.',
            ],
            [
                'icon' => '🔄',
                'title' => 'Recurring Income',
                'description' => 'Komisi berjalan terus selama customer aktif berlangganan.',
            ],
            [
                'icon' => '👥',
                'title' => 'Multi-Level',
                'description' => 'Dapatkan 5% dari downline level 2 yang Anda bangun.',
            ],
            [
                'icon' => '⚡',
                'title' => 'Withdraw Cepat',
                'description' => 'Penarikan dana otomatis dengan fee minimal.',
            ],
        ];

        $howItWorks = [
            ['step' => 1, 'title' => 'Daftar', 'description' => 'Registrasi menjadi affiliator secara gratis'],
            ['step' => 2, 'title' => 'Promosi', 'description' => 'Bagikan link referral Anda ke calon customer'],
            ['step' => 3, 'title' => 'Earn', 'description' => 'Dapatkan komisi saat customer berlangganan'],
            ['step' => 4, 'title' => 'Withdraw', 'description' => 'Tarik saldo komisi ke rekening Anda'],
        ];

        $commissionExample = [
            'monthly' => [
                'customers' => 10,
                'avgPrice' => 500000,
                'commission' => 1250000,
            ],
            'yearly' => [
                'customers' => 10,
                'avgPrice' => 5000000,
                'commission' => 12500000,
            ],
        ];

        return view('landing.affiliate', compact('benefits', 'howItWorks', 'commissionExample'));
    }
}
