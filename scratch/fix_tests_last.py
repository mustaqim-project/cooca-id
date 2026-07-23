import os
import re

def fix_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    orig = content
    
    # 1. Fix actingAs($user, 'guard') -> actingAs($user)
    # or assertAuthenticatedAs($user, 'guard') -> assertAuthenticatedAs($user)
    content = re.sub(r"actingAs\(([^,]+),\s*'(admin|customer|affiliator)'\)", r"actingAs(\1)", content)
    content = re.sub(r"assertAuthenticatedAs\(([^,]+),\s*'(admin|customer|affiliator)'\)", r"assertAuthenticatedAs(\1)", content)
    
    # 2. Fix User::create missing password
    # Look for User::create([ ... ]) and ensure password is in there if it's a test user.
    # A simple way is to replace User::create([ with User::factory()->create([
    content = re.sub(r"User::create\(\[", r"User::factory()->create([", content)
    
    # 3. Fix missing closing brackets in AdminLoginTest
    if 'AdminLoginTest.php' in filepath:
        content = content.replace("'password' => Hash::make('password123'),\n\n        $response", "'password' => Hash::make('password123'),\n        ]);\n\n        $response")
        content = content.replace("User::factory()->create([", "User::create([") # revert factory just for this if it has password
    
    if 'AffiliatorLoginTest.php' in filepath:
        content = content.replace("'password' => Hash::make('password123'),\n\n        $response", "'password' => Hash::make('password123'),\n        ]);\n\n        $response")
        content = content.replace("User::factory()->create([", "User::create([") # revert factory just for this if it has password
        
    # 4. Fix referral_code in AuthAccessTest
    if 'AuthAccessTest.php' in filepath:
        content = re.sub(r"\'referral_code\'\s*=>\s*.*?,", "", content)
        # AuthAccessTest has UserFactory? It might be using User::factory()->create(['referral_code' => ...])
        
    # 5. Fix TrialEligibilityServiceTest ErpRequest creation
    if 'TrialEligibilityServiceTest.php' in filepath:
        content = content.replace("'customer_id'", "'user_id'")
        
    if 'MidtransWebhookSignatureTest.php' in filepath:
        content = content.replace("'customer_id'", "'user_id'")
        
    if content != orig:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f'Fixed {filepath}')

for root, dirs, files in os.walk('tests'):
    for file in files:
        if file.endswith('.php'):
            fix_file(os.path.join(root, file))
print('Done manual fixes.')
