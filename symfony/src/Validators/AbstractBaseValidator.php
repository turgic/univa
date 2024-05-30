<?php

namespace App\Validators;

use App\Exceptions\ValidationException;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Validation;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractBaseValidator
{
    /**
     * @var TranslatorInterface $translator
     */
    protected TranslatorInterface $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(
        TranslatorInterface $translator
    ) {
        $this->translator = $translator;
    }

    /**
     * Get constraints
     * @return mixed
     */
    abstract public function getConstraints(bool $isEdit): mixed;

    /**
     * Validate and decode content
     * @param string $content
     * @param Collection $constraints
     * @return mixed
     * @throws ValidationException
     */
    public function validateAndDecodeContent(string $content, Collection $constraints): mixed
    {
        $validator = Validation::createValidator();
        $data = json_decode($content, true);
        $violations = $validator->validate($data, $constraints);
        if (count($violations) > 0) {
            // Build an array of error messages
            $errors = [];
            foreach ($violations as $violation) {
                $propertyPath = $violation->getPropertyPath();
                $property = ltrim($propertyPath, '[');
                $property = rtrim($property, ']');

                $message = $violation->getMessage();
                $errors[$property] = $message;
            }
            // Throw an exception with the error information
            throw new ValidationException($errors);
        }
        return $data;
    }
}
