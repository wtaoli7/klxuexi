/**
 * Created by DW on 2016/4/12.
 */
var gradeList = {
   getGradeList:function(params){
       ajaxTools($('#dateurl').val(),"get","html",params,gradeList.getGradeListCallBack);
   },
    getGradeListCallBack:function(data){
        $('#gradeList').empty();
        $('#gradeList').append(data);
        
    },

    queryGradeList:function () {
        var searchStr = $('#searchStr').val();
        gradeList.getGradeList({searchStr:searchStr});
    },
    publicGrade:function(gradeId,isPublic){
        gradeList.isPublicId = gradeId;
        ajaxTools("grade/publicGrade","post","json",{gradeId:gradeList.isPublicId,isPublic:isPublic},gradeList.publicGradeCallBack);
    },
    publicGradeCallBack:function(data){
        if(data.status==0){
            $("#"+gradeList.isPublicId+"_btn").text((data.isPublic==0?"发布":"停止"));
            alert("操作成功！");
        }else{
            alert("操作异常，"+data.msg+"请联系管理员!");
        }
    },
    isDeleteId:null,
    deleteGrade:function(gradeId,gradeName,url){
        if(confirm('确定删除当前年级“'+gradeName+'”？')){
         gradeList.isDeleteId = gradeId;
         ajaxTools(url,"post","json",{gradeId:gradeList.isDeleteId},gradeList.deleteGradeCallBack);
       }
    },
    deleteGradeCallBack:function(data){
        if(data.status==0){
            $("#"+gradeList.isDeleteId+"_tr").remove();
            alert("删除成功！");
        }else{
            alert("操作异常，"+data.msg+"请联系管理员!");
        }
    },
    updateGrade:function(gradeId,displayName){
        $('#id').val(gradeId);
        $('#displayName').val(displayName);
        $('#updateGrade').modal('toggle');
    }
}


$(function(){
       // gradeList.getGradeList({});
});