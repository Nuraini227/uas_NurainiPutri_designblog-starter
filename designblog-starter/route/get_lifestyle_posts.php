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

// Menentukan jumlah artikel per halaman
$posts_per_page = 5; // Misalnya 5 artikel per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $posts_per_page;

// Query untuk mengambil artikel dari kategori Lifestyle dengan pagination
$sql = "SELECT id, title, category, author, date_posted, read_time, image_url 
        FROM articles 
        WHERE category = 'Lifestyle'
        ORDER BY read_time DESC 
        LIMIT $offset, $posts_per_page";
$result = $conn->query($sql);

// Menampilkan artikel
if ($result->num_rows > 0) {
    echo '<div class="row">';
    $isFirst = true;
    while ($row = $result->fetch_assoc()) {
        if ($isFirst) {
            echo '<div class="col-md-12 item">';
            $isFirst = false;
        } else {
            echo '<div class="col-lg-6 col-md-6 item mt-5 pt-lg-3">';
        }

        echo '    <div class="card">';
        echo '        <div class="card-header p-0 position-relative">';
        echo '            <a href="#blog-single">';
        echo '                <img class="card-img-bottom d-block radius-image" src="admin/' . htmlspecialchars($row["image_url"]) . '" alt="Card image cap">';
        echo '            </a>';
        echo '        </div>';
        echo '        <div class="card-body p-0 blog-details">';
        echo '            <a href="article.php?id=' . $row['id'] . '" class="blog-desc">' . htmlspecialchars($row["title"]) . '</a>';
        echo '            <p>Lorem ipsum dolor sit amet consectetur ipsum adipisicing elit. Qui eligendi vitae sit.</p>';
        echo '            <div class="author align-items-center mt-3 mb-1">';
        echo '                <a href="#author">' . htmlspecialchars($row["author"]) . '</a> in <a href="#url">' . htmlspecialchars($row["category"]) . '</a>';
        echo '            </div>';
        echo '            <ul class="blog-meta">';
        echo '                <li class="meta-item blog-lesson"><span class="meta-value">' . date("F d, Y", strtotime($row["date_posted"])) . '</span></li>';
        echo '                <li class="meta-item blog-students"><span class="meta-value">' . htmlspecialchars($row["read_time"]) . ' read</span></li>';
        echo '            </ul>';
        echo '        </div>';
        echo '    </div>';
        echo '</div>';
    }
    echo '</div>'; // Menutup div.row
} else {
    echo "<p>No articles found in the Lifestyle category.</p>";
}

// Menghitung total jumlah postingan dalam kategori Lifestyle
$sql_count = "SELECT COUNT(*) as total FROM articles WHERE category = 'Lifestyle'";
$result_count = $conn->query($sql_count);
$total_posts = $result_count->fetch_assoc()['total'];
$total_pages = ceil($total_posts / $posts_per_page);

// Pagination
?>
<div class="pagination-wrapper mt-5">
    <ul class="page-pagination">
        <?php if ($page > 1): ?>
            <li><a class="next" href="?page=<?php echo $page - 1; ?>"><span class="fa fa-angle-left"></span></a></li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li><a class="page-numbers <?php echo ($i == $page) ? 'current' : ''; ?>" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <li><a class="next" href="?page=<?php echo $page + 1; ?>"><span class="fa fa-angle-right"></span></a></li>
        <?php endif; ?>
    </ul>
</div>

<?php
// Menutup koneksi
$conn->close();
?>
