$(document).ready(function(){
    //Funkcija koja dohvata sve proizvode i inicijalno ih ispisuje 

    let url = window.location.href;
    if(url.indexOf("articles") != -1){
        getArticles();
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
                    printArticles(data);
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
                printArticles(data);
                articleName = $("#tbArticleName").val("");
                articleText = $("#taText").val("");
            },
            error: function (xhr) {
                console.log(xhr);
            }
        });
        
    });
});
//Funkcija koja dohvata sve proizvode i inicijalno iz ispisuje 
function getArticles(){
    $.ajax({
        url: "models/articles/get-all.php",
        method: "POST",
        success:function (data) {
            printArticles(data);
        },
        error: function (xhr) {
            console.log(xhr);
        }

    });
}
//funkcija koja se poziva u succesu za ispisivanje artikala
function printArticles(data){
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
                <th>Date</th>
                <th>User</th>
                <th></th>
            </tr>
        `;
        let id = 1;
        for(let article of data)
        {
            let dateDB = new Date(article.CreatedAt);
            let datePrint = dateDB.getDate()+"."+(dateDB.getMonth()+1)+"."+dateDB.getFullYear()+".";
            articlesHtml += `
            <tr>
                <td>${id++}</td>
                <td><img style="width:200px"src="assets/images/${article.InitialPicture}" /></td>
                <td>${article.ArticleName}</td>
                <td>${(article.Text).substr(0,50)}</td>
                <td>${datePrint}</td>
                <td>${article.Username}</td>
                <td><a href="index.php?page=single-article&id=${article.ArticleId}">Details</a></td>
            </tr>
            `;
        }
        articlesHtml += "</table>";
    }
    $("#listOfAllArticles").html(articlesHtml);
}
