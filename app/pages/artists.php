<?php ob_start();
require page('includes/header'); ?>

<div class="section-title">Artist</div>
<?php
$searchTerm = trim($_GET['search'] ?? '');
$rows = [];
$errors = [];
$params = [];

$query = "select * from artists";
if (isset($_GET['submit_search'])) {
    if (!empty($searchTerm)) {
        $query .= " where name LIKE :searchTerm ";
        $params = ['searchTerm' => "%$searchTerm%"];
    } else {
        $errors['search'] = "Search term is required.";

    }
}
$rows = db_query($query, $params);
?>

<!-- Search Form -->
<form action="" method="get" class="search-form">
    <div class="form-group">
        <input class="form-control m-1" type="text" name="search" placeholder="Search artists by name"
            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <button class="btn m-1" type="submit" name="submit_search">Search</button>
    </div>
    <?php if (!empty($errors['search'])): ?>
        <p class="text-danger"><?= $errors['search'] ?></p>
    <?php endif; ?>
</form>

<section class="content">

    <?php if (!empty($rows)): ?>
        <?php foreach ($rows as $row): ?>
            <?php include page('includes/artist'); ?>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-danger">No artist found.</p>
    <?php endif; ?>
</section>

<?php require page('includes/footer'); ?>