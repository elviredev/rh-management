<?php

namespace App\Models;

use Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

#[Fillable(['department_id', 'position_id', 'user_id', 'manager_id', 'first_name', 'last_name', 'email', 'phone', 'avatar_path', 'address', 'employment_status', 'hire_date', 'salary'])]
#[Appends(['full_name', 'avatar_url'])]
class Employee extends Model
{
  /** @use HasFactory<EmployeeFactory> */
  use HasFactory;

  // Casts
  protected function casts(): array
  {
    return [
      'hire_date' => 'date',
      'salary' => 'decimal:2',
    ];
  }

  // Accesseurs
  protected function fullName(): Attribute
  {
    return Attribute::get(fn (): string => "{$this->first_name} {$this->last_name}");
  }

  /**
   * Une URL publique pour l'avatar téléchargé, ou null si aucun n'est défini.
   * @return Attribute<string|null, never>
   */
  protected function avatarUrl(): Attribute
  {
    return Attribute::get(fn (): ?string => $this->avatar_path ? Storage::url($this->avatar_path) : null);
  }


  // Relations
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function department(): BelongsTo
  {
    return $this->belongsTo(Department::class);
  }

  public function position(): BelongsTo
  {
    return $this->belongsTo(Position::class);
  }

  /**
   * Mon manager est l'employé correspondant à ce "manager_id
   * @return BelongsTo
   */
  public function manager(): BelongsTo
  {
    return $this->belongsTo(Employee::class, 'manager_id');
  }

  /**
   * Mes subordonnées sont tous les employés dont l'id de "manager_id" pointe vers moi
   * @return HasMany<Employee, $this>
   */
  public function reports(): HasMany
  {
    return $this->hasMany(Employee::class, 'manager_id');
  }

  /**
   * Demandes de congés
   * @return HasMany<LeaveRequest, $this>
   */
  public function leaveRequests(): HasMany
  {
    return $this->hasMany(LeaveRequest::class, 'employee_id');
  }

  /**
   * Solde de congés
   * @return HasMany<LeaveBalance, $this>
   */
  public function leaveBalances(): HasMany
  {
    return $this->hasMany(LeaveBalance::class, 'employee_id');
  }

  /**
   * Présences
   * @return HasMany<Attendance, $this>
   */
  public function attendances(): HasMany
  {
    return $this->hasMany(Attendance::class, 'employee_id');
  }

  /**
   * Fiches de paye
   * @return HasMany<Payslip, $this>
   */
  public function payslips(): HasMany
  {
    return $this->hasMany(Payslip::class, 'employee_id');
  }
}
