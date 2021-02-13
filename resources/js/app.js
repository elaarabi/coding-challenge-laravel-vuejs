require('./bootstrap');
import Vue from 'vue';
import Products from './components/Products'
import Navbar from './components/Navbar'


new Vue({
    el: '#app',
    components:{
        Products,
        Navbar
    }
});
