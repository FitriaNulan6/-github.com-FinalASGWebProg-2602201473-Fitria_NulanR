<!-- resources/views/messages/show.blade.php -->
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
                    @foreach($matches as $matchUser)
                        <a href="{{ route('messages.show', $matchUser) }}" 
                           class="list-group-item list-group-item-action {{ $matchUser->id === $user->id ? 'active' : '' }}">
                            {{ $matchUser->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $user->name }}</h5>
                    <a href="{{ $user->instagram }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="fab fa-instagram"></i> Instagram
                    </a>
                </div>
                <div class="card-body" id="messages-container" style="height: 400px; overflow-y: auto;">
                    @foreach($messages as $message)
                        <div class="mb-3 {{ $message->from_user_id === auth()->id() ? 'text-end' : '' }}">
                            <div class="d-inline-block p-2 rounded {{ $message->from_user_id === auth()->id() ? 'bg-primary text-white' : 'bg-light' }}" style="max-width: 75%;">
                                {{ $message->content }}
                            </div>
                            <div class="small text-muted">
                                {{ $message->created_at->diffForHumans() }}
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="card-footer">
                    <form id="message-form">
                        <div class="input-group">
                            <input type="text" class="form-control" id="message-input" placeholder="Type your message...">
                            <button class="btn btn-primary" type="submit">Send</button>
                        </div>
                    </form>
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