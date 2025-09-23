<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Controller\Adminhtml\Form\Element\ProductConditions;

use Magento\Backend\App\Action\Context;
use Magento\CatalogWidget\Controller\Adminhtml\Product\Widget;
use Magento\CatalogWidget\Model\Rule;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Rule\Model\Condition\AbstractCondition;

class Child extends Widget implements HttpPostActionInterface
{
    public function __construct(
        private Rule $rule,
        Context $context,
    ) {
        $this->rule = $rule;
        parent::__construct($context);
    }

    /**
     * Render child template
     *
     * @return void
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $formName = $this->getRequest()->getParam('form_namespace');
        $jsObjectName = $this->getRequest()->getParam('js_object_name');

        $typeData = explode('|', str_replace('-', '/', $this->getRequest()->getParam('type')));
        $className = $typeData[0];
        $prefix = $this->getRequest()->getParam('prefix', 'conditions');

        $model = $this->_objectManager->create($className)
            ->setId($id)
            ->setType($className)
            ->setRule($this->rule)
            ->setPrefix($prefix);

        if (!empty($typeData[1])) {
            $model->setAttribute($typeData[1]);
        }

        $result = '';
        if ($model instanceof AbstractCondition) {
            // set value of $prefix in model's data registry to value of 'conditions',
            // as is required for correct use of \Magento\Rule\Model\Condition\Combine::getConditions
            if ($model->getData($prefix) === null) {
                $model->setData($prefix, $model->getData('conditions'));
            }
            $model->setJsFormObject($jsObjectName);
            $model->setFormName($formName);
            $result = $model->asHtmlRecursive();
        }
        $this->getResponse()->setBody($result);
    }
}
