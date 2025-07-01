function isTokenSentToServer(user_login_id) {
	return window.localStorage.getItem('sentToServer') === user_login_id;
}
	
let mess = 'https://www.gstatic.com/firebasejs/7.15.0/firebase-messaging.js';
let app = 'https://www.gstatic.com/firebasejs/7.15.0/firebase-app.js';

loadScript(app, function(){ 
	loadScript(mess,function(){
		// loadScript('https://www.gstatic.com/firebasejs/8.1.1/firebase-auth.js',function(){
		// 	loadScript('https://www.gstatic.com/firebasejs/8.1.1/firebase-database.js',function(){
		// 		InitFirebase();
		// 	}); 
		// }); 
        InitFirebase();
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
	
	firebase.initializeApp(firebaseConfig);
	firebaseNotificationInit();	
}

function firebaseNotificationInit(){
	const messaging = firebase.messaging();
	messaging.usePublicVapidKey("BOYrV-rkG6Tj5ZBPQo5PcfTqWrJHc6nSyMORBQSeVv4yNwg2nMyRK-0rYo2jVdyau4Ss0YGazYaQQzbTce4VUPM");
	//messaging.usePublicVapidKey("BPM2MkQm-o6BBzfQq7ZvmCqcGmJbEHGnuslE7oZiL3VgVF9cGTBD22XSha-rJjj1du3Z3IitVu3JsqeMP_gygFA");  // from ajaydeep gmail account
		
	messaging.onTokenRefresh(() => {
		messaging.getToken().then((refreshedToken) => {
			console.log('Token refreshed.');
			setTokenSentToServer(0)
			sendTokenToServer(refreshedToken)
		}).catch((err) => {
			console.log('Unable to retrieve refreshed token ', err);
			showToken('Unable to retrieve refreshed token ', err);
		});
	});

	Notification.requestPermission().then((permission) => {
		if (permission === 'granted') {
			if(isTokenSentToServer(user_login_id)){
				console.log('Token has already sent.');
			}else{
				deleteToken()
			}
			console.log('Notification permission granted.');
		} else {
			console.log('Unable to get permission to notify.');
		}
	});

	function deleteToken() {
		messaging.getToken().then((currentToken) => {
			messaging.deleteToken(currentToken).then(() => {
				console.log('Token deleted.');
				setTokenSentToServer(0);
				getRegisterToken()
			}).catch((err) => {
				getRegisterToken()	
				console.log('Unable to delete token. ', err);
			});
			// [END delete_token]
		}).catch((err) => {
			getRegisterToken()	
			console.log('Error retrieving Instance ID token. ', err);
		});
	}

	function getRegisterToken(){
		messaging.getToken().then((currentToken) => {
			console.log(currentToken);
			if (currentToken) {
				sendTokenToServer(currentToken)
			} else {
				console.log('No Instance ID token available. Request permission to generate one.');
			}
		}).catch((err) => {
			console.log('An error occurred while retrieving token. ', err.message);
		});
	}

	function sendTokenToServer(currentToken) {
		if (!isTokenSentToServer(user_login_id)) {
			console.log('Sending token to server...');
			$.post(base_url+'dashboard/SetCurrentToken',{'uc_firebase_token':currentToken},function(res){
				setTokenSentToServer(user_login_id);
				// setTimeout(function(){location.reload(true);},30000)
				console.log('Token saved in database');
			})
		}else {
			console.log('Token already sent to server so won\'t send it again ' +'unless it changes');
		}
	}

	function setTokenSentToServer(user_login_id) {
		window.localStorage.setItem('sentToServer', user_login_id);
		storeCurrentTime();
	}

	messaging.onMessage(function(payload) {
		console.log('Message received. ', payload);
		var noti_type 		  = payload.data.noti_type;
		if(noti_type != 'chat_msg'){
			var notificationTitle = payload.notification.title;
			var notificationOptions = {
				body: payload.notification.body,
				icon: payload.notification.icon,
				data: {
						time: new Date(Date.now()).toString(),
						click_action: payload.notification.click_action
					}
				};
			var myNotification = new Notification(notificationTitle, notificationOptions);
			
			myNotification.addEventListener('click', function () { 
				// window.location = notificationOptions.data.click_action;
				window.open(notificationOptions.data.click_action);
				myNotification.close();  
			});
			
			triggerMyNotification();
		}
	});

	function triggerMyNotification(){
		let n =  parseInt($('.NotiCount:last').text())+1;
		$('.NotiCount:last').text(n);
		$('.NotiCount:last').show();
		
		
		if( $('.show_notification').hasClass('open')){
			$('.show_notification').trigger('click');
			$('.show_notification').trigger('click');
		}
	}

	function storeCurrentTime(){
		var d = new Date();
		store("tokenUpdateTime", d );
	}

	function calculateTimeStamp(){
		if ("tokenUpdateTime" in localStorage) {
			const date1 = new Date();
			const date2 = new Date(get('tokenUpdateTime'));
			const millis = Math.abs(date2 - date1);
			var totalMinutes = Math.floor(millis / 60000);
			var seconds = ((millis % 60000) / 1000).toFixed(0);
			var totalHours  = Math.floor((millis / (1000 * 60 * 60)));
			console.log(totalHours);
			if(totalHours>=12){
				deleteToken();
			}
		}else{
			deleteToken();
		}
	}

	setTimeout(function(){ calculateTimeStamp(); }, 10000);
}