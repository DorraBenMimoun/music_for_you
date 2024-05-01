<?php require page('includes/header'); ?>
<?php 
   /* if (logged_in()) {
        message(" login successful");
    }*/

?>
    <section>
        <img class="hero" src="<?=ROOT?>/assets/images/th.jpg" alt=""></a>
    </section>
    <div class="section-title">Featured</div>

    <section class="content">

        <?php
         $rows = db_query(("select * from musics order by id desc"));
        ?>

        <?php if(!empty($rows)): ?>

        <?php foreach($rows as $row): ?>
            <?php include page('includes/song')?>
        
        <?php endforeach; ?>
        <?php else: ?>
            <div class="m-2" >No songs found</div>
        <?php endif; ?>

        

    </section>

    <?php require page('includes/footer'); ?>
