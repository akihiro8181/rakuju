<template>
    <div class="body" @click='onClickVideo'>
        <div class="header">
            <h1>授業名：{{$page.user.school.name}}</h1>
        </div>
        <div class="left">
            <div class="lab" :class="{toRight:showStudentList,toLeft:!showStudentList}">
                <transition name="show">
                    <div v-if="showStudentList" class="studentRoom">
                        <div class="listHeader">学生一覧</div>
                        <hr>
                        <div class="studentList">
                            <ol class="listContent">
                                <transition-group name="show">
                                    <li v-for="(value,key) in roomStudentList" :key="'student'+key">
                                        <div class="listNameTag">{{key+1}}．{{value.name}}</div>
                                        <div class="optionButton">
                                            <div v-if="value.handUp" class="hand ob" @click="handDown(value.id)"></div>
                                            <div v-if="videoStream.getVideoTracks()[0]!=undefined&&value.stream!=''&&value.stream.getVideoTracks()[0]!=undefined" :class="{videoOn:value.live}" class="switchStudentVideo ob" @click="switchVideoStream(value.id)"></div>
                                            <div v-if="!value.noAudio" :class="{micOn:value.audio}" class="audioSwitch ob" @click="mute(value.id)"></div>
                                            <div class="out ob" @click="out(value.id)">x</div>
                                        </div>
                                    </li>
                                </transition-group>
                            </ol>
                        </div>
                    </div>
                </transition>
                <div class="showList" :class="{abRight:showStudentList}" @click="showList">
                    <i class="icon-menu" :class="{close:showStudentList}"></i>
                </div>
            </div>
            <div class="my-video">
                <div v-if="showBackG" class="backG"></div>
                <video :srcObject.prop="videoStream" muted autoplay></video>
                <div class="tools">
                    <div :class="{active:videoEnable}" class="t1 t" @click="switchMedia(0)"><span v-if="videoEnable"></span></div>
                    <div :class="{active:audioEnable}" class="t2 t" @click="switchMedia(1)"><span v-if="audioEnable"></span></div>
                    <div :class="{active:share}" class="t3 t" @click="onScreenShare"></div>
                    <div :class="{active:onCheck}" class="t4 t" @click="checkVideo"></div>
                    <div :class="{active:diaglogFlag}" class="t5 t" @click="showLeaveDialog"></div>
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
                        <div class="my-message" ref="localText" placeholder="message" contenteditable="true" aria-multiline="true">
                        </div>
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
            <div class="waitRoom">
                <div class="waitRoomHead">待ち合わせ室</div>
                <hr>
                <div class="waitList">
                    <ol class="waitListContent">
                        <li v-for="(value,key) in waitRoomStudentList" :key="'wait'+key">
                            <div class="nameTag">{{key+1}}．{{value.name}}</div>
                            <div class="listButton">
                                <div class="ok divButton" @click="joinOk(value.id)">承認</div>
                                <div class="no divButton" @click="joinNo(value.id)">拒否</div>
                            </div>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- dialogBox -->
        <div class="dialog" ref="leaveDialog" v-if="diaglogFlag">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="closeButton" ref="js_Close_Dialog">&times;</span>
                    <h2>ラクジュ</h2>
                </div>
                <div class="modal-body">
                <div class="content-text"><div class="warring"></div>授業を終了しましょうか？</div>
                <div class="okButton" @click="dialogOk">はい</div>
                </div>
            </div>
        </div>
        <!-- studentOut -->
        <div class="dialog" ref="sureOut" v-if="adOut">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="closeButton" ref="js_Close_Dialog">&times;</span>
                    <h2>ラクジュ</h2>
                </div>
                <div class="modal-body">
                <div class="content-text"><div class="warring"></div>{{getName(outName)}}さんを強制退出させますか？</div>
                <div class="okButton" @click="sureOut">はい</div>
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
                    <div class="okButton" @click="classOver">OK</div>
                </div>
            </div>
        </div>
        <!-- video-check -->
        <div :class="{showVideoList:onCheck}" ref="videoList" class="videoList">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="closeButton" ref="js_Close_Dialog">&times;</span>
                    <h2>ラクジュ</h2>
                </div>
                <div class="video-List">
                    <div class="video-List-inner">
                        <div class="video-Item" v-for="(value,key) in roomStudentList" :key="key">
                            <div class="video-Item-inner">
                                <video :srcObject.prop="value.stream" autoplay></video>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="onShowBigImg" ref="imgDialog" class="dialog">
            <div class="img-content">
                <div class="img-fa">
                    <img ref="bigImg" :src="showImgUrl">
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
                outMsg:"授業が終了しました。",
                classEnd:false,
                localStream:undefined,           //カメラ用
                videoStream: undefined,          //画面上に表示するストリーム
                share:false,                     //share flag
                CLASS_CODE:'1234',                //授業Code
                roomStudentList:[],　　//学生のカメラストリームリスト
                peer:undefined,                //Peerのオブジェクト
                room:undefined,               //Room のオブジェクト
                diaglogFlag:false,           //退出dialog FLag 
                adOut:false,                  //強制退出dialog flag
                outName:"",　　　　　　　　　　　//強制退出人の名前
                waitRoom:undefined,            //待ち室
                waitRoomStudentList: [],      //待ち室のリスト
                showBackG:true,                //Videoないの時のbackground
                noVideo:false,　　　　　　　　　//カメラない?
                noAudio:false,              　　//マイクない?
                videoEnable: false,             //カメラ　オン?
                audioEnable: false,              //マイク　オン?
                showStudentList:false,            //学生リスト　表示flag
                selectedFiles:[],　　　　　　　　　　//アップロードしたいファイルリスト
                showStudentVideo:false,            //学生の画面を表示しているか
                shareStream:undefined,              //画面共有　ストリーム
                onCheck:false,                     //video check 中
                onShowBigImg:false,
                showImgUrl:'',
                rejoinList:[],
            }
        },
        methods: {
            init(){
                this.waitRoom = this.peer.joinRoom(`waitRoom${this.CLASS_CODE}`,{
                    mode:'sfu',
                });
                if(this.waitRoom!=undefined){
                    this.waitRoom.on('data',({data,src}) => {
                        if(data.roll_flag ===this.user.roll_flag){
                            return
                        }
                        var has = false;
                        this.waitRoomStudentList.forEach((item)=>{
                            if(item.id==data.id){
                                // this.roomStudentList.splice(index, 1);
                                has = true;
                            }
                        })
                        if(has){
                            return
                        }
                        this.waitRoomStudentList.push({"id":data.id,"name":data.name});
                    });
                    this.waitRoom.on('peerLeave',peerId =>{
                        this.deleteJoinList(peerId.substring(3));
                    });
                }
                this.joinRoom(this.videoStream);
                this.room.once('open', () => {
                    this.appendMsg(this.addElement('div',"授業開設しました。",'systemMsg',''));
                });
            },
            joinRoom(stream){
                this.room = this.peer.joinRoom(this.CLASS_CODE,{
                    mode: 'sfu',
                    stream: stream,
                });
                if(this.room!=undefined){
                    this.room.on('peerJoin', peerId => {
                        if(this.reJoinList.indexOf(peerId.substring(3))!=-1){
                            this.reJoinList.splice(this.reJoinList.indexOf(peerId.substring(3)),1);
                        }
                        // this.roomStudentList.push({"id":peerId.substring(3),"name":"","stream":"","handUp":"","video":"","audio":"","noVideo":"","noAudio":"","live":false,"onSharing":false});
                        // this.appendMsg(this.addElement('div',`=== ${peerId.substring(3)}さんが入りました ===`,'systemMsg',''));
                    });
                
                    this.room.on('stream', async stream => {
                        var has = false;
                        this.roomStudentList.forEach((item)=>{
                            if(item.id == parseInt(stream.peerId.substring(3))){
                                item.stream = stream;
                                has = true;
                            }
                        })
                        if(has){
                            return
                        }else{
                            this.roomStudentList.push({"id":parseInt(stream.peerId.substring(3)),"name":"","stream":stream,"handUp":"","video":"","audio":"","noVideo":"","noAudio":"","live":false,"onSharing":false});
                        }
                    });
            
                    this.room.on('data', ({ data, src }) => {
                        if(data.type==100){
                            if(data.cmd==2){
                                this.roomStudentList.forEach((item)=>{
                                    if(item.id==data.id){
                                        item.handUp = data.value;
                                    }
                                });
                            }
                            if(data.cmd==1){
                                this.roomStudentList.forEach((item)=>{
                                    if(item.id==data.id){
                                        item.audio = data.value;
                                    }
                                });
                            }
                            if(data.cmd==8){
                                this.rejoinRoom(this.videoStream);
                            }
                            if(data.cmd==88){
                                this.rejoinList.push(data.id.toString());
                                this.room.send({"type":100,"cmd":90,"value":true,"roll_flag":this.user.roll_flag});
                            }
                            if(data.cmd==9){
                                var has = false;
                                this.roomStudentList.forEach((item)=>{
                                    item.id == data.id;
                                    item.name = data.name;
                                    item.handUp = data.handUp;
                                    item.video = data.video;
                                    item.audio = data.audio;
                                    item.noVideo = data.noVideo;
                                    item.noAudio = data.noAudio;
                                    item.onSharing = data.onSharing;
                                    has = true;
                                })
                                if(!has){
                                    this.roomStudentList.push({"id":data.id,"name":data.name,"stream":"","handUp":data.handUp,"video":data.video,"audio":data.audio,"noVideo":data.noVideo,"noAudio":data.noAudio,"live":false,"onSharing":data.onSharing});
                                }
                                // this.roomStudentList.push({"id":data.id,"name":data.name,"stream":"","handUp":data.handUp,"video":data.video,"audio":data.audio,"noVideo":data.noVideo,"noAudio":data.noAudio,"live":false,"onSharing":data.onSharing});
                                this.appendMsg(this.addElement('div',`=== ${data.name}さんが入りました ===`,'systemMsg',''));
                            }
                        }
                        if(data.type==200){
                            this.createMsg('/images/bubble.png',data.name,data.roll_flag,data.msg,'studentMsg',200);
                        }
                        if(data.type==1 || data.type==0){
                            this.createMsg('/images/bubble.png',data.name,data.roll_flag,data,'studentMsg',100);
                            
                        }
                    });
            
                    this.room.on('peerLeave', peerId => {
                        if(this.rejoinList.indexOf(peerId.substring(3))!=-1){
                            return
                        }
                        this.appendMsg(this.addElement('div',`=== ${this.getName(peerId.substring(3))}さんが退室しました ===\n`,'systemMsg',''));
                        this.deleteStudentList(peerId.substring(3));
                        this.roomStudentList.forEach((item)=>{
                            if(item.id==peerId.substring(3)){
                                if(item.live){
                                    this.showBackG = true;
                                }
                            }
                        })
                    });
            
                    // this.room.once('close', () => {
                    //     // window.close();
                    // });
                }
            },
            switchMedia(media){
                //video
                if(media===0){
                    if(this.videoStream.getVideoTracks()[0] == undefined){
                        return
                    }
                    if(this.videoStream.getVideoTracks()[0].enabled){
                        //close the video
                        this.videoStream.getVideoTracks().forEach( track => (track.enabled = false));
                        this.showBackG = true;
                        this.videoEnable = true;
                        // this.room.send({"type":100,"cmd":3,"value":true});
                    }else{
                        //open the video
                        // backG.style.display = 'none';
                        this.videoStream.getVideoTracks().forEach( track => (track.enabled = true));
                        this.showBackG = false;
                        this.videoEnable = false;
                        // this.room.send({"type":100,"cmd":3,"value":false});
                    }
                }
                //mic
                if(media===1){
                    if(this.videoStream.getAudioTracks()[0]==undefined){
                        return
                    }
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
                // if(this.noVideo){
                //     return
                // }
                if(this.share){
                    if(this.showStudentVideo){
                        this.shareStream.getVideoTracks()[0].stop();
                        this.showLocalVideo();
                        return
                    }
                    this.videoStream.getVideoTracks()[0].stop();
                    this.showLocalVideo();
                }else{
                    navigator.mediaDevices.getDisplayMedia({ 
                        video: { width: 1024, height: 760 },
                    }).then(stream => {
                        this.roomStudentList.forEach((item)=>{
                            if(item.live){
                                item.live = false
                            }
                        })
                        this.showStudentVideo = false;
                        this.share=true;
                        this.showBackG = false;
                        stream.getVideoTracks()[0].addEventListener('ended', () => {this.showLocalVideo()});
                        var shareStream;
                        if(this.videoStream.getAudioTracks()[0]==undefined){
                            shareStream = new MediaStream([stream.getVideoTracks()[0]]);
                        }else{
                            shareStream = new MediaStream([stream.getVideoTracks()[0],this.videoStream.getAudioTracks()[0]]);
                        }
                        this.shareStream = shareStream;
                        if(this.noVideo){
                            this.videoStream = this.shareStream;
                            this.room.send({"type":100,"id":this.user.id,"cmd":8});
                        }else{
                            if(!this.videoStream.getVideoTracks()[0].enabled){
                                this.videoStream=shareStream;
                                this.videoStream.getVideoTracks().forEach( track => (track.enabled = false));
                            }else{
                                this.videoStream=shareStream;
                            }
                            this.room.replaceStream(this.videoStream);
                        }
                    }).catch(e=>{console.log("err:"+e)});
                }
            },
            rejoinRoom(stream){
                this.room.close();
                this.joinRoom(stream);
            },
            showLocalVideo(){
                this.share=false;
                if(this.showStudentVideo){
                    this.shareStream = undefined;
                    return;
                }
                if(this.noVideo){
                    this.showBackG = true;
                    this.room.send({"type":100,"cmd":3,"value":true});
                    this.videoStream = this.localStream;
                    this.room.replaceStream(this.videoStream);
                    this.shareStream = undefined;
                    return
                }
                if(!this.videoStream.getVideoTracks()[0].enabled){
                    this.videoStream = this.localStream;
                    this.videoStream.getVideoTracks().forEach( track => (track.enabled = false));
                }else{
                    this.videoStream = this.localStream;
                    this.videoStream.getVideoTracks().forEach( track => (track.enabled = true));
                }
                this.room.replaceStream(this.videoStream);
                this.shareStream = undefined;
            },
            onClickSend(){
                if(!this.peer.open){
                    return;
                }
                this.$refs.localText.focus();
                if(this.$refs.localText.innerText != ''){
                    var text = this.$refs.localText.innerText;
                    this.room.send(this.jsonMsg(this.user.id,text));
                    this.createMsg('/images/bubble.png',this.user.name,this.user.roll_flag,text,'teacherMsg',200);
                    this.$refs.localText.innerText = '';
                }
                if(this.selectedFiles.length > 0){
                    this.selectedFiles.forEach((item)=>{
                        this.room.send(item);
                        this.createMsg('/images/bubble.png',this.user.name,this.user.roll_flag,item,'teacherMsg',100);
                    })
                    this.selectedFiles=[]
                }
            },
            showLeaveDialog(){
                this.diaglogFlag = true;
            },
            dialogOk(){
                this.room.send({"type":100,"cmd":33});
                this.classEnd = true;
            },
            classOver(){
                this.room.close();
                window.close();
            },
            onClickVideo:function(e){
                if (e.target == this.$refs.leaveDialog || e.target == this.$refs.js_Close_Dialog ||e.target == this.$refs.bigImg || e.target.innerText== "×" || e.target == this.$refs.videoList || e.target == this.$refs.imgDialog || e.target == this.$refs.sureOut) {
                    this.diaglogFlag = false;
                    this.adOut = false;
                    this.onCheck = false;
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
            joinOk(id){
                this.deleteJoinList(id);
                this.waitRoom.send({"id":id,res:"ok","roll_flag":this.user.roll_flag});
            },
            joinNo(id){
                this.deleteJoinList(id);
                this.waitRoom.send({"id":id,res:"no","roll_flag":this.user.roll_flag});
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
            },
            handDown(id){
                this.room.send({"type":100,"id":id,"cmd":2,"value":false});
                this.roomStudentList.forEach((item)=>{
                    if(item.id==id){
                        item.handUp = false;
                    }
                })
            },
            checkVideo(){
                this.onCheck = true;
            },
            mute(id){
                this.roomStudentList.forEach((item)=>{
                    if(item.id==id){
                        if(item.audio){
                            item.audio = false;
                            this.room.send({"type":100,"id":id,"cmd":1,"value":false});
                        }else{
                            item.audio = true;
                            this.room.send({"type":100,"id":id,"cmd":1,"value":true});
                        }
                    }
                })
            },
            out(id){  
                // this.outName = this.getName(id);
                this.outName = id;
                this.adOut = true;
            },
            sureOut(){
                this.adOut = false;
                this.room.send({"type":100,"id":this.outName,"cmd":0});
            },
            jsonMsg(id,text){
                return {"type":200,"name":this.user.name,"id":id,"roll_flag":this.user.roll_flag,"msg":text};
            },
            switchVideoStream(id){
                //表示したいVideoを探す
                this.roomStudentList.forEach((item)=>{
                    //表示したいVideoのIDがあった
                    if(item.id==id){
                        //クリックしたVideoと現在表示されるVideo一致
                        if(item.live){
                            //学生リストでVideoLive状態(表示されてるか)をOFFにする
                            item.live = false;
                            if(this.share){
                                this.videoStream = this.shareStream;
                            }else{
                                //自分のVideoを表示する
                                this.videoStream = this.localStream;
                                if(this.noVideo){
                                    this.showBackG = true;
                                }
                            }
                            //学生画面を表示しているにしない
                            this.showStudentVideo = false;
                        }else{//クリックしたVideoと現在表示されるVideo一致しない
                            //クリックしたVideoを表示する
                            // var stream 
                            if(!this.noAudio){
                                this.videoStream = new MediaStream([item.stream.getVideoTracks()[0],this.videoStream.getAudioTracks()[0]]);
                            }else{
                                this.videoStream = new MediaStream([item.stream.getVideoTracks()[0]]);
                            }
                            // this.videoStream = item.stream;
                            //学生リストでVideoLive状態(表示されてるか)をOnにする
                            item.live = true;
                            //カメラなしの時のBackGroundを非表示
                            this.showBackG = false;
                            //学生画面を表示しているにする
                            this.showStudentVideo = true;
                        }
                    }
                })
                this.room.send({"type":100,"cmd":3,"value":this.showBackG});
                this.room.replaceStream(this.videoStream);
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
                this.selectedFiles.push({"type":type,"file":file,"fileName":file.name,"fileUrl":fileUrl,"name":this.user.name,"roll_flag":this.user.roll_flag});
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
            },
            getName(id){
                var name = "";
                this.roomStudentList.forEach((item) => {
                    if(item.id == id){
                        name = item.name;
                    }
                });
                return name;
            },
            deleteStudentList(id){
                this.roomStudentList.forEach((item,index)=>{
                    if(item.id==id){
                        this.roomStudentList.splice(index, 1);
                    }
                })
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

            navigator.mediaDevices.getUserMedia({
                video:{ width: 1024, height: 760 },
                audio: true,
            }).then(stream => {
                this.localStream = stream;
                this.videoStream = stream;
                this.showBackG = false;
                if(this.peer.open){
                    this.init();
                }else{
                    this.peer.on('open',this.init);
                }
            }).catch(e=>{
                console.log(`${e.type}:${e}`);
                navigator.mediaDevices.getUserMedia({
                    video:{ width: 1024, height: 760 }
                }).then(stream => {
                    this.localStream = stream;
                    this.videoStream = stream;
                    this.showBackG = false;
                    this.noAudio = true;
                    this.$refs.js_messages.append(this.addElement('div',"マイクが取得できませんでした。",'systemMsg',''));
                    if(this.peer.open){
                        this.init();
                    }else{
                        this.peer.on('open',this.init);
                    }
                }).catch(e=>{
                    navigator.mediaDevices.getUserMedia({
                        audio:true,
                    }).then(stream => {
                        this.noVideo = true;
                        this.localStream = stream;
                        this.videoStream = stream;
                        this.$refs.js_messages.append(this.addElement('div',"カメラが取得できませんでした。",'systemMsg',''));
                        if(this.peer.open){
                            this.init();
                        }else{
                            this.peer.on('open',this.init);
                        }
                    }).catch(e=>{
                        this.outMsg = "授業開設を失敗しました、カメラとマイクどちらが有効にしてください";
                        this.classEnd = true;
                        // this.$refs.js_messages.append(this.addElement('div',"授業開設失敗、カメラとマイクどちらが有効にしてください",'systemMsg',''));
                    });
                });
            });

            this.peer.on('error',(error)=>{
                console.log(`${error.type}: ${error.message}`);
                this.$refs.js_messages.append(this.addElement('div',"画面を閉じて、再起動してください。",'systemMsg',''));
            });
        },
    }
</script>

<style lang="css">
.showVideoList{
    display: flex!important;
}
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
.video-Item-inner video{
    border-radius: 10px;
    height: 100%;
    width: 100%;
    /* background-color: black;
    box-shadow: 0 0 3px 1px black; */
    border: 2px solid white;
    box-shadow: 0 0 3px 1px white;
}
.video-Item {
    display: flex;
    min-height: 203px;
    min-width: 287px;
    height: 100%;
    width: 100%;
}
.video-List-inner {
    position: absolute;
    top: 0;
    bottom: 0;
    right: 0;
    left: 0;
    display: grid;
    grid-template-columns: repeat(4,287px);
    grid-template-rows: repeat(auto-fill,203px);
    grid-gap: 10px;
    margin: 5px;
}
.video-List {
    height: 854px;
    width: 1200px;
    position: relative;
    overflow: overlay;
    display: flex;
    background-color: black;
}
.studentMsg .downloadFile{
    border: 4px solid #f9ead2ff;
}
.teacherMsg .downloadFile{
    border: 4px solid greenyellow;
}
div{
    user-select: none;
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
    border: 4px solid greenyellow;
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
    cursor: auto!important;
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
.videoOn{
    background-color: rgb(180, 0, 0);
}
.videoOn:hover{
    background-color: rgb(255, 0, 0)!important;
}
.micOn{
    background-color: green;
}
.micOn:hover{
    background-color:rgb(0, 189, 0)!important;
}
.abRight{
    right: 50px!important;
}
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
.showList:hover .icon-menu,
.showList:hover .icon-menu::after,
.showList:hover .icon-menu::before{
    background-color: white;
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
/* .list-enter-active,
.list-leave-active{
    transition: all 0.8s;
}
.list-enter, .list-leave-to{
    transform: translateX(-350px);
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
    grid-template-columns: 65% 35%;
    /* min-width: max-content; */
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
.lab {
    height: 100%;
    width: 500px;
    display: flex;
    position: absolute;
    z-index: 1;
    transition: all 0.8s;
}
.toRight{
    transform: translateX(0);
}
.toLeft{
    transform: translateX(-450px);
}
.showList{
    position: absolute;;
    background-color: #7d93baff;
    width: 50px;
    height: 50px;
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
    right: 0;
}
.studentRoom{
    height: 100%;
    width: 450px;
    position: relative;
    background-color:  #7d93baff;
    padding: 5px;
    /* border: 1px solid #111; */
}
.studentRoom hr{
    border-top-width: 3px;
    border-color: darkblue;
    margin: 5px 3px;
}
.listHeader{
    font-size: 30px;
    font-weight: bolder;
    margin-left: 10px;
}
.studentList{
    overflow: overlay;
    height: 89%;
}
.listContent{
    margin:0 5px;
}
.listContent li{
    margin: 5px;
    display: flex;
    position: relative;
    border-bottom: dashed 1px darkslategray;
}
.listNameTag {
    max-width: 250px;
    font-size: 22px;
    font-weight: bolder;
    line-height: 40px;
}
.optionButton {
    position: absolute;
    right: 0;
    display: flex;
}
.hand {
    background-image: url(/images/open-hand.svg);
}
.switchStudentVideo{
    background-image: url(/images/live.svg);
}
.audioSwitch {
    background-image: url(/images/microphone.svg);
}
.out {
    text-align: center;
    font-weight: bolder;
    line-height: 35px;
    font-size: 40px;
}
.ob:hover{
    background-color: rgba(105,105,105,0.8);
}
.ob{
    width: 40px;
    height: 40px;
    margin: 0 5px;
    background-size: 65%;
    background-repeat: no-repeat;
    background-position: center;
    border-radius: 50%;
    cursor: pointer;
}
.my-video{
    position: relative;
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
    max-height: 700px;
    /* border: 1px solid silver; */
    position: absolute;
    top: 0;
    bottom: 0;
    margin: auto;
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
.t4{
    background-image: url(/images/video-check.svg);
    background-size: 45px;
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
.studentList::-webkit-scrollbar-thumb{
    background: #404b73ff;
}
.studentList::-webkit-scrollbar-thumb:hover{
    background: rgba(0,0,0,0.3); 
}
.waitListContent::-webkit-scrollbar-thumb{
    background: #404b73ff;
}

.input-area{
    width: 100%;
    padding-bottom: 10px;
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
    max-height: 150px;
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
.js-send-trigger:focus{
    outline: none;   
    border-width: 2px;
}
.js-send-trigger:hover{
    background-color: gray;
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
    max-width: 344px;
    word-wrap:break-word;
    word-break:break-all;
    overflow: hidden;
    width: fit-content;
    user-select: auto;
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
@keyframes animatetop {
    from {
        top:-300px; 
        opacity:0}

    to {
        top:0; 
        opacity:1
    }
}
.closeButton {
    color: white;
    float: right;
    font-size: 30px;
    font-weight: bold;
}

.closeButton:hover,
.closeButton:focus {
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
    border-top-left-radius: 7px;
    border-top-right-radius: 7px;
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
    background-repeat: no-repeat;
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
    /* margin: 0 5px; */
    padding: 0 0 3px 5px;
}
.waitListContent {
    height: 100%;
    /* padding: 5px 20px; */
    overflow: overlay;
    font-size: 24px;
    /* margin: 3px 10px; */
}
.waitListContent li{
    margin: 5px 0;
    display: flex;
    position: relative;
    width: 100%;
    padding: 0 5px;
}
.listButton {
    display: flex;
    margin: 0 0 0 auto;
}
.divButton{
    margin:0 5px;
    padding: 0 10px;
    cursor: pointer;
    min-width: 68px;
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
.nameTag{
    max-width: 64%;
    overflow: hidden;
}
</style>