<?php
/**
 * Created by JetBrains PhpStorm.
 * User: prof_lebrun
 * Date: 13-03-20
 * Time: 9:09 PM
 * To change this template use File | Settings | File Templates.
 */

namespace CQAtlas\Helpers;

/**
 * Base class for all models
 *
 * @author Zachary Fox
 */
abstract class Base
{
    /**
     * Pass properties to construct
     *
     * @param mixed[] $properties The object properties
     *
     * @throws Exception
     */
    protected function __construct(Array $properties)
    {

        $reflect = new ReflectionObject($this);

        foreach ($reflect->getProperties() as $property) {
            if (!array_key_exists($property->name, $properties)) {
                throw new Exception("Unable to create object. Missing property: {$property->name}");
            }

            $this->{$property->name} = $properties[$property->name];
        }
    }

    /**
     * Get all class properties
     *
     * @return string[]
     */
    protected static function getFields()
    {
        static $fields = array();
        $called_class  = get_called_class();

        if (!array_key_exists($called_class, $fields)) {
            $reflection_class = new \ReflectionClass($called_class);

            $properties = array();

            foreach ($reflection_class->getProperties() as $property) {
                $properties[] = $property->name;
            }

            $fields[$called_class] = $properties;
        }

        return $fields[$called_class];
    }

    /**
     * Get the select statement
     *
     * @return string
     */
    protected static function getSelect()
    {
        return "SELECT " . implode(', ', self::getFields()) . " FROM " . strtolower(get_called_class());
    }

    /**
     * Save this object
     *
     * @return void
     */
    protected function save()
    {
        global $db;
        $db = new \PDO('pgsql:host=localhost;port=5432;');

        $fields   = self::getFields();
        $replace  = "REPLACE INTO " . strtolower(get_called_class()) . "(" . implode(',', $fields) . ")";

        $function = function ($value) {
            return ':' . $value;
        };

        $replace .= " VALUES (" . implode(',', array_map($function, $fields)) . ")";

        $statement = $db->prepare($replace);

        foreach ($fields as $field) {
            $statement->bindParam($field, $this->$field);
        }

        $statement->execute();
    }

    /**
     * Get a single object by id
     *
     * @param integer $id
     * @return Object
     */
    public static function get($id)
    {
        global $db;

        $select    = self::getSelect() . " WHERE `id` = :id";
        //$statement = \PDO::prepare($select);
        $statement = $db->prepare($select);

        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return new static($result[0]);
    }

    /**
     * Get all objects
     *
     * @return Object[]
     */
    public static function getAll()
    {
        global $db;

        $return = array();
        foreach ($db->query(self::getSelect(), PDO::FETCH_ASSOC) as $row) {
            $return[] = new static($row);
        }

        return $return;
    }

    /**
     * Create a new object
     *
     * @param mixed[] $properties Properties
     *
     * @return Object
     */
    public static function create(Array $properties)
    {
        global $db;

        echo 'SELECT MAX(id) FROM ' . strtolower(get_called_class());
        $properties['id'] = ($db->query('SELECT MAX(id) FROM ' . strtolower(get_called_class()))->fetchColumn() + 1);
        $object = new static($properties);
        $object->save();
        return $object;
    }

    /**
     * Update an object
     *
     * @param mixed[] $properties Properties
     *
     * @return void
     */
    public function update(Array $properties)
    {
        foreach ($properties as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        $this->save();
    }

    /**
     * Update a single property
     *
     * @param string $key   Property name
     * @param mixed  $value Property value
     *
     * @return void
     */
    public function updateProperty($key, $value)
    {
        if (property_exists($this, $key)) {
            $this->$key = $value;
        }
        $this->save();
    }

    /**
     * Delete an object
     *
     * @return void
     */
    public function delete()
    {
        global $db;

        $delete    = "DELETE FROM " . strtolower(get_called_class()) . " WHERE `id` = :id";
        $statement = $db->prepare($statement);
        $statement->bindParam(':id', $this->id, PDO::PARAM_INT);

        $statement->execute();
    }
}
?>