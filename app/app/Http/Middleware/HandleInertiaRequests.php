<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\School;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        // 未ログインだとユーザーの取得ができないので
        // データ取得の処理を飛ばす
        if (Auth::guard()->guest()) {
            return parent::share($request);
        }

        // test用データなので最終的には削除する
        // 対象のページ
        // app/resources/js/Pages/DataTest/TeacherVideo.vue
        // app/resources/js/Pages/DataTest/StudentVideo.vue
        $request->user()->school;
        
        return array_merge(parent::share($request), [
            // ユーザーが所属する学校の情報を取得
            'school' => function () use ($request) {
                return User::find($request->user()->id)->school->only('name');
            },
            'classworks' => function () use ($request) {
                $user = User::find($request->user()->id);
                $classworks = array();

                if ($user->roll_flag == 'st') {
                    // 受講中の授業を取得
                    foreach ($user->attendances as $attendance) {
                        // 授業情報を取得
                        array_push($classworks, array(
                            "id" => $attendance->id,
                            "name" => $attendance->classwork->name,
                        ));
                    }
                } elseif ($user->roll_flag == 'te') {
                    // 教師の場合の処理
                    foreach ($user->tutors as $tutor) {
                        // 授業情報を取得
                        array_push($classworks, array(
                            "id" => $tutor->id,
                            "name" => $tutor->classwork->name,
                        ));
                    }
                } elseif ($user->roll_flag == 'ad') {
                    // 管理者の場合の処理
                    // 授業情報を取得
                    foreach ($user->school->classworks as $classwork) {
                        $in_charges = array();
                        foreach ($classwork->in_charges as $in_charge) {
                            array_push($in_charges, array(
                                "id" => $in_charge->id,
                                "name" => $in_charge->teacher->name,
                            ));
                        }
                        array_push($classworks, array(
                            "id" => $classwork->id,
                            "name" => $classwork->name,
                            "in_charges" => $in_charges,
                        ));
                    }
                }
                return $classworks;
            },
        ]);
    }
}
