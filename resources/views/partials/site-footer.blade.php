@php
    $home = route('home');
@endphp
<footer class="footer common section">
  <div class="footer__top common"></div>
  <div class="footer__center common row-padding-top-bottom">
    <div class="container">
      <div class="row gy-5">
        <div class="col-md-8 col-lg-6 fade-anim" data-delay="0.30">
          <div class="footer-info">
            <a href="{{ $home }}" class="footer-logo">
              <img src="{{ asset('assets/images/hero/Logo1.png') }}" alt="logo" />
            </a>
            <p>
                    خبير و مُقيّم الاحجار كريمة بخبرة 20 عامًا في التحليل والتقييم المعتمد.
            </p>
            <div class="contact-info">
              <div class="contact-icon">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="41"
                  height="42"
                  viewBox="0 0 41 42"
                  fill="none"
                >
                  <path
                    d="M28.9607 26.5132C28.2759 25.5779 27.2508 24.9494 26.1066 24.7632C25.5394 24.6709 24.9595 24.6934 24.4013 24.8294C23.843 24.9654 23.3177 25.2121 22.8566 25.5549L20.0649 27.6382C17.1246 24.9164 14.7649 21.6284 13.1274 17.9716L15.9191 15.8882C16.8511 15.1977 17.4741 14.1682 17.6535 13.0221C17.8328 11.8761 17.554 10.7055 16.8774 9.76324L13.5649 5.28407C13.236 4.81086 12.8126 4.41101 12.3213 4.10979C11.83 3.80858 11.2816 3.61254 10.7107 3.53407C10.145 3.43999 9.56613 3.4597 9.00808 3.59206C8.45003 3.72442 7.92399 3.96676 7.46074 4.3049L3.43991 7.22157C2.35909 8.00645 1.51914 9.07777 1.01482 10.3146C0.510495 11.5515 0.361881 12.9047 0.585741 14.2216C1.52833 19.8124 3.75315 25.1088 7.08575 29.6957C10.4184 34.2825 14.7681 38.0351 19.7941 40.6591C20.9792 41.2754 22.3127 41.5493 23.6449 41.4499C24.977 41.3506 26.2551 40.8819 27.3357 40.0966L31.3357 37.1799C32.2506 36.5013 32.8648 35.4926 33.0477 34.3683C33.2305 33.2439 32.9676 32.0926 32.3149 31.1591L28.9607 26.5132ZM29.4607 34.6382L25.4399 37.5549C24.8427 37.9871 24.1381 38.2468 23.4033 38.3056C22.6684 38.3644 21.9316 38.22 21.2732 37.8882C16.6653 35.4964 12.6764 32.0663 9.62129 27.8687C6.56618 23.6711 4.5285 18.8208 3.66907 13.7007C3.54401 12.9581 3.63003 12.1953 3.91732 11.4991C4.20462 10.803 4.68165 10.2015 5.29407 9.76324L9.29407 6.84657C9.42666 6.74917 9.57717 6.6789 9.73697 6.63979C9.89677 6.60068 10.0627 6.5935 10.2253 6.61866C10.3879 6.64382 10.5439 6.70083 10.6844 6.78641C10.8249 6.872 10.9471 6.98448 11.0441 7.1174L14.3774 11.6591C14.5688 11.9287 14.6475 12.2623 14.597 12.589C14.5464 12.9157 14.3705 13.2099 14.1066 13.4091L10.2107 16.1591C9.92567 16.3627 9.71729 16.6562 9.61906 16.9924C9.52083 17.3287 9.53844 17.6882 9.66907 18.0132C11.6624 22.9618 14.8469 27.3414 18.9399 30.7632C19.214 30.9761 19.5512 31.0916 19.8982 31.0916C20.2453 31.0916 20.5825 30.9761 20.8566 30.7632L24.7941 28.0757C24.9267 27.9783 25.0772 27.9081 25.237 27.869C25.3968 27.8298 25.5627 27.8227 25.7253 27.8478C25.8879 27.873 26.0439 27.93 26.1844 28.0156C26.3249 28.1012 26.4471 28.2136 26.5441 28.3466L29.8357 32.9091C29.9324 33.0428 30.0007 33.1949 30.0363 33.356C30.072 33.517 30.0742 33.6837 30.043 33.8457C30.0118 34.0077 29.9477 34.1616 29.8547 34.2979C29.7617 34.4342 29.6418 34.55 29.5024 34.6382H29.4607ZM21.1274 12.9716C21.1577 12.7679 21.2284 12.5724 21.3355 12.3966C21.4426 12.2208 21.5837 12.0681 21.7507 11.9478C21.9177 11.8274 22.1071 11.7416 22.3078 11.6956C22.5085 11.6496 22.7163 11.6443 22.9191 11.6799C23.8909 11.8247 24.8244 12.1606 25.6657 12.6682C26.507 13.1758 27.2393 13.8451 27.8205 14.6373C28.4016 15.4296 28.82 16.3292 29.0515 17.2841C29.2829 18.239 29.3229 19.2303 29.1691 20.2007C29.1102 20.5689 28.9233 20.9046 28.6413 21.1485C28.3593 21.3924 28.0002 21.5289 27.6274 21.5341H27.3774C26.9686 21.4676 26.6029 21.2415 26.3607 20.9056C26.1185 20.5696 26.0196 20.1513 26.0857 19.7424C26.2599 18.6077 25.979 17.4501 25.304 16.5216C24.629 15.593 23.6146 14.9685 22.4816 14.7841C22.2703 14.7618 22.0659 14.696 21.8813 14.591C21.6966 14.4859 21.5357 14.3439 21.4085 14.1737C21.2814 14.0035 21.1908 13.8088 21.1424 13.602C21.094 13.3951 21.0889 13.1805 21.1274 12.9716ZM34.9399 20.9091C34.9344 21.3199 34.7674 21.7119 34.475 22.0005C34.1825 22.289 33.7882 22.4508 33.3774 22.4507C32.9647 22.4399 32.5726 22.2683 32.2847 21.9725C31.9967 21.6768 31.8356 21.2802 31.8357 20.8674C31.8701 18.1986 31.0408 15.5902 29.4714 13.4313C27.902 11.2724 25.6767 9.67884 23.1274 8.88824C22.7591 8.74536 22.4586 8.46826 22.2863 8.11272C22.1141 7.75718 22.0829 7.34959 22.1991 6.972C22.3153 6.5944 22.5702 6.27484 22.9125 6.07762C23.2548 5.8804 23.6591 5.82018 24.0441 5.90907C27.2342 6.90117 30.0184 8.89745 31.9819 11.6005C33.9453 14.3035 34.9828 17.5685 34.9399 20.9091ZM40.7732 21.7424C40.7847 22.1554 40.6342 22.5566 40.354 22.8602C40.0737 23.1638 39.6859 23.3458 39.2732 23.3674C38.8694 23.3677 38.4812 23.2117 38.1899 22.9321C37.8986 22.6524 37.7269 22.2709 37.7107 21.8674C37.5469 17.7367 36.158 13.7482 33.7206 10.4093C31.2832 7.07038 27.9076 4.53209 24.0232 3.1174C23.8258 3.04138 23.6454 2.92705 23.4923 2.78101C23.3393 2.63497 23.2166 2.4601 23.1314 2.26647C23.0462 2.07283 23.0002 1.86426 22.9959 1.65275C22.9916 1.44124 23.0292 1.23098 23.1066 1.03407C23.1802 0.8349 23.2932 0.652584 23.4388 0.497972C23.5844 0.343359 23.7595 0.219609 23.9539 0.134085C24.1483 0.0485606 24.3579 0.00301009 24.5702 0.000144206C24.7826 -0.00272167 24.9933 0.0371553 25.1899 0.117402C29.6308 1.78285 33.4799 4.72464 36.2528 8.57258C39.0257 12.4205 40.5985 17.0027 40.7732 21.7424Z"
                    fill="currentColor"
                  />
                </svg>
              </div>
              <div class="contact">
                <a href="tel:+966538600000" dir="ltr">+966 53 860 0000</a>
                <span class="open-time">اتصل 24/7</span>
              </div>
            </div>
            <div class="social-info">
              <h6 class="social-title">تابعني</h6>
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="5"
                height="22"
                viewBox="0 0 5 22"
                fill="none"
              >
                <line
                  x1="1.375"
                  y1="2.774e-08"
                  x2="1.375"
                  y2="22"
                  stroke="white"
                  stroke-opacity="0.3"
                  stroke-width="1.5"
                />
                <line
                  x1="4.125"
                  y1="4"
                  x2="4.125"
                  y2="18"
                  stroke="white"
                  stroke-opacity="0.3"
                />
              </svg>
              <ul>
                <li>
                  <a href="https://x.com/s_almas3ri" target="_blank" rel="noopener noreferrer">
                    <i class="fa-brands fa-x-twitter"></i>
                  </a>
                </li>
                <li>
                  <a href="https://www.youtube.com/@sultan_jewelry" target="_blank" rel="noopener noreferrer">
                   <i class="fa-brands fa-youtube"></i>
                  </a>
                </li>
                 <li>
                  <a href="https://www.tiktok.com/@sultan.jewelry" target="_blank" rel="noopener noreferrer">
                   <i class="fa-brands fa-tiktok"></i>
                  </a>
                </li>
                 <li>
                  <a href="https://www.snapchat.com/@sultan.jewelry" target="_blank" rel="noopener noreferrer">
                   <i class="fa-brands fa-snapchat"></i>
                  </a>
                </li>
                 <li>
                  <a href="https://www.threads.com/@sultan.jewelry1" target="_blank" rel="noopener noreferrer">
                   <i class="fa-brands fa-threads"></i>
                  </a>
                </li>
                <li>
                  <a href="https://www.instagram.com/sultan.jewelry1" target="_blank" rel="noopener noreferrer">
                    <i class="fa-brands fa-instagram"></i>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-3 fade-anim" data-delay="0.60">
          <div class="footer-widgets">
            <h6 class="widgets-title text-white-color text-capitalize">
              instagram
            </h6>
            <div class="instagram">
              <a href="https://www.instagram.com/reel/DNlgSnWsd4i/?utm_source=ig_web_copy_link&igsh=MzRlODBiNWFlZA==" target="_blank" rel="noopener noreferrer">
                <img
                  src="{{ asset('assets/images/hero/1.jpeg') }}"
                  alt="instagram-1"
                  class="w-200"
                />
              </a>
              <a href="https://www.instagram.com/reel/DNc43NhsUUN/?utm_source=ig_web_copy_link&igsh=MzRlODBiNWFlZA==" target="_blank" rel="noopener noreferrer">
                <img
                  src="{{ asset('assets/images/hero/4.jpeg') }}"
                  alt="instagram-2"
                  class="w-200"
                />
              </a>
              <a href="https://www.instagram.com/reel/DNV4NyPM0h8/?utm_source=ig_web_copy_link&igsh=MzRlODBiNWFlZA==" target="_blank" rel="noopener noreferrer">
                <img
                  src="{{ asset('assets/images/hero/in2.png') }}"
                  alt="instagram-3"
                  class="w-200"
                />
              </a>
              <a href="https://www.instagram.com/reel/DLyOU6hoc7L/?utm_source=ig_web_copy_link&igsh=MzRlODBiNWFlZA==" target="_blank" rel="noopener noreferrer">
                <img
                  src="{{ asset('assets/images/hero/in3.png') }}"
                  alt="instagram-4"
                  class="w-200"
                />
              </a>
              <a href="https://www.instagram.com/reel/DJouFaOsB6q/?utm_source=ig_web_copy_link&igsh=MzRlODBiNWFlZA==" target="_blank" rel="noopener noreferrer">
                <img
                  src="{{ asset('assets/images/hero/in4.png') }}"
                  alt="instagram-5"
                  class="w-200"
                />
              </a>
              <a href="https://www.instagram.com/reel/Cs3P3PMujSR/?utm_source=ig_web_copy_link&igsh=MzRlODBiNWFlZA==" target="_blank" rel="noopener noreferrer">
                <img
                  src="{{ asset('assets/images/hero/in5.png') }}"
                  alt="instagram-6"
                  class="w-200"
                />
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>
