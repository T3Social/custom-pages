<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\custom_pages\widgets;

use Yii;
use humhub\modules\custom_pages\components\Container;

/**
 * Snippet
 *
 * @author buddha
 */
class SnippetWidget extends \yii\base\Widget
{

    public $model;
    
    public function run()
    {
        $contentContainer = property_exists(Yii::$app->controller, 'contentContainer') ? Yii::$app->controller->contentContainer : null;
        
        return $this->render('snippet_'.strtolower(Container::getLabel($this->model->type)), [
            'model' => $this->model,
            'contentContainer' => $contentContainer]);
    }

}
