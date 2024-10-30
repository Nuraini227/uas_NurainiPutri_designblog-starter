<?php
// Pengaturan koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blog_db";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mengambil total artikel
$sql_count = "SELECT COUNT(*) as total FROM articles";
$result_count = $conn->query($sql_count);
$total_articles = $result_count->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Admin Dashboard</h1>
    <div class="row">
        <div class="col-md-6">
            <div class="card text-white bg-primary mb-2">
                <div class="card-header">Total Artikel Yang ada diblog</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $total_articles; ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-white bg-info mb-2">
                <div class="card-header">Lihat Artikel</div>
                <div class="card-body">
                    <a href="admin_view_articles.php" class="btn btn-light">Lihat Semua Artikel</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Menutup koneksi
$conn->close();
?>
