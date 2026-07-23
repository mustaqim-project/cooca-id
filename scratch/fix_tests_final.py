import os
import re

def fix_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    orig = content
    
    # 1. Replace Models
    content = re.sub(r'\\App\\Models\\(Customer|Admin|Affiliator)', r'\\App\\Models\\User', content)
    content = re.sub(r'use App\\Models\\(Customer|Admin|Affiliator);', r'use App\\Models\\User;', content)
    
    # Clean up duplicate 'use App\Models\User;' if it exists multiple times
    if content.count('use App\\Models\\User;') > 1:
        parts = content.split('use App\\Models\\User;', 1)
        content = parts[0] + 'use App\\Models\\User;' + parts[1].replace('use App\\Models\\User;\n', '')
    
    # 2. Replace FKs in tests & factories
    if 'CommissionCalculationServiceTest.php' in filepath:
        content = content.replace('affiliator_id', 'referred_by_id')
    else:
        # In factories, many ids are now user_id or referred_by_id
        # Let's just do a generic replace for customer_id and admin_id
        content = content.replace("'customer_id'", "'user_id'")
        content = content.replace('"customer_id"', '"user_id"')
        content = content.replace("'admin_id'", "'user_id'")
        
        if 'AffiliateCommissionFactory.php' in filepath or 'AffiliateWithdrawalFactory.php' in filepath:
            content = content.replace("'affiliator_id'", "'user_id'")
        elif 'TicketFactory.php' in filepath or 'TicketReplyFactory.php' in filepath:
            # tickets table has user_id, referred_by_id, admin_id (wait, admin_id was kept in migration?)
            # Wait, ticket migration showed: user_id, referred_by_id, admin_id
            content = content.replace("'affiliator_id'", "'referred_by_id'")
            content = content.replace("'admin_id'", "'admin_id'") # kept as is
            # customer_id -> user_id
            
    # 3. Fix AuthAccessTest.php referral_code error
    if 'AuthAccessTest.php' in filepath:
        content = content.replace("'referral_code' => Str::random(10),", "")
        
    if content != orig:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f'Fixed {filepath}')

for root, dirs, files in os.walk('tests'):
    for file in files:
        if file.endswith('.php'):
            fix_file(os.path.join(root, file))

for root, dirs, files in os.walk('database/factories'):
    for file in files:
        if file.endswith('.php'):
            fix_file(os.path.join(root, file))
print('Done fixing.')
