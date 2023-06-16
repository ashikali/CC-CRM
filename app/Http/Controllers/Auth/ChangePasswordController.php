<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Debugbar;
use Carbon\Carbon;


class ChangePasswordController extends Controller
{
    public function edit()
    {
        abort_if(Gate::denies('profile_password_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('auth.passwords.edit');
    }


    public function update(UpdatePasswordRequest $request)
    {
        $info = $request->validated();
	    $info['password_changed_at'] = auth()->user()->password_changed_at;
        if($info['password_changed_at']->gt(Carbon::now()->subDays(2))){
            return redirect()->route('profile.password.edit')->with('warn', __('Password can only be changed after 2 days.'));
        }

        $passHistory = auth()->user()->passwordHistories();
        $pass = $passHistory->orderBy('id', 'desc')->limit(5)->get();
        $newpass = $request->get('password');
        if($pass->contains('password', $newpass)){
            return redirect()->route('profile.password.edit')->with('warn', __('Password can not be one of the last 5 passwords'));
        }

        auth()->user()->update($info);
        
        auth()->user()->passwordHistories()->create($info);
        return redirect()->route('profile.password.edit')->with('message', __('global.change_password_success'));
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = auth()->user();
        $user->update($request->validated());

        return redirect()->route('profile.password.edit')->with('message', __('global.update_profile_success'));
    }

    public function destroy()
    {
        $user = auth()->user();

        $user->update([
            'email' => time() . '_' . $user->email,
        ]);

        $user->delete();

        return redirect()->route('login')->with('message', __('global.delete_account_success'));
    }
}
