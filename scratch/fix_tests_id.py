import os
import re

def fix_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    orig = content
    
    # customer_id -> user_id
    content = content.replace("'customer_id'", "'user_id'")
    content = content.replace('"customer_id"', '"user_id"')
    
    # In CommissionCalculationTest: User::create doesn't have password.
    # Add password if creating user
    content = re.sub(r"(User::create\(\[.*?)\s*\]\);", r"\1, 'password' => bcrypt('password')];", content, flags=re.DOTALL)
    
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
