<?php
namespace App\Http\Controllers;
use App\Models\Supplier;
use Illuminate\Http\Request;
 
class SupplierController extends Controller {
 
    public function index() {
        $suppliers = Supplier::withCount('products')->latest()->paginate(15);
        return view('suppliers.index', compact('suppliers'));
    }
 
    public function create() {
        return view('suppliers.create');
    }
 
    public function store(Request $request) {
        $request->validate([
            'name'           => 'required|string|max:255',
            'contact_person'=> 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'email'          => 'required|email|max:255',
            'address'        => 'required|string',
        ]);
        Supplier::create($request->all());
        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier added successfully!');
    }
 
    public function edit(Supplier $supplier) {
        return view('suppliers.edit', compact('supplier'));
    }
 
    public function update(Request $request, Supplier $supplier) {
        $request->validate([
            'name'           => 'required|string|max:255',
            'contact_person'=> 'nullable|string|max:255',
            'phone'          => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:255',
            'address'        => 'nullable|string',
        ]);
        $supplier->update($request->all());
        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier updated successfully!');
    }
 
    public function destroy(Supplier $supplier) {
        $supplier->delete();
        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier deleted.');
    }
}
