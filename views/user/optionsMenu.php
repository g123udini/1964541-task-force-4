<?php

use yii\widgets\Menu; ?>
<div class="left-menu left-menu--edit">
    <h3 class="head-main head-task">Настройки</h3>
    <?= Menu::widget(['items' => [
    ['label' => 'Мой профиль', 'url' => ['user/options'], 'template' => '<a href="{url}" class="link link--nav">{label}</a>'],
    ['label' => 'Безопасность', 'url' => ['user/security'], 'template' => '<a href="{url}" class="link link--nav">{label}</a>']],
        'options' => [
            'class' => 'side-menu-list',
        ],
        'activeCssClass'=>'side-menu-item--active',
        'itemOptions'=>['class'=>'side-menu-item'],
    ]);?>
</div>
