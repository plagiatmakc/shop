<template>
    <div class="sidebar ">
        <div class="sidebar-header ">
            <h3>Filters</h3>
        </div>

         {{categories}}

        <div class="show dropright">
            <a href="#" class="btn dropdown-toggle" role="button" id="dropdownMenuLinkCategories" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-bars" aria-hidden="true"></i>
                Categories
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuLinkCategories" style="width: 500px">
               <ul style="list-style: none">
                   <li class="dropdown-item dropright" v-for="category in categories">
                           {{category.title}}

                           <dl v-if="category.categories_recursive" style="list-style: none">
                               <dt v-for="subcategory in category.categories_recursive">
                                   {{subcategory.title}}
                                   <dl v-for="subcategory in subcategory.categories_recursive" >
                                       <dd>{{subcategory.title}}</dd>
                                   </dl>
                               </dt>
                           </dl>

                   </li>
               </ul>

                <!--@foreach($categories as $category)-->
                <!--<a class="dropdown-item {{request()->query('category') == $category->id ? 'active': '' }}" href="{{request()->fullUrlWithQuery(['category' => $category->id])}}">{{$category->title}}</a>-->
                <!--@endforeach-->
            </div>
        </div>


    </div>
</template>

<script>
    export default {
        name: "ShopFiltersComponent",
        data() {
          return {
              categories:[],
          }
        },
        mounted() {
            this.getCategories();
        },
        methods: {
            getCategories() {
                window.axios.get('/categories')
                    .then(response => {
                        console.log(response.data);
                        this.categories = response.data;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },

        }
    }
</script>

<style scoped>

</style>
