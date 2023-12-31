<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$employees = Employee::all();
        //$employees = Employee::orderBy('id', 'desc')->get();
        $employees = Employee::orderBy('id', 'desc')->paginate(2);
        return view('index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        //dd($request->except('_token));

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|unique:employees,email|email',
            'phone' => 'required|numeric|unique',
            'joining_date' => 'required',
            'salary' => 'required'
        ],[
            'email.unique' => 'email already exist',
            'phone.unique'=> 'phone number already exist'
        ]);

        $data = $request->except('_token');

        // mass assigment
        Employee::create($data);

        // insert single row 
        // $employee = new Employee;
        // $employee->name = $data['name'];
        // $employee->email = $data['email'];
        // $employee->joining_date = $data['joining_date'];
        // $employee->salary = $data['salary'];
        // $employee->phone = $data['phone'];
        // $employee->is_active = $data['is_active'];
        // $employee->save();

        return redirect()
        ->route('employee.index')
        ->withMessage('Employee has been created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
       return view('show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $employee = Employee::find($id);
        return view('edit', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|unique:employees,email,'.$employee->id.'|email',
            'phone' => 'required|numeric|unique:employees,phone,'.$employee->id,
            'joining_date' => 'required',
            'salary' => 'required'
        ],[
            'email.unique' => 'email already exist',
            'phone.unique'=> 'phone number already exist'
        ]);

        $data = $request->all();
        //$employee = Employee::find($id);
        $employee->update($data);
        return redirect()
        ->route('employee.edit' ,$employee->id)
        ->withSuccess('Employee details update successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()
        ->route('employee.index')
        ->withSuccess('Employee deleted Successfully');
    }
}
