window.onload = function () {
    refreshTiny();
    var loaded = '';
    $('.template_view').click(function () {
        loading();
        $.ajax({
            url     : "templates/" + $(this).data('name') +'?t='+Math.random(),
            type    : "GET",
            dataType: "html",
            success : function (data) {
                $('.main-content').html(data);
            }
        });
        return false;
    });
    $('.template_edit').click(function () {
        loading();
        $.ajax({
            url     : "includes/new.php",
            type    : "GET",
            data    : {'name': $(this).data('name')},
            dataType: "html",
            success : function (data) {
                $('.main-content').html(data);
            }
        });
        return false;
    });
    $('.edit-site-template').click(function () {
        loading();
        $.ajax({
            url     : "includes/new.php",
            type    : "GET",
            data    : {'code': $(this).data('code')},
            dataType: "html",
            success : function (data) {
                $('.main-content').html(data);
            }
        });
        return false;
    });
    $('.addTextarea').click(function (e) {
        console.log('HERE');
        //$('<textarea name="content[' + $('#nb_textarea').val() + ']" id="content' + $('#nb_textarea').val() + '" style="width:100%;height:400px;"></textarea>').insertAfter($('#content' + ($('#nb_textarea').val() - 1)));
        //$('#nb_textarea').val(parseInt($('#nb_textarea').val()) + 1);
        //console.log('<textarea name="content[' + $('#nb_textarea').val() + ']" id="content' + $('#nb_textarea').val() + '" style="width:100%;height:400px;"></textarea>');
        //refreshTiny();
        e.preventDefault();
        return false;
    });
    $('#overlay').click(function () {
        $('.popup').css('display', 'none');
        $(this).css('display', 'none');
        return false;
    });
    /*var options = {
     // other available options:
     url     : "includes/new.php",
     dataType: "html",      // 'xml', 'script', or 'json' (expected server response type)
     //clearForm: true        // clear all form fields after successful submit
     success : function (data) {
     $('#right-wrapper').html(data);
     }
     };
     $('.new').submit(function () {
     loading();
     $(this).ajaxSubmit(options);
     $('#overlay').css('display', 'none');
     $('.popup').css('display', 'none');
     return false;
     });*/
    /*    var options2;
     $('.view_data').submit(function () {
     $('#' + $(this).data('code')).find('.tplOnly').css('display', 'none');
     if (options2['noajax']) {
     return true;
     }
     loading();
     $(this).ajaxSubmit(options2);
     $('#overlay').css('display', 'none');
     $('.popup').css('display', 'none');
     return false;
     });*/
     $('.tplOnly').click(function () {
         loading();
         $('#view_site_template').modal('hide');
         var id = $('#view_site_template').find('input[name="code"]').val();
         $.ajax({
             url     : "./" + id + "/template.tpl?t="+Math.random(),
             type    : "GET",
             dataType: "html",
             success : function (data) {
                $('.main-content').html(data);
                }
         });
         $('#overlay').css('display', 'none');
         $('.popup').css('display', 'none');
         return false;
     });
     $('.view-site-template, .export-site-template').click(function (){
        $('#view_site_template').find('input[name="code"]').val($(this).data("code"));
     });
       $('.tplView').click(function () {
        loading();
        $('#view_site_template').modal('hide');
         var id = $('#view_site_template').find('input[name="code"]').val();
         $.ajax({
             url     : "./ajax.parser.php",
             type    : "POST",
             data    : {
                'code':id,
                'month':$('#view_site_template').find('select[name="month"]').val(),
                'year':$('#view_site_template').find('select[name="year"]').val(),
             },
             dataType: "html",
             success : function (data) {
                $('.main-content').html(data);
                }
         });
        return false;
    });
        $('.exportPdf').click(function () {
            $('#view_site_template').modal('hide');
            $('#form_').attr('action',$('#form_').attr('action')+'='+$('#view_site_template').find('input[name="code"]').val());
            $('#form_').submit();
            /*
            loading();
            $('#view_site_template').modal('hide');
             var id = $('#view_site_template').find('input[name="code"]').val();
             $.ajax({
                 url     : "./includes/export.php",
                 type    : "POST",
                 data    : {
                    'code':id,
                    'month':$('#view_site_template').find('select[name="month"]').val(),
                    'year':$('#view_site_template').find('select[name="year"]').val(),
                 },
                 dataType: "html",
                 success : function (data) {
                    $('.main-content').html(data);
                    }
             });
            return false;
            */
        });
        $('.sendPdf').click(function () {
         loading();
         $('#view_site_template').modal('hide');
         var id = $('#view_site_template').find('input[name="code"]').val();
         $.ajax({
             url     : "./includes/email.php",
             type    : "POST",
             data    : {
                'code':id,
                'month':$('#view_site_template').find('select[name="month"]').val(),
                'year':$('#view_site_template').find('select[name="year"]').val(),
             },
             dataType: "html",
             success : function (data) {
                $('.main-content').html(data);
                }
         });
         return false;
     });
     
    $('.delete-site-template').click(function () {
        var $this=$(this);
        bootbox.confirm('Are you sure you want to delete this ?', function(result) {
            if(result) {
                $.ajax({
                context : $this,
                url     : "./src/delete.php",
                type    : "GET",
                data    : {'site': $this.data('code')},
                dataType: "html",
                success : function (data) {
                    var code = $(this).data('code');
                    $(this).parent().html('');
                    $(this).parent().append(' <a href="#" data-code="'+code+'" data-toggle="modal" class="create-new" data-target="#create_new">New</a>');
                }
            });
        }
    });
    });
    
    $('.template_delete').click(function () {
        var $this=$(this);
        bootbox.confirm('Are you sure you want to delete this ?', function(result) {
        if(result) {
            $.ajax({
                context : $this,
                url     : "./src/delete.php",
                type    : "GET",
                data    : {'tpl': $this.data('name')},
                dataType: "html",
                success : function (data) {
                    emptyView();
                    $(this).parent().parent().parent().remove();
                }
            });
        }
    });
    });
  
    $('.create_new').click(function () {
         $.ajax({
             context : this,
             url     : "./includes/new.php",
             type    : "GET",
             data    : {'create_new': true},
             dataType: "html",
             success : function (data) {
                $('.main-content').html(data);
             }
         });
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
function popupsite(id) {
    $('#overlay').css('display', 'block');
    $('#' + id).css('display', 'block');
    return false;
}
/*function edit(id) {
 loading();
 $.ajax({
 url     : "./includes/new.php",
 type    : "GET",
 data    : {'code': id},
 dataType: "html",
 success : function (data) {
 $('#right-wrapper').html(data);
 }
 });
 }*/
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
}
function loading() {
    $('.main-content').html('<div style="width:124px;margin:0 auto;margin-top:100px;"><img src="images/loading.gif" /><br /><br />Loading...</div>');
}

$(function () {
    $('.create-new').on("click", function () {
        $('form.new input#url').val($(this).closest(".wfc-web-property").find('.wfc-property-url').text());
        $('form.new input#code').val($(this).data('code'));

    });
    $('.view_data').on("click", function () {
        $('form.view_data input#code').val($(this).data('code'));

    });

    $('form.new').on('submit', function (event) {
        $('#create_new').modal('hide');
        $form=$(this);
        var $target = $($form.attr('data-target'));
        event.preventDefault();
        $(this).find(':input:disabled').removeAttr('disabled');
        $.post('includes/new.php', $(this).serialize(),function (data) {
                $('.main-content').html(data);
                 toastr.info('New Template Created!');
            },'html');
    });
    /*
        $.ajax({
            context : this,
            url     : "./includes/new.php",
            type    : "GET",
            data    : {'create_new': true},
            dataType: "html",
            success : function (data) {
                $('.main-content').html(data);
                // Display an info toast with no title
                toastr.info('New Template Created!')
            }
        });
        event.preventDefault();
    });
    */
});