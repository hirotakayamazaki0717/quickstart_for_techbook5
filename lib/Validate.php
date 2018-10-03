<?php

class Validate
{
    public static function test($rules, $params, $messages = [])
    {
        $errors = [];

        foreach ($rules as $key => $rule_string) {
            $rule_list = explode('|', $rule_string);
            $value     = array_get($params, $key);

            $message_templates = array_get($messages, $key);

            $error_messages = [];

            foreach ($rule_list as $rule) {
                $rule_parts  = explode(':', $rule);
                $rule_name   = array_shift($rule_parts);
                $rule_params = $rule_parts;
                $method = 'validate'.camelize(''.$rule_name);
                if (!static::$method($value, $rule_params)) {
                    $error_messages[$rule_name] = array_get($message_templates, $rule_name, $rule_name.'のエラーが発生しました');
                }
            }

            if ($error_messages) {
                $errors[$key] = implode('/', $error_messages);
            }
        }

        return $errors;
    }

    public static function validateRequired($value)
    {
        return !!strlen($value);
    }

    public static function validateNotNumberOnly($value)
    {
        return !preg_match('/^[0-9０-９]+$/', strval($value));
    }

    public static function validateMax($value, $params)
    {
        if (!isset($params[0]) || !intval($params[0])) {
            throw new Exception('Validateのrule maxにはmax:255 のように文字数を指定してください！');
        }

        $max_length = $params[0];

        return mb_strlen($value) <= $max_length;
    }
}
