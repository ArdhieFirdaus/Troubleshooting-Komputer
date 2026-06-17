<?php
require_once '../Auth/cek_session.php';
cek_role('admin');
require_once '../Config/koneksi.php';

$filter_tanggal_mulai = isset($_GET['tanggal_mulai']) ? $_GET['tanggal_mulai'] : '';
$filter_tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : '';
$filter_asisten = isset($_GET['asisten']) ? $_GET['asisten'] : '';

$query = "SELECT d.*, u.nama_lengkap, u.username 
          FROM diagnosa d 
          INNER JOIN users u ON d.id_user = u.id_user 
          WHERE 1=1";

if (!empty($filter_tanggal_mulai) && !empty($filter_tanggal_akhir)) {
    $query .= " AND DATE(d.tanggal) BETWEEN '$filter_tanggal_mulai' AND '$filter_tanggal_akhir'";
}

if (!empty($filter_asisten)) {
    $query .= " AND d.id_user = '$filter_asisten'";
}

$query .= " ORDER BY d.tanggal DESC";

$result = mysqli_query($koneksi, $query);

// Ambil nama asisten jika ada
$asisten_nama = '';
if (!empty($filter_asisten)) {
    $q = "SELECT nama_lengkap FROM users WHERE id_user = '".mysqli_real_escape_string($koneksi, $filter_asisten)."' LIMIT 1";
    $r = mysqli_query($koneksi, $q);
    if ($row = mysqli_fetch_assoc($r)) $asisten_nama = $row['nama_lengkap'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Laporan Diagnosa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
        }
        body { padding: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td><strong>Periode</strong></td>
                        <td>: <?php echo !empty($filter_tanggal_mulai) && !empty($filter_tanggal_akhir) ?
                                    date('d/m/Y', strtotime($filter_tanggal_mulai)).' s/d '.date('d/m/Y', strtotime($filter_tanggal_akhir)) : 'Semua Data'; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Asisten</strong></td>
                        <td>: <?php echo !empty($asisten_nama) ? htmlspecialchars($asisten_nama) : 'Semua'; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Cetak</strong></td>
                        <td>: <span id="tanggalCetakAdmin"><?php echo date('d F Y, H:i:s'); ?> WIB</span></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Laporan Diagnosa</h3>
            <div class="no-print">
                <button class="btn btn-primary" onclick="window.print()">Cetak</button>
                <button class="btn btn-secondary" onclick="window.close()">Tutup</button>
            </div>
        </div>

        <div class="mb-3">
            <strong>Filter:</strong>
            <div>
                Tanggal: <?php echo !empty($filter_tanggal_mulai) ? htmlspecialchars($filter_tanggal_mulai) : '-'; ?> 
                sampai <?php echo !empty($filter_tanggal_akhir) ? htmlspecialchars($filter_tanggal_akhir) : '-'; ?>
            </div>
            <div>Asisten: <?php echo !empty($asisten_nama) ? htmlspecialchars($asisten_nama) : 'Semua'; ?></div>
        </div>

        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Asisten Lab</th>
                    <th>Hasil Kerusakan</th>
                    <th>Gejala</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    $no = 1;
                    while($row = mysqli_fetch_assoc($result)) {
                        // ambil gejala
                        $id = $row['id_diagnosa'];
                        $qg = "SELECT g.kode_gejala, g.nama_gejala FROM diagnosa_detail dd INNER JOIN gejala g ON dd.id_gejala = g.id_gejala WHERE dd.id_diagnosa = '$id'";
                        $rg = mysqli_query($koneksi, $qg);
                        $gejala_list = [];
                        while($gg = mysqli_fetch_assoc($rg)) $gejala_list[] = $gg['kode_gejala'].' - '.$gg['nama_gejala'];
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo date('d/m/Y H:i:s', strtotime($row['tanggal'])); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_lengkap']); ?> (<?php echo htmlspecialchars($row['username']); ?>)</td>
                            <td><?php echo !empty($row['hasil_kerusakan']) ? htmlspecialchars($row['hasil_kerusakan']) : '-'; ?></td>
                            <td><?php echo !empty($gejala_list) ? implode('<br>', $gejala_list) : '-'; ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    echo '<tr><td colspan="5" class="text-center">Tidak ada data diagnosa</td></tr>';
                }
                ?>
            </tbody>
        </table>

        <!-- Dicetak pada (dihapus, sudah tampil di bagian atas) -->
    </div>

    <script>
        // tampilkan jam realtime browser dan otomatis cetak setelah update
        function pad(n){ return n < 10 ? '0'+n : n; }
        function updateClock() {
            const now = new Date();
            const options = { day: '2-digit', month: 'long', year: 'numeric' };
            const dateStr = now.toLocaleDateString('id-ID', options);
            const timeStr = pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());
            // update tanggalCetakAdmin ke waktu WIB (Asia/Jakarta)
            const elAdmin = document.getElementById('tanggalCetakAdmin');
            if (elAdmin) {
                const tanggalWib = now.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric', timeZone: 'Asia/Jakarta' });
                const waktuWib = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false, timeZone: 'Asia/Jakarta' });
                elAdmin.textContent = tanggalWib + ', ' + waktuWib + ' WIB';
            }
        }

        window.onload = function() {
            // lakukan update segera
            updateClock();

            // pastikan update lagi sebelum mencetak, beri sedikit delay untuk render
            setTimeout(function(){
                updateClock();
                window.print();
            }, 800);

            setInterval(updateClock, 1000);
            window.addEventListener('beforeprint', function(){
                updateClock();
            });
        };
    </script>
</body>
</html>
