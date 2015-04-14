function init_keyword() {
    $('#overlay').keypress(function (e) {
        get_value(e)
    });
}

function get_value(e) {
    if (e.which == 13) {
        e.preventDefault();
        var input = $('.input:focus');
        if (input.hasClass('keyword')) {
            get_links(input.val());
        }
        else if (input.hasClass('term')) {

        }
    }
}

function get_links(keyword) {
    $.ajax({
        url: "xhr/xhr.php?action=get",
        type: 'post',
        data: {'keyword': keyword},
        success: show_links
    })

}

function show_links(data){
    console.log(data);
}