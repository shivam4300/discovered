
const mongoose = require('mongoose');

var message = new mongoose.Schema({
    message:{type: String},
    time :{ type: String},
    isDeletedReceiver :{ type: Boolean},
    isDeletedsender :{ type: Boolean},
    isBlockedReceiver :{ type: Boolean, default:false},
    isBlockedSender :{ type: Boolean, default:false},
    sender_auth_id :{ type: String}, //userId from mongoDB database.
    sender :{ type: String},
    receiver: {type: String},
    fileSize: {type: String, default:null},
    read_status :{ type: Number, default: 0},
    createdAt :{type: Date, default: Date.now},
    updatedAt :{type: Date, default: Date.now}
});

var messageSchema = mongoose.model('messages',message);
module.exports = messageSchema
