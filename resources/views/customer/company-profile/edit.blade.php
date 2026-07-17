@extends('layouts.customer')

@section('title', 'Company Profile')
@section('subtitle', 'Manage your corporate organization identity and billing details')

@section('content')
    <div class="space-y-6 max-w-4xl mx-auto">
        <div class="corporate-card">
            <div class="p-6 border-b border-surface-200 dark:border-surface-700 bg-surface-50/50 dark:bg-surface-900/50">
                <h3 class="text-lg font-medium text-surface-900 dark:text-white">Organization Details</h3>
            </div>
            <form action="{{ route('customer.company-profile.update') }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Company Name
                            *</label>
                        <input type="text" name="company_name"
                            value="{{ old('company_name', $companyProfile->company_name) }}" required
                            class="w-full px-3 py-2 border border-surface-300 dark:border-surface-600 rounded-lg dark:bg-surface-800 dark:text-white">
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Industry</label>
                        <input type="text" name="industry" value="{{ old('industry', $companyProfile->industry) }}"
                            class="w-full px-3 py-2 border border-surface-300 dark:border-surface-600 rounded-lg dark:bg-surface-800 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Company
                            Size</label>
                        <select name="company_size"
                            class="w-full px-3 py-2 border border-surface-300 dark:border-surface-600 rounded-lg dark:bg-surface-800 dark:text-white">
                            <option value="">Select size...</option>
                            @foreach (['1-10', '11-50', '51-200', '201-500', '500+'] as $size)
                                <option value="{{ $size }}" @selected(old('company_size', $companyProfile->company_size) === $size)>{{ $size }}
                                    employees</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $companyProfile->phone) }}"
                            class="w-full px-3 py-2 border border-surface-300 dark:border-surface-600 rounded-lg dark:bg-surface-800 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">NPWP (Tax
                            ID)</label>
                        <input type="text" name="npwp" value="{{ old('npwp', $companyProfile->npwp) }}"
                            class="w-full px-3 py-2 border border-surface-300 dark:border-surface-600 rounded-lg dark:bg-surface-800 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Website</label>
                        <input type="url" name="website" value="{{ old('website', $companyProfile->website) }}"
                            class="w-full px-3 py-2 border border-surface-300 dark:border-surface-600 rounded-lg dark:bg-surface-800 dark:text-white">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Address</label>
                        <textarea name="address" rows="3"
                            class="w-full px-3 py-2 border border-surface-300 dark:border-surface-600 rounded-lg dark:bg-surface-800 dark:text-white">{{ old('address', $companyProfile->address) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">City</label>
                        <input type="text" name="city" value="{{ old('city', $companyProfile->city) }}"
                            class="w-full px-3 py-2 border border-surface-300 dark:border-surface-600 rounded-lg dark:bg-surface-800 dark:text-white">
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Province</label>
                        <input type="text" name="province" value="{{ old('province', $companyProfile->province) }}"
                            class="w-full px-3 py-2 border border-surface-300 dark:border-surface-600 rounded-lg dark:bg-surface-800 dark:text-white">
                    </div>
                </div>

                <div class="pt-4 border-t border-surface-200 dark:border-surface-700 flex justify-end">
                    <button type="submit"
                        class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-medium">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
