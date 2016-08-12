/**
 * Created by DW on 2016/4/12.
 */
var pictureList = {
    changeCity:function() {
        var cityId = $('#cityId option:selected').val();
        $('#buId option').each(function(){
                 var parentId = $(this).attr('parentId');
                 if(!parentId){
                     $(this).show();
                     return;
                 }
                 if(parentId != cityId){
                      $(this).hide();
                 }else{
                     $(this).show();
                 }
          }
        );
/*        $('#buId option:not([parentId='+cityId+'])').hide();
        $('#buId option[parentId='+cityId+']').show();*/
        $('#buId option:visible:first').attr('selected','selected');
        pictureList.changeBu();
    },

    changeBu:function(){
        var buId = $('#buId option:selected').val();
        $('#branchId option').each(function(){
                var parentId = $(this).attr('parentId');
                if(!parentId){
                    $(this).show();
                    return;
                }
                if(parentId != buId){
                    $(this).hide();
                }else{
                    $(this).show();
                }
            }
        );

/*        $('#branchId option:not([parentId='+buId+'])').hide();
        $('#branchId option[parentId='+buId+']').show();*/
        $('#branchId option:visible:first').attr('selected','selected');
    },

    addPicture:function(){
        if(!$('#photo').val()){
            alert('请选择图片！');
            return;
        }
        $.ajaxFileUpload({
            url:"picture/addPicture",
            secureuri : false,
            fileElementId:'photo',
            dataType:"json",
            data:{buId:$('#buId').val(),cityId:$('#cityId').val(),branchId:$('#branchId').val(),pictureName:$('#pictureName').val(),pictureType:$('#pictureType').val()},
            type:'post',
            success: pictureList.addPictureCallBack
        });
    },
    addPictureCallBack:function(data){
        if(data.status==0){
            $('#addpicture').modal('toggle');
            alert("保存成功！");
            pictureList.getPictureList({});
        }else{
            alert("保存异常，"+data.msg+"请联系管理员!");
        }
    },

    getPictureList:function(params){
       ajaxTools($('#dateurl').val(),"get","html",params,pictureList.getPictureListCallBack);
   },
    getPictureListCallBack:function(data){
        $('#pictureList').empty();
        $('#pictureList').append(data);
    },

    queryPictureList:function () {
        var searchStr = $('#searchStr').val();
        pictureList.getPictureList({searchStr:searchStr});
    },
    isPublicId:null,
    publicPicture:function(pictureId){
        pictureList.isPublicId = pictureId;
        var isPublic = $('#'+pictureId+'_btn').attr('ispublic');
        ajaxTools("picture/publicPicture","post","json",{pictureId:pictureList.isPublicId,isPublic:isPublic},pictureList.publicPictureCallBack);
    },
    publicPictureCallBack:function(data){
        if(data.status==0){
             $('#'+pictureList.isPublicId+'_btn').attr('ispublic',data.isPublic);
            $("#"+pictureList.isPublicId+"_btn").text((data.isPublic==0?"发布":"停止"));
            alert("操作成功！");
        }else{
            alert("操作异常，"+data.msg+"请联系管理员!");
        }
    },
    isDeleteId:null,
    deletePicture:function(cityId,buId,branchId,pictureType){
        if(confirm('确定删除当前图片集合？')){
         pictureList.isDeleteId = ""+cityId+buId+branchId+pictureType;
         ajaxTools("picture/deletePicture","post","json",{cityId:cityId,buId:buId,branchId:branchId,pictureType:pictureType},pictureList.deletePictureCallBack);
       }
    },
    deletePictureCallBack:function(data){
        if(data.status==0){
            $("#"+pictureList.isDeleteId+"_tr").remove();
            alert("删除成功！");
        }else{
            alert("操作异常，"+data.msg+"请联系管理员!");
        }
    }
}


$(function(){
       // pictureList.getPictureList({});
       // pictureList.changeCity();
});