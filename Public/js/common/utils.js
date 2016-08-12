/**
 * Created by DW on 2016/4/7.
*/
function ajaxTools(url,method,dataType,params,callbackFun){
    $.ajax({
        url:url,
        type:method,
        data:params,
        dataType:dataType,
        success:function(data){
           callbackFun(data);
        },
        error:function (xhr,msg,e) {
            alert(e+msg);
        }
    })
}


function ajaxToolsWithErr(url,method,dataType,params,callbackFun,callbackErrFun) {
    $.ajax({
        url: url,
        type: method,
        data: params,
        dataType: dataType,
        success: function (data) {
            callbackFun(data);
        },
        error: function (xhr, msg, e) {
            callbackErrFun(xhr, msg, e);
        }
    })
}