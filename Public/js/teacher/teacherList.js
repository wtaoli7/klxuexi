/**
 * Created by DW on 2016/4/12.
 */
var teacherList = {
    asynV4:function(url){
       ajaxTools(url,"get","json",{},teacherList.asynV4CallBack);
        // $('#v4syn').submit();
    },

    asynV4CallBack:function(data){
     if(data.status==1){;
         alert('与v4同步成功！');
     }else{
         alert('异常，请联系管理员!'.data.msg);
     }
    },
   getTeacherList:function(params){
       ajaxTools($('#dateurl').val(),"get","html",params,teacherList.getTeacherListCallBack);
   },
    getTeacherListCallBack:function(data){
        $('#teacherList').empty();
        $('#teacherList').append(data);
    },

    queryTeacherList:function () {
        var searchStr = $('#searchStr').val();
        teacherList.getTeacherList({searchStr:searchStr});
    },
    isPublicId:null,
    publicTeacher:function(url,teacherId){
        teacherList.isPublicId = teacherId;
        var isPublic = $('#'+teacherId+'_btn').attr('ispublic');
        ajaxTools(url,"post","json",{teacherId:teacherList.isPublicId,isPublic:isPublic},teacherList.publicTeacherCallBack);
    },
    publicTeacherCallBack:function(data){
        if(data.status==0){
             $('#'+teacherList.isPublicId+'_btn').attr('ispublic',data.isPublic);
            $("#"+teacherList.isPublicId+"_btn").text((data.isPublic==0?"发布":"停止"));
            alert("操作成功！");
        }else{
            alert("操作异常，"+data.msg+"请联系管理员!");
        }
    },
    isDeleteId:null,
    deleteTeacher:function(teacherId,teacherName,url){
        if(confirm('确定删除当前课程“'+teacherName+'”？')){
         teacherList.isDeleteId = teacherId;
         ajaxTools(url,"post","json",{teacherId:teacherList.isDeleteId},teacherList.deleteTeacherCallBack);
       }
    },
    deleteTeacherCallBack:function(data){
        if(data.status==0){
            $("#"+teacherList.isDeleteId+"_tr").remove();
            alert("删除成功！");
        }else{
            alert("操作异常，"+data.msg+"请联系管理员!");
        }
    },
}


$(function(){
       // teacherList.getTeacherList({});
});