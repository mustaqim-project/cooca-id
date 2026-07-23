import os

# 1. Fix AdminLoginTest and AffiliatorLoginTest (user_type missing)
def add_user_type(filepath, role):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Just add 'user_type' => '...', after 'email' => '...'
    if "'user_type'" not in content:
        content = content.replace("'email' => 'admin@cooca.id',", "'email' => 'admin@cooca.id',\n            'user_type' => 'admin',")
        content = content.replace("'email' => 'affiliator@cooca.id',", "'email' => 'affiliator@cooca.id',\n            'user_type' => 'affiliator',")
        
        # also replace the catch block in AdminLoginTest
        if 'catch' in content:
            content = content.replace("} catch (\Throwable $e) {\n            file_put_contents('trace.txt', $e->getMessage() . \"\\n\" . $e->getTraceAsString());\n            throw $e;\n        }", "}")
            
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Fixed user_type in {filepath}")

add_user_type('tests/Feature/AdminLoginTest.php', 'admin')
add_user_type('tests/Feature/AffiliatorLoginTest.php', 'affiliator')

# 2. Fix parent_affiliator_id -> referred_by_id in CommissionCalculationTest
with open('tests/Feature/Affiliate/CommissionCalculationTest.php', 'r', encoding='utf-8') as f:
    content = f.read()
    
content = content.replace("'parent_affiliator_id'", "'referred_by_id'")
content = content.replace("'affiliator_id'", "'referred_by_id'")
content = content.replace("'user_id'", "'referred_by_id'")

with open('tests/Feature/Affiliate/CommissionCalculationTest.php', 'w', encoding='utf-8') as f:
    f.write(content)
print("Fixed referred_by_id in CommissionCalculationTest")

# 3. Fix ErpRequest user_id in TrialEligibilityServiceTest
with open('tests/Unit/TrialEligibilityServiceTest.php', 'r', encoding='utf-8') as f:
    content = f.read()
    
content = content.replace("['customer_id'", "['user_id'")
content = content.replace("['user_id' => $customer->id", "['user_id' => $customer->id]") # in case it's broken
content = content.replace("['user_id' => $customer->id]", "['user_id' => $customer->id, ")

# Just do a clean replace for ErpRequest creation
import re
content = re.sub(r"ErpRequest::create\(\[\s*'product_id'", r"ErpRequest::create([\n            'user_id' => $customer->id,\n            'product_id'", content)

with open('tests/Unit/TrialEligibilityServiceTest.php', 'w', encoding='utf-8') as f:
    f.write(content)
print("Fixed user_id in TrialEligibilityServiceTest")

# 4. MidtransWebhookSignatureTest - transactions.user_id
with open('tests/Feature/Payment/MidtransWebhookSignatureTest.php', 'r', encoding='utf-8') as f:
    content = f.read()
    
content = re.sub(r"Transaction::create\(\[\s*'invoice_number'", r"Transaction::create([\n            'user_id' => \App\Models\User::factory()->create()->id,\n            'invoice_number'", content)

with open('tests/Feature/Payment/MidtransWebhookSignatureTest.php', 'w', encoding='utf-8') as f:
    f.write(content)
print("Fixed user_id in MidtransWebhookSignatureTest")
