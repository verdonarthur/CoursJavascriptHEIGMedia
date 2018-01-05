console.log('1: hello world !');

window.onload = function(){
    console.log("i m inside onload");
    var h1 = document.getElementById("title");
    h1.innerHTML = "Hello world 2";
    let para = $("<p>").text("test");
    $("body").prepend(para);
};

