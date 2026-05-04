@php
    $home = route('home');
@endphp
<div class="vs-menu-wrapper">
  <div class="vs-menu-area text-center">
    <div class="mobile-logo">
      <a href="{{ $home }}"
        ><img src="{{ asset('assets/images/hero/Logo2.png') }}" alt="Sultan" class="logo"
      /></a>
      <button class="vs-menu-toggle" type="button"><i class="fal fa-times"></i></button>
    </div>
    <div class="vs-mobile-menu">
      <ul>
        <li class="menu-item-has-children">
          <a href="{{ $home }}#الرئيسية"><span data-lang="ar"> الرئيسية </span>
                <span data-lang="en">Home</span>

        </li>
        <li>
        <a href="{{ $home }}#نبذه عننا">
               <span data-lang="ar"> نبذه عننا </span>
               <span data-lang="en">About Me</span>
        </li>
        <li class="menu-item-has-children mega-menu-wrap">
          <a href="{{ $home }}#جهات الاعتماد"><span data-lang="ar"> جهات الاعتماد </span>
                <span data-lang="en">Accreditations</span>

        </li>
        <li>
        <a href="{{ $home }}#الخدمات"><span data-lang="ar"> الخدمات </span>
                <span data-lang="en">Services</span>
</a>

        </li>
<li class="language-switcher">
  <button id="lang-toggle" type="button" class="lang-btn">
    <span data-lang="ar">English</span>
    <span data-lang="en">العربية</span>
  </button>
</li>

      </ul>
    </div>
  </div>
</div>
