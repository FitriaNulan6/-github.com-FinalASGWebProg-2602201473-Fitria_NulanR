<!-- resources/views/messages/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    Matches
                </div>
                <div class="list-group list-group-flush">
                    @foreach($matches as $match)
                        <a href="{{ route('messages.show', $match) }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            {{ $match->name }}
                            @if($match->receivedMessages->where('is_read', false)->count() > 0)
                                <span class="badge bg-primary rounded-pill">
                                    {{ $match->receivedMessages->where('is_read', false)->count() }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-body text-center">
                    <h5>Select a match to start chatting</h5>
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script>
$(document).ready(function() {
    const messagesContainer = $('#messages-container');
    messagesContainer.scrollTop(messagesContainer[0].scrollHeight);

    // Enable Pusher
    const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
        encrypted: true
    });

    const channel = pusher.subscribe('chat');
    channel.bind('new-message', function(data) {
        if (data.message.to_user_id === {{ auth()->id() }} && data.message.from_user_id === {{ $user->id }}) {
            appendMessage(data.message);
        }
    });

    $('#message-form').submit(function(e) {
        e.preventDefault();
        const input = $('#message-input');
        const content = input.val().trim();

        if (content) {
            $.post('{{ route('messages.store', $user) }}', {
                _token: '{{ csrf_token() }}',
                content: content
            })
            .done(function(message) {
                appendMessage(message);
                input.val('');
            });
        }
    });

    function appendMessage(message) {
        const isOwn = message.from_user_id === {{ auth()->id() }};
        const html = `
            <div class="mb-3 ${isOwn ? 'text-end' : ''}">
                <div class="d-inline-block p-2 rounded ${isOwn ? 'bg-primary text-white' : 'bg-light'}" style="max-width: 75%;">
                    ${message.content}
                </div>
                <div class="small text-muted">
                    Just now
                </div>
            </div>
        `;
        messagesContainer.append(html);
        messagesContainer.scrollTop(messagesContainer[0].scrollHeight);
    }
});
</script>
@endpush