window.Vue = require('vue');
import Vuex from 'vuex';
Vue.use(Vuex);

import Categories from './modules/Admin/Categories';
import CategoriesList from './modules/Admin/Categories/List';
import Filters from './modules/web/filters';
import ProductAtWeb from "./modules/web/product_at_web";
import PublicationsAtWeb from './modules/web/publications';
import WebLoading from "./modules/web/loading";

const store = new Vuex.Store({
    modules : {
        Categories,
        CategoriesList,
        Filters,
        ProductAtWeb,
        PublicationsAtWeb,
        WebLoading,
    },
});

//Mediator(store);

export default store;