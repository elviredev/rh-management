<?php

namespace App\Models;

use Database\Factories\PayslipFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['employee_id', 'period_start', 'period_end', 'gross_pay', 'deductions', 'net_pay', 'issued_at'])]
class Payslip extends Model
{
  /** @use HasFactory<PayslipFactory> */
  use HasFactory;

  protected function casts(): array
  {
    return [
      'period_start' => 'date',
      'period_end' => 'date',
      'issued_at' => 'datetime',
      'gross_pay' => 'decimal:2',
      'deductions' => 'decimal:2',
      'net_pay' => 'decimal:2',
    ];
  }

  /**
   * @return BelongsTo<Employee, $this>
   */
  public function employee(): BelongsTo
  {
    return $this->belongsTo(Employee::class);
  }
}
