<?php

declare(strict_types=1);

namespace App\Services\Security;

use App\Models\ActivityLog;
use App\Models\AuditLog;
use App\Models\Customer;
use App\Models\Affiliator;
use App\Models\Transaction;
use Illuminate\Support\Facades\Cache;

final class FraudDetectionService
{
    /**
     * Analyze a transaction and its associated customer for potential affiliate fraud.
     * Returns true if fraud is detected, false otherwise.
     */
    public function detectAffiliateFraud(Transaction $transaction): bool
    {
        $customer = $transaction->customer;
        
        if (!$customer || (!$customer->affiliator_id && !$customer->referred_by_id)) {
            return false;
        }

        $affiliator = $customer->affiliator;
        if (!$affiliator) {
            return false;
        }

        // 1. Self-referral by Email similarity
        // If customer email matches affiliator email exactly, or first part of email matches
        $customerEmail = strtolower(trim($customer->email));
        $affiliatorEmail = strtolower(trim($affiliator->email ?? ''));

        if ($customerEmail === $affiliatorEmail && !empty($customerEmail)) {
            $this->logFraudAttempt('Self-referral via exact email match', $transaction, $customer, $affiliator->id);
            return true;
        }

        // 2. Velocity Check / IP Check
        // In a real scenario, we might check if multiple transactions occurred from the same IP
        // Since we may not have the direct request IP here, we can check rapid transactions for the same affiliator
        $recentTransactions = Transaction::whereHas('customer', function($query) use ($affiliator) {
                $query->where('affiliator_id', $affiliator->id);
            })
            ->where('created_at', '>=', now()->subHour())
            ->count();

        // If more than 10 referrals in 1 hour from the same affiliator, flag as potential fraud
        if ($recentTransactions > 10) {
            $this->logFraudAttempt('High velocity referrals (>' . $recentTransactions . '/hr)', $transaction, $customer, $affiliator->id);
            return true;
        }

        return false;
    }

    private function logFraudAttempt(string $reason, Transaction $transaction, Customer $customer, string $affiliatorId): void
    {
        // Log to ActivityLog
        ActivityLog::create([
            'log_name' => 'security',
            'description' => 'Fraud Detected: ' . $reason,
            'action' => 'affiliate_fraud_detected',
            'module' => 'Security',
            'subject_type' => Transaction::class,
            'subject_id' => $transaction->id,
            'causer_type' => Customer::class,
            'causer_id' => $customer->id,
            'properties' => [
                'reason' => $reason,
                'referred_by_id' => $affiliatorId,
                'transaction_amount' => $transaction->gross_amount,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Also log to AuditLog as critical risk
        AuditLog::create([
            'user_type' => AuditLog::USER_TYPE_CUSTOMER,
            'user_id' => $customer->id,
            'action' => 'affiliate_fraud_detected',
            'model_type' => Transaction::class,
            'model_id' => $transaction->id,
            'old_values' => [],
            'new_values' => ['reason' => $reason, 'referred_by_id' => $affiliatorId],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'risk_level' => AuditLog::RISK_CRITICAL,
        ]);
    }
}


