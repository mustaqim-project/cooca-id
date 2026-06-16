import os
import re

html_dir = r"c:\laragon\www\cooca-id\resources\views\html"
pages_dir = r"c:\laragon\www\cooca-id\resources\views\pages"

file_map = {
    "home.html": "home/index.blade.php",
    "about.html": "about/index.blade.php",
    "pricing.html": "pricing/index.blade.php",
    "contact.html": "contact/index.blade.php",
    "affiliate.html": "affiliate/index.blade.php",
    "solution.html": "solutions/index.blade.php",
    "blog.html": "blog/index.blade.php",
    "blog detail.html": "blog/detail.blade.php",
    "Privacy Policy.html": "legal/privacy.blade.php",
    "Terms of Service.html": "legal/terms.blade.php",
    "customer login.html": "auth/customer/login.blade.php",
    "customer_register.html": "auth/customer/register.blade.php",
}

route_map = {
    "index": "home",
    "home": "home",
    "about": "about",
    "pricing": "pricing",
    "contact": "contact",
    "affiliate": "affiliate",
    "solution": "solutions",
    "solutions": "solutions",
    "blog": "blog.index",
    "blog detail": "blog.show", # Needs slug usually, but we'll just put a placeholder or leave it
    "Privacy Policy": "privacy",
    "Terms of Service": "terms",
    "customer login": "customer.login",
    "customer_register": "customer.register",
    "login": "customer.login", # default login
    "register": "customer.register"
}

def fix_routes(content):
    def replacer(match):
        orig = match.group(1)
        # handle login.html?type=affiliate
        if '?' in orig:
            base, qs = orig.split('?', 1)
            base = base.replace('.html', '')
            if 'affiliate' in qs:
                return f'href="{{{{ route(\'affiliator.login\') }}}}"'
        base = orig.replace('.html', '')
        # Handle '#' links
        if base == '#' or orig.startswith('#'):
            return f'href="{orig}"'
            
        if base in route_map:
            return f'href="{{{{ route(\'{route_map[base]}\') }}}}"'
        
        return match.group(0) # fallback
        
    content = re.sub(r'href="([^"]+)"', replacer, content)
    return content

def fix_assets(content):
    content = re.sub(r'src="(assets/[^"]+)"', r'src="{{ asset(\'\1\') }}"', content)
    return content

for html_file, blade_file in file_map.items():
    html_path = os.path.join(html_dir, html_file)
    blade_path = os.path.join(pages_dir, blade_file)
    
    if not os.path.exists(html_path):
        print(f"File not found: {html_path}")
        continue
        
    with open(html_path, 'r', encoding='utf-8') as f:
        content = f.read()
        
    # Extract body content
    body_match = re.search(r'<body>(.*?)</body>', content, re.IGNORECASE | re.DOTALL)
    if body_match:
        body_content = body_match.group(1)
    else:
        body_content = content # fallback
        
    # Remove loader, whatsapp, navbar, mobile menu
    body_content = re.sub(r'<!-- PAGE LOADER -->.*?</div>\s*</div>\s*</div>', '', body_content, flags=re.IGNORECASE | re.DOTALL)
    body_content = re.sub(r'<!-- FLOATING WHATSAPP -->.*?</a>', '', body_content, flags=re.IGNORECASE | re.DOTALL)
    body_content = re.sub(r'<!-- UNIFIED NAVBAR -->.*?</nav>', '', body_content, flags=re.IGNORECASE | re.DOTALL)
    body_content = re.sub(r'<!-- MOBILE OFFCANVAS -->.*?</div>\s*</div>\s*</div>', '', body_content, flags=re.IGNORECASE | re.DOTALL)
    
    # Replace final CTA with include
    if '<!-- FINAL CTA -->' in body_content:
        body_content = re.sub(r'<!-- FINAL CTA -->.*?</section>', "@include('partials.cta')", body_content, flags=re.IGNORECASE | re.DOTALL)
        
    # Remove Footer
    body_content = re.sub(r'<!-- UNIFIED FOOTER -->.*?</footer>', '', body_content, flags=re.IGNORECASE | re.DOTALL)
    
    # Remove scripts
    body_content = re.sub(r'<script.*?</script>', '', body_content, flags=re.IGNORECASE | re.DOTALL)
    
    # Clean up multiple blank lines
    body_content = re.sub(r'\n\s*\n', '\n\n', body_content).strip()
    
    # Fix routes and assets
    body_content = fix_routes(body_content)
    body_content = fix_assets(body_content)
    
    final_blade = f"""@extends('layouts.guest')

@section('title', '{html_file.replace('.html', '').title()} - ' . ($setting->company_name ?? config('app.name')))

@section('content')
{body_content}
@endsection
"""
    
    os.makedirs(os.path.dirname(blade_path), exist_ok=True)
    with open(blade_path, 'w', encoding='utf-8') as f:
        f.write(final_blade)
        
    print(f"Migrated {html_file} -> {blade_file}")

print("Done.")
