<template>
    <iframe allow="camera; microphone; fullscreen; speaker; display-capture"
        :src="link" width="100%" height="600px">
    </iframe>
</template>
<script>
export default {
   props:{
       link: {
           type:String,
           required:true
       }
   }
}
</script>