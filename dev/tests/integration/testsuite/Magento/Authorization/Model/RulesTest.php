<?php
/**
 * @copyright Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 */
namespace Magento\Authorization\Model;

/**
 * @magentoAppArea adminhtml
 */
class RulesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Authorization\Model\Rules
     */
    protected $_model;

    protected function setUp()
    {
        $this->_model = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\Authorization\Model\Rules'
        );
    }

    /**
     * @magentoDbIsolation enabled
     */
    public function testCRUD()
    {
        $this->_model
            ->setRoleType('G')
            ->setResourceId('Magento_Adminhtml::all')
            ->setPrivileges("")
            ->setAssertId(0)
            ->setRoleId(1)
            ->setPermission('allow');

        $crud = new \Magento\TestFramework\Entity($this->_model, ['permission' => 'deny']);
        $crud->testCrud();
    }

    /**
     * @magentoDbIsolation enabled
     */
    public function testInitialUserPermissions()
    {
        $expectedDefaultPermissions = ['Magento_Adminhtml::all'];
        $this->_checkExistingPermissions($expectedDefaultPermissions);
    }

    /**
     * @covers \Magento\Authorization\Model\Rules::saveRel
     * @magentoDbIsolation enabled
     */
    public function testSetAllowForAllResources()
    {
        $resources = ['Magento_Adminhtml::all'];
        $this->_model->setRoleId(1)->setResources($resources)->saveRel();
        $expectedPermissions = ['Magento_Adminhtml::all'];
        $this->_checkExistingPermissions($expectedPermissions);
    }

    /**
     * Ensure that only expected permissions are set.
     */
    protected function _checkExistingPermissions($expectedDefaultPermissions)
    {
        $adapter = $this->_model->getResource()->getReadConnection();
        $ruleSelect = $adapter->select()->from($this->_model->getResource()->getMainTable());

        $rules = $ruleSelect->query()->fetchAll();
        $this->assertEquals(1, count($rules));
        $actualPermissions = [];
        foreach ($rules as $rule) {
            $actualPermissions[] = $rule['resource_id'];
            $this->assertEquals(
                'allow',
                $rule['permission'],
                "Permission for '{$rule['resource_id']}' resource should be 'allow'"
            );
        }
        $this->assertEquals($expectedDefaultPermissions, $actualPermissions, 'Default permissions are invalid');
    }
}
