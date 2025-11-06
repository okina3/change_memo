<?php

namespace App\Services;

use App\Models\Memo;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;

class MemoService
{
    /**
     * 別のユーザーのメモを見られなくする為のメソッド。
     * @param $request
     * @return void
     */
    public static function checkUserMemo($request): void
    {
        // パラメーターを取得
        $id_memo = $request->route()->parameter('memo');
        // 自分自身のメモなのかチェック
        if (!is_null($id_memo)) {
            $memo_relation_user = Memo::findOrFail($id_memo)->user->id;
            if ($memo_relation_user !== Auth::id()) {
                abort(404);
            }
        }
    }

    /**
     * 全メモ、また、検索したメモを一覧表示するメソッド。
     * @return mixed
     */
    public static function searchMemos(): mixed
    {
        // クエリパラメータを取得
        $get_url_tag = \Request::query('tag');
        // クエリパラメータがあった場合の処理
        if (!empty($get_url_tag)) {
            // クエリパラメータから絞り込んだタグを取得
            $select_tag = Tag::availableSelectTag($get_url_tag)->first();
            // クエリパラメータから絞り込んだタグに、リレーションされたメモを取得
            $memos = $select_tag->memos;
        } else {
            // 全メモを取得
            $memos = Memo::availableAllMemos()->get();
        }
        foreach ($memos as $memo) {
            // メモが共有されているかどうかを確認
            $is_shared = $memo->shareSettings->isNotEmpty();
            // もしメモが共有されている場合、そのメモに目印を付ける
            if ($is_shared) {
                $memo->status = "共有中";
            }
        }
        return $memos;
    }

    /**
     * メモに紐づいたエサを、中間テーブルに保存するメソッド
     * @param $request
     * @param int $memo_id
     * @return void
     */
    public static function attachExistingBaits($request, int $memo_id): void
    {
        // 既存エサの選択があれば、メモに紐付けて中間テーブルに保存
        if (!empty($request->baits)) {
            foreach ($request->baits as $bait_number) {
                Memo::findOrFail($memo_id)->baits()->attach($bait_number);
            }
        }
    }

    /**
     * メモに紐づいた釣果データを、中間テーブルに保存するメソッド
     * @param $request
     * @param int $memo_id
     * @return void
     */
    public static function attachExistingFishNames($request, int $memo_id): void
    {
        // 釣果入力があれば処理を進める
        $fishing_results = $request->input('fishing_results', []);
        if (!is_array($fishing_results) || count($fishing_results) === 0) {
            return;
        }

        // ピボット属性付きで中間テーブルに保存するための配列を作成
        $attachData = [];
        foreach ($fishing_results as $fishing_result) {
            $fishNameId = (int) ($fishing_result['fish_name'] ?? 0);
            if ($fishNameId <= 0) {
                // 無効値はスキップ
                continue;
            }
            $count = isset($fishing_result['count']) ? (int) $fishing_result['count'] : 0;
            $length = isset($fishing_result['length']) ? (int) $fishing_result['length'] : 0;
            $attachData[$fishNameId] = ['count' => $count, 'length' => $length];
        }

        // 釣果のデータを、メモに紐付けて中間テーブルに保存
        if (!empty($attachData)) {
            $memo = Memo::findOrFail($memo_id);
            $memo->fish_names()->attach($attachData);
        }
    }

    /**
     * メモに紐づいた既存のタグを、中間テーブルに保存するメソッド
     * @param $request
     * @param int $memo_id
     * @return void
     */
    public static function attachExistingTags($request, int $memo_id): void
    {
        // 既存タグの選択があれば、メモに紐付けて中間テーブルに保存
        if (!empty($request->tags)) {
            foreach ($request->tags as $tag_number) {
                Memo::findOrFail($memo_id)->tags()->attach($tag_number);
            }
        }
    }

    /**
     * メモに紐づいた既存画像を、中間テーブルに値を保存するメソッド
     * @param $request
     * @param int $memo_id
     * @return void
     */
    public static function attachExistingImages($request, int $memo_id): void
    {
        // 画像の選択があれば、メモに紐付けて中間テーブルに保存
        if (!empty($request->images)) {
            foreach ($request->images as $memo_image) {
                Memo::findOrFail($memo_id)->images()->attach($memo_image);
            }
        }
    }

    /**
     * メモを更新するメソッド。
     * @param $request
     * @return mixed
     */
    public static function updateMemo($request): mixed
    {
        $memo = Memo::availableSelectMemo($request->memoId)->first();
        $memo->title = $request->title;
        $memo->content = $request->content;
        $memo->save();

        return $memo;
    }

    /**
     * 共有されているメモに目印を付けるメソッド。
     * @param $select_memo
     * @return mixed
     */
    public static function checkShared($select_memo): mixed
    {
        // メモが共有されているかどうかを確認
        $is_shared = $select_memo->shareSettings->isNotEmpty();
        // もしメモが共有されている場合、メモに共有中のステータスを追加
        if ($is_shared) {
            $select_memo->status = "共有中";
        }
        return $select_memo;
    }
}
