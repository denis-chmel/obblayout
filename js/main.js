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
        $draggable.fadeOut(200, function(){
            $(this).parent().closest(".draggable").click();
            $(this).remove();
            onAfterChange();
        });
    }
}

/**
 * Use to delay reaction on e.g. keyup during search. Or whatever
 */
var delay = (function() {
    var timers = {
        default: 0
    };
    return function(callback, ms, context) {
        context = context || 'default';
        clearTimeout(timers[context]);
        timers[context] = setTimeout(callback, ms);
    };
})();

function initDrag() {

    $(sortables).sortable({
        connectWith: sortables,
        helper: "clone",
        revert: 100,
        tolerance: "pointer",
        over: function(event, ui){

            delay(function(){
                $(ui.helper).height("auto");
                $(ui.helper).width($(ui.placeholder).width());
                setTimeout(function(){
                    $(ui.placeholder).height($(ui.helper).height());
                    alignColumnsInRow();
                }, 150); // to exec after "transition width/height 100ms"
            }, 200); // to not resize too often (sometimes overdoing this when moving)

        },
        cursorAt: { left: 10, top: 10 },
        distance: 0,
        delay: 100,

        start: function(e, ui) {
            $("#sandbox").addClass("while-dragging");
        },
        stop: function(e, ui) {

            $("#sandbox").removeClass("while-dragging");
            if ($(ui.item).is(".draggable-block")) {
                $(ui.item).find(".block-code > *").addClass("draggable").click();
                var htmlCode = $(ui.item).find(".block-code").html();
                $(ui.item).replaceWith(htmlCode);
            } else {
                $(ui.item).click();
            }

            onAfterChange();
        }
    });

    $( ".draggable-block" ).draggable({
        connectToSortable: sortables,
        appendTo: "#sandbox .container",
        tolerance: "pointer",
        helper: "clone",
        drag: function(event, ui){
            delay(alignColumnsInRow, 50);
        },
        start: function(e, ui){
            $("#sandbox").addClass("while-dragging");
        },
        stop: function(e, ui){
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

function addControls(block) {
    var $controls = $(block).children(".block-controls");
    if (!$controls.length) {
        $(block).append($("#settings-teplate").html());
        $controls = $(block).find(".block-controls");
        var firstClass = $(block).attr("class").split(' ')[0];
        var modalId = "#" + firstClass + "-modal";
        var $settingsIcon = $controls.find(".settings");
        if ($(modalId).length) {
            $settingsIcon.show().attr("data-target", modalId);
        } else {
            $settingsIcon.hide();
        }
    }
}

$(function() {

    onAfterChange();

    $("#btn-undo").click(historyGoBack);

    $(document)
        .on("click", function(e) {
            if ($(e.target).closest(".modal").length) {
                return;
            }
            var closestDraggable = $(e.target).closest(".draggable");
            $(".selected").not(closestDraggable).removeClass("selected");
            if (closestDraggable.length) {
                closestDraggable.addClass("selected");
            }
        })
        .on("click", ".block-controls .delete", function(){
            removeElement($(this).closest(".draggable"));
        })
        .on("click", ".block-controls .settings", function(){
//            console.log($(this).closest(".draggable"));
//            removeElement($(this).closest(".draggable"));
            var popupId = $(this).attr("data-target");
            var $popup = $(popupId);
            var closestDraggable = $(this).closest(".draggable");

            var $copy = closestDraggable.clone();
            $copy.find(".block-controls").remove();

            tinyMCE.activeEditor.setContent($copy.html());

            $popup.modal("show");
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
            addControls(this);

            e.stopPropagation();
        }).on("mouseout", ".draggable", function(){
            $(this).removeClass("draggable-hovered");
        });

    $("#btn-toggle-grid").click(function(){
        $("#sandbox").toggleClass("show-grid");
        $(this).html($("#sandbox").hasClass("show-grid") ? "Hide grid" : "Show grid");
    });

    $("#text-modal .btn-save").click(function(){
        var $block = $(".selected");
        setTimeout(function(){
            $block.html(tinyMCE.activeEditor.getContent());
            $block.effect("highlight");
            addControls($block);
        }, 500);
    });

    tinymce.init({
        selector: "textarea",
        height: 300,
        content_css : "/vendor/bootstrap-3.1.1-dist/css/bootstrap.css",
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
    });

});
