$(function()
{
    $("input").blur(function()
    {
        var formElementId = $(this).parent().find('label').attr('for');
        doValidation(formElementId);
    });
});
function doValidation(id)
{
 //  alert(id);

    
    var url = 'validateform'
    var data = {};
    $("input").each(function()
    {
        data[$(this).attr('name')] = $(this).val();
    });
    $.post(url,data,function(resp)
    {
        $("#"+id).parent().find('.errors').remove();
        $("#"+id).parent().append(getErrorHtml(resp[id], id));
    },'json');
}
function getErrorHtml(formErrors , id)
{
    var o = '<ul id="errors-'+id+'" class="errors">';
    for(errorKey in formErrors)
    {
        o += '<li>' + formErrors[errorKey] + '</li>';
    }
    o += '</ul>';
    return o;
}

