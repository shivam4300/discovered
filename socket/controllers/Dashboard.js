const express   = require("express");
const { exit }  = require("process");
const router    = express.Router();

const dbCon     = require('../config/db.js');

const DASHBOARD = {};

const DIRECTORYFIELD = "`users_content`.`uc_city`, `users_content`.`uc_pic`, `users`.`user_uname`, `users`.`user_name`, `users`.`user_email`, `users`.`user_phone`, `artist_category`.`category_name`, `sigup_acc_type`, `country`.`country_name`, `state`.`name`, `users_content`.`total_channel_video`, `users`.`user_regdate`, `users`.`user_status`, `users_content`.`is_iva`, `users_content`.`is_ele`, `users_content`.`is_fc_member`, `users_content`.`uc_type`, `users_content`.`uc_gender`, `users`.`user_id` , `artist_category`.`category_name`";

function getDirectoryRequest(req){
    let myPromise = new Promise(function(myResolve, myReject) {
            
        let user_id     = req.body.user_id ? req.body.user_id : 0;
        let start       = req.body.start ? req.body.start : 0;
        let limit       = req.body.limit ? req.body.limit : 4;
        let search       = req.body.search ? req.body.search : '';
        
        let cate_id     = (req.body.cate_id).trim() ? " AND `artist_category`.`category_id` = " + req.body.cate_id : '';
        let subcate_ids = req.body['subcate_id[]']  ? ( Array.isArray(req.body['subcate_id[]']) ? req.body['subcate_id[]'] : [ req.body['subcate_id[]'] ]  ) : [];
        let country_ids = req.body['country_ids[]']  ? ( Array.isArray(req.body['country_ids[]']) ? req.body['country_ids[]'] : [ req.body['country_ids[]'] ]  ) : [];
            country_ids     = country_ids.toString();
            country_ids     = country_ids.length ? " AND `users_content`.`uc_country`  IN (" + country_ids + ")" : '';
    
        let subcate_id = '';
        if(subcate_ids.length){
            subcate_id += ' AND (' ;
            subcate_ids.forEach( ( sbct_id , index )=> {
                subcate_id += ' FIND_IN_SET('+ sbct_id +',`users_content`.`uc_type`) <> 0 ' ;
                if(subcate_ids.length - 1 != index){
                    subcate_id += ' OR ';
                }
            });
            subcate_id += ')';
        }
        
       
        var data   = {
            user_id,
            start,
            limit,
            search,
            cate_id,
            subcate_id,
            country_ids
        };
        myResolve(data);
    });
    return myPromise;
}

async function getMyFollowingId(req){
    var userIn = [];
    let r = await getDirectoryRequest(req);
   
    let myPromise = new Promise(function(myResolve, myReject) {
        dbCon.getConnection(function(err, connection) { 
			if (err) console.log('DB error1 : '+err);
            connection.query(`SELECT user_id FROM become_a_fan WHERE following_id=${r.user_id} AND user_id != ${r.user_id}`, function (err, result, fields) {
                connection.release();
                if (err){
                    myReject({'status' : 0 , 'message' : err.message});
                }else{
                    result.forEach(element => {
                        userIn.push(element.user_id);
                    });
                    myResolve({'status' :1 , 'userIn' : userIn.length ? userIn : 0 , ...r  }); 
                }
            })
        })
    });
    return myPromise;
}


DASHBOARD.usersYouMayKnow =  async(req, res) => { 
    let r = await getMyFollowingId(req);
    let myPromise = new Promise(function(myResolve, myReject) {
        dbCon.getConnection(function(err, connection) { 
            if (err) console.log('DB error1 : '+err);

            connection.query("SELECT "+ DIRECTORYFIELD +" FROM become_a_fan  LEFT JOIN  users ON users.user_id=become_a_fan.user_id LEFT JOIN `artist_category` ON `users`.`user_level` = `artist_category`.`category_id` LEFT JOIN `users_content` ON `users`.`user_id` = `users_content`.`uc_userid` LEFT JOIN `country` ON `users_content`.`uc_country` 	= `country`.`country_id` LEFT JOIN `state` ON `users_content`.`uc_state` = `state`.`id`  WHERE become_a_fan.following_id IN("+ (r.userIn).toString() +")  AND `become_a_fan`.`user_id` != "+ r.user_id +"  AND `become_a_fan`.`user_id` NOT IN("+ (r.userIn).toString() +") AND `user_status` = 1 AND `is_deleted` = 0 AND (`users`.`user_uname` LIKE '"+  r.search +"%' OR `users`.`user_name` LIKE '"+  r.search +"%' )"+ r.cate_id + r.subcate_id + r.country_ids +"  GROUP BY `users`.`user_id` LIMIT  "+ r.limit +" OFFSET "+ r.start  , function (err, result, fields) {
                connection.release();
                if (err){ 
                    myReject({'status' : 0 , 'message' : err });
                }else
                if(result.length){
                    myResolve({'status' : 1 , 'result' : result , 'myFollowingId' : r.userIn });
                }else{
                    myReject({'status' : 0 , 'message' : 'No User Available.' });
                };
            })
        })
    })
 
    myPromise.then(
        function(value) { 
            return res.json(value);
        },
        function(error) { 
            return res.json(error);
        }
    );
};

DASHBOARD.usersIFollowed =  async (req, res) => {   
    let r = await getDirectoryRequest(req);
    
    let myPromise = new Promise(function(myResolve, myReject) {
        dbCon.getConnection(function(err, connection) {
            if (err) console.log('DB error1 : '+err);

            connection.query("SELECT "+ DIRECTORYFIELD +" FROM become_a_fan  LEFT JOIN  users ON users.user_id=become_a_fan.user_id LEFT JOIN `artist_category` ON `users`.`user_level` = `artist_category`.`category_id` LEFT JOIN `users_content` ON `users`.`user_id` = `users_content`.`uc_userid` LEFT JOIN `country` ON `users_content`.`uc_country` 	= `country`.`country_id` LEFT JOIN `state` ON `users_content`.`uc_state` = `state`.`id`  WHERE become_a_fan.following_id IN("+ r.user_id +") AND `become_a_fan`.`user_id` != "+ r.user_id +" AND (`users`.`user_uname` LIKE '"+  r.search +"%' OR `users`.`user_name` LIKE '"+  r.search +"%' )"+ r.cate_id + r.subcate_id + r.country_ids +"  GROUP BY `users`.`user_id` LIMIT  "+ r.limit +" OFFSET "+ r.start  , function (err, result, fields) {
                connection.release();
                if (err){
                    myReject({'status' : 0 , 'message' : err });
                }else
                if(result.length){
                    myResolve({'status' : 1 , 'result' : result });
                }else{
                    myReject( {'status' : 0 , 'message' : 'No User Available.'});
                };
            })
        })
    });
 
    myPromise.then(
        function(value) { 
            return res.json(value);
        },
        function(error) { 
            return res.json(error);
        }
    );
   
};

DASHBOARD.usersWhoFollowedMe =  async (req, res) => {   
    let r = await getDirectoryRequest(req);
    console.log(r,'usersWhoFollowedMe');
    let myPromise = new Promise(function(myResolve, myReject) {
        dbCon.getConnection(function(err, connection) {
            if (err) console.log('DB error1 : ' + err);
            connection.query("SELECT "+ DIRECTORYFIELD +" FROM become_a_fan  LEFT JOIN  users ON users.user_id=become_a_fan.following_id LEFT JOIN `artist_category` ON `users`.`user_level` = `artist_category`.`category_id` LEFT JOIN `users_content` ON `users`.`user_id` = `users_content`.`uc_userid` LEFT JOIN `country` ON `users_content`.`uc_country` 	= `country`.`country_id` LEFT JOIN `state` ON `users_content`.`uc_state` = `state`.`id`  WHERE become_a_fan.user_id = "+ r.user_id +"  AND `user_status` = 1  AND (`users`.`user_uname` LIKE '"+  r.search +"%' OR `users`.`user_name` LIKE '"+  r.search +"%' )"+ r.cate_id + r.subcate_id + r.country_ids +"  GROUP BY `users`.`user_id` LIMIT  "+ r.limit +" OFFSET "+ r.start  , function (err, result, fields) {
                connection.release();
                if (err){
                    myReject({'status' : 0 , 'message' : err });
                }else
                if(result.length){
                    myResolve({'status' : 1 , 'result' : result});
                }else{
                    myReject({'status' : 0 , 'message' : 'No User Available.' });
                };
            })
        })
       
    });
 
    myPromise.then(
        function(value) { 
            return res.json(value);
        },
        function(error) { 
            return res.json(error);
        }
    );
   
};


DASHBOARD.countAllMyUsersDirectories =  async (req, res) => {  
    
    let r = await getMyFollowingId(req);
    
    let data = {'usersYouMayKnow' : 0, 'usersIFollowed' : (r.userIn).length, 'usersWhoFollowedMe' : 0} ;
    
    let myPromise = new Promise( function(myResolve, myReject) {
        
        dbCon.getConnection(async function(err, connection) { 
			if (err) console.log('DB error1 : '+err);
        
            connection.query("SELECT COUNT( DISTINCT `users`.`user_id` ) AS usersYouMayKnow FROM become_a_fan  LEFT JOIN  users ON users.user_id=become_a_fan.user_id LEFT JOIN `artist_category` ON `users`.`user_level` = `artist_category`.`category_id` LEFT JOIN `users_content` ON `users`.`user_id` = `users_content`.`uc_userid` LEFT JOIN `country` ON `users_content`.`uc_country` 	= `country`.`country_id` LEFT JOIN `state` ON `users_content`.`uc_state` = `state`.`id`  WHERE become_a_fan.following_id IN("+ (r.userIn).toString() +")  AND `become_a_fan`.`user_id` != "+ r.user_id +"  AND `become_a_fan`.`user_id` NOT IN("+ (r.userIn).toString() +") AND `user_status` = 1 GROUP BY `users`.`user_id` ", function (err, result, fields) {
                connection.release();
                if (err){ 
                    myReject({'status' : 0 , 'message' : err });
                }else{
                    data.usersYouMayKnow = result.length;
                };
            });
            let catewisecount = await GetCategoryWiseUsersCount(req);

            if(catewisecount['err'] ){
                myReject({'status' : 0 , 'message' : err });
            }else{
                data.usersWhoFollowedMe = catewisecount;
                myResolve(data);
            }
        })
    })
 
    myPromise.then(
        function(value) { 
            return res.json(value);
        },
        function(error) { 
            return res.json(error);
        }
    );
   
};



async function GetCategoryWiseUsersCount(req){
   
    let r = await getDirectoryRequest(req);
   
    let myPromise = new Promise(function(myResolve, myReject) {

        dbCon.getConnection(function(err, connection) { 
            if (err) console.log('DB error1 : '+err);
            
            connection.query("SELECT artist_category.category_name , COUNT( DISTINCT `users`.`user_id` ) AS usersWhoFollowedMe FROM become_a_fan  LEFT JOIN  users ON users.user_id=become_a_fan.following_id LEFT JOIN `artist_category` ON `users`.`user_level` = `artist_category`.`category_id` LEFT JOIN `users_content` ON `users`.`user_id` = `users_content`.`uc_userid` LEFT JOIN `country` ON `users_content`.`uc_country` 	= `country`.`country_id` LEFT JOIN `state` ON `users_content`.`uc_state` = `state`.`id`  WHERE become_a_fan.user_id = "+ r.user_id + r.cate_id + "   AND `user_status` = 1    GROUP BY `artist_category`.`category_id`" , function (err, result, fields) {
                connection.release();
                if (err){
                    
                    myReject({'status' : 0 , 'err' : err });
                }else{
                    
                    myResolve(result);
                };
            });

        });
        
    })
    return myPromise;
}
DASHBOARD.getMyFanCounts =  async (req, res)=> { 
    let r =  await GetCategoryWiseUsersCount(req);
    return res.json(r);
}



module.exports = DASHBOARD;