define(
    ['jquery', 'core/ajax', 'core/notification'],
    function($, AJAX, NOTIFICATION) {

    return {
        init: function(upload) {
            upload = $(upload);
            console.log(upload);
            upload.bind('click', this.uploadFile(upload));
    
        },  

        uploadFile: function (e) {
            var file_data = $('#chatfile').prop('files')[0];  
            if (!file_data) {	return; }
            var form_data = new FormData();                  
            form_data.append('file', file_data);              
            jQuery.ajax({
                url: 'http://localhost/local/chatfiles/ajax.php?sesskey=' + M.cfg.sesskey, // <-- point to server-side PHP script 
                dataType: 'text',  // <-- what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                         
                type: 'post',
                success: function(response){
                    alert(response);
                    textarea = jQuery('[data-region="send-message-txt"]');
                    var obj = jQuery.parseJSON( response );
                    console.log(obj.url);
                    file = '<p class="text-success font-italic"><a class="" href="' + obj.url + '"><i class="fa fa-file-o fa-2x pr-2 d-block"></i>' + obj.filename + '</a></p>';
                    newtext = textarea.val(file);
                    $('#chatfile').val("");
                    $('[data-action="send-message"]').click();
                }
             });
            //do some stuff
          },
   
    };
});
