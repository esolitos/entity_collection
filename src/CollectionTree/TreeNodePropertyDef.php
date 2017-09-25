<?php

namespace Drupal\entity_collection\CollectionTree;


use Drupal\entity_collection\CollectionTree\Exception\InvalidTreeNodePropertyException;

final class TreeNodePropertyDef {

  /** Allows a property of any type (PHP: mixed) */
  const PROP_TYPE_ANY = -1;
  /** Allows a property of integer type */
  const PROP_TYPE_INT = 1;
  /** Allows a property of string type */
  const PROP_TYPE_STR = 2;
  /** Allows a property of map type (PHP: array) */
  const PROP_TYPE_MAP = 3;
  /** Allows a property of object type */
  const PROP_TYPE_OBJ = 4;
  /** Allows a property of a specific class (Ref ::setClassName) */
  const PROP_TYPE_CLASS = 5;

  /** Property can be set only on object creation and can't be altered */
  const PROP_WRITE_RO = 'ro';
  /** Property can be altered any time */
  const PROP_WRITE_RW = 'rw';

  /** @var string Property name */
  private $name;

  /** @var int Property type, one of TreeNodePropertyDef::PROP_TYPE_* constants */
  private $type;

  /** @var string Class name, only useful with self::PROP_TYPE_CLASS */
  private $className;

  /** @var string */
  private $writable;

  /** @var bool */
  private $childInherits;

  /**
   * TreeNodeProperty constructor.
   *
   * @param string $name Property name
   * @param int $type Property type for validation (TreeNodePropertyDef::PROP_TYPE_*)
   * @param string $writable Property ability to be altered after TreeNode creation. (TreeNodePropertyDef::PROP_WRITE_*)
   * @param bool $inheritance Property is inherited by children.
   * @param string $class_name A class name, only valid when $type is self::PROP_TYPE_ANY
   */
  public function __construct($name, $type = self::PROP_TYPE_ANY, $writable = self::PROP_WRITE_RW, $inheritance = FALSE, $class_name = '') {
    $this->name = $name;
    $this->type = $type;
    $this->writable = $writable;
    $this->childInherits = $inheritance;

    if (!empty($class_name) && $type !== self::PROP_TYPE_CLASS) {
      throw new InvalidTreeNodePropertyException("Cannot specify a class name when property type is not TreeNodePropertyDef::PROP_TYPE_CLASS");
    }
    elseif (empty($class_name) && $type === self::PROP_TYPE_CLASS) {
      throw new InvalidTreeNodePropertyException("A Class name is REQUIRED if you set Type to TreeNodePropertyDef::PROP_TYPE_CLASS");
    }
    else {
      $this->className = $class_name;
    }
  }

  /**
   * Name of this property.
   *
   * @return string
   */
  public function getName() {
    return $this->name;
  }

  /**
   * Gets the property type.
   *
   * @return int
   *   Property type, one of TreeNodePropertyDef::PROP_TYPE_* constants.
   */
  public function getType() {
    return $this->type;
  }

  /**
   * Gets the object's class name. Only makes sense with TreeNodePropertyDef::PROP_TYPE_CLASS
   *
   * @return string
   *  A class name or an empty string of
   */
  public function getClassName() {
    return $this->className;
  }

  /**
   * Checks of the property is set to be writable.
   * @return boolean
   */
  public function isWritable() {
    return $this->writable === TreeNodePropertyDef::PROP_WRITE_RW;
  }

  /**
   * Checks of the property is set to be ReadOnly
   * @return bool
   */
  public function isReadOnly() {
    return $this->writable === TreeNodePropertyDef::PROP_WRITE_RO;
  }

  /**
   * @return bool
   *   Property inheritance, true means the property will be inherited by children, false it will not.
   */
  public function getChildInherits() {
    return $this->childInherits;
  }

  public function isValidDataType($value) {
    switch ($this->getType()) {
      case TreeNodePropertyDef::PROP_TYPE_ANY:
        return TRUE;

      case TreeNodePropertyDef::PROP_TYPE_INT:
        return is_integer($value);

      case TreeNodePropertyDef::PROP_TYPE_STR:
        return is_string($value);

      case TreeNodePropertyDef::PROP_TYPE_MAP:
        return is_array($value);

      case TreeNodePropertyDef::PROP_TYPE_OBJ:
        return is_object($value);

      case TreeNodePropertyDef::PROP_TYPE_CLASS:
        return is_object($value) && is_a($value, $this->className);

      default:
        throw new InvalidTreeNodePropertyException("Invalid property type: ".$this->getType());
    }
  }
}