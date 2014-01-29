(function(t){function e(e){return f?e.data("events"):t._data(e[0]).events}function n(t,n,r){var i=e(t),a=i[n];if(!f){var s=r?a.splice(a.delegateCount-1,1)[0]:a.pop();return a.splice(r?0:a.delegateCount||0,0,s),void 0}r?i.live.unshift(i.live.pop()):a.unshift(a.pop())}function r(e,r,i){var a=r.split(/\s+/);e.each(function(){for(var e=0;a.length>e;++e){var r=t.trim(a[e]).match(/[^\.]+/i)[0];n(t(this),r,i)}})}var i=t.fn.jquery.split("."),a=parseInt(i[0]),s=parseInt(i[1]),f=1>a||1==a&&7>s;t.fn.bindFirst=function(){var e=t.makeArray(arguments),n=e.shift();return n&&(t.fn.bind.apply(this,arguments),r(this,n)),this},t.fn.delegateFirst=function(){var e=t.makeArray(arguments),n=e[1];return n&&(e.splice(0,2),t.fn.delegate.apply(this,arguments),r(this,n,!0)),this},t.fn.liveFirst=function(){var e=t.makeArray(arguments);return e.unshift(this.selector),t.fn.delegateFirst.apply(t(document),e),this},f||(t.fn.onFirst=function(e,n){var i=t(this),a="string"==typeof n;if(t.fn.on.apply(i,arguments),"object"==typeof e)for(type in e)e.hasOwnProperty(type)&&r(i,type,a);else"string"==typeof e&&r(i,e,a);return i})})(jQuery);
window.onload = function () {
    refreshTiny();
    $('#modalform').submit(function(e){
        $('#modalform').find('button[type="submit"]').val("<span class=\"glyphicon glyphicon-cog\"></span> Uploading logo");
        var fd = new FormData($(this)[0]);
        $.ajax({
            url     : "includes/ajax.php",
            type    : "POST",
            data    : fd,
            dataType: "html",
            cache: false,
            contentType: false,
            processData: false,
            async:false,
            success: function(data){
                $('#modalform').find('input[name="logo"]').val(data);
                $('#modalform').find('button[type="submit"]').val("<span class=\"glyphicon glyphicon-ok\"></span>");
                ajax_right($('#modalform').find('button[type="submit"]'));
          }
        });
        e.preventDefault();
    });
    $('[data-action="ajax-right"]').click(function(e){
        ajax_right($(this));
        e.preventDefault();
    });
    function ajax_right($this){
        loading();
        $('#view_site_template').modal('hide');
        $('#create_new').modal('hide');
        if($this.data('names')) var params=$this.data('names').split(',');
        var obj=new Object();
        if(params) for(var i=0;i<params.length;++i)
        {
            if($this.data('where')=='this')
                $lookin=$this;
            else
                $lookin=$($this.data('where'));
            if($lookin.find('[name="'+params[i]+'"]').val())
                obj[params[i]]=$lookin.find('[name="'+params[i]+'"]').val();
            else
                obj[params[i]]=$lookin.data(params[i]);
        }
        obj['fnc']=$this.data('target');
        $.ajax({
            url     : "includes/ajax.php",
            type    : "POST",
            data    : obj,
            dataType: "html",
            success : function (data) {
                $('.main-content').html(data);
            }
        });
        return false;
    }
    $('.main-content').on('click', '.plusemail', function(){
        console.log($(this).parent());
        $(this).parent().append('<div class="input-group">'+
                '<span class="input-group-addon">Email</span>'+
                '<input type="text" class="form-control" name="email[]" value="">'+
                '<span class="input-group-addon deleteemail btn-danger">-</span>'+
            '</div>');
    });
    $('.main-content').on('click', '.deleteemail', function(){
        console.log($(this).parent());
        $(this).parent().remove();
    });
    $('.view-site-template').click(function (){
        $('#view_site_template').find('input[name="code"]').val($(this).data("code"));
     });
    $('.create-new').click(function (){
        $('.new').find('input[name="codenew"]').val($(this).data("code"));
        $('#modalform').find('button[type="submit"]').val('Submit');
     });
    $('.main-content').on('click', '.pluspage', function(e) {
        $('<textarea name="content[' + $('#nb_textarea').val() + ']" id="content' + $(' #nb_textarea').val() + '" style="width:100%;height:400px;"></textarea>').insertAfter($('#content' + ($('#nb_textarea').val() - 1)));
        $('#nb_textarea').val(parseInt($('#nb_textarea').val()) + 1);
        refreshTiny();
    });
    $('.main-content').on('click', '.deletepage', function(e) {
        var id=$(this).parent().find('iframe').attr('id');
        if(id.length>0) id=id.substr(0,8);
        console.log(id);
        tinymce.remove('#'+id);
        $('#'+id).remove();
        e.preventDefault();
        return false;
    });
    $('#filter').focus(function () {
        $(this).val('');
    });
    $('#filter').keyup(function () {
        var v = $(this).val();
        if (v != '') {
            $('.list-sites li').each(function (index) {
                var t = $(this).text();
                if (t.indexOf(v) == -1) {
                    $(this).css('display', 'none');
                }
                else {
                    $(this).css('display', 'block');
                }
            });
        }
        else {
            $('.list-sites li').each(function (index) {
                $(this).css('display', 'block');
            });
        }
    });
};
function emptyView() {
    $('.main-content').html('');
}
function refreshTiny() {
    tinymce.init({
        selector     : "textarea",
        theme        : "modern",
        plugins      : [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor wfc"
        ],
        toolbar1     : "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        toolbar2     : "print preview media | forecolor backcolor emoticons | wfc",
        image_advtab : true,
        content_css  : "css/style.css",
        relative_urls: false
    });
    setTimeout(function() {
        $('.mce-tinymce').prepend('<button class="btn-danger deletepage" style="position:absolute; right:0;, top:0;">X</button>');
    },2000);
}
function loading() {
    $('.main-content').html('<div style="width:124px;margin:0 auto;margin-top:100px;"><span class="glyphicon glyphicon-cog"></span></div>');
}
