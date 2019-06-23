<?php if(isset($_SESSION['user'])){
    include "views/fixed/nav.php";
    if(isset($_GET['id'])):
    $id = $_GET['id'];
    include "models/articles/functions.php";
    $article = singleArticleById($id);
    $picture = allPictureByArticleId($id);
?>
<div class="container">
    <div class="row">
        <div class="col-8 mx-auto">
            <h2 class="padding-30"><?=$article->ArticleName?></h2>
            <img class="img-fluid" src="assets/images/<?=$article->InitialPicture?>" alt="<?=$article->ArticleName?>"/>
            <?php
                if(count($picture) != 0):
            ?>
            <div class="padding">
                <?php
                    foreach($picture as $p):
                ?>
                <img src="assets/images/<?=$p->Path?>" alt="<?=$p->Alt?>" class="img-fluid img-width"/>
                <?php endforeach;
                ?>
            </div>
            <?php
            endif;
            ?>
            <p class="font-italic padding info"><?=date("d.m.Y.", strtotime($article->CreatedAt))?> / by <?=$article->Username?></p>
            <p><?=$article->Text?></p>
        </div>
    </div>
</div>


<?php 
endif;
} 
else{ 
    header("Location: index.php?page=login");
}
?>