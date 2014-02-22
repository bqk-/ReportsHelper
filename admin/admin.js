$(function(){
    $('table input[type="checkbox"]').click(function(e){
        e.preventDefault();
                      console.log($(this).data('value'));
        $.ajax({
              type: "POST",
              context: $(this),
              url: "updateproperty.php",
              data: { path: $(this).data('path'), option: 'auto', value: $(this).is(":checked")},
              dataType: 'text',
              success: function(data){
                    if($(this).is(":checked"))
                        $(this).prop('checked', false);
                    else
                        $(this).prop('checked', true);
              }
            });
    });

    $('.validAWR').click(function(){
        $.ajax({
              type: "POST",
              context: $(this),
              url: "moveawr.php",
              data: {file: $(this).data('awr'), path: $(this).data('path')},
              dataType: 'text',
              success: function(data){
                  $(this).parent().append('<img src="data:image/png;base64,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" />');
                  $(this).remove();
              }
        });
    });
});