@extends('layouts.admin')

@section('title', 'Edit API Integration - Admin Panel')
@section('page-title', 'Edit API Integration')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.api-integrations.update', $apiIntegration) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $apiIntegration->name) }}" required
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="label" class="block text-sm font-medium text-gray-700">Label *</label>
                            <input type="text" name="label" id="label" value="{{ old('label', $apiIntegration->label) }}" required
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('label') border-red-500 @enderror">
                            @error('label')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Category *</label>
                            <select name="category" id="category" required
                                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('category') border-red-500 @enderror">
                                <option value="">Select Category</option>
                                @foreach($categories as $key => $label)
                                    <option value="{{ $key }}" {{ old('category', $apiIntegration->category) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3"
                                      class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $apiIntegration->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $apiIntegration->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                Active
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Credentials -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Credentials</h3>
                    <p class="text-sm text-gray-500 mb-4">Update API credentials. Leave value empty to keep existing.</p>
                    
                    @if($apiIntegration->credentials && count($apiIntegration->credentials) > 0)
                        <div class="mb-4 space-y-3">
                            <p class="text-sm font-medium text-gray-700">Existing Credentials:</p>
                            @foreach($apiIntegration->credentials as $key => $value)
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs text-gray-500">Key</label>
                                        <input type="text" value="{{ $key }}" disabled
                                               class="block w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500">Current Value</label>
                                        <input type="password" value="{{ $value }}" disabled
                                               class="block w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-500">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    
                    <div id="credentials-container" class="space-y-4">
                        <div class="credential-field grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Key</label>
                                <input type="text" name="credentials_keys[]" 
                                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                       placeholder="e.g., api_key">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">New Value (leave empty to keep)</label>
                                <input type="text" name="credentials_values[]" 
                                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                       placeholder="New value here">
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" onclick="addCredentialField()" 
                            class="mt-3 text-sm text-indigo-600 hover:text-indigo-900 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add/Update Credential Field
                    </button>
                </div>

                <!-- Config -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Configuration</h3>
                    
                    @if($apiIntegration->config && count($apiIntegration->config) > 0)
                        <div class="mb-4 space-y-3">
                            <p class="text-sm font-medium text-gray-700">Existing Config:</p>
                            @foreach($apiIntegration->config as $key => $value)
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs text-gray-500">Key</label>
                                        <input type="text" value="{{ $key }}" disabled
                                               class="block w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500">Current Value</label>
                                        <input type="text" value="{{ $value }}" disabled
                                               class="block w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-500">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    
                    <div id="config-container" class="space-y-4">
                        <div class="config-field grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Key</label>
                                <input type="text" name="config_keys[]" 
                                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                       placeholder="e.g., timeout">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">New Value</label>
                                <input type="text" name="config_values[]" 
                                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                       placeholder="e.g., 30">
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" onclick="addConfigField()" 
                            class="mt-3 text-sm text-indigo-600 hover:text-indigo-900 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Config Field
                    </button>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3 pt-6 border-t">
                    <a href="{{ route('admin.api-integrations.index') }}" 
                       class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        Update Integration
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function addCredentialField() {
    const container = document.getElementById('credentials-container');
    const newField = document.createElement('div');
    newField.className = 'credential-field grid grid-cols-2 gap-4 mt-4';
    newField.innerHTML = `
        <div>
            <label class="block text-sm font-medium text-gray-700">Key</label>
            <input type="text" name="credentials_keys[]" 
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                   placeholder="e.g., api_key">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">New Value (leave empty to keep)</label>
            <input type="text" name="credentials_values[]" 
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                   placeholder="New value here">
        </div>
    `;
    container.appendChild(newField);
}

function addConfigField() {
    const container = document.getElementById('config-container');
    const newField = document.createElement('div');
    newField.className = 'config-field grid grid-cols-2 gap-4 mt-4';
    newField.innerHTML = `
        <div>
            <label class="block text-sm font-medium text-gray-700">Key</label>
            <input type="text" name="config_keys[]" 
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                   placeholder="e.g., timeout">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">New Value</label>
            <input type="text" name="config_values[]" 
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                   placeholder="e.g., 30">
        </div>
    `;
    container.appendChild(newField);
}
</script>
@endpush
@endsection
