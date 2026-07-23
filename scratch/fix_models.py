import os
import re

for root, _, files in os.walk('app/Models'):
    for file in files:
        if file.endswith('.php'):
            filepath = os.path.join(root, file)
            with open(filepath, 'r', encoding='utf-8') as f:
                content = f.read()
            
            orig = content
            content = content.replace("'customer_id'", "'user_id'")
            # we also replace any property definitions
            content = content.replace("$customer_id", "$user_id")
            
            if content != orig:
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(content)
                print(f"Fixed {filepath}")
print("Done fixing Models")
