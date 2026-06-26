import os
import re
from glob import glob

def audit():
    admin_routes_file = r"c:\laragon\www\cooca-id\routes\admin.php"
    controllers_dir = r"c:\laragon\www\cooca-id\app\Http\Controllers\Admin"
    views_dir = r"c:\laragon\www\cooca-id\resources\views\admin"

    with open(admin_routes_file, 'r', encoding='utf-8') as f:
        routes_content = f.read()

    # Extract all route names and methods
    routes = re.findall(r"Route::(get|post|put|patch|delete)\('([^']+)',\s*\[(.*?)\]\)->name\('([^']+)'\)", routes_content)
    
    print("--- ROUTE DEFINITIONS ---")
    print(f"Total Routes found in admin.php: {len(routes)}")

    # Check Controllers for implemented methods
    print("\n--- CONTROLLERS AUDIT ---")
    for controller_file in glob(os.path.join(controllers_dir, "*.php")):
        with open(controller_file, 'r', encoding='utf-8') as f:
            content = f.read()
        name = os.path.basename(controller_file)
        
        methods = re.findall(r"public function (\w+)\(", content)
        print(f"{name}: {methods}")
        
        # Check for direct JSON returns where web redirects are expected
        if "response()->json(" in content and "return view(" not in content and name != 'DashboardController.php':
            # This might be an API controller placed in the web namespace
            print(f"  WARNING: {name} might be returning JSON instead of redirects.")

    # Check Views for "Coming Soon", "TODO", and missing variables
    print("\n--- VIEWS AUDIT ---")
    for root, dirs, files in os.walk(views_dir):
        for file in files:
            if file.endswith(".blade.php"):
                path = os.path.join(root, file)
                with open(path, 'r', encoding='utf-8') as f:
                    content = f.read()
                
                if "Coming Soon" in content or "coming soon" in content.lower():
                    print(f"Coming Soon found in: {os.path.relpath(path, views_dir)}")
                
                # Missing variables like {{ $undefined }}
                # (Too hard to perfectly parse blade, but we can look for generic errors)

if __name__ == "__main__":
    audit()
