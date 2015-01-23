<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Customer\Test\Block\Adminhtml\Edit;

use Magento\Backend\Test\Block\Widget\FormTabs;
use Magento\Mtf\Client\Locator;
use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Fixture\InjectableFixture;

/**
 * Form for creation of the customer.
 */
class CustomerForm extends FormTabs
{
    /**
     * Magento form loader.
     *
     * @var string
     */
    protected $loader = '[data-role="spinner"]';

    /**
     * Customer form to load.
     *
     * @var string
     */
    protected $activeFormTab = '.entry-edit.form-inline [data-bind="visible: active"]:not([style="display: none;"])';

    /**
     * Field label on customer form.
     *
     * @var string
     */
    protected  $fieldLabel = './/*[contains(@class, "form__field")]/*[contains(@class,"label")]';

    /**
     * Field with absent label on customer form.
     *
     * @var string
     */
    protected  $fieldLabelAbsent = './/*[contains(@class, "form__field") and not(./*[contains(@class,"label")]/*)]';

    /**
     * Wrapper for field on customer form.
     *
     * @var string
     */
    protected $fieldWrapperControl = './/*[contains(@class, "form__field")]/*[contains(@class,"control")]';

    /**
     * Wrapper with absent field on customer form.
     *
     * @var string
     */
    protected $fieldWrapperControlAbsent = './/*[contains(@class, "form__field") and not(./input or ./*[contains(@class,"control")]/*)]';

    /**
     * Fill Customer forms on tabs by customer, addresses data.
     *
     * @param FixtureInterface $customer
     * @param FixtureInterface|FixtureInterface[]|null $address
     * @return $this
     */
    public function fillCustomer(FixtureInterface $customer, $address = null)
    {
        $isHasData = ($customer instanceof InjectableFixture) ? $customer->hasData() : true;
        $this->waitForm();
        if ($isHasData) {
            parent::fill($customer);
        }
        if (null !== $address) {
            $this->openTab('addresses');
            $this->getTabElement('addresses')->fillAddresses($address);
        }

        return $this;
    }

    /**
     * Update Customer forms on tabs by customer, addresses data.
     *
     * @param FixtureInterface $customer
     * @param FixtureInterface|FixtureInterface[]|null $address
     * @return $this
     */
    public function updateCustomer(FixtureInterface $customer, $address = null)
    {
        $isHasData = ($customer instanceof InjectableFixture) ? $customer->hasData() : true;
        $this->waitForm();
        if ($isHasData) {
            parent::fill($customer);
        }
        if (null !== $address) {
            $this->openTab('addresses');
            $this->getTabElement('addresses')->updateAddresses($address);
        }

        return $this;
    }

    /**
     * Get data of Customer information, addresses on tabs.
     *
     * @param FixtureInterface $customer
     * @param FixtureInterface|FixtureInterface[]|null $address
     * @return array
     */
    public function getDataCustomer(FixtureInterface $customer, $address = null)
    {
        $this->waitForm();

        $data = ['customer' => $customer->hasData() ? parent::getData($customer) : parent::getData()];
        if (null !== $address) {
            $this->openTab('addresses');
            $data['addresses'] = $this->getTabElement('addresses')->getDataAddresses($address);
        }

        return $data;
    }

    /**
     * Wait for User before fill form which calls JS validation on correspondent form.
     * See details in MAGETWO-31435.
     *
     * @return void
     */
    protected function waitForm()
    {
        $this->waitForElementNotVisible($this->loader);
        $this->waitForElementVisible($this->activeFormTab);

        $this->waitForElementVisible($this->fieldLabel, Locator::SELECTOR_XPATH);
        $this->waitForElementNotVisible($this->fieldLabelAbsent, Locator::SELECTOR_XPATH);
        $this->waitForElementVisible($this->fieldWrapperControl, Locator::SELECTOR_XPATH);
        $this->waitForElementNotVisible($this->fieldWrapperControlAbsent, Locator::SELECTOR_XPATH);

        usleep(500000);
    }
}
