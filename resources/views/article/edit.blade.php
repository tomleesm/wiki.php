<p>Home</p>
<form action="{{ route('article.update') }}" method="post">
    @csrf
    @method('put')
    <textarea name="articleContent" id="articleContent"></textarea>
    <button type="submit">Save</button>
</form>
