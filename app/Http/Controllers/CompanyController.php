<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Company;
use App\Models\Employee;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $companyDetail = Company::all()->toArray();
        $totalEarning = DB::table('employee')
        ->join('company', 'company.id', '=', 'employee.company_id')
        ->select('company.name', DB::raw('sum(employee.earning2016) + sum(employee.earning2017)  + sum(employee.earning2018) AS total_earnings'))->groupBy('company.name')
        ->get();
        return view('company', ['companyDetail' => $companyDetail, 'totalEarning' => $totalEarning]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         // get form  value in variable
         $name = $request->input('name');
         $email = $request->input('email');
         $address = $request->input('address');

         // csv file data get in array
         $path = $request->file('csvfile')->getRealPath();
         $data = array_map('str_getcsv', file($path));
         $csv_data = array_slice($data, 1);
       
        //check company is already exist or not
        $companyCheck = Company::select('email')->where('email', $email)->first(); 

        if(empty($companyCheck)){
            // save company details in table
            $company = new Company;
            $company->name = $name;
            $company->email = $email;
            $company->address = $address;
            $company->save();

            // get new record id 
            $companyId = $company->id;
            // save employee details in table
            $this->employeeCreate($csv_data, $companyId);

        }else{
            $company = Company::where('email', $email)->first();
            $company->name = $name;
            $company->address = $address;
            $company->save();

            $companyId = $company['id'];
            // update employee details in table
            $this->employeeUpdate($csv_data, $companyId);
        }

        
         return redirect()->route('companydetails');
    }

    public function employeeCreate($csv_data, $companyId)
    {
        foreach($csv_data as $key =>$csv_datas){
            //check employee is already exist or not
            $employeeCheck = Employee::select('email')->where('email', $csv_datas[1])->first(); 
            if(empty($employeeCheck)){
                $employee = new Employee;
                $employee->name = $csv_datas[0];
                $employee->email = $csv_datas[1];
                $employee->age = $csv_datas[2];
                $employee->earning2016 = $csv_datas[3];
                $employee->earning2017 = $csv_datas[4];
                $employee->earning2018 = $csv_datas[5];
                $employee->company_id = $companyId;
                $employee->save();
            }
        }
    }

    public function employeeUpdate($csv_data, $companyId)
    {
        foreach($csv_data as $key =>$csv_datas){
            //check employee is already exist or not
            $employeeCheck = Employee::select('email', 'company_id')->where('email', $csv_datas[1])->first(); 

            if(empty($employeeCheck)){
                $employee = new Employee;
                $employee->name = $csv_datas[0];
                $employee->email = $csv_datas[1];
                $employee->age = $csv_datas[2];
                $employee->earning2016 = $csv_datas[3];
                $employee->earning2017 = $csv_datas[4];
                $employee->earning2018 = $csv_datas[5];
                $employee->company_id = $companyId;
                $employee->save();
            }
            if(isset($employeeCheck['company_id'])){
                if($employeeCheck['company_id'] == $companyId){
                    $employee = Employee::where('email', $employeeCheck['email'])->first();
                    $employee->name = $csv_datas[0];
                    $employee->age = $csv_datas[2];
                    $employee->earning2016 = $csv_datas[3];
                    $employee->earning2017 = $csv_datas[4];
                    $employee->earning2018 = $csv_datas[5];
                    $employee->save();
                }
            }
        }
        return redirect()->route('companydetails');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
        $allCompanyData = Company::where('id', '=', $id)->with('employee')->get()->toArray();
        $earning_2016 = Employee::where('company_id', $id)->sum('earning2016');
        $earning_2017 = Employee::where('company_id', $id)->sum('earning2017');
        $earning_2018 = Employee::where('company_id', $id)->sum('earning2018');
        return view('report', ['allCompanyData' => $allCompanyData, 'earning_2016' => $earning_2016, 'earning_2017' => $earning_2017, 'earning_2018' => $earning_2018]);
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
