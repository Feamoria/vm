let level= {}
level['class'] = {
    1: 'danger',
    2: 'info',
    3: 'success',
    4: 'success'
}
level['glyphicon'] = {
    1: 'bi-exclamation-octagon',
    2 : 'bi-card-checklist',
    3 : 'bi-patch-check',
    4 : 'bi-patch-check'
};
async function buildEvent(){
    let data =loadEvent();
    let timeline=$('.timeline');
    timeline.empty();
    let inverted= false;
    //console.log(data.level);
    $.each(data.event,function (i, val) {
        let file_html='';
        if (val.file.length>0) {
            if (val.file.length===1){
                file_html="<img class='timeline-img' src='"+val.file[0].pathWeb+"'>"
            } else {
                //** TODO СЛАЙДЕР КАРТИНОК! */
                file_html="<img class='timeline-img' src='"+val.file[0].pathWeb+"'>"
            }
        }
        let person_html='';
        if (val.person.length>0) {
            $.each(val.person,function (i, v) {
                // ССЫЛКИ НА ПЕРСОНАЛИЮ переделать??
                person_html+="<a href='?person=" + v.id + "'>[" + v.Name+ "]</a>";
            });
            if (person_html !== '') {
                person_html = "<p><small class='text-muted'>Персоналии:  "+person_html+"</small></p>";
            }
        }

        let tag_html='';
        /** tag */
        if (val.tag.length>0) {
            $.each(val.tag,function (i, v) {
                // ССЫЛКИ НА ПЕРСОНАЛИЮ переделать??
                tag_html+="<a href='?person=" + v.id + "'>[" + v.Name+ "]</a>";

            });
            if (tag_html !== '') {
                tag_html = "<p><small class='text-muted'>Ключевые слова: "+tag_html+"</small></p>";
            }
        }
        /** dep */
        /** them */
        /** Date*/
        function getMonthName(monthNumber) {
            const Month =[
                'января', 'февраля', 'марта', 'апреля', 'мая', 'июня',
                'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря',
            ]
            return Month[monthNumber - 1]
            /*const date = new Date();
            date.setMonth(monthNumber - 1);
            return date.toLocaleString('ru', {
                month: 'long',
            });*/
        }
        let date_html='';
        let DT=val.Date.split('.');

        if (typeof DT[1] !=='undefined') {
            DT[1] = getMonthName(Number(DT[1]));
        }
        let temp = DT[0];
        DT[0]=DT[2];
        DT[2]=temp+' г.';
        date_html=DT.join(' ');
        let inverted_html=inverted?'class="timeline-inverted"':'';
        let html="<li "+inverted_html+">" +
            "<div class='timeline-badge "+level['class'][val.level]+"'><i class='bi "+level['glyphicon'][val.level]+"'></i></div>" +
            "<div class='timeline-panel'>" +
            "<div class='timeline-heading'>" +
            "<h4 class='timeline-title'>"+val.Name+"</h4>" +
            "<p><small class='text-muted'><i class='bi bi-clock'></i> "+date_html+" </small></p>" +
            "</div>" +
            "<div class='timeline-body'>" +
            file_html +
            "<button class='btn btn-sm btn-info' onclick='getInfo("+val.id+")'>Подробнее</button>" +
            person_html +
            tag_html +
            "</div>" +
            "</div>" +
            "</li>";
        timeline.append(html)
        inverted=!inverted;
    });
}
function getInfo(idEvent) {
    // TODO!
    $.ajax({
        async:true,
        type: 'POST',
        url: 'get.php?eventDisc',
        data: 'id='+idEvent,
        //contentType: 'application/json;',
        dataType: 'json',
        cache: false,    
        success: function(data) {

            $('#infoFM_title').html(data.Desc[0].Name);
            $('#infoFM_body').html(data.Desc[0].Desc);
            //console.log(data.Desc[0].Desc);
            $("#infoFM").modal('show');
        }
    });


}
 function loadEvent() {
    let them=$('.b1.btn-info').attr('value');
     if (typeof them ==='undefined')
         them=0;


    let param={
        'year':$('#years').slider('values'),
        'mod':mod,
        'them':them,
    }

    return $.ajax({
        async:false,
        type: 'POST',
        url: 'get.php?event',
        data: JSON.stringify(param),
        contentType: 'application/json;',
        dataType: 'json',
        cache: false,
        //success: function(data) {}
    }).responseJSON;
}
let mod=16;
let lastBtn=null;
$(document).ready(function () {
    let min=$('#custom-handle-min').attr('year');
    let max=$('#custom-handle-max').attr('year');
    mod = (max-min)/5
    $('#years').slider({
        range: true,
        min: Number(min),
        max: Number(max),
        values: [min, max],
        create: function (){
            $('#custom-handle-min').html(min);
            $('#custom-handle-max').html(max);
            buildEvent();
        },
        slide: function(event, ui) {
            $('#custom-handle-min').html(ui.values[0]);
            $('#custom-handle-max').html(ui.values[1]);
        },
        change: function( event, ui ) {
            //console.log(ui.values);
            buildEvent();
        }
    });
    $('.b1').on('click',function (/*e,ui*/){
        if (lastBtn !==null) {
            lastBtn.removeClass('btn-info').addClass('btn-primary');
            if (lastBtn.prop('value') !== $(this).prop('value')) {
                $(this).removeClass('btn-primary').addClass('btn-info');
                lastBtn =null;
            }
        } else $(this).removeClass('btn-primary').addClass('btn-info');
        lastBtn=$(this);
        buildEvent();
    })
})