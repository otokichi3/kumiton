<?php

$res_info = <<< EOL


■オーパス・スポーツ施設情報システムで、以下の申し込みを受け付けました。


利用者番号 ： 27041850
許可番号 ： 2019-050501-00

※打合せが必要な施設です。
「打合せを行う際の諸注意」を確認してください。

------------------------------------------------------------
[1]明治スポーツプラザ浪速スポーツセンター
　第１体育場１／２面
　2019年8月11日(日)
　18:00 - 21:00(1面)


------------------------------------------------------------
ジャンル ： バドミントン
利用人数 ： 24人

合計金額 ： 3,000円
引落予定日 ： 2019年9月20日(金)

------------------------------------------------------------
※打合せを行う際の諸注意
打合せ期限までに当該施設へ打合せにお越しください。
なお、打合せ期限日が休館日の場合は、その前日までにお越しください。
打合せ期限日：2019年6月23日(日)
打合せ期限日までに打合せを行わない場合、申込が取消されます。
------------------------------------------------------------


----------------------
(お問合せ先)
大阪市スポーツ総合情報センター
06-6691-2711

※送信専用アドレスのため、返信はご遠慮ください。





EOL;


$res_info = str_replace(["\r\n", "\r", "\n"], "\n", $res_info);
$info = explode("\n", $res_info);
dump($info);