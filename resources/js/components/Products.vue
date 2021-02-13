<!-- Products index and form. -->

<template>
    <div class="row">
        <a class="btn btn-primary" data-bs-toggle="collapse" href="#add-product" role="button" aria-expanded="false"
           aria-controls="add-product">
            Add Product
        </a>
        <!-- Product form. -->

        <div class="form card mb-4 collapse" id="add-product">
            <form @submit.prevent="addProduct">
                <h2>Product Form</h2>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Name" v-model="product.name">
                </div>
                <div class="form-group">
                    <textarea class="form-control" placeholder="Description" cols="30" rows="10"
                              v-model="product.description"></textarea>
                </div>
                <div class="form-group">
                    <input type="number" class="form-control" placeholder="Price" v-model="product.price">
                </div>
                <div class="form-group">
                    <label>Select Categories</label>
                    <multiple_categories v-model="product.categories">

                    </multiple_categories>
                </div>
                <div class="form-group">
                    <input type="file" accept="image/*" v-on:change="handleFileUpload">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>

            </form>
        </div>

        <!-- Products list. -->

        <div class="card">
            <h2>Products List</h2>
            <div class="pull-right">

                <categories>

                </categories>
                <a href="#" id="name" v-on:click="sortProducts">Name <i class="sort-arrow"></i></a> |
                <a href="#" class="asc" id="price" v-on:click="sortProducts">Price <i class="sort-arrow"></i></a>
            </div>
            <nav aria-label="Page navigation example">
                <paginate
                    v-model="page"
                    :page-count="totalPages"
                    :page-range="1"
                    :margin-pages="2"
                    :click-handler="pageHandler"
                    :prev-text="'Prev'"
                    :next-text="'Next'"
                    :container-class="'pagination'"
                    :page-link-class="'page-link'"
                    :prev-class="'page-link'"
                    :next-class="'page-link'"
                    :disabled-class="'btn disabled'"
                    :page-class="'page-item'">
                </paginate>
            </nav>
            <div class="row">
                <div class="card mb-2 col-4 justify-content-center" v-for="product in products" v-bind:key="product.id">
                    <img :src="product.image" style="max-width: 350px;" class="card-img-top"
                         :alt="product.name">
                    <div class="card-body">
                        <h5 class="card-title float-right">{{ product.price }} $</h5>
                        <h5 class="card-title">{{ product.name }}</h5>
                        <p class="card-text">{{ product.description }}</p>
                    </div>
                </div>
            </div>
            <nav aria-label="Page navigation example">
                <paginate
                    v-model="page"
                    :page-count="totalPages"
                    :page-range="1"
                    :margin-pages="2"
                    :click-handler="pageHandler"
                    :prev-text="'Prev'"
                    :next-text="'Next'"
                    :container-class="'pagination'"
                    :page-link-class="'page-link'"
                    :prev-class="'page-link'"
                    :next-class="'page-link'"
                    :disabled-class="'btn disabled'"
                    :page-class="'page-item'">
                </paginate>
            </nav>
        </div>
    </div>
</template>
<style>
.select2 {
    min-width: 100% !important;
}

.up-arrow {
    position: relative;
    top: -0.8em;
    width: 0;
    height: 0;
    border: solid 5px transparent;
    background: transparent;
    border-bottom: solid 7px black;
    border-top-width: 0;
    cursor: pointer;
}

.down-arrow {
    position: relative;
    top: 1em;
    width: 0;
    height: 0;
    border: solid 5px transparent;
    background: transparent;
    border-top: solid 7px black;
    border-bottom-width: 0;
    cursor: pointer;
}
</style>
<script>
import Categories from './Categories'
import MultipleCategories from './MultipleCategories'
import Paginate from 'vuejs-paginate'

export default {
    components: {
        'categories': Categories,
        'multiple_categories': MultipleCategories,
        'paginate': Paginate,
    },
    data() {
        return {
            page: 1,
            count: 0,
            totalPages: 0,
            products: [],
            sort: 'name',
            sortWay: '',
            product: {
                id: '',
                name: '',
                description: '',
                price: '',
                image: ''
            }
        }
    },
    created() {
        this.fetchProducts();
    },
    methods: {
        /**
         * Add Product Handler
         */
        addProduct() {
            fetch('api/product', {
                method: 'post',
                body: JSON.stringify(this.product),
                headers: {
                    'content-type': 'application/json'
                }
            }).then(res => res.json())
                .then(data => {
                    this.fetchProducts();
                })
                .catch(err => console.log(err));
        },
        /**
         * File to base64 reader
         */
        handleFileUpload(event) {
            let reader = new FileReader();
            let vm = this;
            reader.onload = (e) => {
                vm.product.image = e.target.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        },
        /**
         * Fetch products list
         * @param category
         */
        fetchProducts(category) {
            if (!category && this.category) {
                category = this.category;
            }
            if (category === '*') {
                category = null;
            }
            this.category = category;
            let url = 'api/products?sort=' + this.sortWay + this.sort;
            if (this.category) {
                url += '&category_id=' + this.category;
            }
            if (this.page > 1) {
                url += '&page=' + this.page;
            }
            fetch(url)
                .then(res => res.json())
                .then(res => {
                    this.products = res.data.models;
                    this.count = res.data.count;
                    this.totalPages = res.data.pages;
                }).catch(function (error) {
                console.log('Fetch Error:', error);
            });
        },
        /**
         * Sort products
         * @param event
         */
        sortProducts(event) {
            this.page = 0;
            if (event.target.id === this.sort) {
                this.sortWay = this.sortWay === '' ? '-' : '';
            } else {
                this.sort = event.target.id;
                this.sortWay = '';
            }
            $('.sort-arrow').removeClass('up-arrow').removeClass('down-arrow');
            if (this.sortWay === '-') {
                $(event.target).find('i').addClass('up-arrow');
            } else {
                $(event.target).find('i').addClass('down-arrow');
            }
            this.fetchProducts();
        },
        /**
         * Change page and reload products
         * @param pageNum
         */
        pageHandler(pageNum) {
            this.page = pageNum;
            this.fetchProducts();
            window.scrollTo(0, 0);
        }
    }
}

</script>
