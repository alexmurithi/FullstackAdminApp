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
                  <Button type="info" size="small" @click="showEditModal(category,index)"><Icon type="ios-create" /></Button>
                  <Button type="error" size="small" @click="showDeleteModal(category,index)"><Icon type="ios-trash" /></Button>
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
                          <br>
                          <br>
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

                              
                          </Upload>
                          <div class="image_thumb" v-if="iconImage">
                                <img :src="`/uploads/${iconImage}`" alt="" >
                               
                              </div>

                          <div slot="footer">
                            <Button type="primary" @click="addCategory" :disabled="isAdding" :loading="isAdding">{{isAdding? 'Adding...' : 'Add Category'}}</Button>
                            <Button type="error" @click="addCategoryModal=false">Close</Button>
                          </div>
                  </Modal>
    <!--end add category modal -->

            <!--edit category modal -->
                  <Modal
                    v-model="editCategoryModal"
                    title="Edit Category"
                    :mask-closable="false"
                    :closable="false"
                    ref="uploads"
                  
                  >
                  
                    <Input v-model="editData.categoryName" placeholder="Category Name" />

                    <br>
                    <br>
                          
                          <Upload v-show="isIconImageNew"
                             
                              type="drag"
                              :headers="{'x-csrf-token':token,'X-Requested-With': 'XMLHttpRequest'}"
                              
                               :on-success="handleSuccess"
                               :on-error="handleError"
                               :format="['jpg','jpeg','png','gif']"
                               :on-format-error="handleFormatError"
                               :show-upload-list="false"

                              
                              action="/app/upload_category">
                              <div style="padding: 20px 0">
                                  <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                                  <p>Click or drag files here to upload</p>
                              </div>

                              
                          </Upload>
                          <div class="image_thumb" v-show="editData.iconImage">
                                <img :src="`/uploads/${editData.iconImage}`" alt="">
                                
                                 <Icon type="ios-close" id="closeImg" @click="deleteCatImg" />
                              </div>
                              <div class="image_thumb" v-show="iconImage">
                                <img :src="`/uploads/${iconImage}`" alt="">
                              
                                 <Icon type="ios-close" id="closeImg" @click="deleteCatImg" />
                              </div>
                             
                  

                    <div slot="footer">
                      <Button type="primary" @click="editCategory" :disabled="isEditing" :loading="isEditing">{{isEditing? 'Editing...' : 'Edit Category'}}</Button>
                      <Button type="error" @click="editCategoryModal=false">Close</Button>
                    </div>
          </Modal>
            <!--end edit category modal -->

        <!---delete category modal --->

          <Modal v-model="deleteCategoryModal" width="360">
                <p slot="header" style="color:#f60;text-align:center">
                    <Icon type="ios-information-circle"></Icon>
                    <span>Delete confirmation</span>
                </p>
                <div style="text-align:center">
                    <p>Do you want to delete this category</p>
                    <p>It will be deleted permanently!</p>
                </div>
                <div slot="footer">
                    <Button type="error" size="large" long :loading="deleteModalLoading" @click="deleteCategory">{{deleteModalLoading ? 'Deleting...' : 'Delete Category'}}</Button>
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
        // spinShow: true,
      categoryName: '',
      iconImage:'',
      addCategoryModal: false,
      isEditing:false,
      isAdding:false,
     
      categories:[],
      editCategoryModal:false,
      deleteCategoryModal:false,
      deleteModalLoading:false,
      editData:{
         categoryName:'',
         iconImage:''
      },
      deleteData:{},
      index:-1,
      isIconImageNew:false,
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
              this.categoryName =''
              this.iconImage =''
              
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
      
//show edit category modal //
         showEditModal:function(category,index){
          
           this.editData =category
           this.editCategoryModal =true

           
            if(this.iconImage =='' && this.editData.iconImage ==null){
              this.isIconImageNew =true
            }else{
              this.isIconImageNew =false
            }
         
         
           
            
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

             
        },

//deleting category image//
        deleteCatImg:function(category){
         
          
          axios.post(`/app/deleteCatImg`,{
            img:this.editData.iconImage ?this.editData.iconImage : this.iconImage,
            id:this.editData.id ? this.editData.id : category.id
            
          }).then((res)=>{
            if(res.status == 200){
             this.editData.iconImage =''
             this.iconImage =''
             this.isIconImageNew =true
             this.$refs.uploads.clearFiles()
                          
            
            }
          }).catch(err=>{
            
          })
         
           
        },

        editCategory:function(){
          this.isEditing=true
         axios.post(`/app/editCategory`,{
           categoryName:this.editData.categoryName,
           iconImage:this.editData.iconImage ? this.editData.iconImage : this.iconImage,
           id:this.editData.id
         }).then((res)=>{
             if(res.status==200){
               this.categories =res.data
               this.isEditing=false
               this.editCategoryModal=false
               this.success('Category updated Successfully!')
             }else{
               this.info(res.status + '' + res.statusText)
             }
         }).catch(err=>{
           this.errordef()
         })
        },

        showDeleteModal:function(category,index){
          this.deleteCategoryModal =true
          this.deleteData =category
        },

        deleteCategory:function(){
          this.deleteModalLoading=true
          axios.post(`/app/deleteCategory`,{
            id:this.deleteData.id
          }).then((res)=>{
            if(res.status ==200){
              this.categories =res.data
              this.deleteModalLoading =false
              this.deleteCategoryModal=false
              this.success('Category deleted successfully!')
            }
          }).catch(err=>{
            this.error('Category could not be deleted');
          })
        }

        
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


</style>