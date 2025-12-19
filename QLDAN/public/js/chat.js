// Chat System JavaScript
(function () {
    'use strict';

    // Configuration
    const config = {
        role: document.querySelector('meta[name="user-role"]')?.content || 'SinhVien',
        baseUrl: window.location.origin,
    };

    // Get route prefix based on role
    const getRoutePrefix = () => {
        return config.role === 'GiangVien' ? '/giangvien' : '/sinhvien';
    };

    // DOM Elements
    const elements = {
        chatButton: document.getElementById('chatButton'),
        chatPopup: document.getElementById('chatPopup'),
        chatClose: document.getElementById('chatClose'),
        conversationList: document.getElementById('conversationList'),
        messageThread: document.getElementById('messageThread'),
        messagesContainer: document.getElementById('messagesContainer'),
        messageInput: document.getElementById('messageInput'),
        sendButton: document.getElementById('sendButton'),
        fileInput: document.getElementById('fileInput'),
        filePreview: document.getElementById('filePreview'),
        filePreviewName: document.getElementById('filePreviewName'),
        filePreviewRemove: document.getElementById('filePreviewRemove'),
        backToList: document.getElementById('backToList'),
        threadName: document.getElementById('threadName'),
        threadAvatar: document.getElementById('threadAvatar'),
        unreadBadge: document.getElementById('chatUnreadBadge'),
        confirmModal: document.getElementById('chatConfirmModal'),
        confirmTitle: document.getElementById('chatConfirmTitle'),
        confirmMessage: document.getElementById('chatConfirmMessage'),
        confirmOk: document.getElementById('chatConfirmOk'),
        confirmCancel: document.getElementById('chatConfirmCancel'),
    };

    // State
    let state = {
        currentConversationId: null,
        selectedFile: null,
        conversations: [],
    };

    // Initialize
    function init() {
        attachEventListeners();
        loadUnreadCount();
        // Poll for unread count every 30 seconds
        setInterval(loadUnreadCount, 30000);
    }

    // Event Listeners
    function attachEventListeners() {
        elements.chatButton.addEventListener('click', toggleChat);
        elements.chatClose.addEventListener('click', closeChat);
        elements.sendButton.addEventListener('click', sendMessage);
        elements.messageInput.addEventListener('input', handleInputChange);
        elements.messageInput.addEventListener('keypress', handleKeyPress);
        elements.fileInput.addEventListener('change', handleFileSelect);
        elements.filePreviewRemove.addEventListener('click', removeFile);
        elements.backToList.addEventListener('click', showConversationList);
    }

    // Toggle Chat Popup
    function toggleChat() {
        const isActive = elements.chatPopup.classList.toggle('active');
        if (isActive) {
            loadConversations();
        }
    }

    // Close Chat
    function closeChat() {
        elements.chatPopup.classList.remove('active');
    }

    // Load Conversations
    async function loadConversations() {
        try {
            const response = await fetch(`${config.baseUrl}${getRoutePrefix()}/chat/conversations`);
            if (!response.ok) throw new Error('Failed to load conversations');

            const conversations = await response.json();
            state.conversations = conversations;
            renderConversations(conversations);
        } catch (error) {
            console.error('Error loading conversations:', error);
            elements.conversationList.innerHTML = `
                <div class="empty-state">
                    <i class="bi bi-chat-x"></i>
                    <p>Không thể tải danh sách hội thoại</p>
                </div>
            `;
        }
    }

    // Render Conversations
    function renderConversations(conversations) {
        if (conversations.length === 0) {
            elements.conversationList.innerHTML = `
                <div class="empty-state">
                    <i class="bi bi-chat-dots"></i>
                    <p>Chưa có cuộc trò chuyện nào</p>
                </div>
            `;
            return;
        }

        const html = conversations.map(conv => {
            const displayName = conv.lecturer_name || conv.student_name || 'Người dùng';
            const avatar = conv.lecturer_avatar || conv.student_avatar;
            const initials = displayName.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();

            return `
                <div class="conversation-item" data-id="${conv.id}">
                    <div class="conversation-avatar">
                        ${avatar ? `<img src="${config.baseUrl}/${avatar}" alt="${displayName}">` : initials}
                    </div>
                    <div class="conversation-info">
                        <div class="conversation-name">${displayName}</div>
                        <div class="conversation-last-message">${conv.last_message || 'Chưa có tin nhắn'}</div>
                    </div>
                    <div class="conversation-meta">
                        ${conv.last_message_time ? `<div class="conversation-time">${conv.last_message_time}</div>` : ''}
                        ${conv.unread_count > 0 ? `<div class="conversation-unread">${conv.unread_count}</div>` : ''}
                    </div>
                    <button class="conversation-delete-btn" data-conv-id="${conv.id}">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            `;
        }).join('');

        elements.conversationList.innerHTML = html;

        // Attach click handlers
        document.querySelectorAll('.conversation-item').forEach(item => {
            item.addEventListener('click', (e) => {
                // Don't open conversation if clicking delete button
                if (e.target.closest('.conversation-delete-btn')) {
                    return;
                }
                const convId = item.dataset.id;
                openConversation(convId);
            });
        });

        // Attach delete handlers
        document.querySelectorAll('.conversation-delete-btn').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                const convId = this.dataset.convId;
                deleteConversation(convId);
            });
        });
    }

    // Open Conversation
    async function openConversation(conversationId) {
        state.currentConversationId = conversationId;

        // Find conversation data
        const conv = state.conversations.find(c => c.id == conversationId);
        if (conv) {
            const displayName = conv.lecturer_name || conv.student_name || 'Người dùng';
            elements.threadName.textContent = displayName;

            const avatar = conv.lecturer_avatar || conv.student_avatar;
            const initials = displayName.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
            elements.threadAvatar.innerHTML = avatar
                ? `<img src="${config.baseUrl}/${avatar}" alt="${displayName}">`
                : initials;
        }

        // Show message thread
        elements.conversationList.style.display = 'none';
        elements.messageThread.classList.add('active');

        // Load messages
        await loadMessages(conversationId);

        // Mark as read
        markAsRead(conversationId);
    }

    // Load Messages
    async function loadMessages(conversationId) {
        try {
            elements.messagesContainer.innerHTML = '<div class="loading"><i class="bi bi-arrow-repeat"></i><p>Đang tải...</p></div>';

            const response = await fetch(`${config.baseUrl}${getRoutePrefix()}/chat/${conversationId}`);
            if (!response.ok) throw new Error('Failed to load messages');

            const data = await response.json();
            renderMessages(data.messages);
        } catch (error) {
            console.error('Error loading messages:', error);
            elements.messagesContainer.innerHTML = `
                <div class="empty-state">
                    <i class="bi bi-chat-x"></i>
                    <p>Không thể tải tin nhắn</p>
                </div>
            `;
        }
    }

    // Render Messages
    function renderMessages(messages) {
        if (messages.length === 0) {
            elements.messagesContainer.innerHTML = `
                <div class="empty-state">
                    <i class="bi bi-chat-dots"></i>
                    <p>Chưa có tin nhắn. Hãy bắt đầu cuộc trò chuyện!</p>
                </div>
            `;
            return;
        }

        const html = messages.map(msg => {
            const messageClass = msg.is_mine ? 'sent' : 'received';
            return `
                <div class="message ${messageClass}" data-message-id="${msg.id}">
                    ${msg.is_mine ? `<button class="message-delete-btn" data-message-id="${msg.id}"><i class="bi bi-trash"></i></button>` : ''}
                    <div class="message-bubble">
                        ${msg.message ? `<p class="message-text">${escapeHtml(msg.message)}</p>` : ''}
                        ${msg.file_name ? `
                            <a href="${msg.file_url}" target="__blank" class="message-file">
                                <i class="bi bi-file-earmark"></i>
                                <span>${escapeHtml(msg.file_name)}</span>
                            </a>
                        ` : ''}
                        <div class="message-time">${msg.created_at}</div>
                    </div>
                </div>
            `;
        }).join('');

        elements.messagesContainer.innerHTML = html;
        // Attach delete button handlers
        attachDeleteHandlers();
        scrollToBottom();
    }

    // Send Message
    async function sendMessage() {
        const message = elements.messageInput.value.trim();
        const file = state.selectedFile;

        if (!message && !file) return;

        const formData = new FormData();
        if (state.currentConversationId) {
            formData.append('conversation_id', state.currentConversationId);
        }
        if (message) {
            formData.append('message', message);
        }
        if (file) {
            formData.append('file', file);
        }

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        try {
            elements.sendButton.disabled = true;

            const response = await fetch(`${config.baseUrl}${getRoutePrefix()}/chat/send`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: formData,
            });

            if (!response.ok) throw new Error('Failed to send message');

            const data = await response.json();

            // Clear input
            elements.messageInput.value = '';
            removeFile();

            // Add message to UI
            if (data.message) {
                addMessageToUI(data.message, true);
            }

            // Reload conversation list to update last message
            loadConversations();

        } catch (error) {
            console.error('Error sending message:', error);
            alert('Không thể gửi tin nhắn. Vui lòng thử lại.');
        } finally {
            elements.sendButton.disabled = false;
        }
    }

    // Add Message to UI
    function addMessageToUI(message, isMine) {
        const messageClass = isMine ? 'sent' : 'received';
        const html = `
            <div class="message ${messageClass}" data-message-id="${message.id}">
                ${isMine ? `<button class="message-delete-btn" data-message-id="${message.id}"><i class="bi bi-trash"></i></button>` : ''}
                <div class="message-bubble">
                    ${message.message ? `<p class="message-text">${escapeHtml(message.message)}</p>` : ''}
                    ${message.file_name ? `
                        <a href="${message.file_url}" target="_blank" class="message-file">
                            <i class="bi bi-file-earmark"></i>
                            <span>${escapeHtml(message.file_name)}</span>
                        </a>
                    ` : ''}
                    <div class="message-time">${message.created_at}</div>
                </div>
            </div>
        `;

        // Remove empty state if exists
        const emptyState = elements.messagesContainer.querySelector('.empty-state');
        if (emptyState) {
            emptyState.remove();
        }

        elements.messagesContainer.insertAdjacentHTML('beforeend', html);
        // Attach delete handler to new message
        attachDeleteHandlers();
        scrollToBottom();
    }

    // Mark as Read
    async function markAsRead(conversationId) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        try {
            await fetch(`${config.baseUrl}${getRoutePrefix()}/chat/${conversationId}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                },
            });

            // Update unread count
            loadUnreadCount();
        } catch (error) {
            console.error('Error marking as read:', error);
        }
    }

    // Load Unread Count
    async function loadUnreadCount() {
        try {
            const response = await fetch(`${config.baseUrl}${getRoutePrefix()}/chat/unread/count`);
            if (!response.ok) return;

            const data = await response.json();
            updateUnreadBadge(data.count);
        } catch (error) {
            console.error('Error loading unread count:', error);
        }
    }

    // Update Unread Badge
    function updateUnreadBadge(count) {
        if (count > 0) {
            elements.unreadBadge.textContent = count > 99 ? '99+' : count;
            elements.unreadBadge.style.display = 'block';
        } else {
            elements.unreadBadge.style.display = 'none';
        }
    }

    // Handle Input Change
    function handleInputChange() {
        const hasContent = elements.messageInput.value.trim() || state.selectedFile;
        elements.sendButton.disabled = !hasContent;

        // Auto-resize textarea
        elements.messageInput.style.height = 'auto';
        elements.messageInput.style.height = elements.messageInput.scrollHeight + 'px';
    }

    // Handle Key Press
    function handleKeyPress(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    }

    // Handle File Select
    function handleFileSelect(e) {
        const file = e.target.files[0];
        if (!file) return;

        // Check file size (10MB max)
        if (file.size > 10 * 1024 * 1024) {
            alert('File quá lớn. Vui lòng chọn file nhỏ hơn 10MB.');
            e.target.value = '';
            return;
        }

        state.selectedFile = file;
        elements.filePreviewName.textContent = file.name;
        elements.filePreview.style.display = 'flex';
        handleInputChange();
    }

    // Remove File
    function removeFile() {
        state.selectedFile = null;
        elements.fileInput.value = '';
        elements.filePreview.style.display = 'none';
        handleInputChange();
    }

    // Show Conversation List
    function showConversationList() {
        elements.messageThread.classList.remove('active');
        elements.conversationList.style.display = 'block';
        state.currentConversationId = null;
        loadConversations();
    }

    // Attach Delete Handlers
    function attachDeleteHandlers() {
        const deleteButtons = elements.messagesContainer.querySelectorAll('.message-delete-btn');
        deleteButtons.forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                const messageId = this.dataset.messageId;
                deleteMessage(messageId);
            });
        });
    }

    // Show Confirmation Modal
    function showConfirmModal(title, message) {
        return new Promise((resolve) => {
            elements.confirmTitle.textContent = title;
            elements.confirmMessage.textContent = message;
            elements.confirmModal.classList.add('active');

            const handleOk = () => {
                elements.confirmModal.classList.remove('active');
                cleanup();
                resolve(true);
            };

            const handleCancel = () => {
                elements.confirmModal.classList.remove('active');
                cleanup();
                resolve(false);
            };

            const cleanup = () => {
                elements.confirmOk.removeEventListener('click', handleOk);
                elements.confirmCancel.removeEventListener('click', handleCancel);
            };

            elements.confirmOk.addEventListener('click', handleOk);
            elements.confirmCancel.addEventListener('click', handleCancel);
        });
    }

    // Delete Message
    async function deleteMessage(messageId) {
        const confirmed = await showConfirmModal(
            'Xóa tin nhắn',
            'Bạn có chắc muốn xóa tin nhắn này?'
        );

        if (!confirmed) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        try {
            const response = await fetch(`${config.baseUrl}${getRoutePrefix()}/chat/message/${messageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                },
            });

            if (!response.ok) throw new Error('Failed to delete message');

            // Remove message from UI
            const messageElement = elements.messagesContainer.querySelector(`[data-message-id="${messageId}"]`);
            if (messageElement) {
                messageElement.remove();
            }

            // Check if no messages left
            if (elements.messagesContainer.querySelectorAll('.message').length === 0) {
                elements.messagesContainer.innerHTML = `
                    <div class="empty-state">
                        <i class="bi bi-chat-dots"></i>
                        <p>Chưa có tin nhắn. Hãy bắt đầu cuộc trò chuyện!</p>
                    </div>
                `;
            }

            // Reload conversation list
            loadConversations();
        } catch (error) {
            console.error('Error deleting message:', error);
            alert('Không thể xóa tin nhắn. Vui lòng thử lại.');
        }
    }

    // Delete Conversation
    async function deleteConversation(conversationId) {
        const confirmed = await showConfirmModal(
            'Xóa cuộc trò chuyện',
            'Bạn có chắc muốn xóa toàn bộ cuộc trò chuyện này?'
        );

        if (!confirmed) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        try {
            const response = await fetch(`${config.baseUrl}${getRoutePrefix()}/chat/conversation/${conversationId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                },
            });

            if (!response.ok) throw new Error('Failed to delete conversation');

            // If currently viewing this conversation, go back to list
            if (state.currentConversationId == conversationId) {
                showConversationList();
            }

            // Reload conversation list
            loadConversations();
            loadUnreadCount();
        } catch (error) {
            console.error('Error deleting conversation:', error);
            alert('Không thể xóa cuộc trò chuyện. Vui lòng thử lại.');
        }
    }

    // Scroll to Bottom
    function scrollToBottom() {
        if (elements.messagesContainer) {
            elements.messagesContainer.scrollTop = elements.messagesContainer.scrollHeight;
        }
    }

    // Escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
