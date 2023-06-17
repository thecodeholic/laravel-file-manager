<p>Hello <b>{{ $user->name }}</b>,</p>

<p>User <b>{{ $author->name }}</b> shared the following files to you.</p>
<hr>
@foreach($files as $file)
    <p>{{$file->is_folder ? 'Folder' : 'File'}} - {{$file->name}}</p>
@endforeach
