<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUser;
use App\Models\Image;
use App\Models\User;
use App\Services\Counter;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        // Only authenticated users can view other user profile
        $this->middleware('auth');

        // Authorize certain actions: ex. for the User model with parameter name
        // user, use the registered model Policy for this particular model
        $this->authorizeResource(User::class, 'user');
    }

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $counter = new Counter();

        return view('users.show', [
            'user' => $user,
            'counter' =>$counter->increment("user-{$user->id}")
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request, User $user)
    {
        // Check if the request contains a file, in the field name="avatar"
        if ($request->hasFile('avatar')) {
            // Store the file in storage/avatars folder
            $path = $request->file('avatar')->store('avatars');

            // Chech if there already is an image for the user
            if ($user->image) {
                // Modify the image path
                $user->image->path = $path;
                // Save changes to the image
                $user->image->save();
            } else {
                // Replaced by shorthand below
                // $image = new Image();
                // $image->path = $path;
                // $user->image()->save($image);

                // Save the image (Polymorphic relation) of the user
                $user->image()->save(
                    Image::make(['path' => $path])
                );
            }
        }

        $user->locale = $request->get('locale');
        $user->save();

        return redirect()
            ->back()
            ->withStatus('Profile was updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
