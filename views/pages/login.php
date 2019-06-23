 <div class="container">
        <div class="row">
            <div class="col-6 mx-auto pt-5" data-aos="fade-up" data-aos-anchor-placement="center-bottom">
                <form action="models/users/login.php" method="post">
                    <div class="form-group">
                        <label for="tbUsername">Username</label>
                        <input type="text" class="form-control" id="tbUsername" name="tbUsername" placeholder="Example: User123"/>
                    </div>
                    <div class="form-group">
                        <label for="tbPassword">Password</label>
                        <input type="password" class="form-control" id="tbPassword" name="tbPassword" placeholder="Example: User1 23.*@"/>
                    </div>
                    <div class="form-group">
                       <button type="submit" class="btn btn-default backgroundColor btn-block" name="btnLogin">Login</button>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-6 mx-auto pt-5">
                    <?php
                     
                        if(isset($_SESSION['error_validation'])){
                            $errors = $_SESSION['error_validation'];
                            foreach($errors as $e){
                                echo $e."<br/>";
                            }
                            unset($_SESSION['error_validation']);
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
   