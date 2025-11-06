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
     * メモに紐づいた魚名を、中間テーブルに保存するメソッド
     * @param $request
     * @param int $memo_id
     * @return void
     */
    public static function attachExistingFishNames($request, int $memo_id): void
    {
        // 釣果のデータ配列を取得。
        $entries = $request->input('fishing_results', []);
        if (empty($entries) || !is_array($entries)) {
            return;
        }

        $memo = Memo::findOrFail($memo_id);
        $attachData = [];
        foreach ($entries as $entry) {
            // 選択された魚名のid
            $fishNameId = $entry['fish_name'] ?? null;
            if (empty($fishNameId)) {
                continue;
            }
            // 匹数
            $count = isset($entry['count']) && $entry['count'] !== '' ? (int) $entry['count'] : 0;
            // 魚の長さ(cm)
            $length = isset($entry['length']) && $entry['length'] !== '' ? (int) $entry['length'] : 0;
            // syncWithoutDetaching を使って既存の関係は残しつつ、ピボットデータを追加/更新する
            $attachData[$fishNameId] = ['count' => $count, 'length' => $length];
        }

        if (!empty($attachData)) {
            $memo->fish_names()->syncWithoutDetaching($attachData);
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
