<?php

declare(strict_types=1);

namespace App\Services\Hostinger;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HostingerDomainService
{
    protected ?string $apiToken;
    protected string $apiUrl;
    protected int $exchangeRate;

    /**
     * Default popular TLDs checked for Indonesia / global business
     */
    protected array $defaultTlds = [
        'com',
        'id',
        'co.id',
        'net',
        'org',
        'shop',
        'online',
        'tech',
        'store',
        'site',
        'xyz',
        'biz',
    ];

    /**
     * Fallback standard market prices (in IDR) per year
     */
    protected array $fallbackPrices = [
        'com'    => 159000,
        'id'     => 225000,
        'co.id'  => 285000,
        'net'    => 195000,
        'org'    => 195000,
        'shop'   => 49000,
        'online' => 35000,
        'tech'   => 59000,
        'store'  => 49000,
        'site'   => 35000,
        'xyz'    => 35000,
        'biz'    => 175000,
    ];

    public function __construct()
    {
        $this->apiToken     = (string) \App\Models\Setting::get('hostinger.api_token', \App\Models\Setting::get('hostinger_api_token', config('services.hostinger.api_token', '')));
        $this->apiUrl       = rtrim((string) \App\Models\Setting::get('hostinger.api_url', config('services.hostinger.api_url', 'https://developers.hostinger.com/api')), '/');
        $this->exchangeRate = (int) \App\Models\Setting::get('hostinger.usd_to_idr_rate', config('services.hostinger.usd_to_idr_rate', 16000));
    }

    /**
     * Check domain availability and combine with pricing
     */
    public function checkDomainWithPrices(string $keyword): array
    {
        $cleanKeyword = strtolower(trim($keyword));
        $cleanKeyword = preg_replace('/^https?:\/\//i', '', $cleanKeyword);
        $cleanKeyword = preg_replace('/^www\./i', '', $cleanKeyword);
        $cleanKeyword = explode('/', $cleanKeyword)[0];

        // If user typed domain with extension, split it
        $exactTld = null;
        if (str_contains($cleanKeyword, '.')) {
            $parts = explode('.', $cleanKeyword, 2);
            $cleanKeyword = $parts[0];
            $exactTld = strtolower($parts[1]);
        }

        // Only allow alphanumeric and dashes
        $cleanKeyword = preg_replace('/[^a-z0-9\-]/', '', $cleanKeyword);

        if (empty($cleanKeyword)) {
            return [
                'success' => false,
                'message' => 'Silakan masukkan nama domain yang valid.',
                'results' => [],
            ];
        }

        $tldsToCheck = $exactTld
            ? array_unique(array_merge([$exactTld], $this->defaultTlds))
            : $this->defaultTlds;

        // Fetch latest live exchange rate on each custom domain request
        $liveRate = $this->fetchLiveExchangeRate();

        $catalogPrices = $this->getCatalogPrices();
        $availabilityData = $this->queryHostingerAvailability($cleanKeyword, $tldsToCheck);

        $results = [];
        $alternatives = [];

        foreach ($availabilityData as $item) {
            $domainName = $item['domain'] ?? '';
            $isAvailable = (bool) ($item['is_available'] ?? false);
            $isAlternative = (bool) ($item['is_alternative'] ?? false);

            // Extract TLD
            $tld = '';
            if (str_contains($domainName, '.')) {
                $parts = explode('.', $domainName, 2);
                $tld = strtolower($parts[1]);
            }

            $priceIdr = $catalogPrices[$tld] ?? $this->fallbackPrices[$tld] ?? 175000;
            $formattedPrice = 'Rp ' . number_format($priceIdr, 0, ',', '.');

            $entry = [
                'domain'          => $domainName,
                'tld'             => $tld,
                'is_available'    => $isAvailable,
                'is_alternative'  => $isAlternative,
                'price_idr'       => $priceIdr,
                'price_formatted' => $formattedPrice,
                'period'          => '1 tahun',
                'badge'           => $isAvailable ? 'Tersedia' : 'Sudah Digunakan',
            ];

            if ($isAlternative) {
                $alternatives[] = $entry;
            } else {
                $results[] = $entry;
            }
        }

        return [
            'success'      => true,
            'keyword'      => $cleanKeyword,
            'exchange_rate'=> $this->exchangeRate,
            'rate_info'    => '1 USD = Rp ' . number_format($this->exchangeRate, 0, ',', '.'),
            'results'      => $results,
            'alternatives' => $alternatives,
        ];
    }

    /**
     * Fetch live USD to IDR exchange rate from Freecurrencyapi on demand
     */
    public function fetchLiveExchangeRate(): float
    {
        try {
            $response = Http::timeout(5)
                ->get('https://api.freecurrencyapi.com/v1/latest', [
                    'apikey'     => 'fca_live_B9PaDQl5CdFfbyOi8ksSiLb7RaCMEjDoQYUx1dSi',
                    'currencies' => 'IDR',
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $rate = (float) ($data['data']['IDR'] ?? 0);
                if ($rate > 0) {
                    $this->exchangeRate = (int) round($rate);
                    // Persist live rate to database setting table
                    \App\Models\Setting::updateOrCreate(
                        ['key' => 'hostinger.usd_to_idr_rate'],
                        [
                            'value' => (string) $this->exchangeRate,
                            'type'  => 'integer',
                            'group' => 'hostinger',
                        ]
                    );
                    return $rate;
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to fetch live exchange rate from Freecurrencyapi: ' . $e->getMessage());
        }

        return (float) $this->exchangeRate;
    }

    /**
     * Query Hostinger API for domain availability with fallback
     */
    protected function queryHostingerAvailability(string $keyword, array $tlds): array
    {
        if (! empty($this->apiToken)) {
            try {
                $response = Http::withToken($this->apiToken)
                    ->timeout(8)
                    ->post("{$this->apiUrl}/domains/v1/availability", [
                        'domain'            => $keyword,
                        'tlds'              => $tlds,
                        'with_alternatives' => true,
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (is_array($data) && ! empty($data)) {
                        return $data;
                    }
                } else {
                    Log::warning('Hostinger Domain Availability API responded with error: ' . $response->status(), [
                        'body' => $response->body(),
                    ]);
                }
            } catch (\Throwable $e) {
                Log::error('Hostinger Domain Availability API request failed: ' . $e->getMessage());
            }
        }

        // Fallback simulation / DNS check if token is missing or API is unreachable
        return $this->simulateAvailability($keyword, $tlds);
    }

    /**
     * Query or cache Hostinger catalog prices
     */
    public function getCatalogPrices(): array
    {
        return Cache::remember('hostinger_domain_catalog_prices', 43200, function () {
            if (! empty($this->apiToken)) {
                try {
                    $response = Http::withToken($this->apiToken)
                        ->timeout(8)
                        ->get("{$this->apiUrl}/billing/v1/catalog", [
                            'category' => 'DOMAIN',
                        ]);

                    if ($response->successful()) {
                        $items = $response->json();
                        $prices = [];

                        if (is_array($items)) {
                            foreach ($items as $item) {
                                $name = strtolower($item['name'] ?? '');
                                // Match .COM, .ID, etc.
                                foreach ($this->defaultTlds as $tld) {
                                    if (str_contains($name, '.' . $tld)) {
                                        $priceItem = $item['prices'][0] ?? null;
                                        if ($priceItem) {
                                            $cents = (int) ($priceItem['price'] ?? 1500);
                                            $currency = strtoupper($priceItem['currency'] ?? 'USD');

                                            if ($currency === 'USD') {
                                                $priceIdr = (int) round(($cents / 100) * $this->exchangeRate);
                                            } elseif ($currency === 'IDR') {
                                                $priceIdr = $cents;
                                            } else {
                                                $priceIdr = (int) round(($cents / 100) * $this->exchangeRate);
                                            }

                                            // Round to thousands
                                            $prices[$tld] = (int) (round($priceIdr / 1000) * 1000);
                                        }
                                    }
                                }
                            }
                        }

                        if (! empty($prices)) {
                            return array_merge($this->fallbackPrices, $prices);
                        }
                    }
                } catch (\Throwable $e) {
                    Log::warning('Failed to fetch Hostinger catalog prices: ' . $e->getMessage());
                }
            }

            return $this->fallbackPrices;
        });
    }

    /**
     * Fallback availability verification using DNS checks
     */
    protected function simulateAvailability(string $keyword, array $tlds): array
    {
        $results = [];
        foreach ($tlds as $tld) {
            $fqdn = "{$keyword}.{$tld}";
            $ip = @gethostbyname($fqdn);
            $isAvailable = ($ip === $fqdn);

            $results[] = [
                'domain'         => $fqdn,
                'is_available'   => $isAvailable,
                'is_alternative' => false,
                'restriction'    => null,
            ];
        }

        return $results;
    }
}
