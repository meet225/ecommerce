<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Http\Requests\StoreCustomerRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $customers = Customer::select(['id', 'name', 'email', 'phone', 'created_at']);

            return DataTables::of($customers)
                ->addIndexColumn()
                ->addColumn('action', function($customer) {
                    return '
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="'.route('admin.customers.edit', $customer->id).'" class="btn btn-warning" title="Edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button class="btn btn-danger delete-customer-btn" data-id="'.$customer->id.'" title="Delete">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    ';
                })
                ->editColumn('created_at', function($customer) {
                    return $customer->created_at->format('d-m-Y H:i');
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.customers.index');
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        Customer::create($request->validated());
        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer created successfully!');
    }


    public function edit(Customer $customer): View
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(StoreCustomerRequest $request, Customer $customer): RedirectResponse
    {
        $customer->update($request->validated());
        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer details updated successfully!');
    }

    public function destroy(Customer $customer)
    {
        try {
            $customer->delete();
            return response()->json(['message' => 'Customer deleted successfully!'], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'constraint violation') || str_contains($e->getMessage(), 'Integrity constraint violation')) {
                return response()->json(['message' => 'Cannot delete customer. This customer is linked to one or more existing orders.'], 409); 
            }
            return response()->json(['message' => 'An error occurred during deletion: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred.'], 500);
        }
    }
}
