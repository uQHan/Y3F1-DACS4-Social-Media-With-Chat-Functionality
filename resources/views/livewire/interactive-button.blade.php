<div>
    <button wire:click="toggleAction"
        class="flex items-center space-x-1 hover:{{ $isActive ? 'text-blue-500' : 'text-gray-500' }}"
        aria-label="{{ ucfirst($type) }}">
        <i class="{{ $isActive ? 'fas' : 'far' }} fa-{{ $icon }}"></i>
        <span class="ml-2">{{ $count }}</span>
    </button>
</div>