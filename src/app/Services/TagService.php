<?php

namespace App\Services;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class TagService
{
    /**
     * タグをDBに保存するメソッド。
     * @param string $new_tag
     * @return Tag
     */
    public static function storeTag(string $new_tag)
    {
        return Tag::firstOrCreate([
            'name' => $new_tag,
            'user_id' => Auth::id(),
        ]);
    }

    /**
     * メモ画面の新規タグの保存・更新するメソッド。
     * @param $request_new_tag
     * @param int $memo_id
     * @return void
     */
    public static function storeNewTag($request_new_tag, int $memo_id): void
    {
        if (!empty($request_new_tag)) {
            // タグを保存または取得
            $tag = self::storeTag($request_new_tag);
            // メモとタグの中間テーブルに値を保存
            Tag::findOrFail($tag->id)->memos()->attach($memo_id);
        }
    }

    /**
     * 選択したメモに紐づいた、タグのIDを、配列で取得するメソッド。
     * @param Collection $select_memo_tags
     * @return array
     */
    public static function getMemoTagsId(Collection $select_memo_tags): array
    {
        return $select_memo_tags->pluck('id')->toArray();
    }

    /**
     * 選択したメモに紐づいた、タグのNameを、配列で取得するメソッド。
     * @param Collection $select_memo_tags
     * @return array
     */
    public static function getMemoTagsName(Collection $select_memo_tags): array
    {
        return $select_memo_tags->pluck('name')->toArray();
    }
}
