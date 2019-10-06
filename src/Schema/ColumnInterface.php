<?php

namespace Akceli\Schema;

interface ColumnInterface
{
    /**
     * @return string
     */
    public function getField(): string;

    /**
     * @return boolean
     */
    public function hasCastsToAttribute(): bool;

    /**
     * @return string
     */
    public function getCastsToAttribute(): string;

    /**
     * @return bool
     */
    public function hasValidationRules(): bool;

    /**
     * @return string
     */
    public function getValidationRulesAsString(): string;

    /**
     * @return string
     */
    public function getDataType(): string;

    /**
     * @return bool
     */
    public function isInteger(): bool;

    /**
     * @return bool
     */
    public function isBoolean(): bool;

    /**
     * @return bool
     */
    public function isTimeStamp(): bool;

    /**
     * @return bool
     */
    public function isEnum(): bool;

    /**
     * @return bool
     */
    public function isString(): bool;
}
