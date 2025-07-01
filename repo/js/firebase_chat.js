loadScript(CDN_BASE_URL + TWEMOJI_JS, function () {
	// twemoji = true;
});
var d 		= new Date();
var oEimg 	= base_url+'repo/images/banner_logo1.png';
var popup_loader  	= base_url+'repo/images/loader.gif';
var goto_profile  	= base_url+'profile?user=';
var cnvrstnId ,friend ,target_obj = '';
var showChatHistory = $('#show_chat_history');
var separatorDate1='';
	
let mess = 'https://www.gstatic.com/firebasejs/7.15.0/firebase-messaging.js';
let app = 'https://www.gstatic.com/firebasejs/7.15.0/firebase-app.js';

loadScript(app, function(){ 
	loadScript(mess,function(){
		loadScript('https://www.gstatic.com/firebasejs/8.1.1/firebase-auth.js',function(){
			loadScript('https://www.gstatic.com/firebasejs/8.1.1/firebase-database.js',function(){
				InitFirebase();
			}); 
		}); 
	}); 
});

var InitFirebase = function(){
	var firebaseConfig = {
		apiKey: "AIzaSyC0ScbuvzFuo6z6DVBZBmdtPcVvOGpY-fc",
		authDomain: "discovered-tv-2763e.firebaseapp.com",
		databaseURL: "https://discovered-tv-2763e.firebaseio.com",
		projectId: "discovered-tv-2763e",
		storageBucket: "discovered-tv-2763e.appspot.com",
		messagingSenderId: "519992397823",
		appId: "1:519992397823:web:fc03331b58cff242f121ca",
		measurementId: "G-TEQ8QTRE4E"
	};
	/* var firebaseConfig = {   												// from ajaydeep gmail account
		apiKey: "AIzaSyANQwPgSUcWez8b9lJ39k0HrzLLZ3cjsXc",
		authDomain: "discoveredchat-2d517.firebaseapp.com",
		databaseURL: "https://discoveredchat-2d517.firebaseio.com",
		projectId: "discoveredchat-2d517",
		storageBucket: "discoveredchat-2d517.appspot.com",
		messagingSenderId: "715217759152",
		appId: "1:715217759152:web:56a6ebbe330b7847c00475",
		measurementId: "G-5X9BGHP60X"
	}; */
	firebase.initializeApp(firebaseConfig);
	
	if($('#firebase_chat_page').length){
		firebaseChatInit();	
	}
}


function firebaseChatInit(){
	var Connkey='';
	if(target_obj.length){
		 Connkey 		=	target_obj.attr('data-key');
	}
	Database = 	firebase.database();
	var email = password =  user_email;
	
	firebase.auth().createUserWithEmailAndPassword(email, password).catch(function(error) {
		var errorCode = error.code;
		var errorMessage = error.message;
		if (errorCode == 'auth/weak-password') {
			console.log('The password is too weak.');
		} else {
			console.log(errorMessage);
		}
		console.log(error);
	});
	
	if (firebase.auth().currentUser) {
		firebase.auth().signOut();
	}else{
		firebase.auth().signInWithEmailAndPassword(email, password).catch(function(error) {
			var errorCode = error.code;
			var errorMessage = error.message;
			if (errorCode === 'auth/wrong-password') {
				console.log('Wrong password.');
			} else {
				console.log('Auth:'+errorMessage);
			}
			console.log(error);
		});
	}
	// firebase.auth().signOut();
	firebase.auth().onAuthStateChanged(function(user) {
		if (user) {
			console.log(user);
			var displayName = user.displayName;
			var email = user.email;
			var emailVerified = user.emailVerified;
			var photoURL = user.photoURL;
			var isAnonymous = user.isAnonymous;
			var uid = sender_auth_id = user.uid;
			var providerData = user.providerData;
	
		} else {
			console.log('User is signed out.');
		}
	});
	
	var Me = Database.ref("users/"+user_uname);
		
		Me.update({
		   "user_pic"	: user_pic,
		   "user_name"	: user_name,
		   "user_id"	: user_login_id,
		   "status"		: "Online",
		   "last_seen" 	: firebase.database.ServerValue.TIMESTAMP
		});
		Me.onDisconnect().update({
		   "user_pic"	: user_pic,
		   "user_name"	: user_name,
		   "user_id"	: user_login_id,
		   "status"		: "Offline",
		   "last_seen" 	: firebase.database.ServerValue.TIMESTAMP
		}).then(function(snapshot) { 
			updateTypingStatus(false); 
		});
	
	var connectedRef = Database.ref(".info/connected");
		connectedRef.on("value", function(snap) {
			if (snap.val() === true) {
				console.log("connected");
				$('#MyActiveStatus').addClass('active_user');
			} else {
				console.log("not connected");
				$('#MyActiveStatus').removeClass('active_user');
			}
		});
	
	var child_changed = true;	
	var MessageRef = Database.ref('messages');
		MessageRef.on('child_changed', function(snapshot) {
			if(child_changed){
				child_changed = false; 
				ShowMessage(snapshot.key);
			}
		});		
		MessageRef.on('child_added', function(snapshot) {
			if(child_changed){
				child_changed = false; 
				ShowMessage(snapshot.key);
				console.log('msg child added');
			}
		});
	
	var UsersRef = Database.ref('users');
		UsersRef.on('child_changed', function(snapshot){
			let KEY = $('li.chat_list[data-user_uname="'+ snapshot.key  +'"]');
			if(snapshot.val() && snapshot.val().status != 'undefined' && snapshot.val().status != undefined && KEY.length){
				console.log("user active state changed");
				let clas = (snapshot.val().status == 'Online') ? 'online' : 'offline';
				KEY.find('.dis_chat_contacts_avatar').removeClass('online offline').addClass(clas);
				KEY.attr('data-online-status',snapshot.val().status);
				if(KEY.hasClass('active')){
					updateActiveStatus(snapshot.val().status);
				}
			}
		});
	var UsersConnRef = Database.ref('users_conn/'+user_uname);
		UsersConnRef.on('child_changed', function(snapshot){
			checkUserTypingStatus(snapshot.val());
			$('#message_count').css('display','block');
			$('#message_count').text(snapshot.val().unrd_msg_cnt);
			// $('#dis_chat_msgcount').text(snapshot.val().unrd_msg_cnt);
			loadMyUserList();

			var active_user_name = $('.chat_list.active').attr('data-user_uname');
			var sidebar_name = $('#sidebar_user_uname').val();
			
			// $('div.chat_list.active').find('span').css('display','none');
			if (sidebar_name === active_user_name) {
				$('div.chat_list.active').find('#dis_chat_msgcount').css('display','none');
				// $('#dis_chat_msgcount').hide();
				console.log($('.chat_list.active').html());
			}

		});
		
		UsersConnRef.on('child_added', function(snapshot){
			console.log('UsersConnRef');
			loadMyUserList();
		});
		
	function ShowMessage(key){
		let html ='';
	if (is_chat_page !== 'true') { target_obj = $('#data_for_msging'); showChatHistory = $('#single_chat_messages'); }
		return Database.ref("messages/"+key).limitToLast(1).once("value", function (snapshot) {
			snapshot.forEach(function(childSnapshot) {
				let childKey = childSnapshot.key;
				let childData = childSnapshot.val();
				if(childData && childData.sender != 'undefined' && childData.sender != undefined &&  $('#'+childKey).length == 0){
					let sender 		= childData.sender;
					let receiver 	= childData.receiver;
					
					if (sender == user_login_id ) {
						html = sendingMessage(childData,childKey);
					}else{
						html = receiveMessage(childData,childKey);
					}
					
					if(target_obj.length){
						target_id 	=	target_obj.attr('data-id');
						if((target_id == sender || target_id == receiver)  && ( user_login_id ==  sender || user_login_id ==  receiver)){
							showChatHistory.append(html);
							showChatHistory.stop().animate({scrollTop:showChatHistory[0].scrollHeight},1000);
						}
					}
					richLinkCode($('.contentTextChat').last());
					//$('#search_user_list').empty();
					setLstMess(childData);
				}
			});
			emojiInit();
			setTimeout(function(){child_changed=true;},500);
		});
	}
	
	loadMyUserList();

}

$(document).ready(function(){

$(document).on('keyup','#SearchUser',function(e){
	if (e.keyCode == 13) { e.preventDefault(); return false; }
	let _this = $(this);
	let search = (_this.val()).trim();
	if(search.length){
		$('#SearchUserClose').removeClass('hide');
		let url = base_url+'chat/search_user';
		$.ajax({
				type	:'POST',
				dataType:'JSON',
				url		: url,
				data	: {'search':search},
				success	: function(result){
					ShowUserLIst(result,'PEOPLE','NEW')
					if (is_chat_page !== 'true') { ShowUserListOnPopup(result,'PEOPLE','NEW'); }
					
				}
		});
	}else{

		$('#search_user_list').empty();
		$('#SearchUserClose').addClass('hide');
		loadMyUserList();

		if (is_chat_page !== 'true') { load_popup(); }
	}
});

$(document).ready(function () {
	if (is_chat_page === 'true') { $('.dis_chat_Righttsidebar').hide(); }
});

$(document).on('click','.chat_list:not(.active)',function(){
	startAtId= ''
	separatorDate1='';
	$('.write_msg').val('');
	$('.dis_userchat_inner').removeClass('hide');
	$('#chat_welcome_msg').addClass('hide');
	target_obj = $(this);
	let user_uname	=	target_obj.attr('data-user_uname');
	let user_name 	=	target_obj.attr('data-user_name');
	let user_id 	=	target_obj.attr('data-id');
	let user_pic 	=	target_obj.attr('data-user_pic');
	let status 		=	target_obj.attr('data-online-status');
	let profile_url =	target_obj.attr('data-profile-url');
	
	CreateUser(user_id,user_uname,user_name,user_pic).then(function(){
		$('.chat_list').removeClass('active');  
		target_obj.addClass('active');
		ShowMessageList();
		$('.type_msg').show();
	})
	
	checkblockedUser();

	$.ajax({
		type: "POST",
		url: base_url+'dashboard/Get_user_desc',
		data: {'user_uname':user_uname},
		success: function (response) {
			var data = JSON.parse(response);
			$('#sidebar_user_description').html(data.info.uc_interest+'<br>'+data.info.uc_city+','+data.info.name+'<br>,'+data.info.country_name);
		}
	});

	// for sidebar user profile
	$('#sidebar_user_uname').val(user_uname);
	$('#sidebar_user_profile_pic').attr('src', target_obj.find('img').attr('src'));
	$('#sidebar_user_name').text(user_name);
	$('#sidebar_user_profile_redirect').attr('href',profile_url);
	$('#sidebar_user_channel_redirect').attr('href', base_url+'channel?user='+user_uname);

	$('#active_user_pic').attr('src',target_obj.find('img').attr('src'));
	$('#active_user_name').text(user_name).attr('href',profile_url);
	
	if($('.dis_chat_search').hasClass('open')){
		$('.dis_chat_search').removeClass('open');
		$('#searchChatMsg').val('');
	}
	$('.dis_chat_Righttsidebar').show();
	updateActiveStatus(status);
	onChangeBlockedUser();
	
})


$(document).on('keyup','.write_msg',function(e){
	updateTypingStatus(true);
	var thiss = $(this);
	if(event.keyCode == 13 && event.shiftKey){
		let content = this.value;
		let caret = getCaret(this);
		thiss.value = content.substring(0,caret)+"\n"+content.substring(caret,content.length-1);
	}else if((e.keyCode || e.which) == 13){
		console.log(thiss.parents('form'));
		thiss.parents('form').submit();
		return false;
	}
})

$(document).on('click','#block_unblock_btn',function(e){
	let blockStatus   =  $(this).attr('data-conn-status');
	if(blockStatus==0){
		$(this).attr('data-conn-status',1);
		$(this).find('.custom_dd_text').text('Unblock');
	}else{
		$(this).attr('data-conn-status',0);
		$(this).find('.custom_dd_text').text('Block');
	} 
	//conn_id 	=	target_obj.attr('data-id');
	//cnvrstnId 	= 	(conn_id < user_login_id)? conn_id+user_login_id : user_login_id+conn_id;
	cnvrstnId 	= 	user_login_id+conn_id;
	Database.ref("users_blocked/"+cnvrstnId).set({blocked_status:blockStatus});
	//checkblockedUser();
	checkblockedUserBtn();
})


/* $(document).on('click','#block_unblock_btn',function(e){
	let connStatus   =  $(this).attr('data-conn-status');
	if(connStatus==0){
		$(this).attr('data-conn-status',1);
		$(this).text('Unblock');
	}else{
		$(this).attr('data-conn-status',0);
		$(this).text('Block');
	}
	Connkey 		=	target_obj.attr('data-key');
	let updates 	= {};
	updates[ '/'+user_uname+'/'+ Connkey + '/conn_status'] = connStatus;
	Database.ref('users_conn').update(updates);
}) */

$(document).on('click','#send_mg_btn',function(e){
	var is_chat_page = $('#firebase_chat_page').val();
	if (is_chat_page !== 'true') { 
		target_obj = $('#data_for_msging'); showChatHistory = $('#single_chat_messages'); 
	}
	$('.sendMessage').submit(); 
	
})

$(document).on('submit','.sendMessage',function(e){
	e.preventDefault();
	var is_chat_page = $('#firebase_chat_page').val();
	if (is_chat_page !== 'true') { target_obj = $('#data_for_msging'); showChatHistory = $('#single_chat_messages'); }
	sendMessage();
})

$(document).on('keydown','#searchChatMsg ,#SearchUser',function(e){
	if (e.keyCode == 13) { e.preventDefault(); return false; }
})

$(document).on('keyup','#searchChatMsg',function(e){
	let queryTerms = $.trim($(this).val());
	searchChatMsg(queryTerms);
	//ShowMessageList(queryTerms);
})

$(document).on('click','.dis_userchat_iconsBox_search',function(e){
	setTimeout(function(){
		$('#searchChatMsg').focus();
	}, 500);
})


$(document).on('click','.dis_chat_pp_search_close',function(e){
	if($(this).parents('.dis_chat_search').find("input[type=text]").attr('id')=='SearchUser'){
		$('#SearchUser').val('').trigger('keyup');
	}else{
		$('#searchChatMsg').val('').trigger('keyup');
	}
})

$(document).on('focusout','.write_msg',function(e){
	updateTypingStatus(false);
})

$(document).on("click", "#clear_chat_btn", function () {
	var _this = $(this);
	confirm_popup_function(
		"Clear",
		"Are you sure you want to clear messages in this chat?",
		"clearChatMessages()"
	);
});

$(document).on("click", "#delete_chat_user", function () {
	var _this = $(this);
	confirm_popup_function(
		"Delete",
		"Are you sure you want to delete this user",
		"deleteUserConnection()"
	);
});


});

function searchChatMsg(query){
	if(query.length>0){  
	
		let searchTerms = query.toString().toLowerCase();
		$("#show_chat_history div.search_class").filter(function() {
			$(this).toggle($(this).find('.contentTextChat').text().toLowerCase().indexOf(searchTerms) > -1);
			//var repl = '<span class="marker" style="background-color:yellow">' + query + '</span>';
			//element.html(element.html().replace(word, repl));
			//let _thisText = $(this).find('.contentTextChat');
			//_thisText.html(_thisText.text().replace(query, repl));
		});
		/* 
		$("#show_chat_history > div").each(function() 
		{
		  if ($(this).text().toLowerCase().search(searchTerms) > -1){
				$(this).show();
		  }
		  else {
				$(this).hide();
		  }
		}); */
	}else{
		ShowMessageList();
	} 
}

function onChangeBlockedUser(){
	conn_id 	=	target_obj.attr('data-id');
	cnvrstnId 	= 	conn_id+user_login_id;
	var UsersBlockRef = Database.ref('users_blocked/'+cnvrstnId);
		UsersBlockRef.on('child_changed', function(snapshot){
		checkblockedUser();
	});
}

function checkblockedUser(){
	isUserBlocked().then(function(resp){
		if(resp){
			//$('#block_unblock_btn').attr('data-conn-status',0);
			//$('#block_unblock_btn').text('Block');
			$('.dis_chat_sendInput_wrap').addClass('hide');
		}else{
			//$('#block_unblock_btn').attr('data-conn-status',1);
			//$('#block_unblock_btn').text('Unblock');
			$('.dis_chat_sendInput_wrap').removeClass('hide');
			checkblockedUserBtn();
		}
	})
}

function isUserBlocked(){
	conn_id 	=	target_obj.attr('data-id');
	//cnvrstnId 	= 	(conn_id < user_login_id)? conn_id+user_login_id : user_login_id+conn_id;
	cnvrstnId 	= 	conn_id+user_login_id;
	return Database.ref("users_blocked/"+cnvrstnId+"/blocked_status").once("value").
	then(function(snapshot) {
		if (snapshot.exists()){
			let  userData =  snapshot.val();
			if(userData==0){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	})
}

function checkblockedUserBtn(){
	isUserBlockedBtn().then(function(resp){
		if(resp){
			$('#block_unblock_btn').attr('data-conn-status',1);
			$('#block_unblock_btn').find('.custom_dd_text').text('Unblock');
			$('.dis_chat_sendInput_wrap').addClass('hide');
			return true;
		}else{
			$('#block_unblock_btn').attr('data-conn-status',0);
			$('#block_unblock_btn').find('.custom_dd_text').text('Block');
			$('.dis_chat_sendInput_wrap').removeClass('hide');
			return false;
		}
	})
}

function isUserBlockedBtn(){
	conn_id 	=	target_obj.attr('data-id');
	//cnvrstnId 	= 	(conn_id < user_login_id)? conn_id+user_login_id : user_login_id+conn_id;
	cnvrstnId 	=  user_login_id+conn_id;
	return Database.ref("users_blocked/"+cnvrstnId+"/blocked_status").once("value").
	then(function(snapshot) {
		if (snapshot.exists()){
			let  userData =  snapshot.val();
			if(userData==0){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	})
}

function getCnvrstnId(){
	conn_id 	=	target_obj.attr('data-id');
	cnvrstnId 	= 	(conn_id < user_login_id)? conn_id+user_login_id : user_login_id+conn_id;
	return cnvrstnId;
}

function getCaret(el) { 
	if(el.selectionStart) { 
		return el.selectionStart; 
	}else if (document.selection) { 
		el.focus(); 
		var r = document.selection.createRange(); 
		if (r == null) { 
		  return 0; 
		} 
		var re = el.createTextRange(), 
			rc = re.duplicate(); 
		re.moveToBookmark(r.getBookmark()); 
		rc.setEndPoint('EndToStart', re); 
		return rc.text.length; 
	}  
	return 0; 
}
var  startAtId = '';
var  limitChat = 10;
function ShowMessageList(query=''){
	showChatHistory.empty();
	friend 		=	target_obj.attr('data-friend');
	target_id 	=	target_obj.attr('data-id');
	conn_user_uname =	target_obj.attr('data-user_uname');
	Connkey 		=	target_obj.attr('data-key');
	
	cnvrstnId 	= 	(target_id < user_login_id)? target_id+user_login_id : user_login_id+target_id;
	
	let html ='';  ///.orderByKey().startAt(startAtId).limitToLast(100)
	
	let dbRef = Database.ref("messages/"+cnvrstnId);
	
	/* if(query.length>0){
		let searchInputToLower = query.toString().toLowerCase();

		let searchInputTOUpper = query.toString().toUpperCase();
		
		dbRef =Database.ref("messages/"+cnvrstnId).orderByChild("message").startAt(`%${searchInputTOUpper}%`).endAt(searchInputToLower+"\uf8ff");
	} */
	
	dbRef.once("value", function (snapshot) {		
		snapshot.forEach(function(childSnapshot) {
			let childKey =childSnapshot.key;
			let childData = childSnapshot.val();
			if(childData && childData.sender != 'undefined' && childData.sender != undefined &&  $('#'+childKey).length == 0){
				
				if (childData.sender == user_login_id && childData.isDeletedsender) {
					html = sendingMessage(childData,childKey);
				}else if(childData.sender !== user_login_id && childData.isDeletedReceiver){
					html = receiveMessage(childData,childKey);
				}
				showChatHistory.append(html);
				showChatHistory.stop().animate({scrollTop:showChatHistory[0].scrollHeight},0);
				emojiInit();
				richLinkCode($('.contentTextChat').last());
				
			}
		});
		if(Connkey.length){
			let updates = {};
			updates[ '/'+user_uname+'/'+ Connkey + '/unrd_msg_cnt'] = 0;
			Database.ref('users_conn').update(updates);
			$('#'+cnvrstnId).next('.dis_chat_msgcount').remove();
			$('#count_unread_msg').text(0).addClass('hide');
			// $('#show_message').html(`<center>No Message </center>`);
			// $('.noti_drop').html(`<center>No Message </center>`);
		}
		
	});
}

function loadMoreChat(){
	
	friend 		=	target_obj.attr('data-friend');
	target_id 	=	target_obj.attr('data-id');
	cnvrstnId 	= 	(target_id < user_login_id)? target_id+user_login_id : user_login_id+target_id;
	
	let html ='';
	
	Database.ref("messages/"+cnvrstnId).orderByKey().endAt(startAtId).limitToLast(limitChat).once("value", function (snapshot) {		let i=0;
		snapshot.forEach(function(childSnapshot) {
			if(i==0){
				startAtId = childSnapshot.key;
			}
			i++;
			let childKey =childSnapshot.key;
			let childData = childSnapshot.val();
			if(childData && childData.sender != 'undefined' && childData.sender != undefined &&  $('#'+childKey).length == 0){
				console.log(childKey);
				if (childData.sender == user_login_id ) {
					html = sendingMessage(childData,childKey);
					
				}else{
					html = receiveMessage(childData,childKey);
				}
				
				showChatHistory.prepend(html);
				showChatHistory.stop().animate({scrollBottom:showChatHistory[0].scrollHeight},0);
			}
		});
	});
}

function searchChatMsg_old(query){
	
	friend 		=	target_obj.attr('data-friend');
	target_id 	=	target_obj.attr('data-id');
	cnvrstnId 	= 	(target_id < user_login_id)? target_id+user_login_id : user_login_id+target_id;
	
	let html ='';
	Database.ref("messages/"+cnvrstnId).orderByChild("message").startAt(query).endAt(query+"\uf8ff").once("value", function (snapshot) {
		showChatHistory.empty();
		snapshot.forEach(function(childSnapshot) {
			let childKey =childSnapshot.key;
			let childData = childSnapshot.val();
			
			if(childData && childData.sender != 'undefined' && childData.sender != undefined){
				console.log(childKey);
				if (childData.sender == user_login_id ) {
					html = sendingMessage(childData,childKey);
					
				}else{
					html = receiveMessage(childData,childKey);
				}
				
				showChatHistory.append(html);
				showChatHistory.stop().animate({scrollBottom:showChatHistory[0].scrollHeight},0);
			}
		});
	});
}

function CreateUser(user_id,user_uname,user_name,user_pic){
	return checkUserExists(user_uname).then(function(response){
		response = response.exists();  // true
		if(!response){
			Database.ref("users/"+user_uname).set({
				"user_id": user_id,
				"user_name": user_name,
				"last_seen": firebase.database.ServerValue.TIMESTAMP,
				"status" : 'Offline',
				"user_pic" : user_pic
			});
		}
		
	}).then(response => {
		return response;
	});;
}

function checkUserExists(user_uname){
	return Database.ref("users/"+user_uname).once("value").
	then(function(snapshot) {
		return snapshot  // true
	}).then(response => {
		return response;
	});
}

function checkUserConnExists(user_uname,conn_user_uname){
	let conn = '';
	var ref = Database.ref("users_conn/"+user_uname);
	return ref.once("value").then(function(snapshot) {
		// snapshot.numChildren()
		 snapshot.forEach(function(childSnapshot) {
			let key =  childSnapshot.key;
			conn_uname =  childSnapshot.val().conn_uname;
			if(conn_uname == conn_user_uname){
				return conn = key;
			}
		})
		return conn ;
	})
}

function CreateUserConn(conn_user_uname){
	return checkUserExists(user_uname).then(function(response){
		response = response.exists();  // true
		if(response){
			return checkUserConnExists(user_uname,conn_user_uname).then(function(key){
				console.log('connection ban gaya2',key);
				if(key.length == 0){
					
					let conn_key_ref = Database.ref("users_conn/"+user_uname).push();
					let conn_key = conn_key_ref.key;
					return conn_key_ref.set({
						"last_message"		: '---',
						"conn_uname"		: conn_user_uname,
						"update_at"			: firebase.database.ServerValue.TIMESTAMP,
						"conn_status" 		: 1,
						"unrd_msg_cnt"		: 0,
						"isTyping"		    : false,
						"isDeleted"		    : 1
					}).then(function(){
						return Database.ref("users_conn/"+conn_user_uname + '/'+conn_key).set({
									"last_message"	: '---',
									"conn_uname"	: user_uname,
									"update_at"		: firebase.database.ServerValue.TIMESTAMP,
									"conn_status" 	: 1,
									"unrd_msg_cnt"	: 0,
									"isTyping"		: false,
									"isDeleted"		: 1
								}).then(function(){
									return conn_key;
								});
					})
					
				}else{
					return key;
				}
			});
		}
	});
}

function sendMessage() {
	var write_msg =  $('.write_msg');
	let message = write_msg.val();
		message = message.replace(/(<([^>]+)>)/gi, "");
		write_msg.val('');
		$('#typingStatus').remove();
		updateTypingStatus(false);
	if((message.trim()).length){
		if(friend == 'NEW'){ write_msg.prop('disabled', true); }
		
		conn_user_uname =	target_obj.attr('data-user_uname');
		conn_id 		=	target_obj.attr('data-id');
		friend 			=	target_obj.attr('data-friend');
		Connkey 		=	target_obj.attr('data-key');
		cnvrstnId 		= 	(conn_id < user_login_id)? conn_id+user_login_id : user_login_id+conn_id;
		console.log(conn_user_uname);
		var CONF = Database.ref("messages/"+cnvrstnId);
		var update_at = firebase.database.ServerValue.TIMESTAMP;
		var mesAry = {
				"message"	:  	message ,
				"sender"	:  	user_login_id ,
				"receiver"	:  	conn_id ,
				"time"   	: 	update_at,
				"read_status": 	0, 
				"sender_auth_id" : sender_auth_id,
				"isDeletedReceiver" :true,
				"isDeletedsender"   :true,
				
			}
	
		console.log(mesAry);
		if(friend == 'NEW'){
			
			CreateUserConn(conn_user_uname).then(function(key){
				write_msg.prop('disabled', false);
				target_obj.attr('data-friend','OLD');
				if(key && key != 'undefined' && key != undefined){ 
					let updates = updateLastMessage(key,update_at,message);
					updates[ '/'+user_uname+'/'+ key + '/isDeleted'] = 1;
					updates[ '/'+conn_user_uname+'/'+ key + '/isDeleted'] = 1;
					Database.ref('users_conn').update(updates); 
				}
				
				CONF.push().set(mesAry).then(function(values) {
					sendChatNotification(conn_id,message);
				});
				$('#SearchUser').val('').trigger('keyup');
			});
		}else if(friend == 'OLD'){
				write_msg.prop('disabled', false);
				if(Connkey != 'undefined' && Connkey != undefined){ 
					let updates  = updateLastMessage(Connkey,update_at,message)
					updates[ '/'+conn_user_uname+'/'+ Connkey + '/isDeleted'] = 1;
					Database.ref('users_conn').update(updates);
					
					let unrd_msg_cnt = Database.ref("users_conn/"+conn_user_uname).child(Connkey).child('unrd_msg_cnt');
					unrd_msg_cnt.transaction(function(msgCount) {
					   return msgCount + 1;
					});
					sortMyUserList();
				}
				
				CONF.push().set(mesAry).then(function(values) {
					sendChatNotification(conn_id,message);
				});
				
				loadMyUserList();
		}
		
	}
	
	return false;
}

function updateLastMessage(Connkey,update_at,message){
	let updates = {};
		updates[ '/'+conn_user_uname+'/'+ Connkey + '/update_at'] = update_at;
		updates[ '/'+conn_user_uname+'/'+ Connkey + '/last_message'] = message.slice(0,30) ;
		updates[ '/'+user_uname+'/'+ Connkey + '/update_at'] = update_at;
		updates[ '/'+user_uname+'/'+ Connkey + '/last_message'] = message.slice(0,30);
		return updates;
}

function loadMyUserList(){
	var promises = [];
	var MyUserList = [];
	Database.ref("users_conn/"+user_uname).orderByChild("update_at").once("value", function (snapshot) {
			let reads = [];
			snapshot.forEach(function(childSnapshot) {
				let childKey = childSnapshot.key;
				let childData = childSnapshot.val();
				if(childData && childData.conn_uname != 'undefined' && childData.conn_uname != undefined && childData.isDeleted){
					 let promise = checkUserExists(childData.conn_uname).then(function(response){
						val = response.val();
						
						if(val && val.user_id != 'undefined' && val.user_id != undefined)
						MyUserList.push( {	"user_id":val.user_id, 
											"user_name":val.user_name, 
											"user_uname":response.key,
											"uc_pic":val.user_pic,
											"status":val.status,
											"last_message":childData.last_message,
											"update_at":childData.update_at,
											"key":childKey,
											"unread_msg": childData.unrd_msg_cnt
											} 
										);
										
					})
					reads.push(promise);
				}
			});
		return Promise.all(reads);
	}, function(error) {}).then(function(values) {
		setTimeout(function(){
			ShowUserLIst(MyUserList.reverse(),'RECENT','OLD');
		},500);
	})
}	

// image for message popups in case needed 	
// <span class="dis_chat_msgcount">${childData.unrd_msg_cnt}</span>
function load_msg_notifications(msg_arr, query_status) {
	$('#show_message').html('');
	msg_arr.forEach(childData => {
		Database.ref("users/"+childData.conn_uname).once("value", function (snapshot) {
				
			users_data = snapshot.val();
		});
		var sender = '';
		let time = childData.last_message;
		if (time === '' || time === undefined) {
			time = "--:--";
		}else{
			time = unixTime(childData.update_at);
			let uimg = getUserImage(users_data.user_id,users_data.user_pic);
				$('#show_message').append(`
				<div class="noti_wrapper message_open" data-p_pic="${uimg}" data-friend="OLD" data-id="${users_data.user_id}" data-user_uname="${childData.conn_uname}" data-key="${childData.childKey}" data-full_name="${users_data.user_name}">
					<div class="left">
						<a class="noti_img">
							<img id="profile_image" src="${uimg}" title="${childData.conn_uname}" class="img-responsive" alt="" onerror="this.onerror=null;this.src='https://test.discovered.tv/repo/images/banner_logo1.png'">
						</a>
						<div class="content">
						<a class="info"> ${users_data.user_name}</a>
							<span>${sender} ${childData.last_message}</span>
						</div>
					</div>
					<p>${getTimeAgo1(childData.update_at)}</p>
				</div>
				`);
			}
			query_status += 1;
		
	});
	return query_status;
}


var is_chat_page = $('#firebase_chat_page').val();
if (is_chat_page !== 'true') {
	$(document).on('click','.message_open', function () {

		$('#single_chat_messages').html('');
		$('.write_msg').val('');
		$('#popup_chat').show();
		$("#single_chat_title").text($(this).data('full_name'));
		$('#single_chat_title').attr('href', goto_profile+$(this).attr('data-user_uname'));
		$('#profile_img_chat_section').attr('src',$(this).find('img').attr('src'));
		$('#data_for_msging').attr('data-friend', $(this).attr('data-friend'));
		$('#data_for_msging').attr('data-id', $(this).attr('data-id'));
		$('#data_for_msging').attr('data-user_uname', $(this).attr('data-user_uname'));
		$('#data_for_msging').attr('data-key', $(this).attr('data-key'));
		$('#data_for_msging').attr('data-full_name', $(this).attr('data-full_name'));
		// target_obj = $('#data_for_msging');
		target_obj = $(this);
		showChatHistory = $('#single_chat_messages');
		ShowMessageList();	
	});
}

function load_popup() { 
	var query_status = 0;
	var MyUserList = [];
	Database.ref("users_conn/"+user_uname).orderByChild("update_at").once("value", function (snapshot) {
		
		snapshot.forEach(function(childSnapshot) {
			let childKey = childSnapshot.key;
			let childData = childSnapshot.val();
			MyUserList.push( {	
				"conn_uname":childData.conn_uname, 
				"last_message":childData.last_message, 
				"conn_uname":childData.conn_uname,
				"update_at":childData.update_at,
				"unrd_msg_cnt":childData.unrd_msg_cnt,
				"childKey":childKey,
				} 
			);
			
		});
	
});
MyUserList.reverse();
let status = load_msg_notifications(MyUserList, query_status);

if (status === 0) {	
	$('#show_message').html(`<center>No Message Discovered </center>`);
}
}
var load = '';
$(document).ready(function () {
	// To disable:    
	$('#loading_title').css('cursor', 'progress');
	document.getElementById('msg_icon').style.pointerEvents = 'none';
	// To re-enable:
	setTimeout(() => {
		document.getElementById('msg_icon').style.pointerEvents = 'auto';
		$('#loading_title').attr('title', '');
		$('#loading_title').css('cursor', 'pointer');
		$('#msg_icon').attr('title', 'Messages');

		load = true;
	}, 4000);
});

$(document).on('click', '#header_message_icon', function () {
	if (load === true) {
		let skeleton = '';
		for (let i = 0; i < 5; i++) {
			skeleton += '<div class="dis_skeleton"><div class="dis_skeleton_left"><div class="dis_skeletonCircle"></div></div><div class="dis_skeleton_right"><div class="dis_skeleton_line"></div><div class="dis_skeleton_line"></div></div></div>'
			
		}
		$('#show_message').html(skeleton);
		setTimeout(() => {
			load_popup();
			load = false;
		}, 2000);
	}else if(load === false){
		load_popup();
	}
});

// $(document).on('click', '#mark_all_read', function () {
// 	Database.ref("users_conn/"+user_uname).orderByChild("update_at").once("value", function (snapshot) {
// 		snapshot.forEach(function(childSnapshot) {
			
// 			// var query = Database.ref("users_conn/"+user_uname).orderByChild("unrd_msg_cnt").equalTo(1);
// 			// childSnapshot.once("child_added", function(snapshot) {
// 				// });
// 				childSnapshot.ref.update({ unrd_msg_cnt: 0 })
// 		});
// 	}); 


// 	$('#show_message').html(`<center>No Message </center>`);
// 	$('.dis_chat_msgcount').remove();
// });
function ShowUserListOnPopup(result,lable,type) { 
	var userList = '';
	let lables= ''; // let lables= '<div class="active" style="background:#dcd6d6">' + lable + '</div><span>'; 
	var count_unread_msg =0;
	userList += lables;
	$('#show_message').empty();
	result.forEach(function(item,index){
		
		let uid 		= item.user_id;
		let unm 		= item.user_name;
		let uunm 		= item.user_uname;
		
		let status 		= (item.status != 'undefined' && item.status != undefined)? item.status :'Offline';
		let msg_count 	= (item.unread_msg != 'undefined' && item.unread_msg != undefined)? item.unread_msg :0;
		let connKey 	= (item.key != 'undefined' && item.key != undefined)? item.key :'';
		let last_message= (item.last_message != 'undefined' && item.last_message != undefined)? (item.last_message).slice(0,30) : '-----';
		let update_at 	= (item.update_at != 'undefined' && item.update_at != undefined && item.update_at !='')? unixTime(item.update_at) : '--:--';
		update_at   = '--:--';
		
		if(user_login_id != uid){
			
			let commonId 	= 	(uid < user_login_id)? (uid+user_login_id) : (user_login_id+uid);
				commonId	= 	(lable == 'RECENT')? commonId :''; 
			let uimg		= 	getUserImage(uid,item.uc_pic);
			let clas = (status == 'Online') ? 'online' : 'offline';
			
			let unread_msg ='';
			if(msg_count>0){
				count_unread_msg += msg_count;
				unread_msg = '<span class="dis_chat_msgcount">'+msg_count+'</span>';
				$('#message_count').text(count_unread_msg).css('display','block');
				console.log(last_message);
				
			}						
			
			userList += '<div class="noti_wrapper message_open" data-p_pic="'+uimg+'" data-friend="'+type+'" data-id="'+uid+'" data-user_uname="'+uunm+'" data-key="'+connKey+'" data-full_name="'+unm+'"><div class="left"><a class="noti_img"><img src="'+uimg+'" title="'+unm+'" class="img-responsive" alt="" onerror="this.onerror=null;this.src=\'https://test.discovered.tv/repo/images/banner_logo1.png\'"></a><div class="content"><a class="info"> '+unm+'</a><span>'+last_message+'</span></div></div><p>'+update_at+'</p></div>';
					
					
			if (index == (result.length -1)){
				if(lable == 'PEOPLE'){
					$('#show_message').html(userList);
				}else{
					$('#show_message').html(userList);
					if( target_obj.length){
						target_obj = $('li.chat_list[data-id="'+ target_obj.attr('data-id')  +'"]');
						target_obj.addClass('active');
					}
				}
			}		
		}
	});
 }

function ShowUserLIst(result,lable,type){
	
	var userList = '';
	let lables= ''; // let lables= '<div class="active" style="background:#dcd6d6">' + lable + '</div><span>'; 
	var count_unread_msg =0;
	userList += lables;
	$('#search_user_list').empty();
	$('.dis_skeleton').css('display', 'none');
	result.forEach(function(item,index){
		
		let uid 		= item.user_id;
		let unm 		= item.user_name;
		let uunm 		= item.user_uname;
		
		let status 		= (item.status != 'undefined' && item.status != undefined)? item.status :'Offline';
		let msg_count 	= (item.unread_msg != 'undefined' && item.unread_msg != undefined)? item.unread_msg :0;
		let connKey 	= (item.key != 'undefined' && item.key != undefined)? item.key :'';
		let last_message= (item.last_message != 'undefined' && item.last_message != undefined)? (item.last_message).slice(0,30) : '-----';
		let update_at 	= (item.update_at != 'undefined' && item.update_at != undefined && item.update_at !='')? unixTime(item.update_at) : '--:--';
		if (item.last_message === '') {
			update_at = '--:--';
		}
		
		if(user_login_id != uid){
			
			let commonId 	= 	(uid < user_login_id)? (uid+user_login_id) : (user_login_id+uid);
				commonId	= 	(lable == 'RECENT')? commonId :''; 
			let uimg		= 	getUserImage(uid,item.uc_pic);
			let clas = (status == 'Online') ? 'online' : 'offline';
			
			let unread_msg ='';
			if(msg_count>0){
				count_unread_msg += msg_count;
				unread_msg = '<span class="dis_chat_msgcount">'+msg_count+'</span>';
				$('#message_count').text(count_unread_msg).css('display','block');
				
			}	
							
					
			userList +='<li class="chat_list" data-key="'+connKey+'" data-id="'+uid+'" data-user_uname="'+uunm+'" data-user_name="'+unm+'" data-friend="'+type+'" data-user_pic="'+item.uc_pic+'" data-online-status="'+status+'" data-profile-url="'+base_url+'profile?user='+uunm+'"><div class="dis_chat_contacts_box"><div class="dis_chat_contacts_avatar '+clas+'"><img src="'+uimg+'" title="avatar" alt="'+unm+'" onerror="this.onerror=null;this.src=\''+oEimg+'\';"></div><div class="dis_chat_contacts_details"><div class="dis_ccd_usertop"><h4 class="dis_ccd_userttl mp_0">'+unm+'</h4><ul class="dis_chatTime_list"><li><svg xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="15px" viewBox="0 0 17 17"><path fill-rule="evenodd" fill="rgb(174 174 174)" d="M11.804,9.294 C12.247,9.731 12.082,10.425 11.497,10.600 C11.208,10.687 10.933,10.636 10.716,10.427 C9.900,9.641 9.089,8.849 8.279,8.056 C8.123,7.903 8.059,7.710 8.059,7.493 C8.061,6.478 8.058,5.464 8.061,4.449 C8.062,3.983 8.406,3.634 8.858,3.632 C9.297,3.631 9.651,3.974 9.660,4.429 C9.670,4.872 9.662,5.316 9.663,5.759 C9.663,6.184 9.668,6.610 9.660,7.035 C9.658,7.161 9.700,7.248 9.791,7.336 C10.465,7.985 11.138,8.637 11.804,9.294 ZM10.068,14.427 C7.137,14.927 4.127,13.562 2.652,11.065 C2.488,10.788 2.470,10.511 2.641,10.237 C2.802,9.977 3.051,9.848 3.363,9.864 C3.660,9.878 3.878,10.026 4.029,10.274 C4.492,11.035 5.106,11.654 5.873,12.131 C9.189,14.192 13.686,12.272 14.362,8.506 C14.909,5.451 12.846,2.636 9.695,2.138 C7.009,1.714 4.284,3.382 3.513,5.924 C3.503,5.957 3.496,5.991 3.478,6.065 C3.967,5.794 4.413,5.539 4.867,5.298 C5.389,5.021 5.987,5.320 6.056,5.893 C6.093,6.203 5.968,6.465 5.692,6.621 C4.738,7.162 3.780,7.697 2.815,8.219 C2.443,8.420 1.976,8.282 1.779,7.915 C1.222,6.883 0.676,5.846 0.141,4.804 C-0.053,4.426 0.126,3.983 0.508,3.796 C0.890,3.609 1.348,3.738 1.561,4.109 C1.742,4.426 1.905,4.755 2.095,5.115 C2.132,5.030 2.157,4.974 2.182,4.918 C3.281,2.473 5.167,0.980 7.888,0.599 C11.668,0.071 15.173,2.529 15.921,6.167 C16.713,10.017 14.072,13.743 10.068,14.427 Z"/></svg></li><li class="chat_date">'+update_at+'</li></ul></div><div class="dis_ccd_usermsg"><p id="'+commonId+'">'+last_message+'</p>'+unread_msg+'</div></div></div></li>';		
					
					
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
		console.log(count_unread_msg);
	}
}


function setLstMess(childData){
	let sender 		= childData.sender;
	let receiver 	= childData.receiver;
	cnvrstnId 	= (sender < receiver)? sender+receiver : receiver+sender;
	if($('#'+cnvrstnId).length && ( user_login_id ==  sender || user_login_id ==  receiver) ){
		$('#'+cnvrstnId).text((childData.message).slice(0,30));
		$('#'+cnvrstnId).parent('.dis_ccd_usermsg').parent().find('li.chat_date').text(unixTime(childData.time));
	}
}

function getUserImage(uid,upc){
	if(upc.length){
	let image 	= 	new Image();
		upc 	= 	upc.split('.');
		upc		= 	upc[0] + '_thumb.'+ upc[1];	
		upc		=  	AMAZON_URL+'aud_'+uid+'/images/'+upc;
		image.src = upc;
		return upc;
		/* if (!image.complete) {
			return oEimg;
		} else {
			return upc;
		} */
	}else{
		return  oEimg;
	}
}

function ChangeImage(upc) {

	var image= new Image();
	return image.onload = function() {
		return upc;
	}();
	return image.onerror = function() {
		 return oEimg; 
	}();

	image.src = upc;
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

function sendingMessage(childData,key){
	let mess = childData.message ;
	mess = mess.replace(/(?:\r\n|\r|\n)/g, '<br>');
	let senderImg		= 	getUserImage(user_login_id,user_pic);
	
	/* if(separatorDate1 ==''){
		separatorDate1= unixDate(childData.time);
	}
	let separatorDate2 = unixDate(childData.time);
	
	html='';
	if(separatorDate1 !==separatorDate2){
		separatorDate1 = separatorDate2
		html +=`<div class="dis_chat_separator">
			<span class="dis_chat_separator_text">${separatorDate1}</span>													
			</div>`;
	} */
	
	return `<div class="dis_userchat_sender search_class">
		<div class="dis_chat_box">
			<!--div class="dis_chat_img">
				<img src="${senderImg}" alt="image" alt="icon" class="img-responsive">
			</div-->
			<div class="dis_chat_data">
				<div class="dis_chat_textbox">
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

function receiveMessage(childData,key){
	let mess = childData.message ;
	mess = mess.replace(/(?:\r\n|\r|\n)/g, '<br>');
	let user_name ='';
	let receiver_img='';
	if(target_obj.length){
		user_name 	 = target_obj.attr('data-user_name');
		receiver_img = target_obj.find('img').attr('src');
	}
	
	/* if(separatorDate1 ==''){
		separatorDate1= unixDate(childData.time);
	}
	let separatorDate2 = unixDate(childData.time);
	
	html='';
	if(separatorDate1 !==separatorDate2){
		separatorDate1 = separatorDate2
		html +=`<div class="dis_chat_separator">
			<span class="dis_chat_separator_text">${separatorDate1}</span>													
			</div>`;
	} */

	return `<div class="dis_userchat_receiver search_class">
			<div class="dis_chat_box">
				<div class="dis_chat_img">
					<img src="${receiver_img}" alt="image" alt="icon" class="img-responsive">
				</div>
				<div class="dis_chat_data">
					<div class="dis_chat_textbox">
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

// for popup messages showing start //
function sendingMessage_popup(childData,key,sender_name){
	let mess = childData.message ;
	mess = mess.replace(/(?:\r\n|\r|\n)/g, '<br>');
	let senderImg		= 	getUserImage(user_login_id,user_pic);
	return `<div class="outgoing message"><p class="dis_streamchat_msg">${mess}</p><p class="dis_streamchat_name">${unixTime(childData.time)}</p></div>`;						
}

function receiveMessage_popup(childData,key,sender_name){
	let mess = childData.message ;
	mess = mess.replace(/(?:\r\n|\r|\n)/g, '<br>');
	let user_name ='';
	let receiver_img='';
	if(target_obj.length){
		user_name 	 = target_obj.attr('data-user_name');
		receiver_img = target_obj.find('img').attr('src');
	}
	return `<div class="incoming message"><p class="dis_streamchat_msg">${mess}</p><p class="dis_streamchat_name">${unixTime(childData.time)}</p></div>`;							
}
// for popup messages showing end //

function emojiInit(){
	if($('.contentTextChat').length){
		let nodes = document.querySelectorAll(".contentTextChat");
		twemoji.parse( nodes[nodes.length- 1] , {folder: '72x72',ext: '.png',});
	}
}

function clearChatMessages_old() {
	 $("#conf_btn")
	.text("Clearing ")
	.append('<i class="fa fa-spinner fa-spin post_spinner"></i>')
	.prop("disabled", true);
	// get message ID
	//var messageId = self.getAttribute("data-id");
	target_id 		=	target_obj.attr('data-id');
	conn_user_uname =	target_obj.attr('data-user_uname');
	Connkey 		=	target_obj.attr('data-key');
	cnvrstnId 		= 	(target_id < user_login_id)? target_id+user_login_id : user_login_id+target_id;
	
	
	let dbRef = Database.ref("messages/"+cnvrstnId).orderByChild('sender').equalTo(user_login_id);
	dbRef.once("value", function (snapshot) {		
		snapshot.forEach(function(childSnapshot) {
			let childKey =childSnapshot.key;
			let childData = childSnapshot.val();
			console.log(childKey);
			// delete message
			Database.ref("messages/"+cnvrstnId).child(childKey).remove();
		});
		
		if(Connkey.length){
			let updates = {};
			updates 	= updateLastMessage(Connkey,'','');
			updates[ '/'+user_uname+'/'+ Connkey + '/unrd_msg_cnt'] = 0;
			updates[ '/'+conn_user_uname+'/'+ Connkey + '/unrd_msg_cnt'] = 0;
			$('#'+cnvrstnId).next('.dis_chat_msgcount').remove();
			Database.ref('users_conn').update(updates);
		}
		
		$(".dis_userchat_sender").remove();
		
		if($('#'+cnvrstnId).length){
			$('#'+cnvrstnId).text('-----');
			$('#'+cnvrstnId).parent('.dis_ccd_usermsg').parent().find('li.chat_date').text('--:--');
		}
		setTimeout(function () {
			$("#conf_btn").text("Clear").prop("disabled", false);
			success_popup_function("Chat clear successfully.");
		}, 1000);
	});
}

var curTypStatus ='';		
function updateTypingStatus(typeStatus){
	if(target_obj.length>0 && curTypStatus !==typeStatus){
		curTypStatus = typeStatus;
		conn_user_uname =	target_obj.attr('data-user_uname');
		Connkey 		=	target_obj.attr('data-key');
		if(Connkey.length){
			let updates = {};
			updates[ '/'+conn_user_uname+'/'+ Connkey + '/isTyping'] = typeStatus;
			Database.ref('users_conn').update(updates);
			// checkUserTypingStatus(updates);
			console.log(Database);
		}
	}
}


function checkUserTypingStatus(snapshot){
	if($("[data-user_uname="+snapshot.conn_uname+"]").hasClass('active')){
		$('#typingStatus').remove();
		if(snapshot.isTyping){
			let typingIcon =`<div class="dis_userchat_receiver" id="typingStatus">
				<div class="dis_chat_box">
					<!--div class="dis_chat_img">
						<img src="${base_url}repo/images/banner_logo1.png" alt="image" alt="icon" class="img-responsive">
					</div-->
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
			showChatHistory.stop().animate({scrollTop:showChatHistory[0].scrollHeight},1000);
		}else{
			$('#typingStatus').remove();
		}
	}
}

function isUserTyping(){
	Connkey 		=	target_obj.attr('data-key');
	return Database.ref("users_conn/"+user_uname+"/"+Connkey+"/isTyping").once("value").
	then(function(snapshot) {
		if (snapshot.exists()){
			return  isTyping =  snapshot.val();
		}else{
			return false;
		}
	})
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

function sendChatNotification(to_user,message){
	let formData = new FormData();
	formData.append("to_uid", to_user);
	formData.append("message", message);
	console.log(formData);
	manageMyAjaxPostRequestData(formData,base_url+'chat/sendChatNotification').done(function(resp){
		resp = JSON.parse(resp);
		if(resp.status){
			console.log(resp.message);
		}else{
			console.log(resp.message);
		}
	})
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

function clearChatMessages(){ 
	$("#conf_btn")
	.text("Clearing ")
	.append('<i class="fa fa-spinner fa-spin post_spinner"></i>')
	.prop("disabled", true);
	
	commonClearChat();
	
	setTimeout(function () {
		$("#conf_btn").text("Clear").prop("disabled", false);
		success_popup_function("Chat clear successfully.");
	}, 1000);
}


function commonClearChat(){
	target_id 		=	target_obj.attr('data-id');
	conn_user_uname =	target_obj.attr('data-user_uname');
	Connkey 		=	target_obj.attr('data-key');
	cnvrstnId 		= 	(target_id < user_login_id)? target_id+user_login_id : user_login_id+target_id;
	
	let dbRef = Database.ref("messages/"+cnvrstnId);
	dbRef.once("value", function (snapshot) {		
		snapshot.forEach(function(childSnapshot) {
			let updates = {};
			let childKey =childSnapshot.key;
			let childData = childSnapshot.val();
			if(childData && childData.sender == user_login_id){
				
				if(childData.isDeletedReceiver){
					updates[ '/'+ childKey + '/isDeletedsender'] = false;
					dbRef.update(updates);
				}else{
					Database.ref("messages/"+cnvrstnId).child(childKey).remove();
				}
				
			}else if(childData.isDeletedsender){
				
				updates[ '/'+ childKey + '/isDeletedReceiver'] = false;
				dbRef.update(updates);
				
			}else{
				
				Database.ref("messages/"+cnvrstnId).child(childKey).remove();
			}
		});
		if(Connkey.length){
			let updates = {};
			updates[ '/'+user_uname+'/'+ Connkey + '/unrd_msg_cnt'] = 0;
			// updates[ '/'+user_uname+'/'+ Connkey + '/update_at'] = '';
			updates[ '/'+user_uname+'/'+ Connkey + '/last_message'] = '';
			$('#'+cnvrstnId).next('.dis_chat_msgcount').remove();
			Database.ref('users_conn').update(updates);
		}
		
		$("#show_chat_history").empty();
		
		if($('#'+cnvrstnId).length){
			$('#'+cnvrstnId).text('-----');
			$('#'+cnvrstnId).parent('.dis_ccd_usermsg').parent().find('li.chat_date').text('--:--');
		}
	});
}

function deleteUserConnection() {
	$("#conf_btn")
	.text("Deleting ")
	.append('<i class="fa fa-spinner fa-spin post_spinner"></i>')
	.prop("disabled", true);
	
	target_id 		=	target_obj.attr('data-id');
	conn_user_uname =	target_obj.attr('data-user_uname');
	Connkey 		=	target_obj.attr('data-key');
	cnvrstnId 		= 	(target_id < user_login_id)? target_id+user_login_id : user_login_id+target_id;
	
	commonClearChat();
	
	let updates = {};
	updates[ '/'+user_uname+'/'+ Connkey + '/isDeleted'] = 0;
	Database.ref('users_conn').update(updates);
	
	loadMyUserList();
	
	$('.dis_userchat_inner').addClass('hide');
	$('#chat_welcome_msg').removeClass('hide');
	setTimeout(function () {
		$("#conf_btn").text("Clear").prop("disabled", false);
		success_popup_function("User deleted successfully.");
	}, 1000);
	
}

