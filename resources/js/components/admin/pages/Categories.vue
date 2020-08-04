<template>
  <div>
    <div class="content">
      <div class="container-fluid">
      
        <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
        <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
          <p class="_title0">
            Categories
            <Button type="dashed" @click="addCategoryModal=true">
              <Icon type="md-add-circle" />Add Categories
            </Button>
          </p>

          <div class="_overflow _table_div">
            <table class="_table">
              <!-- TABLE TITLE -->
              <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Category Name</th>
                <th>Created At</th>
                <th>Action</th>
              </tr>
              <!-- TABLE TITLE -->

              <!-- ITEMS -->

              <tr v-for="(category,index) in categories" :key="index" v-if="categories.length">
                <td>{{category.id}}</td>
                <td class="table_image"><img :src="'/uploads/'+category.iconImage" alt="image"></td>
                <td class="_table_name">{{category.categoryName}}</td>
                <td>{{category.created_at}}</td>
                <td>
                  <Button type="info" size="small" @click="showEditModal(tag,index)"><Icon type="ios-create" /></Button>
                  <Button type="error" size="small" @click="showDeleteModal(tag, index)"><Icon type="ios-trash" /></Button>
                </td>
              </tr>
            </table>
          </div>
        </div>

    <!--adding category modal -->
                  <Modal
                          v-model="addCategoryModal"
                          title="Add Category"
                          :mask-closable="false"
                          :closable="false"
                        
                        >
                        
                          <Input v-model="categoryName" placeholder="Tag Name" />
                          <div class="space"></div>
                          <Upload
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

                              <div class="image_thumb" v-if="iconImage">
                                <img :src="`/uploads/${iconImage}`" alt="">
                              </div>
                          </Upload>

                          <div slot="footer">
                            <Button type="primary" @click="addCategory" :disabled="isAdding" :loading="isAdding">{{isAdding? 'Adding...' : 'Add Category'}}</Button>
                            <Button type="error" @click="addCategoryModal=false">Close</Button>
                          </div>
                  </Modal>
    <!--end add category modal -->

            <!--edit tag modal -->
                  <Modal
                        v-model="editTagModal"
                        title="Edit Tag"
                        :mask-closable="false"
                        :closable="false"
                      
                      >
                      
                        <Input v-model="editData.tagName" placeholder="Tag Name" @keyup.enter.stop="editTag" />
                      

                        <div slot="footer">
                          <Button type="primary" @click="editTag" :disabled="isAdding" :loading="isAdding">{{isAdding? 'Editing...' : 'Edit Tag'}}</Button>
                          <Button type="error" @click="editTagModal=false">Close</Button>
                        </div>
                    </Modal>
            <!--end edit category modal -->

        <!---delete category modal --->

          <Modal v-model="deleteTagModal" width="360">
                <p slot="header" style="color:#f60;text-align:center">
                    <Icon type="ios-information-circle"></Icon>
                    <span>Delete confirmation</span>
                </p>
                <div style="text-align:center">
                    <p>Do you want to delete this tag?</p>
                    <p>It will be deleted permanently!</p>
                </div>
                <div slot="footer">
                    <Button type="error" size="large" long :loading="deleteModalLoading" @click="deleteTag">{{deleteModalLoading ? 'Deleting...' : 'Delete Tag'}}</Button>
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
    
     this.getCategories()
   
  },

  data: function () {
    return {
      token:{},
      categoryName: '',
      iconImage:'',
      addCategoryModal: false,
      isAdding:false,
      categories:[],
      editTagModal:false,
      deleteTagModal:false,
      deleteModalLoading:false,
      editData:{
         tagName:''
      },
      deleteData:{},
      index:-1,
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

        addCategory:function(){
          this.isAdding=true
           axios.post(`/app/add_category`,{
             categoryName : this.categoryName,
             iconImage :this.iconImage
           }).then((res)=>{
             if(res.status ==201){
              this.categories.unshift(res.data)
              this.isAdding=false
              this.success('Category has been added successfully')
              this.addCategoryModal=false
              
             }else{
               this.info(res.status)
             }
           })
        },

         getCategories(){
           axios.get(`/get_categories`)
             .then(res=>{
               if(res.status == 200){
                this.categories =res.data
               }
               
             })
             .catch(err=>{
               this.errordef()
             })
         },
         editTag:function(){
           axios.post(`/edit_tag`,{
             id: this.editData.id,
             tagName: this.editData.tagName
           }
           )
            .then(res=>{
              if(res.status == 200){
                this.tags =res.data
                this.success('Tag has been updated Successfully!')
                this.editTagModal =false

              }else{
                this.warning('Tag was not updated!')
              }
            })
            .catch(err=>{
              this.errordef()
            })
         },

         showEditModal:function(tag,index){
           let obj ={
             id: tag.id,
             tagName: tag.tagName
           }
           this.editData =obj
           this.editTagModal =true
         },
        
        showDeleteModal:function(tag,index){
         
           this.deleteData =tag
           this.index =index
           this.deleteTagModal =true
        },

        deleteTag:function(){
          this.deleteModalLoading =true
          
          axios.post(`/delete_tag`,{
            id:this.deleteData.id
          })
            .then(res=>{
              if(res.status == 200){
                this.tags =res.data
                this.deleteModalLoading =false
                this.deleteTagModal =false
                 this.success('Tag Deleted Successfuly!')
              }else{
                this.errordef()
              }
               
            })
            .catch(err=>{
              this.deleteModalLoading =false
              this.deleteTagModal =false
             this.warning(err.response.statusText)
            })

             
        }

        
   }
};
</script>

<style>
</style>