<?php
/**
 * File: diagnosa_chat.php
 * Deskripsi: Halaman diagnosa dengan tampilan chat interaktif
 * Menggunakan AJAX untuk komunikasi real-time dengan sistem
 */

require_once '../Auth/cek_session.php';
cek_role('asisten_lab');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Diagnosa - Sistem Pakar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../Assets/css/style.css?v=20260713">
    <style>
        :root {
            --chat-primary: #0d6efd;
            --chat-primary-dark: #0b5ed7;
        }

        /* Chat Container Styles */
        .chat-main-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .chat-container {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            height: 600px;
            display: flex;
            flex-direction: column;
        }

        .chat-header {
            background: linear-gradient(135deg, var(--chat-primary) 0%, var(--chat-primary-dark) 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .chat-header h4 {
            margin: 0;
            font-weight: 600;
        }

        .chat-header p {
            margin: 5px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }

        /* Chat Box - Area Pesan */
        .chat-box {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background: #f8f9fa;
            scroll-behavior: smooth;
        }

        /* Chat Bubble Styles */
        .chat-message {
            display: flex;
            margin-bottom: 20px;
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .chat-message.system {
            justify-content: flex-start;
        }

        .chat-message.user {
            justify-content: flex-end;
        }

        .chat-bubble {
            max-width: 70%;
            padding: 12px 18px;
            border-radius: 18px;
            position: relative;
            word-wrap: break-word;
        }

        .chat-bubble.system {
            background: linear-gradient(135deg, var(--chat-primary) 0%, var(--chat-primary-dark) 100%);
            color: white;
            border-bottom-left-radius: 4px;
        }

        .chat-bubble.user {
            background: #e9ecef;
            color: #212529;
            border-bottom-right-radius: 4px;
        }

        .chat-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin: 0 10px;
            flex-shrink: 0;
        }

        .chat-avatar.system {
            background: linear-gradient(135deg, var(--chat-primary) 0%, var(--chat-primary-dark) 100%);
            color: white;
        }

        .chat-avatar.user {
            background: #6c757d;
            color: white;
        }

        .chat-timestamp {
            font-size: 11px;
            color: #6c757d;
            margin-top: 5px;
            text-align: right;
        }

        .chat-message.system .chat-timestamp {
            text-align: left;
        }

        /* Loading Indicator */
        .typing-indicator {
            display: none;
            align-items: center;
            margin-bottom: 15px;
        }

        .typing-indicator .chat-bubble {
            background: linear-gradient(135deg, var(--chat-primary) 0%, var(--chat-primary-dark) 100%);
            padding: 15px 20px;
        }

        .typing-dots {
            display: flex;
            gap: 4px;
        }

        .typing-dots span {
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
            animation: typing 1.4s infinite;
        }

        .typing-dots span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-dots span:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes typing {
            0%, 60%, 100% {
                transform: translateY(0);
            }
            30% {
                transform: translateY(-10px);
            }
        }

        /* Chat Input Form */
        .chat-input-form {
            padding: 20px;
            background: white;
            border-top: 1px solid #dee2e6;
        }

        .chat-input-group {
            display: flex;
            gap: 10px;
        }

        .chat-input-group input {
            flex: 1;
            border: 2px solid #e9ecef;
            border-radius: 25px;
            padding: 12px 20px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .chat-input-group input:focus {
            outline: none;
            border-color: var(--chat-primary);
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
        }

        .chat-input-group button {
            border-radius: 50%;
            width: 50px;
            height: 50px;
            border: none;
            background: linear-gradient(135deg, var(--chat-primary) 0%, var(--chat-primary-dark) 100%);
            color: white;
            font-size: 20px;
            cursor: pointer;
            transition: transform 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chat-input-group button:hover {
            transform: scale(1.05);
        }

        .chat-input-group button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Diagnosa Result Card */
        .diagnosa-result {
            background: white;
            border: 2px solid var(--chat-primary);
            border-radius: 12px;
            padding: 15px;
            margin-top: 10px;
        }

        .diagnosa-result h6 {
            color: var(--chat-primary);
            margin-bottom: 10px;
            font-weight: 600;
        }

        .diagnosa-result .kerusakan {
            font-size: 16px;
            font-weight: 600;
            color: #d63031;
            margin-bottom: 10px;
        }

        .diagnosa-result .solusi {
            font-size: 14px;
            color: #2d3436;
            white-space: pre-line;
            line-height: 1.6;
        }

        /* Scrollbar Custom */
        .chat-box::-webkit-scrollbar {
            width: 6px;
        }

        .chat-box::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .chat-box::-webkit-scrollbar-thumb {
            background: var(--chat-primary);
            border-radius: 10px;
        }

        .chat-box::-webkit-scrollbar-thumb:hover {
            background: var(--chat-primary-dark);
        }

        /* Quick Actions */
        .quick-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
            flex-wrap: wrap;
        }

        .quick-action-btn {
            background: white;
            border: 1px solid var(--chat-primary);
            color: var(--chat-primary);
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .quick-action-btn:hover {
            background: var(--chat-primary);
            color: white;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <?php include 'sidebar_asisten.php'; ?>
        
        <div class="main-content">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                <div class="container-fluid">
                    <button class="btn btn-primary" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <span class="navbar-brand mb-0 h1 ms-3">Chat Bot Diagnosa</span>
                    <div class="ms-auto">
                        <span class="me-3">
                            <i class="bi bi-person-circle"></i> <?php echo $_SESSION['nama_lengkap']; ?>
                        </span>
                        <!-- Logout moved to sidebar -->
                    </div>
                </div>
            </nav>
            
            <!-- Main Content -->
            <div class="container-fluid p-4">
                <div class="chat-main-container">
                    <!-- Info Alert -->
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <h6 class="alert-heading">
                            <i class="bi bi-info-circle"></i> Cara Menggunakan Chat Diagnosa
                        </h6>
                        <ul class="mb-0 small">
                            <li>Jelaskan masalah komputer Anda dengan bahasa natural</li>
                            <li>Contoh: "komputer tidak menyala", "layar hitam", "hardisk bunyi klik"</li>
                            <li>Sistem akan mengidentifikasi gejala dan memberikan diagnosa otomatis</li>
                            <li>Hasil diagnosa akan tersimpan di riwayat</li>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>

                    <!-- Chat Container -->
                    <div class="chat-container">
                        <!-- Chat Header -->
                        <div class="chat-header">
                            <h4><i class="bi bi-chat-dots"></i> Asisten Diagnosa Komputer</h4>
                            <p>Jelaskan masalah komputer Anda dan sistem akan menganalisa secara otomatis</p>
                        </div>

                        <!-- Chat Box - Area Pesan -->
                        <div class="chat-box" id="chatBox">
                            <!-- Welcome Message -->
                            <div class="chat-message system">
                                <div class="chat-avatar system">
                                    <i class="bi bi-robot"></i>
                                </div>
                                <div>
                                    <div class="chat-bubble system">
                                        👋 Halo! Saya adalah asisten diagnosa troubleshooting komputer.<br><br>
                                        Ceritakan masalah yang Anda alami, saya akan membantu mengidentifikasi kerusakan dan memberikan solusinya.
                                    </div>
                                    <div class="chat-timestamp"><?php echo date('H:i'); ?></div>
                                </div>
                            </div>

                            <!-- Quick Action Buttons -->
                            <div class="quick-actions">
                                <button class="quick-action-btn" onclick="sendQuickMessage('komputer tidak menyala')">
                                    💡 Komputer tidak menyala
                                </button>
                                <button class="quick-action-btn" onclick="sendQuickMessage('layar hitam')">
                                    🖥️ Layar hitam/no display
                                </button>
                                <button class="quick-action-btn" onclick="sendQuickMessage('komputer restart sendiri')">
                                    🔄 Restart sendiri
                                </button>
                                <button class="quick-action-btn" onclick="sendQuickMessage('hardisk bunyi klik')">
                                    💾 Hardisk bunyi aneh
                                </button>
                            </div>

                            <!-- Typing Indicator -->
                            <div class="typing-indicator" id="typingIndicator">
                                <div class="chat-avatar system">
                                    <i class="bi bi-robot"></i>
                                </div>
                                <div class="chat-bubble">
                                    <div class="typing-dots">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Chat Input Form -->
                        <div class="chat-input-form">
                            <form id="chatForm" onsubmit="return false;">
                                <div class="chat-input-group">
                                    <input 
                                        type="text" 
                                        id="messageInput" 
                                        placeholder="Ketik masalah komputer Anda di sini..."
                                        autocomplete="off"
                                        required
                                    >
                                    <button type="submit" id="sendBtn">
                                        <i class="bi bi-send-fill"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Chat AJAX Script -->
    <script>
        const chatBox = document.getElementById('chatBox');
        const chatForm = document.getElementById('chatForm');
        const messageInput = document.getElementById('messageInput');
        const sendBtn = document.getElementById('sendBtn');
        const typingIndicator = document.getElementById('typingIndicator');

        // Session storage untuk menyimpan chat
        let chatHistory = [];

        // Handle Form Submit
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            sendMessage();
        });

        // Function untuk mengirim pesan
        function sendMessage() {
            const message = messageInput.value.trim();
            
            if (message === '') return;

            // Disable input saat mengirim
            messageInput.disabled = true;
            sendBtn.disabled = true;

            // Tampilkan pesan user
            addUserMessage(message);

            // Clear input
            messageInput.value = '';

            // Show typing indicator
            showTypingIndicator();

            // Send AJAX request
            fetch('proses_chat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'message=' + encodeURIComponent(message)
            })
            .then(response => response.json())
            .then(data => {
                // Hide typing indicator
                hideTypingIndicator();

                // Tampilkan respon sistem
                addSystemMessage(data);

                // Enable input kembali
                messageInput.disabled = false;
                sendBtn.disabled = false;
                messageInput.focus();
            })
            .catch(error => {
                console.error('Error:', error);
                hideTypingIndicator();
                addSystemMessage({
                    success: false,
                    message: '❌ Maaf, terjadi kesalahan sistem. Silakan coba lagi.'
                });
                messageInput.disabled = false;
                sendBtn.disabled = false;
            });
        }

        // Quick message
        function sendQuickMessage(message) {
            messageInput.value = message;
            sendMessage();
        }

        // Add user message bubble
        function addUserMessage(message) {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'chat-message user';
            messageDiv.innerHTML = `
                <div>
                    <div class="chat-bubble user">
                        ${escapeHtml(message)}
                    </div>
                    <div class="chat-timestamp">${getCurrentTime()}</div>
                </div>
                <div class="chat-avatar user">
                    <i class="bi bi-person-fill"></i>
                </div>
            `;
            chatBox.appendChild(messageDiv);
            scrollToBottom();
        }

        // Add system message bubble
        function addSystemMessage(data) {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'chat-message system';
            
            let messageContent = '';
            
            if (data.success && data.diagnosa) {
                // Ada diagnosa yang ditemukan
                messageContent = `
                    <div class="chat-bubble system">
                        ✅ Diagnosa selesai! Saya telah mengidentifikasi masalah Anda.
                        <div class="diagnosa-result">
                            <h6><i class="bi bi-exclamation-triangle-fill"></i> Diagnosa Kerusakan:</h6>
                            <div class="kerusakan">${escapeHtml(data.diagnosa.nama_kerusakan)}</div>
                            <h6><i class="bi bi-tools"></i> Solusi Perbaikan:</h6>
                            <div class="solusi">${escapeHtml(data.diagnosa.solusi)}</div>
                        </div>
                    </div>
                `;
            } else if (data.success && data.gejala_found) {
                // Gejala ditemukan tapi tidak ada diagnosa
                messageContent = `
                    <div class="chat-bubble system">
                        ${escapeHtml(data.message)}
                    </div>
                `;
            } else {
                // Gejala tidak ditemukan
                messageContent = `
                    <div class="chat-bubble system">
                        ${escapeHtml(data.message)}
                    </div>
                `;
            }
            
            messageDiv.innerHTML = `
                <div class="chat-avatar system">
                    <i class="bi bi-robot"></i>
                </div>
                <div>
                    ${messageContent}
                    <div class="chat-timestamp">${getCurrentTime()}</div>
                </div>
            `;
            
            chatBox.appendChild(messageDiv);
            scrollToBottom();
        }

        // Show/Hide typing indicator
        function showTypingIndicator() {
            typingIndicator.style.display = 'flex';
            scrollToBottom();
        }

        function hideTypingIndicator() {
            typingIndicator.style.display = 'none';
        }

        // Scroll to bottom
        function scrollToBottom() {
            setTimeout(() => {
                chatBox.scrollTop = chatBox.scrollHeight;
            }, 100);
        }

        // Get current time
        function getCurrentTime() {
            const now = new Date();
            return now.getHours().toString().padStart(2, '0') + ':' + 
                   now.getMinutes().toString().padStart(2, '0');
        }

        // Escape HTML to prevent XSS
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Focus on input when page loads
        window.addEventListener('load', function() {
            messageInput.focus();
        });
    </script>

    <!-- Sidebar Toggle Script -->
    <script src="../Assets/js/script.js?v=20260713"></script>
</body>
</html>
