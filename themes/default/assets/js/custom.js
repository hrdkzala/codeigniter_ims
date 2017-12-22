/* JS */


function widthFunctions(e) {
    $(".tip").tooltip();
    $(".chzn-container").css('width', '100%');
    var bH = $('body').height();
    var nH = $('.navbar').outerHeight();
    var mH = $('.mainbar').height();
    var sbH = bH - nH;
    if ( mH > sbH ) {
        $('.sidebar-inner').css('height', (mH+20)+'px');
    } else {
        $('.sidebar-inner').css('height', (sbH)+'px');
    }
}

$(document).ready(function () {
    widthFunctions();
});
$(window).load(function () {
    setTimeout(widthFunctions, 2000);
});
$(window).bind("resize", widthFunctions);
//$('.container').bind("resize", widthFunctions);
/* Navigation */

$(document).ready(function(){

    $(window).resize(function()
    {
        if($(window).width() >= 765){
            $(".sidebar .sidebar-inner").css('width', '100%');
            $(".sidebar .sidebar-inner").slideDown(350);
        }
        else{
            $(".sidebar .sidebar-inner").slideUp(350);
        }
    });

});

$(document).ready(function(){

    $(".has_submenu > a").click(function(e){
        e.preventDefault();
        var menu_li = $(this).parent("li");
        var menu_ul = $(this).next("ul");

        if(menu_li.hasClass("open")){
            menu_ul.slideUp(350);
            menu_li.removeClass("open")
        }
        else{
            $(".navi > li > ul").slideUp(350);
            $(".navi > li").removeClass("open");
            menu_ul.slideDown(350);
            menu_li.addClass("open");
        }
    });

});

$(document).ready(function(){
    $(".sidebar-dropdown a").on('click',function(e){
        e.preventDefault();

        if(!$(this).hasClass("dropy")) {
            // hide any open menus and remove all other classes
            $(".sidebar .sidebar-inner").slideUp(350);
            $(".sidebar-dropdown a").removeClass("dropy");

            // open our new menu and add the dropy class
            $(".sidebar .sidebar-inner").slideDown(350);
            $(this).addClass("dropy");
        }

        else if($(this).hasClass("dropy")) {
            $(this).removeClass("dropy");
            $(".sidebar .sidebar-inner").slideUp(350);
        }
    });

});

/* Widget close */

$('.wclose').click(function(e){
    e.preventDefault();
    var $wbox = $(this).parent().parent().parent();
    $wbox.hide(100);
});

/* Widget minimize */

$('.wminimize').click(function(e){
    e.preventDefault();
    var $wcontent = $(this).parent().parent().next('.widget-content');
    if($wcontent.is(':visible'))
    {
        $(this).children('i').removeClass('icon-chevron-up');
        $(this).children('i').addClass('icon-chevron-down');
    }
    else
    {
        $(this).children('i').removeClass('icon-chevron-down');
        $(this).children('i').addClass('icon-chevron-up');
    }
    $wcontent.toggle(500);
});


/* Progressbar animation */

setTimeout(function(){

    $('.progress-animated .progress-bar').each(function() {
        var me = $(this);
        var perc = me.attr("data-percentage");

        //TODO: left and right text handling

        var current_perc = 0;

        var progress = setInterval(function() {
            if (current_perc>=perc) {
                clearInterval(progress);
            } else {
                current_perc +=1;
                me.css('width', (current_perc)+'%');
            }

            me.text((current_perc)+'%');

        }, 600);

    });

},600);

/* Slider */

$(function() {
    // Horizontal slider
    $( "#master1, #master2" ).slider({
        value: 60,
        orientation: "horizontal",
        range: "min",
        animate: true
    });

    $( "#master4, #master3" ).slider({
        value: 80,
        orientation: "horizontal",
        range: "min",
        animate: true
    });

    $("#master5, #master6").slider({
        range: true,
        min: 0,
        max: 400,
        values: [ 75, 200 ],
        slide: function( event, ui ) {
            $( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
        }
    });


    // Vertical slider
    $( "#eq > span" ).each(function() {
        // read initial values from markup and remove that
        var value = parseInt( $( this ).text(), 10 );
        $( this ).empty().slider({
            value: value,
            range: "min",
            animate: true,
            orientation: "vertical"
        });
    });
});



/* Support */

$(document).ready(function(){
    $("#slist a").click(function(e){
        e.preventDefault();
        $(this).next('p').toggle(200);
    });
});

/* Scroll to Top */


$(".totop").hide();

$(function(){
    $(window).scroll(function(){
        if ($(this).scrollTop()>300)
        {
            $('.totop').slideDown();
        }
        else
        {
            $('.totop').slideUp();
        }
    });

    $('.totop a').click(function (e) {
        e.preventDefault();
        $('body,html').animate({scrollTop: 0}, 500);
    });

});


/* Date picker */

$(function() {
    $('#datetimepicker1').datetimepicker({
        pickTime: false
    });
});



$(function() {
    $('#datetimepicker2').datetimepicker({
        pickDate: false
    });
});


$(function() {
    $( "#todaydate" ).datepicker({
        onSelect: function(dateText, inst) {
            var sp = dateText.split('/')
            var href = base_url+'calendar/get_event/' + sp[2]+'-'+sp[0]+'-'+sp[1];
            $.get(href, function( data ) {
                $("#simModal").html(data).modal();
            });
        }
    });
});

/* Modal fix */

$('.modal').appendTo($('body'));



/* Notification box */


$('.slide-box-head').click(function() {
    var $slidebtn=$(this);
    var $slidebox=$(this).parent().parent();
    if($slidebox.css('right')=="-252px"){
        $slidebox.animate({
            right:0
        },500);
        $slidebtn.children("i").removeClass().addClass("icon-chevron-right");
    }
    else{
        $slidebox.animate({
            right:-252
        },500);
        $slidebtn.children("i").removeClass().addClass("icon-chevron-left");
    }
});


$('.sclose').click(function(e){
    e.preventDefault();
    var $wbox = $(this).parent().parent().parent();
    $wbox.hide(0);
});


$('.sminimize').click(function(e){
    e.preventDefault();
    var $wcontent = $(this).parent().parent().next('.slide-content');
    if($wcontent.is(':visible'))
    {
        $(this).children('i').removeClass('icon-chevron-down');
        $(this).children('i').addClass('icon-chevron-up');
    }
    else
    {
        $(this).children('i').removeClass('icon-chevron-up');
        $(this).children('i').addClass('icon-chevron-down');
    }
    $wcontent.toggle(0);
});

$(document).ready(function() {
    $('#gen_ref').click(function(){
        $(this).parent('.input-group').children('input').val(getRandomRef());
    });
    $('#simModal').on('hidden.bs.modal', function() {
        $(this).find('.modal-dialog').empty();
        //$(this).find('#simModalLabel').empty().html('&nbsp;');
        //$(this).find('.modal-body').empty().text('Loading...');
        //$(this).find('.modal-footer').empty().html('&nbsp;');
        $(this).removeData('bs.modal');
    });
    $('#simModal2').on('hidden.bs.modal', function () {
        $(this).find('.modal-dialog').empty();
        //$(this).find('#simModalLabel').empty().html('&nbsp;');
        //$(this).find('.modal-body').empty().text('Loading...');
        //$(this).find('.modal-footer').empty().html('&nbsp;');
        $(this).removeData('bs.modal');
        $('#simModal').css('zIndex', '1050');
        $('#simModal').css('overflow-y', 'scroll');
    });
    $('#simModal2').on('show.bs.modal', function () {
        $('#simModal').css('zIndex', '1040');
    });
    $('.modal').on('show.bs.modal', function () {
        $('#modal-loading').show();
        $('.blackbg').css('zIndex', '1041');
        $('.loader').css('zIndex', '1042');
    }).on('hide.bs.modal', function () {
        $('#modal-loading').hide();
        $('.blackbg').css('zIndex', '3');
        $('.loader').css('zIndex', '4');
    });
    $("form select").chosen({no_results_text: "No results matched", disable_search_threshold: 5, allow_single_deselect:true});
    $('#myTab a').click(function (e) {
        e.preventDefault()
        $(this).tab('show');
    });
    $('#myTab a').first().tab('show');
    if (location.hash !== '') $('a[href="' + location.hash + '"]').tab('show');
    return $('a[data-toggle="tab"]').on('shown', function(e) {
      return location.hash = $(e.target).attr('href').substr(1);
    });
});

function fld(oObj) {
    if (oObj != null) {
        var aDate = oObj.split('-');
        var bDate = aDate[2].split(' ');
        year = aDate[0], month = aDate[1], day = bDate[0], time = bDate[1];
        if (js_date == 'dd-mm-yy')
            return day + "-" + month + "-" + year + " " + time;
        else if (js_date === 'dd/mm/yy')
            return day + "/" + month + "/" + year + " " + time;
        else if (js_date == 'dd.mm.yy')
            return day + "." + month + "." + year + " " + time;
        else if (js_date == 'mm/dd/yy')
            return month + "/" + day + "/" + year + " " + time;
        else if (js_date == 'mm-dd-yy')
            return month + "-" + day + "-" + year + " " + time;
        else if (js_date == 'mm.dd.yy')
            return month + "." + day + "." + year + " " + time;
        else
            return oObj;
    } else {
        return '';
    }
}
function fsd(oObj) {
    if (oObj != null) {
        var aDate = oObj.split('-');
        if (js_date == 'dd-mm-yy')
            return aDate[2] + "-" + aDate[1] + "-" + aDate[0];
        else if (js_date === 'dd/mm/yy')
            return aDate[2] + "/" + aDate[1] + "/" + aDate[0];
        else if (js_date == 'dd.mm.yy')
            return aDate[2] + "." + aDate[1] + "." + aDate[0];
        else if (js_date == 'mm/dd/yy')
            return aDate[1] + "/" + aDate[2] + "/" + aDate[0];
        else if (js_date == 'mm-dd-yy')
            return aDate[1] + "-" + aDate[2] + "-" + aDate[0];
        else if (js_date == 'mm.dd.yy')
            return aDate[1] + "." + aDate[2] + "." + aDate[0];
        else
            return oObj;
    } else {
        return '';
    }
}
function formatNumber(x, d) {
    if(!d) { d = decimals; }
    return accounting.formatNumber(x, d, thousands_sep == 0 ? ' ' : thousands_sep, decimals_sep);
}
function formatMoney(x, symbol) {
    if(!symbol) { symbol = ""; }
    return accounting.formatMoney(x, symbol, decimals, thousands_sep == 0 ? ' ' : thousands_sep, decimals_sep, "%s%v");
}
function decimalFormat(x) {
    if (x != null) {
        return '<div class="text-center">'+formatNumber(x)+'</div>';
    } else {
        return '<div class="text-center">0</div>';
    }
}
function currencyFormat(x) {
    if (x != null) {
        return '<div class="text-right">'+formatMoney(x)+'</div>';
    } else {
        return '<div class="text-right">0</div>';
    }
}
function formatDecimal(x) {
    return parseFloat(x).toFixed(decimals);
}

function getRandomRef() {
    var min = 1000000000000000, max = 9999999999999999;
    return Math.floor(Math.random() * (max - min + 1)) + min;
}
