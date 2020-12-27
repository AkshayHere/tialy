<?php

namespace App\Helpers;

use App\Helpers\ErrorRespository;
use Carbon\Carbon;

class CustomResponse{
    
    public function addError(string $errorCode,string $errorMessage):CustomResponse{
        ErrorRespository::addError($errorCode,$errorMessage);
        return $this;
    }

    public function addWarning(string $warningMessage):CustomResponse{
        ErrorRespository::addWarning($warningMessage);
        return $this;
    }

    public function error(array $data = []): array{
        return $this->json(false,$data);
    }

    public function success(array $data = []): array{
        return $this->json(true,$data);
    }

    private function json(bool $success = true, array $data = []): array{

        $json = [
            "timestamp" => Carbon::now()->toIso8601String(),
            "success" => $success ? "ok" : "error",
            "warnings" => ErrorRespository::getWarnings(),
            "errors" => ErrorRespository::getErrors()
        ];

        if(!empty($data)){
            $json = array_merge($json,$data);
        }

        return $json;
    }
}
