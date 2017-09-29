<?php

namespace Drupal\entity_collection\CollectionTree;

use Drupal\Core\Entity\EntityInterface;

interface TreeNodeInterface extends \Countable, \IteratorAggregate {

  public static function createChild(TreeNodeInterface $parent, EntityInterface $entity);

  /**
   * @param TreeNodePropertyDef[] $propertyDef
   *   Array containing multiple TreeNodeProperty.
   */
  public function defineProperties(array $propertyDef);

  /**
   * Sets the definition of a single property.
   *
   * @param \Drupal\entity_collection\CollectionTree\TreeNodePropertyDef $propertyDef
   */
  public function defineProperty(TreeNodePropertyDef $propertyDef);

  /**
   * Sets a
   * @param array $properties
   * @throws \Drupal\entity_collection\CollectionTree\Exception\InvalidTreeNodePropertyException
   */
  public function setProperties(array $properties);

  /**
   * Sets a property value for the current TreeNode.
   *
   * @param string $name Name of a priory defined property.
   * @param mixed $value Value of the property, myst comply with prior definition.
   *
   * @throws \Drupal\entity_collection\CollectionTree\Exception\InvalidTreeNodePropertyException
   *
   * @see \Drupal\entity_collection\CollectionTree\TreeNodePropertyDef
   * @see \Drupal\entity_collection\CollectionTree\TreeNodeInterface::defineProperty()
   * @see \Drupal\entity_collection\CollectionTree\TreeNodeInterface::getProperty()
   */
  public function setProperty($name, $value);

  /**
   * Gets the value of a property. This
   *
   * @param $name
   *
   * @return null|mixed
   *  **Important Note** a NULL value means that the property has not been set,
   *  or the value is actually NULL (only if type TreeNodePropertyDef::PROP_TYPE_ANY
   *  However if a property (name) is not yet defined an exception will be thrown!
   *
   * @throws \Drupal\entity_collection\CollectionTree\Exception\InvalidTreeNodePropertyException
   *   When trying to access an undefined property
   */
  public function getProperty($name);

  /**
   * Checks if the current node is the tree root.
   *
   * @return bool
   */
  public function isRoot();

  /**
   * Gets the root node of the tree.
   *
   * This could also be the same object, if called on the root node itself.
   * A bit like running `cd ..` while on the root of a filesystem.
   *
   * @return \Drupal\entity_collection\CollectionTree\TreeNodeInterface
   */
  public function getRoot();

  /**
   * Appends an item to the children's queue.
   *
   * @param \Drupal\entity_collection\CollectionTree\TreeNodeInterface $node
   * @param array $properties The child's properties for the given entity
   */
  public function appendChild(TreeNodeInterface $node, array $properties = []);

  /**
   * @return EntityInterface
   */
  public function entity();
}
