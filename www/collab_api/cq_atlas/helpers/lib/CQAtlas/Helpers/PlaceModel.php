<?php

namespace CQAtlas\Helpers;

/**
 * Class Place_model
 * @package CQAtlas\Helpers
 */
class PlaceModel
{
    private $_di;
    private $_address;
    private $_city;
    private $_latitude;
    private $_location;
    private $_longitude;
    private $_the_geom;
    private $_postal_code;
    private $_tel_number;
    private $_website;
    private $_created_by;
    private $_dataset_id;
    private $_desc_en;
    private $_desc_fr;
    private $_label;
    private $_name;
    private $_place_id;
    private $_privacy;
    private $_slug;
    private $_status;
    private $_version;
    private $_primary_category_id;
    private $_secondary_category_id;
    private $_tags;

    public function __construct($di)
    {
        $this->_di = $di;
        return $this;
    }

    public function setAddress($address)
    {
        $this->_address = $address;
        return $this;
    }

    public function getAddress()
    {
        return $this->_address;
    }

    public function setCity($city)
    {
        $this->_city = $city;
        return $this;
    }

    public function getCity()
    {
        return $this->_city;
    }

    public function setCreatedBy($created_by)
    {
        $this->_created_by = $created_by;
        return $this;
    }

    public function getCreatedBy()
    {
        return $this->_created_by;
    }

    public function setDatasetId($dataset_id)
    {
        $this->_dataset_id = $dataset_id;
        return $this;
    }

    public function getDatasetId()
    {
        return $this->_dataset_id;
    }

    public function setDescEn($desc_en)
    {
        $this->_desc_en = $desc_en;
        return $this;
    }

    public function getDescEn()
    {
        return $this->_desc_en;
    }

    public function setDescFr($desc_fr)
    {
        $this->_desc_fr = $desc_fr;
        return $this;
    }

    public function getDescFr()
    {
        return $this->_desc_fr;
    }

    public function setLabel($label)
    {
        $this->_label = $label;
        return $this;
    }

    public function getLabel()
    {
        return $this->_label;
    }

    public function setLatitude($latitude)
    {
        $this->_latitude = $latitude;
        return $this;
    }

    public function getLatitude()
    {
        return $this->_latitude;
    }

    public function setLocation($location)
    {
        $this->_location = $location;
        return $this;
    }

    public function getLocation()
    {
        return $this->_location;
    }

    public function setLongitude($longitude)
    {
        $this->_longitude = $longitude;
        return $this;
    }

    public function getLongitude()
    {
        return $this->_longitude;
    }

    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setPlaceId($place_id)
    {
        $this->_place_id = $place_id;
        return $this;
    }

    public function getPlaceId()
    {
        return $this->_place_id;
    }

    public function setPostalCode($postal_code)
    {
        $this->_postal_code = $postal_code;
        return $this;
    }

    public function getPostalCode()
    {
        return $this->_postal_code;
    }

    public function setPrimaryCategoryId($primary_category_id)
    {
        $this->_primary_category_id = $primary_category_id;
        return $this;
    }

    public function getPrimaryCategoryId()
    {
        return $this->_primary_category_id;
    }

    public function setPrivacy($privacy)
    {
        $this->_privacy = $privacy;
        return $this;
    }

    public function getPrivacy()
    {
        return $this->_privacy;
    }

    public function setSecondaryCategoryId($secondary_category_id)
    {
        $this->_secondary_category_id = $secondary_category_id;
        return $this;
    }

    public function getSecondaryCategoryId()
    {
        return $this->_secondary_category_id;
    }

    public function setSlug($slug)
    {
        $this->_slug = $slug;
        return $this;
    }

    public function getSlug()
    {
        return $this->_slug;
    }

    public function setStatus($status)
    {
        $this->_status = $status;
        return $this;
    }

    public function getStatus()
    {
        return $this->_status;
    }

    public function setTags($tags)
    {
        $this->_tags = $tags;
        return $this;
    }

    public function getTags()
    {
        return $this->_tags;
    }

    public function setTelNumber($tel_number)
    {
        $this->_tel_number = $tel_number;
        return $this;
    }

    public function getTelNumber()
    {
        return $this->_tel_number;
    }

    public function setTheGeom($the_geom)
    {
        $this->_the_geom = $the_geom;
        return $this;
    }

    public function getTheGeom()
    {
        return $this->_the_geom;
    }

    public function setVersion($version)
    {
        $this->_version = $version;
        return $this;
    }

    public function getVersion()
    {
        return $this->_version;
    }

    public function setWebsite($website)
    {
        $this->_website = $website;
        return $this;
    }

    public function getWebsite()
    {
        return $this->_website;
    }

    public function save(){
        $fields = self::getFields();
        echo '<pre><code>';
        print_r($fields);
        echo '</code></pre>';

        $properties = array();
        foreach ($fields as $property) {
            $properties[] = $property;
        }

        echo implode(',',$properties);
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
}