<template>
    <div class="body" @click='onClickVideo'>
        <div class="header">
            <h1>授業名：{{$page.user.school.name}}</h1>
        </div>
        <div class="left">
            <div class="teacher-video">
                <div v-if="showTBG" class="backTBG"></div>
                <video :srcObject.prop="teacherVideoStream" ref="tVideo" autoplay></video>
            </div>
            <div class="my-video">
                <div v-if="showBackG" class="backG"></div>
                <video :srcObject.prop="localVideoStream" muted autoplay></video>
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
                        <div class="preview-wrapper">
                            <div :class="{preview:selectedFiles.length>0}" ref="preview" ondragstart="return false;">
                                <div v-for="(value,key) in selectedFiles" :key="key" class="fileView">
                                    <div v-if="value.type=='1'" class="imgView">
                                        <img :src="value.fileUrl" @click="showBigImg(value.fileUrl)">
                                    </div>
                                    <div v-if="value.type=='0'" class="noImgView">
                                        <img src="/images/file.svg">
                                        <div class="fileName">{{value.fileName}}</div>
                                    </div>
                                    <div class="deleteFile" @click="deleteFile(key)"></div>
                                </div>
                            </div>
                        </div>
                        <div class="options">
                            <div class="inputImg opentionIcon" @click="showFileSelectWindow(1)">
                                <input style='display:none' type="file" ref="inputImg" @change="handleFileSelect(1)" accept=".gif,.jpg,.jpeg,.png,.bmp,.JPG" multiple>
                            </div>
                            <div class="inputFile opentionIcon" @click="showFileSelectWindow(0)">
                                <input style='display:none' type="file" ref="inputFile" @change="handleFileSelect(0)" multiple>
                            </div>
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
        <!-- 退出されたダイアログ -->
        <div class="dialog" v-if="outDialog">
            <div class="modal-content sys">
                <div class="modal-header">
                    <h2>ラクジュ</h2>
                </div>
                <div class="modal-body">
                    <div class="content-text">
                        {{outMsg}}
                    </div>
                    <div class="okButton" @click="dialogOk">OK</div>
                </div>
            </div>
        </div>
        <div class="dialog" v-if="classEnd">
            <div class="modal-content sys">
                <div class="modal-header">
                    <h2>ラクジュ</h2>
                </div>
                <div class="modal-body">
                    <div class="content-text">
                        {{outMsg}}
                    </div>
                    <div class="okButton" @click="dialogOk">OK</div>
                </div>
            </div>
        </div>
        <div v-if="onShowBigImg" ref="leaveDialog" class="dialog">
            <div class="img-content">
                <div class="img-fa">
                    <img ref="bigImg" :src="showImgUrl">
                </div>
            </div>
        </div>
        <div class="videoList">
            <div class="modal-content">
                <div class="video-List">
                    <div class="video-List-inner">
                        <div class="video-Item" v-for="(value,key) in classMete" :key="key">
                            <div class="video-Item-inner">
                                <video :srcObject.prop="value.stream" autoplay></video>
                            </div>
                        </div>
                    </div>
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
                classEnd:false,
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
                showBackG:true,
                showTBG:true,
                videoEnable: false,
                audioEnable: false,
                handUp:false,
                outDialog:false,
                noVideo:false,
                noAudio:false,
                selectedFiles:[],
                onShowBigImg:false,
                showImgUrl:'',
                reJoinList:[],
                classMete:[],
                outMsg:"強制退出されました。",
            }
        },
        methods: {
            getName(id){
                var name ;
                this.classMete.forEach((item)=>{
                    if(item.id==id){
                        name = item.name;
                    }
                })
                return name;
            },
            init(){
                this.waitRoom = this.peer.joinRoom(`waitRoom${this.CLASS_CODE}`,{
                    mode:'sfu',
                });
                
                if(this.waitRoom!=undefined){
                    this.waitRoom.once('open', () => {
                        this.waitRoom.send({"id":this.user.id,"name":this.user.name,"status":"wait","roll_flag":this.user.roll_flag})
                    });
                    this.waitRoom.on('peerJoin', peerId => {
                        if(peerId.substring(1,3)=="te"){
                            this.waitRoom.send({"id":this.user.id,"name":this.user.name,"status":"wait","roll_flag":this.user.roll_flag})
                        }
                    });
                    this.waitRoom.on('data', ({ data, src }) => {
                        if(data.roll_flag==="st"){
                            return
                        }
                        if(data.id==this.user.id){
                            if(data.res=="ok"){
                                this.$refs.dialogContent.innerHTML = "<div class='content-text'>承認されました</div>";
                                this.delay(1200);
                            }else{
                                this.joinDialog = true
                            }
                        }
                    });
                    this.waitRoom.once('close', () => {
                        if(this.waitDialog){
                            window.close()
                        }else{
                            navigator.mediaDevices.getUserMedia({
                                video:{ width: 1024, height: 760 },
                                audio: true,
                            }).then(stream => {
                                this.localStream = stream;
                                this.localVideoStream = stream;
                                this.showBackG = false;
                                this.joinClassRoom();
                            }).catch(e=>{
                                console.log(`${e.type}:${e}`);
                                navigator.mediaDevices.getUserMedia({
                                    video:{ width: 1024, height: 760 }
                                }).then(stream => {
                                    this.localStream = stream;
                                    this.localVideoStream = stream;
                                    this.showBackG = false;
                                    this.noAudio = true;
                                    this.$refs.js_messages.append(this.addElement('div',"マイクが取得できませんでした。",'systemMsg',''));
                                    this.joinClassRoom();
                                }).catch(e=>{
                                    navigator.mediaDevices.getUserMedia({
                                        audio:true,
                                    }).then(stream => {
                                        this.localStream = stream;
                                        this.videoStream = stream;
                                        this.noVideo = true;
                                        // console.log(this.localStream.getAudioTracks()[0]);
                                        this.$refs.js_messages.append(this.addElement('div',"カメラが取得できませんでした。",'systemMsg',''));
                                        this.joinClassRoom();
                                    }).catch(e=>{
                                        this.classEnd = true;
                                        this.outMsg = "授業開設失敗しました、カメラとマイクどちらが有効にしてください";
                                        // this.$refs.js_messages.append(this.addElement('div',"カメラとマイクが取得できませんでした,どちらが有効にしてください",'systemMsg',''));
                                    });
                                });
                            });
                        }
                    });
                }
            },
            joinClassRoom(){
                this.room = this.peer.joinRoom(this.CLASS_CODE,{
                    mode: 'sfu',
                    stream: this.localVideoStream,
                });

                if(this.room!=undefined){
                    // roomt on lisener
                    this.room.once('open', () => {
                        this.appendMsg(this.addElement('div',`${this.CLASS_CODE}に参加しました。`,'systemMsg',''));
                        this.room.send({"type":100,"name":this.user.name,"id":this.user.id,"cmd":99});
                        this.room.send({"type":100,"name":this.user.name,"id":this.user.id,"handUp":this.handUp,"cmd":9,"video":!this.videoEnable,"audio":!this.audioEnable,"noVideo":this.noVideo,"noAudio":this.noAudio,"onSharing":this.share,"roll_flag":this.user.roll_flag});
                    });

                    this.room.on('peerJoin', peerId => {
                        if(this.reJoinList.indexOf(peerId.substring(3))!=-1){
                            this.reJoinList.splice(this.reJoinList.indexOf(peerId.substring(3)),1);
                            return
                        }
                        // console.log(peerId)
                        if(peerId.substring(1,3)=="te"){
                            this.room.send({"type":100,"name":this.user.name,"id":this.user.id,"handUp":this.handUp,"cmd":9,"video":!this.videoEnable,"audio":!this.audioEnable,"noVideo":this.noVideo,"noAudio":this.noAudio,"onSharing":this.share,"roll_flag":this.user.roll_flag});
                        }else{
                            this.room.send({"type":100,"name":this.user.name,"id":this.user.id,"cmd":99});
                        }
                    });
                
                    this.room.on('stream', async stream => {
                        if(stream.peerId.substring(1,3)=="te"){
                            console.log(stream.getVideoTracks());
                            this.teacherVideoStream = stream;
                            if(this.teacherVideoStream.getVideoTracks()[0]!=undefined){
                                this.showTBG = false;
                            }
                        }else{
                            this.classMete.forEach((item)=>{
                                if(item.id==stream.peerId.substring(3)){
                                    item.stream = stream;
                                }
                            });
                        }
                    });
            
                    this.room.on('data', ({ data, src }) => {
                        console.log(data);
                        if(data.type==100){
                            if(data.roll_flag==="st"){
                                return
                            }
                            if(data.cmd == 3){
                                this.showTBG = data.value;
                            }
                            if(data.cmd == 8){
                                this.reJoinList.push(data.id.toString());
                                this.room.send({"type":100,"cmd":8,"value":true,"roll_flag":this.user.roll_flag});
                            }
                            if(data.cmd == 33){
                                this.outMsg = "授業を終了しました。"
                                this.room.close();
                                this.classEnd = true;
                            }
                            if(data.cmd == 99 || data.cmd==9){
                                var itemSt = this.classMete.find((item)=>{
                                    item.id == data.id;
                                })
                                if(itemSt==undefined){
                                    this.classMete.push({"id":data.id,"name":data.name,"stream":""});
                                }
                            }
                            if(data.id == this.user.id){
                                switch(data.cmd) {
                                    case 0:
                                        this.outDialog = true;
                                        this.room.close();
                                        break;
                                    case 1:
                                        this.audioEnable = !data.value;
                                        this.localVideoStream.getAudioTracks().forEach( track => (track.enabled = data.value));
                                        break;
                                    case 2:
                                        this.handUp = data.value;
                                        break;
                                } 
                            }
                        }
                        if(data.type==200){
                            if(data.roll_flag=='te'){
                                this.createMsg('/images/bubble.png',data.name,data.roll_flag,data.msg,'teacherMsg',200);
                            }else{
                                this.createMsg('/images/bubble.png',this.getName(data.id),data.roll_flag,data.msg,'studentMsg',200);
                            }
                        }
                        if(data.type==1 || data.type==0){
                            if(data.roll_flag=='te'){
                                this.createMsg('/images/bubble.png',data.name,data.roll_flag,data,'teacherMsg',100);
                            }else{
                                this.createMsg('/images/bubble.png',this.getName(data.id),data.roll_flag,data,'studentMsg',100);
                            }
                        }
                    });
            
                    this.room.on('peerLeave', peerId => {
                        if(peerId.substring(1,3)=="te"){
                            this.showTBG = true;
                        }else{
                            if(this.reJoinList.indexOf(peerId.substring(3))!=-1){
                                return
                            }
                            this.appendMsg(this.addElement('div',`${this.getName(peerId.substring(3))}さんが退出しました。`,'systemMsg',''));
                            this.classMete.forEach((item,index)=>{
                                if(item.id==peerId.substring(3)){
                                    this.classMete[index].splice(index,1);
                                }
                            });
                        }
                    });
            
                    // this.room.once('close', () => {
                    // });
                }
            },
            sleep(ms) {
                return new Promise(resolve => setTimeout(resolve, ms));
            },
            async delay(time){
                await this.sleep(time);
                this.waitDialog=false;
                this.waitRoom.close();
            },
            switchMedia(media){
                //video
                if(media===0){
                    if(this.noVideo){
                        return
                    }
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
                    if(this.noAudio){
                        return
                    }
                    if(this.localVideoStream.getAudioTracks()[0].enabled){
                        this.audioEnable = true;
                        this.localVideoStream.getAudioTracks().forEach((track) => (track.enabled = false));
                        this.room.send({"type":100,"id":this.user.id,"cmd":1,"value":false,"roll_flag":this.user.roll_flag});
                    }else{
                        this.audioEnable = false;
                        this.localVideoStream.getAudioTracks().forEach( track => (track.enabled = true));
                        this.room.send({"type":100,"id":this.user.id,"cmd":1,"value":true,"roll_flag":this.user.roll_flag});
                    }
                }
            },
            onScreenShare(){
                if(this.noVideo){
                    return
                }
                if(this.share){
                    this.localVideoStream.getVideoTracks()[0].stop();
                    if(this.localVideoStream.getAudioTracks()[0]!=undefined){
                        this.localVideoStream.getAudioTracks()[0].stop();
                    }
                    this.showLocalVideo();
                }else{
                    navigator.mediaDevices.getDisplayMedia({ 
                        video: { width: 1024, height: 760 },
                    }).then(stream => {
                        this.share=true;
                        this.showBackG = false;
                        if(this.noVideo){
                            this.room.send({"type":100,"id":this.user.id,"value":true,"cmd":10,"roll_flag":this.user.roll_flag});
                        }
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
                if(this.noVideo){
                    this.showBackG = true;
                    this.room.send({"type":100,"id":this.user.id,"value":false,"cmd":10,"roll_flag":this.user.roll_flag});
                }
                if(!this.localVideoStream.getVideoTracks()[0].enabled){
                    this.localVideoStream = this.localStream;
                    this.localVideoStream.getVideoTracks().forEach( track => (track.enabled = false));
                }else{
                    this.localVideoStream = this.localStream;
                    this.localVideoStream.getVideoTracks().forEach( track => (track.enabled = true));
                }
                this.room.replaceStream(this.localVideoStream);
            },
            onClickSend(){
                if(!this.peer.open){
                    return;
                }
                this.$refs.localText.focus();
                if(this.$refs.localText.innerText != ''){
                    var text = this.$refs.localText.innerText;
                    this.room.send(this.jsonMsg(this.user.id,text));
                    this.createMsg('/images/bubble.png',this.user.name,this.user.roll_flag,text,'studentMsg',200);
                    this.$refs.localText.innerText = '';
                }
                if(this.selectedFiles.length > 0){
                    this.selectedFiles.forEach((item)=>{
                        this.room.send(item);
                        this.createMsg('/images/bubble.png',this.user.name,this.user.roll_flag,item,'studentMsg',100);
                    })
                    this.selectedFiles=[]
                }
            },
            showLeaveDialog(){
                this.diaglogFlag = true;
            },
            dialogOk(){
                window.close();
            },
            onClickVideo:function(e){
                if (e.target == this.$refs.leaveDialog || e.target == this.$refs.js_Close_Dialog || e.target == this.$refs.bigImg) {
                    this.diaglogFlag = false;
                    this.onShowBigImg = false;
                }
            },
            onSwitchDialogFlag(){
                return this.diaglogFlag;
            },
            addElement(elementName,content,className,elementId){
                var msg = document.createElement(elementName);
                if(content!=""){
                    msg.innerText=content;
                }
                msg.innerText = content;
                if(className!=''){
                    msg.className = className;
                }
                if(elementId!=''){
                    msg.ref = elementId;
                }
                return msg;
            },
            createMsg(imgUrl,name,roll_flag,msgText,msgClass,msgType){
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
                var msgContent;
                if(msgType==200){
                    msgContent = this.addElement('div',msgText,'msgContent','msgContent');
                }else{
                    msgContent = this.addElement('div',"",'msgContent','msgContent');
                    if(msgText.type==1){
                        var imgDoc = document.createElement('img');
                        imgDoc.src = msgText.fileUrl;
                        imgDoc.addEventListener("click", ()=>{
                                this.showBigImg(msgText.fileUrl)
                            }
                        );
                        msgContent.appendChild(imgDoc);   
                    }else{
                        var msgFileView = document.createElement('div');
                        msgFileView.className = "msgFileView";
                        var imgDoc = document.createElement('img');
                        imgDoc.src = "/images/file.svg";
                        var fileNameDoc = document.createElement('div');
                        fileNameDoc.className="fileName";
                        fileNameDoc.innerHTML=msgText.fileName;
                        var dowload = document.createElement("a");
                        dowload.className="downloadFile";
                        dowload.href = msgText.fileUrl;
                        dowload.download = msgText.fileName;
                        msgFileView.appendChild(imgDoc);
                        msgFileView.appendChild(fileNameDoc);
                        msgFileView.appendChild(dowload);
                        msgContent.appendChild(msgFileView);
                    }
                }

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
                    this.createMsg('/images/bubble.png',this.user.id,this.user.roll_flag,"🖐",'studentMsg',200);
                    this.room.send(this.jsonMsg(this.user.id,"🖐"));
                }
                this.room.send(this.jsonCmd(this.user.id,2));
            },
            jsonMsg(id,text){
                return {"type":200,"name":this.user.name,"id":id,"roll_flag":this.user.roll_flag,"msg":text};
            },
            jsonCmd(id,cmd){
                return {"type":100,"id":id,"cmd":cmd,"value":this.handUp,roll_flag:this.user.roll_flag};
            },
            getMock(tracks){
                var ctx = document.createElement('canvas');
                var stream = ctx.captureStream(0);
                if(tracks!=""){
                    stream.addTrack(tracks);
                }
                this.localStream = stream;
                this.localVideoStream = stream;
            },
            showFileSelectWindow(e){
                if(this.selectedFiles.length>=10){
                    alert("1度にアップロードできるのは 10 個のファイル までです。");
                    return
                }
                switch(e){
                    case 0:
                        this.$refs.inputFile.click();
                        break;
                    case 1:
                        this.$refs.inputImg.click();
                        break;
                }
            },
            readerLoad(e,file){
                const fileUrl = e.target.result; // URLはevent.target.resultで呼び出せる
                var type = 0;
                if(file.type.split("/")[0]=="image"){
                    type = 1
                }
                this.selectedFiles.push({"type":type,"fileName":file.name,"fileUrl":fileUrl,"name":this.user.name,"roll_flag":this.user.roll_flag});
            },
            previewFile(file){
                // FileReaderオブジェクトを作成
                const reader = new FileReader();

                // URLとして読み込まれたときに実行する処理
                reader.onload = e => this.readerLoad(e,file);

                // いざファイルをURLとして読み込む
                reader.readAsDataURL(file);
            },
            handleFileSelect(e){
                if(e==0){
                    var files = this.$refs.inputFile.files;
                    for (let i = 0; i < files.length; i++) {
                        if(i>=10){
                            //dialog に変更
                            alert("1度にアップロードできるのは 10 個のファイル までです。");
                            break;
                        }
                        this.previewFile(files[i]);
                    }   
                }
                if(e==1){
                    var files = this.$refs.inputImg.files;
                    for (let i = 0; i < files.length; i++) {
                        if(i>=10){
                            //dialog に変更
                            alert("1度にアップロードできるのは 10 個のファイル までです。");
                            break;
                        }
                        // console.log(files[i].type.split("/")[0]);
                        //close 那个的旋转角度
                        if(files[i].type.split("/")[0]!="image"){
                            alert("アップロードしたファイルは画像ファイルではありません。");
                            break;
                        }
                        this.previewFile(files[i]);
                    }
                }
                this.$refs.inputFile.value="";
                this.$refs.inputImg.value="";
            },
            deleteFile(key){
                this.selectedFiles.splice(key, 1);
            },
            showBigImg(url){
                this.showImgUrl = url;
                this.onShowBigImg = true;
            }
        },
        created(){
            window.__SKYWAY_KEY__ = '1f3076ca-36ad-4d4f-87d7-23b05a093ca3';

            var peerID = Math.floor(Math.random()*10).toString() + this.user.roll_flag + this.user.id ;

            this.peer = new Peer(peerID,{
                key: window.__SKYWAY_KEY__,
                debug: 1,
            });
        },
        mounted(){
            this.peer.on('open',this.init);

            this.peer.on('error',(error)=>{
                console.log(`${error.type}: ${error.message}`);
                this.$refs.js_messages.append(this.addElement('div',"画面を閉じて、再起動してください。",'systemMsg',''));
            });
        },
    }
</script>

<style lang="css">
.videoList{
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
}
.img-fa img{
    cursor: zoom-out;
}
.img-fa{
    max-width: 1000px;
}
.img-content{
    position: relative;
    margin: auto;
    padding: 0;
    width: auto;
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
    animation-name: animatetop;
    animation-duration: 0.4s;
}
.studentMsg .downloadFile{
    border: 4px solid #f9ead2ff;
}
.teacherMsg .downloadFile{
    border: 4px solid greenyellow;
}
.msgFileView:hover .downloadFile{
    display: block;
}
.downloadFile:hover{
    background-color: #B1B1B1;
}
.downloadFile{
    display: none;
    position: absolute;
    width: 40px;
    height: 40px;
    background-image: url(/images/download.svg);
    background-size: 80%;
    background-color: white;
    background-position: center;
    background-repeat: no-repeat;
    border-radius: 50%;
    right: -15px;
    bottom: -15px;
}
.msgFileView {
    width: 250px;
    display: flex;
    height: 70px;
    padding: 12px;
    margin: 12px;
    border: 1px solid;
    border-radius: 8px;
    position: relative;
}
.msgFileView img {
    cursor:auto!important;
    height: 100%;
    margin-right: 12px;
}
.fileName {
    line-height: inherit;
    width: 100%;
    font-weight: bold;
    font-family: system-ui;
    margin: auto 0;
    white-space: nowrap;
    overflow: hidden;
}
.noImgView img{
    height: 100%;
    margin-right: 12px;
}
.noImgView{
    width: 200px;
    height: 100%;
    padding: 12px;
    display: flex;
}
.preview-wrapper{
    overflow: overlay;
}
.preview{
    padding: 12px 0 12px 12px;
    display: inline-flex;
}
.fileView{
    height: 60px;
    margin-right: 12px;
    border: solid 1px silver;
    border-radius: 5px;
    position: relative;
}
img{
    user-select: none;
}
.imgView{
    height: 100%;
    width: 58px;
}
.imgView img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 5px;
    outline: none;
    cursor: zoom-in;
    filter: blur(0.3px);
}
.fileView:hover .deleteFile{
    display: flex;
}
.deleteFile:hover{
    display: flex;
}
.deleteFile{
    display: none;
    top: -14px;
    right: -15px;
    position: absolute;
    width: 28px;
    height: 28px;
    background-color: gray;
    border-radius: 50%;
    border: 3px solid white;
    cursor: pointer;
    /* display: flex; */
    justify-content: center;
    align-items: center;
}
.deleteFile:hover{
    background-color: black;
}
.deleteFile::before,
.deleteFile::after{
    width: 14px;
    height: 4px;
    background-color: white;
    display: block;
    border-radius: 10px;
    position: absolute;
    content: '';
}
.deleteFile::after{
    transform: rotate(135deg);
}
.deleteFile::before{
    transform: rotate(225deg);
}
.opentionIcon{
    margin: auto 0 auto 10px;
    width: 35px;
    height: 35px;
    border-radius: 5px;
    cursor: pointer;
    background-size: cover;
}
.opentionIcon:hover{
    background-color: #B1B1B1;
}
.inputImg{
    background-image: url(/images/img.svg);
}
.inputFile{
    background-image: url(/images/folder.svg);
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
    grid-template-columns: 55.1% 44.9%;
    /* min-width: max-content;   有问题   上面的bodyer删了得 */
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
    height: 70%;
    background-image: url(/images/user.png);
    background-repeat: no-repeat;
    background-position: center;
    background-size: 20%;
}
.backTBG{
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: url(/images/user.png);
    background-repeat: no-repeat;
    background-position: center;
    background-size: 55%;
    background-color: black;
}
.my-video video{
    height: 70%;
    /* width: 100%; */
    margin: auto;
    border: 1px solid white;
    box-shadow: 0 0 3px 1px white;
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
    height: 100%;
    display: flex;
    flex-direction: column;
}
.messages{
    display: flex;
    flex: 1;
    position: relative;
}
.messages_list{
    overflow-y: overlay;
    position: absolute;
    top: 0;
    bottom: 0;
    height: auto;
    width: 100%;
}
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
    background-color: transparent;
}
::-webkit-scrollbar-thumb {
    background: #999999;
    border-radius: 12px;
    box-shadow:inset 0 0 6px rgba(0,0,0,.3);
}
::-webkit-scrollbar-thumb:hover {
    background: #707070; 
}
::-webkit-scrollbar-track
{
    border-radius:10px;
}
.input-area{
    padding-bottom: 25px;
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
    max-height: 200px;
    overflow: overlay;
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
    display: flex;
    height: 40px;
    background-color:#D8D8D8;
    border-top: 1px solid #999;
    border-bottom-left-radius: 5px;
    border-bottom-right-radius: 5px;
}
.js-send-trigger{
    font-weight: bolder;
    height: 30px;
    width: 60px;
    background: none;
    border: 1.5px solid #111;
    border-radius: 8px;
    outline: none;
    cursor: pointer;
    position: absolute;
    right: 10px;
    bottom: 5px;
    user-select: none;
}
.js-send-trigger:hover{
    background-color: gray;
    border-width: 2px;
}
.js-send-trigger:focus {
    outline: none;
    border-width: 2px;
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
    max-width: 485px;
    word-wrap:break-word;
    word-break:break-all;
    overflow: hidden;
    width: fit-content;
}
.msgContent img{
    cursor: zoom-in;
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
    width: auto;
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
    background-repeat: no-repeat;
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