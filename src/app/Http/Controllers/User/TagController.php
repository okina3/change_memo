<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteTagRequest;
use App\Http\Requests\UploadTagRequest;
use App\Models\Tag;
use App\Services\TagService;
use App\Services\SessionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TagController extends Controller
{
    /**
     * タグの一覧を表示するメソッド。
     * @return View
     */
    public function index(): View
    {
        // ブラウザバック対策（値を削除する）
        SessionService::resetBrowserBackSession();
        // タグを取得する
        $all_tags = Tag::availableAllTags()->get();

        return view('user.tags.index', compact('all_tags'));
    }

    /**
     * タグを保存するメソッド。
     * @param UploadTagRequest $request
     * @return RedirectResponse
     */
    public function store(UploadTagRequest $request): RedirectResponse
    {
        //タグを保存
        TagService::storeTag($request->new_tag);

        return to_route('user.tag.index')->with(['message' => 'タグを登録しました。', 'status' => 'info']);
    }

    /**
     * タグを削除するメソッド。
     * @param DeleteTagRequest $request
     * @return RedirectResponse
     */
    public function destroy(DeleteTagRequest $request): RedirectResponse
    {
        //タグを複数まとめて削除
        foreach ($request->tags as $tag) {
            Tag::availableSelectTag($tag)->delete();
        }
        return to_route('user.tag.index')->with(['message' => 'タグを削除しました。', 'status' => 'alert']);
    }
}
