import re

def refactor():
    with open('app/Http/Controllers/Web/LandingController.php', 'r', encoding='utf-8') as f:
        code = f.read()

    # Replace models
    code = code.replace('use App\\Models\\Admin;', 'use App\\Models\\User;')
    code = code.replace('use App\\Models\\Customer;', '')
    code = code.replace('use App\\Models\\Affiliator;', '')

    # Replace attempt
    code = code.replace("Auth::guard('customer')->attempt($request->only('email', 'password'), $request->boolean('remember'))", "Auth::attempt(array_merge($request->only('email', 'password'), ['user_type' => 'customer']), $request->boolean('remember'))")
    code = code.replace("Auth::guard('affiliator')->attempt($request->only('email', 'password'), $request->boolean('remember'))", "Auth::attempt(array_merge($request->only('email', 'password'), ['user_type' => 'affiliator']), $request->boolean('remember'))")
    code = code.replace("Auth::guard('admin')->attempt($request->only('email', 'password'), $request->boolean('remember'))", "Auth::attempt(array_merge($request->only('email', 'password'), ['user_type' => 'admin']), $request->boolean('remember'))")

    # Replace login
    code = code.replace("Auth::guard('customer')->login(", "Auth::login(")
    code = code.replace("Auth::guard('affiliator')->login(", "Auth::login(")

    # Replace logout
    code = code.replace("Auth::guard('customer')->logout();", "Auth::logout();")
    code = code.replace("Auth::guard('affiliator')->logout();", "Auth::logout();")
    code = code.replace("Auth::guard('admin')->logout();", "Auth::logout();")

    # Replace Socialite
    code = code.replace("Socialite::guard('customer')", "Socialite::driver('google')")

    # Replace broker
    code = code.replace("Password::broker('customers')", "Password::broker()")
    code = code.replace("Password::broker('affiliators')", "Password::broker()")
    code = code.replace("Password::broker('admins')", "Password::broker()")

    # Replace Customer $customer with User $user
    code = code.replace("function (Customer $customer", "function (User $user")
    code = code.replace("function (Affiliator $affiliator", "function (User $user")
    code = code.replace("function (Admin $admin", "function (User $user")

    code = code.replace("$customer->forceFill", "$user->forceFill")
    code = code.replace("$affiliator->forceFill", "$user->forceFill")
    code = code.replace("$admin->forceFill", "$user->forceFill")

    # Replace request user
    code = code.replace("$request->user('customer')", "$request->user()")

    # Other fixes
    code = code.replace("Customer::findOrFail", "User::findOrFail")

    with open('app/Http/Controllers/Web/LandingController.php', 'w', encoding='utf-8') as f:
        f.write(code)

if __name__ == '__main__':
    refactor()
