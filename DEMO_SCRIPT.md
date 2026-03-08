# 🎬 Demo Script - Chat Diagnosa

## Demo Scenario 1: Power Supply Issue

### Setup

1. Buka browser: http://localhost/Troubleshooting-Komputer
2. Login sebagai: `asisten1` / `password`
3. Klik menu: **💬 Chat Diagnosa AI**

### Dialog

```
📱 USER:
komputer saya tidak bisa menyala

🤖 SISTEM:
✅ Diagnosa selesai! Saya telah mengidentifikasi masalah Anda.

┌─────────────────────────────────────────────┐
│ ⚠️ Diagnosa Kerusakan:                     │
│ Kerusakan Power Supply                      │
│                                             │
│ 🔧 Solusi Perbaikan:                       │
│ 1. Cek kabel power apakah terpasang        │
│    dengan benar                             │
│ 2. Test power supply dengan PSU tester     │
│ 3. Ganti power supply jika rusak           │
│ 4. Periksa saklar power di belakang PSU    │
└─────────────────────────────────────────────┘

15:30
```

---

## Demo Scenario 2: VGA/Display Issue

### Dialog

```
📱 USER:
layar hitam tapi komputer nyala

🤖 SISTEM:
✅ Diagnosa selesai! Saya telah mengidentifikasi masalah Anda.

┌─────────────────────────────────────────────┐
│ ⚠️ Diagnosa Kerusakan:                     │
│ Kerusakan VGA Card                          │
│                                             │
│ 🔧 Solusi Perbaikan:                       │
│ 1. Bersihkan slot VGA dan konektor VGA     │
│ 2. Pastikan VGA terpasang dengan benar     │
│ 3. Cek kabel monitor ke VGA                │
│ 4. Test dengan VGA lain jika memungkinkan  │
│ 5. Update driver VGA atau ganti VGA        │
│    jika rusak                               │
└─────────────────────────────────────────────┘

15:32
```

---

## Demo Scenario 3: Multiple Symptoms

### Dialog

```
📱 USER:
komputer saya restart sendiri terus dan panas banget

🤖 SISTEM:
✅ Diagnosa selesai! Saya telah mengidentifikasi masalah Anda.

┌─────────────────────────────────────────────┐
│ ⚠️ Diagnosa Kerusakan:                     │
│ Overheating (Panas Berlebih)                │
│                                             │
│ 🔧 Solusi Perbaikan:                       │
│ 1. Bersihkan debu di dalam casing dengan   │
│    air duster                               │
│ 2. Periksa dan bersihkan heatsink          │
│    processor                                │
│ 3. Ganti thermal paste processor           │
│ 4. Pastikan semua kipas berfungsi          │
│    dengan baik                              │
│ 5. Tambah kipas casing jika perlu          │
│ 6. Periksa ventilasi udara                 │
└─────────────────────────────────────────────┘

15:35
```

---

## Demo Scenario 4: Hardisk Issue

### Dialog

```
📱 USER:
hardisk bunyi klik klik dan tidak bisa boot windows

🤖 SISTEM:
✅ Diagnosa selesai! Saya telah mengidentifikasi masalah Anda.

┌─────────────────────────────────────────────┐
│ ⚠️ Diagnosa Kerusakan:                     │
│ Kerusakan Hardisk                           │
│                                             │
│ 🔧 Solusi Perbaikan:                       │
│ 1. Backup data segera jika masih bisa      │
│    diakses                                  │
│ 2. Cek kabel SATA/IDE hardisk              │
│ 3. Scan hardisk dengan tools seperti       │
│    HD Tune                                  │
│ 4. Perbaiki bad sector dengan              │
│    HDD Regenerator                          │
│ 5. Ganti hardisk jika kerusakan parah      │
└─────────────────────────────────────────────┘

⚠️ WARNING: Kerusakan hardisk dapat menyebabkan
kehilangan data. Segera backup!

15:38
```

---

## Demo Scenario 5: Unknown Issue

### Dialog

```
📱 USER:
komputer saya rusak nih

🤖 SISTEM:
❓ Maaf, saya belum bisa memahami masalah yang
Anda jelaskan. Coba gunakan kata kunci seperti:

• "tidak menyala"
• "layar hitam"
• "hardisk bunyi"
• "restart sendiri"
• dll.

Atau gunakan Quick Action buttons di atas untuk
memilih masalah umum.

15:40
```

---

## Quick Action Demo

### User clicks: 💡 Komputer tidak menyala

```
📱 USER:
komputer tidak menyala

[Typing indicator...]
   ●  ●  ●

🤖 SISTEM:
[Same as Scenario 1 response]
```

---

## Features Showcase

### 1. Auto Scroll

- Setiap pesan baru muncul, chat auto-scroll ke bawah
- Smooth scroll behavior

### 2. Typing Indicator

- Muncul saat sistem sedang memproses
- 3 dots animasi bouncing
- Hilang saat response sudah ready

### 3. Timestamp

- Setiap pesan punya timestamp (HH:mm)
- Real-time dari client

### 4. Avatar

- 🤖 Robot icon untuk sistem
- 👤 Person icon untuk user

### 5. Chat Bubble

- Sistem: Purple gradient, kiri
- User: Gray, kanan
- Rounded corners dengan tail

### 6. Input Validation

- Cannot send empty message
- Required field
- Auto focus after send

---

## Live Demo Script for Presentation

### Opening (1 min)

```
"Selamat datang di demo fitur terbaru kami:
Chat Diagnosa AI untuk Troubleshooting Komputer.

Ini adalah upgrade besar dari sistem checkbox
sebelumnya. Sekarang user bisa menjelaskan
masalah dengan bahasa sehari-hari."
```

### Demo 1: Basic Chat (2 min)

```
"Mari kita coba. Saya akan ketik:
'komputer tidak menyala'

[LIVE TYPING]

Lihat, sistem langsung mengidentifikasi gejala
dan memberikan diagnosa beserta solusinya.

Tidak perlu centang-centang checkbox lagi!"
```

### Demo 2: Quick Actions (1 min)

```
"Untuk kemudahan, kami sediakan Quick Action.

[CLICK: Layar hitam/no display]

Sekali klik langsung terkirim dan dapat solusi!"
```

### Demo 3: Multiple Symptoms (2 min)

```
"Bagaimana kalau gejala kompleks?

[TYPING: 'komputer restart sendiri terus dan panas']

Sistem cerdas mencocokkan multiple keywords:
- 'restart sendiri' → Gejala G006
- 'panas' → Gejala G016

Forward chaining: Diagnosa Overheating!
```

### Demo 4: Unknown Input (1 min)

```
"Kalau input tidak dikenali?

[TYPING: 'komputer error']

Sistem kasih saran kata kunci yang lebih spesifik.
Ini membantu user belajar cara jelaskan masalah."
```

### Technical Deep Dive (2 min)

```
"Di balik layar:

1. User input → AJAX POST ke proses_chat.php
2. Keyword matching dari database
3. Forward chaining algorithm
4. Return JSON response
5. JavaScript render chat bubble
6. Auto-scroll & animation

Semua realtime tanpa reload halaman!"
```

### Closing (1 min)

```
"Fitur ini membuat troubleshooting lebih:
✅ User-friendly
✅ Natural
✅ Cepat
✅ Akurat

Dan semua hasil tetap tersimpan di database
untuk riwayat dan laporan.

Terima kasih!"
```

---

## Recording Checklist

Before recording demo:

- [ ] Clear browser cache
- [ ] Zoom to 125% for better visibility
- [ ] Disable browser notifications
- [ ] Close unnecessary tabs
- [ ] Test XAMPP running (Apache + MySQL)
- [ ] Test database connection
- [ ] Test login credentials
- [ ] Prepare screen recording software
- [ ] Test audio if needed
- [ ] Open chat page in incognito (fresh session)

---

## Screenshots to Capture

1. **Chat Interface - Empty State**
   - Welcome message
   - Quick action buttons
   - Clean chat box

2. **Chat Interface - Conversation**
   - Multiple messages (user + sistem)
   - Timestamp visible
   - Scrollbar if needed

3. **Diagnosa Result Card**
   - Full diagnosa with bordered card
   - Kerusakan name highlighted
   - Solusi in readable format

4. **Typing Indicator**
   - 3 dots animation (capture GIF if possible)

5. **Quick Actions**
   - Hover state on button

6. **Mobile View**
   - Responsive chat on phone screen (use DevTools)

---

## GIF/Video Demos to Create

### 1. Full Chat Flow (15 sec)

```
Type → Send → Typing Indicator → Response → Auto Scroll
```

### 2. Quick Action (5 sec)

```
Click Quick Button → Instant Response
```

### 3. Error Case (8 sec)

```
Type Invalid → Error Message → Suggestion
```

### 4. Multiple Messages (20 sec)

```
Question 1 → Answer 1 → Question 2 → Answer 2
```

---

**Ready for Demo! 🎬**
