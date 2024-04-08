function buildEvent(){
    let data =loadEvent()
    $.each(data,function (i, val) {
        console.log(i, val)
    });
}
function loadEvent() {
    let level = 5;
    let param={'level':level}
    return $.ajax({
        async:true,
        type: 'POST',
        url: 'get.php?event',
        data: JSON.stringify(param),
        contentType: 'application/json;',
        dataType: 'json',
        cache: false,
        success: function(data) {
            console.log(data)

            // do something with ajax data

        },
        error:function (xhr, ajaxOptions, thrownError){
            console.log('error...', xhr);
            //error logging
        }
    }).responseJSON;
}
$(document).ready(function () {
    buildEvent()
})