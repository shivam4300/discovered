
// (function ($) {
("use strict");
var auditionlive = {
    initialised: false,
    version: 1.0,
    mobile: false,
    init: function () {
        if (!this.initialised) {
            this.initialised = true;
        } else {
            return;
        }
        /*-------------- auditionlive Functions Calling ---------------------------------------------------
            ------------------------------------------------------------------------------------------------*/
        this.Auth();
        this.CreatePost();
        this.CommentReply();
    },
    /*-------------- auditionlive Functions definition ------------------------------------------------------------*/

    Auth: function () {
        // on enter submit every form
        $(".validate").on("keyup", function (event) {
            event.preventDefault();
            if (event.keyCode == 13) {
                validate_form();
            }
        });

        $(".check_li").click(function () {
            $(this).prev(".check_box").trigger("click");
        });
    },
    CreatePost: function () {
        $(".post_section_ul li").click(function () {
            $(".post_section_ul li").removeClass("active");
            $(this).addClass("active");
            // $(".uploadSection").addClass("hideme");
			// if($(this).attr("title") == 'fileContent')
			// $(".uploadSection").removeClass("hideme");
		});

        var playcount = 0;
        $(document).on(
            "click",
            ".au_banner_content .play_btn, .overlay .play_btn",
            function (event) {
                event.preventDefault();

                $("video").each(function () {
                    $(this)[0].pause();
                });

                if (playcount == 0) {
                    playcount++;
                    $(this).html(
                        '<img src="' + base_url + 'repo/images/play_icon.png">'
                    );
                } else {
                    playcount = 0;
                    $(this).html(
                        '<img src="' + base_url + 'repo/images/play_icon.png">'
                    );
                }
            }
        );
    },

    CommentReply: function () {
        $(document).on("keydown", ".comment_textarea", function (event) {
            if (parseInt(event.keyCode) == 13) {
                event.preventDefault();
                var getId = $(this).attr("class");
                var id_arr = getId.split("text_");
                var id_arr1 = id_arr[1].split("_");
                id_arr1[1] = id_arr1[1]
                    .replace("focus-visible", "")
                    .trim(); /* Focus_visible was coming in MAC browser, so i replaced it*/
                $(".com_text_" + id_arr1[0] + "_" + id_arr1[1])
                    .next("button")
                    .click();
            }
        });
    },
};

auditionlive.init();

$(".close_opacity").click(function () {
    pub_input.val("");
    pub_btn.html("Publish").attr("onclick", "publish_content('0')");

    $(".edit_media_section").html("");
    $("#input").text("");
    $(".show_opacity").removeClass("active");
    $(".active_area").removeClass("active");
});

// })(jQuery);

/* Validate All Input Fields Function */
$(document).on("keypress", 'input[data-type="number"]', function (e) {
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        return false;
    }
});
$(document).on("keypress", 'input[data-type="alphaNum"]', function (e) {
    var valid =
        (e.which >= 48 && e.which <= 57) ||
        (e.which >= 65 && e.which <= 90) ||
        (e.which >= 97 && e.which <= 122) ||
        e.which == 32 ||
        e.which == 8 ||
        e.which == 61 ||
        e.which == 44 ||
        e.which == 45 ||
        e.which == 58;
    if (!valid) {
        e.preventDefault();
        return false;
    }
});

function validate_form($this) {
    var err_count = 0;
    var dataArr = {};
    $(".multi_select").removeClass("error");
    $("#check_box").text("");

    if ($(".check_box").length > 0) {
        if ($(".check_box:checked").length == 0) {
            $(".multi_select").addClass("error");

            if ($("#check_box").length)
                $("#check_box").text("This field is required.");

            if ($("#check_box_cagegory").length)
                $("#check_box_cagegory").text("Please select a category.");

            err_count++;
            return false;
        }
    }

    if ($(".primay_select").length > 0) {
        if ($(".primay_select").val() == null) {
            $("#check_box").text("Please select your profession.");
            err_count++;
            return false;
        }
    }

    $(".validate").each(function () {
        $(this).removeClass("error");
        $(this).parents(".form-group").find(".form-error").text("");
        $(".check_error").text("");
        $(".gender_error").text("");

        if ($.trim($(this).val()) == "" || $.trim($(this).val()) == "0") {
            $(this).addClass("error");

            let placeholder = "";
            if ($(this).attr("placeholder")) {
                placeholder = $(this).attr("placeholder");
                placeholder = placeholder.toLowerCase();

                $(this)
                    .parents(".form-group")
                    .find(".form-error")
                    .text(
                        "Please enter your " +
                        placeholder.replace("*", "") +
                        "."
                    );
            } else {
                $(this)
                    .parents(".form-group")
                    .find(".form-error")
                    .text("This field is required");
            }

            $(this).focus();
            err_count++;
            return false;
        }

        var clsStr = $(this).attr("class");

        if (clsStr.search("email") != -1) {
            var em = $.trim($(this).val());
            var emRegex = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,15}(?:\.[a-z]{2})?)$/i;

            if (!emRegex.test(em)) {
                $(this).addClass("error");
                $(this)
                    .parents(".form-group")
                    .find(".form-error")
                    .text("Please enter a valid email address.");
                $(this).focus();
                err_count++;
                return false;
            }
        }

        if (clsStr.search("pwd") != -1) {
            var pwd = $(this).val();
            var PwRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&#])[A-Za-z\d$@$!%*?&#]{8,}$/;
            if (!PwRegex.test(pwd)) {
                $(this).addClass("error");
                $("#user_pwd_error").text(
                    "Invalid password, please make sure the password is atleast 8 characters with one number, one capatalized letter and one special character."
                );

                err_count++;
                return false;
            }
        }

        if (clsStr.search("repwd") != -1) {
            var repwd = $(this).val();
            var pwd = $(".pwd").val();

            if (pwd != repwd) {
                $(this).addClass("error");
                $(this)
                    .parents(".form-group")
                    .find(".form-error")
                    .text("New password entered do not match.");

                err_count++;
                return false;
            }
        }
        if (clsStr.search("phone_number") != -1) {
            var phone_number = $(this).val();
            var PhoneNumberRegex = /^.{7,}$/;
            if (!PhoneNumberRegex.test(phone_number)) {
                $(this).addClass("error");
                $(this)
                    .parents(".form-group")
                    .find(".form-error")
                    .text("Please enter a valid phone number.");
                $(this).focus();
                err_count++;
                return false;
            }
        }
        if (err_count == 0) {
            dataArr[$(this).attr("id")] = $(this).val();
        }
    });

    if (err_count == 0) {
        var form_type = $($this).attr("data-form");
        if ($("#check_tnc").length > 0) {
            if ($("#check_tnc:checked").length == "0") {
                $(".check_error").text(
                    "Please accept our terms and conditions."
                );
                return false;
            }
        }
        if(form_type === "forgot_form" && $("#forgot-recaptcha").length > 0){
            var recaptcha_response  = $.trim(getRecaptchaResponse('forgot-recaptcha'));
            if(recaptcha_response === ""){
                $("#" + form_type)
                    .find(".form-error")
                    .text("Please complete the reCAPTCHA.");
                return false;
            }
        }

        $($this).removeAttr("onclick");
        $(".validate").removeAttr("style");
        
        $("#" + form_type).submit();
    }
}

var verify_icon;

$(document).on("change", ".verify_icon_check, .verify_emerge_check", function () {
    let _this = $(this);
    let clas = _this.attr('class');
    $("#check_box").text('');
    if (clas == 'verify_icon_check') {
        $('.verify_emerge_check').prop('checked', false);
    } else
        if (clas == 'verify_emerge_check') {
            $('.verify_icon_check').prop('checked', false);
        }
})

$(document).on("click", ".CheckIconForApproval", function () {

    if ($('.verify_emerge_check').is(":checked")) {
        window.location.replace(base_url + 'primary_type/2');
    } else
        if ($('.verify_icon_check:checked').length > 0) {
            window.location.replace(base_url + 'primary_type/5');
        } else
            if ($('.verify_icon_check:checked').length == 0) {
                $("#check_box").text("Please check at least one checkbox to get auto icon approval.");
            }
})

$(document).on("change", ".chkDupEml", function () {
    let r = $(this);
    let f = new FormData();
    f.append("email", r.val());
    manageMyAjaxPostRequestData(
        f, base_url + "home/is_email_exists"
    ).done(function (data) {
        data = JSON.parse(data);
        if (data.status == 1) {
            r.parents(".form-group").find(".form-error").html(data.message);
            setTimeout(() => { r.addClass('error').focus().val(''); }, 2000);
        } else {
            r.parents(".form-group").find(".form-error").html('');
            r.removeClass('error');
        }
    });
});

/************** Fetch States by Country STARTS ****************/

let country = document.querySelector('select[name="country"]');
console.log(country);
if (country != null && country.length) {
    setTimeout(function () {
        let change = new Event("change");
        country.value = 192;
        country.dispatchEvent(change);
    }, 500)
}

function getStates($this) {
    var country = $($this).val();
    if (country == "0") {
        $($this).addClass("error");
        $($this).next().text("required.");
        $('.validate[name="state"]').html('<option value="0">State*</option>');
        return false;
    } else {
        let formData = new FormData();
        formData.append("country", country);
        manageMyAjaxPostRequestData(formData, base_url + "home/getStates").done(
            function (data) {
                if (data == "0") {
                    $($this).addClass("error");
                    $($this).next().text("Server error.");

                    setInterval(function () {
                        window.location.reload(1);
                    }, 2000);
                } else {
                    $('.validate[name="state"]').html(data);
                }
            }
        );
    }
}
/************** Fetch States by Country ENDS ****************/
/************** Set Username STARTS ****************/

$(document).on('change, keyup', 'input[name="user_uname"]', function (e) {
    let _this = $(this), name_error = $(".uniq_name_error"), uniq_name = $.trim(_this.val()), icon_form = $('[data-form="icon_form"]');
    name_error.text('');
    icon_form.attr('disabled', true);
    if (uniq_name != "") {
        if (/^[a-zA-Z0-9_-]*$/.test(uniq_name) == false) {
            _this.addClass("error");
            name_error.text("Please enter the URL name with no spaces and special characters.");
        }
        else
            if (uniq_name.length < 3 || uniq_name.length > 30) {
                name_error.text("Please enter the URL name with minimum 3 and maximum 30 characters.");
            }
            else {
                let f = new FormData();
                f.append("user_uname", uniq_name);
                manageMyAjaxPostRequestData(
                    f,
                    base_url + "home/is_user_exists"
                ).done(function (data) {
                    if (data == 0) {
                        _this.addClass("error");
                        name_error.text("Oops, This URL name is already taken.");
                    } else {
                        icon_form.attr('disabled', false)
                    }
                });
            }
    } else {
        _this.addClass("error");
        name_error.text("Please enter a URL name.");
    }
    return false;
});

/************** Set Username ENDS ****************/

/************** Open Popup Window STARTS ****************/

function open_window(url) {
    var w = 880,
        h = 600,
        left = Number(screen.width / 2 - w / 2),
        tops = Number(screen.height / 2 - h / 2),
        popupWindow = window.open(
            url,
            "",
            "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=1, copyhistory=no, width=" +
            w +
            ", height=" +
            h +
            ", top=" +
            tops +
            ", left=" +
            left
        );
    popupWindow.focus();
    return false;
}
/************** Open Popup Window ENDS ****************/

/************** Add and Edit Dashboard Content STARTS ****************/
var xsend = 1, pub_btn = $(".ad_publish_btn"), pub_input = $("#publish_input"), pub_error = $("#showerror");

$(document).on('change', '#uploadFile', function () {
    if (xsend == 0) {
        return pub_error.text('Already uploading process is running.');
    }

    let ele = document.getElementById("uploadFile");
    let files = ele.files;
    let file = files[0];

    if (!files.length) {
        return pub_error.text("Please select a file to upload first.");
    } else
        if ($.inArray(file.type, ["video/mp4", "video/quicktime", "image/jpeg", "image/png", "image/jpg", "image/gif"]) == -1) {
            ele.value = '';
            return pub_error.text("Please select a correct file format.");
        }
    _progress.width('0%').text('0%')
    _progress.parent().hide();
    $('._progress_bar').removeClass('hide');
    $('._progress_title').text(file.name);
})

function publish_content(publish_id) {
    pub_btn.text("publishing ").append('<i class="fa fa-spinner fa-spin post_spinner"></i>').attr("onclick", " ");
    xsend = 0;
    let dataArr = {}, uploadType;
    let publish_input = $.trim(pub_input.val());

    if ($("#remove_media_post").length > 0) {
        dataArr["remove_media_post"] = "yes";
    }
    dataArr["publish_input"] = publish_input;
    dataArr["publish_id"] = publish_id;

    $(".post_section_ul li").each(function () {
        if ($(this).hasClass("active")) {
            uploadType = $(this).attr("title");
        }
    });

    let uploadFileEle = document.getElementById("uploadFile");
    let files = uploadFileEle.files;
    if (uploadType == "fileContent" && files.length) {
        let file = files[0];
        let sizeInMb = (file.size / 1024 / 1024).toFixed(2);

        if (sizeInMb < 2000) {
            let type = ((file.type).search("video") >= 0) ? 'videos' : 'images';
            if (type == 'videos') {

                let target = "aud_" + user_login_id + "/videos/";
                target = target + makeid(20) + '.' + (file.name).split('.').pop();

                ProcessUpload(file, target).
                    then(function (data) {
                        uploadFileEle.value = "";
                        $('._progress_title').text('Please wait....');
                        dataArr["Key"] = data.Key;
                        dataArr["Location"] = data.Location;
                        GenerateThumbs(file, 1).then(function (response) {
                            dataArr['Thumb'] = response[0];
                            save_publish_content(dataArr);
                        });
                    })
            } else {
                dataArr['userfile'] = file;
                save_publish_content(dataArr);

                uploadFileEle.value = "";
                _progress.parent().show();
                _progress.width('90%').text('90%');
            }
        } else {
            Custom_notify("error", 'Please select a file less than 2 GB.');
        }
    } else {
        xsend = 1;
        pub_error.text("");
        if (publish_input.length || publish_id != 0) {
            save_publish_content(dataArr);
        } else {
            pub_error.focus().text("Please add content to publish.");
            pub_btn.text("publish").attr("onclick", "publish_content('0');");
        }
    }
}

function save_publish_content(dataArray) {
    let formData = new FormData();
    $.each(dataArray, function (i, l) {
        formData.append(i, l);
    });
    manageMyAjaxPostRequestData(
        formData,
        base_url + "dashboard/front_user_publish_data"
    ).done(function (resp) {
        if (resp.status == 1) {
            $('._progress_bar , .publish_btn').addClass('hide');
            $(".initial_step , .close_opacity").trigger("click");

            if (dataArray["publish_id"] == "0") {
                $("#publish_post").prepend(resp.data);
                intializeVideoJsContent("first");
                richLinkCode($('.contentText').eq(0));
                if (!IsMobileDevice()) {
                    twemoji.parse(document.querySelector(".contentText"), { folder: '72x72', ext: '.png', });
                }
            } else {
                var pubId = dataArray["publish_id"];

                $("#parent_post_content_" + pubId).html(resp.data);
                $(".dis_user_post_data")
                    .find(".dis_user_post_data")
                    .find(".dis_user_post_data")
                    .find(".dis_user_post_footer")
                    .remove();

                let index = $(".video-js").index($("#my_video_" + pubId));

                if (index >= 0) {
                    let id = $(".box:eq(" + index + ")")
                        .find("video")
                        .attr("id");
                    var player = videojs.getPlayer(id);

                    if (player) {
                        videojs(id).dispose();
                    }
                    intializeVideoJsContent("eq(" + index + ")");
                }

                rindex = $(".contentText").index($("#text_" + pubId));
                richLinkCode($('.contentText').eq(rindex));
                if (!IsMobileDevice()) {
                    let nodes = document.querySelectorAll(".contentText");
                    twemoji.parse(nodes[rindex], { folder: '72x72', ext: '.png', });
                }
            }
        } else {
            Custom_notify("error", resp.data);
        }

        $("#input").hide();
        pub_input.val("");
        pub_btn.text("publish").attr("onclick", "publish_content('0');");
    });
    xsend = 1;
}

/************** Begins Of Video Js Content ENDS ****************/

var intpostition;
var PlayPostion;

function intializeVideoJsContent(selector) {
    let id = $(".box:" + selector)
        .find("video")
        .attr("id");

    if (id != null) {
        var player = videojs.getPlayer(id);
        if (!player) {
            let vid = videojs(
                id,
                {
                    playbackRates: [1, 1.5, 2],
                    muted: true,
                    preload: "none",
                    loop: true,
                    aspectRatio: '16:9',
                    html5: {
                        hls: {
                            overrideNative: true,
                        },
                    },
                    /*  plugins: {
                         httpSourceSelector: {
                             default: "low",
                         },
                     }, */
                },
                function () {
                    let _this = $(this);
                    _this.ready(function () {
                        // _this[0].share(shares(_this[0].tagAttributes.vidid));

                        let videoId = _this[0].id_;

                        $("#" + videoId)
                            .find(".vjs-fullscreen-control")
                            .before(
                                '<div class="vjs-brand-container"><a class="vjs-brand-container-link" href="' +
                                base_url +
                                '" title="discovered.tv" target="_top"><img src="' +
                                base_url +
                                'repo/images/favicon.png"></a></div>'
                            );

                        _this[0].on("fullscreenchange", function (e) {
                            if (!_this[0].isFullscreen()) {
                                $("html, body").animate(
                                    {
                                        scrollTop: PlayPostion - 750,
                                    },
                                    0
                                );
                            }
                        });

                        _this[0].on("play", function () {

                            $(".box").each(function () {
                                $(this).removeAttr("id");
                            });

                            $("#" + id).parents(".box").attr("id", "videoBox");

                            let top = $("html").offset().top;
                            let videoBox = $("#videoBox");

                            PlayPostion = videoBox.offset().top + videoBox.height() - top;
                            intpostition = 0;

                            videoBox.removeClass("out").addClass("in");

                            $(".dragRemove").empty();

                            $(".video-js").each(function (index) {
                                let each_id = $(this).attr("id");
                                if (videoId !== each_id) {
                                    this.player.pause();
                                    this.player?.ima?.pauseAd();
                                }
                            });

                            DragStickyPlayer();
                        });

                        let page_url = encodeURIComponent(window.location.href);
                        let Policy = 'allowed';
                        let VideoPostId = '';
                        let domain = (window.location.href).replace('http://', '').replace('https://', '').replace('www.', '').split(/[/?#]/)[0];
                        let custom = '';
                        let size = encodeURIComponent('400x300|640x480');
                        let CACHEBUSTER = Date.now();
                        // let vastUrl = 'https://pubads.g.doubleclick.net/gampad/ads?iu=/22019190093/' + domain + '_video&description_url=' + page_url + '&url=' + page_url + '&tfcd=0&npa=0&sz=' + size + '&cust_params=' + custom + '&vid=' + VideoPostId + '&cmsid=2528975&gdfp_req=1&output=vmap&unviewed_position_start=1&env=vp&impl=s&ad_rule=1&Policy=' + Policy + '&correlator=' + CACHEBUSTER;
                        // if (typeof vid.ima == 'function') {
                        //     // vastUrl = 'https://pubads.g.doubleclick.net/gampad/ads?iu=/21775744923/external/single_ad_samples&sz=640x480&cust_params=sample_ct%3Dlinear&ciu_szs=300x250%2C728x90&gdfp_req=1&output=vast&unviewed_position_start=1&env=vp&impl=s&correlator=';
                        //     vid.ima({ vastLoadTimeout: 10000, adTagUrl: vastUrl });
                        // }
                        
                        vid.on('ads-manager', function (response) {
                            adsManager = response.adsManager;
                            adsManager.addEventListener(google.ima.AdEvent.Type.RESUMED, function () {
                                $(".video-js").each(function (index) {
                                    let each_id = $(this).attr("id");
                                    if (videoId !== each_id) {
                                        this.player.pause();
                                        if(this.player.ads.isAdPlaying()){
                                            this.player.ima.pauseAd();
                                        }
                                    }
                                    
                                });
                            });
                        })
                    });
                }
            );
        }
    }

    $(".box , img").bind("contextmenu", function () {
        return false; /* DISABLE RIGHT MENU IN VIDEO PLAYER*/
    });

    StopDraging();
    $(".box:" + selector).parent().css("min-height", $(".box:" + selector).height());
}
StopDraging();
function StopDraging() {
    $("img").on("dragstart", function (event) {
        event.preventDefault();
    });
}
/* START OF MAKING PLAYER SCROLLABLE AND DRAGGABLE*/

if (!$("#PlayNext").length) {
    $(window).scroll(DragStickyPlayer);

    var playerPostion = 0;
    var vivo;
    var scrollTop;
    $(window).bind("scroll", function () {
        $(".ForAutoPlay").each(function (index) {
            vivo = $(this);
            let top = $("html").offset().top;
            playerPostion = vivo.offset().top + vivo.height() - top;
            scrollTop = $(window).scrollTop() + 750;
            if (
                playerPostion > scrollTop - 75 &&
                playerPostion < scrollTop + 75
            ) {
                vivo.find(".vjs-big-play-button").trigger("click");
            }
        });
    });
}

function DragStickyPlayer() {
    var vidBox = $("#videoBox");
    if (vidBox.length) {
        var ha = vidBox.offset().top + vidBox.height();
        if ($(window).scrollTop() > ha) {
            if (intpostition == 0) {
                intpostition = ha;
            }
            vidBox.removeClass("in").addClass("out");

            let video_id = $("#videoBox");
            $(".dragRemove").empty();
            // video_id.find(".dragRemove").html(`<i class="fa fa-arrows vid_dragicon" aria-hidden="true"></i><i class="fa fa-times vid_dragclose" aria-hidden="true"></i>`);
            video_id.find(".dragRemove").html(`<i class="fa fa-times vid_dragclose" aria-hidden="true"></i>`);
            if(!vidBox.draggable()){
                vidBox.draggable( "destroy" );
            }
            vidBox.draggable({
                containment: "window",
                handle: "i.vid_dragicon",
                axis  : "x"
            });
        } else if ($(window).scrollTop() < intpostition) {
            intpostition = 0;
            vidBox.removeClass("out").addClass("in");
            $(".dragRemove").empty();
        }
    }
}

$(document).on("click", ".vid_dragclose", function () {
    var index = $(this).index();
    $("#videoBox").attr("id", "");
    $(".dragRemove").empty();
});

function shares(source) {
    var shareOptions = {
        embedCode:
            '<iframe src="' +
            source +
            '" width="560" height="315" frameborder="0" allow="autoplay" allowfullscreen="true"></iframe>',
    };
    return shareOptions;
}

/************** End Of Video Js Content ENDS ****************/

/************** Add and Edit Dashboard Content ENDS ****************/

/********************* Drop Down Actions STARTS ***********************/
var selected = "";
function drop_actions(type, pubId) {
    $("#post_drop_" + pubId).removeClass("open_dropdown");
    if (type == "edit") {
        pub_btn.attr("onclick", "publish_content(" + pubId + ")");

        pub_input.val(
            $("#text_" + pubId).data('text').toString().replace(/<br\s*\/?>/gim, "\n").trim()
        );

        let mediaSrc = $("#media_" + pubId);
        if (mediaSrc.length > 0) {
            var media_src = mediaSrc.attr("src");
            if (media_src.split("images").length == 2) {
                var media_content = '<img src="' + media_src + '">';
            } else {
                media_src = mediaSrc.parents("video").attr("poster");
                var media_content = '<img src="' + media_src + '">';
            }
            $(".edit_media_section").html(
                '<div class="edit_media"><span title="Remove Media" onclick="remove_media_post(' + pubId + ')" class="cancle_edit"><i class="fa fa-times" aria-hidden="true"></i></span>' +
                media_content +
                '</div>'
            );
        }

        pub_input.trigger("click");
        pub_btn.html("Save Changes");
    } else if (type == "delete") {
        confirm_popup_function(
            "Delete",
            "Are you sure you want to delete this Post?",
            "delete_post(" + pubId + ")"
        );
    } else if (type == "audience") {
        let formData = new FormData();
        formData.append("pubID", pubId);
        manageMyAjaxPostRequestData(
            formData,
            base_url + "dashboard/getPublishDataStatus"
        ).done(function (data) {
            selected = data;
        });

        setTimeout(function () {
            $("#conf_btn").show();
            $("#conf_header").addClass("notification_popup");
            $("#conf_title").text("Audience");
            $("#conf_text").html(
                'Who can see this? <select class="form_field" id="audience_select"> <option  value="5">Only Me</option> <option  value="7">Public</option>   </select>'
                // 'Who can see this? <select class="form_field" id="audience_select"> <option  value="7">Public</option> <option  value="6">Private</option> <option  value="5">Only Me</option>   </select>'
            );
            $("#audience_select option[value=" + selected + "]").attr(
                "selected",
                "selected"
            );
            $("#conf_btn").text("Change");
            $("#conf_btn").attr("onclick", "change_audience(" + pubId + ")");
            $("#confirm_popup").modal("show");
        }, 500);
    } else if (type == "FEWER") {
        confirm_popup_function(
            "See Fewer Suggestions",
            "Are you sure you want to see fewer suggestions?",
            "stopSuggestions('" + type + "')"
        );
    } else if (type == "STOP") {
        confirm_popup_function(
            "I Don\'t want Suggestions",
            "Are you sure you don\'t want suggestions?",
            "stopSuggestions('" + type + "')"
        );
    }
}

/********************* Drop Down Actions ENDS ***********************/

function stopSuggestions(type) {
    if (user_login_id != "") {

        let f = new FormData();
        f.append("action_type", "stop_suggestions");
        f.append("type", type);
        f.append("pid", '');

        manageMyAjaxPostRequestData(
            f, base_url + "dashboard/action_on_post"
        ).done(function (resp) {
            if (resp.status == 1) {
                redirect('profile?user=social');
                success_popup_function("Your request has been accepted");
            } else {
                server_error_popup_function(resp.message);
            }
        });
    }
}
/*************** Like Post STARTS *********************/

function like_post(pid, type) {
    if (user_login_id != "") {
        if (pid != "") {
            let formData = new FormData();
            formData.append("pid", pid);
            formData.append("type", type);
            formData.append("action_type", "like");

            $(".l_p_text_" + pid)
                .find("a")
                .attr("onclick", "");

            manageMyAjaxPostRequestData(
                formData,
                base_url + "dashboard/action_on_post"
            ).done(function (resp) {
                if (resp.status == 1) {
                    $(".l_p_text_" + pid).html((resp.data)[0]);
                    $(".l_p_text_" + pid).parents('.dis_user_post_footer').find('.wholoveit').html((resp.data)[1]);
                } else {
                    server_error_popup_function(resp.message);
                }
            });
        }
    } else {
        $("#myModal").modal("show");
    }
}

/*************** Like Post ENDS *********************/

/*************** UnLike Post STARTS *********************/

function unlike_post(pid, type) {
    if (user_login_id != "") {
        if (pid != "") {
            $(".l_p_text_" + pid)
                .find("a")
                .attr("onclick", "");

            let formData = new FormData();
            formData.append("pid", pid);
            formData.append("type", type);
            formData.append("action_type", "like");
            formData.append("dislike", "yes");

            manageMyAjaxPostRequestData(
                formData,
                base_url + "dashboard/action_on_post"
            ).done(function (resp) {
                // resp = $.parseJSON(resp);
                if (resp.status == 1) {
                    $(".l_p_text_" + pid).html((resp.data)[0]);
                    $(".l_p_text_" + pid).parents('.dis_user_post_footer').find('.wholoveit').html((resp.data)[1]);
                } else {
                    server_error_popup_function(resp.message);
                }
            });
        }
    } else {
        $("#myModal").modal("show");
    }
}

/*************** UnLike Post ENDS *********************/

/*************** Comment / Reply On Post STARTS *********************/

function save_comment(pubID_str, parentID_str, reply = "") {
    if (user_login_id != "") {
        if (pubID_str != "") {
            let pubID = pubID_str.split("_")[1];
            let parentID = parentID_str.split("_")[1];
            let OC = $(".com_text_" + pubID + "_" + parentID), CD = $("#com_disbl_" + pubID), SC = $('.child[data-id="show_comments_' + parentID + '"]'), com_text = '';

            OC.each(function () {
                if ($(this).val() != "") {
                    com_text = $(this).val();
                }
            });

            com_text = com_text.replace(/\n|\r/g, "").trim();

            if (com_text != "") {
                OC.each(function () {
                    $(this).val("");
                });
                let formData = new FormData();
                formData.set("pub_id", pubID);
                formData.set("parent_id", parentID);
                formData.set("com_text", com_text);
                formData.set("reply", reply);

                manageMyAjaxPostRequestData(
                    formData,
                    base_url + "dashboard/save_comment_db"
                ).done(function (data) {
                    if (data != "0") {
                        if (parentID == "0") {
                            let count = CD.attr("count");
                            count++;
                            CD.find("span:nth-child(2)").text("Comments(" + count + ")");
                            CD.attr("count", count);
                            $("#com_append_" + pubID).append(data);
                        } else {
                            SC.append(data);
                            $(".reply_box_" + parentID).hide();
                        }
                        OC.val("");
                        renderCommEmoji();
                    }
                    $('.commment_p').find('img').removeClass('_PostEmoji');
                });
            }
        }
    } else {
        $("#myModal").modal("show");
    }
}
function get_comment(pub_id, replce_type) {
    if (user_login_id != "") {
        let CD = $("#com_disbl_" + pub_id), VM = $("#com_view_more_" + pub_id), CA = $("#com_append_" + pub_id);

        if (replce_type == "append") VM.remove();

        let formData = new FormData();
        let count_comment = parseInt(
            CA.attr("data-comment")
        );

        formData.set("pub_id", pub_id);
        formData.set("start", count_comment);


        if (CD.attr("count") > 0) {
            manageMyAjaxPostRequestData(
                formData,
                base_url + "dashboard/get_comment"
            ).done(function (resp) {
                if (resp.trim().length > 0) {
                    CA.attr("data-comment", count_comment + 5);

                    if (replce_type == "html") CA.html(resp);

                    if (replce_type == "append") CA.append(resp);

                    CD.attr("onclick", "");
                    $("#com_view_more_" + pub_id).show();
                    renderCommEmoji();
                    $('.commment_p').find('img').removeClass('_PostEmoji');
                }

            });
        }
    } else {
        $("#myModal").modal("show");
    }
}
function get_comment_reply(pub_id, replce_type, parent_id, reply = "") {
    if (user_login_id != "") {
        let VM = $("#com_view_more_" + parent_id), CD = $('.child[data-id="show_comments_' + parent_id + '"]');
        console.log(VM, 'VMVMVMVMVMVMVM');
        if (replce_type == "append") VM.remove();


        let count_comment = parseInt(
            CD.attr(
                "data-comment"
            )
        );
        let f = new FormData();
        f.set("pub_id", pub_id);
        f.set("parent_id", parent_id);
        f.set("start", count_comment);
        f.set("reply", reply);

        manageMyAjaxPostRequestData(
            f, base_url + "dashboard/get_comment"
        ).done(function (resp) {
            if (resp.trim().length > 0) {
                CD.attr("data-comment", count_comment + 5).append(resp);

                $(".reply_box_" + parent_id).hide();

                $("#com_disbl_" + parent_id).attr("onclick", "");

                $("#com_view_more_" + parent_id).show();
                renderCommEmoji();
            }

        });
    } else {
        $("#myModal").modal("show");
    }
}

/*************** Comment / Reply On Post ENDS *********************/

/*************** Show Reply Box STARTS *********************/

function show_reply_box(comment_id) {
    $(".reply_box_" + comment_id).show();
}

var ths_comment = "";
$(document).on("click", ".del_comment", function () {
    ths_comment = $(this);
    confirm_popup_function(
        "Delete",
        "Are you sure you want to delete this comment?",
        "delete_comment()"
    );
});

function delete_comment() {
    let com_id = ths_comment.attr("data-com_id");
    let parentID = ths_comment.attr("data-parent_id");
    let pub_id = ths_comment.attr("data-pub_id");
    if (com_id != "") {
        let dc = new FormData();
        dc.append("comment_id", com_id);

        manageMyAjaxPostRequestData(
            dc,
            base_url + "dashboard/delete_comment"
        ).done(function (resp) {
            // resp = $.parseJSON(resp);
            if (resp == 1) {
                if (parentID == "0") {
                    ths_comment
                        .parents(".comment_div")
                        .next()
                        .remove(".dis_comment_form");
                    ths_comment.parents(".comment_div").remove();

                    let count = $("#com_disbl_" + pub_id).attr("count");
                    count--;
                    $("#com_disbl_" + pub_id)
                        .find("span:nth-child(2)")
                        .text("Comments(" + count + ")");
                    $("#com_disbl_" + pub_id).attr("count", count);
                } else {
                    ths_comment
                        .closest("li")
                        .next(".dis_comment_form ")
                        .remove();
                    ths_comment.closest("li").next(".text-center ").remove();
                    ths_comment.closest("li").remove();
                }
            }
            $("#conf_btn").removeClass("comment_delete");
            $("#confirm_popup").modal("hide");
        });
    }
}

/*************** Show Reply Box ENDS *********************/

/**************** Remove Media STARTS *********************/

function remove_media_post(pubId) {
    $(".edit_media_section").html("");
    $(".edit_media_section").html(
        '<input type="hidden" value="yes" id="remove_media_post">'
    );
}
/**************** Remove Media ENDS *********************/

/***************** Delete Post Completely STARTS ******************/

function delete_post(pid) {
    if (pid != "") {
        $("#conf_btn")
            .text("Deleting ")
            .append('<i class="fa fa-spinner fa-spin post_spinner"></i>')
            .prop("disabled", true);
        let dp = new FormData();
        dp.append("pid", pid);
        dp.append("action_type", "delete");

        manageMyAjaxPostRequestData(
            dp,
            base_url + "dashboard/action_on_post"
        ).done(function (resp) {
            if (resp.status == 1) {
                $("#parent_post_content_" + pid).remove();
                if ($("#side_bar_post_" + pid).length) {
                    $("#side_bar_post_" + pid).remove();
                }

                $("#conf_btn").text("Delete").prop("disabled", false);
                success_popup_function("Your post has been deleted");
            } else {
                server_error_popup_function(resp.message);
            }
        });
    }
}

/***************** Delete Post Completely ENDS ******************/

/***************** Change Audience STARTS ******************/

function change_audience(pid) {
    if (pid != "") {
        let aud = new FormData();
        aud.append("pid", pid);
        aud.append("aud", $("#audience_select").val());
        aud.append("action_type", "change_audience");

        manageMyAjaxPostRequestData(
            aud,
            base_url + "dashboard/action_on_post"
        ).done(function (resp) {
            if (resp.status == 1) {
                success_popup_function("Your preferences have been saved.");
            } else {
                server_error_popup_function(resp.message);
            }
        });
    }
}
/***************** Change Audience ENDS ******************/

/***************** Profile cropp and upload START ******************/
// Drag enter

$(document).on('dragenter dragover','#imgInp', function (e) {
	e.stopPropagation();
	e.preventDefault();
});

$(document).on('drop','#imgInp', function (e) {
	e.stopPropagation();
	e.preventDefault();
	this.files = e.originalEvent.dataTransfer.files;
	$("#imgInp").change();
});

$(document).on('change', '#imgInp' ,function (e) {
    let val = $(this).val();
    let extensions = val.toLowerCase();
        regex = new RegExp("(.*?).(jpg|png|jpeg|gif)$");
    if (extensions !='' && !regex.test(extensions)) {
        $(this).val("");
        server_error_popup_function("Invalid file format.");
        return false;
    }

    $("#cropit").attr("disabled", false);
    readURL(this);
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $("#ProImage").attr("src", e.target.result);
            $("#ProImage").cropper("destroy");
            intialize();
        };

        reader.readAsDataURL(input.files[0]);
    }
}

var proImage = '';
var cropper;
function intialize() {
    proImage = $("#ProImage");
    cropper = proImage.cropper({
        aspectRatio: 1 / 1,
        center: true,
        background: false,
        zoomable: true,
        scalable: false,
        viewMode: 1,
    });

    console.log(cropper);
}

function crop() {
    proImage.cropper("getCroppedCanvas").toBlob(function (blob) {
        $("#cropit")
            .text("Processing.. ")
            .append('<i class="fa fa-spinner fa-spin post_spinner"></i>')
            .prop("disabled", true);
        $(".publish_pro").removeClass("hideme");
        var formData = new FormData();
        formData.append("userfile", blob);

        manageMyAjaxPostRequestData(
            formData,
            base_url + "dashboard/upload_profile_image"
        ).done(function (response) {
            $("#cropit").attr("disabled", true);
            if (response.status == 1) {
                $(".close").trigger("click");
                setTimeout(function () {
                    location.reload(true);
                }, 1000);
            } else {
                $("#upload_err").html(response.message);
                return false;
            }
        });
    });
}
/***************** Profile cropp and upload END******************/

/***************** Cover page video and popup video play and pause START ******************/
var pbv = $(".popup_banner_video"), cbv = $(".cover_banner_video"), avp = $(".au_video_popup"), sp = $(".speaker");
$(document).on("click", ".play_cover_video", function (event) {
    if (pbv.length) {
        pbv[0].play();
        pbv.prop("muted", false);
    }
    if (cbv.length) {
        cbv[0].pause();
        cbv.prop("muted", true);
    }
    avp.addClass("open_popup");
    sp.removeClass("mute");
});

$(document).on("click", ".close_btn ", function (event) {
    if (cbv.length) {
        cbv[0].play();
        cbv.prop("muted", true);
    }
    if (pbv.length) {
        pbv[0].pause();
        pbv.prop("muted", true);
    }
    avp.removeClass("open_popup");
    sp.addClass("mute");
});


/***************** Cover page video and popup video Sound mute END ******************/

/***************** Error hide on tab change  ******************/
$(".action_text").click(function (e) {
    $("#showerror").text("");
});
/***************** Error hide on tab change  ******************/

/***************** Home page scroll  ******************/
if ($("#ScrollToHome").length) {
    $("html,body").animate({ scrollTop: 500 }, 10);
}
/***************** Home page scroll  ******************/

/***************** Get direct tab by url  ******************/
$(document).ready(function () {
    setTimeout(function () {
        var url = window.location.href;
        var activeTab = url.substring(url.indexOf("#") + 1);
        $('a[href="#' + activeTab + '"]').tab("show");

        if (activeTab == "media")
            $(".mob_hide[href='#media']").trigger("click");

        if (activeTab == "message") {
            $(".mob_hide[href='#message']").trigger("click");
        }
        if (activeTab == "chat_message") {
            $(".mob_hide[href='#chat_message']").trigger("click");
        }

    }, 100);
});

/***************** Get direct tab by url  ******************/
 function isJSON(str) { 
            try { 
                return (JSON.parse(str) && !!str); 
            } catch (e) { 
                return false; 
            } 
        } 

$(document).on("click", "#show_more", function () {
    $('.nav-tabs a[href="#detailDescription"]').tab("show");
});

$(document).on("click", ".delete_channel_video", function () {
    var _this = $(this);
    confirm_popup_function(
        "Delete",
        "Are you sure you want to delete this video?",
        "delete_channel_video(" + _this.attr("data-post_id") + ")"
    );
});
function delete_channel_video(post_id) {
    var formData = new FormData();
    formData.append("post_id", post_id);
    $('#feature_area' + post_id).empty().html(cnah);

    $("#conf_btn")
        .text("Deleting ")
        .append('<i class="fa fa-spinner fa-spin post_spinner"></i>')
        .prop("disabled", true);

    manageMyAjaxPostRequestData(
        formData,
        base_url + "dashboard/DeleteChannelVideo"
    ).done(function (resp) {
        if (resp != 0) {
            $('.modal').modal('hide');

            $(document)
                .find("[data-post_delete_id='" + post_id + "']")
                .remove();
            $('#feature_area' + post_id).empty().html(cnah);
            $(".dis_select_video").show();


            $("#conf_btn").text("Delete").prop("disabled", false);

            // swiper.forEach()
            $("div.swiper-container").each(function (index, item) {
                let thhs = $(this);
                // console.log(item);
                swiper[index].update();
            });
            setTimeout(function () {
                success_popup_function("Your video has been deleted");
            }, 1000);
        } else {
            server_error_popup_function(
                "something went wrong, please try again"
            );
        }
    });
}

$(document).on("click", ".delete_playlist", function () {
    var _this = $(this);
    confirm_popup_function(
        "Delete",
        "Are you sure you want to delete this playlist?",
        "delete_playlist(" + _this.attr("data-playlist_id") + ")"
    );
});


$(document).ready(function () {
    if ($(".au_artist_slider").length) {
        var time = 500;
        $("div.au_artist_slider").each(function () {
            let thhs = $(this);
            setTimeout(function () {
                AdAdsOnChannel(thhs, function () {
                    swiperslider(thhs);
                })
            }, time)
            time += 1000;
        });
    }
});

var start = 0;
var swiper = "";
$(document).ready(function () {
    if ($(".au_artist_slider_old")) {
        if ($("#appendGenreSlider_old").length) {
            $(window).scroll(function () {
                if (
                    $(window).scrollTop() + $(window).height() > $(document).height() - 100
                ) {
                    if ($("#appendGenreSlider").attr("data-load") == 1) {


                        var formData;
                        formData = new FormData();
                        formData.append("start", start++);
                        let url = $("#appendGenreSlider").data("url");
                        $("#appendGenreSlider").attr("data-load", 0);
                        manageMyAjaxPostRequestData(
                            formData,
                            base_url + url
                        ).done(function (resp) {

                            if ($.trim(resp).length > 1) {

                                $("#appendGenreSlider").attr("data-load", 1);

                                $(".dis_loadmore_loader").text("");
                                $("#appendGenreSlider").append(resp);
                                let thhs = $("div.au_artist_slider:last");
                                AdAdsOnChannel(thhs, function () {
                                    setTimeout(() => {
                                        swiperslider(thhs);
                                    }, 600)
                                })
                            }


                        });
                    }
                }
            });
        }
    }

});

$(document).ready(function () {
    if ($(".au_artist_slider")) {
        if ($("#appendGenreSlider").length) {
            $(window).scroll(function () {
                if ($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
                    if ($("#appendGenreSlider").attr("data-load") == 1) {

                        var formData;
                        formData = new FormData();
                        formData.append("start", start++);
                        let url = $("#appendGenreSlider").data("url");
                        $("#appendGenreSlider").attr("data-load", 0);

                        manageMyAjaxPostRequestData(
                            formData,
                            base_url + url
                        ).done(function (resp) {

                            if ($.trim(resp).length > 1) {
                                resp = JSON.parse(resp);
                                if (resp.status == 1) {

                                    let resData = resp.data;
                                    $.each(resData, function (i) {
                                        $("#appendGenreSlider").attr("data-load", 1);

                                        $(".dis_loadmore_loader").text("");

                                        var sliderHtml = getSliderHtml(resData[i]);

                                        $("#appendGenreSlider").append(sliderHtml);

                                        let thhs = $("div.au_artist_slider:last");
                                        AdAdsOnChannel(thhs, function () {
                                            setTimeout(() => {
                                                swiperslider(thhs);
                                            }, 100)
                                        })
                                    });
                                }
                            }


                        });
                    }
                }
            });
        }
    }

});


/************** Redirect To CHANNEL Video STARTS from slider************************/
/* $(document).on("click", ".dis_postvideo_img", function (e) {
    e.preventDefault();
    let href = $(this).find("a").attr("href");

    if (href.length) window.location = href;
}); */
/************** Redirect To CHANNEL Video END from slider ************************/

function initSelect2(){
    if ($('[data-target="select2"]').length) {
        $('[data-target="select2"]').each(function () {
            var options = eval("[" + $(this).attr("data-option") + "]");
            if ($.isPlainObject(options[0])) {
                options[0] = $.extend({}, options[0]);
            }
            // console.log(options);
            $(this)[$(this).attr("data-target")].apply($(this), options);
        });
    }
}
initSelect2();

$(document).on("change", ".SelectBySelect2", function () {
    var _this = $(this);
    var formData = new FormData();
    formData.append("id", _this.val());
    manageMyAjaxPostRequestData(formData, base_url + _this.data("url")).done(
        function (resp) {
            resp = $.parseJSON(resp);
            if (resp["status"] == 1) {
                var data = [];
                $(_this.attr("data-id")).empty().trigger("change");
                data = [{ id: "", text: "" }];

                $.each(resp["data"], function (key, val) {
                    data.push({ id: val.id, text: val.name });
                });

                $(_this.attr("data-id")).select2({
                    data: data,
                    placeholder: _this.attr("data-placeholder"),
                    closeOnSelect: false,
                });
            }
        }
    );
});


/************** DONT DELETE IT , FOR FUTURE NEED send endorsement request STARTS ************************/

/*$(document).on('click','.endorse_you',function(e){
        if(user_login_id != ''){
            let ths = $(this);
            let user_id = ths.data('uid');
            let status = ths.data('status');
            if(user_id.toString().length > 0){
                if(status  == 2){
                    server_error_popup_function('Your request has been accepted already');	
                }else 
                if(status  == 3){
                    server_error_popup_function('Your request has been Rejected already');	
                }else{
                    $('.endorser').attr('data-endorsee_id',user_id)	
                    if(status == 1){
                        $('.endorser').text('Cancel Request');
                        $('.endorser').attr('data-status',1);
                    	
                    }else{
                        $('.endorser').text('Send Request')
                        $('.endorser').attr('data-status',0);
                    }
                    $('#endorsementsModal').modal('show');	
                }
            }
        }else{
            $('#myModal').modal('show');
        }
    })
	
    $(document).on('click','.endorser',function(e){
        if(user_login_id != ''){
        	
            let ths = $(this);
            let endorsee_id = ths.data('endorsee_id');
            let status = ths.attr('data-status');
            if(endorsee_id.toString().length > 0){
            	
                if(status == 1){
                    $('#conf_text').text('Do you really want to cancel this endorsement request ?');
                }else{
                    $('#conf_text').text('Do you really want to send this endorsement request ?');
                }
            	
                $('#conf_title').text('YES');
                $('#conf_btn').attr('onclick','ConfirmationOfEndorement("'+endorsee_id+'",'+status+')');
                $('#conf_btn').show();
                $('#conf_header').addClass('notification_popup');
                $('#conf_header').removeClass('success_popup');
                $('#confirm_popup').modal('show');
            }
        }else{
            $('#myModal').modal('show');
        }
    })
    */
function ConfirmationOfEndorement(endorsee_id, status) {
    let formData = new FormData();
    formData.append("endorsee_id", Number(endorsee_id));
    manageMyAjaxPostRequestData(
        formData,
        base_url + "endorse/IWantToendorseYou"
    ).done(function (resp) {
        resp = JSON.parse(resp);
        $("#endorsementsModal").modal("hide");
        if (resp.status == 1) {
            $(".EndorseButton").html(resp.data);
            $("#confirm_popup").modal("hide");
        } else {
            server_error_popup_function(resp.messsage);
        }
    });
}

$(document).on("click", ".endorse_you", function (e) {
    if (user_login_id != "") {
        let ths = $(this);
        let endorsee_id = ths.data("uid");
        let status = ths.data("status");
        if (endorsee_id.toString().length > 0) {
            if (status == 2) {
                server_error_popup_function(
                    "Your request has been accepted already"
                );
            } else if (status == 3) {
                server_error_popup_function(
                    "Your request has been Rejected already"
                );
            } else {
                if (status == 1) {
                    confirm_popup_function(
                        "YES",
                        "Are you sure you want cancel this endorsement??",
                        'ConfirmationOfEndorement("' +
                        endorsee_id +
                        '",' +
                        status +
                        ")"
                    );
                } else {
                    confirm_popup_function(
                        "YES",
                        "Are you sure you want send this endorsement?",
                        'ConfirmationOfEndorement("' +
                        endorsee_id +
                        '",' +
                        status +
                        ")"
                    );
                }
            }
        }
    } else {
        $("#myModal").modal("show");
    }
});

/************** send endorsement request END ***********************/

/************** receive endorsement request STARTS ****************/
$(document).on("click", ".endorse_me", function (e) {
    if (user_login_id != "") {
        let ths = $(this);
        let endorse_id = ths.data("id");
        let status = ths.data("status");

        if ($(".agree").is(":checked")) {
            if (status == 2) {
                $("#conf_title").text("Accept Endorsement");
                $("#conf_text").text(
                    "Are you sure you want to accept this endorsement? ?"
                );
                $("#conf_btn").attr(
                    "onclick",
                    'endorsee("' + endorse_id + '",' + status + ")"
                );
            } else {
                $("#conf_title").text("Decline Endorsement");
                $("#conf_text").text(
                    "Are you sure you want decline this endorsement? ?"
                );
                $("#conf_btn").attr(
                    "onclick",
                    'endorsee("' + endorse_id + '",' + status + ")"
                );
            }

            $("#conf_btn").show();
            $("#conf_header").addClass("notification_popup");
            $("#conf_header").removeClass("success_popup");
            $("#confirm_popup").modal("show");
        } else {
            $("#check").text("Please check this first");
        }
    } else {
        $("#myModal").modal("show");
    }
});

function endorsee(endorse_id, status) {
    if (user_login_id != "") {
        let ths = $(this);

        let formData = new FormData();
        formData.append("endorse_id", endorse_id);
        formData.append("endorsement_status", status);

        manageMyAjaxPostRequestData(
            formData,
            base_url + "endorse/endorse_me"
        ).done(function (resp) {
            resp = JSON.parse(resp);
            // console.log(resp);
            if (resp.status == 1) {
                success_popup_function(resp.message);
            } else {
                server_error_popup_function(resp.message);
            }
            setTimeout(function () {
                location.reload(true);
            }, 3000);
        });
    } else {
        $("#myModal").modal("show");
    }
}
/************** receive endorsement request END ****************/

$(document).ready(function(){
    if ($(".watchLoadMore").length) {
        $(".watchall").trigger("click");
        $(window).scroll(function () {
            if (
                $(window).scrollTop() + $(window).height() >
                $(document).height() - 100
            ) {
                $(".watchall").trigger("click");
            }
        });
    }
})

var offset = 0;
$(document).on("click", ".watchall", function () {
    $(".pro_loader").show();
    var ths = $(this);
    var formData = new FormData();
    ths.removeClass("watchall");
    formData.append("offset", offset);

    const urlParams = new URLSearchParams(window.location.search);
    const mode_id = urlParams.get('mode_id');

    formData.append("mode_id", mode_id);

    if ($(".pro_loader").attr("data-load") == 1) {
        $(".pro_loader").show();

        manageMyAjaxPostRequestData(formData, base_url + "home/watchAll").done(
            function (resp) {
                if ($.trim(resp).length > 1) {
                    $("#loadMoreGenre").append(resp);
                    offset = offset + 10;
                } else {
                    ths.hide();
                    $(".pro_loader").attr("data-load", 0);
                    $(".dis_loadmore_loader").text(
                        "-- Looks like you have reached the end of the list --"
                    );
                }
                $(".pro_loader").hide();
                $(".watchLoadMore").addClass("watchall");
            }
        );
    }
});

if ($(".datePicker").length) {
    $(".datePicker").daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minYear: 1920,
        // maxYear: parseInt(moment().format('YYYY'),10),
        locale: {
            format: "YYYY-MM-DD",
        },
    });
}

function PassUpdateForm($this) {
    var err_count = 0;
    let formData = new FormData();

    $(".require").each(function () {
        $(this).removeClass("error");
        $(this).parents(".form-group").find(".form-error").text("");

        if ($.trim($(this).val()) == "" || $.trim($(this).val()) == "0") {
            $(this).addClass("error");

            let placeholder = "";
            if ($(this).attr("placeholder")) {
                placeholder = $(this).attr("placeholder");
                placeholder = placeholder.toLowerCase();

                $(this)
                    .parents(".form-group")
                    .find(".form-error")
                    .text("Please enter " + placeholder.replace("*", "") + ".");
            }
            $(this).focus();
            err_count++;
            return false;
        }

        var clsStr = $(this).attr("class");

        if (clsStr.search("pwd") != -1) {
            var pwd = $(this).val();
            var PwRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&#])[A-Za-z\d$@$!%*?&#]{8,}$/;
            if (!PwRegex.test(pwd)) {
                $(this).addClass("error");
                $(this)
                    .parents(".form-group")
                    .find(".form-error")
                    .text(
                        "Invalid password, please make sure the password is atleast 8 characters with one number, one capatalized letter and one special character."
                    );
                err_count++;
                return false;
            }
        }

        if (clsStr.search("repwd") != -1) {
            var repwd = $(this).val();
            var pwd = $(".pwd").val();

            if (pwd != repwd) {
                $(this).addClass("error");
                $(this)
                    .parents(".form-group")
                    .find(".form-error")
                    .text("Passwords do not match.");

                err_count++;
                return false;
            }
        }
        formData.append($(this).attr("name"), $(this).val());
    });

    if (err_count == 0) {
        var action = $($this).attr("action");
        manageMyAjaxPostRequestData(formData, action).done(function (data) {
            if (data.status == 1) {
                Custom_notify("success", data.message);
                $($this).trigger("reset");
            }
        });
    }
}
function UpdateUserForm($this) {
    var err_count = 0;
    let formData = new FormData();

    $(".validate").each(function () {
        $(this).removeClass("error");
        $(this).parents(".form-group").find(".form-error").text("");

        if ($.trim($(this).val()) == "" || $.trim($(this).val()) == "0") {
            $(this).addClass("error");

            let placeholder = "";
            if ($(this).attr("placeholder")) {
                placeholder = $(this).attr("placeholder");
                placeholder = placeholder.toLowerCase();

                $(this)
                    .parents(".form-group")
                    .find(".form-error")
                    .text("Please enter " + placeholder.replace("*", "") + ".");
            }
            $(this).focus();
            err_count++;
            return false;
        }
        formData.append($(this).attr("name"), $(this).val());
    });

    if (err_count == 0) {
        var action = $($this).attr("action");
        manageMyAjaxPostRequestData(formData, action).done(function (data) {
            if (data.status == 1) Custom_notify("success", data.message);
        });
    }
}

var is_deleted_status = '';
$(document).on('click', '.deleteMyAcc , .deactivateMyAcc', function () {
    $('.dis_deleteacc_tm').removeClass('hide');
    $('.dis_deleteacc_reason').addClass('hide');

    is_deleted_status = $(this).val();
    let t1 = (is_deleted_status == 2) ? 'DELETE USER ACCOUNT' : 'DEACTIVATE USER ACCOUNT';
    let t2 = (is_deleted_status == 2) ? 'Select date for delete account' : 'Select date for reactivate account';
    let t3 = (is_deleted_status == 2) ? 'Reason for deleting account (optional)' : 'Reason for deactivating account (optional)';

    $('#formTitle1').html(t1);
    $('#formTitle2').html(t2);
    $('#formTitle3').html(t3);

    setTimeout(() => {
        $('#deleteuser_account').modal('show');
    }, 1000)
})

var deleteOrDeactivateDate = '';
$(document).on('click', '.agreeForDeleteOrDeactivate', function () {
    deleteOrDeactivateDate = $('#deleteOrDeactivateDate').val();
    $('.dis_deleteacc_tm').addClass('hide');
    $('.dis_deleteacc_reason').removeClass('hide');
});

$(document).on('click', '.submitDltOrDactvtAccRqst', function () {
    deleteOrDeactivateDate = $('#deleteOrDeactivateDate').val();
    let r = $(this);
    let f = new FormData();
    f.append("is_deleted_status", is_deleted_status);
    f.append("delete_or_reactivate_date", deleteOrDeactivateDate);
    f.append("reason", $('#reason').val());

    var d = new Date();
    f.append("offset", d.getTimezoneOffset());

    manageMyAjaxPostRequestData(
        f, base_url + "dashboard/deleteOrDeactivateMyAccount"
    ).done(function (data) {
        console.log(data);
        if (data.status == 1) {
            Custom_notify("success", data.message);
            $('#deleteuser_account').modal('hide');
            setTimeout(() => {
                window.location.replace(base_url + 'home/logout');
            }, 2000);
        }
    });
})







if ($('input[name="old_password"]').length) {
    setTimeout(function () {
        $('input[name="old_password"]').val("");
        $('input[name="new_password"]').val("");
    }, 1000);
}

$(".cut_copy_paste").bind("cut copy paste", function (e) {
    e.preventDefault();
});


/********Gamepass Subscribe Form********/
$(document).on('click', '#gamepass_form_submit', function (event) {
    event.preventDefault();  
    const _this = $(this);
    let u_em = $('#validate_email').val()
    let recaptcha_response  = $.trim(getRecaptchaResponse('gamepass-recaptcha'));
    var emRegex = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,15}(?:\.[a-z]{2})?)$/i;
    _this.closest('form').find('.form-error').text('');
	if(!u_em){
        Custom_notify("error", 'Please enter your email.');
        return false;
    }else 
    if (!emRegex.test(u_em)) {
        Custom_notify("error", 'Please enter a valid email address.');
        return false;
    }

    if(recaptcha_response ===""){
        _this.closest('form').find('.form-error').text('Please complete the reCAPTCHA.');
        return false;
    }

    let btnTxt = _this.text();
    let form = _this.closest('form')[0];
    let f = new FormData(form);
    _this.html(btnTxt+'<i class="fa fa-spinner fa-spin post_spinner"></i>');
    grecaptcha.reset();
    manageMyAjaxPostRequestData(
        f, base_url + "home/validateGamepassEmail"
    ).done(function (resp) {
        let data = JSON.parse(resp)
        console.log(data);
        if (data.status == 1) {
            Custom_notify("success", data.mess);
            _this.html(btnTxt);
            form.reset();
            $('#gamepass_email_popup').modal('hide');
            setTimeout(() => {
              //  window.location.replace(base_url + 'home/logout');
            }, 2000);
        }else{
            Custom_notify("error", data.mess);
            _this.html(btnTxt);
        }
    });
})

/********Gamepass Subscribe Form********/




