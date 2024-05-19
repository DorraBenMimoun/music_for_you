<?php require page('includes/header'); ?>


<?php
$query = "select musics.*, artists.name AS artist_name, categories.name AS category_name FROM musics 
        LEFT JOIN artists ON musics.artist_id = artists.id 
        LEFT JOIN categories ON musics.category_id = categories.id";

$params = [];
$searchTerm = $_GET['search_term'] ?? '';
$searchType = $_GET['search_type'] ?? '';
$errors = [];
if (isset($_GET['submit_search'])) {
    if (!empty($searchTerm) && !empty($searchType)) {
        switch ($searchType) {
            case 'title':
                $query .= " WHERE musics.title LIKE :searchTerm";
                break;
            case 'artist':
                $query .= " WHERE artists.name LIKE :searchTerm";
                break;
            case 'category':
                $query .= " WHERE categories.name LIKE :searchTerm";
                break;
        }
        $params['searchTerm'] = "%$searchTerm%";
    } else {
        $errors['search'] = "Search term and type are required.";
    }
}
$rows = db_query($query, $params);
?>
<div class="section-title">Music</div>
<div class="search">
    <form action="<?= ROOT ?>/musics" class="search-bar-container"  method="get">
        <div class="form-group">
            <input class="form-control m-1" type="text" placeholder="Search for music" name="search_term"
                value="<?= htmlspecialchars($_GET['search_term'] ?? '') ?>">
            <select name="search_type" class="form-select m-1">
                <option value="">--Search by--</option>

                <option value="title">Title</option>
                <option value="artist">Artist</option>
                <option value="category">Category</option>
            </select>
            <button type="submit" class="btnSearch" name="submit_search"><i class='bx bx-search'></i>Search</button>
        </div>
        <?php if (!empty($errors['search'])): ?>
            <p class="text-danger"><?= $errors['search'] ?></p>
        <?php endif; ?>
    </form>
</div>
<section class="content">
    <?php if (!empty($rows)): ?>

        <?php foreach ($rows as $row): ?>
            <?php include page('includes/song') ?>

        <?php endforeach; ?>

    <?php else: ?>
        <p class="text-danger ">No musics found .</p>
    <?php endif; ?>
</section>
<?php require page('includes/footer'); ?>