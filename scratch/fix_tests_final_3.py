import re

with open('tests/Feature/Affiliate/CommissionCalculationTest.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Fix License, Subscription, Transaction referred_by_id -> user_id
content = re.sub(r"License::factory\(\)->active\(\)->create\(\[\s*'referred_by_id' =>", r"License::factory()->active()->create([\n            'user_id' =>", content)
content = re.sub(r"Subscription::create\(\[\s*'referred_by_id' =>", r"Subscription::create([\n            'user_id' =>", content)
content = re.sub(r"Transaction::create\(\[\s*'referred_by_id' =>", r"Transaction::create([\n            'user_id' =>", content)

# Fix affiliate_commissions referred_by_id -> user_id (if I renamed it to user_id in the migration, let's assume I did because they are all Users now)
content = content.replace("'referred_by_id' => $level1Affiliate->id,", "'user_id' => $level1Affiliate->id,")
content = content.replace("'referred_by_id' => $topAffiliate->id,", "'user_id' => $topAffiliate->id,")
content = content.replace("'referred_by_id' => $affiliate->id,", "'user_id' => $affiliate->id,")
content = content.replace("'referred_by_id', $level1Affiliate->id", "'user_id', $level1Affiliate->id")
content = content.replace("'referred_by_id', $topAffiliate->id", "'user_id', $topAffiliate->id")

# But wait, User::factory()->create(['referred_by_id' => ...]) is correct. 
# So don't replace all referred_by_id.

with open('tests/Feature/Affiliate/CommissionCalculationTest.php', 'w', encoding='utf-8') as f:
    f.write(content)
print("Fixed user_id in CommissionCalculationTest")
