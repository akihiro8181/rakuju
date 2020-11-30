<template>
    <div class="body" @click='onClickVideo'>
        <div class="header">
            <h1>授業名：{{$page.user.school.name}}</h1>
        </div>
        <div class="left">
            <transition name="list">
                <div class="lab">
                    <div class="showList" :class="{toRight:showStudentList}" @click="showList">
                        <i class="icon-menu" :class="{close:showStudentList}"></i>
                    </div>
                    <div v-show="showStudentList" class="studentRoom">
                        <div class="modal-header">学生一覧</div>
                        <div class="studentList">
                            <ol class="listContent">
                                <transition name="show">
                                    <li v-for="(value,key) in waitRoomStudentList" :key="key">
                                        {{key+1}}．ID:{{value.id}}
                                        <div class="listButton">
                                            <div class="ok divButton" @click="joinOk(value.id)">承認</div>
                                            <div class="no divButton" @click="joinNo(value.id)">拒否</div>
                                        </div>
                                    </li>
                                </transition>
                            </ol>
                        </div>
                    </div>
                </div>
            </transition>
            <div class="my-video">
                <div v-if="showBackG" class="backG"></div>
                <video :srcObject.prop="videoStream" ref="localVideo" autoplay></video>
                <div class="tools">
                    <div :class="{active:videoEnable}" class="t1 t" ref="tVideo" @click="switchMedia(0)"><span v-if="videoEnable"></span></div>
                    <div :class="{active:audioEnable}" class="t2 t" ref="tMicrophone" @click="switchMedia(1)"><span v-if="audioEnable"></span></div>
                    <div :class="{active:share}" class="t3 t" ref="tScreenShare" @click="onScreenShare()"><span></span></div>
                    <div :class="{active:diaglogFlag}" class="t5 t" ref="tLeave" @click="showLeaveDialog()"><span></span></div>
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
            <div class="waitRoom">
                <div class="waitRoomHead">待ち合わせ室</div>
                <hr>
                <div class="waitList">
                    <ol class="waitListContent">
                        <transition name="show">
                            <li v-for="(value,key) in waitRoomStudentList" :key="key">
                                {{key+1}}．ID:{{value.id}}
                                <div class="listButton">
                                    <div class="ok divButton" @click="joinOk(value.id)">承認</div>
                                    <div class="no divButton" @click="joinNo(value.id)">拒否</div>
                                </div>
                            </li>
                        </transition>
                    </ol>
                </div>
            </div>
        </div>
        <!-- dialogBox -->
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
    </div>
</template>

<script>
    export default {
        props: ['user'],
        data(){
            return{
                localStream:undefined,           //カメラ用
                videoStream: undefined,          //画面上に表示するストリーム
                share:false,                     //share flag
                CLASS_CODE:'123',                //授業Code
                roomStudentList:[],　　//学生のカメラストリームリスト
                peer:undefined,                //Peerのオブジェクト
                room:undefined,               //Room のオブジェクト
                diaglogFlag:false,           //退出dialog FLag 
                waitRoom:undefined,            //待ち室
                waitRoomStudentList: [],      //待ち室のリスト
                showBackG:false,
                noMedia:false,
                videoEnable: false,
                audioEnable: false,
                showStudentList:false,
            }
        },
        methods: {
            init(){
                console.log("peer.open");

                this.waitRoom = this.peer.joinRoom(`waitRoom${this.CLASS_CODE}`,{
                    mode:'sfu',
                });

                this.room = this.peer.joinRoom(this.CLASS_CODE,{
                    mode: 'sfu',
                    stream: this.videoStream,
                });
                
                if(this.room!=undefined){
                    // roomt on lisener
                    this.room.once('open', () => {
                        this.appendMsg(this.addElement('div',"授業開設しました。",'systemMsg',''));
                    });

                    this.room.on('peerJoin', peerId => {
                        this.appendMsg(this.addElement('div',`=== ${peerId.substring(3)}さんが入りました ===`,'systemMsg',''));
                    });
                
                    this.room.on('stream', async stream => {
                        // streamList.set(stream.peerId,stream);
                        // console.log(streamList);
                    });
            
                    this.room.on('data', ({ data, src }) => {
                        if(src.substring(1,3)==='te'){
                            this.createMsg('/images/bubble.png',src,data,'teacherMsg');
                        }else{
                            this.createMsg('/images/bubble.png',src,data,'studentMsg');
                        }
                    });
            
                    this.room.on('peerLeave', peerId => {
                        this.appendMsg(this.addElement('div',`=== ${peerId.substring(3)}さんが退室しました ===\n`,'systemMsg',''));
                    });
            
                    this.room.once('close', () => {
                        window.close();
                    });
                }

                if(this.waitRoom!=undefined){
                    this.waitRoom.on('data',({data,src}) => {
                        this.waitRoomStudentList.push({"id":src.substring(3)});
                    });
                    this.waitRoom.on('peerLeave',peerId =>{
                        this.deleteJoinList(peerId.substring(3))
                    });
                }
            },
            switchMedia(media){
                if(this.noMedia){
                    return
                }
                //video
                if(media===0){
                    if(this.videoStream.getVideoTracks()[0].enabled){
                        //close the video
                        this.videoStream.getVideoTracks().forEach( track => (track.enabled = false));
                        this.showBackG = true;
                        this.videoEnable = true;
                    }else{
                        //open the video
                        // backG.style.display = 'none';
                        this.videoStream.getVideoTracks().forEach( track => (track.enabled = true));
                        this.showBackG = false;
                        this.videoEnable = false;
                    }
                }
                //mic
                if(media===1){
                    if(this.videoStream.getAudioTracks()[0].enabled){
                        this.audioEnable = true;
                        this.videoStream.getAudioTracks().forEach((track) => (track.enabled = false));
                    }else{
                        this.audioEnable = false;
                        this.videoStream.getAudioTracks().forEach( track => (track.enabled = true));
                    }
                }
            },
            onScreenShare(){
                if(this.noMedia){
                    return
                }
                if(this.share){
                    this.videoStream.getVideoTracks()[0].stop();
                    this.showLocalVideo();
                }else{
                    navigator.mediaDevices.getDisplayMedia({ 
                        video: true,
                    }).then(stream => {
                        this.share=true;
                        if(!this.videoStream.getVideoTracks()[0].enabled){
                            this.videoStream=stream;
                            this.videoStream.getVideoTracks().forEach( track => (track.enabled = false));
                        }else{
                            this.videoStream=stream;
                        }
                        stream.getVideoTracks()[0].addEventListener('ended', () => {this.showLocalVideo()});
                        this.room.replaceStream(this.videoStream);
                    }).catch(e=>{console.log("err:"+e)});
                }
            },
            showLocalVideo(){
                this.share=false;
                if(!this.videoStream.getVideoTracks()[0].enabled){
                    this.videoStream = this.localStream;
                    this.videoStream.getVideoTracks().forEach( track => (track.enabled = false));
                }else{
                    this.videoStream = this.localStream;
                }
                this.room.replaceStream(this.videoStream);
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
                this.room.send(text);
                this.createMsg('/images/bubble.png',this.peer.id,text,'teacherMsg');
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
            createMsg(imgUrl,name,msgText,msgClass){
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
                msgName.innerText = name.substring(3);
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
                if(name.substring(1,3) == 'te'){
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
            joinOk(id){
                this.deleteJoinList(id);
                this.waitRoom.send({id:id,res:"ok"});
            },
            joinNo(id){
                this.deleteJoinList(id);
                this.waitRoom.send(`no${id}`);
            },
            deleteJoinList(id){
                this.waitRoomStudentList.forEach((item,index)=>{
                    if(item.id==id){
                        this.waitRoomStudentList.splice(index, 1);
                    }
                })
            },
            showList(){
                if(this.showStudentList){
                    this.showStudentList = false
                }else[
                    this.showStudentList = true
                ]
            }
        },
        mounted(){
            window.__SKYWAY_KEY__ = '1f3076ca-36ad-4d4f-87d7-23b05a093ca3';
            
            var peerID = Math.floor(Math.random()*10).toString() + this.user.roll_flag + this.user.id ;

            this.peer = new Peer(peerID,{
                key: window.__SKYWAY_KEY__,
                debug: 3,
            });

            navigator.mediaDevices.getUserMedia({
                video: true,
                audio: true
            }).then(stream => {
                this.localStream = stream;
                this.videoStream = this.localStream;
            }).catch(e=>{
                console.log(`${e.type}:${e}`);
                this.showBackG = true;
                this.noMedia = true;
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
.icon-menu,
.icon-menu::before,
.icon-menu::after{
    width: 34px;
    height: 6px;
    background-color: black;
    display: block;
    transition: all 0.5s;
    border-radius: 10px;
}
.icon-menu {
    position: relative;
}
.icon-menu::before,
.icon-menu::after {
    position: absolute;
    content: '';
}
.icon-menu::before {
    top: -12px;
}
.icon-menu::after {
    top: 12px;
}
.close::before{
    top: 0;
    transform: rotate(90deg);
}
.close::after{
    top: 0;
}
.close{
    transform: rotate(225deg);
}
.show-enter-active, .show-leave-active {
    transition: all 1s;
}
.show-enter, .show-leave-to{
    opacity: 0;
}
.list-enter-active, .list-leave-active{
    transition: all 0.8s;
}
.list-enter, .list-leave-to{
    transform: translateX(-100%);
}
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
    grid-template-columns: 65% 35%;
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
    display: flex;
    min-width: 500px;
}
.right{
    grid-column: 2/3;
    grid-row: 2/3;
    height: 100%;
}
.showList{
    position: absolute;
    z-index: 1;
    /* background-image: url(/images/list.svg); */
    background-size: 35px;
    background-repeat: no-repeat;
    background-position: center;
    background-color: #7d93baff;
    width: 50px;
    height: 50px;
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
}
.toRight{
    transform: translateX(350px);
}
.studentRoom{
    height: 100%;
    width: 350px;
    position: absolute;
    background-color:  #7d93baff;
    z-index: 1;
    padding: 5px;
    /* border: 1px solid #111; */
}
.studentList{
    overflow: auto;
    height: 100%;
    /* padding: 50px; */
    /* margin: 10px; */
    /* width: 100%; */
    /* position: relative; */
    padding-left: 50px;
    margin-top: 10px;
    margin: 0 10px;
    border-top: 1px solid;
}
.studentList ol{
    font-size: 20px;
}
.studentList ol li{
    margin: 10px 0;
}
.my-video{
    position: relative;
    display: flex;
    width: 100%;
}
.backG{
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: url(/images/user.png);
    background-repeat: no-repeat;
    background-position: center;
    background-size: 50%;
}
.my-video video{
    width: 100%;
}
.tools{
    display: flex;
    width: inherit;
    position: absolute;
    bottom: 3%;
    justify-content: center;
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
    position: absolute;
    top: 3px;
    width: 1.5px;
    height: 46px;
    background-color: white;
    transition: tran;
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
    position: absolute;
    top: 3px;
    width: 1.5px;
    height: 46px;
    background-color: white;
    transition: tran;
    display: inline-block;
}
.t3{
    background-image: url(/images/screen.svg);
}
.t5{
    background-image: url(/images/out.svg);
}
.t:hover{
    background-color: rgba(105,105,105,0.8);
}
.chat-area{
    position: relative;
    height: 65%;
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
.listContent::-webkit-scrollbar-thumb{
    background: #404b73ff;
    border-radius: 12px;
    box-shadow: inset 0 15px 0 0 #7d93baff,
                inset 0 -15px 0 0 #7d93baff;
}

::-webkit-scrollbar-thumb:hover {
    background: #707070; 
}

.listContent::-webkit-scrollbar-thumb:hover{
    background: wheat; 
}

.input-area{
    width: 100%;
    position: absolute;
    bottom: 0;
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
    padding: 8px 16px;
}
.content-text{
    margin: 5px;
    font-size: 21px;
    font-weight: bold;
    display: flex;
    line-height: 50px;
}
.warring{
    margin: 0 40px;
    width: 50px;
    height: 50px;
    background-image: url(/images/warning.svg);
}
.okButton{
    outline: none;
    background-color:#404b73ff;
    color: #7d93baff;
    width: 70px;
    height: 35px;
    line-height: 35px;
    font-size: 21px;
    border-radius: 12px;
    margin: auto;
    text-align: center;
    cursor: pointer;
}
.okButton:hover{
    background-color: #3b404f;
}
.waitRoom {
    height: 33%;
    border: black 1px solid;
    margin: 2% 5px;
    border-radius: 15px;
    background-color: powderblue;
}
.waitRoomHead {
    text-align: center;
    font-size: 30px;
    font-weight: bolder;
    height: 15%;
    margin: 1% 0;
}
.waitRoom hr{
    border-top-width: 3px;
    border-color: darkcyan;
    margin: 0 10px;
}
.waitList {
    height: 80%;
    margin: 0 5px;
}
.waitListContent {
    height: 100%;
    padding: 5px 20px;
    overflow: auto;
    font-size: 24px;
}
.waitListContent::-webkit-scrollbar-thumb{
    background: #404b73ff;
    border-radius: 12px;
    box-shadow: inset 0 10px 0 0 powderblue,
                inset 0 -10px 0 0 powderblue;
}
.waitListContent li{
    margin: 5px;
    display: flex;
}
.listButton {
    display: flex;
    position: absolute;
    right: 15px;
}
.divButton{
    margin: 0px 15px;
    padding: 0 10px;
    cursor: pointer;
}
.ok{
    background-color: green;
    border-radius: 12px;
}
.ok:hover{
    background-color: lawngreen;
}
.no{
    background-color: darkred;
    border-radius: 12px;
}
.no:hover{
    background-color: red;
}
</style>