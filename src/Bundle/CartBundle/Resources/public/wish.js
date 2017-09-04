$(document)
    .on('click', '[data-wish-add]', function (e) {
        e.preventDefault();
        var $this = $(e.target).closest('a');

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: $this.attr('href'),
            data: {
                id: $this.attr('data-id')
            },
            success: function (data) {
                if (data.status) {
                    mindy.notify('Товар добавлен в избранное');
                    $($this.attr('data-selector')).text(data.count);
                } else {
                    mindy.notify(data.error, 'error');
                    /*
                    $.magnificPopup.open({
                        items: {
                            src: data.html
                        },
                        type: 'inline'
                    }, 0);
                    */
                }
            }
        });
    })
    .on('click', '[data-wish-remove]', function (e) {
        e.preventDefault();
        var $this = $(e.target).closest('a');

        if (confirm($this.attr('data-confirm'))) {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: $this.attr('href'),
                data: {
                    id: $this.attr('data-id')
                },
                success: function (data) {
                    if (data.status) {
                        mindy.notify('Товар удален из избранного');
                        $($this.attr('data-selector')).text(data.count);
                    } else {
                        mindy.notify(data.error, 'error');
                        /*
                        $.magnificPopup.open({
                            items: {
                                src: data.html
                            },
                            type: 'inline'
                        }, 0);
                        */
                    }
                }
            })
        }
    });