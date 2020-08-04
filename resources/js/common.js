export default{

  
  methods:{
    info (desc,title="Info!") {
      this.$Notice.info({
          title: title,
          desc: desc 
      });
  },
  success (desc,title="Great!") {
      this.$Notice.success({
          title: title,
          desc: desc 
      });
  },
  warning (desc,title="Hey!") {
      this.$Notice.warning({
          title: title,
          desc: desc 
      });
  },
  error (desc,title="Error!") {
      this.$Notice.error({
          title: title,
          desc: desc 
      });
  },

  errordef (desc="OOPS! Something went wrong! Please try later",title="Error!") {
    this.$Notice.error({
        title: title,
        desc: desc 
    });
}
  }
}