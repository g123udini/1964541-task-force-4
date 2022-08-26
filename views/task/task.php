<div class="left-column">
    <h3 class="head-main head-task">Новые задания</h3>
    <?php
    use yii\widgets\ActiveField;
    use yii\widgets\ActiveForm;foreach ($tasks as $task): ?>
    <div class="task-card">
        <div class="header-task">
            <a  href="#" class="link link--block link--big"><?= $task->title ?></a>
            <p class="price price--task"><?= $task->price ?></p>
        </div>
        <p class="info-text"><span class="current-time">4 часа </span>назад</p>
        <p class="task-text"><?= $task->description ?></p>
        <div class="footer-task">
            <p class="info-text town-text"><?= $task->city->name ?></p>
            <p class="info-text category-text"><?= $task->category->name ?></p>
            <a href="#" class="button button--black">Смотреть Задание</a>
        </div>
    </div>
    <?php endforeach; ?>
    <div class="pagination-wrapper">
        <ul class="pagination-list">
            <li class="pagination-item mark">
                <a href="#" class="link link--page"></a>
            </li>
            <li class="pagination-item">
                <a href="#" class="link link--page">1</a>
            </li>
            <li class="pagination-item pagination-item--active">
                <a href="#" class="link link--page">2</a>
            </li>
            <li class="pagination-item">
                <a href="#" class="link link--page">3</a>
            </li>
            <li class="pagination-item mark">
                <a href="#" class="link link--page"></a>
            </li>
        </ul>
    </div>
</div>
<div class="right-column">
    <div class="right-card black">
        <div class="search-form">
            <?php $form = ActiveForm::begin([
                    'id' => 'filterForm']);?>
            <h4 class="head-card">Категории</h4>
            <?php
            echo $form->field($model, 'category',['template' => "{input}\n{error}"])->checkboxList($model->attributeLabels());
            ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>