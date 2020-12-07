<template>
    <div class="body">
        <video :srcObject.prop="videoStream" ref="localVideo" autoplay></video>
    </div>
</template>
<script>

    export default {
        data(){
            return{
                videoStream: undefined, 
            }
        },
        methods: {
        },
        mounted(){
            this.videoStream = window.opener.onload();
            if(this.videoStream==""){
                alert("カメラを見つかりませんでした。");
            }
        },
    }
</script>
<style lang="css">
.body {
    height: 100%;
    width: 100%;
    position:fixed;
    display: flex;
}
.body video{
    object-fit: cover;
}
</style>