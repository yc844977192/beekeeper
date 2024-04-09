<h4>全局搜索：</h4>
 <input id="searchKeyWord" type="text"><button onclick="search()">搜索</button>
<div class="show-search-result">
</div>
<script>
  function search() {
      var  searchKeyWord=$("#searchKeyWord").val();
      var requestUrl = '/admin/api/getSearchResult';
      var requestData = {'pageSize':'10','pageNum':1,'searchKeyWord':searchKeyWord};
      $.ajax({
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          url:requestUrl,
          type:'post',
          async:false,
          data:requestData,
          dataType:'html',
          success:function(res){
              $(".show-search-result").html(res);
          },
          error:function(){
          }
      })
  }
</script>