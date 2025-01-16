@extends('user.index')
<link href="{{ asset('css/instructions/introduce.css') }}" rel="stylesheet" type="text/css">
@section('content')
    <div class="p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 ">
            <div class="mt-48">
                <h1 class="text-3xl font-bold mb-2">The Whiplash of a Major Fashion World Shake-up</h1>
                <p class="text-gray-600 mb-4">
                    Brooks shocked the industry by announcing that Brad Simmons, a top designer at Jannings, Brooks' rival,
                    will be joining the company by naming his replacement, effective immediately.
                </p>
            </div>
            <div>
                <video
                    src="{{ asset('video/Tainhanh.net_YouTube_saigon-sunday-fashion-film-BMPCC-6K-Cont_Media_yCBNYpMgDmE_002_720p.mp4') }}"
                    autoplay muted loop width="700px" style="margin-top: 10px;"></video>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            @foreach ([['link' => 'https://www.glitzandglambytiff.com/blog/staging-a-fashion-show-heres-how-to-ensure-its-a-success', 'img' => 'h2.png', 'title' => 'How to Stage a Viral Fashion Show', 'author' => 'By Jane Doe'], ['link' => 'https://northshoreexchange.org/pages/nse-trend-report', 'img' => 'h3.png', 'title' => 'The Best Trend of Fashion Month. And The Worst.', 'author' => 'By John Smith'], ['link' => 'https://www.nytimes.com/2024/10/02/style/walz-vance-debate-style.html', 'img' => 'h4.png', 'title' => 'The Politics of a Boring Suit', 'author' => 'By Alex Johnson']] as $article)
                <div class="border p-2">
                    <a href="{{ $article['link'] }}" target="_blank" rel="noopener noreferrer">
                        <img src="{{ asset('image/h1.png') }}" class="w-full h-auto mb-2" />
                    </a>
                    <h2 class="text-xl font-bold">{{ $article['title'] }}</h2>
                    <p class="text-gray-600">{{ $article['author'] }}</p>
                </div>
            @endforeach
        </div>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            @foreach ([['link' => 'https://www.youtube.com/watch?v=QHMuss1vq7Q', 'img' => 'h5.png', 'title' => 'A Talk with the Talents to Watch'], ['link' => 'https://www.youtube.com/watch?v=qJ-MuvwAkSs', 'img' => 'h6.png', 'title' => 'The New Wave of Fashion Designers'], ['link' => 'https://wwd.com/eye/parties/john-legend-usher-chris-pine-colman-domingo-attend-ralph-lauren-party-milan-fashion-week-1236443486/', 'img' => 'h7.png', 'title' => 'Who Invited John to Milan Fashion Week?'], ['link' => 'https://www.vogue.com/article/vogue-wardrobe-essentials-guide', 'img' => 'h8.png', 'title' => 'What to Wear Next'], ['link' => 'https://northshoreexchange.org/blogs/news/trends-in-fashion', 'img' => 'h9.png', 'title' => 'Fashion Forward: Spring and Summer Trends']] as $video)
                <div class="border p-2">
                    <a href="{{ $video['link'] }}" target="_blank" rel="noopener noreferrer">
                        <img src="{{ asset('image/' . $video['img']) }}" alt="{{ $video['title'] }}"
                            class="w-full h-auto mb-2" />
                    </a>
                    <h3 class="text-lg font-bold">{{ $video['title'] }}</h3>
                </div>
            @endforeach
        </div>
        <hr class="mt-20" />
        <div class="flex">
            <video src="{{ asset('video/yt1s.com - Britney Manson  FΛSHION Single 2023.mp4') }}" autoplay muted loop
                width="1000px" style="margin-top: 10px;"></video>
        </div>
    </div>
@endsection
