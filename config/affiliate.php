<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Affiliate Commission Rates
    |--------------------------------------------------------------------------
    |
    | Default commission rates for affiliate program.
    | These can be overridden via database settings.
    |
    */
    'commission_rate_level_1' => env('AFFILIATE_COMMISSION_L1', 25), // percentage
    'commission_rate_level_2' => env('AFFILIATE_COMMISSION_L2', 5), // percentage
    
    /*
    |--------------------------------------------------------------------------
    | Withdrawal Settings
    |--------------------------------------------------------------------------
    */
    'withdrawal_fee_bank' => env('AFFILIATE_WITHDRAWAL_FEE_BANK', 2500),
    'withdrawal_fee_ewallet' => env('AFFILIATE_WITHDRAWAL_FEE_EWALLET', 1000),
    'minimum_withdrawal' => env('AFFILIATE_MINIMUM_WITHDRAWAL', 50000),
    
    /*
    |--------------------------------------------------------------------------
    | Trial Duration (in days)
    |--------------------------------------------------------------------------
    */
    'trial_duration_days' => env('AFFILIATE_TRIAL_DURATION', 14),
];
