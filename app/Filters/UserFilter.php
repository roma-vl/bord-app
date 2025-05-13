<?php

namespace App\Filters;

class UserFilter extends QueryFilter
{
    public function name(string $value): void
    {
        $this->builder->where('name', 'like', "%{$value}%");
    }

    public function email(string $value): void
    {
        $this->builder->where('email', 'like', "%{$value}%");
    }

    public function status(string $value): void
    {
        if ($value === 'active') {
            $this->builder->whereNotNull('email_verified_at');
        } elseif ($value === 'inactive') {
            $this->builder->whereNull('email_verified_at');
        }
    }

}
