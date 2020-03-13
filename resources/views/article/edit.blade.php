<h3>{{ $title }}</h3>
<form action="{{ route('article.update', ['title' => $title]) }}" method="post">
    @csrf
    @method('put')

    <input type="hidden" name="article[title]" value="{{ $title }}">
    <textarea name="article[content]" value="{{ old('article.content') }}"></textarea>

    <button type="submit">Save</button>
</form>
