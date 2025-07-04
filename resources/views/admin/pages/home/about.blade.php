@extends('admin.layout.master')

@section('content')
<div class="container mt-4">
  <h4 class="mb-4">Update {{ $aboutContent->slug }} Page</h4>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <form action="{{ route('admin.about.update') }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="form-group">
      <label for="content">Manage Content</label>
      <input type="hidden" name="slug" value="{{ $aboutContent->slug }}">
      <textarea id="tinyEditor" name="content" class="form-control" rows="15">{{ $aboutContent->content }}</textarea>
    </div>

    <button type="submit" class="btn btn-primary mt-3">Update</button>
  </form>
</div>

<script src="https://cdn.tiny.cloud/1/src75j1a560alr91je61jc0voz3dkohxktysr03ib929rtfy/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  tinymce.init({
    selector: '#tinyEditor',
    height: 400,
    menubar: false,
    plugins: 'link image code lists table',
    toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent | link image table | code',
    branding: false
  });
</script>
@endsection

