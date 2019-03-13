<template>
    <div class="container">
        <ul v-if="errors != null">
            <li v-for="error in errors" class="alert-danger"> {{error.toString()}}</li>
        </ul>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">Update client</div>
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
                                <label for="phone" class="col-sm-4 col-form-label text-md-right">Phone</label>
                                <div class="col-md-6 custom-control-inline">
                                    <select id="dial_code" v-model="country_code" type="text" class="form-control"
                                            style="max-width: 40px; padding-left: unset; padding-right: unset" required
                                    >
                                        <option v-for="country in countries" :value="country.code" data-toggle="tooltip" :title="country.country">
                                            {{country.flag}}&nbsp;{{country.country}}&nbsp;{{country.dial_code}}
                                        </option>
                                    </select>
                                    <input id="phone" type="tel" class="form-control" v-model="phone" required>
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
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button id="create_client" type="submit" class="btn btn-primary" @click="handleSubmit">
                                        Update
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
    import {phoneUtil} from '../app'
    import {PNF} from '../app'
    export default {
        props: ['client_id'],
        data() {
            return {
                first_name : "",
                last_name : "",
                phone: "",
                avatar: "",
                email : "",
                errors: [],
                countries: [],
                country_code: '',
                new_avatar: ''
            }
        },
        mounted() {
            this.getCountries();
            $('#dial_code').change(function() {
                $(this).attr("data-original-title", $('[value='+$(this).val()+']').attr('title'));
                $('#dial_code').tooltip('show');
            });
            this.getClientInfo();
        },
        methods : {
            getClientInfo() {
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
                      try {
                          this.country_code = phoneUtil.getRegionCodeForNumber(phoneUtil.parseAndKeepRawInput(response.data.phone, ""));
                      }catch (e) {
                          this.errors.push(e);
                          this.country_code = 'unknown';
                      }
                  })
                  .catch(error => {
                      this.errors = error.response.errors;
                  })
            },
            getCountries() {
                window.axios.get('/api/countries')
                    .then(response => {
                        this.countries = response.data;
                    })
                    .catch(error => {
                        console.log(error.response.statusText)
                    });
            },
            handleSubmit(e) {
                try{
                    var number = phoneUtil.parseAndKeepRawInput(this.phone, this.country_code);
                    phoneUtil.isValidNumber(number);
                    phoneUtil.isValidNumberForRegion(number, this.country_code);
                }catch (error) {
                    return alert(error);
                }
                if (!phoneUtil.isValidNumberForRegion(number, this.country_code)) {
                    return alert('This invalid phone number for selected country');
                }

                e.preventDefault();

                let formData = new FormData();
                formData.append('first_name', this.first_name);
                formData.append('last_name', this.last_name);
                formData.append('phone', phoneUtil.format(number, PNF.E164));
                formData.append('email', this.email);
                formData.append('avatar', this.new_avatar);
                formData.append('id', this.client_id);

                axios.post('/api/user/' + this.client_id, formData, {
                    headers: {'Content-Type': 'multipart/form-data',
                        'Accept': 'application/json',
                        'Authorization': 'Bearer '+localStorage.getItem('bigStore.jwt')
                    }
                })
                    .then(response => {
                        if (response.status === 200) {
                            bus.$emit("refreshPage");
                        }
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors;
                    })
            },
            onFileChanged(e) {
                this.new_avatar = "";
                let files = e.target.files || e.dataTransfer.files;
                this.new_avatar = files[0];
            }
        }
    }
</script>

<style scoped>

</style>