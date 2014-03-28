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

        /**
         * http://stackoverflow.com/questions/18053408/vertically-centering-bootstrap-modal-window
         */
        makeBootstrapModalsVerticallyCentered: function() {
            function centerModal() {
                $(this).css('display', 'block');
                var $dialog = $(this).find(".modal-dialog");
                var offset = ($(window).height() - $dialog.height()) / 2;
                // Center modal vertically in window
                $dialog.css("margin-top", offset);
            }

            $('.modal').on('show.bs.modal', centerModal);
            $(window).on("resize", function () {
                $('.modal:visible').each(centerModal);
            });
        },

        helpShow: function() {
            $("body").addClass("help-active");
            var body = $("body");
            setTimeout(function(){
                $("#help-cover").css({
                    "background-color": "rgba(255, 255, 255, 0.6)"
                });
                var delay = 500;
                $("#help svg g").queue(function(){
                    $(this).delay(delay).fadeIn();
                    delay += 1000;
                    $(this).dequeue();
                });
            }, 1)
        },

        helpHide: function() {
            $("body").removeClass("help-active");
            $("#help-cover").css({
                "background-color": ""
            });
        },

        init: function(passedOptions) {

            options = $.extend(defaultOptions, passedOptions);

//            $("body")
//                .on("click", ".default-footer, .default-header", function(e) {
//                    $.growl.error({ message: "This page uses the general header/footer, change them on a front page" });
//                    e.stopPropagation();
//                });

            $(document)
                .on("click", function(e) {
                    if ($(e.target).closest(".modal").length) {
                        return;
                    }
                    if ($(e.target).closest(".nav-tabs li").length) {
                        updateHeights();
                        updateHeights();
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
                .on("click", ".block-controls .settings", function(e) {
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
                        case 27: // Esc
                            methods.helpHide();
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
                .on("resize", function() {
                    var value = Math.max(260, ($(this).width() - 900) / 1.5);
                    $("#left").css("width", value);
                    $("#right").css("padding-left", value);
                    $("#right .header").css("margin-left", value);
                    $(".help-text").css("left", value - 100);
                    $("#left .contents").css("width", $(this).width() < 1600 ? "260" : "430");
                    updateHeights();
                })
                .trigger("resize");

            $("#btn-help").click(function(){
                methods.helpShow();
            });

            $("#help").click(function(){
                methods.helpHide();
            });

            focusCurrrentLayoutEditor();
            methods.makeBootstrapModalsVerticallyCentered();

        }
    }

})(jQuery);
