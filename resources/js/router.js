import Router from 'vue-router'
import Member from './views/Member.vue'

export default new Router({
    mode: 'history',
    routes: [
        {
            path: '/member',
            name: 'member',
            component: Member
        },

    ]
});