<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class ErrorRespository{

    protected static $instance;

    protected static $errors = [];
    protected static $warnings = [];

    /**
     * Protected constructor to prevent creating a new instance of the
     * singleton via the `new` operator.
     */
    protected function __construct()
    {
        // your constructor logic here.
    }

     /**
     * Private clone method to prevent cloning of the instance of the singleton instance.
     */
    private function __clone()
    {
    }

    /**
     * Private unserialize method to prevent unserializing of the singleton instance.
     */
    private function __wakeup()
    {
    }

    static function addWarning(string $warningMessage){
        Log::warning($warningMessage);
        static::$warnings[] = $warningMessage;
    }

    static function addError($code,$message){
        Log::error( $code . ' => ' . $message);
        static::$errors[] = ['code' => $code, 'message' => $message];
    }

    static function addErrors(Array $messages){
        foreach ($messages as $message) {
            static::$errors[] = ['code' => 0, 'message' => $message];
        }
    }

    static function getResponseErrors(){
        $errorsForReturn = [];
        foreach(static::$errors as $error) {
            $errorsForReturn[] = ['message' => $error['message']];
        }

        return $errorsForReturn;
    }

    static function getWarnings(){
        return static::$warnings;
    }

    static function getErrors(){
        return static::$errors;
    }

    static function hasError(){
        return count(static::$errors) > 0;
    }

}