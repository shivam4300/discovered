$(document).ready(function () {
    let id = $('#blg_id').val();
    if (id !== '0') {
        get_blog_data(id);
    }
});

function get_blog_data(id) { 
    let formData = new FormData();
    formData.append('blog_id', id);
    manageMyAjaxPostRequestData(formData , base_url + 'article/get_blog').done(function(resp){
        if(resp.status == 1){
            if (resp.type == 'Success'){ 
                let first = resp.data_first; 
                let second = resp.data_second; 
                $('#blg_title').text(first[0]['ar_title']);
                $('#category').html(`<span class="disBlog_icon"><img src="https://test.discovered.tv/repo/images/blog_tag.svg" alt="icon"></span> ${first[0]['ar_category']}`);
                $('#author_name').text(resp.user_name);
                $('#date_created').html(`<span class="disBlog_icon"><img src="https://test.discovered.tv/repo/images/blog_calender.svg" alt="icon"></span> ${resp.time_updated}`);
                let img = second.filter(p => p.content_type == "image")
                let content = second.filter(p => p.content_type == "ckeditor")
                $('#blg_main_image').attr('src', AMAZON_URL+img[0]['content'])
                let html = '';
                var tagArr = first[0].ar_tag.split(',');
                let tag = '<li><a href="#" class="dis_artSItemF">Tags:</a></li>';
                tagArr.forEach(element => {
                    tag += `<li><a href="#" class="dis_artTagItem">${element}</a></li>`;
                });
                $('#tags').html(tag);

                second.forEach(element => {
                    if (element['content_type'] == 'image') {
                        if (img[0]['content'] !== element['content']) {
                            html += `<div><img style="width:100%;" src="${AMAZON_URL+element['content']}" /></div>`;
                        }
                    }else{
                        html += '<p  class="dis_articlePera">'+element['content']+'</p><br>';
                    }
                    
                });
                $(html).insertAfter('#blg_title');
                $('#dummy_para').hide();
            }else{

            }
        }
    }); 
 }

 $(function(){
     var lastScrollTop = 0, delta = 5;
     let counting = 0;
     $(window).scroll(function(){
        var nowScrollTop = $(this).scrollTop();
        if(Math.abs(lastScrollTop - nowScrollTop) >= delta && counting == 0){
            if (nowScrollTop > lastScrollTop){
                // ACTION ON
                // SCROLLING DOWN 
                console.log('scroll down')
            } else {
                // ACTION ON
                // SCROLLING UP 
                console.log('scroll up')
           }
        lastScrollTop = nowScrollTop;
        }
        counting += 1
    });
});