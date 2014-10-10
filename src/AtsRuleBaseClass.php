<?php
use Illuminate\Support\Facades\Validator;

abstract class AtsRuleBaseClass
{
	protected static function rules(){}
	
	protected static function messages(){}
	
	public static function validator($params) 
	{
		$class = get_called_class();
		
		$validator = Validator::make($params, $class::rules(), $class::messages());
		
		if ($validator->fails())
		{
			return $validator->messages()->all();
		}
		
		return true;
	}
}