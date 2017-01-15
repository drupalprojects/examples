<?php

namespace Drupal\Tests\file_example\Functional;
use Drupal\Tests\examples\Functional\ExamplesBrowserTestBase;


/**
 * Functional tests for the File Example module.
 *
 * @ingroup file_example
 *
 * @group file_example
 * @group examples
 */
class FileExampleTest extends ExamplesBrowserTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   *
   * @todo Add the stream_wrapper_example when it is completed.
   */
  public static $modules = array('file_example');

  /**
   * Test the basic File Example UI.
   *
   * - Create a directory to work with.
   * - For each scheme create and read files using each of the three methods.
   */
  public function testFileExampleBasic() {

    $assert = $this->assertSession();

    // Our test user needs to access some non-standard file types,
    // so we bless it accordingly.
    $permissions = [
      'use file example',
      'read private files',
      'read temporary files',
      'read session files',
    ];
    $priviledged_user = $this->drupalCreateUser($permissions);
    $this->drupalLogin($priviledged_user);

    $expected_text = array(
      'Write managed file' => 'Saved managed file',
      'Write unmanaged file' => 'Saved file as',
      'Unmanaged using PHP' => 'Saved file as',
    );
    // For each of the three buttons == three write types.
    $buttons = array(
      'Write managed file',
      'Write unmanaged file',
      'Unmanaged using PHP',
    );
    foreach ($buttons as $button) {
      // For each scheme supported by Drupal + the session:// wrapper,
      // which is defined in the stream_wrapper_exampnle.
      $schemes = array('public', 'private', 'temporary', 'session');
      foreach ($schemes as $scheme) {
        // Create a directory for use.
        $dirname = $scheme . '://' . $this->randomMachineName(10);

        // Directory does not yet exist; assert that.
        $edit = array(
          'directory_name' => $dirname,
        );
        $this->drupalPostForm('examples/file_example', $edit, t('Check to see if directory exists'));
        $assert->pageTextContains(t('Directory @dirname does not exist', array('@dirname' => $dirname)));

        $this->drupalPostForm('examples/file_example', $edit, t('Create directory'));
        $assert->pageTextContains(t('Directory @dirname is ready for use', array('@dirname' => $dirname)));

        $this->drupalPostForm('examples/file_example', $edit, t('Check to see if directory exists'));
        $assert->pageTextContains(t('Directory @dirname exists', array('@dirname' => $dirname)));

        // Create a file in the directory we created.
        $content = $this->randomMachineName(30);
        $filename = $dirname . '/' . $this->randomMachineName(30) . '.txt';

        // Assert that the file we're about to create does not yet exist.
        $edit = array(
          'fileops_file' => $filename,
        );
        $this->drupalPostForm('examples/file_example', $edit, t('Check to see if file exists'));
        $assert->pageTextContains(t('The file @filename does not exist', array('@filename' => $filename)));

        $this->verbose((string) t('Processing button=%button, scheme=%scheme, dir=%dirname, file=%filename',
            array(
              '%button' => $button,
              '%scheme' => $scheme,
              '%filename' => $filename,
              '%dirname' => $dirname,
            )
          )
        );
        $edit = array(
          'write_contents' => $content,
          'destination' => $filename,
        );
        $this->drupalPostForm('examples/file_example', $edit, $button);
        $this->verbose($expected_text[$button], "Button Text");
        $assert->pageTextContains($expected_text[$button]);

        // Capture the name of the output file, as it might have changed due
        // to file renaming.
        $element = $this->xpath('//span[@id="uri"]');
        $output_filename = (string) $element[0]->getText();
        $this->verbose($output_filename, 'Name of output file');

        // Click the link provided that is an easy way to get the data for
        // checking and make sure that the data we put in is what we get out.
        if (!in_array($scheme, [])) {
          $this->clickLink(t('this URL'));
          $assert->statusCodeEquals(200);
          // assertText give sketchy answers when the content is *exactly* the
          // contents of the buffer, so let's do something less fragile.
          // $this->assertText($content);
          $buffer = $this->getSession()->getPage()->getContent();
          $this->assertEquals($content, $buffer);
        }

        // Verify that the file exists.
        $edit = array(
          'fileops_file' => $filename,
        );
        $this->drupalPostForm('examples/file_example', $edit, t('Check to see if file exists'));
        $assert->pageTextContains(t('The file @filename exists', array('@filename' => $filename)));

        // Now read the file that got written above and verify that we can use
        // the writing tools.
        $edit = array(
          'fileops_file' => $output_filename,
        );
        $this->drupalPostForm('examples/file_example', $edit, t('Read the file and store it locally'));

        $assert->pageTextContains('The file was read and copied');

        $edit = array(
          'fileops_file' => $filename,
        );

        $this->drupalPostForm('examples/file_example', $edit, t('Delete file'));
        $assert->pageTextContains('Successfully deleted');
        $this->drupalPostForm('examples/file_example', $edit, t('Check to see if file exists'));
        $assert->pageTextContains(t('The file @filename does not exist', array('@filename' => $filename)));

        $edit = array(
          'directory_name' => $dirname,
        );
        $this->drupalPostForm('examples/file_example', $edit, t('Delete directory'));
        $this->drupalPostForm('examples/file_example', $edit, t('Check to see if directory exists'));
        $assert->pageTextContains(t('Directory @dirname does not exist', array('@dirname' => $dirname)));
      }
    }
  }

}
