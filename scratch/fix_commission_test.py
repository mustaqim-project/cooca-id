import re

with open('tests/Feature/Affiliate/CommissionCalculationTest.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Replace Affiliator and Customer with User
content = content.replace('use App\\Models\\Affiliator;', 'use App\\Models\\User;')
content = content.replace('use App\\Models\\Customer;', '')
content = content.replace('Affiliator::create([', "User::factory()->create([\n            'user_type' => 'affiliator',")
content = content.replace('Customer::create([', "User::factory()->create([\n            'user_type' => 'customer',")

# Remove code
content = re.sub(r"'code'\s*=>\s*'[^']+',\n\s*", "", content)

# Replace parent_affiliator_id with referred_by_id
content = content.replace("'parent_affiliator_id'", "'referred_by_id'")

# Replace affiliator_id with referred_by_id in Customer
content = content.replace("'affiliator_id' => $level1Affiliate->id", "'referred_by_id' => $level1Affiliate->id")
content = content.replace("'affiliator_id' => $affiliate->id", "'referred_by_id' => $affiliate->id")
content = content.replace("'affiliator_id' => null", "'referred_by_id' => null")

# Replace customer_id with user_id in License, Subscription, Transaction
content = content.replace("'customer_id'", "'user_id'")

# Replace affiliator_id with referred_by_id in affiliate_commissions and wallets
content = content.replace("'affiliator_id'", "'referred_by_id'")

with open('tests/Feature/Affiliate/CommissionCalculationTest.php', 'w', encoding='utf-8') as f:
    f.write(content)
print("Re-fixed CommissionCalculationTest.php correctly!")
