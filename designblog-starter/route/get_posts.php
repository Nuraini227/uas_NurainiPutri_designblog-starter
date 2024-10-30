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

// Tentukan jumlah posting per halaman
$posts_per_page = 5;

// Ambil halaman saat ini dari URL, default ke halaman 1 jika tidak ada
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $posts_per_page;

// Menggunakan prepared statement dengan LIMIT dan OFFSET
$sql = "SELECT id, title, category, description, author, date_posted, read_time, image_url 
        FROM articles 
        ORDER BY date_posted DESC 
        LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $posts_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Menampilkan setiap post dengan template baru
    while ($row = $result->fetch_assoc()) {
        echo '<div class="grids5-info img-block-mobile">';
        echo '    <div class="blog-info align-self">';
        echo '        <span class="category">' . htmlspecialchars($row["category"]) . '</span>';
        echo '         <a href="article.php?id=' . $row['id'] . '" class="blog-desc">' . htmlspecialchars($row["title"]) . '</a>';
        echo '        <p>' . htmlspecialchars($row["description"]) . '</p>';
        echo '        <div class="author align-items-center mt-3 mb-1">';
        echo '            <a href="#author">' . htmlspecialchars($row["author"]) . '</a> in <a href="#url">' . htmlspecialchars($row["category"]) . '</a>';
        echo '        </div>';
        echo '        <ul class="blog-meta">';
        echo '            <li class="meta-item blog-lesson"><span class="meta-value">' . date("F d, Y", strtotime($row["date_posted"])) . '</span></li>';
        echo '            <li class="meta-item blog-students"><span class="meta-value">' . htmlspecialchars($row["read_time"]) . ' read</span></li>';
        echo '        </ul>';
        echo '    </div>';
        // Correctly display the image
        echo '    <a href="article.php?id=' . $row['id'] . '" class="d-block zoom mt-md-0 mt-3"><img src="admin/' . htmlspecialchars($row["image_url"]) . '" alt="Gambar artikel" class="img-fluid radius-image news-image"></a>';
        echo '</div>';
    }
} else {
    echo "<p>No articles found.</p>";
}

// Close statement and connection
$stmt->close();

?>
