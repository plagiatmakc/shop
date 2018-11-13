<template>
    <div>
        <ul v-if="images.length">
            <li v-for="image in images" v-if="images.length"><img :src="'storage/'+image.link_to_thumb" width="50%">
            <a class="btn btn-light btn-sm" @click="deleteImage(image.id)">delete</a>
                <input type="file" name="image" id="image" ref="image" @change="onFileChanged($event)" />
            <a class="btn btn-light btn-sm" @click="updateImage(image.id)">update</a>

            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        props: ['product_id'],
        name: "ImageManagerComponent",
        data() {
            return {
                images:[],
                image_tmp:'',
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
                    this.images = [];
                    this.images = response.data;
                })
                .catch(error => {
                    console.log(error.response.statusText);
                })

            },
            deleteImage(id) {
                window.axios.post('/product_images/'+id, {
                    "_method": 'DELETE',
                    product_image: id,
                })
                .then(response => {
                    console.log(response);
                    this.getImages(this.product_id);
                })
                .catch(error => {
                    console.log(error.response.statusText);
                })
            },
            onFileChanged(e) {
                this.image_tmp = '';
                this.image_tmp = e.target.files[0];
                console.log(e.target.files[0]);

            },
            updateImage(id) {
                let formData = new FormData();
                formData.append('image', this.image_tmp);
                formData.append('id', id);
                formData.append('_method', 'PUT');

                window.axios.post('/product_images/'+id, formData, {headers: {'Content-Type': 'multipart/form-data'}}
                )
                .then(response => {
                    console.log(response.data);
                    this.image_tmp = '';
                    this.getImages(this.product_id);

                })
                .catch(error => {
                    console.log(error.response);
                })
            }
        }
    }
</script>

<style scoped>

</style>
