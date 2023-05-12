<?php

declare(strict_types=1);

namespace SecretServer\Api\v1\Utils;

use SecretServer\Api\v1\Contracts\ValidatorInterface;
use SecretServer\Api\v1\Exceptions\PayloadValidationException;

class PayloadValidator implements ValidatorInterface
{
  private array $result = [
    'data' => [],
    'error' => []
  ];

  public function __construct(private array $payload, private array $schema)
  {
  }


  /**
   *
   * @return array
   * @throws PayloadValidationException
   */
  public function validate(): array
  {
    foreach ($this->payload as $paramKey => $value) {
      $index = array_search($value, array_values($this->payload));
      $validatorName = array_keys($this->payload)[$index];

      $rules = $this->schema[$validatorName];

      if (empty($rules)) {
        throw new PayloadValidationException("Missing validation rule definitions for {$validatorName}!");
      }

      $validators = explode('|', $rules);

      foreach ($validators as $validator) {
        if (str_contains($validator, '=')) {
          $explodedValidator = explode('=', $validator);

          [$method, $param] = $explodedValidator;

          if (!method_exists($this, $method)) {
            throw new PayloadValidationException("Invalid validator method: {$method}!");
          }

          ['data' => $data, 'error' => $error] = $this->{$method}($value, $param);

          if (!empty($error)) {
            $this->result['error'][$validatorName] = $error;
          }

          $this->result['data'][$validatorName] = $data;
        } else {
          if (!method_exists($this, $validator)) {
            throw new PayloadValidationException("Invalid validator method: {$validator}!");
          }

          $result = $this->{$validator}($value);

          ['data' => $data, 'error' => $error] = $result;

          if (!empty($error)) {
            $this->result['error'][$validatorName] = $error;
          }

          $this->result['data'][$validatorName] = $data;
        }
      }
    }
    return $this->result;
  }

  private function minLength(mixed $value, int | string $minLength): array
  {
    $result = [
      'error' => null,
      'data' => null
    ];

    if (!is_string($value)) {
      $result['error'] = 'The given value is not a string!';
      $result['data'] = false;

      return $result;
    }

    if (!is_numeric($minLength)) {
      $result['error'] = 'Invalid parameter for ' . __METHOD__;
      $result['data'] = false;

      return $result;
    }


    $isValid = strlen($value) > $minLength;

    if (!$isValid) {
      $result['error'] = "The given string should be at least {$minLength} characters long!";
    }

    $result['data'] = $isValid;

    return $result;
  }

  private function maxLength(mixed $value, int | string $maxLength): array
  {
    $result = [
      'error' => null,
      'data' => null
    ];

    if (!is_string($value)) {
      $result['error'] = 'The given value is not a string!';
      $result['data'] = false;

      return $result;
    }

    if (!is_numeric($maxLength)) {
      $result['error'] = 'Invalid parameter for ' . __METHOD__;
      $result['data'] = false;

      return $result;
    }


    $isValid = strlen($value) < $maxLength;

    if (!$isValid) {
      $result['error'] = "The given string should be maximum {$maxLength} characters long!";
    }

    $result['data'] = $isValid;

    return $result;
  }

  private function numeric(mixed $value, string $flag): array
  {
    $isValid = is_numeric($value) && $value > 0;

    return [
      'error' => $isValid ? null : 'The given value should be a positive integer number!',
      'data' => $isValid
    ];
  }

  private function text(mixed $value): array
  {
    $isValid = is_string($value);

    return [
      'error' => $isValid ? null : 'The given value should be a string!',
      'data' => $isValid
    ];
  }
}
