<?php
namespace App\Model;

use ArrayAccess;
use ArrayIterator;
use Countable;
use Nette\SmartObject;
use ReflectionClass;

/**
 * Description of BaseEntity
 * @author Tomas Grasl <grasl.t@centrum.cz>
 */
abstract class BaseEntity implements Countable, ArrayAccess  
{
    use SmartObject;
    
    /**
    * Access to reflection.
    * @return ReflectionClass
    */
    public static function getReflection() 
    {
        $class = class_exists('Nette\Reflection\ClassType') ? 'Nette\Reflection\ClassType' : 'ReflectionClass';
        return new $class(get_called_class());
    }    
    
/* 
 * interfaces ArrayAccess, Countable & IteratorAggregate
 * ====================================================================tg=======
 */
   /**
    * @return array
    */
    public function toArray() 
    {
        return (array) $this;
    }
   /**
    * @return array
    */
    public function toArraySets() 
    {
        $return = [];
        foreach ($this->getReflection()->getProperties() as $value) {
            if($this->{$value->name}) {
                $return[$value->name] = $this->{$value->name};
            }
        }
        return $return;
    }
    
   /**
    * @return integer
    */
    final public function count() 
    {
        return count((array) $this);
    }

   /**
    * @return ArrayIterator    */
    final public function getIterator() 
    {
        return new ArrayIterator($this);
    }

   /**
    * @param integer | string $nm
    * @param string $val
    */
    final public function offsetSet($nm, $val) 
    {
        $this->$nm = $val;
    }

   /**
    * @param type $nm
    * @return type
    */
    final public function offsetGet($nm) 
    {
        return $this->$nm;
    }

    /**
     * @param type $nm
     * @return type
     */
    final public function offsetExists($nm) 
    {
        return isset($this->$nm);
    }

    /**
     * @param string $nm
     * @return void
     */
    final public function offsetUnset($nm) 
    {
        unset($this->$nm);
    }     
}
