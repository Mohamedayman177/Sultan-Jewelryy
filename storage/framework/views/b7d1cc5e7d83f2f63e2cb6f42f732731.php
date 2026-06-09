<?php
    $home = route('home');
?>
<div id="navbars" class="header-sticky navbars">
  <div class="container header-container">
    <div class="row justify-content-between align-items-center">
      <div
        class="col-xl-9 col-lg-auto d-none d-lg-flex justify-center align-items-center"
      >
        <button class="vs-menu-toggle d-inline-block d-lg-none" type="button">
          <i class="fal fa-bars"></i>
        </button>
        <div class="logo d-none d-lg-block">
          <a href="<?php echo e($home); ?>"
            ><img src="<?php echo e(asset('assets/images/hero/Logo2.png')); ?>" alt="Sultan" class="logo"
          /></a>
        </div>
        <nav class="main-menu d-none d-lg-block">
          <ul>
            <li>
              <a href="<?php echo e($home); ?>#الرئيسية"><span data-lang="ar"> الرئيسية </span>
                <span data-lang="en">Home</span>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="43"
                  height="10"
                  viewBox="0 0 43 10"
                  fill="none"
                >
                  <path
                    d="M1.625 7.99988C7.71196 3.99988 24.2337 -1.60013 41.625 7.99988"
                    stroke="currentColor"
                    stroke-width="4"
                  />
                </svg>
              </a>
            </li>

            <li>
              <a href="<?php echo e($home); ?>#نبذه عننا">
               <span data-lang="ar"> نبذه عننا </span>
               <span data-lang="en">About Me</span>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="43"
                  height="10"
                  viewBox="0 0 43 10"
                  fill="none"
                >
                  <path
                    d="M1.625 7.99988C7.71196 3.99988 24.2337 -1.60013 41.625 7.99988"
                    stroke="currentColor"
                    stroke-width="4"
                  />
                </svg>
              </a>
            </li>
             <li>
              <a href="<?php echo e($home); ?>#جهات الاعتماد"><span data-lang="ar"> جهات الاعتماد </span>
                <span data-lang="en">Accreditations</span>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="43"
                  height="10"
                  viewBox="0 0 43 10"
                  fill="none"
                >
                  <path
                    d="M1.625 7.99988C7.71196 3.99988 24.2337 -1.60013 41.625 7.99988"
                    stroke="currentColor"
                    stroke-width="4"
                  />
                </svg>
              </a>
            </li>
            <li>
              <a href="<?php echo e($home); ?>#الخدمات"><span data-lang="ar"> الخدمات </span>
                <span data-lang="en">Services</span>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="43"
                  height="10"
                  viewBox="0 0 43 10"
                  fill="none"
                >
                  <path
                    d="M1.625 7.99988C7.71196 3.99988 24.2337 -1.60013 41.625 7.99988"
                    stroke="currentColor"
                    stroke-width="4"
                  />
                </svg>
              </a>

            </li>
<li class="menu-item lang-switch">
  <a href="#" class="lang-btn">
    <span data-lang="ar">English</span>
    <span data-lang="en">العربية</span>

    <svg
      xmlns="http://www.w3.org/2000/svg"
      width="43"
      height="10"
      viewBox="0 0 43 10"
      fill="none"
    >
      <path
        d="M1.625 7.99988C7.71196 3.99988 24.2337 -1.60013 41.625 7.99988"
        stroke="currentColor"
        stroke-width="4"
      />
    </svg>
  </a>
</li>



            <li>
            </li>
          </ul>
        </nav>
      </div>
      <div class="col-xl-3 col-lg-auto">
        <div class="moblide-header">
          <button class="vs-menu-toggle d-inline-block d-lg-none" type="button">
            <i class="fal fa-bars"></i>
          </button>
          <div class="logo d-flex align-items-center d-lg-none">
            <a href="<?php echo e($home); ?>"
              ><img src="<?php echo e(asset('assets/images/hero/Logo2.png')); ?>" alt="Sultan" class="logo"
            /></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php /**PATH C:\xampp\htdocs\Sultan-Jewelryy\resources\views/partials/site-navbar.blade.php ENDPATH**/ ?>