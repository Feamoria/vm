function save() {
    let Content = tinymce.get("disc").getContent();
    console.log(Content);
    tinymce.activeEditor.setContent("<p>Hello world!</p>");

}

function load(id) {
    //https://www.tiny.cloud/blog/how-to-get-content-and-set-content-in-tinymce/
    $.ajax({
        async:false,
        url: '../get.php?event='+id,
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
            let Name= data.event[0].Name;
            let id = data.event[0].id;
            dateEvent
            //console.log(data.event[0]);
           // console.log(Desc);
            tinymce.get("disc").setContent(Desc, { format: "html" });
            $('#idEvent').val(id);
            $('#name').val(Name);
            $('#dateEvent').val(data.event[0].DateN);
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
    tinymce.init({
        selector: '#disc',  // change this value according to the HTML
        menubar: 'edit format',
        language: 'ru',
        browser_spellcheck: true,
        contextmenu: false,
        setup: function (editor) {
            editor.on('init', function (e) {
                load(504)
                //editor.setContent('<p>Hello world!</p>');
            });
        }
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
        //toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | outdent indent'
    });

    //load(2);
})