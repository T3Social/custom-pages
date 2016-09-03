<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use humhub\modules\custom_pages\models\Page;
use humhub\modules\custom_pages\models\ContainerPage;
use humhub\modules\custom_pages\components\Container;
use humhub\modules\custom_pages\models\Snippet;
use humhub\modules\custom_pages\models\ContainerSnippet;

$contentContainer = property_exists(Yii::$app->controller, 'contentContainer') ? Yii::$app->controller->contentContainer : null;

if($contentContainer == null) {
    $indexUrl = Url::to(['index']);
    $deleteUrl =  Url::to(['delete', 'id' => $page->id]);
} else {
     $indexUrl = $contentContainer->createUrl('index');
     $deleteUrl =  $contentContainer->createUrl('delete', ['id' => $page->id]);
}

$contentProp = ($page instanceOf ContainerPage) ? 'page_content' : 'content';
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo Yii::t('CustomPagesModule.base', '<strong>Custom</strong> Pages'); ?>
    </div>

    <?= $subNav ?>

    <div class="panel-body">

        <?php echo Html::a('<i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;&nbsp;' . Yii::t('base', 'Back to overview'), $indexUrl, ['data-ui-loader' => '', 'class' => 'btn btn-default pull-right']); ?>

        <h4><?php echo Yii::t('CustomPagesModule.views_admin_edit', 'Page configuration'); ?></h4>

        <div class="help-block">
            <?= Yii::t('CustomPagesModule.views_admin_edit', 'Here you can configure the general settings of your page.') ?>
        </div>
        <br />
        <?php $form = ActiveForm::begin(); ?>

        <div class="form-group"> 
            <?= Html::textInput('type', Container::getLabel($page->type), ['class' => 'form-control', 'disabled' => '1']); ?>
        </div>

        <?= $form->field($page, 'title') ?>

        <?php if ($page->isType(Container::TYPE_HTML)): ?>
            <?= $form->field($page, $contentProp)->textarea(['id' => 'html_content', 'class' => 'form-control', 'rows' => '15']); ?>
        <?php elseif ($page->isType(Container::TYPE_TEMPLATE)): ?>
            <?= $form->field($page, 'templateId')->dropDownList($page->getAllowedTemplateSelection(), ['disabled' => !$page->isNewRecord]) ?>
        <?php elseif ($page->isType(Container::TYPE_MARKDOWN)): ?>
            <?= $form->field($page, $contentProp)->textarea(['id' => 'markdownField', 'class' => 'form-control', 'rows' => '15']); ?>
            <?= \humhub\widgets\MarkdownEditor::widget(['fieldId' => 'markdownField']); ?>
        <?php elseif ($page->isType(Container::TYPE_LINK) || $page->isType(Container::TYPE_IFRAME)): ?>
            <?= $form->field($page, $contentProp)->textInput(['class' => 'form-control'])->label($page->getAttributeLabel('Url')); ?>
        <?php endif; ?>

        <?php if ($page instanceof Page) : ?> 
            <?= $form->field($page, 'navigation_class')->dropDownList(Page::getNavigationClasses()); ?>
        <?php endif; ?>

        <?php if ($page instanceof Snippet) : ?> 
            <?= $form->field($page, 'sidebar')->dropDownList(Snippet::getSidebarSelection()); ?>
        <?php endif; ?>

        <?= $form->field($page, 'sort_order')->textInput(); ?>

        <?= \humhub\modules\custom_pages\widgets\PageIconSelect::widget(['page' => $page]) ?>

        <?php if (property_exists($page, 'admin_only')) : ?>
            <?= $form->field($page, 'admin_only')->checkbox() ?>
        <?php endif; ?>

        <?php if (property_exists($page, 'in_new_window')) : ?> 
            <?= $form->field($page, 'in_new_window')->checkbox() ?>
        <?php endif; ?>

        <?php echo Html::submitButton(Yii::t('CustomPagesModule.views_admin_edit', 'Save'), ['class' => 'btn btn-primary', 'data-ui-loader' => '']); ?>

        <?php if (!$page->isNewRecord) : ?>
            <?= Html::a(Yii::t('CustomPagesModule.views_admin_edit', 'Delete'), $deleteUrl, ['class' => 'btn btn-danger']); ?>
        <?php endif; ?>

        <?php if ($page->isType(Container::TYPE_TEMPLATE) && !$page->isNewRecord): ?>
            <?php if ($page instanceof Snippet) : ?>
                <?php $url = Url::to(['/custom_pages/snippet/edit-snippet', 'id' => $page->id]); ?>
            <?php elseif ($page instanceof ContainerSnippet) : ?>
                <?php $url = Url::to(['/custom_pages/container-snippet/edit-snippet', 'id' => $page->id]); ?>
            <?php else : ?>
                <?php $url = Url::to(['/custom_pages/view/view', 'id' => $page->id, 'editMode' => 1]); ?>
            <?php endif; ?>
            <?php echo Html::a('<i class="fa fa-pencil"></i> ' . Yii::t('CustomPagesModule.views_admin_edit', 'Inline Editor'), $url, ['class' => 'btn btn-success pull-right', 'data-ui-loader' => '']);
            ?>
        <?php endif; ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>