<style>
.container{max-width:1170px; margin:auto;}
img{ max-width:100%;}
.inbox_people {
  background: #f8f8f8 none repeat scroll 0 0;
  float: left;
  overflow: hidden;
  width: 40%; border-right:1px solid #c4c4c4;
}
.inbox_msg {
  border: 1px solid #c4c4c4;
  clear: both;
  overflow: hidden;
}
.top_spac{ margin: 20px 0 0;}

.headind_srch{ padding:10px 29px 10px 20px; overflow:hidden; border-bottom:1px solid #c4c4c4;}

.recent_heading {position:relative;float: left; width:15%;}
.recent_heading img{max-width:65%;border-radius:20px;}

.srch_bar {
  display: inline-block;
  text-align:right;
  width: 85%; padding:
}



.srch_bar input{ 
	border:1px solid #cdcdcd; border-width:0 0 1px 0; width:100%; padding:2px 0 4px 6px; background:none;}
.srch_bar .input-group-addon button {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  padding: 0;
  color: #707070;
  font-size: 18px;
}
.srch_bar .input-group-addon { margin: 0 0 0 -27px;}

.chat_ib h5{ font-size:15px; color:#464646; margin:0 0 8px 0;}
.chat_ib h5 span{ font-size:13px; float:right;}
.chat_ib p{ font-size:14px; color:#989898; margin:auto}

.chat_img {
  position:relative;
  float: left;
  width: 11%;
}
.chat_img img{
  border-radius:20px;
}
.chat_ib {
  float: left;
  padding: 0 0 0 15px;
  width: 88%;
}

.chat_people{ overflow:hidden; clear:both;}
.chat_list {
  border-bottom: 1px solid #c4c4c4;
  margin: 0;
  padding: 18px 16px 10px;
}
.inbox_chat { height: 550px; overflow-y: scroll;}

.active_chat{ background:#ebebeb;}

.incoming_msg_img {
  display: inline-block;
  width: 6%;
}
.received_msg {
  display: inline-block;
  padding: 0 0 0 10px;
  vertical-align: top;
  width: 92%;
 }
 .received_withd_msg p {
  background: #ebebeb none repeat scroll 0 0;
  border-radius: 3px;
  color: #646464;
  font-size: 14px;
  margin: 0;
  padding: 5px 10px 5px 12px;
  width: 100%;
}
.time_date {
  color: #747474;
  display: block;
  font-size: 12px;
  margin: 8px 0 0;
}
.received_withd_msg { width: 57%;}
.mesgs {
  float: left;
  padding: 30px 15px 0 25px;
  width: 60%;
}

 .sent_msg p {
  background: #05728f none repeat scroll 0 0;
  border-radius: 3px;
  font-size: 14px;
  margin: 0; color:#fff;
  padding: 5px 10px 5px 12px;
  width:100%;
}
.outgoing_msg{ overflow:hidden; margin:26px 0 26px;}
.sent_msg {
  float: right;
  width: 46%;
}
.input_msg_write input {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  color: #4c4c4c;
  font-size: 15px;
  min-height: 48px;
  width: 100%;
}
.write_msg{
	  width: 100%;
}

.type_msg {border-top: 1px solid #c4c4c4;position: relative;}
.msg_send_btn {
  background: #05728f none repeat scroll 0 0;
  border: medium none;
  border-radius: 50%;
  color: #fff;
  cursor: pointer;
  font-size: 17px;
  height: 33px;
  position: absolute;
  right: 0;
  top: 11px;
  width: 33px;
}
.messaging { padding: 0 0 50px 0;}
.msg_history {
  height: 516px;
  overflow-y: auto;
}
.active_user{
	position: absolute;
    width: 12px;
    height: 12px;
    background: #28a745;
    border-radius: 100px;
    bottom: 0;
    right: 20px;
    border: 2px solid #ffffff;
}

.position_user{
    right: 0px;
}
}
</style>
<html lang="en">
<head>
<!-- Latest compiled and minified CSS -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" type="text/css" rel="stylesheet">
	<script type="text/javascript" src="https://www.gstatic.com/firebasejs/8.1.1/firebase-app.js"></script>
	<script type="text/javascript" src="https://www.gstatic.com/firebasejs/8.1.1/firebase-auth.js"></script>
	<script type="text/javascript" src="https://www.gstatic.com/firebasejs/8.1.1/firebase-database.js"></script>
	<script type="text/javascript" src="https://www.gstatic.com/firebasejs/8.1.1/firebase-analytics.js"></script>
	
	<?php
	$base_url 	= 	base_url();
	$user_id 	= 	isset($user_info['user_id'])?$user_info['user_id']:'';
	$user_uname =	isset($user_info['user_uname'])?$user_info['user_uname']:'';
	$user_name 	=	isset($user_info['user_name'])?$user_info['user_name']:'';
	$user_pic 	=	isset($user_info['uc_pic'])?$user_info['uc_pic']:'';
	$user_image = 	get_user_image($user_id);
	$user_email =	isset($user_info['user_email'])?$user_info['user_email']:'';
	?>
	<script>
	/***************************************************************/
	/************FROM AJAYDEEP PIXELNX ACCOUNT FOR WEB********************/
	/***************************************************************/
	var firebaseConfig = {
    apiKey: "AIzaSyANQwPgSUcWez8b9lJ39k0HrzLLZ3cjsXc",
    authDomain: "discoveredchat-2d517.firebaseapp.com",
    databaseURL: "https://discoveredchat-2d517.firebaseio.com",
    projectId: "discoveredchat-2d517",
    storageBucket: "discoveredchat-2d517.appspot.com",
    messagingSenderId: "715217759152",
    appId: "1:715217759152:web:56a6ebbe330b7847c00475",
    measurementId: "G-5X9BGHP60X"
  };
	  
		firebase.initializeApp(firebaseConfig);
		
		var Database = 	firebase.database();
		
		var email = password =  '<?=$user_email ?>';
		
		
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
		var sender_auth_id ='';
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
		
		const base_url 		= 	'<?= $base_url ?>';
		const user_login_id	=	'<?= $user_id; ?>';
		const user_uname	=	'<?= $user_uname; ?>';
		const user_name	=		'<?= $user_name ?>';
		const user_pic	=		'<?= $user_pic ?>';
		
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
		
		var child_changed = true;;		
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
				}
			});
		
		var UsersRef = Database.ref('users');
			UsersRef.on('child_changed', function(snapshot){
			let KEY = $('div.chat_list[data-user_uname="'+ snapshot.key  +'"]');
				if(snapshot.val() && snapshot.val().status != 'undefined' && snapshot.val().status != undefined && KEY.length){
					let clas = (snapshot.val().status == 'Online') ? 'active_user' : '';
					KEY.find('.position_user').removeClass('active_user').addClass(clas);
				}
			});
		var UsersConnRef = Database.ref('users_conn/'+user_uname);
			UsersConnRef.on('child_changed', function(snapshot){
				loadMyUserList();
			});
			
	</script>	
</head>
	
<body onload="loadMyUserList();">
<div class="container">
<h3 class=" text-center">Messaging</h3>
<div class="messaging">
      <div class="inbox_msg">
        <div class="inbox_people">
          <div class="headind_srch">
            <div class="recent_heading">
				<img src="<?= $user_image ;  ?>" alt="sunil">
				<span id="MyActiveStatus"></span>
            </div>
            <div class="srch_bar">
              <div class="stylish-input-group">
                <input type="text" class="search-bar"  placeholder="Search" id="SearchUser">
                <span class="input-group-addon">
                <button type="button"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                </span> 
				</div>
            </div>
          </div>
          <div class="inbox_chat">
			<span id="search_user_list"></span>
            <span id="my_user_list"></span>
          </div>
        </div>
        <div class="mesgs">
			
			<div class="msg_history" id="show_chat_history">
				<h3 style="text-align:center;">Welcome, <?= $user_name; ?></h3>
			</div>
			 
			<div class="type_msg" style="display:none;">
				<div class="input_msg_write">
					<form onsubmit="return sendMessage();">
						<textarea type="text" class="write_msg" placeholder="Type a message" autocomplete="off" row="3"></textarea>
						<button type="submit" class="msg_send_btn" ><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
					</form>
				</div>
			</div>
        </div>
      </div>
      
    </div></div>
    </body>
<script>
			
	var d 		= new Date();
	var oEimg 	= base_url+'repo/images/user/user.png';
	var cnvrstnId ,friend ,target_obj = '';
	var showChatHistory = $('#show_chat_history');
			
	$(document).ready(function(){
		
		$(document).on('keyup','#SearchUser',function(){
			let _this = $(this);
			let search = (_this.val()).trim();
			
			if(search.length){
				let url = base_url+'chat/search_user';
				$.ajax({
						type	:'POST',
						dataType:'JSON',
						url		: url,
						data	: {'search':search},
						success	: function(result){
							ShowUserLIst(result,'PEOPLE','NEW')
						}
				});
			}else{
				$('#search_user_list').empty();
			}
		});
		
		$(document).on('click','.chat_list:not(.active_chat)',function(){
			target_obj = $(this);
				let user_uname	=	target_obj.attr('data-user_uname');
				let user_name 	=	target_obj.attr('data-user_name');
				let user_id 	=	target_obj.attr('data-id');
				let user_pic 	=	target_obj.attr('data-user_pic');
			
				CreateUser(user_id,user_uname,user_name,user_pic).then(function(){
					$('.chat_list').removeClass('active_chat');  
					target_obj.addClass('active_chat');
					ShowMessageList(target_obj);
					$('.type_msg').show();
				})
			
		})
		
		$(document).on('keyup','.write_msg',function(e){
			var thiss = $(this);
				
			if(event.keyCode == 13 && event.shiftKey){
				let content = this.value;
				let caret = getCaret(this);
				   thiss.value = content.substring(0,caret)+"\n"+content.substring(caret,content.length-1);
			}else if((e.keyCode || e.which) == 13){
				thiss.parents('form').submit();
				return false;
			}
		})
	
	})
	
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
	
	function ShowMessageList(snapshot){
		showChatHistory.empty();
		friend 		=	target_obj.attr('data-friend');
		target_id 	=	target_obj.attr('data-id');
		cnvrstnId 	= 	(target_id < user_login_id)? target_id+user_login_id : user_login_id+target_id;
		
		let html ='';
		Database.ref("messages/"+cnvrstnId).once("value", function (snapshot) {
			snapshot.forEach(function(childSnapshot) {
				let childKey = childSnapshot.key;
				let childData = childSnapshot.val();
				if(childData && childData.sender != 'undefined' && childData.sender != undefined &&  $('#'+childKey).length == 0){
					if (childData.sender == user_login_id ) {
						html = sendingMessage(childData,childKey);
					}else{
						html = receiveMessage(childData,childKey);
					}
					showChatHistory.append(html);
					showChatHistory.stop().animate({scrollTop:showChatHistory[0].scrollHeight},0);
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
					
					if(key.length == 0){
						let conn_key_ref = Database.ref("users_conn/"+user_uname).push();
						let conn_key = conn_key_ref.key;
						return conn_key_ref.set({
							"last_message"		: '---',
							"conn_uname"		: conn_user_uname,
							"update_at"			: firebase.database.ServerValue.TIMESTAMP,
							"conn_status" 		: 1
						}).then(function(){
							return Database.ref("users_conn/"+conn_user_uname + '/'+conn_key).set({
										"last_message"	: '---',
										"conn_uname"	: user_uname,
										"update_at"		: firebase.database.ServerValue.TIMESTAMP,
										"conn_status" 	: 1
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
			write_msg.val('')
		
		if((message.trim()).length){
			if(friend == 'NEW'){ write_msg.prop('disabled', true); }
			
			conn_user_uname =	target_obj.attr('data-user_uname');
			conn_id 		=	target_obj.attr('data-id');
			friend 			=	target_obj.attr('data-friend');
			Connkey 		=	target_obj.attr('data-key');
			
			cnvrstnId 	= 	(conn_id < user_login_id)? conn_id+user_login_id : user_login_id+conn_id;
			
			var CONF = Database.ref("messages/"+cnvrstnId);
			var update_at = firebase.database.ServerValue.TIMESTAMP;
			var mesAry = {
					"message"	:  	message ,
					"sender"	:  	user_login_id ,
					"receiver"	:  	conn_id ,
					"time"   	: 	update_at,
					"read_status": 	0, 
					"sender_auth_id" : sender_auth_id
				}
		
			console.log(mesAry);
			if(friend == 'NEW'){
				CreateUserConn(conn_user_uname).then(function(key){
					write_msg.prop('disabled', false);
					target_obj.attr('data-friend','OLD');
					if(key && key != 'undefined' && key != undefined){ 
						let updates = updateLastMessage(key,update_at,message);
							Database.ref('users_conn').update(updates); 
					}
					
					CONF.push().set(mesAry)
				});
			}else if(friend == 'OLD'){
					write_msg.prop('disabled', false);
					if(Connkey != 'undefined' && Connkey != undefined){ 
						let updates  = updateLastMessage(Connkey,update_at,message)
						Database.ref('users_conn').update(updates);
					}
					
					CONF.push().set(mesAry);
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
		
	function ShowMessage(key){
		let html ='';
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
					
					$('#search_user_list').empty();
					setLstMess(childData);
				}
			});
			setTimeout(function(){child_changed=true;},500);
		});
	}
	
	function loadMyUserList(){
		var promises = [];
		var MyUserList = [];
		Database.ref("users_conn/"+user_uname).orderByChild("update_at").once("value", function (snapshot) {
			    let reads = [];
				snapshot.forEach(function(childSnapshot) {
					let childKey = childSnapshot.key;
					let childData = childSnapshot.val();
					if(childData && childData.conn_uname != 'undefined' && childData.conn_uname != undefined){
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
												"key":childKey	
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
		
	function ShowUserLIst(result,lable,type){
		var userList = '';
		let lables= ''; // let lables= '<div class="active_chat" style="background:#dcd6d6">' + lable + '</div><span>'; 
		
		userList += lables;
		
		result.forEach(function(item,index){
			
			let uid 		= item.user_id;
			let unm 		= item.user_name;
			let uunm 		= item.user_uname;
			
			let status 		= (item.status != 'undefined' && item.status != undefined)? item.status :'Offline';
			let connKey 	= (item.key != 'undefined' && item.key != undefined)? item.key :'';
			let last_message= (item.last_message != 'undefined' && item.last_message != undefined)? (item.last_message).slice(0,30) : '-----';
			let update_at 	= (item.update_at != 'undefined' && item.update_at != undefined)? unixTime(item.update_at) : '--:--';
			
			if(user_login_id != uid){
				
				let commonId 	= 	(uid < user_login_id)? (uid+user_login_id) : (user_login_id+uid);
					commonId	= 	(lable == 'RECENT')? commonId :''; 
				let uimg		= 	getUserImage(uid,item.uc_pic);
				let clas = (status == 'Online') ? 'active_user' : '';
				
					userList += '<div class="chat_list" data-key="'+connKey+'" data-id="'+uid+'" data-user_uname="'+uunm+'" data-user_name="'+unm+'" data-friend="'+type+'" data-user_pic="'+item.uc_pic+'" style="cursor:pointer;"> ' +
							  '<div class="chat_people">' +
								'<div class="chat_img"> <img src="'+uimg+'" alt="'+unm+'" onerror="this.onerror=null;this.src=\''+oEimg+'\';"> <span class="position_user '+clas+'"></span> </div>' +
								'<div class="chat_ib">' +
								   '<h5>'+unm+'<span class="chat_date ">'+update_at+'</span></h5>' +
								  '<b><p id="'+commonId+'">'+last_message+'</p></b>' +
								'</div>' +
							  '</div>' +
							'</div>';
					if (index == (result.length -1)){
						if(lable == 'PEOPLE'){
							$('#search_user_list').html(userList);
						}else{
							$('#my_user_list').html(userList);
							if( target_obj.length){
								target_obj = $('div.chat_list[data-id="'+ target_obj.attr('data-id')  +'"]');
								target_obj.addClass('active_chat');
							}
							
						}
						
					}		
			}
		});
	}

	function setLstMess(childData){
		let sender 		= childData.sender;
		let receiver 	= childData.receiver;
		cnvrstnId 	= (sender < receiver)? sender+receiver : receiver+sender;
		if($('#'+cnvrstnId).length && ( user_login_id ==  sender || user_login_id ==  receiver) ){
			$('#'+cnvrstnId).text((childData.message).slice(0,30));
			$('#'+cnvrstnId).parents('.chat_ib').find('.chat_date ').text(unixTime(childData.time));
		}
	}
	// function ImageOnLoadError(uid,upc){
		// _this.src = src1;
		// _this.onload = function() {
			// _this.onerror=null;
		// };
		// _this.onerror = function(){
			// _this.src = src2;
			// _this.onerror=null;
		// };
	// }
	
	function getUserImage(uid,upc){
		console.log(uid,upc);
		if(upc.length){
		let image 	= 	new Image();
			upc 	= 	upc.split('.');
			upc		= 	upc[0] + '_thumb.'+ upc[1];	
			upc		=  	'https://s3-cdn.discovered.tv/'+'aud_'+uid+'/images/'+upc;
			
			image.src = upc;
			if (image.width == 0) {
			   return oEimg;
			} else {
			   return upc;
			}
		}else{
			return  oEimg;
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
		
		return '<div class="outgoing_msg">' +
				  '<div class="sent_msg">' +
					'<p id="'+key+'">'+mess+'</p>' +
					'<span class="time_date" > '+unixTime(childData.time)+'</span> </div>' +
				'</div>';
	}
	
	function receiveMessage(childData,key){
		let mess = childData.message ;
			mess = mess.replace(/(?:\r\n|\r|\n)/g, '<br>');
		
		return '<div class="incoming_msg"> ' +
				  '<!--div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div-->' +
				  '<div class="received_msg">' +
					'<div class="received_withd_msg">' +
					'<p id="'+key+'">'+mess+'</p>' +
					  '<span class="time_date" style="text-align:right;"> '+unixTime(childData.time)+'</span></div>' +
				  '</div>' +
				'</div>' ;
	}

	
</script>
		
	
</html>

