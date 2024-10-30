<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blog_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mendapatkan ID artikel dari URL dan memvalidasinya
$article_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($article_id > 0) {
    // Menambahkan read_time ketika artikel dibaca
    $update_sql = "UPDATE articles SET read_time = read_time + 1 WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("i", $article_id);
    $stmt->execute();

    // Mengambil data artikel untuk ditampilkan
    $sql = "SELECT title, description, author, date_posted, read_time, image_url FROM articles WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $article_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Menampilkan artikel
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
?>
        <!DOCTYPE html>
        <html lang="id">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo htmlspecialchars($row['title']); ?></title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f5f5f5;
                    margin: 0;
                    padding: 20px;
                }

                .container {
                    max-width: 800px;
                    margin: auto;
                    background: #fff;
                    border-radius: 8px;
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                    padding: 20px;
                }

                .article-detail {
                    margin: 20px 0;
                }

                .article-title {
                    font-size: 2em;
                    margin-bottom: 10px;
                }

                .article-meta {
                    color: #555;
                    font-size: 0.9em;
                    margin-bottom: 20px;
                }

                .article-image {
                    width: 100%;
                    height: auto;
                    border-radius: 8px;
                    margin-bottom: 20px;
                }

                .article-content {
                    line-height: 1.6;
                    color: #333;
                }

                .author,
                .date,
                .read-time {
                    margin-right: 15px;
                }

                .back-button {
                    margin-top: 20px;
                }

                .back-button .btn.kembali {
                    display: inline-block;
                    padding: 10px 20px;
                    background-color: #007bff;
                    /* Bootstrap primary color */
                    color: #fff;
                    text-decoration: none;
                    border-radius: 5px;
                    transition: background-color 0.3s;
                }

                .back-button .btn.kembali:hover {
                    background-color: #0056b3;
                    /* Darker shade for hover effect */
                }

                @media (max-width: 600px) {
                    .article-title {
                        font-size: 1.5em;
                    }
                }
            </style>
        </head>

        <body>
            <div class="container">
                <article class="article-detail">
                    <h1 class="article-title"><?php echo htmlspecialchars($row['title']); ?></h1>
                    <div class="article-meta">
                        <span class="author">By <?php echo htmlspecialchars($row['author']); ?></span>
                        <span class="date">Posted on <?php echo date("F d, Y", strtotime($row['date_posted'])); ?></span>
                        <span class="read-time"><?php echo htmlspecialchars($row['read_time']); ?> read</span>
                    </div>
                    <img src="admin/<?php echo htmlspecialchars($row['image_url']); ?>" alt="Gambar artikel" class="article-image img-fluid">
                    <div class="article-content">
                        <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
                    </div>
                    <div class="back-button">
                        <a href="index.php" class="btn kembali">Kembali</a>
                    </div>
                </article>
            </div>
        </body>

        </html>
<?php
    } else {
        echo "<p>Artikel tidak ditemukan.</p>";
    }
} else {
    echo "<p>ID artikel tidak valid.</p>";
}

// Menutup koneksi
$conn->close();
?>