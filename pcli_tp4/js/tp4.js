var GEO_NAMES = "http://www.geonames.org/postalCodeLookupJSON";
// GEONAMES DE SECOURS: http://chabloz.eu/cp/postalCodeLookupJSON.php
// Web Service de secours valide uniquement avec les codes postaux suivants:
// 1000, 12000, 1401, 1700, 2000
var GOOGLE_MAP = "//maps.googleapis.com/maps/api/staticmap?";
var GOOGLE_MAP_PARA = "zoom=14&size=500x400&maptype=hybrid&sensor=false&key=ABQIAAAA1nu4VMtb7TfHd-Dxiy9HmxRi_j0U6kJrkFvY4-OX2XYmEAa76BS_R3kzv5sXG5MMtQXVf5ySWN6_FQ&center=";

var TMPL_LOCALITE;

$(function () {
    TMPL_LOCALITE = $(".templateLocalite").clone().removeClass("template");

    $("#code").on("keyup", function () {
        if ($("#code").val().length >= 4)
            $("#localite").trigger("codechange");
    });


    $("#localite").on("codechange", function () {
        $.getJSON(GEO_NAMES, {postalcode: $("#code").val()}, function (data) {
            $("#localite").empty();

            $("#localite").append(TMPL_LOCALITE);
            $(data.postalcodes).each(function (i, postalcode) {

                $slPostCode = TMPL_LOCALITE.clone();
                $slPostCode.val(postalcode.postalcode + " " + postalcode.placeName);
                $slPostCode.text(postalcode.postalcode + " " + postalcode.placeName);

                $("#localite").append($slPostCode);
            });
        });
    });

    $("#localite").on("change", function () {
        var paramLocality = $(this).val();

        $("#map img").attr("src", GOOGLE_MAP + GOOGLE_MAP_PARA + paramLocality);
    });

});