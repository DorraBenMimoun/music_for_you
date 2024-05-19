<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Music for You</title>
    <style>
        /* General body styles */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background: #f4f4f4;
    color: #333;
}


/* Main content section */
.about-section {
    background: #fff;
    margin: 20px auto;
    padding: 20px;
    width: 80%;
    max-width: 800px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

/* Headings */
h1 {
    color: #333;
}

/* Paragraph styles */
p {
    font-size: 16px;
    line-height: 1.6;
    color: #666;
}

/* List styles */
ul {
    list-style: inside square;
}

li {
    margin-bottom: 10px;
}


/* Responsive adjustments */
@media (max-width: 768px) {
    .about-section {
        width: 95%;
        padding: 10px;
    }

    h1 {
        font-size: 24px;
    }

    .btn {
        padding: 8px 16px;
    }
}

    </style>
</head>
<body>
    <?php require 'includes/header.php'; ?>

    <div class="about-section">
        <h1>About Us</h1>
        <p>Welcome to Music for You, your ultimate destination for listening to, downloading, and discovering music. Founded in [Year], our mission is to make music accessible to everyone, everywhere, at any time.</p>
        <p>At Music for You, users can:</p>
        <ul>
            <li><strong>Listen to music:</strong> Enjoy unlimited access to our extensive library of music tracks, from timeless classics to the latest releases.</li>
            <li><strong>Download music:</strong> Download your favorite songs and listen offline wherever you are.</li>
            <li><strong>Create playlists:</strong> Organize your favorite tracks into playlists for every occasion.</li>
            <li><strong>Explore playlists:</strong> Discover music collections curated by other users and find new musical inspirations.</li>
        </ul>
        <p>At Music for You, we believe in the unifying power of music and are committed to providing the best listening experience to our community of passionate music fans.</p>
        <p>We hope you find your sound with us and invite you to explore, share, and enjoy music like never before.</p>
    </div>

    <?php require 'includes/footer.php'; ?>
</body>
</html>
