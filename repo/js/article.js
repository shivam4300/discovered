$(document).ready(function () {
    let page = $('#page').val()
    let id = $('#post_id').val()
    let mode = $('#mode').val()

    function socialWindow(url) {
        var left = (screen.width - 570) / 2;
        var top = (screen.height - 570) / 2;

        var params = "menubar=no,toolbar=no,status=no,width=570,height=570,top=" + top + ",left=" + left;

        window.open(url, "NewWindow", params);
    }
    // social links share function
    function setShareLinks() {
        var pageUrl = encodeURIComponent(document.URL);
        var tweet = encodeURIComponent($("meta[property='og:description']").attr("content"));
        let link = ''
        $(document).on("click", ".social-share.facebook", function () {
            link = base_url + 'article/' + $(this).attr('data-slug')
            url = "https://www.facebook.com/sharer.php?u=" + link;
            socialWindow(url);
        });

        $(document).on("click", ".social-share.twitter", function () {
            link = base_url + 'article/' + $(this).attr('data-slug')
            url = "https://twitter.com/intent/tweet?url=" + link;
            socialWindow(url);
        });

        $(document).on("click", ".social-share.linkedin", function () {
            link = base_url + 'article/' + $(this).attr('data-slug')
            url = "https://www.linkedin.com/shareArticle?mini=true&url=" + link;
            socialWindow(url);
        })

        $(document).on("click", ".social-share.instagram", function () {
            link = base_url + 'article/' + $(this).attr('data-slug')
            url = "https://www.linkedin.com/shareArticle?mini=true&url=" + link;
            socialWindow(url);
        })

        $(document).on("click", ".social-share.link", function () {

            var link = base_url + 'article/' + $(this).attr('data-slug');
            // Copy the URL to the clipboard
            navigator.clipboard.writeText(link).then(function () {
                // Success callback
                Custom_notify('success', 'Link copied to clipboard!');
            }).catch(function (error) {
                // Error callback
                console.error('Failed to copy the link: ', error);
            });
        });

    }

    // for blogs create step 1
    if (page == 'create_blog_1') {
        get_categories();

        if ($('#post_id').val() !== '0') {
            let formData = new FormData()
            formData.append('post_id', $('#post_id').val())
            manageMyAjaxPostRequestData(formData, base_url + 'articles/get_step1_data').done(function (resp) {
                if (resp.status == 1) {
                    if (resp.type == 'Success') {
                        $(document).prop('title', 'Discovered | Edit Article');
                        setTimeout(() => {
                            $('#first_step_head').text('Edit Article')
                            $('#art_title').val(resp.data[0].ar_title)
                            $('#art_auth_name').val(resp.data[0].ar_author_name)
                            $('#category_select option[value=' + resp.data[0].id + ']').prop('selected', 'selected').change()
                            // $('#category_select').val(resp.data[0].id).change()
                            $('#tokenfield').tokenfield('setTokens', resp.data[0].ar_tag)
                            $('#privacy_status').val(resp.data[0].privacy_status).change()
                            $('#article_type').val(resp.data[0].article_type).change()
                        }, 500);
                    } else {
                        window.location.href = base_url + "articles/step1";
                    }
                }
            });
        }
    }


    // for article single page
    if (page == 'article_single' || page == 'article_slider') {
        setShareLinks();
        $('#footer').hide()
        showCategories()

        AddAdsOnSinglePageOnTheSidebar();

        setTimeout(() => AddSheMediaAdsOnSingleArticleOnTheTop('#content', 'append'));

        setTimeout(() => {
            if (id !== '0') {
                $('.dis_articlesWrap').addClass('dis_articleSingle');
                let k = $('#add_new_btn, #articles_filter');
                let l = $('#top_in_cat_div');
                l.hide();

                if (mode == 'blogs_by_id') {
                    getMostPopularData();
                    getBlogCategoryTops(id, 'blog_id')
                    blogViewsCount();
                    l.show();
                } else if (mode == 'blogs_by_username') {
                    getMostPopularData()
                    if (user_uname == id) k.removeClass('hideme'); else k.addClass('hideme');
                } else if (mode == 'blogs_by_category_id') {
                    getBlogCategoryTops(id, 'by_categ_name');
                    l.show()
                }


                loadArticles(0, 0);

                if (current_page === 'article_slider' && U_B_id !== '0') {
                    $('#content').append(articleLoaderBlock())
                    let type = (ar_mode == 'blogs_by_id') ? 'blog_id' : (ar_mode == 'blogs_by_username' ? 'user_id' : (ar_mode == 'blogs_by_category_id' ? 'categ' : 'tag'));

                    getBlogDataSlider(U_B_id, type).then(function () {
                        AddSheMediaArticleAdsOnSinglePage('#content', 'append');
                        ControlRequest1 = true;
                        create_slide();
                    });
                }
            }
        }, 2000)
    }
    // for preview blog
    if (page == 'preview_blog') {
        if (id !== '0') {
            getBlogPrviewData(id)
        }
    }

    // for main i.e. article mode
    if (page == 'article_mode') {
        getAllCategForHeader()
        getBlockOneData(0, true)
        articleModeClickFuncInit()
        setTimeout(() => {
            checkArrowForMenu()
        }, 2000);
    }

});

// ****************************** FOR Article search start ************************************ //

let before_search_content = ''

$(document).on('keyup', '#art_search', function (e) {
    let keywords = $.trim($(this).val())

    let key = e.keyCode;
    if (key == 13) {
        if (keywords.length !== 0) {
            window.location.assign($('.list-group-item-action.active').attr('data-link'));
        } else {
            $(appendElement).hide()
        }

    }

})

$(document).on('click', 'html:not(#appendUl)', function (event) {
    $('#appendUl').hide()
})

$(document).on('mouseover', '#appendUl li', function (event) {
    let ths = $(this);
    ths.parent().find('.active').removeClass("active");
})

$(document).on('click', '#appendUl li', function (event) {
    let ths = $(this);
    ths.parent().hide();
    let storeTarget = ths.focus().addClass("active");
    $('#art_search').val(storeTarget.text());
    window.location.assign(ths.attr('data-link'));
})

// ****************************** FOR Article search start ************************************ //

// ****************************** FOR Article loader start ************************************ //

function articleLoaderBlock() {
    return false;
    // let html = `<div data-container="loader" class="dis_articleMBox m_b_30">
    //                 <div class="dis_Ainline1">
    //                     <ul class="dis_articleList DAS2 DA-inline">
    //                         <div class="dis_postAnimation articles">
    //                             <div class="dis_postAnimation_header dis_skeletonBB">
    //                             <div class="dis_pAnimation_hLeft">
    //                             <div class="dis_skeletonCircle"></div>
    //                             </div>
    //                             <div class="dis_pAnimation_hRIght">
    //                                 <div>
    //                                     <div class="dis_skeleton_line dsl_15 dsmb_10"></div>
    //                                     <div class="dis_skeleton_line dsl_15"></div>
    //                                 </div>
    //                                     <div class="dis_skeletonCircle"></div>
    //                                 </div>
    //                             </div>
    //                             <div class="dis_postAnimation_body dis_skeletonBB">
    //                                 <div class="dis_skeletonRectangle dsmb_10"></div>
    //                                 <div class="dis_skeleton_line dsl_15 dsmb_10"></div>
    //                                 <div class="dis_skeleton_line dsl_15 dsmb_10"></div>
    //                                 <div class="dis_skeleton_line dsl_15"></div>
    //                             </div>
    //                         </div>
    //                     </ul>
    //                 </div>
    //             </div>`
    // return html
}

// ****************************** FOR Article loader end   ************************************ //

// ****************************** FOR Article mode start ************************************ //
let target_objt = ''
function articleModeClickFuncInit() {

    $(document).on('click', '.dis_artFIlterMenu', function (e) {
        target_objt = $(this)
        $('.dis_artFIlterMenu').removeClass('active')
        target_objt.addClass('active')
        let catg = target_objt.attr('data-cat_id')
        $('#content').html(articleLoaderBlock())
        $('#sidebar').html(articleLoaderBlock())
        getBlockOneData(catg)
        getAdminSlider(0, catg)
        getAdminSidebarSlider(0, catg)
        sc_slider = 1
        sc_sidebar = 1
        ControlRequest1 = true;

    })

    $('.dis_artmenuControls.left').click(function () {
        $(".ar_topCategories").animate({ scrollLeft: "-=150px" }, 200);
        addRemoveArrow()
    });

    $('.dis_artmenuControls.right').click(function () {
        $(".ar_topCategories").animate({ scrollLeft: "+=150px" }, 200);
        addRemoveArrow()
    });

}

if ($('#page').val() == 'article_mode') {

    $(window).on('resize', function () {
        checkArrowForMenu()
    });
}

function checkArrowForMenu() {                        // checking arrow by window size
    if ($('.dis_artFIlterListWrap').width() < $(".dis_artFIlterList li:last-child").offset()?.left) {
        $('.dis_artFIlterListWrap').addClass('right_show')
    } else {
        $('.dis_artFIlterListWrap').removeClass('right_show')
    }
}

function addRemoveArrow() {
    setTimeout(() => {
        let l_arrow = $('.dis_artmenuControls.left').offset().left    // left arrow position
        let first_element_pos = $('.dis_artFIlterListWrap li:first').offset().left // first element position

        if (first_element_pos > l_arrow) {
            $('.dis_artFIlterListWrap').removeClass('left_show')
        } else {
            $('.dis_artFIlterListWrap').addClass('left_show')
        }

        let r_arrow = $('.dis_artmenuControls.right').offset().left    // right arrow position
        let last_element_pos = $('.dis_artFIlterListWrap li:last').offset().left // last element position

        if (last_element_pos < r_arrow) {
            $('.dis_artFIlterListWrap').removeClass('right_show')
        } else {
            $('.dis_artFIlterListWrap').addClass('right_show')
        }

    }, 500);
}


function getAdminSidebarSlider(order, categ_id) {               // admin sidebar slider
    let myPromise = new Promise((resolve, reject) => {
        let formData = new FormData()
        formData.append('order', order)
        formData.append('category_id', categ_id)
        manageMyAjaxPostRequestData(formData, base_url + 'articles/getAdminSidebarSlider').done(function (resp) {
            if (resp.status == 1 && resp.type == 'Success') {
                let data = resp.data, html1 = '', html2 = '', html3 = '', html4 = '', count = 1, category = '', on_error_img = '';
                let divs = '';
                $.each(data, function (key, element) {

                    if (element.slider_title == 'MOST POPULAR') {

                        $.each(element.slider_data, function (k, item) {
                            if(item.publish_status == 1) {
                                if (item.content_type != 'image') {
                                    img = base_url + 'repo/images/blog_pp.png';
                                } else {
                                    img = AMAZON_URL + addKeyWordInName(item.content, '_thumb') + '.webp';
                                    on_error_img = AMAZON_URL + item.content;
                                }

                                html1 += `<li>
                                            <div class="dis_articleBox">
                                                <a class="dis_articleImg">
                                                    <img src="${img}" onerror="this.onerror=null;this.src='${on_error_img}'" alt="images" title="image" loading="lazy">
                                                </a>
                                                <div class="dis_articleDetails">
                                                    <a href="${base_url + 'article/?category=' + item.cat_name}" class="dis_articleCat" >${item.cat_name}</a><br>
                                                    <h1 class="dis_articleT"><a href="${base_url + 'article/' + item.encoded_id + '/' + item.ar_slug}" title="${item.ar_title}" class="dis_articleTtl">${item.ar_title}</a></h1>
                                                    <div class="dis_articleTtlMeta"> <span class="dis_articleTtlAuthor"> <svg class="dis_articleTtlAuthorI" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21 20.75"><path d="M17.92 13.08a10.38 10.38 0 00-3.17-2.18 6.278 6.278 0 002.02-4.62c0-3.46-2.82-6.28-6.28-6.28S4.22 2.82 4.22 6.28c0 1.76.74 3.43 2.02 4.62a10.38 10.38 0 00-3.17 2.18c-1.98 1.98-3.08 4.87-3.08 7.67h2.57c0-4.38 3.56-8.19 7.94-8.19s7.94 3.81 7.94 8.19H21c0-2.8-1.09-5.69-3.08-7.67zm-3.71-6.79c0 2.05-1.67 3.72-3.72 3.72S6.77 8.34 6.77 6.29s1.67-3.72 3.72-3.72 3.72 1.67 3.72 3.72z"/></svg> ${item.user_name}</span> <!-- <span class="dis_articleTtlDate">${getTimeAgo1(item?.ar_date_created)}</span> --> </div>
                                                </div>
                                            </div>
                                        </li>`
                            }
                        })
                    } else
                        if (element.slider_title == 'TOP IN') {
                            count = 1;
                            $.each(element.slider_data, function (k, item) {
                                html2 += `<li>
                                        <div class="dis_articleBox">
                                            <a href="${base_url + 'article/' + item.encoded_id + '/' + item.ar_slug}" class="dis_articleImg">
                                                #0${count}
                                            </a>
                                            <div class="dis_articleDetails">
                                                <a href="${base_url + 'article/' + item.encoded_id + '/' + item.ar_slug}" title="${item.ar_title}" class="dis_articleTtl">${item.ar_title}</a>
                                                <div class="dis_articleTtlMeta"> <span class="dis_articleTtlAuthor"> <svg class="dis_articleTtlAuthorI" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21 20.75"><path d="M17.92 13.08a10.38 10.38 0 00-3.17-2.18 6.278 6.278 0 002.02-4.62c0-3.46-2.82-6.28-6.28-6.28S4.22 2.82 4.22 6.28c0 1.76.74 3.43 2.02 4.62a10.38 10.38 0 00-3.17 2.18c-1.98 1.98-3.08 4.87-3.08 7.67h2.57c0-4.38 3.56-8.19 7.94-8.19s7.94 3.81 7.94 8.19H21c0-2.8-1.09-5.69-3.08-7.67zm-3.71-6.79c0 2.05-1.67 3.72-3.72 3.72S6.77 8.34 6.77 6.29s1.67-3.72 3.72-3.72 3.72 1.67 3.72 3.72z"/></svg> ${item.user_name}</span> <!-- <span class="dis_articleTtlDate">${getTimeAgo1(item?.ar_date_created)}</span> --> </div>
                                            </div>
                                        </div>
                                    </li>`;
                                category = item.cat_name
                            })
                            count++;
                        } else
                            if (element.slider_title == 'CATEGORIES') {
                                $.each(element.slider_data, function (k, item) {
                                    html3 += `<li>
                                        <div class="dis_articleBgBox">
                                            <div class="dis_articleBImg">
                                                <img src="${base_url + 'repo_admin/images/blog_cate/' + item.cat_img}" onerror="this.onerror=null;this.src='${base_url}repo/images/blog_pp.png'" alt="images" title="image" loading="lazy">
                                            </div>
                                            <a href="${base_url + 'article?category=' + item.cat_name}" class="dis_articleBDetails">
                                                <span class="dis_articleBgD">${item.cat_name}</span>
                                                <span class="dis_articleBgBadge">${item.total}</span>
                                            </a>
                                        </div>
                                    </li>`
                                })
                            } else {

                                $.each(element.slider_data, function (k, item) {
                                    if(item.publish_status == 1) {
                                        if (item.content_type != 'image') {
                                            img = base_url + 'repo/images/blog_pp.png';
                                        } else {
                                            img = AMAZON_URL + addKeyWordInName(item.content, '_thumb') + '.webp';
                                            on_error_img = AMAZON_URL + item.content;
                                        }

                                        html4 += `<li>
                                            <div class="dis_articleBox">
                                                <a class="dis_articleImg">
                                                    <img src="${img}" onerror="this.onerror=null;this.src='${on_error_img}'" alt="images" title="image" loading="lazy">
                                                </a>
                                                <div class="dis_articleDetails">
                                                    <a href="${base_url + 'article/?category=' + item.cat_name}" class="dis_articleCat" >${item.cat_name}</a><br>
                                                    <h1 class="dis_articleT"><a href="${base_url + 'article/' + item.encoded_id + '/' + item.ar_slug}" title="${item.ar_title}" class="dis_articleTtl">${item.ar_title}</a></h1>
                                                    <div class="dis_articleTtlMeta"> <span class="dis_articleTtlAuthor"> <svg class="dis_articleTtlAuthorI" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21 20.75"><path d="M17.92 13.08a10.38 10.38 0 00-3.17-2.18 6.278 6.278 0 002.02-4.62c0-3.46-2.82-6.28-6.28-6.28S4.22 2.82 4.22 6.28c0 1.76.74 3.43 2.02 4.62a10.38 10.38 0 00-3.17 2.18c-1.98 1.98-3.08 4.87-3.08 7.67h2.57c0-4.38 3.56-8.19 7.94-8.19s7.94 3.81 7.94 8.19H21c0-2.8-1.09-5.69-3.08-7.67zm-3.71-6.79c0 2.05-1.67 3.72-3.72 3.72S6.77 8.34 6.77 6.29s1.67-3.72 3.72-3.72 3.72 1.67 3.72 3.72z"/></svg> ${item.user_name}</span> <!-- <span class="dis_articleTtlDate">${getTimeAgo1(item?.ar_date_created)}</span> --> </div>
                                                </div>
                                            </div>
                                        </li>`
                                    }
                                })

                            }


                    if (element.slider_title == 'MOST POPULAR') {
                        divs += getMostPopularSidebar(element.slider_title, html1)
                    } else
                        if (element.slider_title == 'TOP IN') {
                            divs += getTopInCategSidebar(element.slider_title, html2, category)
                        } else
                            if (element.slider_title == 'CATEGORIES') {
                                divs += getCategSidebar(element.slider_title, html3)
                            } else {
                                divs += getMostPopularSidebar(element.slider_title, html4)
                            }

                });

                $('[data-container="loader"]').remove()
                $('#sidebar').append(divs);

                resolve()
            } else {
                $('[data-container="loader"]').remove()
                reject()
            }

        })
    })
    return myPromise;
}

function getMostPopularSidebar(slider_title, content) {
    slider_title = slider_title.toLowerCase().replace(/\b[a-z]/g, function (letter) {
        return letter.toUpperCase();
    });
    let html = `<div class="dis_articleMBox m_b_30">
                    <div class="dis_articleLRH m_b_20">
                        <h2 class="dis_articleHeading">${slider_title}</h2>
                        <!-- <a href="javascript:;" class="dis_articleHeadView">view all
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 14" width="9" height="12"><path fill-rule="evenodd" fill="rgb(235 88 31)" id="Arrow" class="shp0" d="M8.41 7C8.41 7.2 8.33 7.4 8.19 7.54L2.12 13.78C1.98 13.92 1.8 14 1.6 14C1.4 14 1.21 13.92 1.07 13.78L0.62 13.32C0.48 13.17 0.41 12.98 0.41 12.78C0.41 12.57 0.48 12.38 0.62 12.23L5.72 7L0.63 1.77C0.34 1.47 0.34 0.98 0.63 0.68L1.08 0.22C1.22 0.08 1.4 0 1.6 0C1.8 0 1.99 0.08 2.13 0.22L8.19 6.45C8.33 6.6 8.41 6.79 8.41 7Z"></path></svg>
                            </span>
                        </a> -->
                    </div>
                    <ul class="dis_articleList DAS2 DAS2_mini">
                        ${content}
                    </ul>
                </div>`
    return html
}

function getTopInCategSidebar(slider_title, content, category) {
    slider_title = slider_title.toLowerCase().replace(/\b[a-z]/g, function (letter) {
        return letter.toUpperCase();
    });
    let html = `<div class="dis_articleNumberWrap dis_articleMBox m_b_30">
                    <div class="dis_articleLRH m_b_20">
                        <h2 class="dis_articleHeading">${slider_title + ' ' + category}</h2>
                        <!-- <a href="javascript:;" class="dis_articleHeadView">view all
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 14" width="9" height="12"><path fill-rule="evenodd" fill="rgb(235 88 31)" id="Arrow" class="shp0" d="M8.41 7C8.41 7.2 8.33 7.4 8.19 7.54L2.12 13.78C1.98 13.92 1.8 14 1.6 14C1.4 14 1.21 13.92 1.07 13.78L0.62 13.32C0.48 13.17 0.41 12.98 0.41 12.78C0.41 12.57 0.48 12.38 0.62 12.23L5.72 7L0.63 1.77C0.34 1.47 0.34 0.98 0.63 0.68L1.08 0.22C1.22 0.08 1.4 0 1.6 0C1.8 0 1.99 0.08 2.13 0.22L8.19 6.45C8.33 6.6 8.41 6.79 8.41 7Z"></path></svg>
                            </span>
                        </a> -->
                    </div>
                    <ul class="dis_articleList DAS2 DAS2_mini dis_Anumber">
                        ${content}
                    </ul>
                </div>`
    return html
}

function getCategSidebar(slider_title, content) {
    slider_title = slider_title.toLowerCase().replace(/\b[a-z]/g, function (letter) {
        return letter.toUpperCase();
    });
    let html = `<div class="dis_articleMBox m_b_30">
                    <div class="dis_articleLRH m_b_20">
                        <h2 class="dis_articleHeading m_b_30">${slider_title}</h2>
                        <!-- <a href="javascript:;" class="dis_articleHeadView">view all
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 14" width="9" height="12"><path fill-rule="evenodd" fill="rgb(235 88 31)" id="Arrow" class="shp0" d="M8.41 7C8.41 7.2 8.33 7.4 8.19 7.54L2.12 13.78C1.98 13.92 1.8 14 1.6 14C1.4 14 1.21 13.92 1.07 13.78L0.62 13.32C0.48 13.17 0.41 12.98 0.41 12.78C0.41 12.57 0.48 12.38 0.62 12.23L5.72 7L0.63 1.77C0.34 1.47 0.34 0.98 0.63 0.68L1.08 0.22C1.22 0.08 1.4 0 1.6 0C1.8 0 1.99 0.08 2.13 0.22L8.19 6.45C8.33 6.6 8.41 6.79 8.41 7Z"></path></svg>
                            </span>
                        </a> -->
                    </div>
                    <ul class="dis_bgartiList">
                        ${content}
                    </ul>
                </div>`
    return html
}

function getAdminSlider(order, categ_id) {
    let myPromise = new Promise((resolve, reject) => {
        let formData = new FormData()
        formData.append('order', order)
        formData.append('category_id', categ_id)
        manageMyAjaxPostRequestData(formData, base_url + 'articles/getAdminSlider').done(function (resp) {

            if (resp.status == 1 && resp.type == 'Success') {

                let data_v = resp.slider_articles
                let hero_img = ''
                let pkd_content_one = ''
                let pkd_content_two = ''
                let count = 0
                let sld_title = resp.slider_title
                let slider_html = ''
                let slider = ''
                let pkd_html = ''
                let pkd_status = false
                let on_error_img = ''

                if (sld_title == 'RECOMMENDED FOR YOU') {

                    let recomHtm = ''
                    let hero_img = ''
                    let element = ''
                    $.each(data_v, function (key, item) {
                        element = item[0]
                        if(element.publish_status == 1) {
                            if (element.content_type == 'image') { hero_img = AMAZON_URL + addKeyWordInName(element.content, '_thumb') + '.webp'; on_error_img = AMAZON_URL + element.content } else { hero_img = base_url + 'repo/images/blog_pp.png'; }

                            recomHtm += `<div class="swiper-slide">
                                                <div class="DAS2 DA-block">
                                                    <div class="dis_articleBox">
                                                        <a href="${base_url + 'article/' + element.encoded_id + '/' + element.ar_slug}" class="dis_articleImg">
                                                            <img src="${hero_img}" onerror="this.onerror=null;this.src='${on_error_img}'" alt="images" title="image" loading="lazy">
                                                        </a>
                                                        <div class="dis_articleDetails">
                                                            <a href="${base_url + 'article/?category=' + element.cat_name}" class="dis_articleCat" >${element.cat_name}</a><br>
                                                            <h1 class="dis_articleT"><a href="${base_url + 'article/' + element.encoded_id + '/' + element.ar_slug}" class="dis_articleTtl">${element.ar_title}</a></h1>
                                                            <div class="dis_articleTtlMeta"> <span class="dis_articleTtlAuthor"> <svg class="dis_articleTtlAuthorI" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21 20.75"><path d="M17.92 13.08a10.38 10.38 0 00-3.17-2.18 6.278 6.278 0 002.02-4.62c0-3.46-2.82-6.28-6.28-6.28S4.22 2.82 4.22 6.28c0 1.76.74 3.43 2.02 4.62a10.38 10.38 0 00-3.17 2.18c-1.98 1.98-3.08 4.87-3.08 7.67h2.57c0-4.38 3.56-8.19 7.94-8.19s7.94 3.81 7.94 8.19H21c0-2.8-1.09-5.69-3.08-7.67zm-3.71-6.79c0 2.05-1.67 3.72-3.72 3.72S6.77 8.34 6.77 6.29s1.67-3.72 3.72-3.72 3.72 1.67 3.72 3.72z"/></svg> ${element.user_name}</span> <!-- <span class="dis_articleTtlDate">${getTimeAgo1(element?.ar_date_created)}</span> --> </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>`
                        }
                    })

                    let ret_html = recomArtHtml(recomHtm)
                    $('#content').append(ret_html).fadeIn('slow')
                    Articles_slider2()
                    ControlRequest1 = true

                } else {
                    $.each(data_v, function (i, item) {
                        element = item[0]
                        if(element.publish_status == 1) {
                            if (sld_title == 'TOP IN CATEGORY' && i == 0) {
                                sld_title = 'TOP IN ' + element.cat_name.toUpperCase();
                            }

                            if (element.content_type == 'image') { hero_img = AMAZON_URL + addKeyWordInName(element.content, '_thumb') + '.webp'; on_error_img = AMAZON_URL + element.content; txt = item[1].plain_content } else { hero_img = base_url + 'repo/images/blog_pp.png'; txt = element.plain_content }

                            if (order % 2 == 0) {
                                if (txt.split(' ').length > 30) { txt = txt.split(' ').slice(0, 30).join(' ') + '... ' } else { txt = txt + '... ' }
                                slider_html += `<li>
                                                    <div class="dis_articleBox">
                                                        <a href="${base_url + 'article/' + element.encoded_id + '/' + element.ar_slug}" class="dis_articleImg">
                                                            <img src="${hero_img}" onerror="this.onerror=null;this.src='${on_error_img}'" alt="images" title="image" loading="lazy">
                                                        </a>
                                                        <div class="dis_articleDetails">
                                                            <a href="${base_url + 'article/?category=' + element.cat_name}" class="dis_articleCat">${element.cat_name}</a><br>
                                                            <h1 class="dis_articleT"><a href="${base_url + 'article/' + element.encoded_id + '/' + element.ar_slug}" class="dis_articleTtl">${element.ar_title}</a></h1>
                                                            <div class="dis_articlePera lineL4 1">${txt}<a href="${base_url + 'article/' + element.encoded_id + '/' + element.ar_slug}" class="dis_articlerMore">Read More..</a></div>
                                                            <div class="dis_articleTtlMeta"> <span class="dis_articleTtlAuthor"> <svg class="dis_articleTtlAuthorI" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21 20.75"><path d="M17.92 13.08a10.38 10.38 0 00-3.17-2.18 6.278 6.278 0 002.02-4.62c0-3.46-2.82-6.28-6.28-6.28S4.22 2.82 4.22 6.28c0 1.76.74 3.43 2.02 4.62a10.38 10.38 0 00-3.17 2.18c-1.98 1.98-3.08 4.87-3.08 7.67h2.57c0-4.38 3.56-8.19 7.94-8.19s7.94 3.81 7.94 8.19H21c0-2.8-1.09-5.69-3.08-7.67zm-3.71-6.79c0 2.05-1.67 3.72-3.72 3.72S6.77 8.34 6.77 6.29s1.67-3.72 3.72-3.72 3.72 1.67 3.72 3.72z"></path></svg> ${element.user_name}</span> <!-- <span class="dis_articleTtlDate">${getTimeAgo1(element?.ar_date_created)}</span> --> </div>
                                                        </div>
                                                    </div>
                                                </li>`

                            } else {
                                if (count < 3) {
                                    pkd_content_one += `<li>
                                                            <div class="dis_articleBox">
                                                                <a href="${base_url + 'article/' + element.encoded_id + '/' + element.ar_slug}" class="dis_articleImg">
                                                                    <img src="${hero_img}" onerror="this.onerror=null;this.src='${on_error_img}'" alt="images" title="image" loading="lazy">
                                                                </a>
                                                                <div class="dis_articleDetails">
                                                                    <a href="${base_url + 'article/?category=' + element.cat_name}" class="dis_articleCat">${element.cat_name}</a><br>
                                                                    <h1 class="dis_articleT"><a href="${base_url + 'article/' + element.encoded_id + '/' + element.ar_slug}" class="dis_articleTtl">${element.ar_title}</a></h1>
                                                                    <div class="dis_articleTtlMeta"> <span class="dis_articleTtlAuthor"> <svg class="dis_articleTtlAuthorI" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21 20.75"><path d="M17.92 13.08a10.38 10.38 0 00-3.17-2.18 6.278 6.278 0 002.02-4.62c0-3.46-2.82-6.28-6.28-6.28S4.22 2.82 4.22 6.28c0 1.76.74 3.43 2.02 4.62a10.38 10.38 0 00-3.17 2.18c-1.98 1.98-3.08 4.87-3.08 7.67h2.57c0-4.38 3.56-8.19 7.94-8.19s7.94 3.81 7.94 8.19H21c0-2.8-1.09-5.69-3.08-7.67zm-3.71-6.79c0 2.05-1.67 3.72-3.72 3.72S6.77 8.34 6.77 6.29s1.67-3.72 3.72-3.72 3.72 1.67 3.72 3.72z"></path></svg> ${element.user_name}</span> <!-- <span class="dis_articleTtlDate">${getTimeAgo1(element?.ar_date_created)}</span> --> </div>
                                                                </div>
                                                            </div>
                                                        </li>`
                                    count += 1

                                } else {
                                    pkd_content_two += `<li>
                                                            <div class="dis_articleBox">
                                                                <a href="${base_url + 'article/' + element.encoded_id + '/' + element.ar_slug}" class="dis_articleImg">
                                                                    <img src="${hero_img}" onerror="this.onerror=null;this.src='${on_error_img}'" alt="images" title="image" loading="lazy">
                                                                </a>
                                                                <div class="dis_articleDetails">
                                                                    <a href="${base_url + 'article/?category=' + element.cat_name}" class="dis_articleCat">${element.cat_name}</a><br>
                                                                    <h1 class="dis_articleT"><a href="${base_url + 'article/' + element.encoded_id + '/' + element.ar_slug}" class="dis_articleTtl">${element.ar_title}</a></h1>
                                                                    <div class="dis_articleTtlMeta"> <span class="dis_articleTtlAuthor"> <svg class="dis_articleTtlAuthorI" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21 20.75"><path d="M17.92 13.08a10.38 10.38 0 00-3.17-2.18 6.278 6.278 0 002.02-4.62c0-3.46-2.82-6.28-6.28-6.28S4.22 2.82 4.22 6.28c0 1.76.74 3.43 2.02 4.62a10.38 10.38 0 00-3.17 2.18c-1.98 1.98-3.08 4.87-3.08 7.67h2.57c0-4.38 3.56-8.19 7.94-8.19s7.94 3.81 7.94 8.19H21c0-2.8-1.09-5.69-3.08-7.67zm-3.71-6.79c0 2.05-1.67 3.72-3.72 3.72S6.77 8.34 6.77 6.29s1.67-3.72 3.72-3.72 3.72 1.67 3.72 3.72z"></path></svg> ${element.user_name}</span> <!-- <span class="dis_articleTtlDate">${getTimeAgo1(element?.ar_date_created)}</span> --> </div>
                                                                </div>
                                                            </div>
                                                        </li>`
                                    count += 1
                                }
                                if (count > 4) {
                                    pkd_status = true
                                    pkd_html += topPickContent(pkd_content_one, pkd_content_two)
                                    pkd_content_one = ''
                                    pkd_content_two = ''
                                    count = 0
                                }
                            }
                        }
                    })

                    sld_title = sld_title.toLowerCase().replace(/\b[a-z]/g, function (letter) {
                        return letter.toUpperCase();
                    });

                    if (order % 2 == 0) {
                        slider = listSlider(slider_html, sld_title)
                    } else {
                        if (pkd_status == false) {
                            pkd_html = topPickContent(pkd_content_one, pkd_content_two)
                        }
                        slider = top_pick_slider(pkd_html, sld_title)
                    }
                }

                $('#content').append(slider).fadeIn('slow')
                contentBtm()
                resolve();
            } else {
                reject();
            }
        })
    });

    return myPromise;

}


function contentBtm(offset) {                                                 // this is for scroll purpose api will trigger when scroll cross this div id ( for main slider )
    $('#content_btm').remove()
    $('#content').append(`<div data-offset="${offset}" id="content_btm"></div>`)
}

// function contentBtmSidebar() {                                                // this is for scroll purpose api will trigger when scroll cross this div id ( for sidebar slider )
//     $('#content_sidebar_btm').remove()
//     $('#sidebar').append(`<div id="content_sidebar_btm"></div>`)
// }

function Articles_slider1() {
    if ($('.dis_TopArtSLider').length > 0) {
        const art_pb = document.querySelector(".dis_art_pb");                                             // initialize the slider top
        var swiper = new Swiper(".dis_TopArtSLider .mySwiper", {
            loop: true,
            // Its speed will be changed by css
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".SBN",
                prevEl: ".SBP",
            }
        });

        art_pb.addEventListener("animationend", myEndFunction);

        // Retrigger Animation on Slide Change

        function myEndFunction() {
            swiper.slideNext();
            art_pb.style.animation = "none";
            void art_pb.offsetWidth; // Triggers Reflow
            art_pb.style.animation = null;
        }

        // Reset Progress Bar On Slide Change

        swiper.on("slideChange", function () {
            art_pb.style.animation = "none";
            void art_pb.offsetWidth; // Triggers Reflow
            art_pb.style.animation = null;
            // art_pb.style.animationPlayState = "paused"; // Optional
        });



    }
}

function Articles_slider2() {                                                // initialize the slider middle
    if ($('.dis_artSTM').length > 0) {
        var swiper = new Swiper(".dis_artSTM .mySwiper", {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".SBN",
                prevEl: ".SBP",
            },
            breakpoints: {
                480: {
                    slidesPerView: 2,
                    spaceBetween: 15,
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 15,
                },
                1200: {
                    slidesPerView: 3,
                    spaceBetween: 15,
                },
            },
        });
    }
}


function listSlider(slider_html, slider_title) {
    let html = `<div class="dis_articleMBox m_b_30">
    <h2 class="dis_articleHeading">${slider_title}</h2>
    <div class="dis_Ainline1">
        <ul data-container="${slider_title}" class="dis_articleList DAS2 DA-inline">
            ${slider_html}
        </ul>
    </div>
</div>`
    return html
}

function top_pick_slider(bottom_html, slider_title) {

    let slider = `<div class="dis_articleMBox m_b_30">
                    <h2 class="dis_articleHeading">${slider_title}</h2>
                        ${bottom_html}
                    </div>`

    return slider
}

function topPickContent(content_one, content_two) {
    let html2 = ''
    if (content_two != '') {
        html2 = `<div class="dis_art_comboBtm">
                        <ul class="dis_articleList DAS2 DA-inline DAS2_mini">
                            ${content_two}
                        </ul>
                    </div>`
    }

    let html = `<div class="dis_art_comboTop">
                    <ul class="dis_articleList DAS2 DA-block dis_asg3">
                        ${content_one}
                    </ul>
                </div>
                ${html2}`
    return html
}


function getBlockOneData(categ = 0, onload = false) {  // $categ = 0 means for all category
    let formData = new FormData();
    formData.append('category_id', categ);
    manageMyAjaxPostRequestData(formData, base_url + 'articles/getBlockOneData').done(function (resp) {
        if (resp.status == 1 && resp.type == 'Success') {

            let data = resp.blockOneArticles;
            let html1 = '';                       // html storing variable for top two blocks articles
            let html2 = '';
            let hero_img = '';
            let cr_slug = '';
            var randomNumber1 = Math.floor(Math.random() * 10);
            var randomNumber2 = Math.floor(Math.random() * 10);
            randomNumber2 = randomNumber1 == randomNumber2 ? randomNumber2 + 2 : randomNumber2;
            let item1 = data[randomNumber1];                  // data array of top two blocks articles
            let item2 = data[randomNumber2];
            let on_error_img = '';

            if (item1 != undefined) {
                if(item1.publish_status == 1){
                    cr_slug = base_url + 'article/' + item1.encoded_id + '/' + item1.ar_slug

                    if (item1.content_type == 'image') { hero_img = AMAZON_URL + addKeyWordInName(item1.content, '_thumb') + '.webp'; on_error_img = AMAZON_URL + item1.content } else { hero_img = base_url + 'repo/images/blog_pp.png' }

                    html1 = `<a href="${cr_slug}" class="dis_articleImg">
                                        <img src="${hero_img}" onerror="this.onerror=null;this.src='${on_error_img}'" alt="images" title="image" loading="lazy">
                                    </a>
                                    <div class="dis_articleDetails">
                                        <a href="javascript:;" class="dis_articleCat" >${item1.cat_name}</a><br>
                                        <h1 class="dis_articleT"><a href="${cr_slug}" class="dis_articleTtl" data-toggle="tooltip" title="${item1.ar_title}">${item1.ar_title}</a></h1>
                                        <div class="dis_articleTtlMeta"> <span class="dis_articleTtlAuthor"> <svg class="dis_articleTtlAuthorI" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21 20.75"><path d="M17.92 13.08a10.38 10.38 0 00-3.17-2.18 6.278 6.278 0 002.02-4.62c0-3.46-2.82-6.28-6.28-6.28S4.22 2.82 4.22 6.28c0 1.76.74 3.43 2.02 4.62a10.38 10.38 0 00-3.17 2.18c-1.98 1.98-3.08 4.87-3.08 7.67h2.57c0-4.38 3.56-8.19 7.94-8.19s7.94 3.81 7.94 8.19H21c0-2.8-1.09-5.69-3.08-7.67zm-3.71-6.79c0 2.05-1.67 3.72-3.72 3.72S6.77 8.34 6.77 6.29s1.67-3.72 3.72-3.72 3.72 1.67 3.72 3.72z"/></svg> ${item1.user_name}</span> <!-- <span class="dis_articleTtlDate">${getTimeAgo1(item1?.ar_date_created)}</span> --> </div>
                                    </div>`

                    $('[data-id="article_content_1"]').html(html1)
                }
            }


            if (item2 != undefined) {
                if(item1.publish_status == 1){
                    cr_slug = base_url + 'article/' + item2.encoded_id + '/' + item2.ar_slug
                    if (item2.content_type == 'image') { hero_img = AMAZON_URL + addKeyWordInName(item2.content, '_thumb') + '.webp'; on_error_img = AMAZON_URL + item2.content } else { hero_img = base_url + 'repo/images/blog_pp.png' }

                    html2 = `<a href="${cr_slug}" class="dis_articleImg">
                                        <img src="${hero_img}" onerror="this.onerror=null;this.src='${on_error_img}'" alt="images" title="image" loading="lazy">
                                    </a>
                                    <div class="dis_articleDetails">
                                        <a href="javascript:;" class="dis_articleCat" >${item2.cat_name}</a><br>
                                        <h1 class="dis_articleT"> <a href="${cr_slug}" class="dis_articleTtl" data-toggle="tooltip" title="${item2.ar_title}">${item2.ar_title}</a></h1>
                                        <div class="dis_articleTtlMeta"> <span class="dis_articleTtlAuthor"> <svg class="dis_articleTtlAuthorI" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21 20.75"><path d="M17.92 13.08a10.38 10.38 0 00-3.17-2.18 6.278 6.278 0 002.02-4.62c0-3.46-2.82-6.28-6.28-6.28S4.22 2.82 4.22 6.28c0 1.76.74 3.43 2.02 4.62a10.38 10.38 0 00-3.17 2.18c-1.98 1.98-3.08 4.87-3.08 7.67h2.57c0-4.38 3.56-8.19 7.94-8.19s7.94 3.81 7.94 8.19H21c0-2.8-1.09-5.69-3.08-7.67zm-3.71-6.79c0 2.05-1.67 3.72-3.72 3.72S6.77 8.34 6.77 6.29s1.67-3.72 3.72-3.72 3.72 1.67 3.72 3.72z"/></svg> ${item2.user_name}</span> <!-- <span class="dis_articleTtlDate">${getTimeAgo1(item1?.ar_date_created)}</span> -->  </div>
                                    </div>`

                    $('[data-id="article_content_2"]').html(html2)
                }
            }

            if ($('[data-toggle="tooltip"]').length > 0) {
                $('[data-toggle="tooltip"]').tooltip();
            }

            data = resp.top_mid_slider          // data array for top middle slider

            if (categ == 0 && onload == true) { // $categ = 0 means for all category

                let slider_html = ''

                $.each(data, function (i, article) {
                    if(article[0].publish_status == 1){
                        if (article[0].content_type == 'image') { hero_img = AMAZON_URL + article[0].content + '.webp'; on_error_img = AMAZON_URL + article[0].content } else { hero_img = base_url + 'repo/images/blog_pp.png' }

                        slider_html += `<div class="swiper-slide">
                                                <div class="DAS1">
                                                    <div class="dis_articleBox dis_artSliderbox">
                                                        <a href="${base_url + 'article/' + article[0].encoded_id + '/' + article[0].ar_slug}" class="dis_articleImg">
                                                            <img class="top_SliderImg" src="${hero_img}" onerror="this.onerror=null;this.src='${on_error_img}'" alt="images" title="image">
                                                        </a>
                                                        <div class="dis_articleDetails">
                                                            <a href="javascript:;" class="dis_articleCat" >${article[0].cat_name}</a><br>
                                                            <h1 class="dis_articleT"><a href="${base_url + 'article/' + article[0].encoded_id + '/' + article[0].ar_slug}" class="dis_articleTtl">${article[0].ar_title}</a></h1>
                                                            <div class="dis_articleTtlMeta"> <span class="dis_articleTtlAuthor"> <svg class="dis_articleTtlAuthorI" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21 20.75"><path d="M17.92 13.08a10.38 10.38 0 00-3.17-2.18 6.278 6.278 0 002.02-4.62c0-3.46-2.82-6.28-6.28-6.28S4.22 2.82 4.22 6.28c0 1.76.74 3.43 2.02 4.62a10.38 10.38 0 00-3.17 2.18c-1.98 1.98-3.08 4.87-3.08 7.67h2.57c0-4.38 3.56-8.19 7.94-8.19s7.94 3.81 7.94 8.19H21c0-2.8-1.09-5.69-3.08-7.67zm-3.71-6.79c0 2.05-1.67 3.72-3.72 3.72S6.77 8.34 6.77 6.29s1.67-3.72 3.72-3.72 3.72 1.67 3.72 3.72z"/></svg> ${article[0].user_name}</span>  </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>`
                    }

                })
                $('#swiper_container').empty().html(slider_html)

                Articles_slider1();
            }



        }
    });
}


function getAllCategForHeader() {                  // top menu items
    let formData = '';
    manageMyAjaxPostRequestData(formData, base_url + 'articles/getCategory').done(function (resp) {
        if (resp.status == 1 && resp.type == 'Success') {
            let data = resp.data;
            let html = '<li><a href="javascript:;" data-cat_id=0 class="dis_artFIlterMenu active">All</a></li>';
            data.forEach(item => {
                html += `<li>
                            <a href="javascript:;" data-cat_id=${item.id} class="dis_artFIlterMenu">${item.cat_name}</a>
                        </li>`
            });
            $('.ar_topCategories').html(html);
        }
    });
}

function recomArtHtml(html) {
    let recomHtml = `<div class="dis_articleMBox m_b_30">
                        <div class="dis_artSTM">
                            <div class="dis_HControls m_b_20">
                                <h2 class="dis_articleHeading">Recommended For You</h2>
                                <div class="dis_artSliderControls dis_controls_TR">
                                    <div class="SBP dis_ASContrls">
                                        <svg xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 492 492" ><g><g xmlns="http://www.w3.org/2000/svg"><g><path d="M198.608,246.104L382.664,62.04c5.068-5.056,7.856-11.816,7.856-19.024c0-7.212-2.788-13.968-7.856-19.032l-16.128-16.12 C361.476,2.792,354.712,0,347.504,0s-13.964,2.792-19.028,7.864L109.328,227.008c-5.084,5.08-7.868,11.868-7.848,19.084 c-0.02,7.248,2.76,14.028,7.848,19.112l218.944,218.932c5.064,5.072,11.82,7.864,19.032,7.864c7.208,0,13.964-2.792,19.032-7.864 l16.124-16.12c10.492-10.492,10.492-27.572,0-38.06L198.608,246.104z" fill="#ffffff" data-original="#000000"></path></g></g></svg>
                                    </div>
                                    <div class="SBN dis_ASContrls">
                                        <svg xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 492.004 492.004"><g> <g xmlns="http://www.w3.org/2000/svg"> <g> <path d="M382.678,226.804L163.73,7.86C158.666,2.792,151.906,0,144.698,0s-13.968,2.792-19.032,7.86l-16.124,16.12 c-10.492,10.504-10.492,27.576,0,38.064L293.398,245.9l-184.06,184.06c-5.064,5.068-7.86,11.824-7.86,19.028 c0,7.212,2.796,13.968,7.86,19.04l16.124,16.116c5.068,5.068,11.824,7.86,19.032,7.86s13.968-2.792,19.032-7.86L382.678,265 c5.076-5.084,7.864-11.872,7.848-19.088C390.542,238.668,387.754,231.884,382.678,226.804z" fill="#ffffff" data-original="#000000" class="hovered-path"></path> </g> </g> </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper mySwiper">
                                <div class="swiper-wrapper dis_art_comboTop">

                                    ${html}

                                </div>
                            </div>
                        </div>
                    </div>`
    return recomHtml
}

// ****************************** FOR Article mode end  ************************************* //

// ****************************** FOR Scroll Load start ************************************* //

var current_page = $('#page').val()
let U_B_id = $('#post_id').val()       //user or blog ID or category ID or tag
let ar_mode = $('#mode').val()

function loadArticles(offset, sc_slider) {
    if (current_page === 'article_single' && U_B_id !== '0') {
        $('#content').append(articleLoaderBlock())
        let type = (ar_mode == 'blogs_by_id') ? 'blog_id' : (ar_mode == 'blogs_by_username' ? 'user_id' : (ar_mode == 'blogs_by_category_id' ? 'categ' : 'tag'));
        console.log('getBlogData');

        getBlogData(U_B_id, offset, type, 1).then(function () {
            ControlRequest1 = true;
            $(".box").each(function (index) {
                let id = $(".box:eq(" + index + ")").find("video").attr("id");
                var player = videojs.getPlayer(id);
                if (!player)
                    intializeVideoJsContent("eq(" + index + ")");
            })
        }).catch((e) =>

            console.log(e, 'ee')
        );
    }

    if (current_page == 'article_mode') {
        ControlRequest1 = false;

        getAdminSlider(sc_slider, $('.dis_artFIlterMenu.active').attr('data-cat_id')).then(function () {
            // taboolaMidArticleCategory('#content', 'append');
            AddSheMediaArticleAdsOnSinglePage('#content', 'append') 
            ControlRequest1 = true;
        }).catch(() => {
            // taboolaBelowArticleThumbnailsFeed('#content', 'append');
            // AddSheMediaArticleAdsOnSinglePage('#content', 'append') 
            AddSheMediaAdsOnSingleArticleOnTheBottom('#content', 'append');
        })
        getAdminSidebarSlider(sc_slider, $('.dis_artFIlterMenu.active').attr('data-cat_id')).then(function () {
            ControlRequest1 = true;
            // addDyanamicSidebarAdsOnArticleHomepage()
            // AdTaboolaAdsOnArticleSidebar('#sidebar', 'append');
        }).catch(() => {
            // AdTaboolaAdsOnArticleSidebar('#sidebar', 'append');
        })
    }
}

var ControlRequest1 = true;

$(document).ready(function () {
    let sc_slider = 1;

    if ($('#articles_homepage').length) {
        setTimeout(() => addSheMediaAdsOnArticleHomepageOnTheTop(), 1500)

        getAdminSlider(0, 0).then(function () {
            setTimeout(() => addSheMediaDyanamicAdsOnArticleHomepage(), 2500)
        })
        getAdminSidebarSlider(0, 0).then(function () {
            // setTimeout(() => addDyanamicSidebarAdsOnArticleHomepage(), 3500)
        })
    }

    $(document).on('click', '.dis_artFIlterMenu', function (e) {
        sc_slider = 1;
    })

    $(window).scroll(function () {
        if (ControlRequest1) {
            let scroll_upto = 0;
            if ($('#content_btm').length > 0) { scroll_upto = $('#content_btm').offset().top } else { scroll_upto = $(document).height() - 200 }
            if ($(window).scrollTop() + $(window).height() > scroll_upto) {
                let offset = $('#content_btm').attr('data-offset');
                loadArticles(parseInt(offset), sc_slider);
                ControlRequest1 = false
                sc_slider++;
            }
        }
    });

});

// ********************************* FOR Scroll Load end ************************************* //

function blogViewsCount() {
    $(function () {
        var lastScrollTop = 0, delta = 5;
        let counting = 0;
        $(window).scroll(function () {
            var nowScrollTop = $(this).scrollTop();
            if (Math.abs(lastScrollTop - nowScrollTop) >= delta && counting == 0) {
                counting = 1;
                if (nowScrollTop > lastScrollTop) {
                    // ACTION ON
                    // SCROLLING DOWN
                    articleViewCount()
                } else {
                    // ACTION ON
                    // SCROLLING UP
                    articleViewCount()
                }
                lastScrollTop = nowScrollTop;
            }

        });
    });
}

function articleViewCount() {
    let formData = new FormData();
    formData.append('post_id', $('#post_id').val());
    manageMyAjaxPostRequestData(formData, base_url + 'articles/articleViewCount').done(function (resp) {
        if (resp.status == 1 && resp.type == 'Success') { }
    });
}

function showCategories() {
    let formData = '';
    manageMyAjaxPostRequestData(formData, base_url + 'articles/getAllCategories').done(function (resp) {
        if (resp.status == 1 && resp.type == 'Success') {
            let data = resp.data;
            let html = '';
            $.each(data, function (key, item) {
                html += `<li>
                    <div class="dis_articleBgBox">
                        <div class="dis_articleBImg">
                            <img loading="lazy" src="${base_url + 'repo_admin/images/blog_cate/' + item.cat_img}" onerror="this.onerror=null;this.src='${base_url}repo/images/blog_pp.png'" alt="images" title="image">
                        </div>
                        <a href="${base_url + 'article?category=' + item.cat_name}" class="dis_articleBDetails">
                            <span class="dis_articleBgD">${item.cat_name}</span>
                            <span class="dis_articleBgBadge">${item.total}</span>
                        </a>
                    </div>
                </li>`
            });
            $('#categories_list').html(html);
        }
    });
}

function getMostPopularData() {
    let formData = '';
    $('.dis_most-popular-sidebar').removeClass('hideme');
    manageMyAjaxPostRequestData(formData, base_url + 'articles/getMostPopularData').done(function (resp) {
        if (resp.status == 1 && resp.type == 'Success') {
            let data = resp.data;
            let popular_html = '';
            let img = ''
            let on_error_img = ''

            data.forEach(item => {
                if(item.publish_status == 1){
                    if (item.content_type != 'image') {
                        img = base_url + 'repo/images/blog_pp.png';
                    } else {
                        img = addKeyWordInName(item.content, '_thumb') + '.webp'
                        img = AMAZON_URL + img;
                        on_error_img = AMAZON_URL + item.content
                    }
                    popular_html += `<li>
                        <div class="dis_articleBox">
                            <a class="dis_articleImg">
                                <img src="${img}" onerror="this.onerror=null;this.src='${on_error_img}'" alt="images" title="image" loading="lazy">
                            </a>
                            <div class="dis_articleDetails">
                                <a href="${base_url + 'article/?category=' + item.cat_name}" class="dis_articleCat" >${item.cat_name}</a><br>
                                <h1 class="dis_articleT"><a href="${base_url + 'article/' + item.encoded_id + '/' + item.ar_slug}" title="${item.ar_title}" class="dis_articleTtl">${item.ar_title}</a></h1>
                                <div class="dis_articleTtlMeta"> <span class="dis_articleTtlAuthor"> <svg class="dis_articleTtlAuthorI" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21 20.75"><path d="M17.92 13.08a10.38 10.38 0 00-3.17-2.18 6.278 6.278 0 002.02-4.62c0-3.46-2.82-6.28-6.28-6.28S4.22 2.82 4.22 6.28c0 1.76.74 3.43 2.02 4.62a10.38 10.38 0 00-3.17 2.18c-1.98 1.98-3.08 4.87-3.08 7.67h2.57c0-4.38 3.56-8.19 7.94-8.19s7.94 3.81 7.94 8.19H21c0-2.8-1.09-5.69-3.08-7.67zm-3.71-6.79c0 2.05-1.67 3.72-3.72 3.72S6.77 8.34 6.77 6.29s1.67-3.72 3.72-3.72 3.72 1.67 3.72 3.72z"/></svg> ${item.user_name}</span> <!-- <span class="dis_articleTtlDate">${getTimeAgo1(item?.ar_date_created)}</span> --> </div>
                            </div>
                        </div>
                    </li>`
                }
            });
            $('#most_popular').show()
            $('#most_popular').html(popular_html)
        } else {
            $('#most_popular').hide()
        }
    });
}

function getBlogCategoryTops(id, categ_by) {
    let formData = new FormData();
    formData.append('blog_id', id);
    formData.append('categ_by', categ_by);
    manageMyAjaxPostRequestData(formData, base_url + 'articles/getBlogCategoryTops').done(function (resp) {
        if (resp.status == 1 && resp.type == 'Success') {

            $('#top_in_category').text('Top in ' + resp.category)

            let top_html = '';
            let count = 1;

            resp.data.forEach(item => {
                top_html += `<li>
                                <div class="dis_articleBox">
                                    <a href="${base_url + 'article/' + item.encoded_id + '/' + item.ar_slug}" class="dis_articleImg">
                                        #0${count}
                                    </a>
                                    <div class="dis_articleDetails">
                                        <a href="${base_url + 'article/' + item.encoded_id + '/' + item.ar_slug}" title="${item.ar_title}" class="dis_articleTtl">${item.ar_title}</a>
                                        <div class="dis_articleTtlMeta"> <span class="dis_articleTtlAuthor"> <svg class="dis_articleTtlAuthorI" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21 20.75"><path d="M17.92 13.08a10.38 10.38 0 00-3.17-2.18 6.278 6.278 0 002.02-4.62c0-3.46-2.82-6.28-6.28-6.28S4.22 2.82 4.22 6.28c0 1.76.74 3.43 2.02 4.62a10.38 10.38 0 00-3.17 2.18c-1.98 1.98-3.08 4.87-3.08 7.67h2.57c0-4.38 3.56-8.19 7.94-8.19s7.94 3.81 7.94 8.19H21c0-2.8-1.09-5.69-3.08-7.67zm-3.71-6.79c0 2.05-1.67 3.72-3.72 3.72S6.77 8.34 6.77 6.29s1.67-3.72 3.72-3.72 3.72 1.67 3.72 3.72z"/></svg> ${item.user_name}</span> <!-- <span class="dis_articleTtlDate">${getTimeAgo1(item?.ar_date_created)}</span> --> </div>
                                    </div>
                                </div>
                            </li>`;
                count += 1;
            });

            $('#top_in_cat_div').show();
            $('#top_in_category_list').html(top_html);
        } else {
            $('#top_in_cat_div').hide();
        }
    });
}

function getAudienceStatus(pubId) {
    let formData = new FormData();
    formData.append("pubID", pubId);
    manageMyAjaxPostRequestData(
        formData,
        base_url + "articles/getPublishDataStatus"
    ).done(function (data) {
        selected = data;
    });

    setTimeout(function () {
        $("#conf_btn").show();
        $("#conf_header").addClass("notification_popup");
        $("#conf_title").text("Audience");
        $("#conf_text").html(
            'Who can see this? <select class="form_field" id="audience_select"> <option  value="7">Public</option> <option  value="6">Private</option> <option  value="5">Only Me</option>   </select>'
        );
        $("#audience_select option[value=" + selected + "]").attr(
            "selected",
            "selected"
        );
        $("#conf_btn").text("Change");
        $("#conf_btn").attr("onclick", "change_audience(" + pubId + ")");
        $("#confirm_popup").modal("show");
    }, 500);
}

function change_audience(pid) {
    if (pid != "") {
        let aud = new FormData();
        aud.append("pubID", pid);
        aud.append("aud", $("#audience_select").val());
        aud.append("action_type", "change_audience");

        manageMyAjaxPostRequestData(
            aud,
            base_url + "articles/changePublishStatus"
        ).done(function (resp) {
            if (resp.status == 1) {
                success_popup_function("Your preferences have been saved.");
            } else {
                server_error_popup_function(resp.message);
            }
        });
    }
}
var slider_offset = 0;

const urlParam = new URLSearchParams(window.location.search);
const myParam = urlParam.get('slider_offset');
if (myParam > 0) {
    slider_offset = myParam;
}

function getBlogDataSlider(id, name) {
    let myPromise = new Promise((resolve, reject) => {

        let f = new FormData();
        f.append(name, id);
        f.append('offset', slider_offset);
        f.append('limit', 1);
        f.append('type', 'part');

        if (name == 'user_id') {
            f.append('complete_status', $('#complete_status').val());
        }

        manageMyAjaxPostRequestData(f, base_url + 'articles/get_blog').done(function (resp) {
            if (resp.status == 1) {
                if (resp.type == 'Success') {

                    let on_error_himg = '',
                        user_image = '',
                        user_name = '',
                        plain_content = '',
                        date_created = '',
                        ar_category = '',
                        category_id = '',
                        ar_title = '',
                        edit_btns = '',
                        article_id = '',
                        slug = '',
                        user_id = '',
                        user_uname = '',
                        img_name = '',
                        text = '',
                        html = '',
                        reading_time = 0,
                        content_html = '',
                        encoded_id = '';
                    // console.log(resp.data, 'resp.data');
                    $.each(resp.data, function (key, val) {

                        let blog_html = '';

                        if (key == 0) {
                            let hero_img = base_url + 'repo/images/blog_pp.png';  // getting hero image //
                            // let tag = '<li><a href="javascript:;" class="dis_artSItemF">Tags:</a></li>';  // getting tags //
                            let tag = '';  // getting tags //

                            if (val[0] != undefined) {
                                var tagArr = val[0].ar_tag.split(',');
                            }

                            tagArr.forEach(element => {
                                pure_tag = element.replace(/[&\/\\#,+()$~%'":*?<>{}]/g, '').trim()
                                tag += `<li><a href="${base_url + 'article?tag=' + pure_tag}" class="dis_artTagItem">${element}</a></li>`;
                            });
                            let i = 0;
                            $.each(val, function (key2, value2) {

                                if (i == 0 && value2['content_type'] == 'image') { // getting hero image //
                                    hero_img = AMAZON_URL + value2['content'] + '.webp';
                                    on_error_himg = AMAZON_URL + value2['content'];
                                } else {
                                    if (value2['content_type'] == 'image') {
                                        if (typeof value2['content'] != 'undefined')
                                            html += `<div class="container_index">
                                            <img style="width:100%;" src="${AMAZON_URL + value2['content'] + '.webp'}" onerror="this.onerror=null;this.src='${AMAZON_URL + value2['content']}'" />
                                        </div><br>`;
                                    } else if (value2['content_type'] == 'video') {
                                        if (typeof value2['content'] != 'undefined')
                                            html += `<div class="dis_art_u_iframe container_index">
                                                <iframe data-num="0" src="${base_url + value2['content']}" width="560" height="315" frameborder="0" allow="autoplay" allowfullscreen="true">
                                            </iframe>
                                        </div><br>`;
                                    } else if (value2['content_type'] == 'ckeditor') {
                                        html += `<div class="container_index"><div class="dis_articlePera 2">${value2['content'].trim('<p>&nbsp;</p>', '')}</div></div>`;
                                        plain_content = value2['plain_content'];
                                    } else {
                                        html += '<div class="container_index next_btn"><button style="padding: 0 30px;max-width:140px;width:100%;" class="dis_btn min_width_inherit b-r-5"> Next </button></div>';
                                    }
                                }

                                if ((i == Object.keys(val).length - 1) && resp.data?.[1]) {
                                    slider_offset = parseInt(slider_offset) + 1;
                                    let next = resp.data?.[1];
                                    html += `<div class="container_index next_btn">
                                            <a style="padding: 0 30px;max-width:140px;width:100%;" href="${base_url + 'article/' + next[0]['encoded_id'] + '/' + next[0]['ar_slug'] + '?slider_offset=' + (slider_offset)}" class="dis_btn min_width_inherit b-r-5" >Next</a>
                                        </div>`;
                                } else
                                    if ((i == Object.keys(val).length - 1) && !resp.data?.[1]) {
                                        html += `<div class="container_index next_btn">
                                            <a style="padding: 0 30px;max-width:140px;width:100%;" href="${base_url + 'article'}" class="dis_btn min_width_inherit b-r-5" >Next</a>
                                        </div>`;
                                    }

                                // getting rest of the contents for display //
                                user_name = value2['user_name'];
                                img_name = value2['uc_pic']?.substring(0, value2['uc_pic'].lastIndexOf(".")) + "_thumb" + value2['uc_pic']?.substring(value2['uc_pic'].lastIndexOf("."));
                                user_image = AMAZON_URL + 'aud_' + value2['ar_uid'] + '/images/' + img_name;
                                date_created = getLocalTime(value2['ar_date_created']);
                                ar_category = value2['cat_name'];
                                ar_title = value2['ar_title'];
                                user_id = value2['ar_uid'];
                                article_id = value2['article_id'];
                                slug = value2['ar_slug'];
                                encoded_id = value2['encoded_id'];
                                user_uname = value2['user_uname'];
                                category_id = value2['id'];
                                reading_time = value2['ar_read_time'];

                                i++;

                            });

                            // edit buttons if this is current users blog //
                            if (user_id == user_login_id) {
                                edit_btns = `<li>
                                    <div class="dis_actiondiv">
                                        <span class="dis_profileOptionIcon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="17px" height="7px" viewBox="0 0 17 7">
                                                <path fill-rule="evenodd" fill="rgb(119, 119, 119)" d="M14.875,4.000 C13.701,4.000 12.750,3.104 12.750,2.000 C12.750,0.895 13.701,-0.000 14.875,-0.000 C16.049,-0.000 17.000,0.895 17.000,2.000 C17.000,3.104 16.049,4.000 14.875,4.000 ZM8.500,4.000 C7.326,4.000 6.375,3.104 6.375,2.000 C6.375,0.895 7.326,-0.000 8.500,-0.000 C9.673,-0.000 10.625,0.895 10.625,2.000 C10.625,3.104 9.673,4.000 8.500,4.000 ZM2.125,4.000 C0.951,4.000 -0.000,3.104 -0.000,2.000 C-0.000,0.895 0.951,-0.000 2.125,-0.000 C3.299,-0.000 4.250,0.895 4.250,2.000 C4.250,3.104 3.299,4.000 2.125,4.000 Z"></path>
                                            </svg>
                                        </span>
                                        <div class="dis_action_content">
                                            <ul>
                                                <li class=""><a href="${base_url + 'articles/step1/' + article_id}">Edit</a></li>
                                                <li><a href="javascript:;" class="delete_article_post" data-article_id="${article_id}">Delete</a></li>
                                                <li onclick="getAudienceStatus(${article_id})" class=""><a href="javascript:;">Audience</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>`;
                            }

                            content_html = html // getting full article //

                            // main blog element
                            blog_html += `<div class="dis_articleMBox vc">
                                        <div class="dSingleA DAS2 DA-block">
                                            <div class="dis_articleBox">
                                                <div class="dis_art_topbar">
                                                    <div class="dis_art_topbarL">
                                                        <div class="dis_art_profile">
                                                            <div class="dis_art_proImg">
                                                                    <img id="user_image" src="${user_image}" onerror="this.onerror=null;this.src='${base_url + 'repo/images/banner_logo1.png'}'" alt="images" title="image">
                                                                </span>
                                                            </div>
                                                            <div class="dis_art_prodet">
                                                            <a href="${base_url + 'profile?user=' + user_uname}" id="user_name" class="dis_art_prodetT">${user_name}</a>
                                                                <p id="date_created" class="dis_art_prodetP">${date_created} - ${reading_time} min read</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="dis_art_topbarR">
                                                        <ul class="dis_arttbrList">
                                                            <li>
                                                                <div class="dis_artSMWrap">
                                                                    <span class="dis_artSMP1" data-toggle="dropdown">
                                                                        <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="20" height="20" viewBox="0 0 8.4666667 8.4666667" ><g><g><path id="path2191" d="m7.0119634-.00000011c-.8057301 0-1.45521.65465298-1.45521 1.45520791 0 .07766.006.155514.0176.229961l-2.78019 1.516186c-.29177-.3394991-.72339-.5550048-1.20613-.5550048-.88189009 0-1.58802009.7123319-1.58802009 1.5859497 0 .873618.70613 1.588017 1.58802009 1.588017.48457 0 .91488-.215777 1.2066399-.5555221l2.7796799 1.5172201c-.011901.07445-.0176.1523001-.0176.2299599 0 .8005551.6458799 1.4546922 1.4551998 1.4546922.8064101 0 1.4547001-.6541371 1.4547001-1.4546922 0-.8005548-.64897-1.4552078-1.45469-1.4552078-.53789 0-1.0038101.2886459-1.25711.719513l-2.6899397-1.4662381c.0706-.1793319.1110999-.3740509.1110999-.577742 0-.20324-.0403-.3968328-.11058-.5756758l2.6892401-1.467094c.2532999.430867.7186099.7203699 1.25729.7203699.80347 0 1.45469-.6541359 1.45469-1.454692 0-.80055493-.64897-1.45520791-1.45469-1.45520791z" font-variant-ligatures="normal" font-variant-position="normal" font-variant-caps="normal" font-variant-numeric="normal" font-variant-alternates="normal" font-feature-settings="normal" text-indent="0" text-align="start" text-decoration-line="none" text-decoration-style="solid" text-decoration-color="rgb(0,0,0)" text-transform="none" text-orientation="mixed" white-space="normal" shape-padding="0" isolation="auto" mix-blend-mode="normal" solid-color="rgb(0,0,0)" solid-opacity="1" vector-effect="none" fill="#000000" data-original="#000000" class=""></path></g></g></svg>
                                                                    </span>
                                                                    <ul class="dis_artSList 1">
                                                                        <li>
                                                                            <a href="javascript:;" class="dis_artSItem social-share facebook" data-slug="${encoded_id}/${slug}" data-title=${ar_title} data-pic=${hero_img} data-toggle="tooltip" data-placement="top" title="Facebook">
                                                                                <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="18" viewBox="0 0 155.139 155.139"><g> <g> <path d="M89.584,155.139V84.378h23.742l3.562-27.585H89.584V39.184 c0-7.984,2.208-13.425,13.67-13.425l14.595-0.006V1.08C115.325,0.752,106.661,0,96.577,0C75.52,0,61.104,12.853,61.104,36.452 v20.341H37.29v27.585h23.814v70.761H89.584z" fill="#eb581f" data-original="#eb581f" ></path> </g> </svg>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="javascript:;" class="dis_artSItem social-share twitter"  data-slug="${encoded_id}/${slug}" data-title=${ar_title} data-pic=${hero_img} data-toggle="tooltip" data-placement="top" title="Twitter">
                                                                                <svg  width="16" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 1226.37 1226.37" ><g><path d="M727.348 519.284 1174.075 0h-105.86L680.322 450.887 370.513 0H13.185l468.492 681.821L13.185 1226.37h105.866l409.625-476.152 327.181 476.152h357.328L727.322 519.284zM582.35 687.828l-47.468-67.894-377.686-540.24H319.8l304.797 435.991 47.468 67.894 396.2 566.721H905.661L582.35 687.854z" fill="#eb581f" opacity="1" data-original="#eb581f"></path></g></svg>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="javascript:;" class="dis_artSItem social-share linkedin"  data-slug="${encoded_id}/${slug}" data-title=${ar_title} data-pic=${hero_img} data-toggle="tooltip" data-placement="top" title="Linkedin">
                                                                            <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs"width="18" viewBox="0 0 100 100" ><g><g id="_x31_0.Linkedin"><path d="m90 90v-29.3c0-14.4-3.1-25.4-19.9-25.4-8.1 0-13.5 4.4-15.7 8.6h-.2v-7.3h-15.9v53.4h16.6v-26.5c0-7 1.3-13.7 9.9-13.7 8.5 0 8.6 7.9 8.6 14.1v26h16.6z" fill="#eb581f" data-original="#eb581f" ></path><path d="m11.3 36.6h16.6v53.4h-16.6z" fill="#eb581f" data-original="#eb581f" ></path><path d="m19.6 10c-5.3 0-9.6 4.3-9.6 9.6s4.3 9.7 9.6 9.7 9.6-4.4 9.6-9.7-4.3-9.6-9.6-9.6z" fill="#eb581f" data-original="#eb581f" ></path></g></g></svg>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </li>
                                                            ${edit_btns}
                                                        </ul>
                                                    </div>
                                                </div>

                                                <div class="dis_articleDetails">
                                                    <a href="${base_url + 'article/' + encoded_id + '/' + slug}" class="dis_articleTtl" >${ar_title}</a>
                                                    <div class="dis_art_TAGSHARE">
                                                        <ul class="dis_artTagList" id="tags">
                                                            ${tag}
                                                        </ul>
                                                        <ul class="dis_bloginfoList">
                                                            <li>
                                                                <a id="category" href="${base_url + 'article?category=' + ar_category}">
                                                                    <span class="disBlog_icon"><img src="${base_url + 'repo/images/blog_tag.svg'}" loading="lazy" alt="icon"></span> ${ar_category}
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>


                                                    <a href="javascript:;" class="dis_articleImg container_index">
                                                        <img src="${hero_img}" loading="lazy" alt="images" title="image" onerror="this.onerror=null;this.src='${on_error_himg}'" alt="images" title="image">
                                                    </a>

                                                    ${content_html}
                                                </div>
                                            </div>
                                        </div>
                                    </div><br>`;
                        }

                        $('#content').append(blog_html);

                        if (key == 0 || key == 1) {
                            // taboolaMidArticleCategory('#content', 'append');
                        }
                    });

                    $('.dis_postAnimation').hide();  // appending to last

                    resolve();
                }

                if ($('[data-toggle="tooltip"]').length > 0) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            }
        });
    });

    return myPromise;

}

function create_slide() {
    let p = 0;
    $('.container_index').each(function (params, index) {
        if ($(this).attr('class').search("next_btn") > 0) {
            p = 1
        }
        if (p == 1) {

            $(this).hide()
        }
    })
    $('.next_btn:first').show();

    document.querySelectorAll('p').forEach((item) => {
        if (item.innerHTML == '&nbsp;') {
            item.remove()
        }
    })
}

$(document).on('click', '.next_btn', function () {

    let clickedBtnIndex = $('.next_btn').index(this);
    let currentContainerIndex = $('.container_index').index(this);

    let nextBtnIndex = $('.next_btn').eq(clickedBtnIndex + 1);
    let nextContainerIndex = $('.container_index').index(nextBtnIndex);

    if (nextContainerIndex == -1) {
        nextContainerIndex = $('.container_index:last').index()
    }

    $('.container_index').each(function (index) {
        if (index > currentContainerIndex && index < nextContainerIndex) {
            $(this).show();
        } else {
            $(this).hide();
        }
    })

    setTimeout(function () {
        $('.next_btn').eq(clickedBtnIndex + 1).show()
    }, 500)
})

var next_offset = 0;
const urlParam_next_btn = new URLSearchParams(window.location.search);
const myParam_next_btn = urlParam_next_btn.get('next_offset');
if (myParam_next_btn > 0) {
    next_offset = myParam_next_btn;
}

function getBlogData(id, offset = 0, name, limit) {
    let myPromise = new Promise((resolve, reject) => {
        if (isNaN(offset)) {
            return true;
        }
        let f = new FormData();
        f.append(name, id);
        f.append('offset', offset);
        f.append('limit', limit);
        f.append('next_offset', next_offset); // use only for next button

        if (name == 'user_id') {
            f.append('complete_status', $('#complete_status').val());
        }

        manageMyAjaxPostRequestData(f, base_url + 'articles/get_blog').done(function (resp) {
            if (resp.status == 1) {
                if (resp.type == 'Success'){
                    let data = resp.data;

                    if (data?.[0]?.[0]?.['article_id'] !== id && offset == 0 && name == 'blog_id') {
                        $('#content').append(`<div class="dis_blogBox">
                                                <div class="no_result_wraaper">
                                                    <div class="no_result_inner">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="144px" height="141px" class="no_resultsvg">
                                                        <path class="a" fill-rule="evenodd" fill="rgb(232, 233, 234)" d="M102.927,67.238 C102.868,85.522 87.999,100.297 69.715,100.239 C51.430,100.180 36.656,85.311 36.714,67.027 C36.773,48.742 51.642,33.968 69.926,34.026 C88.210,34.085 102.985,48.954 102.927,67.238 Z"></path>
                                                        <path class="b" fill-rule="evenodd" fill="rgb(189, 194, 203)" d="M142.570,51.942 L139.426,51.942 L139.426,55.084 C139.426,55.647 138.969,56.103 138.406,56.103 C137.843,56.103 137.387,55.647 137.387,55.084 L137.387,51.942 L134.243,51.942 C133.681,51.942 133.224,51.485 133.224,50.923 C133.224,50.360 133.681,49.904 134.243,49.904 L137.387,49.904 L137.387,46.761 C137.387,46.198 137.843,45.742 138.406,45.742 C138.969,45.742 139.426,46.198 139.426,46.761 L139.426,49.904 L142.570,49.904 C143.133,49.904 143.589,50.360 143.589,50.923 C143.589,51.485 143.133,51.942 142.570,51.942 ZM104.580,90.518 L136.892,123.739 C138.772,125.671 139.787,128.219 139.749,130.915 C139.711,133.611 138.624,136.130 136.689,138.009 C134.756,139.888 132.206,140.902 129.510,140.864 C126.815,140.825 124.295,139.740 122.414,137.806 L98.385,113.101 C98.384,113.100 98.382,113.098 98.381,113.097 L89.621,104.090 C84.913,106.619 79.742,108.234 74.339,108.822 C73.530,108.910 72.711,108.975 71.904,109.017 C61.657,109.542 51.580,106.270 43.533,99.805 C34.789,92.781 29.307,82.790 28.097,71.674 C26.887,60.557 30.091,49.622 37.118,40.882 C44.146,32.143 54.142,26.664 65.264,25.454 C76.387,24.245 87.326,27.447 96.070,34.471 C104.814,41.496 110.296,51.486 111.506,62.603 C112.400,70.815 110.871,79.021 107.084,86.332 C106.309,87.837 105.456,89.237 104.580,90.518 ZM126.034,134.289 C126.975,135.256 128.234,135.799 129.582,135.818 C130.929,135.837 132.204,135.330 133.171,134.390 C134.139,133.450 134.682,132.192 134.701,130.844 C134.720,129.497 134.213,128.223 133.273,127.257 L111.177,104.540 L103.939,111.573 L126.034,134.289 ZM100.411,107.945 L107.649,100.912 L101.466,94.555 C98.813,97.625 96.603,99.380 96.565,99.410 C95.738,100.098 94.887,100.750 94.015,101.369 L100.411,107.945 ZM106.621,63.134 C104.499,43.645 87.504,29.164 67.929,30.167 C67.221,30.203 66.504,30.261 65.795,30.338 C55.976,31.405 47.152,36.243 40.947,43.958 C34.743,51.674 31.914,61.328 32.982,71.142 C34.051,80.957 38.891,89.777 46.610,95.979 C54.330,102.181 63.987,105.007 73.808,103.939 C81.616,103.090 88.817,99.855 94.631,94.585 L94.665,94.554 C97.561,91.925 100.015,88.869 101.959,85.472 C105.858,78.663 107.470,70.939 106.621,63.134 ZM77.256,64.907 C76.232,64.904 75.358,64.539 74.636,63.812 C73.914,63.086 73.554,62.211 73.557,61.187 C73.560,60.164 73.926,59.291 74.652,58.569 C75.379,57.848 76.255,57.488 77.279,57.491 C78.303,57.495 79.176,57.860 79.899,58.586 C80.621,59.313 80.980,60.187 80.977,61.211 C80.974,62.235 80.609,63.107 79.882,63.829 C79.155,64.550 78.279,64.910 77.256,64.907 ZM80.400,78.271 C80.554,78.754 80.514,79.223 80.280,79.675 C80.047,80.128 79.689,80.431 79.205,80.584 C78.722,80.737 78.248,80.697 77.785,80.463 C77.322,80.230 77.014,79.862 76.862,79.360 C76.384,77.813 75.494,76.560 74.193,75.600 C72.892,74.640 71.430,74.157 69.807,74.152 C68.184,74.147 66.719,74.620 65.412,75.571 C64.105,76.523 63.207,77.770 62.719,79.315 C62.563,79.816 62.257,80.182 61.803,80.412 C61.348,80.643 60.880,80.680 60.397,80.524 C59.895,80.367 59.529,80.062 59.299,79.608 C59.068,79.154 59.031,78.685 59.187,78.203 C59.909,75.868 61.249,73.990 63.204,72.567 C65.161,71.144 67.365,70.437 69.819,70.445 C72.272,70.453 74.474,71.174 76.420,72.610 C78.367,74.045 79.694,75.932 80.400,78.271 ZM62.418,64.860 C61.394,64.856 60.521,64.491 59.798,63.765 C59.076,63.039 58.716,62.164 58.720,61.140 C58.723,60.117 59.088,59.244 59.815,58.522 C60.542,57.800 61.417,57.441 62.441,57.444 C63.465,57.447 64.338,57.812 65.060,58.539 C65.783,59.265 66.142,60.140 66.139,61.164 C66.136,62.187 65.771,63.060 65.044,63.782 C64.317,64.503 63.441,64.863 62.418,64.860 ZM87.267,12.903 C83.760,12.903 80.899,10.043 80.899,6.538 C80.899,3.026 83.760,0.173 87.267,0.173 C90.781,0.173 93.636,3.026 93.636,6.538 C93.636,10.043 90.781,12.903 87.267,12.903 ZM87.267,2.211 C84.882,2.211 82.938,4.154 82.938,6.538 C82.938,8.922 84.882,10.865 87.267,10.865 C89.653,10.865 91.597,8.922 91.597,6.538 C91.597,4.154 89.653,2.211 87.267,2.211 ZM29.003,17.857 L31.183,20.122 C31.573,20.527 31.561,21.172 31.155,21.562 C30.750,21.953 30.104,21.940 29.714,21.535 L27.534,19.270 L25.268,21.449 C24.863,21.839 24.217,21.827 23.827,21.421 C23.437,21.016 23.449,20.371 23.855,19.981 L26.120,17.802 L23.941,15.538 C23.550,15.132 23.563,14.487 23.968,14.097 C24.374,13.707 25.020,13.719 25.410,14.125 L27.589,16.389 L29.855,14.210 C30.261,13.819 30.906,13.832 31.296,14.237 C31.687,14.643 31.675,15.288 31.269,15.678 L29.003,17.857 ZM25.581,113.255 C25.676,112.700 26.202,112.327 26.758,112.421 C27.312,112.516 27.686,113.043 27.591,113.597 L27.063,116.694 L30.162,117.222 C30.717,117.318 31.090,117.844 30.996,118.398 C30.901,118.953 30.375,119.326 29.820,119.232 L26.720,118.704 L26.192,121.801 C26.097,122.356 25.571,122.729 25.015,122.634 C24.824,122.601 24.653,122.517 24.517,122.397 C24.258,122.172 24.120,121.821 24.182,121.459 L24.710,118.361 L21.611,117.833 C21.419,117.800 21.249,117.716 21.112,117.596 C20.854,117.370 20.716,117.020 20.777,116.657 C20.872,116.102 21.398,115.730 21.954,115.824 L25.053,116.352 L25.581,113.255 ZM10.330,73.926 C8.941,74.905 7.257,75.292 5.576,75.005 C4.407,74.804 3.337,74.293 2.467,73.532 C2.089,73.201 1.746,72.829 1.453,72.411 C0.469,71.028 0.086,69.339 0.369,67.664 C0.578,66.466 1.100,65.406 1.847,64.552 C3.261,62.936 5.452,62.072 7.718,62.455 C11.177,63.052 13.517,66.342 12.925,69.805 C12.637,71.475 11.714,72.943 10.330,73.926 ZM7.375,64.466 C5.022,64.070 2.788,65.654 2.381,68.006 C1.980,70.354 3.565,72.597 5.923,72.998 C7.063,73.191 8.207,72.927 9.149,72.262 C10.097,71.592 10.719,70.602 10.913,69.463 C11.314,67.106 9.729,64.872 7.375,64.466 Z"></path>
                                                        </svg>
                                                        <p>404 Article Not Found!</p>
                                                    </div>
                                                </div>
                                            </div>`)
                    }

                    let on_error_himg = '', user_image = '', user_name = '', plain_content = '', date_created = '', ar_category = '', category_id = '', ar_title = '', edit_btns = '', article_id = '', slug = '', user_id = '', user_uname = '', img_name = '', text = '', html = '', reading_time = 0, content_html = '', encoded_id = '', ar_author_name = '', next_encoded_id = '', next_slug = '';

                    $.each(data, function (key, val) {

                        let blog_html = '';
                        // getting hero image //
                        let hero_img = base_url + 'repo/images/blog_pp.png';

                        // getting tags //
                        // let tag = '<li><a href="javascript:;" class="dis_artSItemF">Tags:</a></li>';
                        let tag = '';

                        if (val[0] != undefined) {
                            var tagArr = val[0].ar_tag.split(',');
                        }

                        tagArr && tagArr.forEach(element => {
                            pure_tag = element.replace(/[&\/\\#,+()$~%'":*?<>{}]/g, '').trim()
                            tag += `<li><a href="${base_url + 'article?tag=' + pure_tag}" class="dis_artTagItem">${element}</a></li>`;
                        });


                        $.each(val, function (key2, value2) {
                            if(value2['publish_status'] == 1){
                                
                                // getting hero image //
                                if (key2 == 0 && value2['content_type'] == 'image') {
                                    hero_img = AMAZON_URL + value2['content'] + '.webp';
                                    on_error_himg = AMAZON_URL + value2['content'];
                                } else {
                                    user_name = value2['user_name'];
                                    img_name = value2['uc_pic']?.substring(0, value2['uc_pic'].lastIndexOf(".")) + "_thumb" + value2['uc_pic']?.substring(value2['uc_pic'].lastIndexOf("."));
                                    user_image = AMAZON_URL + 'aud_' + value2['ar_uid'] + '/images/' + img_name;
                                    date_created = getLocalTime(value2['ar_date_created']);
                                    ar_category = value2['cat_name'];
                                    ar_title = value2['ar_title'];
                                    user_id = value2['ar_uid'];
                                    article_id = value2['article_id'];
                                    slug = value2['ar_slug'];
                                    encoded_id = value2['encoded_id'];
                                    user_uname = value2['user_uname'];
                                    category_id = value2['id'];
                                    reading_time = value2['ar_read_time'];
                                    ar_author_name = value2['ar_author_name'];

                                    if (typeof value2['content'] != 'undefined') {
                                        if (value2['content_type'] == 'image') {
                                            html += `<br><div>
                                                <img style="width:100%;" src="${AMAZON_URL + value2['content'] + '.webp'}" onerror="this.onerror=null;this.src='${AMAZON_URL + value2['content']}'" />
                                            </div>`;
                                        } else
                                            if (value2['content_type'] == 'video') {
                                                //     html += `<br><div class="dis_art_u_iframe article_sticky_player" style="z-index:0;" >
                                                //         <iframe id="${'stiKyPly'+key+key2}" class="sticky_video" data-num="0" src="${base_url + value2['content']+'&isInViewPort=false'}" width="560" height="315" frameborder="0" allow="autoplay" allowfullscreen="true">
                                                //     </iframe>
                                                // </div>`;
                                                console.log('vidInfo', value2);

                                                html += `<div  class="box"> <video controls id="my_video_${key + key2}"  class="video-js vjs-big-play-centered vjs-default-skin "
                                                    poster="${value2.vidInfo.poster}" vidid = "" >
                                                    <source src="${value2.vidInfo.sources.src}" type="${value2.vidInfo.sources.type}" id="media_${key + key2}">
                                            </video></div><span class="dragRemove"></span>`;
                                            } else
                                                if (value2['content_type'] == 'ckeditor') {
                                                    html += `<div id="${encoded_id + key + key2}"><div class="dis_articlePera 3">${value2['content']}</div></div>`;
                                                    plain_content = value2['plain_content'];
                                                }
                                    }
                                }


                                // getting rest of the contents for display //
                                user_name = value2['user_name'];
                                img_name = value2['uc_pic']?.substring(0, value2['uc_pic'].lastIndexOf(".")) + "_thumb" + value2['uc_pic']?.substring(value2['uc_pic'].lastIndexOf("."));
                                user_image = AMAZON_URL + 'aud_' + value2['ar_uid'] + '/images/' + img_name;
                                date_created = getLocalTime(value2['ar_date_created']);
                                ar_category = value2['cat_name'];
                                ar_title = value2['ar_title'];
                                user_id = value2['ar_uid'];
                                article_id = value2['article_id'];
                                slug = value2['ar_slug'];
                                encoded_id = value2['encoded_id'];
                                user_uname = value2['user_uname'];
                                category_id = value2['id'];
                                reading_time = value2['ar_read_time'];
                                ar_author_name = value2['ar_author_name'];

                            }
                        });

                        next_encoded_id = data[1] && data[1][0] ? data[1][0]['encoded_id'] : '';
                        next_slug = data[1] && data[1][0] ? data[1][0]['ar_slug'] : '';
                        // console.log(next_encoded_id,'next_encoded_id',next_slug);
                        // edit buttons if this is current users blog //
                        if (user_id == user_login_id) {
                            edit_btns = `<li>
                                            <div class="dis_actiondiv">
                                                <span class="dis_profileOptionIcon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="17px" height="7px" viewBox="0 0 17 7">
                                                        <path fill-rule="evenodd" fill="rgb(119, 119, 119)" d="M14.875,4.000 C13.701,4.000 12.750,3.104 12.750,2.000 C12.750,0.895 13.701,-0.000 14.875,-0.000 C16.049,-0.000 17.000,0.895 17.000,2.000 C17.000,3.104 16.049,4.000 14.875,4.000 ZM8.500,4.000 C7.326,4.000 6.375,3.104 6.375,2.000 C6.375,0.895 7.326,-0.000 8.500,-0.000 C9.673,-0.000 10.625,0.895 10.625,2.000 C10.625,3.104 9.673,4.000 8.500,4.000 ZM2.125,4.000 C0.951,4.000 -0.000,3.104 -0.000,2.000 C-0.000,0.895 0.951,-0.000 2.125,-0.000 C3.299,-0.000 4.250,0.895 4.250,2.000 C4.250,3.104 3.299,4.000 2.125,4.000 Z"></path>
                                                    </svg>
                                                </span>
                                                <div class="dis_action_content">
                                                    <ul>
                                                        <li class=""><a href="${base_url + 'articles/step1/' + article_id}">Edit</a></li>
                                                        <li><a href="javascript:;" class="delete_article_post" data-article_id="${article_id}">Delete</a></li>
                                                        <li onclick="getAudienceStatus(${article_id})" class=""><a href="javascript:;">Audience</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>`;
                        }

                        if (val?.[0]?.['article_id'] === id) {
                            content_html = html // getting full article //
                        } else {
                            if (plain_content.length > 300) {
                                plain_content = plain_content.substring(0, 300) + '... ';
                            }   // getting read_more articles //

                            content_html = `<div class="dis_ckeditorContent">${plain_content}</div>
                                            <div>
                                                <a href="${base_url + 'article/' + encoded_id + '/' + slug}" style="padding: 0 30px;" class="dis_btn min_width_inherit b-r-5 m_t_20">Read More</a>
                                            </div>`
                            plain_content = '';
                        }

                        // main blog element

                        blog_html = `<div class="dis_articleMBoxvc1 m_b_50" >
                                    <div class="dSingleA DAS2 DA-block">
                                        <div class="dis_articleBox">
                                            <div class="dis_art_topbar">
                                                <div class="dis_art_topbarL">
                                                    <div class="dis_art_profile">
                                                        <div class="dis_art_proImg">
                                                                <img id="user_image" src="${user_image}" onerror="this.onerror=null;this.src='${base_url + 'repo/images/banner_logo1.png'}'" alt="images" title="image">
                                                            </span>
                                                        </div>
                                                        <div class="dis_art_prodet">
                                                        <a href="${base_url + 'profile?user=' + user_uname}" id="user_name" class="dis_art_prodetT">${user_name}</a>
                                                            <p id="date_created" class="dis_art_prodetP">${date_created} - ${reading_time} min read</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="dis_art_topbarR">
                                                    <ul class="dis_arttbrList">
                                                        <li>
                                                            <div class="dis_artSMWrap">
                                                                <span class="dis_artSMP1" data-toggle="dropdown">
                                                                    <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="20" height="20" viewBox="0 0 8.4666667 8.4666667" ><g><g><path id="path2191" d="m7.0119634-.00000011c-.8057301 0-1.45521.65465298-1.45521 1.45520791 0 .07766.006.155514.0176.229961l-2.78019 1.516186c-.29177-.3394991-.72339-.5550048-1.20613-.5550048-.88189009 0-1.58802009.7123319-1.58802009 1.5859497 0 .873618.70613 1.588017 1.58802009 1.588017.48457 0 .91488-.215777 1.2066399-.5555221l2.7796799 1.5172201c-.011901.07445-.0176.1523001-.0176.2299599 0 .8005551.6458799 1.4546922 1.4551998 1.4546922.8064101 0 1.4547001-.6541371 1.4547001-1.4546922 0-.8005548-.64897-1.4552078-1.45469-1.4552078-.53789 0-1.0038101.2886459-1.25711.719513l-2.6899397-1.4662381c.0706-.1793319.1110999-.3740509.1110999-.577742 0-.20324-.0403-.3968328-.11058-.5756758l2.6892401-1.467094c.2532999.430867.7186099.7203699 1.25729.7203699.80347 0 1.45469-.6541359 1.45469-1.454692 0-.80055493-.64897-1.45520791-1.45469-1.45520791z" font-variant-ligatures="normal" font-variant-position="normal" font-variant-caps="normal" font-variant-numeric="normal" font-variant-alternates="normal" font-feature-settings="normal" text-indent="0" text-align="start" text-decoration-line="none" text-decoration-style="solid" text-decoration-color="rgb(0,0,0)" text-transform="none" text-orientation="mixed" white-space="normal" shape-padding="0" isolation="auto" mix-blend-mode="normal" solid-color="rgb(0,0,0)" solid-opacity="1" vector-effect="none" fill="#000000" data-original="#000000" class=""></path></g></g></svg>
                                                                </span>
                                                                <ul class="dis_artSList 2">
                                                                <li>
                                                                    <a href="javascript:;" class="dis_artSItem social-share link" data-slug="${encoded_id}/${slug}" data-title=${ar_title} data-pic=${hero_img} data-toggle="tooltip" data-placement="top" title="Link">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"  width="16" height="16" x="0" y="0" viewBox="0 0 512.092 512.092" ><g><path d="M312.453 199.601a116.167 116.167 0 0 0-20.053-16.128 119.472 119.472 0 0 0-64.427-18.859 118.952 118.952 0 0 0-84.48 34.987L34.949 308.23a119.466 119.466 0 0 0-34.91 84.318c-.042 65.98 53.41 119.501 119.39 119.543a118.7 118.7 0 0 0 84.395-34.816l89.6-89.6a8.534 8.534 0 0 0-6.059-14.592h-3.413a143.626 143.626 0 0 1-54.613-10.581 8.533 8.533 0 0 0-9.301 1.877l-64.427 64.512c-20.006 20.006-52.442 20.006-72.448 0-20.006-20.006-20.006-52.442 0-72.448l108.971-108.885c19.99-19.965 52.373-19.965 72.363 0 13.472 12.679 34.486 12.679 47.957 0a34.134 34.134 0 0 0 9.899-21.675 34.137 34.137 0 0 0-9.9-26.282z" fill="#000000" opacity="1" data-original="#000000" class=""></path><path d="M477.061 34.993c-46.657-46.657-122.303-46.657-168.96 0l-89.515 89.429a8.533 8.533 0 0 0-1.792 9.387 8.532 8.532 0 0 0 8.021 5.205h3.157a143.357 143.357 0 0 1 54.528 10.667 8.533 8.533 0 0 0 9.301-1.877l64.256-64.171c20.006-20.006 52.442-20.006 72.448 0 20.006 20.006 20.006 52.442 0 72.448l-80.043 79.957-.683.768-27.989 27.819c-19.99 19.965-52.373 19.965-72.363 0-13.472-12.679-34.486-12.679-47.957 0a34.139 34.139 0 0 0-9.899 21.845 34.137 34.137 0 0 0 9.899 26.283 118.447 118.447 0 0 0 34.133 23.893c1.792.853 3.584 1.536 5.376 2.304 1.792.768 3.669 1.365 5.461 2.048a67.799 67.799 0 0 0 5.461 1.792l5.035 1.365c3.413.853 6.827 1.536 10.325 2.133 4.214.626 8.458 1.025 12.715 1.195H284.461l5.12-.597c1.877-.085 3.84-.512 6.059-.512h2.901l5.888-.853 2.731-.512 4.949-1.024h.939a119.456 119.456 0 0 0 55.381-31.403l108.629-108.629c46.66-46.657 46.66-122.303.003-168.96z" fill="#000000" opacity="1" data-original="#000000" class=""></path></g></svg>
                                                                    </a>
                                                                </li>
                                                                    <li>
                                                                        <a href="javascript:;" class="dis_artSItem social-share facebook" data-slug="${encoded_id}/${slug}" data-title=${ar_title} data-pic=${hero_img} data-toggle="tooltip" data-placement="top" title="Facebook">
                                                                            <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="18" viewBox="0 0 155.139 155.139"><g> <g> <path d="M89.584,155.139V84.378h23.742l3.562-27.585H89.584V39.184 c0-7.984,2.208-13.425,13.67-13.425l14.595-0.006V1.08C115.325,0.752,106.661,0,96.577,0C75.52,0,61.104,12.853,61.104,36.452 v20.341H37.29v27.585h23.814v70.761H89.584z" fill="#eb581f" data-original="#eb581f" ></path> </g> </svg>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="javascript:;" class="dis_artSItem social-share twitter"  data-slug="${encoded_id}/${slug}" data-title=${ar_title} data-pic=${hero_img} data-toggle="tooltip" data-placement="top" title="Twitter">
                                                                            <svg  width="16" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 1226.37 1226.37" ><g><path d="M727.348 519.284 1174.075 0h-105.86L680.322 450.887 370.513 0H13.185l468.492 681.821L13.185 1226.37h105.866l409.625-476.152 327.181 476.152h357.328L727.322 519.284zM582.35 687.828l-47.468-67.894-377.686-540.24H319.8l304.797 435.991 47.468 67.894 396.2 566.721H905.661L582.35 687.854z" fill="#eb581f" opacity="1" data-original="#eb581f"></path></g></svg>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="javascript:;" class="dis_artSItem social-share linkedin"  data-slug="${encoded_id}/${slug}" data-title=${ar_title} data-pic=${hero_img} data-toggle="tooltip" data-placement="top" title="Linkedin">
                                                                        <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="18" viewBox="0 0 100 100" ><g><g id="_x31_0.Linkedin"><path d="m90 90v-29.3c0-14.4-3.1-25.4-19.9-25.4-8.1 0-13.5 4.4-15.7 8.6h-.2v-7.3h-15.9v53.4h16.6v-26.5c0-7 1.3-13.7 9.9-13.7 8.5 0 8.6 7.9 8.6 14.1v26h16.6z" fill="#eb581f" data-original="#eb581f" ></path><path d="m11.3 36.6h16.6v53.4h-16.6z" fill="#eb581f" data-original="#eb581f" ></path><path d="m19.6 10c-5.3 0-9.6 4.3-9.6 9.6s4.3 9.7 9.6 9.7 9.6-4.4 9.6-9.7-4.3-9.6-9.6-9.6z" fill="#eb581f" data-original="#eb581f" ></path></g></g></svg>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                        ${edit_btns}
                                                    </ul>
                                                </div>
                                            </div>
                                            <a href="javascript:;" class="dis_articleImg">
                                                <img src="${hero_img}" loading="lazy" alt="images" title="image" onerror="this.onerror=null;this.src='${on_error_himg}'" alt="images" title="image">
                                            </a>
                                            <div class="dis_articleDetails">

                                                <div class="dis_art_TAGSHARE">
                                                    <ul class="dis_artTagList" id="tags">
                                                        ${tag}
                                                    </ul>
                                                    <ul class="dis_bloginfoList 1">
                                                        <li>
                                                            <a id="category" href="${base_url + 'article?category=' + ar_category}">
                                                            <span class="disBlog_icon"><img src="${base_url + 'repo/images/blog_tag.svg'}" loading="lazy" alt="icon"></span> ${ar_category}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#">
                                                                <span class="disBlog_icon">
                                                                    <img src="${base_url + 'repo/images/blog_profile.svg'}" alt="icon">
                                                                </span> ${ar_author_name}
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <a href="${base_url + 'article/' + encoded_id + '/' + slug}" class="dis_articleTtl" >${ar_title}</a>
                                                ${content_html}

                                                ${offset == 0 && key == 0 && next_encoded_id ?
                                `<div class="dis_art_np_share">
                                                        <div class="dis_art_nps_left">
                                                            <!--a  type="button" class="dis_btn min_width_inherit b-r-5 dis_btn_loading">Preview</a-->
                                                            <a href="${base_url + 'article/' + next_encoded_id + '/' + next_slug + '?next_offset=' + (parseInt(next_offset) + 1)}" type="button" class="dis_btn min_width_inherit b-r-5 dis_btn_loading">Next</a>
                                                        </div>
                                                        <div class="dis_art_nps_right">
                                                            <span class="dis_art_nps_share">Share This Article - </span>
                                                            <ul class="dis_artSList 3">
                                                                <li>
                                                                    <a href="javascript:;" class="dis_artSItem social-share facebook" data-slug="${encoded_id}/${slug}" data-title=${ar_title} data-pic=${hero_img} data-toggle="tooltip" data-placement="top" title="Facebook">
                                                                        <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="18" viewBox="0 0 155.139 155.139"><g> <g> <path d="M89.584,155.139V84.378h23.742l3.562-27.585H89.584V39.184 c0-7.984,2.208-13.425,13.67-13.425l14.595-0.006V1.08C115.325,0.752,106.661,0,96.577,0C75.52,0,61.104,12.853,61.104,36.452 v20.341H37.29v27.585h23.814v70.761H89.584z" fill="#eb581f" data-original="#eb581f" ></path> </g> </svg>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="javascript:;" class="dis_artSItem social-share twitter"  data-slug="${encoded_id}/${slug}" data-title=${ar_title} data-pic=${hero_img} data-toggle="tooltip" data-placement="top" title="Twitter">
                                                                        <svg  width="16" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 1226.37 1226.37" ><g><path d="M727.348 519.284 1174.075 0h-105.86L680.322 450.887 370.513 0H13.185l468.492 681.821L13.185 1226.37h105.866l409.625-476.152 327.181 476.152h357.328L727.322 519.284zM582.35 687.828l-47.468-67.894-377.686-540.24H319.8l304.797 435.991 47.468 67.894 396.2 566.721H905.661L582.35 687.854z" fill="#eb581f" opacity="1" data-original="#eb581f"></path></g></svg>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="javascript:;" class="dis_artSItem social-share linkedin" data-slug="${encoded_id}/${slug}" data-title=${ar_title} data-pic=${hero_img}   data-toggle="tooltip" data-placement="top" title="Linkedin">
                                                                    <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="18" viewBox="0 0 100 100" ><g><g id="_x31_0.Linkedin"><path d="m90 90v-29.3c0-14.4-3.1-25.4-19.9-25.4-8.1 0-13.5 4.4-15.7 8.6h-.2v-7.3h-15.9v53.4h16.6v-26.5c0-7 1.3-13.7 9.9-13.7 8.5 0 8.6 7.9 8.6 14.1v26h16.6z" fill="#eb581f" data-original="#eb581f" ></path><path d="m11.3 36.6h16.6v53.4h-16.6z" fill="#eb581f" data-original="#eb581f" ></path><path d="m19.6 10c-5.3 0-9.6 4.3-9.6 9.6s4.3 9.7 9.6 9.7 9.6-4.4 9.6-9.7-4.3-9.6-9.6-9.6z" fill="#eb581f" data-original="#eb581f" ></path></g></g></svg>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="javascript:;" class="dis_artSItem social-share link" data-slug="${encoded_id}/${slug}" data-title=${ar_title} data-pic=${hero_img} data-toggle="tooltip" data-placement="top" title="Link">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"  width="16" height="16" x="0" y="0" viewBox="0 0 512.092 512.092" ><g><path d="M312.453 199.601a116.167 116.167 0 0 0-20.053-16.128 119.472 119.472 0 0 0-64.427-18.859 118.952 118.952 0 0 0-84.48 34.987L34.949 308.23a119.466 119.466 0 0 0-34.91 84.318c-.042 65.98 53.41 119.501 119.39 119.543a118.7 118.7 0 0 0 84.395-34.816l89.6-89.6a8.534 8.534 0 0 0-6.059-14.592h-3.413a143.626 143.626 0 0 1-54.613-10.581 8.533 8.533 0 0 0-9.301 1.877l-64.427 64.512c-20.006 20.006-52.442 20.006-72.448 0-20.006-20.006-20.006-52.442 0-72.448l108.971-108.885c19.99-19.965 52.373-19.965 72.363 0 13.472 12.679 34.486 12.679 47.957 0a34.134 34.134 0 0 0 9.899-21.675 34.137 34.137 0 0 0-9.9-26.282z" fill="#000000" opacity="1" data-original="#000000" class=""></path><path d="M477.061 34.993c-46.657-46.657-122.303-46.657-168.96 0l-89.515 89.429a8.533 8.533 0 0 0-1.792 9.387 8.532 8.532 0 0 0 8.021 5.205h3.157a143.357 143.357 0 0 1 54.528 10.667 8.533 8.533 0 0 0 9.301-1.877l64.256-64.171c20.006-20.006 52.442-20.006 72.448 0 20.006 20.006 20.006 52.442 0 72.448l-80.043 79.957-.683.768-27.989 27.819c-19.99 19.965-52.373 19.965-72.363 0-13.472-12.679-34.486-12.679-47.957 0a34.139 34.139 0 0 0-9.899 21.845 34.137 34.137 0 0 0 9.899 26.283 118.447 118.447 0 0 0 34.133 23.893c1.792.853 3.584 1.536 5.376 2.304 1.792.768 3.669 1.365 5.461 2.048a67.799 67.799 0 0 0 5.461 1.792l5.035 1.365c3.413.853 6.827 1.536 10.325 2.133 4.214.626 8.458 1.025 12.715 1.195H284.461l5.12-.597c1.877-.085 3.84-.512 6.059-.512h2.901l5.888-.853 2.731-.512 4.949-1.024h.939a119.456 119.456 0 0 0 55.381-31.403l108.629-108.629c46.66-46.657 46.66-122.303.003-168.96z" fill="#000000" opacity="1" data-original="#000000" class=""></path></g></svg>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>`
                                : ''
                            }

                                            </div>
                                        </div>
                                    </div>
                                </div>`;


                        // blog_html += $('#ads_container_load').html();
                        
                        $('#content').append(blog_html);
                        // $('#content').append($('#ads_container_load').html());
                        if(offset > 0)
                        AddSheMediaArticleAdsOnSinglePage('#content', 'append');
                    });

                    if(offset == 0){
                        setTimeout(() => {
                            $.each(data, function (key, val) {
                                $.each(val, function (key2, value2) {
                                    if (typeof value2['content'] != 'undefined') {
                                        if (value2['content_type'] == 'ckeditor') {
                                            console.log("#" + value2['encoded_id'] + key + key2,'ckeditor');
                                            AddSheMediaArticleAdsOnSinglePage("#" + value2['encoded_id'] + key + key2, 'append');
                                        }
                                    }
                                });
                            })
                        }, 500)
                    }
                  

                    let get_offset = $('#content_btm').attr('data-offset');                     // for getting last offset-
                    let count = (get_offset != undefined) ? parseInt(get_offset) : 0;     // and parsing it to integer
                    count += limit;                                                             // Incrementing the limit
                    contentBtm(count);                                                          // appending to last
                    $('.dis_postAnimation').hide();
                    resolve();

                } else {
                    // taboolaBelowArticleThumbnailsFeed('#content', 'append');
                    AddSheMediaAdsOnSingleArticleOnTheBottom('#content', 'append');
                    if (offset == 0) {
                        let empty_html = `<div class="dis_blogBox">
                                            <div class="no_result_wraaper">
                                                <div class="no_result_inner">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="144px" height="141px" class="no_resultsvg">
                                                    <path class="a" fill-rule="evenodd" fill="rgb(232, 233, 234)" d="M102.927,67.238 C102.868,85.522 87.999,100.297 69.715,100.239 C51.430,100.180 36.656,85.311 36.714,67.027 C36.773,48.742 51.642,33.968 69.926,34.026 C88.210,34.085 102.985,48.954 102.927,67.238 Z"></path>
                                                    <path class="b" fill-rule="evenodd" fill="rgb(189, 194, 203)" d="M142.570,51.942 L139.426,51.942 L139.426,55.084 C139.426,55.647 138.969,56.103 138.406,56.103 C137.843,56.103 137.387,55.647 137.387,55.084 L137.387,51.942 L134.243,51.942 C133.681,51.942 133.224,51.485 133.224,50.923 C133.224,50.360 133.681,49.904 134.243,49.904 L137.387,49.904 L137.387,46.761 C137.387,46.198 137.843,45.742 138.406,45.742 C138.969,45.742 139.426,46.198 139.426,46.761 L139.426,49.904 L142.570,49.904 C143.133,49.904 143.589,50.360 143.589,50.923 C143.589,51.485 143.133,51.942 142.570,51.942 ZM104.580,90.518 L136.892,123.739 C138.772,125.671 139.787,128.219 139.749,130.915 C139.711,133.611 138.624,136.130 136.689,138.009 C134.756,139.888 132.206,140.902 129.510,140.864 C126.815,140.825 124.295,139.740 122.414,137.806 L98.385,113.101 C98.384,113.100 98.382,113.098 98.381,113.097 L89.621,104.090 C84.913,106.619 79.742,108.234 74.339,108.822 C73.530,108.910 72.711,108.975 71.904,109.017 C61.657,109.542 51.580,106.270 43.533,99.805 C34.789,92.781 29.307,82.790 28.097,71.674 C26.887,60.557 30.091,49.622 37.118,40.882 C44.146,32.143 54.142,26.664 65.264,25.454 C76.387,24.245 87.326,27.447 96.070,34.471 C104.814,41.496 110.296,51.486 111.506,62.603 C112.400,70.815 110.871,79.021 107.084,86.332 C106.309,87.837 105.456,89.237 104.580,90.518 ZM126.034,134.289 C126.975,135.256 128.234,135.799 129.582,135.818 C130.929,135.837 132.204,135.330 133.171,134.390 C134.139,133.450 134.682,132.192 134.701,130.844 C134.720,129.497 134.213,128.223 133.273,127.257 L111.177,104.540 L103.939,111.573 L126.034,134.289 ZM100.411,107.945 L107.649,100.912 L101.466,94.555 C98.813,97.625 96.603,99.380 96.565,99.410 C95.738,100.098 94.887,100.750 94.015,101.369 L100.411,107.945 ZM106.621,63.134 C104.499,43.645 87.504,29.164 67.929,30.167 C67.221,30.203 66.504,30.261 65.795,30.338 C55.976,31.405 47.152,36.243 40.947,43.958 C34.743,51.674 31.914,61.328 32.982,71.142 C34.051,80.957 38.891,89.777 46.610,95.979 C54.330,102.181 63.987,105.007 73.808,103.939 C81.616,103.090 88.817,99.855 94.631,94.585 L94.665,94.554 C97.561,91.925 100.015,88.869 101.959,85.472 C105.858,78.663 107.470,70.939 106.621,63.134 ZM77.256,64.907 C76.232,64.904 75.358,64.539 74.636,63.812 C73.914,63.086 73.554,62.211 73.557,61.187 C73.560,60.164 73.926,59.291 74.652,58.569 C75.379,57.848 76.255,57.488 77.279,57.491 C78.303,57.495 79.176,57.860 79.899,58.586 C80.621,59.313 80.980,60.187 80.977,61.211 C80.974,62.235 80.609,63.107 79.882,63.829 C79.155,64.550 78.279,64.910 77.256,64.907 ZM80.400,78.271 C80.554,78.754 80.514,79.223 80.280,79.675 C80.047,80.128 79.689,80.431 79.205,80.584 C78.722,80.737 78.248,80.697 77.785,80.463 C77.322,80.230 77.014,79.862 76.862,79.360 C76.384,77.813 75.494,76.560 74.193,75.600 C72.892,74.640 71.430,74.157 69.807,74.152 C68.184,74.147 66.719,74.620 65.412,75.571 C64.105,76.523 63.207,77.770 62.719,79.315 C62.563,79.816 62.257,80.182 61.803,80.412 C61.348,80.643 60.880,80.680 60.397,80.524 C59.895,80.367 59.529,80.062 59.299,79.608 C59.068,79.154 59.031,78.685 59.187,78.203 C59.909,75.868 61.249,73.990 63.204,72.567 C65.161,71.144 67.365,70.437 69.819,70.445 C72.272,70.453 74.474,71.174 76.420,72.610 C78.367,74.045 79.694,75.932 80.400,78.271 ZM62.418,64.860 C61.394,64.856 60.521,64.491 59.798,63.765 C59.076,63.039 58.716,62.164 58.720,61.140 C58.723,60.117 59.088,59.244 59.815,58.522 C60.542,57.800 61.417,57.441 62.441,57.444 C63.465,57.447 64.338,57.812 65.060,58.539 C65.783,59.265 66.142,60.140 66.139,61.164 C66.136,62.187 65.771,63.060 65.044,63.782 C64.317,64.503 63.441,64.863 62.418,64.860 ZM87.267,12.903 C83.760,12.903 80.899,10.043 80.899,6.538 C80.899,3.026 83.760,0.173 87.267,0.173 C90.781,0.173 93.636,3.026 93.636,6.538 C93.636,10.043 90.781,12.903 87.267,12.903 ZM87.267,2.211 C84.882,2.211 82.938,4.154 82.938,6.538 C82.938,8.922 84.882,10.865 87.267,10.865 C89.653,10.865 91.597,8.922 91.597,6.538 C91.597,4.154 89.653,2.211 87.267,2.211 ZM29.003,17.857 L31.183,20.122 C31.573,20.527 31.561,21.172 31.155,21.562 C30.750,21.953 30.104,21.940 29.714,21.535 L27.534,19.270 L25.268,21.449 C24.863,21.839 24.217,21.827 23.827,21.421 C23.437,21.016 23.449,20.371 23.855,19.981 L26.120,17.802 L23.941,15.538 C23.550,15.132 23.563,14.487 23.968,14.097 C24.374,13.707 25.020,13.719 25.410,14.125 L27.589,16.389 L29.855,14.210 C30.261,13.819 30.906,13.832 31.296,14.237 C31.687,14.643 31.675,15.288 31.269,15.678 L29.003,17.857 ZM25.581,113.255 C25.676,112.700 26.202,112.327 26.758,112.421 C27.312,112.516 27.686,113.043 27.591,113.597 L27.063,116.694 L30.162,117.222 C30.717,117.318 31.090,117.844 30.996,118.398 C30.901,118.953 30.375,119.326 29.820,119.232 L26.720,118.704 L26.192,121.801 C26.097,122.356 25.571,122.729 25.015,122.634 C24.824,122.601 24.653,122.517 24.517,122.397 C24.258,122.172 24.120,121.821 24.182,121.459 L24.710,118.361 L21.611,117.833 C21.419,117.800 21.249,117.716 21.112,117.596 C20.854,117.370 20.716,117.020 20.777,116.657 C20.872,116.102 21.398,115.730 21.954,115.824 L25.053,116.352 L25.581,113.255 ZM10.330,73.926 C8.941,74.905 7.257,75.292 5.576,75.005 C4.407,74.804 3.337,74.293 2.467,73.532 C2.089,73.201 1.746,72.829 1.453,72.411 C0.469,71.028 0.086,69.339 0.369,67.664 C0.578,66.466 1.100,65.406 1.847,64.552 C3.261,62.936 5.452,62.072 7.718,62.455 C11.177,63.052 13.517,66.342 12.925,69.805 C12.637,71.475 11.714,72.943 10.330,73.926 ZM7.375,64.466 C5.022,64.070 2.788,65.654 2.381,68.006 C1.980,70.354 3.565,72.597 5.923,72.998 C7.063,73.191 8.207,72.927 9.149,72.262 C10.097,71.592 10.719,70.602 10.913,69.463 C11.314,67.106 9.729,64.872 7.375,64.466 Z"></path>
                                                    </svg>
                                                    <p>No Articles Found!</p>
                                                </div>
                                            </div>
                                        </div>`
                        $('#content').append(empty_html);
                        ControlRequest1 = false
                    }
                    $('.dis_postAnimation').hide();
                    reject();
                }

                if ($('[data-toggle="tooltip"]').length > 0) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            }
        });
    });

    return myPromise;

}

$(document).on('change', '#complete_status', function (e) {
    $('#content').empty()
    getBlogData($('#post_id').val(), 0, 'user_id', 1).then(function () {
        $(".box").each(function (index) {
            let id = $(".box:eq(" + index + ")").find("video").attr("id");
            var player = videojs.getPlayer(id);
            if (!player)
                intializeVideoJsContent("eq(" + index + ")");
        })
    }).catch((e) => console.log(e, 'ee'));
})

function getBlogPrviewData(id) {
    let formData = new FormData();
    formData.append('blog_id', id);
    manageMyAjaxPostRequestData(formData, base_url + 'articles/previewBlogData').done(function (resp) {
        if (resp.status == 1) {
            if (resp.type == 'Success') {
                let items = resp.data
                let hero_img = base_url + 'repo/images/blog_pp.png';

                $('#blg_title').text(resp.title);
                $('#category').html(`<span class="disBlog_icon"><img src="${base_url}repo/images/blog_tag.svg" alt="icon"></span> ${resp.category}`);
                $('#user_name').text(resp.user_name);
                $('#user_image').attr('src', resp.user_image);
                $('#date_created').text(resp.time_updated);

                let html = '';
                var tagArr = items[0].ar_tag.split(',');
                // let tag = '<li><a href="javascript:;" class="dis_artSItemF">Tags:</a></li>';
                let tag = '';
                tagArr.forEach(element => {
                    tag += `<li><a href="javascript:;" class="dis_artTagItem">${element}</a></li>`;
                });
                $('#tags').html(tag);

                let img = items.filter(p => p.content_type == "image")
                if (items[0]['content_type'] === 'image') {
                    hero_img = AMAZON_URL + img[0]['content']
                }

                $('#blg_main_image').attr('src', hero_img)
                items.forEach((element, key) => {
                    if (element['content_type'] == 'image') {
                        if (items[0]['content'] !== element['content']) {
                            html += `<div><img style="width:100%;" src="${AMAZON_URL + element['content']}" /></div><br>`;
                        }
                    } else if (element['content_type'] == 'video') {
                        html += `<div class="dis_art_u_iframe" ><iframe  src="${base_url + element['content']}" width="560" height="315" frameborder="0" allow="autoplay" allowfullscreen="true"></iframe></div><br>`;
                    }
                    else {
                        html += '<div  id="ads_' + key + '" class="dis_articlePera 4">' + element['content'] + '</div><br>';
                    }
                });

                $(html).insertAfter('#blg_title');

                setTimeout(() => {
                    $.each(items, function (key, value) {
                        if (typeof value['content'] != 'undefined') {
                            if (value['content_type'] == 'ckeditor') {
                                AddSheMediaArticleAdsOnSinglePage("#ads_" + key, 'append');
                            }
                        }
                    });
                }, 500)
            }
        }
    });
}

function get_categories() {
    let formData = '';
    manageMyAjaxPostRequestData(formData, base_url + 'articles/getCategory').done(function (resp) {
        if (resp.status == 1) {
            if (resp.type == 'Success') {
                let resData = resp.data;
                let html = '';
                resData.forEach(item => {
                    html += `<option value='${item.id}'>${item.cat_name}</option>`;
                });
                $('#category_select').html(html);

            }
        }
    });
}

if ($('#tokenfield').length) {
    intializeTokenField();
}

function intializeTokenField(selector = '#tokenfield') {
    if (typeof $(selector).tokenfield == 'function') {
        $(selector).tokenfield({
            autocomplete: {
                source: function (request, response) {
                    jQuery.post(node_url + "getTaglist", {
                        query: request.term
                    }, function (data) {
                        response(data);
                    });
                },
                delay: 100
            },
            showAutocompleteOnFocus: false,

        });

        $(selector).on('tokenfield:createtoken', function (event) {
            var existingTokens = $(this).tokenfield('getTokens');
            $.each(existingTokens, function (index, token) {
                if (token.value === event.attrs.value)
                    event.preventDefault();
            });
        });
    }
}

$(document).on('submit', '#blog_form_1', function (e) {
    let flag = 0;
    e.preventDefault();
    $('#blog_form_1 :input').each(function () {
        let input = $(this).val();
        let fields = ['art_title', 'art_category', 'art_tags', 'art_auth_name'];
        fields.forEach(el => {
            if (el == $(this).attr('name')) {
                if (input == '' || input.length == 0 || input == 'undefined') {
                    flag += 1;
                    Custom_notify('error', 'All fields are required !');
                }
                if ($(this).attr('name') == 'art_title' && input.length >= 200) {
                    Custom_notify('error', 'Title should not contain more than 200 characters');
                    flag++;
                }
                if ($(this).attr('name') == 'art_tags' && $(this).tokenfield('getTokens').length >= 10) {
                    Custom_notify('error', 'Article should not contain more than 10 tags');
                    flag++;
                }
                if ($(this).attr('name') == 'art_tags' && input.length >= 200) {
                    Custom_notify('error', 'Tags length could not be more than 200 characters');
                    flag++;
                }
            }
        });
    });
    if (flag == 0) {
        var formData = new FormData(this);
        manageMyAjaxPostRequestData(formData, base_url + 'articles/create_update_form1').done(function (resp) {
            if (resp.status == 1 && resp.type == 'Success') {
                let resData = resp.data;
                window.location.assign(base_url + 'articles/step2/' + resData);
            } else if (resp.type == 'Error') {
                Custom_notify('error', resp.message);
            }
        });
    }
})

$(document).on('click', '.dis_EditartBack', function () {
    let id = $('#post_id').val();
    window.location.assign(base_url + 'articles/step1/' + id);
})


/*****Start Image Search section start ******/


var Pixabay = '';

$(document).on('keyup', '.SearchPixabay', function (e) {
    if (e.key === 'Enter' || e.keyCode === 13 || e.which === 13) {
        $('#ShowPixabay').show()
        let _this = $(this);
        let searchKey = $(this).val();
        let type = $(this).data('type');
        if (Pixabay != searchKey) {
            _this.attr('data-offset', 1);
        }
        Pixabay = searchKey;
        let offset = _this.attr('data-offset');
        let apikey = "30123409-212f4a57500b4965b1ed083ce";  /** Pixabay API From ajaydeep sir**/
        let search = $.get("https://pixabay.com/api/?key=" + apikey + "&q=" + encodeURIComponent(searchKey) + "&page=" + offset + "&per_page=24");
        search.done(function (data) {
            let list = '';
            (data.hits).forEach(function (item) {
                list += `<li>
                            <div class="dis_artPixa_iBox">
                                <span class="dis_artPixa_iicon select">
                                    <img class="selectImage" src="`+ item.previewURL + `" alt=""/ data-src="` + item.largeImageURL + `" data-type="` + type + `" data-author=${item.user} data-license_id="${item.id}" data-syndication="1" data-publisher="pixabay">
                                </span>
                            </div>
                        </li>`;

            });
            if (offset == 1)
                $('#ShowPixabay').html(list);
            else
                $('#ShowPixabay').append(list);

            if (data.hits.length == 0) {
                $('#loadMoreBtn').text('No More Images')
            } else {
                $('#loadMoreBtn').text('Load More')
            }

            $('#pixabay_submit').show()
            $('#loadMoreBtn').show()
            $('[data-search=".SearchPixabay"]').show();

            _this.attr('data-offset', parseInt(offset) + 1);

        });

    }
})

$(document).on('click', '.mode', function (e) {
    $('.show_content').empty();
    let getClass = $(this).attr('id');
    let getId = $(this).attr('data-field');
    $('.show_content').attr('id', '');
    $('.show_content').attr('id', getId);
    $('#search').removeClass('SearchGifhy SearchTenor SearchPixabay SearchUnsplash');
    $('#search').addClass(getClass);
})

$(document).on('click', '.triggerSearch', function () {
    $('[data-cls="' + $(this).attr('data-search') + '"]').trigger('click');
})

$(document).on('click', '.SearchEnter', function () {
    let e = $.Event("keyup");
    e.keyCode = 13;
    $($(this).data('cls')).trigger(e);
})

$(document).on('click', '.select', function () {
    $('.select').removeClass('active');
    $(this).addClass('active');
})

$(document).on('click', '.select_video', function () {
    $('.select_video').removeClass('active');
    $(this).addClass('active');
})

$(document).on('click', '#loadMoreBtn', function () {
    var e = $.Event("keyup", { which: 13 });
    $('#img_search').trigger(e);
})

function url_image_upload(formData) {
    spinnerLoader('.submit_img_btn', 'uploading')
    manageMyAjaxPostRequestData(formData, base_url + 'articles/upload_url_s3').done(function (resp) {
        if (resp.status == 1 && resp.type == 'Success') {

            let i = resp.input_id;
            $('#images' + i).attr('data-db_id', resp.db_id);
            $('#show_img' + i).html(get_img(resp.img_src, i, resp.db_id));
            $('#show_img' + i).fadeIn('slow');
            $('#select_mode_modal').modal('hide');
            isFormChanges = true;
            update_sorting();
        }
    });
}

$(document).on('click', '#submit_url_image', function (e) {
    e.preventDefault();

    let obj = $('.select.active')
    if (obj !== undefined && obj.length > 0) {
        let img = $('.select.active').children('img');
        let formData = new FormData();
        let c = $('#input_id').val();
        let ord = order('images' + c);
        formData.append('src', img.attr('data-src'));
        formData.append('license_id', img.attr('data-license_id'));
        formData.append('publisher', img.attr('data-publisher'));
        formData.append('author', img.attr('data-author'));
        formData.append('article_id', $('#post_id').val());
        formData.append('input_id', c);
        formData.append('order_', ord);

        url_image_upload(formData)
    } else {
        Custom_notify('error', 'Please select image to submit')
    }

})

function video_url_upload(formData) {
    spinnerLoader('.submit_img_btn', 'uploading')
    manageMyAjaxPostRequestData(formData, base_url + 'articles/addVideoUrlToArticle').done(function (resp) {
        if (resp.status == 1 && resp.type == 'Success') {

            let i = resp.input_id;
            $('#images' + i).attr('data-db_id', resp.db_id);
            $('#show_img' + i).html(get_video(resp.img_src, i, resp.db_id));
            $('#show_img' + i).fadeIn('slow');
            $('#select_mode_modal').modal('hide');
            isFormChanges = true;
            update_sorting();
        }
    });
}

$(document).on('click', '#submit_video_url', function (e) {
    e.preventDefault();

    let obj = $('.select_video.active')
    if (obj !== undefined && obj.length > 0) {
        let img = $('.select_video.active').children('img');
        let formData = new FormData();
        let c = $('#input_id').val();
        let ord = order('images' + c);
        formData.append('src', img.attr('data-src'));
        formData.append('license_id', img.attr('data-license_id'));
        formData.append('publisher', img.attr('data-publisher'));
        formData.append('author', img.attr('data-author'));
        formData.append('article_id', $('#post_id').val());
        formData.append('input_id', c);
        formData.append('order_', ord);

        video_url_upload(formData)
    } else {
        Custom_notify('error', 'Please select video to submit')
    }

})

$(document).on('click', '.video_lib', function (e) {
    let start = 0
    let limit = 20;
    let formData = new FormData();
    formData.append('limit', limit)
    formData.append('start', start)
    showVideoLibrary(formData, 'html', $(this))

    $('#loadMoreBtnVideoLib').attr('data-start', limit)
})

$(document).on('click', '#loadMoreBtnVideoLib', function (e) {
    let start = parseInt($('#loadMoreBtnVideoLib').attr('data-start'))
    let limit = 20;
    let formData = new FormData();
    formData.append('limit', limit)
    formData.append('start', start)
    showVideoLibrary(formData, 'append', $(this))

    $('#loadMoreBtnVideoLib').attr('data-start', start + limit)

})

$(document).on('keyup', '.search_video_lib', function (e) {

    if (e.key === 'Enter' || e.keyCode === 13 || e.which === 13) {
        let start = 0
        let limit = 20;
        let formData = new FormData();
        formData.append('limit', limit)
        formData.append('start', start)
        showVideoLibrary(formData, 'html', $(this))

        $('#loadMoreBtnVideoLib').attr('data-start', start + limit)
    }


})

let showVideoLibrary = function (formData, appendType, _this) {
    let searchbar = $('.search_video_lib').val()
    if (searchbar.trim() != '') {
        formData.append('search', searchbar)
    }

    manageMyAjaxPostRequestData(formData, base_url + 'articles/getVideoLibraryData').done(function (resp) {
        let list = '';
        if (resp.status == 1) {
            if (resp.type == "Success") {
                (resp.data).forEach(function (item) {
                    let no_thumb_img = base_url + 'repo/images/nothumb/light.jpg';
                    let img = addKeyWordInName(item.image_name, '_thumb') + '.webp'
                    let src = AMAZON_URL + 'aud_' + item.user_id + '/images' + img
                    src = item.image_name == '' ? no_thumb_img : src;
                    list += `<li>
                                <div class="dis_artPixa_iBox">
                                    <span class="dis_artPixa_iicon select_video">
                                        <img class="selectImage" src="`+ src + `" onerror="this.onerror=null;this.src='${no_thumb_img}'" alt=""/ data-license_id="${item.post_id}" data-publisher="${item.user_name}" data-author="${item.user_name}" data-src="` + item.uploaded_video + `" >
                                    </span>
                                    <p class="dis_artPixa_ihead">${(item.title.length > 25) ? item.title.substring(0, 25) + "..." : item.title}</p>
                                    <p  class="dis_artPixa_iex">${item.uploaded_video.split('.').pop()}</p>
                                </div>
                            </li>`;

                });

                if (appendType == 'append') {
                    $('#ShowVideoLibrary').append(list);
                } else
                    if (appendType == 'html') {
                        $('#ShowVideoLibrary').html(list);
                    }
            }
            else if (resp.type == "Empty") {
                $('#loadMoreBtnVideoLib').text('No More Videos');
            }
        }

    });
}

/*****END Image Search section END ******/

/*****START create_blog_2 START ******/

function get_focs(cntr) {
    $(".upper").hide();
    $(".lower").hide();

    $('#upper' + cntr).show(400);
    $('#lower' + cntr).show(400);
}

function insert() {
    // console.log('save article');
    let c = 0;
    $(".focus :input").each(function () {

        let id = $(this).attr('id');
        let cr_len = parseInt($(this).attr("data-length")) + 4;

        if ($(this).attr("data-type") == "ckeditor" && JSON.stringify(CKEDITOR.instances[id].getData()).length !== cr_len) {
            let cnt = $(this).attr('data-counter');
            let leng = JSON.stringify(CKEDITOR.instances[id].getData());
            let len = leng.length;
            let db_id = $(this).attr('data-db_id');
            let data = CKEDITOR.instances[id].getData();
            data = data.replaceAll("javascript:void(0);", "");

            function base64Encode(str) {
                return btoa(unescape(encodeURIComponent(str)));
            }

            data = base64Encode(data);


            if (CKEDITOR.instances[id].getData() !== '' && $(this).attr('data-db_id') == 0) {
                update_insert(id, data, db_id, len, cnt);
            }

            if (db_id != 0) {
                update_insert(id, data, db_id, len, cnt);
            }
        };

        c += 1;

    });
}

function removeFocus() {
    $(".upper").hide();
    $(".lower").hide();
    $('.focus').removeClass('active');
}

$(document).ready(function () {

    isMouseDown = false

    $('body').mousedown(function () {
        isMouseDown = true;
    })
        .mouseup(function () {
            isMouseDown = false;
        });

    setTimeout(function () {
        $('.dis_createBBtm').on('mouseenter', '.focus', function () {
            if (!$(this).hasClass('active') && isMouseDown == false) {
                $('.focus').removeClass('active');
                $(this).addClass('active')
                get_focs(parseInt($(this).attr('data-counter')));

            }
        })
    }, 3000)
})

let editor = '';
$(document).on('click', '#addNewPara', function (event) {
    counter += 1;
    removeFocus()

    let id = $(this).attr('data-id');
    let count = $(this).attr('data-counter');
    let getHtml = get_editor_container(counter, 0, '', 'new');

    if (id == 'upper') {
        $(getHtml).insertBefore("#sorting_" + count);
    } else if (id == 'lower') {
        $(getHtml).insertAfter("#sorting_" + count);
    }
    $('.first').remove();
    CKEDITOR.replace('editor' + counter);
    ckeditorConfigs()
    //insert();
    initSelect2()
    Audition.tooltip()
});

$(document).on('click', '#addNewPart', function (event) {
    counter += 1;
    removeFocus()

    let id = $(this).attr('data-id');
    let count = $(this).attr('data-counter');

    let formData = new FormData();
    formData.append('art_id', $('#post_id').val());

    manageMyAjaxPostRequestData(formData, base_url + 'articles/addNewPart').done(function (resp) {
        if (resp.status == 1 && resp.type == 'Success') {
            let getHtml = get_part(counter, resp.db_id);

            if (id == 'upper') {
                $(getHtml).insertBefore("#sorting_" + count);
            } else if (id == 'lower') {
                $(getHtml).insertAfter("#sorting_" + count);
            }

            update_sorting();
        }
    });

    setTimeout(function () {
        $('.first').remove();
    }, 500)
    insert();
});

$(document).on('click', '#addNewImage', function (event) {
    counter += 1;
    removeFocus()

    $('#select_mode_modal').modal({ backdrop: 'static', keyboard: false });

    let id = $(this).attr('data-id');
    let count = $(this).attr('data-counter');
    let get_input = $('#select_mode_modal').find('#articleCustom');
    $('#select_mode_modal').find('#input_id').val(counter);
    get_input.attr('data-for_id', id + counter);
    get_input.attr('data-counter', counter);

    let getHtml = get_file_container(counter, 0, '');

    if (id == 'upper') {
        $(getHtml).insertBefore("#sorting_" + count);
    } else if (id == 'lower') {
        $(getHtml).insertAfter("#sorting_" + count);
    }
    $('.first').remove();
    //insert();
});

function show_img_blob(input) {
    let html = '';
    let src = '';
    if (input.files.length == 0) {
        $('#file_field').show();
        $('#show_blob').hide();
        $('#show_blob_img').attr('src', src);
    } else {
        src = (window.URL ? URL : webkitURL).createObjectURL(input.files[0]);
        $('#file_field').hide();
        $('#show_blob').show();
        $('#show_blob_img').attr('src', src);
    }

}





$('#select_mode_modal').on('hidden.bs.modal', function () {
    resetModal()
})

function resetModal() {
    let num = $('#select_mode_modal').find('#articleCustom').attr('data-counter')

    if ($('#images' + num).attr('data-db_id') == 0) {
        $('.first').remove()

        $('#sorting_' + num).remove()
        $('#upper' + num).remove()
        $('#mid' + num).remove()
        $('#lower' + num).remove()

    }

    $('#img_search').val('')
    $('#file_field').show()
    $('#show_blob').hide()
    $('#ShowPixabay').empty()
    $('#ShowPixabay').fadeOut('slow')
    $('#pixabay_submit').hide()
    $('#loadMoreBtn').hide()
    $('.focus').removeClass('active');
    $('#submit_image').trigger("reset");
    $('.submit_img_btn').prop('disabled', false).text('Submit').find('.post_spinner').remove()
    check_btns()
}

function order(id) {
    let order = 0;
    let ret = 0;
    $(".focus :input").each(function () {
        if ($(this).attr('id') == id) {
            ret = order;
        };
        order += 1;
    });
    return ret;
}

$(document).ready(function () {
    let page = $('#page').val();
    if (page == 'create_blogs_2') {


        $('.dis_artUploadImage').on('drag dragstart dragend dragover dragenter dragleave drop', function (event) {
            event.preventDefault();
            event.stopPropagation();
        })
            .on('dragover dragenter', function () {
                $(this).addClass('is-dragover');
            })
            .on('dragleave dragend drop', function () {
                $(this).removeClass('is-dragover');
            })
            .on('drop', function (event) {
                $('#articleCustom').prop('files', event.originalEvent.dataTransfer.files);
                $('#articleCustom').trigger('change');
            });


        let formData = { 'post_id': $('#post_id').val() };
        manageMyAjaxPostRequestData(formData, base_url + 'articles/check_blog_updates').done(function (resp) {
            if (resp.status == 1) {
                if (resp.type == 'Success') {
                    $(document).prop('title', 'Discovered | Edit Article');
                    $('#head').html('Edit Article')
                    $('#submit').text('Save')
                    let resData = resp.data;

                    let html = ``;
                    let cnt = 0;
                    resData.forEach(el => {
                        if (el.content_type == 'image') {
                            let img = get_img(el.content, cnt, el.id);
                            html += get_file_container(cnt, el.id, img);

                        } else if (el.content_type == 'ckeditor') {
                            let str_len = el.content;
                            html += get_editor_container(cnt, el.id, el.content, str_len.length);

                        } else if (el.content_type == 'video') {
                            let vid = get_video(el.content, cnt, el.id);
                            html += get_file_container(cnt, el.id, vid);

                        } else if (el.content_type == 'part') {
                            html += get_part(cnt, el.id);
                        }
                        cnt += 1;
                    });
                    counter = cnt;
                    $('#para').html(html);
                    get_focs(0);
                    init_ckeditor();
                    initSelect2()

                    Audition.tooltip();

                } else {
                    counter = 0;
                }
            }
        });

        $('#para').sortable({
            // placeholder: "dis_sortable_placeholder",
            // group: 'list',
            // animation: 200,
            // ghostClass: 'ghost',
            // onSort: reportActivity,
            tolerance: 'pointer',
            opacity: 0.6,
            refreshPositions: true,
            containment: ".main_contnt_wrapper",
            connectWith: "#para",
            handle: '.dis_art_dragIcon',
            scroll: true,
            scrollSensitivity: 0,
            scrollSpeed: 10,
            change: function (event, ui) {
                setTimeout(() => {
                    ui.placeholder.css({ visibility: 'visible', border: '1px solid #EC5800' });
                }, 500);
            },
            sort: function (event, ui) {
                var currentScrollTop = $(window).scrollTop(),
                    topHelper = ui.position.top,
                    delta = topHelper - currentScrollTop;
                setTimeout(function () {
                    $(window).scrollTop(currentScrollTop + delta);
                }, 5);
            },
            start: function (event, ui) {
                // ui.item.find(".dis_art_upImg").addClass("dis_art_changeOnDrag")
                removeFocus()
                var id_textarea = ui.item.find(".editor").attr("id");
                if (id_textarea != undefined) {
                    CKEDITOR.instances[id_textarea].destroy();
                }
                ui.placeholder.height(ui.item.find(".dis_art_upImg").height());
                // ui.placeholder.height(ui.item.height());
            },
            stop: function (event, ui) {
                ui.item.find(".dis_art_upImg").removeClass("dis_art_changeOnDrag")
                var id_textarea = ui.item.find(".editor").attr("id");
                if (id_textarea != undefined) {
                    CKEDITOR.replace(id_textarea);
                }
                update_sorting()
            }

        }).disableSelection();

    }
});

function init_ckeditor() {
    $(".editor").each(function () {
        if ($(this).attr("data-type") == "ckeditor") {
            CKEDITOR.replace($(this).attr('id'));
            ckeditorConfigs()
        };
    });
}


$(document).on('mousedown', '.dis_art_dragIcon', function () {
    $(this).closest('.dis_art_upImg').addClass('dis_art_changeOnDrag');
})
var isFormChanges = false;

function ckeditorConfigs() {
    // including iframe plugin.
    CKEDITOR.config.versionCheck = false;
    CKEDITOR.plugins.addExternal('iframe', base_url + 'repo/js/plugin/ckeditor/iframe/');
    CKEDITOR.plugins.addExternal('lineheight', base_url + 'repo/js/plugin/ckeditor/lineheight/');
    CKEDITOR.plugins.addExternal('font', base_url + 'repo/js/plugin/ckeditor/font/');
    CKEDITOR.config.embed_provider = '//ckeditor.iframe.ly/api/oembed?url={url}&callback={callback}';
    CKEDITOR.config.toolbar = 'Basic';
    CKEDITOR.config.height = 200;
    //CKEDITOR.config.fontSize_defaultLabel = '18';
    CKEDITOR.config.toolbar_Basic =
        [
            { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
            { name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll', '-', 'SpellChecker'] }, //, 'Scayt'
            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'] },
            { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'] },
            { name: 'insert', items: ['mediaEmbed', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe'] },
            { name: 'styles', items: ['Format', 'FontSize', 'lineheight'] }, //'Format',
            { name: 'colors', items: ['TextColor', 'BGColor'] },
            { name: 'document', items: ['Source'] },
            { name: 'links', items: ['Link'] },
        ];
    CKEDITOR.config.extraPlugins = 'iframe, lineheight, font';
    //CKEDITOR.config.contentsCss= base_url + 'repo/js/plugin/ckeditor/lineheight/custom-styles.css';
    CKEDITOR.config.allowedContent = true;
    //CKEDITOR.config.allowedContent = '*[*](style);';
    //CKEDITOR.config.allowedContent = 'p h1 h2 h3 a[!href] img[!src,alt](style);';
    // Initialize each CKEditor instance

    
    CKEDITOR.on('instanceReady', function (evt) {
        var editor = evt.editor; // Get the specific editor instance

        editor.on('change', function () { // Listen for the change event
            isFormChanges = true;
        });

        editor.on('selectionChange', function () {
            var selection = editor.getSelection();
            var element = selection.getStartElement();
            if (element) {
                var tagName = element.getName();
                // Function to check if the element is inside a header tag
                function isInsideHeaderTag(el) {
                    while (el) {
                        var name = el.getName();
                        if (name === 'h1' || name === 'h2' || name === 'h3' || name === 'h4' || name === 'h5' || name === 'h6') {
                            return true;
                        }
                        el = el.getParent();
                    }
                    return false;
                }
                // Function to check if the element is inside a header tag
                function isInsideSpanTag(el) {
                    while (el) {
                        var name = el.getName();
                        if (name === 'span') {
                            return true;
                        }
                        el = el.getParent();
                    }
                    return false;
                }
                // Determine if the element is a header tag or a span inside a header tag
                var isHeaderTag = tagName === 'h1' || tagName === 'h2' || tagName === 'h3' || tagName === 'h4' || tagName === 'h5' || tagName === 'h6';
                var isSpanInHeader = tagName === 'span' && isInsideHeaderTag(element);
                var nestedSpanTag = tagName === 'h1' && tagName === 'span' && isInsideSpanTag(element);

                if (isHeaderTag || isSpanInHeader || nestedSpanTag) {
                    // Disable font size button for header tags
                    var fontSizeButton = editor.ui.get('FontSize');
                    if (fontSizeButton) {
                        fontSizeButton.setState(CKEDITOR.TRISTATE_DISABLED);
                    }
                } else {
                    // Enable font size button for other tags
                    var fontSizeButton = editor.ui.get('FontSize');
                    if (fontSizeButton) {
                        fontSizeButton.setState(CKEDITOR.TRISTATE_OFF);
                    }
                }
            }
        });
    });


    window.addEventListener('beforeunload', function (e) {
        if (isFormChanges) {
            var confirmationMessage = 'You have unsaved changes. Are you sure you want to leave?';
            e.returnValue = confirmationMessage; // Gecko, Trident, Chrome 34+
            return confirmationMessage; // Gecko, WebKit, Chrome <34
        }
    });

}

function get_img(src, cnt, db_id) {
    // console.log(src, cnt, db_id, 'src, cnt, db_id');
    let src1 = AMAZON_URL + src;
    let html = `<li>
                    <div class="dis_field_box ">
                        <div class="dis_art_upImg ">
                            <img class="" data-num="${cnt}" style='width:100%;' src='${src1}' >
                            <ul class="dis_art_boxOPtion">
                                <li>
                                    <span id="remove${cnt}" data-db_id="${db_id}" data-counter="${cnt}" data-key="${src}" data-type="file" class="dis_cross_sign remove"></span>
                                </li>
                                <li>
                                    <span class="dis_art_dragIcon" title="Hold to drag" ><svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="18" height="18" viewBox="0 0 64 64" ><g><path d="m58.995 35.148-5.766 6.368c-2.634 2.912-7.15-1.045-4.448-4.028L50.58 35.5H35v15.078l1.988-1.799c2.936-2.663 6.972 1.783 4.028 4.449l-6.365 5.763a3.99 3.99 0 0 1-5.3.003l-6.367-5.766c-2.948-2.67 1.094-7.11 4.028-4.448L29 50.579V35.501H13.42l1.8 1.987c2.71 2.994-1.842 6.91-4.45 4.028l-5.762-6.365a3.992 3.992 0 0 1-.003-5.299l5.766-6.368c2.66-2.94 7.113 1.085 4.448 4.029l-1.799 1.986H29V13.421l-1.988 1.799c-2.935 2.662-6.973-1.783-4.028-4.449l6.365-5.763a3.99 3.99 0 0 1 5.3-.003l6.367 5.766c2.997 2.714-1.153 7.055-4.028 4.448L35 13.421v16.078H50.58l-1.8-1.986c-2.66-2.94 1.786-6.975 4.45-4.029l5.762 6.365a3.992 3.992 0 0 1 .003 5.3z" fill="#ffffff" data-original="#000000" class=""></path></g></svg></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>`;
    return html;
}

function get_video(src, cnt, db_id) {
    let src1 = base_url + src;
    let html = `<li>
                    <div class="dis_field_box ">
                        <div class="dis_art_upImg dis_art_u_iframe "><!--  -->
                        <iframe data-num="${cnt}" src="${src1}" width="560" height="315" frameborder="0" allow="autoplay" allowfullscreen="true" ></iframe>
                        <ul class="dis_art_boxOPtion">
                            <li>
                                <span id="remove${cnt}" data-db_id="${db_id}" data-counter="${cnt}" data-key="${src}" class="dis_cross_sign remove"></span>
                            </li>
                            <li>
                                <span class="dis_art_dragIcon" title="Hold to drag" ><svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="18" height="18" viewBox="0 0 64 64" ><g><path d="m58.995 35.148-5.766 6.368c-2.634 2.912-7.15-1.045-4.448-4.028L50.58 35.5H35v15.078l1.988-1.799c2.936-2.663 6.972 1.783 4.028 4.449l-6.365 5.763a3.99 3.99 0 0 1-5.3.003l-6.367-5.766c-2.948-2.67 1.094-7.11 4.028-4.448L29 50.579V35.501H13.42l1.8 1.987c2.71 2.994-1.842 6.91-4.45 4.028l-5.762-6.365a3.992 3.992 0 0 1-.003-5.299l5.766-6.368c2.66-2.94 7.113 1.085 4.448 4.029l-1.799 1.986H29V13.421l-1.988 1.799c-2.935 2.662-6.973-1.783-4.028-4.449l6.365-5.763a3.99 3.99 0 0 1 5.3-.003l6.367 5.766c2.997 2.714-1.153 7.055-4.028 4.448L35 13.421v16.078H50.58l-1.8-1.986c-2.66-2.94 1.786-6.975 4.45-4.029l5.762 6.365a3.992 3.992 0 0 1 .003 5.3z" fill="#ffffff" data-original="#000000" class=""></path></g></svg></span>
                            </li>
                        </ul>
                        </div>
                    </div>
                </li>`;
    return html;
}

function update_insert(a, data, db_i, len, cnt) {
    let formData = new FormData();

    formData.append('art_id', $('#post_id').val());
    formData.append('input_id', a);
    formData.append('order', order(a));
    formData.append('type', 'ckeditor');
    formData.append('content', data);
    formData.append('db_id', db_i);
    formData.append('length', len);
    formData.append('counter', cnt);

    send_input_data(formData)
}

function update_sorting() {
    let formData = new FormData();
    let id = 0, c = 0;
    let db_id = [], order_ = []

    // $(".focus :input").each(function (item,index) {
    //     console.log(item,index,'item,index');
    //     id = parseInt($(this).attr('data-db_id'));
    //     if (id !== 0) {
    //         db_id.push(id)
    //         order_.push(c)
    //         c += 1;
    //     }
    // });
    $('input[data-db_id] ,textarea[data-db_id]').each(function (item, index) {
        id = parseInt($(this).attr('data-db_id'));
        if (id !== 0) {
            db_id.push(id)
            order_.push(c)
            c += 1;
        }
    });

    $('.sliderPart').length && $('.sliderPart').each(function (key, val) {
        val.innerhtml = `Slide ${key + 1} Ends Here &nbsp;`;
    })

    formData.append('db_id', db_id);
    formData.append('order_', order_);
    manageMyAjaxPostRequestData(formData, base_url + 'articles/update_sorting').done(function (resp) { });
}

function get_input_counts() {
    let text = 0;
    let c = 0
    let i = 0;
    let ckeditor = 0;

    $(".editor").each(function () {
        let _this = $(this);

        let type = _this.attr('data-type')
        let id = _this.attr('id');

        if (type == 'ckeditor') {

            if (CKEDITOR.instances[id].getData().split(' ').length > 30 && c == 0) {  //checking the first ckeditor words count
                text += 1;
            }
            c += 1
            if (i == 0 || i == 1) {
                ckeditor++;
            }
            i++;
        }

    });
    let arr = { 'text': text, 'ckeditor': ckeditor };
    return arr;
}

$(document).on('click', '#preview', function (e) {
    let id = $('#post_id').val();
    insert()
    window.open(base_url + "articles/preview/" + id, '_blank');
})

$(document).on('click', '#submit', function () {
    let data = get_input_counts();
    let id = $('#post_id').val();

    if (data.text !== 0 && data.ckeditor !== 0) {
        insert()
        let formData = new FormData();
        formData.append('post_id', $('#post_id').val())

        manageMyAjaxPostRequestData(formData, base_url + 'articles/post_article').done(function (resp) {

            if (resp['status'] == 1 && resp['type'] == 'Success') {

                Custom_notify('success', resp['message']);
                let slug = resp.data[0].ar_slug;
                let enc_id = resp.data[0].encoded_id;
                isFormChanges = false;
                setTimeout(() => { window.location.href = base_url + "article?user=" + user_uname; }, 1500);

            }

        });
    } else {
        if (data.ckeditor == 0) {
            Custom_notify('error', 'The article must contain a paragraph after your hero image !');
        } else {
            Custom_notify('error', 'The article must contain first Paragraph with more than 30 words !');
        }

    }
});

$(document).on('click', '.remove', function () {
    let key = $(this).attr('data-key');
    let db_id = $(this).attr('data-db_id');
    let div_num = $(this).attr('data-counter');
    let type = $(this).attr('data-type');
    // console.log(db_id, 'db_id');
    confirm_popup_function(
        "Delete",
        "Are you sure you want to delete this element?",
        'delete_object(' + db_id + ',' + div_num + ',"' + key + '","' + type + '")'
    );
});

function delete_object(db_id, div_num, key, type) {
    $("#conf_btn")
        .text("Deleting ")
        .append('<i class="fa fa-spinner fa-spin post_spinner"></i>')
        .prop("disabled", true);
    if (db_id == 0) {
        $("#conf_btn").text("Delete").prop("disabled", false);
        success_popup_function("Element has been removed");
        remove_object(div_num);
    } else {
        let formData = new FormData();
        formData.append('key', key);
        formData.append('db_id', db_id);
        manageMyAjaxPostRequestData(formData, base_url + 'articles/remove_object').done(function (resp) {
            if (resp['status'] == 1 && resp['type'] == 'Success') {
                $("#conf_btn").text("Delete").prop("disabled", false);
                success_popup_function("Element has been removed");
                remove_object(div_num);
                if (type == 'part') {
                    part--;
                }

            }
        });
    }
}

function remove_object(obj) {
    // console.log(obj, 'objobj');
    $("#sorting_" + obj).fadeOut(800, function () { $(this).remove(); });
    setTimeout(() => {
        check_btns()
        update_sorting()
    }, 1000);

}
function get_file_container(cntr, db_id, img_data) {

    let html = `<div class="col-md-12" id="sorting_${cntr}">
                    ${upperBtn(cntr)}
                    <div id="mid${cntr}" class="col-md-12 focus" data-counter="${cntr}">
                        <div class="m_b_30">
                            <input type="file" class="hide" id="images${cntr}" data-db_id="${db_id}" data-type="file" data-num="${cntr}" name="images${cntr}[]" multiple="multiple" onChange="img_pathUrl_show(this, ${cntr}, 'upper');">
                            <ul class="dis_createBlogIB" id="show_img${cntr}">${img_data}</ul>
                        </div>
                    </div>
                    ${lowerBtn(cntr)}
                </div>`;

    return html;

}

function get_editor_container(cntr, db_id, val, length) {

    let html = `<div class="col-md-12" id="sorting_${cntr}">
                    ${upperBtn(cntr)}
                    <div id="mid${cntr}" class="col-md-12 focus" data-counter="${cntr}">
                        <div class="m_b_30 dis_art_upImg">
                            <textarea class="editor" id="editor${cntr}" data-length="${length}" data-counter="${cntr}" data-type="ckeditor" data-db_id="${db_id}" name="ckeditor[]" >${val}</textarea>
                            ${ShowAiPrompt(className = 'articles_page', idName = 'editor' + cntr)}
                            <ul class="dis_art_boxOPtion">
                                <li>
                                    <span  id="remove${cntr}" data-key="0" data-counter="${cntr}" data-db_id="${db_id}" data-type="file" class="dis_cross_sign remove"></span>
                                </li>
                                <li>
                                    <span class="dis_art_dragIcon" title="Hold to drag" ><svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="18" height="18" viewBox="0 0 64 64" ><g><path d="m58.995 35.148-5.766 6.368c-2.634 2.912-7.15-1.045-4.448-4.028L50.58 35.5H35v15.078l1.988-1.799c2.936-2.663 6.972 1.783 4.028 4.449l-6.365 5.763a3.99 3.99 0 0 1-5.3.003l-6.367-5.766c-2.948-2.67 1.094-7.11 4.028-4.448L29 50.579V35.501H13.42l1.8 1.987c2.71 2.994-1.842 6.91-4.45 4.028l-5.762-6.365a3.992 3.992 0 0 1-.003-5.299l5.766-6.368c2.66-2.94 7.113 1.085 4.448 4.029l-1.799 1.986H29V13.421l-1.988 1.799c-2.935 2.662-6.973-1.783-4.028-4.449l6.365-5.763a3.99 3.99 0 0 1 5.3-.003l6.367 5.766c2.997 2.714-1.153 7.055-4.028 4.448L35 13.421v16.078H50.58l-1.8-1.986c-2.66-2.94 1.786-6.975 4.45-4.029l5.762 6.365a3.992 3.992 0 0 1 .003 5.3z" fill="#ffffff" data-original="#000000" class=""></path></g></svg></span>
                                </li>
                            </ul>

                        </div>
                    </div>

                    ${lowerBtn(cntr)}
                </div>`;
    return html;

}
var part = 1;
function get_part(cntr, db_id) {
    let html = `<div class="col-md-12" id="sorting_${cntr}">
                    ${upperBtn(cntr)}
                    <div id="mid${cntr}" class="col-md-12 focus" data-counter="${cntr}">
                        <div class="m_b_30 dis_art_upImg dis_art_seprator">
                            <div class="sliderPart">Slide ${part++} Ends Here &nbsp;</div>
                            <input type="text" class="hide" data-db_id="${db_id}">
                            <ul class="dis_art_boxOPtion">
                                <li>
                                    <span  id="remove${cntr}" data-key="0" data-counter="${cntr}" data-db_id="${db_id}" data-type="part" class="dis_cross_sign remove"></span>
                                </li>
                                <li>
                                    <span class="dis_art_dragIcon" title="Hold to drag" ><svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="18" height="18" viewBox="0 0 64 64" ><g><path d="m58.995 35.148-5.766 6.368c-2.634 2.912-7.15-1.045-4.448-4.028L50.58 35.5H35v15.078l1.988-1.799c2.936-2.663 6.972 1.783 4.028 4.449l-6.365 5.763a3.99 3.99 0 0 1-5.3.003l-6.367-5.766c-2.948-2.67 1.094-7.11 4.028-4.448L29 50.579V35.501H13.42l1.8 1.987c2.71 2.994-1.842 6.91-4.45 4.028l-5.762-6.365a3.992 3.992 0 0 1-.003-5.299l5.766-6.368c2.66-2.94 7.113 1.085 4.448 4.029l-1.799 1.986H29V13.421l-1.988 1.799c-2.935 2.662-6.973-1.783-4.028-4.449l6.365-5.763a3.99 3.99 0 0 1 5.3-.003l6.367 5.766c2.997 2.714-1.153 7.055-4.028 4.448L35 13.421v16.078H50.58l-1.8-1.986c-2.66-2.94 1.786-6.975 4.45-4.029l5.762 6.365a3.992 3.992 0 0 1 .003 5.3z" fill="#ffffff" data-original="#000000" class=""></path></g></svg></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    ${lowerBtn(cntr)}
                </div>`;
    return html;
}

var slider_type = $('#slider_type').val();
check_btns()
function check_btns() {
    if (isEmpty($('#para'))) {
        $('#para').append(`<div id="sorting_0" class="col-md-12 first lower" data-counter="0">
                                ${lowerBtn(0)}
                            </div>`);
    }
}



function lowerBtn(cntr) {
    return `<div id="lower${cntr}" class="col-md-12 lower" data-focus="1">
                <div class="m_b_30">
                    <ul class="dis_createBlogIB dis_createBlogMP">
                        <li>
                            <div class="dis_field_box">
                                <div class="dis_field_wrap">
                                <button id="addNewImage" data-counter="${cntr}" data-id="lower" type="button" class="dis_attach_label dis_field_input">Add New Media</button>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="dis_field_box">
                                <div class="dis_field_wrap">
                                    <button id="addNewPara" data-counter="${cntr}" data-id="lower" type="button" class="dis_attach_label dis_field_input">Add New Paragraph</button>
                                </div>
                            </div>
                        </li>
                        ${slider_type == 'slider' ?
            `<li>
                                <div class="dis_field_box">
                                    <div class="dis_field_wrap">
                                        <button id="addNewPart" data-counter="${cntr}" data-id="lower" type="button" class="dis_attach_label dis_field_input">Add New Slide</button>
                                    </div>
                                </div>
                            </li>`
            : ''
        }
                    </ul>
                </div>
            </div>`;
}

function upperBtn(cntr) {
    return `<div id="upper${cntr}" class="col-md-12 upper" data-counter="${cntr}">
        <div class="m_b_30">
            <ul class="dis_createBlogIB dis_createBlogMP">
                <li>
                    <div class="dis_field_box">
                        <div class="dis_field_wrap">
                            <button id="addNewImage" data-counter="${cntr}" data-id="upper" type="button" class="dis_attach_label dis_field_input">Add New Media</button>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="dis_field_box">
                        <div class="dis_field_wrap">
                            <button id="addNewPara" data-counter="${cntr}" data-id="upper" type="button" class="dis_attach_label dis_field_input">Add New Paragraph</button>
                        </div>
                    </div>
                </li>
                ${slider_type == 'slider' ?
            `<li>
                        <div class="dis_field_box">
                            <div class="dis_field_wrap">
                                <button id="addNewPart" data-counter="${cntr}" data-id="upper" type="button" class="dis_attach_label dis_field_input">Add New Slide</button>
                            </div>
                        </div>
                    </li>`
            : ''
        }
            </ul>
        </div>
    </div>`;
}

function isEmpty(el) {
    return !$.trim(el.html())
}

function send_input_data(formData) {
    manageMyAjaxPostRequestData(formData, base_url + 'articles/insertUpdatePara').done(function (resp) {
        if (resp.status == 1) {
            if (resp.type == 'Success') {
                $('#' + resp.input_id).attr('data-db_id', resp.db_id);
                $('#' + resp.input_id).attr('data-length', resp.content_length);
                $('#remove' + resp.counter).attr('data-db_id', resp.db_id);
                update_sorting();
            }
        }
    });
}

$(document).on('submit', '#submit_image', function (e) {
    let flag = 0;
    let form = this
    $('#error').val(0);
    e.preventDefault();
    $('#submit_image :input:not([type=button])').each(function () {
        let input = $(this);
        if (input.attr('type') == 'text') {
            if (input.val() == '' || input.val() == 0) {
                flag += 1;
            }
        }

        else if (input.attr('type') == 'file') {
            let content = this.files[0];

            if (!(this.files).length) {
                Custom_notify('error', 'Please choose a file to upload first !');
                flag += 1;
                return false;
            }

            else if ($.inArray(content.type, ["image/jpeg", "image/png", "image/jpg", "image/gif"]) == -1) {
                this.value = '';
                $('#file_field').show();
                $('#show_blob').hide();
                $('#show_blob_img').attr('src', '');
                Custom_notify('error', 'Please choose a correct file format !');
                flag += 1;
                return false;
            }

            else {

                var img = new Image();
                img.src = window.URL.createObjectURL(content);

                img.onload = function () {
                    var width = img.naturalWidth;
                    var height = img.naturalHeight;

                    window.URL.revokeObjectURL(img.src);

                    if (width >= 1280) {
                        // send_data(formData)
                        spinnerLoader('.submit_img_btn', 'uploading')
                        send_img(flag, form)

                        let html = '';
                        let src = '';
                    }
                    else {
                        $('#error').val(1)
                        Custom_notify('error', 'Minimum image resolution should be 1280*720px !');
                    }
                };

            }
        }


    });

    let n = $('#error').val();
})

function spinnerLoader(element, type) {
    $(element)
        .text(type)
        .append('<i class="fa fa-spinner fa-spin post_spinner"></i>')
        .prop("disabled", true);
}

function send_img(flag, form) {
    if (flag == 0) {
        let c = $('#input_id').val();
        let formData = new FormData(form);
        let ord = order('images' + c);
        let db_id = $('#images' + c).attr('data-db_id');
        formData.append('article_id', $('#post_id').val());
        formData.append('order_', ord);
        formData.append('db_id', db_id);

        manageMyAjaxPostRequestData(formData, base_url + 'articles/upload_image').done(function (resp) {
            if (resp.status == 1 && resp.type == 'Success') {
                let i = resp.input_id;
                $('#images' + i).attr('data-db_id', resp.db_id);
                $('#show_img' + i).html(get_img(resp.img_src, i, resp.db_id));
                $('#show_img' + i).fadeIn('slow');
                $('#select_mode_modal').modal('hide');
                isFormChanges = true;
                update_sorting();
            } else if (resp.status == 1 && resp.type == 'error') {
                Custom_notify('error', resp.message)
            }
        });
    }
}

$(document).on("click", ".delete_article_post", function () {
    var _this = $(this);
    confirm_popup_function(
        "Delete",
        "Are you sure you want to delete this article?",
        "delete_article_post(" + _this.attr("data-article_id") + ")"
    );
});

function delete_article_post(article_id) {
    if (article_id != '') {
        $("#conf_btn")
            .text("Deleting ")
            .append('<i class="fa fa-spinner fa-spin post_spinner"></i>')
            .prop("disabled", true);

        let formData = new FormData();
        formData.append('article_id', article_id);
        manageMyAjaxPostRequestData(formData, base_url + 'articles/delete_article_post').done(function (resp) {
            if (resp['status'] == 1 && resp['type'] == 'Success') {
                $("#conf_btn").text("Delete").prop("disabled", false);
                $('[data-article_id="' + article_id + '"]').closest('.dis_articleMBox').next("br").remove();
                $('[data-article_id="' + article_id + '"]').closest('.dis_articleMBox').remove();
                success_popup_function("Article deleted successfully.");
            }
        });
    }
}
/*

$(window).on('scroll', function () {
    let scrollTop = $(this).scrollTop();
    let scrollDirection = scrollTop > lastScrollTop ? 'down' : 'up';
    lastScrollTop = scrollTop;

    if ($('.article_sticky_player').length > 0) {
        $('.article_sticky_player').each(function (index) {
            if (isElementFullyVisible($(this)) && !$(this).hasClass('alert-shown') ) {
                playVideo($(this));
                $(this).addClass('alert-shown'); // Add the class to mark it as shown
                lastVisibleIndex = index; // Update the last visible index
            } else if (!isElementFullyVisible($(this)) && $(this).hasClass('alert-shown')) {
                $(this).removeClass('alert-shown'); // Remove the class if the element is not visible
                pauseVideo($(this));
            }
        });

        // Make the last visible player sticky
        if (lastVisibleIndex == ($('.article_sticky_player').length-1)) {
            const lastPlayer = $('.article_sticky_player').eq(lastVisibleIndex);
            const playerOffset   = lastPlayer.offset().top;
            const playerHeight   = lastPlayer.outerHeight()+ $(window).height();
            const viewportBottom = $(window).scrollTop() + $(window).height();

            // Check if the last player is above the viewport bottom
            if (!isElementFullyVisible(lastPlayer)  && playerOffset + playerHeight < viewportBottom && scrollDirection == "down") {
                lastPlayer.removeClass('dis_art_u_iframe').addClass('dis_art_u_iframe_new');
                playVideo(lastPlayer);
            } else if(playerOffset + playerHeight > viewportBottom ){
                lastPlayer.removeClass('dis_art_u_iframe_new').addClass('dis_art_u_iframe');
            }
        }

    }

},1000);

function isElementFullyVisible(element) {
    var elementTop = element.offset().top;
    var elementBottom = elementTop + element.outerHeight();
    var viewportTop = $(window).scrollTop();
    var viewportBottom = viewportTop + $(window).height();
    return elementTop >= viewportTop && elementBottom <= viewportBottom;
}


function playVideo(element) {
    let videoTag = element.find("iframe.sticky_video").contents().find("video.vjs-tech");
    videoTag?.[0]?.play();
}

function pauseVideo(element) {
    let videoTag = element.find("iframe.sticky_video").contents().find("video.vjs-tech");
    videoTag?.[0]?.pause();
    pauseAds(element);
}

function pauseAds(element) {
    // Logic to pause ads in the element
    const adTag = element.find("iframe.sticky_video").contents().find("video.ad-tech"); // Adjust this selector based on your ad setup
    adTag?.[0]?.pause(); // Pause the ad if it's playing
}
*/

/****************New Code Start***********/