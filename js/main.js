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
                file_html="<img class='timeline-img' src='"+val.file[0].pathWeb+"' alt='"+val.file[0].Name+"'>"
            } else {
                let first=true;
                let carousel_indicators='';
                let carousel_item='';
                $.each(val.file,function (index, value) {
                    let carousel_indicator_active='';
                    let carousel_item_active='';
                    if (first) {
                        carousel_indicator_active ='class="active" aria-current="true';
                        carousel_item_active='active';
                        first=false;
                    }
                    carousel_indicators+='<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="'+index+'" '+carousel_indicator_active+' aria-label="Slide '+(index+1)+'"></button>'
                    carousel_item+='<div class="carousel-item '+carousel_item_active+'">' +
                        '      <img src="'+value.pathWeb+'" class="d-block w-100" alt="'+value.Name+'">' +
                        '      <div style="background-color: gray" class="carousel-caption d-none d-md-block">' +
                        '        <h5>'+value.Name+'</h5>' +
                        '        <p>'+value.disc+'</p>' +
                        '      </div>' +
                        '    </div>'
                });
                file_html='<div id="carouselExampleCaptions" class="carousel slide">' +
                    '<div class="carousel-indicators">' +carousel_indicators+'</div>'+
                    '<div class="carousel-inner">'+carousel_item+'</div>'+
                    '<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">' +
                    '    <span class="carousel-control-prev-icon" aria-hidden="true"></span>' +
                    '    <span class="visually-hidden">Предыдущий</span>' +
                    '  </button>' +
                    '  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">' +
                    '    <span class="carousel-control-next-icon" aria-hidden="true"></span>' +
                    '    <span class="visually-hidden">Следующий</span>' +
                    '  </button>' +
                    '</div>'
                //** TODO СЛАЙДЕР КАРТИНОК! */
                /*
<div id="carouselExampleCaptions" class="carousel slide">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="..." class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>Метка первого слайда</h5>
        <p>Некоторый репрезентативный заполнитель для первого слайда.</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="..." class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>Метка второго слайда</h5>
        <p>Некоторый репрезентативный заполнитель для второго слайда.</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="..." class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>Метка третьего слайда</h5>
        <p>Некоторый репрезентативный заполнитель для третьего слайда.</p>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Предыдущий</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Следующий</span>
  </button>
</div>






                 */
                //file_html="<img class='timeline-img' src='"+val.file[0].pathWeb+"'>"
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
                tag_html+="<a href='?tag=" + v.id + "'>[" + v.Name+ "]</a>";

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
function loadPerson(){
    let personID=$('#personID').val();
    return $.ajax({
        async:false,
        type: 'POST',
        url: 'get.php?person',
        data: 'person='+personID,
        //contentType: 'application/json;',
        dataType: 'json',
        cache: false,
        //success: function(data) {}
    }).responseJSON;
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
    if ($('#personID').length >0) {
        let data=loadPerson();
        $('#person').html(JSON.stringify(data));
        return;
    }
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
    /*let temp=loadEvent();
    $('#tempCont').html(JSON.stringify(temp));*/
})