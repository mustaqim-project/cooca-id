<!DOCTYPE html>
<html>
<head>
    <title>AI Quota Warning</title>
</head>
<body>
    <h2>Hello {{ $customer->business_name ?? $customer->name }},</h2>

    <p>This is an automated notification regarding your AI Gateway usage.</p>
    
    <p>Your current usage cycle has reached <strong>{{ $percentage }}%</strong> of its allowed token quota.</p>

    <ul>
        <li><strong>Tokens Used:</strong> {{ number_format($cycle->tokens_used) }}</li>
        <li><strong>Total Quota:</strong> {{ number_format($cycle->token_quota) }}</li>
        <li><strong>Cycle Ends On:</strong> {{ $cycle->cycle_end->format('M d, Y') }}</li>
    </ul>

    @if($percentage >= 100)
    <p style="color: red;"><strong>You have exhausted your AI token quota.</strong> Please upgrade your plan or wait for the next billing cycle to continue using AI services.</p>
    @else
    <p>Please monitor your usage to avoid disruption in service.</p>
    @endif

    <p>Thank you,<br>The Cooca Team</p>
</body>
</html>
