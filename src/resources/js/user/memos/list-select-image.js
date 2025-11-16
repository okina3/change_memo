'use strict'
// === 画像クリックでの選択／選択解除とサムネイル表示の制御 =====================================
document.addEventListener('DOMContentLoaded', () => {
   // 画像の上限数の設定
   const maxCount = 4;
   // チェックボックスをクラス名で取得
   const checkBoxClassName = 'imageCheckbox';
   // サムネイルコンテナ要素を取得
   const thumbnailArea = document.getElementById('thumbnail-area');
   // ユーザーが選択した画像を保持する配列
   const selectedImages = [];

   // チェックボックスが変更されたときに呼び出されるメソッド
   function handleCheckboxChange() {
      const checkBoxes = document.getElementsByClassName(checkBoxClassName);
      let checked_count = 0;

      // 画像の枚数を、チェックする
      for (let i = 0; i < checkBoxes.length; i++) {
         if (checkBoxes[i].checked) {
            checked_count++;
         }
      }
      // 画像の上限枚数に、達したら選択をキャンセル
      if (checked_count > maxCount) {
         alert('画像は ' + maxCount + ' 枚までにしてください。');
         this.checked = false;
      }
   }

   // チェックボックスの変更イベントにメソッドを紐付ける
   const checkBoxes = document.getElementsByClassName(checkBoxClassName);
   for (let i = 0; i < checkBoxes.length; i++) {
      checkBoxes[i].addEventListener('change', handleCheckboxChange);
   }

   // 画像要素を取得
   const images = document.querySelectorAll('.image');

   // 各画像にクリックイベントリスナーを追加
   images.forEach(image => {
      image.addEventListener('click', function (e) {
         const target = e.currentTarget;
         // クリックされた画像のデータ属性を取得
         const imageId = Number(target.dataset.id);
         const imageFile = target.dataset.file;
         const imagePath = target.dataset.path;

         // 画像がすでに選択されているか確認
         const isSelecte = selectedImages.some(img => img.id === imageId);
         if (!isSelecte && selectedImages.length < maxCount) {
            // 上限数に達していない場合、ユーザーが選択した画像を配列に追加
            selectedImages.push({
               id: imageId,
               file: imageFile,
               path: imagePath
            });
            // 画像に対応するチェックボックスがあればチェック
            const cb = document.querySelector(`input.${checkBoxClassName}[data-id="${imageId}"]`);
            if (cb) cb.checked = true;
            // サムネイルエリアに選択した画像を表示
            const thumbnailImage = document.createElement('img');
            thumbnailImage.src = imagePath + '/' + imageFile;
            thumbnailImage.classList.add('mr-2', 'mb-2', 'border', 'rounded-md', 'p-1', 'w-[22.7%]', 'sm:w-[23%]');
            thumbnailArea.appendChild(thumbnailImage);
         } else if (isSelecte) {
            // すでに選択されている場合、配列から削除
            const imageArrayExistsDelete = selectedImages.findIndex(img => img.id === imageId);
            selectedImages.splice(imageArrayExistsDelete, 1);
            // 画像に対応するチェックボックスがあればチェック解除
            const cb = document.querySelector(`input.${checkBoxClassName}[data-id="${imageId}"]`);
            if (cb) cb.checked = false;
            // 続けて、サムネイルエリアに選択されている画像を削除
            const thumbnailDelete = thumbnailArea.querySelector(`[src="${imagePath}/${imageFile}"]`);
            if (thumbnailDelete) {
               thumbnailArea.removeChild(thumbnailDelete);
            }
         }
      });
   });

   // edit 用: サーバー側から渡された既選択画像データを処理
   if (Array.isArray(window.__PRESELECTED_IMAGES__) && window.__PRESELECTED_IMAGES__.length > 0) {
      window.__PRESELECTED_IMAGES__.forEach(data => {
         // サーバー側から渡されたオブジェクトから画像の識別子を取得
         const imageId = data.id;
         // filename / file のどちらのキー名で来ても対応できるように両方チェック
         const imageFile = data.filename ?? data.file ?? '';
         // ページに描画されている画像要素から path を取得する
         const imageEl = document.querySelector(`.image[data-id="${imageId}"]`);
         const imagePath = imageEl ? imageEl.dataset.path : (data.path ?? '');

         // file または path が不足している場合は処理しない（安全策）
         if (!imageFile || !imagePath) return;

         // 既に選択済みでないことと上限に達していないことを確認してから追加
         if (selectedImages.length < maxCount && !selectedImages.some(img => img.id === imageId)) {
            // 内部配列に格納
            selectedImages.push({ id: imageId, file: imageFile, path: imagePath });
            // サムネイルを作成して表示
            const thumbnailImage = document.createElement('img');
            thumbnailImage.src = imagePath + '/' + imageFile;
            thumbnailImage.classList.add('mr-2', 'mb-2', 'border', 'rounded-md', 'p-1', 'w-[22.7%]', 'sm:w-[23%]');
            thumbnailArea.appendChild(thumbnailImage);
            // 画像に対応するチェックボックスがあればチェックを入れて UI を同期
            const cb = document.querySelector(`input.${checkBoxClassName}[data-id="${imageId}"]`);
            if (cb) cb.checked = true;
         }
      });
   }
});