
<div class="mb-3">
    <label class="form-label">Title</label>
    <input type="text" name="title" class="form-control " value="{{ old('title', $news->title ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="desc" class="form-control" required>{{ old('desc', $news->desc ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label">Image</label>
    <input type="file" name="img_path" class="form-control">
    @if(!empty($news->img_path))
        <img src="{{ asset('storage/' . $news->img_path) }}" width="150" class="mt-2">
    @endif
</div>

<div class="mb-3">
    <label class="form-label">University</label>
    <select name="university_id" class="form-control" required>
        <option value="">-- Select University --</option>
        @foreach($universities as $university)
            <option value="{{ $university->id }}" {{ old('university_id', $news->university_id ?? '') == $university->id ? 'selected' : '' }}>
                {{ $university->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Publish Date</label>
    <input type="date" 
           name="publish_date" 
           class="form-control" 
           value="{{ old('publish_date', isset($news) && $news->publish_date ? $news->publish_date->format('Y-m-d') : '') }}">
</div>


<div class="mb-3">
    <label class="form-label">Time</label>
    <input type="time" name="time" class="form-control" value="{{ old('time', $news->time ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">Location</label>
    <input type="text" name="location" class="form-control" value="{{ old('location', $news->location ?? '') }}">
</div>

<div class=" mb-3">
    <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $news->is_active ?? false) ? 'checked' : '' }}>
    <label class="">Active</label>
</div>
<div class="mb-3">
    <label class="form-label">Additional Images</label>
    <input type="file" name="news_images[]" class="form-control" multiple>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif