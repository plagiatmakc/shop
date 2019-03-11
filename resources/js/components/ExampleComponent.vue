<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header m-0" style="height: 42px; padding-top: 2px;">
                        <div class="d-flex bd-highlight mb-3">
                            <div class="p-0 bd-highlight">
                                <button :class="'btn ' + onAir" disabled>{{btn_onAir}}</button>
                            </div>
                            <div class="pl-2 bd-highlight mr-auto">
                                <button class="btn rounded-circle" style="background-color: black; padding: 3px;">
                                    <span class="fa-stack ">
                                        <i class="fa fa-circle fa-stack-2x icon-background"></i>
                                        <i class="fa fa-play-circle-o fa-stack-2x"></i>
                                    </span>
                                </button>
                            </div>
                            <div class="p-0 bd-highlight">
                                <button class="btn" disabled>
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                </button>
                            </div>
                            <div class="p-0 bd-highlight">
                                <button class="btn " disabled>
                                    <i class="fa fa-paperclip " aria-hidden="true"></i>
                                </button>
                            </div>
                            <div class="p-0 bd-highlight">
                                <button class="btn " disabled>
                                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="scrollDisplay" class="card-body scrollable-wrapper">
                        <div class="scrollable-display">
                            <transition-group name="message" tag="p">
                                <div v-for="(message, index) in messages" v-bind:key="message.id" class="message-item">
                                    <div class="form-group d-flex mb-3 bd-highlight">
                                        <div style="max-width: 50px">
                                            <img :src="'/storage/'+message.avatar" v-if="message.avatar != null"
                                                 style="border-radius: 25px">
                                        </div>
                                        <div class="d-flex" style="background-color: lightgoldenrodyellow; width: 300px">
                                            <div class="p-2 mr-auto bd-highlight" style="width: 280px">
                                                {{message.msg}}
                                            </div>
                                            <div class="p-2  bd-hightlight">
                                                <div class="dropdown">
                                                    <button class="btn" type="button" id="dropdownMsgButton"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMsgButton">
                                                        <a class="dropdown-item" @click="removeMsg(index)">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </transition-group>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="form-group d-flex bd-highlight">
                            <div style="max-width: 50px">
                                <img :src="'/storage/'+avatar" v-if="avatar != null" style="border-radius: 25px;">
                            </div>
                            <textarea v-model="simpleMsg" class="form-control p-2 flex-grow-1 bd-highlight" rows="3"
                                      placeholder="Message..." style="resize: none;"
                                      v-on:keyup.enter="appendMessage()">
                            </textarea>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {bus} from '../app'
    let pusher = new Pusher(process.env.MIX_PUSHER_APP_KEY, {
        cluster: process.env.MIX_PUSHER_APP_CLUSTER,
        forceTLS: true
    });
    export default {
        props: ['room_id'],
        data() {
            return {
                messages: [],
                simpleMsg: '',
                msgCount: 0,
                selectChannel: null,
                role: this.$router.currentRoute.query.role || null,
                onAir: 'btn-danger',
                btn_onAir: 'Please wait free operators...',
                avatar: this.$store.state.user ? this.$store.state.user['avatar'] : null,
            }
        },
        mounted() {
            bus.$on('isLoggedIn', () => {
                this.avatar = this.$store.state.user['avatar'];
            });
            console.log('Component mounted.');
            this.sendInvite(this.room_id);
        },
        methods: {
            sendInvite(room) {
                if (this.role === null) {
                    window.axios.post('/api/send-invite', {
                        room_id: room,
                        message: 'visit room to chat with me',
                    })
                        .then(response => {
                            this.selectChannel = room;
                            this.prepareListener();
                        })
                        .catch(error => {
                            console.log(error.response);
                        });
                } else {
                    this.selectChannel = room;
                    this.prepareListener();
                    this.simpleMsg = 'Support join to chat\n';
                    this.appendMessage();
                    this.simpleMsg = 'How can i help you?\n';
                    this.appendMessage();
                }

            },
            prepareListener() {

                this.channel = pusher.subscribe(this.selectChannel);
                let vm = this;
                this.channel.bind('my-event', function (data) {
                    if (data.message === 'Support join to chat') {
                        vm.onAir = 'btn-success';
                        vm.btn_onAir = 'Support on-air';
                    }
                    vm.messages.push({id: vm.msgCount++, name: data.name, msg: data.message, avatar: data.avatar});
                    vm.autoScrollDown();
                });
            },
            appendMessage() {
                if (this.simpleMsg.length > 1) {
                    window.axios.post('/api/messenger', {
                        channel: this.selectChannel,
                        message: this.simpleMsg.slice(0, -1),
                        avatar: this.avatar,
                    })
                        .then(response => {
                            this.simpleMsg = '';
                        })
                        .catch(error => {
                            console.log(error.response);
                        });

                }

            },
            removeMsg(index) {
                Vue.delete(this.messages, index);
            },
            autoScrollDown() {
                // $('.scrollable-wrapper').scrollTop(10000000);
                var objDiv = document.getElementById("scrollDisplay");
                // objDiv.scrollTop = objDiv.scrollHeight - objDiv.clientHeight;
                $('#scrollDisplay').animate({
                    scrollTop: objDiv.scrollHeight //- objDiv.clientHeight
                }, 1000);
            }
        }
    }
</script>

<style scoped>
    .scrollable-wrapper {
        height: 350px;
        overflow-y: auto;
        /*display: flex;*/
        /*flex-direction: column-reverse;*/
    }

    .scrollable-display {

        display: flex;
        flex-direction: column-reverse;
        flex-grow: 1;

    }

    .message-item {
        display: block;
        margin-right: 10px;
        transition: all .3s;
        flex: 1;
    }

    .message-enter-active, .message-leave-active {
        transition: all .1s;
        position: absolute;
    }

    .message-enter, .message-leave-to {
        opacity: 0;
        transform: translateY(30px);
        /*transform: scale3d(10,10,10);*/

    }

    .icon-background {
        color: white;
    }

    /*.message-item {*/
    /*background-color: #f1f1f1;*/
    /*box-shadow: rgb(0, 0, 0) 1px 1px,*/
    /*rgb(20, 20, 20) 2px 2px,*/
    /*rgb(35, 35, 35) 3px 3px,*/
    /*rgb(40, 40, 40) 4px 4px,*/
    /*rgb(45, 45, 45) 5px 5px,*/
    /*rgb(50, 50, 50) 6px 6px,*/
    /*rgb(60, 60, 60) 7px 7px,*/
    /*rgb(60, 60, 60) 8px 8px,*/
    /*rgb(70, 70, 70) 9px 9px,*/
    /*rgb(90, 90, 90) 10px 10px;*/
    /*text-align: center;*/
    /*}*/
</style>