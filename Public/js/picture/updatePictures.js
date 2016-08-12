var picture={
    changeCity:function() {
        var cityId = $('#cityId_h').val();
        $('#buId option:not([parentId='+cityId+'])').hide();
        $('#buId option[parentId='+cityId+']').show();
        if(!cityId){
            $('#buId option:visible:first').attr('selected','selected');
        }

        picture.changeBu();
    },

    changeBu:function(){
        var buId = $('#buId_h').val();
        $('#branchId option:not([parentId='+buId+'])').hide();
        $('#branchId option[parentId='+buId+']').show();
        if(!buId){
            $('#branchId option:visible:first').attr('selected','selected');
        }
    },
    getPictureList:function(){
        ajaxTools($('#module').val()+'/picture/getUpdatePicturesList',"get","html",{buId:$('#buId_h').val(),cityId:$('#cityId_h').val(),branchId:$('#branchId_h').val(),pictureType:$('#pictureType_h').val()},picture.getPictureListCallBack);
    },
    getPictureListCallBack:function(data){
        $('#updatePictureList').empty();
        $('#updatePictureList').append(data);
    },

    showUpdate:function(pictureId,cityId,buId,branchId,pictureType,pictureName){
        $('#photo').val('');
        $('#pictureId').val(pictureId);
        $('#cityId').val(cityId);
        $('#buId').val(buId);
        $('#branchId').val(branchId);
        $('#pictureType').val(pictureType);
        $('#pictureName').val(pictureName);
        $('#updatepicture').modal('toggle');
    },
    updatePicture:function(module,root){
        $.ajaxFileUpload({
            url:module+'/picture/updatePicture',
            secureuri : false,
            fileElementId:'photo',
            dataType:"json",
            data:{id:$('#pictureId').val(),buId:$('#buId').val(),cityId:$('#cityId').val(),branchId:$('#branchId').val(),pictureName:$('#pictureName').val(),pictureType:$('#pictureType').val()},
            type:'post',
            success: function(data){
                if(data.status==0){
                    alert("保存成功！");
                    picture.getPictureList({});
                    $('#updatepicture').modal('toggle');
                }else{
                    alert("保存异常，"+data.msg+"请联系管理员!");
                }
            },error: function (data, status, e)
            {
                //这里处理的是网络异常，返回参数解析异常，DOM操作异常
                alert("上传发生异常");
            }
        });
    },
    isDeleteId:null,
    deletePicture:function(){
        var pictureId = $('#pictureId').val()
        if(confirm('确定删除当前图片？')){
            picture.isDeleteId = pictureId;
            ajaxTools($('#module').val()+"/picture/deletePicture","post","json",{id:pictureId},picture.deletePictureCallBack);
        }
    },
    deletePictureCallBack:function(data){
        if(data.status==0){
            $("#"+picture.isDeleteId+"_tr").remove();
            alert("删除成功！");
            $('#updatepicture').modal('toggle');
        }else{
            alert("操作异常，"+data.msg+"请联系管理员!");
        }
    }
}

jQuery(document).ready(function($) {
   picture.getPictureList();
    picture.changeCity();
});