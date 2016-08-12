var teacher={

    showBranch:function(){
        if(!$('#buId').val()){
            alert('请先选择团队！');
            return;
        }
        $('#selectSchool').modal('toggle');
    },
    changeBu:function(){
        var buId = $('#buId').val();
        $('#branchId option:not([parentId='+buId+'])').hide();
        $('#branchId option[parentId='+buId+']').show();
        if(!buId){
            $('#branchId option:visible:first').attr('selected','selected');
        }
    },

    save:function(){
        $('#teacherform').submit();
    },
    selectGrades:function(){
        var tgrades='';
        $('#grades option').each(
            function(){
                if(tgrades!=''){
                    tgrades = tgrades+','+$(this).val();
                }else{
                    tgrades =$(this).val();
                }
            }
        );
        $('#tgrades').val(tgrades);
        $('#selectGrades').modal('toggle');
    },
    selectSubjects:function(){
        var tsubjects='';
        $('#subjects option').each(
            function(){
                if(tsubjects!=''){
                    tsubjects = tsubjects+','+$(this).val();
                }else{
                    tsubjects =$(this).val();
                }
            }
        );
        $('#tsubjects').val(tsubjects);
        $('#selectSubjects').modal('toggle');
    },

    selectedBranchs:function(){
        var tbranchs='';

        $('#branchs option').each(
            function(){
                if(tbranchs!=''){
                    tbranchs = tbranchs+','+$(this).val();
                }else{
                    tbranchs =$(this).val();
                }
            }
        );
        $('#tbranchs').val(tbranchs);
        $('#selectSchool').modal('toggle');
    },
}

jQuery(document).ready(function($) {
    $('.js-multiselect').multiselect({
        right: '#grades',
        rightAll: '#js_right_All_1',
        rightSelected: '#js_right_Selected_1',
        leftSelected: '#js_left_Selected_1',
        leftAll: '#js_left_All_1'
    });

    $('.js-multiselect_teacher').multiselect({
        right: '#subjects',
        rightAll: '#js_right_All_1_teacher',
        rightSelected: '#js_right_Selected_1_teacher',
        leftSelected: '#js_left_Selected_1_teacher',
        leftAll: '#js_left_All_1_teacher'
    });

    $('.js-multiselect_branch').multiselect({
        right: '#branchs',
        rightAll: '#js_right_All_1_branch',
        rightSelected: '#js_right_Selected_1_branch',
        leftSelected: '#js_left_Selected_1_branch',
        leftAll: '#js_left_All_1_branch'
    });
    teacher.changeBu();
});