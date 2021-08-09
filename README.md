# Name
DataTables風GUIをVue.jsで作成してみました

# DEMO
https://koba-chi.com/lara_vue_datatables/member

# Features
jQueryにDataTablesというプラグインがあります。
とても便利なプラグインで、DBにアクセスせずに、ローカルで、検索・ページ移動・ソートができるテーブルです。
そのDataTablesプラグインを使用せずに、その機能をVue.jsとBootstrapVueで構築してみました。

サンプルとして、簡単な社員データを実装してみました。
・テーブルの検索、ページネーション、ソート機能は、BootstrapVueのTableを使用しました。
（https://bootstrap-vue.org/docs/components/table）
・データの新規追加・編集・削除は、axiosを使用し、Laravelのapiを叩いています。
・サンプルのデータは、Laravelのダミーデータ作成（Seeder等）を使用し、生成しました。

# Requirement
Laravel 6.x
MariaDB10.5 (MySQL 5.7)

