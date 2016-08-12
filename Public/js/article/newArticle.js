/**
 * Created by DW on 2016/4/7.
 */
<!-- 实例化编辑器 -->
window.UEDITOR_HOME_URL = '__ROOT__/Data/Ueditor/';
$(function () {
    window.UEDITOR_CONFIG.initialFrameHeight = 600;
    window.UEDITOR_CONFIG.initialFrameWidth = 1200;
    var ue = UE.getEditor('content');
    ue.addListener("ready", function () {
        // editor准备好之后才可以使用
        article.getArticle();
    });
});
var article={
    getArticle:function(){
           if($('#linktype').val()!=1){
               return;
           }else{
               $('#linkAddress').val("");
           }

            var id = $("#id").val();
            if(id){
                ajaxToolsWithErr($("#path").val(),"get","html",{articleId:id},article.getArticleCallBack,function(xhr,msg,e){
                            alert('文章未找到！异常：'+msg);
                        });
            }

    },
    getArticleCallBack:function(pp){
        if(pp){
                UE.getEditor('content').execCommand('insertHtml', pp);
        }else{
            alert("异常，请联系管理员!");
        }
    },
    changeActivity:function(){
        if($('#isActivity').prop("checked")){
            $('#activity').show();
        }else{
            $('#activity').hide();
        }

    }
}

$(function(){
    // article.changeActivity();
});