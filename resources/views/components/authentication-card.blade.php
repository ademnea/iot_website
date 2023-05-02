<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
  @foreach ($contents as $content)
    <div>
       <img src="../images/logo/{{ $content->logo }}" width="300px" height="800px" alt="Logo" style="border-radius:20px;">
    </div>
     @endforeach

    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        {{ $slot }}
    </div>
</div>
