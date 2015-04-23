(function () {
    $('#info-wrapper .info-button').on("click", function(){
        $('#info-wrapper .info-text').fadeToggle(200);
    });
    init_canvas();
    init_keyword();
})();