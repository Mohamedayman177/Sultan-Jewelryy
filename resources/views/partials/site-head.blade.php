<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<meta
    name="description"
    content="@yield('meta_description', 'Sultan هو قالب بورتفوليو شخصي حديث واحترافي، مناسب لعرض الأعمال والسيرة الذاتية والمشاريع الشخصية بتصميم أنيق وحركات سلسة.')"
/>
<meta
    name="keywords"
    content="@yield('meta_keywords', 'بورتفوليو شخصي, قالب بورتفوليو, سيرة ذاتية, قالب CV, موقع شخصي, قالب HTML عربي, أعمال, تصميم حديث')"
/>

<title>@yield('title', 'سلطان المسعري - خبير الاحجار الكريمة و مدير تنفيذي في صناعة المجوهرات')</title>

<link rel="icon" href="{{ asset('assets/images/hero/fav.png') }}" type="image/x-icon" />

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('assets/css/plugins.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
<style>
body.rtl {
  direction: rtl;
  text-align: right;
}

body.ltr {
  direction: ltr;
  text-align: left;
  line-height: 1.6;
}

body.ltr [data-lang="en"] {
  display: block;
  margin-bottom: 1em;
}

body.rtl [data-lang="ar"] {
  display: block;
}
</style>
