let timerId;
let first=true;
$(document).ready(function () {
    timerId = setInterval(GetOnline, 0);

    let dialog_del = $('#dialog_del').dialog({
        modal: true,
        resizable: false,
        autoOpen: false
    });
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
                        text.append("<a target='_blank' href='" + data_file[id - 1].pathWeb + "'>" + data_file[id - 1].name + " </a>  ")
                        sel.append('<option value="' + id + '">' + id + '</option>');
                    }

                })
                sel.val(chk);
                dialog.dialog("close");
            },
            'Отмена': function () {
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
                let data = load_file();
                updateFile(data);
            }
            if (event.target.hash === '#tabs-4') {
                let data = load_tag();
                let tbl = $("#tbl_tag");
                tbl.html('');
                $.each(data, function (i, v) {
                    tbl.append('<tr><td style="width: 20px"><button type="button" class="btn btn-danger" onclick="delTag(' + v.id + ')"><i class="bi bi-trash"></i></button></td><td>' + v.Name + '</td></tr>')
                })
            }
            if (event.target.hash === '#tabs-5') {
                let data = load_sci_field()
                let tbl = $("#tbl_sci_field");
                tbl.html('');
                $.each(data, function (i, v) {
                    tbl.append('<tr><td style="width: 20px"><button type="button" class="btn btn-danger" onclick="delSci_field(' + v.id + ')"><i class="bi bi-trash"></i></button></td><td>' + v.Name + '</td></tr>')
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
        }
    })
    /*LOAD DATA*/
    let data_tag = load_tag();
    let data_pers = load_person();
    let data_sci_field = load_sci_field();
    let data_sci_department = load_sci_department();
    let data_file = load_file();
    /*******************
     /*     EVENT
     /********************/
    initmultiselect('ev_tem');
    initmultiselect('ev_tag');
    initmultiselect('ev_pers')
    initmultiselect('ev_sci_department');
    //update_person('ev_pers');
    $('#ev_Y_n,#ev_M_n,#ev_D_n,#ev_Y_e,#ev_M_e,#ev_D_e').on('keyup', function (e) {
        this.value = this.value.replace(/\D/g, '');
    })
    $('#ev_Desc_short').on('keyup', function (e) {
        let text = $(this).val();
        if (text.length > 300) {
            $(this).val(text.substring(0, 300));
        }
        $('#ev_Desc_short_COUNT').html(text.length + '/' + '300')
    })

    inittag('ev');
    initTagAjax('#ev_tag', data_tag);
    initTagAjax('#ev_tem', data_sci_field);
    initTagAjax('#ev_pers', data_pers);
    initTagAjax('#ev_sci_department', data_sci_department);
    /*Форма выбора файла*/
    $('#btn_open_file').on('click', function (e) {
        e.preventDefault();
        data_file = load_file();
        let cont = $('#dialog_file_cont').html('');
        $.each(data_file, function (index, value) {
            //console.log(value);
            let html = '<div class="card" style="width: 18rem;">' +
                '<img src="' + value.pathWeb + '" class="card-img-top" alt=""' +
                '<div class="card-body">' +
                '<h5 class="card-title">' +
                '<input value="' + value.id + '" id="chk_' + value.id + '" name="chk[]" type="checkbox" class="form-check-input">' +
                '<label class="" for="chk_' + value.id + '">' + value.name + '</label></h5>' +
                '<p class="card-text">' + value.disc + '</p>' +

                /*'<p class="card-text"><small class="text-muted">Последнее обновление 3 мин. назад</small></p>'+
                '    <a href="#" class="card-link">Ссылка карточки</a>' +
                '    <a href="#" class="card-link">Другая ссылка</a>' +*/
                '  </div>' +
                '</div>';
            cont.append(html);
        });
        dialog.dialog("open");
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
                console.log(data);
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
    // initTagAjax('#pers_file',data_sci_field); TODO

    $('#pers_Desc').on('keyup', function (e) {
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
                updatePerson(load_person())
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
    initTagAjax('#sci_department_owner', data_sci_department); //TODO

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
                console.log(data);
                if (typeof data.err === 'undefined') {
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
    inittag('file');
    initTagAjax('#file_tag', data_tag);
    initTagAjax('#file_tem', data_sci_field);
    initTagAjax('#file_pers', data_pers);

    $('#file_Desc').on('keyup', function (e) {
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
        let chek = /^\d{2}[./-]\d{2}[./-]\d{4}$/.test(datepic.val())
        if (!chek) {
            alert('Заполните дату правильно!');
            return;
        }
        let ret = false;
        /*проверка на заполнение обязательных полей*/
        $('#file input, #file textarea').each(function () {
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
        datepic.datepicker("option", "dateFormat", "yy-mm-dd");
        let form = $('#file')[0];
        $.ajax({
            type: 'POST',
            url: 'set.php?file',
            //data: data,
            dataType: 'json',
            data: new FormData(form),
            processData: false,
            contentType: false,
            cache: false,
            success: function (data, status) {
                updateFile(data);
            }

        });
        datepic.datepicker("option", "dateFormat", "dd.mm.yy");

    })
})

function GetOnline() {
    if (first) {
        first=false;
        clearInterval(timerId)
        timerId=setInterval(GetOnline, 60*1000);
    }
    $.ajax({
        async: true,
        url: 'getOnline.php',
        dataType: 'json',
        cache: false,
        success: function (data, status) {
            $('#UserOnline').html(data[0].COUNT);
            console.log(data[0]);
        }
    });
}

function updateFile(data) {
    let tbl = $("#tbl_file");
    tbl.html('<thead><tr>' +
        '<th></th>' +
        '<th>Дата файла</th>' +
        '<th>Название файла</th>' +
        '<th>Аннотация</th>' +
        '<th>Пероналии</th>' +
        '<th>Научная тематика</th>' +
        '<th>Ссылки на архивный докумен</th>' +
        '<th>Ключевые слова</th>' +
        '<th>Файл</th>' +
        '</tr></thead>');
    $.each(data, function (i, v) {
        tbl.append('<tr>' +
            '<td style="width: 20px"><button type="button" class="btn btn-danger" onclick="delFile(' + v.id + ')"><i class="bi bi-trash"></i></button></td>' +
            '<td>' + v.date + '</td>' +
            '<td>' + v.name + '</td>' +
            '<td><textarea style="width: 100%" class="form-control">' + v.disc + '</textarea></td>' +
            '<td>' + v.person + '</td>' +
            '<td>' + v.sci_theme + '</td>' +
            '<td>' + v.doc + '</td>' +
            '<td>' + v.tag + '</td>' +
            '<td><a target="_blank" href="' + v.pathWeb + '">' + v.name + '</a></td>' +
            '</tr>')
    })
}

function updatePerson(data) {
    initTagAjax('#ev_pers', data);
    initTagAjax('#file_pers', data);
    let tbl = $("#tbl_person");
    tbl.html('<thead><tr>' +
        '<th></th>' +
        '<th>Фамилия</th>' +
        '<th>Имя</th>' +
        '<th>Отчество</th>' +
        '<th>Должность</th>' +
        '<th>Даты жизни</th>' +
        '<th>Аннотация</th>' +
        '<th>Ключевые</th>' +
        '<th>подразделение</th>' +
        '<th>тематика</th>' +
        '<th>файлы</th>' +
        '</tr></thead>');
    $.each(data, function (i, v) {
        let file = '';
        $.each(v.file, function (index, value) {
            file += "<a href='" + value.pathWeb + "' target='_blank'>" + value.name + "</a>";
        })
        tbl.append('<tr>' +
            '<td style="width: 20px"><button type="button" class="btn btn-danger" onclick="delPerson(' + v.id + ')"><i class="bi bi-trash"></i></button></td>' +
            '<td>' + v.F + '</td>' +
            '<td>' + v.I + '</td>' +
            '<td>' + v.O + '</td>' +
            '<td>' + v.DOL + '</td>' +
            '<td>c ' + v.DAYN + ' по ' + v.DAYD + '</td>' +
            '<td><textarea style="width: 100%" class="form-control">' + v.COMMENT + '</textarea></td>' +
            '<td>' + v.tag + '</td>' +
            '<td>' + v.sci_department + '</td>' +
            '<td>' + v.sci_theme + '</td>' +
            '<td>' + file + '</td>' +
            '</tr>')
    })
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

function load_person() {
    return $.ajax({
        async: false,
        type: 'POST',
        url: 'get.php?person',
        //data: 'tag='+tag,
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

function load_file() {
    return $.ajax({
        async: false,
        type: 'POST',
        url: 'get.php?file',
        //data: 'tag='+tag,
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
        $('#' + elem + '_tag').append('<option>' + tag.val() + '</option>').multiselect('refresh');
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
        $(elem).append('<option value="' + v.id + '">' + v.Name + '</option>');
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
        buttonW = '60%';
        menuWidth = '80%';
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
    }).multiselect('refresh').multiselectfilter({
        width: '300px'
    });
}