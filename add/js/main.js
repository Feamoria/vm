$(document).ready(function () {
    $('#tabs').tabs().bind('click', function (event) {	// Событие при переключении на таб
        if (typeof event.target.hash !=='undefined') {
            console.log(event.target.hash);
            if (event.target.hash ==='#tabs-1') {
                initmultiselect('ev_pers');
                initmultiselect('ev_tag');
                initmultiselect('ev_tem');
            }
            if (event.target.hash ==='#tabs-2') {
                initmultiselect('pers_tag');
                initmultiselect('pers_tem');
            }
            if (event.target.hash ==='#tabs-4'){
                let data=load_tag();
                let tbl = $("#tbl_tag");
                tbl.html('');
                $.each(data, function (i, v) {
                    tbl.append('<tr><td style="width: 20px"><button type="button" class="btn btn-danger" onclick="delTag('+v.id+')"><i class="bi bi-trash"></i></button></td><td>'+v.Name+'</td></tr>')
                })
            }
            if (event.target.hash ==='#tabs-5'){
                let data=load_sci_field()
                let tbl = $("#tbl_sci_field");
                tbl.html('');
                $.each(data, function (i, v) {
                    tbl.append('<tr><td style="width: 20px"><button type="button" class="btn btn-danger" onclick="delSci_field('+v.id+')"><i class="bi bi-trash"></i></button></td><td>'+v.Name+'</td></tr>')
                })

            }
        }
    })
    /*******************
    /*     EVENT
    /********************/
    initmultiselect('ev_tem');
    initmultiselect('ev_tag');
    initmultiselect('ev_pers')
    update_person('ev_pers');
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
    initTagAjax('#ev_tag',load_tag());
    initTagAjax('#ev_tem',load_sci_field());
    $('#ev_btn_send').on('click',function (e){
        e.preventDefault();
        /* ВАЛИДАЦИЯ
         проверка на заполнение обязательных полей*/
        let ret = false;
        $('#event input, #event textarea, #event select').each(function (){
            console.log(this);
            if ($(this).prop('required')) {
                let temp = $(this).val();
                if (temp ==='' ||temp.length<2){
                    ret=true;
                }
            }
        })
        if (ret)  {
            alert('Заполните обязательные поля!');
            return;
        }
        /**/

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
    /***********************
    /*PERS
    /***********************/
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
    initTagAjax('#pers_tag',load_tag());
    initTagAjax('#pers_tem',load_sci_field());

    $('#pers_Desc').on('keyup',function (e){
        let text=$(this).val();
        if (text.length> 1000) {
            $(this).val(text.substring(0, 1000));
        }
        $('#pers_Desc_short_COUNT').html(text.length+'/'+'1000')
    })
    $('#pers_btn_send').on('click',function (e){
        e.preventDefault();

        /*ВАЛИДАЦИЯ
        /*Валидация даты*/
        let chek=/^\d{2}[./-]\d{2}[./-]\d{4}$/.test($('#pers_date1').val())
        if (!chek) {alert('Заполните дату правильно!');return;}
        let date2=$('#pers_date2').val();
        if (date2.length > 0 ) {
            chek=/^\d{2}[./-]\d{2}[./-]\d{4}$/.test(date2)
            if(!chek) {alert('Заполните дату правильно!');return;}
        }
        let ret=false;
        /*проверка на заполнение обязательных полей*/
        $('#pers input, #pers textarea').each(function (){
             if ($(this).prop('required')) {
                let temp = $(this).val();
                if (temp ==='' ||temp.length<2){
                    ret=true;
                }
            }
        })
        if (ret)  {
            alert('Заполните обязательные поля!');
            return;
        }
        /**/
        let datepic = $('#pers_date1,#pers_date2');
        datepic.datepicker("option", "dateFormat", "yy-mm-dd");
        let data=$('#pers').serialize();
        datepic.datepicker("option", "dateFormat", "dd.mm.yy");
        $.ajax({
            type: 'POST',
            url: 'set.php?pers',
            data: data,
            dataType: 'json',
            cache: false,
            success: function(data) {
                update_person('ev_pers');
            }
        });
    })
    /***********************
     /*TAG
     /***********************/
    $("#tag_add_btn").on('click', function (e){
        e.preventDefault();
        let tag=$('#tag_add').val();
        if (tag.length >3) {
            $.ajax({
                type: 'POST',
                url: 'set.php?tag',
                data: 'tag='+tag,
                dataType: 'json',
                cache: false,
                success: function(data) {
                    console.log(data);
                    if (typeof data.err ==='undefined') {
                        initTagAjax('#ev_tag',data);
                        initTagAjax('#pers_tag',data);
                        let tbl = $("#tbl_tag");
                        tbl.html('');
                        $.each(data, function (i, v) {
                            tbl.append('<tr><td style="width: 20px"><button type="button" class="btn btn-danger" onclick="delTag('+v.id+')"><i class="bi bi-trash"></i></button></td><td>'+v.Name+'</td></tr>')
                        })
                    } else alert(data.err);
                }
            })
        } else alert('Длинна ключевого слова должна быть больше 3')
    })
    /***********************
     /*sci_field // Научное напрвление
     /***********************/
    $("#sci_field_add_btn").on('click', function (e){
        e.preventDefault();
        let sci_field=$('#sci_field_add').val();
        if (sci_field.length >3) {
            $.ajax({
                type: 'POST',
                url: 'set.php?sci_field',
                data: 'sci_field='+sci_field,
                dataType: 'json',
                cache: false,
                success: function(data) {
                    if (typeof data.err ==='undefined') {
                        initTagAjax('#pers_tem',data);
                        initTagAjax('#ev_tem',data);
                        let tbl = $("#tbl_sci_field");
                        tbl.html('');
                        $.each(data, function (i, v) {
                            tbl.append('<tr><td style="width: 20px"><button type="button" class="btn btn-danger" onclick="delSci_field('+v.id+')"><i class="bi bi-trash"></i></button></td><td>'+v.Name+'</td></tr>')
                        })
                    } else alert(data.err);
                }
            })
        } else alert('Длинна ключевого слова должна быть больше 3')
    })
})
function delTag(tag){
    /* !*/
    $.ajax({
        type: 'POST',
        url: 'set.php?tag&del',
        data: 'tag='+tag,
        dataType: 'json',
        cache: false,
        success: function(data) {
            console.log(data);
            if (typeof data.err ==='undefined') {
                let tbl = $("#tbl_tag");
                tbl.html('');
                $.each(data, function (i, v) {
                    tbl.append('<tr><td style="width: 20px"><button type="button" class="btn btn-danger" onclick="delTag('+v.id+')"><i class="bi bi-trash"></i></button></td><td>'+v.Name+'</td></tr>')
                })
            } else alert(data.err);
            // do something with ajax data
        }
    })
}
function delSci_field(sci_field){
    /*TODO !*/
    $.ajax({
        type: 'POST',
        url: 'set.php?sci_field&del',
        data: 'sci_field='+sci_field,
        dataType: 'json',
        cache: false,
        success: function(data) {
            console.log(data);
            if (typeof data.err ==='undefined') {
                let tbl = $("#tbl_sci_field");
                tbl.html('');
                $.each(data, function (i, v) {
                    tbl.append('<tr><td style="width: 20px"><button type="button" class="btn btn-danger" onclick="delSci_field('+v.id+')"><i class="bi bi-trash"></i></button></td><td>'+v.Name+'</td></tr>')
                })
            } else alert(data.err);
        }
    })
}
function load_tag(){
    return $.ajax({
        async:false,
        type: 'POST',
        url: 'get.php?tag',
        //data: 'tag='+tag,
        dataType: 'json',
        cache: false,
        success: function(data) {
            //console.log(data);
            // do something with ajax data
        }
    }).responseJSON;
}
function load_sci_field(){
    return $.ajax({
        async:false,
        type: 'POST',
        url: 'get.php?sci_field',
        //data: 'tag='+tag,
        dataType: 'json',
        cache: false,
        success: function(data) {
            //console.log(data);
            // do something with ajax data
        }
    }).responseJSON;
}
function update_person(elem) {
    let html='';
    $.ajax({
        type: 'POST',
        url: 'get.php?person',
        //contentType: 'application/json;',
        dataType: 'json',
        cache: false,
        success: function(data) {
            console.log(data);
            initTagAjax('#'+elem,data)

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
function initTagAjax(elem,data) {
        let elem_g=$(elem);
        elem_g.html('');
        $.each(data,function (i,v){
            $(elem).append('<option value="'+v.id+'">' + v.Name + '</option>');
        });
        elem_g.multiselect('refresh');
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