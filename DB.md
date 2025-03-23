1. 基本的な身体成長データ
   身長：定期的な身長の記録
   体重：成長に伴う体重変化
   頭囲：特に乳児期に重要
   測定日：いつ測定したか
2. 発達マイルストーン
   首すわりの日付
   お座りができた日
   ハイハイを始めた日
   つかまり立ちの日
   歩き始めた日
   初めての言葉
   その他の発達イベント
3. 健康記録
   予防接種の履歴
   健診の記録
   病気の履歴
   通院記録
4. 食事関連
   離乳食の開始と進行
   新しい食品の導入
   食事量や反応
   食べ物の好き嫌い
5. 日常の記録
   睡眠パターン
   おむつ/トイレトレーニング
   日々の活動や遊び
6. 思い出記録
   写真や動画
   特別なイベント（初めての〇〇）
   かわいいエピソード

gorwth_records テーブル
id (PK)
child_id (FK)
record_date - 記録日
height - 身長 (cm)
weight - 体重 (kg)
head_circumference - 頭囲 (cm)
memo - メモ
created_at
updated_at

milestones テーブル
id (PK)
child_id (FK)
milestone_type - マイルストーンの種類（首すわり、歩行など）
achieved_date - 達成日
description - 詳細説明
photo_id - 関連写真（任意）
created_at
updated_at

health_record テーブル
id (PK)
child_id (FK)
record_type - 記録タイプ（予防接種、健診、病気など）
record_date - 記録日
description - 詳細
doctor_name - 医師名（任意）
facility_name - 医療機関名（任意）
notes - 備考
created_at
updated_at

food_record テーブル
id (PK)
child_id (FK)
record_date - 記録日
meal_type - 食事タイプ（朝食、昼食、夕食、おやつ）
food_items - 食べた物
amount - 量
reaction - 反応（おいしい、残した、アレルギーなど）
notes - 備考
created_at
updated_at

memories テーブル
id (PK)
child_id (FK)
title - タイトル
memory_date - 日付
description - 詳細
is_favorite - お気に入りフラグ
created_at
updated_at

photos テーブル
id (PK)
record_id - 関連レコード ID
record_type - 関連テーブル名（ポリモーフィック関連）
file_path - ファイルパス
caption - キャプション
created_at
updated_at

考慮すべきポイント
データの関連付け: 各記録を子供に正しく関連付けることが重要です
時系列表示: カレンダー表示や成長曲線のグラフなど、時系列での表示方法を考慮する
検索とフィルタリング: 日付やタイプで記録を検索・フィルタリングできるようにする
写真/メディア管理: 大量の写真を効率的に保存・管理する方法
WHO 成長基準との比較: 身長・体重を世界保健機関（WHO）の標準値と比較できるとよい
