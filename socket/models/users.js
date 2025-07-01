
const mongoose = require('mongoose');

var Users = new mongoose.Schema({
    user_name :{ type: String},
    user_uname :{ type: String},
    last_seen: {type: String},
    user_id:{type: String},
    user_pic :{ type: String},
    conn_key: { type: Array, ref: "users_connection" },
    status :{ type: String},
    createdAt :{type: Date, default: Date.now},
    updatedAt :{type: Date, default: Date.now}
});

var userSchema = mongoose.model('users',Users);
module.exports = userSchema
