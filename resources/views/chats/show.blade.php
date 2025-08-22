@extends('layouts.app')

@section('title', 'Chat with ' . $otherUser->name . ' - Find & Found')

@section('content')
<div class="container py-4">
    <div class="chat-container">
        <div class="row h-100">
            <!-- Conversations List -->
            <div class="col-lg-4 chat-sidebar d-none d-lg-flex">
                <div class="chat-header">
                    <h4><i class="fas fa-comments me-2"></i>Messages</h4>
                    <div class="chat-search">
                        <input type="text" class="form-control" placeholder="Search conversations..." id="searchConversations">
                    </div>
                </div>
                
                <div class="conversations-list">
                    <!-- This would be populated via AJAX or passed from controller -->
                    <a href="{{ route('chats.index') }}" class="back-to-conversations">
                        <i class="fas fa-arrow-left me-2"></i>Back to all conversations
                    </a>
                </div>
            </div>

            <!-- Chat Area -->
            <div class="col-lg-8 col-12 chat-main">
                <!-- Chat Header -->
                <div class="chat-main-header">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('chats.index') }}" class="btn btn-outline-secondary btn-sm me-3 d-lg-none">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div class="chat-user-info">
                            <img src="{{ $otherUser->avatar_url }}" alt="{{ $otherUser->name }}" class="chat-user-avatar">
                            <div class="chat-user-details">
                                <h5>{{ $otherUser->name }}</h5>
                                <p class="user-status">Member since {{ $otherUser->created_at->format('M Y') }}</p>
                            </div>
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
                    @forelse($chats as $chat)
                        <div class="message {{ $chat->sender_id === auth()->id() ? 'sent' : 'received' }}">
                            <div class="message-content">
                                {{ $chat->message }}
                            </div>
                            <div class="message-time">
                                {{ $chat->created_at->format('H:i') }}
                                @if($chat->sender_id === auth()->id())
                                    <i class="fas fa-check{{ $chat->is_read ? '-double' : '' }} ms-1 {{ $chat->is_read ? 'text-primary' : '' }}"></i>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="empty-messages">
                            <i class="fas fa-comments"></i>
                            <h6>Start the conversation</h6>
                            <p>Send a message to begin chatting with {{ $otherUser->name }}</p>
                        </div>
                    @endforelse
                </div>

                <!-- Message Input -->
                <div class="message-input-area">
                    <form id="messageForm" class="message-form">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $otherUser->id }}">
                        <div class="input-group">
                            <input type="text" class="form-control" name="message" placeholder="Type a message..." 
                                   id="messageInput" autocomplete="off" required>
                            <button type="submit" class="btn btn-primary" id="sendButton">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                    
                </div>
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form action="{{ route('chats.delete', $otherUser->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" 
                            onclick="return confirm('Delete this conversation?')">
                        Delete Conversation
                    </button>
                </form>
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
    padding: 1rem;
}

.back-to-conversations {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    background: #e9ecef;
    border-radius: 8px;
    text-decoration: none;
    color: #495057;
    font-weight: 500;
    transition: background-color 0.2s;
}

.back-to-conversations:hover {
    background: #dee2e6;
    text-decoration: none;
    color: #495057;
}

/* Chat Main */
.chat-main {
    padding: 0;
    display: flex;
    flex-direction: column;
    height: 100%; /* 🔥 penting biar flex 1 di messages-container kepake */
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
    min-height: 0; /* 🔥 wajib di flexbox supaya overflow jalan */
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

.empty-messages {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    text-align: center;
}

.empty-messages i {
    font-size: 3rem;
    color: #dee2e6;
    margin-bottom: 1rem;
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

/* Loading States */
.message-form.loading input {
    opacity: 0.6;
}

.message-form.loading button {
    opacity: 0.6;
    pointer-events: none;
}

/* Responsive */
@media (max-width: 992px) {
    .chat-container {
        height: 90vh;
    }
}

@media (max-width: 768px) {
    .message {
        max-width: 85%;
    }
    
    .message-input-area {
        padding: 0.75rem 1rem;
    }
    
    .chat-main-header {
        padding: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
let messageForm, messageInput, messagesContainer, sendButton;

document.addEventListener('DOMContentLoaded', function() {
    messageForm = document.getElementById('messageForm');
    messageInput = document.getElementById('messageInput');
    messagesContainer = document.getElementById('messagesContainer');
    sendButton = document.getElementById('sendButton');
    
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
    
    // Mark messages as read
    markMessagesAsRead();
});

function sendMessage(e) {
    e.preventDefault();
    
    const formData = new FormData(messageForm);
    const messageText = formData.get('message').trim();
    
    if (!messageText) return;
    
    // Show loading state
    messageForm.classList.add('loading');
    messageInput.disabled = true;
    sendButton.disabled = true;
    
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
        } else {
            throw new Error(data.message || 'Failed to send message');
        }
    })
    .catch(error => {
        console.error('Error sending message:', error);
        alert('Failed to send message. Please try again.');
    })
    .finally(() => {
        messageForm.classList.remove('loading');
        messageInput.disabled = false;
        sendButton.disabled = false;
        messageInput.focus();
    });
}

function addMessageToUI(chat, isSent) {
    // Remove empty state if exists
    const emptyMessages = messagesContainer.querySelector('.empty-messages');
    if (emptyMessages) {
        emptyMessages.remove();
    }
    
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${isSent ? 'sent' : 'received'}`;
    
    messageDiv.innerHTML = `
        <div class="message-content">${escapeHtml(chat.message)}</div>
        <div class="message-time">
            ${new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})}
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

function markMessagesAsRead() {
    fetch('{{ route("api.chats.mark-read", $otherUser->id) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update read indicators
            const checkIcons = document.querySelectorAll('.message.received .fa-check');
            checkIcons.forEach(icon => {
                icon.classList.remove('fa-check');
                icon.classList.add('fa-check-double', 'text-primary');
            });
        }
    })
    .catch(error => console.log('Error marking messages as read:', error));
}

function toggleChatInfo() {
    const modal = new bootstrap.Modal(document.getElementById('chatInfoModal'));
    modal.show();
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Auto-refresh messages every 5 seconds
setInterval(function() {
    // TODO: Implement real-time message updates via AJAX
    // For now, we can poll for new messages
    fetchNewMessages();
}, 5000);

function fetchNewMessages() {
    const lastMessage = messagesContainer.querySelector('.message:last-child');
    if (!lastMessage) return;
    
    // This would need to be implemented in the backend
    // to return only new messages since the last one
}

// Enter key to send message
messageInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        messageForm.dispatchEvent(new Event('submit'));
    }
});
</script>
@endpush