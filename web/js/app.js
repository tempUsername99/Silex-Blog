$(document).ready(function () {
    $("article h2 a").click(function () {
        $("article h2 a").not(this).parent().parent().hide("slow");
        $('.articleBody').show();
        $('.showList').show();
        $(this).parent().parent().find('.badge-views').load('/articles/' + $(this).parent().parent().find('#dontlookme').val());
    });

    $(".showList").click(function () {
        $('.articleBody').hide();
        $('.showList').hide();
        $('article').show();
    });
});