<template>
    <div>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Image</th>
                <th scope="col">Operations</th>
            </tr>
            </thead>
            <tbody v-if="images.length">

            <tr v-for="image in images" v-if="images.length">
                <td>
                    <img :src="'/storage/'+image.link_to_thumb + '?rnd=' + Math.random()"  width="50%">
                </td>
                <td>
                    <input type="file" class="btn btn-sm btn-light" name="image" :id="'image'+image.id" ref="image" @change="onExistsFileChanged($event,image.id)" hidden/>
                    <button class="btn btn-sm btn-light" value="update" @click="browseExistsFileUpdate(image.id)" data-toggle="tooltip" title="Change image">
                        <i class="fa fa-exchange" aria-hidden="true"></i>
                    </button>
                    <button class="btn btn-light btn-sm" @click="destroyImage(image.id)" data-toggle="tooltip" title="Delete">
                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                    </button>
                    <!--<a class="btn btn-light btn-sm" @click="updateImage(image.id)">update</a>-->
                </td>
            </tr>
            </tbody>
        </table>
        <input type="file" multiple class="btn btn-sm btn-light" name="image[]" id="new_image" ref="image" @change="onNewFilesChanged($event)" hidden/>
        <button class="btn btn-light btn-sm" data-toggle="tooltip" title="add new one" onclick="document.getElementById('new_image').click();">
            <i class="fa fa-plus-square-o" aria-hidden="true"></i> New
        </button>
        <div class="col-md-12">
            <div class="attachment-holder animated fadeIn" v-cloak v-for="(image, index) in new_images">
                <span class="label label-primary">{{ image.name + ' (' + Number((image.size / 1024 / 1024).toFixed(1)) + 'MB)'}}</span>
                <button class="btn btn-light btn-sm" @click="dropFromNewImages(image)" data-toggle="tooltip" title="Delete">
                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                </button>
            </div>
        </div>
        <button v-if="new_images.length" class="btn btn-light btn-sm" @click="appendImagesToProduct()" data-toggle="tooltip" title="Append">
            <i class="fa fa-check-square-o" aria-hidden="true">OK</i>
        </button>
        <ul v-if="errors != null">
            <li v-for="error in errors" class="alert-danger"> {{error.toString()}}</li>
        </ul>
    </div>
</template>

<script>

    import {bus} from '../app';
    export default {
        props: ['product_id'],
        name: "ImageManagerComponent",
        data() {
            return {
                images:[],
                image_to_change:'',
                new_images:[],
                errors:[],
            }
        },
        mounted() {
            this.getImages(this.product_id);
        },
        methods: {
            getImages(id) {
                window.axios.get('/product_images', {
                    params: {
                        'product_id': id,
                    }
                })
                .then(response => {
                    console.log(response.data);
                    this.errors = [];
                    this.images = response.data;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    console.log(error.response.statusText);
                })

            },
            destroyImage(id) {
                window.axios.post('/product_images/'+id, {
                    "_method": 'DELETE',
                    product_image: id,
                })
                .then(response => {
                    console.log(response);
                    this.errors = [];
                    this.getImages(this.product_id);
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    console.log(error.response.statusText);
                })
            },
            onExistsFileChanged(e, id) {
                this.image_to_change = '';
                this.image_to_change = e.target.files[0];
                this.updateImage(id);
                console.log(e.target.files[0]);
            },
            onNewFilesChanged(e) {
                this.new_images = [];
                var files = e.target.files || e.dataTransfer.files;

                console.log(files);
                if (!files.length)
                    return;
                for (var i = files.length - 1; i >= 0; i--) {
                    this.new_images.push(files[i]);
                }
                console.log(this.new_images);
            },
            updateImage(id) {
                let formData = new FormData();
                formData.append('image', this.image_to_change);
                formData.append('id', id);
                formData.append('_method', 'PUT');

                window.axios.post('/product_images/'+id, formData, {headers: {'Content-Type': 'multipart/form-data'}}
                )
                .then(response => {
                    console.log(response.data);
                    this.image_to_change = '';
                    this.errors = [];
                    this.getImages(this.product_id);
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    console.log(error.response);
                });

            },
            browseExistsFileUpdate(id) {
                $("#image"+id).click();
            },
            appendImagesToProduct() {
                let formData = new FormData();
                formData.append('product_id', this.product_id);

                for (var i =0 ; i < this.new_images.length; i++)
                {     let image = this.new_images[i];
                    formData.append('images['+i+']', image);
                }
                window.axios.post('/product_images',
                    formData,
                    {headers: {'Content-Type': 'multipart/form-data'}}
                )
                .then(response => {
                    console.log(response);
                    this.errors = [];
                    this.new_images=[];
                    this.getImages(this.product_id);
                })
                .catch(error => {
                    console.log(error.response.data.errors);
                    this.message = '';
                    this.errors = error.response.data.errors;
                });
            },
            dropFromNewImages(image) {
                this.new_images.splice(this.new_images.indexOf(image),1);
                this.errors = [];
            },
        }
    }
</script>

<style scoped>

</style>
