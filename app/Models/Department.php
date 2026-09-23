<?php

namespace App\Models;

use Database\Factories\DepartmentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'code', 'description'])]
class Department extends Model
{
  /** @use HasFactory<DepartmentFactory> */
  use HasFactory;

  public function positions(): HasMany
  {
    return $this->hasMany(Position::class, 'department_id');
  }

  public function employees(): HasMany
  {
    return $this->hasMany(Employee::class, 'department_id');
  }
}
