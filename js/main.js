var changesHistory = [];
var sortables = "#sandbox .grid-row > *, #sandbox header, #sandbox footer, #sandbox main, #sandbox .tab-pane";
var enabledLocalizations = ['en', 'da', 'de'];

function getBlueLineDelay() {
    return 600;
}

function historySave() {
    var currentHtml = $("#sandbox .active-layout").html();
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

function focusCurrrentLayoutEditor() {
    //var currentPageId = $(this).attr("data-obb-page");
    var currentLayout = $(".pages-switcher").val();

    var $currentLayout = $(".obb-page-" + currentLayout);

    var layoutSelector = $(".layout");
    var layoutsCount = $(layoutSelector).length;
    $(layoutSelector).each(function(i, item) {
        i -= $currentLayout.index();
        var newCss = {
            opacity: 0.4,
            marginLeft: (i * 140) + "%",
            marginRight: (i - layoutsCount) * 140 + "%"
        };
        $(item).css(newCss);
    });
    $currentLayout.css("opacity", 1);
    $currentLayout.addClass("active-layout").siblings().removeClass("active-layout");

    if (currentLayout !== 'front') {
        $currentLayout.find("header").html($(".obb-page-front header").html()); // FIXME
        $currentLayout.find("footer").html($(".obb-page-front footer").html()); // FIXME
    }
    updateHeights();
}

function saveLayout(id, title, $dom) {

    alert("Save doesn't work yet");
    return;

    var $currentPage = $(".active-obb-page");
    var currentPageType = $currentPage.attr("data-type");

    $dom = $dom && $dom.length ? $dom : $currentPage.find(".layout-" + id).clone();
    $dom.find("*").removeClass("ui-sortable draggable").removeAttr("style");
    $dom.find(".block-controls").remove();
    $dom.find(".sortable").removeClass("sortable").removeAttr("style"); // with hardcoded height

    $.post(
        "/save.php",
        {
            id: id,
            pageType: currentPageType,
            title: title,
            html: $dom.html()
        }
    ).done(function(response) {

            var data = JSON.parse(response);
            var $select = $(".pages-switcher:visible");
            var $option = $select.find("option[value=" + data.id + "]");
            if (!$option.length) {
                $("<option value='" + data.id + "'>" + data.title + "</option>").insertBefore($select.find("option[value='new']"));
                $option = $select.find("option[value=" + data.id + "]");
                $("<div class='layout layout-" + data.id + "'>" + data.html + "</div>").insertBefore(".active-obb-page .layout-new");
            }
            $option.attr("selected", true);
            onAfterChange();
            initDrag();
            focusCurrrentLayoutEditor();

            $.growl.notice({ title: "", message: "Saved successfully" });

        }).fail(function() {
            alert("failure");
        });
    $("#save-as-modal").modal("hide");
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

            $(ui.helper).height("");
            $(ui.helper).width(0).width($(ui.placeholder).width());
            $(ui.placeholder).height($(ui.helper).css("height"));

            $(ui.placeholder).removeClass("placeholder-line").slideDown(300);

            var $oldOldPlaceholder = $(".old-placeholder");
            $oldOldPlaceholder.height(0);
            setTimeout(function() {
                $oldOldPlaceholder.remove();
                updateHeights();
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

    /* TODO: do this elsewhere
     $("header, footer", where).each(function() {
     if ($(this).closest(".obb-page-front").length == 0) {
     $(this).find("*").andSelf().removeClass("sortable");
     }
     });
     */
    $(".block-controls").removeClass("sortable");

    $(".sortable", where).sortable({
        connectWith: ".sortable",
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
                ui.item.isNewItem = true;
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

            if (ui.item.isNewItem) {
                if ($(ui.item).is(".customize")) {
                    setTimeout(function(){
                        $(ui.item).click();
                        showSettingsPopup(null, $(ui.item));

                    }, 0); // 0ms is enough for item to be inserted into DOM
                }

                if ($(ui.item).is(".carousel")) {
                    var uniqId = "carousel-" + (Math.random() + '').replace('0.', '');
                    $(ui.item).attr("id", uniqId);
                    $(ui.item).find("[data-target]").attr("data-target", "#" + uniqId);
                }
            }

            onAfterChange();
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
        $("#sandbox .active-layout").html(previousHtml);
        onAfterChange(true);
    }
}

function updateHeights(where) {
    $("#sandbox .row", where).each(function() {
        var maxHeight = 0;
        $(this).children().not(".block-controls").height("auto").each(function() {
            maxHeight = Math.max(maxHeight, $(this).height());
        }).height(maxHeight);

    });
}

function onAfterChange(skipHistory) {
    $(sortables).not(".block-controls").addClass("sortable").children().addClass("draggable");

    setTimeout(function() {
        updateHeights();
    }, 1);

    if (!skipHistory) {
        historySave();
    }
    $(".btn-undo").toggleClass("disabled", historyEmpty());
    $(".btn-save-layout").toggleClass("disabled", historyEmpty()).text(historyEmpty() ? "Saved" : "Save");

    initDrag();
    $("main, footer, header, .sortable").each(function() {
        if (!$(this).children().length && $.trim($(this).text()).length == 0) {
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
    if (!popupId) {
        var firstClass = $block.attr("class").split(' ')[0];
        popupId = "#" + firstClass + "-modal";
    }
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
                    $.each(json.translations, function(code, value) {
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

    var builder = $("#sandbox").layoutBuilder();

    onAfterChange();

    $(".btn-undo").click(historyGoBack);

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

    $("#custom-text-html-modal .btn-save").click(function() {
        var $block = $(".selected");
        var previewHtml = tinyMCE.get('content-en').getContent();
        setTimeout(function() {
            $block.html(previewHtml);
            $block.effect("highlight");

            var json = {
                translations: {}
            };
            $.each(enabledLocalizations, function(i, code) {
                json.translations[code] = tinyMCE.get('content-' + code).getContent();
            });

            $block.prepend("<script class='params' type='application/json'>" + JSON.stringify(json) + "</script>");

            addControls($block);

            onAfterChange();

        }, 500);

    });

    $("#component-tabs-modal .btn-save").click(function() {
        var $modal = $(this).closest(".modal");
        var $block = $(".selected");
        var previewData = $modal.find('.lang-en textarea').html();

        var tabs = [];
        $.each(previewData.split("\n"), function(i, tabLabel) {
            tabLabel = $.trim(tabLabel);
            if (!tabLabel.length) {
                return;
            }
            tabs.push()
            console.log(tabLabel);
        });
//        alert(previewData);
        $modal.modal("hide");

        /*
         var previewHtml = tinyMCE.get('content-en').getContent();
         setTimeout(function() {
         $block.html(previewHtml);
         $block.effect("highlight");

         var json = {
         translations: {}
         };
         $.each(enabledLocalizations, function(i, code) {
         json.translations[code] = tinyMCE.get('content-' + code).getContent();
         });

         $block.prepend("<script class='params' type='application/json'>" + JSON.stringify(json) + "</script>");

         addControls($block);

         onAfterChange();

         }, 500);
         */

    });

    $("#grid-row-modal .btn-save").click(function() {
        var $block = $(".selected");
        var currentCols = $block.find("> *:not(.block-controls)");
        var proportions = getCellsProportions(
            $(this).closest(".modal").find(".grid-setup")
        );
        for (var i = currentCols.length - 1; i >= proportions.length; i--) {
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

    $(".btn-group-device .btn").click(function() {

        $(this).addClass('active').siblings().removeClass('active');
        $("#sandbox").attr("class", $(this).attr("data-device"));

        setTimeout(function() {
            updateHeights();
        }, 200);

    });

    tinymce.init({
        selector: "textarea.wysiwyg",
        height: 300,
        content_css: "/vendor/bootstrap-3.1.1-dist/css/bootstrap.css",
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
    });

    $(".pages-switcher").change(focusCurrrentLayoutEditor);

    $(".btn-saved-layouts-next").click(function() {
        var nextOption = $(".pages-switcher option:selected").next();
        if (nextOption.length) {
            $(nextOption).attr("selected", true);
            $(".pages-switcher").trigger("change");
        }
    });

    $(".btn-saved-layouts-prev").click(function() {
        var prevOption = $(".pages-switcher option:selected").prev();
        if (prevOption.length) {
            $(prevOption).attr("selected", true);
            $(".pages-switcher").trigger("change");
        }
    });

    $(".btn-save-layout-as").click(function() {
        $("#save-as-modal").modal("show");
    });

    $(".btn-save-layout").click(function() {
        var currentLayoutId = $(".pages-switcher:visible").val();
        if (currentLayoutId == "new") {
            $("#save-as-modal").modal("show");
            return;
        }
        saveLayout(
            currentLayoutId,
            $(".pages-switcher:visible option:selected").text()
        );
    });

    $("#save-as-modal").on("show.bs.modal", function() {
        $("#layout_name").val("").trigger("change");
    });

    $("#save-as-modal").on("shown.bs.modal", function() {
        $("#layout_name").focus();
    });

    $("#layout_name").on("change keyup", function() {
        $("#save-as-modal .btn-save").toggleClass("disabled", $.trim(this.value).length == 0);
    });

    /*
     $("#save-as-modal form").submit(function() {
     var name = $("#layout_name").val();
     var dom = $(".active-obb-page .layout-" + $(".pages-switcher:visible").val());
     saveLayout(null, name, dom);
     return false;
     });
     */

    $($("#section-preset-switcher-tpl").html()).insertBefore(
        $("header:visible, main:visible, footer:visible")
    );

    function getPresetsForBlock($block) {
        if ($block.is("header")) {
            return $("#header-layout-presets").find(".layout-preset");
        }
        if ($block.is("footer")) {
            return $("#footer-layout-presets").find(".layout-preset");
        }
        return $block.closest(".layout").find(".layout-preset");
    }

    $("#sandbox .section-preset-switcher").each(function() {
        var $block = $(this).next();

        var $layouts = getPresetsForBlock($block);
        $(this).attr("data-current-layout", 0); // FIXME unhardcode

        $(this).addClass("section-preset-switcher-for-" + $block.prop("tagName").toLowerCase());

        $(this).find(".preset-name var").html(($layouts.attr("data-title") || "new layout").toLowerCase());
    });

    $(document).on("click", ".preset-next", function() {
        var $switcher = $(this).closest(".section-preset-switcher");
        var $block = $switcher.next();

        var currentLayoutNum = parseInt($switcher.attr("data-current-layout"));

        var $presets = getPresetsForBlock($block);
        var $nextLayout = $presets.eq(++currentLayoutNum);
        if (!$nextLayout.length) {
            return;
        }

        $(this).siblings().removeClass("disabled");
        if (currentLayoutNum == $presets.length - 1) {
            $(this).addClass("disabled").siblings().removeClass("disabled");
        }

        $switcher.attr("data-current-layout", currentLayoutNum);

        $block.addClass("transition-none");
        $block.height($block.height());
        setTimeout(function() { // XXX at least 0ms is needed to apply height
            $block.removeClass("transition-none");

            $($nextLayout.html()).insertBefore($block);
            initDrag(); // affects height
            var $newBlock = $block.prev();

            $newBlock.addClass("transition-none");
            $newBlock.css({
                position: "absolute",
                width: $newBlock.width(),
                "margin-left": "100%",
                opacity: 0
            });
            updateHeights();
            $newBlock.removeClass("transition-none");

            $block.css({
                "margin-left": "-210%",
                "margin-right": "210%",
                "height": $newBlock.height(),
                opacity: 1
            });
            $newBlock.css({
                "margin-left": "0%",
                "margin-right": "0%",
                opacity: 1
            });

            setTimeout(function() {
                $block.remove();
                $newBlock.css({
                    position: "",
                    width: ""
                });
                updateHeights(); // FIXME
                onAfterChange();
            }, 400);
        }, 0);

        $switcher.find(".preset-name var").html($nextLayout.attr("data-title").toLowerCase());
    });

    $(document).on("click", ".preset-prev", function() {
        var $switcher = $(this).closest(".section-preset-switcher");
        var $block = $switcher.next();
        var currentLayoutNum = parseInt($switcher.attr("data-current-layout"));

        var $presets = getPresetsForBlock($block);
        var $nextLayout = $presets.eq(--currentLayoutNum);
        if (currentLayoutNum < 0 || !$nextLayout.length) {
            return;
        }

        $(this).siblings().removeClass("disabled");
        if (currentLayoutNum == 0) {
            $(this).addClass("disabled");
        }

        $switcher.attr("data-current-layout", currentLayoutNum);

        $block.addClass("transition-none");
        $block.height($block.height());
        setTimeout(function() {
            $block.removeClass("transition-none");

            $($nextLayout.html()).insertBefore($block);
            initDrag(); // affects height
            var $newBlock = $block.prev();

            $newBlock.addClass("transition-none");
            $newBlock.css({
                position: "absolute",
                width: $block.width(),
                "margin-left": "-110%",
                "margin-right": "110%",
                opacity: 0
            });
            updateHeights();
            $newBlock.removeClass("transition-none");

            $block.css({
                "margin-left": "210%",
                "margin-right": "-210%",
                "height": $newBlock.height(),
                opacity: 1
            });
            $newBlock.css({
                "margin-left": "0%",
                "margin-right": "0%",
                opacity: 1
            });

            setTimeout(function() {
                $block.remove();
                $newBlock.css({
                    position: "",
                    width: ""
                });
                updateHeights(); // FIXME
                onAfterChange();
            }, 400);
        }, 0);

        $switcher.find(".preset-name var").html($nextLayout.attr("data-title").toLowerCase());
    });

    $("#btn-toggle-grid").bootstrapSwitch().on('switchChange', function(e, data) {
        $("#sandbox").toggleClass("show-grid", data.value);
    });

    $(".bootstrap-switch-id-btn-toggle-grid")
        .mouseenter(function() {
            $("#sandbox").addClass("show-grid");
        })
        .mouseleave(function() {
            if (!$(this).find(":checked").length)
                $("#sandbox").removeClass("show-grid");
        });


});
