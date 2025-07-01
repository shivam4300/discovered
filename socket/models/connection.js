var mongoose = require('mongoose');
const connection = mongoose.connect("mongodb://127.0.0.1:27017/discoveredSocket",{
    useNewUrlParser:true,
    useUnifiedTopology:true
})
mongoose.connection.on('connected',()=>{
    console.log("connected to mongoDB server");
});

mongoose.connection.on('error',(err)=>{
    console.log("error connecting",err);
});

module.exports = connection;