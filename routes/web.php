<?php

use App\Http\Controllers\HomesController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\PostTagController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/', function () {
//     return view('home.index', []);
// })->name('home.index');

// Route::get('/contact', function () {
//     return view('home.contact');
// })->name('home.contact');

// Simplified routes of the above, if you don't have any extra parameters or do any extra work, with the use of Route::view (view is a static method of Route class)

// Routes with Controllers
Route::get('/', [HomesController::class, 'home'])
    ->name('home.index')
    // ->middleware('auth')
    ;
Route::get('/contact', [HomesController::class, 'contact'])->name('home.contact');

Route::get('/secret', [HomesController::class, 'secret'])
    ->name('home.secret')->middleware('can:home.secret');
// Single Action Controller, don't need to add array [<NameController::class>, 'method/action']
Route::get('/single', AboutController::class);

// Route for Resource Controller - PostsController. After we use only to choose the index methods and show
Route::resource('posts', PostsController::class);
    // ->only(['index', 'show', 'create', 'store', 'edit', 'update']);
Route::get('/posts/tag/{tag}', [PostTagController::class, 'index'])->name('posts.tags.index');

Route::resource('posts.comments', PostCommentController::class)->only(['store']);

Auth::routes();

// Route::get('/posts', function () use($posts){
//     // dd(request()->all());

//     // dd((int)request()->input('page', 1));

//     dd((int)request()->query('page', 1));

//     //compact($posts) === ['posts' => $posts] 
//     return view('posts.index', ['posts' => $posts]);
// });

// // Constrain route parameter
// Route::get('/posts/{id}', function ($id) use($posts) {
//     // Helper function if the id in the url is not in the range 1-2
//     abort_if(!isset($posts[$id]), 404);

//     return view('posts.show', ['post' => $posts[$id]]);
// })
// // ->where([
// //     'id' => '[0-9]+'
// // ])
// ->name('posts.show');

Route::get('/recent-posts/{days_ago?}', function($daysAgo = 20) {
    return 'Posts from ' . $daysAgo . ' days ago.';
})->name('posts.recent.index');

// Route::prefix('/fun/')->name('fun.')->group(function() use($posts){

//   Route::get('responses', function() use($posts){
//     return response($posts, 201)
//       ->header('Content-Type', 'application/json')
//       ->cookie('MY_COOKiE', 'John Doe', 180);
//   })->name('responses');
  
//   Route::get('redirect', function(){
//     return redirect('/contact');
//   })->name('redirect');
  
//   Route::get('back', function(){
//     return back();
//   })->name('back');
  
//   Route::get('named-route', function(){
//     return redirect()->route('posts.show', ['id' => 1]);
//   })->name('named-route');
  
//   Route::get('away', function(){
//     return redirect()->away('https://google.com');
//   })->name('away');
  
//   Route::get('json', function() use($posts){
//     return response()->json($posts);
//   })->name('json');
  
//   // Force the browser to download a file
//   Route::get('download', function() use($posts){
//     return response()->download(public_path('/daniel.jpg'), 'face.jpg');// The 3rd parameter is an optional array of header to return
//   })->name('download');
// });
