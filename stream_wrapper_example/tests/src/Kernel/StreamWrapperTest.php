<?php

namespace Drupal\Tests\stream_wrapper_example\Kernel;

use Drupal\Component\FileCache\FileCacheFactory;
use Drupal\Core\Site\Settings;
use Drupal\KernelTests\KernelTestBase;
use Drupal\Component\Utility\Html;
use Drupal\stream_wrapper_example\StreamWrapper\MockSessionTrait;

/**
 * Test of the Session Stream Wrapper Class.
 *
 * This test covers the PHP-level (i.e., not Drupal-specific) functions of the
 * FileExampleSessionStreamWrapper class. It's not directly loaded here because
 * it loads in background automatically as soon as the stream_wrapper_example
 * module loads.
 *
 * The tests invoke the stream wrapper's functionality indirectly by calling
 * PHP's file functions.
 *
 * @ingroup stream_wrapper_example
 * @group stream_wrapper_example
 * @group examples
 */
class StreamWrapperTest extends KernelTestBase {

  use MockSessionTrait;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['stream_wrapper_example', 'file', 'system'];

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    // @todo Extra hack to avoid test fails, remove this once
    // https://www.drupal.org/node/2553661 is fixed.
    FileCacheFactory::setPrefix(Settings::getApcuPrefix('file_cache', $this->root));
    parent::setUp();
    // Typically if we need our tested class to get information from the system,
    // we use dependency injection (DI) to get that information to the class.
    // But stream wrappers are unusual.  They are created automatically by PHP
    // itself when it calls one of the standard file functions, and for that
    // reason, the constructor functions of stream wrappers cannot be passed any
    // arguments, which prevents us from using the stardard DI technique we use
    // in Drupal 8. The alternative is to create a "global" container that makes
    // our services available to the class, which is what we do here.
    $request_stack = $this->createSessionMock();
    $this->container->set('request_stack', $request_stack);
    $this->container->set('file_system', \Drupal::service('file_system'));
    $this->container->set('kernel', \Drupal::service('kernel'));
    \Drupal::setContainer($this->container);
  }

  /**
   * Test if the session scheme was actually registered.
   */
  public function testSchemeRegistered() {
    $have_session_scheme = $this->container->get('file_system')->validScheme('session');
    $this->assertTrue($have_session_scheme, "System knows about our stream wrapper");
  }

  /**
   * Test functions on a URI.
   */
  public function testReadWrite() {
    $this->resetStore();
    $store = $this->getCurrentStore();

    $uri = 'session://drupal.txt';

    $this->assertFalse(file_exists($uri), "File $uri should not exist yet.");
    $handle = fopen($uri, 'wb');
    $this->assertNotEmpty($handle, "Handle for $uri should be non-empty.");
    $buffer = "Ain't seen nothin' yet!\n";
    $len = strlen($buffer);

    // Original session class gets an error here,
    // "...stream_write wrote 10 bytes more data than requested".
    // Does not matter for our demo, so repress error reporting here.".
    $old = error_reporting(E_ERROR);
    $bytes_written = @fwrite($handle, $buffer);
    error_reporting($old);
    $this->assertNotFalse($bytes_written, "Write to $uri succeeded.");

    $rslt = fclose($handle);
    $this->assertNotFalse($rslt, "Closed $uri.");
    $this->assertTrue(file_exists($uri), "File $uri should now exist.");
    $this->assertFalse(is_dir($uri), "$uri is not a directory.");
    $this->assertTrue(is_file($uri), "$uri is a file.");
    $size = filesize($uri);

    $contents = file_get_contents($uri);
    // The example implementation calls HTML::escape() on output. We reverse it
    // well enough for our sample data (this code is not I18n safe).
    $contents = Html::decodeEntities($contents);
    $this->assertEquals($buffer, $contents, "Data for $uri should make the round trip.");
  }

  /**
   * Directory creation.
   */
  public function testDirectories() {
    $this->resetStore();
    $dir_uri = 'session://directory1/directory2';
    $sample_file = 'file.txt';
    $content = "Wrote this as a file?\n";
    $dir2 = basename($dir_uri);
    $dir1 = dirname($dir_uri);

    $this->assertFalse(file_exists($dir1), "The outer dir $dir1 should not exist yet.");
    // We don't care about mode, since we don't support it.
    $worked = mkdir($dir1);
    $this->assertTrue(is_dir($dir1), "Directory $dir1 was created.");
    $first_file_content = "This one is in the first directory.";
    $uri = $dir1 . "/" . $sample_file;
    $bytes = file_put_contents($uri, $first_file_content);
    $this->assertNotFalse($bytes, "Wrote to $uri.\n");
    $this->assertTrue(file_exists($uri), "File $uri actually exists.");
    $got_back = file_get_contents($uri);
    $got_back = Html::decodeEntities($got_back);
    $this->assertSame($first_file_content, $got_back, "Data in subdir made round trip.");

    // Now try down down nested.
    $rslt = mkdir($dir_uri);
    $this->assertTrue($rslt, "Nested dir got created.");
    $file_in_sub = $dir_uri . "/" . $sample_file;
    $bytes = file_put_contents($file_in_sub, $content);
    $this->assertNotFalse($bytes, "File in nested dirs got written to.");
    $got_back = file_get_contents($file_in_sub);
    $got_back = Html::decodeEntities($got_back);
    $this->assertSame($content, $got_back, "Data in subdir made round trip.");
    $worked = unlink($file_in_sub);
    $this->assertTrue($worked, "Deleted file in subdir.");
    $this->assertFalse(file_exists($file_in_sub), "File in subdir should not exist.");
  }

  /**
   * Get the contents of the complete array stored in the session.
   */
  protected function getCurrentStore() {
    $handle = $this->getSessionWrapper();
    return $handle->getPath('');
  }

  /**
   * Clear the session storage area.
   */
  protected function resetStore() {
    $handle = $this->getSessionWrapper();
    $handle->cleanUpStore();
  }

}
