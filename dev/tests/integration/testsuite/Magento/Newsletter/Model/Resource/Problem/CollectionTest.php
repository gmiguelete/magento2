<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Newsletter\Model\Resource\Problem;

use Magento\TestFramework\Helper\Bootstrap;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Newsletter\Model\Resource\Problem\Collection
     */
    protected $_collection;

    protected function setUp()
    {
        $this->_collection = Bootstrap::getObjectManager()
            ->create('Magento\Newsletter\Model\Resource\Problem\Collection');
    }

    /**
     * @magentoDataFixture Magento/Newsletter/_files/problems.php
     */
    public function testAddCustomersData()
    {
        /** @var \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository */
        $customerRepository = Bootstrap::getObjectManager()
            ->create('Magento\Customer\Api\CustomerRepositoryInterface');
        $customer = $customerRepository->getById(1);
        /** @var \Magento\Newsletter\Model\Subscriber $subscriber */
        $subscriber = Bootstrap::getObjectManager()
            ->create('Magento\Newsletter\Model\Subscriber')->loadByEmail($customer->getEmail());
        /** @var \Magento\Newsletter\Model\Problem $problem */
        $problem = Bootstrap::getObjectManager()
            ->create('Magento\Newsletter\Model\Problem')->addSubscriberData($subscriber);

        $item = $this->_collection->addSubscriberInfo()->load()->getFirstItem();

        $this->assertEquals($problem->getProblemErrorCode(), $item->getErrorCode());
        $this->assertEquals($problem->getProblemErrorText(), $item->getErrorText());
        $this->assertEquals($problem->getSubscriberId(), $item->getSubscriberId());
        $this->assertEquals($customer->getEmail(), $item->getSubscriberEmail());
        $this->assertEquals($customer->getFirstname(), $item->getCustomerFirstName());
        $this->assertEquals($customer->getLastname(), $item->getCustomerLastName());
        $this->assertContains($customer->getFirstname(), $item->getCustomerName());
    }
}
