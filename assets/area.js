(function(){
    $('[role="area.add"]').on('click', '', function(e){
        var tmplId = $(this).data('tmpl');
        var odata = {};
        var content = $(tmpl( tmplId , odata));
        $('#areaId' + tmplId).append(content);
    });
    $('body').on('click','[role="area.remove"]', function(e){
        $(this).closest('.areaItem').remove();
    });

})(jQuery)