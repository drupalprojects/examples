<?php

namespace Drupal\Tests\stream_wrapper_example\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\stream_wrapper_example\StreamWrapper\SessionWrapper;
use Drupal\stream_wrapper_example\StreamWrapper\MockSessionTrait;

/**
 * PHPUnit test for the SessionWrapper session manipulation class.
 *
 * The SessionWrapper class is a utility used to manipulate an associative
 * array stored in the session object as if it were a file system.  This
 * greatly simplifies the code in our stream wrapper class, since
 * SessionWrapper handles things like interacting with the session object,
 * and also deals with translating path strings into nested arrays.
 *
 * The test class covers the equivalent of adding directories and files,
 * reading and writing data nodes (our "files"), and clearing of arrays
 * and data nodes (file deletion for purposes of the stream wrapper class).
 *
 * @ingroup stream_wrapper_example
 * @group stream_wrapper_example
 * @group examples
 */
class SessionWrapperTest extends UnitTestCase {

  use MockSessionTrait;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    // Mock the session service.
    $this->createSessionMock();

    // Set up the example.
    $helper = new SessionWrapper($this->requestStack);
    $helper->setUpStore();
  }

  /**
   * Run our wrapper through the paces.
   */
  public function testWrapper() {
    // Check out root.
    $helper = new SessionWrapper($this->requestStack);
    $root = $helper->getPath('');
    $this->assertTrue(is_array($root), "The root is an array");
    $this->assertTrue(empty($root), "The root is empty.");

    // Add a top level file.
    $helper = new SessionWrapper($this->requestStack);
    $helper->setPath('drupal.txt', "Stuff");
    $text = $helper->getPath('drupal.txt');
    $this->assertEquals($text, "Stuff", "File at base of hierarchy can be read.");

    // Add a "directory".
    $helper = new SessionWrapper($this->requestStack);
    $dir = [
      'file.txt' => 'More stuff',
    ];
    $helper->setPath('directory1', $dir);
    $fetched_dir = $helper->getPath('directory1');
    $this->assertEquals($fetched_dir['file.txt'], "More stuff", "File inside of directory can be read.");

    // Check file existance.
    $helper = new SessionWrapper($this->requestStack);
    $this->assertTrue($helper->checkPath('drupal.txt'), "File at root still exists.");
    $this->assertFalse($helper->checkPath('file.txt'), "Non-existant file at root does not exist.");
    $this->assertTrue($helper->checkPath('directory1'), "Directory at root still exists.");
    $this->assertTrue($helper->checkPath('directory1/file.txt'), "File in directory at root still exists.");

    // Two deep.
    $helper = new SessionWrapper($this->requestStack);
    $helper->setPath('directory1/directory2', []);
    $helper->setPath('directory1/directory2/junk.txt', "Store some junk");
    $text = $helper->getPath('directory1/directory2/junk.txt');
    $this->assertEquals($text, "Store some junk", "File inside of nested directory can be read.");

    // Clear references.
    $helper = new SessionWrapper($this->requestStack);
    $before = $helper->checkPath('directory1/directory2/junk.txt');
    $this->assertTrue($before, "File 2 deep exists.");
    $helper->clearPath('directory1/directory2/junk.txt');
    $after = $helper->checkPath('directory1/directory2/junk.txt');
    $this->assertFalse($after, "File 2 deep should be gone.");

    // Clean up test.
    $helper = new SessionWrapper($this->requestStack);
    $store = $helper->getPath('');
    $this->assertNotEmpty($store, "Before cleanup store is not empty.");
    $helper->cleanUpStore();
    $store = $helper->getPath('');
    $this->assertEmpty($store, "After cleanup store is empty.");

  }

}
