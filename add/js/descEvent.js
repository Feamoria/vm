function save(type) {
    /*
    Систематическое изучение сельскохозяйственных возможностей и природных богатств Печорского края связано с именем А.В. Журавского, который стал одним из первых исследователей края, привлекающим внимание правительственных и научных организаций к нуждам северных территорий. В его работах «Печорская естественно-историческая станция при Императорской Академии наук. Её задачи и история возникновения», «Приполярная Россия в связи с разрешением общегосударственного аграрного и финансового кризиса (экономический потенциал Севера)», «Приполярная Россия. Нефть в бассейне Печоры», «К проблеме колонизации Печорского края» подняты важные вопросы социально-экономического развития Севера. А.В. Журавский сотрудничал с органами землеустройства Российской империи по вопросам выявления земельных потенциалов нижнепечорских земель, по выработке принципов научного обоснования разделения России на физико-географические районы, по вопросам землеустройства в северных губерниях, подготовке и экспедиционном исследовании Печорского края. Именно А.В. Журавскому мы обязаны тем, что на берегу Печоры, в 1905-1906 гг. была заложена академическая наука на территории Республики Коми.
     */
    let event={}
    event.id=$('#idEvent').val();
    let Desc =encodeURIComponent(tinymce.get("disc").getContent());
    let Doc =encodeURIComponent(tinymce.get("doc").getContent());
    event.Name=$('#name').val();
    console.log(event);
    $.ajax({
        type: 'POST',
        url: 'set.php?descEvent&type='+type,
        //data: JSON.stringify(parameters),
        data: 'data='+JSON.stringify(event)+'&Desc='+Desc+'&Doc='+Doc,
        dataType: 'json',
        cache: false,
        success: function(data) {
            if (typeof data.err ==='undefined'){
                alert('Сохранено');
            } else alert(data.err)
            // do something with ajax data

        },
        error:function (xhr, ajaxOptions, thrownError){
            console.log('error...', xhr);
            //error logging
        },
        complete: function(){
            //afer ajax call is completed
        }
    });
    //tinymce.activeEditor.setContent("<p>Hello world!</p>");

}

function load(id) {
    //https://www.tiny.cloud/blog/how-to-get-content-and-set-content-in-tinymce/
    $.ajax({
        async:false,
        url: '../get.php?mod&event='+id,
        dataType: 'json',
        cache: false,
        success: function(data) {
            // do something with ajax data
            let next =''
            if ( data.next !==null){
                next=data.next
                $('#btnNext').show().unbind().on('click',function (){
                    load(next);
                })
            } else $('#btnNext').hide();
            let back =''
            if ( data.back !==null){
                back=data.back;
                $('#btnBack').show().unbind().on('click',function (){
                    load(back);
                })
            } else $('#btnBack').hide();

            //console.log(next,back);
            let Desc= data.event[0].Desc;
            let Doc	= data.event[0].Doc;
            let Name= data.event[0].Name;
            let id = data.event[0].id;
           // dateEvent
            //console.log(data.event[0]);
           // console.log(Desc);
            tinymce.get("disc").setContent(Desc, { format: "html" });
            tinymce.get("doc").setContent(Doc, { format: "html" });
            $('#idEvent').val(id);
            $('#name').val(Name);
            $('#dateEvent').val(data.event[0].DateN);
            $('#create_user').val(data.event[0].create_user.fio)
            $('#create_dep').val(data.event[0].create_user.dep)
            $('#create_date').val(data.event[0].create_date)
            /*create_user
            create_dep
            create_date*/
            $('#isModerated').hide();
            if (data.event[0].moderated !=='0') {
                $('#moderated_user').val(data.event[0].moderated_user.fio);
                $('#moderated_dep').val(data.event[0].moderated_user.dep);
                $('#moderated_date').val(data.event[0].moderated_date);
                $('#isModerated').show();
            }
            $(window).scrollTop($('#idEvent').offset().top);
            //idEvent
           // isModerated
        }
    });


}
function isInt(value) {
    return !isNaN(value) &&
        parseInt(Number(value)) === value &&
        !isNaN(parseInt(value, 10));
}
$(document).ready(function () {
    $('#btnStep').on('click',function (){
        let Step = $('#Step').val();
        if (parseInt(Step)>0) {
                load(Step);
        } //else console.log(isInt(Step))
    })
    $('#btnSave').on('click',function (){
        save(0);
    })
    $('#btnPublic').on('click',function (){
        save(1);
    })
    tinymce.init({
        selector: '#disc,#doc',  // change this value according to the HTML
        menubar: 'edit format',
        language: 'ru',
        license_key: 'gpl',
        browser_spellcheck: true,
        contextmenu: false,
        plugins:['wordcount','searchreplace','lists','link', 'autolink','quickbars','visualchars'],
        link_default_target: '_blank',
        quickbars_selection_toolbar:'bold italic | blocks | quicklink blockquote | numlist bullist | removeformat',
        //toolbar: 'numlist bullist',
        setup: function (editor) {
            editor.on('init', function (e) {
                load(curent)
                //editor.setContent('<p>Hello world!</p>');
            });
        },
        /*menu: {
            file: { title: 'File', items: 'newdocument restoredraft | preview | importword exportpdf exportword | print | deleteallconversations' },
            edit: { title: 'Edit', items: 'undo redo | cut copy paste pastetext | selectall | searchreplace' },
            view: { title: 'View', items: 'code revisionhistory | visualaid visualchars visualblocks | spellchecker | preview fullscreen | showcomments' },
            insert: { title: 'Insert', items: 'image link media addcomment pageembed codesample inserttable | math | charmap emoticons hr | pagebreak nonbreaking anchor tableofcontents | insertdatetime' },
            format: { title: 'Format', items: 'bold italic underline strikethrough superscript subscript codeformat | styles blocks fontfamily fontsize align lineheight | forecolor backcolor | language | removeformat' },
            tools: { title: 'Tools', items: 'spellchecker spellcheckerlanguage | a11ycheck code wordcount' },
            table: { title: 'Table', items: 'inserttable | cell row column | advtablesort | tableprops deletetable' },
            help: { title: 'Help', items: 'help' }
        }*/
        toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | link | visualchars codeformat removeformat'
    });

    //load(2);
})