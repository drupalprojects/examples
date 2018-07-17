<?php

namespace Drupal\Tests\file_example\Functional;

use Drupal\Component\Render\FormattableMarkup;
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
   */
  public static $modules = ['file_example'];

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

    $expected_text = [
      'Write managed file' => 'Saved managed file',
      'Write unmanaged file' => 'Saved file as',
      'Unmanaged using PHP' => 'Saved file as',
    ];
    // For each of the three buttons == three write types.
    $buttons = [
      'Write managed file',
      'Write unmanaged file',
      'Unmanaged using PHP',
    ];
    foreach ($buttons as $button) {
      // For each scheme supported by Drupal + the session:// wrapper,
      // which is defined in the stream_wrapper_exampnle.
      $schemes = ['public', 'private', 'temporary', 'session'];
      foreach ($schemes as $scheme) {
        // Create a directory for use.
        $dirname = $scheme . '://' . $this->randomMachineName(10);

        // Directory does not yet exist; assert that.
        $edit = [
          'directory_name' => $dirname,
        ];
        $this->drupalPostForm('examples/file_example', $edit, 'Check to see if directory exists');
        $assert->pageTextContains((string) new FormattableMarkup('Directory @dirname does not exist', ['@dirname' => $dirname]));

        $this->drupalPostForm('examples/file_example', $edit, 'Create directory');
        $assert->pageTextContains((string) new FormattableMarkup('Directory @dirname is ready for use', ['@dirname' => $dirname]));

        $this->drupalPostForm('examples/file_example', $edit, 'Check to see if directory exists');
        $assert->pageTextContains((string) new FormattableMarkup('Directory @dirname exists', ['@dirname' => $dirname]));

        // Create a file in the directory we created.
        $content = $this->randomMachineName(30);
        $filename = $dirname . '/' . $this->randomMachineName(30) . '.txt';

        // Assert that the file we're about to create does not yet exist.
        $edit = [
          'fileops_file' => $filename,
        ];
        $this->drupalPostForm('examples/file_example', $edit, 'Check to see if file exists');
        $assert->pageTextContains((string) new FormattableMarkup('The file @filename does not exist', ['@filename' => $filename]));

        $this->verbose("Processing button=$button, scheme=$scheme, dir=$dirname, file=$filename");
        $edit = [
          'write_contents' => $content,
          'destination' => $filename,
        ];
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
          $this->clickLink('this URL');
          $assert->statusCodeEquals(200);
          // assertText give sketchy answers when the content is *exactly* the
          // contents of the buffer, so let's do something less fragile.
          // $this->assertText($content);
          $buffer = $this->getSession()->getPage()->getContent();
          $this->assertEquals($content, $buffer);
        }

        // Verify that the file exists.
        $edit = [
          'fileops_file' => $filename,
        ];
        $this->drupalPostForm('examples/file_example', $edit, 'Check to see if file exists');
        $assert->pageTextContains("The file $filename exists");

        // Now read the file that got written above and verify that we can use
        // the writing tools.
        $edit = [
          'fileops_file' => $output_filename,
        ];
        $this->drupalPostForm('examples/file_example', $edit, 'Read the file and store it locally');

        $assert->pageTextContains('The file was read and copied');

        $edit = [
          'fileops_file' => $filename,
        ];

        $this->drupalPostForm('examples/file_example', $edit, 'Delete file');
        $assert->pageTextContains('Successfully deleted');
        $this->drupalPostForm('examples/file_example', $edit, 'Check to see if file exists');
        $assert->pageTextContains((string) new FormattableMarkup('The file @filename does not exist', ['@filename' => $filename]));

        $edit = [
          'directory_name' => $dirname,
        ];
        $this->drupalPostForm('examples/file_example', $edit, 'Delete directory');
        $this->drupalPostForm('examples/file_example', $edit, 'Check to see if directory exists');
        $assert->pageTextContains((string) new FormattableMarkup('Directory @dirname does not exist', ['@dirname' => $dirname]));
      }
    }
  }

}
