import os
import re

def modify_file(path, replacements):
    with open(path, 'r', encoding='utf-8') as f:
        content = f.read()
    orig = content
    for old, new in replacements:
        content = content.replace(old, new)
    if content != orig:
        with open(path, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Fixed {path}")

# 1. AdminLoginTest
modify_file('tests/Feature/AdminLoginTest.php', [
    ("User::create([", "User::factory()->create(["),
    ("'password' => Hash::make('password123'),\n        ]);", "'password' => Hash::make('password123'),\n        ]);"), # if any leftovers
])
# Clean up extra brackets if factory is used
with open('tests/Feature/AdminLoginTest.php', 'r') as f:
    c = f.read()
c = c.replace("User::factory()->create([\n            'name' => 'Super Admin',\n            'email' => 'admin@cooca.id',\n            'password' => Hash::make('password123'),\n        ]);", 
              "User::factory()->create([\n            'name' => 'Super Admin',\n            'email' => 'admin@cooca.id',\n            'password' => \Illuminate\Support\Facades\Hash::make('password123'),\n        ]);")
with open('tests/Feature/AdminLoginTest.php', 'w') as f:
    f.write(c)


# 2. AffiliatorLoginTest
modify_file('tests/Feature/AffiliatorLoginTest.php', [
    ("User::create([", "User::factory()->create([")
])

# 3. TrialEligibilityServiceTest
modify_file('tests/Unit/TrialEligibilityServiceTest.php', [
    ("'customer_id' => $customer->id", "'user_id' => $customer->id")
])

# 4. CommissionCalculationTest
modify_file('tests/Feature/Affiliate/CommissionCalculationTest.php', [
    ("'code' => ", "'id' => "), # Wait, code => TOP001 was probably referral code. Let's just remove code.
    ("'code' => 'TOP001'", ""),
    ("'code' => 'AFF001'", ""),
    ("'affiliator_id'", "'user_id'"),
])

with open('tests/Feature/Affiliate/CommissionCalculationTest.php', 'r') as f:
    c = f.read()
    c = re.sub(r"'code'\s*=>\s*'.*?',", "", c)
    c = c.replace("'affiliator_id' => null,", "")
with open('tests/Feature/Affiliate/CommissionCalculationTest.php', 'w') as f:
    f.write(c)

# 5. AuthAccessTest
with open('tests/Feature/AuthAccessTest.php', 'r') as f:
    c = f.read()
    c = re.sub(r"'referral_code'\s*=>\s*.*?,", "", c)
with open('tests/Feature/AuthAccessTest.php', 'w') as f:
    f.write(c)

# 6. CustomerLoginTest / CustomerMenuTest
with open('tests/Feature/CustomerLoginTest.php', 'r') as f:
    c = f.read()
    c = c.replace("assertAuthenticatedAs($customer, 'customer')", "assertAuthenticatedAs($customer, 'web')")
with open('tests/Feature/CustomerLoginTest.php', 'w') as f:
    f.write(c)
    
with open('tests/Feature/CustomerMenuTest.php', 'r') as f:
    c = f.read()
    c = c.replace("actingAs($customer, 'customer')", "actingAs($customer)")
with open('tests/Feature/CustomerMenuTest.php', 'w') as f:
    f.write(c)
    
# 7. MidtransWebhookSignatureTest
with open('tests/Feature/Payment/MidtransWebhookSignatureTest.php', 'r') as f:
    c = f.read()
    c = c.replace("'customer_id'", "'user_id'")
with open('tests/Feature/Payment/MidtransWebhookSignatureTest.php', 'w') as f:
    f.write(c)

# Ensure UserFactory doesn't have referral_code (should be clean)
with open('database/factories/UserFactory.php', 'r') as f:
    c = f.read()
    c = re.sub(r"'referral_code'\s*=>\s*.*?,", "", c)
with open('database/factories/UserFactory.php', 'w') as f:
    f.write(c)
    
print("Fixed files.")
