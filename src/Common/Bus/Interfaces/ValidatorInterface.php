<?php

declare(strict_types=1);


namespace Api\Common\Bus\Interfaces;

/**
 * Interface ValidatorInterface
 * @package Api\Common\Bus\Interfaces
 */
interface ValidatorInterface
{
    /**
     * @return array
     */
    public function rules(): array;

    /**
     * @return array
     */
    public function messages(): array;

    /**
     * @return array
     */
    public function customAttributes(): array;
}
