var WS_GOOGLE_BOOKS = "https://www.googleapis.com/books/v1/volumes?key=AIzaSyCqXyRFYjzkJIsQpTCpz7hSnjqJ0XZ49eQ"; // &q=algorithme
// WS DE SECOURS
var WS_GOOGLE_BOOKS_STATIC = "http://chabloz.eu/m44/books/"

var WS_YOUTUBE = "https://www.googleapis.com/youtube/v3/search?part=snippet&type=video&videoEmbeddable=true&key=AIzaSyCqXyRFYjzkJIsQpTCpz7hSnjqJ0XZ49eQ"; // &q=algorithme
// WS DE SECOURS
var WS_YOUTUBE_STATIC = "http://chabloz.eu/m44/videos/"; // &q=algorithme

// Variables globales
var templateBook;
var templateVideo;

$(document).ready(function()
{
    // Copie des templates
    templateBook = $(".book").clone();
    templateVideo = $(".video").clone();
    
    // Suppression des templates
    $(".book").remove();
    $(".video").remove();

    // Au début l'onglet Vidéos est caché
    $(".page_videos").hide();

    $("ul li").click(function()
    {
        $("ul li").removeClass("on");
        $("ul li").addClass("off");

        var tab_text = $(this).text();
        var tab_class = $(this).attr("class");
        
        if(tab_class === "off")
        {
            $(this).toggleClass("on off");
        }       

        if(tab_text === "Livres")
        {
            $(".page_videos").hide();
            $(".page_books").show();
        }
        else if (tab_text === "Vidéos")
        {
            $(".page_books").hide();
            $(".page_videos").show();
        }        
	});

    $("#search").click(function()
    {
        getBooks($("#query").val());
        getVideos($("#query").val());
    });

    $("#query").pressEnter(function()
    {
        getBooks($(this).val());
        getVideos($(this).val());
    });

    $("body").on("click", "button.remove", function() 
    {
        $("#" + $(this).attr("data-id")).hide();
    });
});

function getBooks(q) 
{
    $.getJSON(WS_GOOGLE_BOOKS + "&q=" + q, function(books)
    {
        $(".results_books").empty();

        $.each(books.items, function(i, book)
        {
            var tmpl = templateBook.clone();

            tmpl.attr("id", book.id);

            $(".title", tmpl).text(book.volumeInfo.title);
            $(".authors", tmpl).text(book.volumeInfo.authors.join(", "));
            $(".description", tmpl).text(book.volumeInfo.description);
            $("button:first-of-type", tmpl).attr("data-id", book.id);
            
            tmpl.appendTo(".results_books");
        });
    });
}

function getVideos(q) 
{
    $.getJSON(WS_YOUTUBE + "&q=" + q, function(videos)
    {
        $(".results_videos").empty();

        $.each(videos.items, function(i, video)
        {
            var tmpl = templateVideo.clone();
            
            tmpl.attr('id', video.id.videoId);

            $(".title", tmpl).text(video.snippet.title);
            $("iframe:first-of-type", tmpl).attr("src", "https://www.youtube.com/embed/" + video.id.videoId);
            $("button:first-of-type", tmpl).attr("data-id", video.id.videoId);

            tmpl.appendTo(".results_videos");
        });
    });
}

$.fn.pressEnter = function(fn)
{  
    return this.each(function() 
    {  
        $(this).bind("enterPress", fn);
        $(this).keyup(function(e)
        {
            if(e.keyCode == 13)
            {
              $(this).trigger("enterPress");
            }
        })
    });  
 }; 