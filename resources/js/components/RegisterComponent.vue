<template>
    <div class="container">
        <ul v-if="errors != null">
            <li v-for="error in errors" class="alert-danger"> {{error.toString()}}</li>
        </ul>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Register</div>
                    <div class="card-body">
                        <form enctype="multipart/form-data">
                            <div class="form-group row">
                                <label for="first_name" class="col-md-4 col-form-label text-md-right">First name</label>
                                <div class="col-md-6">
                                    <input id="first_name" type="text" class="form-control" v-model="first_name" required autofocus>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="last_name" class="col-md-4 col-form-label text-md-right">Last name</label>
                                <div class="col-md-6">
                                    <input id="last_name" type="text" class="form-control" v-model="last_name" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="phone" class="col-md-4 col-form-label text-md-right">Phone</label>
                                <div class="col-md-6">
                                    <input id="phone" type="text" class="form-control" v-model="phone" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" v-model="email" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="avatar" class="col-md-4 col-form-label text-md-right">Avatar</label>
                                <div class="col-md-6">
                                    <input id="avatar" type="file" ref="avatar" class="form-control" @change="onFileChanged($event)">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" v-model="password" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>
                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" v-model="password_confirmation" required>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary" @click="handleSubmit">
                                        Register
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {bus} from '../app'
    export default {
        data(){
            return {
                first_name : "",
                last_name : "",
                phone: "",
                avatar: "",
                email : "",
                password : "",
                password_confirmation : "",
                errors: [],
            }
        },
        methods : {
            handleSubmit(e) {
                e.preventDefault();
                if (this.password !== this.password_confirmation || this.password.length <= 0) {
                    this.password = "";
                    this.password_confirmation = "";
                    return alert('Passwords do not match');
                }
                let formData = new FormData();
                formData.append('first_name', this.first_name);
                formData.append('last_name', this.last_name);
                formData.append('phone', this.phone);
                formData.append('email', this.email);
                formData.append('password', this.password);
                formData.append('c_password', this.password_confirmation);
                formData.append('avatar', this.avatar);

                axios.post('/api/register', formData, {headers: {'Content-Type': 'multipart/form-data'}})
                    .then(response => {

                        let data = response.data;
                        console.log(data);
                        localStorage.setItem('bigStore.user', JSON.stringify(data.user));
                        localStorage.setItem('bigStore.jwt', data.token);
                        if (localStorage.getItem('bigStore.jwt') != null) {
                            bus.$emit('isLoggedIn');

                        this.$router.push('/');
                    }
                })
                    .catch(error => {
                        this.errors = error.response.data.errors;
                        console.log(error.response);
                    })
            },
            onFileChanged(e) {
                this.avatar = "";
                var files = e.target.files || e.dataTransfer.files;
                this.avatar = files[0];
                console.log(this.avatar);
            }
        }
    }
</script>

<style scoped>

</style>