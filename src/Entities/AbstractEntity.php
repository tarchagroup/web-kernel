<?php

namespace Tarcha\WebKernel\Entities;

use \JsonSerializable;
use \ReflectionClass;
use Slug\Slugifier;

abstract class AbstractEntity implements JsonSerializable
{
    /**
     * Remember if any properties have been changed
     */
    private $isDirty = false;
    private $slugifier;
    protected $slug;

    /**
     * Construct
     */
    public function __construct($data, Slugifier $slugifier)
    {
        $this->slugifier = $slugifier;
        $this->setData($data);
        $this->isDirty = false;
    }

    /**
     *
     * Allows for an object to be populated by passign in an array
     * Properties MUST be defined
     *
     * @param array $data an array of data to set
     *
     */
    public function setData($data = [])
    {
        foreach ($data as $key => $value) {
            $this->__set($key, $value);
        }
    }

    /**
     * Returns the accessible non-static properties of the given object
     */
    public function getData()
    {
        $properties = get_object_vars($this);
        unset($properties['isDirty']);
        unset($properties['slugifier']);
        
        return $properties;
    }

    /**
     * Returns true if the objects properties have changed
     */
    public function isDirty()
    {
        return $this->isDirty;
    }

    /**
     * setter to access private properties
     */
    public function __set($key, $val)
    {
        if ($this->protectedPropertyExists($this, $key) && $this->$key !== $val) {
            $this->$key = $val;
            $this->isDirty = true;
        }
    }

    /**
     * Getter to read private properties
     */
    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * Returns an array of properties for json_encode to serialize
     */
    public function jsonSerialize()
    {
        return $this->getData();
    }

    /**
     * Check if a property exists, even if its protected
     *
     * @param object $obj the contest in which to look
     * @param mixed $key the key name
     *
     * @return bool if ket was found
     *
     */
    private function protectedPropertyExists($obj, $key)
    {
        $reflection = new ReflectionClass($obj);
        if (!$reflection->hasProperty($key)) {
            return false;
        }
        
        return $reflection->getProperty($key)->isProtected();
    }
    
    public function generateSlug($suffix = '')
    {
        if (!is_string($this->getSlugData())) {
            return false;
        }
        return $this->slugifier->slugify($this->getSlugData() . ' ' . $suffix);
    }
    
    public function getSlugData()
    {
        
    }
}
