@extends('layout')

@section('content')

<main class="main">
    <div class="container">
        <section class="section">
            <form action={{ route('update', $data['filename']) }} method="POST">
                @csrf
                @method('PATCH')
                <label for="content">Edit Article - {{ $data['title'] }}</label>
                <textarea 
                    name="content" 
                    id="content" 
                    style="width:100%;height:450px">{{ $data['content'] }}</textarea>

                <div class="btns">
                    <a href={{ url()->previous() }}>Cancel</a>
                    <button type="submit">Save changes</button>
                </div>
            </form>
        </section>
    </div>
</main>
    
@endsection