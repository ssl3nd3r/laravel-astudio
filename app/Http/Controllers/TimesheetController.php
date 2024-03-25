<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Timesheet;
use Illuminate\Support\Facades\Validator;

class TimesheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $timesheets = Timesheet::latest();

        if ($request->task_name) {
            $timesheets->where('task_name' , $request->task_name);
        }
        if ($request->date) {
            $timesheets->whereDate('date' , $request->date);
        }

        $timesheets = $timesheets->get();

        return response()->json(compact('timesheets'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'task_name' => 'required|string',
            'date' => 'required|string',
            'hours' => 'required|integer',
            'user_id' => 'required|integer',
            'project_id' => 'required|integer',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 400);

        $timesheet = Timesheet::create([
            'task_name' => $request->task_name,
            'date' => $request->date,
            'hours' => $request->hours,
            'user_id' => $request->user_id,
            'project_id' => $request->project_id,
        ]);


        return response()->json([
            'message' => 'Timesheet added',
            'timesheet' => $timesheet,
            'associated_user' => $timesheet->user,
            'associated_project' => $timesheet->project
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $timesheet = Timesheet::findOrFail($id);
            
            return response()->json([
                'timesheet' => $timesheet
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Timesheet not found'
            ],400);
        }
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
        $validator = Validator::make($request->all() , [
            'task_name' => 'string',
            'date' => 'string',
            'hours' => 'integer',
            'user_id' => 'integer',
            'project_id' => 'integer',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 400);

        try {
            $timesheet = Timesheet::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Timesheet not found'
            ],400);
        }

        $timesheet->update([
            'task_name' => $request->task_name ?? $timesheet->task_name,
            'date' => $request->date ?? $timesheet->date,
            'hours' => $request->hours ?? $timesheet->hours,
            'user_id' => $request->user_id ?? $timesheet->user_id,
            'project_id' => $request->project_id ?? $timesheet->project_id,
        ]);


        return response()->json([
            'message' => "Project $id modified",
            'timesheet' => $timesheet,
            'associated_user' => $timesheet->user,
            'associated_project' => $timesheet->project
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $timesheet = Timesheet::findOrFail($id);
            $timesheet->delete();
            return response()->json([
                'message' => 'Timesheet deleted'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Timesheet not found'
            ],400);
        }
    }
}
