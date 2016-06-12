<?php
use HMinng\Validator\Base\Validator;

abstract class AtsRuleBaseClass
{
	protected static function rules(){}
	
	protected static function messages(){}

    private static $verifiedAttribute = array();

    protected static function setPresenceVerifier(\HMinng\Validator\Base\Validator $validator)
    {
        return true;
    }

	public static function validator($params = array(), $scene = 'all')
	{
		$class = get_called_class();

        $validator = new Validator($params, $class::rules(), $class::messages(), $scene);

        $class::setPresenceVerifier($validator);

		if ($validator->fails()) {
			return $validator->getFallbackMessage();
		}

        self::$verifiedAttribute = $validator->getVerifiedAttribute();

		return true;
	}

    public static function getVerifiedAttribute()
    {
        $verifiedAttribute = self::$verifiedAttribute;

        self::$verifiedAttribute = array();

        return $verifiedAttribute;
    }
}