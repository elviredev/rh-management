<?php

namespace App\Models;

use Database\Factories\LeaveTypeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'default_days_per_year', 'is_paid'])]
class LeaveType extends Model
{
    /** @use HasFactory<LeaveTypeFactory> */
    use HasFactory;

  protected function casts(): array
  {
    return [
      'is_paid' => 'boolean',
    ];
  }

  /**
   * @return HasMany<LeaveRequest, $this>
   */
  public function leaveRequests(): HasMany
  {
    return $this->hasMany(LeaveRequest::class, 'leave_type_id');
  }

  /**
   * @return HasMany<LeaveBalance, $this>
   */
  public function balances(): HasMany
  {
    return $this->hasMany(LeaveBalance::class, 'leave_type_id');
  }
}
