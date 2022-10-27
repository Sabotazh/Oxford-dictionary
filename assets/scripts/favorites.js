const alertPlaceholder = document.getElementById('liveAlertPlaceholder')

const allert = (message, type) => {
    const wrapper = document.createElement('div')
    wrapper.innerHTML = [
        `<div class="alert alert-${type} alert-dismissible mt-3 position-fixed" role="alert" style="right: 12px">`,
        `   <div>${message}</div>`,
        '   <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>',
        '</div>'
    ].join('')

    alertPlaceholder.append(wrapper)
}

$('#save-to-favorite').on('click', function(e) {
    e.preventDefault();
    $.ajax({
        url: '/user/favorite/add',
        method: 'POST',
        data: {
            favorite: $('#search').val()
        }
    }).then((response) => {
        allert(response.message, 'success')
    }).fail((e) => {
        allert(e.responseJSON.message, 'danger')
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
