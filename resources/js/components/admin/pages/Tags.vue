<template>
  <div>
    <div class="content">
      <div class="container-fluid">
      
        <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
        <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
          <p class="_title0">
            Tags
            <Button type="dashed" @click="addTagModal=true">
              <Icon type="md-add-circle" />Add Tag
            </Button>
          </p>

          <div class="_overflow _table_div">
            <table class="_table">
              <!-- TABLE TITLE -->
              <tr>
                <th>ID</th>
                <th>Tag Name</th>
                <th>Created At</th>
                <th>Action</th>
              </tr>
              <!-- TABLE TITLE -->

              <!-- ITEMS -->

              <tr v-for="(tag,index) in tags" :key="index" v-if="tags.length">
                <td>{{tag.id}}</td>
                <td class="_table_name">{{tag.tagName}}</td>
                <td>{{tag.created_at}}</td>
                <td>
                  <Button type="info" size="small" @click="showEditModal(tag,index)"><Icon type="ios-create" /></Button>
                  <Button type="error" size="small" @click="showDeleteModal(tag, index)"><Icon type="ios-trash" /></Button>
                </td>
              </tr>
            </table>
          </div>
        </div>

        <!--adding tag modal -->
        <Modal
                v-model="addTagModal"
                title="Add Tag"
                :mask-closable="false"
                :closable="false"
               
              >
              
                <Input v-model="tagName" placeholder="Tag Name" />
              

                <div slot="footer">
                  <Button type="primary" @click="addTag" :disabled="isAdding" :loading="isAdding">{{isAdding? 'Adding...' : 'Add Tag'}}</Button>
                  <Button type="error" @click="addTagModal=false">Close</Button>
                </div>
        </Modal>

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

        <!---delete tag modal --->

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
                <Button type="error" size="large" long :loading="deleteModalLoading" @click="deleteTag">{{deleteModalLoading ? 'deleting...' : 'Delete Tag'}}</Button>
            </div>
    </Modal>

        <!-- <Page :total="30" /> -->
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "tags",

  mounted(){
   this.getTags()
   
  },

  data: function () {
    return {
      tagName: "",
      addTagModal: false,
      isAdding:false,
      tags:[],
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
         addTag:function(){
          
            
            axios.post(`/tag/create`,{
              tagName: this.tagName
            })
             .then((res)=>{
               if(res.status==201){
                   this.tags.unshift(res.data)
                   this.success('Tag has been added Successfully');
                   this.addTagModal= false;
                   this.tagName = ''
               } 
                
             })
             .catch((err)=>{
                if(err.response.status ==422){
                 if(err.response.data.errors.tagName){
                   this.error(err.response.data.errors.tagName[0])
                 }
                }else{
                  this.errordef()
                }
             })
           
         },

         getTags(){
           axios.get(`/get_tags`)
             .then(res=>{
               if(res.status == 200){
                this.tags =res.data
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
                 this.success('Tag has been deleted Successfuly!')
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