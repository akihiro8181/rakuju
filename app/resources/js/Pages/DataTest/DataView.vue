<template>
    <div>
        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
            <div class="text-2xl">
                新規ページ作成の流れ
            </div>

            <div class="mt-6 text-gray-500">
                1.Vueファイルを作成する
            </div>
            <div class="mt-6 text-gray-500">
                2.ルーティングを`routes/`に書く
            </div>
            <div class="mt-6 text-gray-500">
                3.`make npm-dev`を走らせる（`make npm-watch`でも可）<br>
            </div>
            <div class="mt-6 text-gray-500">
                ページが白紙→まず開発者ツールのコンソールで確認<br>
                リソースが見つからない等のエラーが出ているときは該当のURLを直接叩いて開発者ツール→リロード長押し→二番目か一番下を選んでキャッシュ削除すれば大体解決する
            </div>
        </div>
    </div>
</template>
