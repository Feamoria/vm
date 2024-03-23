$(document).ready(function () {
    $('#tabs').tabs().bind('click', function (event) {	// Событие при переключении на таб
        if (typeof event.target.hash !=='undefined') {
            console.log(event.target.hash);
            if (event.target.hash ==='#tabs-2') {
                initmultiselect('pers_tag');
                initmultiselect('pers_tem');
            }
        }
    })
    /*EVENT*/
    let ev_tem = 'ev_tem';
    let ev_tag = 'ev_tag';
    let ev_pers = 'ev_pers';
    initmultiselect(ev_tem);
    initmultiselect(ev_tag);
    initmultiselect(ev_pers)
    update_person();
    $('#ev_Y_n,#ev_M_n,#ev_D_n,#ev_Y_e,#ev_M_e,#ev_D_e').on('keyup',function (e){
        this.value = this.value.replace(/\D/g,'');
    })
    $('#ev_Desc_short').on('keyup',function (e){
        let text=$(this).val();
        if (text.length> 300) {
            $(this).val(text.substring(0, 300));
        }
        $('#ev_Desc_short_COUNT').html(text.length+'/'+'300')
    })
    inittag('ev');
    $('#ev_btn_send').on('click',function (e){
        e.preventDefault();
        /*TODO ВАЛИДАЦИЯ
        *  +  datepic.datepicker("option", "dateFormat", "dd.mm.yy");  "yy-mm-dd"
        * */
        let data=$('#event').serialize();
        $.ajax({
            type: 'POST',
            url: 'set.php?event',
            //data: JSON.stringify(parameters),
            data: data,
            //contentType: 'application/json;',
            dataType: 'json',
            cache: false,
            success: function(data) {
                // do something with ajax data

            }
        });
    })
    /*PERS*/
    $('#pers_date1,#pers_date2').datepicker({
        showOtherMonths: true,
        selectOtherMonths: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd.mm.yy',
    });
    initmultiselect('pers_tag');
    initmultiselect('pers_tem');
    inittag('pers');
    $('#pers_Desc').on('keyup',function (e){
        let text=$(this).val();
        if (text.length> 1000) {
            $(this).val(text.substring(0, 1000));
        }
        $('#pers_Desc_short_COUNT').html(text.length+'/'+'1000')
    })
    $('#pers_btn_send').on('click',function (e){
        e.preventDefault();
        /*TODO ВАЛИДАЦИЯ
        * + datepic.datepicker("option", "dateFormat", "dd.mm.yy");
        * */
        let data=$('#pers').serialize();
        $.ajax({
            type: 'POST',
            url: 'set.php?pers',
            //data: JSON.stringify(parameters),
            data: data,
            //contentType: 'application/json;',
            dataType: 'json',
            cache: false,
            success: function(data) {
                update_person();
                // do something with ajax data

            }
        });
    })

})
function update_person() {
    let html='';
    $.ajax({
        type: 'POST',
        url: 'get.php?person',
        //contentType: 'application/json;',
        dataType: 'json',
        cache: false,
        success: function(data) {
            console.log(data);
            $.each(data, function(key, value){
                html+='<option value="'+value.id+'">'+value.F+' '+value.I+' '+value.O+'</option>';
            })
            $('#ev_pers').html(html).multiselect('refresh');
            // do something with ajax data
        }
    });

}
function search_status(elem) {
    let id_checked = [];
    $('[name="multiselect_' + elem + '"]').each(function () {
        //console.log(this);
        let x = this.checked;
        let y = this.value;
        let z = this.disabled;
        if (x && !z) id_checked.push(y);
    })
    $('#' + elem).val(id_checked);
}
function inittag(elem) {
    $('#'+elem+'_tag_add_btn').on('click', function (e) {
        e.preventDefault();
        let tag=$('#'+elem+'_tag_add');
        $('#'+elem+'_tag').append('<option>' + tag.val() + '</option>').multiselect('refresh');
        $('#'+elem+'_tag_ms').addClass('form-control');
        tag.val('');
    })
}
function initmultiselect(elem) {
    let buttonW='80%';
    let menuWidth='80%';
    if (elem.indexOf('_tag') >0){
         buttonW='60%';
         menuWidth='80%';
    }
    $('#' + elem).multiselect({
        buttonWidth: buttonW, // (integer | string | 'auto' | null) Sets the min/max/exact width of the button.
        menuWidth: menuWidth, // (integer | string | 'auto' | null) If a number is provided, sets the exact menu width.

        //header: ['Всё', 'Ничего'],
        noneSelectedText: '--',
        selectedText: '#',
        selectedList: 5,
        click: function (event, ui) {
            search_status(elem)
        },
        checkAll: function () {
            search_status(elem)
        },
        uncheckAll: function () {
            search_status(elem)
        },
        open: function () {
           // $('.ui-multiselect-menu').css({'z-index': '999999', 'width': '200px'});
        },
    }).change(function () {
        //  $("#" + z_table)[0].triggerToolbar();
    }).multiselect('refresh');
}