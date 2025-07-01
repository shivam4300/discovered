const express = require("express");
const router = express.Router();

const ytdl = require("ytdl-core");
const readline = require("readline");
const dbCon = require('../config/db.js');
const AWS = require("aws-sdk");
const stream = require("stream");
const urlMetadata = require('url-metadata')

const controllers = {
	dashboard   : require('../controllers/Dashboard'),
	groupChat   : require('../controllers/groupChat'),
	messanger   : require('../controllers/messanger')
}

router.get('/', function(req, res, next) {
	res.render('index', {});  
});

router.post('/usersYouMayKnow', controllers.dashboard.usersYouMayKnow);
router.post('/usersIFollowed', controllers.dashboard.usersIFollowed);
router.post('/usersWhoFollowedMe', controllers.dashboard.usersWhoFollowedMe);
router.post('/countAllMyUsersDirectories', controllers.dashboard.countAllMyUsersDirectories);
router.post('/getMyFanCounts', controllers.dashboard.getMyFanCounts);


/* messanger controller */
router.post('/users/:id', controllers.messanger.manageUsers);
router.post('/get-connected-users/:id', controllers.messanger.getConnectedUsersList);
router.post('/add-users-connection', controllers.messanger.createUsersConnection);
router.post('/update-sender-connection', controllers.messanger.updateSenderConnection);
/* router.post('/update-connection-status', controllers.messanger.updateConnectionsStatus); */
router.post('/block-unblock-connection', controllers.messanger.blockUnblockConnection);
router.post('/clear-chat', controllers.messanger.clearChat);
router.post('/delete-user', controllers.messanger.deleteUser);
router.post('/update-unreadmsg', controllers.messanger.updateUnreadMessage);


function onlyUnique(value, index, self) {
  return self.indexOf(value) === index;
} 

router.post("/getGenreList", async (req, res) => {

	// dbCon.query(`SELECT * FROM mode_of_genre WHERE mode_id=${req.body.id} AND level = 1 ORDER BY genre_name ASC`, function (err, result, fields) {
	// 	if (err) console.log('DB error : '+err);
	// 	let option = '<option value="">Select Genre</option>';
	// 	console.log(result,'result');
	// 	if(result && result.length){
	// 		result.forEach(element => {
	// 			option += '<option value="'+element.genre_id+'">'+element.genre_name+'</option>';
	// 		});
	// 	}
	// 	res.send(option);
		
	// });
	
	dbCon.getConnection(function(err, connection) { 
		if (err) console.log('DB error1 : '+err);
		connection.query(`SELECT * FROM mode_of_genre WHERE mode_id=${req.body.id} AND level = 1 AND status = 1 ORDER BY genre_name ASC`, function (err, result, fields) {
			connection.release();
			if (err) console.log('DB error2 : '+err);
			let option = '<option value="">Select Genre</option>';
			
			if(result && result.length){
				result.forEach(element => {
					option += '<option value="'+element.genre_id+'">'+element.genre_name+'</option>';
				});
			}
			res.send(option);
		});
	});

})

router.post("/getSubGenreList", async (req, res) => {

	// dbCon.query(`SELECT * FROM mode_of_genre WHERE parent_id=${req.body.genre_id} ORDER BY genre_name ASC`, function (err, result, fields) {
	// 	if (err) console.log('DB error : '+err);
	// 	let option = '<option value="">Select Genre</option>';
	// 	if(result.length){
	// 		result.forEach(element => {
	// 			option += '<option value="'+element.genre_id+'">'+element.genre_name+'</option>';
	// 		});
	// 	}
	// 	res.send(option);
	// });

	dbCon.getConnection(function(err, connection) { 
		if (err) console.log('DB error1 : '+err);
		connection.query(`SELECT * FROM mode_of_genre WHERE parent_id=${req.body.genre_id} AND status = 1 ORDER BY genre_name ASC`, function (err, result, fields) {
			connection.release();
			if (err) console.log('DB error : '+err);
			let option = '<option value="">Select Genre</option>';
			if(result.length){
				result.forEach(element => {
					option += '<option value="'+element.genre_id+'">'+element.genre_name+'</option>';
				});
			}
			res.send(option);
		});
	});
}) 

router.post("/getTaglist", async (req, res) => { 
	// console.log(req.body.query);
	// dbCon.query(`SELECT tag FROM channel_post_video WHERE tag LIKE '%${req.body.query}%' LIMIT 5`, function (err, result, fields) {
	// 	if (err) console.log('DB error : '+err); 
	// 	let option = '<option value="">Select Genre</option>';
	// 	let arr = [];
	// 	if(result.length){
			
	// 		result.forEach(element => {
				
	// 			let res = (element.tag).split(",");
	// 				res.forEach(element => {
	// 					console.log(element)
	// 					arr.push(element);

	// 				});
	// 		});
	// 	}
	// 	let unique = arr.filter(onlyUnique);

	// 	res.json(unique);
	// });



	dbCon.getConnection(function(err, connection) { 
		if (err) console.log('DB error1 : '+err);
		connection.query(`SELECT tag FROM channel_post_video WHERE tag LIKE '%${req.body.query}%' LIMIT 5`, function (err, result, fields) {
			connection.release();
			if (err) console.log('DB error : '+err); 
			
			let arr = [];
			if(result.length){
				
				result.forEach(element => {
					
					let res = (element.tag).split(",");
						res.forEach(element => {
							console.log(element)
							arr.push(element);

						});
				});
			}
			let unique = arr.filter(onlyUnique);

			res.json(unique);
		});
	});


})

router.post("/getSubCategoryList", async (req, res) => {
	// dbCon.query(`SELECT * FROM artist_category WHERE parent_id=${req.body.id}`, function (err, result, fields) {
	// 	if (err) console.log('DB error : '+err);
	// 	let option = '<option value="">All Sub Category</option>';
	// 	if(result.length){
	// 		result.forEach(element => {
	// 			option += '<option value="'+element.category_id+'">'+element.category_name+'</option>';
	// 		});
	// 	}
	// 	res.send(option);
	// });


	dbCon.getConnection(function(err, connection) { 
		if (err) console.log('DB error1 : '+err);
		connection.query(`SELECT * FROM artist_category WHERE parent_id=${req.body.id}`, function (err, result, fields) {
			connection.release()
			if (err) console.log('DB error : '+err);
			let option = '<option value="">All Sub Category</option>';
			if(result.length){
				result.forEach(element => {
					option += '<option value="'+element.category_id+'">'+element.category_name+'</option>';
				});
			}
			res.send(option);
		});
	});
})

router.post("/metadata", async (req, res) => { 
	urlMetadata(req.body.url).then(
	  function (metadata) { // success handler
		res.json(metadata);
	  },
	  function (error) { // failure handler
		console.log(error)
	})
})


/*********************************Youtube video download code start*****************************************************************/
const uploadStream = ({ Bucket, Key, accessKeyId, secretAccessKey }) => {
const s3 = new AWS.S3({
	accessKeyId: accessKeyId,
	secretAccessKey: secretAccessKey,
});

const pass = new stream.PassThrough();
return {
	writeStream: pass,
	promise: s3.upload({ Bucket, Key, Body: pass, ACL: "public-read",ContentType:'video/mp4'}).promise(),
};
};

router.post("/download", async (req, res) => {
	console.log('user_id:',req.body.user_id);
	// return res.send('testing..');
	// let checkAWSCred = await checkUserAWSCred(req.body.user_id);
	// console.log(checkAWSCred);
	// if(checkAWSCred.status == 1 && checkAWSCred.data) {
		// let awsCred = (checkAWSCred.data) ? JSON.parse(checkAWSCred.data) : '';
	   
		const { writeStream, promise } = uploadStream({
			Bucket: 'discovered.tv',
			Key: `youtube/${req.body.channel_id}/${req.body.video_id}.mp4`,
			accessKeyId:'AKIAJF2HAKEJHPZURUJA',
			secretAccessKey:'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'
		});
		
		// promise.then((d) => {
			// updateVideoStatus(2,req.body.db_id,d.Location);
			// console.log("upload completed successfully", d.Location);
		// })
		// .catch((err) => {
			// updateVideoStatus(3,req.body.db_id,'');
			// console.log("upload failed.", err.message);
		// });
		
		try {
			console.log(req.body.url);
			ytdl(req.body.url, { filter: (format) => format.container === "mp4" })
				.on("error", (err) => {
					console.log('Errors:',err);
					res.json({ status: "error", message: err });
				})
				.on("progress", (chunkLength, downloaded, total) => {
					const percent = downloaded / total;
					readline.cursorTo(process.stdout, 0);
					process.stdout.write(
						`${(percent * 100).toFixed(2)}% downloaded `
					);
					process.stdout.write(
						`(${(downloaded / 1024 / 1024).toFixed(2)}MB of ${(
							total /
							1024 /
							1024
						).toFixed(2)}MB)`
					);
				})
				.pipe(writeStream)
				.on("finish", () => {
					console.log("\nVideo Downloaded");
					res.json({
						status: "success",
						message: "Video Downloaded Successfully!",
						data: '',
					});
				});
		} catch (err) {
			console.log(err);
			res.json({ status: "error", message: 'Error:'+err  });
		}
	// }else{
		// res.json({ status: "error", message: 'No AWS detail found.' });
		// console.log("No detail match.")
	// }
});


async function checkUserAWSCred(user_id) {
	return new Promise((resolve, reject) => {
		dbCon.query(`SELECT value FROM yd_user_meta WHERE user_id=${user_id}`, function (err, result, fields) {
			if (err) reject({'status':0,'msg':'DB error : '+err,'data':''})
			let cred = (result.length > 0) ? result[0].value : '';
			resolve({'status':1,'msg':'','data':cred});
		});
	})
}

async function updateVideoStatus(status,video_id,url = null){
	return new Promise((resolve, reject) => {
		let sql = `UPDATE yd_channel_videos SET is_download=${status} WHERE id=${video_id}`;
		if(url){
			sql = `UPDATE yd_channel_videos SET is_download=${status},download_url='${url}' WHERE id=${video_id}`
		}
		dbCon.query(sql, function (err, result, fields) {
			if (err) {
				console.log('DB Error',err); 
				reject(err); 
			}
			resolve('done')
			console.log('Download update record in database.')
		});
	})
}
/*********************************Youtube video download code End*****************************************************************/

module.exports = router;
