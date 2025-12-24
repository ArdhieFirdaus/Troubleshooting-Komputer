# 📚 QUICK REFERENCE GUIDE

## Sistem Pakar Troubleshooting Komputer

---

## 🔗 URL PENTING

```
Base URL        : http://localhost/Troubleshooting-Komputer

Login           : /Auth/login.php
Logout          : /Auth/logout.php

Admin Dashboard : /Admin/dashboard_admin.php
Admin Gejala    : /Admin/gejala_management.php
Admin Kerusakan : /Admin/kerusakan_management.php
Admin Rule      : /Admin/rule_management.php
Admin Laporan   : /Admin/laporan_diagnosa.php

Asisten Dashboard : /Asisten/dashboard_asisten.php
Asisten Diagnosa  : /Asisten/diagnosa.php
Asisten Hasil     : /Asisten/hasil_diagnosa.php
Asisten Riwayat   : /Asisten/riwayat_diagnosa.php
Asisten Export    : /Asisten/export_laporan.php
```

---

## 🔐 AKUN DEFAULT

### Admin

```php
Username: admin
Password: password
Role: admin
```

### Asisten Lab

```php
Username: asisten1
Password: password
Role: asisten_lab
```

---

## 🗄️ TABEL DATABASE

### users

```sql
CREATE TABLE users (
    id_user INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin', 'asisten_lab'),
    nama_lengkap VARCHAR(100),
    created_at TIMESTAMP
);
```

### gejala

```sql
CREATE TABLE gejala (
    id_gejala INT PRIMARY KEY AUTO_INCREMENT,
    kode_gejala VARCHAR(10),
    nama_gejala TEXT
);
```

### kerusakan

```sql
CREATE TABLE kerusakan (
    id_kerusakan INT PRIMARY KEY AUTO_INCREMENT,
    kode_kerusakan VARCHAR(10),
    nama_kerusakan TEXT,
    solusi TEXT
);
```

### rule

```sql
CREATE TABLE rule (
    id_rule INT PRIMARY KEY AUTO_INCREMENT,
    id_kerusakan INT,
    FOREIGN KEY (id_kerusakan) REFERENCES kerusakan(id_kerusakan)
);
```

### rule_detail

```sql
CREATE TABLE rule_detail (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_rule INT,
    id_gejala INT,
    FOREIGN KEY (id_rule) REFERENCES rule(id_rule),
    FOREIGN KEY (id_gejala) REFERENCES gejala(id_gejala)
);
```

### diagnosa

```sql
CREATE TABLE diagnosa (
    id_diagnosa INT PRIMARY KEY AUTO_INCREMENT,
    id_user INT,
    tanggal DATETIME,
    hasil_kerusakan TEXT,
    FOREIGN KEY (id_user) REFERENCES users(id_user)
);
```

### diagnosa_detail

```sql
CREATE TABLE diagnosa_detail (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_diagnosa INT,
    id_gejala INT,
    FOREIGN KEY (id_diagnosa) REFERENCES diagnosa(id_diagnosa),
    FOREIGN KEY (id_gejala) REFERENCES gejala(id_gejala)
);
```

---

## 📋 QUERY PENTING

### Ambil Semua Gejala

```sql
SELECT * FROM gejala ORDER BY kode_gejala ASC;
```

### Ambil Kerusakan dengan Solusi

```sql
SELECT * FROM kerusakan ORDER BY kode_kerusakan ASC;
```

### Ambil Rule dengan Detail

```sql
SELECT r.*, k.nama_kerusakan, k.solusi
FROM rule r
INNER JOIN kerusakan k ON r.id_kerusakan = k.id_kerusakan;
```

### Ambil Gejala dari Rule Tertentu

```sql
SELECT g.*
FROM rule_detail rd
INNER JOIN gejala g ON rd.id_gejala = g.id_gejala
WHERE rd.id_rule = ?;
```

### Ambil Diagnosa User

```sql
SELECT d.*, u.nama_lengkap
FROM diagnosa d
INNER JOIN users u ON d.id_user = u.id_user
WHERE d.id_user = ?
ORDER BY d.tanggal DESC;
```

### Ambil Gejala dari Diagnosa

```sql
SELECT g.*
FROM diagnosa_detail dd
INNER JOIN gejala g ON dd.id_gejala = g.id_gejala
WHERE dd.id_diagnosa = ?;
```

---

## 🔧 KONFIGURASI

### Database Config (Config/koneksi.php)

```php
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "db_sistem_pakar_gontory";

$koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
```

### Session Variables

```php
$_SESSION['id_user']      // ID user
$_SESSION['username']     // Username
$_SESSION['role']         // Role: admin / asisten_lab
$_SESSION['nama_lengkap'] // Nama lengkap user
```

---

## 🎯 FORWARD CHAINING LOGIC

### Pseudocode

```
1. GET gejala_dipilih[] from user input
2. FOREACH rule in database:
    a. GET gejala_rule[] for this rule
    b. IF all gejala_rule IN gejala_dipilih:
        - Rule MATCH!
        - GET kerusakan for this rule
        - BREAK
3. IF kerusakan found:
    - DISPLAY kerusakan & solusi
   ELSE:
    - DISPLAY "Tidak Teridentifikasi"
4. SAVE diagnosa to database
```

### PHP Implementation

```php
// Ambil gejala yang dipilih
$gejala_dipilih = $_POST['gejala'];

// Loop setiap rule
while ($rule = mysqli_fetch_assoc($result_rules)) {
    // Ambil gejala syarat rule
    $gejala_rule = [...];

    // Cek apakah semua gejala rule ada di pilihan
    $difference = array_diff($gejala_rule, $gejala_dipilih);

    if (empty($difference)) {
        // MATCH! Ambil kerusakan
        $kerusakan = ...;
        break;
    }
}
```

---

## 🎨 BOOTSTRAP CLASSES

### Button Colors

```html
<button class="btn btn-primary">Primary</button>
<button class="btn btn-success">Success</button>
<button class="btn btn-danger">Danger</button>
<button class="btn btn-warning">Warning</button>
<button class="btn btn-info">Info</button>
```

### Alert Types

```html
<div class="alert alert-success">Success message</div>
<div class="alert alert-danger">Error message</div>
<div class="alert alert-warning">Warning message</div>
<div class="alert alert-info">Info message</div>
```

### Card Structure

```html
<div class="card shadow">
  <div class="card-header bg-primary text-white">
    <h5>Title</h5>
  </div>
  <div class="card-body">Content</div>
</div>
```

---

## 🔒 SECURITY CHECKLIST

- ✅ Password hashing: `password_hash()` & `password_verify()`
- ✅ SQL Injection: `mysqli_real_escape_string()`
- ✅ Session validation: `cek_session.php`
- ✅ Role-based access: `cek_role()`
- ✅ XSS protection: HTML escaping
- ✅ CSRF protection: Session tokens (future)

---

## 📱 RESPONSIVE BREAKPOINTS

```css
/* Mobile */
@media (max-width: 576px) {
}

/* Tablet */
@media (max-width: 768px) {
}

/* Desktop */
@media (max-width: 992px) {
}

/* Large Desktop */
@media (min-width: 1200px) {
}
```

---

## 🚀 SHORTCUT KEYS

**Browser:**

- `Ctrl + F5` : Hard refresh (clear cache)
- `Ctrl + P` : Print / Save as PDF
- `F12` : Open Developer Tools

**XAMPP:**

- Start Apache & MySQL before testing

---

## 📞 TROUBLESHOOTING CEPAT

| Error            | Solusi                                    |
| ---------------- | ----------------------------------------- |
| Koneksi DB gagal | Cek MySQL running, cek Config/koneksi.php |
| Session error    | Logout & login ulang                      |
| CSS tidak muncul | Hard refresh (Ctrl+F5)                    |
| Rule tidak match | Cek data rule & rule_detail               |
| 404 Not Found    | Cek URL & folder name                     |

---

## 📈 STATISTIK PROYEK

```
Total Files     : 30+
Total Lines     : 3700+
Languages       : PHP, SQL, JavaScript, CSS, HTML
Database Tables : 7
Features        : 25+
Roles           : 2 (Admin & Asisten)
```

---

## 🎓 TIPS & TRICKS

### Menambah Gejala Baru

1. Login sebagai admin
2. Manajemen Gejala → Tambah
3. Format kode: G001, G002, dst.

### Membuat Rule yang Tepat

- Pilih gejala yang spesifik
- Minimal 2-3 gejala per rule
- Hindari overlap gejala antar rule

### Diagnosa Akurat

- Pilih semua gejala yang relevan
- Jangan skip gejala penting
- Jika tidak match, tambah rule baru

---

## 📚 RESOURCES

- **Bootstrap Docs:** https://getbootstrap.com/docs/5.3/
- **PHP Manual:** https://www.php.net/manual/
- **MySQL Docs:** https://dev.mysql.com/doc/
- **W3Schools:** https://www.w3schools.com/

---

**Last Updated:** January 20, 2025  
**Version:** 1.0.0
