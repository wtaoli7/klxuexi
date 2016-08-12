/**
 * Created by DW on 2016/4/7.
 */
var articleList={

    getArticleList:function(params){
        ajaxTools($('#dateurl').val(),"get","html",params,articleList.getArticleListCallBack);
    },
    getArticleListCallBack:function(data){
        $('#articleList').empty();
        $('#articleList').append(data);
    },

    queryArticleList:function () {
        var searchStr = $('#searchStr').val();
        // articleList.getArticleList({searchStr:searchStr});
        $href = $('#dateurl').val()+ "/searchStr/" + searchStr;
        location.href = $href;
    },

    deleteId:null,
    deleteArticle:function(articleId,url){
        if(confirm("确定删除？")){
            articleList.deleteId = articleId;
            ajaxTools(url,"post","json",{articleId:articleList.deleteId},articleList.deleteArticleCallBack);
        }
    },
    deleteArticleCallBack:function(data){
        if(data.status==0){
            $("#"+articleList.deleteId+"_tr").remove();
            alert("删除成功！");
        }else{
            alert("异常，请联系管理员!");
        }
    },

    isPublicId:null,
    publicArticle:function(articleId,url){
            articleList.isPublicId = articleId;
            var isPublic = $('#'+articleId+'_btn').attr('ispublic');
            ajaxTools(url,"post","json",{articleId:articleList.isPublicId,isPublic:isPublic},articleList.publicArticleCallBack);
    },
    publicArticleCallBack:function(data){
        if(data.status==0){
             $('#'+articleList.isPublicId+'_btn').attr('ispublic',data.isPublic);
            $("#"+articleList.isPublicId+"_btn").text((data.isPublic==0?"发布":"停止"));
            alert("操作成功！");
        }else{
            alert("操作异常，"+data.msg+"请联系管理员!");
        }
    },
    getAddress:function (address) {
       alert(address);
    }
}

$(function(){
    // articleList.getArticleList({});
});
