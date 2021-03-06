/* globals OverrideFormTab, OverrideMarker */

$(document).ready(function () {
    var local_OverrideMarker = OverrideMarker;

    // Delete NoJS Warning
    $(".JSWarning").hide(0);

    // Handle DarkEnergy FormTabs
    $("[name=\"option\"]").val(OverrideFormTab);
    $("[class^=\"deForm\"]").hide(0);
    $(".deTab")
        .hover(function () {
            $(this).addClass("deTabHover");
        }, function () {
            $(this).removeClass("deTabHover");
        })
        .click(function () {
            $(".deTab").each(function () {
                $(this).removeClass("deTabSelect");
            });
            $("[class^=deForm]").hide(0);
            $("[name=\"option\"]").val($(this).attr("id").replace("deTab", ""));
            var ThisTab = $(this).attr("id").replace("deTab", "deForm");
            $("." + ThisTab).show(0);
            $(this).removeClass("deTabHover").addClass("deTabSelect");
        });

    $("#deTab" + OverrideFormTab).click();

    // Handle Tabs
    var InHashSelected = $("[id*=\"" + document.location.hash.replace("#", "") + "\"]");
    if (InHashSelected.length > 0) {
        var InHashSelectedID = InHashSelected.attr("id").split("_");
        local_OverrideMarker = InHashSelectedID[0].replace("Mark", "");
    }

    $("[class^=Cont]").hide(0);
    $(".mark")
        .hover(function () {
            $(this).addClass("markHover");
        }, function () {
            $(this).removeClass("markHover");
        })
        .click(function () {
            var ThisID = $(this).attr("id").split("_");
            $(".mark").each(function () {
                $(this).removeClass("markSelect");
            });
            $("[class^=Cont]").hide(0);
            var ThisMark = ThisID[0].replace("Mark", "Cont");
            $("." + ThisMark).show(0);
            $(this).removeClass("markHover").addClass("markSelect");
            document.location.hash = ThisID[1];
        });

    $("[id^=\"Mark" + local_OverrideMarker + "\"]").click();

    $(".freeItemUse").click(function () {
        document.location = "?use_freeid=" + $(this).attr("id");
    });
});
