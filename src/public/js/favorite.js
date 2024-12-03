$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('.like-button').on('click', function () {
        const shop_id = $(this).data('id');
        if (this.classList.contains('liked')) {
            this.classList.remove('liked');
            this.classList.replace('las', 'lar');
        } else {
            this.classList.add('liked');
            this.classList.replace('lar', 'las');
        }
        $.ajax({
            url: '/favorite',
            type: 'POST',
            data: {
                shop_id: shop_id
            },
            dataType: "json",
        }).done(function (res) {
            console.log(res);
        }).fail(function () {
            alert('通信の失敗をしました');
        });
    });
});
