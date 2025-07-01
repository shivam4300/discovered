var oEimg           =   base_url+'repo/images/banner_logo1.png';
var is_chat_page    =   $('#socket_chat_page').val();
var showChatHistory =   $('#show_chat_history'); 
var userChatBox     =   $(".dis_userchat_box");

var d 		        =   new Date();
var popup_loader  	=   base_url+'repo/images/loader.gif';
var goto_profile  	=   base_url+'profile?user=';
var separatorDate1  =   '';

var cnvrstnId , friend ,target_obj = '', connectionList = [], parentAuthId = "", messages = [], write_msg = '', notificationConnections = [] ;
var skip = 0, limit = 50, active = false;


const socket = io('https://'.concat(window.location.host), {path: "/node/socket.io"} );

window.addEventListener("load", async() => {
    socket.emit("user-status", {status:"Online", user_id: user_login_id})
    await getUserConnection();
});

$(document).on('keyup','#SearchUser',async function(e){
    if (e.keyCode == 13) { e.preventDefault(); return false; }
    let _this   = $(this);
    let search  = (_this.val()).trim();
    if(search.length){ 
        $('#SearchUserClose').removeClass('hide');
        let url = base_url+'chat/search_user';
        let resultAr = [];

        $.ajax({
                type	:'POST',
                dataType:'JSON',
                url		: url,
                data	: {'search':search},
                success	: function(result){
                    result.map(re => {
                        let existConn = connectionList.find(cl => cl.user_id === re.user_id);
                        if(existConn && Object.keys(existConn).length > 0 ) {
                            resultAr.push(existConn)
                        } else resultAr.push(re)
                    });

                    ShowUserLIst(resultAr,'PEOPLE','NEW')
                }
        });
    } else {
        _this.val("");
        $('#search_user_list').empty();
        $('#SearchUserClose').addClass('hide');
        
        await getUserConnection();
        
        if (is_chat_page !== 'true') load_popup();
        
    }
});

$(document).on('keydown','#searchChatMsg ,#SearchUser',function(e){
    if (e.keyCode == 13) { e.preventDefault(); return false; }
});

$(document).on("click", ".custom_dropdown_wrap", function(e){
    $(this).toggleClass("open");
})

/* function for searchClose */ 
$(document).on('click','.dis_chat_pp_search_close',function(e){
    if($(this).parents('.dis_chat_search').find("input[type=text]").attr('id')=='SearchUser'){
        $('#SearchUser').val('').trigger('keyup');
    }else{
        $('#searchChatMsg').val('').trigger('keyup');
    }
});

/* function for search messages of the users. */
$(document).on('keyup','#searchChatMsg',function(e){
    let queryTerms = $.trim($(this).val());
    searchChatMsg(queryTerms);
})

$('.dis_userchat_box').scroll(async function(){
    if($(this).scrollTop() === 0){
        $("#chat_inside_loader").css("display","");
        skip = skip + limit
        active = true;
        
        let details = await databaseMessages();
        if(details) {
            let userDetails = details.userDetails;
            if(userDetails.status === "success") {
                messages.unshift(...userDetails.messages)

                ShowMessageList(messages);
                setTimeout(() => {
                    $("#chat_inside_loader").css("display","none");
                }, 1000);
            }
        }
    }
});

/* show all messages of the active user. */
$(document).on('click','.chat_list:not(.active)',  function() {
    target_obj = $(this);
    skip = 0; active = false;
    if(msg_open){
        message_open()
    }else{
        loadScript(CDN_BASE_URL+TWEMOJI_JS,function(){
            message_open();
            msg_open = true;
        }); 
    }
   
    async function message_open(){
        startAtId= ''
        separatorDate1=''; messages = [];
        $('.write_msg').val('');
        $('.dis_userchat_inner').removeClass('hide');
        $('#chat_welcome_msg').addClass('hide');
        $("#message_skeleton").find(".dis_skeleton").css("display", "");

        let details = await databaseMessages();
        if(details) {
            let userDetails = details.userDetails;
            let userAttr = details.desc;
            
            if(userDetails.status === "success") {
                let d       = userDetails.data;
                messages    = userDetails.messages;
                $('.chat_list').removeClass('active');  
                target_obj.addClass('active');
                
                target_obj.attr('data-key', d._id);
                updateActiveStatus(d.status);
                $('.type_msg').show();
    
                // for sidebar user profile
                $('#sidebar_user_uname').val(userAttr.user_uname);
                $('#sidebar_user_profile_pic').attr('src', target_obj.find('img').attr('src'));
                $('#sidebar_user_name').text(userAttr.user_name);
                $('#sidebar_user_profile_redirect').attr('href',userAttr.profile_url);
                $('#sidebar_user_channel_redirect').attr('href', base_url+'channel?user='+userAttr.user_uname);
    
                $('#active_user_pic').attr('src',target_obj.find('img').attr('src'));
                $('#active_user_name').text(userAttr.user_name).attr('href',userAttr.profile_url);
                
                if($('.dis_chat_search').hasClass('open')){
                    $('.dis_chat_search').removeClass('open');
                    $('#searchChatMsg').val('');
                }
    
                if($(".custom_dropdown_wrap").hasClass("open")){
                    $('.custom_dropdown_wrap').removeClass('open');
                }
    
                let conn_status     = target_obj.attr("data-connection-status");
                $("#blockTextHeader").html(conn_status === "accepted" || conn_status === "pending" ? "Block" : "Unblock")
                
                $('.dis_chat_Righttsidebar').show();
                setTimeout(() => {
                    ShowMessageList(userDetails.messages);
                },1000);
            } else {
                console.log("else");
            }
        }
    }
});

/* send message to the user using send button. */
$(document).on('click','#send_mg_btn',function(e){
    var is_chat_page = $('#socket_chat_page').val();
    if (is_chat_page !== 'true') { target_obj = $('#data_for_msging'); showChatHistory = $('#single_chat_messages'); }
    let conn_status = target_obj.attr("data-connection-status");
    if(conn_status === "accepted") {
        $('.sendMessage').submit(); 
    }
});

/* send message to the user on enter button click. */
$(document).on('submit','.sendMessage',function(e){
    e.preventDefault();

    var is_chat_page = $('#socket_chat_page').val();
    if (is_chat_page !== 'true') { target_obj = $('#data_for_msging'); showChatHistory = $('#single_chat_messages'); }
    
    let conn_status = target_obj.attr("data-connection-status");
    if(conn_status === "accepted") {
        sendMessage();
    }
});

/* showing the status of typing messages */
$(document).on('keyup','.write_msg',function(e){
    socket.emit("typing-status", {typing: true, user_id: user_login_id, conn_uname: user_uname});
    var thiss = $(this);
    write_msg = $(this);

    if(e.keyCode == 13 && e.shiftKey){
        let content = this.value;
        // console.log("content", content);
        let conn_status = target_obj.attr("data-connection-status");
        if(conn_status === "accepted") {
            sendMessage();
        }
        // let caret = getCaret(this);
        // thiss.value = content.substring(0,caret)+"\n"+content.substring(caret,content.length-1);
    }else if((e.keyCode || e.which) == 13){
        thiss.parents('.sendMessage').submit();
        return false;
    }
})

$(document).on('focusout','.write_msg',function(e){
    socket.emit("typing-status", {typing: false, user_id: user_login_id, conn_uname: user_uname});
});

/* file upload feature. */
$(document).on('change','#attachment_image_upload, #attachment_video_upload, #attachment_file_upload', async function(e){
    var targetId = e.target.id
    var files = e.target.files;
	var file = files[0], fileType = "";
	if (files.length > 0) {
        if(targetId === "attachment_image_upload") {
            fileType = "img"
            if (file.type !== "image/jpg" && file.type !== "image/jpeg" && file.type !== "image/png") {
                setTimeout(() => {
                    Custom_notify("error", "Please choose a correct file format (JPG, JPEG, PNG).");
                }, 1000);
                return
            }
        } 
        if(targetId === "attachment_video_upload") {
            fileType = "video"
            if (file.type !== "video/mp4" ) {
                setTimeout(() => {
                    Custom_notify("error", "Please choose a correct file format (Only MP4).");
                }, 1000);
                return
            }
        } 
        
        if(targetId === "attachment_file_upload") {
            fileType = "file"
            if (file.type !== "application/vnd.openxmlformats-officedocument.wordprocessingml.document" && file.type !== "text/plain" && file.type !== "application/pdf" && file.type !== "application/x-zip-compressed") {
                setTimeout(() => {
                    Custom_notify("error", "Please choose a correct file format (DOC, TXT, PDF, ZIP ).");
                }, 1000);
                return
            }
        } 
        
        fileUpload(file)
    }
});

/* accept message request */
$(document).on("click",".dis_chatbtn.green", async function(e){
    let conn_id = target_obj.attr("data-key");
    let postData = {
        Connkey: conn_id,
        sender_auth_id: parentAuthId,
        conn_status: 1
    }

    socket.emit('change-connection-status', postData);

    target_obj.attr('data-connection-status', 'accepted');
    $(".dis_userchat_footer").removeClass("hide");    

    messages.map( msg => {
        let cnvrstnId 	= (msg.sender < msg.receiver)? msg.sender+msg.receiver : msg.receiver+msg.sender;
        $("#"+cnvrstnId).text(msg.message);
    })

    ShowMessageList(messages);    
});

$(document).on("click",".dis_chatbtn.red", async function(e){
    let conn_id = target_obj.attr("data-key");
    let postData = {
        Connkey: conn_id,
        sender_auth_id: parentAuthId,
        conn_status: 3
    }

    socket.emit('change-connection-status', postData);

    target_obj.attr('data-connection-status', 'blocked');
    
    setTimeout(() => {
        ShowMessageList(messages);    
    }, 100);
});

/* Block user from the profile */
$(document).on("click", "#block_unblock_btn", async function(e){
    let conn_status = target_obj.attr("data-connection-status");
    let type = conn_status === "accepted" ? "block" : "unblock";

    if(confirm("Are you sure, you want to "+ type +" this user?")) {
        let conn_id = target_obj.attr("data-key");
        let postData = {
            Connkey: conn_id,
            sender_auth_id: parentAuthId,
            conn_status
        }
    
        let changeStatus = await handleRequest("POST", node_url+'block-unblock-connection', postData);
        if(changeStatus.status === "success") {
            setTimeout(() => {
                if(conn_status === "accepted") {
                    target_obj.attr("data-connection-status", "blocked");
                    $("#submitMessageInut").hide();
                    $(".blockUserMsg").show();
                    $("#blockTextHeader").html("Unblock")
                } else{
                    target_obj.attr("data-connection-status", "accepted");
                    $(".blockUserMsg").hide();
                    $("#submitMessageInut").show();
                    $("#blockTextHeader").html("Block")
                }
            }, 100);
        }
    }
})
/* Clear chat function */
$(document).on("click", "#clear_chat_btn", async function(e){
    let userId = target_obj.attr("data-id");
    let conn_id = target_obj.attr("data-key");

    let cnvrstnId 	= (user_login_id < userId)? user_login_id+userId : userId+user_login_id;

    if(confirm("Are you sure, you want to clear chat?")) {
        let postData = {
            sender: user_login_id,
            receiver: userId,
            sender_auth_id: parentAuthId,
            conn_id
        }
    
        let clearChat = await handleRequest("POST", node_url+'clear-chat', postData);
        if(clearChat.status === "success") {
            setTimeout(() => {
                messages = [];
                $("#show_chat_history").html("");
                $("#"+cnvrstnId).html("");
                $('#'+cnvrstnId).parent('.dis_ccd_usermsg').parent().find('li.chat_date').text(unixTime(date.now.toString()));
            }, 100);
        }
    }
})
/* Delete user function */ 
$(document).on("click", "#delete_chat_user", async function(e){
    let userId = target_obj.attr("data-id");
    let conn_id = target_obj.attr("data-key");

    let cnvrstnId   = (user_login_id < userId)? user_login_id+userId : userId+user_login_id;

    if(confirm("Are you sure, you want to Delete User?")) {
        let postData = {
            sender: user_login_id,
            receiver: userId,
            sender_auth_id: parentAuthId,
            conn_id
        }
        
        messages = [];
        let deleteUser = await handleRequest("POST", node_url+'delete-user', postData);
        if(deleteUser.status === "success") {
            if(connectionList.length === 1) {
                connectionList = []
            } else {
                const newList = connectionList.filter(cl => cl.key !== conn_id);
                connectionList = newList;
            }

            setTimeout(() => {
                target_obj = '';
                $(".chat_list.active").remove();
                $('#chat_welcome_msg').removeClass('hide');
                if($(".chat_list").length === 0) {
                    $("#search_user_list").html('<li style="text-align: center;">No connections found.</li>');
                }
            }, 100);
        }
    }
})

/* socket connections start */
socket.on("received-message", async(data) => {
    const { receiver, sender, message, time } = data;
    
    let cnvrstnId 	= (sender < receiver)? sender+receiver : receiver+sender;
    
    if(target_obj) {
        conn_id = target_obj.attr('data-id');
        Connkey = target_obj.attr('data-key');
        target_id =	target_obj.attr('data-id');
        
        if(receiver === user_login_id && sender === conn_id) {
            html = receiveMessage(data, Connkey);
        }
    
        messages.push(data);
        if(target_obj.length){
            if((target_id == sender || target_id == receiver)  && ( user_login_id ==  sender || user_login_id ==  receiver)){
                setTimeout(() => {
                    showChatHistory.append(html);
                    userChatBox.stop().animate({scrollTop:userChatBox[0].scrollHeight},1000);

                    richLinkCode($('.contentTextChat').last());
                }, 1000);
            } else {
                if(receiver === user_login_id) { 
                    let getUser = connectionList.filter((cl) => cl.user_id === sender);
                    if(getUser.length === 0) {
                        addNewUser(data)
                    }
                }
            }

            msgCount(cnvrstnId, data)
        }

        var result = $('.chat_list').sort(function (a, b) {
            let contentA = $(a).attr("data-id");
            let contentB = $(b).attr("data-id");
        
            return (sender === contentA) ? -1 : (sender === contentB) ? 1 : 0;
        });
        
        // sortMyUserList();

        if((target_id == sender || target_id == receiver)  && ( user_login_id ==  sender || user_login_id ==  receiver)) {
            let postData = {
                user_data_id : target_obj.attr('data-key'),
                parentAuthId
            }
            let updateUnreadCount = await handleRequest("POST", node_url+'update-unreadmsg', postData);
            if(updateUnreadCount.status === "success") {
                $('#'+cnvrstnId).siblings('.dis_chat_msgcount').remove();
            }
        }
        $("#search_user_list").html(result);
    } else {

        var result = $('.chat_list').sort(function (a, b) {
            let contentA = $(a).attr("data-id");
            let contentB = $(b).attr("data-id");
        
            return (sender === contentA) ? -1 : (sender === contentB) ? 1 : 0;
        });
        
        $("#search_user_list").html(result);

        if(receiver === user_login_id) {
            let getUser = connectionList.filter((cl) => cl.user_id === sender);
            if(getUser.length === 0) {
                addNewUser(data)
            }

            msgCount(cnvrstnId, data);
        }
    }
});

socket.on("change-typing-status", (data) => {
    checkUserTypingStatus(data);
});

socket.on("change-user-status", (data) => {
    $(".chat_list").map((i,cl) => {
        if(cl.getAttribute("data-id") === data.user_id) {
            if(cl.classList.contains("active")) updateActiveStatus(data.status);
            $(cl).find(".dis_chat_contacts_avatar").removeClass('online offline').addClass(data.status.toLowerCase());
            cl.setAttribute("data-online-status", data.status)
        }
    });
});

socket.on("change-connection", (data) => {
    if(data) {
        $('.chat_list').map((i,cl) => { 
            if($(cl).attr('data-key') !== data.sender_auth_id) {
                if($(cl).attr('data-connection-status') === "pending") {
                    if(data.conn_status === 1) {
                        $(cl).attr('data-connection-status', 'accepted');
                    } else if(data.conn_status === 2) {
                        $(cl).attr('data-connection-status', 'pending');
                    } else {
                        $(cl).attr('data-connection-status', 'blocked');
                    }
                }
            }
        });
    }
})

/* socket connection end */


async function databaseMessages() {
    let desc = {
        user_uname: target_obj.attr('data-user_uname'),
        user_name: target_obj.attr('data-user_name'),
        user_data_id: target_obj.attr('data-key'),
        user_id: target_obj.attr('data-id'),
        user_pic: target_obj.attr('data-user_pic'),
        status: target_obj.attr('data-online-status'),
    }
    
    let postData = { ...desc, user_login_id, parentAuthId,userSelect:"chatList", skip, limit }

    let userDetails = await handleRequest("POST", node_url+'users/'+desc.user_id, postData);
    return { userDetails , desc}
}

function msgCount(cnvrstnId, data) {
    let msg_count = $('#'+cnvrstnId).next(".dis_chat_msgcount").text() !=="" ? parseInt($('#'+cnvrstnId).next(".dis_chat_msgcount").text()) : 0;
            
    if(msg_count !== 0) {
        $('#'+cnvrstnId).siblings('.dis_chat_msgcount').text(msg_count+1);
    } else {
        $('#'+cnvrstnId).parent('.dis_ccd_usermsg').append('<span class="dis_chat_msgcount">'+(msg_count+1)+'</span>');
    }

    data.ConnStatus !== "pending" ? $("#"+cnvrstnId).text((data.message).slice(0,30)) : null;
    $('#'+cnvrstnId).parent('.dis_ccd_usermsg').parent().find('li.chat_date').text(unixTime(data.time));
}

async function fileUpload(file) {
    const actualSize = await formatBytes(file.size);
    const fileSize = actualSize.split(" ");

    if(fileSize[0] > 10 && fileSize[1].trim() === "MB") {
        setTimeout(() => {
            Custom_notify("error", "file size is more than 10 MB, Please choose less than 10 MB.");
        }, 1000);
        return
    }

    conn_user_uname =	target_obj.attr('data-user_uname');
    conn_id 		=	target_obj.attr('data-id');
    friend 			=	target_obj.attr('data-friend');
    Connkey 		=	target_obj.attr('data-key');
    // cnvrstnId 		= 	(conn_id < user_login_id)? conn_id+user_login_id : user_login_id+conn_id;

    let target = "aud_"+user_login_id+"/chat/";
    target = target + makeid(20) +'.'+  (file.name).split('.').pop();

    var mesAry = {
        "sender"	:  	user_login_id ,
        "receiver"	:  	conn_id ,
        "time"   	: 	Date.now(),
        "read_status": 	0, 
        "sender_auth_id": parentAuthId, 
        "isDeletedReceiver" :false,
        "isDeletedsender"   :false,
        "Connkey"   : Connkey,
        "fileSize"  : actualSize,
        "fileType"  : file.type,
        "fileName"  : target.split("chat/").pop()
    }  
    
    let blob = new Blob([file], {type: file.type});
    let blobUrl = URL.createObjectURL(blob);

    mesAry["message"] = blobUrl;
    handleSendingMessages(mesAry);
    
    ProcessUpload(file, target, TRANS_BUCKET_NAME).
    then(function(data){
        setTimeout(() => {
            let url = AMAZON_TRANCODE_URL+target;
            messages[messages.length - 1].message = url;
            if(file.type.includes("image")) {
                let imageTag = $(".search_class:last").find(".dis_chat_textbox.image > p > a")
                imageTag.attr("href", url);
                imageTag.children("img").attr("src", url)
            } else if( file.type.includes("video")) {
                let videoTag = $(".search_class:last").find(".dis_chat_textbox.video > p > a")
                videoTag.attr("href", url);
                videoTag.children("video").attr("src", url)
            }
            $(".upload_blob").remove();
            // handleSendingMessages(messages[messages.length - 1]);
            socket.emit("new-message", messages[messages.length - 1])
        }, 1000);
    }).
    catch(function(err){
        console.log(err);
    })
}

function addNewUser(data) {
    if(data.newConnection) {
        connectionList.unshift({
            key: data.newConnection._id, 
            last_seen: data.newConnection.last_seen, 
            status: data.newConnection.status, 
            user_id: data.newConnection.user_id, 
            user_name: data.newConnection.user_name, 
            uc_pic: data.newConnection.user_pic, 
            user_uname: data.newConnection.user_uname,
            createdAt: data.newConnection.createdAt,
            last_message: "-",
            unread_msg: 0,
            update_at: data.time ? data.time?.toString() : Date.now().toString(),
            conn_status: 2
        });
    }

    ShowUserLIst(connectionList,'RECENT','OLD','newConnection');
}

function updateActiveStatus(status){
    let statusClass = 'offline';
    let statusText = 'Inactive Right Now';
    if(status=='Online'){
        statusClass = 'online';
        statusText = 'Active Right Now';
    }
    $('#active_user_status').removeClass('online offline').addClass(statusClass);
    $('#active_user_text').text(statusText);
}

async function sendMessage() {
    let write_msg = $('.write_msg');
    let message = write_msg.val();
        message = message.replace(/(<([^>]+)>)/gi, "");
        write_msg.val('');
        message = message.trim() 
    
    // console.log("message",message);
    if(message.length){
        if(friend == 'NEW'){ write_msg.prop('disabled', true); }
        
        conn_user_uname =	target_obj.attr('data-user_uname');
        conn_id 		=	target_obj.attr('data-id');
        friend 			=	target_obj.attr('data-friend');
        Connkey 		=	target_obj.attr('data-key');
        ConnStatus		=	target_obj.attr('data-connection-status');
        // cnvrstnId 		= 	(conn_id < user_login_id)? conn_id+user_login_id : user_login_id+conn_id;
        var mesAry = {
            "message"	:  	message ,
            "sender"	:  	user_login_id ,
            "receiver"	:  	conn_id ,
            "time"   	: 	Date.now(),
            "read_status": 	0, 
            "sender_auth_id": parentAuthId, 
            "isDeletedReceiver" :false,
            "isDeletedsender"   :false,
            "Connkey"   : Connkey,
            "ConnStatus": ConnStatus
        }        

        if(friend == 'NEW'){
            let postData = { 
                user_id : user_login_id,
                user_uname, 
                user_name,
                user_pic, 
                status: "Online"
            }
    
            let userDetails = await handleRequest("POST", node_url+'users/'+user_login_id, postData);
            if(userDetails.status === "success") {
                parentAuthId = userDetails.data._id
                let postData = {
                    "conn_id"           : Connkey,
                    "last_message"		: message,
                    "conn_uname"		: conn_user_uname,
                    "loggedin_user_id"  : userDetails.data._id,
                    "loggedin_user_name": userDetails.data.user_uname,
                    "update_at"			: Date.now(),
                    "conn_status" 		: 1,
                    "unrd_msg_cnt"		: 0,
                    "isTyping"		    : false,
                    "isDeleted"		    : 1
                }
                let getConnection = await handleRequest("POST", node_url+"add-users-connection", postData);
                if(getConnection.status === "success") {
                    getConnection.data["last_message"] = mesAry.message,
                    getConnection.data["update_at"] = mesAry.time,
                    connectionList.unshift(getConnection.data);
                    target_obj.attr('data-friend','OLD');
                    mesAry["connType"] = friend;
                    handleSendingMessages(mesAry);
                    write_msg.prop('disabled', false);
                    $('#SearchUser').val('').trigger('keyup');
                }
            } 
        }else if(friend == 'OLD'){
            write_msg.prop('disabled', false);
            mesAry["connType"] = friend;
            handleSendingMessages(mesAry);
        }

        $("[data-key="+Connkey+"]").find(".info").next().text(message)
        notificationConnections.map((nc) => {
            if(nc.key === Connkey) {
                nc.last_message = message
            }
        })

        socket.emit("typing-status", {typing: false, user_id: user_login_id, conn_uname: user_uname});
    }
    
    return false;
}

function ShowMessageList(messages, type = null){
    showChatHistory.empty();
    friend 		=	target_obj.attr('data-friend');
    target_id 	=	target_obj.attr('data-id');
    conn_user_uname =	target_obj.attr('data-user_uname');
    Connkey 		=	target_obj.attr('data-key');
    ConnStatus		=	target_obj.attr('data-connection-status');
    
    let html =''; 
    
    if(messages.length > 0) {
        if(ConnStatus === "blocked") {
            $("#submitMessageInut").hide();
            $(".blockUserMsg").show();
            $("#blockTextHeader").html("Unblock")
        } else {
            $(".blockUserMsg").hide();
            $("#submitMessageInut").show();
        }
        messages.map( msg => {
            if(msg.sender === user_login_id) {
                html = sendingMessage(msg, Connkey);
            } else {
                html = receiveMessage(msg, Connkey); 
            }

            if(target_obj.length){
                target_id 	=	target_obj.attr('data-id');
                let cnvrstnId 	= (msg.sender < msg.receiver)? msg.sender+msg.receiver : msg.receiver+msg.sender;
                if((target_id == msg.sender || target_id == msg.receiver)  && ( user_login_id ==  msg.sender || user_login_id ==  msg.receiver)){
                    showChatHistory.append(html);
                
                    // let nodes = document.querySelectorAll(".contentTextChat");
					// 	twemoji.parse(nodes[nodes.length- 1], {folder: '72x72',ext: '.png',});

                    !active ? userChatBox.stop().animate({scrollTop:userChatBox[0].scrollHeight},1000) : null;
                    $('#'+cnvrstnId).next('.dis_chat_msgcount').remove();
                }

                $("#message_skeleton").find(".dis_skeleton").css("display", "none");
            }

            
            chat_post_magnific();
            richLinkCode($('.contentTextChat').last());
        });
    } else $("#message_skeleton").find(".dis_skeleton").css("display", "none");
}

async function handleSendingMessages(mesAry) {
    const sender = user_login_id, receiver 	= conn_id;
    const { message, time, fileType } = mesAry;

    if (sender == user_login_id ) {
        html = sendingMessage(mesAry, Connkey);
    }else{
        html = receiveMessage(mesAry, Connkey);
    }
    
    const cnvrstnId = (sender < receiver)? sender+receiver : receiver+sender;
    if(target_obj.length){
        target_id 	=	target_obj.attr('data-id');
        showChatHistory.append(html);

        let nodes = document.querySelectorAll(".contentTextChat");
		/* twemoji.parse(nodes[nodes.length- 1], {folder: '72x72',ext: '.png',});
        userChatBox.stop().animate({scrollTop:userChatBox[0].scrollHeight},1000); */

        messages.push(mesAry)
        
        $("#"+cnvrstnId).text(LastMessageSettings(message, fileType))
        $('#'+cnvrstnId).parent('.dis_ccd_usermsg').parent().find('li.chat_date').text(unixTime(time));

        setLstMess(mesAry)


        sortMyUserList();
    }
 
    richLinkCode($('.contentTextChat').last());
    if(mesAry.sender_auth_id === "") {
        mesAry["sender_auth_id"] = parentAuthId
    }
    
    $('#SearchUser').val('')

    if(!mesAry.message.includes("blob")) socket.emit("new-message", mesAry);
    return
}

function sortMyUserList(){
    let index = $('li.chat_list.active').index();
    if(index>0){
        html = $('li.chat_list').eq(index).clone();
        $('li.chat_list').eq(index).remove();
        $('li.chat_list').removeClass('active');
        $('#search_user_list').prepend(html);
    }
}

function ShowUserLIst(result,lable,type,funcType=null){	
    var userList = '';
    let lables= ''; // let lables= '<div class="active" style="background:#dcd6d6">' + lable + '</div><span>'; 
    var count_unread_msg =0;
    userList += lables;
    $('#search_user_list').empty();
    $('.dis_skeleton').css('display', 'none');

    // console.log("result", result);
    result.forEach(function(item,index){
        let uid 		= item.user_id;
        let unm 		= item.user_name;
        let uunm 		= item.user_uname;
        
        let status 		= (item.status != 'undefined' && item.status != undefined)? item.status :'Offline';
        let msg_count 	= (item.unread_msg != 'undefined' && item.unread_msg != undefined)? item.unread_msg :0;
        let connKey 	= (item.key != 'undefined' && item.key != undefined)? item.key :'';
        let connStatus 	= (item.conn_status != 'undefined' && item.conn_status != undefined)? (item.conn_status === 1? 'accepted':(item.conn_status === 2? 'pending':'blocked')) :'accepted';
        let last_message= (item.last_message != 'undefined' && item.last_message != undefined)? LastMessageSettings(item.last_message) : '-----';
        
        let update_at 	= (item.update_at != 'undefined' && item.update_at != undefined && item.update_at !='')? unixTime(item.update_at) : '--:--';
        if (item.last_message === '') {
            update_at = '--:--';
        }
        
        if(user_login_id != uid){
            let commonId 	= 	(uid < user_login_id)? (uid+user_login_id) : (user_login_id+uid);
                commonId	= 	(lable == 'RECENT')? commonId :''; 
            let uimg		= 	getUserImage(uid,item.uc_pic|| "");
            let clas = (status == 'Online') ? 'online' : 'offline';
            
            let unread_msg ='';
            if(msg_count>0){
                count_unread_msg += msg_count;
                unread_msg = '<span class="dis_chat_msgcount">'+msg_count+'</span>';
                $('#message_count').text(count_unread_msg).css('display','block');
                
            }	         
            if(connStatus === "pending") {
                last_message = "I would like to add you on Discovered"
            }

            userList +='<li class="chat_list" data-key="'+connKey+'" data-id="'+uid+'" data-user_uname="'+uunm+'" data-user_name="'+unm+'" data-friend="'+(funcType? "NEW" : type)+'" data-user_pic="'+item.uc_pic+'" data-connection-status="'+connStatus+'" data-online-status="'+status+'" data-profile-url="'+base_url+'profile?user='+uunm+'"><div class="dis_chat_contacts_box"><div class="dis_chat_contacts_avatar '+clas+'"><img src="'+uimg+'" title="avatar" alt="'+unm+'" onerror="this.onerror=null;this.src=\''+oEimg+'\';"></div><div class="dis_chat_contacts_details"><div class="dis_ccd_usertop"><h4 class="dis_ccd_userttl mp_0">'+unm+'</h4><ul class="dis_chatTime_list"><li><svg xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="15px" viewBox="0 0 17 17"><path fill-rule="evenodd" fill="rgb(174 174 174)" d="M11.804,9.294 C12.247,9.731 12.082,10.425 11.497,10.600 C11.208,10.687 10.933,10.636 10.716,10.427 C9.900,9.641 9.089,8.849 8.279,8.056 C8.123,7.903 8.059,7.710 8.059,7.493 C8.061,6.478 8.058,5.464 8.061,4.449 C8.062,3.983 8.406,3.634 8.858,3.632 C9.297,3.631 9.651,3.974 9.660,4.429 C9.670,4.872 9.662,5.316 9.663,5.759 C9.663,6.184 9.668,6.610 9.660,7.035 C9.658,7.161 9.700,7.248 9.791,7.336 C10.465,7.985 11.138,8.637 11.804,9.294 ZM10.068,14.427 C7.137,14.927 4.127,13.562 2.652,11.065 C2.488,10.788 2.470,10.511 2.641,10.237 C2.802,9.977 3.051,9.848 3.363,9.864 C3.660,9.878 3.878,10.026 4.029,10.274 C4.492,11.035 5.106,11.654 5.873,12.131 C9.189,14.192 13.686,12.272 14.362,8.506 C14.909,5.451 12.846,2.636 9.695,2.138 C7.009,1.714 4.284,3.382 3.513,5.924 C3.503,5.957 3.496,5.991 3.478,6.065 C3.967,5.794 4.413,5.539 4.867,5.298 C5.389,5.021 5.987,5.320 6.056,5.893 C6.093,6.203 5.968,6.465 5.692,6.621 C4.738,7.162 3.780,7.697 2.815,8.219 C2.443,8.420 1.976,8.282 1.779,7.915 C1.222,6.883 0.676,5.846 0.141,4.804 C-0.053,4.426 0.126,3.983 0.508,3.796 C0.890,3.609 1.348,3.738 1.561,4.109 C1.742,4.426 1.905,4.755 2.095,5.115 C2.132,5.030 2.157,4.974 2.182,4.918 C3.281,2.473 5.167,0.980 7.888,0.599 C11.668,0.071 15.173,2.529 15.921,6.167 C16.713,10.017 14.072,13.743 10.068,14.427 Z"/></svg></li><li class="chat_date">'+update_at+'</li></ul></div><div class="dis_ccd_usermsg"><p id="'+commonId+'">'+last_message+'</p>'+unread_msg+'</div></div></div></li>';		
            
            if (index == (result.length -1)){
                if(lable == 'PEOPLE'){
                    $('#search_user_list').html(userList);
                }else{
                    $('#search_user_list').html(userList);
                    if( target_obj.length){
                        target_obj = $('li.chat_list[data-id="'+ target_obj.attr('data-id')  +'"]');
                        target_obj.addClass('active');
                    }
                }
            }		
        }
    });
    if(count_unread_msg>0){
        $("#count_unread_msg").text(count_unread_msg).removeClass('hide');
    }
}

function unixTime(message_time) {
    const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",  "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var now = new Date(),
    d = new Date( parseInt( message_time ) ), // Chat message date
    t = d.getHours() + ':' + ( d.getMinutes() < 10 ? '0' : '' ) + d.getMinutes(), // Chat message time
    msg_time =    tConvert (t)  + ' | ' + monthNames[d.getUTCMonth()] +' '+  d.getUTCDate() ;
    return msg_time;
}

function unixDate(message_time) {
    const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",  "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var now = new Date(),
    d = new Date( parseInt( message_time ) ), // Chat message date
    t = d.getHours() + ':' + ( d.getMinutes() < 10 ? '0' : '' ) + d.getMinutes(), // Chat message time
    msg_time = d.getUTCDate() +' '+ monthNames[d.getUTCMonth()] +' '+  d.getUTCFullYear() ;
    return msg_time;
}

function tConvert (time) {
    time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];
    if (time.length > 1) { // If time format correct
        time = time.slice (1);  // Remove full string match value
        time[5] = +time[0] < 12 ? ' AM' : ' PM'; // Set AM/PM
        time[0] = +time[0] % 12 || 12; // Adjust hours
    }	
    return time.join (''); // return adjusted time or original string
}

function Download(url) {
    document.getElementById('my_iframe').src = url;
};

function sendingMessage(childData,key){
    let conn_status = target_obj.attr('data-connection-status');
    let mess = childData.message , msg_type = "";
    mess = mess.replace(/(?:\r\n|\r|\n)/g, '<br>');

    let checkType = childData?.fileType ?? mess;

    let senderImg		= 	getUserImage(user_login_id,user_pic);
    
    if(conn_status === "pending") {
        $(".dis_userchat_footer").addClass('hide');

        if(!$('#show_chat_history').html()) {
            return pandingMessageHTML(user_name) 
        } else return ''
    } else if(conn_status === "accepted" || conn_status === "blocked") {
        let getMessages = checkMessageFile(mess, checkType, childData)
        if(getMessages) {
            mess = getMessages.message
            msg_type = getMessages.msg_type
        }
        
        return `<div class="dis_userchat_sender search_class">
            <div class="dis_chat_box">
                <!--div class="dis_chat_img">
                    <img src="${senderImg}" alt="image" alt="icon" class="img-responsive">
                </div-->
                <div class="dis_chat_data">
                    <div class="dis_chat_textbox ${msg_type}">
                        ${mess.includes("blob") ? `<span class="upload_blob">Uploading......</span>` : ""}
                        <p class="dis_chat_pera contentTextChat" id="${key}">${mess}</p>
                        <a href="#" class="dis_chat_pera  post_linkpreview hide" target="_blank"></a>
                    </div>
                    <ul class="dis_chat_send_nameTime">
                        <li>
                            <ul class="dis_chatTime_list">
                                <li>
                                    <svg xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="15px" viewBox="0 0 17 17"><path fill-rule="evenodd" fill="rgb(174 174 174)" d="M11.804,9.294 C12.247,9.731 12.082,10.425 11.497,10.600 C11.208,10.687 10.933,10.636 10.716,10.427 C9.900,9.641 9.089,8.849 8.279,8.056 C8.123,7.903 8.059,7.710 8.059,7.493 C8.061,6.478 8.058,5.464 8.061,4.449 C8.062,3.983 8.406,3.634 8.858,3.632 C9.297,3.631 9.651,3.974 9.660,4.429 C9.670,4.872 9.662,5.316 9.663,5.759 C9.663,6.184 9.668,6.610 9.660,7.035 C9.658,7.161 9.700,7.248 9.791,7.336 C10.465,7.985 11.138,8.637 11.804,9.294 ZM10.068,14.427 C7.137,14.927 4.127,13.562 2.652,11.065 C2.488,10.788 2.470,10.511 2.641,10.237 C2.802,9.977 3.051,9.848 3.363,9.864 C3.660,9.878 3.878,10.026 4.029,10.274 C4.492,11.035 5.106,11.654 5.873,12.131 C9.189,14.192 13.686,12.272 14.362,8.506 C14.909,5.451 12.846,2.636 9.695,2.138 C7.009,1.714 4.284,3.382 3.513,5.924 C3.503,5.957 3.496,5.991 3.478,6.065 C3.967,5.794 4.413,5.539 4.867,5.298 C5.389,5.021 5.987,5.320 6.056,5.893 C6.093,6.203 5.968,6.465 5.692,6.621 C4.738,7.162 3.780,7.697 2.815,8.219 C2.443,8.420 1.976,8.282 1.779,7.915 C1.222,6.883 0.676,5.846 0.141,4.804 C-0.053,4.426 0.126,3.983 0.508,3.796 C0.890,3.609 1.348,3.738 1.561,4.109 C1.742,4.426 1.905,4.755 2.095,5.115 C2.132,5.030 2.157,4.974 2.182,4.918 C3.281,2.473 5.167,0.980 7.888,0.599 C11.668,0.071 15.173,2.529 15.921,6.167 C16.713,10.017 14.072,13.743 10.068,14.427 Z"/></svg>
                                </li>
                                <li>${unixTime(childData.time)}</li>
                                <li>
                                    <svg xmlns:xlink="http://www.w3.org/1999/xlink" width="13px" height="9px"><path fill-rule="evenodd" fill="rgb(174 174 174)" d="M12.410,0.234 L12.409,0.234 C12.258,0.083 12.057,-0.000 11.843,-0.000 C11.630,-0.000 11.430,0.083 11.279,0.233 L4.427,7.052 L2.050,4.475 C1.905,4.318 1.709,4.227 1.495,4.218 C1.290,4.217 1.078,4.285 0.921,4.428 C0.597,4.726 0.576,5.231 0.874,5.555 L3.815,8.742 C3.962,8.901 4.170,8.995 4.403,9.000 C4.612,9.000 4.817,8.915 4.967,8.767 L12.408,1.362 C12.720,1.052 12.720,0.546 12.410,0.234 Z"/></svg>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>`;		
    }
}

function receiveMessage(childData,key){
    let conn_status = target_obj.attr('data-connection-status');
    let mess = childData.message, msg_type = "" ;
    mess = mess.replace(/(?:\r\n|\r|\n)/g, '<br>');

    if(childData.newConnection) delete childData.newConnection;
    // messages.push(childData);

    let user_name ='';
    let receiver_img='', checkType = childData?.fileType ?? mess;
    if(target_obj.length){
        user_name 	 = target_obj.attr('data-user_name');
        receiver_img = target_obj.find('img').attr('src');
    }

    if(conn_status === "pending") {
        $(".dis_userchat_footer").addClass('hide');

        if(!$('#show_chat_history').html()) {
            return pandingMessageHTML(user_name)
        } else return ''
    } else if(conn_status === "accepted" || conn_status === "blocked") {
        let getMessages = checkMessageFile(mess, checkType, childData)
        if(getMessages) {
            mess = getMessages.message
            msg_type = getMessages.msg_type
        }

        return `<div class="dis_userchat_receiver search_class">
                <div class="dis_chat_box">
                    <div class="dis_chat_img">
                        <img src="${receiver_img}" alt="image" alt="icon" class="img-responsive">
                    </div>
                    <div class="dis_chat_data">
                        <div class="dis_chat_textbox ${msg_type}">
                            <p class="dis_chat_pera contentTextChat" id="${key}">${mess}</p>
                            <a href="#" class="dis_chat_pera  post_linkpreview hide" target="_blank"></a>
                        </div>
                        <ul class="dis_chat_send_nameTime">
                            <!--li>
                                <p>${user_name}</p>
                            </li-->
                            <li>
                                <ul class="dis_chatTime_list">
                                    <li>
                                        <svg xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="15px" viewBox="0 0 17 17"><path fill-rule="evenodd" fill="rgb(174 174 174)" d="M11.804,9.294 C12.247,9.731 12.082,10.425 11.497,10.600 C11.208,10.687 10.933,10.636 10.716,10.427 C9.900,9.641 9.089,8.849 8.279,8.056 C8.123,7.903 8.059,7.710 8.059,7.493 C8.061,6.478 8.058,5.464 8.061,4.449 C8.062,3.983 8.406,3.634 8.858,3.632 C9.297,3.631 9.651,3.974 9.660,4.429 C9.670,4.872 9.662,5.316 9.663,5.759 C9.663,6.184 9.668,6.610 9.660,7.035 C9.658,7.161 9.700,7.248 9.791,7.336 C10.465,7.985 11.138,8.637 11.804,9.294 ZM10.068,14.427 C7.137,14.927 4.127,13.562 2.652,11.065 C2.488,10.788 2.470,10.511 2.641,10.237 C2.802,9.977 3.051,9.848 3.363,9.864 C3.660,9.878 3.878,10.026 4.029,10.274 C4.492,11.035 5.106,11.654 5.873,12.131 C9.189,14.192 13.686,12.272 14.362,8.506 C14.909,5.451 12.846,2.636 9.695,2.138 C7.009,1.714 4.284,3.382 3.513,5.924 C3.503,5.957 3.496,5.991 3.478,6.065 C3.967,5.794 4.413,5.539 4.867,5.298 C5.389,5.021 5.987,5.320 6.056,5.893 C6.093,6.203 5.968,6.465 5.692,6.621 C4.738,7.162 3.780,7.697 2.815,8.219 C2.443,8.420 1.976,8.282 1.779,7.915 C1.222,6.883 0.676,5.846 0.141,4.804 C-0.053,4.426 0.126,3.983 0.508,3.796 C0.890,3.609 1.348,3.738 1.561,4.109 C1.742,4.426 1.905,4.755 2.095,5.115 C2.132,5.030 2.157,4.974 2.182,4.918 C3.281,2.473 5.167,0.980 7.888,0.599 C11.668,0.071 15.173,2.529 15.921,6.167 C16.713,10.017 14.072,13.743 10.068,14.427 Z"/></svg>
                                    </li>
                                    <li>${unixTime(childData.time)}</li>
                                </ul>
                            </li>
                        </ul>
    
                    </div>
                </div>
            </div>`;		
                
    }

}

function checkMessageFile(message, checkType, childData) {
    let msg_type= "";
    if(message.includes("cloudfront") || message.includes("blob") || message.includes("s3")) {
        if(checkType.includes("png") || checkType.includes("jpg") || checkType.includes("jpeg")){
            msg_type = "image";
            message = `<a href="${message}" class="chat_img">
                <img src="${message}" height="200" width="250">
            </a>`
        } else if(checkType.includes("mp4")) {
            msg_type = "video";
            message = "<a href='"+message+"' class='chat_video'><video class='view' src='"+message+"' height='200' width='250'></video></a>"
        } else {
            let link = message;
            message = `<div class="dis_chat_zipWrap">
                <div class="dis_chat_zipR">
                    <h1 class="dis_chat_Zipname mp_0">${link.split("chat/")[1]}</h1>
                    <p class="dis_chat_ZipSize mp_0">${childData.fileSize ?? "1 MB"}</p>
                </div>
                <span class="dis_chat_zipL">
                    <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="512" height="512" viewBox="0 0 512 512"><g><g><g><path d="M382.56,233.376C379.968,227.648,374.272,224,368,224h-64V16c0-8.832-7.168-16-16-16h-64c-8.832,0-16,7.168-16,16v208h-64c-6.272,0-11.968,3.68-14.56,9.376c-2.624,5.728-1.6,12.416,2.528,17.152l112,128c3.04,3.488,7.424,5.472,12.032,5.472c4.608,0,8.992-2.016,12.032-5.472l112-128C384.192,245.824,385.152,239.104,382.56,233.376z" fill="#000000" data-original="#000000" class=""></path></g></g><g><g><path d="M432,352v96H80v-96H16v128c0,17.696,14.336,32,32,32h416c17.696,0,32-14.304,32-32V352H432z" fill="#000000" data-original="#000000" class=""></path></g></g></svg>
                </span>
            </div>`
        }
    }

    return { message, msg_type}
}

function LastMessageSettings(last_message, type = null) {
    let checkType = type ?? last_message;

    if(last_message.includes("cloudfront") || last_message.includes("s3") || last_message.includes("blob") ){
        if(checkType.includes("png") || checkType.includes("jpg") || checkType.includes("jpeg")){
            return `<i class="fa fa-photo " aria-hidden="true"></i> Photo`
        } else  if(checkType.includes("mp4")) {
            return `<i class="fa fa-video-camera" aria-hidden="true"></i> Video`
        } else {
            return `<i class="fa fa-folder" aria-hidden="true"></i> File`
        }
    } else {
        return last_message.slice(0,30)
    }
}

function pandingMessageHTML(user_name) {
    return `<div class="dis_userchat_connetBox">
        <span class="dis_UC_cImage">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 118 87.1" width="140px" height="103.33px"><g><path class="welcome1" d="M43,1.7c-0.2-0.4-0.8-0.5-1.2-0.3l-2.3,1.3l-1.3-2.3C38,0,37.4-0.1,37,0.1c-0.4,0.2-0.5,0.8-0.3,1.2L38,3.5l-2.3,1.3c0,0,0,0,0,0c-0.4,0.2-0.5,0.8-0.3,1.2c0.2,0.4,0.8,0.5,1.2,0.3L38.9,5l0,0l0,0l1.3,2.3c0.2,0.4,0.8,0.5,1.2,0.3c0.4-0.2,0.5-0.8,0.3-1.2l-1.2-2l0,0l-0.1-0.2l2.3-1.3C43,2.6,43.2,2.1,43,1.7z"></path><path class="welcome1" d="M102.7,3.8c-2.9-0.7-5.7,1.1-6.4,3.9c-0.7,2.9,1.1,5.7,3.9,6.4c2.9,0.7,5.7-1.1,6.4-3.9C107.3,7.4,105.6,4.5,102.7,3.8z M105,9.8c-0.5,1.9-2.4,3.1-4.4,2.7c-1.9-0.5-3.1-2.4-2.7-4.4c0.5-1.9,2.4-3.1,4.4-2.7C104.2,5.9,105.4,7.9,105,9.8z"></path><path class="welcome1" d="M100.8,38.1L85.8,26.5L81,23.2c-0.6-3.7-2.3-7.2-4.9-9.9c-3.3-3.3-7.8-5.2-12.4-5.2c-0.5,0-1,0-1.6,0.1c-8.8,0-15.9,7.8-16,17.5L43.5,26l-1.2,0.4l-16,11.7c-0.6,0.5-1,1.2-1.3,1.9c-0.3,0.7-0.4,1.4-0.4,2.2v38.5c0,1.6,0.7,3.1,1.8,4.1c1,0.9,2.2,1.4,3.5,1.4h67.2c1.3,0,2.6-0.5,3.5-1.4c1.2-1,1.8-2.5,1.8-4.1V42.1c0-1.1-0.3-2.2-1-3.2C101.4,38.6,101.1,38.3,100.8,38.1z M69.1,26.9l-6.5,6.7l-6.5-6.7c-1.4-1.5-1.4-3.9,0-5.4c1.4-1.4,3.6-1.5,5-0.1c0,0,0.1,0.1,0.1,0.1l0.5,0.5c0.5,0.5,1.3,0.5,1.8,0l0.5-0.5c1.4-1.4,3.6-1.5,5-0.1c0,0,0.1,0.1,0.1,0.1C70.5,23,70.5,25.3,69.1,26.9z"></path><path class="welcome1" d="M25.9,25.8c0.8-1.8,0-3.8-1.8-4.6c-1.8-0.8-3.8,0-4.6,1.8c-0.4,0.8-0.4,1.8,0,2.7c0.1,0.3,0.2,0.5,0.4,0.7c1.1,1.6,3.3,1.9,4.8,0.8C25.2,26.8,25.6,26.3,25.9,25.8z M23.6,26.6L23.6,26.6c-1.2,0.5-2.6-0.1-3.1-1.3c-0.5-1.2,0.1-2.6,1.3-3.1c1.2-0.5,2.6,0.1,3.1,1.3c0.2,0.6,0.2,1.2,0,1.8C24.6,25.9,24.1,26.3,23.6,26.6z"></path></g><path class="welcome2" d="M23.3,70.7L23.3,70.7c0.1,0.1,0.2,0.1,0.3,0.2c0.1,0,0.2,0.1,0.3,0.1c0.1,0,0.2,0,0.3,0c0.4,0,0.8-0.2,1.1-0.5c0.6-0.7,0.6-1.7,0-2.3c-0.1-0.1-0.3-0.3-0.5-0.3c-0.2-0.1-0.4-0.1-0.6-0.1c-0.4,0-0.8,0.2-1.1,0.5c-0.3,0.3-0.5,0.7-0.5,1.1v0c0,0.4,0.2,0.8,0.5,1.1L23.3,70.7z M100.3,36.9L81.2,23.2C79.7,13,70.2,5.8,60,7.3c-8.4,1.2-15,7.9-16,16.3L24.9,36.9l0,0c-1.4,1.3-2.2,3.1-2.3,4.9h0v22.1c-0.1,0.9,0.6,1.6,1.5,1.7c0.9,0.1,1.6-0.6,1.7-1.5c0-0.1,0-0.2,0-0.2V42.8l27.5,19.3l-27,19.9c-0.3-0.6-0.5-1.2-0.5-1.9v-5.4c0-0.9-0.7-1.6-1.6-1.6l0,0c-0.9,0-1.6,0.7-1.6,1.6v5.7h0c0.1,1.9,0.9,3.6,2.3,4.9c0.1,0.1,0.1,0.1,0.2,0.2c1.2,1.1,2.8,1.6,4.4,1.6h66.2c1.6,0,3.2-0.6,4.4-1.6c0.1-0.1,0.1-0.1,0.2-0.2c1.4-1.3,2.3-3.2,2.3-5.2v-38C102.6,40.1,101.8,38.2,100.3,36.9z M62.6,10.1c8.9-0.1,16.3,7,16.4,15.9s-7,16.3-15.9,16.4s-16.3-7-16.4-15.9c0-0.1,0-0.2,0-0.3C46.7,17.4,53.8,10.1,62.6,10.1z M26.8,39.5c0.1-0.1,0.1-0.1,0.2-0.2l16.9-11.5c0.7,10.3,9.7,18.2,20,17.4c9.4-0.6,16.9-8.2,17.5-17.6l16.2,11.3L64.7,63.1c-1.3,0.9-3,1-4.3,0.1l-3.3-2.3c-0.1-0.1-0.2-0.1-0.3-0.2L26.8,39.5z M95.7,83.8H29.5c-0.1,0-0.2,0-0.3,0L56.1,64l2.5,1.8c1.1,0.8,2.5,1.2,3.9,1.2h0c1.5,0,2.9-0.5,4-1.3l2.4-1.8l27.1,19.9C96,83.8,95.8,83.8,95.7,83.8z M99.4,80.1c0,0.6-0.2,1.3-0.5,1.9l-27.2-20l27.7-20.4c0,0.2,0,0.4,0,0.6L99.4,80.1L99.4,80.1zM61.7,36.2c0.5,0.5,1.2,0.5,1.7,0c0,0,0,0,0,0l7.4-7.6c2.4-2.5,2.4-6.4,0-8.9c-2.2-2.3-5.8-2.5-8.2-0.4c-2.4-2.1-6-1.9-8.2,0.4c-2.4,2.5-2.4,6.4,0,8.9L61.7,36.2z M56.1,21.5c1.4-1.4,3.6-1.5,5-0.1c0,0,0.1,0.1,0.1,0.1l0.5,0.5c0.5,0.5,1.3,0.5,1.8,0l0.5-0.5c1.4-1.4,3.6-1.5,5-0.1c0,0,0.1,0.1,0.1,0.1c1.4,1.5,1.4,3.9,0,5.4l-6.5,6.7l-6.5-6.7C54.7,25.3,54.7,23,56.1,21.5L56.1,21.5z"></path><path class="welcome1" d="M5.9,53.4c-0.4-0.2-0.9,0-1.1,0.4l-1.1,2.4L1.2,55c-0.4-0.2-0.9,0-1.1,0.4c-0.2,0.4,0,0.9,0.4,1.1l2.4,1.1l-1.1,2.4c0,0,0,0,0,0c-0.2,0.4,0,0.9,0.4,1.1c0.4,0.2,0.9,0,1.1-0.4l1.1-2.4l0,0l0,0l2.4,1.1c0.4,0.2,0.9,0,1.1-0.4c0.2-0.4,0-0.9-0.4-1.1l-2.1-1l0,0l-0.2-0.1l1.1-2.4C6.5,54.1,6.3,53.6,5.9,53.4z"></path><path class="welcome1" d="M118,44.1c0.1-0.5-0.2-0.9-0.6-1l-2.5-0.6l0.6-2.5c0.1-0.5-0.2-0.9-0.6-1c-0.5-0.1-0.9,0.2-1,0.6l-0.6,2.5l-2.6-0.6c0,0,0,0,0,0c-0.5-0.1-0.9,0.2-1,0.6c-0.1,0.5,0.2,0.9,0.6,1l2.6,0.6l0,0l0,0l-0.6,2.5c-0.1,0.5,0.2,0.9,0.6,1c0.5,0.1,0.9-0.2,1-0.6l0.6-2.3l0,0l0.1-0.3l2.5,0.6C117.4,44.9,117.9,44.6,118,44.1z"></path></svg>
        </span>
        <h2>${user_name} wants to connect with you</h2>
        <ul class="dis_userchat_connetbtn">
            <li>
                <a href="#" class="dis_chatbtn red">Block</a>
            </li>
            <li>
                <a href="#" class="dis_chatbtn green">Accept</a>
            </li>
        </ul>
    </div>`
}

function getUserImage(uid,upc){
    if(upc){
        let image 	= 	new Image();
            upc 	= 	upc.split('.');
            upc		= 	upc[0] + '_thumb.'+ upc[1];	
            upc		=  	AMAZON_URL+'aud_'+uid+'/images/'+upc;
            image.src = upc;
            return upc;
    }else{
        return  oEimg;
    }
}

async function getUserConnection() {
    let userConnection = await handleRequest("POST", node_url+"get-connected-users/"+user_login_id, {});
    if(userConnection.status === "success") {
        if(userConnection.data) {
            parentAuthId = userConnection.data._id
            connectionList = userConnection.data.conn_key;
            let pic = userConnection.data.user_pic === "" ? oEimg : userConnection.data.user_pic
            $("#sidebar_user_profile_pic").attr("src", pic);
            $("#sidebar_user_name").html(userConnection.data.user_name);

            if(!connectionList.length) {
                $('.dis_skeleton').css('display', 'none');
                $('#search_user_list').html('<li style="text-align: center;">No connections found.</li>')
            } else {
                ShowUserLIst(connectionList,'RECENT','OLD');
            }
        } else {
            $('.dis_skeleton').css('display', 'none');
            $('#search_user_list').html('<li style="text-align: center;">No connections found.</li>')
        }
    } 

}

async function handleRequest(method, url, data) {
    return new Promise((resolve, reject) => {
        $.ajax({
            type: method,
            url,
            data,
            success: function (response) {
                resolve(response);
            }
        });
    })
}

function checkUserTypingStatus(snapshot){
    if($("[data-user_uname="+snapshot.conn_uname+"]").hasClass('active')){
        $('#typingStatus').remove();
        if(snapshot.typing){
            let typingIcon =`<div class="dis_userchat_receiver" id="typingStatus">
                <div class="dis_chat_box">
                    <div class="dis_chat_data">
                        <div class="dis_chat_textbox">
                            <div class="dis_chat_typing">
                                <span class="dot"></span>
                                <span class="dot"></span>
                                <span class="dot"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
            showChatHistory.append(typingIcon);
            userChatBox.stop().animate({scrollTop:userChatBox[0].scrollHeight},1000);
        }else{
            $('#typingStatus').remove();
        }
    }
}

function searchChatMsg(query){
    if(query.length>0){  
    
        let searchTerms = query.toString().toLowerCase();
        $("#show_chat_history div.search_class").filter(function() {
            $(this).toggle($(this).find('.contentTextChat').text().toLowerCase().indexOf(searchTerms) > -1);
        });
    }else{
        // ShowMessageList(messages);
    } 
}

function chat_post_magnific() {
    if($('.chat_img').length > 0){
        $('.chat_img').magnificPopup({
            type: 'image',
            mainClass: 'mfp-with-zoom', 			  
            gallery: {
                enabled: true
            },
            zoom: {
                enabled: true, 
                duration: 300, 
                easing: 'ease-in-out', 
            }
        });
    }
    if($('.chat_video').length > 0){
        $('.chat_video').magnificPopup({
            type: 'iframe',
            mainClass: 'mfp-with-zoom', 			  
            gallery: {
                enabled: true
            },
            zoom: {
                enabled: true, 
                duration: 300, 
                easing: 'ease-in-out', 
            }
        });
    }
}

// convert bytes to MB or KB etc.
async function formatBytes(_size) {
    return new Promise((resolve, reject)=> {
        var fSExt = new Array('Bytes', 'KB', 'MB'),
        i=0;while(_size>900){_size/=1024;i++;}
        var exactSize = (Math.round(_size*100)/100)+' '+fSExt[i];
        resolve(exactSize)
    });
}

window.onbeforeunload = function (event) {
    let pathname = window.location.pathname
    if(pathname === "/socket_messenger") {
        var message = 'Important: Are you sure you want to leave this page.';
        if (typeof event == 'undefined') {
            event = window.event;
        }
        if (event) {
            socket.emit("user-status", {status:"Offline", user_id: user_login_id})
            event.returnValue = message;
        }
        return message;
    }
};

function setLstMess(childData){
    let sender 		= childData.sender;
    let receiver 	= childData.receiver;
    cnvrstnId 	= (sender < receiver)? sender+receiver : receiver+sender;
    if($('#'+cnvrstnId).length && ( user_login_id ==  sender || user_login_id ==  receiver) ){
        $('#'+cnvrstnId).text((childData.message).slice(0,30));
        $('#'+cnvrstnId).parent('.dis_ccd_usermsg').parent().find('li.chat_date').text(unixTime(childData.time));
    }
}

/* --------------------- Message notification function start ----------------------- */



var load = '';
$(document).ready(function () {
    // To disable:    
    $('#loading_title').css('cursor', 'progress');
    if(document.getElementById('msg_icon')) {
        document.getElementById('msg_icon').style.pointerEvents = 'none';
        // To re-enable:
        setTimeout(() => {
            document.getElementById('msg_icon').style.pointerEvents = 'auto';
            $('#loading_title').attr('title', '');
            $('#loading_title').css('cursor', 'pointer');
            $('#msg_icon').attr('title', 'Messages');
    
            load = true;
        }, 4000);
    }
});

async function load_popup() { 
    
    var query_status = 0;
    
    if(connectionList.length) {
        notificationConnections = connectionList.slice(0,5)
    } else {
        await getUserConnection();
        notificationConnections = connectionList.slice(0,5)
    }

    if(notificationConnections.length) {
        let status = load_msg_notifications(notificationConnections, query_status);
    
        if (status === 0) {	
            $('#show_message').html(`<center>No Message Discovered </center>`);
        } 
    }
}

async function load_msg_notifications(msg_arr, query_status) {
    $('#show_message').html('');
    await Promise.all(
        msg_arr.map(childData => {
            var sender = '';
            let time = childData.last_message;
            if (time === '' || time === undefined) {
                time = "--:--";
            }else{
                time            = unixTime(childData.update_at);
                let uimg        = getUserImage(childData.user_id,childData.user_pic);
                let connStatus 	= (childData.conn_status != 'undefined' && childData.conn_status != undefined) ? (childData.conn_status === 1? 'accepted' : (childData.conn_status === 2? 'pending':'blocked')) :'accepted';
                
                let last_message = childData.last_message != 'undefined' && childData.last_message != undefined ? LastMessageSettings(childData.last_message) : '-----';

                let html = `<div class="noti_wrapper message_open" data-p_pic="${uimg}" data-friend="OLD" data-id="${childData.user_id}" data-user_uname="${childData.user_uname}" data-key="${childData.key}" data-full_name="${childData.user_name}" data-connection-status="${connStatus}">
                    <div class="left">
                        <a class="noti_img">
                            <img id="profile_image" src="${uimg}" title="${childData.user_uname}" class="img-responsive" alt="" onerror="this.onerror=null;this.src='${oEimg}'">
                        </a>
                        <div class="content">
                        <a class="info"> ${childData.user_name}</a>
                            <span>${sender} ${last_message}</span>
                        </div>
                    </div>
                    <p>${time}</p>
                </div>`
    
                $('#show_message').append(html);
            }
            query_status += 1;
        })
    );
    return query_status;
}


var is_chat_page = $('#firebase_chat_page').val();

var msg_open = false;
if (is_chat_page !== 'true') {
    $(document).on('click','.message_open',  function () {
        let _this = $(this);
        if(msg_open){
			message_open()
		}else{
			loadScript(CDN_BASE_URL+TWEMOJI_JS,function(){
				message_open();
				msg_open = true;
			}); 
		}
        async function message_open(){
            let user_id =  _this.attr('data-id');
            let user_uname =  _this.attr('data-user_uname');
            let user_name =  _this.attr('data-full_name');
            let user_pic =  _this.attr('data-p_pic');
            let user_data_id =  _this.attr('data-key');
            let status = _this.attr('data-online-status');
            let connStatus = _this.attr('data-connection-status');
    
            $('#single_chat_messages').html('');
            $('.write_msg').val('');
            $('#popup_chat').show();
            
            $("#single_chat_title")
            .text(_this.data('full_name'))
            .attr('href', goto_profile+_this.attr('data-user_uname'));
    
            $('#profile_img_chat_section').attr('src',_this.find('img').attr('src'));
            
            $('#data_for_msging')
            .attr('data-friend', _this.attr('data-friend'))
            .attr('data-id', user_id)
            .attr('data-user_uname', user_uname)
            .attr('data-key', user_data_id)
            .attr('data-full_name', user_name)
            .attr('data-connection-status', connStatus);
    
            target_obj = _this;
            showChatHistory = $('#single_chat_messages');
            userChatBox = $('#single_chat_messages');
    
            let postData = { user_id,user_uname,user_name,user_pic,status,user_login_id,user_data_id,parentAuthId,userSelect:"chatList" }
            let userDetails = await handleRequest("POST", node_url+'users/'+user_id, postData);
            if(userDetails.status === "success") {
                messages = userDetails.messages;
                setTimeout(() => {
                    ShowMessageList(messages, "rightpopup");	
                }, 1000);
            }
        }
       
    });
}


/* --------------------- Message notification function end ----------------------- */

