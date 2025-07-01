importScripts('https://www.gstatic.com/firebasejs/7.15.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.15.0/firebase-messaging.js');

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
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
  
	self.addEventListener('notificationclick', function(event) {
		//console.log(doge.wow);
		console.log('enoti:',event.notification);
		event.notification.close();

		var promise = new Promise(function(resolve) {
			setTimeout(resolve, 3000);
		}).then(function() {
			return clients.openWindow(event.notification.data.click_action);
		});
		event.waitUntil(promise);
	});
	
	const messaging = firebase.messaging();
	
	messaging.setBackgroundMessageHandler(function(payload) {
		console.log('[firebase-messaging-sw.js] Received background message ', payload);
		var notificationTitle = payload.data.title;
		var notificationOptions = {
			body: payload.data.body,
			icon: payload.data.icon,
			data: {
				time: new Date(Date.now()).toString(),
				click_action: payload.data.click_action
			}
		};
		return  self.registration.showNotification(notificationTitle,notificationOptions);
	});
	
	
	
	
	
	

