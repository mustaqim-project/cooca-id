import os
import re

def fix_acting_as(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    orig = content
    content = re.sub(r"->actingAs\(([^,]+),\s*'[^']+'\)", r'->actingAs(\1)', content)
    
    if content != orig:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f'Fixed {filepath}')

for root, dirs, files in os.walk('tests'):
    for file in files:
        if file.endswith('.php'):
            fix_acting_as(os.path.join(root, file))
print('Done fixing actingAs')
