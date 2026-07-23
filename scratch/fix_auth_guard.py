import os
import re

for root, _, files in os.walk('app/Http/Controllers'):
    for file in files:
        if file.endswith('.php'):
            filepath = os.path.join(root, file)
            with open(filepath, 'r', encoding='utf-8') as f:
                content = f.read()
            
            orig = content
            content = re.sub(r"Auth::guard\('customer'\)->user\(\)", "Auth::user()", content)
            content = re.sub(r"Auth::guard\('customer'\)->id\(\)", "Auth::id()", content)
            content = re.sub(r"Auth::guard\('affiliator'\)->user\(\)", "Auth::user()", content)
            content = re.sub(r"Auth::guard\('affiliator'\)->id\(\)", "Auth::id()", content)
            content = re.sub(r"Auth::guard\('admin'\)->user\(\)", "Auth::user()", content)
            content = re.sub(r"Auth::guard\('admin'\)->id\(\)", "Auth::id()", content)
            
            if content != orig:
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(content)
                print(f"Fixed {filepath}")
print("Done fixing Controllers")
