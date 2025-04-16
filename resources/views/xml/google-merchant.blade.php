<?xml version="1.0"?>
<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
<channel>
<title>ArtHall - онлайн-галерея современного искусства</title>
<link>https://arthall.online</link>
<description>Ведущие художники. Интерьерные картины, подарок на любой случай</description>

@foreach ($artworks as $artwork)
<item>
<g:id>{{ $artwork->id }}</g:id>
<g:title>{{ $artwork->title->ru }}</g:title>
<g:description>{{ $artwork->description->ru }}</g:description>
<g:link>{{ url('/artists/'.$artwork->artist->url.'/'.$artwork->id) }}</g:link>
<g:image_link>{{ url($artwork->images[0]['url']) }}</g:image_link>
<g:condition>new</g:condition>
<g:availability>in stock</g:availability>
<g:identifier_exists>no</g:identifier_exists>
<g:price>{{ $artwork->price }} RUB</g:price>
<g:brand>{{ $artwork->artist->fio->ru }}</g:brand>
</item>
@endforeach
</channel>
</rss>
