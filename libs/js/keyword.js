var clockwise = false;
var voting =
    '<div class="voting">' +
    '<ul>' +
    '<li class="downvote">-</li>' +
    '<li class="upvote">+</li>' +
    '<li class="report">X</li>' +
    '</ul>' +
    '</div>';

var breadcrumbs = [];

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
            breadcrumb(null, null, true);
            get_links(input.val());
        }
        else if (input.hasClass('term')) {

        }
    }
}

function get_links(keyword) {
    thinking();

    $('body').find('.error').remove();
    $('#links').find('.link-container').remove();
    $('#canvas').animate({
        'height': '0',
        'width': '0',
        'top': '50%',
        'left': '50%'
    }, 500);


    $.ajax({
        url: "xhr/xhr.php?action=get",
        type: 'post',
        data: {'keyword': keyword},
        success: links_callback
    })

}

function links_callback(data, statusText, xhr) {

    if (xhr.status = !200) {
        handle_error(xhr.status);
        return;
    }

    if (data['status'] != 'success') {
        var message = 'Are you sure that\'s a word? ';
        handle_error(null, message);
        return;
    }
    append_links(data['keyword'], data['links']);
}

function append_links(keyword, links) {
    $('#canvas').clearCanvas();

    thinking(true);

    var height = $('#overlay').height();
    var width = $('#overlay').width();

    $('#canvas').attr({'width': width, 'height': height});

    var coordinates = circle_coordinates(links.length, width / 2, height / 2, height / 3, width / 3);
    var lines_end = circle_coordinates(links.length, width / 2, height / 2, height / 3.5, width / 3.5);


    $.each(links, function (i, val) {
        var id_div = '';
        if (!DEBUG) {
            id_div = '<div class="link-id">' + keyword.id + '-' + val.id + '</div>';
        }

        var link = $(
            '<div class="link-container">' +
            '<p class="link">'
            + val.term + '' +
            '</p>' +
            id_div +
            '</div>'
        ).
            appendTo('#links').css({
                'opacity': 0,
                'left': width / 2,
                'top': height / 2
            });

        link.append(voting);
        handle_voting(link);
        handle_next(link);

        link.on('click', function () {
            link.children('.voting').fadeToggle();
        });
        link.hover(function () {
            link.children('.voting').fadeToggle();
        });

        var height_offset = link.height() / 2;
        var width_offset = link.width() / 2;
        var centerX = coordinates[i]['x'];
        var centerY = coordinates[i]['y'];

        draw_gradient_line(width / 2, height / 2, lines_end[i]['x'], lines_end[i]['y']);

        var delay;
        if (clockwise) {
            delay = (links.length * 50) - (i * 50);
        } else {
            delay = i * 50;
        }

        link.animate({
            opacity: 1,
            'margin-left': -width_offset,
            'margin-top': -height_offset,
            'left': (centerX / width) * 100 + '%',
            top: (centerY / height) * 100 + '%'
        }, delay);

    });
    breadcrumb(keyword.content, links);

    //draw_input_lines(links.length, height, width);

    if (clockwise) {
        clockwise = false;
    } else {
        clockwise = true;
    }


    $('#canvas').animate({
        'height': '100%',
        'width': '100%',
        'top': 0,
        'left': 0
    }, 500);

}

function circle_coordinates(steps, centerX, centerY, height, width) {

    var values = [];

    for (var i = 0; i < steps; i++) {
        var value = [];
        value['x'] = (centerX + width * Math.cos(2 * Math.PI * i / steps));
        value['y'] = (centerY + height * Math.sin(2 * Math.PI * i / steps));

        values.push(value);
    }

    return values;

}

function handle_error(status, message) {
    thinking(true);

    if (status == 400) {
        //missing parameter
        return false;
    }

    var error = 'Oops... something went wrong, please try again.';
    if (message != '') {
        error = message;
    }

    $('#error-placeholder').append('<p class="error">' + error + '</p>')
}

function draw_input_lines(length, height, width) {

    var lines_input = circle_coordinates(length, width / 2, height / 2, height / 2.5, width / 2.5);

    $.each(lines_input, function (i, val) {
        draw_gradient_line(width / 2, height / 2, lines_input[i]['x'], lines_input[i]['y']);
    });

}

function thinking(stop) {
    if (stop) {
        $('#error-placeholder').children('.thinking').remove();
    }
    else {
        var random = Math.floor((Math.random() * thinking_array.length));
        $('<p class="thinking">' + thinking_array[random] + '</p>').appendTo('#error-placeholder').fadeIn();
    }
}

function breadcrumb(keyword, links, empty) {
    if (empty) {
        $('#breadcrumbs').empty();
        breadcrumbs = [];
        return;
    }

    if (keyword == undefined) {
        return;
    }

    console.log(keyword);
    console.log(links);

    breadcrumbs.push({
        id: breadcrumbs.length,
        keyword: keyword,
        links: links
    });

    var crumb = $('<div class="breadcrumb">' + keyword + '<span class="bc-sep">></span></div>');


    crumb.appendTo('#breadcrumbs')
        .on("click", function () {

            $('body').find('.error').remove();
            $('#links').find('.link-container').remove();
            $('#canvas').animate({
                'height': '0',
                'width': '0',
                'top': '50%',
                'left': '50%'
            }, 500);


            $('#keyword').css('color', 'transparent');


            var temp = crumb.clone().css(
                {
                    'position': 'absolute',
                    'top': crumb.offset().top,
                    'left': crumb.offset().left
                });

            temp.children('.bc-sep').remove();
            temp.appendTo('#overlay');

            temp.animate({
                left: '50%',
                top: '50%',
                'fontSize': 18
            }, 500, function () {
                temp.remove();
                $('#keyword').val(keyword).css('color', 'inherit');
                append_links(keyword, links);
            });
        })
        .animate({
            'opacity': 1
        })

}

