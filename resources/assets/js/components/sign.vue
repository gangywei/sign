<template>
    <div>
       <el-row :gutter="20">
          <el-col :span="16"><div u_class="grid-content bg-purple">
                <el-card u_class="box-card">
                    <div slot="header" u_class="clearfix">
                        <span>签到信息</span>
                        <el-button style="float: right; padding: 3px 0" v-if="user.u_status=='离线'" type="text" @click="sign(1)">签到</el-button>
                        <el-button style="float: right; padding: 3px 0" v-if="user.u_status=='在线'" type="text" @click="sign(0)">签退</el-button>
                    </div>
                   <el-table
                    :data="signList"
                    style="width: 100%"
                    :row-class-name="tableRowClassName">
                    <el-table-column
                      prop="s_time"
                      label="时间"
                      width="180">
                    </el-table-column>
                    <el-table-column
                      prop="u_id"
                      label="姓名"
                      width="180">
                    </el-table-column>
                    <el-table-column
                      prop="s_totle"
                      label="统计"
                      width="180">
                    </el-table-column>
                    <el-table-column
                      prop="s_type"
                      :filter-method="filterTag"
                      filter-placement="bottom-end"
                      :filters="[{ text: '签到', value: '签到' }, { text: '签退', value: '签退' }]"
                      label="状态">
                    </el-table-column>
                  </el-table>
                </el-card>
          </div></el-col>
          <el-col :span="8"><div u_class="grid-content bg-purple">
                <el-card u_class="box-card">
                    <div slot="header" u_class="clearfix">
                        <span>用户信息</span>
                        <el-button style="float: right; padding: 3px 0" v-if="!user.id" type="text" @click="outerVisible = true">登录</el-button>
                        <el-button style="float: right; padding: 3px 0" v-if="user.id" type="text" @click="loginOut">退出</el-button>
                    </div>
                    <div v-for="(item,index) in msg" v-if="user.id" u_class="text item">
                        {{item}}：{{user[index]}}
                    </div>
                </el-card>

                <el-card u_class="box-card">
                    <div slot="header" u_class="clearfix">
                        <span>公告信息</span>
                    </div>
                    <p>1、每次签到，在线时间最长6个小时</p>
                    <p>2、没有反馈功能，Bug和建议可以给管理员反应</p>
                    <p>3、签到统计-用户记录功能，只能看当前周的详细签到情况</p>
                </el-card>
          </div></el-col>
        </el-row>
        <el-dialog  title="登录功能" :visible.sync="outerVisible">
        <el-form label-width="80px">
            <el-form-item label="输入学号">
              <el-input v-model="user.u_number"  auto-complete="off"></el-input>
            </el-form-item>
          </el-form>
        <div slot="footer" u_class="dialog-footer">
            <el-button type="primary" @click="innerVisible = true,outerVisible = false">注册</el-button>
            <el-button type="primary" @click="login">确 定</el-button>
        </div>
      </el-dialog>
      <el-dialog
          title="注册功能"
          :visible.sync="innerVisible"
          append-to-body>
          <el-form  label-width="50px" :model="user">
                <el-form-item label="学号">
                    <el-input v-model="user.u_number"></el-input>
                </el-form-item>
                <el-form-item label="姓名">
                    <el-input v-model="user.u_name"></el-input>
                </el-form-item>
                <el-form-item label="性别">
                    <el-radio-group v-model="user.u_sex">
                        <el-radio :label="1">男</el-radio>
                        <el-radio :label="0">女</el-radio>
                      </el-radio-group>
                </el-form-item>
                <el-form-item label="班级">
                    <el-input v-model="user.u_class" placeholder="请选择">
                      </el-input>
                </el-form-item>
                <el-form-item label="手机">
                    <el-input v-model="user.u_phone"></el-input>
                </el-form-item>
          </el-form>
          <div slot="footer" u_class="dialog-footer">
            <el-button type="primary" @click="register">确 定</el-button>
        </div>
        </el-dialog>

        <div class="block">
            <el-pagination layout="prev, pager, next" :page-size="12" :total="totle" @current-change="getLog">
            </el-pagination>
        </div>

        <el-dialog
          title="补签"
          :visible.sync="dialogVisible"
          width="30%">
          <span>上次签到时间为{{beforeTime}}</span><p></p>
          <el-date-picker
            v-model="nowTime"
            type="datetime"
            value-format="yyyy-MM-dd HH:mm:ss"
            placeholder="选择日期时间">
          </el-date-picker>
          <span slot="footer" class="dialog-footer">
            <el-button type="primary" @click="retroactive">确 定</el-button>
          </span>
        </el-dialog>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                msg:{'u_name':'姓名','u_number':'学号','u_sex':'性别','u_class':'班级','u_phone':'手机','u_role':'角色','u_status':'状态'},
                user:{'u_number':'','u_name':'','u_sex':1,'u_class':'','u_phone':''},
                outerVisible: false,
                innerVisible: false,
                dialogVisible: false,
                page:1,
                signList:[],
                totle:0,
                beforeTime:'',
                nowTime: null
            }
        },
        created(){
          this.$emit('viewIn',"1");
        },
        methods:{
            filterTag(value, row) {
                return row.s_type === value;
            },
            retroactive(){
                if(this.nowTime!=null)
                  this.sign(0)
                else{
                    this.$message({
                      message: '补签时间为必填项',
                      type: 'warning'
                    });
                    return
                }
            },
            register(){
                let self = this
                var u_name = /^[\u4E00-\u9FA5]{1,6}$/;
                var u_number = /^\d{10,11}$/;
                var u_phone = /^1\d{10}$/;
                if(!u_number.test(self.user.u_number)||self.user.u_number==''){
                    self.$message({
                      message: '学号为10位或者11位',
                      type: 'warning'
                    });
                    return
                }else if(!u_name.test(self.user.u_name)||self.user.u_name==''){
                    self.$message({
                      message: '姓名必须为中文',
                      type: 'warning'
                    });
                    return
                }else if(self.user.u_class==''){
                    self.$message({
                      message: '班级为必填项',
                      type: 'warning'
                    });
                    return
                }else if(!u_phone.test(self.user.u_phone)||self.user.u_phone==''){
                    self.$message({
                      message: '手机号为11位',
                      type: 'warning'
                    });
                    return
                }
                axios.post("register",self.user).then(function(response){
                    if(response.data.code == 1){
                        self.user = response.data.msg
                        self.user.u_role = '成员'
                        self.user.u_status = '离线'
                        self.innerVisible = false
                        localStorage.setItem('sign_id',self.user.id)
                        localStorage.setItem('u_role',self.user.u_role)
                    }else if(response.data.code == 0){
                        self.$message({
                          message: response.data.msg,
                          type: 'warning'
                        });
                    }
                });
            },
            tableRowClassName({row, rowIndex}) {
                if (row.s_type == '签退') {
                    return 'warning-row';
                }else{
                    return 'success-row';
                }
            },
            login(){
                let self = this
                var u_number = /^\d{10,11}$/
                 if(!u_number.test(self.user.u_number)||self.user.u_number==''){
                    self.$message({
                      message: '学号为10位或者11位',
                      type: 'warning'
                    });
                    return
                }
                axios.post("login/"+self.user.u_number).then(function(response){
                        if(response.data.code == 1){
                            self.user = response.data.msg
                            localStorage.setItem('sign_id',self.user.id)
                            self.outerVisible = false
                            self.$emit('login',self.user.id,self.user.u_role)
                        }else if(response.data.code == 0){
                            self.$message({
                              message: response.data.msg,
                              type: 'warning'
                            });
                        }
                });
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
            loginOut(){
                localStorage.removeItem('sign_id')
                localStorage.removeItem('u_role')
                this.$emit('login',null,'用户');
                this.user = {}
            },
            // type=0 表示签退，type=1表示签到
            sign(type){
                let self = this
                axios.post("sign/"+type,{now_time:self.nowTime}).then(function(response){
                        if(response.data.code == 1){
                            if(type==1){
                                self.user.u_status = "在线"
                            }else{
                                self.user.u_status = "离线"
                            }
                            self.nowTime = null
                            self.dialogVisible = false
                            self.signList.splice(0,0,response.data.msg)
                        }else if(response.data.code == 0){
                            self.$message({
                              message: response.data.msg,
                              type: 'warning'
                            });
                        }else if(response.data.code == 2){
                            if(!self.dialogVisible){
                                self.dialogVisible = true
                                self.beforeTime = response.data.msg
                            }else{
                                self.$message({
                                  message: "补签时间大于6个小时",
                                  type: 'warning'
                                });
                            }
                        }
                    });
            },
            //签到首页的分页显示数据
            getLog(val){
                let self = this
                axios.get("getLog?page="+val).then(function(response){
                        if(response.data.code == 1){
                            self.signList = response.data.msg.data
                            self.totle = response.data.msg.total
                        }else if(response.data.code == 0){
                            self.$message({
                              message: response.data.msg,
                              type: 'warning'
                            });
                        }
                    });
            }
        },
        mounted() {
            this.getLog(1)
            this.autoLogin()
        }
    }
</script>
<style>
.el-pager li{
  font-size: 32px;
}
.block{padding-top: 20px;}
.el-dialog--small{
    width: 30% !important;
}
.el-select{
    width: 100%;
}
.el-table .warning-row {
  background: oldlace;
}

.el-table .success-row {
  background: #f0f9eb;
}
</style>
