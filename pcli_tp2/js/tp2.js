$(function () {

    $(".questionnaire dd").toggle();


    $(".questionnaire dt").click(function () {
        $(this).next().toggle();
    });

    $(".questionnaire dt").each(function (index, value) { 
        $(value).text("Q"+(index+1)+ " : "+$(value).text());
    });

    /*
    $("dl:last-of-type dd").hover(function(e){
        $(e.target).css({"color":"red"});
    },function(e){
       $(e.target).css({"color":"black"}); 
    });
    */

    //$("a[href^=\"http\"]").css({"color":"orange"});

    $("#ajouter").click(function(){
        $("#notes").append($("<p>").text($("#newNote").val()));
        $("#newNote").val("");
    });
});