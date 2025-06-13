<?php
$this->title = 'Short URL Generator';
?>

<div class="container mt-5">
    <h3>Raccourcisseur de lien + QR Code</h3>
    <div class="input-group mt-4">
        <input type="text" id="originalUrl" class="form-control" placeholder="Entrez votre URL ici...">
        <button class="btn btn-primary" id="shortenBtn">OK</button>
    </div>
    <div id="result" class="mt-4"></div>
</div>

<?php
$checkUrl = \yii\helpers\Url::to(['site/shorten']);
$js = <<<JS
$('#shortenBtn').on('click', function() {
    const url = $('#originalUrl').val().trim();
    $('#result').html('⏳ Vérification...');
    
    $.post('$checkUrl', { url: url }, function(data) {
        if (data.success) {
            $('#result').html(
                '<p><strong>Lien court :</strong> <a href="' + data.short_url + '" target="_blank">' + data.short_url + '</a></p>' +
                '<p><img src="' + data.qr_url + '" alt="QR Code"></p>'
            );
        } else {
            $('#result').html('<div class="alert alert-danger">' + data.message + '</div>');
        }
    }).fail(function() {
        $('#result').html('<div class="alert alert-danger">Erreur lors de la requête.</div>');
    });
});
JS;

$this->registerJs($js);
?>