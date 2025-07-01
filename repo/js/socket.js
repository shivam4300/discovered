(function () {

    if (!$('.chatArea').length) {
        return false;
    }
    
    let socket      = io('https://'.concat(window.location.host), { path: "/node/socket.io" });
    let username    = $('#account_owner').val();
    let textarea    = document.querySelector('#textareas');
    let messageArea = document.querySelector('.chatArea');

    loadScript(CDN_BASE_URL + TWEMOJI_JS, function() {
        window.loadChat();
    });

    window.loadChat = function(){
        isChatLoaded=true;

        $('.chatArea').empty();

        let currntIndex = getCurrentIndex();
	    let item        = mainPlaylist[currntIndex].single_video;
        let room        = item.post_id;
   
        socket.emit('joinRoom', { username, room });
        
        if (textarea) {
            textarea.addEventListener("keyup", (e) => {
                if (e.key == 'Enter') {
                    if ($.trim((e.target.value)).length > 0)
                        sendMessage(e.target.value);
                    else
                        textarea.value = '';
                }
            })
        }

        function sendMessage(msgs) {
            if(username == 'Someone'){
                $('.login [data-href="modal/login_popup"]').click();
            }else{
                msg = {
                    user: username,
                    message: msgs.trim()
                }
                appendMessage(msg, 'outgoing');
                scrollToBottom()
                textarea.value = '';
                socket.emit("message", msg);
            }
        }
        
        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        function appendMessage(msg, type,saved='') {
            if(saved.length == 0){
                saveMsgStorage(msg, type)
            }
            
            let mainDiv = document.createElement('div');
            let className = type;
            mainDiv.classList.add(className, 'message');
            let markup = `<div class="dis_streamchatDetails"><h4 class="dis_streamchat_name">${capitalizeFirstLetter(msg.user)}</h4><p class="dis_streamchat_msg">${msg.message}</p></div>`;
            mainDiv.innerHTML = markup;
            messageArea.appendChild(mainDiv);

            let nodes = document.querySelectorAll('.dis_streamchat_msg');

            twemoji.parse(nodes[nodes.length - 1], { folder: '72x72', ext: '.png', });
        }

        var LiveChatMsg = 'LiveChatMsg'+room;

        function saveMsgStorage(msg, type){
            if(msg.message == "Welcome to Discovered!"){
                return;
            }
            msg.type = type;
            if(LiveChatMsg in localStorage) {
				let oldMsg = JSON.parse(get(LiveChatMsg));
				    oldMsg.push(msg);
				store(LiveChatMsg, JSON.stringify(oldMsg));
			}else {
				store(LiveChatMsg, JSON.stringify([msg]));
			}
        }
       
        socket.on('message', (msg) => {
            console.log(msg,'msg');
            
            $('#according_chat').attr('data-view_count',msg.totalRoomUsers);
            appendMessage(msg, 'incoming')
            scrollToBottom()
        })

        $(document).ready(function(){
            setTimeout(()=>{
                if(LiveChatMsg in localStorage) {
                    let oldMsg = JSON.parse(get(LiveChatMsg));
                    oldMsg.map(item => {
                        appendMessage(item, item.type,'saved');
                    })
                }
            },1000)
        })

        function scrollToBottom() {
            messageArea.scrollTop = messageArea.scrollHeight;
        }
    }

})();