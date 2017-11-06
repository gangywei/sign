<template>
    <div>
        <el-row :gutter="24">
          <el-col :span="20"  :offset="2"><div class="grid-content bg-purple">
                 <el-menu :default-active="activeIndex" class="el-menu-demo" mode="horizontal">
                    <el-menu-item index="1" @click="$router.push('/sign')">签到首页</el-menu-item>
                    <el-menu-item index="2" v-if="id!=null" @click="$router.push('/signMag')">签到统计</el-menu-item>
                    <el-menu-item index="3" v-if="id!=null && role!='用户' && role!=undefined" @click="$router.push('/userMag')">用户管理</el-menu-item>
                </el-menu>
                <keep-alive>
                    <router-view v-if="$route.meta.keepAlive" v-on:viewIn="uRole" v-on:login="uLogin"></router-view>
                </keep-alive>
                <router-view v-if="!$route.meta.keepAlive" v-on:viewIn="uRole" v-on:login="uLogin"></router-view>
          </div></el-col>
        </el-row>

    </div>
</template>
<script scoped>
    export default {
        data(){
            return {
                activeIndex: '1',
                user:{},
                role:'用户',
                id:null,
            }
        },
        components: {},
        computed: {},
        methods: {
            uRole(index){
                if(this.activeIndex!=index)
                    this.activeIndex = index
                if(this.id==null)
                    this.$router.push('/sign')
            },
            uLogin(id,role){
                this.id = id
                this.role = role
            },
            autoLogin(){
                let self = this
                self.id = localStorage.getItem('sign_id')
                if(self.id!=null){
                    axios.post("auto_login/"+self.id).then(function(response){
                        if(response.data.code == 1){
                            self.user = response.data.msg
                            self.role = response.data.msg.u_role
                            localStorage.setItem('sign_id',self.user.id)
                        }else if(response.data.code == 0){
                            self.$message({
                              message: response.data.msg,
                              type: 'warning'
                            });
                        }
                    });
                }
            },
        },
        mounted() {
            this.autoLogin()
        },
    }
</script>
<style scoped>
.el-menu-item{
    font-size: 20px;
}
.el-menu-demo{margin-bottom: 30px;}

</style>