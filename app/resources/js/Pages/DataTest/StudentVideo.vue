<template>
    <div class="body" @click='onClickVideo'>
        <div class="header">
            <h1>授業名：{{$page.user.school.name}}</h1>
        </div>
        <div class="left">
            <div class="teacher-video">
                <div v-if="showTBG" class="backG"></div>
                <video :srcObject.prop="teacherVideoStream" autoplay></video>
            </div>
            <div class="my-video">
                <div v-if="showBackG" class="backG"></div>
                <video :srcObject.prop="localVideoStream" autoplay></video>
                <div class="tools">
                    <div :class="{active:videoEnable}" class="t1 t" @click="switchMedia(0)"><span v-if="videoEnable"></span></div>
                    <div :class="{active:audioEnable}" class="t2 t" @click="switchMedia(1)"><span v-if="audioEnable"></span></div>
                    <div :class="{active:share}" class="t3 t" @click="onScreenShare()"><span></span></div>
                    <div :class="{active:handUp}" class="t4 t" @click="hand()"><span></span></div>
                    <div :class="{active:diaglogFlag}" class="t5 t" @click="showLeaveDialog()"><span></span></div>
                </div>
            </div>
        </div>
        <div class="right">
            <div class="chat-area">
                <div class="messages">
                    <div ref="js_messages" class="messages_list"></div>
                </div>
                <div class="input-area">
                    <div class="input-area-inner">
                        <div class="my-message" ref="localText" placeholder="message" contenteditable="true" aria-multiline="true"></div>
                        <div class="options">
                            <button class="js-send-trigger" @click="onClickSend">Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 退出ダイアログ -->
        <div class="dialog" ref="leaveDialog" v-if="diaglogFlag">
            <div class="modal-content">
                <div class="modal-header">
                <span class="close" ref="js_Close_Dialog">&times;</span>
                <h2>ラクジュ</h2>
                </div>
                <div class="modal-body">
                <div class="content-text"><div class="warring"></div>退出しますか？</div>
                <div class="okButton" @click="dialogOk">はい</div>
                </div>
            </div>
        </div>
        <!-- 承認待ちダイアログ -->
        <transition name="show">
            <div class="dialog dialogBackG" v-if="waitDialog">
                <div class="modal-content sys">
                    <div class="modal-header">
                        <h2>ラクジュ</h2>
                    </div>
                    <div class="modal-body" ref="dialogContent">
                        <div class="content-text">
                            <div class="loadingio-spinner-spinner-nglqv2ndcch">
                                <div class="ldio-5ekurv8jzaa">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                            </div>
                            先生からの承認を待っています. . .
                        </div>
                        <div class="okButton" @click="joinCancel">キャンセル</div>
                    </div>
                </div>
            </div>
        </transition>
        <!-- 拒否ダイアログ -->
        <div class="dialog dialogBackG" v-if="joinDialog">
            <div class="modal-content sys">
                <div class="modal-header">
                    <h2>ラクジュ</h2>
                </div>
                <div class="modal-body">
                    <div class="content-text">
                        加入を拒否されました
                    </div>
                    <div class="okButton" @click="joinCancel">OK</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['user'],
        data(){
            return{
                waitDialog:true,                //承認待ち用
                localStream:undefined,           //カメラ用
                localVideoStream: undefined,          //画面上に表示するストリーム
                teacherVideoStream:undefined,
                share:false,                     //share flag
                CLASS_CODE:'123',                //授業Code
                peer:undefined,                //Peerのオブジェクト
                room:undefined,               //Room のオブジェクト
                diaglogFlag:false,           //退出dialog FLag 
                joinDialog:false,    
                showBackG:false,
                showTBG:false,
                videoEnable: false,
                audioEnable: false,
                noMedia:false,
                handUp:false,
            }
        },
        methods: {
            init(){
                console.log("peer.open");

                this.waitRoom = this.peer.joinRoom(`waitRoom${this.CLASS_CODE}`,{
                    mode:'sfu',
                });
                
                if(this.waitRoom!=undefined){
                    this.waitRoom.once('open', () => {
                        this.waitRoom.send(`wait${this.user.id}`)
                    });
                    this.waitRoom.on('peerJoin', peerId => {
                        this.waitRoom.send(`wait${this.user.id}`)
                    });
                    this.waitRoom.on('data', ({ data, src }) => {
                        if(data.id==this.user.id){
                            if(data.res=="ok"){
                                this.$refs.dialogContent.innerHTML = "<div class='content-text'>承認されました</div>";
                                this.waitDialog=false
                                this.waitRoom.close();
                            }else{
                                this.joinDialog = true
                            }
                        }
                    });
                    this.waitRoom.once('close', () => {
                        if(this.waitDialog){
                            window.close()
                        }else{
                            this.joinClassRoom();
                        }
                    });
                }
            },
            joinClassRoom(){
                navigator.mediaDevices.getUserMedia({
                    video: true,
                    audio: true
                }).then(stream => {
                    this.localStream = stream;
                    this.localVideoStream = this.localStream;
                }).catch(e=>{
                    console.log(`${e.type}:${e}`);
                    this.showBackG = true;
                });

                this.room = this.peer.joinRoom(this.CLASS_CODE,{
                    mode: 'sfu',
                    stream: this.localVideoStream,
                });
                console.log(this.room);
                if(this.room!=undefined){
                    // roomt on lisener
                    this.room.once('open', () => {
                        this.appendMsg(this.addElement('div',`${this.CLASS_CODE}に参加しました。`,'systemMsg',''));
                    });

                    this.room.on('peerJoin', peerId => {
                        this.appendMsg(this.addElement('div',`=== ${peerId.substring(3)}さんが入りました ===`,'systemMsg',''));
                    });
                
                    this.room.on('stream', async stream => {
                        alert("ok");
                        if(stream.peerId.substring(1,2)=="te"){
                            this.teacherVideoStream = stream;
                        }
                    });
            
                    this.room.on('data', ({ data, src }) => {
                        if(data.type==100){
                            if(data.cmd==2){
                                this.handUp = data.value;
                            }
                            switch(data.cmd) {
                                case 0:
                                    this.room.close()
                                    break;
                                case 1:
                                    
                                    break;
                                case 2:
                                    this.handUp = data.value;
                                    break;
                            } 
                        }
                        if(data.type==200){
                            if(data.roll_flag=='te'){
                                this.createMsg('/images/bubble.png',data.id,data.roll_flag,data.msg,'teacherMsg');
                            }else{
                                this.createMsg('/images/bubble.png',data.id,data.roll_flag,data.msg,'studentMsg');
                            }
                        }
                    });
            
                    this.room.on('peerLeave', peerId => {
                        this.appendMsg(this.addElement('div',`=== ${peerId.substring(3)}さんが退室しました ===\n`,'systemMsg',''));                    });
            
                    this.room.once('close', () => {
                        window.close()
                    });
                }
            },
            switchMedia(media){
                if(this.noMedia){
                    return
                }
                //video
                if(media===0){
                    if(this.localVideoStream.getVideoTracks()[0].enabled){
                        //close the video
                        this.localVideoStream.getVideoTracks().forEach( track => (track.enabled = false));
                        this.showBackG = true;
                        this.videoEnable = true;
                    }else{
                        //open the video
                        this.localVideoStream.getVideoTracks().forEach( track => (track.enabled = true));
                        this.showBackG = false;
                        this.videoEnable = false;
                    }
                }
                //mic
                if(media===1){
                    if(this.localVideoStream.getAudioTracks()[0].enabled){
                        this.audioEnable = true;
                        this.localVideoStream.getAudioTracks().forEach((track) => (track.enabled = false));
                    }else{
                        this.audioEnable = false;
                        this.localVideoStream.getAudioTracks().forEach( track => (track.enabled = true));
                    }
                }
            },
            onScreenShare(){
                if(this.noMedia){
                    return
                }
                if(this.share){
                    this.localVideoStream.getVideoTracks()[0].stop();
                    this.showLocalVideo();
                }else{
                    navigator.mediaDevices.getDisplayMedia({ 
                        video: true,
                    }).then(stream => {
                        this.share=true;
                        if(!this.localVideoStream.getVideoTracks()[0].enabled){
                            this.localVideoStream=stream;
                            this.localVideoStream.getVideoTracks().forEach( track => (track.enabled = false));
                        }else{
                            this.localVideoStream=stream;
                        }
                        stream.getVideoTracks()[0].addEventListener('ended', () => {this.showLocalVideo()});
                        this.room.replaceStream(this.localVideoStream);
                    }).catch(e=>{console.log("err:"+e)});
                }
            },
            showLocalVideo(){
                this.share=false;
                if(!this.localVideoStream.getVideoTracks()[0].enabled){
                    this.localVideoStream = this.localStream;
                    this.localVideoStream.getVideoTracks().forEach( track => (track.enabled = false));
                }else{
                    this.localVideoStream = this.localStream;
                }
                this.room.replaceStream(this.localVideoStream);
            },
            onClickSend(){
                if(!this.peer.open){
                    return;
                }
                this.$refs.localText.focus();
                if(this.$refs.localText.innerText === ''){
                    return;
                }
                var text = this.$refs.localText.innerText;
                this.room.send(this.jsonMsg(this.user.id,text));
                this.createMsg('/images/bubble.png',this.user.id,this.user.roll_flag,text,'studentMsg');
                this.$refs.localText.innerText = '';
            },
            showLeaveDialog(){
                this.diaglogFlag = true;
            },
            dialogOk(){
                this.room.close();
            },
            onClickVideo:function(e){
                if (e.target == this.$refs.leaveDialog || e.target == this.$refs.js_Close_Dialog) {
                    this.diaglogFlag = false;
                }
            },
            onSwitchDialogFlag(){
                return this.diaglogFlag;
            },
            addElement(elementName,content,className,elementId){
                var msg = document.createElement(elementName);
                msg.innerText = content;
                if(className!=''){
                    msg.className = className;
                }
                if(elementId!=''){
                    msg.ref = elementId;
                }
                return msg;
            },
            createMsg(imgUrl,name,roll_flag,msgText,msgClass){
                var msg =  document.createElement('div');
                msg.className='msg';

                var msgHead =  document.createElement('div');
                msgHead.className = 'msgHead';

                var msgTime =  document.createElement('div');
                msgTime.className = 'msgTime';
                var d = new Date();
                msgTime.innerText = d.getHours() +":"+d.getMinutes(); 

                var img =  document.createElement('div');
                img.className = 'msgImg';
                img.style.backgroundImage=`url(${imgUrl})`;

                var msgName =  document.createElement('div');
                msgName.innerText = name;
                msgName.className = 'msgName';
                //msg
                var msgContent = this.addElement('div',msgText,'msgContent','msgContent');

                //studentMsg
                var theMsg = document.createElement('div');
                theMsg.className = msgClass;
                //append
                msgHead.appendChild(msgName);
                msgHead.appendChild(msgTime);
                msg.appendChild(msgHead);
                msg.appendChild(msgContent);
                if(roll_flag == 'te'){
                    theMsg.appendChild(msg);
                    theMsg.appendChild(img);
                }else{
                    theMsg.appendChild(img);
                    theMsg.appendChild(msg);
                }
                this.appendMsg(theMsg);
            },
            appendMsg(msg){
                this.$refs.js_messages.append(msg);
                this.$refs.js_messages.scrollTop =this.$refs.js_messages.scrollHeight;
            },
            joinCancel(){
                this.waitRoom.close()  
            },
            hand(){
                if(this.handUp){
                    this.handUp = false;
                }else{
                    this.handUp = true;
                    this.createMsg('/images/bubble.png',this.user.id,this.user.roll_flag,"🖐",'studentMsg');
                    this.room.send(this.jsonMsg(this.user.id,"🖐"));
                }
                this.room.send(this.jsonCmd(this.user.id,2));
            },
            jsonMsg(id,text){
                return {"type":200,"id":id,"roll_flag":this.user.roll_flag,"msg":text};
            },
            jsonCmd(id,cmd){
                return {"type":100,"id":id,"cmd":cmd,"value":this.handUp};
            }
        },
        mounted(){
            window.__SKYWAY_KEY__ = '1f3076ca-36ad-4d4f-87d7-23b05a093ca3';
            
            var data = Math.floor(Math.random()*10).toString() + this.user.roll_flag + this.user.id ;

            this.peer = new Peer(data,{
                key: window.__SKYWAY_KEY__,
                debug: 3,
            });

            this.peer.on('open',this.init);

            this.peer.on('error',(error)=>{
                console.log(`${error.type}: ${error.message}`);
                this.$refs.js_messages.append(this.addElement('div',"画面を閉じて、再起動してください。",'systemMsg',''));
            });
        },
    }
</script>

<style lang="css">
.show-leave-active{
    transition: all 1.5s;
}
/* .show-leave-to{
    opacity: 0;
} */
*{
    margin: 0;
    padding: 0;
}
.body{
    height: 100%;
    display: grid;
    grid-template-rows: 50px 95vh;
    position: fixed;
    width: 100%;
    grid-template-columns: 55.1% 44.9%;
}
div{
    display: block;
    outline: none;
}
.header{
    grid-row: 1/2;
    grid-column: 1/3;
    width: 100%;
    background-color: #D8D8D8;
    text-align: center;
}
h1{
    font-size: revert;
    height: 100%;
}
.left{
    grid-row: 2/3;
    grid-column: 1/2;
    background-color: black;
    min-width: 500px;
    height: 100%;
}
.right{
    grid-column: 2/3;
    grid-row: 2/3;
    height: 100%;
}
.teacher-video{
    height: 61%;
    position: relative;
}
.teacher-video video{
    height: 100%;
    width: 100%;
}
.my-video{
    height: 39%;
    position: relative;
}
.backG{
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 80%;
    background-image: url(/images/user.png);
    background-repeat: no-repeat;
    background-position: center;
    background-size: 30%;
}
.my-video video{
    height: 70%;
    width: 100%;
}
.tools{
    display: flex;
    justify-content: center;
    height: 30%;
    align-items: center;
}
.t{
    margin: 0 3%;
    border-radius: 50%;
    background-size: 30px;
    background-repeat: no-repeat;
    background-position: center;
    background-color: white;
    /* background-blend-mode:lighten; */ 
    width: 50px;
    height: 50px;
    min-height: 50px;
    min-width: 50px;
    text-align: center;
    line-height: 50px;
    cursor: pointer;
}
.t1{
    background-image: url(/images/video-camera.svg);
}
.t1 span{
    transform: rotate(40deg);
    content: '';
    position: relative;
    top: 3px;
    width: 1.5px;
    height: 46px;
    background-color: white;
    display: inline-block;
}
.t2{
    background-image: url(/images/microphone.svg);
}
.active{
    background-color: rgba(220,220,220,0.3);
}
.t2 span{
    transform: rotate(40deg);
    content: '';
    position: relative;
    top: 3px;
    width: 1.5px;
    height: 46px;
    background-color: white;
    display: inline-block;
}
.t3{
    background-image: url(/images/screen.svg);
}
.t4{
    background-image: url(/images/open-hand.svg);
}
.t5{
    background-image: url(/images/out.svg);
}
.t:hover{
    background-color: rgba(105,105,105,0.8);
}
.chat-area{
    position: relative;
    height: 100%;
}
.messages{
   height: 86%;
}
.messages_list{
    height: 100%;
    overflow-y: overlay;
}
::-webkit-scrollbar {
    width: 8px;
}
::-webkit-scrollbar-track {
    border-radius: 10px;
}
::-webkit-scrollbar-thumb {
    background: #999999;
    border-radius: 12px;
    box-shadow: inset 0 15px 0 0 white,
                inset 0 -15px 0 0 white;
}
::-webkit-scrollbar-thumb:hover {
    background: #707070; 
}
.input-area{
    position: absolute;
    bottom: 4%;
    width: 100%;
}
.input-area-inner{
    margin: 0 20px;
    border: 1px solid #111;
    position: relative;
    border-radius: 5px;
    background-color: white;
}
.my-message{
    padding: 5px 10px;
    color: #555;
    background-color: #fff;
    max-height: 120px;
    overflow: auto;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    margin: 5px 5px;
    font-size: 18px;
}
.my-message:empty::before{
    color:#ADADAD;
    content:attr(placeholder);
    font-size: 18px;
}
.options{
    height: 40px;
    background-color:#D8D8D8;
    border-top: 1px solid #999;
    border-bottom-left-radius: 5px;
    border-bottom-right-radius: 5px;
}
.js-send-trigger{
    height: 30px;
    width: 60px;
    background: none;
    border: 1px solid #111;
    border-radius: 8px;
    outline: none;
    cursor: pointer;
    position: absolute;
    right: 10px;
    bottom: 5px;
}
.js-send-trigger:hover{
    background-color: gray;
}
.systemMsg{
    text-align: center;
    font-weight: bolder;
    font-size: 12px;
    color: gray;
    margin: 10px 0;
    animation-name: animateshow;
    animation-duration: 0.4s;
}
@keyframes animateshow{
    from {
        opacity:0
    }
    to {
        opacity:1
    }
}
.studentMsg,.teacherMsg{
    display: flex;
    margin: 10px 20px;
    padding: 5px 0;
    animation-name: animateshow;
    animation-duration: 0.4s;
}
.msg{
    position: relative;
    top: -12px;
}
.msgImg{
    background-size: contain;
    background-repeat: no-repeat;
    width: 50px;
    height: 50px;
}
.studentMsg .msgImg{
    margin: 0 10px 0 0;
}
.teacherMsg .msgImg{
    margin: 0 0 0 10px;
}
.msgHead {
    display: flex;
}
.teacherMsg .msgHead{
    direction: rtl;
}
.msgName{
    font-size: 15px;
}
.msgTime {
    margin: auto 10PX;
    font-size: 10px;
    color: gray;
}
.msgContent{
    background-color: #f9ead2ff;
    border-radius: 8px;
    padding: 5px;
    margin: 0 10px;
    max-width: 344px;
    word-wrap:break-word;
    word-break:break-all;
    overflow: hidden;
    width: fit-content;
}
.studentMsg .msgContent{
    margin: 0 auto 0 0;
}
.teacherMsg .msgContent{
    margin: 0 0 0 auto;
}
.teacherMsg{
    justify-content: flex-end;
}
.teacherMsg .msgContent{
    background-color: greenyellow;
}
/* dialog */
.dialog{
    display: flex;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
}
.dialogBackG{
    background-color: rgba(0,0,0,0.85);
}
.modal-content {
    position: relative;
    background-color: #fefefe;
    margin: auto;
    padding: 0;
    border: 1px solid #888;
    width: 410px;
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
    animation-name: animatetop;
    animation-duration: 0.4s;
    border-radius: 10px;
}
.sys{
    width: auto;
}
@keyframes animatetop {
    from {
        top:-300px; 
        opacity:0}

    to {
        top:0; 
        opacity:1
    }
}
.close {
    color: white;
    float: right;
    font-size: 30px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
h2{
    font-size: revert;
    font-weight: bolder;
}
.modal-header {
    padding: 2px 16px;
    background-color: #7d93baff;
    color: #404b73ff;
    line-height: 38px;
    border-top-left-radius: 9px;
    border-top-right-radius: 9px;
}
.modal-body {
    padding: 10px 16px;
}
.content-text{
    margin: 5px 30px;
    font-size: 21px;
    font-weight: bold;
    display: flex;
    line-height: 50px;
}
.warring{
    margin: 0 35px;
    width: 50px;
    height: 50px;
    background-image: url(/images/warning.svg);
}
.okButton{
    outline: none;
    background-color:#404b73ff;
    color: #7d93baff;
    width: fit-content;
    font-size: 21px;
    border-radius: 12px;
    margin: auto;
    text-align: center;
    cursor: pointer;
    padding: 3px 10px;
}
.okButton:hover{
    background-color: #3b404f;
}

@keyframes ldio-5ekurv8jzaa {
  0% { opacity: 1 }
  100% { opacity: 0 }
}
.ldio-5ekurv8jzaa div {
  left: 44.5px;
  top: 3.5px;
  position: absolute;
  animation: ldio-5ekurv8jzaa linear 0.6666666666666666s infinite;
  background: #93dbe9;
  width: 11px;
  height: 33px;
  border-radius: 5.5px / 7.92px;
  transform-origin: 5.5px 46.5px;
}.ldio-5ekurv8jzaa div:nth-child(1) {
  transform: rotate(0deg);
  animation-delay: -0.5833333333333334s;
  background: #93dbe9;
}.ldio-5ekurv8jzaa div:nth-child(2) {
  transform: rotate(45deg);
  animation-delay: -0.5s;
  background: #689cc5;
}.ldio-5ekurv8jzaa div:nth-child(3) {
  transform: rotate(90deg);
  animation-delay: -0.4166666666666667s;
  background: #5e6fa3;
}.ldio-5ekurv8jzaa div:nth-child(4) {
  transform: rotate(135deg);
  animation-delay: -0.3333333333333333s;
  background: #3b4368;
}.ldio-5ekurv8jzaa div:nth-child(5) {
  transform: rotate(180deg);
  animation-delay: -0.25s;
  background: #191d3a;
}.ldio-5ekurv8jzaa div:nth-child(6) {
  transform: rotate(225deg);
  animation-delay: -0.16666666666666666s;
  background: #d9dbee;
}.ldio-5ekurv8jzaa div:nth-child(7) {
  transform: rotate(270deg);
  animation-delay: -0.08333333333333333s;
  background: #b3b7e2;
}.ldio-5ekurv8jzaa div:nth-child(8) {
  transform: rotate(315deg);
  animation-delay: 0s;
  background: #93dbe9;
}
.loadingio-spinner-spinner-nglqv2ndcch {
  width: 50px;
  height: 50px;
  display: inline-block;
  overflow: hidden;
  background: none;
  margin-right: 15px;
}
.ldio-5ekurv8jzaa {
  width: 100%;
  height: 100%;
  position: relative;
  transform: translateZ(0) scale(0.5);
  backface-visibility: hidden;
  transform-origin: 0 0; /* see note above */
}
.ldio-5ekurv8jzaa div { 
    box-sizing: content-box; 
}
</style>