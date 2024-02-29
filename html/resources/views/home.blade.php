@extends('layout')

@section('content')

<main class="main">
    <div class="container">
        <section class="section">
            <table>
                <thead>
                    <tr>
                        <th>Article name</th>
                        <th>Category</th>
                        <th>Published On</th>
                        <th>Views</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($articles as $article)
                        <tr>
                            <td>{{ $article['title'] }}</td>
                            <td>{{ $article['category'] }}</td>
                            <td>{{ $article['publishedOn'] }}</td>
                            <td>{{ $article['views'] }}</td>
                            <td><a href={{ route('edit', $article['filename']) }}>Edit</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </div>
</main>
    
@endsection