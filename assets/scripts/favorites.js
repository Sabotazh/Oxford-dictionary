$('#save-to-favorite').on('click', function(e) {
    e.preventDefault();
    $.ajax({
        url: '/favorites',
        method: 'POST',
        data: $('#search').val()
    }).then(() => {
        // 
    })
})