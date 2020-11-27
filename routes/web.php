<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::match(['post'], '/poll/submit', function (Request $request) {

    $total = \App\Models\Poll::where('user_session', \Session::getId())->where('external_id', $request->external_id)->count();
    if($total > 0){ throw new \Exception('You have already voted.'); }

    // Check if external_id is correct
    $hashids = new \Hashids\Hashids(env('APP_KEY'));
    [$event_id, $user_id] = $hashids->decode($request->external_id);
    $event_check = \App\Models\Event::where('id', $event_id)->where('user_id', $user_id)->count();
    if($event_check <= 0){ throw new \Exception('No event found.'); }

    // Sorry bout these guys, I'll tidy it up after the event, :( #feelsbadman
    \Validator::make($request->all(), [
        'external_id' => ['required','max:255'],
        'user_answer' => ['required','max:255'],
    ])->validateWithBag('createNewEvent');

    $poll = new \App\Models\Poll;
    $poll->external_id = $request->external_id;
    $poll->user_session = \Session::getId();
    $poll->answer = $request->user_answer;
    $poll->save();

    return redirect()->back()->with('message', 'Poll Saved Successfully.');

})->name('submit_poll');

Route::get('/poll/{id}', function (Request $request, $id) {

    $data = \App\Models\Event::select(['name', 'external_id', 'description', 'deadline', 'answers', 'qr'])->where('external_id', $id)->first();
    $total = \App\Models\Poll::where('user_session', \Session::getId())->where('external_id', $id)->count();
    $data->poll_url = route('user_poll', ['id' => $data->external_id]);
    if($data->qr){
        //$data->qrcode = base64_encode(QrCode::format('png')->size(800)->generate(route('user_poll', ['id' => $data->external_id])));
        $data->qrcode = '';
    }

    return Inertia\Inertia::render('Event/UserPoll', ['data' => $data, 'voted' => ($total ? 1:0)]);
})->name('user_poll');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia\Inertia::render('Dashboard');
})->name('dashboard');


Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::get('/event/create', function () {
        return Inertia\Inertia::render('Event/Create');
    })->name('create_event');

    Route::get('/events', function () {
        $data = \App\Models\Event::where('user_id', \Illuminate\Support\Facades\Auth::id())->get();
        foreach($data as $row){
            $row->poll_url = route('user_poll', ['id' => $row->external_id]);
            if($row->qr){
                //$row->qrcode = base64_encode(QrCode::format('png')->size(800)->generate(route('user_poll', ['id' => $row->external_id])));
                $row->qrcode = '';
            }
            foreach($row->answers as $answer){
                $row[$answer] = \App\Models\Poll::where('external_id', $row->external_id)->where('answer', $answer)->count();
            }
        }
        return Inertia\Inertia::render('Event/List', ['data' => $data]);
    })->name('events');

    Route::match(['put', 'post'], '/event/new/save', [EventController::class, 'store'])->name('save_event');
    Route::match(['get'], '/events/list', [EventController::class, 'show'])->name('get_events');
});
