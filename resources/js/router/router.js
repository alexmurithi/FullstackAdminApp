import Vue from 'vue';
import VueRouter from 'vue-router';

import Home from '../components/Home.vue';
import Tags from '../components/admin/pages/Tags.vue';
import Categories from '../components/admin/pages/Categories.vue';
import Users from '../components/admin/pages/Users.vue';
Vue.use(VueRouter);

export const router =new VueRouter({
    mode:'history',
    routes:[
        {
           path:'/',
           component:Home
        },
        {
            path:'/tags',
            component:Tags
        },
        {
          path:'/categories',
          component:Categories
      },
      
      {
        path:'/users',
        component:Users
    }
    
    ]
})