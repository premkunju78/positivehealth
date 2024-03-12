<template>
  <chat-window
    :current-user-id="currentUserId"
    :rooms="rooms"
    :messages="messages"
    :loading-rooms="loadingRooms"
    :rooms-loaded="roomsLoaded"
    :messages-loaded="messagesLoaded"
    :show-add-room="false"
    :show-files="false"
    :show-audio="false"
    @fetch-messages="fetchMessages"
    @send-message="sendMessage"
  />
</template>
<script>
import ChatWindow from "vue-advanced-chat";
import "vue-advanced-chat/dist/vue-advanced-chat.css";
import { methods } from "vue-echarts";

export default {
  components: {
    ChatWindow,
  },
  data() {
    return {
      rooms: [],
      messages: [],
      currentUserId: this.$store.state.auth.user.id,
      selectedRoom: null,
      roomId: "",
      messagesLoaded: true,
      loadingRooms: false,
      roomsLoaded: true,
      message: {},
    };
  },
  created() {
    this.fetchRooms();
    var self = this;
    setTimeout(function () {
      self.updateChat();
    }, 1000);
  },
  mounted() {
    document.querySelector("vue-advanced-chat").currentUserId = this.currentUserId;
    document.querySelector("vue-advanced-chat").rooms = this.rooms;
    document.querySelector("vue-advanced-chat").messages = this.messages;
  },
  methods: {
    resetMessages() {
      this.messages = [];
      this.messagesLoaded = false;
    },
    async fetchRooms() {
      const { data } = await axios.get("/chat/rooms");
      this.rooms = data.rooms;
      this.messagesLoaded = true;
      this.messages = data.messages;
      this.roomMessages(this.rooms[0].roomId);
      this.roomId = this.rooms[0].roomId;
    },
    async fetchMessages({ room }) {
      // if (options.reset) {
      //   this.resetMessages()
      //   this.roomId = room.roomId
      // }
      // if (this.previousLastLoadedMessage && !this.lastLoadedMessage) {
      //   this.messagesLoaded = true
      //   return
      // }
      this.roomId = room.roomId;
      this.selectedRoom = room.roomId;
      const { data } = await axios.get("/chat/messages/" + this.selectedRoom);
      this.messages = data.messages;
    },
    async roomMessages(roomId) {
      const { data } = await axios.get("/chat/messages/" + this.selectedRoom);
      this.messages = data.messages;
    },
    async sendMessage({ content, roomId, files, replyMessage }) {
      const message = {
        sender_id: this.currentUserId,
        content,
        timestamp: new Date(),
      };
      if (files) {
        message.files = this.formattedFiles(files);
      }
      const { data } = await axios.post("/chat/sendmessage", {
        roomId: roomId,
        message: message,
      });
      this.roomMessages(roomId);
    },
    async updateChat() {
      this.roomMessages(this.roomId);
      var self = this;
      // setTimeout(function () {
      //   self.updateChat();
      // }, 2000);
    },
    formattedFiles(files) {
      const formattedFiles = [];
      files.forEach((file) => {
        const messageFile = {
          name: file.name,
          size: file.size,
          type: file.type,
          extension: file.extension || file.type,
          url: file.url || file.localUrl,
        };
        if (file.audio) {
          messageFile.audio = true;
          messageFile.duration = file.duration;
        }
        formattedFiles.push(messageFile);
      });
      return formattedFiles;
    },
    openFile({ file }) {
      window.open(file.file.url, "_blank");
    },
  },
};
</script>
<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";
@import "@core/scss/vue/libs/vue-flatpicker.scss";

.custom-radio {
  margin-top: 0 !important;
}

.eventDate {
  background: #0d1570;
  color: #fff;
  padding: 13px;
  border-radius: 3px;
}

span.badge.badge-primary.small {
  font-size: 10px;
}

html .content.app-content.show-overlay .content-overlay {
  opacity: 0 !important;
}
</style>
