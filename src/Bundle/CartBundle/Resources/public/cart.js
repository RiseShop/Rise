$(document)
    .on('click', '[data-cart-add]', function (e) {
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
                    mindy.notify('Товар добавлен в корзину');
                    $($this.attr('data-price-selector')).text(data.cart.total_price);
                    $($this.attr('data-html-selector')).replaceWith(data.html);
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
                dataType: 'json',
                url: $this.attr('href'),
                data: {
                    key: $this.attr('data-key')
                },
                success: function (data) {
                    if (data.status) {
                        mindy.notify('Позиция удалена из корзины');
                        $($this.attr('data-price-selector')).text(data.cart.total_price);
                        $($this.attr('data-html-selector')).replaceWith(data.html);
                    }
                }
            })
        }
    });

/**
 * Counter
 */

$(document)
    .on('click', '[data-cart-counter]', function (e) {
        e.preventDefault();

        var $this = $(e.target).closest('a'),
            operation = $this.attr('data-cart-operation'),
            $text = $($this.attr('data-cart-selector')),
            value = parseInt($text.text());

        if (operation == 'inc') {
            value += 1;
        } else if (operation == 'dec') {
            value -= 1;
            if (value <= 1) {
                value = 1;
            }
        } else {
            console.log('Unknown operation');
            return;
        }

        $text.text(value);

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: $this.attr('href'),
            data: {
                index: $this.attr('data-key'),
                quantity: value
            },
            success: function (data) {
                if (data.status) {
                    $($this.attr('data-price-selector')).text(data.cart.total_price);
                    $($this.attr('data-html-selector')).replaceWith(data.html);
                }
            }
        })
    });