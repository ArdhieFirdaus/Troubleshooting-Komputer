<?php
/**
 * File: diagnosa_chat.php
 * Deskripsi: Halaman Chatbot Diagnosa Interaktif berbasis Pilihan Gejala (Guided Forward Chaining)
 */

require_once '../Auth/cek_session.php';
cek_role('asisten_lab');
require_once '../Config/koneksi.php';

// Ambil semua data gejala dari database
$query_gejala = "SELECT id_gejala, kode_gejala, nama_gejala FROM gejala ORDER BY id_gejala ASC";
$result_gejala = mysqli_query($koneksi, $query_gejala);
$gejala_list = [];
while ($g = mysqli_fetch_assoc($result_gejala)) {
    $gejala_list[] = [
        'id_gejala' => (int)$g['id_gejala'],
        'kode_gejala' => $g['kode_gejala'],
        'nama_gejala' => $g['nama_gejala']
    ];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guided Chatbot Diagnosa - Sistem Pakar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../Assets/css/style.css?v=20260713">
    <style>
        :root {
            --chat-primary: #0d6efd;
            --chat-primary-dark: #0b5ed7;
            --chat-bg: #f4f6f9;
        }

        .chat-main-container {
            max-width: 950px;
            margin: 0 auto;
        }

        .chat-container {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            overflow: hidden;
            height: calc(100vh - 170px);
            min-height: 620px;
            display: flex;
            flex-direction: column;
            border: 1px solid #e2e8f0;
        }

        .chat-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .chat-header h5 {
            margin: 0;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chat-header p {
            margin: 3px 0 0 0;
            font-size: 13px;
            opacity: 0.85;
        }

        /* Area Chat Box */
        .chat-box {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
            background: var(--chat-bg);
            scroll-behavior: smooth;
        }

        .chat-message {
            display: flex;
            margin-bottom: 22px;
            animation: fadeIn 0.3s cubic-bezier(0.1, 0.9, 0.2, 1);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .chat-message.system { justify-content: flex-start; }
        .chat-message.user { justify-content: flex-end; }

        .chat-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin: 0 12px;
            flex-shrink: 0;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .chat-avatar.system {
            background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
            color: white;
        }

        .chat-avatar.user {
            background: #4a5568;
            color: white;
        }

        .chat-bubble-container {
            max-width: 82%;
        }

        .chat-bubble {
            padding: 14px 20px;
            border-radius: 18px;
            word-wrap: break-word;
            font-size: 14.5px;
            line-height: 1.55;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        .chat-bubble.system {
            background: white;
            color: #1a202c;
            border: 1px solid #e2e8f0;
            border-top-left-radius: 4px;
        }

        .chat-bubble.user {
            background: linear-gradient(135deg, #3182ce 0%, #2b6cb0 100%);
            color: white;
            border-top-right-radius: 4px;
        }

        .chat-timestamp {
            font-size: 11px;
            color: #a0aec0;
            margin-top: 5px;
            padding: 0 4px;
        }

        /* Buttons & Options Card */
        .category-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            margin-top: 14px;
        }

        .cat-btn {
            background: #ffffff;
            border: 1.5px solid #cbd5e0;
            color: #2d3748;
            padding: 10px 14px;
            border-radius: 12px;
            font-size: 13.5px;
            font-weight: 600;
            text-align: left;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .cat-btn:hover {
            background: #ebf8ff;
            border-color: #3182ce;
            color: #2b6cb0;
            transform: translateY(-2px);
        }

        /* Symptom Option Pills */
        .symptom-grid {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 12px;
            max-height: 320px;
            overflow-y: auto;
            padding-right: 6px;
        }

        .symptom-pill {
            background: #f7fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 13.5px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
            user-select: none;
        }

        .symptom-pill:hover {
            border-color: #cbd5e0;
            background: #edf2f7;
        }

        .symptom-pill.active {
            background: #ebf8ff;
            border-color: #3182ce;
            color: #2b6cb0;
            font-weight: 600;
        }

        .symptom-pill .check-icon {
            font-size: 18px;
            color: #cbd5e0;
        }

        .symptom-pill.active .check-icon {
            color: #3182ce;
        }

        /* Filter Box Inside Chat */
        .symptom-search-box {
            margin-top: 10px;
            margin-bottom: 8px;
        }

        .symptom-search-box input {
            border-radius: 20px;
            font-size: 13px;
            padding: 8px 16px;
            border: 1px solid #cbd5e0;
        }

        /* Result Card */
        .result-card {
            background: #ffffff;
            border: 2px solid #3182ce;
            border-radius: 14px;
            padding: 18px;
            margin-top: 12px;
        }

        .result-title {
            font-size: 17px;
            font-weight: 700;
            color: #e53e3e;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .result-solusi {
            background: #f7fafc;
            border-left: 4px solid #3182ce;
            padding: 12px 16px;
            border-radius: 6px;
            font-size: 13.5px;
            white-space: pre-line;
            line-height: 1.6;
        }

        /* Action Footer */
        .chat-action-bar {
            padding: 14px 24px;
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Typing indicator */
        .typing-indicator {
            display: none;
            align-items: center;
            margin-bottom: 15px;
        }
        .typing-indicator .chat-bubble {
            background: white;
            border: 1px solid #e2e8f0;
            padding: 12px 18px;
        }
        .typing-dots { display: flex; gap: 4px; }
        .typing-dots span {
            width: 7px; height: 7px; background: #3182ce; border-radius: 50%;
            animation: typing 1.4s infinite;
        }
        .typing-dots span:nth-child(2) { animation-delay: 0.2s; }
        .typing-dots span:nth-child(3) { animation-delay: 0.4s; }
        @keyframes typing {
            0%, 60%, 100% { transform: translateY(0); }
            30% { transform: translateY(-8px); }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <?php include 'sidebar_asisten.php'; ?>
        
        <div class="main-content">
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                <div class="container-fluid">
                    <button class="btn btn-primary" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <span class="navbar-brand mb-0 h1 ms-3">Guided Chatbot Diagnosa</span>
                    <div class="ms-auto">
                        <span class="me-3">
                            <i class="bi bi-person-circle"></i> <?php echo $_SESSION['nama_lengkap']; ?>
                        </span>
                    </div>
                </div>
            </nav>
            
            <div class="container-fluid p-4">
                <div class="chat-main-container">
                    <div class="chat-container">
                        <!-- Chat Header -->
                        <div class="chat-header">
                            <div>
                                <h5><i class="bi bi-robot"></i> Asisten Pakar Troubleshooting</h5>
                                <p>Sistem Diagnosa Interaktif Forward Chaining Berbasis Pilihan Gejala</p>
                            </div>
                            <button class="btn btn-light btn-sm font-weight-bold" onclick="resetChatHistory()">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset Diagnosa
                            </button>
                        </div>

                        <!-- Chat Box Container -->
                        <div class="chat-box" id="chatBox">
                            <!-- Typing Indicator -->
                            <div class="typing-indicator" id="typingIndicator">
                                <div class="chat-avatar system"><i class="bi bi-robot"></i></div>
                                <div class="chat-bubble">
                                    <div class="typing-dots">
                                        <span></span><span></span><span></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Bar Bottom -->
                        <div class="chat-action-bar">
                            <div class="text-muted small">
                                <i class="bi bi-shield-check text-success"></i> 
                                Forward Chaining Engine Ready &bull; Total Master Gejala: <?php echo count($gejala_list); ?>
                            </div>
                            <div>
                                <a href="riwayat_diagnosa.php" class="btn btn-outline-secondary btn-sm me-1">
                                    <i class="bi bi-clock-history"></i> Riwayat
                                </a>
                                <a href="export_laporan.php" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-file-earmark-pdf"></i> Export Laporan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../Assets/js/script.js?v=20260713"></script>

    <script>
        // Data Master Gejala dari Database
        const ALL_GEJALA = <?php echo json_encode($gejala_list); ?>;
        
        // Master Kategori untuk mempermudah navigasi user
        const CATEGORIES = [
            { id: 'daya', name: 'Masalah Daya & Booting', icon: 'bi-power' },
            { id: 'layar', name: 'Masalah Layar & Tampilan', icon: 'bi-display' },
            { id: 'performa', name: 'Penyimpanan, OS & Performa', icon: 'bi-hdd-network' },
            { id: 'io', name: 'Perangkat Input/Output & Sound', icon: 'bi-keyboard' },
            { id: 'jaringan', name: 'Jaringan & Internet', icon: 'bi-wifi' },
            { id: 'semua', name: 'Lihat Semua Gejala (' + ALL_GEJALA.length + ')', icon: 'bi-list-stars' }
        ];

        const chatBox = document.getElementById('chatBox');
        const typingIndicator = document.getElementById('typingIndicator');

        // Set untuk menyimpan ID gejala yang dipilih pengguna
        let selectedGejalaIds = new Set();
        let currentStepId = 0;

        window.onload = function() {
            initWelcomeChat();
        };

        function resetChatHistory() {
            chatBox.innerHTML = '';
            chatBox.appendChild(typingIndicator);
            selectedGejalaIds.clear();
            initWelcomeChat();
        }

        function initWelcomeChat() {
            const welcomeHtml = `
                👋 <strong>Halo! Saya Asisten Diagnosa Komputer & Software.</strong><br>
                Sistem ini menggunakan metode <em>Forward Chaining</em> untuk menganalisis kerusakan berdasarkan gejala yang Anda alami.<br><br>
                <strong>Silakan pilih kategori kendala utama komputer Anda di bawah ini:</strong>
                <div class="category-buttons">
                    ${CATEGORIES.map(cat => `
                        <button class="cat-btn" onclick="onCategorySelect('${cat.id}', '${cat.name}')">
                            <i class="bi ${cat.icon} text-primary"></i> ${cat.name}
                        </button>
                    `).join('')}
                </div>
            `;
            appendSystemMessage(welcomeHtml);
        }

        function onCategorySelect(catId, catName) {
            appendUserMessage(`Pilih Kategori: <strong>${catName}</strong>`);
            showTypingIndicator();

            setTimeout(() => {
                hideTypingIndicator();

                // Filter data gejala berdasarkan kategori
                let filtered = [];
                if (catId === 'daya') {
                    filtered = ALL_GEJALA.filter(g => [1,2,3,5,6,11,12,23,25,30].includes(g.id_gejala));
                } else if (catId === 'layar') {
                    filtered = ALL_GEJALA.filter(g => [4,7,15,24,28].includes(g.id_gejala));
                } else if (catId === 'performa') {
                    filtered = ALL_GEJALA.filter(g => [8,9,10,13,14,16,26,31,32,33,34,35,37,38,40].includes(g.id_gejala));
                } else if (catId === 'io') {
                    filtered = ALL_GEJALA.filter(g => [18,19,20,27,29].includes(g.id_gejala));
                } else if (catId === 'jaringan') {
                    filtered = ALL_GEJALA.filter(g => [17,21,22,36,39].includes(g.id_gejala));
                } else {
                    filtered = ALL_GEJALA; // Semua
                }

                // Fallback jika array kosong
                if (filtered.length === 0) filtered = ALL_GEJALA;

                renderSymptomSelector(filtered, catName);
            }, 300);
        }

        function renderSymptomSelector(gejalaArray, catName) {
            const stepId = 'step_' + Date.now();
            currentStepId = stepId;

            let selectorHtml = `
                📌 <strong>Pilih satu atau beberapa gejala pada kategori [${catName}]:</strong><br>
                <small class="text-muted">Klik pada gejala untuk memilih/membatalkan. Anda bisa memilih lebih dari satu gejala.</small>
                
                <div class="symptom-search-box">
                    <input type="text" class="form-control" id="search_${stepId}" placeholder="🔍 Cari gejala..." onkeyup="filterSymptomList('${stepId}')">
                </div>

                <div class="symptom-grid" id="grid_${stepId}">
                    ${gejalaArray.map(g => {
                        const isChecked = selectedGejalaIds.has(g.id_gejala);
                        return `
                            <div class="symptom-pill ${isChecked ? 'active' : ''}" id="pill_${stepId}_${g.id_gejala}" onclick="toggleSymptom(${g.id_gejala}, '${stepId}')">
                                <div>
                                    <span class="badge bg-secondary me-1">${g.kode_gejala}</span>
                                    <span>${escapeHtml(g.nama_gejala)}</span>
                                </div>
                                <i class="bi ${isChecked ? 'bi-check-circle-fill' : 'bi-circle'} check-icon" id="check_${stepId}_${g.id_gejala}"></i>
                            </div>
                        `;
                    }).join('')}
                </div>

                <div class="mt-3 d-flex flex-wrap gap-2">
                    <button class="btn btn-primary btn-sm" id="btnSubmit_${stepId}" onclick="submitDiagnosis()">
                        <i class="bi bi-cpu"></i> Process Diagnosa (<span id="count_${stepId}">${selectedGejalaIds.size}</span> Terpilih)
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="showOtherCategories()">
                        <i class="bi bi-plus-circle"></i> Tambah Kategori Lain
                    </button>
                    <button class="btn btn-outline-danger btn-sm" onclick="clearSelectedSymptoms('${stepId}')">
                        <i class="bi bi-x-circle"></i> Reset Pilihan
                    </button>
                </div>
            `;

            appendSystemMessage(selectorHtml);
        }

        function toggleSymptom(idGejala, stepId) {
            if (selectedGejalaIds.has(idGejala)) {
                selectedGejalaIds.delete(idGejala);
            } else {
                selectedGejalaIds.add(idGejala);
            }
            updatePillState(idGejala, stepId);
            updateSelectedCount();
        }

        function updatePillState(idGejala, stepId) {
            const pill = document.getElementById(`pill_${stepId}_${idGejala}`);
            const check = document.getElementById(`check_${stepId}_${idGejala}`);
            if (pill && check) {
                const isSelected = selectedGejalaIds.has(idGejala);
                if (isSelected) {
                    pill.classList.add('active');
                    check.className = 'bi bi-check-circle-fill check-icon';
                } else {
                    pill.classList.remove('active');
                    check.className = 'bi bi-circle check-icon';
                }
            }
        }

        function updateSelectedCount() {
            document.querySelectorAll('[id^="count_step_"]').forEach(el => {
                el.textContent = selectedGejalaIds.size;
            });
        }

        function clearSelectedSymptoms(stepId) {
            selectedGejalaIds.clear();
            ALL_GEJALA.forEach(g => updatePillState(g.id_gejala, stepId));
            updateSelectedCount();
        }

        function filterSymptomList(stepId) {
            const query = document.getElementById(`search_${stepId}`).value.toLowerCase();
            const grid = document.getElementById(`grid_${stepId}`);
            const pills = grid.getElementsByClassName('symptom-pill');
            Array.from(pills).forEach(pill => {
                const text = pill.textContent.toLowerCase();
                pill.style.display = text.includes(query) ? 'flex' : 'none';
            });
        }

        function showOtherCategories() {
            const html = `
                <strong>Silakan pilih kategori gejala tambahan:</strong>
                <div class="category-buttons">
                    ${CATEGORIES.map(cat => `
                        <button class="cat-btn" onclick="onCategorySelect('${cat.id}', '${cat.name}')">
                            <i class="bi ${cat.icon} text-primary"></i> ${cat.name}
                        </button>
                    `).join('')}
                </div>
            `;
            appendSystemMessage(html);
        }

        function submitDiagnosis() {
            if (selectedGejalaIds.size === 0) {
                alert('Silakan pilih setidaknya 1 gejala terlebih dahulu sebelum memproses diagnosa.');
                return;
            }

            // Tampilkan gejala yang dipilih di pesan user
            const selectedList = ALL_GEJALA.filter(g => selectedGejalaIds.has(g.id_gejala));
            const userText = `
                🔍 <strong>Gejala yang Saya Pilih (${selectedList.length}):</strong><br>
                ${selectedList.map(g => `&bull; <strong>${g.kode_gejala}</strong>: ${escapeHtml(g.nama_gejala)}`).join('<br>')}
            `;
            appendUserMessage(userText);

            showTypingIndicator();

            // Kirim data gejala_ids ke backend proses_chat.php
            const gejalaArray = Array.from(selectedGejalaIds);
            const formData = new URLSearchParams();
            gejalaArray.forEach(id => formData.append('gejala_ids[]', id));

            fetch('proses_chat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: formData.toString()
            })
            .then(res => res.json())
            .then(data => {
                hideTypingIndicator();
                renderDiagnosisResult(data);
            })
            .catch(err => {
                console.error(err);
                hideTypingIndicator();
                appendSystemMessage('❌ Maaf, terjadi kesalahan saat menghubungi server. Silakan coba lagi.');
            });
        }

        function renderDiagnosisResult(data) {
            if (!data.success) {
                appendSystemMessage(`❌ ${data.message}`);
                return;
            }

            const diag = data.diagnosa;
            const detail = data.detail || {};
            const isUnknown = diag.nama_kerusakan === 'Kerusakan Tidak Teridentifikasi';

            const katBadge = diag.kategori === 'Hardware' 
                ? '<span class="badge bg-primary"><i class="bi bi-cpu"></i> Hardware</span>' 
                : (diag.kategori === 'Software' 
                    ? '<span class="badge bg-success"><i class="bi bi-window-stack"></i> Software</span>'
                    : '<span class="badge bg-secondary">Tidak Diketahui</span>');

            const resultHtml = `
                ✅ <strong>Diagnosa Forward Chaining Selesai!</strong>
                <div class="result-card">
                    <div class="result-title">
                        <span><i class="bi bi-exclamation-triangle-fill"></i> ${escapeHtml(diag.nama_kerusakan)}</span>
                        ${katBadge}
                    </div>
                    
                    ${!isUnknown ? `
                        <div class="mb-2 small">
                            <strong>Kode Kerusakan:</strong> <span class="badge bg-dark">${diag.kode_kerusakan}</span> &bull; 
                            <strong>Tingkat Kecocokan Rule:</strong> <span class="badge bg-info text-dark">${detail.kecocokan || '100%'}</span>
                        </div>
                    ` : ''}

                    <div class="mb-2 font-weight-bold">🛠️ Solusi Perbaikan & Penanganan:</div>
                    <div class="result-solusi">${escapeHtml(diag.solusi)}</div>
                </div>

                <div class="mt-3 d-flex flex-wrap gap-2">
                    <button class="btn btn-success btn-sm" onclick="resetChatHistory()">
                        <i class="bi bi-plus-circle"></i> Diagnosa Baru
                    </button>
                    <a href="riwayat_diagnosa.php" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-clock-history"></i> Lihat Riwayat
                    </a>
                    <a href="export_laporan.php" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-file-earmark-pdf"></i> Cetak Laporan
                    </a>
                </div>
            `;

            appendSystemMessage(resultHtml);
        }

        // Helper Append Bubbles
        function appendSystemMessage(htmlContent) {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'chat-message system';
            messageDiv.innerHTML = `
                <div class="chat-avatar system"><i class="bi bi-robot"></i></div>
                <div class="chat-bubble-container">
                    <div class="chat-bubble system">${htmlContent}</div>
                    <div class="chat-timestamp">${getCurrentTime()}</div>
                </div>
            `;
            chatBox.insertBefore(messageDiv, typingIndicator);
            scrollToBottom();
        }

        function appendUserMessage(htmlContent) {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'chat-message user';
            messageDiv.innerHTML = `
                <div class="chat-bubble-container">
                    <div class="chat-bubble user">${htmlContent}</div>
                    <div class="chat-timestamp text-end">${getCurrentTime()}</div>
                </div>
                <div class="chat-avatar user"><i class="bi bi-person-fill"></i></div>
            `;
            chatBox.insertBefore(messageDiv, typingIndicator);
            scrollToBottom();
        }

        function showTypingIndicator() {
            typingIndicator.style.display = 'flex';
            scrollToBottom();
        }

        function hideTypingIndicator() {
            typingIndicator.style.display = 'none';
        }

        function scrollToBottom() {
            setTimeout(() => {
                chatBox.scrollTop = chatBox.scrollHeight;
            }, 100);
        }

        function getCurrentTime() {
            const now = new Date();
            return now.getHours().toString().padStart(2, '0') + ':' + 
                   now.getMinutes().toString().padStart(2, '0');
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>
