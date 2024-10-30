<?php
// Konfigurasi parameter koneksi database
$host = 'localhost'; // Ubah sesuai dengan host database Anda
$username = 'root'; // Ubah sesuai dengan username database Anda
$password = ''; // Ubah sesuai dengan password database Anda
$database = 'blog_db'; // Ubah sesuai dengan nama database Anda

// Membuat koneksi ke database
$conn = new mysqli($host, $username, $password, $database);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Memproses formulir saat tombol "Add Article" ditekan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $author = $_POST['author'];
    $date_posted = $_POST['date_posted'];
    
    // Set read_time to 0 if not provided
    $read_time = isset($_POST['read_time']) ? (int)$_POST['read_time'] : 0;

    // Menangani upload gambar
    $image_url = 'uploads/' . basename($_FILES['image_url']['name']);
    
    // Memindahkan file yang diunggah ke folder 'uploads'
    if (move_uploaded_file($_FILES['image_url']['tmp_name'], $image_url)) {
        // Menyiapkan pernyataan SQL untuk memasukkan data ke dalam tabel
        $stmt = $conn->prepare("INSERT INTO articles (title, category, description, author, date_posted, read_time, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssis", $title, $category, $description, $author, $date_posted, $read_time, $image_url);
        
        // Eksekusi pernyataan
        if ($stmt->execute()) {
            header("Location: admin_view_articles.php"); // Ubah ke halaman tujuan yang diinginkan
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }

        // Menutup pernyataan
        $stmt->close();
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

// Menutup koneksi
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Article</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Add New Article</h1>
    <form method="POST" enctype="multipart/form-data"> <!-- enctype untuk pengunggahan file -->
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="category">Category</label>
            <select class="form-control" id="category" name="category" required>
                <option value="Technology">Technology</option>
                <option value="Lifestyle">Lifestyle</option>
                <!-- Tambahkan kategori lain sesuai kebutuhan -->
            </select>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" required></textarea>
        </div>
        <div class="form-group">
            <label for="author">Author</label>
            <input type="text" class="form-control" id="author" name="author" required>
        </div>
        <div class="form-group">
            <label for="date_posted">Date Posted</label>
            <input type="date" class="form-control" id="date_posted" name="date_posted" required>
        </div>
        <div class="form-group">
            <label for="image_url">Upload Image</label>
            <input type="file" class="form-control" id="image_url" name="image_url" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Article</button>
        <a href="admin_view_articles.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
