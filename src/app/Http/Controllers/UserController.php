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
use App\Services\UserAgentService;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    private $agent;

    public function __construct()
    {
        $this->agent = new UserAgentService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        Gate::authorize('isAdmin');

        // ユーザーエージェントで分岐
        $terminal = $this->agent->GetAgent($request);

        $approval = $request->approval;
        $count = User::count();

        if (isset($approval)) {
            // 承認非承認時の処理
            if ((string)$user->id === $request->id) {
                // 自分自身のボタンを押した場合
                if ($terminal === 'mobile') {
                    $users = User::select(['id', 'name', 'email', 'role', 'tel', 'accepted', 'created_at'])->orderBy('created_at', 'asc')->paginate(15);
                } else {
                    $users = User::select(['id', 'name', 'email', 'role', 'tel', 'accepted', 'created_at'])->orderBy('created_at', 'asc')->get();
                }

                return view("users.index")->with([
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

                if ($terminal === 'mobile') {
                    $users = User::select(['id', 'name', 'email', 'role', 'tel', 'accepted', 'created_at'])->orderBy('created_at', 'asc')->paginate(15);
                } else {
                    $users = User::select(['id', 'name', 'email', 'role', 'tel', 'accepted', 'created_at'])->orderBy('created_at', 'asc')->get();
                }

                if($approval === "1"){
                    if ($request->sendflag === "1") {
                        // dd($request->sendflag);
                        $to   = $requser->email;
                        Mail::to($to)->send(new UserApproval($user));

                        return view("users.index")->with([
                            'user' => $user,
                            'users' => $users,
                            'count' => $count,
                            'success' => '※' . $requser->name . 'さんを承認して、承認完了メールを送信しました',
                        ]);
                    } else {
                        return view("users.index")->with([
                            'user' => $user,
                            'users' => $users,
                            'count' => $count,
                            'success' => '※' . $requser->name . 'さんを承認しました',
                        ]);
                    }
                } elseif($approval === "2") {
                    return view("users.index")->with([
                        'user' => $user,
                        'users' => $users,
                        'count' => $count,
                        'warning' => '※' . $requser->name . 'さんの承認を取消ました',
                    ]);
                }
                // dd($users);


            }
        } else {
            $keyword = '';
            // 通常の処理
            if ($terminal === 'mobile') {
                if ($request->keyword) {
                    $keyword = $request->keyword;
                    $users = User::select(['id', 'name', 'email', 'role', 'tel', 'accepted', 'created_at'])->orderBy('created_at', 'asc')->where(DB::raw('CONCAT(name, email, tel)'), 'like', '%' . $keyword . '%')->paginate(15);
                } else {
                    $users = User::select(['id', 'name', 'email', 'role', 'tel', 'accepted', 'created_at'])->orderBy('created_at', 'desc')->paginate(15);
                }
            } else {
                $users = User::select(['id', 'name', 'email', 'role', 'tel', 'accepted', 'created_at'])->orderBy('created_at', 'desc')->get();
            }

            return view("users.index")->with([
                'user' => $user,
                'users' => $users,
                'count' => $count,
                'keyword' => $keyword,
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
