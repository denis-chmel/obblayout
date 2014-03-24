/**
 * Usage:
 *
 * var constructor = $("#constructor").layoutBuilder();
 *
 */
(function($) {

    var options;
    var defaultOptions = {
        // TODO
    };
    var $constructor = $(this);

    $.fn.layoutBuilder = function(param) {

        if (methods[param]) {
            return methods[ param ].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof param === 'object' || !param) {
            // By default call "__construct" method
            methods.init.apply(this, arguments);
        } else {
            $.error('Method with name ' + param + ' doesn\'t exist in jQuery.workspace');
        }

        return this;
    };

    var methods = {

        focusCurrrentLayoutEditor: function() {
        },

        init: function(passedOptions) {

            options = $.extend(defaultOptions, passedOptions);

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
                .on("keyup", function(e) {
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
                })
                .on("mouseover", ".draggable",function(e) {
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

            $(window)
                .on("scroll", function() {
                    $(".right .header").toggleClass("scrolled", $(self).scrollTop() > 10);
                })
                .on("resize", function() {
                    var value = Math.max(260, ($(this).width() - 1000) / 1.5) + "px";
                    $(".left").css("width", value);
                    $(".right").css("padding-left", value);
                    $(".right .header").css("margin-left", value);
                })
                .trigger("resize");

            $("#page-switcher").change(function() {

                var $currentPage = $(".active-obb-page");
                var $comingPage = $("#" + this.value);

                $(".saved-layouts-for-" + $comingPage.attr("id")).show().siblings().hide();

                if ($currentPage.attr("id") == $comingPage.attr("id")) {
                    return;
                }

                var shouldComeFromUp = $currentPage.index() > $comingPage.index();

                $("#sandbox").css("height", "100%");

                $comingPage.siblings().not($currentPage).hide();

                $currentPage.css({
                    "margin-top": (shouldComeFromUp ? "" : "-") + $currentPage.height() + "px",
                    "opacity": 0
                });

                $comingPage.addClass("animation-off");
                $comingPage.css({
                    "display": "block", // unhide
                    "opacity": 0 // but invisible yet
                });
                $comingPage.removeClass("animation-off");

                $comingPage.css({
                    "margin-top": (shouldComeFromUp ? "-" : "") + $currentPage.height(), // fly in
                    "opacity": 1 // and make visible
                });

                $comingPage.addClass("active-obb-page");
                $comingPage.siblings().removeClass("active-obb-page");
                updateHeights();

            }).trigger("change");

            focusCurrrentLayoutEditor();

        }
    }

})(jQuery);
