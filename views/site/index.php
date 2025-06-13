<?php
$this->title = 'Short URL Generator';
?>

<div class="container mt-5">
    <h3>Сокращатель ссылок + QR-код</h3>
    <div class="input-group mt-4">
        <input type="text" id="originalUrl" class="form-control" placeholder="Вставьте вашу ссылку здесь...">
        <button class="btn btn-primary" id="shortenBtn">Хорошо</button>
    </div>
    <div id="result" class="mt-4"></div>
</div>

<?php

use yii\helpers\Url;

$checkUrl = Url::to(['site/shorten']);
$js = <<<JS
$('#shortenBtn').on('click', function() {
    const url = $('#originalUrl').val().trim();
    $('#result').html('⏳ Проверка...');

    $.post('$checkUrl', { url: url }, function(data) {
        if (data.success) {
            const shortUrl = $('<div>').text(data.short_url).html(); // protège contre injection
            const qrUrl = $('<div>').text(data.qr_url).html();
            const statsUrl = '/stats/' + data.code;

            $('#result').html(
                '<p><strong>Короткая ссылка:</strong> ' +
                '<a id="shortLink" href="' + shortUrl + '" target="_blank">' + shortUrl + '</a> ' +
                '<button class="btn btn-sm btn-outline-secondary ms-2" id="copyBtn">Скопировать</button>' +
                '<span id="copiedMsg" class="text-success ms-2" style="display:none;">📋 Скопировано!</span>' +
                '</p>' +
                '<p>' +
                    '<img id="qrImage" src="' + qrUrl + '" alt="QR Code" class="img-thumbnail" style="max-width:200px;">' +
                    '<br>' +
                    '<a id="downloadBtn" href="' + qrUrl + '" download class="btn btn-sm btn-outline-success mt-2">Скачать QR-код</a>' +
                    ' <a href="' + statsUrl + '" class="btn btn-sm btn-info mt-2 ms-2" target="_blank">📊 Посмотреть статистику</a>' +
                '</p>'
            );

            $('#copyBtn').on('click', function() {
                navigator.clipboard.writeText($('#shortLink').attr('href')).then(function() {
                    $('#copiedMsg').fadeIn().delay(1500).fadeOut();
                });
            });

        } else {
            $('#result').html('<div class="alert alert-danger">' + data.message + '</div>');
        }
    }).fail(function() {
        $('#result').html('<div class="alert alert-danger">Ошибка при отправке запроса.</div>');
    });
});
JS;

$this->registerJs($js);
?>