$(function () {

    $("#Chaek").on("click", function () {
        $("#datatable").DataTable().rows().select();
    });

    $("#Uncheck").on("click", function () {
        $("#datatable").DataTable().rows().deselect();
    });

    $("#Search").on("click", function () {
        $("#Search-Modal").modal({
            backdrop: false,
            keyboard: false
        });
    });

    $("#Search-Submit").on("click", function () {
        $("#datatable").DataTable().search($("#global_text").val());
        let name = null;
        $.each($(".colfilter"), function () {
            name = $(this).attr("name");
            $("#datatable").DataTable().column(name + ":name").search($(this).val(), $("#regex-" + name).prop("checked"));
        });
        $("#datatable").DataTable().draw();
    });

    $("#Search-Clear").on("click", function () {
        $("#Search-Modal input:text").val("");
        $("#Search-Modal input:checkbox").prop("checked", false);
    });

});