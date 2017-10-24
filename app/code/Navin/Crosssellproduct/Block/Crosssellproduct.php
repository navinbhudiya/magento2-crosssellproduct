<?php

namespace Navin\Crosssellproduct\Block;

class Crosssellproduct extends \Magento\Catalog\Block\Product\AbstractProduct implements \Magento\Framework\DataObject\IdentityInterface {

    protected $_itemCollection;

    public function __construct(
    \Magento\Catalog\Block\Product\Context $context, array $data = []
    ) {
        $this->_registry = $context->getRegistry();
        parent::__construct(
                $context, $data
        );
    }

    protected function _prepareData() {
        $product = $this->_registry->registry('current_product');
        $this->_itemCollection = $product->getCrossSellProductCollection()->addAttributeToSelect(
                        $this->_catalogConfig->getProductAttributes()
                )->setPositionOrder()->addStoreFilter();
        $this->_itemCollection->load();
        foreach ($this->_itemCollection as $product) {
            $product->setDoNotUseCategoryId(true);
        }
        return $this;
    }

    protected function _beforeToHtml() {
        $this->_prepareData();
        return parent::_beforeToHtml();
    }

    public function getItems() {
        return $this->_itemCollection;
    }

    public function getItemCount() {
        return count($this->getItems());
    }

    public function getIdentities() {
        $identities = [];
        foreach ($this->getItems() as $item) {
            $identities = array_merge($identities, $item->getIdentities());
        }
        return $identities;
    }

}
