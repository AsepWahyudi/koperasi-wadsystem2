'use strict';

/*

Main javascript functions to init most of the elements

#1. CHAT APP
#2. CALENDAR INIT
#3. FORM VALIDATION
#4. DATE RANGE PICKER
#5. DATATABLES
#6. EDITABLE TABLES
#7. FORM STEPS FUNCTIONALITY
#8. SELECT 2 ACTIVATION
#9. CKEDITOR ACTIVATION
#10. CHARTJS CHARTS http://www.chartjs.org/
#11. MENU RELATED STUFF
#12. CONTENT SIDE PANEL TOGGLER
#13. EMAIL APP
#14. FULL CHAT APP
#15. CRM PIPELINE
#16. OUR OWN CUSTOM DROPDOWNS 
#17. BOOTSTRAP RELATED JS ACTIVATIONS
#18. TODO Application
#19. Fancy Selector
#20. SUPPORT SERVICE
#21. Onboarding Screens Modal
#22. Colors Toggler
#23. Auto Suggest Search
#24. Element Actions

*/

// ------------------------------------
// HELPER FUNCTIONS TO TEST FOR SPECIFIC DISPLAY SIZE (RESPONSIVE HELPERS)
// ------------------------------------


function is_display_type(display_type) {
    return $('.display-type').css('content') == display_type || $('.display-type').css('content') == '"' + display_type + '"';
} 
function not_display_type(display_type) {
    return $('.display-type').css('content') != display_type && $('.display-type').css('content') != '"' + display_type + '"';
} 
// Initiate on click and on hover sub menu activation logic
function os_init_sub_menus() { 
    // INIT MENU TO ACTIVATE ON HOVER
    var menu_timer;
    $('.menu-activated-on-hover').on('mouseenter', 'ul.main-menu > li.has-sub-menu', function() {
        var $elem = $(this);
        clearTimeout(menu_timer);
        $elem.closest('ul').addClass('has-active').find('> li').removeClass('active');
        $elem.addClass('active');
    });

    $('.menu-activated-on-hover').on('mouseleave', 'ul.main-menu > li.has-sub-menu', function() {
        var $elem = $(this);
        menu_timer = setTimeout(function() {
            $elem.removeClass('active').closest('ul').removeClass('has-active');
        }, 30);
    });

    // INIT MENU TO ACTIVATE ON CLICK
    $('.menu-activated-on-click').on('click', 'li.has-sub-menu > a', function(event) {
        var $elem = $(this).closest('li');
        if ($elem.hasClass('active')) {
            $elem.removeClass('active');
        } else {
            $elem.closest('ul').find('li.active').removeClass('active');
            $elem.addClass('active');
        }
        return false;
    });
} 
$(function() {
	$('#myudate').daterangepicker({
		"singleDatePicker": true,
		"showDropdowns": true,
		"minYear": 1901,
        "opens": "center",
        "locale": {
            'format': 'DD/MM/YYYY'
        }
	});
	
    $('input.single-daterange').daterangepicker({
        "singleDatePicker": true,
        showDropdowns: true,
        minYear: 1901,
        maxYear: parseInt(moment().format('YYYY'), 10),
        "locale": {
            'format': 'DD/MM/YYYY'
        }
    });

    // INIT MOBILE MENU TRIGGER BUTTON
    $('.mobile-menu-trigger').on('click', function() {
        $('.menu-mobile .menu-and-user').slideToggle(200, 'swing');
        return false;
    });

    if ($('#dataTable1').length) {
        $('#dataTable1').DataTable({ buttons: ['copy', 'excel', 'pdf'] });
    }



    os_init_sub_menus();

    // #3. FORM VALIDATION

    if ($('.formValidate').length) {
        $('.formValidate').validator();
    }

    // #12. CONTENT SIDE PANEL TOGGLER

    $('.content-panel-toggler, .content-panel-close, .content-panel-open').on('click', function() {
        $('.all-wrapper').toggleClass('content-panel-active');
    });



    // #16. OUR OWN CUSTOM DROPDOWNS 
    $('.os-dropdown-trigger').on('mouseenter', function() {
        $(this).addClass('over');
    });
    $('.os-dropdown-trigger').on('mouseleave', function() {
        $(this).removeClass('over');
    });

    // #17. BOOTSTRAP RELATED JS ACTIVATIONS

    // - Activate tooltips
    //$('[data-toggle="tooltip"]').tooltip();

    // - Activate popovers
    //$('[data-toggle="popover"]').popover();

    // #18. TODO Application

    // Tasks foldable trigger
    $('.tasks-header-toggler').on('click', function() {
        $(this).closest('.tasks-section').find('.tasks-list-w').slideToggle(100);
        return false;
    });

    // Sidebar Sections foldable trigger
    $('.todo-sidebar-section-toggle').on('click', function() {
        $(this).closest('.todo-sidebar-section').find('.todo-sidebar-section-contents').slideToggle(100);
        return false;
    });

    // Sidebar Sub Sections foldable trigger
    $('.todo-sidebar-section-sub-section-toggler').on('click', function() {
        $(this).closest('.todo-sidebar-section-sub-section').find('.todo-sidebar-section-sub-section-content').slideToggle(100);
        return false;
    });

    // Drag init
    if ($('.tasks-list').length) {
        // INIT DRAG AND DROP FOR Todo Tasks
        var dragulaTasksObj = dragula($('.tasks-list').toArray(), {
            moves: function moves(el, container, handle) {
                return handle.classList.contains('drag-handle');
            }
        }).on('drag', function() {}).on('drop', function(el) {}).on('over', function(el, container) {
            $(container).closest('.tasks-list').addClass('over');
        }).on('out', function(el, container, source) {

            var new_pipeline_body = $(container).closest('.tasks-list');
            new_pipeline_body.removeClass('over');
            var old_pipeline_body = $(source).closest('.tasks-list');
        });
    }

    // Task actions init

    // Complete/Done
    $('.task-btn-done').on('click', function() {
        $(this).closest('.draggable-task').toggleClass('complete');
        return false;
    });

    // Favorite/star
    $('.task-btn-star').on('click', function() {
        $(this).closest('.draggable-task').toggleClass('favorite');
        return false;
    });

    // Delete
    var timeoutDeleteTask;
    $('.task-btn-delete').on('click', function() {
        if (confirm('Are you sure you want to delete this task?')) {
            var $task_to_remove = $(this).closest('.draggable-task');
            $task_to_remove.addClass('pre-removed');
            $task_to_remove.append('<a href="#" class="task-btn-undelete">Undo Delete</a>');
            timeoutDeleteTask = setTimeout(function() {
                $task_to_remove.slideUp(300, function() {
                    $(this).remove();
                });
            }, 5000);
        }
        return false;
    });

    $('.tasks-list').on('click', '.task-btn-undelete', function() {
        $(this).closest('.draggable-task').removeClass('pre-removed');
        $(this).remove();
        if (typeof timeoutDeleteTask !== 'undefined') {
            clearTimeout(timeoutDeleteTask);
        }
        return false;
    });

    // #19. Fancy Selector
    $('.fs-selector-trigger').on('click', function() {
        $(this).closest('.fancy-selector-w').toggleClass('opened');
    });

    // #20. SUPPORT SERVICE

    $('.close-ticket-info').on('click', function() {
        $('.support-ticket-content-w').addClass('folded-info').removeClass('force-show-folded-info');
        return false;
    });

    $('.show-ticket-info').on('click', function() {
        $('.support-ticket-content-w').removeClass('folded-info').addClass('force-show-folded-info');
        return false;
    });

    $('.support-index .support-tickets .support-ticket').on('click', function() {
        $('.support-index').addClass('show-ticket-content');
        return false;
    });

    $('.support-index .back-to-index').on('click', function() {
        $('.support-index').removeClass('show-ticket-content');
        return false;
    });

    // #21. Onboarding Screens Modal

    $('.onboarding-modal.show-on-load').modal('show');
    if ($('.onboarding-modal .onboarding-slider-w').length) {
        $('.onboarding-modal .onboarding-slider-w').slick({
            dots: true,
            infinite: false,
            adaptiveHeight: true,
            slidesToShow: 1,
            slidesToScroll: 1
        });
        $('.onboarding-modal').on('shown.bs.modal', function(e) {
            $('.onboarding-modal .onboarding-slider-w').slick('setPosition');
        });
    }

    // #22. Colors Toggler

    $('.floated-colors-btn').on('click', function() {
        if ($('body').hasClass('color-scheme-dark')) {
            $('.menu-w').removeClass('color-scheme-dark').addClass('color-scheme-light').removeClass('selected-menu-color-bright').addClass('selected-menu-color-light');
            $(this).find('.os-toggler-w').removeClass('on');
        } else {
            $('.menu-w, .top-bar').removeClass(function(index, className) {
                return (className.match(/(^|\s)color-scheme-\S+/g) || []).join(' ');
            });
            $('.menu-w').removeClass(function(index, className) {
                return (className.match(/(^|\s)color-style-\S+/g) || []).join(' ');
            });
            $('.menu-w').addClass('color-scheme-dark').addClass('color-style-transparent').removeClass('selected-menu-color-light').addClass('selected-menu-color-bright');
            $('.top-bar').addClass('color-scheme-transparent');
            $(this).find('.os-toggler-w').addClass('on');
        }
        $('body').toggleClass('color-scheme-dark');
        return false;
    });

    // #23. Autosuggest Search
    $('.autosuggest-search-activator').on('click', function() {
        var search_offset = $(this).offset();
        // If input field is in the activator - show on top of it
        if ($(this).find('input[type="text"]')) {
            search_offset = $(this).find('input[type="text"]').offset();
        }
        var search_field_position_left = search_offset.left;
        var search_field_position_top = search_offset.top;
        $('.search-with-suggestions-w').css('left', search_field_position_left).css('top', search_field_position_top).addClass('over-search-field').fadeIn(300).find('.search-suggest-input').focus();
        return false;
    });

    $('.search-suggest-input').on('keydown', function(e) {

        // Close if ESC was pressed
        if (e.which == 27) {
            $('.search-with-suggestions-w').fadeOut();
        }

        // Backspace/Delete pressed
        if (e.which == 46 || e.which == 8) {
            // This is a test code, remove when in real life usage
            $('.search-with-suggestions-w .ssg-item:last-child').show();
            $('.search-with-suggestions-w .ssg-items.ssg-items-blocks').show();
            $('.ssg-nothing-found').hide();
        }

        // Imitate item removal on search, test code
        if (e.which != 27 && e.which != 8 && e.which != 46) {
            // This is a test code, remove when in real life usage
            $('.search-with-suggestions-w .ssg-item:last-child').hide();
            $('.search-with-suggestions-w .ssg-items.ssg-items-blocks').hide();
            $('.ssg-nothing-found').show();
        }
    });

    $('.close-search-suggestions').on('click', function() {
        $('.search-with-suggestions-w').fadeOut();
        return false;
    });

    // #24. Element Actions
    $('.element-action-fold').on('click', function() {
        var $wrapper = $(this).closest('.element-wrapper');
        $wrapper.find('.element-box-tp, .element-box').toggle(0);
        var $icon = $(this).find('i');

        if ($wrapper.hasClass('folded')) {
            $icon.removeClass('os-icon-plus-circle').addClass('os-icon-minus-circle');
            $wrapper.removeClass('folded');
        } else {
            $icon.removeClass('os-icon-minus-circle').addClass('os-icon-plus-circle');
            $wrapper.addClass('folded');
        }
        return false;
    });

    //CUSTOM AGUS
    $('#confirmModal').on('shown.bs.modal', function(event) {

        var confirmText = $(event.relatedTarget).attr('data-text-confirm'),
            urlRedirect = $(event.relatedTarget).attr('data-url-redirect');
        $('#confirmModalText').html(confirmText);

        $('#confirmModalBtn').click(function(e) {
            $(location).attr('href', urlRedirect);
        });

    });

    $("#opt-dataperpage").on("change", function() {
        $('#page').val("1");
        ajaxDataTable();
    });
    $("#form-search").keydown(function(e) {
        if (e.which === 13) {
            resetPage();
            ajaxDataTable();
        }
    });

});

//CUSTOM AGUS
function resetPage() {
    $('#page').val(1);
} 
function ajaxDataTable(footer = true) {

    var columnNum = $('#table-view table thead tr').find('th').length,
        tableBody = $('#table-view table tbody'),
        data      = $('#filterForm').serializeArray(),
        urlData   = $('#urltarget').val(),
        page      = $('#page').val();

    if ($('#opt-dataperpage').length > 0) {
        data.push({ name: 'dataperpage', value: $('#opt-dataperpage').val() });
    }

    if ($('#form-search').length > 0) {
        data.push({ name: 'keyword', value: $('#form-search').val() });
    }

    $.ajax({
        type      : 'POST',
        url       : base_url + urlData,
        data      : data,
        dataType  : 'json',
        beforeSend: function() {
            tableBody.html("<tr><td colspan='" + columnNum + "' class='text-center'>Sedang mengambil data..</td></tr>");
        },
        success: function(apiRes) {

            var pageTotal = apiRes.pagetotal;

            if (apiRes.status == 404) {
                tableData = "<tr><td colspan='" + columnNum + "' align='center'><b>No data found</b></td></tr>";
            } else {
                var numData   = apiRes.startNumber,
                    tableData = renderTableData(numData, apiRes.data);
            }

            tableBody.html(tableData);
            if (footer) {
                generateDataInfo("dataTables_info", apiRes.datastart, apiRes.dataend, apiRes.datatotal);
                generatePagination("dataTables_paginate", page, pageTotal);
            }

        },
        error: function() {
            alert("Gagal mendapatkan data. Harap cek koneksi anda");
        }
    }); 
} 
function setPageAjaxDataTable(page) {
    $('#page').val(page);
    ajaxDataTable();
} 
function generateDataInfo(idcontainer, datastart, dataend, datatotal) {
    $('#' + idcontainer).html("Menampilkan data ke " + datastart + " sampai " + dataend + " dari " + datatotal);
} 
function generatePagination(idcontainer, page, pageTotal) {

    var nextpage    = (page * 1 + 1);
    var next        = page == pageTotal || pageTotal == 0 || nextpage > pageTotal ? "disabled" : "";
    var nextOnClick = page == pageTotal || pageTotal == 0 || nextpage > pageTotal ? "" : "setPageAjaxDataTable(" + nextpage + ")";
    var nextButton  = "<li class='paginate_button page-item next " + next + "' id='datatable_next' onclick='" + nextOnClick + "'><a href='#' class='page-link'>Next</a></li>";

    var prevpage    = (page * 1 - 1);
    var previous    = page == 1 || pageTotal <= 1 ? "disabled" : "";
    var prevOnClick = page == 1 || pageTotal <= 1 ? "" : "setPageAjaxDataTable(" + prevpage + ")";
    var prevButton  = "<li class='paginate_button page-item previous " + previous + "' id='datatable_previous' onclick='" + prevOnClick + "'><a href='#' class='page-link'>Prev</a></li>";
    var pagesBtn    = "";

    if (pageTotal > 0) {

        if (pageTotal <= 10) {
            for (var i = 1; i <= pageTotal; i++) {
                var activeStr = i == page ? "active" : "";
                var onClick = i == page ? "" : "setPageAjaxDataTable(" + i + ")";
                pagesBtn += "<li class='paginate_button page-item " + activeStr + "' onclick='" + onClick + "'><a href='#' class='page-link'>" + i + "</a></li>";
            }
        } else {
            var lastNum, nextNum;

            if (page > pageTotal - 5) {
                lastNum = page - (10 - (pageTotal - page + 1));
                nextNum = pageTotal;
            } else {
                lastNum = page <= 4 ? 1 : page - 4;
                nextNum = page <= 4 ? 10 : (page * 1) + 5;
            }
            console.log("page : " + page);
            console.log("first num : " + lastNum);
            console.log("last num : " + nextNum);
            var pagesPrev = "";
            var pagesNext = "";

            if (page != 1) {
                for (var i = lastNum; i < page; i++) {
                    var activeStr = i == page ? "active" : "";
                    var onClick = i == page ? "" : "setPageAjaxDataTable(" + i + ")";
                    pagesPrev += "<li class='paginate_button page-item " + activeStr + "' onclick='" + onClick + "'><a href='#' class='page-link'>" + i + "</a></li>";
                }
            }

            for (var j = page; j <= nextNum; j++) {
                var activeStr = j == page ? "active" : "";
                var onClick = j == page ? "" : "setPageAjaxDataTable(" + j + ")";
                pagesNext += "<li class='paginate_button page-item " + activeStr + "' onclick='" + onClick + "'><a href='#' class='page-link'>" + j + "</a></li>";
            }

            pagesBtn = pagesPrev + pagesNext;
        }

    }
    $('#' + idcontainer).html(prevButton + pagesBtn + nextButton);

} 
$('#btnfotowajah').click(function() {
    $('#bfotowajah').click();
});
$('#btnfotoktp').click(function() {
    $('#bfotoktp').click();
}); 
$('#btnfotobn').click(function() {
    $('#bfotobn').click();
}); 
$('#btnfotokk').click(function() {
    $('#bfotokk').click();
}); 
$('#btnfotonpwp').click(function() {
    $('#bfotonpwp').click();
}); 
$('#bfotowajah').change(function(e) {
    var urlpath = $(this).data("url");
    var formdata = new FormData();
    if ($(this).prop('files').length > 0) {
        var file = $(this).prop('files')[0];
        formdata.append("music", file);
        if (!file.type.match('image.*')) {
            alert("Invalid File Type");
        } else {
            $.ajax({
                url: base_url + '/anggota/uploadwajah',
                method: 'POST',
                data: formdata,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $("#loadingfotowajah").css("display", "block");
                    $("#btnfotowajah").css("display", "none");
                    $("#lamafotowajah").css("display", "none");
                },
                success: function(data) {
                    var datax = data.split("|");
                    if (datax[0] === "00") {
                        $("#loadingfotowajah").css("display", "none");
                        $("#filefotowajah").val(datax[1]);
                        $('#blokfotowajah').prepend('<img src="' +
                            urlpath + datax[1] +
                            '"  width="50" />')
                    } else {
                        $("#loadingfotowajah").css("display", "none");
                        $("#btnfotowajah").css("display", "block");
						alert(datax[1]);
                    }
                }
            });
        }
    } else {
        alert("Invalid File");
    }
}); 
$('#bfotoktp').change(function(e) {
    var urlpath = $(this).data("url");
    var formdata = new FormData();
    if ($(this).prop('files').length > 0) {
        var file = $(this).prop('files')[0];
        formdata.append("music", file);
        if (!file.type.match('image.*')) {
            alert("Invalid File Type");
        } else {
            $.ajax({
                url: base_url + '/anggota/uploadktp',
                method: 'POST',
                data: formdata,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $("#loadingfotoktp").css("display", "block");
                    $("#btnfotoktp").css("display", "none");
                    $("#lamafotoktp").css("display", "none");
                },
                success: function(data) {
                    var datax = data.split("|");
                    if (datax[0] === "00") {
                        $("#loadingfotoktp").css("display", "none");
                        $("#filefotoktp").val(datax[1]);
                        $('#blokfotoktp').prepend('<img src="' +
                            urlpath + datax[1] +
                            '"  width="50" />')
                    } else {
                        $("#loadingfotoktp").css("display", "none");
                        $("#btnfotoktp").css("display", "block");
                        alert(datax[1]);
                    }

                }
            });
        }
    } else {
        alert("Invalid File");
    }
}); 
$('#bfotonpwp').change(function(e) {
    var urlpath = $(this).data("url");
    var formdata = new FormData();
    if ($(this).prop('files').length > 0) {
        var file = $(this).prop('files')[0];
        formdata.append("music", file);
        if (!file.type.match('image.*')) {
            alert("Invalid File Type");
        } else {
            $.ajax({
                url: base_url + '/anggota/uploadnpwp',
                method: 'POST',
                data: formdata,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $("#loadingfotonpwp").css("display", "block");
                    $("#btnfotonpwp").css("display", "none");
                    $("#lamafotonpwp").css("display", "none");
                },
                success: function(data) {
                    var datax = data.split("|");
                    if (datax[0] === "00") {
                        $("#loadingfotonpwp").css("display", "none");
                        $("#filefotonpwp").val(datax[1]);
                        $('#blokfotonpwp').prepend('<img src="' +
                            urlpath + datax[1] +
                            '"  width="50" />')
                    } else {
                        $("#loadingfotonpwp").css("display", "none");
                        $("#btnfotonpwp").css("display", "block");
                        alert(datax[1]);
                    }

                }
            });
        }
    } else {
        alert("Invalid File");
    }
}); 
$('#bfotokk').change(function(e) {
    var urlpath = $(this).data("url");
    var formdata = new FormData();
    if ($(this).prop('files').length > 0) {
        var file = $(this).prop('files')[0];
        formdata.append("music", file);
        if (!file.type.match('image.*')) {
            alert("Invalid File Type");
        } else {
            $.ajax({
                url: base_url + '/anggota/uploadkk',
                method: 'POST',
                data: formdata,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $("#loadingfotokk").css("display", "block");
                    $("#btnfotokk").css("display", "none");
                    $("#lamafotokk").css("display", "none");
                },
                success: function(data) {
                    var datax = data.split("|");
                    if (datax[0] === "00") {
                        $("#loadingfotokk").css("display", "none");
                        $("#filefotokk").val(datax[1]);
                        $('#blokfotokk').prepend('<img src="' +
                            urlpath + datax[1] +
                            '"  width="50" />')
                    } else {
						alert(datax[1]);
                        $("#loadingfotokk").css("display", "none");
                        $("#btnfotokk").css("display", "block");
                        
                    }

                }
            });
        }
    } else {
        alert("Invalid File");
    }
}); 
$('#bfotobn').change(function(e) {
    var urlpath = $(this).data("url");
    var formdata = new FormData();
    if ($(this).prop('files').length > 0) {
        var file = $(this).prop('files')[0];
        formdata.append("music", file);
        if (!file.type.match('image.*')) {
            alert("Invalid File Type");
        } else {
            $.ajax({
                url: base_url + 'anggota/uploadbn',
                method: 'POST',
                data: formdata,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $("#loadingfotobn").css("display", "block");
                    $("#btnfotobn").css("display", "none");
                    $("#lamafotobn").css("display", "none");
                },
                success: function(data) {
                    var datax = data.split("|");
                    if (datax[0] === "00") {
                        $("#loadingfotobn").css("display", "none");
                        $("#filefotobn").val(datax[1]);
                        $('#blokfotobn').prepend('<img src="' +
                            urlpath + datax[1] +
                            '"  width="50" />')
                    } else {
						alert(datax[1]);
                        $("#loadingfotobn").css("display", "none");
                        $("#btnfotobn").css("display", "block");
                    }

                }
            });
        }
    } else {
        alert("Invalid File");
    }
});

/////////////////////////////////////////
var idbukti;
$('.uploadbukti').click(function() {
    //alert($(this).data("id"));
	idbukti = $(this).data("id");
	 $('#buktiteller'+idbukti).click();
}); 
$('.buktiteller').change(function(e) {
    var form = $("#form"+idbukti)[0];
    var data = new FormData(form);
    if ($(this).prop('files').length > 0) {
		
        $.ajax({
                url: base_url + 'uploadbukti/teller/',
                method: 'POST',
				enctype: 'multipart/form-data',
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $("#loadingteller"+idbukti).css("display", "block");
                    $("#uploadbukti"+idbukti).css("display", "none");
                   
                },
                success: function(data) {
                    var datax = data.split("|");
                    if (datax[0] === "00") {
                        location.reload();
                       
                    } else {
						alert(datax[1]);
                        $("#loadingteller"+idbukti).css("display", "none");
                       
                    }

                }
            });
    } else {
        alert("Invalid File");
    }
});
$('.btnconfteller').click(function() { 
	$.ajax({
		url: base_url + "confirmteller",
		type: "post",
		data: "id="+$(this).data("id"),
		success: function (response) {
			if(response=="ok")
			{
				alert("Update Berhasil");
				location.reload();
			}else{
				alert("Konfirmasi Gagal");
			}
		}
	});
});
///////////////////////////////// 
var idbuktisetoran;
$('.uploadbuktisetoran').click(function() {
    //alert($(this).data("id"));
	idbuktisetoran = $(this).data("id");
	 $('#buktitellersetoran'+idbuktisetoran).click();
}); 
$('.buktitellersetoran').change(function(e) {
    var form = $("#form"+idbuktisetoran)[0];
    var data = new FormData(form);
    if ($(this).prop('files').length > 0) {
		
        $.ajax({
                url: base_url + 'uploadbukti/tellersetoran/',
                method: 'POST',
				enctype: 'multipart/form-data',
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $("#loadingtellersetoran"+idbuktisetoran).css("display", "block");
                    $("#uploadbuktisetoran"+idbuktisetoran).css("display", "none");
                   
                },
                success: function(data) {
                    var datax = data.split("|");
                    if (datax[0] === "00") {
                        location.reload();
                       
                    } else {
						alert(datax[1]);
                        $("#loadingtellersetoran"+idbuktisetoran).css("display", "none");
                       
                    }

                }
            });
    } else {
        alert("Invalid File");
    }
});
$('.btnconftellersetoran').click(function() { 
	$.ajax({
		url: base_url + "confirmtellersetoran",
		type: "post",
		data: "id="+$(this).data("id"),
		success: function (response) {
			if(response=="ok")
			{
				alert("Update Berhasil");
				location.reload();
			}else{
				alert("Konfirmasi Gagal");
			}
		}
	});
});
/////////////////////////////////
///////////////////////////////// 
var idbuktiangsuran;
$('.uploadbuktiangsuran').click(function() {
    //alert($(this).data("id"));
	idbuktiangsuran = $(this).data("id");
	 $('#buktitellerangsuran'+idbuktiangsuran).click();
}); 
$('.buktitellerangsuran').change(function(e) {
    var form = $("#form"+idbuktiangsuran)[0];
    var data = new FormData(form);
    if ($(this).prop('files').length > 0) {
		
        $.ajax({
                url: base_url + 'uploadbukti/tellerangsuran/',
                method: 'POST',
				enctype: 'multipart/form-data',
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $("#loadingtellerangsuran"+idbuktiangsuran).css("display", "block");
                    $("#uploadbuktiangsuran"+idbuktiangsuran).css("display", "none");
                   
                },
                success: function(data) {
                    var datax = data.split("|");
                    if (datax[0] === "00") {
                        location.reload();
                       
                    } else {
						alert(datax[1]);
                        $("#loadingtellerangsuran"+idbuktiangsuran).css("display", "none");
                       
                    }

                }
            });
    } else {
        alert("Invalid File");
    }
});
$('.btnconftellerangsuran').click(function() { 
	$.ajax({
		url: base_url + "confirmtellerangsuran", 
		type: "post",
		data: "id="+$(this).data("id"),
		success: function (response) {
			if(response=="ok")
			{
				// alert("Update Berhasil");
				// location.reload();
			}else{
				alert("Konfirmasi Gagal");
			}
		}
	});
});
/////////////////////////////////
var idbukticair;
$('.cairbutton').click(function() {
    //alert($(this).data("id"));
	idbukticair = $(this).data("id");
	 $('#bukticair'+idbukticair).click();
}); 
$('.bukticair').change(function(e) {
    var form = $("#form"+idbukticair)[0];
    var data = new FormData(form);
    if ($(this).prop('files').length > 0) {
		
        $.ajax({
                url: base_url + 'uploadbukti/pinjaman/',
                method: 'POST',
				enctype: 'multipart/form-data',
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $("#loadingcair"+idbukticair).css("display", "block");
                    $("#cairbutton"+idbukticair).css("display", "none");
                   
                },
                success: function(data) {
                    var datax = data.split("|");
                    if (datax[0] === "00") {
                        location.reload();
                       
                    } else {
						alert(datax[1]);
                        $("#loadingcair"+idbukticair).css("display", "none");
                       
                    }

                }
            });
    } else {
        alert("Invalid File");
    }
}); 
$('.btnconfpinjam').click(function() { 
	$.ajax({
		url: base_url + "confirmpinjam",
		type: "post",
		data: "id="+$(this).data("id"),
		success: function (response) {
			if(response=="ok")
			{
				alert("Update Berhasil");
				location.reload();
			}else{
				alert("Konfirmasi Gagal");
			}
		}
	});
}); 
///////////////////KOLEKTOR//////////////////////
var idkoletro;
$('.uploadkolektor').click(function() {
    //alert($(this).data("id"));
	idbukti = $(this).data("id");
	$('#buktikolektor'+idbukti).click();
}); 
$('.buktikolektor').change(function(e) {
    var form = $("#form"+idbukti)[0];
    var data = new FormData(form);
    if ($(this).prop('files').length > 0) {
		
        $.ajax({
                url: base_url + 'uploadbukti/kolektor/',
                method: 'POST',
				enctype: 'multipart/form-data',
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $("#loadingkolektor"+idbukti).css("display", "block");
                    $("#uploadkolektor"+idbukti).css("display", "none");
                },
                success: function(data) {
                    var datax = data.split("|");
                    if (datax[0] === "00") {
                        location.reload(); 
                    } else {
						alert(datax[1]);
                        $("#uploadkolektor"+idbukti).css("display", "none"); 
                    } 
                }
            });
    } else {
        alert("Invalid File");
    }
});
$('.btnconfkolektor').click(function() { 
	$.ajax({
		url: base_url + "confirmkolektor",
		type: "post",
		data: "id="+$(this).data("id"),
		success: function (response) {
			if(response=="ok")
			{
				alert("Update Berhasil");
				location.reload();
			}else{
				alert("Konfirmasi Gagal");
			}
		}
	});
});
///////////////////////////////// 