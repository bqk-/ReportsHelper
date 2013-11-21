window.onload=function (){
    refreshTiny();
    var loaded='';
    $('.template_link').click(function(){
        loading();
        $.ajax({
            url: "templates/"+$(this).data('name'),
            type: "GET",
            dataType: "html",
            success: function (data) {
                $('#right-wrapper').html(data);
            }
        });
        return false;
    });

    $('.template_edit').click(function(){
        loading();
        $.ajax({
            url: "includes/new.php",
            type: "GET",
            data:{'name':$(this).data('name')},
            dataType: "html",
            success: function (data) {
                $('#right-wrapper').html(data);
            }
        });
        return false;
    });

    $('.addTextarea').click(function() {
        $('<textarea name="content['+$('#nb_textarea').val()+']" id="content'+$('#nb_textarea').val()+'" style="width:100%;height:400px;"></textarea>').insertAfter($('#content'+($('#nb_textarea').val()-1)));
        $('#nb_textarea').val(parseInt($('#nb_textarea').val())+1);
        refreshTiny();
        return false;
    });

    $('#overlay').click(function() {
        $('.popup').css('display','none');
        $(this).css('display','none');
        return false;
    });

    var options = { 
            // other available options: 
            url:      "includes/new.php",
            dataType:  "html"  ,      // 'xml', 'script', or 'json' (expected server response type) 
            //clearForm: true        // clear all form fields after successful submit 
            success: function(data){
                $('#right-wrapper').html(data);
            }
        }; 
    $('.new').submit(function(){
        loading();
        $(this).ajaxSubmit(options);
        $('#overlay').css('display','none');
        $('.popup').css('display','none');
        return false;
    });
    var options2;
    $('.view_data').submit(function(){
        $('#'+$(this).data('code')).find('.tplOnly').css('display','none');
        if(options2['noajax'])
            return true;
        loading();
        $(this).ajaxSubmit(options2);
        $('#overlay').css('display','none');
        $('.popup').css('display','none');
        return false;
    });

    $('.tplOnly').click(function(){
        loading();
        var id=$(this).parent().find('input[name="code"]').val();
        $.ajax({
            url: "./"+id+"/template.tpl",
            type: "GET",
            dataType: "html",
            success: function(data) {            
                $('#right-wrapper').html(data);
            }
        });
        $('#overlay').css('display','none');
        $('.popup').css('display','none');
        return false;
    });

    $('.delete_site').click(function(){
        $.ajax({
            context: this,
            url: "./src/delete.php",
            type: "GET",
            data: {'site':$(this).data('code')},
            dataType: "html",
            success: function(data) {            
                $(this).parent().remove();
            }
        });
        return false;
    });

    $('.delete_tpl').click(function(){
        $.ajax({
            context: this,
            url: "./src/delete.php",
            type: "GET",
            data: {'tpl':$(this).data('name')},
            dataType: "html",
            success: function(data) {            
                $(this).parent().remove();
            }
        });
        return false;
    });

    $('.export_pdf').click(function() {
        $('#'+$(this).data('code')).find('.tplOnly').css('display','none');
        $('#'+$(this).data('code')).css('display','block');
        $('#overlay').css('display','block');
        options2 = { 
            // other available options: 
            noajax:true
        }; 
        return false;
    });

    $('.viewtemplate').click(function() {
        $('#'+$(this).data('code')).css('display','block');
        $('#overlay').css('display','block');
        options2 = { 
            // other available options: 
            url:      "ajax.parser.php?"+Math.random(),
            dataType:  "html"  ,      // 'xml', 'script', or 'json' (expected server response type) 
            //clearForm: true        // clear all form fields after successful submit 
            success: function(data){
                $('#right-wrapper').html(data);
            }
        }; 
        return false;
    });

    $('.create_new').click(function() {
        $.ajax({
            context: this,
            url: "./includes/new.php",
            type: "GET",
            data: {'create_new':true},
            dataType: "html",
            success: function(data) {            
                $('#right-wrapper').html(data);
            }
        });
        return false;
    });
};
function emptyView()
{
    $('#right-wrapper').html('');
}
function popupsite(id)
{
    $('#overlay').css('display','block');
    $('#'+id).css('display','block');
    return false;
}
function edit(id)
{
    loading();
    $.ajax({
        url: "./includes/new.php",
        type: "GET",
        data: {'code':id},
        dataType: "html",
        success: function(data) {            
            $('#right-wrapper').html(data);
        }
    });
}
function refreshTiny()
{
    tinymce.init({
            selector: "textarea",
            theme: "modern",
            plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality",
                "emoticons template paste textcolor wfc"
            ],
            toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
            toolbar2: "print preview media | forecolor backcolor emoticons | wfc",
            image_advtab: true,
            content_css : "style.css",
            relative_urls: false
        });
}
function loading()
{
    $('#right-wrapper').html('<div style="width:124px;margin:0 auto;margin-top:100px;"><img src="images/loading.gif" /><br /><br />Loading...</div>');
}