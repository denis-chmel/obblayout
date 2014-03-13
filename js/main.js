var changesHistory = [];
var sortables = ".sortable";

function historySave() {
    var currentHtml = $("#sandbox").html();
    if (changesHistory[changesHistory.length - 1] == currentHtml) {
        return;
    }
    changesHistory.push(currentHtml);
}

function historyEmpty() {
    return changesHistory.length <= 1;
}

function removeElement($draggable) {
    if ($draggable.length) {
        $("#settings").appendTo($("body"));
        $draggable.fadeOut(function(){
            $(this).parent().closest(".draggable").click();
            $(this).remove();
            onAfterChange();
        });
    }
}

function initDrag() {

    $(sortables).sortable({
        connectWith: sortables,
        helper: "clone",
//        placeholder: "task-sortable-placeholder",
        tolerance: "pointer",
        over: function(event, ui){
//            console.log(ui.placeholder);
//            console.log($(ui.helper).css("width"));
            $(ui.helper).width($(ui.placeholder).width());
            alignColumnsInRow();
        },
        cursorAt: { left: 10, top: 10 },
        distance: 0,
        delay: 100,

        start: function(e, ui) {
            /*
            $(".row").each(function(){
                $(this).children().css("min-height", $(this).height()).height("auto");
            });
            */
//            $(ui.item).show().addClass("draggable-ghost");
            $("#sandbox").addClass("while-dragging");
        },
        stop: function(e, ui) {
//            $(ui.item).removeClass("draggable-ghost");
            $("#sandbox").removeClass("while-dragging");
            if ($(ui.item).is(".draggable-block")) {
                $(ui.item).replaceWith($(ui.item).find(".block-code").html());
            }

            onAfterChange();
        }
    });

    $( ".draggable-block" ).draggable({
        connectToSortable: sortables,
        appendTo: "#sandbox .container",
        tolerance: "pointer",
        helper: "clone",
//        stack: ".block-code",
        create: function(e, ui){
//            console.log(ui);
        },
        start: function(e, ui){
//            $(ui.helper).find(".block-label").remove();
//            console.log(ui);
            $("#sandbox").addClass("while-dragging");
        },
        stop: function(e, ui){
            console.log(ui);
//            $(ui.helper).html($(ui.helper).find(".block-code").children());
            $("#sandbox").removeClass("while-dragging");
            onAfterChange();
        }
    });
}

function historyGoBack() {
    historyGo(-1);
}

function historyGo(direction) {
    if (direction == -1) {
        if (historyEmpty()) { // already initial HTML
            return;
        }
        changesHistory.pop();
        var previousHtml = changesHistory[changesHistory.length - 1];
        $("#sandbox").html(previousHtml);
        onAfterChange(true);
    }
}

function alignColumnsInRow(where) {
    $(".row", where).each(function(){
        var maxHeight = 0;
        $(this).children(".sortable").height("auto").each(function(){
            maxHeight = Math.max(maxHeight, $(this).height());
        }).height(maxHeight);

    });

}

function onAfterChange(skipHistory) {
    $(".sortable > *").addClass("draggable");
    setTimeout(function(){
        alignColumnsInRow();
    }, 1);

    if (!skipHistory) {
        historySave();
    }
    $("#btn-undo").toggleClass("disabled", historyEmpty());
    initDrag();
    $("main, footer, header").each(function(){
        if (!$(this).children().length) {
            $(this).empty();
        }
    });
}

$(function() {

    onAfterChange();

    $("#btn-undo").click(historyGoBack);

    $(document)
        .on("click", function(e) {
            var closestDraggable = $(e.target).closest(".draggable");
            $(".selected").not(closestDraggable).removeClass("selected");
            if (closestDraggable.length) {
                closestDraggable.addClass("selected");
                var firstClass = closestDraggable.attr("class").split(' ')[0];
                var modalId = "#" + firstClass + "-modal";
                if ($(modalId).length) {
                    $("#settings .settings").show().attr("data-target", modalId);
                } else {
                    $("#settings .settings").hide();
                }
                closestDraggable.append($("#settings"));


            }
        })
        .on("keyup", function(e){
            var hotkey = String.fromCharCode(e.keyCode).toLowerCase();
            if (e.ctrlKey) {
                hotkey = 'Ctrl+' + hotkey;
            }

            switch (hotkey) {
                case 'Ctrl+z':
                    historyGoBack();
                    break;
            }
            switch (e.keyCode) {
                case 46: // delete
                    removeElement($(".draggable.selected"));
                    break;
            }

        }).on("mouseover", ".draggable", function(e){
            if ($(".task-sortable-placeholder:visible").length) {
                return;
            }
            $(this).addClass("draggable-hovered");
            e.stopPropagation();
        }).on("mouseout", ".draggable", function(){
            $(this).removeClass("draggable-hovered");
        });

    $("#settings .delete").on("click", function(){
        removeElement($(this).closest(".draggable"));
    });

    $("#btn-toggle-grid").click(function(){
        $("#sandbox").toggleClass("show-grid");
        $(this).html($("#sandbox").hasClass("show-grid") ? "Hide grid" : "Show grid");
    });

});
