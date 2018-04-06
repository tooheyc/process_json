<?php

class person
{
    protected $FName, $LName, $Age, $Errs = '';
    public $minAge = 0, $maxAge = 130, $maxLen = 100;
    public $filter = ['FName', 'LName', 'Age'];
    private $isValid = true;
    private $messages = [
        'Age' => ['notNum' => 'Age is not numeric.', 'young' => 'Age is less than ', 'old' => 'Age is greater than '],
        'long' => ' is longer than ',
        'empty' => ' is empty. '
    ];

    /*
     * Assign attributes, verify that only expected (and all expected) fields have been received.
     *
     * @return void
     */
    public function __construct($stObj)
    {
        $this->messages['Age']['young'] .= $this->minAge . ". ";
        $this->messages['Age']['old'] .= $this->maxAge . ". ";
        $this->messages['long'] .= $this->maxLen . " characters. ";

        foreach ($stObj as $attribute => $value) {
            if (in_array($attribute, $this->filter)) {
                if (strlen($value) > $this->maxLen) {
                    $this->Errs .= $attribute . $this->messages['long'];
                    $this->isValid = false;
                }
                $this->$attribute = $value;
            }
        }

        if (!is_numeric($this->Age)) {
            $this->Errs .= $this->messages['Age']['notNum'];
            $this->isValid = false;
        } elseif ((float)$this->Age < $this->minAge) {
            $this->Errs .= $this->messages['Age']['young'];
            $this->isValid = false;
        } elseif ((float)$this->Age > $this->maxAge) {
            $this->Errs .= $this->messages['Age']['old'];
            $this->isValid = false;
        } else {
            $this->Age = round($this->Age, 2);
        }
        $this->isFilled();
    }

    /*
     * Verify that all fields have been filled
     *
     * @return void
     */
    public function isFilled()
    {
        foreach ($this->filter as $key)
            if (mb_strlen($this->$key) == 0) {
                $this->Errs .= $key . $this->messages['empty'];
                $this->isValid = false;
            }
    }

    public function getRejection()
    {
        return $this->Errs;
    }

    /*
     * If invalid fields or an incomplete object were received, we won't use this object.
     *
     * @return boolean
     */
    public function isValid()
    {
        return $this->isValid;
    }

    /*
     * Only access valid fields.
     *
     * @return string
     */
    public function getField($field)
    {
        return in_array($field, $this->filter) ? $this->$field : '';
    }

    /*
     * Only access valid fields.
     *
     * @return Array
     */
    public function getFields()
    {
        $arr = [];
        foreach ($this->filter as $key) {
            $arr[] = $this->$key;
        }
        if (!$this->isValid()) {
            $arr[] = $this->getRejection();
        }

        return $arr;
    }

}
