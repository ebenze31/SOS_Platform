<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\User_command;
use Illuminate\Http\Request;

class User_commandsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $user_commands = User_command::where('name_command', 'LIKE', "%$keyword%")
                ->orWhere('command_role', 'LIKE', "%$keyword%")
                ->orWhere('number', 'LIKE', "%$keyword%")
                ->orWhere('status', 'LIKE', "%$keyword%")
                ->orWhere('creator', 'LIKE', "%$keyword%")
                ->orWhere('user_id', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $user_commands = User_command::latest()->paginate($perPage);
        }

        return view('user_commands.index', compact('user_commands'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('user_commands.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
        $requestData = $request->all();
        
        User_command::create($requestData);

        return redirect('user_commands')->with('flash_message', 'User_command added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user_command = User_command::findOrFail($id);

        return view('user_commands.show', compact('user_command'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $user_command = User_command::findOrFail($id);

        return view('user_commands.edit', compact('user_command'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        
        $requestData = $request->all();
        
        $user_command = User_command::findOrFail($id);
        $user_command->update($requestData);

        return redirect('user_commands')->with('flash_message', 'User_command updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        User_command::destroy($id);

        return redirect('user_commands')->with('flash_message', 'User_command deleted!');
    }
}
