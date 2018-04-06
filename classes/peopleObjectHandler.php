<?php

class peopleObjectHandler
{
    protected $people = [], $badPeople = [];

    /*
     * First, test for valid json. If so create an array of person objects with makeArray method.
     *
     * @return void
     */
    public function __construct($json = false)
    {
        if ($json) {
            $json = iconv("UTF-8", "UTF-8//IGNORE", $json);
            $objArr = json_decode($json);
            if ($objArr) $this->makeArray($objArr);
        }
    }

    /*
     * Create arrays of objects of class person for "good" and "bad" data.
     *
     * @return void
     */
    protected function makeArray($data)
    {
        foreach ($data AS $key => $value) {
            if (!is_array($value)) {
                $individual = new person($value);
                if ($individual->isValid()) {
                    $this->people[] = $individual;
                } else {
                    $this->badPeople[] = $individual;
                }
            }
        }
    }

    /*
     * Get a person.
     *
     * @return array
     */
    public function get()
    {
        return $this->people;
    }

    /*
     * Get bad people.
     *
     * @return array
     */
    public function getBad()
    {
        return $this->badPeople;
    }

    /*
     * Sort by fields
     *
     * @return void
     */
    public function sortPeople($field, $dir = 'desc')
    {
        usort($this->people, $this->sortByField($field, $dir));
        if(substr($field, -3) == "Too") $field = substr($field, 0, -3);
        usort($this->badPeople, $this->sortByField($field, $dir));
    }

    /*
     * Compare method for sorting by fields.
     * Javascript sorting would take some load off the server.
     *
     * @return integer
     */
    public function sortByField($field, $direction)
    {
        return function ($a, $b) use ($field, $direction) {

            // Handle Age as a numeric
            $values = ['desc' => -1, 'asc' => 1];
            if ($field == 'Age') {
                $aFloat = (float)$a->getField($field);
                $bFloat = (float)$b->getField($field);
                if($aFloat == $bFloat) {
                    $result = 0;
                } else if($aFloat > $bFloat) {
                    $result = $values[$direction];
                } else {
                    $result = -$values[$direction];
                }
            } else {
                // Use multi byte, case insensitive string comparison
                $result = $values[$direction] * $this->mb_strcasecmp($a->getField($field), $b->getField($field));
            }

            return $result;
        };
    }

    public function mb_strcasecmp($str1, $str2, $encoding = null)
    {
        if (null === $encoding) {
            $encoding = mb_internal_encoding();
        }
        return strcmp(mb_strtoupper($str1, $encoding), mb_strtoupper($str2, $encoding));
    }


}
