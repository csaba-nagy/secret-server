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
    $this->checkRequiredParams();

    if (!empty($this->result['error'])) {
      return $this->result;
    }

    foreach ($this->payload as $paramKey => $value) {
      $validatorName = $paramKey;

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

  /**
   * Checks that all of the schema parameters are defined in the payload.
   * Optional parameters are not supported for now
   * @return void
   */
  private function checkRequiredParams(): void
  {
    $requiredParams = array_keys($this->schema);

    foreach ($requiredParams as $param) {
      $isValid = in_array($param, array_keys($this->payload));

      if (!$isValid) {
        $this->result['error'][$param] = "{$param} parameter is required!";
      }

      $this->result['data'][$param] = $isValid;
    }
  }

  /**
   *
   * @param mixed $value
   * @param int|string $minLength
   * @return array
   */
  private function minLength(mixed $value, int | string $minLength): array
  {
    $result = [
      'error' => [],
      'data' => false
    ];

    if (!is_string($value)) {
      $result['error'] = 'The given value is not a string!';
      $result['data'] = false;

      return $result;
    }

    if (!is_numeric($minLength)) {
      $result['error'] = 'Invalid parameter for minLength validator. IT should be ';
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


  /**
   *
   * @param mixed $value
   * @param int|string $maxLength
   * @return array
   */
  private function maxLength(mixed $value, int | string $maxLength): array
  {
    $result = [
      'error' => [],
      'data' => false
    ];

    $this->text($value);

    if (!is_numeric($maxLength)) {
      $result['error'] = 'Invalid parameter for maxLength validation.';
      $result['data'] = false;

      return $result;
    }

    $isValid = strlen($value) < $maxLength;

    if (!$isValid) {
      $result['error'] = "The given string should be maximum {$maxLength} characters long.";
    }

    $result['data'] = $isValid;

    return $result;
  }

  /**
   * Checks that the given value is a valid numeric or numeric string and it should be greater than 0.
   * @param mixed $value
   * @param null|string $flag Accepted flags: unsigned
   * @return array
   * @throws PayloadValidationException
   */
  private function numeric(mixed $value): array
  {
    $isValid = is_numeric($value) && (int) $value > 0;

    return [
      'error' => $isValid ? null : "The given value should be a positive integer number! Received: {$value}",
      'data' => $isValid
    ];
  }

  /**
   * Checks that the given value is a valid string
   * @param mixed $value
   * @return array
   */
  private function text(mixed $value): array
  {
    $isValid = is_string($value);

    return [
      'error' => $isValid ? null : 'The given value is invalid. It should be a string!',
      'data' => $isValid
    ];
  }
}
