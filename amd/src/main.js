define(
    ['jquery'],
    function($) {

    return {
        init: function(upload) {
            upload = $(upload);
            upload.bind('click', this.uploadFile(upload));
        },

        uploadFile: function (id) {
            let file_data;
            let drawer = 0;
            if ($('[id^=drawer] #chatfile').prop('files')[0]) {
                file_data = $('[id^=drawer] #chatfile').prop('files')[0];
                drawer = 1;
            } else {
                file_data = $('#page #chatfile').prop('files')[0];
                drawer = 2;
            }

            if (!file_data) { return; }
            var form_data = new FormData();
            form_data.append('file', file_data);
            var username = $('[data-region="view-conversation"] [data-region="header-content"] strong.text-truncate').text()
            form_data.append('name', username);
            form_data.append('drawer', drawer);
            $.ajax({
                url: M.cfg.wwwroot + '/local/chatfiles/ajax.php?sesskey=' + M.cfg.sesskey,
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response){
                    var textarea;
                    var obj = $.parseJSON( response );
                    if (obj.drawer == 1) {
                        textarea = $('[id^=drawer] [data-region="send-message-txt"]');
                    } else if (obj.drawer == 2) {
                        textarea = $('#page [data-region="send-message-txt"]');
                    }

                    if (obj.error) {
                        alert(obj.error);
                        $('#chatfile').val("");
                        return;
                    }
                    var file = '<p class="text-success font-italic"><a class="" href="' + obj.url +
                     '"><i class="fa fa-file-o fa-2x pr-2 d-block"></i>' + obj.filename + '</a></p>';
                    textarea.val(file);
                    if (obj.drawer == 1) {
                        $('[id^=drawer] #chatfile').val("");
                        $('[data-action="send-message"]').click();
                    }
                    else if (obj.drawer == 2) {
                        $('#page #chatfile').val("");
                        $('#page [data-action="send-message"]').click();
                    }

                }
             });
          },
    };
});
