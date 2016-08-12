/**
 * Created by DW on 2016/4/7.
 */
<!-- 实例化编辑器 -->
window.UEDITOR_HOME_URL = '__ROOT__/Data/Ueditor/';
$(function () {
    var editorOption = {
        //focus时自动清空初始化时的内容
        autoClearinitialContent: true,
        //关闭elementPath
        elementPathEnabled: false
    };
    var ue = new baidu.editor.ui.Editor(editorOption);
    ue.render('content');
    ue.addListener("ready", function (){
       var desc_link_re=$("#desc_link_re").val();
        // editor准备好之后才可以使用
        if(desc_link_re){
            course.getCourseDescLink();
        }
    });
});
var course={
    getCourseDescLink:function(){
        var id = $("#id").val();
        if(id){
            ajaxTools($("#desc_link").val(),"get","html",{articleId:id},course.getCourseDescLinkCallBack);
        }
    },
    getCourseDescLinkCallBack:function(pp){
        if(pp){
            UE.getEditor('content').execCommand('insertHtml', pp);
        }else{
            alert("异常，请联系管理员!");
        }
    },
    selectTeachers:function(){
        var cteachers='';
        $('#teachers option').each(
            function(){
                if(cteachers!=''){
                    cteachers = cteachers+','+$(this).val();
                }else{
                    cteachers =$(this).val();
                }
            }
        );
        $('#cteachers').val(cteachers);
        $('#selectTeachers').modal('toggle');

    },
    searchTeacher:function(){
        ajaxTools($('#teacherDataUrl').val(),"get","html",{searchName:$('#searchTeacher').val()},course.searchTeacherCallBack);
    },

    searchTeacherCallBack:function(data){
        $('#from').empty();
        $('#from').append(data);
    },
}

jQuery(document).ready(function($) {
    $('.js-multiselect').multiselect({
        right: '#teachers',
        rightAll: '#js_right_All_1',
        rightSelected: '#js_right_Selected_1',
        leftSelected: '#js_left_Selected_1',
        leftAll: '#js_left_All_1'
    });

});