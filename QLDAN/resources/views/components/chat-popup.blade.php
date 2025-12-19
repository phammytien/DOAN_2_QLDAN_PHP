{{-- Chat Popup Component --}}
<link rel="stylesheet" href="{{ asset('css/chat.css') }}?v={{ time() }}">

{{-- Chat Button --}}
<div class="chat-button" id="chatButton">
    <i class="bi bi-chat-dots-fill"></i>
    <span class="unread-badge" id="chatUnreadBadge" style="display: none;">0</span>
</div>

{{-- Chat Popup --}}
<div class="chat-popup" id="chatPopup">
    {{-- Chat Header --}}
    <div class="chat-header">
        <div class="chat-header-left">
            <i class="bi bi-chat-dots-fill"></i>
            <h3>Tin nhắn</h3>
        </div>
        <button class="chat-close" id="chatClose">
            <i class="bi bi-x"></i>
        </button>
    </div>

    {{-- Conversation List View --}}
    <div class="conversation-list" id="conversationList">
        <div class="loading">
            <i class="bi bi-arrow-repeat"></i>
            <p>Đang tải...</p>
        </div>
    </div>


    {{-- Message Thread View --}}
    <div class="message-thread" id="messageThread">
        {{-- Thread Header --}}
        <div class="thread-header">
            <button class="back-button" id="backToList">
                <i class="bi bi-arrow-left"></i>
            </button>
            <div class="conversation-avatar" id="threadAvatar">
                <i class="bi bi-person-fill"></i>
            </div>
            <div class="thread-name" id="threadName">Giảng viên</div>
        </div>

        {{-- Messages Container --}}
        <div class="messages-container" id="messagesContainer">
            <div class="loading">
                <i class="bi bi-arrow-repeat"></i>
                <p>Đang tải tin nhắn...</p>
            </div>
        </div>
    </div>

    {{-- Message Input - MOVED OUTSIDE message-thread --}}
    <div class="message-input-container" id="messageInputContainer">
        <div class="file-preview" id="filePreview" style="display: none;">
            <div class="file-preview-info">
                <i class="bi bi-file-earmark"></i>
                <span id="filePreviewName"></span>
            </div>
            <button class="file-preview-remove" id="filePreviewRemove">
                <i class="bi bi-x"></i>
            </button>
        </div>
        <div class="message-input-wrapper">
            <label class="file-input-label">
                <i class="bi bi-paperclip"></i>
                <input type="file" id="fileInput" accept="*/*">
            </label>
            <textarea 
                class="message-input" 
                id="messageInput" 
                placeholder="Aa" 
                rows="1"
            ></textarea>
            <button class="send-button" id="sendButton" disabled>
                <i class="bi bi-send-fill"></i>
            </button>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="chatConfirmModal" class="chat-confirm-modal">
    <div class="chat-confirm-content">
        <div class="chat-confirm-icon">
            <i class="bi bi-exclamation-triangle"></i>
        </div>
        <h3 class="chat-confirm-title" id="chatConfirmTitle">Xác nhận</h3>
        <p class="chat-confirm-message" id="chatConfirmMessage">Bạn có chắc chắn muốn thực hiện?</p>
        <div class="chat-confirm-buttons">
            <button type="button" id="chatConfirmCancel" class="chat-btn chat-btn-secondary">Hủy</button>
            <button type="button" id="chatConfirmOk" class="chat-btn chat-btn-danger">Xóa</button>
        </div>
    </div>
</div>

<script src="{{ asset('js/chat.js') }}"></script>
