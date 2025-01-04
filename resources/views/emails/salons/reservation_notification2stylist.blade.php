<p>{{ $stylistName }}さん</p>
<p>新規予約が入りましたのでご連絡いたしました。</p>
<p>よろしくお願いいたします。</p>
<br>
<p>【予約内容】</p>
<div style="display: grid; grid-template-columns: auto 1fr; gap: 10px;">
    <div>ご予約者名：</div>
    <div>{{ $reservedUserName }}</div>
    <div>サービス：</div>
    <div>{{ $reservedServiceName }}</div>
    <div>予約日時：</div>
    <div>{{ \Carbon\Carbon::parse($validatedData->start_time)->isoFormat('YYYY年MM月DD日 HH時mm分') }}〜</div>
    <div>所要時間：</div>
    <div>{{ $validatedData->duration }}分</div>
</div>
