import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)

export default new VueRouter({
    saveScrollPosition: true,
    routes: [
        {
            name:"签到",
            path:'/sign',
            component: resolve =>void(require(['./components/sign.vue'], resolve))
        },
        {
            name:"签到统计",
            path:'/signMag',
            component: resolve =>void(require(['./components/signMag.vue'], resolve))
        },
        {
            name:"用户统计",
            path:'/userMag',
            component: resolve =>void(require(['./components/userMag.vue'], resolve))
        },
        {
            name:"主页",
            path:'/main',
            component: resolve =>void(require(['./components/main.vue'], resolve))
        },
    ]
})
