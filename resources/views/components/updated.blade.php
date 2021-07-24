<div>
    <p class="text-muted"> 
        {{ empty(trim($slot)) ? __('Added') : $slot }} {{ $date->diffForHumans() }}
        @if (isset($name))
            @if (isset($userId))
                @lang('by') <a href="{{ route('users.show', ['user' => $userId]) }}">{{ $name }}</a>
            @else
                @lang('by') {{ $name }}  
            @endif
        @endif
    </p>
</div>