{!! '<'.'?xml version="1.0" encoding="UTF-8"?'.'>' !!}
<rss version="2.0">
    <channel>
        <title>Ruang — Stories &amp; Ideas</title>
        <link>{{ url('/') }}</link>
        <description>Independent editorial platform for developers and modern thinkers.</description>
        <language>en</language>
        <lastBuildDate>{{ now()->toRfc2822String() }}</lastBuildDate>
        @foreach ($artikels as $artikel)
            <item>
                <title>{{ $artikel->judul }}</title>
                <link>{{ route('artikel.show', $artikel->id) }}</link>
                <guid isPermaLink="true">{{ route('artikel.show', $artikel->id) }}</guid>
                <pubDate>{{ $artikel->created_at ? $artikel->created_at->toRfc2822String() : now()->toRfc2822String() }}</pubDate>
                <description><![CDATA[{{ Str::limit(strip_tags($artikel->konten), 300) }}]]></description>
                @if ($artikel->user)
                    <author>{{ $artikel->user->email }} ({{ $artikel->user->name }})</author>
                @endif
                @if ($artikel->category)
                    <category>{{ $artikel->category->nama }}</category>
                @endif
            </item>
        @endforeach
    </channel>
</rss>
