<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat Rosa Spa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            height: 100vh;
            background: transparent;
        }
        
        .chat-container {
            height: 100%;
            display: flex;
            flex-direction: column;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .chat-header {
            background: linear-gradient(135deg, #FF6B95, #ff4d7d);
            color: white;
            padding: 12px 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .chat-title {
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .chat-body {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            background: #f5f5f5;
        }
        
        .message {
            margin-bottom: 15px;
        }
        
        .user-message {
            text-align: right;
        }
        
        .bot-message {
            text-align: left;
        }
        
        .message-content {
            display: inline-block;
            max-width: 80%;
            padding: 10px 15px;
            border-radius: 18px;
            margin-bottom: 2px;
            word-break: break-word;
            line-height: 1.4;
        }
        
        .user-message .message-content {
            background: linear-gradient(135deg, #FF6B95, #ff4d7d);
            color: white;
            border-bottom-right-radius: 5px;
        }
        
        .bot-message .message-content {
            background: #e4e4e4;
            color: #333;
            border-bottom-left-radius: 5px;
        }
        
        .message-time {
            font-size: 11px;
            color: #888;
            margin-top: 2px;
        }
        
        .chat-input {
            padding: 10px;
            background: white;
            border-top: 1px solid #eee;
            display: flex;
        }
        
        .chat-input textarea {
            flex: 1;
            border: 1px solid #ddd;
            border-radius: 20px;
            padding: 10px 15px;
            font-size: 14px;
            resize: none;
            outline: none;
            height: 42px;
            max-height: 120px;
        }
        
        .chat-input button {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #FF6B95, #ff4d7d);
            color: white;
            border: none;
            border-radius: 50%;
            margin-left: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        
        .chat-input button:hover {
            transform: scale(1.05);
            background: linear-gradient(135deg, #ff4d7d, #FF6B95);
        }
        
        .close-btn {
            background: transparent;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.2s ease;
        }
        
        .close-btn:hover {
            transform: scale(1.1);
        }
        
        .typing-indicator {
            display: none;
            text-align: left;
            margin-bottom: 15px;
        }
        
        .typing-indicator .dots {
            display: inline-block;
            background: #e4e4e4;
            padding: 8px 15px;
            border-radius: 18px;
            border-bottom-left-radius: 5px;
        }
        
        .typing-indicator .dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            background: #999;
            border-radius: 50%;
            margin-right: 3px;
            animation: typing-dot 1.4s infinite;
        }
        
        .typing-indicator .dot:nth-child(2) {
            animation-delay: 0.2s;
        }
        
        .typing-indicator .dot:nth-child(3) {
            animation-delay: 0.4s;
            margin-right: 0;
        }
        
        @keyframes typing-dot {
            0%, 60%, 100% { transform: translateY(0); }
            30% { transform: translateY(-5px); }
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .action-btn {
            background: transparent;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s ease;
        }
        
        .action-btn:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <div class="chat-title">
                <i class="fas fa-spa"></i> Rosa Spa Assistant
            </div>
            <div class="header-actions">
                <button class="action-btn" id="newChatBtn" title="Cuộc trò chuyện mới">
                    <i class="fas fa-plus"></i>
                </button>
                <button class="close-btn" id="closeBtn" title="Đóng">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <div class="chat-body" id="chatBody">
            <div class="message bot-message">
                <div class="message-content">
                    Xin chào! Tôi là trợ lý ảo của Rosa Spa. Tôi có thể giúp bạn tìm hiểu về dịch vụ, đặt lịch, khuyến mãi hoặc thông tin thành viên. Tôi có thể giúp gì cho bạn hôm nay?
                </div>
                <div class="message-time">Rosa Spa</div>
            </div>
            
            @if(session('chat_history'))
                @foreach(session('chat_history') as $chat)
                <div class="message user-message">
                    <div class="message-content">
                        {{ $chat['user'] }}
                    </div>
                    <div class="message-time">Bạn</div>
                </div>
                
                <div class="message bot-message">
                    <div class="message-content">
                        {!! nl2br(e($chat['reply'])) !!}
                    </div>
                    <div class="message-time">Rosa Spa</div>
                </div>
                @endforeach
            @endif
            
            <div class="typing-indicator" id="typingIndicator">
                <div class="dots">
                    <span class="dot"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                </div>
            </div>
        </div>
        
        <form class="chat-input" id="chatForm">
            @csrf
            <textarea 
                name="message" 
                id="messageInput" 
                placeholder="Nhập tin nhắn của bạn..." 
                required
            ></textarea>
            <button type="submit">
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatBody = document.getElementById('chatBody');
            const messageInput = document.getElementById('messageInput');
            const chatForm = document.getElementById('chatForm');
            const closeBtn = document.getElementById('closeBtn');
            const typingIndicator = document.getElementById('typingIndicator');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Auto-scroll to bottom
            chatBody.scrollTop = chatBody.scrollHeight;
            
            // Auto-resize textarea
            messageInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 120) + 'px';
            });
            
            // Xử lý Enter và Shift+Enter
            messageInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    // Nếu nhấn Shift + Enter thì xuống dòng
                    if (e.shiftKey) {
                        return;  // Để mặc định xuống dòng
                    } else {
                        // Nếu chỉ nhấn Enter thì gửi tin nhắn
                        e.preventDefault();
                        if (this.value.trim() !== '') {
                            chatForm.dispatchEvent(new Event('submit'));
                        }
                    }
                }
            });
            
            // Show user message immediately when form is submitted
            chatForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const message = messageInput.value.trim();
                if (!message) {
                    return;
                }
                
                // Create user message element
                const userMessage = document.createElement('div');
                userMessage.className = 'message user-message';
                userMessage.innerHTML = `
                    <div class="message-content">${message}</div>
                    <div class="message-time">Bạn</div>
                `;
                
                // Add to chat
                chatBody.appendChild(userMessage);
                
                // Show typing indicator
                typingIndicator.style.display = 'block';
                
                // Scroll to bottom
                chatBody.scrollTop = chatBody.scrollHeight;
                
                // Reset input height and clear text
                const currentMessage = messageInput.value;
                messageInput.value = '';
                messageInput.style.height = '42px';
                
                // Send message to server via AJAX
                fetch('/chat/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ message: currentMessage })
                })
                .then(response => response.json())
                .then(data => {
                    // Hide typing indicator
                    typingIndicator.style.display = 'none';
                    
                    if (data.success) {
                        // Create bot reply element
                        const botMessage = document.createElement('div');
                        botMessage.className = 'message bot-message';
                        botMessage.innerHTML = `
                            <div class="message-content">${data.reply.replace(/\n/g, '<br>')}</div>
                            <div class="message-time">Rosa Spa</div>
                        `;
                        
                        // Add to chat
                        chatBody.appendChild(botMessage);
                        
                        // Scroll to bottom
                        chatBody.scrollTop = chatBody.scrollHeight;
                    } else {
                        // Handle error
                        const botMessage = document.createElement('div');
                        botMessage.className = 'message bot-message';
                        botMessage.innerHTML = `
                            <div class="message-content">${data.reply.replace(/\n/g, '<br>')}</div>
                            <div class="message-time">Rosa Spa</div>
                        `;
                        
                        // Add to chat
                        chatBody.appendChild(botMessage);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    // Hide typing indicator
                    typingIndicator.style.display = 'none';
                    
                    // Show error message
                    const botMessage = document.createElement('div');
                    botMessage.className = 'message bot-message';
                    botMessage.innerHTML = `
                        <div class="message-content">Xin lỗi, đã xảy ra lỗi khi gửi tin nhắn. Vui lòng thử lại sau.</div>
                        <div class="message-time">Rosa Spa</div>
                    `;
                    
                    // Add to chat
                    chatBody.appendChild(botMessage);
                    
                    // Scroll to bottom
                    chatBody.scrollTop = chatBody.scrollHeight;
                });
            });
            
            // Close button
            closeBtn.addEventListener('click', function() {
                window.parent.postMessage('closeChat', '*');
            });
            
            // New Chat button
            const newChatBtn = document.getElementById('newChatBtn');
            newChatBtn.addEventListener('click', function() {
                if (confirm('Bạn có chắc muốn bắt đầu cuộc trò chuyện mới? Tất cả tin nhắn hiện tại sẽ bị xóa.')) {
                    // Gửi yêu cầu reset chat
                    fetch('/chat/reset', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Xóa tất cả tin nhắn trừ tin nhắn chào mừng đầu tiên
                            const welcomeMessage = chatBody.firstElementChild;
                            chatBody.innerHTML = '';
                            chatBody.appendChild(welcomeMessage);
                            
                            // Scroll to bottom
                            chatBody.scrollTop = chatBody.scrollHeight;
                        } else {
                            alert('Không thể khởi tạo cuộc trò chuyện mới: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Đã xảy ra lỗi khi khởi tạo cuộc trò chuyện mới.');
                    });
                }
            });
        });
    </script>
</body>
</html> 