@extends('layouts.app')

@section('title', 'Messages - Find & Found')

@section('content')
<div class="container py-4">
    <div class="chat-container">
        <div class="row h-100">
            <!-- Conversations List -->
            <div class="col-lg-4 chat-sidebar">
                <div class="chat-header">
                    <h4><i class="fas fa-comments me-2"></i>Messages</h4>
                    <div class="chat-search">
                        <input type="text" class="form-control" placeholder="Search conversations..." id="searchConversations">
                    </div>
                </div>
                
                <div class="conversations-list">
                    @forelse($conversations as $conversation)
                        @php
                            $otherUserConv = $conversation->sender_id === auth()->id() ? $conversation->receiver : $conversation->sender;
                        @endphp
                        <a href="{{ route('chats.show', $otherUserConv->id) }}" 
                           class="conversation-item {{ request()->route('userId') == $otherUserConv->id ? 'active' : '' }}">
                            <div class="conversation-avatar">
                                <img src="{{ $otherUserConv->avatar_url }}" alt="{{ $otherUserConv->name }}">
                                @if(!$conversation->is_read && $conversation->receiver_id === auth()->id())
                                    <div class="unread-indicator"></div>
                                @endif
                            </div>
                            <div class="conversation-content">
                                <div class="conversation-header">
                                    <h6 class="conversation-name">{{ $otherUserConv->name }}</h6>
                                    <small class="conversation-time">{{ $conversation->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="conversation-preview">{{ Str::limit($conversation->message, 50) }}</p>
                                @if($conversation->item)
                                    <div class="conversation-context">
                                        <i class="fas fa-box me-1"></i>
                                        {{ $conversation->item->title }}
                                    </div>
                                @endif
                            </div>
                        </a>
                    @empty
                        <div class="empty-conversations">
                            <i class="fas fa-comments"></i>
                            <h6>No conversations yet</h6>
                            <p>Start a conversation by messaging someone about their item</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Chat Area -->
            <div class="col-lg-8 chat-main">
                @if(isset($otherUser) && isset($chats))
                    <!-- Chat Header -->
                    <div class="chat-main-header">
                        <div class="chat-user-info">
                            <img src="{{ $otherUser->avatar_url }}" alt="{{ $otherUser->name }}" class="chat-user-avatar">
                            <div class="chat-user-details">
                                <h5>{{ $otherUser->name }}</h5>
                                <p class="user-status">Member since {{ $otherUser->created_at->format('M Y') }}</p>
                            </div>
                        </div>
                        <div class="chat-actions">
                            <button class="btn btn-outline-secondary btn-sm" onclick="toggleChatInfo()">
                                <i class="fas fa-info-circle"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Messages Area -->
                    <div class="messages-container" id="messagesContainer">
                        @foreach($chats as $chat)
                            <div class="message {{ $chat->sender_id === auth()->id() ? 'sent' : 'received' }}">
                                <div class="message-content">
                                    {{ $chat->message }}
                                </div>
                                <div class="message-time">
                                    {{ $chat->created_at->format('H:i') }}
                                    @if($chat->sender_id === auth()->id())
                                        <i class="fas fa-check{{ $chat->is_read ? '-double' : '' }} ms-1"></i>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Message Input -->
                    <div class="message-input-area">
                        <form id="messageForm" class="message-form">
                            @csrf
                            <input type="hidden" name="receiver_id" value="{{ $otherUser->id }}">
                            <div class="input-group">
                                <input type="text" class="form-control" name="message" placeholder="Type a message..." 
                                       id="messageInput" autocomplete="off" required>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <!-- Welcome Screen -->
                    <div class="chat-welcome">
                        <div class="welcome-content">
                            <i class="fas fa-comments"></i>
                            <h4>Welcome to Messages</h4>
                            <p>Select a conversation from the left to start chatting, or find items to message their owners.</p>
                            <a href="{{ route('items.index') }}" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Browse Items
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Chat Info Modal -->
<div class="modal fade" id="chatInfoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chat Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if(isset($otherUser))
                    <div class="text-center mb-3">
                        <img src="{{ $otherUser->avatar_url }}" alt="{{ $otherUser->name }}" 
                             class="rounded-circle" width="80" height="80">
                        <h5 class="mt-2">{{ $otherUser->name }}</h5>
                        <p class="text-muted">{{ $otherUser->email }}</p>
                    </div>
                    <div class="chat-info-details">
                        <div class="info-item">
                            <strong>Member since:</strong>
                            <span>{{ $otherUser->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="info-item">
                            <strong>Items posted:</strong>
                            <span>{{ $otherUser->items()->count() }}</span>
                        </div>
                        <div class="info-item">
                            <strong>Successful reunions:</strong>
                            <span>{{ $otherUser->items()->resolved()->count() }}</span>
                        </div>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                @if(isset($otherUser))
                    <form action="{{ route('chats.delete', $otherUser->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" 
                                onclick="return confirm('Delete this conversation?')">
                            Delete Conversation
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection


@push('styles')
<style>
/* Chat Container */
.chat-container {
    height: 80vh;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.chat-container .row {
    height: 100%;
    margin: 0;
}

/* Chat Sidebar */
.chat-sidebar {
    background: #f8f9fa;
    border-right: 1px solid #dee2e6;
    padding: 0;
    display: flex;
    flex-direction: column;
}

.chat-header {
    padding: 1.5rem;
    border-bottom: 1px solid #dee2e6;
    background: white;
}

.chat-header h4 {
    margin-bottom: 1rem;
    color: #212529;
    font-weight: 600;
}

.chat-search input {
    border-radius: 20px;
    padding: 0.5rem 1rem;
}

.conversations-list {
    flex: 1;
    overflow-y: auto;
    padding: 0;
}

.conversation-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.5rem;
    text-decoration: none;
    color: inherit;
    border-bottom: 1px solid #e9ecef;
    transition: background-color 0.2s;
}

.conversation-item:hover {
    background: #e9ecef;
    text-decoration: none;
    color: inherit;
}

.conversation-item.active {
    background: #e3f2fd;
    border-left: 4px solid #2563eb;
}

.conversation-avatar {
    position: relative;
    flex-shrink: 0;
}

.conversation-avatar img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.unread-indicator {
    position: absolute;
    top: -2px;
    right: -2px;
    width: 12px;
    height: 12px;
    background: #dc3545;
    border-radius: 50%;
    border: 2px solid white;
}

.conversation-content {
    flex: 1;
    min-width: 0;
}

.conversation-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.25rem;
}

.conversation-name {
    font-weight: 600;
    margin-bottom: 0;
    color: #212529;
}

.conversation-time {
    color: #6c757d;
    font-size: 0.8rem;
}

.conversation-preview {
    color: #6c757d;
    margin-bottom: 0.25rem;
    font-size: 0.9rem;
    line-height: 1.3;
}

.conversation-context {
    font-size: 0.75rem;
    color: #2563eb;
    background: rgba(37, 99, 235, 0.1);
    padding: 2px 6px;
    border-radius: 8px;
    display: inline-block;
}

.empty-conversations {
    text-align: center;
    padding: 3rem 1rem;
    color: #6c757d;
}

.empty-conversations i {
    font-size: 3rem;
    color: #dee2e6;
    margin-bottom: 1rem;
}

/* Chat Main */
.chat-main {
    padding: 0;
    display: flex;
    flex-direction: column;
}

.chat-main-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #dee2e6;
    background: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chat-user-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.chat-user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.chat-user-details h5 {
    margin-bottom: 0.25rem;
    color: #212529;
    font-weight: 600;
}

.user-status {
    color: #6c757d;
    font-size: 0.8rem;
    margin-bottom: 0;
}

/* Messages Container */
.messages-container {
    flex: 1;
    padding: 1rem;
    overflow-y: auto;
    background: #f8f9fa;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.message {
    display: flex;
    flex-direction: column;
    max-width: 70%;
}

.message.sent {
    align-self: flex-end;
    align-items: flex-end;
}

.message.received {
    align-self: flex-start;
    align-items: flex-start;
}

.message-content {
    padding: 0.75rem 1rem;
    border-radius: 18px;
    word-wrap: break-word;
    line-height: 1.4;
}

.message.sent .message-content {
    background: #2563eb;
    color: white;
    border-bottom-right-radius: 4px;
}

.message.received .message-content {
    background: white;
    color: #212529;
    border: 1px solid #e9ecef;
    border-bottom-left-radius: 4px;
}

.message-time {
    font-size: 0.7rem;
    color: #6c757d;
    margin-top: 0.25rem;
    padding: 0 0.5rem;
}

.message.sent .message-time {
    text-align: right;
}

/* Message Input */
.message-input-area {
    padding: 1rem 1.5rem;
    background: white;
    border-top: 1px solid #dee2e6;
}

.message-form .input-group input {
    border-radius: 20px;
    border-right: none;
}

.message-form .input-group button {
    border-radius: 50%;
    width: 40px;
    height: 40px;
    padding: 0;
    margin-left: 0.5rem;
}

/* Chat Welcome */
.chat-welcome {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
}

.welcome-content {
    text-align: center;
    color: #6c757d;
    max-width: 400px;
    padding: 2rem;
}

.welcome-content i {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 1rem;
}

.welcome-content h4 {
    color: #495057;
    margin-bottom: 1rem;
}

.welcome-content p {
    margin-bottom: 2rem;
    line-height: 1.6;
}

/* Chat Info Modal */
.chat-info-details {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid #e9ecef;
}

/* Responsive */
@media (max-width: 992px) {
    .chat-container {
        height: 90vh;
    }
    
    .chat-sidebar {
        display: {{ isset($otherUser) ? 'none' : 'flex' }};
    }
    
    .chat-main {
        display: {{ isset($otherUser) ? 'flex' : 'none' }};
    }
}

@media (max-width: 768px) {
    .conversation-item {
        padding: 0.75rem 1rem;
    }
    
    .conversation-avatar img {
        width: 40px;
        height: 40px;
    }
    
    .message {
        max-width: 85%;
    }
    
    .message-input-area {
        padding: 0.75rem 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
let messageForm, messageInput, messagesContainer;

document.addEventListener('DOMContentLoaded', function() {
    messageForm = document.getElementById('messageForm');
    messageInput = document.getElementById('messageInput');
    messagesContainer = document.getElementById('messagesContainer');
    
    if (messageForm) {
        messageForm.addEventListener('submit', sendMessage);
    }
    
    // Auto-scroll to bottom
    if (messagesContainer) {
        scrollToBottom();
    }
    
    // Focus message input
    if (messageInput) {
        messageInput.focus();
    }
    
    // Search conversations
    const searchInput = document.getElementById('searchConversations');
    if (searchInput) {
        searchInput.addEventListener('input', filterConversations);
    }
});

function sendMessage(e) {
    e.preventDefault();
    
    const formData = new FormData(messageForm);
    const messageText = formData.get('message').trim();
    
    if (!messageText) return;
    
    // Disable form
    messageInput.disabled = true;
    
    fetch('{{ route("chats.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Add message to UI
            addMessageToUI(data.chat, true);
            messageInput.value = '';
            scrollToBottom();
        }
    })
    .catch(error => {
        console.error('Error sending message:', error);
        alert('Failed to send message. Please try again.');
    })
    .finally(() => {
        messageInput.disabled = false;
        messageInput.focus();
    });
}

function addMessageToUI(chat, isSent) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${isSent ? 'sent' : 'received'}`;
    
    messageDiv.innerHTML = `
        <div class="message-content">${chat.message}</div>
        <div class="message-time">
            ${chat.created_at_human}
            ${isSent ? '<i class="fas fa-check ms-1"></i>' : ''}
        </div>
    `;
    
    messagesContainer.appendChild(messageDiv);
}

function scrollToBottom() {
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
}

function filterConversations() {
    const searchTerm = document.getElementById('searchConversations').value.toLowerCase();
    const conversations = document.querySelectorAll('.conversation-item');
    
    conversations.forEach(conversation => {
        const name = conversation.querySelector('.conversation-name').textContent.toLowerCase();
        const preview = conversation.querySelector('.conversation-preview').textContent.toLowerCase();
        
        if (name.includes(searchTerm) || preview.includes(searchTerm)) {
            conversation.style.display = 'flex';
        } else {
            conversation.style.display = 'none';
        }
    });
}

function toggleChatInfo() {
    const modal = new bootstrap.Modal(document.getElementById('chatInfoModal'));
    modal.show();
}

// Auto-refresh messages every 10 seconds
@if(isset($otherUser) && isset($chats))
setInterval(function() {
    // TODO: Implement real-time message updates
    fetch(`/api/chats/{{ $otherUser->id }}/messages`)
        .then(response => response.json())
        .then(data => {
            // Update messages if new ones are available
            // This is a placeholder for real-time updates
        })
        .catch(error => console.log('Error fetching messages:', error));
}, 10000);
@endif
</script>
@endpush