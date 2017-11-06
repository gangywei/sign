<template>
<el-table
    :data="tableData"
    style="width: 100%">
    <el-table-column type="expand">
      <template slot-scope="props">
        <el-form label-position="left" inline class="demo-table-expand">
        <el-form-item label="性别">
            <span>{{ props.row.u_sex }}</span>
          </el-form-item>
          <el-form-item label="手机">
            <span>{{ props.row.u_phone }}</span>
          </el-form-item>
          <el-form-item label="班级">
            <span>{{ props.row.u_class }}</span>
          </el-form-item>
          <el-form-item label="状态">
            <span>{{ props.row.u_status }}</span>
          </el-form-item>
        </el-form>
      </template>
    </el-table-column>
    	<el-table-column
      	label="姓名"
      	prop="u_name"
      	width="180">
      	</el-table-column>
      	<el-table-column
      	label="学号"
      	prop="u_number"
      	width="180">
   	</el-table-column>
      	<el-table-column
      	label="用户"
      	prop="u_role"
      	width="180">
   	</el-table-column>
    	<el-table-column label="操作">
	       	<template slot-scope="scope">
		        <el-button
		        size="mini"
		       type="danger"
		       @click="handleDelete(scope.$index,scope.row.id)">删除</el-button>
		       <el-button
	          	size="mini"
	          	type="success"
	          	v-if="scope.row.u_role=='用户'"
	          	@click="handleEdit(scope.row,1)">管理</el-button>
	          	<el-button
	          	size="mini"
	          	type="success"
	          	v-if="scope.row.u_role=='管理员'"
	          	@click="handleEdit(scope.row,0)">成员</el-button>
        	</template>
    	</el-table-column>
  </el-table>
</template>

<script>
export default {
	data(){
		return {
			tableData: [],
			activeIndex: '3',
		}
	},
	created(){
        	this.$emit('viewIn',"3");
        },
	methods:{
		userMag(){
			let self = this
			axios.get("userMag").then(function(response){
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
		handleDelete(index,id){
			this.$confirm('此操作将永久删除该文件, 是否继续?', '提示', {
		          	confirmButtonText: '确定',
		         	cancelButtonText: '取消',
		          	type: 'warning'
		        }).then(() => {
		          	let self = this
				axios.post("userDel/"+id).then(function(response){
		                    if(response.data.code == 1){
		                    	 self.tableData.splice(index, 1)
		                    }else if(response.data.code == 0){
		                        self.$message({
		                          message: response.data.msg,
		                          type: 'warning'
		                        });
		                    }
		                });
		        }).catch(() => {
			       this.$message({
			            	type: 'info',
			            	message: '已取消删除'
			      	});
		        });
		},
		handleEdit(row,type){
			axios.post("userEdit/"+row.id+"/"+type).then(function(response){
	                    if(response.data.code == 1){
	                    	row.u_role = response.data.msg
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
		this.userMag()
	}
}
</script>