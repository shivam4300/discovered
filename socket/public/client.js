(function () {

    const socket = io('https://'.concat(window.location.host), {path: "/node/socket.io"} );
    
    let username;
    let textarea = document.querySelector('#textareas');
    let messageArea = document.querySelector('.message__area');
    
    do{
        username = prompt('Please enter your name;') 
    }while(!username)
    
    do{
        room = prompt('Please enter your room name;') 
    }while(!room)
     
    socket.emit('joinRoom', { username, room });
    if(textarea){
        textarea.addEventListener("keyup",(e)=>{
            if(e.key == 'Enter'){
                sendMessage(e.target.value);
            }
        })
    }
    
    function sendMessage(msgs){
        msg = {
            user:username,
            message:msgs.trim()
        }
        appendMessage(msg,'outgoing');
        scrollToBottom()
        textarea.value = '';
        socket.emit("message",msg);
    
    }
    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
      }
    function appendMessage(msg , type){
        let mainDiv = document.createElement('div');
        let className = type;
        mainDiv.classList.add(className,'message');
        let markup = `<h4>${ capitalizeFirstLetter(msg.user)}</h4><p>${msg.message}</p>`;
        mainDiv.innerHTML = markup;
        messageArea.appendChild(mainDiv); 
    }
    socket.on('message',(msg)=>{
        console.log(msg);
        appendMessage(msg , 'incoming')
        scrollToBottom()
    })
    function scrollToBottom(){
        messageArea.scrollTop = messageArea.scrollHeight;
    }
    
    })();