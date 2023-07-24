<div class="mb-3">
    <label>Title</label>
    <input type="text" name="title" class="w-100 form-control" value="{{ old('title', $post->title ?? null) }}">
</div>
<div class="mb-3">
    <label>Content</label>
    <input type="text" class="w-100 form-control" name="content" value="{{ old('content', $post->content ?? null) }}">
</div>

@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>
                    {{ $error }}
                </li>
            @endforeach
        </ul>
    </div>
@endif
