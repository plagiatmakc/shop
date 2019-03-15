<template>
    <div>
        <div v-for="comment in comments">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex bd-highlight">
                        <div v-if="comment.user.avatar" class="bd-highlight">
                            <img class="rounded-circle" :src="'/storage/'+comment.user.avatar" v-if="!comment.user.avatar.startsWith('http')">
                            <img class="rounded-circle" :src="comment.user.avatar" v-else>
                        </div>
                        <div v-else class="bd-highlight">
                            <img class="rounded-circle w-25" src="/images/No_Image.png">
                        </div>
                        <div class="ml-2 my-auto bd-highlight">
                            {{comment.user.first_name}} {{comment.user.last_name}}
                        </div>
                        <div class="ml-auto bd-highlight my-auto" v-if="isAdmin">
                            <i class="fa fa-trash btn" aria-hidden="true" data-toggle="tooltip" title="Delete comment with his children" @click="deleteComment(comment.id)"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <p class="card-text">{{comment.body}}</p>
                    <div class="dropdown">
                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" :id="'dropdownMenuButton_'+comment.id" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Reply
                        </button>
                        <div class="dropdown-menu col-6 p-4" :aria-labelledby="'dropdownMenuButton_'+comment.id">
                            <input type="text" class="form-control" v-model="newCommentForComment" placeholder="Quick reply...">
                            <div class="dropdown-divider"></div>
                            <div class="row justify-content-end pt-2 px-2" >
                                <button class="btn btn-primary btn-sm" @click="replyComment(comment.id)">Post comment</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <ul v-bind:id="'sub_'+ comment.id" v-if="comment.comments_recursive"
                style="list-style: none">
                <comment-component
                        v-bind:parent_id="comment.id"
                        v-bind:comments="comment.comments_recursive"
                ></comment-component>
            </ul>
        </div>
    </div>
</template>

<script>
    import {bus} from '../app';
    export default {
        props: ['parent_id', 'comments'],
        name: "CommentComponent",
        data() {
            return {
                newCommentForComment: '',
                isAdmin: this.$store.state.isAdmin || false
            }
        },
        watch: {
            '$store.state.isAdmin'() {
                this.isAdmin = this.$store.state.isAdmin;
            }

        },
        methods: {
            deleteComment(comment_id) {
                if(this.$store.state.isAdmin) {
                    window.axios.delete('/api/comment/'+comment_id, {
                        headers: {'Accept': 'application/json' , 'Authorization': 'Bearer '+ this.$store.state.token}
                    })
                        .then(response => {
                            console.log(response.data);
                            bus.$emit('refreshPage');

                        })
                        .catch(error => {
                            console.log(error.response);
                        })
                }
            },
            replyComment(comment_id) {
                if (this.newCommentForComment.length > 0) {
                    window.axios.post('/api/comment/'+comment_id,
                        {
                            comment_body: this.newCommentForComment
                        },
                        {
                            headers: {'Accept': 'application/json' , 'Authorization': 'Bearer '+ this.$store.state.token}
                        }
                    )
                        .then(response => {
                            console.log(response.data);
                            this.newCommentForComment = '';
                            bus.$emit('refreshPage');

                        })
                        .catch(error => {
                            console.log(error.response);
                        })
                } else {
                    alert('Comment is empty!');
                }

            },
        }
    }
</script>

<style scoped>

</style>