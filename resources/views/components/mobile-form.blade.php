@props(['action' => '', 'method' => 'POST', 'class' => ''])

<form action="{{ $action }}" method="{{ $method }}" class="space-y-6 {{ $class }}">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{ $slot }}
    </div>
    
    <div class="flex flex-col sm:flex-row gap-4 pt-6">
        <button type="submit" class="btn-mobile bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200">
            {{ $submitText ?? 'Save Changes' }}
        </button>
        @if(isset($cancelUrl))
            <a href="{{ $cancelUrl }}" class="btn-mobile bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-3 px-6 rounded-lg transition-colors duration-200 text-center">
                Cancel
            </a>
        @endif
    </div>
</form>
