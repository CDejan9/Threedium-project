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
    
    <div class="col-7 mx-auto" id="updateForm">
    <h3 class="padding-30">Update article</h3>
            <form action="">
                <input type="hidden" id="tbArticleId" value="<?=$article->ArticleId?>">
                <div class="form-group">
                    <label for="tbArticleName">Article name:</label>
                    <input type="text" class="form-control" id="tbUpdateArticleName" value="<?=$article->ArticleName?>">
                </div>
                <div class="form-group">
                    <label for="taText">Article text:</label>
                    <textarea name="" id="taUpdateText" class="form-control"><?=$article->Text?></textarea>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h6>Initial picture</h6>
                        <div class="col-3 float-left">
                            <img style="width:100%" src="assets/images/<?=$article->InitialPicture?>" alt="<?=$article->ArticleName?>">
                        </div>
                        <div class="col-9 float-right">
                            <div class="custom-file">
                                <input type="file" disabled class="custom-file-input" id="fUpdateImage" required/>
                                <label class="custom-file-label" for="fImage"><?=$article->InitialPicture?></label><br>
                            </div>
                        </div>
                    </div>
                </div>
                    <?php
                        if(count($picture) != 0):
                    ?>
                         <div class="row borders">
                        <h6>Additional pictures</h6>
                        <?php
                            foreach($picture as $i=>$p):
                        ?>
                        <div class="col-12 inputFileBlock">
                            <div class="col-4 float-left">
                            <br>
                                <img style="width:100%" src="assets/images/<?=$p->Path?>" alt="<?=$p->Alt?>">
                            </div>
                            <div class="col-2 float-left">
                            <br>
                                <a href="#" data-idpicture="<?=$p->PictureId?>" data-idarticle="<?=$article->ArticleId?>" class="deletePicture"><i class='fas fa-times'></i></a>
                            </div>
                        </div>
                        <?php
                            endforeach;
                        ?>
                        </div>
                    <?php
                        endif;
                    ?>
                <div class="col-12 padding" id="pictures"></div>
                <div class="form-group col-12" id="buttons">
                    <span class="col-4">
                        <button type="button" class="btn btn-default backgroundColor other-picture">Add a new picture</button>
                    </span>
                </div>
                <div class="form-group">
                <br>
                    <button type="button" class="btn btn-default backgroundColor btn-block" id="btnUpdateArticle">Update</button>
                    </div>
            </form>
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