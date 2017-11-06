<template>
  	<el-tabs type="border-card" value='1'>
		<el-tab-pane label="签到统计" name='1' key='1'>
		  	<el-date-picker
			      v-model="time"
			      type="daterange"
			      @change = "getSign"
			      range-separator="至"
			      start-placeholder="开始日期"
			      end-placeholder="结束日期"
			      value-format="yyyy-MM-dd">
			</el-date-picker>
			<el-table
			    :data="tableData"
			    style="width: 100%"
			    :row-class-name="tableRowClassName">
			    <el-table-column
			      prop="u_number"
			      label="学号"
			      width="180">
			    </el-table-column>
			    <el-table-column
			      prop="u_name"
			      label="姓名"
			      width="180">
			    </el-table-column>
			    <el-table-column
			      prop="totle"
			      label="统计"
			      width="180">
			    </el-table-column>
			    <el-table-column
			      prop="u_status"
			      label="状态">
			    </el-table-column>
			  </el-table>
		</el-tab-pane>

	  	<el-tab-pane label="用户记录" name='2' key='2'>
	  		<el-collapse value="1">
				  <el-collapse-item title="成员" name="1">
				  	<span v-for="us in user">
				  		<el-tooltip disabled effect="dark" content="mmasdasd" placement="right">
				  			<el-button size="medium" @click="changeUser(us.id)">{{us.u_name}}</el-button>
				  		</el-tooltip>
				  	</span>
				  </el-collapse-item>
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
				  <div class="block">
			            <el-pagination layout="prev, pager, next" :page-size="12" :total="totle" @current-change="userSign">
			            </el-pagination>
			        </div>
			</el-collapse>
	  	</el-tab-pane>
	</el-tabs>
</template>

<script>
export default {
	props: ['role'],
	data(){
            return {
            	id:'',
            	time: [getWeekStartDate(),getWeekEndDate()],
            	user: [],
            	tableData: [],
            	signList:[],
            	totle:0,
            	page:1,
            }
        },
        created(){
        	this.$emit('viewIn',"2");
        },
	methods:{
		filterTag(value, row) {
		              return row.s_type === value;
		},
		signMsg(){
			let self = this
			axios.get("signMag").then(function(response){
	                    if(response.data.code == 1){
	                    	  self.tableData = response.data.msg;
	                    }else if(response.data.code == 0){
	                        self.$message({
	                          message: response.data.msg,
	                          type: 'warning'
	                        });
	                    }
	                });
		},
		tableRowClassName({row, rowIndex}) {
		        if (row.s_type == '签退'||row.u_status == '离线') {
	                    return 'warning-row';
	                }else{
	                    return 'success-row';
	                }
		        return '';
		},
		getSign(){
			let self = this
			axios.post("getSign",{'time':self.time}).then(function(response){
				console.log(self.time)
	                    if(response.data.code == 1){
	                    	self.tableData = response.data.msg;
	                    }else if(response.data.code == 0){
	                        self.$message({
	                          message: response.data.msg,
	                          type: 'warning'
	                        });
	                    }
	               });
		},
		changeUser(id){
			this.id = id
			this.userSign(1,id)
		},
		userSign(val){
			let self = this
			axios.post("userSign/"+self.id+"?page="+val).then(function(response){
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
		},
		getUser(){
			let self = this
			axios.get("getUser").then(function(response){
	                    if(response.data.code == 1){
	                    	self.user = response.data.msg;
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
		this.signMsg()
		this.getUser()
	},
	watch:{}
}
var now = new Date(); //当前日期
var nowDayOfWeek = now.getDay(); //今天本周的第几天
var nowDay = now.getDate(); //当前日
var nowMonth = now.getMonth(); //当前月
var nowYear = now.getYear(); //当前年
nowYear += (nowYear < 2000) ? 1900 : 0; //
function formatDate(date) {
	var myyear = date.getFullYear();
	var mymonth = date.getMonth()+1;
	var myweekday = date.getDate();
	if(mymonth < 10)
		mymonth = "0" + mymonth;
	if(myweekday < 10)
		myweekday = "0" + myweekday;
	return (myyear+"-"+mymonth + "-" + myweekday);
}
//获得本周的开端日期
function getWeekStartDate() {
	var weekStartDate = new Date(nowYear, nowMonth, nowDay - nowDayOfWeek+1);
	return formatDate(weekStartDate);
}
//获得本周的停止日期
function getWeekEndDate() {
	var weekEndDate = new Date(nowYear, nowMonth, nowDay + (7 - nowDayOfWeek));
	return formatDate(weekEndDate);
}
</script>

<style>
.el-table .warning-row {
  background: oldlace;
}

.el-table .success-row {
  background: #f0f9eb;
}
.el-button--medium{margin: 5px 10px 0 0;}
</style>