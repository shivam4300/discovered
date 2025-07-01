const User = require("../models/users");
const Connection = require("../models/usersConnection");
const Message = require("../models/message");

const moment = require("moment");
const cron = require('node-cron');

cron.schedule("* 0 * * *", function () {
    console.log("called cron");
    deleteMessagesAfter90Days() 
});

exports.manageUsers = async(req,res) => {
    try { 
        const { 
            user_id,
            user_uname,
            user_name,
            user_pic,
            status,
            user_login_id,
            user_data_id,
            parentAuthId, 
            userSelect=null,
            skip, limit
        } = req.body;
        let messageArray = [];

        let findUser = await User.findOne({user_id});
        if(findUser) {
            let connectionsReceiverStatus = await Connection.findOne({conn_id: parentAuthId,conn_parent_user: user_data_id}, { conn_status: 1});
            let connectionsSenderStatus = await Connection.findOne({conn_id: user_data_id,conn_parent_user: parentAuthId}, { conn_status: 1});
            let condition = {
                $and: [
                    {sender: { $in: [user_id, user_login_id] }},
                    {receiver: { $in: [user_id, user_login_id] }},
                    {isDeletedsender : false},
                    {isDeletedReceiver : false},
                    {isBlockedReceiver : false}
                ]
            }
            // if(connectionsReceiverStatus && connectionsReceiverStatus.conn_status === 3) condition["isBlockedReceiver"] = true;
            // if(connectionsSenderStatus && connectionsSenderStatus.conn_status === 3 && !userSelect)  condition["isBlockedSender"] = true;

            let allMessages = await Message.find(condition, {createdAt: 0, updatedAt: 0}).sort({ createdAt : -1 }).skip(skip).limit(limit);
            if(allMessages.length) {
                for await(let alm of allMessages) {
                    if(alm.sender === user_login_id && alm.receiver === user_id) {
                        if(!alm.isDeletedsender) {
                            messageArray.unshift(alm);
                        }
                    }
    
                    if(alm.sender === user_id  && alm.receiver === user_login_id) {
                        if(!alm.isDeletedReceiver && !alm.isBlockedReceiver) {
                            messageArray.unshift(alm);
                        }
                    }
                }
                let updateUnreadCount = await Connection.updateOne({conn_id: user_data_id,conn_parent_user: parentAuthId}, {unrd_msg_cnt: 0, updatedAt: Date.now()});
                res.status(200).json({status: "success", message: "", data: findUser, messages: messageArray});
                
            } else {
                res.status(200).json({status: "success", message: "", data: findUser, messages: []});
            }

        } else {
            let newUser = {
                "user_id"  : user_id,
                "user_uname": user_uname,
                "user_name": user_name,
                "last_seen": Date.now(),
                "status"   : status,
                "user_pic" : user_pic
            }
            let joinedUser = await User.create(newUser);
            if(joinedUser) {
                res.status(200).json({status: "success", message: "", data: joinedUser, messages: []});
            }
        }
    } catch (error) {
        res.status(403).json({status: "error", message: "Users list not found.", data: error});
    }
}

exports.updateSenderConnection = async(req,res) => {
    try {
        const { user_id, user_login_id,parentAuthId } = req.body;
        let connection = {};

        let findUser = await User.findOne({user_id});
        if(findUser) {
            let findConnection = await Connection.find({conn_id: {$in : [parentAuthId, findUser._id]},conn_parent_user:  {$in : [parentAuthId, findUser._id]}});
            await Promise.all(findConnection.map(async fc => {
                if(parentAuthId === fc.conn_parent_user) {
                    let findConnectedUser = await User.findOne({user_id : user_login_id})
                    if(!findConnectedUser.conn_key.includes(fc._id)) {
                        let updateUsersConnection = await User.updateOne({ user_id : user_login_id }, {$set: {updatedAt: Date.now()}, $push: {conn_key: fc._id}});
                        console.log("updateUsersConnection", updateUsersConnection);
                        let updateConnection = await Connection.updateOne({ _id : fc._id}, {$set : {conn_status: 1, isDeleted: 1}})
                        console.log("updateConnection", updateConnection);
                        connection = {
                            key: findUser._id, 
                            last_seen: findUser.last_seen, 
                            status: findUser.status, 
                            user_id: findUser.user_id, 
                            user_name: findUser.user_name, 
                            uc_pic: findUser.user_pic, 
                            user_uname: findUser.user_uname,
                            createdAt: fc.createdAt,
                            last_message: fc.last_message,
                            unread_msg: fc.unrd_msg_cnt,
                            update_at: fc.update_at,
                            conn_status: 1
                        }
                    }
                }
            }));

            res.status(200).json({status: "success", message: "", data: connection});
        }
    } catch(error) {
        res.status(403).json({status: "error", message: "Connection not found.", data: error});
    }
}

exports.getConnectedUsersList = async(req,res) => {
    try {
        let connectionArray = [];
        let findConnections = await User.findOne({ user_id : req.params.id}, { user_name:1, user_pic:1, createdAt: 1 })
        .populate("conn_key", "conn_id conn_uname isDeleted last_message unrd_msg_cnt update_at conn_status createdAt")
        .sort({"createdAt": -1});

        if(findConnections) {
            for await( var fc of findConnections.conn_key) {
                let findUser = await User.findOne({ _id: fc.conn_id}, { last_seen:1, status:1, user_id:1, user_name:1, user_pic:1, user_uname:1 })
                if(fc.isDeleted === 1) {
                    connectionArray.push({
                        key: findUser._id, 
                        last_seen: findUser.last_seen, 
                        status: findUser.status, 
                        user_id: findUser.user_id, 
                        user_name: findUser.user_name, 
                        uc_pic: findUser.user_pic, 
                        user_uname: findUser.user_uname,
                        createdAt: fc.createdAt,
                        last_message: fc.last_message,
                        unread_msg: fc.unrd_msg_cnt,
                        update_at: fc.update_at,
                        conn_status: fc.conn_status
                    });
                }
            }

            let sortData = connectionArray.sort((a,b) => {
                return (a.update_at > b.update_at) ? -1 : 0;
            });
            
            findConnections["conn_key"] = sortData;
            res.status(200).json({status: "success", message: "", data: findConnections});
        } else {
            res.status(200).json({status: "success", message: "", data: findConnections});
        } 
    } catch (error) {
        res.status(403).json({status: "error", message: "Users list not found.", data: error});
    }
}

exports.createUsersConnection = async (req, res) => {
    try {
        const { 
            conn_id, loggedin_user_id, last_message, 
            conn_uname, update_at, conn_status, 
            unrd_msg_cnt, isTyping, isDeleted, loggedin_user_name 
        } = req.body;

        let connection = {};

        let userConnection = await Connection.findOne({ conn_uname, conn_parent_user: loggedin_user_id });
        if(userConnection) {
            let checkConnectionExists = await Connection.findOne({conn_id: loggedin_user_id , conn_parent_user: conn_id})
            if(checkConnectionExists) {
                let findConnection = await Connection.findOne({conn_id: checkConnectionExists.conn_parent_user, conn_parent_user: checkConnectionExists.conn_id});
                if(findConnection) {
                    let findConnectedUser = await User.findOne({_id : loggedin_user_id});
                    let chatUser = await User.findOne({_id : conn_id}); 
                    if(!findConnectedUser.conn_key.includes(findConnection._id)) {
                        let updateUsersConnection = await User.updateOne({_id : loggedin_user_id}, {$set: {updatedAt: Date.now()}, $push: {conn_key: findConnection._id}});
                        let updateConnection = await Connection.updateOne({_id : findConnection._id}, {$set : {isDeleted: 1, conn_status: 1, updatedAt: Date.now()} });
                    }

                    connection = {
                        key: chatUser._id, 
                        last_seen: chatUser.last_seen, 
                        status: chatUser.status, 
                        user_id: chatUser.user_id, 
                        user_name: chatUser.user_name, 
                        uc_pic: chatUser.user_pic, 
                        user_uname: chatUser.user_uname,
                        createdAt: findConnection.createdAt,
                        last_message: findConnection.last_message,
                        unread_msg: findConnection.unrd_msg_cnt,
                        update_at: findConnection.update_at,
                        conn_status: findConnection.conn_status !== 1 ? 1 : findConnection.conn_status
                    }
                }


                if(checkConnectionExists.isDeleted) {
                    res.status(200).json({status: "success", message: "Connection found", data: connection});
                } else {
                    let updateCon = await Connection.updateOne({_id: checkConnectionExists._id}, {$set : {isDeleted: 1, updatedAt: Date.now()}})
                    let checkKeyExists = await User.findOne({ _id: loggedin_user_id });
                    if(!checkKeyExists.conn_key.includes(checkConnectionExists._id)) {
                        let updateUsersConnection = await User.updateOne({ _id: loggedin_user_id }, {$set: {updatedAt: Date.now()}, $push: {conn_key: checkConnectionExists._id}});
                        if(updateUsersConnection) {
                            console.log("userConnection if else");
                            res.status(200).json({status: "success", message: "Connection found", data: connection});
                        }
                    }
                }
            } else {
                let newConnection = { 
                    conn_id, conn_uname, last_message, 
                    update_at, unrd_msg_cnt, 
                    isTyping, isDeleted, conn_parent_user: loggedin_user_id, 
                    conn_status: 2
                }
    
                let createConnection = await Connection.create(newConnection);
                if(createConnection) {
                    let updateUsersConnection = await User.updateOne({ _id: loggedin_user_id }, {$set: {updatedAt: Date.now()}, $push: {conn_key: createConnection._id}});
                    if(updateUsersConnection) {
                        // console.log("userConnection if else");
                        res.status(200).json({status: "success", message: "Connection found", data: userConnection});
                    }
                }
            }
        } else {
            try {
                let newConnection = { 
                    conn_id, conn_uname, last_message, 
                    update_at, unrd_msg_cnt, 
                    isTyping, isDeleted, conn_parent_user: loggedin_user_id, 
                    conn_status: 1
                }
    
                let createConnection = await Connection.create(newConnection);
                if(createConnection) {
                    try {
                        let updateUsersConnection = await User.updateOne({ _id: loggedin_user_id }, {$set: {updatedAt: Date.now()}, $push: {conn_key: createConnection._id}});
                        if(updateUsersConnection) {
                            try {
                                let pn = { 
                                    conn_id : loggedin_user_id,
                                    conn_uname: loggedin_user_name, last_message, update_at, 
                                    conn_status, unrd_msg_cnt, isTyping, 
                                    isDeleted, conn_parent_user: conn_id, conn_status: 2
                                }
                                let createSecConn = await Connection.create(pn);
                                if(createSecConn) {
                                    try {
                                        let updateConnKey = await User.updateOne({ _id: conn_id}, {$set: {updatedAt: Date.now()}, $push: {conn_key: createSecConn._id}});
                                        console.log("updateConnKey createUsersConnection", updateConnKey);
                                        res.status(200).json({status: "success", message: "Connection is added.", data: createConnection});
                                    } catch(error) {
                                        res.status(403).json({status: "error", message: "Connection is not updated.", data: error, "errorType": "error"});
                                    }
                                } 
                            } catch(error1) {
                                res.status(403).json({status: "error", message: "2nd Connection is not added.", data: error1, "errorType": "error1"});
                            }
                        }
                    } catch(error2) {
                        res.status(403).json({status: "error", message: "Connection id is not added in users.", data: error2, "errorType": "error2"});
                    }
                }
            } catch(error3) {
                res.status(403).json({status: "error", message: "1st Connection is not added.", data: error3, "errorType": "error3"});
            }
        }
    } catch(err) {
        res.status(403).json({status: "error", message: "Users not found.", data: err, "errorType": "err"});
    }
}

exports.blockUnblockConnection = async(req,res) => {
    try {
        const { Connkey, sender_auth_id, conn_status } = req.body;
        let condition = {
            conn_parent_user: sender_auth_id,
            conn_id: Connkey,
        }, status ;

        if(conn_status === "accepted") {
            status = 3
        } else {
            status = 1
        }

        let changeStatus = await Connection.updateOne(condition, { conn_status: status });
        if(changeStatus) {
            res.status(200).json({status: "success", message: "User blocked.", data: changeStatus});
        }
    } catch(error) {
        res.status(403).json({status: "error", message: "1st Connection is not added.", data: error, "errorType": "error"});
    }
}

exports.clearChat = async(req,res) => {
    try {
        const { sender, receiver, sender_auth_id, conn_id } = req.body;

        let checkMessagesForSender = await Message.find({sender, receiver}, {isDeletedReceiver : 1, isDeletedsender : 1})
        if(checkMessagesForSender) {
            for await(cmfs of checkMessagesForSender) {
                if(!cmfs.isDeletedsender) {
                    let clearChatForSender = await Message.updateOne({_id: cmfs._id}, { isDeletedsender: true});
                    console.log("clearChatForSender", clearChatForSender);
                }
            }
        } 

        let checkMessagesForReceiver = await Message.find({sender: receiver, receiver: sender}, {isDeletedReceiver : 1, isDeletedsender : 1})
        if(checkMessagesForReceiver) {
            for await(cmfr of checkMessagesForReceiver) {
                if(!cmfr.isDeletedReceiver) {
                    let clearChatForReceiver = await Message.updateOne({_id: cmfr._id}, { isDeletedReceiver: true});
                    console.log("clearChatForReceiver", clearChatForReceiver);
                }
            }
        } 

        let condiiton =  {
            sender: { $in: [sender, receiver] },
            receiver: { $in: [sender, receiver] },
        }
        let checkBothClear = await Message.find(condiiton, {isDeletedReceiver : 1, isDeletedsender : 1})
        // console.log("checkBothClear",checkBothClear);

        if(checkBothClear) {
            for await(cbc of checkBothClear) {
                if(cbc.isDeletedReceiver && cbc.isDeletedsender) {
                    let deleteMessage = await Message.deleteOne({_id: cbc._id});
                    console.log("deleteMessage",deleteMessage);
                }
            }

            let updateLastMessage = await Connection.updateOne({conn_id, sender_auth_id}, { last_message: "-", update_at: Date.now().toString()});
            if(updateLastMessage){
                res.status(200).json({status: "success", message: "Chat is clear."})
            }
        }
    } catch(error) {
        res.status(403).json({status: "error", message: "Chat is not clear", data: error, "errorType": "error"});
    }
}

exports.deleteUser = async(req,res) => {
    try {
        const { sender, receiver, sender_auth_id, conn_id } = req.body;
        
        let condiiton =  {
            conn_id: { $in: [conn_id, sender_auth_id] },
            conn_parent_user: { $in: [conn_id, sender_auth_id] },
        }

        let findConnections = await Connection.find(condiiton);
        if(findConnections) {
            for await(let fc of findConnections) {
                if(fc.conn_id === conn_id) {
                    let updateConnection = await Connection.updateOne({conn_id,conn_parent_user: sender_auth_id}, {$set : {isDeleted: 0, conn_status: 2, updatedAt: Date.now()}});
                    if(updateConnection) {
                        let updateSenderMessages = await Message.updateMany({sender: receiver, receiver : sender}, {isDeletedReceiver: true});
                        let updateReceiverMessages = await Message.updateMany({sender, receiver}, {isDeletedsender: true});
                        console.log("updateReceiverMessages",updateReceiverMessages);
                        console.log("fc._id",fc._id);
                        let removeConnKey = await User.updateOne({user_id: sender}, { $pull: { conn_key: fc._id}})
                        // console.log("removeConnKey", removeConnKey)
                    }
                    
                } 
            }
    
            let findUpdateConnections = await Connection.find(condiiton);
            if(findUpdateConnections[0].isDeleted === 0 && findUpdateConnections[1].isDeleted === 0) {
                let msgCondition = {
                    sender: { $in: [sender, receiver] },
                    receiver: { $in: [sender, receiver] },
                }
                
                let deleteMessages = await Message.deleteMany(msgCondition);
                // console.log("deleteMessages", deleteMessages);
                if(deleteMessages){
                    let deleteConnections = await Connection.deleteMany(condiiton);
                    // console.log("deleteConnections", deleteConnections);
                    res.status(200).json({status: "success", message: "User connection deleted.", data: ""});
                }
            } else {
                res.status(200).json({status: "success", message: "User connection deleted.", data: ""});
            }
        }
    } catch(error) {
        res.status(403).json({status: "error", message: "Connection not found.", data: error});
    }
}

exports.updateUnreadMessage = async(req,res) => {
    try {
        const { user_data_id, parentAuthId } = req.body;

        let updateUnreadCount = await Connection.updateOne({conn_id: user_data_id,conn_parent_user: parentAuthId}, {unrd_msg_cnt: 0, updatedAt: Date.now()});
        if(updateUnreadCount) {
            res.status(200).json({status: "success", message: "", data: updateUnreadCount});
        }
    } catch(error) {
        res.status(403).json({status: "error", message: "Connection not found.", data: error});
    }
}

io.on('connection', socket => {
    console.log("socket connected successfully in messager!");
    /* console.log("socket", socket); */

    socket.on("new-message", async (data) => {
        // console.log("data", data);
        let conn_status = 1;
        if(data.connType === "NEW") {
            conn_status = 2;
            let findNewConnectionUser = await User.findOne({ user_id: data.sender });
            data["newConnection"] = findNewConnectionUser;
        }

        let checkStatus = await Connection.findOne({conn_id: data.sender_auth_id, conn_parent_user: data.Connkey });
        // console.log("checkStatus", checkStatus);
        if(checkStatus && checkStatus.conn_status === 3) {
            data["isBlockedReceiver"] = true;
        }

        if(data.ConnStatus === 'blocked') {
            data["isBlockedSender"] = true;
        }

        /* Add message into the database */
        let saveMsg = await Message.create(data);
        // console.log("saveMsg", saveMsg);

        /* update last message and time into the users connections */
        let condition = {
            conn_parent_user: { $in: [data.sender_auth_id, data.Connkey] },
            conn_id: { $in: [data.sender_auth_id, data.Connkey] },
        }
        
        if(checkStatus && checkStatus.conn_status !== 3 && data.ConnStatus !== 'blocked') {
            let checkUserHasConnectionId = await User.findOne({ user_id : data.receiver, conn_key: {$in : [checkStatus._id] }})
            // console.log("checkUserHasConnectionId", checkUserHasConnectionId);
            if(!checkUserHasConnectionId) {
                let pushConnectionKey = await User.updateOne({ user_id : data.receiver}, {$set: {updatedAt: Date.now()}, $push: {conn_key: checkStatus._id}})

                // console.log("pushConnectionKey", pushConnectionKey);
                let findNewConnectionUser = await User.findOne({ user_id: data.sender });
                data["newConnection"] = findNewConnectionUser;
            }

            let updateConnection = await Connection.updateMany(condition, { last_message: data.message, update_at: data.time});
            console.log("updateConnection", updateConnection);
            
            /* update single users connections status. */
            let updateUsersConnection = await Connection.updateOne({conn_id: data.Connkey, conn_parent_user: data.sender_auth_id }, { $set: {updatedAt: Date.now(), conn_status:1 } });
            // console.log("updateUsersConnection",updateUsersConnection);

            /* update single users connection unread message count. */
            let updateUnreadMsgCount = await Connection.updateOne({conn_id: data.sender_auth_id, conn_parent_user: data.Connkey }, {$set: {updatedAt: Date.now(), isDeleted: 1}, $inc: { unrd_msg_cnt : 1 } });
            // console.log("updateUnreadMsgCount",updateUnreadMsgCount);

            let status = await Connection.findOne({conn_id: data.sender_auth_id, conn_parent_user: data.Connkey }, {conn_status:1});
            console.log("status", status);
            if(status) {
                data.ConnStatus = status.conn_status === 2 ? "pending" : (status.conn_status === 3 ? "blocked" : "accepted")
            }
            socket.broadcast.emit("received-message", data);

        } else if(data.ConnStatus === 'blocked') {
            console.log("else if")
            /* update single users connections status. */
            let updateUsersConnection = await Connection.updateOne({conn_id: data.sender_auth_id, conn_parent_user: data.Connkey }, { last_message: data.message, update_at: data.time, updatedAt: Date.now()});
            // console.log("updateUsersConnection",updateUsersConnection);
        } else {
            console.log("else")
            /* update single users connections status. */
            let updateUsersConnection = await Connection.updateOne({conn_id: data.Connkey, conn_parent_user: data.sender_auth_id }, { last_message: data.message, update_at: data.time, conn_status:1, updatedAt: Date.now()});
            // console.log("updateUsersConnection",updateUsersConnection);
        }
    });

    /* Change message typing status */
    socket.on("typing-status", data => {
        console.log("typing-status data",data);
        socket.broadcast.emit("change-typing-status", data);
    });

    /* Change user status active and inactive */
    socket.on("user-status", async(data) => {
        let changeStatus = await User.updateOne({ "user_id": data.user_id }, { status: data.status });
        // console.log("changeStatus", changeStatus);
        socket.broadcast.emit("change-user-status", data);
    });

    /* Change user status like pending, accepted or blocked */
    socket.on('change-connection-status', async(data) => {
        // console.log("data", data);
        if(data.conn_status === 1) {
            let condition = {
                conn_parent_user: { $in: [data.sender_auth_id, data.Connkey] },
                conn_id: { $in: [data.sender_auth_id, data.Connkey] },
            }
    
            let changeStatus = await Connection.updateMany(condition, { conn_status: data.conn_status });
            if(changeStatus.modifiedCount === 0) {
                data.conn_status = 2
                socket.broadcast.emit("change-connection", data);
            } else socket.broadcast.emit("change-connection", data);
        } else if(data.conn_status === 3) {
            let condition = {
                conn_parent_user: data.sender_auth_id,
                conn_id: data.Connkey,
            }
    
            let changeStatus = await Connection.updateOne(condition, { conn_status: data.conn_status });
            if(changeStatus.modifiedCount === 0) {
                data.conn_status = 2
                socket.broadcast.emit("change-connection", data);
            } else socket.broadcast.emit("change-connection", data);
        }
    })

});

async function deleteMessagesAfter90Days() {
    const date = new Date().toLocaleString('en-US', {
        timeZone: 'Asia/Calcutta'
    });

    let deleteMessages = await Message.deleteMany({ 
        createdAt : {
            '$gte': moment(date).subtract(90, 'days').format('YYYY-MM-DD 00:00:00'),
            '$lte': moment(date).subtract(90, 'days').format('YYYY-MM-DD 23:59:59')
        }
    }) 
    if(deleteMessages) {
        console.log("deleteMessages", deleteMessages);
        console.log("Messages deleted successfully.");
    } else {
        console.log("Messages not found.");
    }
}
