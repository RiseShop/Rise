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
                    $($this.attr('data-selector')).text(data.count);
                } else {
                    $(data.html).magnificPopup('open');
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
                        $($this.attr('data-selector')).text(data.count);
                    }
                }
            })
        }
    });