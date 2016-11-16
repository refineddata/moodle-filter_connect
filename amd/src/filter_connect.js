/**
 * Created by Dmitriy on 11/07/14.
 * Converted to AMD by Ras 2016 July 6
 */

define(['jquery', 'jqueryui'], function($, ui){

    var connect = {
        init: function(){
            $(document).ready(function () {
                get_connect_filter();

                $(".connect_filter_mymeetings_block").each(function (index) {
                    var block = $(this);
                    $.ajax({
                        url: window.wwwroot + "/filter/connect/ajax/mymeetings_callback.php",
                        dataType: "html",
                        data: {
                            days: block.data('days')
                        }
                    }).done(function (data) {
                        block.html(data);
                        get_connect_filter();
                    }).fail(function (jqXHR, textStatus, errorThrown) {
                        add_connect_filter_alert(block, 'danger', jqXHR.status + " " + jqXHR.statusText);
                    });
                });
            });

            $(document).ajaxComplete(function () {
                $(document).tooltip({
                    show: null, // show immediately
                    items: '.connect_tooltip',
                    content: function () {
                        return $(this).next('.connect_popup').html();
                    },
                    position: {my: "left top", at: "right top", collision: "flipfit"},
                    hide: {
                        effect: "" // fadeOut
                    },
                    open: function (event, ui) {
                        ui.tooltip.animate({left: ui.tooltip.position().left + 10}, "fast");
                    },
                    close: function (event, ui) {
                        ui.tooltip.hover(
                            function () {
                                $(this).stop(true).fadeTo(400, 1);
                            },
                            function () {
                                $(this).fadeOut("400", function () {
                                    $(this).remove();
                                })
                            }
                        );
                    }
                });
            });


            function add_connect_filter_alert(block, type, msg) {
                block.html(
                    '<div class="fitem" id="fgroup_id_urlgrp_alert">' +
                    '<div class="felement fstatic alert alert-' + type + ' alert-dismissible">' +
                    '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>' +
                    msg +
                    '</div>' +
                    '</div>'
                );
            }

            function get_connect_filter() {
                $('.connect_filter_block').each(function (index) {
                    var block = $(this);
                    var acurl = block.data('acurl');
                    var sco = block.data('sco');
                    var courseid = block.data('courseid');
                    block.removeClass('connect_filter_block').addClass('connect_filter_block_done');
                    $.ajax({
                        url: window.wwwroot + "/filter/connect/ajax/connect_callback.php",
                        dataType: "html",
                        data: {
                            acurl: acurl,
                            sco: sco,
                            courseid: courseid,
                            options: encodeURIComponent(block.data('options')),
                            frommymeetings: block.data('frommymeetings'),
                            frommyrecordings: block.data('frommyrecordings')
                        }
                    }).done(function (data) {
                        block.html(data);
                    }).fail(function (jqXHR, textStatus, errorThrown) {
                        add_connect_filter_alert(block, 'danger', jqXHR.status + " " + jqXHR.statusText);
                    });
                });
            }

        }
    };

    return connect;
});

