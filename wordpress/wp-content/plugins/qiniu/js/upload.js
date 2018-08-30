(function($){
    $('#uploadToQiniu').click(function(){
        $.ajax({
            type    :'POST',
            url     : ajaxurl,
            data    : {
                action: 'upload_to_qiniu',
                post_id: $(this).data('post-id')
            },
            success: (data)=> {
                if(data.success){
                    alert('上传成功!');
                }
                if(data.error){
                    alert('上传失败!');
                }
            }       
        })
    })
})(jQuery)