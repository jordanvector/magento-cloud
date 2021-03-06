<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Test\Integrity\Magento\GiftRegistry;

/**
 * Test for validation check of the giftregistry.xml and xsd for this file
 */
class ConfigFileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Schema for gift registry
     *
     * @var string
     */
    protected $_schemaFile;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * Set up schema file
     */
    protected function setUp()
    {
        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $this->_schemaFile = $this->objectManager->get('Magento\GiftRegistry\Model\Config\SchemaLocator')->getSchema();
    }

    /**
     * Validation test for xml and xsd files
     * Can fail if the libXML errors exist
     *
     * @param string $file
     * @dataProvider giftRegistryConfigFilesDataProvider
     */
    public function testGiftRegistryConfigValidation($file)
    {
        $validationState = $this->objectManager->get(
            'Magento\Framework\App\Arguments\ValidationState',
            ['appMode' => 'developer']
        );
        $errors = [];
        $dom = new \Magento\Framework\Config\Dom(file_get_contents($file), $validationState);
        $result = $dom->validate($this->_schemaFile, $errors);
        $message = "Invalid XML-file: {$file}\n";
        foreach ($errors as $error) {
            $message .= "{$error->message} Line: {$error->line}\n";
        }
        $this->assertTrue($result, $message);
    }

    /**
     * Data provider for testGiftRegistryConfigValidation
     *
     * @return array
     */
    public function giftRegistryConfigFilesDataProvider()
    {
        return \Magento\Framework\App\Utility\Files::init()->getConfigFiles('giftregistry.xml');
    }
}
