<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Builder;

abstract class Controller extends BaseController
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = $request->user();
            if ($user && (! $user->is_active)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'email' => 'Akun Anda dinonaktifkan. Hubungi admin perusahaan/super admin.',
                ]);
            }

            return $next($request);
        });
    }

    protected function isSuperAdmin(): bool
    {
        $u = Auth::user();
        return $u && method_exists($u, 'hasRole') ? $u->hasRole('super-admin') : false;
    }

    protected function isAdmin(): bool
    {
        $u = Auth::user();
        return $u && method_exists($u, 'hasRole') ? $u->hasRole('admin') : false;
    }

    protected function isLeadOps(): bool
    {
        $u = Auth::user();
        return $u && method_exists($u, 'hasRole') ? $u->hasRole('lead-operations') : false;
    }

    protected function schemaHas(string $table, string $column): bool
    {
        return cache()->remember("schema_{$table}_{$column}", 3600, fn() =>
            Schema::hasColumn($table, $column)
        );
    }

    protected function scopeByRole(Builder $builder, array $options = []): Builder
    {
        if ($this->isSuperAdmin()) {
            return $builder;
        }

        $companyId = Auth::user()?->company_id;
        if ($companyId !== null) {
            if ($builder->getModel()->getTable() && $this->schemaHas($builder->getModel()->getTable(), 'company_id')) {
                $builder->where('company_id', $companyId);
            }
        }

        if ($this->isLeadOps() && ($options['restrict_to_owner'] ?? false)) {
            $uid = Auth::id();
            $table = $builder->getModel()->getTable();
            if ($this->schemaHas($table, 'assigned_to_id')) {
                $builder->where('assigned_to_id', $uid);
            } elseif ($this->schemaHas($table, 'owner_id')) {
                $builder->where('owner_id', $uid);
            } elseif ($this->schemaHas($table, 'created_by')) {
                $builder->where('created_by', $uid);
            }
        }

        return $builder;
    }

    protected function queryFor(string $modelClass, array $options = []): Builder
    {
        /** @var \Illuminate\Database\Eloquent\Model $model */
        $model = new $modelClass();
        $builder = $modelClass::query();
        return $this->scopeByRole($builder, $options);
    }
}
