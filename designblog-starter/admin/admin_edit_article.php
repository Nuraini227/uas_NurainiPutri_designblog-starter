<?php
// Konfigurasi parameter koneksi database
$host = 'localhost'; // Ubah sesuai dengan host database Anda
$username = 'root'; // Ubah sesuai dengan username database Anda
$password = ''; // Ubah sesuai dengan password database Anda
$database = 'blog_db'; // Ubah sesuai dengan nama database Anda

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Mengambil data artikel yang ada
    $result = $conn->query("SELECT * FROM articles WHERE id = $id");
    $article = $result->fetch_assoc();
    
    if (!$article) {
        die("Artikel tidak ditemukan.");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $author = $_POST['author'];
    $date_posted = $_POST['date_posted'];
    
    $image_url = $article['image_url']; 
    if ($_FILES['image_url']['name']) {
        $image_url = 'uploads/' . basename($_FILES['image_url']['name']);
        
        if (!move_uploaded_file($_FILES['image_url']['tmp_name'], $image_url)) {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    $stmt = $conn->prepare("UPDATE articles SET title = ?, category = ?, description = ?, author = ?, date_posted = ?, image_url = ? WHERE id = ?");
    $stmt->bind_param("ssssssi", $title, $category, $description, $author, $date_posted, $image_url, $id);
    
    if ($stmt->execute()) {
        header("Location: admin_view_articles.php"); 
            exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Article</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Edit Article</h1>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($article['title']); ?>" required>
        </div>
        <div class="form-group">
            <label for="category">Category</label>
            <select class="form-control" id="category" name="category" required>
                <option value="Technology" <?php echo ($article['category'] == 'Technology') ? 'selected' : ''; ?>>Technology</option>
                <option value="Lifestyle" <?php echo ($article['category'] == 'Lifestyle') ? 'selected' : ''; ?>>Lifestyle</option>
            </select>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" required><?php echo htmlspecialchars($article['description']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="author">Author</label>
            <input type="text" class="form-control" id="author" name="author" value="<?php echo htmlspecialchars($article['author']); ?>" required>
        </div>
        <div class="form-group">
            <label for="date_posted">Date Posted</label>
            <input type="date" class="form-control" id="date_posted" name="date_posted" value="<?php echo htmlspecialchars($article['date_posted']); ?>" required>
        </div>
        <div class="form-group">
            <label for="image_url">Image Upload</label>
            <input type="file" class="form-control" id="image_url" name="image_url" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Update Article</button>
        <a href="admin_view_articles.php" class="btn btn-secondary">Cancel</a>
    </form>
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
