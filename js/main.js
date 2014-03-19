var changesHistory = [];
var sortables = ".sortable";
var enabledLocalizations = ['en', 'da', 'de'];

function getBlueLineDelay() {
    return $("#blue-line-delay").val();
}

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
        $draggable.fadeOut(200, function() {
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

function samePositionWithOldPlaceholder(currentPlaceholder) {
    if ($(currentPlaceholder).next().is(".old-placeholder")) {
        return true;
    }

    if ($(currentPlaceholder).prev().is(".old-placeholder")) {
        return true;
    }

    return false;
}

function round40(value) {
    return Math.round(value / 40) * 40;
}

function getCellsProportions(gridSetupBlock) {
    var dividers = [];
    var proportions = [];
    $(gridSetupBlock).find(".grid-setup-line").each(function() {
        var left = Math.round(parseInt($(this).css("left")) / 40);
        if (left && -1 == dividers.indexOf(left)) {
            dividers.push(left);
        }
    });
    dividers.sort(function(a, b) {
        return a - b;
    });
    var slotsUsed = 0;
    $.each(dividers, function(i, leftPosition) {
        proportions.push(leftPosition - slotsUsed);
        slotsUsed += leftPosition - slotsUsed;
    })
    return proportions;
}

function updatePlaceholders(ui) {

    var lineDelay = getBlueLineDelay();

    if (samePositionWithOldPlaceholder(ui.placeholder)) {

        $(ui.placeholder).removeClass("placeholder-line");
        $(".old-placeholder").hide();

    } else {

        $(ui.placeholder).addClass("placeholder-line");
        $(".old-placeholder").show();

        if ($(ui.placeholder).css("display") !== 'inline-block') {
            $(ui.placeholder).width("auto");
        }

        delay(function() {

            if (samePositionWithOldPlaceholder(ui.placeholder)) {
                return;
            }

            $(ui.helper).height("auto");
            $(ui.helper).width(0).width($(ui.placeholder).width());
            $(ui.placeholder).height($(ui.helper).css("height"));

            $(ui.placeholder).removeClass("placeholder-line").slideDown(300);

            var $oldOldPlaceholder = $(".old-placeholder");
            $oldOldPlaceholder.height(0);
            setTimeout(function() {
                $oldOldPlaceholder.remove();
                alignColumnsInRow();
            }, 200);

            var $newOldPlaceholder = $(ui.placeholder).clone();
            $newOldPlaceholder.addClass("old-placeholder").insertAfter($(ui.placeholder)).hide();
            $(ui.placeholder).data("previous-index", $(ui.placeholder).index());


        }, lineDelay, "drag-placeholder-show")

    }
}

function initDrag(where) {

    var $grids = $(where || ".grid-setup");
    $grids.each(function() {
        var leftestCount = 0;
        var rightestCount = 0;
        $(this).find(".grid-setup-line").each(function() {
            var left = parseInt($(this).css("left"));
            leftestCount += 0 == left;
            rightestCount += 480 == left;
        });

        if (leftestCount < 2) {
            $(this).append("<div class='grid-setup-line' style='left: 0'></div>")
        }
        if (rightestCount < 2) {
            $(this).append("<div class='grid-setup-line' style='left: 480px'></div>")
        }

        var cells = getCellsProportions(this);

        $(this).closest(".modal-body").find(".btn-default").removeClass("active");
        $(this).closest(".modal-body").find(".btn-default:nth(" + (cells.length - 1) + ")").addClass("active").focus();
    });

    $(".grid-setup-line", where).draggable({
        grid: [40],
        stop: function(e, ui) {
            var dividers = [];
            var $grid = $(this).closest(".grid-setup");
            $grid.find(".grid-setup-line").each(function() {
                var left = parseInt($(this).css("left"));
                if (left && left < 480) {
                    if (-1 == dividers.indexOf(left)) {
                        dividers.push(left);
                    } else {
                        $(this).remove(); // line is duplicate
                    }
                }
            });
            initDrag($grid); // to make new lines draggable
        },
        containment: "parent"
    });

    $(sortables, where).sortable({
        connectWith: sortables,
        helper: "clone",
        revert: 100,
        tolerance: "pointer",
        cursorAt: { left: 10, top: 10 },
        distance: 0,
        delay: 100,
        change: function(event, ui) {

            updatePlaceholders(ui);

        },
        start: function(e, ui) {

            if ($(ui.helper).data("isNewItem")) {
                updatePlaceholders(ui);
                if (getBlueLineDelay() > 0) {
                    $(ui.placeholder).addClass("ui-sortable-placeholder-animated");
                }
            } else {
                if (getBlueLineDelay() > 0) {
                    $(ui.placeholder).addClass("ui-sortable-placeholder-animated");
                }
                var oldPlaceholder = $(ui.placeholder).clone();
                oldPlaceholder.addClass("old-placeholder").insertAfter($(ui.placeholder)).hide();
            }

            $("#sandbox").addClass("while-dragging");

        },
        stop: function(e, ui) {
            $(".old-placeholder").remove();

            $("#sandbox").removeClass("while-dragging");

            onAfterChange(); // TODO wait till transition ends?

        }
    });

    $(".draggable-block .block-code > *", where).draggable({
        connectToSortable: sortables,
        appendTo: "body",
        tolerance: "pointer",
        helper: "clone",
        start: function(e, ui) {
            $(ui.helper).data("isNewItem", true); // flag for sortable to disinct this case
            $("#sandbox").addClass("while-dragging");
        },
        stop: function(e, ui) {
            $("#sandbox").removeClass("while-dragging");
            setTimeout(onAfterChange, 300); // wait till transition ends
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
    $(".row", where).each(function() {
        var maxHeight = 0;
        $(this).children(".sortable").height("auto").each(function() {
            maxHeight = Math.max(maxHeight, $(this).height());
        }).height(maxHeight);

    });

}

function onAfterChange(skipHistory) {
    $(".sortable > *").addClass("draggable");
    setTimeout(function() {
        alignColumnsInRow();
    }, 1);

    if (!skipHistory) {
        historySave();
    }
    $("#btn-undo").toggleClass("disabled", historyEmpty());
    initDrag();
    $("main, footer, header").each(function() {
        if (!$(this).children().length) {
            $(this).empty();
        }
    });
}

function addControls(block) {
    var $controls = $(block).find("> .block-controls");
    if (!$controls.length) {
        $(block).prepend($("#settings-template").html());
        $controls = $(block).find("> .block-controls");
        var firstClass = $(block).attr("class").split(' ')[0];
        var modalId = "#" + firstClass + "-modal";
        var $settingsIcon = $controls.find(".settings");
        var blockType = firstClass == "draggable" ? $(block).prop("tagName").toLowerCase() : firstClass;
        var blockLabel = $("#draggable-blocks ." + blockType + ":first").closest(".draggable-block").find(".block-label").text().toLowerCase();
        if (blockType == "grid-row") {
            blockLabel = "grid";
        }

        $controls.find(".block-name").text(blockLabel);
        if ($(modalId).length) {
            $settingsIcon.show().attr("data-target", modalId);
        } else {
            $settingsIcon.hide();
        }
    }
}

function initGridSettings(cellProportions) {
    var $grid = $("#grid-row-modal .grid-setup");
    $grid.empty();
    var offset = 0;
    for (var i in cellProportions) {
        offset += round40(cellProportions[i] * 40);
        $grid.append("<div class='grid-setup-line' style='left: " + offset + "px'></div>");
    }
    initDrag($grid);
}

function showSettingsPopup(popupId, $block) {
    var $popup = $(popupId);
    switch (popupId) {
        case '#custom-text-html-modal':
            var $copy = $block.clone();
            $copy.find(".block-controls").remove();
            $copy.find("script.params").remove();

            tinyMCE.get('content-en').setContent($copy.html());

            var $params = $block.find("> script.params");
            if ($params.length) {
                var json = JSON.parse($block.find("> script.params").text());

                if (json.translations) {
                    $.each(json.translations, function(code, value){
                        tinyMCE.get('content-' + code).setContent(value);
                    });
                }
            }

            break;
        case '#grid-row-modal':
            var cellProportions = [];
            $block.children("[class*='col-']").each(function() {
                var size = parseInt(this.className.replace(/\D+/g, '')); // leave only numbers
                cellProportions.push(size);
            });
            initGridSettings(cellProportions);
            break;
    }

    $popup.modal("show");
}

$(function() {

    onAfterChange();

    $("#btn-undo").click(historyGoBack);

    $(".btn-group-language-switcher .btn").click(function() {

        $(this).addClass('active').siblings().removeClass('active');

        var language = $(this).attr("data-lang-code");
        $(this).closest(".modal").find(".lang-dependent").addClass("hidden");
        $(this).closest(".modal").find(".lang-" + language).removeClass("hidden");

    });

    $(".btn-group-col-counts .btn").click(function() {

        $(this).addClass('active').siblings().removeClass('active');

        var colsCount = $(this).index() + 1;
        var $grid = $(this).closest(".modal-body").find(".grid-setup");
        $grid.empty();
        var distance = 12 / colsCount;
        while (colsCount--) {
            $grid.append("<div class='grid-setup-line' style='left: " + round40(colsCount * distance * 40) + "px'></div>");
        }
        initDrag($grid);
    });

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
        .on("dblclick", ".draggable", function(e) {
            $(this).find(".block-controls:first .settings").click();
            e.stopPropagation();
        })
        .on("click", ".block-controls .delete", function() {
            removeElement($(this).closest(".draggable"));
        })
        .on("click", ".block-controls .settings", function() {
            var popupId = $(this).attr("data-target");

            if (popupId) {
                showSettingsPopup(popupId, $(this).closest(".draggable"));
            }

        })
        .on("keyup",function(e) {
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

        }).on("mouseover", ".draggable",function(e) {
            if ($("#sandbox").hasClass("while-dragging")) {
                return;
            }
            if ($(".task-sortable-placeholder:visible").length) {
                return;
            }
            $(this).addClass("draggable-hovered");
            addControls(this);

            e.stopPropagation();
        }).on("mouseout", ".draggable",function() {
            $(this).removeClass("draggable-hovered");
        }).on("mouseover", ".grid-setup-line", function() {
            $(this).addClass("shake");
        });

    $("#btn-toggle-grid").click(function() {
        $("#sandbox").toggleClass("show-grid");
        $(this).html($("#sandbox").hasClass("show-grid") ? "Hide grid" : "Show grid");
    });

    $("#custom-text-html-modal .btn-save").click(function() {
        var $block = $(".selected");
        var previewHtml = tinyMCE.get('content-en').getContent();
        setTimeout(function() {
            $block.html(previewHtml);
            $block.effect("highlight");

            var json = {
                translations: {}
            };
            $.each(enabledLocalizations, function(i, code){
                json.translations[code] = tinyMCE.get('content-' + code).getContent();
            });

            $block.prepend("<script class='params' type='application/json'>" + JSON.stringify(json) + "</script>");

            addControls($block);

            onAfterChange();

        }, 500);

    });

    $("#grid-row-modal .btn-save").click(function() {
        var $block = $(".selected");
        var currentCols = $block.find("> *:not(.block-controls)");
        var proportions = getCellsProportions(
            $(this).closest(".modal").find(".grid-setup")
        );
        for (var i=currentCols.length - 1; i >= proportions.length; i--) {
            $(currentCols[i]).children().insertAfter($block);
            $(currentCols[i]).remove();
        }
        $.each(proportions, function(i, proportion) {
            var className = "sortable col-md-" + proportion;
            var col = currentCols.get(i);
            if (col) {
                col.className = className;
            } else {
                $block.append("<div class='" + className + "'></div>");
            }
        });
        onAfterChange();
    });

    $(".btn-group-device .btn").click(function(){

        $(this).addClass('active').siblings().removeClass('active');
        $("#sandbox").attr("class", $(this).attr("data-device"));

        setTimeout(function(){
            alignColumnsInRow();
        }, 200);

    });

    tinymce.init({
        selector: "textarea",
        height: 300,
        content_css: "/vendor/bootstrap-3.1.1-dist/css/bootstrap.css",
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
    });

});
