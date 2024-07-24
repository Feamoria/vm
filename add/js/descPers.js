function save() {

    let Person={}
    Person.id=$('#idPerson').val();
    Person.dol=$('#dol').val();
    let comment=encodeURIComponent(tinymce.get("comment").getContent());
    let awards=encodeURIComponent(tinymce.get("awards").getContent());
    let publications=encodeURIComponent(tinymce.get("publications").getContent());
    //console.log(Person);
    $.ajax({
        type: 'POST',
        url: 'set.php?descPerson',
        //data: JSON.stringify(parameters),
        data: 'data='+JSON.stringify(Person)+
            '&comment='+comment+
            '&awards='+awards+
            '&publications='+publications,
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
        url: '../get.php?mod&person='+id,
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

            console.log(data.person[0]);
            let pers=data.person[0];
            tinymce.get("comment").setContent(pers.comment, { format: "html" });
            tinymce.get("awards").setContent(pers.awards, { format: "html" });
            tinymce.get("publications").setContent(pers.publications, { format: "html" });
            $('#idPerson').val(pers.id);
            $('#FIO').val(pers.F+' '+pers.I+' '+pers.O);
            $('#dol').val(pers.dol);
            $('#create_user').val(pers.create_user.fio)
            $('#create_dep').val(pers.create_user.dep)
            $('#create_date').val(pers.create_date)

            $('#isModerated').hide();
            if (pers.moderated !=='0') {
                $('#moderated_user').val(pers.moderated_user.fio);
                $('#moderated_dep').val(pers.moderated_user.dep);
                $('#moderated_date').val(pers.moderated_date);
                $('#isModerated').show();
            }
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
        save();
    })
    tinymce.init({
        selector: '#comment,#publications,#awards',  // change this value according to the HTML
        menubar: 'edit format',
        language: 'ru',
        license_key: 'gpl',
        browser_spellcheck: true,
        contextmenu: false,
        plugins:['wordcount','searchreplace','lists','link', 'autolink','quickbars','visualchars'],
        link_default_target: '_blank',
        quickbars_selection_toolbar:'bold italic | blocks | quicklink blockquote | numlist bullist',
        //toolbar: 'numlist bullist',
        setup: function (editor) {
            editor.on('init', function (e) {
                load(5)
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
        toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | link | visualchars'
    });

    //load(2);
})