
const io = require('socket.io')(http, {path: '/node/socket.io'});
io.on("connection", socket => {
    socket.on('joinRoom', ({ username, room }) => {
        const user = users.userJoin(socket.id,username, room);
        console.log('current-join : ' , user)
        
        socket.join(user.room);
        // Welcome current user
        socket.emit('message', {'user':'Discovered','message':'Welcome to Discovered!'});
        // Broadcast when a user connects
        socket.broadcast
            .to(user.room)
            .emit(
            'message',  {'user':'Discovered','message': `${user.username} has joined the chat`}
        );
    });
    
    socket.on('message',(msg)=>{
        const user = users.getCurrentUser(socket.id);
        console.log('room : ' , 'ajaydeep parmar' )     
        
        if(typeof user !== 'undefined') 
        socket.broadcast.to(user.room).emit('message',{'user':user.username,'message':msg.message,'totalRoomUsers' : users.getRoomUsers(user.room).length});
    })
    
    // socket.broadcast.emit('message','A user has joined the chat.')
    
    socket.on('disconnect',(msg)=>{
        const user = users.userLeave(socket.id);
        console.log('current-disconnect : ' , user)
        if(typeof user !== 'undefined')
        socket.broadcast .to(user.room).emit('message',{'user':user.username,'message':`${user.username} has left the chat`});
    })
});

