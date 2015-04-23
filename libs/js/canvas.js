function init_canvas(){
    var width = $('body').width();
    var height = $('body').height();

    $('#canvas').css({'top': '50%', 'left': '50%'});
    $('#canvas').attr({'width': width, 'height': height});

}

function draw_gradient_line(x1, y1, x2, y2){
    var linear = $('canvas').createGradient({
        x1: x1, y1: y1,
        x2: x2, y2: y2,
        c1: 'rgb(255, 255, 255)',
        c2: 'rgb(0, 0, 0)'
    });

    linear = 'rgb(110, 110, 110)';

    $('#canvas').drawLine({
        strokeStyle: 'rgb(110, 110, 110)',
        layer: true,
        groups: ['lines'],
        strokeWidth: 0.5,
        x1: x1, y1: y1,
        x2: x2, y2: y2
    });
}