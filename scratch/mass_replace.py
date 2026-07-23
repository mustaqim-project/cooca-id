import os
import re

def replace_in_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    orig = content
    
    content = content.replace('App\\Models\\Customer', 'App\\Models\\User')
    content = content.replace('App\\Models\\Admin', 'App\\Models\\User')
    content = content.replace('App\\Models\\Affiliator', 'App\\Models\\User')
    
    content = content.replace('Customer::', 'User::')
    content = content.replace('Admin::', 'User::')
    content = content.replace('Affiliator::', 'User::')
    
    # Types in function params
    content = re.sub(r'\bCustomer\s+\$', 'User $', content)
    content = re.sub(r'\bAdmin\s+\$', 'User $', content)
    content = re.sub(r'\bAffiliator\s+\$', 'User $', content)
    
    # Return types
    content = re.sub(r':\s*\??Customer\b', ': ?User', content)
    content = re.sub(r':\s*\??Admin\b', ': ?User', content)
    content = re.sub(r':\s*\??Affiliator\b', ': ?User', content)
    
    # DB columns
    content = content.replace('affiliator_id', 'referred_by_id')
    
    if content != orig:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Updated {filepath}")

for root, dirs, files in os.walk('app'):
    for file in files:
        if file.endswith('.php'):
            replace_in_file(os.path.join(root, file))

for root, dirs, files in os.walk('routes'):
    for file in files:
        if file.endswith('.php'):
            replace_in_file(os.path.join(root, file))

for root, dirs, files in os.walk('database'):
    for file in files:
        if file.endswith('.php'):
            replace_in_file(os.path.join(root, file))
