$(document).ready(function () {

// User filter
    $("select[name=filter_assign]").select2({
        placeholder: $('select[name=filter_assign] option:first-child').text(),
        ajax: {
            url: '/api/profile/query-users',
            dataType: 'json',
            processResults: function (data) {
                let formattedData = [];

                Object.keys(data).forEach(function (id) {
                    formattedData.push({
                        'id': id,
                        'text': data[id]
                    })
                });

                return {
                    results: formattedData
                }
            }
        }
    });
});