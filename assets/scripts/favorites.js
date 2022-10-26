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