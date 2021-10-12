define(
    ['jquery'],
    function($) {

    return {
        init: function(upload) {
            upload = $(upload);
            upload.bind('click', this.uploadFile(upload));
        },

        uploadFile: function () {
            var file_data = $('#chatfile').prop('files')[0];
            if (!file_data) { return; }
            var form_data = new FormData();
            form_data.append('file', file_data);
            $.ajax({
                url: M.cfg.wwwroot + '/local/chatfiles/ajax.php?sesskey=' + M.cfg.sesskey,
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response){
                    var textarea = $('.drawer [data-region="send-message-txt"]');
                    var obj = $.parseJSON( response );
                    console.log ( obj);
                    if (obj.error) {
                        alert(obj.error);
                        $('#chatfile').val("");
                        return;
                    }
                    var file = '<p class="text-success font-italic"><a class="" href="' + obj.url +
                     '"><i class="fa fa-file-o fa-2x pr-2 d-block"></i>' + obj.filename + '</a></p>';
                    textarea.val(file);
                    $('#chatfile').val("");
                    $('[data-action="send-message"]').click();
                }
             });
          },
    };
});
