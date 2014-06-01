<?php
	
abstract class validation {
	public static function minLength ($string, $minLength) {
		if (self::isString($string) && self::isNumeric($minLength))
			return strlen($string) >= $minLength;
		else
			throw new UnexpectedValueException("Expected a string and a numeric value. But got " + gettype($string) + " and " + gettype($minLength));
	}

	public static function maxLength ($string, $maxLength) {
		if (self::isString($string) && self::isNumeric($maxLength))
			return strlen($string) <= $maxLength;
		else
			throw new UnexpectedValueException("Expected a string and a numeric value. But got " + gettype($string) + " and " + gettype($maxLength));
	}

	public static function length ($string, $minLength, $maxLength) {
		if (self::isString($string) && self::isNumeric($minLength) && self::isNumeric($maxLength))
			return strlen($string) >= $minLength && strlen($string) <= $maxLength;
		else
			throw new UnexpectedValueException("Expected a string, numeric and numeric value. But got " + gettype($string) + ", " + gettype($minLength) + " and " + gettype($maxLength));
	}

	public static function maxNum ($value, $max) {
		if (self::isNumeric($value))
			return $value <= $max;
		else
			throw new UnexpectedValueException("Expected numeric value. But got " + gettype($value));
	}

	public static function minNum ($value, $min) {
		if (self::isNumeric($value))
			return $value >= $min;
		else
			throw new UnexpectedValueException("Expected numeric value. But got " + gettype($value));
	}

	public static function email ($string) {
		return filter_var($string, FILTER_VALIDATE_EMAIL);
	}

	public static function isNumeric ($value) {
		return is_numeric($value);
	}

	public static function isString ($string) {
		return is_string($string);
	}
}

?>