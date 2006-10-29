<?php
class Syscp_Validator_Regex implements Syscp_Validator_Regex_Interface
{
    protected $regex = '';

    public function __construct($value = '/^\n\0$/')
    {
        $this->regex = $value;
    }

    public function validate($value)
    {
        return (preg_match($this->regex, $value));
    }
}

