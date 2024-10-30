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

// Cek jika ada ID artikel yang diberikan
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Query untuk menghapus artikel
    $sql = "DELETE FROM articles WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: admin_view_articles.php"); // Redirect setelah delete
        exit;
    } else {
        echo "Error deleting article: " . $conn->error;
    }
} else {
    echo "Article ID is not provided.";
}

// Menutup koneksi
$conn->close();
?>
