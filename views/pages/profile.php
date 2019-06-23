<?php if(isset($_SESSION['user'])){
    include "views/fixed/nav.php";
    $userId = $_SESSION['user']->UserId;
    include "models/users/functions.php";
    include "models/articles/functions.php";
    $user = userInfo($userId);
    $articles = allArticlesByUser($userId);
    
?>
<div class="container">
    <div class="row">
        <div class="col-5">
            <h3 class="padding-30"><?=$user->Name?> <?=$user->Surname?></h3>
            <p>Username: <?=$user->Username?></p>
            <p>Profile created at: <?=date("d.m.Y.", strtotime($user->CreatedAt))?></p>
        </div>
        <div class="col-7">
             <h3 class="padding-30">Insert article</h3>
            <form action="">
                <div class="form-group">
                    <label for="tbArticleName">Article name:</label>
                    <input type="text" class="form-control" id="tbArticleName">
                </div>
                <div class="form-group">
                    <label for="taText">Article text:</label>
                    <textarea name="" id="taText" class="form-control"></textarea>
                </div>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="fImage" required/>
                    <label class="custom-file-label" for="fImage">Choose file...</label>
                </div>
                <div class="col-12 padding" id="pictures"></div>
                <div class="form-group col-12" id="buttons">
                    <span class="col-4">
                        <button type="button" class="btn btn-default backgroundColor other-picture">Add a new picture</button>
                    </span>
                </div>
                <div class="form-group">
                <br>
                    <button type="button" class="btn btn-default backgroundColor btn-block" id="btnInsertArticle">Insert</button>
                    </div>
            </form>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-12 mx-auto padding-50" id="listOfAllArticles">
            <?php
            if(count($articles) == 0){
                echo "<p>No items.</p>";
            }
            else{
            ?>
            <table class="table">
                <tr>
                    <th>ID</th>
                    <th></th>
                    <th>Article name</th>
                    <th>Article text</th>
                    <th>Date</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <?php
                    $id = 1;
                    foreach($articles as $article):
                ?>
        
                <tr>
                    <td><?=$id++?></td>
                    <td><img style="width:200px"src="assets/images/<?=$article->InitialPicture?>" /></td>
                    <td><?=$article->ArticleName?></td>
                    <td><?=substr($article->Text, 0, 50)?></td>
                    <td><?=date("d.m.Y.", strtotime($article->CreatedAt))?></td>
                    <td><a href="#" data-idarticle="<?=$article->ArticleId?>"><i class='fas fa-edit'></i></a></td>
                    <td><a href="#" data-idarticle="<?=$article->ArticleId?>"><i class='fas fa-times'></i></a></td>
                    <td><a href="index.php?page=single-article&id=<?=$article->ArticleId?>">Details</a></td>
                </tr>
                <?php endforeach;?>
            </table>
            <?php } ?>
        </div>
    </div>
</div>


<?php 
} 
else{ 
    header("Location: index.php?page=login");
}
?>