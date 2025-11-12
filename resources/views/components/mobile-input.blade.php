@props(['name', 'label', 'type' => 'text', 'value' => '', 'placeholder' => '', 'required' => false, 'class' => ''])

<div class="space-y-2">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    
    <input 
        type="{{ $type }}" 
        id="{{ $name }}" 
        name="{{ $name }}" 
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error($name) border-red-500 @enderror {{ $class }}"
    >
    
    @error($name)
        <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
