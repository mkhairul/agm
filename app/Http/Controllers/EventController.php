<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Hashids\Hashids;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $output->writeln("<info>Controller Create</info>");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => ['required','max:255'],
            'description' => ['required'],
            'deadline' => ['date'],
            'qr' => [],
            'answers' => ['present', 'array', 'required']
        ])->validateWithBag('createNewEvent');

        //Post::create($request->all());
        $event = new \App\Models\Event;
        $event->name = $request->name;
        $event->description = $request->description;
        $event->answers = json_encode(array_filter($request->answers, 'strlen'));
        $event->deadline = Carbon::parse($request->deadline);
        $event->qr = $request->qr ? 1:0;
        $event->user_id = Auth::id();
        $event->save();

        $hashids = new Hashids(env('APP_KEY'));
        $event->external_id = $hashids->encode($event->id, $event->user_id);
        $event->save();

        return redirect()->back()->with('message', 'Post Created Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
