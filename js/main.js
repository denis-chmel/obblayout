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

function initDrag() {

    $(sortables).sortable({
        connectWith: sortables,
        helper: "clone",
//        placeholder: "task-sortable-placeholder",
        tolerance: "pointer",
        over: function(event, ui){
//            console.log(ui.placeholder);
            $(ui.helper).width($(this).width());
            console.log($(ui.helper).width());
            alignColumnsInRow();
        },
//        cursorAt: { left: 10, top: 10 },
        delay: 100,
        start: function(e, ui) {
            /*
            $(".row").each(function(){
                $(this).children().css("min-height", $(this).height()).height("auto");
            });
            */
//            $(ui.item).show().addClass("draggable-ghost");
        },
        stop: function(e, ui) {
//            $(ui.item).removeClass("draggable-ghost");

            onAfterChange();
        }
    });

    $( "#draggable-blocks > *" ).draggable({
        connectToSortable: sortables,
//        appendTo: "#sandbox .container",
        tolerance: "pointer",
        helper: "clone",
        stop: function(){
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
        $(this).children().height("auto").each(function(){
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
                    var $selected = $(".draggable.selected");
                    if ($selected.length) {
                        $selected.remove();
                        onAfterChange();
                    }
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

});
