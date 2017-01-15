<?php

namespace Drupal\Tests\tablesort_example\Functional;

use Drupal\Tests\examples\Functional\ExamplesBrowserTestBase;

/**
 * Verify the tablesort functionality.
 *
 * @group tablesort_example
 * @group examples
 *
 * @ingroup tablesort_example
 */
class TableSortExampleTest extends ExamplesBrowserTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = array('tablesort_example');

  /**
   * The installation profile to use with this test.
   *
   * We need the 'minimal' profile in order to make sure the Tool block is
   * available.
   *
   * @var string
   */
  protected $profile = 'minimal';

  /**
   * Verify the functionality of the sortable table.
   */
  public function testTableSortExampleBasic() {
    $assert = $this->assertSession();

    // No need to login for this test.
    $this->drupalGet('/examples/tablesort-example', array('query' => array('sort' => 'desc', 'order' => 'Numbers')));
    $assert->statusCodeEquals(200);
    // Ordered by number decending.
    $item = $this->getSession()->getPage()->find('xpath', '//tbody/tr/td[1]');
    $this->assertEquals(7, $item->getText(), 'Ordered by number decending.');
    drupal_flush_all_caches();

    $this->drupalGet('/examples/tablesort-example', array('query' => array('sort' => 'asc', 'order' => 'Numbers')));
    $assert->statusCodeEquals(200);
    // Ordered by Number ascending.
    $item = $this->getSession()->getPage()->find('xpath', '//tbody/tr/td[1]');
    $this->assertEquals(1, $item->getText(), 'Ordered by Number ascending.');
    drupal_flush_all_caches();

    // Sort by Letters.
    $this->drupalGet('/examples/tablesort-example', array('query' => array('sort' => 'desc', 'order' => 'Letters')));
    $assert->statusCodeEquals(200);
    // Ordered by Letters decending.
    $item = $this->getSession()->getPage()->find('xpath', '//tbody/tr/td[2]');
    $this->assertEquals('w', $item->getText(), 'Ordered by Letters decending.');
    drupal_flush_all_caches();

    $this->drupalGet('/examples/tablesort-example', array('query' => array('sort' => 'asc', 'order' => 'Letters')));
    $assert->statusCodeEquals(200);
    // Ordered by Letters ascending.
    $item = $this->getSession()->getPage()->find('xpath', '//tbody/tr/td[2]');
    $this->assertEquals('a', $item->getText(), 'Ordered by Letters ascending.');
    drupal_flush_all_caches();

    // Sort by Mixture.
    $this->drupalGet('/examples/tablesort-example', array('query' => array('sort' => 'desc', 'order' => 'Mixture')));
    $assert->statusCodeEquals(200);
    // Ordered by Mixture decending.
    $item = $this->getSession()->getPage()->find('xpath', '//tbody/tr/td[3]');
    $this->assertEquals('t982hkv', $item->getText(), 'Ordered by Mixture decending.');
    drupal_flush_all_caches();

    $this->drupalGet('/examples/tablesort-example', array('query' => array('sort' => 'asc', 'order' => 'Mixture')));
    $assert->statusCodeEquals(200);
    // Ordered by Mixture ascending.
    $item = $this->getSession()->getPage()->find('xpath', '//tbody/tr/td[3]');
    $this->assertEquals('0kuykuh', $item->getText(), 'Ordered by Mixture ascending.');
    drupal_flush_all_caches();

  }

  /**
   * Data provider for testing menu links.
   *
   * @return array
   *   Array of page -> link relationships to check for.
   *   The key is the path to the page where our link should appear.
   *   The value is the link that should appear on that page.
   */
  protected function providerMenuLinks() {
    return array(
      '' => '/examples/tablesort-example',
    );
  }

  /**
   * Verify and validate that default menu links were loaded for this module.
   */
  public function testTableSortExampleLink() {
    $assert = $this->assertSession();

    $links = $this->providerMenuLinks();
    foreach ($links as $page => $path) {
      $this->drupalGet($page);
      $assert->linkByHrefExists($path);
    }
  }

  /**
   * Tests tablesort_example menus.
   */
  public function testTableSortExampleMenu() {
    $assert = $this->assertSession();

    $this->drupalGet('/examples/tablesort-example');
    $assert->statusCodeEquals(200);
  }

}
