<!DOCTYPE html>
<html>
  <head>
     <!-- **DO THIS**: -->
    <!--   Replace SDK_VERSION_NUMBER with the current SDK version number -->
   <script asyc src="<?php echo base_url('repo/js/aws/aws-sdk-2.747.0.min.js'); ?>" type="text/javascript"></script>
    
  
  </head>
  <body>
    <h1>My Photo Albums App</h1>
    <div id="app"></div>
	
	<input id="photoupload" type="file">
      <button id="addphoto" onclick="addphoto()">Add Photo</button>
  </body>
</html>

  <script>

	// var IdentityPoolId = 'us-west-2:1004c46f-a2bd-42d2-b8fd-41e34ea0fb7f';
	// var crad = AWS.config.credentials 	= new AWS.CognitoIdentityCredentials({
												// IdentityPoolId: IdentityPoolId,
											// });
			
				// AWS.config.credentials.expired
				// AWS.config.credentials.refresh((err)=> {
						// if(err)  {
						  // console.log(err);
						// }
						// else{
						  // console.log("TOKEN SUCCESSFULLY UPDATED");
						// }
				// })
		var base_url = window.location.origin;
		var albumBucketName = "discovered.tv.thumbs";
		var bucketRegion = "us-west-1";
		
		
		AWS.config.region 		= bucketRegion;
		
		var sts = new AWS.STS({apiVersion: '2011-06-15'});
		
		async function GetTokenData(url, data) {
			return await fetch(url, {
				method: "POST",
				headers: {
					'Accept': 'application/json',
					'Content-Type': 'application/json'
				},
				body: JSON.stringify(data)
			})
			.then( (response) => { 
				return response.json();
			}).then((response) => {
				return response;
			});
		}
		
		function GetIdTokens(){
			return  GetTokenData(base_url + '/test/GetIdTokens', { user : 'sachinraj' } )
			  .then(data => {
				return data;
			});
		}
		
		function GetWebIdentity(){
			
			return  GetIdTokens().then(function(data){
				return new Promise((resolve, reject) => {
					var params = {
					  RoleArn: 'arn:aws:iam::201068771454:role/Cognito_DiscoveredAuth_Role_new', /* required */
					  RoleSessionName: 'Cognito_DiscoveredAuth_Role_new', /* required */
					  WebIdentityToken: data.Token, /* required */
					};
					var myAwsCr = {};
					sts.assumeRoleWithWebIdentity(params, function(err, Crd) {
						if (err){
							reject(err, err.stack); // an error occurred
						}else{
							
							let obj = {
								region: bucketRegion,
								accessKeyId: Crd.Credentials.AccessKeyId,
								secretAccessKey: Crd.Credentials.SecretAccessKey,
								sessionToken : Crd.Credentials.SessionToken,
							}
							
							if (typeof (Storage) !== 'undefined'){
								let newObj = Object.assign(obj);
								newObj.exp_in = data.expire_in;
								localStorage.setItem('cit', JSON.stringify(newObj) );
							}
							
							resolve(obj) 
						}    
					});
					
				}) 
				
			})
			
		}
		
		function SetGetToken(){
			return new Promise((resolve, reject) => {
				
				if (typeof (Storage) !== 'undefined'){
					let cit = localStorage.getItem('cit');
					if(cit && cit.length){
						cit = JSON.parse(cit);
						var date = new Date(cit.exp_in +' UTC');
						var diff = date - new Date();
						console.log(diff)
						if(diff >= 0){
							delete cit.exp_in; 
							AWS.config.update(cit);
							return resolve();
						}
					}
				}
				
				GetWebIdentity().then(function(Credentials){
					AWS.config.update(Credentials);
					resolve();
				}).catch(function(e){
					console.log(e);
					reject()
				})
			})
		}
		
		function addphoto(){
			var files = document.getElementById("photoupload").files;
			var file = files[0];
			console.log(file);
			if (!files.length) {
				return alert("Please choose a file to upload first.");
			}else
			if (file.type != "video/mp4" && file.type != "video/quicktime"  ) {
				return alert("Please choose a correct file format.");
			} 
			
			var target = "aud_215/videos/";
			ProcessUpload(file,target).
			then(function(data){
				console.log(data);
			}).
			catch(function(err){
				 return alert(err);
			})
		}
		
		function ProcessUpload(file,target) {
			return new Promise((resolve, reject) => {
				SetGetToken().then(function(){
					
					var Key = target + generateRandomString(20) +'.'+  (file.name).split('.').pop();

					var upload = new AWS.S3.ManagedUpload({ // Use S3 ManagedUpload class as it supports multipart uploads
						params: {
						Bucket: albumBucketName,
						Key: Key,
						Body: file,
						ACL: "public-read",
						ContentType:file.type
						}
					});
					upload.on('httpUploadProgress', function (progress) {
					  // console.log(progress.loaded + " of " + progress.total + " bytes");
					  console.log(Math.round(progress.loaded/progress.total*100)+ '% done');
					});
					var promise = upload.promise();
					promise.then(
						function(data) {
							return resolve(data);
						},
						function(err) {
							return reject("There was an error uploading your photo: ", err.message);
						}
					);
				
				})	
			})
		}
		function generateRandomString(length){
			var chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			var random_string = '';
			if(length > 0){
			  for(var i=0; i < length; i++){
				  random_string += chars.charAt(Math.floor(Math.random() * chars.length));
			  }
		  }
			return random_string;
		}
   </script>