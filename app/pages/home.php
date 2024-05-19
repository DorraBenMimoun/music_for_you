<div class="transparent">
    <?php require page('includes/header'); ?>
</div>
<?php
/* if (logged_in()) {
     message(" login successful");
 }*/

?>
<section class="content">
    <div class="bg_gradient"></div>
    <div class="home_main">
        <div class="left">
            <div class="title">
                <h1>Music application <br>To share your Sound</h1>
            </div>
            <div class="textM">
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. It as been the
                    industry'sprinter.</p>
            </div>
            <div class="btnM">
                <?php if (!logged_in()): ?>
                    <a href="<?= ROOT ?>/login"><button>Log In</button></a>
                <?php endif ?>
                <a href="<?= ROOT ?>/musics"><button class="sec">Looking for music</button></a>
            </div>
        </div>
        <div class="right">
            <img src="../public/assets/images/Vinyl.svg" alt="Vinyl">
        </div>

        <i class='bx bxs-down-arrow'></i>

    </div>




    <?php
    $rows = db_query(("select * from musics order by id desc"));
    ?>

    <?php if (!empty($rows)): ?>

        <?php foreach ($rows as $row): ?>
            <?php include page('includes/song') ?>

        <?php endforeach; ?>
    <?php else: ?>
        <div class="text">No songs found</div>
    <?php endif; ?>
</section>
<?php require page('includes/footer'); ?>