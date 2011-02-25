<?php

/**
 * @file
 * Test case for the xmlrpc example module.
 *
 * This file contains the test cases to check if module is performing as
 * expected.
 *
 */
class XmlrpcExampleTestCase extends DrupalWebTestCase {
  protected $xmlrpc_url;

  public static function getInfo() {
    return array(
      'name' => 'XMLRPC example functionality',
      'description' => 'Test xmlrpc service implementation.',
      'group' => 'Examples',
    );
  }

  /**
   * Enable module.
   */
  function setUp() {
    parent::setUp('xmlrpc_example');

    // Init common variables.
    global $base_url;
    $this->xmlrpc_url = url($base_url . '/xmlrpc.php', array('external' => TRUE));
  }

  /**
   * Perform several calls the xmlrpc inteface to test the services.
   */
  function testXmlrpcExampleBasic() {
    // Unit test functionality.
    $result = xmlrpc($this->xmlrpc_url, 'xmlrpc_example.add', 3, 4);
    $this->assertEqual($result, 7, t('Successfully added 3+4 = 7'));

    $result = xmlrpc($this->xmlrpc_url, 'xmlrpc_example.subtract', 4, 3);
    $this->assertEqual($result, 1, t('Successfully subtracted 4-3 = 1'));

    // Verify default limits
    $result = xmlrpc($this->xmlrpc_url, 'xmlrpc_example.subtract', 3, 4);
    $this->assertEqual(xmlrpc_errno(), 10002, t('Results below minimum return error: 10002'));

    $result = xmlrpc($this->xmlrpc_url, 'xmlrpc_example.add', 7, 4);
    $this->assertEqual(xmlrpc_errno(), 10001, t('Results beyond maximum return error: 10001'));
  }


  /**
   * Perform several calls using xmlrpc UI client
   */
  function testXmlrpcExampleClient() {
    // Now test the UI.
    $edit = array('num1' => 3, 'num2' => 5);
    $this->drupalPost('examples/xmlrpc_client', $edit, t('Add the integers'));
    $this->assertText(t("The XMLRPC server returned this response: @num", array('@num' => 8)));

    $edit = array('num1' => 8, 'num2' => 3);
    $this->drupalPost('examples/xmlrpc_client', $edit, t('Subtract the integers'));
    $this->assertText(t("The XMLRPC server returned this response: @num", array('@num' => 5)));
  }

  /**
   * Perform several xmlrpc requests changing server settings
   */
  function testXmlrpcExampleServer() {
    // Now test the UI.
    $options = array('xmlrpc_example_server_min' => 3, 'xmlrpc_example_server_max' => 7);
    $this->drupalPost('examples/xmlrpc_server', $options, t('Save configuration'));
    $this->assertText(t("The configuration options have been saved"), t("Results limited to >= 3 and <= 7"));

    $edit = array('num1' => 8, 'num2' => 3);
    $this->drupalPost('examples/xmlrpc_client', $edit, t('Subtract the integers'));
    $this->assertText(t("The XMLRPC server returned this response: @num", array('@num' => 5)));

    $result = xmlrpc($this->xmlrpc_url, 'xmlrpc_example.add', 3, 4);
    $this->assertEqual($result, 7, t('Successfully added 3+4 = 7'));

    $result = xmlrpc($this->xmlrpc_url, 'xmlrpc_example.subtract', 4, 3);
    $this->assertEqual(xmlrpc_errno(), 10002, t('subtracting 4-3 = 1 returns: 10002'));

    $result = xmlrpc($this->xmlrpc_url, 'xmlrpc_example.add', 7, 4);
    $this->assertEqual(xmlrpc_errno(), 10001, t('Adding 7 + 4 = 11 returns: 10001'));
  }
}
