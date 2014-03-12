var changesHistory = [];
var sortables = "main, header, footer";
sortables = "main, header, footer";

function updateHints() {
    $.each(["main", "header", "footer"], function(i, name) {
        if ($(name + " .hint").length == 0) {
            $(name).append("<div class=\"hint\">" + name + "</div>");
        }
        $(name + " .hint").toggle($(name + " > *").length == 1);
    });
}

function historySave() {
    changesHistory.push($("#sandbox").html());
}

function historyEmpty() {
    return changesHistory.length <= 1;
}

function initDrag() {
    $(sortables).sortable({
        connectWith: sortables,
        out: updateHints,
        over: updateHints,
        stop: function() {
            onAfterChange();
        }
    });

    $( "#draggable-blocks > *" ).draggable({
        connectToSortable: sortables,
        appendTo: "body",
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

function onAfterChange(skipHistory) {
    updateHints();
    if (!skipHistory) {
        historySave();
    }
    $("#btn-undo").toggleClass("disabled", historyEmpty());
    initDrag();
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

        });

});
