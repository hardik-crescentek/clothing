function uuid(){
	return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
		var r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
		return v.toString(16);
	  });
}
jQuery(function ($) {

    

    $('.dropdown-menu li').click(function(){
        $(this).parent().closest('div').find('.response-name-label').text($(this).text());
        var dataId= $(this).data('responseInfo');
        var requestId= $(this).data('requestInfo');

        $(".formatted-requests[data-request-id=" + requestId + "]").hide();
        $(".formatted-requests[data-id=" + dataId + "]").show();
    });

    $('.is-expandable').click(function () {
        var modal = $('#snippetModal');
        var current = $(this);
        $("#snippetModal .modal-header .title").empty().text(current.data('title'));

        $("#snippetModal code").text($(this).text());
        modal.toggle('.modal-open');
        modal.show();
		window.Prism.highlightAllUnder(modal.get(0));
    })

    $('.close').click(function () {
        var modal = $('#snippetModal');
        modal.toggle('.modal-open');
        modal.hide();
    })
	

window.Prism = window.Prism || {};
// window.Prism.manual = true;

console.log(window.Prism);

});





