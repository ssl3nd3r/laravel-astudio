<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Timesheet;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $projects = Project::latest();

        if ($request->name) {
            $projects->where('name' , $request->name);
        }
        if ($request->department) {
            $projects->where('department' , $request->department);
        }
        if ($request->status) {
            $projects->where('status' , $request->status);
        }

        $projects = $projects->get();

        return response()->json(compact('projects'));
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
            'name' => 'required|string',
            'department' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => 'required|integer',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 400);

        $project = Project::create([
            'name' => $request->name,
            'department' => $request->department,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
        ]);

        if ($request->users && is_array(json_decode($request->users))) {
            $project->users()->sync(json_decode($request->users));
        }

        return response()->json([
            'message' => 'Project added',
            'project' => $project,
            'associated_users' => $project->users
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
            $project = Project::findOrFail($id);
            
            return response()->json([
                'project' => $project
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Project not found'
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
            'name' => 'string',
            'department' => 'string',
            'start_date' => 'date',
            'end_date' => 'date',
            'status' => 'integer',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 400);

        try {
            $project = Project::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Project not found'
            ],400);
        }

        $project->update([
            'name' => $request->name ?? $project->name,
            'department' => $request->department ?? $project->department,
            'start_date' => $request->start_date ?? $project->start_date,
            'end_date' => $request->end_date ?? $project->end_date,
            'status' => $request->status ?? $project->status,
        ]);

        if ($request->users && is_array(json_decode($request->users))) {
            $project->users()->sync(json_decode($request->users));
        }

        return response()->json([
            'message' => "Project $id modified",
            'project' => $project,
            'associated_users' => $project->users
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
            $project = Project::findOrFail($id);
            Timesheet::where('project_id',$project->id)->delete();
            $project->delete();
            return response()->json([
                'message' => 'Project deleted'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Project not found'
            ],400);
        }
    }
}
