<?php

namespace App\Validators;

use App\Exceptions\ValidationException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Collection;

class UserValidator extends AbstractBaseValidator
{
    /**
     * @throws ValidationException
     */
    public function validate(string $content, bool $isEdit = false): array
    {
        return $this->validateAndDecodeContent($content, $this->getConstraints($isEdit));
    }

    /**
     * Get constraints
     * @param bool $isEdit
     * @return Collection
     */
    public function getConstraints(bool $isEdit): Assert\Collection
    {
        $constraints = [
            'email' => [
                new Assert\Email(['message' => $this->translator->trans('Email is not valid')]),
            ],
            'role' => [
                new Assert\Choice(['choices' => ['ROLE_USER', 'ROLE_ADMIN'], 'message' => $this->translator->trans('Role is not valid')])
            ],
        ];

        if (!$isEdit) {
            // Add constraints for 'email' and 'password' if it's not an edit
            $constraints['email'][] = new Assert\NotBlank(['message' => $this->translator->trans('Email required')]);
            $constraints['password'] = new Assert\NotBlank(['message' => $this->translator->trans('Password required')]);
            $constraints['role'][] = new Assert\NotBlank(['message' => $this->translator->trans('Role required')]);
        }

        return new Assert\Collection($constraints);
    }
}
