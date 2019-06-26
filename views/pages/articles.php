<?php if(isset($_SESSION['user'])){
    include "views/fixed/nav.php";
?>
<div class="container">
    <div class="row padding-50">
        <div class="col-6 mx-auto">
            <form action="">
                <div class="form-group">
                    <label for="">Filter by user:</label>
                    <select name="" id="ddlUser" class="form-control">
                        <option value="0">Choose a user</option>
                        <?php
                            include "models/users/functions.php";
                            $users = listOfUsers();
                            foreach($users as $user):
                        ?>
                        <option value="<?=$user->UserId?>"><?=$user->Username?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="container">
    <div class="row padding-50">
        <div class="col-12 mx-auto" id="listOfAllArticles">
        </div>
    </div>
    <div class="row justify-content-center" id="pagination-line">
        <nav>
            <ul class="pagination" id="pagination">
            </ul> 
        </nav> 
    </div>
</div>
<?php 
} 
else{ 
    header("Location: index.php?page=login");
}
?>