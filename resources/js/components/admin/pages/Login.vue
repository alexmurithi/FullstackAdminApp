<template>
  <div>
      <div class="container-fluid">
        <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20 col-md-4 mx-auto my-5">
            <div class="card border-0 bg-white">
                <h2 class="card-header bg-white">Login</h2>

                <div class="card-body">
                    <div class="form-group row mx-2">
                           <Input type="email" v-model="logins.email" placeholder="Email" />
                </div>

                <div class="form-group row mx-2">
                           <Input type="password" v-model="logins.password" password placeholder="Password" />
                </div>
                </div>

               
                <div class="card-footer bg-white text-center">
                  <Button type="primary" :disabled="logging" :loading="logging" @click="login">{{logging ? 'logging in ..' : 'Login'}}</Button>
                </div>
            </div>
               
        </div>
      </div>
  </div>
</template>

<script>
export default {
   data:function(){
     return{
       logins:{
         email:'',
         password:'',
       },
       logging: false,
     }
   },

   methods:{
       login:function(){
          this.logging =true
         
          axios.post(`/app/admin_login`,{
            email : this.logins.email,
            password : this.logins.password
          }).then((res)=>{
              if(res.status==200 && res.data.msg == 'logged in'){
                this.logging =false
                this.success('Logged in successfully!')
              }
             
          }).catch((err)=>{
             if(err.response.status ==401){
               this.logging =false
             this.error(err.response.data.msg)
             }else if(err.response.status ==422){
                for(let i in err.response.data.errors){
                  this.logging=false
                  this.warning(err.response.data.errors[i][0])
                }
             }
            
          })
       }
   },
}
</script>

<style>

</style>