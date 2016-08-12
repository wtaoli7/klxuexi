/**
 * Created by DW on 2016/4/12.
 */
var courseList = {
    asynV4:function(url){
        ajaxTools(url,"get","json",{},courseList.asynV4CallBack);
    },

    asynV4CallBack:function(data){
        if(data.status==1){;
            alert('与v4同步成功！');
        }else{
            alert('异常，请联系管理员!'.data.msg);
        }
    },
   getCourseList:function(params){
       ajaxTools($('#dateurl').val(),"get","html",params,courseList.getCourseListCallBack);
   },
    getCourseListCallBack:function(data){
        $('#courseList').empty();
        $('#courseList').append(data);
    },

    queryCourseList:function () {
        var searchStr = $('#searchStr').val();
        courseList.getCourseList({searchStr:searchStr});
    },
    publicCourse:function(url,courseId){
        courseList.isPublicId = courseId;
        var isPublic = $('#'+courseId+'_btn').attr('ispublic');
        ajaxTools(url,"post","json",{courseId:courseList.isPublicId,isPublic:isPublic},courseList.publicCourseCallBack);
    },
    publicCourseCallBack:function(data){
        if(data.status==0){
            $('#'+courseList.isPublicId+'_btn').attr('ispublic',data.isPublic);
            $("#"+courseList.isPublicId+"_btn").text((data.isPublic==0?"发布":"停止"));
            alert("操作成功！");
        }else{
            alert("操作异常，"+data.msg+"请联系管理员!");
        }
    },
    isRecommendId:null,
    recommendCourse:function(url,courseId){
        courseList.isRecommendId = courseId;
        var isRecommend =   $('#'+courseList.isRecommendId+'_recommend').attr('isrecommend');
        ajaxTools(url,"post","json",{courseId:courseList.isRecommendId,isRecommend:isRecommend},courseList.recommendCourseCallBack);
    },
    recommendCourseCallBack:function(data){
        if(data.status==0){
             $('#'+courseList.isRecommendId+'_recommend').attr('isrecommend',data.isRecommend);
            $("#"+courseList.isRecommendId+"_recommend").text((data.isRecommend==0?"推荐":"不推荐"));
            alert("操作成功！");
        }else{
            alert("操作异常，"+data.msg+"请联系管理员!");
        }
    },
    isDeleteId:null,
    deleteCourse:function(courseId,courseName,url){
        if(confirm('确定删除当前课程“'+courseName+'”？')){
         courseList.isDeleteId = courseId;
         ajaxTools(url,"post","json",{courseId:courseList.isDeleteId},courseList.deleteCourseCallBack);
       }
    },
    deleteCourseCallBack:function(data){
        if(data.status==0){
            $("#"+courseList.isDeleteId+"_tr").remove();
            alert("删除成功！");
        }else{
            alert("操作异常，"+data.msg+"请联系管理员!");
        }
    },
}


$(function(){
       // courseList.getCourseList({});
});