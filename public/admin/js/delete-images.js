// delete fav icon
var ids;
$("#delete-fav").on('click', function(){

    // delete from db
    $.ajax({
        url: "/admin/delete/fav" ,
        type: 'POST',
        data: { ids: ids },
        success:function(data){
            window.location.href = data;
            $(":checkbox").attr("autocomplete", "off");
        }
    });

});
// delete logo header
var ids;
$("#delete-logo-h").on('click', function(){

    // delete from db
    $.ajax({
        url: "/admin/delete/logoHeader" ,
        type: 'POST',
        data: { ids: ids },
        success:function(data){
            window.location.href = data;
            $(":checkbox").attr("autocomplete", "off");
        }
    });

});
// delete logo footer
var ids;
$("#delete-logo-f").on('click', function(){

    // delete from db
    $.ajax({
        url: "/admin/delete/logoFooter" ,
        type: 'POST',
        data: { ids: ids },
        success:function(data){
            window.location.href = data;
            $(":checkbox").attr("autocomplete", "off");
        }
    });

});
// delete logo section
var ids;
$("#delete-logo-s").on('click', function(){

    // delete from db
    $.ajax({
        url: "/admin/delete/logoSection" ,
        type: 'POST',
        data: { ids: ids },
        success:function(data){
            window.location.href = data;
            $(":checkbox").attr("autocomplete", "off");
        }
    });

});
// delete logo watermark
var ids;
$("#delete-logo-w").on('click', function(){

    // delete from db
    $.ajax({
        url: "/admin/delete/watermark" ,
        type: 'POST',
        data: { ids: ids },
        success:function(data){
            window.location.href = data;
            $(":checkbox").attr("autocomplete", "off");
        }
    });

});