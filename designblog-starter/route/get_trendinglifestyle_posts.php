<?php
// Pengaturan koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blog_db";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil 5 artikel dengan kategori Technology
$sql = "SELECT id, title, category, author, date_posted, read_time
        FROM articles 
        WHERE category = 'Lifestyle'
        ORDER BY read_time DESC 
        LIMIT 5";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $index = 1;
    while ($row = $result->fetch_assoc()) {
        echo '<div class="grids5-info">';
        echo '    <h4>' . str_pad($index, 2, '0', STR_PAD_LEFT) . '.</h4>';
        echo '    <div class="blog-info">';
        echo '        <a href="article.php?id=' . $row['id'] . '" class="blog-desc1">' . htmlspecialchars($row["title"]) . '</a>';
        echo '        <div class="author align-items-center mt-2 mb-1">';
        echo '            <a href="#author">' . htmlspecialchars($row["author"]) . '</a> in <a href="#url">' . htmlspecialchars($row["category"]) . '</a>';
        echo '        </div>';
        echo '        <ul class="blog-meta">';
        echo '            <li class="meta-item blog-lesson"><span class="meta-value">' . date("F d, Y", strtotime($row["date_posted"])) . '</span></li>';
        echo '            <li class="meta-item blog-students"><span class="meta-value">' . htmlspecialchars($row["read_time"]) . ' read</span></li>';
        echo '        </ul>';
        echo '    </div>';
        echo '</div>';
        $index++;
    }
} else {
    echo "<p>No articles found in the Technology category.</p>";
}

// Menutup koneksi
$conn->close();
?>
