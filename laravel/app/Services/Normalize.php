<?php namespace App\Services;

use \Collator;
use \Normalizer;

class Normalize {

    private $para1;
    private $collator;

    public function __construct($local)
    {
        $this->collator = new Collator($local);
    }

    public function normalize($str)
    {
        return normalizer_normalize($str, Normalizer::FORM_D);
    }

    public function compare($str1, $str2)
    {
        return $this->collator->compare($str1, $str2);
    }

    public function removeDiacritics($str)
    {
        $str = $this->normalize($str);
        return preg_replace('~\p{M}~u', '', $str);
    }

    public function trimBeforeFirstUppercase($str)
    {
        $str = $this->normalize($str);
        return preg_replace('/^\P{Lu}*/u', '', $str);
    }

    public function onlyLettersAndDigits($str)
    {
        $str = $this->normalize($str);
        return preg_replace('~[^\p{L}|\p{Nd}]~u', '', $str);
    }

}
