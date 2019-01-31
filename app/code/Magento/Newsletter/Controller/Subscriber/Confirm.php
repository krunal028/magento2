<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\Newsletter\Controller\Subscriber;

class Confirm extends \Magento\Newsletter\Controller\Subscriber
{
    /**
     * Subscription confirm action
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute(): \Magento\Framework\Controller\Result\Redirect
    {
        $id = (int)$this->getRequest()->getParam('id');
        $code = (string)$this->getRequest()->getParam('code');

        if ($id && $code) {
            /** @var \Magento\Newsletter\Model\Subscriber $subscriber */
            $subscriber = $this->_subscriberFactory->create()->load($id);

            if ($subscriber->getId() && $subscriber->getCode()) {
                if ($subscriber->confirm($code)) {
                    $this->messageManager->addSuccess(__('Your subscription has been confirmed.'));
                } else {
                    $this->messageManager->addError(__('This is an invalid subscription confirmation code.'));
                }
            } else {
                $this->messageManager->addError(__('This is an invalid subscription ID.'));
            }
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setUrl($this->_storeManager->getStore()->getBaseUrl());
        return $resultRedirect;
    }
}
