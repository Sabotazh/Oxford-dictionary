$('#save-to-favorite').on('click', function(e) {
    e.preventDefault();
    $.ajax({
        url: '/user/favorite/add',
        method: 'POST',
        data: {
            favorite: $('#search').val()
        }
    }).then(() => {
        // 
    })
})

$('.remove-favorite').on('click', function(e) {
    e.preventDefault();
    let id = $(this).data('id');
    $('table#favorites tr#row-'+id).remove();
    $.ajax({
        url: '/user/favorite/delete/'+id,
        method: 'DELETE'
    }).then(() => {
        $('table#favorites tr#row-'+id).remove();
    })
})