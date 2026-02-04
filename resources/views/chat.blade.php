<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0d6efd;
            --bg-color: #f8f9fa;
            --card-bg: #ffffff;
            --border-color: #dee2e6;
            --message-sent-bg: #0d6efd;
            --message-received-bg: #e9ecef;
        }

        body {
            background-color: var(--bg-color);
            height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .main-container {
            flex: 1;
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        .chat-container {
            background: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            height: calc(100vh - 120px);
            display: flex;
            max-width: 1200px;
            width: 100%;
            overflow: hidden;
        }

        .users-list {
            width: 320px;
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
        }

        .users-header {
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
            background: #f8f9fa;
        }

        .user-item {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: background-color 0.2s;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-item:hover {
            background-color: #f8f9fa;
        }

        .user-item.active {
            background-color: #e7f1ff;
            border-left: 4px solid var(--primary-color);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 16px;
        }

        .chat-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .chat-header {
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .messages-area {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background-color: var(--bg-color);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .message-row {
            display: flex;
            margin-bottom: 12px;
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .message-container {
            max-width: 70%;
            display: flex;
            flex-direction: column;
        }

        .message-bubble {
            padding: 12px 16px;
            border-radius: 18px;
            word-wrap: break-word;
            position: relative;
        }

        .message-sent {
            background-color: var(--message-sent-bg);
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 5px;
        }

        .message-received {
            background-color: var(--message-received-bg);
            color: #212529;
            align-self: flex-start;
            border-bottom-left-radius: 5px;
        }

        .message-meta {
            font-size: 11px;
            opacity: 0.7;
            margin-top: 6px;
            text-align: right;
        }

        .message-sent .message-meta {
            text-align: right;
        }

        .message-received .message-meta {
            text-align: left;
        }

        .message-form {
            padding: 15px;
            border-top: 1px solid var(--border-color);
            background: var(--card-bg);
            display: flex;
            gap: 10px;
        }

        .message-input {
            flex: 1;
            border-radius: 20px;
            border: 1px solid var(--border-color);
            padding: 10px 15px;
        }

        .message-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.25);
        }

        .send-btn {
            background-color: var(--primary-color);
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            color: white;
            font-weight: 500;
        }

        .send-btn:hover {
            background-color: #0b5ed7;
        }

        .send-btn:disabled {
            background-color: #6c757d;
            cursor: not-allowed;
        }

        .no-messages {
            color: #6c757d;
            text-align: center;
            margin-top: 50px;
            font-size: 16px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-name {
            font-weight: 600;
            font-size: 16px;
        }

        .user-email {
            font-size: 12px;
            color: #6c757d;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .chat-container {
                height: calc(100vh - 80px);
            }

            .users-list {
                width: 100%;
                max-width: 100%;
            }

            .message-bubble {
                max-width: 85%;
            }
        }
    </style>
</head>
<body>
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <button class="btn btn-outline-light d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#usersOffcanvas" aria-controls="usersOffcanvas">
                <i class="bi bi-people"></i> Users
            </button>
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-chat-text me-2"></i>Chat Application
            </a>
            <div class="navbar-nav ms-auto">
                <div class="nav-item position-relative">
                    <button class="btn btn-outline-light position-relative" id="notification-btn" title="Notifications">
                        <i class="bi bi-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notification-badge" style="display: none;">0</span>
                    </button>
                </div>
                <div class="nav-item ms-2">
                    <button class="btn btn-outline-light" id="logout-btn" title="Logout">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Users Offcanvas -->
    <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="usersOffcanvas" aria-labelledby="usersOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="usersOffcanvasLabel">Online Users</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <div id="users-list-mobile" style="overflow-y: auto; height: calc(100vh - 120px);">
                <!-- Users will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Notification Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" id="notification-container">
        <!-- Notifications will be added here -->
    </div>

    <!-- Main Chat Container -->
    <div class="main-container">
        <div class="chat-container">
            <!-- Users List -->
            <div class="users-list">
                <div class="users-header">
                    <h6 class="mb-0">Online Users</h6>
                </div>
                <div id="users-list" style="overflow-y: auto; flex: 1;">
                    <!-- Users will be loaded here -->
                </div>
            </div>

            <!-- Chat Area -->
            <div class="chat-area">
                <div class="chat-header">
                    <div class="user-info">
                        <div class="user-avatar" id="current-chat-avatar">U</div>
                        <div>
                            <div class="user-name" id="current-chat-user">Select a user to start chat</div>
                            <div class="user-email" id="current-chat-email"></div>
                        </div>
                    </div>
                    {{-- <div class="text-muted small" id="online-status">Offline</div> --}}
                </div>

                <!-- Messages Area -->
                <div class="messages-area" id="messages-area">
                    <div class="no-messages" id="no-messages">
                        <i class="bi bi-chat-dots" style="font-size: 48px; color: #6c757d;"></i>
                        <p class="mt-3">Select a user to start chatting</p>
                    </div>
                </div>

                <!-- Message Form -->
                <div class="message-form">
                    <input type="text" id="message-input" class="form-control message-input" placeholder="Type your message..." disabled>
                    <button class="btn btn-primary send-btn" id="send-btn" disabled>
                        <i class="bi bi-send"></i> Send
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}
    @vite(['resources/js/app.js'])

    <script>
        class ChatApp {
            constructor() {
                // Standardize token handling - use 'token' as the primary key
                this.token = localStorage.getItem('token') || localStorage.getItem('access_token') || localStorage.getItem('auth_token');

                // Ensure we have a consistent token key
                if (this.token && !localStorage.getItem('token')) {
                    localStorage.setItem('token', this.token);
                }

                this.currentUserId = null;
                this.currentUser = null;
                this.messagesArea = document.getElementById('messages-area');
                this.usersList = document.getElementById('users-list');
                this.usersListMobile = document.getElementById('users-list-mobile');
                this.currentChatUser = document.getElementById('current-chat-user');
                this.currentChatEmail = document.getElementById('current-chat-email');
                this.currentChatAvatar = document.getElementById('current-chat-avatar');
                this.messageInput = document.getElementById('message-input');
                this.sendBtn = document.getElementById('send-btn');
                this.noMessages = document.getElementById('no-messages');
                this.echo = null;
                this.unreadCount = 0;
                this.notifications = [];
                this.userId = localStorage.getItem('user_id');

                // User sorting and tracking
                this.lastMessageAt = {}; // Map userId -> latest message timestamp
                this.usersMap = {}; // Map userId -> user object

                if (!this.token || !this.userId) {
                    window.location.href = '/';
                    return;
                }

                this.init();
            }

            async init() {
                try {
                    await this.loadUsers();
                    this.initializeEcho();
                    this.setupEventListeners();
                } catch (error) {
                    console.error('Error initializing chat:', error);
                    alert('Error loading chat. Please refresh the page.');
                }
            }

            setupEventListeners() {
                this.sendBtn.addEventListener('click', () => this.sendMessage());
                this.messageInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        this.sendMessage();
                    }
                });

                // Logout button
                document.getElementById('logout-btn').addEventListener('click', () => {
                    this.logout();
                });
            }

            async loadUsers() {
                try {
                    const response = await fetch('/api/chat/users', {
                        headers: {
                            'Authorization': `Bearer ${this.token}`,
                            'Content-Type': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Failed to load users');
                    }

                    const users = await response.json();
                    this.renderUsers(users);
                } catch (error) {
                    console.error('Error loading users:', error);
                }
            }

            renderUsers(users) {
                // Store users in map for quick access
                users.forEach(user => {
                    this.usersMap[user.id] = user;
                });

                // Sort users by last message time (newest first)
                const sortedUsers = users.sort((a, b) => {
                    const timeA = this.lastMessageAt[a.id] || 0;
                    const timeB = this.lastMessageAt[b.id] || 0;
                    return timeB - timeA;
                });

                this.usersList.innerHTML = '';

                sortedUsers.forEach(user => {
                    const userDiv = document.createElement('div');
                    userDiv.className = 'user-item';
                    userDiv.dataset.userId = user.id;

                    const initials = user.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);

                    userDiv.innerHTML = `
                        <div class="user-avatar">${initials}</div>
                        <div class="flex-grow-1">
                            <div class="user-name">${user.name}</div>
                            <div class="user-email">${user.email}</div>
                        </div>
                    `;

                    userDiv.addEventListener('click', () => this.selectUser(user));
                    this.usersList.appendChild(userDiv);
                });
            }

            async selectUser(user) {
                // Remove active class from all users
                document.querySelectorAll('.user-item').forEach(item => {
                    item.classList.remove('active');
                });

                // Add active class to selected user
                event.currentTarget.classList.add('active');

                this.currentUserId = user.id;
                this.currentUser = user;
                this.currentChatUser.textContent = user.name;
                this.currentChatEmail.textContent = user.email;
                this.currentChatAvatar.textContent = user.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
                this.messageInput.disabled = false;
                this.sendBtn.disabled = false;
                this.noMessages.style.display = 'none';

                // Reset unread count for this conversation
                this.resetUnreadCount();

                await this.loadMessages();
            }

            resetUnreadCount() {
                // Filter out notifications from current conversation
                this.notifications = this.notifications.filter(n => n.conversation_id !== this.currentUserId);
                this.unreadCount = this.notifications.length;
                this.updateBadge();
            }

            async loadMessages() {
                if (!this.currentUserId) return;

                try {
                    const response = await fetch(`/api/chat/${this.currentUserId}`, {
                        headers: {
                            'Authorization': `Bearer ${this.token}`,
                            'Content-Type': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Failed to load messages');
                    }

                    const messages = await response.json();
                    this.renderMessages(messages);

                    // Scroll to bottom
                    this.messagesArea.scrollTop = this.messagesArea.scrollHeight;
                } catch (error) {
                    console.error('Error loading messages:', error);
                }
            }

            renderMessages(messages) {
                this.messagesArea.innerHTML = '';

                if (messages.length === 0) {
                    this.messagesArea.innerHTML = '<div class="no-messages">No messages yet. Start the conversation!</div>';
                    return;
                }

                // Update last message time for this user
                const latestMessage = messages[messages.length - 1];
                this.lastMessageAt[this.currentUserId] = new Date(latestMessage.created_at).getTime();
                this.renderUsers(Object.values(this.usersMap));

                messages.forEach(message => {
                    const isSent = message.sender_id === parseInt(this.userId);
                    this.addMessageToUI(message, isSent);
                });
            }

            formatTime(dateString) {
                const date = new Date(dateString);
                return date.toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });
            }

            async sendMessage() {
                const message = this.messageInput.value.trim();

                if (!message || !this.currentUserId) {
                    return;
                }

                try {
                    const response = await fetch('/api/chat/send', {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${this.token}`,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            receiver_id: this.currentUserId,
                            message: message
                        })
                    });

                    if (!response.ok) {
                        throw new Error('Failed to send message');
                    }

                    const result = await response.json();
                    this.messageInput.value = '';

                    // Update last message time for this user
                    this.lastMessageAt[this.currentUserId] = new Date(result.data.created_at).getTime();
                    this.renderUsers(Object.values(this.usersMap));

                    // Add the sent message to the UI immediately
                    this.addMessageToUI(result.data, true);

                    // Scroll to bottom
                    this.messagesArea.scrollTop = this.messagesArea.scrollHeight;
                } catch (error) {
                    console.error('Error sending message:', error);
                    alert('Failed to send message. Please try again.');
                }
            }

            addMessageToUI(message, isSent) {
                const messageRow = document.createElement('div');
                messageRow.className = 'message-row';

                const messageContainer = document.createElement('div');
                messageContainer.className = 'message-container';

                const messageDiv = document.createElement('div');
                messageDiv.className = `message-bubble ${isSent ? 'message-sent' : 'message-received'}`;

                const messageContent = document.createElement('div');
                messageContent.textContent = message.message;

                const messageMeta = document.createElement('div');
                messageMeta.className = 'message-meta';
                messageMeta.textContent = this.formatTime(message.created_at);

                messageDiv.appendChild(messageContent);
                messageDiv.appendChild(messageMeta);
                messageContainer.appendChild(messageDiv);
                messageRow.appendChild(messageContainer);

                this.messagesArea.appendChild(messageRow);

                // Scroll to bottom
                this.messagesArea.scrollTop = this.messagesArea.scrollHeight;
            }

            initializeEcho() {
                if (typeof window.Echo === 'undefined') {
                    console.error('Echo is not loaded');
                    return;
                }

                // Listen for message events
                this.echo = window.Echo.private(`chat.${this.userId}`)
                    .listen('.App\\Events\\MessageSent', (event) => {
                        // Only add the message if it's from the current conversation
                        if (this.currentUserId === event.sender_id) {
                            this.addMessageToUI(event, false);
                        }
                    })
                    // Listen for notification events
                    .listen('.App\\Events\\MessageNotification', (notification) => {
                        this.handleNotification(notification);
                    });

                console.log('Echo initialized for user:', this.userId);
            }

            handleNotification(notification) {
                // Increment unread count
                this.unreadCount++;
                this.updateBadge();

                // Add to notifications array
                this.notifications.push(notification);

                // Show popup notification
                this.showNotificationPopup(notification);
            }

            updateBadge() {
                const badge = document.getElementById('notification-badge');
                if (this.unreadCount > 0) {
                    badge.textContent = this.unreadCount;
                    badge.style.display = 'inline-block';
                } else {
                    badge.style.display = 'none';
                }
            }

            showNotificationPopup(notification) {
                const container = document.getElementById('notification-container');
                const toastId = `notification-${Date.now()}`;

                const toastHtml = `
                    <div class="toast" id="${toastId}" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header">
                            <i class="bi bi-chat-text text-primary me-2"></i>
                            <strong class="me-auto">${notification.sender_name}</strong>
                            <small>Just now</small>
                            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                        </div>
                        <div class="toast-body">
                            ${notification.message_snippet}
                        </div>
                    </div>
                `;

                container.insertAdjacentHTML('beforeend', toastHtml);

                const toastElement = document.getElementById(toastId);
                const toast = new bootstrap.Toast(toastElement, {
                    autohide: true,
                    delay: 5000
                });

                // Add click handler to navigate to conversation
                toastElement.addEventListener('shown.bs.toast', () => {
                    toastElement.style.cursor = 'pointer';
                    toastElement.addEventListener('click', () => {
                        this.navigateToConversation(notification.conversation_id);
                    });
                });

                toast.show();

                // Remove toast element after it's hidden
                toastElement.addEventListener('hidden.bs.toast', () => {
                    toastElement.remove();
                });
            }

            navigateToConversation(userId) {
                // Find and click the user in the list
                const userItems = document.querySelectorAll('.user-item');
                userItems.forEach(item => {
                    if (item.dataset.userId === userId.toString()) {
                        item.click();
                        return;
                    }
                });
            }

            logout() {
                // Remove all token variants to ensure clean logout
                localStorage.removeItem('token');
                localStorage.removeItem('access_token');
                localStorage.removeItem('auth_token');
                localStorage.removeItem('user_id');
                window.location.href = '/';
            }
        }

        // Initialize chat app when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            new ChatApp();
        });
    </script>
</body>
</html>
