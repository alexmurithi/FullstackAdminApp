<template>
  <div>
    <div class="content">
      <div class="container-fluid">
      
        <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
        <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
          <p class="_title0">
            Users
            <Button type="dashed" @click="createUserModal=true">
              <Icon type="md-add-circle" />Add User
            </Button>
          </p>

          <div class="_overflow _table_div">
           
            <table class="_table" v-if="users" >
              <!-- TABLE TITLE -->
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>User Type</th>
                <th>Created At</th>
                <th>Action</th>
              </tr>
              <!-- TABLE TITLE -->

              <!-- ITEMS -->

              <tr v-for="(user,index) in users" :key="index" v-if="users.length">
                <td>{{user.id}}</td>
                
                <td class="_table_name">{{user.fullname}}</td>
                <td >{{user.email}}</td>
                <td>{{user.userType}}</td>
                <td>{{user.created_at}}</td>
                <td>
                  <Button type="info" size="small" @click="editUser(user,index)"><Icon type="ios-create" /></Button>
                  <Button type="error" size="small" @click="deleteUser(user,index)"><Icon type="ios-trash" /></Button>
                </td>
              </tr>
            </table>
          </div>
          
        </div>

    <!--adding User modal -->
                  <Modal
                          v-model="createUserModal"
                          title="Add User"
                          :mask-closable="false"
                          :closable="false"
                          width="600"
                        
                        >
                        <div class="form-group row mx-2">
                           <Input v-model="user.fullName" placeholder="Full Name" />
                        </div>
                         <div class="form-group row mx-2">
                           <Input type="email" v-model="user.email" placeholder="Email" />
                        </div>

                        <div class="form-group row mx-2">
                           <Input type="password" v-model="user.password" placeholder="Password" />
                        </div>

                        <div class="form-group row mx-2">
                           <Select v-model="user.userType" clearable >
                             <Option  value="Admin" >Admin</Option>
                             <Option  value="User" >User</Option>
                           </Select>
                        </div>
                         
                          <!-- <Upload
                              multiple
                              type="drag"
                              :headers="{'x-csrf-token':token,'X-Requested-With': 'XMLHttpRequest'}"
                              
                               :on-success="handleSuccess"
                               :on-error="handleError"
                               :format="['jpg','jpeg','png','gif']"
                               :on-format-error="handleFormatError"
                               

                              
                              action="/app/upload_category">
                              <div style="padding: 20px 0">
                                  <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                                  <p>Click or drag files here to upload</p>
                              </div>

                              
                          </Upload> -->
                          <!-- <div class="image_thumb" v-if="iconImage">
                                <img :src="`/uploads/${iconImage}`" alt="" >
                               
                              </div> -->

                          <div slot="footer">
                            <Button type="primary" @click="createUser" :disabled="isAdding" :loading="isAdding">{{isAdding? 'creating...' : 'Create User'}}</Button>
                            <Button type="error" @click="createUserModal=false">Close</Button>
                          </div>
                  </Modal>
    <!--end add category modal -->

            <!--edit category modal -->
                <Drawer
                    v-model="editUserModal"
                    title="Edit User"
                    :mask-closable="false"
                    :styles="styles"
                    width="720"
                  >
              <Form :model="editData">
                <Row :gutter="32">
                    <Col span="12">
                        <FormItem label="Name" label-position="top">
                            <Input prefix="ios-contact" v-model="editData.fullname"  />
                        </FormItem>
                    </Col>
                    <Col span="12">
                        <FormItem label="Email" label-position="top">
                            <Input prefix="ios-mail" v-model="editData.email" />
                            
                        </FormItem>
                    </Col>
                </Row>
                
                <Row :gutter="32">
                    <Col span="12">
                        <FormItem label="Password" label-position="top">
                            <Input prefix="ios-lock" type="password" password v-model="editData.password" />
                        </FormItem>
                    </Col>
                    <Col span="12">
                        <FormItem label="Confirm Password" label-position="top">
                            <Input prefix="ios-lock" type="password" password v-model="editData.confirmPassword" />
                            
                        </FormItem>
                    </Col>
                </Row>

                <Row :gutter="32">
                  <Col span="24">
                    <FormItem label="User Type" label-position="top">
                           <Select v-model="editData.userType" clearable >
                             <Option value="Admin">Admin</Option>
                             <Option value="Author">Author</Option>
                             <Option value="User">User</Option>
                          </Select> 
                            
                        </FormItem>
                  </Col>
                </Row>

              </Form>
                   


                    <div class="footer">
                      <Button type="primary" @click="editThisUser" :disabled="isEditing" :loading="isEditing">{{isEditing? 'Editing...' : 'Edit User'}}</Button>
                      <Button type="error" @click="editUserModal=false">Close</Button>
                    </div>
                </Drawer>
            <!--end edit category modal -->

        <!---delete category modal --->

          <Modal v-model="deleteUserModal" width="360">
                <p slot="header" style="color:#f60;text-align:center">
                    <Icon type="ios-information-circle"></Icon>
                    <span>Delete confirmation</span>
                </p>
                <div style="text-align:center">
                    <p>Do you want to delete this User?</p>
                    <p>It will be deleted permanently!</p>
                </div>
                <div slot="footer">
                  
                    <Button type="error" size="large" long :loading="deleteModalLoading" @click="deleteThisUser">{{deleteModalLoading ? 'Deleting...' : 'Delete User'}}</Button>
                </div>
          </Modal>
        <!-- end delete category modal -->
        
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "categories",

  mounted(){
    this.token =window.Laravel.csrfToken
    
     this.getUsers();
   
  },

  data: function () {
    return {
      token:{},
        spinShow: true,
      categoryName: '',
      iconImage:'',
      createUserModal: false,
      isEditing:false,
      isAdding:false,
      editUserModal:false,
      deleteUserModal:false,
      deleteModalLoading:false,
      editData:{
        
      },
      deleteData:{},
     
      user:{
        fullName:'',
        email:'',
        password:'',
        userType:'Admin'
      },
     
      users:[],
      styles: {
                    height: 'calc(100% - 55px)',
                    overflow: 'auto',
                    paddingBottom: '53px',
                    position: 'static'
                },
    }
  },
  
   methods:{
        handleSuccess (res, file) {
               this.iconImage =res;
            },

        handleError(res,file){
          console.log(file)
           this.$Notice.warning({
                    title: 'The file format is incorrect',
                    desc: `${file.errors.file.length ? file.errors.file[0] : 'OOPS! Something went wrong!'}`
                });
        },

        handleFormatError (file) {
                this.$Notice.warning({
                    title: 'The file format is incorrect',
                    desc: 'File format of ' + file.name + ' is incorrect, please select jpg or png.'
                });
            },

        createUser:function(){
          this.isAdding=true
           axios.post(`/app/createUser`,{
             fullName : this.user.fullName,
             email :this.user.email,
             password: this.user.password,
             userType: this.user.userType
           }).then((res)=>{
             if(res.status ==201){
              this.users.unshift(res.data)
              this.isAdding=false
              this.success('User has been created successfully')
              this.createUserModal=false
              this.user.fullName =''
              this.user.email =''
              this.user.password =''
              this.user.userType ='Admin'
              
             }
           }).catch(err=>{
            if(err.response.status ==422){
              for(let i in err.response.data.errors){
                this.isAdding=false
                 this.warning(err.response.data.errors[i][0])
              }
              
            }
           })
        },

         getUsers(){
         
           axios.get(`/app/users`)
             .then(res=>{
               if(res.status == 200){
                this.users =res.data
               
               }
               
             })
             .catch(err=>{
               this.error(err.response.statusText + ' could not fetch users')
             })
         },

         editUser:function(user,index){
         
          this.editData =user
           this.editUserModal=true
         },

         editThisUser:function(){
           this.isEditing=true

           axios.post(`/app/editUser`,{
            id: this.editData.id,
            fullname: this.editData.fullname,
            email:this.editData.email,
            userType: this.editData.userType,
            password:this.editData.password,
            confirmPassword: this.editData.confirmPassword 

           }).then((res)=>{
              if(res.status ==200){
                this.isEditing =false
                this.users =res.data
                this.success('User has been updated Successfully')
                this.editUserModal =false
                
              }
           }).catch(err=>{
              if(err.response.status ==422){
                  for(let i in err.response.data.errors){
                  this.isEditing=false
                  this.warning(err.response.data.errors[i][0])
                }
              }else{
                this.isEditing =false
                this.warning(err.response.statusText)
              }
           })
         },

         deleteUser:function(user,index){
           this.deleteData =user
           this.deleteUserModal =true
         },


        deleteThisUser:function(){
          this.deleteModalLoading =true
          
          axios.post(`/app/deleteUser`,{
            id:this.deleteData.id
          })
            .then(res=>{
              if(res.status == 200){
                this.users =res.data
                this.deleteModalLoading =false
                this.deleteUserModal =false
                 this.success('User Deleted Successfuly!')
              }else{
                this.errordef()
              }
               
            })
            .catch(err=>{
              this.deleteModalLoading =false
              this.deleteTagModal =false
             this.warning(err.response.statusText + ' User could not be deleted')
            })

             
        },


        
   }
};
</script>

<style lang="scss" scoped>
.image_thumb{
  position: relative;
   #closeImg{
  position: absolute;
  top:5px;
  right: 8px;
  background: black;
  opacity: 0.6;
  font-size: 22px;
  font-weight: bold;
  height: 25px;
  width: 25px;
  border-radius: 50%;
  color: white !important;
  cursor: pointer;
}
#closeImg:hover{
  background: black;
  opacity: 1;
}
}

.footer{
   width: 100%;
        position: absolute;
        bottom: 0;
        left: 0;
        border-top: 1px solid #e8e8e8;
        padding: 10px 16px;
        text-align: right;
        background: #fff;
}

</style>