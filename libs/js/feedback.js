function handle_voting(link) {

    link.children('.voting').find('li').on('click', function (e) {

        var link_id = link.children('.link-id').html();
        var term = link.children('.link').html();
        var vote = $(e.currentTarget).attr('class');

        $.ajax({
            url: "xhr/xhr.php?action=feedback",
            type: 'post',
            data: {
                'link_id': link_id,
                'term': term,
                'vote': vote
            },
            link: link,
            success: function (data, statusText, xhr) {
                feedback_callback(data, statusText, xhr, link)
            }
        });

    });
}

function feedback_callback(data, statusText, xhr, link) {

    console.log(link);

    if (xhr.status = !200) {
        handle_error(xhr.status);
        return;
    }

    if (data['status'] != 'success') {
        var message = 'I\'m not able to that right now, sorry!';
        handle_error(null, message);
        return;
    }

    link.children('.voting').remove();

    var color_class;

    switch (data['vote']) {
        case 'upvote':
            color_class = 'green';
            break;
        case 'downvote':
            color_class = 'red';
            break;
        case 'report':
            color_class = 'blue';
            break;
    }

    link.children('.link').addClass(color_class);
}

function handle_next(link) {
    link.children('.link').on('click', function (e) {

        var term = link.children('.link').html();
        var temp = $(link).removeClass('link-container').addClass('temp');

        temp.children('.voting').remove();
        $('#keyword').css('color', 'transparent');

        temp.animate({
            left: '50%',
            top: '50%'
        },300, function () {
            temp.remove();
            $('#keyword').val(term).css('color', 'inherit');
        });


        get_links(term);
    });
}