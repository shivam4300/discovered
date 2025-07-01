const mongoose = require('mongoose');

var userConnection = new mongoose.Schema({
    conn_id: { type: String},
    conn_uname :{ type: String},
    conn_parent_user: { type: String},
    isDeleted :{ type: Number}, //1-not deleted, 0-deleted
    isTyping: {type: Boolean},
    last_message:{type: String},
    unrd_msg_cnt :{ type: Number},
    conn_status :{ type: Number}, //1-accepted, 2-pending, 3-blocked.
    update_at :{type: String},
    createdAt :{type: Date, default: Date.now},
    updatedAt :{type: Date, default: Date.now}
});

var userConnectionSchema = mongoose.model('users_connection',userConnection);
module.exports = userConnectionSchema
