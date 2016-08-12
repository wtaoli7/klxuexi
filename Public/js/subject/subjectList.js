/**
 * Created by DW on 2016/4/12.
 */
var subjectList = {
   getSubjectList:function(params){
       ajaxTools($('#dateurl').val(),"get","html",params,subjectList.getSubjectListCallBack);
   },
    getSubjectListCallBack:function(data){
        $('#subjectList').empty();
        $('#subjectList').append(data);
        
    },

    querySubjectList:function () {
        var searchStr = $('#searchStr').val();
        subjectList.getSubjectList({searchStr:searchStr});
    },
    publicSubject:function(subjectId,isPublic){
        subjectList.isPublicId = subjectId;
        ajaxTools("subject/publicSubject","post","json",{subjectId:subjectList.isPublicId,isPublic:isPublic},subjectList.publicSubjectCallBack);
    },
    publicSubjectCallBack:function(data){
        if(data.status==0){
            $("#"+subjectList.isPublicId+"_btn").text((data.isPublic==0?"发布":"停止"));
            alert("操作成功！");
        }else{
            alert("操作异常，"+data.msg+"请联系管理员!");
        }
    },
    isDeleteId:null,
    deleteSubject:function(subjectId,subjectName,url){
        if(confirm('确定删除当前科目“'+subjectName+'”？')){
         subjectList.isDeleteId = subjectId;
         ajaxTools(url,"post","json",{subjectId:subjectList.isDeleteId},subjectList.deleteSubjectCallBack);
       }
    },
    deleteSubjectCallBack:function(data){
        if(data.status==0){
            $("#"+subjectList.isDeleteId+"_tr").remove();
            alert("删除成功！");
        }else{
            alert("操作异常，"+data.msg+"请联系管理员!");
        }
    },
    updateSubject:function(subjectId,displayName){
        $('#id').val(subjectId);
        $('#displayName').val(displayName);
        $('#updateSubject').modal('toggle');
    }
}


$(function(){
       // subjectList.getSubjectList({});
});