<p>
    <strong>{{ $operator['name'] }}</strong>
    @if (filled($operator['id'])) (Tax ID / Reg. No.: {{ $operator['id'] }})@endif
    @if (filled($operator['address']))<br>{{ $operator['address'] }}@endif
    @if (filled($operator['email']))<br>Email: <a href="mailto:{{ $operator['email'] }}">{{ $operator['email'] }}</a>@endif
    @if (filled($operator['phone']))<br>Phone: {{ $operator['phone'] }}@endif
</p>
