$(document).ready(function(){
    getArticles();
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
                    console.log(data);
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
});
function getArticles(){
    $.ajax({
        url: "models/articles/get-all.php",
        method: "POST",
        success:function (data) {
            printArticles(data);
            console.log(data);
        },
        error: function (xhr) {
            console.log(xhr);
        }

    });
}
function printArticles(data){
    let articlesHtml="";
    if(data.length == 0){
        articlesHtml += "No items for the selected user.";
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
                <td>${id}</td>
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
