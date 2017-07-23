<?php

namespace Drupal\entity_collection\CollectionTree;

use Drupal\Console\Command\Shared\TranslationTrait;
use Drupal\Core\Entity\EntityInterface;
use Drupal\entity_collection\CollectionTree\Exception\InvalidTreeNodePropertyException;

class TreeNode implements TreeNodeInterface {

  use TranslationTrait;

  /**
   * Children queue
   *
   * @var \SplMinHeap
   */
  protected $innerQueue;

  /**
   *
   * @var \Drupal\Core\Entity\EntityInterface
   */
  protected $entity;

  /**
   *
   * @var array
   */
  protected $properties;

  /**
   * Contains the list of allowed property and the definition of each.
   * @var TreeNodePropertyDef[]
   */
  protected $propertiesDefinition;

  /**
   * @inheritDoc
   */
  public function __construct(EntityInterface $entity = NULL, array $properties = []) {
    $this->innerQueue = new \SplMinHeap();
    $this->entity = $entity;

    $this->setProperties($properties);
  }

  /**
   * @inheritDoc
   */
  public function count() {
    return count($this->innerQueue);
  }

  /**
   * @inheritDoc
   */
  public function getIterator() {
    return clone $this->innerQueue;
  }

  /**
   * @inheritDoc
   */
  public function defineProperties(array $propertyDef) {
    foreach ($propertyDef as $item) {
      $this->defineProperty($item);
    }
  }

  /**
   * @inheritDoc
   */
  public function defineProperty(TreeNodePropertyDef $propertyDef) {
    $this->propertiesDefinition[$propertyDef->getName()] = $propertyDef;
  }


  /**
   * @inheritDoc
   */
  public function setProperty($name, $value) {
    if (!$this->isValidProperty($name, $value)) {
      throw new InvalidTreeNodePropertyException("Attempting to set a property {$name}, but types do not match");
    }
    if (!$this->isWritableProperty($name)) {
      throw new InvalidTreeNodePropertyException("Attempting to change {$name}, but that is a read-only property");
    }

    $this->properties[$name] = $value;
  }

  /**
   * @inheritDoc
   */
  public function setProperties(array $properties) {
    foreach ($properties as $name => $value) {
      $this->setProperty($name, $value);
    }
  }

  /**
   * @inheritDoc
   */
  public function getProperty($name) {
    if (!isset($this->propertiesDefinition[$name])) {
      throw new InvalidTreeNodePropertyException("Requested property is not defined!");
    }

    /*
     * Note that `isset()` works best than `empty()` in this occasion, because
     * (if it is set) we don't want to alter the data type.
     */
    return isset($this->properties[$name]) ? $this->properties[$name] : NULL;
  }


  /**
   * Checks if the set of property name and value is valid for the current tree node.
   *
   * @param string $name The property identifier
   * @param mixed $value The property value.
   *
   * @return bool
   *
   * @throws \Drupal\entity_collection\CollectionTree\Exception\InvalidTreeNodePropertyException
   *   If attempting to check a property that is not defined or the defined
   *   property type is not known.
   */
  private function isValidProperty($name, $value) {
    if (!isset($this->propertiesDefinition[$name])) {
      throw new InvalidTreeNodePropertyException("Property {$name} not defined.");
    }
    else {
      return $this->propertiesDefinition[$name]->isValidDataType($value);
    }
  }

  /**
   * Checks of the given property is writable.
   *
   * Note: if property is not set will always return FALSE
   *
   * @param string $name Property name
   *
   * @return bool
   */
  private function isWritableProperty($name) {
    if (isset($this->propertiesDefinition[$name])) {
      return $this->propertiesDefinition[$name]->isWritable();
    }

    return FALSE;
  }


}
