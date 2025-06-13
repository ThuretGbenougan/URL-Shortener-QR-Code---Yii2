<?php

use yii\helpers\Html;

$this->title = 'Статистика';
?>

<div class="container mt-5">
    <h3>📈 Статистика по ссылке</h3>

    <p><strong>Оригинальная ссылка:</strong> <?= Html::encode($url->original_url) ?></p>
    <p><strong>Короткая ссылка:</strong> <?= Html::a(Yii::$app->request->hostInfo . '/u/' . $url->short_code, ['/site/redirect', 'code' => $url->short_code]) ?></p>
    <p><strong>Общее количество переходов:</strong> <?= $url->clicks ?></p>

    <hr>

    <h5>🌍 Детали переходов</h5>
    <?php if (empty($logs)): ?>
        <p class="text-muted">Нет переходов по этой ссылке.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>IP-адрес</th>
                    <th>Дата и время</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?= Html::encode($log->ip_address) ?></td>
                        <td><?= date('Y-m-d H:i:s', strtotime($log->visited_at)) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>