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

// Query untuk mengambil semua artikel
$sql = "SELECT id, title, category, description, author, date_posted, read_time, image_url FROM articles";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Semua Artikel</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Semua Artikel</h1>
    <a href="admin_add_article.php" class="btn btn-primary" style="margin-bottom: 20px;">Tambah Artikel Baru</a>
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Category</th>
                <th>Description</th>
                <th>Author</th>
                <th>Date Posted</th>
                <th>Read Time</th>
                <th>Image URL</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["title"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["category"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["description"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["author"]) . "</td>";
                    echo "<td>" . date("F d, Y", strtotime($row["date_posted"])) . "</td>";
                    echo "<td>" . htmlspecialchars($row["read_time"]) . "</td>";
                    echo "<td><img src='" . htmlspecialchars($row["image_url"]) . "' alt='Image' style='width: 100px;'></td>";
                    echo "<td>
                            <a href='admin_edit_article.php?id=" . $row["id"] . "' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='admin_delete_article.php?id=" . $row["id"] . "' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this article?');\">Delete</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9' class='text-center'>No articles found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
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
