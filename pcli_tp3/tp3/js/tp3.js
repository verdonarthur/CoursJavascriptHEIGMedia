// http://services.heig-vd.ch/ComemSchedule/ScheduleService.ashx/GetSchedule

var TMPL_COURS;

function displayCourse(course) {
    $("tbody").text("");

    urlToLoad = 'http://127.0.0.1:8080/proxyHEIGWebService.php';

    $.get(urlToLoad, {CourseId: course}, function (xml) {

        $("ScheduleEntity", xml).each(function (i, scheduleEntity) {

            var courseDom = TMPL_COURS.clone();

            var date = new Date($('Date', scheduleEntity).text()),
                period = $('Period', scheduleEntity).text(),
                room = $('Room', scheduleEntity).text();

            $(courseDom).find(".date").text(date.toDateString());
            $(courseDom).find(".period").text(period);
            $(courseDom).find(".room").text(room);

            if (i % 2)
                $(courseDom).addClass('color1');

            $("tbody").append(courseDom);
        });
        $("#horaire").trigger("update");
    });
}


$(function () {
    // make template
    TMPL_COURS = $(".templateCours").clone().removeClass("template");


    $(".btnHoraire").click(function () {
        displayCourse($(this).attr("id"));
    });

    $("#horaire").tablesorter();


});
