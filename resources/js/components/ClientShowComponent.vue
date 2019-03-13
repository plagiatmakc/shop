<template>
    <div class="container">
        <ul v-if="errors != null">
            <li v-for="error in errors" class="alert-danger"> {{error.toString()}}</li>
        </ul>
        <div v-if="is_loading" class="row justify-content-center">
            <img src="/images/loading.gif">
        </div>
        <div class="row justify-content-center" v-else>
            <div class="col-md-12">
                <div class="card card-default">
                    <!--<div class="card-header">Show client</div>-->
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">First name</label>
                            <div class="col-md-6 col-form-label">
                                {{first_name}}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Last name</label>
                            <div class="col-md-6 col-form-label">
                                {{last_name}}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label text-md-right">Phone</label>
                            <div class="col-md-6 col-form-label">
                                {{phone}} ({{country_code}})
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                            <div class="col-md-6 col-form-label">
                               {{email}}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Avatar</label>
                            <div v-if="avatar" class="col-md-6 col-form-label">
                                <img :src="'/storage/'+ avatar + '?img=' + Math.random()" v-if="!avatar.startsWith('http')" >
                                <img :src="avatar + '?img=' + Math.random()" v-else >
                            </div>
                            <div v-else class="col-md-6 col-form-label" >
                                <img src="/images/No_Image.png" width="30%">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Created at</label>
                            <div class="col-md-6 col-form-label">
                                {{created_at}}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Updated at</label>
                            <div class="col-md-6 col-form-label">
                                {{updated_at}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {phoneUtil} from '../app'
    export default {
        props: ['client_id'],
        data() {
            return {
                first_name : "",
                last_name : "",
                phone: "",
                avatar: "",
                email : "",
                created_at: '',
                updated_at: '',
                errors: [],
                country_code: '',
                is_loading: false

            }
        },
        mounted() {
            this.getClientInfo();
        },
        methods : {
            getClientInfo() {
                this.is_loading = true;
                window.axios.get('/api/user/'+ this.client_id, {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer '+localStorage.getItem('bigStore.jwt')
                    }
                })
                    .then(response => {
                        this.first_name = response.data.first_name;
                        this.last_name = response.data.last_name;
                        this.phone = response.data.phone;
                        this.avatar = response.data.avatar;
                        this.email = response.data.email;
                        this.created_at = response.data.created_at;
                        this.updated_at = response.data.updated_at;
                        try {
                            this.country_code = phoneUtil.getRegionCodeForNumber(phoneUtil.parseAndKeepRawInput(response.data.phone, ""));
                        }catch (e) {
                            this.errors.push(e);
                            this.country_code = 'unknown';
                        }
                        this.is_loading = false;
                    })
                    .catch(error => {
                        this.errors = error.response.error;
                        this.is_loading = false;
                    })
            },

        }
    }
</script>

<style scoped>

</style>