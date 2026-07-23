import os
import re

def fix_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    orig = content
    
    # Replace static calls
    content = content.replace('Customer::', 'User::')
    content = content.replace('Admin::', 'User::')
    content = content.replace('Affiliator::', 'User::')
    
    # In UserFactory or wherever it's trying to insert referral_code (mostly AuthAccessTest)
    # AuthAccessTest has code doing: User::factory()->create(['referral_code' => ...])
    if 'AuthAccessTest.php' in filepath:
        content = re.sub(r"\'referral_code\'\s*=>\s*.*?,", "", content)
        
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
print('Done fixing bare classes.')
