<p>◯◯様</p>
<p>下記予約が完了しましたのでご連絡いたします。</p>
<p>どうぞお待ちしております。</p><br>
<p>【予約内容】</p>
<div style="display: grid; grid-template-columns: auto 1fr; gap: 10px;">
    <div>サービス：</div>
    <div>{{ $reservedServiceName }}</div>
    <div>予約日時：</div>
    <div>{{ \Carbon\Carbon::parse($validatedData->start_time)->isoFormat('YYYY年MM月DD日 HH時mm分') }}〜</div>
    <div>所要時間：</div>
    <div>{{ $validatedData->duration }}分</div>
    <div>料金：</div>
    <div>{{ $validatedData->price }}円</div>
</div>
