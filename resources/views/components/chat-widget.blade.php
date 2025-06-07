<!-- Chat Widget Component -->
<div class="chat-widget" id="chatWidget">
    <button id="chatToggleBtn" class="chat-toggle-btn">
        <i class="fas fa-robot"></i>
    </button>
    
    <div class="chat-popup" id="chatPopup">
        <iframe src="{{ route('chat.show') }}" id="chatFrame" frameborder="0"></iframe>
    </div>
</div>

<style>
    .chat-widget {
        position: fixed;
        bottom: 110px;
        right: 20px;
        z-index: 9999;
    }
    
    .chat-toggle-btn {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #FF6B95, #ff4d7d);
        color: white;
        border: none;
        cursor: pointer;
        font-size: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 15px rgba(255, 107, 149, 0.4);
        transition: all 0.3s ease;
    }
    
    .chat-toggle-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 20px rgba(255, 107, 149, 0.5);
    }
    
    .chat-popup {
        position: fixed;
        bottom: 100px;
        right: 100px;
        width: 350px;
        height: 500px;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
        display: none;
        transition: all 0.3s ease;
        opacity: 0;
        transform: translateX(20px);
    }
    
    .chat-popup.active {
        display: block;
        opacity: 1;
        transform: translateX(0);
    }
    
    .chat-popup iframe {
        width: 100%;
        height: 100%;
        border: none;
        background: white;
    }

    /* Animation for the button */
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(255, 107, 149, 0.7);
        }
        70% {
            box-shadow: 0 0 0 15px rgba(255, 107, 149, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(255, 107, 149, 0);
        }
    }
    
    .pulse {
        animation: pulse 2s infinite;
    }
    
    /* Responsive design */
    @media (max-width: 576px) {
        .chat-popup {
            width: 90%;
            height: 70%;
            right: 5%;
            bottom: 80px;
        }
        
        .chat-toggle-btn {
            width: 50px;
            height: 50px;
            font-size: 20px;
            right: 20px;
            bottom: 20px;
        }
        
        .chat-widget {
            bottom: 100px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatToggleBtn = document.getElementById('chatToggleBtn');
        const chatPopup = document.getElementById('chatPopup');
        
        // Add pulse animation after page load
        setTimeout(() => {
            chatToggleBtn.classList.add('pulse');
        }, 3000);
        
        // Toggle chat visibility
        chatToggleBtn.addEventListener('click', function() {
            chatPopup.classList.toggle('active');
            chatToggleBtn.classList.remove('pulse'); // Remove pulse when clicked
        });
        
        // Listen for messages from iframe
        window.addEventListener('message', function(event) {
            if (event.data === 'closeChat') {
                chatPopup.classList.remove('active');
            }
        });
    });
</script> 