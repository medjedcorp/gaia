<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserRequest; //バリデーション
// use App\Events\UserRegistered;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegistered;
use App\Mail\UserApproval;
use App\Mail\RegistrationRequest;
use Illuminate\Support\Facades\Hash;
use Gate;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        Gate::authorize('isSystem');

        $approval = $request->approval;
        $count = User::count();

        // adminのときの処理
        if (isset($approval)) {
            // dd($request);
            // 承認非承認時の処理
            if ((string)$user->id === $request->id) {
                $users = User::select(['id', 'name', 'email', 'role', 'tel', 'accepted', 'created_at'])->orderBy('created_at', 'asc')->get();
                return redirect("/users")->with([
                    'user' => $user,
                    'users' => $users,
                    'count' => $count,
                    'danger' => '※自分は変更できません！',
                ]);
            } else {
                // dd($request);
                $id = $request->id;
                $requser = User::find($id);
                $requser->accepted = $approval;
                $requser->save();
                $users = User::select(['id', 'name', 'email', 'role', 'tel', 'accepted', 'created_at'])->orderBy('created_at', 'asc')->get();
                // dd($users);
                if ($request->sendflag === "1") {
                    // dd($request->sendflag);
                    $to   = $requser->email;
                    Mail::to($to)->send(new UserApproval($user));
                }
                // return view("users.index");
                return view("users.index")->with([
                    'user' => $user,
                    'users' => $users,
                    'count' => $count,
                    'success' => '※' . $requser->name . 'さんの承認状況を変更しました',
                ]);
                // return redirect()->route("users.index")->with([
                //     'user' =>  $user,
                //     'users' => $users,
                //     'count' => $count,
                //     'success' => '※'. $requser->name .'さんの承認状況を変更しました',
                // ]);
            }
        } else {
            // 通常の処理
            $users = User::select(['id', 'name', 'email', 'role', 'tel', 'accepted', 'created_at'])->orderBy('created_at', 'desc')->get();
            return view("users.index")->with([
                'user' => $user,
                'users' => $users,
                'count' => $count,
            ]);
        }
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
    // public function thanks()
    // {
    //     return view('auth.thanks', [
    //         'user' => $user,
    //         'success' => '※ご登録ありがとうございました。確認後ご連絡させて頂きます。'
    //     ]);
    // }
    // public function approval(Request $request)
    // {
    //     $name = $request['name'];

    //     Mail::send(new UserApproval $name));
    // 　　return view('registers.index');
    // }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        // Log::debug($request);
        // dd($request);
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->tel = $request->tel;
        $user->role = 'user';
        $user->password = Hash::make($request['password']);
        $user->accepted = 0;
        $user->save();
        $to   = $user->email;
        $admin_mail = config('const.admin_mail');
        // event(new UserRegistered($user));

        Mail::to($to)->send(new UserRegistered($user));
        Mail::to($admin_mail)->send(new RegistrationRequest($user));

        Auth::logout();
        // パスワードは除外
        unset($user['password']);
        return view('auth.thanks', [
            'user' => $user,
            'success' => '※ご登録ありがとうございました。確認後ご連絡させて頂きます。'
        ]);
        // return view('auth.thanks', [
        //     'user' => $user,
        //     'success' => '※ご登録ありがとうございました。確認後ご連絡させて頂きます。'
        // ]);
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

    // public function thanks()
    // {
    //     return view('auth.thanks', [
    //         'user' => $user,
    //         'success' => '※ご登録ありがとうございました。確認後ご連絡させて頂きます。'
    //     ]);
    // }
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
