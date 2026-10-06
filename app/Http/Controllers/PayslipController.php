<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payslip;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Inertia\Inertia;
use Inertia\Response;

class PayslipController extends Controller
{
  // Liste des bulletins de paye + filtre optionnel sur employee
  public function index(Request $request): Response
  {
    $query = Payslip::with('employee')->latest('period_end');

    if ($request->filled('employee')) {
      $query->where('employee_id', $request->integer('employee'));
    }

    return Inertia::render('payslips/index', [
      'payslips' => $query->paginate(15)->withQueryString(),
      'employees' => Employee::orderBy('first_name')->get(['id', 'first_name', 'last_name']),
      'filters' => $request->only(['employee']),
    ]);
  }

  // Générer plusieurs bulletins pour un mois entier
  public function store(Request $request): RedirectResponse
  {
    $data = $request->validate([
      'month' => ['required', 'date_format:Y-m'],
    ]);

    $period = Carbon::createFromFormat('Y-m', $data['month']);
    $start = $period->copy()->startOfMonth();
    $end = $period->copy()->endOfMonth();

    $created = 0;

    Employee::where('employment_status', 'active')->each(function (Employee $employee) use ($start, $end, &$created) {
      $exists = $employee->payslips()
        ->where('period_start', $start->toDateString())
        ->exists();

      if ($exists) {
        return;
      }

      $gross = round((float)$employee->salary / 12, 2);
      $deductions = round($gross * 0.2, 2);

      $employee->payslips()->create([
        'period_start' => $start->toDateString(),
        'period_end' => $end->toDateString(),
        'gross_pay' => $gross,
        'deductions' => $deductions,
        'net_pay' => $gross - $deductions,
        'issued_at' => now(),
      ]);

      $created++;
    });

    return back()->with('flash', "Generated $created payslip(s).");
  }

  /**
   * Bulletin de paye prêt à être imprimé. Ouvrir dans un nouvel onglet et
   * utiliser la fonction "Enregistrer au format PDF" du navigateur.
   * (aucune dépendance serveur supplémentaire requise).
   * @param Payslip $payslip
   * @return View
   */
  public function show(Payslip $payslip): View
  {
    $payslip->load('employee.department', 'employee.position');

    return view('payslips.show', [
      'payslip' => $payslip,
    ]);
  }
}












