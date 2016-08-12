/**
 * Created by DW on 2016/4/12.
 */
var branchList = {
   getBranchList:function(params){
       ajaxTools($('#dateurl').val(),"get","html",params,branchList.getBranchListCallBack);
   },
    getBranchListCallBack:function(data){
        $('#branchList').empty();
        $('#branchList').append(data);
        
    },

    queryBranchList:function () {
        var searchStr = $('#searchStr').val();
        branchList.getBranchList({searchStr:searchStr});
    },
    publicBranch:function(branchId,isPublic){
        branchList.isPublicId = branchId;
        ajaxTools("branch/publicBranch","post","json",{branchId:branchList.isPublicId,isPublic:isPublic},branchList.publicBranchCallBack);
    },
    publicBranchCallBack:function(data){
        if(data.status==0){
            $("#"+branchList.isPublicId+"_btn").text((data.isPublic==0?"发布":"停止"));
            alert("操作成功！");
        }else{
            alert("操作异常，"+data.msg+"请联系管理员!");
        }
    },
    isDeleteId:null,
    deleteBranch:function(branchId,branchName,url){
        if(confirm('确定删除当前校区“'+branchName+'”？')){
         branchList.isDeleteId = branchId;
         ajaxTools(url,"post","json",{branchId:branchList.isDeleteId},branchList.deleteBranchCallBack);
       }
    },
    deleteBranchCallBack:function(data){
        if(data.status==0){
            $("#"+branchList.isDeleteId+"_tr").remove();
            alert("删除成功！");
        }else{
            alert("操作异常，"+data.msg+"请联系管理员!");
        }
    },
}


$(function(){
       // branchList.getBranchList({});
});