<?php

namespace Drupal\Tests\entity_collection\Unit;

use Drupal\entity_collection\CollectionTree\Exception\InvalidTreeNodePropertyException;
use Drupal\entity_collection\CollectionTree\TreeNodePropertyDef as Property;
use Drupal\Tests\UnitTestCase;
use Drupal\entity_collection\CollectionTree\TreeNode;

/**
 * Tests EntityCollection functionality.
 *
 *
 * @coversDefaultClass \Drupal\entity_collection\CollectionTree\TreeNode
 * @group EntityCollectionEntityTest
 */
class TreeNodeTest extends UnitTestCase {

  private $entityMockBuilder;
  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->entityMockBuilder = $this->getMockBuilder('\Drupal\Core\Entity\ContentEntityBase')
      ->disableOriginalConstructor();
  }

  /**
   * Simply tests some basic involved in a Entity Collection creation
   *
   * @covers ::__construct
   */
  public function test create an empty TreeNode() {
    $treeNode = new TreeNode();

    $this->assertNotNull($treeNode, 'The tree is created.');
    $this->assertEquals(0, count($treeNode), 'New tree is countable and contain zero items.');

    $this->assertTrue($treeNode->isRoot(), 'A new TreeNode should always be root');
    $this->assertSame($treeNode, $treeNode->getRoot(), 'TreeNode::getRoot() on the root element should return itself');

    return $treeNode;
  }

  /**
   *
   * @depends      test create an empty TreeNode
   * @dataProvider treeNodeSimplePropertiesProvider
   *
   * @covers ::defineProperty
   * @covers ::getProperty
   * @covers ::setProperty
   *
   * @param \Drupal\entity_collection\CollectionTree\TreeNodePropertyDef $property
   * @param mixed $testData Data to be tested for a property
   * @param bool $isValidData Tells if the passed data is valid or not (hence: expect exceptions or not)
   * @param \Drupal\entity_collection\CollectionTree\TreeNode $tree Just a test tree
   */
  public function test define new simple tree property(Property $property, $testData, $isValidData, TreeNode $tree) {
    $test_tree = clone $tree;
    $prop_name = $property->getName();

    // Define property
    $test_tree->defineProperty($property);

    // Expect exception if the provided data is not to be considered valid
    if (!$isValidData) {
      $this->setExpectedException(InvalidTreeNodePropertyException::class);
    }

    // Set the a value for the just defined property
    $test_tree->setProperty($prop_name, $testData);

    // Attempt retrieving the data back
    $actual = $test_tree->getProperty($prop_name);
    $expected = ($isValidData) ? $testData : NULL;
    $this->assertEquals($actual, $expected, "Property $prop_name expected to be of type " . gettype($testData) . ", but received: " . gettype($expected));
  }

  /* ----------------------------------------------
   * --------------- DATA PROVIDERS ---------------
   * ---------------------------------------------- */

  public function treeNodeSimplePropertiesProvider() {
    /*
     * Each item in the array has this structure:
     * - TreeNodePropertyDef: new pre-configured instance
     * - mixed: Data to test against
     * - bool: verifies if this "is valid data" ot nor for the configured Property
     */
    return [
      [
        new Property('any-type--string'),
        'some data',
        TRUE
      ],
      [
        new Property('any-type--number'),
        1234,
        TRUE
      ],
      [
        new Property('any-type--object'),
        (object) ['prop' => 'value'],
        TRUE
      ],
      [
        new Property('integer', Property::PROP_TYPE_INT),
        123,
        TRUE
      ],
      [
        new Property('integer-hex', Property::PROP_TYPE_INT),
        0x123,
        TRUE
      ],
      [
        new Property('not-integer', Property::PROP_TYPE_INT),
        'a123',
        FALSE
      ],
      [
        new Property('string', Property::PROP_TYPE_STR),
        'Lorem Ipsum',
        TRUE
      ],
      [
        new Property('not-string-hex', Property::PROP_TYPE_STR),
        0x123,
        FALSE
      ],
      [
        new Property('array', Property::PROP_TYPE_MAP),
        ['a', 'b', 'c' => 'c1'],
        TRUE,
      ],
      [
        new Property('not-array-str', Property::PROP_TYPE_MAP),
        "['a', 'b', 'c'=>'c1']",
        FALSE,
      ],
      [
        new Property('object', Property::PROP_TYPE_OBJ),
        new \stdClass(),
        TRUE
      ],
      [
        new Property('not-object-array', Property::PROP_TYPE_OBJ),
        ['foo'],
        FALSE,
      ],
      [
        new Property('object-class', Property::PROP_TYPE_CLASS, Property::PROP_WRITE_RW, FALSE, \ArrayObject::class),
        new \ArrayObject(),
        TRUE,
      ],
      [
        new Property('object-wrong-class', Property::PROP_TYPE_CLASS, Property::PROP_WRITE_RW, FALSE, \RecursiveArrayIterator::class),
        new \ArrayObject(),
        FALSE,
      ],
      [
        new Property('not-object-class-string', Property::PROP_TYPE_CLASS, Property::PROP_WRITE_RW, FALSE, \RecursiveArrayIterator::class),
        'Lorem Ipsum',
        FALSE,
      ],
    ];
  }
}
