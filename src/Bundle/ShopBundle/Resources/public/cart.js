$(document)
    .on('click', '[data-cart-add]', function (e) {
        e.preventDefault();

        var $this = $(e.target).closest('a');
        $.ajax({
            type: 'POST',
            url: $this.attr('href'),
            data: {
                _csrf_token: window.csrf,
                id: $this.attr('data-id')
            },
            success: function (data) {
                if (data.status) {
                    $($this.attr('data-selector')).text(data.price);
                }
            }
        })
    })
    .on('click', '[data-cart-remove]', function (e) {
        e.preventDefault();
        var $this = $(e.target).closest('a');

        if (confirm($this.attr('data-confirm'))) {
            $.ajax({
                type: 'POST',
                url: $this.attr('href'),
                data: {
                    _csrf_token: window.csrf,
                    index: $this.attr('data-index')
                },
                success: function (data) {
                    if (data.status) {
                        $($this.attr('data-selector')).text(data.price);
                    }
                }
            })
        }
    })

    .on('click', '[data-favorite-add]', function (e) {
        e.preventDefault();
        var $this = $(e.target).closest('a');

        $.ajax({
            type: 'POST',
            url: $this.attr('href'),
            data: {
                _csrf_token: window.csrf,
                id: $this.attr('data-id')
            },
            success: function (data) {
                if (data.status) {
                    $($this.attr('data-selector')).text(data.count);
                }
            }
        });
    })
    .on('click', '[data-favorite-remove]', function (e) {
        e.preventDefault();
        var $this = $(e.target).closest('a');

        if (confirm($this.attr('data-confirm'))) {
            $.ajax({
                type: 'POST',
                url: $this.attr('href'),
                data: {
                    _csrf_token: window.csrf,
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