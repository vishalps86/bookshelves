jQuery(document).ready(function ($v) {

    $v(document).on("keyup", "#search_box", function () {
        setTimeout(function () {            
            get_bookshelves()
        }, 2000);

    })

    $v(document).on("change", ".shelves", function () {        
        get_bookshelves()
    })

})

function get_bookshelves() {
    var data = {};
    data.callback = jQuery.trim(jQuery("#callback").val());
    data.form_data = jQuery("#grid-filter").serializeArray();
    page_ajaxify(data);
    return true
}
