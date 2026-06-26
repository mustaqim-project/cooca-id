<?php
$idFile = 'lang/id.json';
$translations = json_decode(file_get_contents($idFile), true) ?: [];

$updates = [
    "COOCA Business System" => "Sistem Operasional COOCA",
    "Revenue This Month" => "Pendapatan Bulan Ini",
    '$284,500' => 'Rp 4,5 Miliar',
    "+24.5% growth" => "+24.5% Pertumbuhan",
    "Active Licenses" => "Lisensi Aktif",
    "All protected" => "Sepenuhnya Terlindungi",
    "System Status" => "Status Infrastruktur",
    "Protected" => "Aman & Terlindungi",
    "Monthly Growth" => "Akselerasi Bulanan",
    "+Rp48jt MRR" => "+Rp48jt MRR",
    "Businesses Trust COOCA" => "Perusahaan Mempercayakan Bisnisnya pada COOCA",
    "Guaranteed Uptime SLA" => "Jaminan Uptime & Stabilitas SLA",
    "Transactions Processed" => "Total Transaksi Berhasil Diproses",
    "1 Customer" => "1 Klien",
    "1 Isolated System" => "1 Sistem Independen",
    "Your own dedicated infrastructure. Fully separated. Independent security. Not shared — <strong>yours alone</strong>." => "Infrastruktur dedicated khusus untuk bisnis Anda. Sepenuhnya terisolasi dengan keamanan berlapis. Tidak berbagi resource — <strong>100% milik Anda</strong>.",
    "Dedicated Environment" => "Lingkungan Dedicated",
    "Zero Data Leakage" => "Bebas Kebocoran Data",
    "Independent Scaling" => "Skalabilitas Tanpa Batas"
];

foreach ($updates as $key => $val) {
    $translations[$key] = $val;
}

file_put_contents($idFile, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "id.json updated successfully.\n";
