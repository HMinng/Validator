<?php
use HMinng\Validator\Base\Validator;

abstract class AtsRuleBaseClass
{
	protected static function rules(){}
	
	protected static function messages(){}

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
		
		return true;
	}
}