let timerId;
let first = true;
/** TODO НЕЗАБЫТЬ ВЕРНУТЬ НА ПРОДЕ!*/
let test = false;
let cache_tag = {};
let cache_pers = {};
$(document).ready(function () {
    if (!test)
        timerId = setInterval(GetOnline, 0);
    $( '#file_doc,#ev_doc,#ev_Name,#ev_Y_n,#ev_Desc,#div_ev_file,' +
        '#pers_Desc,#pers_awards,#pers_publications,#div_persFio,#pers_date1,#pers_dol' ).tooltip({
        position: {
            my: "center bottom-20",
            at: "left top",
            using: function( position, feedback ) {
                $( this ).css( position );
                $( "<div>" )
                    .addClass( "arrow" )
                    .addClass( feedback.vertical )
                    .addClass( feedback.horizontal )
                    .appendTo( this );
            }
        }
    });
    $('#my_event').on('click',function (e,ui){
        updateEvent(load_event('dep='+$(this).prop('checked')));
    })
    $('#my_pers').on('click',function (e,ui){
        updatePerson(load_person('dep='+$(this).prop('checked')));
    })
    $('#my_file').on('click',function (e,ui){
        let search=null;
        if ($(this).prop('checked') === true) {
            search='dep='+$(this).prop('checked');
        }
        updateFile(load_file(search));
    })
    $('#dialog_del').dialog({
        modal: true,
        resizable: false,
        autoOpen: false
    });
    $('#dialogImportance').dialog({
        modal: true,
        resizable: false,
        autoOpen: false,
        width: 1000,
    });
    $('#importance').on('click',function () {
        $('#dialogImportance').dialog('open');
    })
    let dialog = $('#dialog_file').dialog({
        autoOpen: false,
        width: 1400,
        height: 600,
        modal: true,
        buttons: {
            "Выбрать файлы": function () {
                let chk = [];
                let text = $('#ev_file_text');
                text.html('');
                let sel = $('#ev_file');
                sel.html('');
                $('#dialog_file_cont .form-check-input').each(function () {
                    if ($(this).prop('checked')) {
                        let id = $(this).val();
                        chk.push(id);
                        text.append("<a target='_blank' href='" + data_file.GET[id].pathWeb + "'>[" + data_file.GET[id].name + "] </a>  ")
                        sel.append('<option value="' + id + '">' + id + '</option>');
                    }

                })
                sel.val(chk);
                dialog.dialog("close");
            },
            "Отмена": function () {
                dialog.dialog("close");
            }
        },
    })
    $('#tabs').tabs().bind('click', function (event) {	// Событие при переключении на таб
        if (typeof event.target.hash !== 'undefined') {
            console.log(event.target.hash);
            if (event.target.hash === '#tabs-1') {
                initmultiselect('ev_pers');
                initmultiselect('ev_tag');
                initmultiselect('ev_tem');
                let data = load_event();
                updateEvent(data);
            }
            if (event.target.hash === '#tabs-2') {
                initmultiselect('pers_tag');
                initmultiselect('pers_tem');
                initmultiselect('pers_sci_department');
                /*Загрузка таблицы с персоналиями*/
                let data = load_person();
                updatePerson(data);
            }
            if (event.target.hash === '#tabs-3') {
                initmultiselect('file_tag');
                initmultiselect('file_tem');
                initmultiselect('file_pers');
                initmultiselect('file_sci_department');
                let data = load_file();
                updateFile(data);
            }
            if (event.target.hash === '#tabs-4') {
                let data = load_tag();
                let tbl = $("#tbl_tag");
                tbl.html('');
                $.each(data, function (i, v) {
                    if (v.SUMM === null) {
                        v.SUMM = 0;
                    }
                    tbl.append('<tr><td style="width: 20px"><button type="button" class="btn btn-danger" onclick="delTag(' + v.id + ')"><i class="bi bi-trash"></i></button></td><td>' + v.Name + ' (' + v.SUMM + ')</td></tr>')
                })
            }
            if (event.target.hash === '#tabs-5') {
                let data = load_sci_field()
                let tbl = $("#tbl_sci_field");
                tbl.html('');
                $.each(data, function (i, v) {
                    if (v.SUMM === null) {
                        v.SUMM = 0;
                    }
                    tbl.append('<tr><td style="width: 20px"><button type="button" class="btn btn-danger" onclick="delSci_field(' + v.id + ')"><i class="bi bi-trash"></i></button></td><td>' + v.Name + ' (' + v.SUMM + ')</td></tr>')
                })

            }
            if (event.target.hash === '#tabs-6') {
                initmultiselect('sci_department_owner');
                let data = load_sci_department()
                let tbl = $("#tbl_sci_department");
                tbl.html('');
                $.each(data, function (i, v) {
                    tbl.append('<tr><td style="width: 20px"><button type="button" class="btn btn-danger" onclick="delSciDepartment(' + v.id + ')"><i class="bi bi-trash"></i></button></td><td>' + v.Name + '</td></tr>')
                })

            }
            if (event.target.hash === '#tabs-7') {
                initmultiselect('collection_sci_department');
                updateCollection(load_collection());
            }
            if (event.target.hash === '#tabs-8') {
                initmultiselect('collectionItem_tag');
                initmultiselect('collectionItem_tem');
                initmultiselect('collectionItem_pers');

                // TODO
                let coll=load_collection();
                let select=$('#collectionItemColl');
                select.empty().append('<option disabled value="" selected>--</option>')
                $.each(coll,function (index, value) {
                    select.append('<option value="'+this.id+'">'+this.value+'</option>')
                });
                updateCollectionItem(load_collectionItem());

            }

        }
    })
    /*LOAD DATA*/
    let data_tag = load_tag();
    let data_pers = load_person('select');
    let data_sci_field = load_sci_field();
    let data_sci_department = load_sci_department();
    //let data_file = load_file('select');
    let data_event = load_event();

    /*******************
     /*     EVENT
     /********************/
    initmultiselect('ev_tem');
    initmultiselect('ev_tag');
    initmultiselect('ev_pers')
    initmultiselect('ev_sci_department');
    updateEvent(data_event);
    $('#ev_tag_add').autocomplete({
        minLength: 1,
        source: AutocompleteSourceTag,
        select: AutocompleteSelect,
    });
    $('#ev_pers_add').autocomplete({
        minLength: 1,
        source: AutocompleteSourcePers,
        select: AutocompleteSelect,
    });
    $('#ev_Y_n,#ev_M_n,#ev_D_n,#ev_Y_e,#ev_M_e,#ev_D_e').on('keyup', function (/*e*/) {
        this.value = this.value.replace(/\D/g, '');
    })
    $('#ev_Name').on('keyup', function (/*e*/) {
        let text = $(this).val();
        if (text.length > 200) {
            $(this).val(text.substring(0, 200));
        }
        $('#ev_Name_COUNT').html(text.length + '/' + '200')
    })

    inittag('ev');
    initTagAjax('#ev_tag', data_tag);
    initTagAjax('#ev_tem', data_sci_field);
    initTagAjax('#ev_pers', data_pers);
    initTagAjax('#ev_sci_department', data_sci_department);
    /*Форма выбора файла*/
    $('#btn_open_file').on('click', function (e) {
        e.preventDefault();
/*
let chk = [];
                let text = $('#ev_file_text');
                text.html('');
                let sel = $('#ev_file');
                sel.html('');
                $('#dialog_file_cont .form-check-input').each(function () {
                    if ($(this).prop('checked')) {
                        let id = $(this).val();
                        chk.push(id);
                        text.append("<a target='_blank' href='" + data_file.GET[id].pathWeb + "'>[" + data_file.GET[id].name + "] </a>  ")
                        sel.append('<option value="' + id + '">' + id + '</option>');
                    }

                })
                sel.val(chk);
* */
        function load(search = null) {
            data_file = load_file(search);

            let sel = $('#ev_file');
            let ev_file_val=sel.val()
            let cont = $('#dialog_file_cont').html('');
            $.each(data_file.GET, function (index, value) {
                let chk = '';
                if (ev_file_val.indexOf( value.id ) !== -1){
                    chk=' checked ';
                }
                let html = '<div class="card" style="width: 18rem;">' +
                    '<img style="height: 200px;width: max-content;" src="' + value.pathWeb + '" class="card-img-top" alt=""' +
                    '<div class="card-body">' +
                    '<h5 class="card-title">' +
                    '<input value="' + value.id + '"  id="chk_' + value.id + '" name="chk[]" type="checkbox" class="form-check-input" '+chk+'>' +
                    '<label class="" for="chk_' + value.id + '">' + value.name + '</label></h5>' +
                    '<p class="card-text">' + value.disc + '</p>' +

                    /*'<p class="card-text"><small class="text-muted">Последнее обновление 3 мин. назад</small></p>'+
                    '    <a href="#" class="card-link">Ссылка карточки</a>' +
                    '    <a href="#" class="card-link">Другая ссылка</a>' +*/
                    '  </div>' +
                    '</div>';
                cont.append(html);
            });
        }
        let search =''
        if ($('#my_Efile').prop('checked')) {
            search='dep=true';
        }
        load(search);
        dialog.dialog("open");
        /**/
        initmultiselect('s_tg');
        initmultiselect('s_tem');
        initmultiselect('s_pers')

        initTagAjax('#s_tg', data_tag);
        initTagAjax('#s_tem', data_sci_field);
        initTagAjax('#s_pers', data_pers);

        $('#s_Name,#s_tg,#s_tem,#s_pers,#my_Efile').on('change keyup', function () {
            search='';
            if ($('#my_Efile').prop('checked')) {
                search='&dep=true';
            }
            load($('#s_form').serialize()+search);

        })
        /**/

    })
    $('#ev_btn_send').on('click', function (e) {
        e.preventDefault();
        /* ВАЛИДАЦИЯ
         проверка на заполнение обязательных полей*/
        let ret = false;
        $('#event input, #event textarea, #event select').each(function () {
            if ($(this).prop('required')) {
                let temp = $(this).val();
                if (temp === '') {
                    $(this).focus();
                    ret = true;
                    return false;
                }
            }
        })
        if (ret) {
            alert('Заполните обязательные поля!');
            return;
        }
        /**/
        let data = $('#event').serialize();
        $.ajax({
            type: 'POST',
            url: 'set.php?event',
            data: data,
            dataType: 'json',
            cache: false,
            success: function (data) {
                $('#ev_file_text').empty();
                $('#event').trigger("reset");
                if (typeof data.err === 'undefined') {
                    //console.log(data);
                    $('#eventID').val('');
                    updateEvent(load_event());
                } else alert(data.err);
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
    initmultiselect('pers_sci_department');
    inittag('pers');
    initTagAjax('#pers_tag', data_tag);
    initTagAjax('#pers_tem', data_sci_field);
    initTagAjax('#pers_sci_department', data_sci_department);
    // initTagAjax('#pers_file',data_sci_field); TODO pers_file (?)
    $('#pers_tag_add').autocomplete({
        minLength: 1,
        source: AutocompleteSourceTag,
        select: AutocompleteSelect,
    });

    $('#pers_Desc').on('keyup', function (/*e*/) {
        let text = $(this).val();
        if (text.length > 1000) {
            $(this).val(text.substring(0, 1000));
        }
        $('#pers_Desc_short_COUNT').html(text.length + '/' + '1000')
    })
    /*ЗАГРУЗКА НА СЕРВЕР*/
    $('#pers_btn_send').on('click', function (e) {
        e.preventDefault();
        /*ВАЛИДАЦИЯ
        /*Валидация даты*/
        let chek = /^\d{2}[./-]\d{2}[./-]\d{4}$/.test($('#pers_date1').val())
        if (!chek) {
            alert('Заполните дату правильно!');
            return;
        }
        let date2 = $('#pers_date2').val();
        if (date2.length > 0) {
            chek = /^\d{2}[./-]\d{2}[./-]\d{4}$/.test(date2)
            if (!chek) {
                alert('Заполните дату правильно!');
                return;
            }
        }
        let ret = false;
        /*проверка на заполнение обязательных полей*/
        $('#pers input, #pers textarea').each(function () {
            if ($(this).prop('required')) {
                let temp = $(this).val();
                if (temp === '' || temp.length < 2) {
                    ret = true;
                }
            }
        })
        if (ret) {
            alert('Заполните обязательные поля!');
            return;
        }
        /**/
        let datepic = $('#pers_date1,#pers_date2');
        datepic.datepicker("option", "dateFormat", "yy-mm-dd");
        let data = $('#pers').serialize();
        datepic.datepicker("option", "dateFormat", "dd.mm.yy");
        $.ajax({
            type: 'POST',
            url: 'set.php?pers',
            data: data,
            dataType: 'json',
            cache: false,
            success: function (data) {
                console.log(data);
                $('#pers').trigger("reset");
                $('#persID').val('');
                if (typeof data.err === 'undefined') {
                    updatePerson(load_person());

                    //initmultiselect('pers_tag');
                    //inittag('pers');
                    initTagAjax('#pers_tag', load_tag());

                } else alert(data.err);
            }
        });
    })
    /***********************
     /*TAG
     /***********************/
    $("#tag_add_btn").on('click', function (e) {
        e.preventDefault();
        let tag = $('#tag_add').val();
        if (tag.length > 3) {
            $.ajax({
                type: 'POST',
                url: 'set.php?tag',
                data: 'tag=' + tag,
                dataType: 'json',
                cache: false,
                success: function (data) {
                    console.log(data);
                    if (typeof data.err === 'undefined') {
                        $('#tag_add').val('');
                        initTagAjax('#ev_tag', data);
                        initTagAjax('#pers_tag', data);
                        let tbl = $("#tbl_tag");
                        tbl.html('');
                        $.each(data, function (i, v) {
                            tbl.append('<tr><td style="width: 20px"><button type="button" class="btn btn-danger" onclick="delTag(' + v.id + ')"><i class="bi bi-trash"></i></button></td><td>' + v.Name + '</td></tr>')
                        })
                    } else alert(data.err);
                }
            })
        } else alert('Длинна ключевого слова должна быть больше 3')
    })
    /***********************
     /*sci_theme // Научное напрвление
     /***********************/
    $("#sci_field_add_btn").on('click', function (e) {
        e.preventDefault();
        let sci_theme = $('#sci_field_add').val();
        if (sci_theme.length > 3) {
            $.ajax({
                type: 'POST',
                url: 'set.php?sci_theme',
                data: 'sci_theme=' + sci_theme,
                dataType: 'json',
                cache: false,
                success: function (data) {
                    if (typeof data.err === 'undefined') {
                        $('#sci_field_add').val('');
                        initTagAjax('#pers_tem', data);
                        initTagAjax('#ev_tem', data);
                        let tbl = $("#tbl_sci_field");
                        tbl.html('');
                        $.each(data, function (i, v) {
                            tbl.append('<tr><td style="width: 20px"><button type="button" class="btn btn-danger" onclick="delSci_field(' + v.id + ')"><i class="bi bi-trash"></i></button></td><td>' + v.Name + '</td></tr>')
                        })
                    } else alert(data.err);
                }
            })
        } else alert('Длинна ключевого слова должна быть больше 3')
    })
    /***********************
     /*sci_sci_department // Научное подразделение
     /***********************/
    $('#sci_department_date1,#sci_department_date2').datepicker({
        showOtherMonths: true,
        selectOtherMonths: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd.mm.yy',
    });
    initmultiselect('sci_department_owner');
    initTagAjax('#sci_department_owner', data_sci_department);

    /*ЗАГРУЗКА НА СЕРВЕР*/
    $('#sci_department_add_btn').on('click', function (e) {
        e.preventDefault();
        /*ВАЛИДАЦИЯ
        /*Валидация даты*/
        let chek = /^\d{2}[./-]\d{2}[./-]\d{4}$/.test($('#sci_department_date1').val())
        if (!chek) {
            alert('Заполните дату правильно!');
            return;
        }
        let date2 = $('#sci_department_date2').val();
        if (date2.length > 0) {
            chek = /^\d{2}[./-]\d{2}[./-]\d{4}$/.test(date2)
            if (!chek) {
                alert('Заполните дату правильно!');
                return;
            }
        }
        let ret = false;
        /*проверка на заполнение обязательных полей*/
        $('#sci_department input, #sci_department textarea').each(function () {
            if ($(this).prop('required')) {
                let temp = $(this).val();
                if (temp === '' || temp.length < 2) {
                    ret = true;
                }
            }
        })
        if (ret) {
            alert('Заполните обязательные поля!');
            return;
        }
        /**/
        let datepic = $('#sci_department_date1,#sci_department_date2');
        datepic.datepicker("option", "dateFormat", "yy-mm-dd");
        let data = $('#sci_department').serialize();
        datepic.datepicker("option", "dateFormat", "dd.mm.yy");
        $.ajax({
            type: 'POST',
            url: 'set.php?sci_department',
            data: data,
            dataType: 'json',
            cache: false,
            success: function (data) {
                //console.log(data);
                if (typeof data.err === 'undefined') {
                    $('#sci_department').trigger("reset");
                    data_sci_department = data;
                    initTagAjax('#sci_department_owner', data);
                    let tbl = $("#tbl_sci_department");
                    tbl.html('');
                    $.each(data, function (i, v) {
                        tbl.append('<tr><td style="width: 20px"><button type="button" class="btn btn-danger" onclick="delSciDepartment(' + v.id + ')"><i class="bi bi-trash"></i></button></td><td>' + v.Name + '</td></tr>')
                    })
                } else alert(data.err);
                //update_person('ev_pers');
            }
        });
    })
    /***********************
     /*   file
     /***********************/
    $('#file_date').datepicker({
        showOtherMonths: true,
        selectOtherMonths: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd.mm.yy',
    });
    initmultiselect('file_tag');
    initmultiselect('file_tem');
    initmultiselect('file_pers');
    initmultiselect('file_sci_department');
    inittag('file');
    initTagAjax('#file_tag', data_tag);
    initTagAjax('#file_tem', data_sci_field);
    initTagAjax('#file_pers', data_pers);
    initTagAjax('#file_sci_department', data_sci_department);
    $('#file_tag_add').autocomplete({
        minLength: 1,
        source: AutocompleteSourceTag,
        select: AutocompleteSelect,
    });
    $('#file_pers_add').autocomplete({
        minLength: 1,
        source: AutocompleteSourcePers,
        select: AutocompleteSelect,
    });
    $('#file_Desc').on('keyup', function (/*e*/) {
        let text = $(this).val();
        if (text.length > 1000) {
            $(this).val(text.substring(0, 1000));
        }
        $('#file_Desc_short_COUNT').html(text.length + '/' + '1000')
    })
    $('#file_btn_send').on('click', function (e) {
        e.preventDefault();
        /*ВАЛИДАЦИЯ
        /*Валидация даты*/
        let datepic = $('#file_date');
        if (datepic.val() !== '') {
            let chek = /^\d{2}[./-]\d{2}[./-]\d{4}$/.test(datepic.val())
            if (!chek) {
                alert('Заполните дату правильно!');
                return;
            }
        }
        let ret = false;
        /*проверка на заполнение обязательных полей*/
        $('#file input, #file textarea').each(function () {
            if ($(this).prop('required')) {
                if (!$(this).prop('disabled')) {
                    let temp = $(this).val();
                    if (temp === '' || temp.length < 2) {
                        console.log($(this));
                        ret = true;
                    }
                }
            }
        })
        if (ret) {
            alert('Заполните обязательные поля!');
            return;
        }
        /**/
        datepic.datepicker("option", "dateFormat", "yy-mm-dd");
        let form = $('#file')[0];
        let my_file =$('#my_file').prop('checked');
        $.ajax({
            type: 'POST',
            url: 'set.php?file',
            //data: data,
            dataType: 'json',
            data: new FormData(form),
            processData: false,
            contentType: false,
            cache: false,
            success: function (data/*, /*status*/) {
                if (typeof data.err === 'undefined') {
                    updateFile(data);
                    $('#file').trigger("reset");
                    $('#id_file').val('');
                    $('#file_F').prop('disabled', false);
                    $('#my_file').prop('checked',my_file);
                } else alert(data.err);
            }
        });
        datepic.datepicker("option", "dateFormat", "dd.mm.yy");
    })



    /***********************
     /*   collection
     /***********************/
    // TODO
    initmultiselect('collection_sci_department');
    initTagAjax('#collection_sci_department', data_sci_department);

    $('#collection_add_btn').on('click', function (e) {
        e.preventDefault();
        let ret = false;
        /*проверка на заполнение обязательных полей*/
        $('#collection input, #collection textarea').each(function () {
            if ($(this).prop('required')) {
                if (!$(this).prop('disabled')) {
                    let temp = $(this).val();
                    if (temp === '' || temp.length < 2) {
                        ret = true;
                    }
                }
            }
        })
        if (ret) {
            alert('Заполните обязательные поля!');
            return;
        }
        let data = $('#collection').serialize();
        $.ajax({
            type: 'POST',
            url: 'set.php?collection',
            data: data,
            dataType: 'json',
            cache: false,
            success: function (data) {
                if (typeof data.err === 'undefined') {
                    $('#collection')[0].reset();
                    updateCollection(load_collection())
                } else alert(data.err);
            }
        });
    });



    /***********************
     /*   collectionItem
     /***********************/
    // TODO
    initmultiselect('collectionItem_tag');
    initmultiselect('collectionItem_tem');
    initmultiselect('collectionItem_pers');
    inittag('collectionItem_tag');
    initTagAjax('#collectionItem_tag', data_tag);
    initTagAjax('#collectionItem_tem', data_sci_field);
    initTagAjax('#collectionItem_pers', data_pers);
    $('#collectionItem_pers_add').autocomplete({
        minLength: 1,
        source: AutocompleteSourcePers,
        select: AutocompleteSelect,
    });
    $('#collectionItem_tag_add').autocomplete({
        minLength: 1,
        source: AutocompleteSourceTag,
        select: AutocompleteSelect,
    });

    $('#collectionItem_add_btn').on('click', function (e) {
        e.preventDefault();
        let ret = false;
        /*проверка на заполнение обязательных полей*/
        $('#collectionItem input, #collectionItem textarea').each(function () {
            if ($(this).prop('required')) {
                if (!$(this).prop('disabled')) {
                    let temp = $(this).val();
                    if (temp === '' || temp.length < 2) {
                        ret = true;
                        $(this).focus();
                    }
                }
            }
        })
        if (ret) {
            alert('Заполните обязательные поля!');
            return;
        }
        let form = $('#collectionItem')[0];
        $.ajax({
            type: 'POST',
            url: 'set.php?collectionItem',
            dataType: 'json',
            data: new FormData(form),
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                if (typeof data.err === 'undefined') {
                    $('#collectionItem')[0].reset();
                    $('#collectionItemId').val('');
                    $('#collectionItemFile').prop('disabled', false);
                    updateCollectionItem(load_collectionItem())
                } else alert(data.err);
            }
        });
    });
})
function AutocompleteSourcePers(request, response) {
    let term = request.term;
    if (term in cache_pers) {
        response(cache_pers[term]);
        return;
    }
    $.getJSON("get.php?person", request, function (data, status, xhr) {
        cache_pers[term] = data;
        response(data);
    });
}
function AutocompleteSourceTag(request, response) {
    let term = request.term;
    if (term in cache_tag) {
        response(cache_tag[term]);
        return;
    }
    $.getJSON("get.php?tag", request, function (data, status, xhr) {
        cache_tag[term] = data;
        response(data);
    });
}

function AutocompleteSelect(event, ui) {
    let element_id = event.target.id
    element_id = element_id.replace("_add", '');
    let el = $('#' + element_id);
    let tag = el.val();
    if (!Array.isArray(tag)) {
        tag = [];
    }
    tag.push(ui.item.id)
    el.val(tag).multiselect('refresh');
    ui.item.value = '';
}

function GetOnline() {
    if (first) {
        first = false;
        clearInterval(timerId)
        timerId = setInterval(GetOnline, 10 * 1000);
    }
    $.ajax({
        async: true,
        url: 'getOnline.php',
        dataType: 'json',
        cache: false,
        success: function (data/*, /*status*/) {
            $('#UserOnline').html('<div class="badge bg-primary text-wrap">' + data[0].COUNT + '</div>');
            if (typeof data.user !== 'undefined') {
                $.each(data.user, function (index, value) {
                    $('#UserOnline').append(' [' + value.FIO + '] ');
                });
            }
            //console.log(data);
        }
    });
}

function editEvent(id) {
    /** #eventID*/
    let data = load_event('s_id=' + id);
    let info = data[0];
    if (info.moderated !=='0') {
        alert('Событие проверено модератором, изменить нельзя.');
        return;
    }
    console.log(info.moderated);
    //if info.moder
    $('#eventID').val(info.id);
    $('#ev_Name').val(info.Name);
    let ev_n = info.DateN.split('.');
    let ev_k = info.DateK.split('.');
    $('#ev_Y_n').val(ev_n[0]);
    $('#ev_M_n').val((ev_n[1]) ? ev_n[1] : '');
    $('#ev_D_n').val((ev_n[2]) ? ev_n[2] : '');
    $('#ev_Y_e').val((ev_k[0]) ? ev_k[0] : '');
    $('#ev_M_e').val((ev_k[1]) ? ev_k[1] : '');
    $('#ev_D_e').val((ev_k[2]) ? ev_k[2] : '');
    $('#ev_Desc').val(info.Desc);
    $('#ev_importance').val(info.importance);
    $('#ev_doc').val(info.Doc);
    $('#ev_latitude').val(info.latitude);
    $('#ev_longitude').val(info.longitude);
    // ev_sci_department - sci_department
    let sci_department = [];
    if (info.sci_department.length > 0) {
        let dt = info.sci_department;
        $.each(dt, function (index, value) {
            sci_department.push(value.id)
        });
    }
    $('#ev_sci_department').val(sci_department).multiselect('refresh');
    // ev_tem- sci_theme
    let sci_theme = [];
    if (info.sci_theme.length > 0) {
        let dt = info.sci_theme;
        $.each(dt, function (index, value) {
            sci_theme.push(value.id)
        });
    }
    $('#ev_tem').val(sci_theme).multiselect('refresh');
    // ev_tag - tag
    let tag = [];
    if (info.tag.length > 0) {
        let dt = info.tag;
        $.each(dt, function (index, value) {
            tag.push(value.id)
        });
    }
    $('#ev_tag').val(tag).multiselect('refresh');
    // ev_pers - pers
    let pers = [];
    if (info.pers.length > 0) {
        let dt = info.pers;
        $.each(dt, function (index, value) {
            pers.push(value.id)
        });
    }
    $('#ev_pers').val(pers).multiselect('refresh');
    // ev_file - file
    let file = [];
    let ev_file=$('#ev_file');
    ev_file.html('');
    if (info.file.length > 0) {
        let dt = info.file;
        let text = $('#ev_file_text');
        text.html('');
        $.each(dt, function (index, value) {
            file.push(value.id)
            ev_file.append('<option value="'+value.id+'">'+value.id+'</option>')
            text.append("<a target='_blank' href='" + value.pathWeb + "'>[" + value.name + "] </a>  ")
        });
    }
    ev_file.val(file);
    $(window).scrollTop($('#UserOnline').offset().top);
}

function editPerson(id) {
    /**  #persID*/
    let data = load_person('s_id=' + id);
    let info = data[0];
    $('#persID').val(info.id);

    //console.log(info);
    $('#pers_F').val(info.F);
    $('#pers_I').val(info.I);
    $('#pers_O').val(info.O);
    let DT = $('#pers_date1,#pers_date2');
    DT.datepicker("option", "dateFormat", "yy-mm-dd");
    $('#pers_date1').val(info.DAYN);
    $('#pers_date2').val(info.DAYD);
    DT.datepicker("option", "dateFormat", "dd.mm.yy");
    $('#pers_dol').val(info.DOL);
    $('#pers_Desc').val(info.COMMENT);
    $('#pers_publications').val(info.publications);
    $('#pers_awards').val(info.awards);
    //pers_sci_department  - sci_department
    let sci_department = [];
    if (info.sci_department.length > 0) {
        let dt = info.sci_department;
        $.each(dt, function (index, value) {
            sci_department.push(value.id)
        });
    }
    $('#pers_sci_department').val(sci_department).multiselect('refresh');
    // pers_tem  - sci_theme
    let sci_theme = [];
    if (info.sci_theme.length > 0) {
        let dt = info.sci_theme;
        $.each(dt, function (index, value) {
            sci_theme.push(value.id)
        });
    }
    $('#pers_tem').val(sci_theme).multiselect('refresh');
    // pers_tag - tag
    let tag = [];
    if (info.tag.length > 0) {
        let dt = info.tag;
        $.each(dt, function (index, value) {
            tag.push(value.id)
        });
    }
    $('#pers_tag').val(tag).multiselect('refresh');
    $(window).scrollTop($('#UserOnline').offset().top);
}
function editCollection(id) {
    let data = load_collection('s_id=' + id);
    let info = data[0];
    //id_collection
    console.log(info);
    $('#id_collection').val(info.id);
    $('#collection_name').val(info.Name);
    $('#collection_Desc').val(info.collection_Desc);
    $('#collection_url').val(info.url);
    ///$('#').val();
    //pers_sci_department  - sci_department
    let sci_department = [];
    if (info.sci_department.length > 0) {
        let dt = info.sci_department;
        $.each(dt, function (index, value) {
            sci_department.push(value.id)
        });
    }
    $('#collection_sci_department').val(sci_department).multiselect('refresh');
}

function editCollectionItem(id) {
    $('#collectionItemFile').prop('disabled', true);
    let data = load_collectionItem('s_id=' + id);
    let info = data.GET[0];
    console.log(info);
    $('#collectionItemName').val(info.Name);
    $('#collectionItemColl').val(info.CollectionId);
    $('#collectionItemId').val(info.CollectionItemId);
    $('#collectionItemDesc').val(info.Desc);
    $('#collectionItemMaterial').val(info.Material);
    $('#collectionItemPlace').val(info.Place);
    $('#collectionItemTime').val(info.Time);
    $('#collectionItemSize').val(info.Size);
    $('#collectionItemNom').val(info.Nom);
    $('#collectionItem_latitude').val(info.latitude);
    $('#collectionItem_longitude').val(info.longitude);
    /*collectionItem_pers[]*/
    let person = [];
    if (info.person.length > 0) {
        let dt = info.person;
        $.each(dt, function (index, value) {
            person.push(value.id)
        });
    }
    $('#collectionItem_pers').val(person).multiselect('refresh');//refresh
    //collectionItem_tem[]
    let sci_theme = [];
    if (info.sci_theme.length > 0) {
        let dt = info.sci_theme;
        $.each(dt, function (index, value) {
            sci_theme.push(value.id)
        });
    }
    $('#collectionItem_tem').val(sci_theme).multiselect('refresh');
    //collectionItem_tag[]
    let tag = [];
    if (info.tag.length > 0) {
        let dt = info.tag;
        $.each(dt, function (index, value) {
            tag.push(value.id)
        });
    }
    $('#collectionItem_tag').val(tag).multiselect('refresh');

    $(window).scrollTop($('#UserOnline').offset().top);
}

function editFile(id) {
    $('#file_F').prop('disabled', true);
    let data = load_file('s_id=' + id);
    let info = data.GET[id];
    $('#id_file').val(info.id);
    $('#file_name').val(info.name);
    let DT = $('#file_date');
    DT.datepicker("option", "dateFormat", "yy-mm-dd");
    DT.val(info.date);
    DT.datepicker("option", "dateFormat", "dd.mm.yy");
    $('#file_Desc').val(info.disc);
    $('#file_doc').val(info.doc);
    let person = [];
    if (info.person.length > 0) {
        let dt = info.person;
        $.each(dt, function (index, value) {
            person.push(value.id)
        });
    }
    $('#file_pers').val(person).multiselect('refresh');//refresh
    /*sci_theme*/
    let file_sci_department = [];
    if (info.sci_department.length > 0) {
        let dt = info.sci_department;
        $.each(dt, function (index, value) {
            file_sci_department.push(value.id)
        });
    }
    $('#file_sci_department').val(file_sci_department).multiselect('refresh');
    /*sci_theme*/
    let sci_theme = [];
    if (info.sci_theme.length > 0) {
        let dt = info.sci_theme;
        $.each(dt, function (index, value) {
            sci_theme.push(value.id)
        });
    }
    $('#file_tem').val(sci_theme).multiselect('refresh');
    /*tag*/
    let tag = [];
    if (info.tag.length > 0) {
        let dt = info.tag;
        $.each(dt, function (index, value) {
            tag.push(value.id)
        });
    }
    $('#file_tag').val(tag).multiselect('refresh');
    $(window).scrollTop($('#UserOnline').offset().top);
}
function arrdata(data, href = false) {
    let ret = '';
    if (typeof data !== 'undefined')
        if (data.length > 0) {
            $.each(data, function (index, value) {
                if (!href) {
                    ret += '[' + value.Name + '] ';
                } else {
                    ret += '<a target="_blank" href="' + value.pathWeb + '">[' + value.name + ']</a> ';
                }
            });
        }
    return ret;
}

function updateFile(data) {
    let html='<table id="tbl_file" class="table table-bordered border-primary"><thead><tr>' +
        '<th>ID</th>' +
        '<th class="filter-false sorter-false"></th>' +
        '<th>Дата файла</th>' +
        '<th>Название файла</th>' +
        '<th>Аннотация</th>' +
        '<th>Перcоналии</th>' +
        '<th>Научная тематика</th>' +
        '<th>Структурное подразделение</th>' +
        '<th>Ссылки на архивный докумен</th>' +
        '<th>Ключевые слова</th>' +
        '<th>Файл</th>' +
        '</tr></thead><tbody>';
    $.each(data.GET, function (i, v) {
        let sci_department = arrdata(v.sci_department);
        let sci_theme = arrdata(v.sci_theme);
        let tag = arrdata(v.tag);
        let person = arrdata(v.person);
        html+='<tr>' +
            '<td>' + v.id + '</td>' +
            '<td style="width: 20px">' +
            '<div class="d-flex flex-column"><div>' +
            '<button type="button" class="btn btn-danger" onclick="delFile(' + v.id + ')"><i class="bi bi-trash"></i></button></div>' +
            '<div><button type="button" class="btn btn-info" onclick="editFile(' + v.id + ')"><i class="bi bi-pencil-square"></i></button></div>' +
            '</td>' +
            '<td>' + v.date + '</td>' +
            '<td>' + v.name + '</td>' +
            '<td><textarea style="width: 100%" class="form-control">' + v.disc + '</textarea></td>' +
            '<td>' + person + '</td>' +
            '<td>' + sci_theme + '</td>' +
            '<td>' + sci_department + '</td>' +
            '<td>' + v.doc + '</td>' +
            '<td class="text-wrap">' + tag + '</td>' +
            '<td>' +
            '<a target="_blank" href="' + v.pathWeb + '">' + v.name + '</a><br>' +
            '<img src="' + v.pathWeb + '" width="100px">' +
            '</td>' +
            '</tr>';
    })
    html+='</tbody></table>';
    $("#div_tbl_file").html(html);
    $('#tbl_file').tablesorter({
        //theme : 'blue',
        //widthFixed: true,
        widgets : [ 'zebra', 'filter' ],
        /*widgetOptions : {
            filter_external: 'input.search',
            filter_reset: '.reset'
        }*/
    });
}
function updateCollectionItem(data) {
    //console.log(data);
    let html='<table id="tbl_CollectionItem" class="table table-bordered border-primary"><thead><tr>' +
        '<th>ID</th>' +
        '<th class="filter-false sorter-false"></th>' +
        '<th>CollectionName</th>' +
        '<th>Название экземпляра</th>' +
        '<th>Аннотация </th>' +
        '<th>Место нахождения</th>' +
        '<th>Время создания</th>' +
        '<th>Материал, техника</th>' +
        '<th>Размер</th>' +
        '<th>Учетный номер</th>' +
        '<th>Координаты</th>' +
        '<th>Файл</th>' +
        '<th>Направление науки</th>'+
        '<th>Ключевые слова</th>' +
        '<th>Авторство</th>' +
        '</tr></thead><tbody>';
    $.each(data.GET, function (i, v) {
        let sci_theme = arrdata(v.sci_theme);
        let tag = arrdata(v.tag);
        let person = arrdata(v.person);
        html+='<tr>' +
            '<td style="width: 20px">' + v.CollectionItemId + '</td>' +
            '<td style="width: 20px">' +
            '<div class="d-flex flex-column">' +
            '<div><button type="button" class="btn btn-danger" onclick="delCollectionItem(' + v.CollectionItemId + ')"><i class="bi bi-trash"></i></button></div>' +
            '<div><button type="button" class="btn btn-info" onclick="editCollectionItem(' + v.CollectionItemId + ')"><i class="bi bi-pencil-square"></i></button></div>' +
            '</td>' +
            '<td>' + v.Name + '</td>' +
            '<td>' + v.CollectionName + '</td>' +
            '<td><textarea style="width: 100%" class="form-control">' + v.Desc + '</textarea></td>' +
            '<td><textarea style="width: 100%" class="form-control">' + v.Place + '</textarea></td>' +
            '<td>' + v.Time + '</td>' +
            '<td>' + v.Material + '</td>' +
            '<td>' + v.Size + '</td>' +
            '<td>' + v.Nom + '</td>' +
            '<td>' + v.latitude+'/'+v.longitude + '</td>' +
            '<td>' +
            '<a target="_blank" href="' + v.pathWeb + '">[Файл:' + v.Name + ']</a>' +
            '<img src="' + v.pathWeb + '" width="100px">'+
            '</td>' +
            '<td>' + sci_theme + '</td>' +
            '<td>' + tag + '</td>' +
            '<td>' + person + '</td>' +
            '</tr>';
    })
    html+='</tbody></table>';
    $("#div_tbl_collectionItem").html(html);
    $('#tbl_CollectionItem').tablesorter({
        //theme : 'blue',
        //widthFixed: true,
        widgets : [ 'zebra', 'filter' ],
        /*widgetOptions : {
            filter_external: 'input.search',
            filter_reset: '.reset'
        }*/
    });
}
function updateCollection(data) {
    let html='<table id="tbl_Collection" class="table table-bordered border-primary"><thead><tr>' +
        '<th>ID</th>' +
        '<th class="filter-false sorter-false"></th>' +
        '<th>Название колекции</th>' +
        '<th>История формирования коллекции</th>' +
        '<th>Ссылка на коллекцию</th>' +
        '<th>Структурное подразделение</th>' +
        '</tr></thead><tbody>';
    $.each(data, function (i, v) {
        let sci_department = arrdata(v.sci_department);
        let url='<a target="_blank" href="' + v.url + '">Ссылка</a>'
        if ((v.url === null) || (v.url === '')) {
            url='';
        }
        html+='<tr>' +
            '<td style="width: 20px">' + v.id + '</td>' +
            '<td style="width: 20px">' +
            '<div class="d-flex flex-column"><div>' +
            '<button type="button" class="btn btn-danger" onclick="delCollection(' + v.id + ')"><i class="bi bi-trash"></i></button></div>' +
            '<div><button type="button" class="btn btn-info" onclick="editCollection(' + v.id + ')"><i class="bi bi-pencil-square"></i></button></div>' +
            '</td>' +
            '<td>' + v.Name + '</td>' +
            '<td><textarea style="width: 100%" class="form-control">' + v.collection_Desc + '</textarea></td>' +
            '<td>'+url+'</td>' +
            '<td>' + sci_department + '</td>' +
            '</tr>';
    })
    html+='</tbody></table>';
    $("#div_tbl_collection").html(html);
    $('#tbl_Collection').tablesorter({
        //theme : 'blue',
        //widthFixed: true,
        widgets : [ 'zebra', 'filter' ],
        /*widgetOptions : {
            filter_external: 'input.search',
            filter_reset: '.reset'
        }*/
    });
}

function updateEvent(data) {
   // let tbl = $("#div_tbl_event");
    //console.log(data);
    let html='<table id="tbl_event" class="table table-bordered border-primary"><thead><tr>' +
        '<th>ID</th>' +
        '<th class="filter-false sorter-false"></th>' +
        '<th>Название события</th>' +
        '<th>Дата</th>' +
        '<th>Событие полное</th>' +
        '<th>Файлы</th>' +
        '<th>Ссылка на архивный докумены</th>' +
        '<th>Важность события</th>' +
        '<th>Персоналии</th>' +
        '<th>Структурное подразделение</th>' +
        '<th>Научная тематика</th>' +
        '<th>Ключевые слова</th>' +
        '</tr></thead><tbody>';
    $.each(data, function (i, v) {
        //console.log(v)
        let file = arrdata(v.file, true);
        let sci_department = arrdata(v.sci_department);
        let sci_theme = arrdata(v.sci_theme);
        let tag = arrdata(v.tag);
        let pers = arrdata(v.pers);
        let mod=v.moderated;
        let str_mod='';
        if (mod ==='0') {
            str_mod='<div><div class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Модерация не пройдена. Можно вносить изменения"><i class="bi bi-unlock-fill"></i></div></div>'
        }
        if (mod ==='1') {
            str_mod='<div><div class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Модерация пройдена. Внесение изменений невозможно!"><i class="bi bi-file-lock-fill"></i></div></div>'
        }
         html+='<tr>' +
            '<td>' + v.id + '</td>' +
            '<td style="width: 20px">' +
            '<div class="d-flex flex-column">' +
             '<div>'+str_mod+'</div>'+
            '<div><button type="button" class="btn btn-danger" onclick="delEvent(' + v.id + ')"><i class="bi bi-trash"></i></button></div>' +
            '<div><button type="button" class="btn btn-info" onclick="editEvent(' + v.id + ')"><i class="bi bi-pencil-square"></i></button></div>' +
            '</div></td>' +
            '<td>' + v.Name + '</td>' +
            '<td>' + v.DateN + '<br>' + v.DateK + '</td>' +
            '<td><textarea style="width: 100%" class="form-control">' + v.Desc + '</textarea></td>' +
            '<td>' + file + '</td>' +//Файлы
            '<td><textarea style="width: 100%" class="form-control">' + v.Doc + '</textarea></td>' +//Ссылка на архивный докумены
            '<td>' + v.importance + '</td>' +//Важность
            '<td>' + pers + '</td>' +//Персоналии
            '<td>' + sci_department + '</td>' +//Структурное подразделение
            '<td>' + sci_theme + '</td>' +//Научная тематика
            '<td>' + tag + '</td>' +//Ключевые слова
            //'<td><a target="_blank" href="' + v.pathWeb + '">' + v.name + '</a></td>' +
            '</tr>'
    })
    html+="</tbody></table>";
    $("#div_tbl_event").html(html);
    $('#tbl_event').tablesorter({
        //theme : 'blue',
        //widthFixed: true,
        widgets : [ 'zebra', 'filter' ],
        /*widgetOptions : {
            filter_external: 'input.search',
            filter_reset: '.reset'
        }*/
    });
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })


}

function updatePerson(data) {
    initTagAjax('#ev_pers', data);
    initTagAjax('#file_pers', data);

    let html='<table id="tbl_person" class="table table-bordered border-primary"><thead><tr>' +
        '<th>ID</th>' +
        '<th class="filter-false sorter-false"></th>' +
        '<th>ФИО</th>' +
        //'<th>Имя</th>' +
       // '<th>Отчество</th>' +
        '<th>Должность</th>' +
        '<th>Даты жизни</th>' +
        '<th>Аннотация</th>' +
        '<th>Ключевые</th>' +
        '<th>подразделение</th>' +
        '<th>тематика</th>' +
        '<th style="width: 10%">файлы</th>' +
        '</tr></thead><tbody>';

    $.each(data, function (i, v) {
       // console.log(i);
        if (i==='POST') {return;}
        if (i==='profiling') {
            let time={
                tag:0,
                sci_department:0,
                sci_theme:0,
                file:0
            };
            //console.log(v);
            $.each(v,function (index, value) {
                if (typeof value[1] !=='undefined') {
                    time.tag+=parseFloat(value[1].Duration);
                }
                if (typeof value[2] !=='undefined') {
                    time.sci_department+=parseFloat(value[2].Duration);
                }
                if (typeof value[3] !=='undefined') {
                    time.sci_theme+=parseFloat(value[3].Duration);
                }
                if (typeof value[4] !=='undefined') {
                    time.file+=parseFloat(value[4].Duration);
                }
            });
            console.log(time);

            return;
        }
        let file = arrdata(v.file, true);
        let sci_department = arrdata(v.sci_department);
        let sci_theme = arrdata(v.sci_theme);
        let tag = arrdata(v.tag);
        html+='<tr>' +
            '<td>' + v.id + '</td>' +
            '<td style="width: 20px">' +
            '<div class="d-flex flex-column">' +
            '<div><button type="button" class="btn btn-danger" onclick="delPerson(' + v.id + ')"><i class="bi bi-trash"></i></button></div>' +
            '<div><button type="button" class="btn btn-info" onclick="editPerson(' + v.id + ')"><i class="bi bi-pencil-square"></i></button></div>' +
            '</div></td>' +
            '<td>' + v.F +' '+v.I +' '+ v.O + '</td>' +
           //'<td>' + v.I + '</td>' +
            //'<td>' + v.O + '</td>' +
            '<td>' + v.DOL + '</td>' +
            '<td>c ' + v.DAYN + ' по ' + v.DAYD + '</td>' +
            '<td><textarea style="width: 100%" class="form-control">' + v.COMMENT + '</textarea></td>' +
            '<td class="text-wrap">' + tag + '</td>' +
            '<td class="text-wrap">' + sci_department + '</td>' +
            '<td class="text-wrap">' + sci_theme + '</td>' +
            '<td style="width: 10%" class="text-wrap">' + file + '</td>' +
            '</tr>'
    })
    html+='</tbody></table>';
    $("#div_tbl_person").html(html);
    $('#tbl_person').tablesorter({
        //theme : 'blue',
        //widthFixed: true,
        widgets : [ 'zebra', 'filter' ],
        /*widgetOptions : {
            filter_external: 'input.search',
            filter_reset: '.reset'
        }*/
    });
}
function delCollectionItem(id, answer = null){
    if (answer == null) {
        $('#dialog_del').dialog({
            buttons: {
                "Да": function () {
                    $(this).dialog("close");
                    delCollectionItem(id, true)
                },
                'Нет': function () {
                    $(this).dialog("close");
                }
            }
        }).dialog("open");
    } else if (answer === true) {
        $.ajax({
            type: 'POST',
            url: 'set.php?collectionItem&del',
            data: 'collectionItem=' + id,
            dataType: 'json',
            cache: false,
            success: function (data) {
                if (typeof data.err === 'undefined') {
                    updateCollectionItem(load_collectionItem());
                } else alert(data.err);
            }
        })
    }
}
function delCollection(id, answer = null){
    if (answer == null) {
        $('#dialog_del').dialog({
            buttons: {
                "Да": function () {
                    $(this).dialog("close");
                    delCollection(id, true)
                },
                'Нет': function () {
                    $(this).dialog("close");
                }
            }
        }).dialog("open");
    } else if (answer === true) {
        $.ajax({
            type: 'POST',
            url: 'set.php?collection&del',
            data: 'collection=' + id,
            dataType: 'json',
            cache: false,
            success: function (data) {
                if (typeof data.err === 'undefined') {
                    updateCollection(load_collection());
                } else alert(data.err);
            }
        })
    }
}
function delEvent(tag, answer = null) {
    let data = load_event('s_id=' + tag);
    let info = data[0];
     if (info.moderated !=='0') {
        alert('Событие проверено модератором, изменить нельзя.');
        return;
    }

    if (answer == null) {
        $('#dialog_del').dialog({
            buttons: {
                "Да": function () {
                    $(this).dialog("close");
                    delEvent(tag, true)
                },
                'Нет': function () {
                    $(this).dialog("close");
                }
            }
        }).dialog("open");
    } else if (answer === true) {
        $.ajax({
            type: 'POST',
            url: 'set.php?event&del',
            data: 'event=' + tag,
            dataType: 'json',
            cache: false,
            success: function (data) {
                if (typeof data.err === 'undefined') {
                    updateEvent(load_event());
                } else alert(data.err);
            }
        })
    }
}

function delFile(tag, answer = null) {
    if (answer == null) {
        $('#dialog_del').dialog({
            buttons: {
                "Да": function () {
                    $(this).dialog("close");
                    delFile(tag, true)
                },
                'Нет': function () {
                    $(this).dialog("close");
                }
            }
        }).dialog("open");
    } else if (answer === true) {
        $.ajax({
            async:true,
            type: 'POST',
            url: 'set.php?file&del',
            data: 'file=' + tag+'&dep=true',
            dataType: 'json',
            cache: false,
            success: function (data) {
                if (typeof data.err === 'undefined') {
                    updateFile(data);
                } else alert(data.err);
            }
        })
    }
}

function delPerson(tag, answer = null) {
    if (answer == null) {
        $('#dialog_del').dialog({
            buttons: {
                "Да": function () {
                    $(this).dialog("close");
                    delPerson(tag, true)
                },
                'Нет': function () {
                    $(this).dialog("close");
                }
            }
        }).dialog("open");
    } else if (answer === true) {
        $.ajax({
            type: 'POST',
            url: 'set.php?pers&del',
            data: 'pers=' + tag,
            dataType: 'json',
            cache: false,
            success: function (data) {
                if (typeof data.err === 'undefined') {
                    updatePerson(load_person());

                } else alert(data.err);
            }
        })
    }
}

function delTag(tag, answer = null) {
    /* !*/
    if (answer == null) {
        $('#dialog_del').dialog({
            buttons: {
                "Да": function () {
                    $(this).dialog("close");
                    delTag(tag, true)
                },
                'Нет': function () {
                    $(this).dialog("close");
                }
            }
        }).dialog("open");
    } else if (answer === true) {
        $.ajax({
            type: 'POST',
            url: 'set.php?tag&del',
            data: 'tag=' + tag,
            dataType: 'json',
            cache: false,
            success: function (data) {
                console.log(data);
                if (typeof data.err === 'undefined') {
                    let tbl = $("#tbl_tag");
                    tbl.html('');
                    $.each(data, function (i, v) {
                        tbl.append('<tr><td style="width: 20px"><button type="button" class="btn btn-danger" onclick="delTag(' + v.id + ')"><i class="bi bi-trash"></i></button></td><td>' + v.Name + '</td></tr>')
                    })
                } else alert(data.err);
                // do something with ajax data
            }
        })
    }
}

function delSciDepartment(sci_department, answer = null) {
    if (answer == null) {
        $('#dialog_del').dialog({
            buttons: {
                "Да": function () {
                    $(this).dialog("close");
                    delSciDepartment(sci_department, true)
                },
                'Нет': function () {
                    $(this).dialog("close");
                }
            }
        }).dialog("open");
    } else if (answer === true) {
        $.ajax({
            type: 'POST',
            url: 'set.php?sci_department&del',
            data: 'sci_department=' + sci_department,
            dataType: 'json',
            cache: false,
            success: function (data) {
                console.log(data);
                if (typeof data.err === 'undefined') {
                    let tbl = $("#tbl_sci_department");
                    tbl.html('');
                    $.each(data, function (i, v) {
                        tbl.append('<tr><td style="width: 20px"><button type="button" class="btn btn-danger" onclick="delSciDepartment(' + v.id + ')"><i class="bi bi-trash"></i></button></td><td>' + v.Name + '</td></tr>')
                    })
                } else alert(data.err);
            }
        })
    }
}

function delSci_field(sci_field, answer = null) {
    /* !*/
    if (answer == null) {
        $('#dialog_del').dialog({
            buttons: {
                "Да": function () {
                    $(this).dialog("close");
                    delSci_field(sci_field, true)
                },
                'Нет': function () {
                    $(this).dialog("close");
                }
            }
        }).dialog("open");
    } else if (answer === true) {
        $.ajax({
            type: 'POST',
            url: 'set.php?sci_theme&del',
            data: 'sci_theme=' + sci_field,
            dataType: 'json',
            cache: false,
            success: function (data) {
                console.log(data);
                if (typeof data.err === 'undefined') {
                    let tbl = $("#tbl_sci_field");
                    tbl.html('');
                    $.each(data, function (i, v) {
                        tbl.append('<tr><td style="width: 20px"><button type="button" class="btn btn-danger" onclick="delSci_field(' + v.id + ')"><i class="bi bi-trash"></i></button></td><td>' + v.Name + '</td></tr>')
                    })
                } else alert(data.err);
            }
        })
    }
}

function load_event(search = null) {
    let data_search = '';
    if (search !== null) {
        data_search = search;
    } else {
        if ($('#my_event').prop('checked')) {
            data_search='dep=true';
        }
    }
    return $.ajax({
        async: false,
        type: 'POST',
        url: 'get.php?event',
        data: data_search,
        dataType: 'json',
        cache: false,
        success: function (data) {
            //console.log(data);
            // do something with ajax data
        }
    }).responseJSON;
}

function load_tag() {
    return $.ajax({
        async: false,
        type: 'POST',
        url: 'get.php?tag',
        //data: 'tag='+tag,
        dataType: 'json',
        cache: false,
        success: function (data) {
            //console.log(data);
            // do something with ajax data
        }
    }).responseJSON;
}

function load_person(search = null) {
    let data_search = '';
    if (search !== null) {
        data_search = search;
    } else {
        if ($('#my_pers').prop('checked')) {
            data_search='dep=true';
        }
    }
    return $.ajax({
        async: false,
        type: 'POST',
        url: 'get.php?person',
        data: data_search,
        dataType: 'json',
        cache: false,
        success: function (data) {
           // updatePerson(data);
            //console.log(data);
            // do something with ajax data
        }
    }).responseJSON;
}
function load_collectionItem(search = null){
    let data_search = '';
    if (search !== null) {
        data_search = search;
    }
    return $.ajax({
        async: false,
        type: 'POST',
        url: 'get.php?collectionItem',
        data: data_search,
        dataType: 'json',
        cache: false,
        success: function (data) {
            //console.log(data);
            // do something with ajax data
        }
    }).responseJSON;
}
function load_collection(search = null) {
    let data_search = '';
    if (search !== null) {
        data_search = search;
    }
    return $.ajax({
        async: false,
        type: 'POST',
        url: 'get.php?collection',
        data: data_search,
        dataType: 'json',
        cache: false,
        success: function (data) {
            //console.log(data);
            // do something with ajax data
        }
    }).responseJSON;
}

function load_sci_field() {
    return $.ajax({
        async: false,
        type: 'POST',
        url: 'get.php?sci_theme',
        //data: 'tag='+tag,
        dataType: 'json',
        cache: false,
        success: function (data) {
            //console.log(data);
            // do something with ajax data
        }
    }).responseJSON;
}

function load_sci_department() {
    return $.ajax({
        async: false,
        type: 'POST',
        url: 'get.php?sci_department',
        //data: 'tag='+tag,
        dataType: 'json',
        cache: false,
        success: function (data) {
            //console.log(data);
            // do something with ajax data
        }
    }).responseJSON;
}

function load_file(search = null) {
    let data_search = '';
    if (search !== null) {
        data_search = search;
    } else {
        if ($('#my_file').prop('checked')) {
            data_search='dep=true';
        }
    }
    return $.ajax({
        async: false,
        type: 'POST',
        url: 'get.php?file',
        data: data_search,
        dataType: 'json',
        cache: false,
        success: function (data) {
            //console.log(data);
            // do something with ajax data
        }
    }).responseJSON;
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

/**
 * @comment Инициализация кнопки добавить для элементов с тегами
 * @param elem Type:string|idSelect`а пример 'file_pers' без #
 */
function inittag(elem) {
    $('#' + elem + '_tag_add_btn').on('click', function (e) {
        e.preventDefault();
        let tag = $('#' + elem + '_tag_add');
        if (tag.val()==='') {
            alert('Пустое ключевое!');
            return false;
        }
        let tag_sel=$('#' + elem + '_tag');
        let val_tag=tag_sel.val();
        val_tag.push(tag.val());
        tag_sel
            .prepend('<option>' + tag.val() + '</option>')
            .val(val_tag)
            .multiselect('refresh');
        $('#' + elem + '_tag_ms').addClass('form-control');
        tag.val('');
    })
}

/**
 * @comment Заполнение Select`а данными
 * @param elem Type:string|idSelect`а пример '#file_pers'
 * @param data Type:array[id,Name]
 */
function initTagAjax(elem, data) {
    let elem_g = $(elem);
    elem_g.html('');

    $.each(data, function (i, v) {
        let SUMM = '';
        //if (typeof v.SUMM !== 'undefined') {
        if (typeof v.SUMM !== 'undefined') {
            if (v.SUMM === null) v.SUMM = 0;
            SUMM = "(" + v.SUMM + ")"
        }
        $(elem).append('<option value="' + v.id + '">' + v.Name + SUMM + '</option>');
    });
    elem_g.multiselect('refresh');
}

/**
 *  @comment Инициализация Multiselect на elem Select
 *  @param elem Type:string|idSelect`а пример 'file_pers' без #
 */
function initmultiselect(elem) {
    let buttonW = '79%';
    let menuWidth = '80%';
    if (elem.indexOf('_tag') > 0) {
        buttonW = '40%';
        menuWidth = '60%';
    }
    if (elem.indexOf('_pers') > 0) {
        buttonW = '40%';
        menuWidth = '60%';
    }

    if (elem.indexOf('s_') === 0) {
        buttonW = '100%';
        menuWidth = '100%';
    }
    let select = $('#' + elem);
    let ariaLabel = select.attr('aria-label');
    select.multiselect({

        buttonWidth: buttonW, // (integer | string | 'auto' | null) Sets the min/max/exact width of the button.
        menuWidth: menuWidth, // (integer | string | 'auto' | null) If a number is provided, sets the exact menu width.
        //multiple:false,
        //header: ['Всё', 'Ничего'],
        noneSelectedText: ariaLabel,
        selectedText: '#',
        selectedList: 5,
        click: function (/*event, ui*/) {
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
    }).multiselect('refresh').multiselectfilter({
        width: '300px'
    });
}