


function themphoihop(){
    var macanbo =  [];



    $(".CBphongban1:checked").each(function(){
        macanbo.push($(this).val());

    });

    $("#don_vi_nhan").val(macanbo).trigger("change");
}


function xoatatca(){
    console.log(1);
    $('#don_vi_nhan').val('').trigger("change");
    $( ".CBphongban1" ).prop( "checked", false );
    $( ".checkboxall1" ).prop( "checked", false );
}


function docheckall1(){


    // $('input.loaiPB1id:checkbox').not('input[name=checkall1]').prop('checked', 'input[name=checkall1].checked');
    $(document).on('change','input[name=checkall1]',function(){
        $('input.loaiPB1:checkbox').not(this).prop('checked', this.checked);
    });

}




function docheckall2(){
    // $('input.loaiPB2id:checkbox').not('input[name=checkall2]').prop('checked', 'input[name=checkall2].checked');
    $(document).on('change','input[name=checkall2]',function(){
        $('input.loaiPB2:checkbox').not(this).prop('checked', this.checked);
    });
}
function docheckall3(){
    // $('input.loaiPB3id:checkbox').not('input[name=checkall3]').prop('checked', 'input[name=checkall3].checked');
    $(document).on('change','input[name=checkall3]',function(){
        $('input.loaiPB3:checkbox').not(this).prop('checked', this.checked);
    });
}


