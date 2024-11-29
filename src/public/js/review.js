/* 口コミの文字数表示処理 */
// 最大文字数
const maxLength = 400;
// 要素の取得
const textarea = document.getElementById('comment');
const charCount = document.getElementById('char_count');
// イベントリスナーを設定
textarea.addEventListener('input', () => {
    // 入力された文字数を取得
    const currentLength = textarea.value.length;
    // 文字数を表示
    charCount.textContent = currentLength;
});

/* お気に入りボタン処理 */
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('.like-button').on('click', function () {
        const shop_id = $(this).data('id');
        if (this.classList.contains('liked')) {
            this.classList.remove('liked');
            this.classList.replace('las', 'lar');
        } else {
            this.classList.add('liked');
            this.classList.replace('lar', 'las');
        }
        $.ajax({
            url: '/favorite',
            type: 'POST',
            data: {
                shop_id: shop_id
            },
            dataType: "json",
        }).done(function (res) {
            console.log(res);
        }).fail(function () {
            alert('通信の失敗をしました');
        });
    });
});

/* 画像アプロード処理 */
document.addEventListener('DOMContentLoaded', () => {
    const dropzone = document.getElementById('dropzone');
    const input = document.getElementById('image_upload');
    const preview = document.getElementById('preview');
    const uploadedFiles = []; // すべてのファイルを管理する配列

    // ドラッグイベント
    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault(); //ブラウザによるファイルの処理を中断
        dropzone.style.backgroundColor = '#2384cf';
        // 内部の p タグをすべて取得してフォント色を変更
        const pTags = dropzone.querySelectorAll('p');
        pTags.forEach((p) => {
            p.style.color = '#fff';
        });
    });

    dropzone.addEventListener('dragleave', () => {
        dropzone.style.backgroundColor = '';
        // 内部の p タグをすべて取得してフォント色を元に戻す
        const pTags = dropzone.querySelectorAll('p');
        pTags.forEach((p) => {
            p.style.color = ''; // デフォルトスタイルに戻す
        });
    });

    dropzone.addEventListener('drop', (e) => {
        e.preventDefault(); //ブラウザによるファイルの処理を中断
        dropzone.style.backgroundColor = '';
        // 内部の p タグをすべて取得してフォント色を元に戻す
        const pTags = dropzone.querySelectorAll('p');
        pTags.forEach((p) => {
            p.style.color = ''; // デフォルトスタイルに戻す
        });
        const files = Array.from(e.dataTransfer.files);
        // 配列にファイルを追加
        addFiles(files);
        handleFiles(files);
    });

    // クリックでファイル選択
    input.addEventListener('change', (e) => {
        const files = Array.from(e.target.files);

        // 配列にファイルを追加
        addFiles(files);
        handleFiles(files);
    });

    // 配列にファイルを追加（重複を避ける）
    function addFiles(files) {
        files.forEach((file) => {
            if (!uploadedFiles.some((uploadedFile) => uploadedFile.name === file.name)) {
                uploadedFiles.push(file);
            }
        });
        // 配列内容を `<input>` に反映
        updateInputFiles();
    }

    // `<input>` の `files` を更新
    function updateInputFiles() {
        const dataTransfer = new DataTransfer();

        uploadedFiles.forEach((file) => {
            dataTransfer.items.add(file);
        });

        input.files = dataTransfer.files; // `<input>` に反映
    }

    // ファイルを処理してプレビューに表示
    function handleFiles(files) {
        for (const file of files) {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        }
    }
});
