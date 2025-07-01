
const users = require('../utils/users');

io.on("connection", socket => {
    socket.on('joinRoom', ({ username, room }) => {
        const user = users.userJoin(socket.id,username, room);
        socket.join(user.room);
        
        // Welcome current user
        socket.emit('message', {'user':'Discovered','message':'Welcome to Discovered!','totalRoomUsers' : users.getRoomUsers(user.room).length});
        // Broadcast when a user connects
        /*
        socket.broadcast
            .to(user.room)
            .emit(
            'message',  {'user':'Discovered','message': `${user.username} has joined the chat`,'totalRoomUsers' : users.getRoomUsers(user.room).length}
        );*/
    });
    
    socket.on('message',(msg)=>{
        const user = users.getCurrentUser(socket.id);
        
        if(typeof user !== 'undefined')
        socket.broadcast .to(user.room).emit('message',{'user':user.username,'message':msg.message,'totalRoomUsers' : users.getRoomUsers(user.room).length});
    })
    
    socket.on('disconnect',(msg)=>{
        const user = users.userLeave(socket.id);
        
        /*
        if(typeof user !== 'undefined')
        socket.broadcast .to(user.room).emit('message',{'user':user.username,'message':`${user.username} has left the chat`,'totalRoomUsers' : users.getRoomUsers(user.room).length});
        */
    })
});

