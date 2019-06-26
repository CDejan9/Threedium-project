$(document).ready(function(){
    let url = window.location.href;
    if(url.indexOf("articles") != -1){
        //Funkcija koja dohvata sve proizvode i inicijalno ih ispisuje 
        getArticles();
    }
     //Prikaz dodatnih slika
     $(document).on("click", ".additionalPicture", function(){
        let src = $(this).attr("src");
        $("#initialPicture").attr("src", src);
    }); 

    //Dodovanje putanje u custom input file
    $("#fImage").blur(function(){
        let path = $(this).val();
        printText($(this));
    });

    $(document).on("blur", ".imgFiles", function(){
        let path = $(this).val();
        printText($(this));
    });

    function printText(inputFile){
        let path = inputFile.val();
        let arrayPath = path.split("\\");
        inputFile.next().text(arrayPath[arrayPath.length-1]);
    }

    //Iz padajuce liste se bira User ciji artikli se zele videti
    $("#ddlUser").change(function(){
        let idUser = $(this).val();
        if(idUser != 0){
            $.ajax({
                url: "models/articles/get-articles-by-user.php",
                method: "POST",
                data: {
                    id:idUser
                },
                success:function (data) {
                    printArticles(data.articles);
                    printPagination(data.num_of_articles);
                },
                error: function (xhr) {
                    console.log(xhr);
                }
       
            });
        }
        else{
            alert("Choose a user.");
        }
    });

    //Dodavanje novog polja za sliku prilikom unosa Artikla
    let counter = 1;
    $(document).on('click', '.other-picture', function(e){
        $("#pictures").addClass('borders');
        let divTag = $(document.createElement('div')).attr('class', 'form-group').attr("id", 'picture' + counter);
        divTag.after().html(`
            <div class="custom-file">
                <input type="file" name="file[]"class="custom-file-input imgFiles" id="additionalImage${counter}" required/>
                <label class="custom-file-label" for="additionalImage${counter}">Choose file...</label>
            </div>`);
        divTag.appendTo("#pictures");
        if(counter==1){
            $("#buttons").append(`
                <span class="col-4">
                    <button type="button" class="btn btn-default orange" id="removePicture">Remove picture</button>
                </span>`);
            $("#removePicture").attr("style","display:inline-block");  
            $("#removePicture").click(function(){
                counter--;
                $("#picture"+counter).remove();
                if(counter == 1){
                    $("#removePicture").remove();
                    $("#pictures").removeClass('borders');
                }
            });
        }
        counter++;
    });

    //Funkcija za unos novog artikla
    $("#btnInsertArticle").click(function(){
        let articleName, articleText, articleInitialPicture, file, filedata;
        articleName = $("#tbArticleName").val();
        articleText = $("#taText").val();
        articleInitialPicture = $("#fImage")[0].files[0];
        
        filedata = $(".imgFiles");
        formData = new FormData();
        formData.append('fImage', articleInitialPicture);
        formData.append('articleName', articleName);
        formData.append('articleText', articleText);

        if(filedata.length != 0){
            formData.append('numberOfNewFiles', filedata.length);  
            for (let i=0; i < filedata.length; i++) {
                file = filedata[i].files[0];
    
                if (formData) {
                    formData.append("file"+i, file);
                }
            }
        }
        
        $.ajax({
            url: "models/articles/insert-article.php",
            method: "POST",
            data: formData,
            contentType:false,
            processData: false,
            success:function (data) {
                printArticles(data.articles);
                printPagination(data.num_of_articles);
                articleName = $("#tbArticleName").val("");
                articleText = $("#taText").val("");
                $("#fImage").next().text("Choose file...");  
                $("#pictures").html(" ");  
                $("#pictures").removeClass("borders");
                $("#removePicture").attr("style", "display:none");       
            },
            error: function (xhr) {
                console.log(xhr);
            }
        });
        
    });

    //Brisanje artikala
    $(document).on('click', '.delete', function(e){
        e.preventDefault();
        let id = $(this).data('idarticle');
        $.ajax({
            url: "models/articles/delete-article.php",
            method: "POST",
            dataType: "json",
            data:{
                id : id
            },
            success:function (data) {
                printArticles(data.articles);
                printPagination(data.num_of_articles);
                $("#pagination-line").removeClass("pagination-color");
            },
            error: function (xhr) {
                console.log(xhr);
            }
        });
    });

    //Brisanje dodatnih slika prilikom update-a
    $(document).on('click', '.deletePicture', function(e){
        e.preventDefault();
        let idP = $(this).data('idpicture');
        let idA = $(this).data('idarticle');
        $(this).parent().parent().remove();

        $.ajax({
            url: "models/articles/delete-additional-picture.php",
            method: "POST",
            dataType: "json",
            data:{
                idP: idP,
                idA: idA
            },
            success:function (data) {
                printSingleArticleForUpdate(data);
                // console.log(data);
                
            },
            error: function (xhr) {
                console.log(xhr);
            }
        });
    });

    //Update artikla
    $(document).on('click', '#btnUpdateArticle', function(){
        let articleName, articleText, articleId, initialPicture, formData, fileDataExisting, fileDataNew;

        articleName = $("#tbUpdateArticleName").val();
        articleText = $("#taUpdateText").val();
        articleId = $("#tbArticleId").val();

        initialPicture = $("#fUpdateImage")[0].files[0];
        
        fileDataNew = $(".imgFiles");
        formData = new FormData();
        formData.append('fImage', initialPicture);
        formData.append('articleName', articleName);
        formData.append('articleText', articleText); 
        formData.append('articleId', articleId);

        if(fileDataNew.length != 0){
            formData.append('numberOfNewFiles', fileDataNew.length); 
            for (let i=0; i < fileDataNew.length; i++) {
                file = fileDataNew[i].files[0];
    
                if (formData) {
                    formData.append("file"+i, file);
                }
            }
        }

        $.ajax({
            url: "models/articles/update-article.php",
            method: "POST",
            data: formData,
            contentType:false,
            processData: false,
            success:function (data) {
                window.location.href="index.php?page=profile"      
            },
            error: function (xhr) {
                console.log(xhr);
            }
        });
    });

    //stranicenje
    $("body").on("click", ".articles-pagination", function(e){
        e.preventDefault();
        let limit = $(this).data("limit");

        $.ajax({
            url: "models/articles/get_with_pagination.php",
            method: "GET",
            data: {
                limit: limit
            },
            success: function(data){
                // console.log(data.num_of_articles);
                printArticles(data.articles);
                printPagination(data.num_of_articles);
            },
            error: function(error){
                console.log(error);
            }
        })
        
    });
     //stranicenje
     $("body").on("click", ".articles-pagination-all", function(e){
        e.preventDefault();
        let limit = $(this).data("limit");

        $.ajax({
            url: "models/articles/get_with_pagination_all.php",
            method: "GET",
            data: {
                limit: limit
            },
            success: function(data){
                // console.log(data.num_of_articles);
                printArticles(data.articles);
                printPaginationAll(data.num_of_articles);
                $("#pagination-line").addClass("pagination-color");
            },
            error: function(error){
                console.log(error);
            }
        })
        
    });

});
//stampanje stranicenja
function printPagination(num_of_articles){
    // alert(num_of_articles);
    if(num_of_articles != 0){
        let html = "";
        for(let i = 0; i < num_of_articles; i++){
            html += `<li class="page-item">
                    <a href="#" class="articles-pagination" data-limit="${ i }">
                        ${ i + 1 } 
                    </a>
                </li>`;
        }
        $("#pagination").html(html);
    }
    else{
        $("#pagination-line").removeClass("pagination-color");
        $("#pagination").html(" ");
    }
}
function printPaginationAll(num_of_articles){
    if(num_of_articles.length == 0){
        $("#pagination-line").removeClass("pagination-color");
    }
    else{
        $("#pagination-line").addClass("pagination-color");
        let html = "";
        for(let i = 0; i < num_of_articles; i++){
            html += `<li class="page-item">
                    <a href="#" class="articles-pagination-all" data-limit="${ i }">
                        ${ i + 1 } 
                    </a>
                </li>`;
        }
        $("#pagination").html(html);
    }
   
}
//Funkcija koja dohvata sve proizvode i inicijalno iz ispisuje 
function getArticles(){
    $.ajax({
        url: "models/articles/get-all.php",
        method: "POST",
        success:function (data) {
            printArticles(data.articles);
            printPaginationAll(data.num_of_articles);
        },
        error: function (xhr) {
            console.log(xhr);
        }
    });
}
//funkcija koja se poziva u succesu za ispisivanje artikala
function printArticles(data){
    let url = window.location.href;
    let articlesHtml="";
    if(data.length == 0){
        articlesHtml += "No items.";
    }
    else{
        articlesHtml += `
        <table class="table">
            <tr>
                <th>ID</th>
                <th></th>
                <th>Article name</th>
                <th>Article text</th>
                <th>Date</th>`
                if(url.indexOf("articles") == -1)
                {
                    articlesHtml += `<th></th>
                    <th></th>
                    <th></th>`
                }
                else{
                    articlesHtml += ` <th>User</th>
                    <th></th>`
                }
               
            articlesHtml += `</tr>` 
        ;
        let id = 1;
        for(let article of data)
        {
            let dateDB = new Date(article.created);
            let datePrint = dateDB.getDate()+"."+(dateDB.getMonth()+1)+"."+dateDB.getFullYear()+".";
            articlesHtml += `
            <tr>
                <td>${id++}</td>
                <td><img style="width:200px"src="assets/images/${article.InitialPicture}" /></td>
                <td>${article.ArticleName}</td>
                <td>${(article.Text).substr(0,50)}...</td>
                <td>${datePrint}</td>`
                if(url.indexOf("articles") == -1)
                {
                    articlesHtml += ` <td><a href="index.php?page=show-update&id=${article.ArticleId}"><i class='fas fa-edit'></i></a></td>
                    <td><a href="#" data-idarticle="${article.ArticleId}" class="delete"><i class='fas fa-times'></i></a></td>`
                }
                else{
                    articlesHtml += ` <td>${article.Username}</td>`
                }
                
            articlesHtml +=  `<td><a href="index.php?page=single-article&id=${article.ArticleId}">Details</a></td>`
            articlesHtml += ` </tr>`
            ;
        }
        articlesHtml += "</table>";

    }
    $("#listOfAllArticles").html(articlesHtml);
}
//Funkcija za ispisivanje forme za update artikla
function printSingleArticleForUpdate(article){
    let articleHtml = `
        
    <form action="">
    <div class="form-group">
        <label for="tbArticleName">Article name:</label>
        <input type="text" class="form-control" id="tbArticleName" value="${article.ArticleName}">
    </div>
    <div class="form-group">
        <label for="taText">Article text:</label>
        <textarea name="" id="taText" class="form-control">${article.Text}</textarea>
    </div>
    <div class="row">
        <div class="col-12">
            <h6>Initial picture</h6>
            <div class="col-3 float-left">
                <img style="width:100%" src="assets/images/${article.InitialPicture}" alt="${article.ArticleName}">
            </div>
            <div class="col-9 float-right">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="fImage" required/>
                    <label class="custom-file-label" for="fImage">${article.InitialPicture}</label><br>
                </div>
            </div>
        </div>
    </div>`;
            
    if(article.hasOwnProperty('pictures')){
        let p = article.pictures;
        articleHtml += `
             <div class="row borders">
            <h6>Additional pictures</h6>`;
            
                for(let i in article.pictures){
                    articleHtml +=`
            <div class="col-12 inputFileBlock">
                <div class="col-3 float-left">
                <br>
                    <img style="width:100%" src="assets/images/${p[i].Path}" alt="${p[i].Alt}">
                </div>
                <div class="col-2 float-left">
                <br>
                    <a href="#" data-idpicture="${p[i].PictureId}" data-idarticle="${article.ArticleId}" class="deletePicture"><i class='fas fa-times'></i></a>
                </div>
            </div>`;
                }
                articleHtml += `
            </div>`;
            }
            articleHtml += `
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
    `;

    $("#updateForm").html(articleHtml);
}