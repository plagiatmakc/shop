<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default" >
                    <div class="card-header m-0" style="height: 42px; padding-top: 2px;">
                        <div class="d-flex bd-highlight mb-3">
                            <div class="p-0 bd-highlight">
                                <button class="btn btn-secondary" disabled>Erledigt</button>
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

                    <div class="card-body">

                        <h3>Wohnzimmer aufräumen</h3><br>
                        <div class="d-flex bd-highlight mb-3">
                            <div class="pr-2 bd-highlight">
                                <i class="fa fa-tag fa-lg fa-rotate-90" aria-hidden="true"></i>
                            </div>
                            <div class="p-0 bd-highlight">
                                <span style="color: white; background-color: black; border-radius: 3px; padding-left: 5px; padding-right: 3px;">PERSÖNLICH</span>
                            </div>
                            <div class="pl-2 bd-highlight mr-auto ">
                                <i class="fa fa-clock-o" aria-hidden="true"> 45 min</i>
                            </div>
                            <div class="p-0 bd-highlight">
                                <div class="dropdown">
                                    <button class="btn" type="button" id="dropdownTaskButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownTaskButton">
                                        <a class="dropdown-item" @click="showModal()">New</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <ul style="list-style: none; padding-left: 0px">
                            <transition-group name ="task">
                                <li v-for="(task, key) in tasks" class="task-item" v-bind:key="task.id">
                                    <strong>{{task.id}}</strong>
                                    <ul style="list-style: none; padding-left: 0px">
                                        <transition-group name="subtask" tag="p">
                                            <li v-for="(subtask, subkey) in task.value" v-bind:key="subtask" class="subtask-item" style="margin: 10px">
                                                <div class="d-flex p-0" style="background-color: #f1f1f1; border-radius: 5px">
                                                    <div class="mr-auto p-0">
                                                        <i class="fa fa-circle-o p-2" aria-hidden="true"></i>
                                                        <strong>{{subtask}}</strong>
                                                    </div>
                                                    <div class="p-0">
                                                        <div class="dropdown">
                                                            <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                            </button>
                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                <a class="dropdown-item" @click="removeSubtask(key, subkey)">Delete</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </transition-group>
                                    </ul>
                                </li>
                            </transition-group>
                        </ul>
                        <div>
                            <transition-group name="message" tag="p">
                                <div v-for="(message, index) in messages" v-bind:key="message.id" class="message-item">
                                    <div class="form-group d-flex mb-3 bd-highlight">
                                        <i class="fa fa-circle fa-3x p-2 bd-highlight" aria-hidden="true" ></i>
                                        <div class="p-2 mr-auto bd-highlight">
                                            {{message.msg}}
                                        </div>
                                        <div class="p-2 bd-highlight">
                                            <div class="dropdown">
                                                <button class="btn" type="button" id="dropdownMsgButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMsgButton">
                                                    <a class="dropdown-item" @click="removeMsg(index)">Delete</a>
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
                            <i class="fa fa-circle fa-3x p-2 bd-highlight" aria-hidden="true" ></i>
                            <textarea v-model="simpleMsg" class="form-control p-2 flex-grow-1 bd-highlight" rows="3"
                                      placeholder="Ziele updaten..." style="resize: none;"
                                      v-on:keyup.enter="appendMessage()">
                            </textarea>
                        </div>
                    </div>
                    <!--modal window-->
                    <div class="modal fade" id="exampleModal" v-show="isModalVisible" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">New task</h5>
                                    <button type="button" class="close" @click="isModalVisible = false" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="form-group">
                                            <label for="recipient-name" class="col-form-label">Task title:</label>
                                            <input type="text" v-model="newTaskId" class="form-control" id="recipient-name">
                                        </div>
                                        <div class="form-group">
                                            <label for="message-text" class="col-form-label">Subtask:</label>
                                            <input class="form-control" v-model="newTaskValue" id="message-text">
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" @click="isModalVisible = false" data-dismiss="modal">Close</button>
                                    <button type="button" @click="appendTask()" class="btn btn-primary">Create</button>
                                </div>
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
        data() {
            return {
                messages: [],
                newTaskId:'',
                newTaskValue:'',
                simpleMsg: '',
                msgCount: 0,
                isModalVisible: false,
                tasks: [
                    {id: 'Unteraufgaben1', value: [
                            "Unteraufgabe 1",
                            "Unteraufgabe 2",
                            "Unteraufgabe 3",
                        ]
                    },
                    {id: 'Unteraufgaben2', value: [
                            "Unteraufgabe 1",
                        ]
                    }
                ],
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
            appendTask() {
                let vm = this;
                let isIdExists = false;
                this.tasks.forEach(function(item)
                {
                    console.log(item);
                    if (item.id === vm.newTaskId)
                    {
                        item.value.push(vm.newTaskValue);
                        isIdExists = true;
                    }
                });
                if (!isIdExists)
                {
                    vm.tasks.push({id: vm.newTaskId, value: [vm.newTaskValue]});
                }
                this.newTaskId='';
                this.newTaskValue='';
            },

            removeSubtask(key,subkey) {
                Vue.delete(this.tasks[key].value, subkey);
            },

            appendMessage() {
                this.messages.push({id:this.msgCount++, msg: this.simpleMsg.slice(0,-1)});
                this.simpleMsg='';
            },
            removeMsg(index) {
                Vue.delete(this.messages, index);
            },
            showModal() {
                this.isModalVisible = true;
                $("#exampleModal").modal('show');
            }
        }
    }
</script>

<style scoped>
    .message-item {
        display: block;
        margin-right: 10px;
        transition: all .3s;
    }
    .message-enter-active, .message-leave-active {
        transition: all 1s;
        position: absolute;
    }
    .message-enter, .message-leave-to {
        opacity: 0;
        transform: translateY(30px);
        /*transform: scale3d(10,10,10);*/

    }

    .task-item {
        display: block;
        margin-right: 10px;
        transition: all .3s;
    }
    .task-enter-active, .task-leave-active {
        transition: all 1s;
        position: absolute;
    }
    .task-enter, .task-leave-to {
        opacity: 0;
        /*transform: translateX(-30px);*/
    }

    .subtask-item {
        display: block;
        margin-right: 10px;
        transition: all .3s;
    }
    .subtask-enter-active, .subtask-leave-active {
        transition: all 1s;
        position: absolute;
    }
    .subtask-enter, .subtask-leave-to {
        opacity: 0;
        transform: translateX(-30px);
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