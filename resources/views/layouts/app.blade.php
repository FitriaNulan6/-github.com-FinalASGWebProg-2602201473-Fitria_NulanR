<!-- Add to resources/views/layouts/app.blade.php in the navbar -->
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
        <i class="fas fa-bell"></i>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <span class="badge bg-danger">
                {{ auth()->user()->unreadNotifications->count() }}
            </span>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
        @forelse(auth()->user()->notifications as $notification)
            <a class="dropdown-item {{ $notification->read_at ? '' : 'bg-light' }}"
               href="{{ route('messages.show', $notification->data['user_id']) }}"
               onclick="event.preventDefault(); markAsRead('{{ $notification->id }}', this);">
                {{ $notification->data['message'] }}
                <small class="text-muted d-block">
                    {{ $notification->created_at->diffForHumans() }}
                </small>
            </a>
        @empty
            <div class="dropdown-item">No notifications</div>
        @endforelse
    </div>
</li>

@push('scripts')
<script>
function markAsRead(notificationId, element) {
    $.post(`/notifications/${notificationId}/mark-as-read`, {
        _token: '{{ csrf_token() }}'
    })
    .done(function() {
        $(element).removeClass('bg-light');
        window.location.href = $(element).attr('href');
    });
}
</script>
@endpush